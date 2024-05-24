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
      <th>Penerima</th>
      <th>Status</th>
      <th>Total</th>
      <th>PPN</th>
      <th>Diskon</th>
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
    <?php else : $no = 1; foreach ($data_faktur as $faktur) : ?>
    <tr>
      <td class="text-start"><?= $no; ?></td>
      <td><?= strtoupper($faktur['no_faktur']); ?></td>
      <td><?= dateID($faktur['tanggal']); ?></td>
      <td class="text-wrap"><?= ucwords($faktur['nama_penerima']); ?></td>
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
      <td><?= formatRupiah($faktur['total']); ?></td>
      <td><?= ucwords($faktur['jenis_ppn']); ?></td>
      <td><?= $faktur['diskon'] != 0 ? $faktur['diskon'] : "-"; ?></td>
      <td>
        <?php if (!empty($faktur['logo'])) : ?>
        <img class="me-3" src="<?= base_url($faktur['logo']); ?>" alt="Logo" width="50">
        <?php endif; ?>
        <?= ucwords($faktur['nama_pengirim']); ?>
      </td>
      <td><?= !empty($faktur['catatan']) ? ucfirst($faktur['catatan']) : "-"; ?></td>
      <td>
        <div class="btn-group">
          <a href="detail.php?category=<?= $category_param ?>&id=<?= $faktur['id_faktur'] ?>" class="btn-act btn-view"
            title="Lihat Detail"></a>
          <a href="edit.php?category=<?= $category_param ?>&id=<?= $faktur['id_faktur'] ?>" class="btn-act btn-edit"
            title="Ubah Data"></a>
          <a href="javascript:void(0);"
            onclick="confirmDelete('del.php?category=<?= $category_param ?>&id=<?= $faktur['id_faktur']; ?>', 'Apakah Anda yakin ingin menghapus data penawaran ini? Semua detail penawaran terkait juga akan dihapus.')"
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