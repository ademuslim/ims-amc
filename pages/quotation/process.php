<?php
require '../../includes/function.php';
require '../../includes/vendor/autoload.php';

if (isset($_POST['add'])) {
    $pengirim = $_POST['pengirim'];
    $no_penawaran = strtolower($_POST['no_penawaran']);
    $tanggal = $_POST['tanggal'];
    // $total = $_POST['total'];
    $catatan = strtolower($_POST['catatan']);
    $penerima = $_POST['penerima'];
    $up = strtolower($_POST['up']);
    $diskon = $_POST['diskon'];
    $jenis_ppn = $_POST['jenis_ppn'];

    $id_penawaran = Ramsey\Uuid\Uuid::uuid4()->toString();

    $data = [
        'id_penawaran' => $id_penawaran,
        'id_pengirim' => $pengirim,
        'no_penawaran' => $no_penawaran,
        'tanggal' => $tanggal,
        // 'total' => $total,
        'catatan' => $catatan,
        'id_penerima' => $penerima,
        'up' => $up,
        'diskon' => $diskon,
        'id_ppn' => $jenis_ppn
    ];

    $result = insertData('penawaran_harga', $data);

    if ($result > 0) {
        $_SESSION['success_message'] = "Penawaran harga berhasil ditambahkan!";
    } else {
        $_SESSION['error_message'] = "Terjadi kesalahan saat menambahkan penawaran harga.";
    }
} elseif (isset($_POST['edit'])) {
    $id_penawaran = $_POST['id_penawaran'];
    $pengirim = $_POST['pengirim'];
    $no_penawaran = strtolower($_POST['no_penawaran']);
    $tanggal = $_POST['tanggal'];
    $total = $_POST['total'];
    $catatan = strtolower($_POST['catatan']);
    $penerima = $_POST['penerima'];
    $up = strtolower($_POST['up']);
    $diskon = isset($_POST['diskon']) ? $_POST['diskon'] : 0;
    $jenis_ppn = $_POST['jenis_ppn'];

    $data = [
        'id_penawaran' => $id_penawaran,
        'id_pengirim' => $pengirim,
        'no_penawaran' => $no_penawaran,
        'tanggal' => $tanggal,
        'total' => $total,
        'catatan' => $catatan,
        'id_penerima' => $penerima,
        'up' => $up,
        'diskon' => $diskon,
        'id_ppn' => $jenis_ppn
    ];
    
    $conditions = "id_penawaran = '$id_penawaran'";

    $result = updateData('penawaran_harga', $data, $conditions);
  
    if ($result > 0) {
        $_SESSION['success_message'] = "Penawaran harga berhasil diupdate!";
    } else {
        $_SESSION['error_message'] = "Terjadi kesalahan saat mengupdate penawaran harga.";
    }
} else {
    $_SESSION['error_message'] = "Permintaan tidak valid!";
}

mysqli_close($conn);

header("Location: index.php");
exit();
?>