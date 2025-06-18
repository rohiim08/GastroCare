<?php
session_start();
include '../db.php'; // Include database connection

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../_Login/login.php');
    exit;
}

$message = ''; // For success/error messages
$section_types = ['penyebab', 'gejala', 'pencegahan', 'pengobatan']; // Allowed types

// Re-open connection if closed previously
if ($conn->connect_error) {
    $conn = new mysqli($host, $user, $password, $dbname);
    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }
}

// --- CRUD Operations Logic for List Items --- 

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {

    // ADD Action
    if ($_POST['action'] === 'add') {
        $section_type = trim($_POST['section_type']);
        $item_text = trim($_POST['item_text']);
        $display_order = filter_input(INPUT_POST, 'display_order', FILTER_VALIDATE_INT) ?: 0;

        if (in_array($section_type, $section_types) && !empty($item_text)) {
            $stmt = $conn->prepare("INSERT INTO education_list_items (section_type, item_text, display_order) VALUES (?, ?, ?)");
            if ($stmt) {
                $stmt->bind_param('ssi', $section_type, $item_text, $display_order);
                if ($stmt->execute()) {
                    $message = "Item daftar berhasil ditambahkan.";
                } else {
                    $message = "Error: Gagal menambahkan item daftar. " . $stmt->error;
                }
                $stmt->close();
            } else {
                 $message = "Error: Gagal menyiapkan statement. " . $conn->error;
            }
        } else {
            $message = "Error: Tipe Bagian atau Teks Item tidak valid.";
        }
        header("Location: manage_list_items.php?message=" . urlencode($message));
        exit;
    }

    // UPDATE Action
    if ($_POST['action'] === 'update') {
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $section_type = trim($_POST['section_type']);
        $item_text = trim($_POST['item_text']);
        $display_order = filter_input(INPUT_POST, 'display_order', FILTER_VALIDATE_INT) ?: 0;

        if ($id && in_array($section_type, $section_types) && !empty($item_text)) {
            $stmt = $conn->prepare("UPDATE education_list_items SET section_type = ?, item_text = ?, display_order = ? WHERE id = ?");
            if ($stmt) {
                $stmt->bind_param('ssii', $section_type, $item_text, $display_order, $id);
                if ($stmt->execute()) {
                    $message = "Item daftar berhasil diperbarui.";
                } else {
                    $message = "Error: Gagal memperbarui item daftar. " . $stmt->error;
                }
                $stmt->close();
            } else {
                $message = "Error: Gagal menyiapkan statement pembaruan. " . $conn->error;
            }
        } else {
            $message = "Error: ID, Tipe Bagian, atau Teks Item tidak valid untuk pembaruan.";
        }
        header("Location: manage_list_items.php?message=" . urlencode($message));
        exit;
    }

    // DELETE Action
    if ($_POST['action'] === 'delete') {
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        if ($id) {
            $stmt = $conn->prepare("DELETE FROM education_list_items WHERE id = ?");
            if ($stmt) {
                $stmt->bind_param('i', $id);
                if ($stmt->execute()) {
                    $message = "Item daftar berhasil dihapus.";
                } else {
                    $message = "Error: Gagal menghapus item daftar. " . $stmt->error;
                }
                $stmt->close();
            } else {
                 $message = "Error: Gagal menyiapkan statement penghapusan. " . $conn->error;
            }
        } else {
            $message = "Error: ID tidak valid.";
        }
        header("Location: manage_list_items.php?message=" . urlencode($message));
        exit;
    }
}

// --- Fetch existing data --- 
$items_by_section = [];
foreach ($section_types as $type) {
    $items_by_section[$type] = [];
}

$sql = "SELECT id, section_type, item_text, display_order FROM education_list_items ORDER BY section_type ASC, display_order ASC, id ASC"; 
$result = $conn->query($sql);

if ($result === false) {
    $message = "Error saat mengambil data: " . $conn->error;
} elseif ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        if (isset($items_by_section[$row['section_type']])) {
            $items_by_section[$row['section_type']][] = $row;
        }
    }
}

// Get message from redirect
if (isset($_GET['message'])) {
    $message = htmlspecialchars(urldecode($_GET['message']));
}

// Determine item to edit
$edit_item = null;
if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
    $edit_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    if ($edit_id) {
        // Find the item across all sections
        foreach ($items_by_section as $section) {
            foreach ($section as $item) {
                 if ($item['id'] == $edit_id) {
                    $edit_item = $item;
                    break 2; // Break both loops
                }
            }
        }
        if (!$edit_item) {
             $message = "Error: Item untuk diedit tidak ditemukan.";
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Kelola Daftar Item Edukasi</title>
    <link rel="stylesheet" href="../_Template/template.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <style>
        /* Basic Admin Page Styling */
        body { font-family: sans-serif; background-color: #f4f7f6; margin: 0; padding: 20px; }
        .admin-container { max-width: 1000px; margin: 2rem auto; padding: 2rem; background-color: #fff; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1, h2, h3 { color: #0E416C; text-align: center; margin-bottom: 1.5rem; }
        h3 { text-align: left; margin-top: 2rem; border-bottom: 1px solid #eee; padding-bottom: 0.5rem; color: #1A8EC4; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 1rem; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; vertical-align: middle; }
        th { background-color: #e9ecef; color: #495057; }
        tr:nth-child(even) { background-color: #f8f9fa; }
        .action-buttons a, .action-buttons button { display: inline-block; padding: 5px 8px; margin: 2px; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; font-size: 0.85em; text-align: center; }
        .edit-btn { background-color: #ffc107; color: #333; }
        .edit-btn:hover { background-color: #e0a800; }
        .delete-btn { background-color: #dc3545; color: white; }
        .delete-btn:hover { background-color: #c82333; }
        .crud-form { padding: 1.5rem; border: 1px solid #ddd; border-radius: 5px; background-color: #f8f9fa; margin-bottom: 2rem; }
        .crud-form label { display: block; margin-bottom: 0.5rem; font-weight: bold; color: #495057; }
        .crud-form select, .crud-form input[type="text"], .crud-form input[type="number"], .crud-form textarea { width: 100%; padding: 10px; margin-bottom: 1rem; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .crud-form button { background-color: #28a745; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; font-size: 1em; }
        .crud-form button.update-btn { background-color: #007bff; }
        .crud-form button:hover { opacity: 0.9; }
        .back-link { display: block; margin-bottom: 1rem; text-align: left; color: #1A8EC4; text-decoration: none; font-weight: bold; }
        .back-link:hover { text-decoration: underline; }
        .message { padding: 1rem; margin-bottom: 1rem; border-radius: 5px; text-align: center; font-weight: bold; }
        .message.success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .message.error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .no-data { text-align: center; color: #6c757d; padding: 1rem; }
    </style>
</head>
<body>

    <div class="admin-container">
        <a href="admin_education_sections.php" class="back-link">&larr; Kembali ke Kelola Bagian Edukasi</a>
        <h1>Kelola Daftar Item (Penyebab, Gejala, dll.)</h1>

        <?php if (!empty($message)): ?>
            <div class="message <?php echo (strpos($message, 'Error:') === 0 || strpos($message, 'Gagal') !== false) ? 'error' : 'success'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <!-- Form for Adding or Editing -->
        <div class="crud-form">
            <h2><?php echo $edit_item ? 'Edit Item Daftar' : 'Tambah Item Daftar Baru'; ?></h2>
            <form action="manage_list_items.php" method="POST">
                <input type="hidden" name="action" value="<?php echo $edit_item ? 'update' : 'add'; ?>">
                <?php if ($edit_item): ?>
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($edit_item['id']); ?>">
                <?php endif; ?>
                
                <div>
                    <label for="section_type">Tipe Bagian:</label>
                    <select id="section_type" name="section_type" required>
                        <option value="" disabled <?php echo !$edit_item ? 'selected' : ''; ?>>-- Pilih Tipe Bagian --</option>
                        <?php foreach ($section_types as $type): ?>
                            <option value="<?php echo $type; ?>" <?php echo ($edit_item && $edit_item['section_type'] === $type) ? 'selected' : ''; ?>>
                                <?php echo ucfirst($type); // Capitalize first letter ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label for="item_text">Teks Item:</label>
                    <textarea id="item_text" name="item_text" rows="3" required><?php echo htmlspecialchars($edit_item['item_text'] ?? ''); ?></textarea>
                </div>
                 <div>
                    <label for="display_order">Urutan Tampil (Angka kecil tampil duluan):</label>
                    <input type="number" id="display_order" name="display_order" value="<?php echo htmlspecialchars($edit_item['display_order'] ?? 0); ?>" min="0">
                </div>
                <button type="submit" class="<?php echo $edit_item ? 'update-btn' : ''; ?>">
                    <?php echo $edit_item ? 'Simpan Perubahan' : 'Tambah Item'; ?>
                </button>
                <?php if ($edit_item): ?>
                    <a href="manage_list_items.php" style="margin-left: 10px; text-decoration: none; color: #6c757d;">Batal Edit</a>
                <?php endif; ?>
            </form>
        </div>

        <!-- Section to Display Existing Items -->
        <h2>Daftar Item Saat Ini</h2>

        <?php foreach ($section_types as $type): ?>
            <h3><?php echo ucfirst($type); ?></h3>
            <?php if (!empty($items_by_section[$type])): ?>
                <table>
                    <thead>
                        <tr>
                            <th style="width:10%;">Urutan</th>
                            <th>Teks Item</th>
                            <th style="width:20%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items_by_section[$type] as $item): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($item['display_order']); ?></td>
                                <td><?php echo nl2br(htmlspecialchars($item['item_text'])); ?></td>
                                <td class="action-buttons">
                                    <a href="manage_list_items.php?action=edit&id=<?php echo $item['id']; ?>" class="edit-btn">Edit</a> 
                                    <form action="manage_list_items.php" method="POST" style="display: inline;">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                                        <button type="submit" class="delete-btn" onclick="return confirm('Anda yakin ingin menghapus item ini?');">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="no-data">Belum ada data untuk bagian <?php echo $type; ?>.</p>
            <?php endif; ?>
        <?php endforeach; ?>

    </div>

    <script>
        // Optional: Clear message from URL
        if (window.location.search.includes('message=')) {
            setTimeout(() => {
                window.history.replaceState({}, document.title, window.location.pathname);
            }, 5000);
        }
    </script>

</body>
</html>

