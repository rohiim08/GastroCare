<?php
include '../db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    if (isset($_POST['action']) && $_POST['action'] === 'login') {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                $redirectUrl = $user['role'] === 'admin' ? '../admin.php' : '../main.php';
                echo json_encode(['status' => 'success', 'redirect' => $redirectUrl]);
                exit;
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Password salah!']);
                exit;
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Email tidak ditemukan!']);
            exit;
        }
    } elseif (isset($_POST['action']) && $_POST['action'] === 'register') {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param('sss', $username, $email, $password);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Registrasi berhasil! Silakan login.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Registrasi gagal!']);
        }
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign in || Sign up form</title>
     <!-- font awesome icons -->
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- css stylesheet -->
    <link rel="stylesheet" href="login.css">
</head>
<body>

    <div class="container" id="container">
        <!-- Form Register -->
        <div class="form-container sign-up-container">
            <form id="registerForm" method="POST" action="">
                <h1>Create Account</h1>
                <button class="google-signin-btn">
                    <img src="https://www.google.com/favicon.ico" alt="Google Logo" class="google-logo">
                    Continue with Google
                </button>
                <div class="divider">
                    <span>OR</span>
                </div>
                <div class="infield">
                    <input type="text" id="name" name="username" placeholder="Name" required />
                    <label></label>
                </div>
                <div class="infield">
                    <input type="email" id="regEmail" placeholder="Email" name="email" required/>
                    <label></label>
                </div>
                <div class="infield">
                    <input type="password" id="regPassword" placeholder="Password" name="password" required />
                    <label></label>
                </div>
                <input type="hidden" name="action" value="register">
                <button type="submit">Sign Up</button>
            </form>
        </div>

        <!-- Form Login -->
        <div class="form-container sign-in-container">
            <form id="loginForm" method="POST" action="">
                <h1>Sign in</h1>
                <button class="google-signin-btn">
                    <img src="https://www.google.com/favicon.ico" alt="Google Logo" class="google-logo">
                    Continue with Google
                </button>
                <div class="divider">
                    <span>OR</span>
                </div>
                <div class="infield">
                    <input type="email" id="email" placeholder="Email" name="email" required />
                    <label></label>
                </div>
                <div class="infield">
                    <input type="password" id="password" placeholder="Password" name="password" required />
                    <label></label>
                </div>
                <input type="hidden" name="action" value="login">
                <button type="submit">Sign In</button>
            </form>
        </div>

        <!-- Overlay Container -->
        <div class="overlay-container" id="overlayCon">
            <div class="overlay">
                <div class="overlay-panel overlay-left">
                    <h1>Hallo, user!</h1>
                    <p>Daftar untuk mendapatkan info seputar kesehatan lambung Anda</p>
                    <button>Sign In</button>
                </div>
                <div class="overlay-panel overlay-right">
                    <h1>Welcome Back!</h1>
                    <p>Masuk dan akses semua fitur terkait kesehatan lambung Anda</p>
                    <button>Sign Up</button>
                </div>
            </div>
            <button id="overlayBtn"></button>
        </div>
    </div>
    
   <!-- Custom Popup -->
    <div class="popup" id="customPopup">
        <div class="popup-content">
            <h2 id="popupTitle">Notifikasi</h2>
            <p id="popupMessage">Pesan akan muncul di sini</p>
            <button class="popup-close-btn" id="popupClose">Close</button>
        </div>
    </div>

    <!-- js code -->
    <script src="login.js"></script>

</body>
</html>