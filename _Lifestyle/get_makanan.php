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
    
    // Query untuk mengambil data makanan
    $stmt = $pdo->prepare("SELECT id_makanan, jenis, gambar, menu, deskripsi FROM makanan ORDER BY jenis, id_makanan");
    $stmt->execute();
    $makanan = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Reorganisasi data berdasarkan jenis
    $data = [
        'sayur' => [],
        'ikan' => [],
        'daging' => []
    ];
    
    foreach ($makanan as $item) {
        $jenis = strtolower($item['jenis']);
        if (isset($data[$jenis])) {
            $data[$jenis][] = [
                'id' => $item['id_makanan'],
                'nama' => $item['menu'],
                'khasiat' => $item['deskripsi'],
                'img' => $item['gambar'],
                'kategori' => ucfirst($item['jenis'])
            ];
        }
    }
    
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed: ' . $e->getMessage()]);
}
?>