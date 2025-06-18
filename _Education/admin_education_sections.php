<?php
session_start();
include '../db.php'; // Include database connection

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../_Login/login.php');
    exit;
}

// Optional: Fetch counts for summary
$jenis_count = $conn->query("SELECT COUNT(*) as count FROM education_jenis_penyakit")->fetch_assoc()['count'] ?? 0;
$list_item_count = $conn->query("SELECT COUNT(*) as count FROM education_list_items")->fetch_assoc()['count'] ?? 0;
$mitos_count = $conn->query("SELECT COUNT(*) as count FROM education_mitos_fakta")->fetch_assoc()['count'] ?? 0;

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- External Stylesheets -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="../_Template/template.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0&family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@48,400,1,0" />

    <!-- Title -->
    <title>Kelola Edukasi - GastroCare</title>

    <style>
        /* Import Font dari Google Fonts */
        @import url("https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap");

        /* Definisi Variabel CSS */
        :root {
            --primary-color: #0E416C;
            --secondary-color: #1A8EC4;
            --text-dark: #3f3b57;
            --text-white: #ffffff;
            --text-light: #908ca3;
            --white: #ffffff;
            --max-width: 1200px;
        }

        /* Reset Default Styles */
        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
        }

        body {
            font-family: "Poppins", sans-serif;
        }

        /* Styling Kontainer Utama */
        .section-container {
            max-width: var(--max-width);
            padding: 5rem 1rem;
            margin: auto;
        }

        .section-subheader {
            font-size: 1.2rem;
            font-weight: 500;
            color: var(--secondary-color);
            text-align: center;
        }

        .section-header {
            font-size: 2.5rem;
            font-weight: 500;
            color: var(--text-dark);
            text-align: center;
            line-height: 3.5rem;
        }

        /* Admin Dashboard Specific Styles */
        .admin-container {
            max-width: 900px;
            margin: 2rem auto;
            padding: 2rem;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .section-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }

        .section-card {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 1.5rem;
            text-align: center;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .section-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .section-card h2 {
            color: #1A8EC4;
            margin-top: 0;
            margin-bottom: 1rem;
            font-size: 1.4em;
        }

        .section-card p {
            color: #6c757d;
            margin-bottom: 1.5rem;
            font-size: 0.95em;
        }

        .manage-btn {
            display: inline-block;
            background-color: #0E416C;
            color: white;
            padding: 10px 20px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.2s ease;
        }

        .manage-btn:hover {
            background-color: #1A8EC4;
        }

        .back-link {
            display: block;
            margin-bottom: 1.5rem;
            text-align: center;
            color: #1A8EC4;
            text-decoration: none;
            font-weight: bold;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        /* Media Queries */
        @media (max-width: 760px) {
            .section-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <!-- Navigasi -->
    <nav>
        <div class="nav-header">
            <a href="../admin.php" class="nav-close"><img src="assets/close.png" id="close-btn" alt="Tutup" /></a>
            <a href="#" class="nav-logo"><img src="assets/logo.png" alt="Logo"></a>

            <div class="nav-profile">
                <img src="assets/user.png" alt="profile">
                <span id="nav-username">Nama User</span> <!-- Add ID for JS update -->
                <i class='bx bx-caret-down'></i>
                <button class="login-button" id="login-btn">Login</button>
                <div class="profile-dropdown" id="profile-dropdown">
                    <button id="logout-btn" class="logout-button">Logout</button>
                </div>
            </div>

            <!-- Hamburger Menu untuk Mobile -->
            <div class="hamburger">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>

        <!-- Mobile Menu -->
        <ul class="mobile-menu">
            <!-- Mobile Profile Info -->
            <div class="profile-info" id="mobile-profile-info">
                <img src="../assets/user.png" alt="profile">
                <span>Nama User</span>
            </div>

            <!-- Mobile Login Button -->
            <button class="login-button-mobile" id="login-btn-mobile">Login</button>

            <!-- Mobile Logout Button -->
            <button id="logout-btn-mobile" class="logout-button-mobile">Logout</button>
        </ul>
    </nav>

    <section class="section-container">
        <div class="admin-container">
            <h1 class="section-header">Kelola Bagian Konten Edukasi</h1>

            <div class="section-grid">
                <div class="section-card">
                    <h2>Jenis Penyakit</h2>
                    <p>Kelola daftar jenis-jenis penyakit lambung yang ditampilkan. (<?php echo $jenis_count; ?> item)</p>
                    <a href="manage_jenis_penyakit.php" class="manage-btn">Kelola</a>
                </div>

                <div class="section-card">
                    <h2>Daftar Item</h2>
                    <p>Kelola item dalam daftar Penyebab, Gejala, Pencegahan, dan Pengobatan. (<?php echo $list_item_count; ?> item)</p>
                    <a href="manage_list_items.php" class="manage-btn">Kelola</a>
                </div>

                <div class="section-card">
                    <h2>Mitos vs Fakta</h2>
                    <p>Kelola pasangan mitos dan fakta seputar kesehatan lambung. (<?php echo $mitos_count; ?> item)</p>
                    <a href="manage_mitos_fakta.php" class="manage-btn">Kelola</a>
                </div>
                <div class="section-card">
                    <h2>Pengertian Umum</h2>
                    <p>Edit teks pengantar "Apa itu Penyakit Lambung?".</p>
                    <a href="manage_static_text.php?section=pengertian" class="manage-btn">Kelola</a>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="footer-content">
        <div class="container">
            <div class="footer-content-content">
                <h2 class="footer-content-logo">GASTROCARE</h2>

                <div class="footer-content-main">
                    <h3 class="footer-content-title">Butuh Bantuan untuk Kesehatan Lambung Anda?</h3>
                    <p class="footer-content-desc">
                        Dapatkan wawasan akurat, analisis bertenaga AI, dan panduan yang didukung pakar tentang semua kondisi terkait lambung. Sistem cerdas kami membantu Anda memahami gejala, pencegahan, dan pilihan pengobatan hanya dalam beberapa
                        klik.
                    </p>
                </div>

                <div class="footer-content-bottom">
                    <p class="copyright">© 2025 GASTROCARE</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="../_Template/profile.js"></script>
    <script>
        // Logout function
        document.getElementById('logout-btn').addEventListener('click', function() {
            window.location.href = '../logout.php';
        });

        // Mobile logout button
        document.getElementById('logout-btn-mobile').addEventListener('click', function() {
            window.location.href = '../logout.php';
        });

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
                // Panggil updateProfile jika sudah didefinisikan
                if (typeof updateProfile === 'function') updateProfile();
            });
    </script>
</body>

</html>