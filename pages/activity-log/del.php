<?php
require '../../includes/function.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $table = 'log_aktivitas';
    $data = [
        'status_hapus' => 1
    ];
    $conditions = "id_log = $id";

    $result = updateData($table, $data, $conditions);

    if ($result > 0) {
        // Jika berhasil memperbarui status_hapus, tampilkan pesan sukses
        $_SESSION['success_message'] = "Log aktivitas berhasil dihapus dari sistem, data tetap ada dalam database!";

    } else {
        // Jika gagal menghapus, tampilkan pesan error
        $_SESSION['error_message'] = "Log aktivitas gagal dihapus dari sistem!";
    }

} else {
    // Jika tidak ada ID yang diberikan, tampilkan pesan error
    $_SESSION['error_message'] = "ID Log Aktivitas tidak valid.";
}

// Redirect kembali ke index.php setelah proses selesai
header("Location: index.php");
exit();