<?php
// logout.php
session_start();

// Hapus semua variabel sesi
session_unset();

// Hancurkan sesi
session_destroy();

// Arahkan kembali ke halaman utama (index.php)
header('Location: index.php');
exit();
?>