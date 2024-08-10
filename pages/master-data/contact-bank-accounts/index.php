<?php
$page_title = "Contact Bank Accounts";
require '../../../includes/header.php';

$mainTable = 'rekening_kontak';
$joinTables = [
    ["kontak", "rekening_kontak.id_kontak = kontak.id_kontak"], 
];
$columns = 'rekening_kontak.*, kontak.nama_kontak';
$conditions = "rekening_kontak.status_hapus = 0";
$orderBy = 'kontak.nama_kontak DESC';

$data_rekening_kontak = selectDataJoin($mainTable, $joinTables, $columns, $conditions);
?>

<div class="d-flex justify-content-between align-items-center mb-4">
  <h1 class="fs-5 m-0">Data Rekening Kontak</h1>
  <button type="button" class="btn btn-primary btn-lg btn-icon btn-add" data-bs-toggle="modal"
    data-bs-target="#addModal">
    Tambah Data Rekening Kontak
  </button>
</div>
<table id="example" class="table nowrap table-hover" style="width:100%">
  <thead>
    <tr>
      <th>No.</th>
      <th>Nama Kontak</th>
      <th>Nama Bank</th>
      <th>Nomor Rekening</th>
      <th>Pemegang Rekening</th>
      <th>Kode SWIFT</th>
      <th>Cabang Bank</th>
      <th>Keterangan</th>
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody>
    <?php if (empty($data_rekening_kontak)) : ?>
    <tr>
      <td colspan="10">Tidak ada data</td>
    </tr>
    <?php else : ?>
    <?php $no = 1; ?>
    <?php foreach ($data_rekening_kontak as $rek) : ?>
    <tr>
      <td><?= $no; ?></td>
      <td><?= strtoupper($rek['nama_kontak']); ?></td>
      <td><?= strtoupper($rek['nama_bank']); ?></td>
      <td class="text-primary"><?= $rek['nomor_rekening']; ?></td>
      <td><?= strtoupper($rek['pemegang_rekening']); ?></td>
      <td><?= !empty($rek['kode_swift']) ? $rek['kode_swift'] : '_'; ?></td>
      <td><?= !empty($rek['cabang_bank']) ? strtoupper($rek['cabang_bank']) : '_'; ?></td>
      <td><?= !empty($rek['keterangan']) ? $rek['keterangan'] : '_'; ?></td>
      <td>
        <div class="btn-group">
          <button type="button" class="btn-act btn-edit bg-transparent" data-bs-toggle="modal"
            data-bs-target="#editModal<?= $rek['id_rekening']; ?>" title="Ubah Data"></button>
          <a href="javascript:void(0);"
            onclick="confirmDelete('del.php?id=<?= $rek['id_rekening']; ?>', 'Apakah Anda yakin ingin menghapus data ini? Data yang sudah dihapus tidak dapat dikembalikan.')"
            class="btn-act btn-del" title="Hapus Data"></a>
        </div>
      </td>
    </tr>

    <!-- Modal Edit -->
    <div class="modal fade" id="editModal<?= $rek['id_rekening']; ?>" data-bs-backdrop="static" tabindex="-1"
      aria-labelledby="editModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="editModalLabel">Edit Data Rekening</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <?php include 'edit.php'; ?>
            <!-- Include file edit.php untuk konten modal edit -->
          </div>
        </div>
      </div>
    </div>
    <?php 
    $no++;
    endforeach; 
    endif; ?>
  </tbody>
</table>

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