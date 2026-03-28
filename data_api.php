<?php
header('Content-Type: application/json');
$action = $_POST['action'] ?? '';

// Contoh respon saat user membeli "Create Account Auto King"
if ($action == 'create_account_auto_king') {
    echo json_encode([
        "success" => true,
        "email" => "espo_" . rand(100,999) . "@game.com",
        "password" => "king" . rand(1000,9999)
    ]);
} else {
    echo json_encode(["success" => true, "balance" => 10000, "is_vip" => false]);
}
?>

