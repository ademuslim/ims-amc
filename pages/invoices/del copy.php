<?php
require '../../includes/function.php';

if (isset($_GET['id'])) {
    // Ambil ID faktur yang akan dihapus
    $id_faktur = $_GET['id'];

    // Ambil nilai kategori dari parameter URL
    $category_param = isset($_GET['category']) ? $_GET['category'] : '';

    // Pengecekan apakah data sedang digunakan dalam tabel lain
    $tableColumnMap = [
        // 'detail_faktur' => ['id_faktur'],
        'detail_faktur' => ['id_faktur']
    ];
    $isInUse = isDataInUse($id_faktur, $tableColumnMap);

    if ($isInUse) {
        // Jika data digunakan dalam tabel lain, tampilkan pesan error
        $_SESSION['error_message'] = "Data sedang digunakan dalam data lain dan tidak dapat dihapus.";
    } else {
        $result1 = deleteData('detail_faktur', "id_faktur = '$id_faktur'");
        if ($result1 > 0) {
            $result2 = deleteData('faktur', "id_faktur = '$id_faktur'");
            if ($result2 > 0) {
                // Jika berhasil menghapus, tampilkan pesan sukses
                $_SESSION['success_message'] = "Data faktur berhasil dihapus!";
            } else {
                $_SESSION['error_message'] = "Terjadi kesalahan saat menghapus data faktur.";
            }
        }else {
            $_SESSION['error_message'] = "Terjadi kesalahan saat menghapus data detail faktur.";
        }
    }
} else {
    // Jika tidak ada ID yang diberikan, tampilkan pesan error
    $_SESSION['error_message'] = "ID faktur tidak valid.";
}

// Redirect kembali ke index.php setelah proses selesai
header("Location: index.php?category=$category_param");
exit();
?>