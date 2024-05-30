<?php
$category_param = isset($_GET['category']) ? $_GET['category'] : '';
$page_title = $category_param === 'outgoing' ? 'Invoice Outgoing' : 'Invoice Incoming';
require '../../includes/header.php';

$category = ($category_param === 'outgoing') ? 'keluar' : (($category_param === 'incoming') ? 'masuk' : die("Kategori tidak valid"));


$mainTable = 'faktur';
$joinTables = [
  ["kontak pengirim", "faktur.id_pengirim = pengirim.id_kontak"], 
  ["kontak penerima", "faktur.id_penerima = penerima.id_kontak"],
  ['ppn', 'faktur.id_ppn = ppn.id_ppn']
];

$columns = 'faktur.*, pengirim.nama_kontak AS nama_pengirim, penerima.nama_kontak AS nama_penerima, ppn.jenis_ppn';
$conditions = "faktur.kategori = '$category'";
$orderBy = 'faktur.tanggal DESC';

$data_faktur = selectDataJoin($mainTable, $joinTables, $columns, $conditions, $orderBy);
?>

<div class="d-flex justify-content-between align-items-center mb-4">
  <h1 class="fs-5 m-0">Data Invoice</h1>
  <a href="add.php?category=<?= $category_param ?>" class="btn btn-primary btn-lg btn-icon btn-add">
    <?= $category_param === 'incoming' ? 'Tambah Invoice' : 'Buat Invoice' ?>
  </a>
</div>
<table id="example" class="table nowrap table-hover" style="width:100%">
  <thead>
    <tr>
      <th class="text-start">No.</th>
      <th>No. Invoice</th>
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
    <?php if (empty($data_faktur)) : ?>
    <tr>
      <td colspan="11">Tidak ada data</td>
    </tr>
    <?php else : $no = 1; foreach ($data_faktur as $faktur) : ?>
    <tr>
      <td class="text-start"><?= $no; ?></td>
      <td><?= strtoupper($faktur['no_faktur']); ?></td>
      <td><?= dateID($faktur['tanggal']); ?></td>

      <?php if ($category_param == 'outgoing') { ?>
      <td class="text-wrap"><?= ucwords($faktur['nama_penerima']); ?></td>
      <?php } elseif ($category_param == 'incoming') { ?>
      <td class="text-wrap"><?= ucwords($faktur['nama_pengirim']); ?></td>
      <?php } ?>

      <td>
        <?php
        // Tentukan kelas bootstrap berdasarkan nilai status
        $status_class = '';
        if ($faktur['status'] == 'draft') {
            $status_class = 'text-bg-warning';
        } elseif ($faktur['status'] == 'terkirim' || $faktur['status'] == 'belum dibayar') {
            $status_class = 'text-bg-info';
        } elseif ($faktur['status'] == 'dibayar') {
            $status_class = 'text-bg-success';
        }
        ?>
        <span class="badge <?= $status_class ?>"><?= strtoupper($faktur['status']) ?></span>
      </td>
      <td><?= formatRupiah($faktur['total']); ?></td>
      <td><?= ucwords($faktur['jenis_ppn']); ?></td>
      <td><?= $faktur['diskon'] != 0 ? $faktur['diskon'] : "-"; ?></td>
      <td>
        <?php if (!empty($faktur['logo'])) : ?>
        <img class="me-3" src="<?= base_url($faktur['logo']); ?>" alt="Logo" width="50">
        <?php 
          endif; 
      
          if ($category_param == 'outgoing') { 
            echo ucwords($faktur['nama_pengirim']);
          } elseif ($category_param == 'incoming') {
            echo ucwords($faktur['nama_penerima']); 
          } 
        ?>
      </td>

      <td><?= !empty($faktur['catatan']) ? ucfirst($faktur['catatan']) : "-"; ?></td>

      <!-- Detail Faktur -->
      <td>
        <?php
        $id_faktur = $faktur['id_faktur'];
        $data_faktur_detail = [];
        $mainDetailTable = 'detail_faktur';
        $joinDetailTables = [
            ['faktur', 'detail_faktur.id_faktur = faktur.id_faktur'], 
            ['pesanan_pembelian', 'detail_faktur.id_pesanan = pesanan_pembelian.id_pesanan'],
            ['produk', 'detail_faktur.id_produk = produk.id_produk']
        ];
        $columns = 'detail_faktur.*, produk.*, pesanan_pembelian.no_pesanan';
        $conditions = "detail_faktur.id_faktur = '$id_faktur'";

        // Panggil fungsi selectDataJoin dengan ORDER BY
        $data_faktur_detail = selectDataJoin($mainDetailTable, $joinDetailTables, $columns, $conditions);

        $subtotal = 0;
        if (!empty($data_faktur_detail)): ?>

        <div class="row fw-bold border-bottom">
          <div class="col">No.</div>
          <div class="col">Deskripsi</div>
          <div class="col">Kuantitas</div>
          <div class="col">Harga</div>
          <div class="col">Total Harga</div>
          <!-- faktur Open -->
          <div class="col">No. PO</div>
        </div>
        <?php
            $no_detail = 1; 
            foreach ($data_faktur_detail as $detail): 
            
            // Hitung total harga untuk setiap baris
            $total_harga = $detail['jumlah'] * $detail['harga_satuan'];
            // Tambahkan total harga ke subtotal
            $subtotal += $total_harga;
        ?>
        <div class="row border-bottom">
          <div class="col"><?= $no_detail ?></div>
          <div class="col"><?= ucwords($detail['nama_produk']); ?></div>
          <div class="col"><?= $detail['jumlah'] . " " . strtoupper($detail['satuan']); ?></div>
          <div class="col"><?= formatRupiah($detail['harga_satuan']); ?></div>
          <div class="col"><?= formatRupiah($total_harga); ?></div>

          <div class="col" style="font-size: .7rem;"><?= strtoupper($detail['no_pesanan']); ?></div>
        </div>
        <?php $no_detail++; endforeach; ?>
        <?php else: ?>
        <span class="text-center">-</span>
        <?php endif; ?>
      </td>

      <td>
        <div class="btn-group">
          <a href="detail.php?category=<?= $category_param ?>&id=<?= $faktur['id_faktur'] ?>" class="btn-act btn-view"
            title="Lihat Detail"></a>
          <a href="edit.php?category=<?= $category_param ?>&id=<?= $faktur['id_faktur'] ?>" class="btn-act btn-edit"
            title="Ubah Data"></a>
          <a href="javascript:void(0);"
            onclick="confirmDelete('del.php?category=<?= $category_param ?>&id=<?= $faktur['id_faktur']; ?>', 'Apakah Anda yakin ingin menghapus data invoice ini? Semua detail invoice terkait juga akan dihapus dan tidak dapat dikembalikan.')"
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