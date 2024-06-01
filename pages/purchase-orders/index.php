<?php
$category_param = isset($_GET['category']) ? $_GET['category'] : '';
$page_title = $category_param === 'outgoing' ? 'PO Outgoing' : 'PO Incoming';
require '../../includes/header.php';

$category = ($category_param === 'outgoing') ? 'keluar' : (($category_param === 'incoming') ? 'masuk' : die("Kategori tidak valid"));

$mainTable = 'pesanan_pembelian';
$joinTables = [
  ["kontak pengirim", "pesanan_pembelian.id_pengirim = pengirim.id_kontak"], 
  ["kontak penerima", "pesanan_pembelian.id_penerima = penerima.id_kontak"],
  ['ppn', 'pesanan_pembelian.id_ppn = ppn.id_ppn']
];

$columns = 'pesanan_pembelian.*, pengirim.nama_kontak AS nama_pengirim, penerima.nama_kontak AS nama_penerima, ppn.jenis_ppn';
$conditions = "pesanan_pembelian.kategori = '$category'";
$orderBy = 'pesanan_pembelian.tanggal DESC';

$data_pesanan_pembelian = selectDataJoin($mainTable, $joinTables, $columns, $conditions, $orderBy);
?>

<div class="d-flex justify-content-between align-items-center mb-4">
  <h1 class="fs-5 m-0">Data Purchase Order</h1>
  <a href="<?= base_url("pages/purchase-orders/add/$category_param") ?>"
    class="btn btn-primary btn-lg btn-icon btn-add">
    <?= $category_param === 'incoming' ? 'Tambah Purchase Order' : 'Buat Purchase Order' ?>
  </a>
</div>

<table id="example" class="table nowrap table-hover" style="width: 100%;">
  <thead>
    <tr>
      <th class="text-start">No.</th>
      <th>No. PO</th>
      <th>Tanggal</th>

      <?php if ($category_param == 'outgoing') { ?>
      <th>Penerima</th>
      <?php } elseif ($category_param == 'incoming') { ?>
      <th>Pengirim</th>
      <?php } ?>

      <th>Status</th>
      <th>Total</th>
      <th>PPN</th>
      <th>Diskon</th>
      <th>U.P.</th>

      <?php if ($category_param == 'outgoing') { ?>
      <th>Pengirim</th>
      <?php } elseif ($category_param == 'incoming') { ?>
      <th>Penerima</th>
      <?php } ?>

      <th>Catatan</th>
      <th>Detail</th>
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody>
    <?php if (empty($data_pesanan_pembelian)) : ?>
    <tr>
      <td colspan="12">Tidak ada data</td>
    </tr>
    <?php else : $no = 1; foreach ($data_pesanan_pembelian as $po) : ?>
    <tr>
      <td class="text-start"><?= $no; ?></td>
      <td><?= strtoupper($po['no_pesanan']); ?></td>
      <td><?= dateID($po['tanggal']); ?></td>

      <?php if ($category_param == 'outgoing') { ?>
      <td class="text-wrap"><?= ucwords($po['nama_penerima']); ?></td>
      <?php } elseif ($category_param == 'incoming') { ?>
      <td class="text-wrap"><?= ucwords($po['nama_pengirim']); ?></td>
      <?php } ?>

      <td>
        <?php
        // Tentukan kelas bootstrap berdasarkan nilai status
        $status_class = '';
        if ($po['status'] == 'draft') {
            $status_class = 'text-bg-warning';
        } elseif ($po['status'] == 'terkirim' || $po['status'] == 'diproses') {
            $status_class = 'text-bg-info';
        } elseif ($po['status'] == 'selesai') {
            $status_class = 'text-bg-success';
        }
        ?>
        <span class="badge <?= $status_class ?>"><?= strtoupper($po['status']) ?></span>
      </td>
      <td><?= formatRupiah($po['total']); ?></td>
      <td><?= ucwords($po['jenis_ppn']); ?></td>
      <td><?= $po['diskon'] != 0 ? $po['diskon'] : "-"; ?></td>
      <td><?= !empty($po['up']) ? ucwords($po['up']) : "-"; ?></td>
      <td>
        <?php if (!empty($po['logo'])) : ?>
        <img class="me-3" src="<?= base_url($po['logo']); ?>" alt="Logo" width="50">
        <?php 
          endif; 
      
          if ($category_param == 'outgoing') { 
            echo ucwords($po['nama_pengirim']);
          } elseif ($category_param == 'incoming') {
            echo ucwords($po['nama_penerima']); 
          } 
        ?>
      </td>

      <td><?= !empty($po['catatan']) ? ucfirst($po['catatan']) : "-"; ?></td>

      <!-- Detail PO -->
      <td>
        <?php
        $id_pesanan = $po['id_pesanan'];
        $data_pesanan_pembelian_detail = [];
        $mainDetailTable = 'detail_pesanan';
        $joinDetailTables = [
            ['pesanan_pembelian', 'detail_pesanan.id_pesanan = pesanan_pembelian.id_pesanan'], 
            ['produk', 'detail_pesanan.id_produk = produk.id_produk']
        ];
        $columns = 'detail_pesanan.*, produk.*';
        $conditions = "detail_pesanan.id_pesanan = '$id_pesanan'";

        // Panggil fungsi selectDataJoin dengan ORDER BY
        $data_pesanan_pembelian_detail = selectDataJoin($mainDetailTable, $joinDetailTables, $columns, $conditions);

        $subtotal = 0;
        if (!empty($data_pesanan_pembelian_detail)): ?>

        <div class="row fw-bold border-bottom">
          <div class="col">No.</div>
          <div class="col">Deskripsi</div>
          <div class="col">Kuantitas</div>
          <div class="col">Harga</div>
          <div class="col">Total Harga</div>
          <!-- PO Open -->
          <div class="col">Pesanan Terkirim</div>
          <div class="col">Sisa Pesanan</div>
        </div>
        <?php
            $no_detail = 1; 
            foreach ($data_pesanan_pembelian_detail as $detail): 
            
            // Hitung total harga untuk setiap baris
            $total_harga = $detail['jumlah'] * $detail['harga_satuan'];
            // Tambahkan total harga ke subtotal
            $subtotal += $total_harga;
        ?>
        <div class="row border-bottom">
          <div class="col"><?= $no_detail ?></div>
          <div class="col"><?= strtoupper($detail['nama_produk']); ?></div>
          <div class="col"><?= $detail['jumlah'] . " " . strtoupper($detail['satuan']); ?></div>
          <div class="col"><?= formatRupiah($detail['harga_satuan']); ?></div>
          <div class="col"><?= formatRupiah($total_harga); ?></div>

          <div class="col"><?= $detail['jumlah_dikirim'] . " " . strtoupper($detail['satuan']); ?></div>
          <div class="col"><?= $detail['sisa_pesanan'] . " " . strtoupper($detail['satuan']); ?></div>
        </div>
        <?php $no_detail++; endforeach; ?>
        <?php else: ?>
        <span class="text-center">-</span>
        <?php endif; ?>
      </td>

      <td>
        <div class="btn-group">
          <a href="<?= base_url("pages/purchase-orders/detail/$category_param/{$po['id_pesanan']}") ?>"
            class="btn-act btn-view" title="Lihat Detail"></a>


          <a href="<?= base_url("pages/purchase-orders/edit/$category_param/{$po['id_pesanan']}") ?>"
            class="btn-act btn-edit" title="Ubah Data"></a>
          <a href="javascript:void(0);"
            onclick="confirmDelete('del.php?category=<?= $category_param ?>&id=<?= $po['id_pesanan']; ?>', 'Apakah Anda yakin ingin menghapus data PO ini? Semua detail PO terkait juga akan dihapus.')"
            class="btn-act btn-del" title="Hapus Data"></a>
        </div>
      </td>
    </tr>
    <?php $no++; endforeach; endif; ?>
  </tbody>
</table>

<script>
$(document).ready(function() {
  // Tangkap klik pada setiap tombol di dalam .btn-group
  $('.btn-group').on('click', function(event) {
    // Hentikan propagasi event agar tidak mencapai elemen td
    event.stopPropagation();
  });
});
</script>

<?php require '../../includes/footer.php'; ?>