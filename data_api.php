<?php
require 'db_config.php';
header('Content-Type: application/json');

// Ambil input dari user (API Key & Aksi)
$api_key = mysqli_real_escape_string($conn, $_POST['api_key'] ?? '');
$action  = $_POST['action'] ?? ''; 

// --- TAMBAHAN FITUR DISINI ---

// 1. Fitur Check Access (Untuk Login dari Script GameGuardian)
if ($action == 'check_script_access') {
    if (empty($api_key)) {
        echo json_encode(["success" => false, "message" => "API Key kosong!"]);
        exit;
    }
    
    $checkQuery = mysqli_query($conn, "SELECT username, balance, is_vip FROM users WHERE api_key = '$api_key'");
    $userData = mysqli_fetch_assoc($checkQuery);

    if ($userData) {
        echo json_encode([
            "success" => true,
            "username" => $userData['username'],
            "balance" => (int)$userData['balance'],
            "is_vip" => (bool)$userData['is_vip']
        ]);
    } else {
        echo json_encode(["success" => false, "message" => "API Key tidak valid!"]);
    }
    exit;
}

// ---------------------------

// 1. Validasi Sesi/User berdasarkan API Key (Untuk fitur beli/transaksi)
if (empty($api_key)) {
    echo json_encode(["success" => false, "message" => "Sesi tidak valid! Silakan login ulang."]);
    exit;
}

$userQuery = mysqli_query($conn, "SELECT * FROM users WHERE api_key = '$api_key'");
$user = mysqli_fetch_assoc($userQuery);

if (!$user) {
    echo json_encode(["success" => false, "message" => "Pengguna tidak ditemukan!"]);
    exit;
}

$user_id = $user['id'];
$balance = $user['balance'];

// 2. Logika Berbagai Fitur CPM
switch ($action) {

    case 'create_account_auto_king':
        $price = 5000; // Harga fitur dalam Rupiah
        
        if ($balance >= $price) {
            // Proses Kurang Saldo
            $new_balance = $balance - $price;
            mysqli_query($conn, "UPDATE users SET balance = $new_balance WHERE id = $user_id");

            // Generate Akun (Contoh acak)
            $email = "espo_" . rand(100, 999) . "@cpm.com";
            $pass  = "king" . rand(111, 999);

            // Simpan Riwayat
            mysqli_query($conn, "INSERT INTO transactions (user_id, feature_name, amount_spent, details) VALUES ($user_id, 'Auto King Account', $price, '$email')");

            echo json_encode([
                "success" => true, 
                "message" => "Akun berhasil dibuat!", 
                "email" => $email, 
                "password" => $pass,
                "new_balance" => $new_balance
            ]);
        } else {
            echo json_encode(["success" => false, "message" => "Saldo kurang! Butuh Rp" . number_format($price)]);
        }
        break;

    case 'inject_coin':
        $price = 2000;
        
        if ($balance >= $price) {
            $new_balance = $balance - $price;
            mysqli_query($conn, "UPDATE users SET balance = $new_balance WHERE id = $user_id");
            
            mysqli_query($conn, "INSERT INTO transactions (user_id, feature_name, amount_spent) VALUES ($user_id, 'Inject 50M Coin', $price)");

            echo json_encode([
                "success" => true, 
                "message" => "Suntik koin berhasil diproses!",
                "new_balance" => $new_balance
            ]);
        } else {
            echo json_encode(["success" => false, "message" => "Saldo kurang!"]);
        }
        break;

    case 'get_user_info':
        // Hanya untuk cek saldo terbaru di dashboard
        echo json_encode([
            "success" => true,
            "balance" => (int)$user['balance'],
            "is_vip" => (bool)$user['is_vip']
        ]);
        break;

    default:
        echo json_encode(["success" => false, "message" => "Fitur belum tersedia."]);
        break;
}
?>
