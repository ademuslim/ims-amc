<?php
$page_title = "Users";
require '../../../includes/header.php';

$data_pengguna = selectData('pengguna', 'status_hapus = 0');
?>
<div class="d-flex justify-content-between align-items-center mb-4">
  <h1 class="fs-5 m-0">Data Pengguna</h1>
  <button type="button" class="btn btn-primary btn-lg btn-icon btn-add" data-bs-toggle="modal"
    data-bs-target="#addModal">
    Tambah Data Pengguna
  </button>
</div>
<div class="row" style="width: 500px;">
  <table id="example" class="table nowrap table-hover">
    <thead>
      <tr>
        <th>No.</th>
        <th>Nama Lengkap</th>
        <th>Username</th>
        <th>Tipe Pengguna</th>
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
        <td><?= ucwords($pengguna['nama_lengkap']); ?></td>
        <td><?= $pengguna['nama_pengguna']; ?></td>
        <td>
          <?= $pengguna['tipe_pengguna'] == 'kepala_perusahaan' ? "Kepala Perusahaan" : ucwords($pengguna['tipe_pengguna']); ?>
        </td>
        <td>
          <div class="btn-group">
            <?php if ($pengguna['id_pengguna'] == $id_pengguna) : ?>
            <!-- Tombol untuk mengubah data dan password hanya untuk pengguna yang sedang login -->
            <button type="button" class="btn-act btn-edit bg-transparent" data-bs-toggle="modal"
              data-bs-target="#editModal<?= $pengguna['id_pengguna']; ?>" title="Ubah Data"></button>

            <button type="button" class="btn-act btn-password bg-transparent" data-bs-toggle="modal"
              data-bs-target="#changePasswordModal<?= $pengguna['id_pengguna']; ?>" title="Ubah Password"></button>
            <?php endif; ?>

            <?php if ($peran_pengguna === 'superadmin') : ?>
            <a href="javascript:void(0);"
              onclick="confirmDelete('del.php?id=<?= $pengguna['id_pengguna']; ?>', 'Apakah Anda yakin ingin menghapus data user ini? Semua data terkait juga akan dihapus dan tidak dapat dikembalikan.')"
              class="btn-act btn-del" title="Hapus Data"></a>
            <?php endif; ?>
          </div>
        </td>
      </tr>

      <!-- Modal Edit -->
      <div class="modal fade" id="editModal<?= $pengguna['id_pengguna']; ?>" data-bs-backdrop="static" tabindex="-1"
        aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h1 class="modal-title fs-5" id="editModalLabel">Ubah Data pengguna</h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <?php include 'edit.php'; ?>
              <!-- Include file edit.php untuk konten modal edit -->
            </div>
          </div>
        </div>
      </div>

      <!-- Modal Change Password -->
      <div class="modal fade" id="changePasswordModal<?= $pengguna['id_pengguna']; ?>" data-bs-backdrop="static"
        tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h1 class="modal-title fs-5" id="changePasswordModalLabel">Ubah Password</h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <?php include 'change_password.php'; ?>
            </div>
          </div>
        </div>
      </div>
      <?php
    $no++;
    endforeach;
    endif;
    ?>
    </tbody>
  </table>
</div>

<script>
function confirmDelete(url, message) {
  console.log(url); // Tambahkan ini untuk debugging
  Swal.fire({
    title: 'Konfirmasi',
    text: message,
    position: "top",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Ya, Hapus!',
    cancelButtonText: 'Batal'
  }).then((result) => {
    if (result.isConfirmed) {
      window.location.href = url;
    }
  });
}


// Sweetalert
document.addEventListener('DOMContentLoaded', function() {
  // Tampilkan pesan sukses jika ada
  if ('<?= $success_message ?>' !== '') {
    Swal.fire({
      toast: true,
      position: "top",
      icon: "success",
      title: "Berhasil",
      text: "<?= $success_message ?>",
      showConfirmButton: false,
      timer: 7000,
      timerProgressBar: true,
      showCloseButton: true,
    });
  }

  // Tampilkan pesan error jika ada
  if ('<?= $error_message ?>' !== '') {
    Swal.fire({
      toast: true,
      position: "top",
      icon: "error",
      title: "Gagal",
      text: "<?= $error_message ?>",
      showConfirmButton: false,
      timer: 7000,
      timerProgressBar: true,
      showCloseButton: true,
    });
  }
});

$(document).ready(function() {
  // Tangkap klik pada setiap tombol di dalam .btn-group
  $('.btn-group').on('click', function(event) {
    // Hentikan propagasi event agar tidak mencapai elemen td
    event.stopPropagation();
  });
});
</script>

<?php
// Add Modal
require 'add.php';
require '../../../includes/footer.php';
?>