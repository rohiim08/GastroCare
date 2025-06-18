<?php
session_start();
include '../db.php'; // Include database connection

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../_Login/login.php');
    exit;
}

$message = ''; // For success/error messages
$section_key = filter_input(INPUT_GET, 'section', FILTER_SANITIZE_STRING);
$section_data = null;

if (!$section_key) {
    header('Location: admin_education_sections.php?message=' . urlencode('Error: Kunci bagian tidak ditentukan.'));
    exit;
}

if ($conn->connect_error) {
    $conn = new mysqli($host, $user, $password, $dbname);
    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
    $posted_key = trim($_POST['section_key']);
    $content = trim($_POST['content']);

    if ($posted_key === $section_key) {
        $stmt = $conn->prepare("UPDATE education_static_text SET content = ? WHERE section_key = ?");
        if ($stmt) {
            $stmt->bind_param('ss', $content, $section_key);
            if ($stmt->execute()) {
                $message = "Teks bagian berhasil diperbarui.";
            } else {
                $message = "Error: Gagal memperbarui teks bagian. " . $stmt->error;
            }
            $stmt->close();
        } else {
            $message = "Error: Gagal menyiapkan statement pembaruan. " . $conn->error;
        }
    } else {
        $message = "Error: Kunci bagian tidak cocok.";
    }
    header("Location: manage_static_text.php?section=" . urlencode($section_key) . "&message=" . urlencode($message));
    exit;
}

$stmt = $conn->prepare("SELECT section_key, section_title, content FROM education_static_text WHERE section_key = ?");
if ($stmt) {
    $stmt->bind_param('s', $section_key);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        $section_data = $result->fetch_assoc();
    } else {
        // Optionally, insert a default record if not found, or show error
        $message = "Error: Bagian teks dengan kunci '{$section_key}' tidak ditemukan.";
        // For now, redirect back if not found
        // header('Location: admin_education_sections.php?message=' . urlencode($message));
        // exit;
    }
    $stmt->close();
} else {
    $message = "Error saat mengambil data: " . $conn->error;
}


// Get message from redirect
if (isset($_GET['message'])) {
    $message = htmlspecialchars(urldecode($_GET['message']));
}

$conn->close();

// Use a default title if not found in DB
$page_title = $section_data ? htmlspecialchars($section_data['section_title']) : 'Bagian Teks Tidak Dikenal';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Kelola Teks: <?php echo $page_title; ?></title>
    <link rel="stylesheet" href="../_Template/template.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <style>
        /* Basic Admin Page Styling */
        body {
            font-family: sans-serif;
            background-color: #f4f7f6;
            margin: 0;
            padding: 20px;
        }

        .admin-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 2rem;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #0E416C;
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .crud-form {
            padding: 1.5rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f8f9fa;
            margin-bottom: 2rem;
        }

        .crud-form label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: bold;
            color: #495057;
        }

        .crud-form textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 1rem;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            min-height: 150px;
        }

        .crud-form button {
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1em;
        }

        .crud-form button:hover {
            opacity: 0.9;
        }

        .back-link {
            display: block;
            margin-bottom: 1rem;
            text-align: left;
            color: #1A8EC4;
            text-decoration: none;
            font-weight: bold;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .message {
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 5px;
            text-align: center;
            font-weight: bold;
        }

        .message.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .message.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .no-data {
            text-align: center;
            color: red;
            padding: 1rem;
        }
    </style>
</head>

<body>

    <div class="admin-container">
        <a href="admin_education_sections.php" class="back-link">&larr; Kembali ke Kelola Bagian Edukasi</a>
        <h1>Kelola Teks: <?php echo $page_title; ?></h1>

        <?php if (!empty($message)): ?>
            <div class="message <?php echo (strpos($message, 'Error:') === 0 || strpos($message, 'Gagal') !== false) ? 'error' : 'success'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <?php if ($section_data): ?>
            <!-- Form for Editing -->
            <div class="crud-form">
                <form action="manage_static_text.php?section=<?php echo urlencode($section_key); ?>" method="POST">
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="section_key" value="<?php echo htmlspecialchars($section_data['section_key']); ?>">

                    <div>
                        <label for="content">Konten Teks:</label>
                        <textarea id="content" name="content" rows="10" required><?php echo htmlspecialchars($section_data['content'] ?? ''); ?></textarea>
                    </div>

                    <button type="submit">Simpan Perubahan</button>
                </form>
            </div>
        <?php else: ?>
            <p class="no-data">Bagian teks ini belum ada di database atau kunci bagian tidak valid.</p>
        <?php endif; ?>

    </div>

    <script>
        // Optional: Clear message from URL
        if (window.location.search.includes('message=')) {
            setTimeout(() => {
                const url = new URL(window.location);
                url.searchParams.delete('message');
                window.history.replaceState({}, document.title, url);
            }, 5000);
        }
    </script>

</body>

</html>