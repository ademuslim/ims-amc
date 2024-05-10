<?php
$page_title = "Edit Quotation";
require '../../includes/header.php';

if (isset($_GET['id']) && !empty($_GET['id'])) {
  $id = $_GET['id'];
  
  // Ambil data penawaran yang akan diedit
  $mainTable = 'penawaran_harga';
  $joinTables = [
      ['kontak_internal', 'penawaran_harga.id_pengirim = kontak_internal.id_pengirim'], 
      ['pelanggan', 'penawaran_harga.id_penerima = pelanggan.id_pelanggan'],
      ['ppn', 'penawaran_harga.id_ppn = ppn.id_ppn']
  ];

  // Kolom-kolom yang ingin diambil dari tabel utama dan tabel-tabel yang di-join
  $columns = 'penawaran_harga.*, kontak_internal.nama_pengirim AS nama_pengirim, pelanggan.nama_pelanggan AS nama_penerima, ppn.jenis_ppn AS jenis_ppn';

  // Kondisi tambahan untuk seleksi data berdasarkan ID penawaran
  $conditions = "penawaran_harga.id_penawaran = '$id'";

  // Panggil fungsi selectDataJoin dengan ORDER BY
  $data_penawaran_harga = selectDataJoin($mainTable, $joinTables, $columns, $conditions);
  
  // Periksa apakah data ditemukan
  if (empty($data_penawaran_harga)) {
    $_SESSION['error_message'] = "Penawaran dengan ID yang diberikan tidak ditemukan.";
    header("Location: index.php");
    exit();
  }
  
  // Ambil data penawaran dari array pertama
  $ph = $data_penawaran_harga[0];

  // Ambil data detail penawaran yang akan diedit
  $mainTable = 'detail_penawaran';
  $joinTables = [
      ['produk', 'detail_penawaran.id_produk = produk.id_produk']
  ];

  // Kolom-kolom yang ingin diambil dari tabel utama dan tabel-tabel yang di-join
  $columns = 'detail_penawaran.*, produk.*';

  // Kondisi tambahan untuk seleksi data berdasarkan ID penawaran
  $conditions = "detail_penawaran.id_penawaran = '$id'";

  // Panggil fungsi selectDataJoin dengan ORDER BY
  $data_detail_penawaran = selectDataJoin($mainTable, $joinTables, $columns, $conditions);
  
  // Periksa apakah data detail penawaran ditemukan
  if (empty($data_detail_penawaran)) {
    // Tidak ada detail penawaran yang ditemukan, tetap tampilkan formulir edit dengan input detail produk kosong
    $detail = array(); // Inisialisasi $detail sebagai array kosong
  } else {
    // Simpan semua data detail penawaran dalam sebuah variabel
    $detail = $data_detail_penawaran;
  }
} else {
  $_SESSION['error_message'] = "ID penawaran tidak valid.";
  header("Location: index.php");
  exit();
}
?>

<div class="container">
  <form action="process.php" method="POST" class="row needs-validation" enctype="multipart/form-data" novalidate>
    <!-- Tambahkan input hidden untuk menyimpan ID penawaran -->
    <input type="hidden" name="id_penawaran" value="<?= $ph['id_penawaran']; ?>">

    <!-- Input hidden untuk menyimpan lokasi file gambar logo sebelumnya -->
    <input type="hidden" name="existing_logo" value="<?= $ph['logo']; ?>">

    <div class="row mb-3">
      <label for="pengirim" class="col-sm-2 col-form-label">Pengirim</label>
      <div class="col-sm-10">
        <select class="form-select form-select-sm" id="pengirim" name="pengirim" required>
          <?php
            $kontak_internal = selectData("kontak_internal");
            foreach ($kontak_internal as $row_pengirim) {
              $selected = ($row_pengirim['id_pengirim'] == $ph['id_pengirim']) ? "selected" : "";
              echo '<option value="' . $row_pengirim['id_pengirim'] . '" ' . $selected . '>' . ucwords($row_pengirim['nama_pengirim']) . '</option>';
            }
          ?>
        </select>
        <div class="invalid-feedback">
          Harap pilih pengirim.
        </div>
      </div>
    </div>

    <!-- Input untuk logo -->
    <!-- Tampilkan gambar sebelumnya jika ada -->
    <?php if (!empty($ph['logo'])): ?>
    <div class="row mb-3">
      <div class="col-sm-2"></div>
      <div class="col-sm-10">
        <img id="preview" src="<?= base_url($ph['logo']); ?>" alt="Previous Logo" style="max-width: 200px;">
      </div>
    </div>
    <?php endif; ?>

    <div class="row mb-3">
      <label for="logo" class="col-sm-2 col-form-label">Logo</label>
      <div class="col-sm-10">
        <input type="file" class="form-control form-control-sm" id="logo" name="logo" accept="image/*"
          onchange="previewImage(event)">
        <div class="invalid-feedback">
          Harap pilih file gambar dengan format yang valid.
        </div>
        <?php if (!empty($ph['logo'])): ?>
        <button type="button" id="cancelButton" class="btn btn-danger btn-sm mt-2" style="display: none;"
          onclick="cancelChange()">Batal</button>
        <?php endif; ?>
      </div>
    </div>

    <div class="row mb-3">
      <label for="tanggal" class="col-sm-2 col-form-label">Tanggal</label>
      <div class="col-sm-10">
        <input type="datetime-local" class="form-control form-control-sm" id="tanggal" name="tanggal"
          value="<?= date('Y-m-d\TH:i', strtotime($ph['tanggal'])); ?>" required>
        <div class="invalid-feedback">
          Harap pilih tanggal.
        </div>
      </div>
    </div>

    <div class="row mb-3">
      <label for="no_penawaran" class="col-sm-2 col-form-label">No:</label>
      <div class="col-sm-10">
        <input type="text" class="form-control form-control-sm" id="no_penawaran" name="no_penawaran"
          value="<?= $ph['no_penawaran']; ?>" readonly required>
        <div class="invalid-feedback">
          Sistem error, nomor penawaran gagal dimuat.
        </div>
      </div>
    </div>

    <div class="row mb-3">
      <label for="penerima" class="col-sm-2 col-form-label">Penerima</label>
      <div class="col-sm-10">
        <select class="form-select form-select-sm" id="penerima" name="penerima" required>
          <option value="" disabled>-- Pilih Penerima --</option>
          <?php
          $pelanggan = selectData("pelanggan");
          foreach ($pelanggan as $row_penerima) {
            $selected = ($row_penerima['id_pelanggan'] == $ph['id_penerima']) ? "selected" : "";
            echo '<option value="' . $row_penerima['id_pelanggan'] . '" ' . $selected . '>' . ucwords($row_penerima['nama_pelanggan']) . '</option>';
          }
          ?>
        </select>
        <div class="invalid-feedback">
          Harap pilih pelanggan.
        </div>
      </div>
    </div>

    <div class="row mb-3">
      <label for="up" class="col-sm-2 col-form-label">U.P.</label>
      <div class="col-sm-10">
        <input type="text" class="form-control form-control-sm" id="up" name="up" value="<?= $ph['up']; ?>" required>
        <div class="invalid-feedback">
          Harap masukan U.P.(Contact Person) dengan valid.
        </div>
      </div>
    </div>

    <div class="row mb-3">
      <label for="diskon" class="col-sm-2 col-form-label">Diskon</label>
      <div class="col-sm-10">
        <input type="number" class="form-control form-control-sm" id="diskon" name="diskon" min="0" step="0.01"
          value="<?= $ph['diskon']; ?>" required>
        <div class="invalid-feedback">
          Harap masukan diskon dengan valid.
        </div>
      </div>
    </div>

    <div class="row mb-3">
      <label for="jenis_ppn" class="col-sm-2 col-form-label">PPN</label>
      <div class="col-sm-10">
        <select class="form-select form-select-sm" id="jenis_ppn" name="jenis_ppn" required>
          <option value="" disabled>-- Pilih Jenis PPN --</option>
          <?php
          $ppn = selectData("ppn");
          foreach ($ppn as $row_ppn) {
              $selected = ($row_ppn['id_ppn'] == $ph['id_ppn']) ? "selected" : "";
              echo '<option value="' . $row_ppn['id_ppn'] . '" ' . $selected . '>' . $row_ppn['jenis_ppn'] . '</option>';
          }
          ?>
        </select>
        <div class="invalid-feedback">
          Harap pilih PPN dengan valid.
        </div>
      </div>
    </div>

    <div class="row mb-3">
      <label for="catatan" class="col-sm-2 col-form-label">Catatan</label>
      <div class="col-sm-10">
        <textarea class="form-control form-control-sm" id="catatan" name="catatan"
          row="1"><?= $ph['catatan']; ?></textarea>
      </div>
    </div>

    <!-- Edit detail -->
    <?php if (empty($data_detail_penawaran)) : ?>
    <!-- Tampilkan link ke add-detail.php jika belum ada detail produk -->
    <div class="container">
      <div class="alert alert-warning" role="alert">
        Detail penawaran belum ada. <a href="add-detail.php?id=<?= $id ?>">Tambahkan Detail Penawaran</a>
      </div>
    </div>
    <?php else: ?>
    <!-- Tampilkan input edit detail produk jika ada data detail produk -->
    <?php foreach ($detail as $dt): ?>
    <div class="row">
      <input type="hidden" name="id_detail[]" value="<?= $dt['id_detail_penawaran']; ?>">
      <div class="col-md-4 mb-3">
        <select class="form-select form-select-sm" name="id_produk[]" required>
          <option value="" selected disabled>-- Pilih Produk --</option>
          <?php
          $produk = selectData("produk");
          foreach ($produk as $row) {
              $selected = ($row['id_produk'] == $dt['id_produk']) ? 'selected' : '';
              echo '<option value="' . $row['id_produk'] . '" ' . $selected . '>' . $row['nama_produk'] . '</option>';
          }
          ?>
        </select>
        <div class="invalid-feedback">
          Harap pilih produk.
        </div>
      </div>

      <div class="col-md-3 mb-3">
        <input type="number" name="jumlah[]" placeholder="Jumlah" class="form-control form-control-sm" min="0"
          value="<?= $dt['jumlah']; ?>" required>
        <div class="invalid-feedback">
          Harap masukkan jumlah dengan valid.
        </div>
      </div>

      <div class="col-md-3 mb-3">
        <input type="number" name="harga_satuan[]" placeholder="Harga Satuan" class="form-control form-control-sm"
          min="0" value="<?= $dt['harga_satuan']; ?>" required>
        <div class="invalid-feedback">
          Harap masukkan harga satuan dengan valid.
        </div>
      </div>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>
</div>

<button type="submit" class="btn btn-primary btn-sm" name="edit">Simpan</button>
</form>
</div>

<script>
document.getElementById("tanggal").addEventListener("change", function() {
  let tanggalInput = document.getElementById("tanggal").value;
  if (tanggalInput) {
    let date = new Date(tanggalInput);
    let month = date.getMonth() + 1;
    let year = date.getFullYear();

    // Format bulan menjadi dua digit jika nilainya kurang dari 10
    if (month < 10) {
      month = '0' + month;
    }

    // Request nomor penawaran berdasarkan bulan dan tahun ke getDocumentNumber.php
    let xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById("no_penawaran").value = this.responseText;
      }
    };
    xhttp.open("GET", "getDocumentNumber.php?month=" + month + "&year=" + year, true);
    xhttp.send();
  } else {
    document.getElementById("no_penawaran").value = ""; // Kosongkan nilai nomor penawaran jika tanggal tidak diisi
  }
});

// Jalankan JavaScript untuk mencegah pengiriman form jika ada input yang tidak valid
document.addEventListener('DOMContentLoaded', function() {
  let forms = document.querySelectorAll('.needs-validation');

  Array.prototype.slice.call(forms)
    .forEach(function(form) {
      form.addEventListener('submit', function(event) {
        if (!form.checkValidity()) {
          event.preventDefault();
          event.stopPropagation();
        }

        form.classList.add('was-validated');
      }, false);
    });
});

// Penanganan logo pada form edit
let originalImageSrc = <?= json_encode(base_url($ph['logo'])); ?>;

function previewImage(event) {
  let input = event.target;
  let reader = new FileReader();
  reader.onload = function() {
    let imgElement = document.getElementById("preview");
    imgElement.src = reader.result;

    // Tampilkan tombol "Batal" jika ada perubahan pada input gambar
    document.getElementById("cancelButton").style.display = "block";
  }
  reader.readAsDataURL(input.files[0]);
}

function cancelChange() {
  let imgElement = document.getElementById("preview");
  imgElement.src = originalImageSrc;
  document.getElementById("logo").value = null; // Clear file input

  // Sembunyikan tombol "Batal"
  document.getElementById("cancelButton").style.display = "none";
}
</script>

<?php
require '../../includes/footer.php';
?>