<?php
session_start();
require_once 'C:\xampp\htdocs\gastrocare\db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$formData = $input['formData'];
$nextPage = $input['nextPage'];

if (!$formData || !$nextPage) {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit;
}

// Store form data in session
$_SESSION['diagnosis_data'] = isset($_SESSION['diagnosis_data']) ? array_merge($_SESSION['diagnosis_data'], $formData) : $formData;

// If final step (going to result.php), calculate score and save to database
if ($nextPage === 'result.php') {
    $data = $_SESSION['diagnosis_data'];
    $score = 0;
    $scores = [];

    // Fetch questions from database
    // Fetch questions from database
    $questions = $conn->query("SELECT * FROM questions ORDER BY session ASC, id ASC");
    while ($row = $questions->fetch_assoc()) {
        $field_name = $row['field_name'];
        $question_text = $row['question_text'];
        $input_type = $row['input_type'];
        $points = json_decode($row['points'], true) ?? [];
        $options = json_decode($row['options'], true) ?? [];

        // Check if the user provided an answer for this question
        if (isset($data[$field_name])) {
            $answer = $data[$field_name];

            // Handle different input types
            if ($input_type === 'checkbox') {
                // For checkboxes, answer is an array
                $answer = is_array($answer) ? $answer : (array)$answer;
                $answer_points = 0;
                $answer_text = count($answer) > 0 ? implode(', ', $answer) : 'Tidak';
                foreach ($answer as $value) {
                    if (isset($points[$value])) {
                        $answer_points += (int)$points[$value];
                    }
                }
                $score += $answer_points;
                $scores[$question_text] = ['answer' => $answer_text, 'points' => $answer_points];
            } elseif ($input_type === 'number' && $field_name === 'weight' && isset($data['height'])) {
                // Special case for BMI calculation
                $heightInMeters = $data['height'] / 100;
                $bmi = $data['weight'] / ($heightInMeters * $heightInMeters);
                $bmiPoints = 0;
                $bmiCategory = '';
                if ($bmi < 18.5) {
                    $bmiPoints = 3;
                    $bmiCategory = 'Kurus';
                } else if ($bmi >= 18.5 && $bmi <= 24.9) {
                    $bmiPoints = 0;
                    $bmiCategory = 'Normal';
                } else if ($bmi >= 25 && $bmi <= 29.9) {
                    $bmiPoints = 4;
                    $bmiCategory = 'Kelebihan Berat Badan';
                } else {
                    $bmiPoints = 6;
                    $bmiCategory = 'Obesitas';
                }
                $score += $bmiPoints;
                $scores['Berapa berat badan anda? (kg)'] = ['answer' => "{$data['weight']} kg", 'points' => $bmiPoints];
                $scores['Berapa tinggi badan anda? (cm)'] = ['answer' => "{$data['height']} cm (BMI: " . number_format($bmi, 1) . " - {$bmiCategory})", 'points' => 0];
            } elseif ($input_type === 'number') {
                // For other number inputs, treat as direct points if specified in points JSON
                $answer_points = isset($points[$answer]) ? (int)$points[$answer] : 0;
                $score += $answer_points;
                $scores[$question_text] = ['answer' => $answer, 'points' => $answer_points];
            } else {
                // For select or button inputs
                $answer_points = isset($points[$answer]) ? (int)$points[$answer] : 0;
                $answer_text = isset($options[array_search($answer, array_keys($points))]) ? $options[array_search($answer, array_keys($points))] : $answer;
                $score += $answer_points;
                $scores[$question_text] = ['answer' => $answer_text, 'points' => $answer_points];
            }
        } else {
            // If no answer provided, record as 0 points
            $scores[$question_text] = ['answer' => 'Tidak dijawab', 'points' => 0];
        }
    }

    // Cap the score at 40
    $score = min($score, 40);

    // Determine diagnosis
    if ($score <= 8) {
        $diagnosis = [
            'title' => 'Kondisi Lambung Aman',
            'text' => 'Berdasarkan jawaban Anda, kondisi lambung Anda tampaknya sangat baik. Pertahankan pola hidup sehat, konsumsi makanan bergizi, dan terus hindari kebiasaan yang dapat memengaruhi kesehatan lambung seperti merokok atau konsumsi alkohol berlebihan.'
        ];
    } else if ($score <= 16) {
        $diagnosis = [
            'title' => 'Kondisi Lambung Aman Menengah',
            'text' => 'Berdasarkan jawaban Anda, kondisi lambung Anda cukup baik, namun perlu sedikit perhatian. Lanjutkan kebiasaan sehat, perhatikan pola makan, dan hindari faktor risiko seperti stres berlebih atau makanan yang memicu gangguan lambung.'
        ];
    } else if ($score <= 24) {
        $diagnosis = [
            'title' => 'Kondisi Lambung Menengah',
            'text' => 'Berdasarkan jawaban Anda, Anda memiliki risiko sedang untuk masalah lambung. Perhatikan pola makan, hindari makanan pedas atau asam berlebihan, dan pertimbangkan konsultasi dengan dokter jika gejala seperti nyeri perut atau mual muncul.'
        ];
    } else if ($score <= 32) {
        $diagnosis = [
            'title' => 'Kondisi Lambung Waspada',
            'text' => 'Berdasarkan jawaban Anda, kondisi lambung Anda menunjukkan risiko tinggi. Gejala seperti nyeri, mual, atau pola makan tidak sehat perlu diperhatikan. Konsultasikan dengan dokter untuk evaluasi lebih lanjut dan lakukan perubahan gaya hidup segera.'
        ];
    } else {
        $diagnosis = [
            'title' => 'Kondisi Lambung Kritis',
            'text' => 'Berdasarkan jawaban Anda, Anda memiliki risiko sangat tinggi terhadap penyakit lambung kronis. Gejala ini dapat dipicu oleh pola makan tidak sehat, stres, atau kebiasaan merokok/alkohol. Segera konsultasikan dengan dokter untuk pemeriksaan dan penanganan mendesak.'
        ];
    }

    // Save to database
    $user_id = $_SESSION['user_id'];
    $answers = json_encode($scores);
    $stmt = $conn->prepare("INSERT INTO diagnosis_results (user_id, total_score, diagnosis_title, diagnosis_text, answers) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iisss", $user_id, $score, $diagnosis['title'], $diagnosis['text'], $answers);
    
    if ($stmt->execute()) {
        // Clear session data after saving
        unset($_SESSION['diagnosis_data']);
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to save to database']);
    }
    $stmt->close();
} else {
    echo json_encode(['success' => true]);
}
?>