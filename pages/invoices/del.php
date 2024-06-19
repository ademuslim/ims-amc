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

    // Periksa terlebih dahulu status faktur, hanya boleh dihapus jika status "tunggu kirim"
    $query = "SELECT status FROM faktur WHERE id_faktur = '$id_faktur'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $faktur = mysqli_fetch_assoc($result);
        $status_faktur = $faktur['status'];
        
        if ($status_faktur !== 'tunggu kirim') {
            // Jika status bukan "tunggu kirim", atur pesan error dan lakukan redirect
            $_SESSION['error_message'] = "Hanya faktur dengan status 'tunggu kirim' yang dapat dihapus.";
            header("Location: " . base_url("pages/invoices/$category_param"));
            exit(); // Pastikan untuk keluar dari skrip setelah redirect
        }
    } else {
        // Jika query tidak mengembalikan hasil, atau terjadi kesalahan, atur pesan error dan redirect
        $_SESSION['error_message'] = "Faktur tidak ditemukan atau terjadi kesalahan dalam mengambil status faktur.";
        header("Location: " . base_url("pages/invoices/$category_param"));
        exit(); // Pastikan untuk keluar dari skrip setelah redirect
    }

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

            // Biarkan detail faktur tetap ada
            // deleteData('detail_faktur', "id_detail_faktur = '$id_detail_faktur'");
        }
    }

    // Update status hapus menjadi true
    $table = 'faktur';
    $data = [
        'status_hapus' => 1
    ];
    $conditions = "id_faktur = '$id_faktur'";

    $result = updateData($table, $data, $conditions);

    if ($result > 0) {
        // Jika berhasil menghapus, tampilkan pesan sukses
        $_SESSION['success_message'] = "Data invoice berhasil dihapus dari sistem (soft delete).";

        // Pencatatan log aktivitas
        $aktivitas = 'Berhasil hapus data';
        $tabel = 'Faktur';
        $keterangan = "Berhasil hapus invoice dengan ID $id_faktur (soft delete).";
        $log_data = [
            'id_pengguna' => $id_pengguna,
            'aktivitas' => $aktivitas,
            'tabel' => $tabel,
            'keterangan' => $keterangan
        ];
        insertData('log_aktivitas', $log_data);
    } else {
        // Jika gagal menghapus, tampilkan pesan error
        $_SESSION['error_message'] = "Data invoice gagal dihapus dari sistem!";
    }
} else {
    // Jika tidak ada ID yang diberikan, tampilkan pesan error
    $_SESSION['error_message'] = "ID faktur tidak valid.";
}

// Redirect kembali ke index.php setelah proses selesai
header("Location: " . base_url("pages/invoices/$category_param"));
exit();
?>