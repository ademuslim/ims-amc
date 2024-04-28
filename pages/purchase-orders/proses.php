<?php
require_once "../_config.php";


if (isset($_POST['add'])) {
    $kode_loket = $_POST['kode_loket'];
    $nama_loket = strtolower($_POST['nama_loket']);
    $id_karyawan = $_POST['id_karyawan'];

    // Hindari penggunaan kata kunci 'limit' sebagai nama kolom karena merupakan kata kunci SQL
    $query = "INSERT INTO loket (kode_loket, nama_loket, id_karyawan) VALUES ('$kode_loket', '$nama_loket', '$id_karyawan')";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo "<script>alert('Tambah loket berhasil.'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Tambah loket gagal.'); window.location='add.php';</script>";
    }
}else if (isset($_POST['edit'])) {
    $id_loket = $_POST['id'];
    $kode_loket = strtolower($_POST['kode_loket']);
    $nama_loket = strtolower($_POST['nama_loket']);
    $id_karyawan = $_POST['id_karyawan'];

    // Cek duplikasi kode loket sebelum melakukan update
    $cek_duplikasi_query = "SELECT id_loket FROM loket WHERE kode_loket = '$kode_loket' AND id_loket != '$id_loket'";
    $cek_duplikasi_result = mysqli_query($conn, $cek_duplikasi_query);

    if (mysqli_num_rows($cek_duplikasi_result) > 0) {
        // Jika terdapat duplikasi, tampilkan pesan error
        echo "<script>alert('Kode Loket sudah ada. Silakan pilih kode loket yang lain.'); window.location='edit.php?id=$id_loket';</script>";
        exit();
    }

    // Query untuk melakukan update data
    $query = "UPDATE loket SET kode_loket='$kode_loket', nama_loket='$nama_loket', id_karyawan='$id_karyawan' WHERE id_loket='$id_loket'";

    $result = mysqli_query($conn, $query);

    if ($result) {
        // Jika update berhasil, redirect ke halaman index.php
        echo "<script>alert('Update data loket berhasil.'); window.location='index.php';</script>";
        exit();
    } else {
        // Jika update gagal, tampilkan pesan error
        echo "Error: " . mysqli_error($conn);
    }
}