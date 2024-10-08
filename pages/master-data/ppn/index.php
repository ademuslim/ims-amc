<?php
$page_title = "PPN";
require '../../../includes/header.php';

$data_ppn = selectData('ppn', 'status_hapus = 0');
?>
<div class="d-flex justify-content-between align-items-center mb-4">
  <h1 class="fs-5 m-0">Data PPN</h1>
  <button type="button" class="btn btn-primary btn-lg btn-icon btn-add" data-bs-toggle="modal"
    data-bs-target="#addModal">
    Tambah Data PPN
  </button>
</div>
<div class="row" style="width: 480px;">
  <table id="example" class="table table-hover" style="width:100%">
    <thead>
      <tr>
        <th>No.</th>
        <th>Jenis PPN</th>
        <th>Tarif</th>
        <th>Keterangan</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($data_ppn)) : ?>
      <tr>
        <td colspan="9">Tidak ada data</td>
      </tr>
      <?php else : ?>
      <?php $no = 1; ?>
      <?php foreach ($data_ppn as $ppn) : ?>
      <tr>
        <td class="text-start"><?= $no; ?></td>
        <td class="text-primary"><?= ucwords($ppn['jenis_ppn']); ?></td>
        <td><?= $ppn['tarif'] . "%"; ?></td>
        <td><?= ucwords($ppn['keterangan']) ?></td>
        <td>
          <div class="btn-group">
            <button type="button" class="btn-act btn-edit bg-transparent" data-bs-toggle="modal"
              data-bs-target="#editModal<?= $ppn['id_ppn']; ?>" title="Ubah Data"></button>
            <a href="javascript:void(0);"
              onclick="confirmDelete('del.php?id=<?= $ppn['id_ppn']; ?>', 'Apakah Anda yakin ingin menghapus data ini? Data yang sudah dihapus tidak dapat dikembalikan.')"
              class="btn-act btn-del" title="Hapus Data"></a>
          </div>
        </td>
      </tr>

      <!-- Modal Edit -->
      <div class="modal fade" id="editModal<?= $ppn['id_ppn']; ?>" data-bs-backdrop="static" tabindex="-1"
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