<?php
$page_title = "Products";
require '../../../includes/header.php';
$data_produk = selectData('produk');
?>

<!-- Button add modal -->
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
  Tambah Data Produk
</button>

<!-- Tampil data -->
<h1>Data Produk</h1>

<table id="example" class="table table-striped nowrap" style="width:100%">
  <thead>
    <tr>
      <th>No.</th>
      <!-- <th>ID Produk</th> -->
      <th>Nomor Produk</th>
      <th>Nama Produk</th>
      <th>Satuan</th>
      <th>Harga</th>
      <th>Status</th>
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody>
    <?php if (empty($data_produk)) : ?>
    <tr>
      <td colspan="7">Tidak ada data</td>
    </tr>
    <?php else : ?>
    <?php $no = 1; ?>
    <?php foreach ($data_produk as $produk) : ?>
    <tr>
      <td class="text-start"><?= $no; ?></td>
      <!-- <td><?= $produk['id_produk']; ?></td> -->
      <td class="text-primary"><?= strtoupper($produk['no_produk']); ?></td>
      <td><?= ucwords($produk['nama_produk']); ?></td>
      <td><?= strtoupper($produk['satuan']); ?></td>
      <td><?= $produk['harga']; ?></td>
      <td><?= ucwords($produk['status']); ?></td>
      <td>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
          data-bs-target="#editModal<?= $produk['id_produk']; ?>">Edit</button>
        <a href="del.php?id=<?= $produk['id_produk']; ?>" class="btn btn-danger">Hapus</a>
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
    <?php $no++; ?>
    <?php endforeach; ?>
    <?php endif; ?>
  </tbody>
</table>

<?php
// Add Modal
require 'add.php';
require '../../../includes/footer.php';
?>