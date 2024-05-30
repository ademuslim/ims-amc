<?php
require '../../includes/function.php';

if (isset($_GET['id'])) {
    // Ambil ID pesanan yang akan dihapus
    $id_pesanan = $_GET['id'];

    // Ambil nilai kategori dari parameter URL
    $category_param = isset($_GET['category']) ? $_GET['category'] : '';

    // Pengecekan apakah data sedang digunakan dalam tabel lain
    $tableColumnMap = [
        // 'detail_pesanan' => ['id_pesanan'],
        'detail_faktur' => ['id_pesanan']
    ];
    $isInUse = isDataInUse($id_pesanan, $tableColumnMap);

    if ($isInUse) {
        // Jika data digunakan dalam tabel lain, tampilkan pesan error
        $_SESSION['error_message'] = "Data sedang digunakan dalam data lain dan tidak dapat dihapus.";
    } else {
        $result1 = deleteData('detail_pesanan', "id_pesanan = '$id_pesanan'");
        if ($result1 > 0) {
            $result2 = deleteData('pesanan_pembelian', "id_pesanan = '$id_pesanan'");
            if ($result2 > 0) {
                // Jika berhasil menghapus, tampilkan pesan sukses
                $_SESSION['success_message'] = "Data PO berhasil dihapus!";
            } else {
                $_SESSION['error_message'] = "Terjadi kesalahan saat menghapus data PO.";
            }
        }else {
            $_SESSION['error_message'] = "Terjadi kesalahan saat menghapus data detail PO.";
        }
    }
} else {
    // Jika tidak ada ID yang diberikan, tampilkan pesan error
    $_SESSION['error_message'] = "ID pesanan tidak valid.";
}

// Redirect kembali ke index.php setelah proses selesai
header("Location: index.php?category=$category_param");
exit();
?>