<?php
require_once "../../../_config.php";

if (isset($_POST['add'])) {
    $no_penawaran = $_POST['no_penawaran'];
    $tanggal = $_POST['tanggal'];
    $total = $_POST['total'];
    $catatan = $_POST['catatan'];
    $id_penerima = $_POST['id_penerima'];
    $diskon = $_POST['diskon'];
    $id_ppn = $_POST['id_ppn'];

    $query = "INSERT INTO penawaran_harga (no_penawaran, tanggal, total, catatan, id_penerima, diskon, id_ppn) 
              VALUES ('$no_penawaran', '$tanggal', '$total', '$catatan', '$id_penerima', '$diskon', '$id_ppn')";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo "<script>alert('Tambah data penawaran harga berhasil.'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Tambah data penawaran harga gagal.'); window.location='add.php';</script>";
    }
} elseif (isset($_POST['edit'])) {
    $id_penawaran = $_POST['id_penawaran'];
    $no_penawaran = $_POST['no_penawaran'];
    $tanggal = $_POST['tanggal'];
    $total = $_POST['total'];
    $catatan = $_POST['catatan'];
    $id_penerima = $_POST['id_penerima'];
    $diskon = $_POST['diskon'];
    $id_ppn = $_POST['id_ppn'];

    $query = "UPDATE penawaran_harga 
              SET no_penawaran='$no_penawaran', tanggal='$tanggal', total='$total', catatan='$catatan', 
                  id_penerima='$id_penerima', diskon='$diskon', id_ppn='$id_ppn' 
              WHERE id_penawaran='$id_penawaran'";

    $result = mysqli_query($conn, $query);

    if ($result) {
        echo "<script>alert('Update data penawaran harga berhasil.'); window.location='index.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>