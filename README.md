# GastroCare (PHP & Web)

Aplikasi **sistem pakar berbasis web** untuk kesehatan lambung yang menyediakan fitur komprehensif mulai dari edukasi, diagnosis awal berbasis kuesioner, panduan gaya hidup, hingga chatbot AI. Aplikasi ini dirancang dengan dua peran utama: **pengguna** yang mencari informasi dan **admin** yang mengelola seluruh konten.
![Image](https://github.com/user-attachments/assets/5d2c4afa-1cc1-42b1-ba5d-01808904f164)
![Image](https://github.com/user-attachments/assets/adf183a6-c124-4c6d-b9c6-4f6122eb2022)
![Image](https://github.com/user-attachments/assets/3a487517-3bde-4cfe-b69f-2a3993cfd36c)

## Fitur Aplikasi

### Pengguna (User)
-   **Otentikasi Aman:** Sistem registrasi dan login untuk personalisasi pengalaman.
-   **Diagnosis Interaktif:** Mengikuti kuesioner 6 sesi yang terstruktur untuk mengidentifikasi gejala, kebiasaan, riwayat kesehatan, hingga faktor psikologis.
-   **Hasil Diagnosis Personal:** Menerima hasil berupa skor, skala kondisi (Aman, Waspada, Kritis), dan penjelasan mendalam berdasarkan jawaban yang diberikan.
-   **Portal Edukasi:** Mempelajari berbagai jenis penyakit lambung, penyebab, gejala, cara pencegahan, pengobatan, serta trivia mitos vs. fakta.
-   **Panduan Gaya Hidup:** Mendapatkan rekomendasi makanan sehat (bisa difilter per kategori), jadwal makan interaktif, serta tips manajemen stres lengkap dengan video panduan dari YouTube.
-   **AI Chatbot (GastroBot):** Berinteraksi langsung dengan chatbot cerdas yang dilatih untuk menjawab pertanyaan-pertanyaan spesifik seputar kesehatan lambung.

### Admin
-   **Dashboard Terpusat:** Mengakses panel admin yang aman untuk mengelola seluruh aspek aplikasi.
-   **Manajemen Konten Edukasi:** Kontrol penuh (CRUD) atas konten edukasi, termasuk jenis penyakit, daftar penyebab/gejala, dan mitos vs. fakta.
-   **Manajemen Modul Diagnosa:** Kemampuan untuk menambah, mengubah, atau menghapus pertanyaan dan bobot skor pada setiap sesi kuesioner.
-   **Pemantauan Hasil Pengguna:** Melihat riwayat dan detail hasil diagnosis dari seluruh pengguna yang terdaftar.
-   **Manajemen Konten Lifestyle:** Mengelola daftar makanan sehat dan video panduan manajemen stres yang akan ditampilkan kepada pengguna.

## Teknologi yang Digunakan
-   **Frontend:** HTML, CSS, JavaScript (ES6+)
-   **Backend:** PHP
-   **Database:** MySQL
-   **APIs & Libraries:**
    -   Google Gemini API (untuk AI Chatbot)
    -   Swiper.js (untuk slider/carousel)
    -   ScrollReveal.js (untuk animasi saat scroll)

## 📁 Struktur File
-   `main.php` – Halaman utama (landing page) untuk pengguna.
-   `admin.php` – Halaman dashboard utama untuk admin.
-   `db.php` – File konfigurasi dan koneksi ke database.
-   `logout.php` - Skrip untuk menghancurkan sesi dan logout pengguna.
-   `_Login/` – Modul otentikasi (login & registrasi).
-   `_Diagnosa/` – Modul untuk alur proses, hasil, dan manajemen diagnosis.
-   `_Education/` – Modul untuk konten dan manajemen halaman edukasi.
-   `_Lifestyle/` – Modul untuk saran gaya hidup, nutrisi, dan manajemennya.
-   `_Chatbot/` – Komponen frontend dan logika untuk fitur AI Chatbot.
-   `_Template/` - Berisi file CSS dan JS yang digunakan secara umum di berbagai halaman.

## Cara Menjalankan Aplikasi
1.  **Prasyarat:**
    -   Pastikan Anda memiliki web server lokal seperti **XAMPP** atau **WAMP** yang sudah terinstal.

2.  **Clone Repository:**
    -   Buka terminal atau Git Bash, lalu clone repository ini.
    ```bash
    git clone [https://github.com/rohiim08/gastrocare.git](https://github.com/rohiim08/gastrocare.git)
    ```

3.  **Setup Database:**
    -   Jalankan modul Apache dan MySQL dari XAMPP Control Panel.
    -   Buka `phpMyAdmin` (`http://localhost/phpmyadmin`).
    -   Buat database baru dengan nama `gastrocare_db`.
    -   Impor file `.sql` (yang berisi struktur tabel dan data) ke dalam database `gastrocare_db`. Anda perlu mengekspor file ini dari lingkungan pengembangan asli Anda.

4.  **Konfigurasi Aplikasi:**
    -   **Koneksi Database:** Buka file `db.php`, pastikan kredensial (`$host`, `$user`, `$password`, `$dbname`) sudah sesuai.
    -   **API Key:** Buka file `_Chatbot/chatbot.js` dan ganti placeholder `YOUR_API_KEY_HERE` dengan API Key Google Gemini Anda yang valid.
    -   **(PENTING) Perbaiki Path File:** Beberapa file (contohnya di dalam folder `_Diagnosa/` dan `_Education/`) mungkin menggunakan path absolut seperti `require_once 'C:\xampp\htdocs\gastrocare\db.php'`. Ganti path tersebut menjadi **path relatif** (contoh: `require_once '../db.php';`) agar proyek berjalan dengan benar.

5.  **Jalankan Proyek:**
    -   Pindahkan folder `gastrocare` ke dalam direktori `htdocs` di dalam folder instalasi XAMPP.
    -   Buka browser dan akses: `http://localhost/gastrocare/main.php`

### Akun Demo
-   **Admin:** Untuk mengakses dashboard admin, login menggunakan akun yang memiliki `role` = `admin` di tabel `users`. Jika belum ada, Anda bisa membuatnya secara manual di database.
-   **Pengguna:** Lakukan registrasi melalui halaman login untuk membuat akun pengguna baru.
