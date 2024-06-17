<?php
require '../../includes/function.php';
require '../../includes/vendor/autoload.php';

// Ambil id_pengguna dari sesi atau cookie untuk pencatatan log aktivitas
$id_pengguna = $_SESSION['id_pengguna'] ?? $_COOKIE['ingat_user_id'] ?? '';
$category_param = isset($_GET['category']) ? $_GET['category'] : '';

if (isset($_GET['id'])) {
    // Ambil ID penawaran yang akan dihapus
    $id_penawaran = $_GET['id'];

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
        $table = 'penawaran_harga';
        $data = [
            'status_hapus' => 1
        ];
        $conditions = "id_penawaran = '$id_penawaran'";

        $result = updateData($table, $data, $conditions);

        if ($result > 0) {
            // Jika berhasil menghapus, tampilkan pesan sukses
            $_SESSION['success_message'] = "Data penawaran harga berhasil dihapus dari sistem, data tetap ada dalam database!";

            // Pencatatan log aktivitas
            $aktivitas = 'Berhasil hapus data';
            $tabel = 'Penawaran Harga';
            $keterangan = "Berhasil hapus penawaran harga dengan ID $id_penawaran, data tetap ada dalam database!";
            $log_data = [
                'id_pengguna' => $id_pengguna,
                'aktivitas' => $aktivitas,
                'tabel' => $tabel,
                'keterangan' => $keterangan
            ];
            insertData('log_aktivitas', $log_data);
        } else {
            // Jika gagal menghapus, tampilkan pesan error
            $_SESSION['error_message'] = "Data penawaran harga gagal dihapus dari sistem!";
        }
    }
} else {
    // Jika tidak ada ID yang diberikan, tampilkan pesan error
    $_SESSION['error_message'] = "ID penawaran harga tidak valid.";
}

header("Location: index.php?category=$category_param");
exit();
?>