<?php // master-data/penawaran_harga/index.php
require '../../includes/header.php';
// Select data "penawaran_harga"
$data_penawaran_harga = selectData('penawaran_harga');
?>

<!-- Button add modal -->
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
  Tambah Data Penawaran Harga
</button>

<!-- Tampil data -->
<div class="mt-3">
  <h2>Data Penawaran Harga</h2>
  <table class="table">
    <thead>
      <tr>
        <th>ID Penawaran</th>
        <th>ID Pengirim</th>
        <th>Nomor Penawaran</th>
        <th>Tanggal</th>
        <th>Total</th>
        <th>Catatan</th>
        <th>ID Penerima</th>
        <th>Diskon</th>
        <th>ID PPN</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($data_penawaran_harga)) : ?>
      <tr>
        <td colspan="10">Tidak ada data</td>
      </tr>
      <?php else : ?>
      <?php foreach ($data_penawaran_harga as $penawaran) : ?>
      <tr>
        <td><?= $penawaran['id_penawaran']; ?></td>
        <td><?= $penawaran['id_pengirim']; ?></td>
        <td><?= $penawaran['no_penawaran']; ?></td>
        <td><?= $penawaran['tanggal']; ?></td>
        <td><?= $penawaran['total']; ?></td>
        <td><?= $penawaran['catatan']; ?></td>
        <td><?= $penawaran['id_penerima']; ?></td>
        <td><?= $penawaran['diskon']; ?></td>
        <td><?= $penawaran['id_ppn']; ?></td>
        <td>
          <button type="button" class="btn btn-primary" data-bs-toggle="modal"
            data-bs-target="#editModal<?= $penawaran['id_penawaran']; ?>">Edit</button>
          <a href="del.php?id=<?= $penawaran['id_penawaran']; ?>" class="btn btn-danger">Hapus</a>
        </td>
      </tr>

      <!-- Modal Edit -->
      <div class="modal fade" id="editModal<?= $penawaran['id_penawaran']; ?>" tabindex="-1"
        aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h1 class="modal-title fs-5" id="editModalLabel">Edit Data Penawaran Harga</h1>
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
require '../../includes/footer.php';
?>