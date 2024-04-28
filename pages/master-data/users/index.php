<?php // master-data/product/index
require '../../../includes/header.php';
// selectData "pengguna"
$data_pengguna = selectData('pengguna');
?>

<!-- Button add modal -->
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
  Tambah Data Pengguna
</button>

<!-- Tampil data -->
<div class="mt-3">
  <h2>Data Pengguna</h2>
  <table class="table">
    <thead>
      <tr>
        <th scope="col">ID</th>
        <th scope="col">Nama</th>
        <th scope="col">Email</th>
        <th scope="col">Tipe</th>
        <th scope="col">Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($data_pengguna)) : ?>
      <tr>
        <td colspan="5">Tidak ada data</td>
      </tr>
      <?php else : ?>
      <?php foreach ($data_pengguna as $pengguna) : ?>
      <tr>
        <td><?php echo $pengguna['id_pengguna']; ?></td>
        <td><?php echo $pengguna['nama_pengguna']; ?></td>
        <td><?php echo $pengguna['email']; ?></td>
        <td><?php echo $pengguna['tipe_pengguna']; ?></td>
        <td>
          <button type="button" class="btn btn-primary" data-bs-toggle="modal"
            data-bs-target="#editModal<?php echo $pengguna['id_pengguna']; ?>">Edit</button>
          <a href="del.php?id=<?php echo $pengguna['id_pengguna']; ?>" class="btn btn-danger">Hapus</a>
        </td>
      </tr>

      <!-- Modal Edit -->
      <div class="modal fade" id="editModal<?php echo $pengguna['id_pengguna']; ?>" tabindex="-1"
        aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h1 class="modal-title fs-5" id="editModalLabel">Edit Data pengguna</h1>
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