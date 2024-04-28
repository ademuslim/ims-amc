<?php // master-data/product/del
require '../../../includes/function.php';

if (isset($_GET['id'])) {
    // Ambil ID produk yang akan dihapus
    $id_pengirim = $_GET['id'];

    // Pengecekan apakah data sedang digunakan dalam tabel lain
    $isInUse = isDataInUse('id_pengirim', $id_pengirim, ['faktur', 'penawaran_harga', 'pesanan_pembelian']);

    if ($isInUse) {
        // Jika data digunakan dalam tabel lain, tampilkan pesan error
        $_SESSION['error_message'] = "Data kontak sedang digunakan dalam tabel lain dan tidak dapat dihapus.";
    } else {
        // Jika data tidak digunakan dalam tabel lain, hapus data dari tabel kontak
        $result = deleteData('kontak_internal', "id_pengirim = '$id_pengirim'");

        if ($result > 0) {
            // Jika berhasil menghapus, tampilkan pesan sukses
            $_SESSION['success_message'] = "Data kontak berhasil dihapus!";
        } else {
            // Jika gagal menghapus, tampilkan pesan error
            $_SESSION['error_message'] = "Terjadi kesalahan saat menghapus data kontak.";
        }
    }
} else {
    // Jika tidak ada ID yang diberikan, tampilkan pesan error
    $_SESSION['error_message'] = "ID produk tidak valid.";
}

// Redirect kembali ke index.php setelah proses selesai
header("Location: index.php");
exit();