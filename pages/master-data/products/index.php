<?php
$page_title = "Products";
require '../../../includes/header.php';

$data_produk = selectData('produk', 'status_hapus = 0');
?>
<div class="d-flex justify-content-between align-items-center mb-4">
  <h1 class="fs-5 m-0">Data Produk</h1>
  <button type="button" class="btn btn-primary btn-lg btn-icon btn-add" data-bs-toggle="modal"
    data-bs-target="#addModal">
    Tambah Data Produk
  </button>
</div>
<table id="example" class="table nowrap table-hover" style="width:100%">
  <thead>
    <tr>
      <th>No.</th>
      <th>No. Produk</th>
      <th>Nama Produk</th>
      <th>Status</th>
      <th>Harga</th>
      <th>Satuan</th>
      <th>Keterangan</th>
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody>
    <?php if (empty($data_produk)) : ?>
    <tr>
      <td colspan="8">Tidak ada data</td>
    </tr>
    <?php else : ?>
    <?php $no = 1; ?>
    <?php foreach ($data_produk as $produk) : ?>
    <tr>
      <td class="text-start"><?= $no; ?></td>
      <td class="text-primary"><?= strtoupper($produk['no_produk']); ?></td>
      <td><?= strtoupper($produk['nama_produk']); ?></td>
      <td class="text-center">
        <?php
        // Tentukan kelas bootstrap berdasarkan nilai status
        $status_class = '';
        if ($produk['status'] == 'draft') {
            $status_class = 'text-bg-warning';
        } elseif ($produk['status'] == 'pending') {
            $status_class = 'text-bg-info';
        } elseif ($produk['status'] == 'ditolak') {
            $status_class = 'text-bg-danger';
        } elseif ($produk['status'] == 'disetujui') {
            $status_class = 'text-bg-success';
        }
        ?>
        <span class="badge <?= $status_class ?>"><?= strtoupper($produk['status']); ?></span>
      </td>
      <td><?= formatRupiah($produk['harga']); ?></td>
      <td><?= strtoupper($produk['satuan']); ?></td>
      <td><?= ucwords($produk['keterangan']) ?></td>
      <td>
        <div class="btn-group">
          <button type="button" class="btn-act btn-edit bg-transparent" data-bs-toggle="modal"
            data-bs-target="#editModal<?= $produk['id_produk']; ?>" title="Ubah Data"></button>
          <a href="javascript:void(0);"
            onclick="confirmDelete('del.php?id=<?= $produk['id_produk']; ?>', 'Apakah Anda yakin ingin menghapus data ini? Data yang sudah dihapus tidak dapat dikembalikan.')"
            class="btn-act btn-del" title="Hapus Data"></a>
        </div>
      </td>
    </tr>

    <!-- Modal Edit -->
    <div class="modal fade" id="editModal<?= $produk['id_produk']; ?>" data-bs-backdrop="static" tabindex="-1"
      aria-labelledby="editModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="editModalLabel">Edit Data Produk</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <?php include 'edit.php'; ?>
            <!-- Include file edit.php untuk konten modal edit -->
          </div>
        </div>
      </div>
    </div>
    <?php 
    $no++;
    endforeach; 
    endif; ?>
  </tbody>
</table>

<?php
// Add Modal
require 'add.php';
require '../../../includes/footer.php';
?>