<?php
require 'db_config.php';
header('Content-Type: application/json');

$action = $_POST['action'] ?? '';

// --- LOGIKA REGISTER ---
if ($action == 'register') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];
    
    // Validasi input kosong
    if (empty($username) || empty($password)) {
        echo json_encode(["success" => false, "message" => "Username dan password tidak boleh kosong!"]);
        exit;
    }

    // Cek apakah username sudah ada
    $checkUser = mysqli_query($conn, "SELECT id FROM users WHERE username = '$username'");
    if (mysqli_num_rows($checkUser) > 0) {
        echo json_encode(["success" => false, "message" => "Username sudah terdaftar!"]);
    } else {
        // Enkripsi password agar aman di database
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        // Generate API Key unik untuk user
        $api_key = bin2hex(random_bytes(16));

        $query = "INSERT INTO users (username, password, api_key, balance, is_vip) VALUES ('$username', '$hashed_password', '$api_key', 0, 0)";
        
        if (mysqli_query($conn, $query)) {
            echo json_encode(["success" => true, "message" => "Berhasil daftar! Silakan login."]);
        } else {
            echo json_encode(["success" => false, "message" => "Gagal mendaftar: " . mysqli_error($conn)]);
        }
    }

// --- LOGIKA LOGIN ---
} elseif ($action == 'login') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        
        // Verifikasi password yang diinput dengan password terenkripsi di DB
        if (password_verify($password, $user['password'])) {
            echo json_encode([
                "success" => true,
                "message" => "Login berhasil!",
                "user" => [
                    "username" => $user['username'],
                    "balance"  => (int)$user['balance'],
                    "is_vip"   => (bool)$user['is_vip']
                ],
                "api_key" => $user['api_key']
            ]);
        } else {
            echo json_encode(["success" => false, "message" => "Password salah!"]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "User tidak ditemukan!"]);
    }

} else {
    echo json_encode(["success" => false, "message" => "Aksi tidak dikenali."]);
}
?>
