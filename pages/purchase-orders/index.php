<?php
// Ambil nilai kategori dari parameter URL
$category_param = isset($_GET['category']) ? $_GET['category'] : '';

// Atur judul halaman berdasarkan kategori
$page_title = $category_param === 'outgoing' ? 'PO Outgoing' : 'PO Incoming';

require '../../includes/header.php';

// Validasi nilai kategori dan atur nilai deskriptif
if ($category_param === 'outgoing') {
  $category = 'keluar';
  $sender = 'internal';
  $receiver = 'customer';
} elseif ($category_param === 'incoming') {
  $category = 'masuk';
  $sender = 'customer';
  $receiver = 'internal';
} else {
  die("Kategori tidak valid");
}

$mainTable = 'pesanan_pembelian';
$joinTables = [
  ["kontak pengirim", "pesanan_pembelian.id_pengirim = pengirim.id_kontak AND pengirim.kategori = '$sender'"], 
  ["kontak penerima", "pesanan_pembelian.id_penerima = penerima.id_kontak AND penerima.kategori = '$receiver'"],
  ['ppn', 'pesanan_pembelian.id_ppn = ppn.id_ppn']
];

// Kolom-kolom yang ingin diambil dari tabel utama dan tabel-tabel yang di-join
$columns = 'pesanan_pembelian.*, pengirim.nama_kontak AS nama_pengirim, penerima.nama_kontak AS nama_penerima, ppn.jenis_ppn';

// Kondisi tambahan untuk seleksi data (opsional)
$conditions = "pesanan_pembelian.kategori = '$category'";

// Klausa ORDER BY
$orderBy = 'pesanan_pembelian.tanggal DESC';

// Panggil fungsi selectDataJoin dengan ORDER BY
$data_pesanan_pembelian = selectDataJoin($mainTable, $joinTables, $columns, $conditions, $orderBy);
?>

<div class="d-flex justify-content-between align-items-center mb-4">
  <h1 class="fs-5 m-0">Data Purchase Order</h1>
  <a href="add.php?category=<?= $category_param ?>" class="btn btn-primary btn-lg btn-icon btn-add">
    <?= $category_param === 'incoming' ? 'Tambah Purchase Order' : 'Buat Purchase Order' ?>
  </a>
</div>
<table id="example" class="table nowrap table-hover" style="width:100%">
  <thead>
    <tr>
      <th class="text-start">No.</th>
      <th>Nomor PO</th>
      <th>Tanggal</th>
      <th>Penerima</th>
      <th>U.P.</th>
      <th>Total</th>
      <th>PPN</th>
      <th>Diskon</th>
      <th>Status</th>
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
    <?php foreach ($data_pesanan_pembelian as $po) : ?>
    <tr>
      <td class="text-start"><?= $no; ?></td>
      <!-- <td><?= $po['id_pesanan']; ?></td> -->
      <td><?= strtoupper($po['no_pesanan']); ?></td>
      <td><?= dateID($po['tanggal']); ?></td>
      <td><?= ucwords($po['nama_penerima']); ?></td>
      <td><?= ucwords($po['up']); ?></td>
      <td><?= $po['total']; ?></td>
      <td><?= ucwords($po['jenis_ppn']); ?></td>
      <td><?= $po['diskon']; ?></td>
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
      <td><img class="me-3" src="<?= base_url($po['logo']); ?>" alt="Logo"
          width="50"><?= ucwords($po['nama_pengirim']); ?></td>
      <td><?= ucfirst($po['catatan']); ?></td>
      <td>
        <div class="btn-group">
          <a href="detail.php?category=<?= $category_param ?>&id=<?= $ph['id_pesanan']; ?>" class="btn-act btn-view"
            title="Lihat Detail"></a>
          <a href="edit.php?category=<?= $category_param ?>&id=<?= $ph['id_pesanan']; ?>" class="btn-act btn-edit"
            title="Ubah Data"></a>
          <a href="javascript:void(0);"
            onclick="confirmDelete('del.php?category=<?= $category_param ?>&id=<?= $ph['id_pesanan']; ?>', 'Apakah Anda yakin ingin menghapus data penawaran ini? Semua detail penawaran terkait juga akan dihapus.')"
            class="btn-act btn-del" title="Hapus Data"></a>
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