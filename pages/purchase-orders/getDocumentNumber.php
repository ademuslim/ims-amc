<?php
require '../../includes/function.php';

// Gunakan fungsi getLastDocumentNumber untuk mendapatkan nomor penawaran
$month = $_GET['month']; // Ambil nilai bulan dari parameter GET
$year = $_GET['year']; // Ambil nilai tahun dari parameter GET
$new_doc_number = getLastDocumentNumber('pesanan_pembelian', 'no_pesanan', 'tanggal', 'po', 'mtg', $month, $year);

// Kembalikan nomor penawaran sebagai respons
echo $new_doc_number;