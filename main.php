<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        
        <!-- External Stylesheets -->
        <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0&family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@48,400,1,0" />
        <link rel="stylesheet" href="main.css" />
        <link rel="stylesheet" href="_Chatbot/chatbot.css" />
        
        <!-- Title -->
        <title>GastroCare</title>
    </head>
    <body>
        <!-- Navigasi -->
        <nav>
            <div class="nav-header">
                <a href="#" class="nav-logo"><img src="assets/logo.png" alt="Logo"></a>
                
                <ul class="nav-links">
                    <li><a href="#home">Home</a></li>
                    <li><a href="#tools">Tools</a></li>
                    <li><a href="#about">About</a></li>
                    <li><a href="#service">Service</a></li>
                    <li><a href="#contact">Contact</a></li>
                </ul>
                
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
                <li><a href="#home">Home</a></li>
                <li><a href="#tools">Tools</a></li>
                <li><a href="#about">About</a></li>
                <li><a href="#service">Service</a></li>
                <li><a href="#contact">Contact</a></li>
                
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
        <header id="home">
            <div class="section-container header-container">
                <div class="header-image">
                    <img src="assets/header-bg.png" alt="header-bg" class="header-image-bg" />
                    <img src="assets/header2.png" alt="header" />
                </div>
                <div class="header-content">
                    <h4>Selamat Datang</h4>
                    <h1>GASTROCARE</h1>
                    <h2>Kami peduli pada kesehatan lambung Anda</h2>
                    <p>
                        Dari edukasi hingga saran nutrisi, kami hadir untuk membantu Anda menjaga lambung tetap sehat dan nyaman.
                    </p>
                    <div class="header-btn">
                        <a href="#tools">
                            Lebih Lanjut
                            <span><i class="ri-arrow-right-line"></i></span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="header-bottom"></div>
        </header>

        <!-- Intro Section -->
        <section class="section-container intro-container" id="tools">
            <p class="section-subheader">Tools</p>
            <h2 class="section-header">Coba Fitur Unggulan Kami</h2>
            <div class="intro-grid">
                <div class="intro-card">
                    <div class="intro-image">
                        <img src="assets/intro-1.png" alt="intro" />
                    </div>
                    <h4>Edukasi & Informasi</h4>
                    <p>Pelajari segala hal tentang kesehatan lambung dari sumber terpercaya untuk lambung Anda.</p>
                    <a href="_Education/education.php" class="tool-link">Baca Selengkapnya</a>
                </div>
                <div class="intro-card">
                    <div class="intro-image">
                        <img src="assets/intro-2.png" alt="intro" />
                    </div>
                    <h4>Diagnosa Sederhana</h4>
                    <p>Dapatkan gambaran awal tentang kondisi lambung Anda melalui fitur diagnosa cepat dan mudah.</p>
                    <a href="_Diagnosa/diagnosa.php" class="tool-link">Coba Sekarang</a>
                </div>
                <div class="intro-card">
                    <div class="intro-image">
                        <img src="assets/intro-3.png" alt="intro" />
                    </div>
                    <h4>Saran Pola Hidup & Nutrisi</h4>
                    <p>Temukan rekomendasi pola hidup dan nutrisi untuk menjaga lambung Anda tetap sehat setiap hari.</p>
                    <a href="_Lifestyle/lifestyle.php" class="tool-link">Baca Selengkapnya</a>
                </div>
            </div>
        </section>

        <!-- About Section -->
        <section class="section-container about-container" id="about">
            <p class="section-subheader">About Us</p>
            <h2 class="section-header">Yang Bisa Anda Dapatkan</h2>
            <div class="about-row">
                <div class="about-image">
                    <img src="assets/about-1.jpg" alt="about" />
                </div>
                <div class="about-content">
                    <span><img src="assets/about-1-icon.png" alt="about-icon" /></span>
                    <h4>Dukungan Kesehatan Lambung</h4>
                    <p>Kami menyediakan informasi dan panduan lengkap untuk membantu Anda memahami dan merawat kesehatan lambung.</p>
                </div>
            </div>
            <div class="about-row">
                <div class="about-image">
                    <img src="assets/about-6.jpg" alt="about" />
                </div>
                <div class="about-content">
                    <span><img src="assets/about-2-icon.png" alt="about-icon" /></span>
                    <h4>Diagnosa Awal yang Mudah</h4>
                    <p>Dengan alat diagnosa sederhana kami, Anda bisa mengetahui kondisi lambung secara cepat dan praktis.</p>
                </div>
            </div>
            <div class="about-row">
                <div class="about-image">
                    <img src="assets/about-3.jpg" alt="about" />
                </div>
                <div class="about-content">
                    <span><img src="assets/about-3-icon.png" alt="about-icon" /></span>
                    <h4>Gaya Hidup Sehat</h4>
                    <p>Kami menawarkan saran pola makan dan kebiasaan sehari-hari untuk mendukung fungsi lambung yang optimal.</p>
                </div>
            </div>
        </section>
        
        <!-- Kumpulan Data Aktual tentang Lambung Section -->
        <section class="fact" id="store">
            <div class="section-container fact-container">
                <p class="section-subheader">Fakta Penting</p>
                <h2 class="section-header">Pentingnya Kesehatan Lambung</h2>
                <div class="swiper tanya-jawab-slider">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide">
                            <div class="fact-card">
                                <h4>15-20% Populasi Dunia Alami GERD</h4>
                                <p>GERD (asam lambung naik) memengaruhi 15-20% orang di dunia, terutama di negara Barat, akibat obesitas dan pola makan tidak sehat.</p>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="fact-card">
                                <h4>50% Populasi Dunia Terinfeksi H. pylori</h4>
                                <p>Bakteri H. pylori, penyebab utama ulkus lambung, menginfeksi lebih dari 50% populasi dunia, meningkatkan risiko kanker lambung.</p>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="fact-card">
                                <h4>Kanker Lambung: 769.000 Kematian</h4>
                                <p>Kanker lambung adalah penyebab kematian akibat kanker ke-5 di dunia, dengan 769.000 kematian per tahun, tertinggi di Asia Timur.</p>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="fact-card">
                                <h4>70-80% Pasien: Stres Perburuk Gejala</h4>
                                <p>Stres memperburuk gejala lambung pada 70-80% pasien, meningkatkan produksi asam lambung dan memperlambat pencernaan.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Service Section -->
        <section class="section-container service-container" id="service">
            <p class="section-subheader">Service</p>
            <h2 class="section-header">Layanan Yang Kami Sediakan</h2>
            <div class="service-flex">
                <div class="service-card">
                    <div>
                        <img src="assets/service-1.png" alt="service" />
                    </div>
                    <p>AI Chatbot</p>
                </div>
                <div class="service-card">
                    <div>
                        <img src="assets/service-2.png" alt="service" />
                    </div>
                    <p>Edukasi Kesehatan</p>
                </div>
                <div class="service-card">
                    <div>
                        <img src="assets/service-3.png" alt="service" />
                    </div>
                    <p>Saran Nutrisi</p>
                </div>
                <div class="service-card">
                    <div>
                        <img src="assets/about-2-icon.png" alt="service" />
                    </div>
                    <p>Diagnosa Awal</p>
                </div>
                <div class="service-card">
                    <div>
                        <img src="assets/about-3-icon.png" alt="service" />
                    </div>
                    <p>Panduan Pola Hidup</p>
                </div>
            </div>
        </section>

        <!-- Client Section -->
        <section class="client">
            <div class="section-container client-container">
                <p class="section-subheader">Testimoni</p>
                <h2 class="section-header">Kata Pengguna Tentang Kami</h2>
                <div class="swiper">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide">
                            <div class="client-card">
                                <div class="client-details">
                                    <img src="assets/client-1.jpg" alt="client" />
                                    <div>
                                        <h4>Sarah Johnson</h4>
                                        <h5>18 Tahun</h5>
                                    </div>
                                </div>
                                <p>Bagian Edukasi di GastroCare sangat membantu! Saya jadi tahu penyebab maag saya dan cara mencegahnya dengan pola makan yang lebih baik.</p>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="client-card">
                                <div class="client-details">
                                    <img src="assets/client-2.jpg" alt="client" />
                                    <div>
                                        <h4>Michael Adams</h4>
                                        <h5>40 Tahun</h5>
                                    </div>
                                </div>
                                <p>Fitur Diagnosa Sederhana sangat praktis. Kuesioner singkatnya membantu saya tahu kondisi lambung saya dan dapat saran yang tepat!</p>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="client-card">
                                <div class="client-details">
                                    <img src="assets/client-3.jpg" alt="client" />
                                    <div>
                                        <h4>Emily Martinez</h4>
                                        <h5>22 Tahun</h5>
                                    </div>
                                </div>
                                <p>Saran Pola Hidup & Nutrisi di GastroCare bikin hidup saya lebih sehat. Daftar makanan sehat dan jadwal makan sangat membantu!</p>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="client-card">
                                <div class="client-details">
                                    <img src="assets/client-4.jpg" alt="client" />
                                    <div>
                                        <h4>Jason Lee</h4>
                                        <h5>35 Tahun</h5>
                                    </div>
                                </div>
                                <p>AI Chatbot GastroCare luar biasa! Saya tanya tentang gejala lambung saya, dan jawabannya sangat informatif dan fokus pada lambung.</p>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="client-card">
                                <div class="client-details">
                                    <img src="assets/client-5.jpg" alt="client" />
                                    <div>
                                        <h4>David Thompson</h4>
                                        <h5>28 Tahun</h5>
                                    </div>
                                </div>
                                <p>Trivia Mitos vs Fakta membuka mata saya! Ternyata minum susu tidak selalu baik untuk maag. Terima kasih, GastroCare!</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Footer Section -->
        <footer id="contact">
            <div class="section-container footer-container">
                <div class="footer-col">
                    <div class="footer-logo">
                        <a href="#">GastroCare</a>
                        <p>Gastrocare HQ, Tech Park <br>4th Floor, Surabaya, ID</p>
                        <ul class="footer-socials">
                        <li><a href="#"><i class="ri-facebook-fill"></i></a></li>
                        <li><a href="#"><i class="ri-twitter-fill"></i></a></li>
                        <li><a href="#"><i class="ri-youtube-fill"></i></a></li>
                        <li><a href="#"><i class="ri-instagram-fill"></i></a></li>
                    </ul>
                    </div>
                </div>
                <div class="footer-col">
                    <h4>Features</h4>
                    <ul class="footer-links">
                        <li><a href="#tools">Edukasi & Informasi</a></li>
                        <li><a href="#tools">Diagnosa Sederhana</a></li>
                        <li><a href="#tools">Pola Hidup & Nutrisi</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>About Us</h4>
                    <ul class="footer-links">
                        <li><a href="#">Privacy Policy</a></li>
                        <li><a href="#">Term of Use</a></li>
                        <li><a href="#">Disclaimer</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Newsletter</h4>
                    <p>Ikuti update terbaru dari kami, <br>untuk kesehatan lambung anda</p>
                    <form action="/">
                        <input type="text" placeholder="Email Anda" />
                        <button class="btn">
                            <i class="ri-arrow-right-line"></i>
                        </button>
                    </form>
                    <br />
                    
                </div>
            </div>
            <div class="footer-bar">
                Copyright © 2024 GastroCare. All rights reserved.
            </div>
        </footer>

        <!-- Chatbot -->
        <button id="chatbot-toggler">
            <span class="material-symbols-rounded">mode_comment</span>
            <span class="material-symbols-rounded">close</span>
        </button>

        <div class="chatbot-popup">
            <div class="chat-header">
                <div class="header-info">
                    <svg class="chatbot-logo" xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 1024 1024">
                        <path d="M738.3 287.6H285.7c-59 0-106.8 47.8-106.8 106.8v303.1c0 59 47.8 106.8 106.8 106.8h81.5v111.1c0 .7.8 1.1 1.4.7l166.9-110.6 41.8-.8h117.4l43.6-.4c59 0 106.8-47.8 106.8-106.8V394.5c0-59-47.8-106.9-106.8-106.9zM351.7 448.2c0-29.5 23.9-53.5 53.5-53.5s53.5 23.9 53.5 53.5-23.9 53.5-53.5 53.5-53.5-23.9-53.5-53.5zm157.9 267.1c-67.8 0-123.8-47.5-132.3-109h264.6c-8.6 61.5-64.5 109-132.3 109zm110-213.7c-29.5 0-53.5-23.9-53.5-53.5s23.9-53.5 53.5-53.5 53.5 23.9 53.5 53.5-23.9 53.5-53.5 53.5zM867.2 644.5V453.1h26.5c19.4 0 35.1 15.7 35.1 35.1v121.1c0 19.4-15.7 35.1-35.1 35.1h-26.5zM95.2 609.4V488.2c0-19.4 15.7-35.1 35.1-35.1h26.5v191.3h-26.5c-19.4 0-35.1-15.7-35.1-35.1zM561.5 149.6c0 23.4-15.6 43.3-36.9 49.7v44.9h-30v-44.9c-21.4-6.5-36.9-26.3-36.9-49.7 0-28.6 23.3-51.9 51.9-51.9s51.9 23.3 51.9 51.9z"></path>
                    </svg>
                    <h2 class="logo-text">GastroBot</h2>
                </div>
                <button id="close-chatbot" class="material-symbols-rounded">keyboard_arrow_down</button>
            </div>

            <!-- Chatbot Body -->
            <div class="chat-body">
                <div class="message bot-message">
                    <svg class="bot-avatar" xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 1024 1024">
                        <path d="M738.3 287.6H285.7c-59 0-106.8 47.8-106.8 106.8v303.1c0 59 47.8 106.8 106.8 106.8h81.5v111.1c0 .7.8 1.1 1.4.7l166.9-110.6 41.8-.8h117.4l43.6-.4c59 0 106.8-47.8 106.8-106.8V394.5c0-59-47.8-106.9-106.8-106.9zM351.7 448.2c0-29.5 23.9-53.5 53.5-53.5s53.5 23.9 53.5 53.5-23.9 53.5-53.5 53.5-53.5-23.9-53.5-53.5zm157.9 267.1c-67.8 0-123.8-47.5-132.3-109h264.6c-8.6 61.5-64.5 109-132.3 109zm110-213.7c-29.5 0-53.5-23.9-53.5-53.5s23.9-53.5 53.5-53.5 53.5 23.9 53.5 53.5-23.9 53.5-53.5 53.5zM867.2 644.5V453.1h26.5c19.4 0 35.1 15.7 35.1 35.1v121.1c0 19.4-15.7 35.1-35.1 35.1h-26.5zM95.2 609.4V488.2c0-19.4 15.7-35.1 35.1-35.1h26.5v191.3h-26.5c-19.4 0-35.1-15.7-35.1-35.1zM561.5 149.6c0 23.4-15.6 43.3-36.9 49.7v44.9h-30v-44.9c-21.4-6.5-36.9-26.3-36.9-49.7 0-28.6 23.3-51.9 51.9-51.9s51.9 23.3 51.9 51.9z"></path>
                    </svg>
                    <div class="message-text">Hai, User 👋 <br>Ada yang bisa saya bantu?</div>
                </div>
            </div>

            <!-- Chatbot Footer -->
            <div class="chat-footer">
                <form action="#" class="chat-form">
                    <textarea placeholder="Ask anything . . ." class="message-input" required></textarea>
                    <div class="chat-controls">
                        <button type="submit" id="send-message" class="material-symbols-rounded">arrow_upward</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Pop Up -->
        <div id="customPopup" class="popup">
            <div class="popup-content">
                <h3 id="popupTitle"></h3>
                <p id="popupMessage"></p>
                <button id="popupClose">Tutup</button>
            </div>
        </div>

        <!-- External Scripts -->
        <script src="https://unpkg.com/scrollreveal"></script>
        <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
        <script src="main.js"></script>
        <script src="_Chatbot/chatbot.js"></script>

        <!-- Cek session login dan set sessionStorage -->
        <script>
        // Cek status login dari server (PHP session)
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