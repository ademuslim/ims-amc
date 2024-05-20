<?php
$page_title = "Purchase Orders";
require '../../includes/header.php';

$mainTable = 'pesanan_pembelian';
$joinTables = [
    ['kontak_internal', 'pesanan_pembelian.id_pengirim = kontak_internal.id_pengirim'], 
    ['pelanggan', 'pesanan_pembelian.id_penerima = pelanggan.id_pelanggan'],
    ['ppn', 'pesanan_pembelian.id_ppn = ppn.id_ppn']
];

// Kolom-kolom yang ingin diambil dari tabel utama dan tabel-tabel yang di-join
$columns = 'pesanan_pembelian.*, kontak_internal.nama_pengirim AS nama_pengirim, pelanggan.nama_pelanggan AS nama_penerima, ppn.jenis_ppn AS jenis_ppn';

// Kondisi tambahan untuk seleksi data (opsional)
$conditions = "";

// Klausa ORDER BY
$orderBy = 'pesanan_pembelian.tanggal DESC';

// Panggil fungsi selectDataJoin dengan ORDER BY
$data_pesanan_pembelian = selectDataJoin($mainTable, $joinTables, $columns, $conditions, $orderBy);
?>

<div class="d-flex justify-content-between align-items-center mb-4">
  <h1 class="fs-5 m-0">Data Purchase Order</h1>
  <a href="add.php" class="btn btn-primary btn-lg btn-icon btn-add">Buat Purchase Order</a>
</div>
<table id="example" class="table nowrap table-hover" style="width:100%">
  <thead>
    <tr>
      <th class="text-start">No.</th>
      <!-- <th>ID Penawaran</th> -->
      <th>Nomor PO</th>
      <th>Tanggal</th>
      <th>Penerima</th>
      <th>U.P.</th>
      <th>Total</th>
      <th>PPN</th>
      <th>Diskon</th>
      <th>Pengirim</th>
      <th>Catatan</th>
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody>
    <?php if (empty($data_pesanan_pembelian)) : ?>
    <tr>
      <td colspan="11">Tidak ada data</td>
    </tr>
    <?php else : ?>
    <?php $no = 1; ?>
    <?php foreach ($data_pesanan_pembelian as $ph) : ?>
    <tr>
      <td class="text-start"><?= $no; ?></td>
      <!-- <td><?= $ph['id_pesanan']; ?></td> -->
      <td><?= strtoupper($ph['no_pesanan']); ?></td>
      <td><?= dateID($ph['tanggal']); ?></td>
      <td><?= ucwords($ph['nama_penerima']); ?></td>
      <td><?= ucwords($ph['up']); ?></td>
      <td><?= $ph['total']; ?></td>
      <td><?= ucwords($ph['jenis_ppn']); ?></td>
      <td><?= $ph['diskon']; ?></td>
      <td><img class="me-3" src="<?= base_url($ph['logo']); ?>" alt="Logo"
          width="50"><?= ucwords($ph['nama_pengirim']); ?></td>
      <td><?= ucfirst($ph['catatan']); ?></td>
      <td>
        <div class="btn-group">
          <a href="detail.php?id=<?= $ph['id_pesanan']; ?>" class="btn-act btn-view" title="Lihat Detail"></a>
          <a href="edit.php?id=<?= $ph['id_pesanan']; ?>" class="btn-act btn-edit" title="Ubah Data"></a>
          <a href="del.php?id=<?= $ph['id_pesanan']; ?>" class="btn-act btn-del" title="Hapus Data"></a>
        </div>
      </td>
    </tr>
    <?php $no++; ?>
    <?php endforeach; ?>
    <?php endif; ?>
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

<?php
require '../../includes/footer.php';
?>