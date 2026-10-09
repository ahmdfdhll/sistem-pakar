<?php
// admin/kelola_kerusakan.php
include '../includes/check_login.php';

$page_title = 'Kelola Data Kerusakan';
$message = $error = '';

// --- LOGIKA CRUD ---

// C: CREATE (Tambah Kerusakan Baru)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tambah_kerusakan'])) {
    // 5. Fitur Backend: Sanitasi Input
    $kode = sanitize_input($conn, strtoupper($_POST['kode_kerusakan']));
    $nama = sanitize_input($conn, $_POST['nama_kerusakan']);
    $solusi = sanitize_input($conn, $_POST['solusi']);

    if (empty($kode) || empty($nama) || empty($solusi)) {
        $error = "Semua field harus diisi.";
    } else {
        $check = $conn->query("SELECT * FROM kerusakan WHERE kode_kerusakan = '$kode'");
        if ($check->num_rows == 0) {
            $sql = "INSERT INTO kerusakan (kode_kerusakan, nama_kerusakan, solusi) VALUES ('$kode', '$nama', '$solusi')";
            if ($conn->query($sql) === TRUE) {
                $message = "Kerusakan **$kode** berhasil ditambahkan.";
            } else {
                $error = "Error saat menyimpan: " . $conn->error;
            }
        } else {
             $error = "Kode Kerusakan **$kode** sudah ada.";
        }
    }
}

// D: DELETE (Hapus Kerusakan)
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // PENTING: Menghapus kerusakan juga akan menghapus semua ATURAN yang terkait (CASCADE di SQL)
    $sql = "DELETE FROM kerusakan WHERE id = $id";
    if ($conn->query($sql) === TRUE) {
        $message = "Kerusakan berhasil dihapus. (Semua aturan terkait juga dihapus)";
        // Redirect untuk menghilangkan parameter GET setelah delete
        header('Location: kelola_kerusakan.php?msg=' . urlencode($message));
        exit();
    } else {
        $error = "Error saat menghapus: " . $conn->error;
    }
}

// R: READ (Ambil pesan dari redirect)
if (isset($_GET['msg'])) {
    $message = htmlspecialchars($_GET['msg']);
}

// R: READ (Ambil semua data kerusakan untuk tabel)
$kerusakan_list = $conn->query("SELECT * FROM kerusakan ORDER BY id DESC");

// --- END LOGIKA CRUD ---

include '../includes/header.php'; 
?>

<h1 class="text-3xl font-bold text-gray-800 mb-6">Kelola Data Kerusakan</h1>
<p class="text-gray-600 mb-6">Kelola daftar potensi kerusakan yang akan menjadi hasil diagnosis, beserta solusi perbaikannya.</p>

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
    <h2 class="text-xl font-semibold mb-4 border-b pb-2 text-xiaomi">Tambah Kerusakan Baru</h2>
    <form method="POST" action="">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="kode_kerusakan" class="block text-gray-700 text-sm font-bold mb-2">Kode (Ex: K01):</label>
                <input type="text" id="kode_kerusakan" name="kode_kerusakan" required maxlength="10"
                       class="shadow border rounded w-full py-2 px-3 text-gray-700 focus:border-xiaomi">
            </div>
            <div class="col-span-1 md:col-span-3">
                <label for="nama_kerusakan" class="block text-gray-700 text-sm font-bold mb-2">Nama Kerusakan:</label>
                <input type="text" id="nama_kerusakan" name="nama_kerusakan" required
                       class="shadow border rounded w-full py-2 px-3 text-gray-700 focus:border-xiaomi">
            </div>
        </div>
        <div class="mt-4">
            <label for="solusi" class="block text-gray-700 text-sm font-bold mb-2">Solusi Perbaikan:</label>
            <textarea id="solusi" name="solusi" rows="4" required
                      class="shadow border rounded w-full py-2 px-3 text-gray-700 focus:border-xiaomi"></textarea>
        </div>
        <button type="submit" name="tambah_kerusakan"
                class="mt-4 bg-xiaomi hover:opacity-90 text-white font-bold py-2 px-4 rounded transition">
            Simpan Kerusakan
        </button>
    </form>
</div>

---

<div class="bg-white p-6 rounded-lg shadow-lg">
    <h2 class="text-xl font-semibold mb-4 border-b pb-2">Daftar Semua Kerusakan</h2>
    <div class="overflow-x-auto">
        <table class="min-w-full leading-normal">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kode</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama Kerusakan</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Solusi Singkat</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($kerusakan_list->num_rows > 0): ?>
                    <?php while($row = $kerusakan_list->fetch_assoc()): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-5 border-b border-gray-200 text-sm"><?= $row['kode_kerusakan'] ?></td>
                        <td class="px-5 py-5 border-b border-gray-200 text-sm"><?= $row['nama_kerusakan'] ?></td>
                        <td class="px-5 py-5 border-b border-gray-200 text-sm"><?= substr($row['solusi'], 0, 70) ?>...</td>
                        <td class="px-5 py-5 border-b border-gray-200 text-sm">
                            <a href="?action=delete&id=<?= $row['id'] ?>" onclick="return confirm('Yakin ingin menghapus kerusakan ini? PERINGATAN: Semua ATURAN yang terkait akan ikut terhapus!')" 
                               class="text-red-600 hover:text-red-900 font-medium">Hapus</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="px-5 py-5 border-b border-gray-200 text-sm text-center text-gray-500">
                            Belum ada data kerusakan yang ditambahkan.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../includes/footer.php'; ?>