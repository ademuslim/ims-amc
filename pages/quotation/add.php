<?php
$page_title = "Add Quotation";
require '../../includes/header.php';

// Tampilkan pesan sukses jika ada
if (isset($_SESSION['success_message'])) {
  echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
          ' . $_SESSION['success_message'] . '
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
  unset($_SESSION['success_message']);
}

// Tampilkan pesan error jika ada
if (isset($_SESSION['error_message'])) {
  echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
          ' . $_SESSION['error_message'] . '
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
  unset($_SESSION['error_message']);
}
?>
<div class="container">
  <form action="process.php" method="POST" class="row needs-validation" enctype="multipart/form-data" novalidate>
    <div class="row mb-3">
      <label for="pengirim" class="col-sm-2 col-form-label">Pengirim</label>
      <div class="col-sm-10">
        <select class="form-select form-select-sm" id="pengirim" name="pengirim" required>
          <?php
            $kontak_internal = selectData("kontak_internal");
            foreach ($kontak_internal as $row_pengirim) {
              $selected = ""; // variabel untuk menentukan apakah opsi saat ini harus dipilih
              // Tentukan pengirim mana yang akan menjadi default
              if ($row_pengirim['nama_pengirim'] == "pt. mitra tehno gemilang") {
                $selected = "selected";
              }
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
    <div class="row mb-3">
      <label for="logo" class="col-sm-2 col-form-label">Logo</label>
      <div class="col-sm-10">
        <input type="file" class="form-control form-control-sm" id="logo" name="logo" accept="image/*"
          onchange="previewAddImage(event)" required>
        <div class="invalid-feedback">
          Harap pilih file gambar dengan format yang valid.
        </div>
      </div>
    </div>

    <!-- Tampilkan preview gambar jika ada -->
    <div class="row mb-3">
      <div class="col-sm-2"></div>
      <div class="col-sm-10">
        <div id="image-preview-container" style="display: none;">
          <img id="logo-preview" src="" alt="Preview Logo" style="max-width: 100px;">
        </div>
        <div id="placeholder-container" style="display: block; width: 100px; height: 100px; border: 1px solid #ccc;">
          <!-- Placeholder 100x100px -->
        </div>
      </div>
    </div>

    <div class="row mb-3">
      <label for="tanggal" class="col-sm-2 col-form-label">Tanggal</label>
      <div class="col-sm-10">
        <input type="datetime-local" class="form-control form-control-sm" id="tanggal" name="tanggal" required>
        <div class="invalid-feedback">
          Harap pilih tanggal.
        </div>
      </div>
    </div>

    <div class="row mb-3">
      <label for="no_penawaran" class="col-sm-2 col-form-label">No:</label>
      <div class="col-sm-10">
        <input type="text" class="form-control form-control-sm" id="no_penawaran" name="no_penawaran" readonly required>
        <div class="invalid-feedback">
          Sistem error, nomor penawaran gagal dimuat.
        </div>
      </div>
    </div>

    <div class="row mb-3">
      <label for="penerima" class="col-sm-2 col-form-label">Penerima</label>
      <div class="col-sm-10">
        <select class="form-select form-select-sm" id="penerima" name="penerima" required>
          <option value="" selected disabled>-- Pilih Penerima --</option>
          <?php
          $pelanggan = selectData("pelanggan");
          foreach ($pelanggan as $row_penerima) {
            echo '<option value="' . $row_penerima['id_pelanggan'] . '">' . ucwords($row_penerima['nama_pelanggan']) . '</option>';
          }
          ?>
        </select>
        <div class="invalid-feedback">
          Harap pilih penerima.
        </div>
      </div>
    </div>

    <div class="row mb-3">
      <label for="up" class="col-sm-2 col-form-label">U.P.</label>
      <div class="col-sm-10">
        <input type="text" class="form-control form-control-sm" id="up" name="up" value="-" required>
        <div class="invalid-feedback">
          Harap masukan U.P. dengan valid.
        </div>
      </div>
    </div>

    <div class="row mb-3">
      <label for="diskon" class="col-sm-2 col-form-label">Diskon</label>
      <div class="col-sm-10">
        <input type="number" class="form-control form-control-sm" id="diskon" name="diskon" min="0" step="0.01"
          value="0" required>
        <div class="invalid-feedback">
          Harap masukan diskon dengan valid.
        </div>
      </div>
    </div>

    <div class="row mb-3">
      <label for="jenis_ppn" class="col-sm-2 col-form-label">PPN</label>
      <div class="col-sm-10">
        <select class="form-select form-select-sm" id="jenis_ppn" name="jenis_ppn" required>
          <option value="" selected disabled>-- Pilih Jenis PPN --</option>
          <?php
          $ppn = selectData("ppn");
          foreach ($ppn as $row_ppn) {
              echo '<option value="' . $row_ppn['id_ppn'] . '">' . $row_ppn['jenis_ppn'] . '</option>';
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
        <textarea class="form-control form-control-sm" id="catatan" name="catatan" row="1"></textarea>
      </div>
    </div>

    <!-- Tambah detail produk -->
    <table>
      <thead>
        <tr>
          <td>No</td>
          <td>Nama Produk</td>
          <td>Qty</td>
          <td>Harga Satuan</td>
          <td>BTN</td>
        </tr>
      </thead>
      <tbody id="detail-table">
        <tr class="main-tr">
          <td>1</td>
          <td>
            <select class="form-select form-select-sm" id="id_produk" name="id_produk[]" required>
              <option value="" selected disabled>-- Pilih Produk --</option>
              <?php
          $produk = selectData("produk");
          foreach ($produk as $row_produk) {
              echo '<option value="' . $row_produk['id_produk'] . '">' . $row_produk['nama_produk'] . '</option>';
          }
          ?>
            </select>
          </td>
          <td>
            <input type="number" name="jumlah[]" class="form-control" required>
          </td>
          <td>
            <input type="number" name="harga_satuan[]" class="form-control" required>
          </td>
          <td>
            <button type="button" class="remove-btn btn btn-danger">Remove</button>
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Tombol ADD MORE di luar tabel -->
    <button type="button" class="add-more-tr btn btn-primary">ADD MORE</button>

    <!-- Tombol Submit -->
    <button type="submit" class="btn btn-primary btn-sm" name="add">Simpan</button>
  </form>
</div>

<script>
$(document).ready(function() {
  // Event untuk menghapus baris
  $(document).on('click', '.remove-btn', function() {
    $(this).closest('.main-tr').remove();
  });

  // Event untuk menambahkan baris baru
  $(document).on('click', '.add-more-tr', function() {
    var produkOptions = '<?php
      $produk = selectData("produk");
      foreach ($produk as $row_produk) {
          echo '<option value="' . $row_produk['id_produk'] . '">' . $row_produk['nama_produk'] . '</option>';
      }
      ?>';

    $('#detail-table').append(
      `<tr class="main-tr">
          <td>1</td>
          <td>
            <select class="form-select form-select-sm" id="id_produk" name="id_produk[]" required>
              <option value="" selected disabled>-- Pilih Produk --</option>
              ${produkOptions}
            </select>
          </td>
          <td>
            <input type="number" name="jumlah[]" class="form-control" required>
          </td>
          <td>
            <input type="number" name="harga_satuan[]" class="form-control" required>
          </td>
          <td>
            <button type="button" class="remove-btn btn btn-danger">Remove</button>
          </td>
        </tr>`
    );
  });
});

document.getElementById("tanggal").addEventListener("change", function() {
  var tanggalInput = document.getElementById("tanggal").value;
  if (tanggalInput) {
    var date = new Date(tanggalInput);
    var month = date.getMonth() + 1;
    var year = date.getFullYear();

    // Format bulan menjadi dua digit jika nilainya kurang dari 10
    if (month < 10) {
      month = '0' + month;
    }

    // Request nomor penawaran berdasarkan bulan dan tahun ke getDocumentNumber.php
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById("no_penawaran").value = this.responseText;
      }
    };
    xhttp.open("GET", "getDocumentNumber.php?month=" + month + "&year=" + year, true);
    xhttp.send();
  } else {
    document.getElementById("no_penawaran").value =
      ""; // Kosongkan nilai nomor penawaran jika tanggal tidak diisi
  }
});

// Fungsi untuk menampilkan preview gambar saat memilih file
function previewAddImage(event) {
  let input = event.target;
  if (input.files && input.files[0]) {
    let reader = new FileReader();
    reader.onload = function(e) {
      let imgElement = document.getElementById("logo-preview");
      let placeholderContainer = document.getElementById("placeholder-container");
      let imagePreviewContainer = document.getElementById("image-preview-container");

      imgElement.src = e.target.result;
      imgElement.style.display = "block"; // Tampilkan preview gambar

      // Sembunyikan placeholder dan tampilkan preview gambar container
      placeholderContainer.style.display = "none";
      imagePreviewContainer.style.display = "block";
    };
    reader.readAsDataURL(input.files[0]);
  }
}
// Panggil fungsi previewAddImage saat pengguna memilih file
document.getElementById('logo').addEventListener('change', previewAddImage);
$(function() {
  $("#btnAdd").on("click", function() {
    var tr = $("<tr />");
    tr.html(GetDynamicTextBox(""));
    $("#TextBoxContainer").append(tr);
  });
  $("body").on("click", ".remove", function() {
    $(this).closest("tr").remove();
  });
});

// Aktifkan validasi Bootstrap pada formulir
$('form.needs-validation').on('submit', function(e) {
  if (!this.checkValidity()) {
    e.preventDefault();
    e.stopPropagation();
    // Tambahkan kode untuk menampilkan pesan kesalahan kepada pengguna
    $('#submit-error-message').text('Mohon lengkapi semua field yang diperlukan.');
  } else {
    $('#submit-error-message').text(''); // Kosongkan pesan kesalahan jika validasi berhasil
    // Lakukan tindakan yang diperlukan jika formulir valid
  }
  $(this).addClass('was-validated');
});
</script>

<?php
require '../../includes/footer.php';
?>