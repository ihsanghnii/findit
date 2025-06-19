<?php
declare(strict_types=1);
// Konfigurasi Database
$db_host = 'localhost';
$db_name = 'findit_db';
$db_user = 'root'; 
$db_pass = '';
// Inisialisasi session dan koneksi database
session_start();
try {
    $mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);
    if ($mysqli->connect_error) {
        throw new Exception('Database connection failed: ' . $mysqli->connect_error);
    }
    $mysqli->set_charset('utf8mb4');
} catch (Exception $e) {
    die('Database error: ' . $e->getMessage());
}
// Fungsi helper
function sanitize_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}
// Redirect jika tidak login
function require_login() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: auth/login.php');
        exit;
    }
}
?>

