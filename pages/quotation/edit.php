<?php
// Ambil nilai kategori dari parameter URL
$category_param = isset($_GET['category']) ? $_GET['category'] : '';

// Atur judul halaman berdasarkan kategori
$page_title = $category_param === 'outgoing' ? 'Edit Quotation Outgoing' : 'Edit Quotation Incoming';

require '../../includes/header.php';

// Notifikasi
if (isset($_SESSION['success_message'])) {
  echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
          ' . $_SESSION['success_message'] . '
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
  unset($_SESSION['success_message']);
}
if (isset($_SESSION['error_message'])) {
  echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
          ' . $_SESSION['error_message'] . '
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
  unset($_SESSION['error_message']);
}

// Validasi nilai kategori dan atur nilai deskriptif
if ($category_param === 'outgoing') {
  $category = 'keluar';
  $sender = 'internal';
  $receiver = 'customer';
} elseif ($category_param === 'incoming') {
  $category = 'masuk';
  $sender = 'customer';
  $receiver = 'internal';
} else {
  die("Kategori tidak valid");
}

// Variabel data detail
$data_detail = [];
$signatureInfo = []; // Array detail signature info
$error_message = '';

// Inisialisasi nilai defaultLogoPath dan defaultSignaturePath
$defaultLogoPath = "";
$defaultSignaturePath = "";

// Ambil Data Penawaran Harga berdasarkan id
if (isset($_GET['id']) && $_GET['id'] !== '') {
  $id_penawaran = $_GET['id'];
  $mainTable = 'penawaran_harga';
  $joinTables = [
    ["kontak pengirim", "penawaran_harga.id_pengirim = pengirim.id_kontak AND pengirim.kategori = '$sender'"], 
    ["kontak penerima", "penawaran_harga.id_penerima = penerima.id_kontak AND penerima.kategori = '$receiver'"],
    ['ppn', 'penawaran_harga.id_ppn = ppn.id_ppn']
  ];

  // Kolom-kolom yang ingin diambil dari tabel utama dan tabel-tabel yang di-join
  $columns = 'penawaran_harga.*, pengirim.id_kontak AS id_pengirim, pengirim.nama_kontak AS nama_pengirim, penerima.nama_kontak AS nama_penerima, penerima.id_kontak AS id_penerima, ppn.jenis_ppn';
  $conditions = "penawaran_harga.id_penawaran = '$id_penawaran'";
  
  // Panggil fungsi selectDataJoin dengan ORDER BY
  $data = selectDataJoin($mainTable, $joinTables, $columns, $conditions);
  
  // Cek apakah data ditemukan
  if (!empty($data)) {
    $data = $data[0];
    $defaultLogoPath = $data["logo"];
    if (!empty($data["signature_info"])) {
      // Pisahkan data signature_info berdasarkan koma (,) untuk mendapatkan setiap elemen
      $signature_info_parts = explode(", ", $data["signature_info"]);
      // Loop melalui setiap elemen untuk menyimpan pasangan kunci dan nilai
      foreach ($signature_info_parts as $part) {
        // Pecah setiap elemen menjadi pasangan kunci dan nilai
        $pair = explode(": ", $part);
        // Simpan pasangan kunci dan nilai dalam array asosiatif
        if (count($pair) == 2) {
          $signatureInfo[$pair[0]] = $pair[1];
        }
      }
      $defaultSignaturePath = $signatureInfo["Path"];
    }
    // Jika data penawaran harga ditemukan lanjut mengambil detail penawaran berdasarkan id
    $mainDetailTable = 'detail_penawaran';
    $joinDetailTables = [
        ['penawaran_harga', 'detail_penawaran.id_penawaran = penawaran_harga.id_penawaran'], 
        ['produk', 'detail_penawaran.id_produk = produk.id_produk']
    ];
    $columns = 'detail_penawaran.*, produk.*';
    $conditions = "detail_penawaran.id_penawaran = '$id_penawaran'";
    // Panggil fungsi selectDataJoin dengan ORDER BY
    $data_detail = selectDataJoin($mainDetailTable, $joinDetailTables, $columns, $conditions);
  } else {
      echo "Penawaran tidak ditemukan.";
  }
} else {
echo "ID penawaran tidak ditemukan.";
}
if ($error_message): ?>
<p><?php echo $error_message; ?></p>
<?php else: ?>
<?php if (!empty($data)): ?>
<h1 class="fs-5 mb-4">Ubah Penawaran Harga</h1>
<div class="paper-wrapper">
  <form action="process.php" method="POST" class="needs-validation" enctype="multipart/form-data" novalidate>
    <div class="container">
      <input type="hidden" name="kategori" value="<?= htmlspecialchars($category) ?>">
      <input type="hidden" name="id_penawaran" value="<?= $id_penawaran ?>">
      <!-- Input Hidden untuk Mengirim Path Default Logo -->
      <input type="hidden" id="defaultLogoPath" name="defaultLogoPath" value="<?= $defaultLogoPath ?>">
      <div class="row">
        <!-- Input Hidden untuk Mengirim Path Default Signature -->
        <input type="hidden" id="defaultSignaturePath" name="defaultSignaturePath" value="<?= $defaultSignaturePath ?>">
        <div class="row">
          <!-- Input Logo Dokumen Outgoing-->
          <?php if ($category_param === 'outgoing') {?>
          <div class="col-md-6 p-0 position-relative">
            <div id="image-preview-container" class="position-relative">
              <div class="d-flex flex-column justify-content-center align-items-center h-100">
                <img id="logo-preview" src="" alt="Preview Logo">
              </div>
              <button type="button" title="Hapus Logo" class="position-absolute top-0 end-0 z-3" id="cancelButton"
                onclick="resetImage()"></button>
              <span class="position-absolute top-0 start-0" id="changeImage">Ubah
                Logo</span>
            </div>
            <div id="placeholder-container">
              <div class="d-flex flex-column justify-content-center align-items-center h-100">
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px"
                  fill="#0077b6">
                  <path
                    d="M440-320v-326L336-542l-56-58 200-200 200 200-56 58-104-104v326h-80ZM240-160q-33 0-56.5-23.5T160-240v-120h80v120h480v-120h80v120q0 33-23.5 56.5T720-160H240Z" />
                </svg>
                <p class="fw-medium m-1">Unggah Logo</p>
                <p class="text-center">Maksimal ukuran 20MB JPEG, PNG. Rekomendasi ukuran 300x300</p>
              </div>
            </div>
            <div class="position-absolute top-0 start-0 p-0">
              <input type="file" title="" class="form-control form-control-sm" id="logo" name="logo" accept="image/*"
                onchange="previewAddImage(event)">
            </div>
            <button type="button" id="refreshButton" onclick="refreshImage()" class="mt-2"
              title="Batalkan Perubahan Logo"></button>
          </div>
          <!-- Judul Dokumen -->
          <div class="col-md-6 p-0">
            <p class="fs-2 text-end">Penawaran Harga</p>
          </div>
          <?php } else { ?>
          <p class="fs-2 p-0">Penawaran Harga Incoming</p>
          <?php } ?>
        </div>
        <div class="row justify-content-between align-items-end">
          <!-- Input Pengirim -->
          <div class="col-md-5 p-0">
            <div class="row mb-3">
              <label for="pengirim" class="col-sm-3 col-form-label">Pengirim</label>
              <div class="col-sm-9">
                <select class="form-select form-select-sm" id="pengirim" name="pengirim" required>
                  <?php
                    // Ambil data kontak sesuai dengan kategori sender
                    $kontak_pengirim = selectData("kontak", "kategori = '$sender'");
                    
                    foreach ($kontak_pengirim as $row_pengirim) {
                        $selected = ""; // Variabel untuk menentukan apakah opsi saat ini harus dipilih

                        // Tentukan pengirim mana yang akan menjadi default berdasarkan kategori dan ID pengirim saat ini
                        if ($row_pengirim['id_kontak'] == $data['id_pengirim']) {
                            $selected = "selected";
                        }
                        echo '<option value="' . $row_pengirim['id_kontak'] . '" ' . $selected . '>' . ucwords($row_pengirim['nama_kontak']) . '</option>';
                    }
                  ?>
                </select>
                <div class="invalid-feedback">
                  Harap pilih pengirim.
                </div>
              </div>
            </div>
          </div>
          <!-- Info Dokumen -->
          <div class="col-md-5 p-0">
            <div class="row mb-3">
              <label for="no_penawaran" class="col-sm-3 col-form-label">No:</label>
              <div class="col-sm-9">
                <input type="text" class="form-control form-control-sm" id="no_penawaran" name="no_penawaran"
                  value="<?= $data['no_penawaran'] ?>" readonly required>
                <div class="invalid-feedback">
                  Sistem error, nomor penawaran gagal dimuat.
                </div>
              </div>
            </div>
            <div class="row mb-3">
              <label for="tanggal" class="col-sm-3 col-form-label">Tanggal</label>
              <div class="col-sm-9">
                <input type="datetime-local" class="form-control form-control-sm" id="tanggal" name="tanggal"
                  value="<?= $data['tanggal'] ?>" required>
                <div class="invalid-feedback">
                  Harap pilih tanggal.
                </div>
              </div>
            </div>
          </div>
        </div>
        <hr class="row mb-5 border border-secondary border-1 opacity-25">
        <div class="row">
          <div class="col-md-5 p-0">
            <div class="row mb-3">
              <label for="penerima" class="col-sm-3 col-form-label">Penerima</label>
              <div class="col-sm-9">
                <?php if ($category_param == 'incoming') {
                  // Ambil data penerima yang merupakan internal
                  $kontak_penerima = selectData("kontak", "kategori = '$receiver' AND nama_kontak = 'pt. mitra tehno gemilang'");
        
                  // Periksa apakah ada hasil dari query
                  if (!empty($kontak_penerima)) {
                    // Jika ada hasil, ambil ID pelanggan pertama dari hasil query
                    $id_penerima_internal = $kontak_penerima[0]['id_kontak'];
                  } else {
                    // Jika tidak ada hasil, atur ID pelanggan menjadi kosong atau sesuai kebutuhan
                    $id_penerima_internal = "";
                  } 
                ?>
                <!-- Jika kategori adalah 'incoming', gunakan input tersembunyi untuk menyimpan ID pelanggan -->
                <input type="hidden" id="penerima" name="penerima" value="<?= $id_penerima_internal ?>">
                <input type="text" class="form-control form-control-sm" value="PT. Mitra Tehno Gemilang" readonly>

                <?php } elseif ($category_param == 'outgoing') { ?>
                <!-- Jika kategori adalah 'outgoing', tampilkan dropdown untuk ubah pelanggan -->
                <select class="form-select form-select-sm" id="penerima" name="penerima" required>
                  <option value="" selected disabled>-- Pilih Penerima --</option>
                  <?php
                    $kontak_penerima = selectData("kontak", "kategori = '$receiver'");
                    foreach ($kontak_penerima as $row_penerima) {
                      $selected = ($row_penerima['id_kontak'] == $data['id_penerima']) ? "selected" : "";
                      echo '<option value="' . $row_penerima['id_kontak'] . '" ' . $selected . '>' . ucwords($row_penerima['nama_penerima']) . '</option>';
                    }
                  ?>
                </select>
                <div class="invalid-feedback">
                  Harap pilih penerima.
                </div>
                <?php } ?>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-5 p-0">
            <div class="row mb-3">
              <label for="up" class="col-sm-3 col-form-label">U.P.</label>
              <div class="col-sm-9">
                <input type="text" class="form-control form-control-sm" id="up" name="up" value="<?= $data['up'] ?>"
                  required>
                <div class="invalid-feedback">
                  Harap masukan U.P. dengan valid.
                </div>
              </div>
            </div>
          </div>
        </div>
        <hr class="row mb-5 border border-secondary border-1 opacity-25">
        <div class="row">
          <!-- Tambah, Hapus, Edit detail produk -->
          <table class="table table-light table-striped">
            <thead>
              <tr class="fw-bolder">
                <td>No</td>
                <td>Nama Produk</td>
                <td>Kuantitas</td>
                <td>Harga (Rp)</td>
                <td colspan="2">Jumlah</td>
              </tr>
            </thead>
            <tbody id="detail-table">
              <?php
            $subtotal = 0;
            if (!empty($data_detail)): ?>
              <?php $no = 1; ?>
              <?php foreach ($data_detail as $detail): ?>
              <?php
              // Hitung total harga untuk setiap baris
              $total_harga = $detail['jumlah'] * $detail['harga_satuan'];
              // // Tambahkan total harga ke subtotal
              // $subtotal += $total_harga;
            ?>
              <tr class="main-tr">
                <td><?= $no ?></td>
                <input type="hidden" name="id_detail_penawaran[]" value="<?= $detail['id_detail_penawaran'] ?>">
                <td>
                  <select class="form-select form-select-sm" name="id_produk[]" required>
                    <?php
                    $produk = selectData("produk");
                    foreach ($produk as $row_produk) {
                        $selected = ($row_produk['id_produk'] == $detail['id_produk']) ? "selected" : "";
                        echo '<option value="' . $row_produk['id_produk'] . '" ' . $selected . '>' . ucwords($row_produk['nama_produk']) . '</option>';
                    }
                  ?>
                  </select>
                </td>
                <td><input type="number" name="jumlah[]" class="form-control form-control-sm qty" min="1"
                    value="<?= $detail['jumlah'] ?>" required></td>
                <td><input type="text" name="harga_satuan[]" class="form-control form-control-sm price" min="0"
                    value="<?= $detail['harga_satuan'] ?>" required></td>
                <td class="total"><?= $total_harga ?></td>
                <td class="align-middle text-center"><button type="button" class="remove-btn btn-cancel m-0"></button>
                </td>
              </tr>
              <?php $no++ ?>
              <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
            <tfoot>
              <tr>
                <td colspan="2" rowspan="2" style="background-color: transparent;">
                  <button type="button" class="add-more-tr btn btn-primary btn-lg btn-icon btn-add mt-3">Tambah
                    Baris</button>
                </td>
                <td colspan="2">Subtotal</td>
                <td colspan="2" id="total-harga">0</td>
              </tr>
              <tr>
                <td>Diskon</td>
                <td>
                  <div class="input-group input-group-sm">
                    <input type="number" class="form-control" id="diskon" name="diskon" min="0" max="99"
                      value="<?= $data['diskon'] ?>" aria-describedby="basic-addon1">
                    <span class="input-group-text" id="basic-addon1">%</span>
                  </div>
                </td>
                <td colspan="2" id="nilai-diskon">0</td>
              </tr>
              <tr>
                <td colspan="2" style="background-color: transparent;"></td>
                <td>PPN</td>
                <td>
                  <div class="input-group input-group-sm">
                    <select class="form-select form-select-sm" id="jenis_ppn" name="jenis_ppn" required
                      aria-describedby="tarif-ppn">
                      <option value="" selected disabled>-- Pilih PPN --</option>
                      <?php
                      $ppn = selectData("ppn");
                      foreach ($ppn as $row_ppn) {
                        $selected = ($row_ppn['id_ppn'] == $data['id_ppn']) ? "selected" : "";
                          echo '<option value="' . $row_ppn['id_ppn'] . '" ' . $selected . '>' . ucwords($row_ppn['jenis_ppn']) . '</option>';
                      }
                    ?>
                    </select>
                    <label class="input-group-text" for="jenis_ppn">%<span class="d-none" id="tarif-ppn"></span></label>
                  </div>
                </td>
                <td colspan="2" id="total-ppn">0</td>
              </tr>
              <tr>
                <td colspan="2" style="background-color: transparent;"></td>
                <td colspan="2">Total</td>
                <td colspan="2">
                  <span id="grand-total">0</span>
                  <!-- Input tersembunyi untuk menyimpan grand total -->
                  <input type="hidden" id="hidden-grand-total" name="grand_total">
                </td>
              </tr>
            </tfoot>
          </table>

          <!-- Input tersembunyi untuk menyimpan ID detail yang dihapus -->
          <div id="deleted-rows"></div>
        </div>
        <hr class="row mb-5 border border-secondary border-1 opacity-25">
        <div class="row justify-content-between">
          <div class="col-md-5 p-0 mb-3">
            <div class="form-floating">
              <textarea class="form-control" id="catatan" name="catatan"
                style="height: 100px"><?= ucfirst($data['catatan']) ?></textarea>
              <label for="catatan" name="catatan">Catatan</label>
            </div>
          </div>
          <div class="col-md-5">
            <!-- Input Tempat dan Tanggal Signature -->
            <div class="row mb-3">
              <label class="mb-3">Tempat dan tanggal.</label>
              <div class="input-group input-group-sm p-0">
                <input type="text" class="form-control" name="signing_location"
                  value="<?= isset($signatureInfo['Location']) ? ucwords($signatureInfo['Location']) : '' ?>" required>
                <span class="input-group-text">,</span>
                <input type="date" class="form-control" name="signing_date"
                  value="<?= isset($signatureInfo['Date']) ? $signatureInfo['Date'] : '' ?>" required>
              </div>
            </div>
            <!-- Input Gambar Signature -->
            <div class="row justify-content-center mb-3">
              <div class="col-md-6 p-0 position-relative">
                <div id="signature-preview-container" class="position-relative">
                  <div class="d-flex flex-column justify-content-center align-items-center h-100">
                    <img id="signature-preview" src="" alt="Preview Signature.">
                  </div>
                  <button type="button" title="Hapus Tanda Tangan" class="position-absolute top-0 end-0 z-3"
                    id="cancelButton2" onclick="resetSignature()"></button>
                  <span class="position-absolute top-0 start-0" id="changeSignature">Ubah Tanda Tangan.</span>
                </div>
                <div id="signature-placeholder-container">
                  <div class="d-flex flex-column justify-content-center align-items-center h-100">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px"
                      fill="#0077b6">
                      <path
                        d="M440-320v-326L336-542l-56-58 200-200 200 200-56 58-104-104v326h-80ZM240-160q-33 0-56.5-23.5T160-240v-120h80v120h480v-120h80v120q0 33-23.5 56.5T720-160H240Z" />
                    </svg>
                    <p class="fw-medium m-1">Unggah Tanda Tangan</p>
                    <p class="text-center">Maksimal ukuran 20MB JPEG, PNG. Rekomendasi ukuran 300x300</p>
                  </div>
                </div>
                <div class="position-absolute top-0 start-0 p-0">
                  <input type="file" title="" class="form-control form-control-sm" id="signature" name="signature"
                    accept="image/*" onchange="previewAddSignature(event)">
                </div>
                <button type="button" id="refreshButton2" onclick="refreshSignature()" class="mt-2"
                  title="Batalkan Perubahan Tanda Tangan"></button>
              </div>
              <!--  -->
            </div>
            <div class="row mb-3">
              <input type="text" class="form-control form-control-sm" id="signer-name" name="signer_name"
                value="<?= isset($signatureInfo['Name']) ? ucwords($signatureInfo['Name']) : '' ?>" required>
            </div>
            <div class="row mb-3">
              <input type="text" class="form-control form-control-sm" id="signer-position" name="signer_position"
                value="<?= isset($signatureInfo['Position']) ? ucwords($signatureInfo['Position']) : '' ?>" required>
            </div>
          </div>
        </div>
        <div class="row justify-content-end mt-4 mb-4">
          <div class="col-auto">
            <button type="submit" class="btn btn-primary btn-lg" name="edit">Simpan</button>
          </div>
          <div class="col-auto">
            <a href="index.php?category=<?= $category_param ?>">
              <button type="button" class="btn btn-secondary btn-lg">Batal</button>
            </a>
          </div>
        </div>
      </div>
  </form>
</div>
<?php endif; ?>
<?php endif; ?>
<script>
// Data Detail
$(document).ready(function() {
  $(document).on('input', '.price', function() {
    // Ambil nilai input
    var input = $(this).val();

    // Hapus semua karakter kecuali angka
    var number = input.replace(/\D/g, '');

    // Periksa apakah input hanya 'Rp', 'Rp ' dengan spasi, diikuti '0', diawali '0', atau tidak mengandung angka
    if (/^Rp\s?0*$/.test(input.trim()) || /^[^\d]+$/.test(number) || /^0/.test(number)) {
      // Jika kondisi terpenuhi, kosongkan input
      $(this).val('');
    } else {
      // Format angka dengan Rp dan pemisah ribuan
      var formatted = formatRupiah(number);

      // Perbarui nilai input dengan format yang diformat
      $(this).val(formatted);
    }
  });


  // Event listener untuk keypress
  $(document).on('keypress', '.price', function(event) {
    // Hanya izinkan angka (kode ASCII 48-57)
    var charCode = (event.which) ? event.which : event.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
      event.preventDefault();
    }
  });

  // Fungsi untuk format angka dengan Rp dan pemisah ribuan
  function formatRupiah(angka) {
    var reverse = angka.toString().split('').reverse().join('');
    var ribuan = reverse.match(/\d{1,3}/g);
    var formatted = ribuan.join('.').split('').reverse().join('');
    return 'Rp ' + formatted;
  }

  // Fungsi untuk mengembalikan nilai harga yang telah diunformat ke dalam bentuk angka
  function unformatRupiah(price) {
    // Hapus semua karakter kecuali angka
    return parseFloat(price.replace(/\D/g, ''));
  }

  // Panggil updateTotal() dan updateGrandTotal() saat halaman dimuat
  updateTotal();
  setPPNTarif(); // Memastikan tarif PPN diatur saat halaman dimuat
  updateGrandTotal();

  // Format harga satuan saat halaman dimuat
  $('.price').each(function() {
    var value = $(this).val();
    $(this).val(formatRupiah(value));
  });

  // Format total saat halaman dimuat
  $('.total').each(function() {
    var value = $(this).text();
    $(this).text(formatRupiah(value));
  });

  // Format subtotal saat halaman dimuat
  $('#total-harga').each(function() {
    var value = $(this).text();
    $(this).text(formatRupiah(value));
  });

  // Format nilai diskon saat halaman dimuat
  $('#nilai-diskon').each(function() {
    var value = $(this).text();
    $(this).text(formatRupiah(value));
  });

  // Format nilai ppn saat halaman dimuat
  $('#total-ppn').each(function() {
    var value = $(this).text();
    $(this).text(formatRupiah(value));
  });

  // Format nilai ppn saat halaman dimuat
  $('#grand-total').each(function() {
    var value = $(this).text();
    $(this).text(formatRupiah(value));
  });


  // Event untuk menghapus baris
  $(document).on('click', '.remove-btn', function() {
    var idDetailPenawaran = $(this).closest('.main-tr').find('input[name="id_detail_penawaran[]"]').val();
    $(this).closest('.main-tr').remove();
    updateRowNumbers(); // Panggil fungsi untuk memperbarui nomor urutan setelah menghapus baris
    updateTotal(); // Panggil fungsi untuk memperbarui total setelah menghapus baris
    updateGrandTotal(); // Panggil fungsi untuk memperbarui grand total setelah menghapus baris
    // Tambahkan idDetailPenawaran ke #deleted-rows dan cetak konten #deleted-rows ke konsol
    $('#deleted-rows').append('<input type="hidden" name="deleted_rows[]" value="' + idDetailPenawaran + '">');
  });

  // Event untuk menambahkan baris baru
  $(document).on('click', '.add-more-tr', function() {
    var produkOptions = '<?php
      $produk = selectData("produk");
      foreach ($produk as $row_produk) {
          echo '<option value="' . $row_produk['id_produk'] . '">' . $row_produk['nama_produk'] . '</option>';
      }
      ?>';
    // Ambil jumlah baris saat ini dan tambahkan 1,
    var rowCount = $('#detail-table tr.main-tr').length + 1;
    var newIdDetail = "newId" + (rowCount - 1);
    $('#detail-table').append(
      `<tr class="main-tr">
          <td>${rowCount}</td>
          <input type="hidden" name="id_detail_penawaran[]" value="${newIdDetail}">
          <td>
            <select class="form-select form-select-sm" id="id_produk" name="id_produk[]" required>
              <option value="" selected disabled>-- Pilih Produk --</option>
              ${produkOptions}
            </select>
          </td>
            <td><input type="number" name="jumlah[]" class="form-control form-control-sm qty" min="1" required></td>
            <td><input type="text" name="harga_satuan[]" class="form-control form-control-sm price" min="0" required></td>
            <td class="total">0</td>
            <td class="align-middle text-center"><button type="button" class="remove-btn btn-cancel"></button></td>
        </tr>`
    );
    updateRowNumbers();
    updateTotal();
    updateGrandTotal();
  });

  // Fungsi untuk memperbarui nomor urutan pada setiap baris
  function updateRowNumbers() {
    $('#detail-table tr.main-tr').each(function(index) {
      $(this).find('td:first').text(index + 1); // Atur nomor urutan sesuai dengan indeks baris
    });
  }

  // Event listener untuk menghitung total saat nilai "Qty" atau "Harga Satuan" berubah
  $(document).on('input', '.qty, .price', function() {
    var row = $(this).closest('.main-tr'); // Temukan baris terdekat
    var idDetailPenawaran = row.find('input[name="id_detail_penawaran[]"]').val(); // Dapatkan ID detail
    var idProduk = row.find('select[name="id_produk[]"]').val(); // Dapatkan ID produk
    var jumlah = row.find('.qty').val(); // Dapatkan jumlah
    var hargaSatuan = unformatRupiah(row.find('.price').val()); // Dapatkan harga satuan tanpa format Rupiah

    updateTotal(); // Panggil fungsi untuk memperbarui total
    updateGrandTotal(); // Panggil fungsi untuk memperbarui grand total
  });

  // Event listener untuk menghitung grand total saat nilai diskon berubah
  $('#diskon').on('input', function() {
    updateGrandTotal(); // Panggil fungsi untuk memperbarui grand total
  });

  // Event listener untuk mengubah tarif PPN saat pemilihan jenis PPN berubah
  $('#jenis_ppn').change(function() {
    var selectedOption = $(this).val();
    var tarifPPN = getTarifPPN(
      selectedOption); // Fungsi ini akan mengambil tarif PPN sesuai dengan pilihan jenis PPN yang dipilih
    $('#tarif-ppn').text(tarifPPN); // Menampilkan tarif PPN di kolom tarif PPN
    updateGrandTotal(); // Panggil fungsi untuk memperbarui grand total
  });

  // Fungsi untuk mengambil tarif PPN berdasarkan id_ppn
  function getTarifPPN(id_ppn) {
    var tarif = ''; // Inisialisasi variabel tarif
    <?php
      $ppn = selectData("ppn");
      foreach ($ppn as $row_ppn) {
          echo "if (id_ppn === '{$row_ppn['id_ppn']}') {
                  tarif = '{$row_ppn['tarif']}';
              }";
      }
    ?>
    return tarif; // Mengembalikan tarif PPN
  }

  // Fungsi untuk memastikan tarif PPN diatur saat halaman dimuat
  function setPPNTarif() {
    var selectedOption = $('#jenis_ppn').val();
    if (selectedOption) {
      var tarifPPN = getTarifPPN(selectedOption);
      $('#tarif-ppn').text(tarifPPN);
    }
  }

  // Fungsi untuk memperbarui total harga
  function updateTotal() {
    var totalHarga = 0;
    $('#detail-table tr.main-tr').each(function() {
      var qty = parseFloat($(this).find('.qty').val()) || 0;
      var price = parseFloat(unformatRupiah($(this).find('.price').val())) || 0;
      var total = qty * price;
      $(this).find('.total').text(formatRupiah(total)); // Atur teks pada elemen <td class="total">
      totalHarga += total; // Tambahkan total baris ke total harga
    });
    $('#total-harga').text(formatRupiah(totalHarga)); // Atur teks total harga pada elemen dengan id "total-harga"
  }

  // Fungsi untuk memperbarui grand total
  function updateGrandTotal() {
    var totalHarga = unformatRupiah($('#total-harga').text()) || 0;
    var diskonPersen = unformatRupiah($('#diskon').val()) || 0;
    var diskon = (totalHarga * diskonPersen) / 100; // Hitung nilai diskon dari persentase
    var tarifPPN = parseFloat($('#tarif-ppn').text()) || 0;
    var totalPPN = Math.round((totalHarga - diskon) * (tarifPPN / 100)); // Hitung dan bulatkan total PPN
    var grandTotal = totalHarga - diskon + totalPPN; // Perhatikan pengurangan diskon dan penambahan total PPN

    // Memperbarui teks grand total pada elemen dengan id "grand-total"
    $('#grand-total').text(formatRupiah(grandTotal));

    // Memperbarui nilai input tersembunyi dengan grand total
    $('#hidden-grand-total').val(grandTotal);

    // Memperbarui teks nilai diskon pada elemen dengan id "nilai-diskon"
    $('#nilai-diskon').text(formatRupiah(diskon));

    // Memperbarui teks nilai total PPN pada elemen dengan id "total-ppn"
    $('#total-ppn').text(formatRupiah(totalPPN));
  }
});

// Tampil Nomor Dokumen
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
        // Ubah nomor penawaran menjadi huruf kapital
        var nomorPenawaran = this.responseText.toUpperCase();
        document.getElementById("no_penawaran").value = nomorPenawaran;
      }
    };
    xhttp.open("GET", "getDocumentNumber.php?month=" + month + "&year=" + year, true);
    xhttp.send();
  } else {
    document.getElementById("no_penawaran").value =
      ""; // Kosongkan nilai nomor penawaran jika tanggal tidak diisi
  }
});

// Preview logo dan ubah logo
// Panggil fungsi previewAddImage saat halaman dimuat pertama kali
document.addEventListener('DOMContentLoaded', function() {
  // Ubah panggilan fungsi previewAddImage untuk menyertakan defaultLogoPath
  var defaultLogoPath = "<?= $defaultLogoPath; ?>"; // Ubah menjadi nilai yang sesuai dari PHP
  previewAddImage(defaultLogoPath);
});

// Panggil fungsi previewAddImage saat pengguna memilih file
document.getElementById('logo').addEventListener('change', function(event) {
  // Ambil file yang dipilih oleh pengguna
  var selectedFile = event.target.files[0];

  // Cek apakah pengguna telah memilih file baru
  if (selectedFile) {
    // Buat URL untuk file yang dipilih
    var fileURL = URL.createObjectURL(selectedFile);
    // Panggil fungsi previewAddImage dengan menggunakan file yang dipilih
    previewAddImage(fileURL);
  }
});

// Fungsi untuk menampilkan preview gambar
function previewAddImage(imageURL) {
  let imgElement = document.getElementById("logo-preview");
  let placeholderContainer = document.getElementById("placeholder-container");
  let imagePreviewContainer = document.getElementById("image-preview-container");
  let cancelButton = document.getElementById("cancelButton");

  if (imageURL) {
    // Tampilkan gambar
    imgElement.src = imageURL;
    imgElement.style.display = "block";
    // Sembunyikan placeholder dan tampilkan container gambar
    placeholderContainer.style.display = "none";
    imagePreviewContainer.style.display = "block";
    // Tampilkan tombol "Batal"
    cancelButton.style.display = "block";
  } else {
    // Jika tidak ada gambar yang dipilih, gunakan gambar default
    var defaultLogoPath = "<?php echo $defaultLogoPath; ?>";
    imgElement.src = defaultLogoPath;
    imgElement.style.display = "block";
    // Sembunyikan placeholder dan tampilkan container gambar
    placeholderContainer.style.display = "none";
    imagePreviewContainer.style.display = "block";
    // Tampilkan tombol "Batal"
    cancelButton.style.display = "block";
  }
}

// Fungsi untuk menghapus gambar
function resetImage() {
  var logoInput = document.getElementById('logo');
  logoInput.value = ''; // Menghapus nilai input
  document.getElementById('image-preview-container').style.display = 'none'; // Menyembunyikan kontainer preview gambar
  document.getElementById('placeholder-container').style.display = 'block'; // Menampilkan kontainer placeholder
  document.getElementById('cancelButton').style.display = 'none'; // Menyembunyikan tombol "Batal"
}
// Fungsi refresh gambar
function refreshImage() {
  var defaultLogoPath = "<?= $defaultLogoPath; ?>";
  previewAddImage(defaultLogoPath);
}

document.addEventListener('DOMContentLoaded', function() {
  // Panggil fungsi untuk mengatur visibilitas tombol "Change Image" secara awal
  toggleChangeImageButton(false);

  // Tambahkan event listener untuk event mouseenter
  document.getElementById("logo").addEventListener("mouseenter", function(event) {
    toggleChangeImageButton(
      true); // Tampilkan tombol "Change Image" saat mouse masuk ke dalam imagePreviewContainer
  });

  // Tambahkan event listener untuk event mouseleave
  document.getElementById("logo").addEventListener("mouseleave", function(event) {
    toggleChangeImageButton(
      false); // Sembunyikan tombol "Change Image" saat mouse meninggalkan imagePreviewContainer
  });
});

// Fungsi untuk mengatur visibilitas tombol "Change Image"
function toggleChangeImageButton(visible) {
  let changeImage = document.getElementById("changeImage");
  if (visible) {
    changeImage.style.display = "flex"; // Mengubah display menjadi flex jika visible true
    changeImage.style.justifyContent = "center"; // Mengatur justify content menjadi center
    changeImage.style.alignItems = "center"; // Mengatur align items menjadi center
  } else {
    changeImage.style.display = "none"; // Jika tidak, display none
    changeImage.style.width = ""; // Menghapus width jika tidak visible
    changeImage.style.height = ""; // Menghapus height jika tidak visible
  }
}

/////////////////////////////////////
// Fungsi preview signature
// Panggil fungsi previewAddSignature saat halaman dimuat pertama kali
document.addEventListener('DOMContentLoaded', function() {
  // Ubah panggilan fungsi previewAddSignature untuk menyertakan defaultSignaturePath
  var defaultSignaturePath = "<?= $signatureInfo['Path']; ?>"; // Ubah menjadi nilai yang sesuai dari PHP
  previewAddSignature(defaultSignaturePath);
});

// Panggil fungsi previewAddSignature saat pengguna memilih file
document.getElementById('signature').addEventListener('change', function(event) {
  // Ambil file yang dipilih oleh pengguna
  var selectedSignature = event.target.files[0];

  // Cek apakah pengguna telah memilih file baru
  if (selectedSignature) {
    // Buat URL untuk file yang dipilih
    var fileURL = URL.createObjectURL(selectedSignature);
    // Panggil fungsi previewAddSignature dengan menggunakan file yang dipilih
    previewAddSignature(fileURL);
  }
});

// Fungsi untuk menampilkan preview signature
function previewAddSignature(signatureURL) {
  let signatureElement = document.getElementById("signature-preview");
  let signaturePlaceholderContainer = document.getElementById("signature-placeholder-container");
  let signaturePreviewContainer = document.getElementById("signature-preview-container");
  let cancelButton2 = document.getElementById("cancelButton2");

  if (signatureURL) {
    // Tampilkan gambar
    signatureElement.src = signatureURL;
    signatureElement.style.display = "block";
    // Sembunyikan placeholder dan tampilkan container gambar
    signaturePlaceholderContainer.style.display = "none";
    signaturePreviewContainer.style.display = "block";
    // Tampilkan tombol "Batal"
    cancelButton2.style.display = "block";
  } else {
    // Jika tidak ada gambar yang dipilih, gunakan gambar default
    var defaultLogoPath = "<?php echo $defaultLogoPath; ?>";
    signatureElement.src = defaultLogoPath;
    signatureElement.style.display = "block";
    // Sembunyikan placeholder dan tampilkan container gambar
    signaturePlaceholderContainer.style.display = "none";
    signaturePreviewContainer.style.display = "block";
    // Tampilkan tombol "Batal"
    cancelButton2.style.display = "block";
  }
}

// Fungsi untuk menghapus signature
function resetSignature() {
  var signatureInput = document.getElementById('signature');
  signatureInput.value = ''; // Menghapus nilai input
  document.getElementById('signature-preview-container').style.display =
    'none'; // Menyembunyikan kontainer preview gambar
  document.getElementById('signature-placeholder-container').style.display =
    'block'; // Menampilkan kontainer placeholder
  document.getElementById('cancelButton2').style.display = 'none'; // Menyembunyikan tombol "Batal"
}

// Fungsi refresh signature
function refreshSignature() {
  defaultSignaturePath = "<?= $signatureInfo['Path'] ?>";
  previewAddSignature(defaultSignaturePath);
}

document.addEventListener('DOMContentLoaded', function() {
  // Panggil fungsi untuk mengatur visibilitas tombol "Change Signature" secara awal
  toggleChangeSignatureButton(false);

  // Tambahkan event listener untuk event mouseenter
  document.getElementById("signature").addEventListener("mouseenter", function(event) {
    toggleChangeSignatureButton(
      true); // Tampilkan tombol "Change Signature" saat mouse masuk ke dalam SignaturePreviewContainer
  });

  // Tambahkan event listener untuk event mouseleave
  document.getElementById("signature").addEventListener("mouseleave", function(event) {
    toggleChangeSignatureButton(
      false); // Sembunyikan tombol "Change Signature" saat mouse meninggalkan SignaturePreviewContainer
  });
});

// Fungsi untuk mengatur visibilitas tombol "Change Signature"
function toggleChangeSignatureButton(visible) {
  let changeSignature = document.getElementById("changeSignature");
  if (visible) {
    changeSignature.style.display = "flex"; // Mengubah display menjadi flex jika visible true
    changeSignature.style.justifyContent = "center"; // Mengatur justify content menjadi center
    changeSignature.style.alignItems = "center"; // Mengatur align items menjadi center
  } else {
    changeSignature.style.display = "none"; // Jika tidak, display none
    changeSignature.style.width = ""; // Menghapus width jika tidak visible
    changeSignature.style.height = ""; // Menghapus height jika tidak visible
  }
}


// Validasi Bootstrap pada formulir
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