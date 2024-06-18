<?php
// INFO PENDAPATAN ////////////////////////////////////////////////
// Ambil id_faktur dari tabel faktur berdasarkan kategori keluar, tanggal bulan saat ini, status dibayar dan status hapus 0
$conditions_faktur_this_month = "kategori = 'keluar' AND MONTH(tanggal) = ? AND YEAR(tanggal) = ? AND status = 'dibayar' AND status_hapus = 0";
$bind_params_faktur_this_month = array(
    array('type' => 'i', 'value' => $current_month),
    array('type' => 'i', 'value' => $current_year)
);
$data_faktur_this_month = selectData('faktur', $conditions_faktur_this_month, "", "", $bind_params_faktur_this_month);

// Iterasi setiap id_faktur yang sesuai dengan kondisi bulan ini
$subtotal_this_month = 0;
foreach ($data_faktur_this_month as $faktur_this_month) {
    $id_faktur = $faktur_this_month['id_faktur'];

    // Ambil detail faktur berdasarkan id_faktur
    $conditions_detail_faktur_this_month = "id_faktur = ?";
    $bind_params_detail_faktur_this_month = array(
        array('type' => 'i', 'value' => $id_faktur)
    );
    $data_faktur_detail_this_month = selectData('detail_faktur', $conditions_detail_faktur_this_month, "", "", $bind_params_detail_faktur_this_month);

    // Hitung subtotal bulan ini dan tambahkan ke variabel subtotal bulan ini
    foreach ($data_faktur_detail_this_month as $detail_faktur_this_month) {
        $subtotal_this_month += ($detail_faktur_this_month['jumlah'] * $detail_faktur_this_month['harga_satuan']);
    }
}

// Ambil id_faktur dari tabel faktur berdasarkan kategori keluar, tanggal bulan lalu, dan status dibayar
$conditions_faktur_last_month = "kategori = 'keluar' AND MONTH(tanggal) = ? AND YEAR(tanggal) = ? AND status = 'dibayar' AND status_hapus = 0";
$bind_params_faktur_last_month = array(
    array('type' => 'i', 'value' => $last_month),
    array('type' => 'i', 'value' => $last_year)
);
$data_faktur_last_month = selectData('faktur', $conditions_faktur_last_month, "", "", $bind_params_faktur_last_month);

// Iterasi setiap id_faktur yang sesuai dengan kondisi bulan lalu
$subtotal_last_month = 0;
foreach ($data_faktur_last_month as $faktur_last_month) {
    $id_faktur_last_month = $faktur_last_month['id_faktur'];

    // Ambil data detail faktur berdasarkan id_faktur
    $conditions_detail_faktur_last_month = "id_faktur = ?";
    $bind_params_detail_faktur_last_month = array(
        array('type' => 'i', 'value' => $id_faktur_last_month)
    );
    $data_faktur_detail_last_month = selectData('detail_faktur', $conditions_detail_faktur_last_month, "", "", $bind_params_detail_faktur_last_month);

    // Hitung subtotal bulan lalu dan tambahkan ke variabel subtotal bulan lalu
    foreach ($data_faktur_detail_last_month as $detail_last_month) {
        $subtotal_last_month += ($detail_last_month['jumlah'] * $detail_last_month['harga_satuan']);
    }
}

// // Tampilkan pendapatan dengan pemisah ribuan atau titik ribuan
// echo "Pendapatan bulan saat ini: Rp " . number_format($subtotal_this_month, 0, ',', '.') . "<br>";
// echo "Pendapatan bulan lalu: Rp " . number_format($subtotal_last_month, 0, ',', '.') . "<br>";

// Menghitung perbedaan subtotal bulan ini dan bulan lalu
$difference = $subtotal_this_month - $subtotal_last_month;

// Menghitung persentase perubahan pendapatan
if ($subtotal_last_month != 0) {
    $percentage_change = ($difference / $subtotal_last_month) * 100;
} else {
    $percentage_change = 0; // Untuk menghindari pembagian dengan nol
}

// Simpan data pendapatan dalam variabel untuk di-include di index.php
$income_info = [
  'subtotal_this_month' => $subtotal_this_month,
  'subtotal_last_month' => $subtotal_last_month,
  'percentage_change' => $percentage_change
];