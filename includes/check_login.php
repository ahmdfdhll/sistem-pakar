<?php
// includes/check_login.php

session_start();

// Proteksi halaman admin
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: ../login.php');
    exit();
}

// Tambahkan koneksi database
include_once '../config/database.php';
?>