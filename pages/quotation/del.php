<?php // master-data/product/del
require '../../includes/function.php';

if (isset($_GET['id'])) {
    // Ambil ID penawaran yang akan dihapus
    $id_penawaran = $_GET['id'];

    $result = deleteData('detail_penawaran', "id_penawaran = '$id_penawaran'");

    if ($result > 0) {
        // Jika berhasil menghapus, tampilkan pesan sukses
        $_SESSION['success_message'] = "Data penawaran berhasil dihapus!";
        // lanjut hapus
    } else {
        // Jika gagal menghapus, tampilkan pesan error
        $_SESSION['error_message'] = "Terjadi kesalahan saat menghapus data penawaran.";
    }

} else {
    // Jika tidak ada ID yang diberikan, tampilkan pesan error
    // $_SESSION['error_message'] = "ID penawaran tidak valid.";
}

// Redirect kembali ke index.php setelah proses selesai
header("Location: index.php");
exit();