<?php
require '../../includes/function.php';
require '../../includes/vendor/autoload.php';

// Ambil id_pengguna dari sesi atau cookie untuk pencatatan log aktivitas
$id_pengguna = $_SESSION['id_pengguna'] ?? $_COOKIE['ingat_user_id'] ?? '';

if (isset($_GET['id'])) {
    // Ambil ID faktur yang akan dihapus
    $id_faktur = $_GET['id'];

    // Ambil nilai kategori dari parameter URL
    $category_param = isset($_GET['category']) ? $_GET['category'] : '';

    // Ambil semua detail faktur yang terkait dengan faktur yang akan dihapus
    $query = "SELECT * FROM detail_faktur WHERE id_faktur = '$id_faktur'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $id_detail_faktur = $row['id_detail_faktur'];
            $id_produk = $row['id_produk'];
            $jumlah_dikirim = $row['jumlah'];
            $id_pesanan = $row['id_pesanan'];

            // Kembalikan jumlah_dikirim ke tabel detail_pesanan
            updateDetailPesanan($id_produk, $id_pesanan, -$jumlah_dikirim);

            // Hapus detail faktur
            deleteData('detail_faktur', "id_detail_faktur = '$id_detail_faktur'");
        }
    }

    // Hapus faktur setelah semua detail faktur dihapus
    $result2 = deleteData('faktur', "id_faktur = '$id_faktur'");
    if ($result2 > 0) {
        // Jika berhasil menghapus, tampilkan pesan sukses
        $_SESSION['success_message'] = "Data faktur beserta detail berhasil dihapus!";

        // Pencatatan log aktivitas
        $id_log = Ramsey\Uuid\Uuid::uuid4()->toString();
        $aktivitas = 'Berhasil hapus invoice';
        $tabel = 'faktur';
        $keterangan = 'Pengguna dengan ID ' . $id_pengguna . ' berhasil hapus invoice dengan ID ' . $id_faktur;
        $log_data = [
            'id_log' => $id_log,
            'id_pengguna' => $id_pengguna,
            'aktivitas' => $aktivitas,
            'tabel' => $tabel,
            'keterangan' => $keterangan
        ];
        insertData('log_aktivitas', $log_data);
    } else {
        $_SESSION['error_message'] = "Terjadi kesalahan saat menghapus data faktur.";

        // Pencatatan log aktivitas
        $id_log = Ramsey\Uuid\Uuid::uuid4()->toString();
        $aktivitas = 'Gagal hapus invoice';
        $tabel = 'faktur';
        $keterangan = 'Pengguna dengan ID ' . $id_pengguna . ' gagal hapus invoice dengan ID ' . $id_faktur;
        $log_data = [
            'id_log' => $id_log,
            'id_pengguna' => $id_pengguna,
            'aktivitas' => $aktivitas,
            'tabel' => $tabel,
            'keterangan' => $keterangan
        ];
        insertData('log_aktivitas', $log_data);
    }
} else {
    // Jika tidak ada ID yang diberikan, tampilkan pesan error
    $_SESSION['error_message'] = "ID faktur tidak valid.";
}

// Redirect kembali ke index.php setelah proses selesai
header("Location: index.php?category=$category_param");
exit();
?>