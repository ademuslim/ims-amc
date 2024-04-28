<?php // master-data/internal-contacts/index
require '../../../includes/header.php';
// selectData "pelanggan"
$data_pelanggan = selectData('pelanggan');
?>

<!-- Button add modal -->
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
  Tambah Data Pelanggan
</button>

<!-- Tampil data -->
<div class="mt-3">
  <h2>Data Pelanggan</h2>
  <table class="table">
    <thead>
      <tr>
        <th scope="col">ID Pelanggan</th>
        <th scope="col">Nama</th>
        <th scope="col">Alamat</th>
        <th scope="col">Telepon</th>
        <th scope="col">Email</th>
        <th scope="col">Keterangan</th>
        <th scope="col">Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($data_pelanggan)) : ?>
      <tr>
        <td colspan="6">Tidak ada data</td>
      </tr>
      <?php else : ?>
      <?php foreach ($data_pelanggan as $pelanggan) : ?>
      <tr>
        <td><?php echo $pelanggan['id_pelanggan']; ?></td>
        <td><?php echo $pelanggan['nama_pelanggan']; ?></td>
        <td><?php echo $pelanggan['alamat']; ?></td>
        <td><?php echo $pelanggan['telepon']; ?></td>
        <td><?php echo $pelanggan['email']; ?></td>
        <td><?php echo $pelanggan['keterangan']; ?></td>
        <td>
          <button type="button" class="btn btn-primary" data-bs-toggle="modal"
            data-bs-target="#editModal<?php echo $pelanggan['id_pelanggan']; ?>">Edit</button>
          <a href="del.php?id=<?php echo $pelanggan['id_pelanggan']; ?>" class="btn btn-danger">Hapus</a>
        </td>
      </tr>

      <!-- Modal Edit -->
      <div class="modal fade" id="editModal<?php echo $pelanggan['id_pelanggan']; ?>" tabindex="-1"
        aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h1 class="modal-title fs-5" id="editModalLabel">Edit Data pelanggan Internal</h1>
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