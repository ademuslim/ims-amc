<?php // master-data/product/del
require '../../../includes/function.php';

if (isset($_GET['id'])) {
    // Ambil ID produk yang akan dihapus
    $id_kontak = $_GET['id'];

    // Ambil nilai kategori dari parameter URL
    $category_param = isset($_GET['category']) ? $_GET['category'] : '';

    // Pengecekan apakah data sedang digunakan dalam tabel lain
    $isInUse = isDataInUse(
        $id_kontak, 
        [
            ['table' => 'faktur', 'columns' => ['id_pengirim', 'id_penerima']],
            ['table' => 'penawaran_harga', 'columns' => ['id_pengirim', 'id_penerima']],
            ['table' => 'pesanan_pembelian', 'columns' => ['id_pengirim', 'id_penerima']]
        ]
    );

    if ($isInUse) {
        // Jika data digunakan dalam tabel lain, tampilkan pesan error
        $_SESSION['error_message'] = "Data kontak sedang digunakan dalam tabel lain dan tidak dapat dihapus.";
    } else {
        // Jika data tidak digunakan dalam tabel lain, hapus data dari tabel kontak
        $result = deleteData('kontak', "id_kontak = '$id_kontak'");

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
    $_SESSION['error_message'] = "ID kontak tidak valid.";
}

// Redirect kembali ke index.php setelah proses selesai
header("Location: index.php?category=$category_param");
exit();
?>