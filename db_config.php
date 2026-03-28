<?php
$host = "localhost";
$user = "root"; // Sesuaikan dengan username database kamu
$pass = "";     // Sesuaikan dengan password database kamu
$db   = "espo_store_db";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die(json_encode(["success" => false, "message" => "Koneksi Database Gagal"]));
}
?>
