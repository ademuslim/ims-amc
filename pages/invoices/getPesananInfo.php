<?php
require '../../includes/function.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_pesanan = $_POST['id_pesanan'];
    $id_produk = $_POST['id_produk']; // Jika diperlukan

    $mainTable = 'detail_pesanan';
    $joinTables = [
        ['produk', 'detail_pesanan.id_produk = produk.id_produk']
    ];
    $columns = 'produk.nama_produk, detail_pesanan.jumlah, detail_pesanan.sisa_pesanan';
    $conditions = "detail_pesanan.id_pesanan = '$id_pesanan'";

    // Tambahkan kondisi jika $id_produk juga diperlukan
    if (!empty($id_produk)) {
        $conditions .= " AND detail_pesanan.id_produk = '$id_produk'";
    }

    $pesanan_info = selectDataJoin($mainTable, $joinTables, $columns, $conditions);

    if (!empty($pesanan_info)) {
        $item = $pesanan_info[0]; // Mengambil item pertama karena hanya ada satu produk

        $infoContent = '<ul class="list-group" style="font-size:.8rem;">';
        $infoContent .= '<li class="list-group-item list-group-item-warning d-flex justify-content-between align-items-center">';
        $infoContent .= '<span>' . ucwords($item['nama_produk']) . '</span>';
        $infoContent .= '</li>';

        $infoContent .= '<li class="list-group-item list-group-item-warning d-flex justify-content-between align-items-center">';
        $infoContent .= 'Jumlah: <span class="badge bg-primary rounded-pill">' . $item['jumlah'] . '</span>';
        $infoContent .= '</li>';

        $infoContent .= '<li class="list-group-item list-group-item-warning d-flex justify-content-between align-items-center">';
        $infoContent .= 'Sisa: <span class="badge bg-primary rounded-pill">' . $item['sisa_pesanan'] . '</span>';
        $infoContent .= '</li>';
        $infoContent .= '</ul>';

        echo $infoContent;
    } else {
        echo '<p>Data tidak ditemukan.</p>';
    }
}
?>