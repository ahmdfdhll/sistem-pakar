<?php
// admin/dashboard.php
include '../includes/check_login.php'; // Proteksi halaman admin

$page_title = 'Dashboard Admin';
include '../includes/header.php'; // Gunakan header yang sama untuk konsistensi

// LOGIKA DASHBOARD DI SINI
$count_gejala = $conn->query("SELECT COUNT(*) FROM gejala")->fetch_row()[0];
$count_kerusakan = $conn->query("SELECT COUNT(*) FROM kerusakan")->fetch_row()[0];
$count_aturan = $conn->query("SELECT COUNT(*) FROM aturan")->fetch_row()[0];
?>

<div class="flex items-center justify-between border-b pb-3 mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Dashboard Admin</h1>
    
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-blue-500">
        <p class="text-sm font-medium text-gray-500">Total Gejala</p>
        <p class="text-4xl font-bold text-gray-900 mt-1"><?= $count_gejala ?></p>
        <a href="kelola_gejala.php" class="text-blue-500 hover:text-blue-700 text-sm mt-2 block">Kelola Data Gejala &rarr;</a>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-green-500">
        <p class="text-sm font-medium text-gray-500">Total Kerusakan</p>
        <p class="text-4xl font-bold text-gray-900 mt-1"><?= $count_kerusakan ?></p>
        <a href="kelola_kerusakan.php" class="text-green-500 hover:text-green-700 text-sm mt-2 block">Kelola Data Kerusakan &rarr;</a>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-yellow-500">
        <p class="text-sm font-medium text-gray-500">Total Aturan</p>
        <p class="text-4xl font-bold text-gray-900 mt-1"><?= $count_aturan ?></p>
        <a href="kelola_aturan.php" class="text-yellow-500 hover:text-yellow-700 text-sm mt-2 block">Kelola Basis Aturan &rarr;</a>
    </div>
</div>

<div class="bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-xl font-semibold mb-4 border-b pb-2">Riwayat Diagnosa Terbaru</h2>
    <p class="text-gray-500">Fitur ini (Riwayat Diagnosa) belum diaktifkan dalam fungsi `diagnose()` namun dapat dikembangkan lebih lanjut di sini.</p>
    <a href="riwayat_diagnosa.php" class="text-xiaomi hover:text-orange-700 font-medium mt-2 block">Lihat Semua Riwayat (Jika Aktif) &rarr;</a>
</div>

<?php include '../includes/footer.php'; ?>