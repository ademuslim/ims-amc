<?php
require_once "../_config.php";

if (isset($_GET['id'])) {
    $idNasabah = $_GET['id'];

    // Query untuk mengambil data karyawan berdasarkan ID
    $query = "SELECT * FROM nasabah WHERE id_nasabah = '$idNasabah'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $dataNasabah = mysqli_fetch_assoc($result);
        echo json_encode($dataNasabah);
    } else {
        echo json_encode(['error' => 'Query error']);
    }
} else {
    echo json_encode(['error' => 'ID Nasabah tidak tersedia']);
}
?>
