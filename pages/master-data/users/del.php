<?php // master-data/user/del
require '../../../includes/function.php';
require '../../../includes/vendor/autoload.php';

// Ambil id_pengguna dari sesi atau cookie untuk pencatatan log aktivitas
$id_pengguna_log = $_SESSION['id_pengguna'] ?? $_COOKIE['ingat_user_id'] ?? '';

if (isset($_GET['id'])) {
    // Ambil ID pengguna yang akan dihapus
    $id_pengguna = $_GET['id'];

    // Pengecekan apakah data sedang digunakan dalam tabel lain
    $tableColumnMap = [
        'log_aktivitas' => ['id_pengguna']
    ];
    $isInUse = isDataInUse($id_pengguna, $tableColumnMap);

    if ($isInUse) {
        // Jika data digunakan dalam tabel lain, tampilkan pesan error
        $_SESSION['error_message'] = "Data sedang digunakan dalam data lain dan tidak dapat dihapus.";
    } else {
        // Jika data tidak digunakan dalam tabel lain, hapus data dari tabel produk
        $result = deleteData('pengguna', "id_pengguna = '$id_pengguna'");

        if ($result > 0) {
            // Jika berhasil menghapus, tampilkan pesan sukses
            $_SESSION['success_message'] = "Data pengguna berhasil dihapus!";

            // Pencatatan log aktivitas
            $id_log = Ramsey\Uuid\Uuid::uuid4()->toString();
            $aktivitas = 'Berhasil hapus pengguna';
            $tabel = 'pengguna';
            $keterangan = 'Pengguna dengan ID ' . $id_pengguna_log . ' berhasil hapus pengguna dengan ID ' . $id_pengguna;
            $log_data = [
                'id_log' => $id_log,
                'id_pengguna' => $id_pengguna_log,
                'aktivitas' => $aktivitas,
                'tabel' => $tabel,
                'keterangan' => $keterangan
            ];
            insertData('log_aktivitas', $log_data);
        } else {
            // Jika gagal menghapus, tampilkan pesan error
            $_SESSION['error_message'] = "Terjadi kesalahan saat menghapus data pengguna.";
        }
    }
} else {
    // Jika tidak ada ID yang diberikan, tampilkan pesan error
    $_SESSION['error_message'] = "ID pengguna tidak valid.";
}

// Redirect kembali ke index.php setelah proses selesai
header("Location: index.php");
exit();
?>