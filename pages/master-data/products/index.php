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
        <th scope="col">ID Produk</th>
        <th scope="col">Nomor Produk</th>
        <th scope="col">Nama Produk</th>
        <th scope="col">Satuan</th>
        <th scope="col">Harga</th>
        <th scope="col">Status</th>
        <th scope="col">Aksi</th>
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
        <td><?php echo $produk['id_produk']; ?></td>
        <td><?php echo $produk['no_produk']; ?></td>
        <td><?php echo $produk['nama_produk']; ?></td>
        <td><?php echo $produk['satuan']; ?></td>
        <td><?php echo $produk['harga']; ?></td>
        <td><?php echo $produk['status']; ?></td>
        <td>
          <button type="button" class="btn btn-primary" data-bs-toggle="modal"
            data-bs-target="#editModal<?php echo $produk['id_produk']; ?>">Edit</button>
          <a href="del.php?id=<?php echo $produk['id_produk']; ?>" class="btn btn-danger">Hapus</a>
        </td>
      </tr>

      <!-- Modal Edit -->
      <div class="modal fade" id="editModal<?php echo $produk['id_produk']; ?>" tabindex="-1"
        aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
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