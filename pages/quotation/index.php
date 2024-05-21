<?php
// Ambil nilai kategori dari parameter URL
$category_param = isset($_GET['category']) ? $_GET['category'] : '';

// Atur judul halaman berdasarkan kategori
$page_title = $category_param === 'outgoing' ? 'Quotation Outgoing' : 'Quotation Incoming';

require '../../includes/header.php';

// Validasi nilai kategori dan atur nilai deskriptif
if ($category_param === 'outgoing') {
    $category = 'keluar';
} elseif ($category_param === 'incoming') {
    $category = 'masuk';
} else {
    die("Kategori tidak valid");
}

$mainTable = 'penawaran_harga';
$joinTables = [
    ['kontak_internal', 'penawaran_harga.id_pengirim = kontak_internal.id_pengirim'], 
    ['pelanggan', 'penawaran_harga.id_penerima = pelanggan.id_pelanggan'],
    ['ppn', 'penawaran_harga.id_ppn = ppn.id_ppn']
];

// Kolom-kolom yang ingin diambil dari tabel utama dan tabel-tabel yang di-join
$columns = 'penawaran_harga.*, kontak_internal.nama_pengirim AS nama_pengirim, pelanggan.nama_pelanggan AS nama_penerima, ppn.jenis_ppn AS jenis_ppn';

// Kondisi tambahan untuk seleksi data (opsional)
$conditions = "penawaran_harga.kategori = '$category'";

// Klausa ORDER BY
$orderBy = 'penawaran_harga.tanggal DESC';

// Panggil fungsi selectDataJoin dengan ORDER BY
$data_penawaran_harga = selectDataJoin($mainTable, $joinTables, $columns, $conditions, $orderBy);
?>

<div class="d-flex justify-content-between align-items-center mb-4">
  <h1 class="fs-5 m-0">Data Penawaran Harga</h1>
  <a href="add.php?category=<?= $category_param ?>" class="btn btn-primary btn-lg btn-icon btn-add">Buat Penawaran
    harga</a>
</div>
<table id="example" class="table nowrap table-hover" style="width:100%">
  <thead>
    <tr>
      <th class="text-start">No.</th>
      <!-- <th>ID Penawaran</th> -->
      <th>Nomor Penawaran</th>
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
    <?php if (empty($data_penawaran_harga)) : ?>
    <tr>
      <td colspan="11">Tidak ada data</td>
    </tr>
    <?php else : ?>
    <?php $no = 1; ?>
    <?php foreach ($data_penawaran_harga as $ph) : ?>
    <tr>
      <td class="text-start"><?= $no; ?></td>
      <!-- <td><?= $ph['id_penawaran']; ?></td> -->
      <td><?= strtoupper($ph['no_penawaran']); ?></td>
      <td><?= dateID($ph['tanggal']); ?></td>
      <td><?= ucwords($ph['nama_penerima']); ?></td>
      <td><?= ucwords($ph['up']); ?></td>
      <td><?= $ph['total']; ?></td>
      <td><?= ucwords($ph['jenis_ppn']); ?></td>
      <td><?= $ph['diskon']; ?></td>
      <td>
        <?php
        // Tentukan kelas bootstrap berdasarkan nilai status
        $status_class = '';
        if ($ph['status'] == 'draft') {
            $status_class = 'text-bg-warning';
        } elseif ($ph['status'] == 'terkirim') {
            $status_class = 'text-bg-info';
        } elseif ($ph['status'] == 'ditolak') {
            $status_class = 'text-bg-danger';
        } elseif ($ph['status'] == 'dibayar') {
            $status_class = 'text-bg-success';
        }
        ?>
        <span class="badge <?= $status_class ?>"><?= strtoupper($ph['status']) ?></span>
      </td>
      <td><img class="me-3" src="<?= base_url($ph['logo']); ?>" alt="Logo"
          width="50"><?= ucwords($ph['nama_pengirim']); ?></td>
      <td><?= ucfirst($ph['catatan']); ?></td>
      <td>
        <div class="btn-group">
          <a href="detail.php?category=<?= $category_param ?>&id=<?= $ph['id_penawaran']; ?>" class="btn-act btn-view"
            title="Lihat Detail"></a>
          <a href="edit.php?category=<?= $category_param ?>&id=<?= $ph['id_penawaran']; ?>" class="btn-act btn-edit"
            title="Ubah Data"></a>
          <a href="del.php?id=<?= $ph['id_penawaran']; ?>" class="btn-act btn-del" title="Hapus Data"></a>
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