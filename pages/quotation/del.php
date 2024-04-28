<?php // master-data/product/del
require '../../includes/function.php';

if (isset($_GET['id'])) {
    // Ambil ID penawaran yang akan dihapus
    $id_penawaran = $_GET['id'];

    // Pengecekan apakah data sedang digunakan dalam tabel lain
    $isInUse = isDataInUse('id_penawaran', $id_penawaran, ['detail_faktur', 'detail_penawaran', 'detail_pesanan']);

    if ($isInUse) {
        // Jika data digunakan dalam tabel lain, tampilkan pesan error
        $_SESSION['error_message'] = "Data penawaran sedang digunakan dalam tabel lain dan tidak dapat dihapus.";
    } else {
        // Jika data tidak digunakan dalam tabel lain, hapus data dari tabel penawaran
        $result = deleteData('penawaran_harga', "id_penawaran = '$id_penawaran'");

        if ($result > 0) {
            // Jika berhasil menghapus, tampilkan pesan sukses
            $_SESSION['success_message'] = "Data penawaran berhasil dihapus!";
        } else {
            // Jika gagal menghapus, tampilkan pesan error
            $_SESSION['error_message'] = "Terjadi kesalahan saat menghapus data penawaran.";
        }
    }
} else {
    // Jika tidak ada ID yang diberikan, tampilkan pesan error
    $_SESSION['error_message'] = "ID penawaran tidak valid.";
}

// Redirect kembali ke index.php setelah proses selesai
header("Location: index.php");
exit();