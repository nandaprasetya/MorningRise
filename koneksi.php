<?php
$host = "localhost";
$user = "morningrise_user";
$password = "password_baru_kuat";
$dbname = "morningrise";

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
