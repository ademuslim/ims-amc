<?php // master-data/product/del
require '../../../includes/function.php';
require '../../../includes/vendor/autoload.php';

// Ambil id_pengguna dari sesi atau cookie untuk pencatatan log aktivitas
$id_pengguna = $_SESSION['id_pengguna'] ?? $_COOKIE['ingat_user_id'] ?? '';
$category_param = isset($_GET['category']) ? $_GET['category'] : '';

if (isset($_GET['id'])) {
    // Ambil ID produk yang akan dihapus
    $id = $_GET['id'];

    // Pengecekan apakah data sedang digunakan dalam tabel lain
    $tableColumnMap = [
        'faktur' => ['id_pengirim', 'id_penerima'],
        'pesanan_pembelian' => ['id_pengirim', 'id_penerima'],
        'penawaran_harga' => ['id_pengirim', 'id_penerima']
    ];
    $isInUse = isDataInUse($id, $tableColumnMap);

    if ($isInUse) {
        // Jika data digunakan dalam tabel lain, tampilkan pesan error
        $_SESSION['error_message'] = "Data sedang digunakan dalam tabel lain dan tidak dapat dihapus.";
    } else {
        $table = 'kontak';
        $data = [
            'status_hapus' => 1
        ];
        $conditions = "id_kontak = '$id'";

        $result = updateData($table, $data, $conditions);

        if ($result > 0) {
            // Jika berhasil menghapus, tampilkan pesan sukses
            $_SESSION['success_message'] = "Data kontak berhasil dihapus dari sistem, data tetap ada dalam database!";

            // Pencatatan log aktivitas
            $aktivitas = 'Berhasil hapus data';
            $tabel = 'Kontak';
            $keterangan = "Berhasil hapus kontak dengan ID $id, data tetap ada dalam database!";
            $log_data = [
                'id_pengguna' => $id_pengguna,
                'aktivitas' => $aktivitas,
                'tabel' => $tabel,
                'keterangan' => $keterangan
            ];
            insertData('log_aktivitas', $log_data);
        } else {
            // Jika gagal menghapus, tampilkan pesan error
            $_SESSION['error_message'] = "Data kontak gagal dihapus dari sistem!";
        }
    }
} else {
    // Jika tidak ada ID yang diberikan, tampilkan pesan error
    $_SESSION['error_message'] = "ID kontak tidak valid.";
}

header("Location: index.php?category=$category_param");
exit();
?>