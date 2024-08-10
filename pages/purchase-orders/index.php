<?php
$category_param = isset($_GET['category']) ? $_GET['category'] : '';
$page_title = $category_param === 'outgoing' ? 'PO Outgoing' : 'PO Incoming';
$content_title = $category_param === 'outgoing' ? 'Keluar' : 'Masuk';
$button_add = $category_param === 'outgoing' ? 'Buat Purchase Order' : 'Terima Purchase Order';

require '../../includes/header.php';

$category = ($category_param === 'outgoing') ? 'keluar' : (($category_param === 'incoming') ? 'masuk' : die("Kategori tidak valid"));

$mainTable = 'pesanan_pembelian';
$joinTables = [
  ["kontak pengirim", "pesanan_pembelian.id_pengirim = pengirim.id_kontak"], 
  ["kontak penerima", "pesanan_pembelian.id_penerima = penerima.id_kontak"],
  ['ppn', 'pesanan_pembelian.id_ppn = ppn.id_ppn']
];

$columns = 'pesanan_pembelian.*, pengirim.nama_kontak AS nama_pengirim, penerima.nama_kontak AS nama_penerima, ppn.jenis_ppn';
$conditions = "pesanan_pembelian.kategori = '$category' AND pesanan_pembelian.status_hapus = 0";
$orderBy = 'pesanan_pembelian.tanggal DESC';

$data_pesanan_pembelian = selectDataJoin($mainTable, $joinTables, $columns, $conditions, $orderBy);
?>

<div class="d-flex justify-content-between align-items-center mb-4">
  <h1 class="fs-5 m-0">Data Purchase Order <?= $content_title ?></h1>
  <a href="<?= base_url("pages/purchase-orders/add/$category_param") ?>"
    class="btn btn-primary btn-lg btn-icon btn-add"><?= $button_add ?></a>
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

      <?php if ($category_param == 'outgoing') { ?>
      <th>Pengirim</th>
      <?php } elseif ($category_param == 'incoming') { ?>
      <th>Penerima</th>
      <?php } ?>

      <th>Aksi</th>
    </tr>
  </thead>
  <tbody>
    <?php if (empty($data_pesanan_pembelian)) : ?>
    <tr>
      <td colspan="8">Tidak ada data</td>
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
        <span class="badge rounded-pill <?= $status_class ?>"><?= ucwords($po['status']) ?></span>
      </td>

      <td><?= formatRupiah($po['total']); ?></td>
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

      <td>
        <div class="btn-group">
          <button type="button" class="btn-act btn-approve bg-transparent" data-bs-toggle="modal"
            data-bs-target="#approveModal<?= $po['id_pesanan']; ?>" title="Perbarui Status"></button>

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

    <!-- Modal Approve -->
    <div class="modal fade" id="approveModal<?= $po['id_pesanan']; ?>" data-bs-backdrop="static" tabindex="-1"
      aria-labelledby="approveModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-md">
        <div class="modal-content">
          <div class="modal-header">
            <h3 class="modal-title fs-5" id="approveModalLabel">Perbarui Status Purchase Order</h3>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <p><?= "No: " . strtoupper($po['no_pesanan']) ?></p>
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
function confirmDelete(url, message) {
  console.log(url); // Tambahkan ini untuk debugging
  Swal.fire({
    title: 'Konfirmasi',
    text: message,
    position: "top",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Ya, Hapus!',
    cancelButtonText: 'Batal'
  }).then((result) => {
    if (result.isConfirmed) {
      window.location.href = url;
    }
  });
}

$(document).ready(function() {
  // Tangkap klik pada setiap tombol di dalam .btn-group
  $('.btn-group').on('click', function(event) {
    // Hentikan propagasi event agar tidak mencapai elemen td
    event.stopPropagation();
  });
});
</script>

<?php require '../../includes/footer.php'; ?>