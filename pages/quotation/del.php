<?php
require '../../includes/function.php';

if (isset($_GET['id'])) {
    // Ambil ID penawaran yang akan dihapus
    $id_penawaran = $_GET['id'];

    // Ambil nilai kategori dari parameter URL
    $category_param = isset($_GET['category']) ? $_GET['category'] : '';

    // Pengecekan apakah data sedang digunakan dalam tabel lain
    $tableColumnMap = [
        // 'detail_penawaran' => ['id_penawaran'],
        'detail_pesanan' => ['id_penawaran']
    ];
    $isInUse = isDataInUse($id_penawaran, $tableColumnMap);

    if ($isInUse) {
        // Jika data digunakan dalam tabel lain, tampilkan pesan error
        $_SESSION['error_message'] = "Data sedang digunakan dalam data lain dan tidak dapat dihapus.";
    } else {
        $result1 = deleteData('detail_penawaran', "id_penawaran = '$id_penawaran'");
        if ($result1 > 0) {
            $result2 = deleteData('penawaran_harga', "id_penawaran = '$id_penawaran'");
            if ($result2 > 0) {
                // Jika berhasil menghapus, tampilkan pesan sukses
                $_SESSION['success_message'] = "Data penawaran harga berhasil dihapus!";
            } else {
                $_SESSION['error_message'] = "Terjadi kesalahan saat menghapus data penawaran harga.";
            }
        }else {
            $_SESSION['error_message'] = "Terjadi kesalahan saat menghapus data detail penawaran harga.";
        }
    }
} else {
    // Jika tidak ada ID yang diberikan, tampilkan pesan error
    $_SESSION['error_message'] = "ID penawaran tidak valid.";
}

// Redirect kembali ke index.php setelah proses selesai
header("Location: index.php?category=$category_param");
exit();
?>