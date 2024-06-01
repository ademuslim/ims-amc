<?php
require '../../../includes/function.php';
require '../../../includes/vendor/autoload.php';

// Ambil id_pengguna dari sesi atau cookie untuk pencatatan log aktivitas
$id_pengguna = $_SESSION['id_pengguna'] ?? $_COOKIE['ingat_user_id'] ?? '';

if (isset($_GET['id'])) {
    // Ambil ID ppn yang akan dihapus
    $id = $_GET['id'];

    // Pengecekan apakah data sedang digunakan dalam tabel lain
    $tableColumnMap = [
        'faktur' => ['id_ppn'],
        'penawaran_harga' => ['id_ppn'],
        'pesanan_pembelian' => ['id_ppn']
    ];
    $isInUse = isDataInUse($id, $tableColumnMap);

    if ($isInUse) {
        // Jika data digunakan dalam tabel lain, tampilkan pesan error
        $_SESSION['error_message'] = "Data ppn sedang digunakan dalam tabel lain dan tidak dapat dihapus.";
    } else {
        // Jika data tidak digunakan dalam tabel lain, hapus data dari tabel ppn
        $result = deleteData('ppn', "id_ppn = '$id'");

        if ($result > 0) {
            // Jika berhasil menghapus, tampilkan pesan sukses
            $_SESSION['success_message'] = "Data ppn berhasil dihapus!";

            // Pencatatan log aktivitas
            $id_log = Ramsey\Uuid\Uuid::uuid4()->toString();
            $aktivitas = 'Berhasil hapus ppn';
            $tabel = 'ppn';
            $keterangan = 'Pengguna dengan ID ' . $id_pengguna . ' berhasil hapus ppn dengan ID ' . $id;
            $log_data = [
                'id_log' => $id_log,
                'id_pengguna' => $id_pengguna,
                'aktivitas' => $aktivitas,
                'tabel' => $tabel,
                'keterangan' => $keterangan
            ];
            insertData('log_aktivitas', $log_data);
        } else {
            // Jika gagal menghapus, tampilkan pesan error
            $_SESSION['error_message'] = "Terjadi kesalahan saat menghapus data ppn.";

            // Pencatatan log aktivitas
            $id_log = Ramsey\Uuid\Uuid::uuid4()->toString();
            $aktivitas = 'Gagal hapus ppn';
            $tabel = 'ppn';
            $keterangan = 'Pengguna dengan ID ' . $id_pengguna . ' gagal hapus ppn dengan ID ' . $id;
            $log_data = [
                'id_log' => $id_log,
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
    $_SESSION['error_message'] = "ID ppn tidak valid.";
}

// Redirect kembali ke index.php setelah proses selesai
header("Location: index.php");
exit();