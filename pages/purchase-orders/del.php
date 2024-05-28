<?php // master-data/product/del
require '../../includes/function.php';

if (isset($_GET['id'])) {
    // Ambil ID penawaran yang akan dihapus
    $id_pesanan = $_GET['id'];

    // Ambil nilai kategori dari parameter URL
    $category_param = isset($_GET['category']) ? $_GET['category'] : '';

    // Pengecekan apakah data sedang digunakan dalam tabel lain
    $isInUse = isDataInUse(
        $id_pesanan, 
        [
            ['table' => 'detail_pesanan', 'columns' => ['id_pesanan']],
        ]
    );

    $result = deleteData('detail_pesanan', "id_pesanan = '$id_pesanan'");

    if ($result > 0) {
        deleteData('pesanan_pembelian', "id_pesanan = '$id_pesanan'");
        // Jika berhasil menghapus, tampilkan pesan sukses
        $_SESSION['success_message'] = "Data pesanan pembelian berhasil dihapus!";
    } else {
        // Jika gagal menghapus, tampilkan pesan error
        $_SESSION['error_message'] = "Terjadi kesalahan saat menghapus data pesanan pembelian.";
    }

} else {
    // Jika tidak ada ID yang diberikan, tampilkan pesan error
    $_SESSION['error_message'] = "ID penawaran tidak valid.";
}


// Redirect kembali ke index.php setelah proses selesai
header("Location: index.php?category=$category_param");
exit();