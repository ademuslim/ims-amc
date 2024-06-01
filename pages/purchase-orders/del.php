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
        $result1 = deleteData('detail_pesanan', "id_pesanan = '$id_pesanan'");
        if ($result1 > 0) {
            $result2 = deleteData('pesanan_pembelian', "id_pesanan = '$id_pesanan'");
            if ($result2 > 0) {
                // Jika berhasil menghapus, tampilkan pesan sukses
                $_SESSION['success_message'] = "Data PO berhasil dihapus!";

                // Pencatatan log aktivitas
                $id_log = Ramsey\Uuid\Uuid::uuid4()->toString();
                $aktivitas = 'Berhasil hapus PO';
                $tabel = 'pesanan_pembelian';
                $keterangan = 'Pengguna dengan ID ' . $id_pengguna . ' berhasil hapus PO dengan ID ' . $id_pesanan;
                $log_data = [
                    'id_log' => $id_log,
                    'id_pengguna' => $id_pengguna,
                    'aktivitas' => $aktivitas,
                    'tabel' => $tabel,
                    'keterangan' => $keterangan
                ];
                insertData('log_aktivitas', $log_data);
            } else {
                $_SESSION['error_message'] = "Terjadi kesalahan saat menghapus data PO.";
            }
        }else {
            $_SESSION['error_message'] = "Terjadi kesalahan saat menghapus data detail PO.";
        }
    }
} else {
    // Jika tidak ada ID yang diberikan, tampilkan pesan error
    $_SESSION['error_message'] = "ID pesanan tidak valid.";
}

// Redirect kembali ke index.php setelah proses selesai
header("Location: index.php?category=$category_param");
exit();
?>