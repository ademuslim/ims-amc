<?php
// Hitung total PH keluar tahun ini
$conditionsPH = "kategori = 'keluar' AND YEAR(tanggal) = ? AND status_hapus = 0";
$bind_paramsPH = array(
    array('type' => 'i', 'value' => $current_year)
);
$data_ph = selectData('penawaran_harga', $conditionsPH, "", "", $bind_paramsPH);
$total_ph_outgoing_curent_year = count($data_ph);

// Hitung total PH keluar tahun ini dengan status draft
$conditionsPH = "kategori = 'keluar' AND YEAR(tanggal) = ? AND status = 'draft' AND status_hapus = 0";
$bind_paramsPH = array(
    array('type' => 'i', 'value' => $current_year)
);
$data_ph = selectData('penawaran_harga', $conditionsPH, "", "", $bind_paramsPH);
$total_draft_ph_outgoing_curent_year = count($data_ph);

// Hitung total PH keluar tahun ini dengan status disetujui
$conditionsPH = "kategori = 'keluar' AND YEAR(tanggal) = ? AND status = 'disetujui' AND status_hapus = 0";
$bind_paramsPH = array(
    array('type' => 'i', 'value' => $current_year)
);
$data_ph = selectData('penawaran_harga', $conditionsPH, "tanggal DESC", "", $bind_paramsPH);
$total_approved_ph_outgoing_curent_year = count($data_ph);

// Hitung total PH keluar tahun ini dengan status ditolak
$conditionsPH = "kategori = 'keluar' AND YEAR(tanggal) = ? AND status = 'ditolak' AND status_hapus = 0";
$bind_paramsPH = array(
    array('type' => 'i', 'value' => $current_year)
);
$data_ph = selectData('penawaran_harga', $conditionsPH, "tanggal DESC", "", $bind_paramsPH);
$total_rejected_ph_outgoing_curent_year = count($data_ph);

// Hitung total PH keluar tahun ini dengan status disetujui atau ditolak
$conditionsPH = "kategori = 'keluar' AND YEAR(tanggal) = ? AND status IN ('disetujui', 'ditolak') AND status_hapus = 0";
$bind_paramsPH = array(
    array('type' => 'i', 'value' => $current_year)
);
$data_ph = selectData('penawaran_harga', $conditionsPH, "tanggal DESC", "", $bind_paramsPH);
$total_sending_ph_outgoing_curent_year = count($data_ph);

// Simpan data PH dalam variabel untuk di-include di index.php
$ph_info = [
  'total_ph_outgoing_curent_year' => $total_ph_outgoing_curent_year,
  'total_draft_ph_outgoing_curent_year' => $total_draft_ph_outgoing_curent_year,
  'total_approved_ph_outgoing_curent_year' => $total_approved_ph_outgoing_curent_year,
  'total_rejected_ph_outgoing_curent_year' => $total_rejected_ph_outgoing_curent_year,
  'total_sending_ph_outgoing_curent_year' => $total_sending_ph_outgoing_curent_year,
];