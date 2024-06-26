<?php
require '../../../includes/function.php';
require '../../../includes/vendor/autoload.php';

// Ambil id_pengguna dari sesi atau cookie untuk pencatatan log aktivitas
$id_pengguna = $_SESSION['id_pengguna'] ?? $_COOKIE['ingat_user_id'] ?? '';

if (isset($_GET['id'])) {
    // Ambil ID rekening_kontak yang akan dihapus
    $id = $_GET['id'];

    // Cek apakah id_kontak terkait dengan faktur sebagai pengirim
    $query = "SELECT COUNT(*) AS jumlah FROM faktur WHERE id_pengirim = (SELECT id_kontak FROM rekening_kontak WHERE id_rekening = '$id')";
    $result = selectDataCustomQuery($query);

    if (!empty($result) && $result[0]['jumlah'] > 0) {
        // Jika id_kontak terkait dengan faktur sebagai pengirim, tampilkan pesan error
        $_SESSION['error_message'] = "Data rekening kontak tidak dapat dihapus karena terkait dengan data faktur!";
    } else {
        // Jika tidak terkait dengan faktur, lanjutkan dengan penghapusan
        $table = 'rekening_kontak';
        $data = [
            'status_hapus' => 1
        ];
        $conditions = "id_rekening = '$id'";

        $result = updateData($table, $data, $conditions);

        if ($result > 0) {
            // Jika berhasil menghapus, tampilkan pesan sukses
            $_SESSION['success_message'] = "Data rekening kontak berhasil dihapus dari sistem, data tetap ada dalam database!";

            // Pencatatan log aktivitas
            $aktivitas = 'Berhasil hapus data';
            $tabel = 'Rekening Kontak';
            $keterangan = "Berhasil hapus rekening kontak dengan ID $id, data tetap ada dalam database!";
            $log_data = [
                'id_pengguna' => $id_pengguna,
                'aktivitas' => $aktivitas,
                'tabel' => $tabel,
                'keterangan' => $keterangan
            ];
            insertData('log_aktivitas', $log_data);
        } else {
            // Jika gagal menghapus, tampilkan pesan error
            $_SESSION['error_message'] = "Data rekening kontak gagal dihapus dari sistem!";
        }
    }
} else {
    // Jika tidak ada ID yang diberikan, tampilkan pesan error
    $_SESSION['error_message'] = "ID rekening kontak tidak valid.";
}

// Redirect kembali ke index.php setelah proses selesai
header("Location: index.php");
exit();
?>