<?php
session_start();
require_once 'C:\xampp\htdocs\gastrocare\db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT total_score, diagnosis_title, diagnosis_text, answers FROM diagnosis_results WHERE user_id = ? ORDER BY created_at DESC LIMIT 1");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    echo json_encode([
        'success' => true,
        'totalScore' => $row['total_score'],
        'scores' => json_decode($row['answers'], true),
        'diagnosis' => [
            'title' => $row['diagnosis_title'],
            'text' => $row['diagnosis_text']
        ]
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'No diagnosis result found']);
}

$stmt->close();
?>