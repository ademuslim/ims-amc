<?php
require '../../../includes/function.php';
require '../../../includes/vendor/autoload.php';

// Ambil id_pengguna dari sesi atau cookie untuk pencatatan log aktivitas
$id_pengguna = $_SESSION['id_pengguna'] ?? $_COOKIE['ingat_user_id'] ?? '';

if (isset($_GET['id'])) {
    // Ambil ID produk yang akan dihapus
    $id = $_GET['id'];

    // Pengecekan apakah data sedang digunakan dalam tabel lain
    $tableColumnMap = [
        'detail_faktur' => ['id_produk'],
        'detail_penawaran' => ['id_produk'],
        'detail_pesanan' => ['id_produk']
    ];
    $isInUse = isDataInUse($id, $tableColumnMap);

    if ($isInUse) {
        // Jika data digunakan dalam tabel lain, tampilkan pesan error
        $_SESSION['error_message'] = "Data sedang digunakan dalam data lain dan tidak dapat dihapus.";
    } else {
        // Jika data tidak digunakan dalam tabel lain, hapus data dari tabel produk
        $result = deleteData('produk', "id_produk = '$id'");

        if ($result > 0) {
            // Jika berhasil menghapus, tampilkan pesan sukses
            $_SESSION['success_message'] = "Data berhasil dihapus!";

            // Pencatatan log aktivitas
            $aktivitas = 'Berhasil hapus produk';
            $tabel = 'produk';
            $keterangan = 'Pengguna dengan ID ' . $id_pengguna . ' berhasil hapus produk dengan ID ' . $id;
            $log_data = [
                'id_pengguna' => $id_pengguna,
                'aktivitas' => $aktivitas,
                'tabel' => $tabel,
                'keterangan' => $keterangan
            ];
            insertData('log_aktivitas', $log_data);
        } else {
            // Jika gagal menghapus, tampilkan pesan error
            $_SESSION['error_message'] = "Terjadi kesalahan saat menghapus data.";

            // Pencatatan log aktivitas
            $aktivitas = 'Gagal hapus produk';
            $tabel = 'produk';
            $keterangan = 'Pengguna dengan ID ' . $id_pengguna . ' gagal hapus produk dengan ID ' . $id;
            $log_data = [
                'id_pengguna' => $id_pengguna,
                'aktivitas' => $aktivitas,
                'tabel' => $tabel,
                'keterangan' => $keterangan
            ];
            insertData('log_aktivitas', $log_data);
        }
    }
} else {
    // Jika tidak ada ID yang diberikan, tampilkan pesan error
    $_SESSION['error_message'] = "ID tidak valid.";
}

// Redirect kembali ke index.php setelah proses selesai
header("Location: index.php");
exit();