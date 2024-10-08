<?php
// Ambil nilai kategori dari parameter URL
$category_param = isset($_GET['category']) ? $_GET['category'] : '';

// Tentukan judul halaman berdasarkan kategori kontak
if ($category_param === 'internal') {
    $page_title = 'Internal Contacts';
    $title = 'Data Kontak Internal';
    $category = 'internal';
} elseif ($category_param === 'customer') {
    $page_title = 'Customer Contacts';
    $title = 'Data Pelanggan';
    $category = 'customer';
} elseif ($category_param === 'supplier') {
    $page_title = 'Supplier Contacts';
    $title = 'Data Pemasok';
    $category = 'supplier';
} else {
    // Default jika kategori tidak valid
    $page_title = 'Unknown Category';
    // Redirect atau tampilkan pesan error karena kategori tidak valid
}

require '../../../includes/header.php';

// Ambil data kontak sesuai dengan kategori yang dipilih dan status_hapus 0
$conditions = "kontak.kategori = '$category' AND status_hapus = 0";
$data_kontak = selectData('kontak', $conditions);
?>

<div class="d-flex justify-content-between align-items-center mb-4">
  <h1 class="fs-5 m-0"><?= $title ?></h1>
  <button type="button" class="btn btn-primary btn-lg btn-icon btn-add" data-bs-toggle="modal"
    data-bs-target="#addModal">
    Tambah Data Kontak
  </button>
</div>
<table id="example" class="table nowrap table-hover" style="width:100%">
  <thead>
    <tr>
      <th>No.</th>
      <th>Nama</th>
      <th>Email</th>
      <th>Telepon</th>
      <th>Alamat</th>
      <th>Keterangan</th>
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody>
    <?php if (empty($data_kontak)) : ?>
    <tr>
      <td colspan="7">Tidak ada data</td>
    </tr>
    <?php else : ?>
    <?php $no = 1; ?>
    <?php foreach ($data_kontak as $kontak) : ?>
    <tr>
      <td class="text-start"><?= $no; ?></td>
      <td class="text-primary"><?= ucwords($kontak['nama_kontak']); ?></td>
      <td><?= !empty($kontak['email']) ? $kontak['email'] : '_'; ?></td>
      <td><?= !empty($kontak['telepon']) ? $kontak['telepon'] : '_'; ?></td>
      <td><?= ucwords($kontak['alamat']); ?></td>
      <td><?= !empty($kontak['keterangan']) ? ucwords($kontak['keterangan']) : '_'; ?></td>
      <td>
        <div class="btn-group">
          <button type="button" class="btn-act btn-edit bg-transparent" data-bs-toggle="modal"
            data-bs-target="#editModal<?= $kontak['id_kontak']; ?>" title="Ubah Data"></button>
          <a href="javascript:void(0);"
            onclick="confirmDelete('del.php?category=<?= $category_param ?>&id=<?= $kontak['id_kontak']; ?>', 'Apakah Anda yakin ingin menghapus data ini? Data yang sudah dihapus tidak dapat dikembalikan.')"
            class="btn-act btn-del" title="Hapus Data"></a>
        </div>
      </td>
    </tr>

    <!-- Modal Edit -->
    <div class="modal fade" id="editModal<?= $kontak['id_kontak']; ?>" data-bs-backdrop="static" tabindex="-1"
      aria-labelledby="editModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="editModalLabel">Edit Data Kontak <?php echo ucwords($category); ?></h1>
            <!-- Perbarui judul modal dengan menggunakan kategori kontak -->
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <?php include 'edit.php'; ?>
            <!-- Sertakan file edit.php untuk konten modal edit -->
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
// Sertakan modal tambah
require 'add.php';
// Sertakan footer
require '../../../includes/footer.php';
?>