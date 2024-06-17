<?php
require '../../includes/function.php';

// Gunakan fungsi getLastDocumentNumber untuk mendapatkan nomor faktur
$month = $_GET['month']; // Ambil nilai bulan dari parameter GET
$year = $_GET['year']; // Ambil nilai tahun dari parameter GET
$new_doc_number = getLastDocumentNumber('faktur', 'no_faktur', 'tanggal', 'inv', 'mtg', $month, $year);

// Kembalikan nomor faktur sebagai respons
echo $new_doc_number;