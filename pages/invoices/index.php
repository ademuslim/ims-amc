<?php
// Ambil nilai kategori dari parameter URL
$category_param = isset($_GET['category']) ? $_GET['category'] : '';

// Atur judul halaman berdasarkan kategori
$page_title = $category_param === 'outgoing' ? 'Invoice Outgoing' : 'Invoice Incoming';

require '../../includes/header.php';

// Validasi nilai kategori dan atur nilai deskriptif
if ($category_param === 'outgoing') {
    $category = 'keluar';
} elseif ($category_param === 'incoming') {
    $category = 'masuk';
} else {
    die("Kategori tidak valid");
}

$mainTable = 'faktur';
$joinTables = [
    ['kontak_internal', 'faktur.id_pengirim = kontak_internal.id_pengirim'], 
    ['pelanggan', 'faktur.id_penerima = pelanggan.id_pelanggan'],
    ['ppn', 'faktur.id_ppn = ppn.id_ppn']
];

// Kolom-kolom yang ingin diambil dari tabel utama dan tabel-tabel yang di-join
$columns = 'faktur.*, kontak_internal.nama_pengirim AS nama_pengirim, pelanggan.nama_pelanggan AS nama_penerima, ppn.jenis_ppn AS jenis_ppn';

// Kondisi tambahan untuk seleksi data (opsional)
$conditions = "faktur.kategori = '$category'";

// Klausa ORDER BY
$orderBy = 'faktur.tanggal DESC';

// Panggil fungsi selectDataJoin dengan ORDER BY
$data_faktur = selectDataJoin($mainTable, $joinTables, $columns, $conditions, $orderBy);
?>

<div class="d-flex justify-content-between align-items-center mb-4">
  <h1 class="fs-5 m-0">Data Invoice</h1>
  <a href="add.php?category=<?= $category_param ?>" class="btn btn-primary btn-lg btn-icon btn-add">Buat Invoice</a>
</div>
<table id="example" class="table nowrap table-hover" style="width:100%">
  <thead>
    <tr>
      <th class="text-start">No.</th>
      <!-- <th>ID faktur</th> -->
      <th>Nomor Invoice</th>
      <th>Tanggal</th>
      <th>Penerima</th>
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
    <?php if (empty($data_faktur)) : ?>
    <tr>
      <td colspan="11">Tidak ada data</td>
    </tr>
    <?php else : ?>
    <?php $no = 1; ?>
    <?php foreach ($data_faktur as $faktur) : ?>
    <tr>
      <td class="text-start"><?= $no; ?></td>
      <!-- <td><?= $faktur['id_faktur']; ?></td> -->
      <td><?= strtoupper($faktur['no_faktur']); ?></td>
      <td><?= dateID($faktur['tanggal']); ?></td>
      <td><?= ucwords($faktur['nama_penerima']); ?></td>
      <td><?= $faktur['total']; ?></td>
      <td><?= ucwords($faktur['jenis_ppn']); ?></td>
      <td><?= $faktur['diskon']; ?></td>
      <td>
        <?php
        // Tentukan kelas bootstrap berdasarkan nilai status
        $status_class = '';
        if ($faktur['status'] == 'draft') {
            $status_class = 'text-bg-warning';
        } elseif ($faktur['status'] == 'belum dibayar') {
            $status_class = 'text-bg-info';
        } elseif ($faktur['status'] == 'dibayar') {
            $status_class = 'text-bg-success';
        }
        ?>
        <span class="badge <?= $status_class ?>"><?= strtoupper($faktur['status']) ?></span>
      </td>
      <td><img class="me-3" src="<?= base_url($faktur['logo']); ?>" alt="Logo"
          width="50"><?= ucwords($faktur['nama_pengirim']); ?></td>
      <td><?= ucfirst($faktur['catatan']); ?></td>
      <td>
        <div class="btn-group">
          <a href="detail.php?category=<?= $category_param ?>&id=<?= $faktur['id_faktur'] ?>" class="btn-act btn-view"
            title="Lihat Detail"></a>
          <a href="edit.php?category=<?= $category_param ?>&id=<?= $faktur['id_faktur'] ?>" class="btn-act btn-edit"
            title="Ubah Data"></a>
          <a href="del.php?id=<?= $faktur['id_faktur']; ?>" class="btn-act btn-del" title="Hapus Data"></a>
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