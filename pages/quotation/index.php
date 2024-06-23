<?php
// Ambil nilai kategori dari parameter URL
$category_param = isset($_GET['category']) ? $_GET['category'] : '';

// Atur judul halaman berdasarkan kategori
$page_title = $category_param === 'outgoing' ? 'Quotation Outgoing' : 'Quotation Incoming';
$content_title = $category_param === 'outgoing' ? 'Keluar' : 'Masuk';

require '../../includes/header.php';

// Tentukan kategori dan validasi
$category = ($category_param === 'outgoing') ? 'keluar' : (($category_param === 'incoming') ? 'masuk' : die("Kategori tidak valid"));

$mainTable = 'penawaran_harga';
$joinTables = [
    ["kontak pengirim", "penawaran_harga.id_pengirim = pengirim.id_kontak"], 
    ["kontak penerima", "penawaran_harga.id_penerima = penerima.id_kontak"],
    ['ppn', 'penawaran_harga.id_ppn = ppn.id_ppn']
];
$columns = 'penawaran_harga.*, pengirim.nama_kontak AS nama_pengirim, penerima.nama_kontak AS nama_penerima, ppn.jenis_ppn';
$conditions = "penawaran_harga.kategori = '$category' AND penawaran_harga.status_hapus = 0";
$orderBy = 'penawaran_harga.tanggal DESC';
$data_penawaran_harga = selectDataJoin($mainTable, $joinTables, $columns, $conditions, $orderBy);

// Debug: Tampilkan hasil data (Hapus ini di produksi)
// echo "<pre>";
// print_r($data_penawaran_harga);
// echo "</pre>";
?>

<div class="d-flex justify-content-between align-items-center mb-4">
  <h1 class="fs-5 m-0">Data Penawaran Harga <?= $content_title ?></h1>
  <a href="<?= base_url("pages/quotation/add/$category_param") ?>" class="btn btn-primary btn-lg btn-icon btn-add">Buat
    Penawaran
    harga</a>
</div>
<table id="example" class="table nowrap table-hover" style="width:100%">
  <thead>
    <tr>
      <th class="text-start">No.</th>
      <th>Nomor Penawaran</th>
      <th>Tanggal</th>

      <?php if ($category_param == 'outgoing') { ?>
      <th>Penerima</th>
      <?php } elseif ($category_param == 'incoming') { ?>
      <th>Pengirim</th>
      <?php } ?>

      <th>Status</th>
      <th>Total</th>

      <?php if ($category_param == 'outgoing') { ?>
      <th>Pengirim</th>
      <?php } elseif ($category_param == 'incoming') { ?>
      <th>Penerima</th>
      <?php } ?>

      <th>Aksi</th>
    </tr>
  </thead>
  <tbody>
    <?php if (empty($data_penawaran_harga)) : ?>
    <tr>
      <td colspan="8">Tidak ada data</td>
    </tr>
    <?php else: $no = 1; foreach ($data_penawaran_harga as $ph): ?>
    <tr>
      <td class="text-start"><?= $no; ?></td>
      <td><?= strtoupper($ph['no_penawaran']); ?></td>
      <td><?= dateID($ph['tanggal']); ?></td>

      <?php if ($category_param == 'outgoing') { ?>
      <td class="text-wrap"><?= ucwords($ph['nama_penerima']); ?></td>
      <?php } elseif ($category_param == 'incoming') { ?>
      <td class="text-wrap"><?= ucwords($ph['nama_pengirim']); ?></td>
      <?php } ?>

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
        } elseif ($ph['status'] == 'disetujui') {
            $status_class = 'text-bg-success';
        }
        ?>
        <span class="badge rounded-pill <?= $status_class ?>"><?= ucfirst($ph['status']) ?></span>
      </td>
      <td><?= formatRupiah($ph['total']); ?></td>
      <td>
        <?php if (!empty($ph['logo'])) : ?>
        <img class="me-3" src="<?= base_url($ph['logo']); ?>" alt="Logo" width="50">
        <?php 
          endif; 
      
          if ($category_param == 'outgoing') { 
            echo ucwords($ph['nama_pengirim']);
          } elseif ($category_param == 'incoming') {
            echo ucwords($ph['nama_penerima']); 
          } 
        ?>
      </td>

      <td>
        <div class="btn-group">
          <button type="button" class="btn-act btn-approve bg-transparent" data-bs-toggle="modal"
            data-bs-target="#approveModal<?= $ph['id_penawaran']; ?>" title="Perbarui Status"
            data-status="<?= $ph['status']; ?>"></button>

          <a href="<?= base_url("pages/quotation/detail/$category_param/{$ph['id_penawaran']}") ?>"
            class="btn-act btn-view" title="Lihat Detail"></a>

          <a href="<?= base_url("pages/quotation/edit/$category_param/{$ph['id_penawaran']}") ?>"
            class="btn-act btn-edit" title="Ubah Data"></a>

          <a href="javascript:void(0);"
            onclick="confirmDelete('del.php?category=<?= $category_param ?>&id=<?= $ph['id_penawaran']; ?>', 'Apakah Anda yakin ingin menghapus data penawaran ini? Semua detail penawaran terkait juga akan dihapus.')"
            class="btn-act btn-del" title="Hapus Data"></a>
        </div>
      </td>
    </tr>

    <!-- Modal Approve -->
    <div class="modal fade" id="approveModal<?= $ph['id_penawaran']; ?>" data-bs-backdrop="static" tabindex="-1"
      aria-labelledby="approveModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-md">
        <div class="modal-content">
          <div class="modal-header">
            <h3 class="modal-title fs-5" id="approveModalLabel">Perbarui Status Penawaran Harga</h3>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <p><?= "No: " . strtoupper($ph['no_penawaran']) ?></p>
            <?php include 'approve.php'; ?>
            <!-- Include file approve.php untuk konten modal persetujuan -->
          </div>
        </div>
      </div>
    </div>

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