<?php
session_start();
include '../db.php'; // Include database connection

// Check if user is logged in (optional, but good practice)
if (!isset($_SESSION['user_id'])) {
  header('Location: ../_Login/login.php');
  exit;
}

// --- Fetch Dynamic Content --- 

// Re-open connection if closed previously
if ($conn->connect_error) {
  $conn = new mysqli($host, $user, $password, $dbname);
  if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
  }
}

// 1. Fetch Static Text (Pengertian)
$pengertian_text = '';
$stmt_static = $conn->prepare("SELECT content FROM education_static_text WHERE section_key = ?");
$pengertian_key = 'pengertian';
if ($stmt_static) {
  $stmt_static->bind_param('s', $pengertian_key);
  $stmt_static->execute();
  $result_static = $stmt_static->get_result();
  if ($result_static->num_rows > 0) {
    $pengertian_text = $result_static->fetch_assoc()['content'];
  }
  $stmt_static->close();
} else {
  // Handle error if needed
  error_log("Failed to prepare statement for static text: " . $conn->error);
}

// 2. Fetch Jenis Penyakit
$jenis_penyakit_items = [];
$sql_jenis = "SELECT title, description FROM education_jenis_penyakit ORDER BY display_order ASC, id ASC";
$result_jenis = $conn->query($sql_jenis);
if ($result_jenis && $result_jenis->num_rows > 0) {
  while ($row = $result_jenis->fetch_assoc()) {
    $jenis_penyakit_items[] = $row;
  }
}

// 3. Fetch List Items (Penyebab, Gejala, Pencegahan, Pengobatan)
$list_items = ['penyebab' => [], 'gejala' => [], 'pencegahan' => [], 'pengobatan' => []];
$sql_list = "SELECT section_type, item_text FROM education_list_items ORDER BY section_type ASC, display_order ASC, id ASC";
$result_list = $conn->query($sql_list);
if ($result_list && $result_list->num_rows > 0) {
  while ($row = $result_list->fetch_assoc()) {
    if (isset($list_items[$row['section_type']])) {
      $list_items[$row['section_type']][] = $row['item_text'];
    }
  }
}

// 4. Fetch Mitos vs Fakta
$mitos_fakta_items = [];
$sql_mitos = "SELECT mitos_text, fakta_text FROM education_mitos_fakta ORDER BY display_order ASC, id ASC";
$result_mitos = $conn->query($sql_mitos);
if ($result_mitos && $result_mitos->num_rows > 0) {
  while ($row = $result_mitos->fetch_assoc()) {
    $mitos_fakta_items[] = $row;
  }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>GastroCare - Kesehatan Lambung</title>
  <!-- Keep original CSS links -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0&family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@48,400,1,0" />
  <link rel="stylesheet" href="education.css" />
  <link rel="stylesheet" href="../_Chatbot/chatbot.css">
  <link rel="stylesheet" href="../_Template/template.css">
</head>

<body>
  <!-- Navigasi (Keep original navigation) -->
  <nav>
    <div class="nav-header">
      <a href="../main.php" class="nav-close"><img src="assets/close.png" id="close-btn" alt="Tutup" /></a>
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

      <div class="hamburger">
        <span></span>
        <span></span>
        <span></span>
      </div>
    </div>

    <ul class="mobile-menu">
      <div class="profile-info" id="mobile-profile-info">
        <img src="assets/user.png" alt="profile">
        <span id="mobile-username">Nama User</span> <!-- Add ID for JS update -->
      </div>
      <button class="login-button-mobile" id="login-btn-mobile">Login</button>
      <button id="logout-btn-mobile" class="logout-button-mobile">Logout</button>
    </ul>
  </nav>

  <!-- SECTION UTAMA / HERO (Keep original) -->
  <section class="hero">
    <div class="hero-bg"></div>
    <div class="hero-content">
      <div class="hero-text">
        <h3 class="hero-subtitle">Edukasi & Informasi</h3>
        <h1 class="hero-title">Ayo Pelajari Mengenai<br />Kesehatan Lambung</h1>
      </div>
    </div>
  </section>

  <!-- PENGERTIAN PENYAKIT LAMBUNG (Dynamic) -->
  <section class="penyakit-lambung">
    <div class="container">
      <h1>Apa itu Penyakit Lambung?</h1>
      <p>
        <?php echo !empty($pengertian_text) ? nl2br(htmlspecialchars($pengertian_text)) : 'Informasi tidak tersedia saat ini.'; ?>
      </p>
    </div>
  </section>

  <!-- JENIS-JENIS PENYAKIT LAMBUNG (Dynamic) -->
  <section class="jenis-penyakit">
    <div class="container">
      <h2>JENIS – JENIS PENYAKIT LAMBUNG</h2>
      <div class="grid-penyakit">
        <?php if (!empty($jenis_penyakit_items)): ?>
          <?php foreach ($jenis_penyakit_items as $item): ?>
            <div class="card-penyakit">
              <h3><?php echo htmlspecialchars($item['title']); ?></h3>
              <p><?php echo nl2br(htmlspecialchars($item['description'])); ?></p>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <p>Informasi jenis penyakit tidak tersedia saat ini.</p>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <section class="info-section">
    <div class="container">
      <div class="info-grid-container">
        <?php
        $sections = [
          'penyebab' => [
            'title' => 'Penyebab Penyakit Lambung',
            'image' => 'assets/penyebab.jpg'
          ],
          'gejala' => [
            'title' => 'Gejala yang Perlu Diwaspadai',
            'image' => 'assets/gejala.jpg'
          ],
          'pencegahan' => [
            'title' => 'Pencegahan dalam Sehari - hari',
            'image' => 'assets/pencegahan.jpg'
          ],
          'pengobatan' => [
            'title' => 'Pengobatan yang Dapat Dilakukan',
            'image' => 'assets/pengobatan.jpg'
          ]
        ];

        foreach ($sections as $key => $details):
        ?>
          <div class="info-card">
            <div class="info-card-image">
              <img src="<?php echo htmlspecialchars($details['image']); ?>" alt="<?php echo htmlspecialchars($details['title']); ?>">
            </div>

            <h2 class="info-card-title"><?php echo htmlspecialchars($details['title']); ?>:</h2>

            <div class="info-card-body">
              <?php if (!empty($list_items[$key])): ?>
                <ul class="info-list">
                  <?php foreach ($list_items[$key] as $text): ?>
                    <li><?php echo htmlspecialchars($text); ?></li>
                  <?php endforeach; ?>
                </ul>
              <?php else: ?>
                <p>Informasi tidak tersedia.</p>
              <?php endif; ?>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- MITOS DAN FAKTA (Dynamic) -->
  <section class="mitos-fakta">
    <div class="container">
      <h2>Trivia Mitos vs Fakta Seputar Lambung</h2>
      <div class="grid-mitos-fakta">
        <?php if (!empty($mitos_fakta_items)): ?>
          <?php foreach ($mitos_fakta_items as $item): ?>
            <div class="item-mitos-fakta">
              <div class="header-mitos">
                <span class="icon-mitos">✗</span>
                <span class="text-mitos">Mitos: <?php echo htmlspecialchars($item['mitos_text']); ?></span>
              </div>
              <div class="text-fakta">
                <span class="icon-fakta">✓</span>
                <span>Fakta: <?php echo nl2br(htmlspecialchars($item['fakta_text'])); ?></span>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <p>Informasi mitos & fakta tidak tersedia saat ini.</p>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <!-- FOOTER (Keep original) -->
  <footer class="footer-content">
    <div class="container">
      <div class="footer-content-content">
        <h2 class="footer-content-logo">GASTROCARE</h2>
        <div class="footer-content-main">
          <h3 class="footer-content-title">Butuh Bantuan untuk Kesehatan Lambung Anda?</h3>
          <p class="footer-content-desc">
            Dapatkan wawasan akurat, analisis bertenaga AI, dan panduan yang didukung pakar tentang semua kondisi terkait lambung. Sistem cerdas kami membantu Anda memahami gejala, pencegahan, dan pilihan pengobatan hanya dalam beberapa klik.
          </p>
        </div>
        <div class="footer-content-bottom">
          <p class="copyright">© 2025 GASTROCARE</p>
        </div>
      </div>
    </div>
  </footer>

  <!-- Chatbot (Keep original) -->
  <button id="chatbot-toggler">
    <span class="material-symbols-rounded">mode_comment</span>
    <span class="material-symbols-rounded">close</span>
  </button>
  <div class="chatbot-popup">
    <!-- Chatbot content remains the same -->
    <div class="chat-header">
      <div class="header-info">
        <svg class="chatbot-logo" xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 1024 1024">
          <path d="M738.3 287.6H285.7c-59 0-106.8 47.8-106.8 106.8v303.1c0 59 47.8 106.8 106.8 106.8h81.5v111.1c0 .7.8 1.1 1.4.7l166.9-110.6 41.8-.8h117.4l43.6-.4c59 0 106.8-47.8 106.8-106.8V394.5c0-59-47.8-106.9-106.8-106.9zM351.7 448.2c0-29.5 23.9-53.5 53.5-53.5s53.5 23.9 53.5 53.5-23.9 53.5-53.5 53.5-53.5-23.9-53.5-53.5zm157.9 267.1c-67.8 0-123.8-47.5-132.3-109h264.6c-8.6 61.5-64.5 109-132.3 109zm110-213.7c-29.5 0-53.5-23.9-53.5-53.5s23.9-53.5 53.5-53.5 53.5 23.9 53.5 53.5-23.9 53.5-53.5 53.5zM867.2 644.5V453.1h26.5c19.4 0 35.1 15.7 35.1 35.1v121.1c0 19.4-15.7 35.1-35.1 35.1h-26.5zM95.2 609.4V488.2c0-19.4 15.7-35.1 35.1-35.1h26.5v191.3h-26.5c-19.4 0-35.1-15.7-35.1-35.1zM561.5 149.6c0 23.4-15.6 43.3-36.9 49.7v44.9h-30v-44.9c-21.4-6.5-36.9-26.3-36.9-49.7 0-28.6 23.3-51.9 51.9-51.9s51.9 23.3 51.9 51.9z"></path>
        </svg>
        <h2 class="logo-text">GastroBot</h2>
      </div>
      <button id="close-chatbot" class="material-symbols-rounded">keyboard_arrow_down</button>
    </div>
    <div class="chat-body">
      <div class="message bot-message">
        <svg class="bot-avatar" xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 1024 1024">
          <path d="M738.3 287.6H285.7c-59 0-106.8 47.8-106.8 106.8v303.1c0 59 47.8 106.8 106.8 106.8h81.5v111.1c0 .7.8 1.1 1.4.7l166.9-110.6 41.8-.8h117.4l43.6-.4c59 0 106.8-47.8 106.8-106.8V394.5c0-59-47.8-106.9-106.8-106.9zM351.7 448.2c0-29.5 23.9-53.5 53.5-53.5s53.5 23.9 53.5 53.5-23.9 53.5-53.5 53.5-53.5-23.9-53.5-53.5zm157.9 267.1c-67.8 0-123.8-47.5-132.3-109h264.6c-8.6 61.5-64.5 109-132.3 109zm110-213.7c-29.5 0-53.5-23.9-53.5-53.5s23.9-53.5 53.5-53.5 53.5 23.9 53.5 53.5-23.9 53.5-53.5 53.5zM867.2 644.5V453.1h26.5c19.4 0 35.1 15.7 35.1 35.1v121.1c0 19.4-15.7 35.1-35.1 35.1h-26.5zM95.2 609.4V488.2c0-19.4 15.7-35.1 35.1-35.1h26.5v191.3h-26.5c-19.4 0-35.1-15.7-35.1-35.1zM561.5 149.6c0 23.4-15.6 43.3-36.9 49.7v44.9h-30v-44.9c-21.4-6.5-36.9-26.3-36.9-49.7 0-28.6 23.3-51.9 51.9-51.9s51.9 23.3 51.9 51.9z"></path>
        </svg>
        <div class="message-text">Hai, User 👋 <br>Ada yang bisa saya bantu?</div>
      </div>
    </div>
    <div class="chat-footer">
      <form action="#" class="chat-form">
        <textarea placeholder="Ask anything . . ." class="message-input" required></textarea>
        <div class="chat-controls">
          <button type="submit" id="send-message" class="material-symbols-rounded">arrow_upward</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Keep original JS links -->
  <script src="https://unpkg.com/scrollreveal"></script>
  <script src="../main.js"></script>
  <script src="../_Chatbot/chatbot.js"></script>
  <script src="../_Template/profile.js"></script>
  <script src="education-animation.js"></script>
  <!-- Add script to update username dynamically -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      fetch('../get_user.php')
        .then(res => res.json())
        .then(data => {
          if (data.loggedIn) {
            // Update username in nav and mobile menu
            const usernameElements = document.querySelectorAll('#nav-username, #mobile-username');
            usernameElements.forEach(el => el.textContent = data.username);

            // Show/hide login/logout buttons based on login status
            document.getElementById('login-btn').style.display = 'none';
            document.getElementById('logout-btn').style.display = 'block';
            document.getElementById('login-btn-mobile').style.display = 'none';
            document.getElementById('logout-btn-mobile').style.display = 'block';
            document.getElementById('mobile-profile-info').style.display = 'flex'; // Show profile info
            document.querySelector('.nav-profile .bx-caret-down').style.display = 'inline-block'; // Show dropdown arrow

          } else {
            // User not logged in - show login, hide logout/profile
            document.getElementById('login-btn').style.display = 'block';
            document.getElementById('logout-btn').style.display = 'none';
            document.getElementById('login-btn-mobile').style.display = 'block';
            document.getElementById('logout-btn-mobile').style.display = 'none';
            document.getElementById('mobile-profile-info').style.display = 'none'; // Hide profile info
            document.querySelector('.nav-profile .bx-caret-down').style.display = 'none'; // Hide dropdown arrow
            document.querySelector('.nav-profile > img').style.display = 'none'; // Hide profile image
            document.querySelector('.nav-profile > span').style.display = 'none'; // Hide username span
          }
        })
        .catch(error => console.error('Error fetching user data:', error));

      // Add event listener for logout buttons
      document.getElementById('logout-btn').addEventListener('click', function() {
        window.location.href = '../logout.php';
      });
      document.getElementById('logout-btn-mobile').addEventListener('click', function() {
        window.location.href = '../logout.php';
      });
      // Add event listener for login buttons (if needed, though usually handled by profile.js)
      document.getElementById('login-btn').addEventListener('click', function() {
        window.location.href = '../_Login/login.php';
      });
      document.getElementById('login-btn-mobile').addEventListener('click', function() {
        window.location.href = '../_Login/login.php';
      });
    });
  </script>

</body>

</html>