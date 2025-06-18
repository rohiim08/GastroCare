<?php
require 'db.php';
session_start();

// Hanya admin yang bisa mengakses
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: _Login/login.php');
    exit;
}

// Proses Create atau Update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] == 'add_makanan' || $_POST['action'] == 'edit_makanan') {
            $id_makanan = isset($_POST['id_makanan']) ? $_POST['id_makanan'] : null;
            $jenis = $_POST['jenis'];
            $gambar = $_POST['gambar'];
            $menu = $_POST['menu'];
            $deskripsi = $_POST['deskripsi'];

            if ($_POST['action'] == 'add_makanan') {
                $query = "INSERT INTO makanan (jenis, gambar, menu, deskripsi) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($query);
                $stmt->bind_param('ssss', $jenis, $gambar, $menu, $deskripsi);
            } else {
                $query = "UPDATE makanan SET jenis = ?, gambar = ?, menu = ?, deskripsi = ? WHERE id_makanan = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param('ssssi', $jenis, $gambar, $menu, $deskripsi, $id_makanan);
            }

            if ($stmt->execute()) {
                echo "<script>alert('Data makanan berhasil disimpan!'); window.location.href='admin_lifestyle.php';</script>";
            } else {
                echo "<script>alert('Gagal menyimpan data makanan.');</script>";
            }
            $stmt->close();
        } elseif ($_POST['action'] == 'add_stres' || $_POST['action'] == 'edit_stres') {
            $id_stres = isset($_POST['id_stres']) ? $_POST['id_stres'] : null;
            $judul = $_POST['judul'];
            $link_video = $_POST['link_video'];

            if ($_POST['action'] == 'add_stres') {
                $query = "INSERT INTO manajemen_stres (judul, link_video) VALUES (?, ?)";
                $stmt = $conn->prepare($query);
                $stmt->bind_param('ss', $judul, $link_video);
            } else {
                $query = "UPDATE manajemen_stres SET judul = ?, link_video = ? WHERE id_stres = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param('ssi', $judul, $link_video, $id_stres);
            }

            if ($stmt->execute()) {
                echo "<script>alert('Data manajemen stres berhasil disimpan!'); window.location.href='admin_lifestyle.php';</script>";
            } else {
                echo "<script>alert('Gagal menyimpan data manajemen stres.');</script>";
            }
            $stmt->close();
        }
    }
}

// Proses Delete
if (isset($_GET['delete_makanan'])) {
    $id_makanan = $_GET['delete_makanan'];
    $query = "DELETE FROM makanan WHERE id_makanan = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $id_makanan);
    if ($stmt->execute()) {
        echo "<script>alert('Data makanan berhasil dihapus!'); window.location.href='admin_lifestyle.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus data makanan.');</script>";
    }
    $stmt->close();
}

if (isset($_GET['delete_stres'])) {
    $id_stres = $_GET['delete_stres'];
    $query = "DELETE FROM manajemen_stres WHERE id_stres = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $id_stres);
    if ($stmt->execute()) {
        echo "<script>alert('Data manajemen stres berhasil dihapus!'); window.location.href='admin_lifestyle.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus data manajemen stres.');</script>";
    }
    $stmt->close();
}

// Ambil data untuk ditampilkan
$makanan_result = $conn->query("SELECT * FROM makanan");
$stres_result = $conn->query("SELECT * FROM manajemen_stres");

// Hitung statistik
$total_makanan = $conn->query("SELECT COUNT(*) as total FROM makanan")->fetch_assoc()['total'];
$total_stres = $conn->query("SELECT COUNT(*) as total FROM manajemen_stres")->fetch_assoc()['total'];

// Ambil data untuk edit (jika ada)
$edit_makanan = null;
$edit_stres = null;
if (isset($_GET['edit_makanan'])) {
    $id_makanan = $_GET['edit_makanan'];
    $query = "SELECT * FROM makanan WHERE id_makanan = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $id_makanan);
    $stmt->execute();
    $edit_makanan = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}
if (isset($_GET['edit_stres'])) {
    $id_stres = $_GET['edit_stres'];
    $query = "SELECT * FROM manajemen_stres WHERE id_stres = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $id_stres);
    $stmt->execute();
    $edit_stres = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Lifestyle Management - GastroCare</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0&family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@48,400,1,0">
    <style>
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
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Styling HTML dan Body */
        html,
        body {
            scroll-behavior: smooth;
            font-family: 'Poppins', sans-serif;
        }

        /* Navigasi dari lifestyle.php */
        nav {
            position: fixed;
            top: 0;
            width: 100%;
            background: var(--white);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }

        .nav-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 2rem;
            max-width: var(--max-width);
            margin: auto;
        }

        .nav-logo img {
            height: 40px;
        }

        .nav-profile {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            position: relative;
        }

        .nav-profile img {
            width: 30px;
            height: 30px;
            border-radius: 50%;
        }

        .nav-profile span {
            font-weight: 500;
            color: var(--text-dark);
        }

        .profile-dropdown {
            display: none;
            position: absolute;
            top: 100%;
            right: 0;
            background: var(--white);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            padding: 0.5rem;
            min-width: 150px;
        }

        .profile-dropdown.show {
            display: block;
        }

        .logout-button {
            width: 100%;
            padding: 0.5rem;
            background: var(--secondary-color);
            color: var(--text-white);
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-align: left;
        }

        .logout-button:hover {
            background: var(--primary-color);
        }

        .hamburger {
            display: none;
            flex-direction: column;
            gap: 5px;
            cursor: pointer;
        }

        .hamburger span {
            width: 25px;
            height: 3px;
            background: var(--text-dark);
        }

        .mobile-menu {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            height: 100%;
            background: var(--white);
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            transform: translateX(-100%);
            transition: transform 0.3s ease;
        }

        .mobile-menu.active {
            transform: translateX(0);
        }

        .profile-info {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        .profile-info img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }

        .logout-button-mobile {
            width: 100%;
            padding: 0.75rem;
            background: var(--secondary-color);
            color: var(--text-white);
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .nav-close img {
            width: 24px;
            height: 24px;
        }

        @media (max-width: 768px) {
            .hamburger {
                display: flex;
            }

            .nav-profile {
                display: none;
            }
        }

        /* Styling Kontainer Utama */
        .container {
            max-width: var(--max-width);
            padding: 2rem 1rem;
            margin: auto;
            margin-top: 80px;
            /* Adjust for fixed nav */
        }

        .dashboard-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .dashboard-header h1 {
            font-size: 2.5rem;
            font-weight: 600;
            color: var(--text-dark);
            line-height: 3.5rem;
        }

        .dashboard-header p {
            font-size: 1.1rem;
            color: var(--text-light);
        }

        /* Styling Statistik */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 3rem;
        }

        .stat-card {
            background-color: var(--white);
            padding: 2rem;
            border-radius: 1rem;
            border: 2px solid var(--secondary-color);
            text-align: center;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(26, 142, 196, 0.15);
        }

        .stat-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: var(--secondary-color);
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }

        .stat-label {
            font-size: 1rem;
            color: var(--text-light);
        }

        /* Styling Seksi Konten */
        .content-section {
            background-color: var(--white);
            border-radius: 1rem;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }

        .section-title {
            color: var(--text-dark);
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Styling Form */
        .form-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .form-group label {
            font-weight: 600;
            color: var(--text-dark);
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            padding: 0.75rem;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--secondary-color);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }

        /* Styling Tombol */
        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 25px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        .btn-primary {
            background-color: var(--secondary-color);
            color: var(--text-white);
        }

        .btn-primary:hover {
            background-color: var(--primary-color);
            transform: translateY(-2px);
        }

        .btn-success {
            background-color: #28a745;
            color: var(--text-white);
        }

        .btn-success:hover {
            background-color: #218838;
            transform: translateY(-2px);
        }

        .btn-danger {
            background-color: #dc3545;
            color: var(--text-white);
        }

        .btn-danger:hover {
            background-color: #c82333;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background-color: #6c757d;
            color: var(--text-white);
        }

        .btn-secondary:hover {
            background-color: #5a6268;
            transform: translateY(-2px);
        }

        .btn-sm {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
        }

        /* Styling Tabel */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
            background: var(--white);
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        }

        .data-table th {
            background-color: var(--primary-color);
            color: var(--text-white);
            padding: 1rem;
            text-align: left;
            font-weight: 600;
        }

        .data-table td {
            padding: 1rem;
            border-bottom: 1px solid #e0e0e0;
        }

        .data-table tr:hover {
            background-color: #f8f9fa;
        }

        .food-image {
            width: 80px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }

        /* Styling Modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background: var(--white);
            margin: 5% auto;
            padding: 2rem;
            border-radius: 15px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .close {
            color: var(--text-light);
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover {
            color: var(--text-dark);
        }

        /* Styling Badge */
        .badge {
            background: var(--secondary-color);
            color: var(--text-white);
            padding: 0.25rem 0.5rem;
            border-radius: 15px;
            font-size: 0.875rem;
        }

        /* Footer dari lifestyle.css dan _Template/template.css */
        .footer-content {
            background: var(--primary-color);
            color: var(--text-white);
            padding: 2rem 0;
            margin-top: 100px;
        }

        .footer-content .container {
            max-width: var(--max-width);
            margin: auto;
            padding: 0 1rem;
            text-align: center;
        }

        .footer-content-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .footer-content-logo {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .footer-content-main {
            margin-bottom: 1.5rem;
            max-width: 600px;
        }

        .footer-content-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .footer-content-desc {
            font-size: 0.9rem;
            line-height: 1.6;
        }

        .footer-content-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 1rem;
            width: 100%;
        }

        .copyright {
            font-size: 0.9rem;
            text-align: center;
        }


        /* Media Queries */
        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }

            .dashboard-header h1 {
                font-size: 2rem;
            }

            .form-container {
                grid-template-columns: 1fr;
            }

            .footer-content-title {
                font-size: 1rem;
            }

            .footer-content-desc {
                font-size: 0.85rem;
            }

            .footer-content-logo {
                font-size: 1.3rem;
            }

            .footer-content-title {
                font-size: 1rem;
            }

            .footer-content-desc {
                font-size: 0.85rem;
            }
        }
    </style>
</head>

<body>
    <!-- Navigasi dari lifestyle.php -->
    <nav>
        <div class="nav-header">
            <a href="admin.php" class="nav-close"><img src="assets/close.png" id="close-btn" alt="Tutup" /></a>
            <a href="admin.php" class="nav-logo"><img src="assets/logo.png" alt="Logo"></a>

            <div class="nav-profile">
                <img src="assets/user.png" alt="profile">
                <span><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                <i class='bx bx-caret-down'></i>
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
                <span><?php echo htmlspecialchars($_SESSION['username']); ?></span>
            </div>

            <!-- Mobile Logout Button -->
            <button id="logout-btn-mobile" class="logout-button-mobile">Logout</button>
        </ul>
    </nav>

    <div class="container">
        <!-- Dashboard Header -->
        <div class="dashboard-header">
            <h1>Kelola Lifestyle</h1>
            <p>Kelola data manajemen stres dan makanan sehat dengan mudah</p>
        </div>

        <!-- Statistics -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-utensils"></i>
                </div>
                <div class="stat-number"><?php echo $total_makanan; ?></div>
                <div class="stat-label">Total Makanan</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-heart"></i>
                </div>
                <div class="stat-number"><?php echo $total_stres; ?></div>
                <div class="stat-label">Video Manajemen Stres</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="stat-number"><?php echo $total_makanan + $total_stres; ?></div>
                <div class="stat-label">Total Konten</div>
            </div>
        </div>

        <!-- Form Makanan -->
        <div class="content-section">
            <h2 class="section-title">
                <i class="fas fa-utensils"></i>
                <?php echo $edit_makanan ? 'Edit' : 'Tambah'; ?> Makanan Sehat
            </h2>
            <form method="POST">
                <input type="hidden" name="action" value="<?php echo $edit_makanan ? 'edit_makanan' : 'add_makanan'; ?>">
                <?php if ($edit_makanan): ?>
                    <input type="hidden" name="id_makanan" value="<?php echo $edit_makanan['id_makanan']; ?>">
                <?php endif; ?>

                <div class="form-container">
                    <div class="form-group">
                        <label for="jenis">Jenis Makanan</label>
                        <select name="jenis" id="jenis" required>
                            <option value="Sayur" <?php echo $edit_makanan && $edit_makanan['jenis'] == 'Sayur' ? 'selected' : ''; ?>>Sayur</option>
                            <option value="Ikan" <?php echo $edit_makanan && $edit_makanan['jenis'] == 'Ikan' ? 'selected' : ''; ?>>Ikan</option>
                            <option value="Daging" <?php echo $edit_makanan && $edit_makanan['jenis'] == 'Daging' ? 'selected' : ''; ?>>Daging</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="menu">Nama Menu</label>
                        <input type="text" name="menu" id="menu" value="<?php echo $edit_makanan ? $edit_makanan['menu'] : ''; ?>" placeholder="Masukkan nama menu" required>
                    </div>

                    <div class="form-group">
                        <label for="gambar">Link Gambar</label>
                        <input type="text" name="gambar" id="gambar" value="<?php echo $edit_makanan ? $edit_makanan['gambar'] : ''; ?>" placeholder="assets/sayur1.png" required>
                    </div>

                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label for="deskripsi">Deskripsi</label>
                        <textarea name="deskripsi" id="deskripsi" placeholder="Masukkan deskripsi makanan" required><?php echo $edit_makanan ? $edit_makanan['deskripsi'] : ''; ?></textarea>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    <?php echo $edit_makanan ? 'Update' : 'Tambah'; ?> Makanan
                </button>
                <?php if ($edit_makanan): ?>
                    <a href="admin_lifestyle.php" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Batal
                    </a>
                <?php endif; ?>
            </form>
        </div>

        <!-- Tabel Makanan -->
        <div class="content-section">
            <h2 class="section-title">
                <i class="fas fa-list"></i>
                Daftar Makanan Sehat
            </h2>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Jenis</th>
                        <th>Gambar</th>
                        <th>Menu</th>
                        <th>Deskripsi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $makanan_result->fetch_assoc()): ?>
                        <tr>
                            <td>
                                <div class="badge"><?php echo $row['jenis']; ?></div>
                            </td>
                            <td>
                                <img src="<?php echo $row['gambar']; ?>" alt="<?php echo $row['menu']; ?>" class="food-image">
                            </td>
                            <td><strong><?php echo $row['menu']; ?></strong></td>
                            <td><?php echo substr($row['deskripsi'], 0, 100) . '...'; ?></td>
                            <td>
                                <div class="action-buttons">
                                    <a href="?edit_makanan=<?php echo $row['id_makanan']; ?>" class="btn btn-success btn-sm">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a href="?delete_makanan=<?php echo $row['id_makanan']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus data ini?')">
                                        <i class="fas fa-trash"></i> Hapus
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- Form Manajemen Stres -->
        <div class="content-section">
            <h2 class="section-title">
                <i class="fas fa-heart"></i>
                <?php echo $edit_stres ? 'Edit' : 'Tambah'; ?> Manajemen Stres
            </h2>
            <form method="POST">
                <input type="hidden" name="action" value="<?php echo $edit_stres ? 'edit_stres' : 'add_stres'; ?>">
                <?php if ($edit_stres): ?>
                    <input type="hidden" name="id_stres" value="<?php echo $edit_stres['id_stres']; ?>">
                <?php endif; ?>

                <div class="form-container">
                    <div class="form-group">
                        <label for="judul">Judul Video</label>
                        <input type="text" name="judul" id="judul" value="<?php echo $edit_stres ? $edit_stres['judul'] : ''; ?>" placeholder="Masukkan judul video" required>
                    </div>

                    <div class="form-group">
                        <label for="link_video">Link Video</label>
                        <input type="url" name="link_video" id="link_video" value="<?php echo $edit_stres ? $edit_stres['link_video'] : ''; ?>" placeholder="https://youtube.com/..." required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    <?php echo $edit_stres ? 'Update' : 'Tambah'; ?> Video
                </button>
                <?php if ($edit_stres): ?>
                    <a href="admin_lifestyle.php" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Batal
                    </a>
                <?php endif; ?>
            </form>
        </div>

        <!-- Tabel Manajemen Stres -->
        <div class="content-section">
            <h2 class="section-title">
                <i class="fas fa-video"></i>
                Daftar Video Manajemen Stres
            </h2>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Link Video</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $stres_result->fetch_assoc()): ?>
                        <tr>
                            <td><strong><?php echo $row['judul']; ?></strong></td>
                            <td>
                                <a href="<?php echo $row['link_video']; ?>" target="_blank" class="btn btn-primary btn-sm">
                                    <i class="fas fa-external-link-alt"></i> Lihat Video
                                </a>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="?edit_stres=<?php echo $row['id_stres']; ?>" class="btn btn-success btn-sm">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a href="?delete_stres=<?php echo $row['id_stres']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus data ini?')">
                                        <i class="fas fa-trash"></i> Hapus
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Footer dari lifestyle.php -->
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

    <script src="https://unpkg.com/scrollreveal"></script>
    <script src="../main.js"></script>
    <script src="../_Template/profile.js"></script>
    <script>
        // Smooth animations
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.stat-card, .content-section');

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, {
                root: null,
                rootMargin: '0px',
                threshold: 0.1
            });

            cards.forEach(card => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(30px)';
                card.style.transition = 'all 0.6s ease';
                observer.observe(card);
            });
        });

        // Form validation
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function(e) {
                const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
                let isValid = true;

                inputs.forEach(input => {
                    if (!input.value.trim()) {
                        input.style.borderColor = '#e74c3c';
                        isValid = false;
                    } else {
                        input.style.borderColor = '#e0e0e0';
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                    alert('Mohon lengkapi semua field yang diperlukan!');
                }
            });
        });

        // Profile dropdown dan hamburger menu
        const profile = document.querySelector('.nav-profile');
        const dropdown = document.querySelector('#profile-dropdown');
        const hamburger = document.querySelector('.hamburger');
        const mobileMenu = document.querySelector('.mobile-menu');
        const logoutBtn = document.querySelector('#logout-btn');
        const logoutBtnMobile = document.querySelector('#logout-btn-mobile');

        if (profile && dropdown) {
            profile.addEventListener('click', () => {
                dropdown.classList.toggle('show');
            });
        }

        if (hamburger && mobileMenu) {
            hamburger.addEventListener('click', () => {
                mobileMenu.classList.toggle('active');
            });
        }

        if (logoutBtn) {
            logoutBtn.addEventListener('click', () => {
                window.location.href = 'logout.php';
            });
        }

        if (logoutBtnMobile) {
            logoutBtnMobile.addEventListener('click', () => {
                window.location.href = 'logout.php';
            });
        }

        // Fetch user data
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
                if (typeof updateProfile === 'function') updateProfile();
            });
    </script>
</body>

</html>