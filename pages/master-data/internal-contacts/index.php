<?php // master-data/internal-contacts/index
require '../../../includes/header.php';
// selectData "kontak"
$data_kontak_internal = selectData('kontak_internal');
?>

<!-- Button add modal -->
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
  Tambah Data Kontak Internal
</button>

<!-- Tampil data -->
<div class="mt-3">
  <h2>Data Kontak Internal</h2>
  <table class="table">
    <thead>
      <tr>
        <th>ID Kontak</th>
        <th>Nama</th>
        <th>Alamat</th>
        <th>Telepon</th>
        <th>Email</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($data_kontak_internal)) : ?>
      <tr>
        <td colspan="6">Tidak ada data</td>
      </tr>
      <?php else : ?>
      <?php foreach ($data_kontak_internal as $kontak) : ?>
      <tr>
        <td><?php echo $kontak['id_pengirim']; ?></td>
        <td><?php echo $kontak['nama_pengirim']; ?></td>
        <td><?php echo $kontak['alamat_pengirim']; ?></td>
        <td><?php echo $kontak['telepon']; ?></td>
        <td><?php echo $kontak['email']; ?></td>
        <td>
          <button type="button" class="btn btn-primary" data-bs-toggle="modal"
            data-bs-target="#editModal<?php echo $kontak['id_pengirim']; ?>">Edit</button>
          <a href="del.php?id=<?php echo $kontak['id_pengirim']; ?>" class="btn btn-danger">Hapus</a>
        </td>
      </tr>

      <!-- Modal Edit -->
      <div class="modal fade" id="editModal<?php echo $kontak['id_pengirim']; ?>" data-bs-backdrop="static"
        tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h1 class="modal-title fs-5" id="editModalLabel">Edit Data Kontak Internal</h1>
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