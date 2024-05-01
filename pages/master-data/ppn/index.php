<?php // master-data/product/index
require '../../../includes/header.php';
// selectData "ppn"
$data_ppn = selectData('ppn');
?>

<!-- Button add modal -->
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
  Tambah Data PPN
</button>

<!-- Tampil data -->
<div class="mt-3">
  <h2>Data PPN</h2>
  <table class="table">
    <thead>
      <tr>
        <th>ID</th>
        <th>Jenis PPN</th>
        <th>Tarif</th>
        <th>Keterangan</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($data_ppn)) : ?>
      <tr>
        <td colspan="4">Tidak ada data</td>
      </tr>
      <?php else : ?>
      <?php foreach ($data_ppn as $ppn) : ?>
      <tr>
        <td><?= $ppn['id_ppn']; ?></td>
        <td><?= $ppn['jenis_ppn']; ?></td>
        <td><?= $ppn['tarif']; ?></td>
        <td><?= $ppn['keterangan']; ?></td>
        <td>
          <button type="button" class="btn btn-primary" data-bs-toggle="modal"
            data-bs-target="#editModal<?= $ppn['id_ppn']; ?>">Edit</button>
          <a href="del.php?id=<?= $ppn['id_ppn']; ?>" class="btn btn-danger">Hapus</a>
        </td>
      </tr>

      <!-- Modal Edit -->
      <div class="modal fade" id="editModal<?= $ppn['id_ppn']; ?>" data-bs-backdrop="static" tabindex="-1"
        aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h1 class="modal-title fs-5" id="editModalLabel">Edit Data PPN</h1>
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