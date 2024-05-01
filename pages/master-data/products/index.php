<?php // master-data/product/index
require '../../../includes/header.php';
// selectData "produk"
$data_produk = selectData('produk');
?>

<!-- Button add modal -->
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
  Tambah Data Produk
</button>

<!-- Tampil data -->
<div class="mt-3">
  <h2>Data Produk</h2>
  <table class="table">
    <thead>
      <tr>
        <th>ID Produk</th>
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
      <?php foreach ($data_produk as $produk) : ?>
      <tr>
        <td><?= $produk['id_produk']; ?></td>
        <td><?= $produk['no_produk']; ?></td>
        <td><?= $produk['nama_produk']; ?></td>
        <td><?= $produk['satuan']; ?></td>
        <td><?= $produk['harga']; ?></td>
        <td><?= $produk['status']; ?></td>
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
      <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<?php
// Add Modal
require 'add.php';
require '../../../includes/footer.php';
?>