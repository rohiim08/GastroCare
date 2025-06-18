<?php
session_start();
require_once 'C:\xampp\htdocs\gastrocare\db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../_Login/login.php');
    exit;
}

// Fetch questions for session 3
$questions = $conn->query("SELECT * FROM questions WHERE session = 3 ORDER BY id");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sesi 3: Gejala Fisik</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="../_Chatbot/chatbot.css">
    <link rel="stylesheet" href="../_Template/template.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0&family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@48,400,1,0" />
    <style>
        .checkbox-label {
            display: inline-block;
            margin-right: 15px;
            margin-bottom: 10px;
        }
        .form-group.checkbox-group {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
    </style>
</head>
<body>
    <nav>
        <div class="nav-header">
            <a href="../main.php" class="nav-close"><img src="assets/close.png" alt="Tutup" /></a>
            <a href="#" class="nav-logo"><img src="assets/logo.png" alt="Logo"></a>
            <div class="nav-profile">
                <img src="assets/user.png" alt="profile">
                <span><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                <i class='bx bx-caret-down'></i>
                <button class="login-button" id="login-btn">Login</button>
                <div class="profile-dropdown" id="profile-dropdown">
                    <button id="logout-btn" class="logout-button">Logout</button>
                </div>
            </div>
            <div class="hamburger">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
        <ul class="mobile-menu">
            <div class="profile-info" id="mobile-profile-info">
                <img src="assets/user.png" alt="profile">
                <span><?php echo htmlspecialchars($_SESSION['username']); ?></span>
            </div>
            <button class="login-button-mobile" id="login-btn-mobile">Login</button>
            <button id="logout-btn-mobile" class="logout-button-mobile">Logout</button>
        </ul>
    </nav>

    <div class="progress-container">
        <h2>Lengkapi Pertanyaan Dengan Data Yang Sesuai!</h2>
        <div class="steps">
            <div class="step active" data-step="1"><span>1</span></div>
            <div class="step active" data-step="2"><span>2</span></div>
            <div class="step active" data-step="3"><span>3</span></div>
            <div class="step" data-step="4"><span>4</span></div>
            <div class="step" data-step="5"><span>5</span></div>
            <div class="step" data-step="6"><span>6</span></div>
        </div>
    </div>

    <div class="container1">
        <form id="diagnosis-form" onsubmit="saveFormData(event, 'session4.php')">
            <div class="question-session active">
                <?php while ($question = $questions->fetch_assoc()): ?>
                    <h2><?php echo htmlspecialchars($question['question_text']); ?></h2>
                    <div class="form-group">
                        <?php
                        $options = json_decode($question['options'], true);
                        if ($question['input_type'] === 'button') {
                            foreach ($options as $option) {
                                echo "<button type='button' class='option-btn' data-field='{$question['field_name']}' data-value='$option' onclick=\"selectOption(this, '{$question['field_name']}', '$option')\">" . htmlspecialchars($option) . "</button>";
                            }
                            echo "<input type='hidden' id='{$question['field_name']}' name='{$question['field_name']}' required>";
                        } elseif ($question['input_type'] === 'select') {
                            echo "<select name='{$question['field_name']}' id='{$question['field_name']}' required>";
                            echo "<option value=''>Pilih opsi</option>";
                            foreach ($options as $option) {
                                echo "<option value='$option'>" . htmlspecialchars($option) . "</option>";
                            }
                            echo "</select>";
                        } elseif ($question['input_type'] === 'number') {
                            echo "<input type='number' name='{$question['field_name']}' id='{$question['field_name']}' placeholder='Isi dengan angka' required min='1'>";
                        } elseif ($question['input_type'] === 'checkbox') {
                            foreach ($options as $option) {
                                echo "<label class='checkbox-label'><input type='checkbox' name='{$question['field_name']}' value='$option'><span>" . htmlspecialchars($option) . "</span></label>";
                            }
                        }
                        ?>
                    </div>
                <?php endwhile; ?>
            </div>
            <div class="navigation-buttons">
                <a href="session2.php" class="btn secondary">Kembali</a>
                <button type="submit" class="btn primary">Lanjut</button>
            </div>
        </form>
    </div>

    <footer class="footer-content">
        <div class="container">
            <div class="footer-content-content">
                <h2 class="footer-content-logo">GASTROCARE</h2>
                <div class="footer-content-main">
                    <h3 class="footer-content-title">Butuh Bantuan untuk Kesehatan Lambung Anda?</h3>
                    <p class="footer-content-desc">
                        Dapatkan wawasan akurat, analisis bertenaga AI, dan panduan yang didukung pakar tentang semua kondisi terkait lambung.
                    </p>
                </div>
                <div class="footer-content-bottom">
                    <p class="copyright">© 2025 GASTROCARE</p>
                </div>
            </div>
        </div>
    </footer>

    <button id="chatbot-toggler">
        <span class="material-symbols-rounded">mode_comment</span>
        <span class="material-symbols-rounded">close</span>
    </button>
    <div class="chatbot-popup">
        <div class="chat-header">
            <div class="header-info">
                <svg class="chatbot-logo" xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 1024 1024">
                    <path d="M738.3 287.6H285.7c-59 0-106.8 47.8-106.8 106.8v303.1c0 59 47.8 106.8 106.8 106.8h81.5v111.1c0 .7.8 1.1 1.4 .7l166.9-110.6 41.8-.8h117.4l43.6-.4c59 0 106.8-47.8 106.8-106.8V394.5c0-59-47.8-106.9-106.8-106.9zM351.7 448.2c0-29.5 23.9-53.5 53.5-53.5s53.5 23.9 53.5 53.5-23.9 53.5-53.5 53.5-53.5-23.9-53.5-53.5zm157.9 267.1c-67.8 0-123.8-47.5-132.3-109h264.6c-8.6 61.5-64.5 109-132.3 109zm110-213.7c-29.5 0-53.5-23.9-53.5-53.5s23.9-53.5 53.5-53.5 53.5 23.9 53.5 53.5-23.9 53.5-53.5 53.5zM867.2 644.5V453.1h26.5c19.4 0 35.1 15.7 35.1 35.1v121.1c0 19.4-15.7 35.1-35.1 35.1h-26.5zM95.2 609.4V488.2c0-19.4 15.7-35.1 35.1-35.1h26.5v191.3h-26.5c-19.4 0-35.1-15.7-35.1-35.1zM561.5 149.6c0 23.4-15.6 43.3-36.9 49.7v44.9h-30v-44.9c-21.4-6.5-36.9-26.3-36.9-49.7 0-28.6 23.3-51.9 51.9-51.9s51.9 23.3 51.9 51.9z"></path>
                </svg>
                <h2 class="logo-text">GastroBot</h2>
            </div>
            <button id="close-chatbot" class="material-symbols-rounded">keyboard_arrow_down</button>
        </div>
        <div class="chat-body"></div>
        <div class="chat-footer">
            <form action="#" class="chat-form">
                <textarea placeholder="Ask anything . . ." class="message-input" required></textarea>
                <div class="chat-controls">
                    <button type="submit" id="send-message" class="material-symbols-rounded">arrow_upward</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://unpkg.com/scrollreveal"></script>
    <script src="../main.js"></script>
    <script src="js/script1.js"></script>
    <script src="../_Chatbot/chatbot.js"></script>
    <script src="../_Template/profile.js"></script>
    <script>
        fetch('../get_user.php')
            .then(res => res.json())
            .then(data => {
                if (data.loggedIn) {
                    sessionStorage.setItem('loggedInUser', data.username);
                    sessionStorage.setItem('userRole', data.role);
                } else {
                    sessionStorage.removeItem('loggedInUser');
                    sessionStorage.removeItem('userRole');
                }
                if (typeof updateProfile === 'function') updateProfile();
            });
        document.addEventListener('DOMContentLoaded', () => {
            updateProgressBar(3);
            loadFormData();
        });
    </script>
</body>
</html>