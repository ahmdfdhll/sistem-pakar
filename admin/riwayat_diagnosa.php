<?php
// admin/riwayat_diagnosa.php
include '../includes/check_login.php'; // Proteksi admin dan koneksi DB

$page_title = 'Riwayat Diagnosa Pengguna';

// Ambil data riwayat diagnosa dari database
$sql_riwayat = "
    SELECT 
        r.tanggal_diagnosa, 
        r.persentase,
        r.gejala_dipilih,
        k.nama_kerusakan,
        k.kode_kerusakan
    FROM riwayat_diagnosa r
    LEFT JOIN kerusakan k ON r.hasil_kerusakan_id = k.id
    ORDER BY r.tanggal_diagnosa DESC
    LIMIT 50 
";

$riwayat_result = $conn->query($sql_riwayat);

include '../includes/header.php'; 
?>

<h1 class="text-3xl font-bold text-gray-800 mb-6">Riwayat Diagnosa Pengguna</h1>
<p class="text-gray-600 mb-6">Data ini menampilkan 50 riwayat diagnosis terakhir yang telah dilakukan oleh pengguna publik.</p>

<div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-4" role="alert">
    
</div>

<div class="bg-white p-6 rounded-lg shadow-lg">
    <h2 class="text-xl font-semibold mb-4 border-b pb-2">50 Riwayat Diagnosis Terakhir</h2>
    <div class="overflow-x-auto">
        <table class="min-w-full leading-normal">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Tanggal
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Hasil Diagnosa
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Persentase
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Gejala Dipilih (ID)
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php if ($riwayat_result->num_rows > 0): ?>
                    <?php while($row = $riwayat_result->fetch_assoc()): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-5 border-b border-gray-200 text-sm">
                            <?= date('d-m-Y H:i', strtotime($row['tanggal_diagnosa'])) ?>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 text-sm font-semibold">
                            <?= $row['kode_kerusakan'] ?? 'N/A' ?> - <?= $row['nama_kerusakan'] ?? 'Tidak Terdeteksi' ?>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 text-sm">
                            <span class="font-bold <?= ($row['persentase'] > 50) ? 'text-green-600' : 'text-orange-600' ?>">
                                <?= $row['persentase'] ?>%
                            </span>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 text-sm">
                            <?= $row['gejala_dipilih'] ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="px-5 py-5 border-b border-gray-200 text-sm text-center text-gray-500">
                            Belum ada riwayat diagnosis yang tercatat.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../includes/footer.php'; ?>