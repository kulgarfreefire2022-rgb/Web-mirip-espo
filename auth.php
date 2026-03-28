<?php
require 'db_config.php';
header('Content-Type: application/json');

$action = $_POST['action'] ?? '';

if ($action == 'login') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        
        // Verifikasi password (disarankan gunakan password_hash saat register)
        if (password_verify($password, $user['password'])) {
            echo json_encode([
                "success" => true,
                "user" => [
                    "username" => $user['username'],
                    "balance" => $user['balance'],
                    "is_vip" => (bool)$user['is_vip']
                ],
                "api_key" => $user['api_key']
            ]);
        } else {
            echo json_encode(["success" => false, "message" => "Password salah!"]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "User tidak ditemukan!"]);
    }
}
?>
