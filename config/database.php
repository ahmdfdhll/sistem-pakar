<?php
// config/database.php

$host = 'localhost';
$user = 'root'; // Ganti dengan user DB Anda
$pass = '';     // Ganti dengan password DB Anda
$db_name = 'sistem_pakar_xiomi'; // Ganti dengan nama database Anda

// Buat koneksi
$conn = new mysqli($host, $user, $pass, $db_name);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Set karakter ke UTF8 (opsional tapi disarankan)
$conn->set_charset("utf8");

// Helper function untuk sanitasi input database
function sanitize_input($conn, $data) {
    return mysqli_real_escape_string($conn, htmlspecialchars($data));
}
?>