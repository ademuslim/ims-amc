<?php
$page_title = "Users";
require '../../../includes/header.php';

$data_pengguna = selectData('pengguna', 'status_hapus = 0');
?>
<div class="d-flex justify-content-between align-items-center mb-4">
  <h1 class="fs-5 m-0">Data User</h1>
  <button type="button" class="btn btn-primary btn-lg btn-icon btn-add" data-bs-toggle="modal"
    data-bs-target="#addModal">
    Tambah Data Pengguna
  </button>
</div>
<table id="example" class="table table-hover" style="width:100%">
  <thead>
    <tr>
      <th>No.</th>
      <th colspan="4">Nama</th>
      <th>Email</th>
      <th>Tipe</th>
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody>
    <?php if (empty($data_pengguna)) : ?>
    <tr>
      <td colspan="5">Tidak ada data</td>
    </tr>
    <?php else : ?>
    <?php $no = 1; ?>
    <?php foreach ($data_pengguna as $pengguna) : ?>
    <tr>
      <td class="text-start"><?= $no; ?></td>
      <td colspan="4"><?= ucwords($pengguna['nama_pengguna']); ?></td>
      <td><?= $pengguna['email']; ?></td>
      <td>
        <?php
        if ($pengguna['tipe_pengguna'] == 'kepala_perusahaan'){
          echo "Kepala Perusahaan";
        } elseif ($pengguna['tipe_pengguna'] != 'kepala_perusahaan'){
          echo ucwords($pengguna['tipe_pengguna']);
        }
        ?>
      </td>
      <td>
        <div class="btn-group">
          <button type="button" class="btn-act btn-edit bg-transparent" data-bs-toggle="modal"
            data-bs-target="#editModal<?= $pengguna['id_pengguna']; ?>" title="Ubah Data"></button>
          <a href="javascript:void(0);"
            onclick="confirmDelete('del.php?id=<?= $pengguna['id_pengguna']; ?>', 'Apakah Anda yakin ingin menghapus data user ini? Semua data terkait juga akan dihapus dan tidak dapat dikembalikan.')"
            class="btn-act btn-del" title="Hapus Data"></a>
        </div>
      </td>
    </tr>

    <!-- Modal Edit -->
    <div class="modal fade" id="editModal<?= $pengguna['id_pengguna']; ?>" data-bs-backdrop="static" tabindex="-1"
      aria-labelledby="editModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
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
    <?php $no++; ?>
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