<?php
header('Content-Type: application/json');
// Koneksi database di sini
$action = $_POST['action'] ?? '';

if ($action == 'login') {
    // Logika verifikasi username & password
    echo json_encode(["success" => true, "user" => ["username" => "User123", "balance" => 5000], "api_key" => " Rahasia123"]);
} elseif ($action == 'register') {
    echo json_encode(["success" => true, "message" => "Berhasil daftar!"]);
}
?>

