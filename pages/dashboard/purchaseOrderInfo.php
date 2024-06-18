<?php
// Hitung total PO masuk tahun ini
$conditionsPO = "kategori = 'masuk' AND YEAR(tanggal) = ? AND status_hapus = 0";
$bind_paramsPO = array(
    array('type' => 'i', 'value' => $current_year)
);
$data_po = selectData('pesanan_pembelian', $conditionsPO, "", "", $bind_paramsPO);
$total_po_incoming_curent_year = count($data_po);

// Hitung total PO masuk tahun ini dengan status draft
$conditionsPO = "kategori = 'masuk' AND YEAR(tanggal) = ? AND status = 'draft' AND status_hapus = 0";
$bind_paramsPO = array(
    array('type' => 'i', 'value' => $current_year)
);
$data_po = selectData('pesanan_pembelian', $conditionsPO, "", "", $bind_paramsPO);
$total_new_po_incoming_curent_year = count($data_po);

// Hitung total PO masuk tahun ini status = selesai
$conditionsPO = "kategori = 'masuk' AND YEAR(tanggal) = ? AND status = 'selesai' AND status_hapus = 0";
$bind_paramsPO = array(
    array('type' => 'i', 'value' => $current_year)
);
$data_po = selectData('pesanan_pembelian', $conditionsPO, "", "", $bind_paramsPO);
$total_close_po_incoming_curent_year = count($data_po);

// Hitung total PO masuk tahun ini dengan status diproses
$conditionsPO = "kategori = 'masuk' AND YEAR(tanggal) = ? AND status = 'diproses' AND status_hapus = 0";
$bind_paramsPO = array(
    array('type' => 'i', 'value' => $current_year)
);
$data_po = selectData('pesanan_pembelian', $conditionsPO, "tanggal DESC", "", $bind_paramsPO);
$total_process_po_incoming_curent_year = count($data_po);

// Grafik PO Open
$data_sisa_pesanan = array(); // Array untuk menyimpan data sisa pesanan

foreach ($data_po as $po) {
    $id_pesanan = $po['id_pesanan'];
    $mainDetailTable = 'detail_pesanan';
    $joinDetailTables = [
        ['pesanan_pembelian', 'detail_pesanan.id_pesanan = pesanan_pembelian.id_pesanan'], 
        ['produk', 'detail_pesanan.id_produk = produk.id_produk']
    ];
    $columns = 'detail_pesanan.*, produk.*';
    $conditions = "detail_pesanan.id_pesanan = '$id_pesanan'";

    $data_detail_po = selectDataJoin($mainDetailTable, $joinDetailTables, $columns, $conditions);

    if (!empty($data_detail_po)) {
        foreach ($data_detail_po as $detail) {
            // Split nama_produk into words
            $words = explode(' ', ucwords($detail['nama_produk']));
            
            // Cek jika lebih dari 4 kata
            if (count($words) > 4) {
                // Ambil 4 kata pertama dan tambahkan ellipsis
                $shortened_nama_produk = implode(' ', array_slice($words, 0, 4)) . '...';
            } else {
                // Tampilkan dengan lengkap
                $shortened_nama_produk = implode(' ', $words);
            }
            
            $data_sisa_pesanan[] = array(
                'no_pesanan' => strtoupper($po['no_pesanan']),
                'nama_produk' => $shortened_nama_produk,
                'sisa_pesanan' => $detail['sisa_pesanan']
            );
        }
    }
}

// Format Data untuk Grafik
$labels = array();
$sisa_pesanan_data = array();

foreach ($data_sisa_pesanan as $sisa) {
    $labels[] = $sisa['no_pesanan'] . ' - ' . $sisa['nama_produk'];
    $sisa_pesanan_data[] = $sisa['sisa_pesanan'];
}

// Simpan data PO dalam variabel untuk di-include di index.php
$po_info = [
  'total_po_incoming_curent_year' => $total_po_incoming_curent_year,
  'total_new_po_incoming_curent_year' => $total_new_po_incoming_curent_year,
  'total_close_po_incoming_curent_year' => $total_close_po_incoming_curent_year,
  'total_process_po_incoming_curent_year' => $total_process_po_incoming_curent_year,
  'data_sisa_pesanan' => $data_sisa_pesanan,
  'labels' => $labels,
  'sisa_pesanan_data' => $sisa_pesanan_data
];