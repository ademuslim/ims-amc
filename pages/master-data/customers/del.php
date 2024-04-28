<?php // master-data/product/del
require '../../../includes/function.php';

if (isset($_GET['id'])) {
    // Ambil ID pelanggan yang akan dihapus
    $id_pelanggan = $_GET['id'];

    // Pengecekan apakah data sedang digunakan dalam tabel lain
    $isInUse = isDataInUse('id_penerima', $id_pelanggan, ['faktur', 'penawaran_harga', 'pesanan_pembelian']);

    if ($isInUse) {
        // Jika data digunakan dalam tabel lain, tampilkan pesan error
        $_SESSION['error_message'] = "Data pelanggan sedang digunakan dalam tabel lain dan tidak dapat dihapus.";
    } else {
        // Jika data tidak digunakan dalam tabel lain, hapus data dari tabel pelanggan
        $result = deleteData('pelanggan', "id_pelanggan = '$id_pelanggan'");

        if ($result > 0) {
            // Jika berhasil menghapus, tampilkan pesan sukses
            $_SESSION['success_message'] = "Data pelanggan berhasil dihapus!";
        } else {
            // Jika gagal menghapus, tampilkan pesan error
            $_SESSION['error_message'] = "Terjadi kesalahan saat menghapus data pelanggan.";
        }
    }
} else {
    // Jika tidak ada ID yang diberikan, tampilkan pesan error
    $_SESSION['error_message'] = "ID pelanggan tidak valid.";
}

// Redirect kembali ke index.php setelah proses selesai
header("Location: index.php");
exit();