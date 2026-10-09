<?php
// includes/header.php
// FILE INI HARUS BERADA DI DALAM FOLDER 'includes'

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 1. Logika Penentuan Base URL (Root Path)
// Kita gunakan logika yang lebih kuat untuk menentukan apakah kita berada di subfolder (admin/ atau pages/)

$current_path = $_SERVER['PHP_SELF'];

// Cek apakah script yang dieksekusi berada di dalam folder 'admin' atau 'pages'
if (strpos($current_path, '/admin/') !== false || strpos($current_path, '/pages/') !== false) {
    // Jika berada di subfolder, kita perlu 'naik' satu level (../)
    $base_url = '../';
} else {
    // Jika berada di root proyek (misal: index.php atau login.php)
    $base_url = '';
}

// 2. Tentukan Judul Halaman
$page_title = $page_title ?? 'Sistem Pakar Diagnosa HP Xiaomi';

// Variabel opsional: Flag untuk admin header
// Variabel $is_admin harus didefinisikan di check_login.php
// Jika Anda memanggil header dari halaman admin, ini akan bernilai true.
// Namun, kita tidak terlalu mengandalkannya karena $base_url sudah diatasi.
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Desain: Tema warna khas Xiaomi */
        :root { --color-primary: #FF6700; }
        .bg-xiaomi { background-color: var(--color-primary); }
        .text-xiaomi { color: var(--color-primary); }
        .custom-checkbox { transform: scale(1.2); margin-right: 1rem; }
    </style>
</head>
<body class="bg-gray-50">
    <header class="bg-white shadow-md">
        <div class="container mx-auto p-4 flex justify-between items-center">
            <a href="<?= $base_url ?>index.php" class="text-2xl font-extrabold text-xiaomi">
                Diagnosa HP Xiaomi
            </a>
            <nav class="space-x-4 hidden md:block">
                <a href="<?= $base_url ?>index.php" class="text-gray-600 hover:text-xiaomi font-medium">Home</a>
                <a href="<?= $base_url ?>pages/diagnosa.php" class="bg-xiaomi text-white px-4 py-2 rounded-lg hover:opacity-90 transition duration-300">Mulai Diagnosa</a>
                
                <?php if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true): ?>
                    <a href="<?= $base_url ?>admin/dashboard.php" class="text-gray-600 hover:text-xiaomi font-medium">Dashboard</a>
                    <a href="<?= $base_url ?>logout.php" class="text-red-500 hover:text-red-700 font-medium">Logout</a>
                <?php else: ?>
                    <a href="<?= $base_url ?>login.php" class="text-gray-600 hover:text-xiaomi font-medium">Admin</a>
                <?php endif; ?>

            </nav>
            <button class="md:hidden text-2xl text-xiaomi">☰</button>
        </div>
    </header>
    <main class="container mx-auto p-4 pt-8 min-h-[80vh]">