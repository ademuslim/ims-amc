<?php
require '../../../includes/function.php';

if (isset($_GET['id'])) {
    // Ambil ID ppn yang akan dihapus
    $id = $_GET['id'];

    // Pengecekan apakah data sedang digunakan dalam tabel lain
    $tableColumnMap = [
        'faktur' => ['id_ppn'],
        'penawaran' => ['id_ppn'],
        'pesanan' => ['id_ppn']
    ];
    $isInUse = isDataInUse($id, $tableColumnMap);

    if ($isInUse) {
        // Jika data digunakan dalam tabel lain, tampilkan pesan error
        $_SESSION['error_message'] = "Data ppn sedang digunakan dalam tabel lain dan tidak dapat dihapus.";
    } else {
        // Jika data tidak digunakan dalam tabel lain, hapus data dari tabel ppn
        $result = deleteData('ppn', "id_ppn = '$id_ppn'");

        if ($result > 0) {
            // Jika berhasil menghapus, tampilkan pesan sukses
            $_SESSION['success_message'] = "Data ppn berhasil dihapus!";
        } else {
            // Jika gagal menghapus, tampilkan pesan error
            $_SESSION['error_message'] = "Terjadi kesalahan saat menghapus data ppn.";
        }
    }
} else {
    // Jika tidak ada ID yang diberikan, tampilkan pesan error
    $_SESSION['error_message'] = "ID ppn tidak valid.";
}

// Redirect kembali ke index.php setelah proses selesai
header("Location: index.php");
exit();