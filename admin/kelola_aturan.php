<?php
// admin/kelola_aturan.php
include '../includes/check_login.php';

$page_title = 'Kelola Aturan (Basis Pengetahuan)';

// --- LOGIKA CRUD (C: CREATE & D: DELETE) ---
$message = $error = '';

// C: CREATE - Tambah Aturan Baru
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tambah_aturan'])) {
    // 5. Fitur Backend: Sanitasi Input
    $kerusakan_id = intval($_POST['kerusakan_id']);
    $gejala_id = intval($_POST['gejala_id']);

    if ($kerusakan_id > 0 && $gejala_id > 0) {
        // Cek apakah aturan sudah ada
        $check = $conn->query("SELECT * FROM aturan WHERE kerusakan_id = $kerusakan_id AND gejala_id = $gejala_id");
        
        if ($check->num_rows == 0) {
            $sql = "INSERT INTO aturan (kerusakan_id, gejala_id) VALUES ($kerusakan_id, $gejala_id)";
            if ($conn->query($sql) === TRUE) {
                $message = "Aturan baru berhasil ditambahkan.";
            } else {
                $error = "Error: " . $conn->error;
            }
        } else {
            $error = "Aturan ini (Kerusakan & Gejala) sudah ada.";
        }
    } else {
        $error = "Kerusakan dan Gejala harus dipilih.";
    }
}

// D: DELETE - Hapus Aturan
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "DELETE FROM aturan WHERE id = $id";
    if ($conn->query($sql) === TRUE) {
        $message = "Aturan berhasil dihapus.";
        // Redirect untuk menghilangkan parameter GET setelah delete
        header('Location: kelola_aturan.php?msg=' . urlencode($message));
        exit();
    } else {
        $error = "Error: " . $conn->error;
    }
}

// Tampilkan pesan setelah redirect (jika ada)
if (isset($_GET['msg'])) {
    $message = htmlspecialchars($_GET['msg']);
}


// R: READ - Ambil data untuk dropdown dan tabel
$kerusakan_list = $conn->query("SELECT id, kode_kerusakan, nama_kerusakan FROM kerusakan ORDER BY kode_kerusakan ASC");
$gejala_list = $conn->query("SELECT id, kode_gejala, deskripsi FROM gejala ORDER BY kode_gejala ASC");

// R: READ - Ambil semua Aturan dengan JOIN (untuk deskripsi)
$sql_aturan = "
    SELECT 
        a.id as aturan_id,
        k.kode_kerusakan,
        k.nama_kerusakan,
        g.kode_gejala,
        g.deskripsi
    FROM aturan a
    JOIN kerusakan k ON a.kerusakan_id = k.id
    JOIN gejala g ON a.gejala_id = g.id
    ORDER BY a.id DESC
";
$aturan_data = $conn->query($sql_aturan);

// --- END LOGIKA CRUD ---

include '../includes/header.php'; 
?>

<h1 class="text-3xl font-bold text-gray-800 mb-6">Kelola Basis Aturan (IF-THEN)</h1>
<p class="text-gray-600 mb-6">Kelola hubungan antara Gejala dan Kerusakan. Setiap baris di bawah adalah 1 Aturan (Rule): **IF** (Gejala ini) **THEN** (Kerusakan ini).</p>

<?php if (isset($message) && $message): ?>
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
        <p><?= $message ?></p>
    </div>
<?php endif; ?>
<?php if (isset($error) && $error): ?>
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
        <p><?= $error ?></p>
    </div>
<?php endif; ?>

<div class="bg-white p-6 rounded-lg shadow-lg mb-8">
    <h2 class="text-xl font-semibold mb-4 border-b pb-2 text-xiaomi">Tambah Aturan Baru</h2>
    <form method="POST" action="kelola_aturan.php">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            
            <div>
                <label for="kerusakan_id" class="block text-gray-700 text-sm font-bold mb-2">THEN (Kerusakan):</label>
                <select id="kerusakan_id" name="kerusakan_id" required
                        class="shadow border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline focus:border-xiaomi">
                    <option value="">-- Pilih Kerusakan --</option>
                    <?php while($k = $kerusakan_list->fetch_assoc()): ?>
                        <option value="<?= $k['id'] ?>"><?= $k['kode_kerusakan'] ?> - <?= $k['nama_kerusakan'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="text-center font-bold text-xl mb-2 text-gray-500 hidden md:block">
                &#x2190; IF (Dapat Diakibatkan Oleh)
            </div>
            
            <div class="md:col-span-2">
                <label for="gejala_id" class="block text-gray-700 text-sm font-bold mb-2">IF (Gejala):</label>
                <select id="gejala_id" name="gejala_id" required
                        class="shadow border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline focus:border-xiaomi">
                    <option value="">-- Pilih Gejala --</option>
                    <?php while($g = $gejala_list->fetch_assoc()): ?>
                        <option value="<?= $g['id'] ?>"><?= $g['kode_gejala'] ?> - <?= $g['deskripsi'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
        </div>
        
        <button type="submit" name="tambah_aturan"
                class="mt-4 bg-xiaomi hover:opacity-90 text-white font-bold py-2 px-4 rounded transition duration-300">
            Simpan Aturan
        </button>
    </form>
</div>

<div class="bg-white p-6 rounded-lg shadow-lg">
    <h2 class="text-xl font-semibold mb-4 border-b pb-2">Daftar Semua Aturan yang Ada</h2>
    <div class="overflow-x-auto">
        <table class="min-w-full leading-normal">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-[10%]">
                        ID Aturan
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-[40%]">
                        Kerusakan (THEN)
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-[40%]">
                        Gejala (IF)
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-[10%]">
                        Aksi
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php if ($aturan_data->num_rows > 0): ?>
                    <?php while($row = $aturan_data->fetch_assoc()): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-5 border-b border-gray-200 text-sm">
                            <?= $row['aturan_id'] ?>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 text-sm">
                            **<?= $row['kode_kerusakan'] ?>** - <?= $row['nama_kerusakan'] ?>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 text-sm">
                            **<?= $row['kode_gejala'] ?>** - <?= $row['deskripsi'] ?>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 text-sm">
                            <a href="?action=delete&id=<?= $row['aturan_id'] ?>" onclick="return confirm('Yakin ingin menghapus aturan ini?')" 
                               class="text-red-600 hover:text-red-900 font-medium">Hapus</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="px-5 py-5 border-b border-gray-200 text-sm text-center text-gray-500">
                            Belum ada aturan yang ditambahkan.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../includes/footer.php'; ?>