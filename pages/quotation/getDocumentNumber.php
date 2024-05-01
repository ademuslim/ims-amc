<?php
require '../../includes/function.php';

// Gunakan fungsi getLastDocumentNumber untuk mendapatkan nomor penawaran
$month = $_GET['month']; // Ambil nilai bulan dari parameter GET
$year = $_GET['year']; // Ambil nilai tahun dari parameter GET
$new_doc_number = getLastDocumentNumber('penawaran_harga', 'no_penawaran', 'tanggal', 'ph', 'mtg', $month, $year);

// Kembalikan nomor penawaran sebagai respons
echo $new_doc_number;