<?php
// index.php
$page_title = 'Home - Sistem Pakar Diagnosa HP Xiaomi';
include 'includes/header.php';
?>

<div class="text-center py-12 bg-white rounded-lg shadow-xl">
    <h1 class="text-5xl font-extrabold text-gray-800 mb-4">
        Diagnosa Kerusakan HP Xiaomi
    </h1>
    <p class="text-xl text-gray-600 mb-8">
        Sistem Pakar Berbasis Aturan (Forward Chaining) untuk membantu Anda menemukan masalah pada perangkat Xiaomi Anda.
    </p>
    <a href="pages/diagnosa.php" class="inline-block bg-xiaomi hover:opacity-90 text-white font-bold py-3 px-8 rounded-full transition duration-300 text-lg shadow-lg">
        Mulai Diagnosa Sekarang &rarr;
    </a>
</div>

<div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-8">
    <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-xiaomi">
        <h2 class="text-2xl font-semibold mb-3">Gejala Input</h2>
        <p class="text-gray-600">Anda hanya perlu memilih gejala-gejala yang sedang dialami oleh HP Anda melalui *checkbox*.</p>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-xiaomi">
        <h2 class="text-2xl font-semibold mb-3">Proses Inferensi</h2>
        <p class="text-gray-600">Sistem akan memproses gejala menggunakan metode **Forward Chaining** pada basis aturan.</p>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-xiaomi">
        <h2 class="text-2xl font-semibold mb-3">Hasil Akurat</h2>
        <p class="text-gray-600">Anda akan mendapatkan diagnosis, tingkat kemungkinan, dan solusi perbaikan yang direkomendasikan.</p>
    </div>
</div>

<?php include 'includes/footer.php'; ?>