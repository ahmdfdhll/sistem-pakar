<?php
// functions/expert_system.php

/**
 * Fungsi untuk memproses Forward Chaining dan menghitung persentase kecocokan.
 * @param mysqli $conn Objek koneksi database.
 * @param array $selected_gejala_ids Array ID gejala yang dipilih pengguna.
 * @return array Hasil diagnosis [kerusakan_id, nama_kerusakan, solusi, persentase].
 */
function diagnose($conn, $selected_gejala_ids) {
    if (empty($selected_gejala_ids)) {
        return ['error' => 'Anda harus memilih minimal satu gejala.'];
    }

    // 1. Ambil semua aturan yang melibatkan gejala yang dipilih
    $gejala_list = implode(',', array_map('intval', $selected_gejala_ids));
    
    // Query untuk mencari kerusakan yang terkait dengan gejala yang dipilih
    $sql_rules = "
        SELECT 
            t1.kerusakan_id, 
            t2.nama_kerusakan, 
            t2.solusi,
            t1.gejala_id
        FROM aturan t1
        JOIN kerusakan t2 ON t1.kerusakan_id = t2.id
        WHERE t1.gejala_id IN ($gejala_list)
    ";
    
    $result_rules = $conn->query($sql_rules);
    
    if ($result_rules->num_rows === 0) {
        return ['error' => 'Tidak ditemukan kerusakan yang cocok dengan gejala yang dipilih.'];
    }

    $matched_kerusakan = [];
    
    // 2. Kumpulkan data Gejala yang Cocok untuk Setiap Kerusakan
    while ($row = $result_rules->fetch_assoc()) {
        $k_id = $row['kerusakan_id'];
        
        if (!isset($matched_kerusakan[$k_id])) {
            $matched_kerusakan[$k_id] = [
                'id' => $k_id,
                'nama' => $row['nama_kerusakan'],
                'solusi' => $row['solusi'],
                'gejala_cocok_count' => 0, // Gejala yang dipilih dan relevan
                'total_gejala_relevan' => 0 // Total gejala untuk kerusakan ini
            ];
        }
        
        // Hanya hitung gejala yang dipilih (karena sudah di filter di WHERE IN)
        $matched_kerusakan[$k_id]['gejala_cocok_count']++; 
    }

    // 3. Hitung Total Gejala Relevan untuk Setiap Kerusakan
    // Kita harus tahu SEMUA gejala untuk setiap Kerusakan (tidak hanya yang dipilih)
    $kerusakan_ids = implode(',', array_keys($matched_kerusakan));
    
    $sql_total_rules = "
        SELECT kerusakan_id, COUNT(gejala_id) as total
        FROM aturan
        WHERE kerusakan_id IN ($kerusakan_ids)
        GROUP BY kerusakan_id
    ";
    
    $result_total = $conn->query($sql_total_rules);
    while ($row_total = $result_total->fetch_assoc()) {
        $k_id = $row_total['kerusakan_id'];
        if (isset($matched_kerusakan[$k_id])) {
            $matched_kerusakan[$k_id]['total_gejala_relevan'] = (int)$row_total['total'];
        }
    }

    // 4. Hitung Persentase Kecocokan dan Tentukan Hasil Terbaik
    $best_result = null;
    $max_persentase = -1;

    foreach ($matched_kerusakan as $k_id => $data) {
        if ($data['total_gejala_relevan'] > 0) {
            // Perhitungan Persentase Kecocokan Gejala
            $persentase = ($data['gejala_cocok_count'] / $data['total_gejala_relevan']) * 100;
        } else {
            $persentase = 0;
        }

        $data['persentase'] = round($persentase, 2);

        // Tentukan hasil terbaik
        if ($persentase > $max_persentase) {
            $max_persentase = $persentase;
            $best_result = $data;
        }
    }
    
    // Simpan Riwayat Diagnosa (opsional)
     $gejala_dipilih_str = implode(',', $selected_gejala_ids);
     if ($best_result) {
         $k_id = $best_result['id'];
         $persen = $best_result['persentase'];
         $sql_insert = "INSERT INTO riwayat_diagnosa (gejala_dipilih, hasil_kerusakan_id, persentase) VALUES ('$gejala_dipilih_str', $k_id, $persen)";
         $conn->query($sql_insert);
     }

    return $best_result;
}
?>