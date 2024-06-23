<?php
$category_param = isset($_GET['category']) ? $_GET['category'] : '';
$page_title = $category_param === 'outgoing' ? 'Edit Invoice Outgoing' : 'Edit Invoice Incoming';
require '../../includes/header.php';

// Set kategori halaman (Outgoing / Incoming)
if ($category_param === 'outgoing') {
  $category = 'keluar';
  $sender = 'internal';
  $receiver = 'customer';
  $category_po = 'masuk';
} elseif ($category_param === 'incoming') {
  $category = 'masuk';
  $sender = 'customer';
  $receiver = 'internal';
  $category_po = 'keluar';
} else {
  die("Kategori tidak valid");
}

// Variabel data detail
$data_detail = [];
$signatureInfo = []; // Array detail signature info
$error_message = '';

if (isset($_GET['id']) && $_GET['id'] !== '') {
  $id_faktur = $_GET['id'];
  $mainTable = 'faktur';
  $joinTables = [
    ["kontak pengirim", "faktur.id_pengirim = pengirim.id_kontak"],
    ["kontak penerima", "faktur.id_penerima = penerima.id_kontak"],
    ['ppn', 'faktur.id_ppn = ppn.id_ppn']
  ];
  $columns = 'faktur.*, pengirim.id_kontak AS id_pengirim, pengirim.nama_kontak AS nama_pengirim, penerima.nama_kontak AS nama_penerima, penerima.id_kontak AS id_penerima, ppn.jenis_ppn';
  $conditions = "faktur.id_faktur = '$id_faktur'";

  $data = selectDataJoin($mainTable, $joinTables, $columns, $conditions);

  if (!empty($data)) {
    $data = $data[0];
    $default_logo_path = $data["logo"];
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
      $default_signature_path = $signatureInfo["Path"];
      if ($default_signature_path == "") {
        $default_signature_path = "assets/image/uploads/signature/no_signature.png";
      }
    }

    // Detail pesanan berdasarkan id_faktur
    $mainDetailTable = 'detail_faktur';
    $joinDetailTables = [
        ['faktur', 'detail_faktur.id_faktur = faktur.id_faktur'], 
        ['produk', 'detail_faktur.id_produk = produk.id_produk']
    ];
    $columns = 'detail_faktur.*, produk.*';
    $conditions = "detail_faktur.id_faktur = '$id_faktur'";
    // Panggil fungsi selectDataJoin dengan ORDER BY
    $data_detail = selectDataJoin($mainDetailTable, $joinDetailTables, $columns, $conditions);
  } else {
      $error_message = "Faktur tidak ditemukan.";
  }
} else {
    $error_message = "ID faktur tidak ditemukan.";
}

if ($error_message): ?>
<p><?php echo $error_message; ?></p>
<?php else: ?>
<?php if (!empty($data)): ?>
<h1 class="fs-5 mb-4">Ubah Faktur</h1>
<div class="paper-wrapper p-5">
  <form action="<?= base_url("pages/invoices/process.php") ?>" method="POST" class="needs-validation"
    enctype="multipart/form-data" novalidate>
    <div class="container">
      <input type="hidden" name="kategori" value="<?= htmlspecialchars($category) ?>">
      <input type="hidden" name="id_faktur" value="<?= $id_faktur ?>">
      <input type="hidden" name="default_logo_path" value="<?= $default_logo_path ?>">
      <input type="hidden" name="signature_info" value="<?= $data['signature_info'] ?>">

      <!-- Input Logo Outgoing-->
      <div class="row">
        <?php if ($category_param === 'outgoing') {?>
        <div class="col-md-6 p-0 position-relative">
          <input type="hidden" id="removeLogoInput" name="remove_logo" value="false">
          <div id="image-preview-container" class="position-relative">
            <div class="d-flex flex-column justify-content-center align-items-center h-100">
              <img id="logo-preview" src="" alt="Preview Logo">
            </div>
            <button type="button" title="Hapus Logo" class="position-absolute top-0 end-0 z-3" id="cancelButton"
              onclick="removeLogo()"></button>
            <span class="position-absolute top-0 start-0" id="changeImage">Ubah Logo</span>
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
        </div>

        <!-- Judul Dokumen -->
        <div class="col-md-6 p-0">
          <p class="fs-2 text-end">INVOICE</p>
        </div>
        <?php } else { ?>
        <p class="fs-2 p-0">INVOICE INCOMING</p>
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
            <!-- input edit no invoice outgoing -->
            <?php if ($category_param == 'outgoing') { ?>
            <label for="no_faktur" class="col-sm-3 col-form-label">No:</label>
            <div class="col-sm-9">
              <input type="text" class="form-control form-control-sm" id="no_faktur" name="no_faktur"
                value="<?= strtoupper($data['no_faktur']) ?>" readonly required>
              <div class="invalid-feedback">
                Sistem error, nomor faktur gagal dimuat.
              </div>
            </div>

            <!-- input edit no invoice incoming -->
            <?php } elseif ($category_param == 'incoming') { ?>
            <label for="no_faktur" class="col-sm-3 col-form-label">No:</label>
            <div class="col-sm-9">
              <input type="text" class="form-control form-control-sm" name="no_faktur"
                value="<?= strtoupper($data['no_faktur']) ?>" required>
              <div class="invalid-feedback">
                No Faktur tidak boleh kosong.
              </div>
            </div>
            <?php } ?>
          </div>

          <div class="row mb-3">
            <label for="tanggal" class="col-sm-3 col-form-label">Tanggal</label>
            <div class="col-sm-9">
              <!-- input tgl invoice outgoing -->
              <?php if ($category_param == 'outgoing') { ?>
              <input type="datetime-local" class="form-control form-control-sm" id="tanggal" name="tanggal"
                value="<?= $data['tanggal'] ?>" readonly required>
              <!-- input tgl invoice incoming -->
              <?php } elseif ($category_param == 'incoming') { ?>
              <input type="datetime-local" class="form-control form-control-sm" id="tanggal" name="tanggal"
                value="<?= $data['tanggal'] ?>" required>
              <?php } ?>
              <div class="invalid-feedback">
                Harap pilih tanggal.
              </div>
            </div>
          </div>

          <div class="row mb-3">
            <label for="status" class="col-sm-3 col-form-label">status</label>
            <div class="col-sm-9">
              <select class="form-select form-select-sm" id="status" name="status" required>
                <?php 
              // Ambil status
              $status_options = getEnum('status', 'faktur');
              foreach ($status_options as $option) { ?>
                <option value="<?= $option; ?>" <?php if ($option === $data['status']) echo 'selected'; ?>>
                  <?= ucfirst($option); ?>
                </option>
                <?php } ?>
              </select>
            </div>
          </div>
        </div>
      </div>

      <hr class="row mb-5 border border-secondary border-1 opacity-25">

      <div class="row">
        <!-- Input Penerima -->
        <div class="col-md-5 p-0">
          <div class="row mb-3">
            <label for="penerima" class="col-sm-3 col-form-label">Penerima</label>
            <div class="col-sm-9">
              <select class="form-select form-select-sm" id="penerima" name="penerima" required>
                <?php
                    // Ambil data kontak sesuai dengan kategori receiver
                    $kontak_penerima = selectData("kontak", "kategori = '$receiver'");
                    foreach ($kontak_penerima as $row_penerima) {
                        $selected = ""; // Variabel untuk menentukan apakah opsi saat ini harus dipilih

                        // Tentukan penerima mana yang akan menjadi default berdasarkan kategori dan ID penerima saat ini
                        if ($row_penerima['id_kontak'] == $data['id_penerima']) {
                            $selected = "selected";
                        }
                        echo '<option value="' . $row_penerima['id_kontak'] . '" ' . $selected . '>' . ucwords($row_penerima['nama_kontak']) . '</option>';
                    }
                  ?>
              </select>
              <div class="invalid-feedback">
                Harap pilih penerima.
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
              <td>No.</td>
              <td>No. PO.<a href="#" class="link-danger link-offset-2 link-underline-opacity-0" data-bs-toggle="tooltip"
                  data-bs-custom-class="custom-tooltip" data-bs-title="PO yang tersedia">*</a></td>
              <td>Nama Produk<a href="#" class="link-danger link-offset-2 link-underline-opacity-0"
                  data-bs-toggle="tooltip" data-bs-custom-class="custom-tooltip"
                  data-bs-title="Pilih produk sesuai PO">*</a></td>
              <td>Kuantitas<a href="#" class="link-danger link-offset-2 link-underline-opacity-0"
                  data-bs-toggle="tooltip" data-bs-custom-class="custom-tooltip"
                  data-bs-title="Masukan kuantitas tidak melebihi kuantitas PO">*</a></td>
              <td>Harga</td>
              <td colspan="2">Jumlah</td>
            </tr>
          </thead>
          <tbody id="detail-table">
            <?php
              $subtotal = 0;
              if (!empty($data_detail)):
                  $no = 1;
                  foreach ($data_detail as $detail):
                      // Hitung total harga untuk setiap baris
                      $total_harga = $detail['jumlah'] * $detail['harga_satuan'];
                      $subtotal += $total_harga;
            ?>
            <tr class="main-tr">
              <td><?= $no ?></td>
              <input type="hidden" name="id_detail_faktur[]" value="<?= $detail['id_detail_faktur'] ?>">
              <td>
                <select class="form-select form-select-sm" name="id_pesanan[]" required>
                  <?php
                    $po = selectData("pesanan_pembelian");
                    foreach ($po as $row_po) {
                        $selected = ($row_po['id_pesanan'] == $detail['id_pesanan']) ? "selected" : "";
                        echo '<option value="' . $row_po['id_pesanan'] . '" ' . $selected . '>' . strtoupper($row_po['no_pesanan']) . '</option>';
                    }
                  ?>
                </select>
              </td>
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
            <?php $no++; endforeach; endif; ?>
          </tbody>
          <tfoot>
            <tr>
              <td colspan="3" rowspan="2" class="bg-transparent">
                <button type="button" class="add-more-tr btn btn-primary btn-lg btn-icon btn-add mt-3">Tambah
                  Baris</button>
              </td>
              <td class="fw-bolder" colspan="2">Subtotal</td>
              <td colspan="2" id="total-harga">0</td>
            </tr>
            <tr>
              <td class="fw-bolder">Diskon<a href="#" class="link-danger link-offset-2 link-underline-opacity-0"
                  data-bs-toggle="tooltip" data-bs-custom-class="custom-tooltip"
                  data-bs-title="Diskon dalam persen, isi 0 jika tanpa diskon.">*</a></td>
              <td>
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
              <td colspan="3" class="bg-transparent"></td>
              <td class="fw-bolder">PPN<a href="#" class="link-danger link-offset-2 link-underline-opacity-0"
                  data-bs-toggle="tooltip" data-bs-custom-class="custom-tooltip"
                  data-bs-title="PPN dalam persen, pilih 'Tanpa PPN' jika tanpa PPN.">*</a></td>
              <td>
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
              <td colspan="3" class="bg-transparent"></td>
              <td class="fw-bolder" colspan="2">Total</td>
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

          <!-- Input Signature Outgoing-->
          <?php if ($category_param === 'outgoing') {?>
          <div class="row justify-content-center mb-3">
            <div class="col-md-6 p-0 position-relative">
              <input type="hidden" name="remove_signature" id="removeSignatureInput" value="false">

              <div id="signature-preview-container" class="position-relative">
                <div class="d-flex flex-column justify-content-center align-items-center h-100">
                  <img id="signature-preview" src="" alt="Tanpa Signature">
                </div>
                <button type="button" title="Hapus Tanda Tangan" class="position-absolute top-0 end-0 z-3"
                  id="cancelButtonSignature" onclick="removeSignature()"></button>
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

              <div class="position-absolute bottom-0 start-0 p-0">
                <input type="file" title="" class="form-control form-control-sm" id="signature" name="signature"
                  accept="image/*" onchange="previewAddSignature(event)">
              </div>
            </div>
          </div>
          <?php } ?>

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
          <a href="javascript:history.back()">
            <button type="button" class="btn btn-secondary btn-lg">Batal</button>
          </a>
        </div>
      </div>
    </div>
  </form>
</div>
<?php endif; endif; ?>

<script>
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
    var idDetailFaktur = $(this).closest('.main-tr').find('input[name="id_detail_faktur[]"]').val();
    $(this).closest('.main-tr').remove();
    updateRowNumbers(); // Panggil fungsi untuk memperbarui nomor urutan setelah menghapus baris
    updateTotal(); // Panggil fungsi untuk memperbarui total setelah menghapus baris
    updateGrandTotal(); // Panggil fungsi untuk memperbarui grand total setelah menghapus baris
    // Tambahkan idDetailFaktur ke #deleted-rows dan cetak konten #deleted-rows ke konsol
    $('#deleted-rows').append('<input type="hidden" name="deleted_rows[]" value="' + idDetailFaktur + '">');
  });

  // Event untuk menambahkan baris baru
  $(document).on('click', '.add-more-tr', function() {
    var produkOptions = '<?php
      $produk = selectData("produk");
      foreach ($produk as $row_produk) {
          echo '<option value="' . $row_produk['id_produk'] . '">' . $row_produk['nama_produk'] . '</option>';
      }
      ?>';

    var poOptions = '<?php
        $po = selectData("pesanan_pembelian","kategori = '$category_po'" );
        foreach ($po as $row_po) {
            echo '<option value="' . $row_po['id_pesanan'] . '">' . $row_po['no_pesanan'] . '</option>';
        }
    ?>';

    var rowCount = $('#detail-table tr.main-tr').length + 1;
    var newIdDetail = "newId" + (rowCount - 1);
    $('#detail-table').append(
      `<tr class="main-tr">
          <td>${rowCount}</td>
          <input type="hidden" name="id_detail_faktur[]" value="${newIdDetail}">
          <td>
            <select class="form-select form-select-sm" id="id_pesanan" name="id_pesanan[]" required>
                <option value="" selected disabled>-- Pilih Pesanan Pembelian. --</option>
                ${poOptions}
            </select>
          </td>
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
    var idDetailFaktur = row.find('input[name="id_detail_faktur[]"]').val(); // Dapatkan ID detail
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
  // Panggil fungsi untuk menghitung total dan grand total saat halaman dimuat pertama kali
  updateTotal();
  updateGrandTotal();
});
</script>

<?php if ($category_param === 'outgoing'): ?>
<script>
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
        var nomorFaktur = this.responseText.toUpperCase();
        document.getElementById("no_faktur").value = nomorFaktur;
      }
    };
    xhttp.open("GET", "<?= base_url("pages/invoices/getDocumentNumber.php") ?>?month=" + month + "&year=" +
      year,
      true);
    xhttp.send();
  } else {
    document.getElementById("no_faktur").value =
      ""; // Kosongkan nilai nomor penawaran jika tanggal tidak diisi
  }
});

// Penanganan logo
// Fungsi untuk menampilkan preview gambar
function previewAddImage(event) {
  let imgElement = document.getElementById("logo-preview");
  let placeholderContainer = document.getElementById("placeholder-container");
  let imagePreviewContainer = document.getElementById("image-preview-container");
  let cancelButton = document.getElementById("cancelButton");

  let imageURL = "";

  if (event && event.target && event.target.files && event.target.files[0]) {
    let file = event.target.files[0];
    imageURL = URL.createObjectURL(file);
    console.log(`File selected: ${file.name}`);
    imgElement.src = imageURL;
    imgElement.style.display = "block";
    placeholderContainer.style.display = "none";
    imagePreviewContainer.style.display = "block";
    cancelButton.style.display = "block";
  } else {
    let default_logo_path = "<?= base_url($default_logo_path) ?>";

    // Periksa apakah default_logo_path tidak kosong
    if (default_logo_path.trim() !== "") {
      imgElement.src = default_logo_path;
      imgElement.style.display = "block";
      placeholderContainer.style.display = "none";
      imagePreviewContainer.style.display = "block";
      cancelButton.style.display = "block";
      console.log("Using default image.");
    } else {
      // Jika default_logo_path kosong, sembunyikan preview
      imgElement.style.display = "none";
      placeholderContainer.style.display = "block";
      imagePreviewContainer.style.display = "none";
      cancelButton.style.display = "none";
      console.log("No file selected and default image not available.");
    }
  }
  console.log("Image preview updated.");
}

// Fungsi untuk menghapus logo dan menampilkan placeholder
function removeLogo() {
  let imgElement = document.getElementById("logo-preview");
  let placeholderContainer = document.getElementById("placeholder-container");
  let imagePreviewContainer = document.getElementById("image-preview-container");
  let cancelButton = document.getElementById("cancelButton");
  let inputFile = document.getElementById("logo");

  document.getElementById("removeLogoInput").value = "true";

  // Sembunyikan elemen gambar
  imgElement.src = "";
  imgElement.style.display = "none";
  // Tampilkan container placeholder
  placeholderContainer.style.display = "block";
  // Sembunyikan container preview gambar
  imagePreviewContainer.style.display = "none";
  // Sembunyikan tombol "Batal"
  cancelButton.style.display = "none";
  // Hapus nilai input file
  inputFile.value = "";

  console.log("Image removed and input file cleared.");
}

// Panggil fungsi previewAddImage saat halaman dimuat pertama kali
document.addEventListener('DOMContentLoaded', function() {
  previewAddImage();
});

// Panggil fungsi previewAddImage saat pengguna memilih file
document.getElementById('logo').addEventListener('change', function(event) {
  previewAddImage(event);
});

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

// Penanganan signature
// Fungsi untuk menampilkan preview signature
function previewAddSignature(event) {
  let signElement = document.getElementById("signature-preview");
  let signaturePlaceholderContainer = document.getElementById("signature-placeholder-container");
  let signaturePreviewContainer = document.getElementById("signature-preview-container");
  let cancelButtonSignature = document.getElementById("cancelButtonSignature");

  let signatureURL = "";

  if (event && event.target && event.target.files && event.target.files[0]) {
    let file = event.target.files[0];
    signatureURL = URL.createObjectURL(file);
    console.log(`File selected: ${file.name}`);
    signElement.src = signatureURL;
    signElement.style.display = "block";
    signaturePlaceholderContainer.style.display = "none";
    signaturePreviewContainer.style.display = "block";
    cancelButtonSignature.style.display = "block";
  } else {
    let default_signature_path = "<?= base_url($default_signature_path) ?>";

    // Periksa apakah default_signature_path tidak kosong
    if (default_signature_path.trim() !== "") {
      signElement.src = default_signature_path;
      signElement.style.display = "block";
      signaturePlaceholderContainer.style.display = "none";
      signaturePreviewContainer.style.display = "block";
      cancelButtonSignature.style.display = "block";
      console.log("Using default signature.");
    } else {
      // Jika default_signature_path kosong, sembunyikan preview
      signElement.style.display = "none";
      signaturePlaceholderContainer.style.display = "block";
      signaturePreviewContainer.style.display = "none";
      cancelButtonSignature.style.display = "none";
      console.log("No file selected and default signature not available.");
    }
  }
  console.log("Signature preview updated.");
}

// Fungsi untuk menghapus signature dan menampilkan placeholder
function removeSignature() {
  let signElement = document.getElementById("signature-preview");
  let signaturePlaceholderContainer = document.getElementById("signature-placeholder-container");
  let signaturePreviewContainer = document.getElementById("signature-preview-container");
  let cancelButtonSignature = document.getElementById("cancelButtonSignature");
  let inputSignature = document.getElementById("signature");

  document.getElementById("removeSignatureInput").value = "true";

  // Sembunyikan elemen gambar
  signElement.src = "";
  signElement.style.display = "none";
  // Tampilkan container placeholder
  signaturePlaceholderContainer.style.display = "block";
  // Sembunyikan container preview gambar
  signaturePreviewContainer.style.display = "none";
  // Sembunyikan tombol "Batal"
  cancelButtonSignature.style.display = "none";
  // Hapus nilai input file
  inputSignature.value = "";

  console.log("Signature removed and input file cleared.");
}

// Panggil fungsi previewAddSignature saat halaman dimuat pertama kali
document.addEventListener('DOMContentLoaded', function() {
  previewAddSignature();
});

// Panggil fungsi previewAddSignature saat pengguna memilih file
document.getElementById('signature').addEventListener('change', function(event) {
  previewAddSignature(event);
});

document.addEventListener('DOMContentLoaded', function() {
  // Panggil fungsi untuk mengatur visibilitas tombol "Change signature" secara awal
  toggleChangeSignatureButton(false);

  // Tambahkan event listener untuk event mouseenter
  document.getElementById("signature").addEventListener("mouseenter", function(event) {
    toggleChangeSignatureButton(
      true); // Tampilkan tombol "Change signature" saat mouse masuk ke dalam signaturePreviewContainer
  });

  // Tambahkan event listener untuk event mouseleave
  document.getElementById("signature").addEventListener("mouseleave", function(event) {
    toggleChangeSignatureButton(
      false); // Sembunyikan tombol "Change signature" saat mouse meninggalkan signaturePreviewContainer
  });
});

// Fungsi untuk mengatur visibilitas tombol "Change signature"
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
</script>

<?php endif; require '../../includes/footer.php'; ?>