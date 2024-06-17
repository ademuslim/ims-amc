<?php
require '../../includes/function.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_penawaran = $_POST['id_penawaran'];
    $id_produk = $_POST['id_produk']; // Jika diperlukan

    $mainTable = 'detail_penawaran';
    $joinTables = [
        ['produk', 'detail_penawaran.id_produk = produk.id_produk']
    ];
    $columns = 'produk.nama_produk, detail_penawaran.jumlah, detail_penawaran.harga_satuan';
    $conditions = "detail_penawaran.id_penawaran = '$id_penawaran'";

    // Tambahkan kondisi jika $id_produk juga diperlukan
    if (!empty($id_produk)) {
        $conditions .= " AND detail_penawaran.id_produk = '$id_produk'";
    }

    $penawaran_info = selectDataJoin($mainTable, $joinTables, $columns, $conditions);

    if (!empty($penawaran_info)) {
        $item = $penawaran_info[0]; // Mengambil item pertama karena hanya ada satu produk

        $infoContent = '<ul class="list-group" style="font-size:.8rem;">';
        $infoContent .= '<li class="list-group-item list-group-item-warning d-flex justify-content-between align-items-center">';
        $infoContent .= '<span>' . ucwords($item['nama_produk']) . '</span>';
        $infoContent .= '</li>';

        $infoContent .= '<li class="list-group-item list-group-item-warning d-flex justify-content-between align-items-center">';
        $infoContent .= 'Jumlah: <span class="badge bg-primary rounded-pill">' . $item['jumlah'] . '</span>';
        $infoContent .= '</li>';

        $infoContent .= '<li class="list-group-item list-group-item-warning d-flex justify-content-between align-items-center">';
        $infoContent .= 'Sisa: <span class="badge bg-primary rounded-pill">' . $item['harga_satuan'] . '</span>';
        $infoContent .= '</li>';
        $infoContent .= '</ul>';

        echo $infoContent;
    } else {
        echo '<p>Data tidak ditemukan.</p>';
    }
}
?>