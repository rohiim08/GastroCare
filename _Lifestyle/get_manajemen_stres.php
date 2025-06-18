<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Database configuration
$host = 'localhost';
$dbname = 'gastrocare_db';
$username = 'root'; // sesuaikan dengan username database Anda
$password = ''; // sesuaikan dengan password database Anda

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Query untuk mengambil data manajemen stres
    $stmt = $pdo->prepare("SELECT id_stres, judul, link_video FROM manajemen_stres ORDER BY id_stres");
    $stmt->execute();
    $stres_tips = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Format data untuk frontend
    $data = [];
    foreach ($stres_tips as $tip) {
        $data[] = [
            'id' => $tip['id_stres'],
            'title' => $tip['judul'],
            'video' => $tip['link_video']
        ];
    }
    
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed: ' . $e->getMessage()]);
}
?>