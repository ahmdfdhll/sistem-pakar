<?php
// pages/hasil_diagnosa.php
$page_title = 'Hasil Diagnosa';
include '../config/database.php';
require_once '../functions/sistem_pakar.php';
include '../includes/header.php';

$hasil = null;
$error_msg = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['gejala'])) {
    // 5. Fitur Backend: Validasi & Sanitasi Input
    $selected_gejala_ids = array_filter($_POST['gejala'], 'is_numeric'); // Pastikan hanya angka
    
    // 1. Fitur Utama Sistem Pakar: Proses forward chaining
    $hasil = diagnose($conn, $selected_gejala_ids);

    if (isset($hasil['error'])) {
        $error_msg = $hasil['error'];
        $hasil = null;
    }
} else {
    $error_msg = "Anda belum memilih gejala apapun. Silakan kembali ke halaman diagnosa.";
}
?>

<h1 class="text-4xl font-bold text-gray-800 mb-6 border-b-2 border-xiaomi pb-2">Hasil Diagnosa Kerusakan</h1>

<?php if ($hasil): ?>
    <div class="bg-white p-6 md:p-8 rounded-xl shadow-lg border-t-4 border-xiaomi">
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-6">
            <h2 class="text-3xl font-extrabold text-xiaomi">
                Diagnosis Utama: <?= $hasil['nama'] ?>
            </h2>
            <div class="mt-4 md:mt-0 bg-red-500 text-white text-xl font-bold px-4 py-2 rounded-full shadow-md">
                Tingkat Kemungkinan: <?= $hasil['persentase'] ?>%
            </div>
        </div>
        
        <div class="mb-8">
            <h3 class="text-2xl font-semibold text-gray-800 border-b pb-2 mb-4">Solusi Perbaikan</h3>
            <div class="prose max-w-none text-gray-700 leading-relaxed">
                <?= nl2br($hasil['solusi']) ?>
            </div>
        </div>
        
        <a href="diagnosa.php" class="inline-block bg-gray-200 text-gray-800 hover:bg-gray-300 font-medium py-2 px-4 rounded-lg transition duration-300">
            &#x2190; Diagnosa Ulang
        </a>
    </div>

<?php else: ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
        <p class="font-bold">Proses Diagnosa Gagal!</p>
        <p class="text-sm"><?= $error_msg ?></p>
    </div>
    <a href="diagnosa.php" class="inline-block bg-xiaomi text-white hover:opacity-90 font-bold py-2 px-4 rounded-lg transition duration-300">
        Mulai Diagnosa Ulang
    </a>
<?php endif; ?>

<?php include '../includes/footer.php'; ?>