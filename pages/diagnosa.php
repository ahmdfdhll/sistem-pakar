<?php
// pages/diagnosa.php
$page_title = 'Mulai Diagnosa';
include '../config/database.php';
include '../includes/header.php';

// Ambil semua gejala dari database
$gejala_result = $conn->query("SELECT * FROM gejala ORDER BY kode_gejala ASC");
$gejala = [];
while($row = $gejala_result->fetch_assoc()) {
    $gejala[] = $row;
}
?>

<h1 class="text-4xl font-bold text-gray-800 mb-6 border-b-2 border-xiaomi pb-2">Diagnosa Kerusakan</h1>
<p class="text-gray-600 mb-8">Pilih semua gejala yang dialami oleh HP Xiaomi Anda. Semakin akurat Anda memilih, semakin tepat hasil diagnosisnya.</p>

<form action="hasil_diagnosa.php" method="POST" class="bg-white p-6 md:p-10 rounded-xl shadow-lg">
    <div class="space-y-6">
        <?php if (count($gejala) > 0): ?>
            <?php foreach ($gejala as $g): ?>
                <div class="flex items-center p-4 border rounded-lg hover:bg-gray-50 transition duration-150">
                    <input type="checkbox" id="gejala_<?= $g['id'] ?>" name="gejala[]" value="<?= $g['id'] ?>"
                           class="custom-checkbox h-5 w-5 text-xiaomi border-gray-300 rounded focus:ring-xiaomi">
                    <label for="gejala_<?= $g['id'] ?>" class="ml-4 text-gray-700 font-medium cursor-pointer flex-1">
                        **<?= $g['kode_gejala'] ?>** - <?= $g['deskripsi'] ?>
                    </label>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-red-500">Data gejala belum tersedia. Silakan hubungi admin.</p>
        <?php endif; ?>
    </div>
    
    <div class="mt-10">
        <button type="submit" 
                class="w-full bg-xiaomi hover:opacity-90 text-white font-bold py-3 px-4 rounded-lg focus:outline-none focus:shadow-outline transition duration-300 text-lg shadow-md">
            Proses Diagnosa Sekarang
        </button>
    </div>
</form>

<?php include '../includes/footer.php'; ?>