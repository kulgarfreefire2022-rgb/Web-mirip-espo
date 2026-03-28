<?php
require 'db_config.php';
header('Content-Type: application/json');

$action = $_POST['action'] ?? '';
$api_key = $_POST['api_key'] ?? ''; // Ambil API Key untuk identifikasi user

if ($action == 'create_account_auto_king') {
    $price = 5000; // Harga fitur

    // Cari user berdasarkan API Key
    $userQuery = mysqli_query($conn, "SELECT * FROM users WHERE api_key = '$api_key'");
    $user = mysqli_fetch_assoc($userQuery);

    if (!$user) {
        echo json_encode(["success" => false, "message" => "Sesi tidak valid!"]);
        exit;
    }

    if ($user['balance'] >= $price) {
        // Kurangi Saldo
        $newBalance = $user['balance'] - $price;
        $userId = $user['id'];
        
        mysqli_query($conn, "UPDATE users SET balance = $newBalance WHERE id = $userId");
        
        // Catat di riwayat transaksi
        mysqli_query($conn, "INSERT INTO transactions (user_id, feature_name, amount_spent) VALUES ($userId, 'Auto King Account', $price)");

        echo json_encode([
            "success" => true,
            "message" => "Pembelian berhasil!",
            "new_balance" => $newBalance,
            "email" => "espo_" . rand(100, 999) . "@game.com",
            "password" => "king" . rand(1000, 9999)
        ]);
    } else {
        echo json_encode(["success" => false, "message" => "Saldo tidak cukup! Silakan top up."]);
    }
}
?>
