<?php
session_start();
require_once 'C:\xampp\htdocs\gastrocare\db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

echo json_encode(['success' => true, 'formData' => isset($_SESSION['diagnosis_data']) ? $_SESSION['diagnosis_data'] : []]);
?>