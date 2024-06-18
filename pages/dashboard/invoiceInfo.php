<?php
// Hitung total semua Invoice keluar tahun ini
$conditionsInv = "kategori = 'keluar' AND YEAR(tanggal) = ? AND status_hapus = 0";
$bind_paramsInv = array(
    array('type' => 'i', 'value' => $current_year)
);
$data_inv = selectData('faktur', $conditionsInv, "", "", $bind_paramsInv);
$total_inv_outgoing_curent_year = count($data_inv);

// Hitung total Invoice keluar tahun ini dengan status tunggu kirim
$conditionsInv = "kategori = 'keluar' AND YEAR(tanggal) = ? AND status = 'tunggu kirim' AND status_hapus = 0";
$bind_paramsInv = array(
    array('type' => 'i', 'value' => $current_year)
);
$data_inv = selectData('faktur', $conditionsInv, "", "", $bind_paramsInv);
$total_waiting_inv_outgoing_curent_year = count($data_inv);

// Hitung total Invoice keluar tahun ini dengan status dibayar
$conditionsInv = "kategori = 'keluar' AND YEAR(tanggal) = ? AND status = 'dibayar'";
$bind_paramsInv = array(
    array('type' => 'i', 'value' => $current_year)
);
$data_inv = selectData('faktur', $conditionsInv, "tanggal DESC", "", $bind_paramsInv);
$total_paid_inv_outgoing_curent_year = count($data_inv);

// Hitung total Invoice keluar tahun ini dengan status belum dibayar
$conditionsInv = "kategori = 'keluar' AND YEAR(tanggal) = ? AND status = 'belum dibayar' AND status_hapus = 0";
$bind_paramsInv = array(
    array('type' => 'i', 'value' => $current_year)
);
$data_inv = selectData('faktur', $conditionsInv, "tanggal DESC", "", $bind_paramsInv);
$total_unpaid_inv_outgoing_curent_year = count($data_inv);

// Hitung total Invoice keluar tahun ini dengan status dibayar atau belum dibayar
$conditionsInv = "kategori = 'keluar' AND YEAR(tanggal) = ? AND status IN ('dibayar', 'belum dibayar') AND status_hapus = 0";
$bind_paramsInv = array(
    array('type' => 'i', 'value' => $current_year)
);
$data_inv = selectData('faktur', $conditionsInv, "tanggal DESC", "", $bind_paramsInv);
$total_sending_inv_outgoing_curent_year = count($data_inv);

// Grapik pendapatan perbulan
$monthly_revenue = array_fill(0, 12, 0); // Array untuk menyimpan total pendapatan per bulan

for ($month = 1; $month <= 12; $month++) {
    $conditionsFaktur = "kategori = 'keluar' AND MONTH(tanggal) = ? AND YEAR(tanggal) = ? AND status = 'dibayar' AND status_hapus = 0";
    $bind_paramsFaktur = array(
        array('type' => 'i', 'value' => $month),
        array('type' => 'i', 'value' => $current_year)
    );

    $data_faktur = selectData('faktur', $conditionsFaktur, "", "", $bind_paramsFaktur);
    $subtotalPerMonth = 0;
    foreach ($data_faktur as $faktur) {
        $id_faktur = $faktur['id_faktur'];
        $conditionsDetailFaktur = "id_faktur = ?";
        $bind_paramsDetailFaktur = array(
            array('type' => 'i', 'value' => $id_faktur)
        );

        $data_faktur_detail = selectData('detail_faktur', $conditionsDetailFaktur, "", "", $bind_paramsDetailFaktur);
        foreach ($data_faktur_detail as $detail) {
            $subtotalPerMonth += ($detail['jumlah'] * $detail['harga_satuan']);
        }
    }

    $monthly_revenue[$month - 1] = $subtotalPerMonth;
}

// Simpan data invoice dalam variabel untuk di-include di index.php
$invoice_info = [
  'total_inv_outgoing_curent_year' => $total_inv_outgoing_curent_year,
  'total_waiting_inv_outgoing_curent_year' => $total_waiting_inv_outgoing_curent_year,
  'total_paid_inv_outgoing_curent_year' => $total_paid_inv_outgoing_curent_year,
  'total_unpaid_inv_outgoing_curent_year' => $total_unpaid_inv_outgoing_curent_year,
  'total_sending_inv_outgoing_curent_year' => $total_sending_inv_outgoing_curent_year,
  'monthly_revenue' => $monthly_revenue
];