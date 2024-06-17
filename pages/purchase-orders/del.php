<?php
require '../../includes/function.php';
require '../../includes/vendor/autoload.php';

// Ambil id_pengguna dari sesi atau cookie untuk pencatatan log aktivitas
$id_pengguna = $_SESSION['id_pengguna'] ?? $_COOKIE['ingat_user_id'] ?? '';

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
        $table = 'pesanan_pembelian';
        $data = [
            'status_hapus' => 1
        ];
        $conditions = "id_pesanan = '$id_pesanan'";

        $result = updateData($table, $data, $conditions);

        if ($result > 0) {
            // Jika berhasil menghapus, tampilkan pesan sukses
            $_SESSION['success_message'] = "Data pesanan pembelian berhasil dihapus dari sistem, data tetap ada dalam database!";

            // Pencatatan log aktivitas
            $aktivitas = 'Berhasil hapus data';
            $tabel = 'Pesanan Pembelian';
            $keterangan = "Berhasil hapus pesanan pembelian dengan ID $id_pesanan, data tetap ada dalam database!";
            $log_data = [
                'id_pengguna' => $id_pengguna,
                'aktivitas' => $aktivitas,
                'tabel' => $tabel,
                'keterangan' => $keterangan
            ];
            insertData('log_aktivitas', $log_data);
        } else {
            // Jika gagal menghapus, tampilkan pesan error
            $_SESSION['error_message'] = "Data pesanan pembelian gagal dihapus dari sistem!";
        }
    }
} else {
    // Jika tidak ada ID yang diberikan, tampilkan pesan error
    $_SESSION['error_message'] = "ID pesanan pembelian tidak valid.";
}

header("Location: index.php?category=$category_param");
exit();
?>