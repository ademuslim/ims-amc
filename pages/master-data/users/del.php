<?php // master-data/user/del
require '../../../includes/function.php';

if (isset($_GET['id'])) {
    // Ambil ID pengguna yang akan dihapus
    $id_pengguna = $_GET['id'];

    // Hapus data dari tabel pengguna
    $result = deleteData('pengguna', "id_pengguna = '$id_pengguna'");

    if ($result > 0) {
        // Jika berhasil menghapus, tampilkan pesan sukses
        $_SESSION['success_message'] = "Data pengguna berhasil dihapus!";
    } else {
        // Jika gagal menghapus, tampilkan pesan error
        $_SESSION['error_message'] = "Terjadi kesalahan saat menghapus data pengguna.";
    }
} else {
    // Jika tidak ada ID yang diberikan, tampilkan pesan error
    $_SESSION['error_message'] = "ID pengguna tidak valid.";
}

// Redirect kembali ke index.php setelah proses selesai
header("Location: index.php");
exit();
?>