<?php
// admin/kelola_gejala.php
include '../includes/check_login.php';

$page_title = 'Kelola Gejala';

// --- LOGIKA CRUD ---

// C: CREATE
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tambah_gejala'])) {
    $kode = sanitize_input($conn, strtoupper($_POST['kode_gejala']));
    $deskripsi = sanitize_input($conn, $_POST['deskripsi']);

    // Pastikan kode unik
    $check = $conn->query("SELECT * FROM gejala WHERE kode_gejala = '$kode'");
    if ($check->num_rows == 0) {
        $sql = "INSERT INTO gejala (kode_gejala, deskripsi) VALUES ('$kode', '$deskripsi')";
        if ($conn->query($sql) === TRUE) {
            $message = "Gejala **$kode** berhasil ditambahkan.";
        } else {
            $error = "Error: " . $conn->error;
        }
    } else {
         $error = "Kode Gejala **$kode** sudah ada.";
    }
}

// U: UPDATE (LOGIKA BELUM DIMASUKKAN, tapi akan diletakkan di sini)

// D: DELETE
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "DELETE FROM gejala WHERE id = $id";
    if ($conn->query($sql) === TRUE) {
        $message = "Gejala berhasil dihapus.";
    } else {
        $error = "Error: " . $conn->error;
    }
}

// R: READ
$gejala_list = $conn->query("SELECT * FROM gejala ORDER BY id DESC");

// --- END LOGIKA CRUD ---

include '../includes/header.php'; 
?>

<h1 class="text-3xl font-bold text-gray-800 mb-6">Kelola Data Gejala</h1>

<?php if (isset($message)): ?>
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
        <p><?= $message ?></p>
    </div>
<?php endif; ?>
<?php if (isset($error)): ?>
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
        <p><?= $error ?></p>
    </div>
<?php endif; ?>

<div class="bg-white p-6 rounded-lg shadow-lg mb-8">
    <h2 class="text-xl font-semibold mb-4 border-b pb-2 text-xiaomi">Tambah Gejala Baru</h2>
    <form method="POST" action="">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="kode_gejala" class="block text-gray-700 text-sm font-bold mb-2">Kode (Ex: G01):</label>
                <input type="text" id="kode_gejala" name="kode_gejala" required maxlength="10"
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-xiaomi">
            </div>
            <div class="col-span-1 md:col-span-2">
                <label for="deskripsi" class="block text-gray-700 text-sm font-bold mb-2">Deskripsi Gejala:</label>
                <input type="text" id="deskripsi" name="deskripsi" required
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-xiaomi">
            </div>
        </div>
        <button type="submit" name="tambah_gejala"
                class="mt-4 bg-xiaomi hover:opacity-90 text-white font-bold py-2 px-4 rounded transition duration-300">
            Simpan Gejala
        </button>
    </form>
</div>

<div class="bg-white p-6 rounded-lg shadow-lg">
    <h2 class="text-xl font-semibold mb-4 border-b pb-2">Daftar Semua Gejala</h2>
    <div class="overflow-x-auto">
        <table class="min-w-full leading-normal">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Kode
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Deskripsi
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Aksi
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $gejala_list->fetch_assoc()): ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-5 border-b border-gray-200 text-sm">
                        <?= $row['kode_gejala'] ?>
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 text-sm">
                        <?= $row['deskripsi'] ?>
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 text-sm">
                        <a href="?action=delete&id=<?= $row['id'] ?>" onclick="return confirm('Yakin ingin menghapus gejala ini?')" 
                           class="text-red-600 hover:text-red-900">Hapus</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../includes/footer.php'; ?>