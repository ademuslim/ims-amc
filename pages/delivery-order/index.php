<?php
$category_param = isset($_GET['category']) ? $_GET['category'] : '';
$page_title = $category_param === 'outgoing' ? 'Invoice Outgoing' : 'Invoice Incoming';
$content_title = $category_param === 'outgoing' ? 'Keluar' : 'Masuk';

require '../../includes/header.php';

// Kategori dokumen
$category = ($category_param === 'outgoing') ? 'keluar' : (($category_param === 'incoming') ? 'masuk' : die("Kategori tidak valid"));

$mainDetailTable = 'detail_faktur';
$joinDetailTables = [
    ['faktur', 'detail_faktur.id_faktur = faktur.id_faktur'],
    ['pesanan_pembelian', 'detail_faktur.id_pesanan = pesanan_pembelian.id_pesanan'],
    ['produk', 'detail_faktur.id_produk = produk.id_produk']
];
$columns = 'detail_faktur.*, faktur.*, produk.nama_produk, produk.satuan, pesanan_pembelian.no_pesanan';
$conditions = "faktur.kategori = '$category' AND faktur.status_hapus = 0 AND faktur.status IN ('dibayar', 'belum dibayar')";
$orderBy = 'faktur.tanggal DESC, detail_faktur.no_pengiriman_barang ASC';

$data_detail_inv = selectDataJoin($mainDetailTable, $joinDetailTables, $columns, $conditions, $orderBy);

// var_dump($data_detail_inv);
?>

<h1 class="fs-5 mb-3">Data Delivery Order <?= $content_title ?></h1>

<!-- Detail Invoice -->
<table id="example" class="table nowrap table-hover" style="width:100%">
  <thead>
    <tr>
      <th>No.</th>
      <th>No. Invoice</th>
      <th>Tanggal</th>
      <th>No. Delivery Order</th>
      <th>Produk / Jasa</th>
      <th>Kuantitas</th>
      <th>No. Purchase Order</th>
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody>
    <?php if (empty($data_detail_inv)) : ?>
    <tr>
      <td colspan="8">Tidak ada data Delivery Order</td>
    </tr>
    <?php else : $no = 1; foreach ($data_detail_inv as $do) : ?>
    <tr>
      <td class="text-start"><?= $no; ?></td>
      <td><?= strtoupper($do['no_faktur']); ?></td>
      <td><?= dateID($do['tanggal']); ?></td>
      <td class="text-primary"><?= strtoupper($do['no_pengiriman_barang']); ?></td>
      <td><?= strtoupper($do['nama_produk']); ?></td>
      <td class="text-end no-wrap">
        <?= number_format($do['jumlah'], 0, ',', '.') . ' ' . strtoupper($do['satuan']); ?>
      </td>
      <td><?= strtoupper($do['no_pesanan']); ?></td>
      <td>
        <div class="btn-group">
          <a href="<?= base_url("pages/delivery-order/detail/$category_param/{$do['id_detail_faktur']}") ?>"
            class="btn-act btn-view" title="Lihat Detail"></a>
        </div>
      </td>
    </tr>
    <?php $no++; endforeach; endif; ?>
  </tbody>
</table>

<?php require '../../includes/footer.php'; ?>