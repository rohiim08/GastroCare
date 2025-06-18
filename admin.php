<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header('Location: _Login/login.php');
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <!-- External Stylesheets -->
  <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
  <link rel="stylesheet" href="_Template/template.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0&family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@48,400,1,0" />

  <!-- Title -->
  <title>Dashboard Admin - GastroCare</title>

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

    /* Styling Gambar */
    img {
      display: flex;
      width: 100%;
    }

    /* Styling Tautan */
    a {
      text-decoration: none;
      transition: 0.3s;
    }

    /* Styling Daftar */
    ul {
      list-style: none;
    }

    /* Styling HTML dan Body */
    html,
    body {
      scroll-behavior: smooth;
    }

    body {
      font-family: "Poppins", sans-serif;
    }

    /* Styling Header */
    header {
      padding-top: 3rem;
      position: relative;
      isolation: isolate;
      background-color: var(--primary-color);
      z-index: 1;
    }

    .header-bottom {
      position: relative;
      bottom: 0;
      left: 0;
      width: 100%;
      height: 3rem;
      background-color: var(--white);
      z-index: -1;
    }

    .header-container {
      align-items: center;
      height: 20rem;
      padding-block: 2rem 0;
      display: flex;
      flex-direction: column;
      justify-content: center;
      text-align: center;
      gap: 1rem;
    }

    .header-content h4 {
      margin-bottom: 1rem;
      max-width: fit-content;
      padding: 5px 15px;
      font-size: 1.2rem;
      font-weight: 600;
      color: var(--text-white);
      background-color: rgba(255, 255, 255, 0.1);
      border-radius: 5rem;
      margin-inline: auto;
    }

    .header-content h1 {
      margin-bottom: 1rem;
      font-size: 3.5rem;
      font-weight: 700;
      line-height: 3.5rem;
      color: var(--text-white);
    }

    .header-content p {
      max-width: 600px;
      margin-bottom: 2rem;
      color: var(--text-light);
      margin-inline: auto;
    }

    /* Admin Dashboard Specific Styles */
    .admin-grid {
      grid-template-columns: repeat(3, 1fr);
      margin-top: 4rem;
      display: grid;
      gap: 3rem;
    }

    .admin-card {
      text-align: center;
      background-color: var(--white);
      border: 2px solid var(--secondary-color);
      border-radius: 1rem;
      padding: 2rem;
      transition: all 0.3s ease;
      cursor: pointer;
    }

    .admin-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 25px rgba(26, 142, 196, 0.15);
    }

    .admin-card .icon {
      width: 80px;
      height: 80px;
      margin: 0 auto 1.5rem;
      background-color: rgba(26, 142, 196, 0.1);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 2rem;
      color: var(--secondary-color);
    }

    .admin-card h4 {
      margin-bottom: 1rem;
      font-size: 1.3rem;
      font-weight: 600;
      color: var(--secondary-color);
    }

    .admin-card p {
      margin-bottom: 1.5rem;
      color: var(--text-light);
      line-height: 1.6;
    }

    .admin-card .btn {
      display: inline-block;
      padding: 0.75rem 2rem;
      background-color: var(--secondary-color);
      color: var(--white);
      border-radius: 25px;
      transition: 0.3s;
      font-weight: 500;
    }

    .admin-card .btn:hover {
      background-color: var(--primary-color);
      transform: scale(1.05);
    }

    /* Info Section */
    .info-section {
      background-color: #f8f9fa;
      padding: 4rem 0;
    }

    .info-grid {
      grid-template-columns: repeat(2, 1fr);
      display: grid;
      gap: 3rem;
      margin-top: 3rem;
    }

    .info-card {
      background-color: var(--white);
      padding: 2rem;
      border-radius: 1rem;
      border-left: 4px solid var(--secondary-color);
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
    }

    .info-card h4 {
      color: var(--primary-color);
      margin-bottom: 1rem;
      font-size: 1.2rem;
      font-weight: 600;
    }

    .info-card p {
      color: var(--text-light);
      line-height: 1.6;
    }

    .info-card ul {
      margin-top: 1rem;
      padding-left: 1rem;
    }

    .info-card li {
      color: var(--text-light);
      margin: 0.5rem 0;
      list-style: disc;
    }

    /* Quick Actions */
    .quick-actions {
      margin-top: 4rem;
      text-align: center;
    }

    .actions-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 2rem;
      margin-top: 2rem;
    }

    .action-item {
      background-color: var(--white);
      padding: 1.5rem;
      border-radius: 1rem;
      border: 1px solid #e0e0e0;
      transition: all 0.3s ease;
    }

    .action-item:hover {
      border-color: var(--secondary-color);
      transform: translateY(-2px);
    }

    .action-item i {
      font-size: 2rem;
      color: var(--secondary-color);
      margin-bottom: 1rem;
    }

    .action-item h5 {
      color: var(--text-dark);
      font-weight: 500;
      font-size: 0.9rem;
    }

    /* Media Queries */
    @media (max-width: 1130px) {
      .header-content h1 {
        font-size: 3rem;
      }

      .admin-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 2rem;
      }

      .actions-grid {
        grid-template-columns: repeat(2, 1fr);
      }
    }

    @media (max-width: 760px) {
      .header-content h1 {
        font-size: 2.5rem;
        line-height: 2.5rem;
      }

      .admin-grid {
        grid-template-columns: 1fr;
        gap: 2rem;
      }

      .info-grid {
        grid-template-columns: 1fr;
        gap: 2rem;
      }

      .actions-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
      }
    }

    @media (max-width: 480px) {
      .header-content h1 {
        font-size: 2rem;
        line-height: 2rem;
      }

      .actions-grid {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>

<body>
  <!-- Navigasi -->
  <nav>
    <div class="nav-header">
      <a href="#" class="nav-logo"><img src="assets/logo.png" alt="Logo"></a>

      <div class="nav-profile">
        <img src="assets/user.png" alt="profile">
        <span>Nama User</span>
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
        <img src="assets/user.png" alt="profile">
        <span>Nama User</span>
      </div>

      <!-- Mobile Login Button -->
      <button class="login-button-mobile" id="login-btn-mobile">Login</button>

      <!-- Mobile Logout Button -->
      <button id="logout-btn-mobile" class="logout-button-mobile">Logout</button>
    </ul>
  </nav>

  <!-- Header Section -->
  <header id="dashboard">
    <div class="section-container header-container">
      <div class="header-content">
        <h4>Admin Panel</h4>
        <h1>DASHBOARD ADMIN</h1>
        <p>
          Kelola dan pantau sistem GastroCare dengan mudah. Akses semua fitur administrasi untuk memberikan layanan terbaik kepada pengguna.
        </p>
      </div>
    </div>
    <div class="header-bottom"></div>
  </header>

  <!-- Main Admin Functions -->
  <section class="section-container" id="management">
    <p class="section-subheader">Fungsi Utama</p>
    <h2 class="section-header">Panel Kontrol Admin</h2>
    <div class="admin-grid">
      <div class="admin-card" onclick="goToEducationManagement()">
        <div class="icon">
          <i class="ri-book-2-line"></i>
        </div>
        <h4>Kelola Edukasi</h4>
        <p>Tambah, edit, atau hapus artikel edukasi kesehatan. Pastikan informasi yang diberikan selalu akurat dan terbaru untuk pengguna GastroCare.</p>
        <a href="_Education/admin_education_sections.php" class="btn">Kelola Edukasi</a>
      </div>
      <div class="admin-card" onclick="goToDiagnosisManagement()">
        <div class="icon">
          <i class="ri-stethoscope-line"></i>
        </div>
        <h4>Kelola Diagnosa</h4>
        <p>Kelola data diagnosa, pertanyaan, dan hasil analisis. Atur alur diagnosa agar pengguna mendapatkan hasil yang tepat dan informatif.</p>
        <a href="_Diagnosa/admin_diagnosa_3.php" class="btn">Kelola Diagnosa</a>
      </div>
      <div class="admin-card" onclick="goToLifestyleManagement()">
        <div class="icon">
          <i class="ri-heart-pulse-line"></i>
        </div>
        <h4>Kelola Lifestyle</h4>
        <p>Atur konten gaya hidup sehat, rekomendasi makanan, dan tips kesehatan. Bantu pengguna menerapkan pola hidup sehat melalui fitur ini.</p>
        <a href="admin_lifestyle.php" class="btn">Kelola Lifestyle</a>
      </div>
    </div>
  </section>

  <!-- Information Section -->
  <section class="info-section">
    <div class="section-container">
      <p class="section-subheader">Informasi Admin</p>
      <h2 class="section-header">Panduan & Tanggung Jawab</h2>
      <div class="info-grid">
        <div class="info-card">
          <h4>Tanggung Jawab Admin</h4>
          <p>Sebagai administrator GastroCare, Anda bertanggung jawab untuk:</p>
          <ul>
            <li>Memastikan keakuratan informasi kesehatan</li>
            <li>Mengelola akun pengguna dengan bijak</li>
            <li>Memantau kualitas konten edukasi</li>
            <li>Menjaga keamanan data pengguna</li>
            <li>Merespons feedback dan laporan pengguna</li>
          </ul>
        </div>
        <div class="info-card">
          <h4>Keamanan Data</h4>
          <p>Prioritas utama dalam mengelola sistem:</p>
          <ul>
            <li>Enkripsi data sensitif pengguna</li>
            <li>Backup rutin setiap 24 jam</li>
            <li>Monitoring akses tidak sah</li>
            <li>Audit log aktivitas admin</li>
            <li>Compliance dengan regulasi kesehatan</li>
          </ul>
        </div>
        <div class="info-card">
          <h4>Maintenance Sistem</h4>
          <p>Jadwal dan prosedur maintenance:</p>
          <ul>
            <li>Update keamanan setiap Minggu</li>
            <li>Pembersihan database bulanan</li>
            <li>Monitoring performa harian</li>
            <li>Backup verification mingguan</li>
            <li>Disaster recovery testing</li>
          </ul>
        </div>
        <div class="info-card">
          <h4>Support Guidelines</h4>
          <p>Standar layanan dukungan pengguna:</p>
          <ul>
            <li>Respon maksimal 24 jam</li>
            <li>Escalation untuk isu medis</li>
            <li>Dokumentasi semua interaksi</li>
            <li>Follow-up resolusi masalah</li>
            <li>Training update berkala</li>
          </ul>
        </div>
      </div>
    </div>
  </section>

  <!-- Quick Actions -->
  <section class="section-container">
    <div class="quick-actions">
      <p class="section-subheader">Aksi Cepat</p>
      <h2 class="section-header">Shortcut Admin</h2>
      <div class="actions-grid">
        <div class="action-item" onclick="viewRecentUsers()">
          <i class="ri-user-add-line"></i>
          <h5>User Terbaru</h5>
        </div>
        <div class="action-item" onclick="viewSystemLogs()">
          <i class="ri-file-list-line"></i>
          <h5>System Logs</h5>
        </div>
        <div class="action-item" onclick="viewFeedback()">
          <i class="ri-feedback-line"></i>
          <h5>User Feedback</h5>
        </div>
        <div class="action-item" onclick="emergencyMode()">
          <i class="ri-alarm-warning-line"></i>
          <h5>Emergency Mode</h5>
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

  <script src="_Template/profile.js"></script>
  <script>
    // Admin functions
    function goToEducationManagement() {
      window.location.href = '_Education/admin_education.php';
    }

    function goToDiagnosisManagement() {
      window.location.href = '_Diagnosa/admin_diagnosa.php';
    }

    function goToLifestyleManagement() {
      window.location.href = '_Lifestyle/admin_lifestyle.php';
    }

    // Logout function
    document.getElementById('logout-btn').addEventListener('click', function() {
      window.location.href = 'logout.php';
    });

    // Mobile logout button
    document.getElementById('logout-btn-mobile').addEventListener('click', function() {
      window.location.href = 'logout.php';
    });

    // Quick actions
    function viewRecentUsers() {
      alert('Menampilkan daftar pengguna terbaru...');
    }

    function viewSystemLogs() {
      alert('Membuka system logs...');
    }

    function viewFeedback() {
      alert('Menampilkan feedback pengguna...');
    }

    function emergencyMode() {
      if (confirm('Apakah Anda yakin ingin mengaktifkan Emergency Mode?')) {
        alert('Emergency Mode diaktifkan! Sistem dalam mode maintenance.');
      }
    }

    fetch('get_user.php')
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