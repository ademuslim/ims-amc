<?php
$category_param = isset($_GET['category']) ? $_GET['category'] : '';
$page_title = $category_param === 'outgoing' ? 'Add Invoice Outgoing' : 'Add Invoice Incoming';
require '../../includes/header.php';

// Set kategori halaman (Outgoing / Incoming)
if ($category_param === 'outgoing') {
  $category = 'keluar';
  $sender = 'internal';
  $receiver1 = 'customer';
  $receiver2 = 'supplier';
  $category_po = 'masuk';
} elseif ($category_param === 'incoming') {
  $category = 'masuk';
  $sender1 = 'customer';
  $sender2 = 'supplier';
  $receiver = 'internal';
  $category_po = 'keluar';
} else {
  die("Kategori tidak valid");
}

// Khusus Outgoing
if ($category_param === 'outgoing') {
  $default_logo_path = "";
  $default_signature_path = ""; // Default path signature
  
  // Ambil path logo dan path signature terbaru dari tabel penawaran_harga (Kategori = keluar)
  $data = selectData("penawaran_harga", "kategori = 'keluar' AND logo IS NOT NULL AND logo != ''", "tanggal DESC", "1");

  if (!empty($data)) {
    $default_logo_path = $data[0]["logo"];
    
    // Ambil signature_info
    if (!empty($data[0]["signature_info"])) {
      // Pisahkan setiap elemen signature_info berdasarkan koma (,)
      $signature_info_parts = explode(", ", $data[0]["signature_info"]);

      // Ambil 'path' untuk path signature
      foreach ($signature_info_parts as $part) {
        $pair = explode(": ", $part); // Pecah menjadi pasangan kunci dan nilai
        
        // Jika pasangan kunci dan nilai sesuai dengan 'path', simpan nilainya
        if ($pair[0] == 'Path') {
          // Jika nilai Path tidak kosong, gunakan nilai tersebut
          if (!empty($pair[1])) {
            $default_signature_path = $pair[1];
          } else {
            // Jika nilai Path kosong, set default path
            
            $default_signature_path = "../../assets/image/uploads/signature/no_signature.png";
          }
          break; // Keluar dari loop setelah menemukan path
        }
      }
    }
  }
}
?>

<h1 class="fs-5 mb-4">Buat Invoice Baru</h1>
<div class="paper-wrapper">
  <form action="<?= base_url("pages/invoices/process.php") ?>" method="POST" class="needs-validation"
    enctype="multipart/form-data" novalidate>
    <!-- Input kategori -->
    <input type="hidden" name="kategori" value="<?= htmlspecialchars($category) ?>">

    <div class="container">
      <!-- Input Logo Outgoing-->
      <div class="row">
        <?php if ($category_param === 'outgoing') {?>
        <div class="col-md-6 p-0 position-relative">
          <input type="hidden" id="removeLogoInput" name="remove_logo" value="false">
          <div id="image-preview-container" class="position-relative">
            <div class="d-flex flex-column justify-content-center align-items-center h-100">
              <img id="logo-preview" src="" alt="Tanpa Logo">
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
          <p class="fs-2 text-end">Invoice</p>
        </div>
        <?php } else { ?>
        <p class="fs-2 p-0">Invoice Incoming</p>
        <?php } ?>
      </div>

      <div class="row justify-content-between align-items-end">
        <!-- Input Pengirim -->
        <div class="col-md-5 p-0">
          <div class="row mb-3">
            <label for="pengirim" class="col-sm-3 col-form-label">Pengirim</label>
            <div class="col-sm-9">
              <select class="form-select form-select-sm" id="pengirim" name="pengirim" required>
                <option value="" selected disabled>-- Pilih Pengirim --</option>
                <?php
                  // Ambil data kontak sesuai dengan kategori sender
                  $kontak_pengirim = [];
                  if ($category_param === 'outgoing') {
                    $kontak_pengirim = selectData("kontak", "kategori = '$sender'");
                  } elseif ($category_param === 'incoming') {
                    $kontak_pengirim = array_merge(
                      selectData("kontak", "kategori = '$sender1'"),
                      selectData("kontak", "kategori = '$sender2'")
                    );
                  }
                  
                  foreach ($kontak_pengirim as $row_pengirim) {
                    // Jika kategori adalah 'outgoing' dan nama kontak adalah 'PT. Mitra Tehno Gemilang', tandai sebagai selected
                    $selected = ($category_param == 'outgoing' && strtolower($row_pengirim['nama_kontak']) == 'pt. mitra tehno gemilang') ? 'selected' : '';
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
            <!-- input no invoice outgoing -->
            <?php if ($category_param == 'outgoing') { ?>
            <label for="no_faktur" class="col-sm-3 col-form-label">No:</label>
            <div class="col-sm-9">
              <input type="text" class="form-control form-control-sm" id="no_faktur" name="no_faktur" readonly required>
              <div class="invalid-feedback">
                Sistem error, nomor invoice gagal dimuat.
              </div>
            </div>

            <!-- input no invoice incoming -->
            <?php } elseif ($category_param == 'incoming') { ?>
            <label for="no_faktur" class="col-sm-3 col-form-label">No:</label>
            <div class="col-sm-9">
              <input type="text" class="form-control form-control-sm" name="no_faktur" required>
              <div class="invalid-feedback">
                No faktur tidak boleh kosong.
              </div>
            </div>
            <?php } ?>
          </div>

          <!-- Tanggal -->
          <div class="row mb-3">
            <label for="tanggal" class="col-sm-3 col-form-label">Tanggal</label>
            <div class="col-auto">
              <input type="datetime-local" class="form-control form-control-sm" id="tanggal" name="tanggal" required>
              <div class="invalid-feedback">
                Harap pilih tanggal.
              </div>
            </div>
          </div>
        </div>
      </div>

      <hr class="row mb-5 border border-secondary border-1 opacity-25">

      <div class="row">
        <!-- Input penerima -->
        <div class="col-md-5 p-0">
          <div class="row mb-3">
            <label for="penerima" class="col-sm-3 col-form-label">Penerima</label>
            <div class="col-sm-9">
              <select class="form-select form-select-sm" id="penerima" name="penerima" required>
                <option value="" selected disabled>-- Pilih Penerima --</option>
                <?php
                  // Ambil data kontak sesuai dengan kategori receiver
                  $kontak_penerima = [];
                  if ($category_param === 'outgoing') {
                    $kontak_penerima = array_merge(
                      selectData("kontak", "kategori = '$receiver1'"),
                      selectData("kontak", "kategori = '$receiver2'")
                    );
                  } elseif ($category_param === 'incoming') {
                    $kontak_penerima = selectData("kontak", "kategori = '$receiver'");
                  }

                  foreach ($kontak_penerima as $row_penerima) {
                    // Jika kategori adalah 'incoming' dan nama kontak adalah 'PT. Mitra Tehno Gemilang', tandai sebagai selected
                    $selected = ($category_param == 'incoming' && strtolower($row_penerima['nama_kontak']) == 'pt. mitra tehno gemilang') ? 'selected' : '';
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
        <!-- Tambah detail produk -->
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
            <tr class="main-tr">
              <td>1</td>

              <td>
                <select class="form-select form-select-sm id_pesanan_info" id="id_pesanan" name="id_pesanan[]" required>
                  <option value="" selected disabled>-- Pilih PO. --</option>
                  <?php
                    $mainTable = 'pesanan_pembelian';
                    $joinTables = [
                        ['detail_pesanan', 'pesanan_pembelian.id_pesanan = detail_pesanan.id_pesanan'],
                        ['produk', 'detail_pesanan.id_produk = produk.id_produk']
                    ];
                    $columns = 'pesanan_pembelian.id_pesanan, pesanan_pembelian.no_pesanan, produk.nama_produk, produk.id_produk';
                    $conditions = "pesanan_pembelian.kategori = '$category_po' AND detail_pesanan.sisa_pesanan > 0 AND pesanan_pembelian.status = 'diproses' AND pesanan_pembelian.status_hapus = 0";
                    $orderBy = 'pesanan_pembelian.tanggal DESC, produk.nama_produk ASC';
                    $po = selectDataJoin($mainTable, $joinTables, $columns, $conditions, $orderBy);

                    foreach ($po as $row_po) {
                        echo '<option value="' . $row_po['id_pesanan'] . '" data-id-produk="' . $row_po['id_produk'] . '">' 
                            . strtoupper($row_po['no_pesanan']) . " (" . strtoupper($row_po['nama_produk']) . ")" 
                            . '</option>';
                    }
                  ?>
                </select>
                <div class="pesanan-info">
                  <!-- Info pesanan yang terpilih akan ditampilkan di sini -->
                </div>
              </td>

              <td>
                <select class="form-select form-select-sm" id="id_produk" name="id_produk[]" required>
                  <option value="" selected disabled>-- Pilih Produk --</option>
                  <?php
                    $mainTable = 'produk';
                    $joinTables = [
                        ['detail_pesanan', 'produk.id_produk = detail_pesanan.id_produk'],
                    ];
                    $columns = 'DISTINCT produk.id_produk, produk.nama_produk';
                    $conditions = "detail_pesanan.sisa_pesanan > 0";
                    $produk = selectDataJoin($mainTable, $joinTables, $columns, $conditions);

                    foreach ($produk as $row_produk) {
                      echo '<option value="' . $row_produk['id_produk'] . '">' . strtoupper($row_produk['nama_produk']) . '</option>';
                    }
                  ?>
                </select>
              </td>

              <td>
                <input type="number" name="jumlah[]" class="form-control form-control-sm qty" min="1" required>
              </td>
              <td>
                <input type="text" name="harga_satuan[]" class="form-control form-control-sm price" min="0" required>
              </td>
              <td class="total">0</td>
              <td class="text-center">
                <div class="d-flex justify-content-center align-items-center">
                  <button type="button" class="remove-btn btn-cancel m-0"></button>
                </div>
              </td>
            </tr>
          </tbody>
          <tfoot>
            <tr>
              <td colspan="4" rowspan="4" class="bg-transparent">
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
                <div class="input-group input-group-sm">
                  <input type="number" class="form-control" id="diskon" name="diskon" min="0" step="0.01" value="0"
                    aria-describedby="basic-addon1">
                  <span class="input-group-text" id="basic-addon1">%</span>
                </div>
              </td>
              <td colspan="2" id="nilai-diskon">0</td>
            </tr>

            <tr>
              <td class="fw-bolder">PPN<a href="#" class="link-danger link-offset-2 link-underline-opacity-0"
                  data-bs-toggle="tooltip" data-bs-custom-class="custom-tooltip"
                  data-bs-title="PPN dalam persen, pilih 'Tanpa PPN' jika tanpa PPN.">*</a></td>
              <td>
                <div class="input-group input-group-sm">
                  <select class="form-select form-select-sm" id="jenis_ppn" name="jenis_ppn" required
                    aria-describedby="tarif-ppn">
                    <option value="" selected disabled>-- Pilih PPN --</option>
                    <?php
                        $ppn = selectData("ppn");
                        foreach ($ppn as $row_ppn) {
                            echo '<option value="' . $row_ppn['id_ppn'] . '">' . ucwords($row_ppn['jenis_ppn']) . '</option>';
                        }
                      ?>
                  </select>
                  <label class="input-group-text" for="jenis_ppn">%<span class="d-none" id="tarif-ppn"></span></label>
                </div>
              </td>
              <td colspan="2" id="total-ppn">0</td>
            </tr>

            <tr>
              <td class="fw-bolder" colspan="2">Total</td>
              <td colspan="2">
                <span id="grand-total">0</span>
                <!-- Input tersembunyi untuk menyimpan grand total -->
                <input type="hidden" id="hidden-grand-total" name="grand_total">
              </td>
            </tr>
          </tfoot>
        </table>
      </div>

      <hr class="row mb-5 border border-secondary border-1 opacity-25">

      <div class="row justify-content-between">
        <div class="col-md-5 p-0 mb-3">
          <div class="form-floating">
            <textarea class="form-control" id="catatan" name="catatan" style="height: 100px"></textarea>
            <label for="catatan" name="catatan">Catatan</label>
          </div>
        </div>

        <div class="col-md-5">
          <!-- Input Tempat dan Tanggal Signature -->
          <div class="row mb-3">
            <label class="mb-3">Tempat dan tanggal.</label>
            <div class="input-group input-group-sm p-0">
              <input type="text" class="form-control" name="signing_location" required>
              <span class="input-group-text">,</span>
              <input type="date" class="form-control" name="signing_date" required>
            </div>
          </div>

          <!-- Input Signature Outgoing-->
          <?php if ($category_param === 'outgoing') {?>
          <div class="row justify-content-center mb-3">
            <div class="col-md-6 p-0 position-relative">
              <input type="hidden" name="remove_signature" id="removeSignatureInput" value="false">

              <div id="signature-preview-container" class="position-relative">
                <div class="d-flex flex-column justify-content-center align-items-center h-100">
                  <img id="signature-preview" src="" alt="Preview Signature">
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
            <input type="text" class="form-control form-control-sm" id="signer-name" name="signer_name" required
              placeholder="Nama Lengkap">
          </div>
          <div class="row mb-3">
            <input type="text" class="form-control form-control-sm" id="signer-position" name="signer_position"
              placeholder="Jabatan" required>
          </div>
        </div>
      </div>

      <div class="row justify-content-end mt-4 mb-4">
        <div class="col-auto">
          <button type="submit" class="btn btn-primary btn-lg" name="add">Simpan</button>
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
  // Event listener untuk input harga satuan
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

  // Event untuk menghapus baris
  $(document).on('click', '.remove-btn', function() {
    $(this).closest('.main-tr').remove();
    updateRowNumbers(); // Panggil fungsi untuk memperbarui nomor urutan setelah menghapus baris
    updateTotal(); // Panggil fungsi untuk memperbarui total setelah menghapus baris
    updateGrandTotal(); // Panggil fungsi untuk memperbarui grand total setelah menghapus baris
  });

  // Event untuk menambahkan baris baru
  $(document).on('click', '.add-more-tr', function() {
    var produkOptions = '<?php
      $mainTable = 'produk';
      $joinTables = [
          ['detail_pesanan', 'produk.id_produk = detail_pesanan.id_produk'],
      ];
      $columns = 'DISTINCT produk.id_produk, produk.nama_produk';
      $conditions = "detail_pesanan.sisa_pesanan > 0";
      $produk = selectDataJoin($mainTable, $joinTables, $columns, $conditions);

      foreach ($produk as $row_produk) {
        echo '<option value="' . $row_produk['id_produk'] . '">' . strtoupper($row_produk['nama_produk']) . '</option>';
      }
    ?>';

    var poOptions = '<?php
        $mainTable = 'pesanan_pembelian';
        $joinTables = [
          ['detail_pesanan', 'pesanan_pembelian.id_pesanan = detail_pesanan.id_pesanan'],
          ['produk', 'detail_pesanan.id_produk = produk.id_produk']
        ];
        $columns = 'pesanan_pembelian.id_pesanan, pesanan_pembelian.no_pesanan, produk.nama_produk, produk.id_produk';
        $conditions = "pesanan_pembelian.kategori = '$category_po' AND detail_pesanan.sisa_pesanan > 0 AND pesanan_pembelian.status = 'diproses' AND pesanan_pembelian.status_hapus = 0";
        $orderBy = 'pesanan_pembelian.tanggal DESC, produk.nama_produk ASC';

        // Fungsi selectDataJoin Anda mungkin perlu penyesuaian untuk menerima parameter DISTINCT
        $po = selectDataJoin($mainTable, $joinTables, $columns, $conditions, $orderBy);

        // Loop melalui hasil query dan tampilkan dalam opsi dropdown
        foreach ($po as $row_po) {
          echo '<option value="' . $row_po['id_pesanan'] . '" data-id-produk="' . $row_po['id_produk'] . '">' 
              . strtoupper($row_po['no_pesanan']) . " (" . strtoupper($row_po['nama_produk']) . ")" 
              . '</option>';
          }
    ?>';

    var rowCount = $('#detail-table tr.main-tr').length + 1; // Ambil jumlah baris saat ini dan tambahkan 1
    $('#detail-table').append(
      `<tr class="main-tr">
          <td>${rowCount}</td>
          <td>
              <select class="form-select form-select-sm id_pesanan_info" id="id_pesanan" name="id_pesanan[]" required>
                <option value="" selected disabled>-- Pilih PO. --</option>
                ${poOptions}
              </select>
              <div class="pesanan-info">
              </div>
          </td>
          <td>
            <select class="form-select form-select-sm" id="id_produk" name="id_produk[]" required>
              <option value="" selected disabled>-- Pilih Produk --</option>
              ${produkOptions}
            </select>
          </td>
          <td>
            <input type="number" name="jumlah[]" class="form-control form-control-sm qty" min="1" required>
          </td>
          <td>
            <input type="text" name="harga_satuan[]" class="form-control form-control-sm price" min="0" required>
          </td>
          <td class="total">0</td>
          <td class="text-center">
            <div class="d-flex justify-content-center align-items-center">
              <button type="button" class="remove-btn btn-cancel m-0"></button>
            </div>
          </td>
        </tr>`
    );
    updateRowNumbers(); // Panggil fungsi untuk memperbarui nomor urutan setelah menambahkan baris baru
  });

  // Fungsi untuk memperbarui nomor urutan pada setiap baris
  function updateRowNumbers() {
    $('#detail-table tr.main-tr').each(function(index) {
      $(this).find('td:first').text(index + 1); // Atur nomor urutan sesuai dengan indeks baris
    });
  }
  // Event listener untuk menghitung total saat nilai "Qty" atau "Harga Satuan" berubah
  $(document).on('input', '.qty, .price', function() {
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

  // Fungsi untuk memperbarui total harga
  function updateTotal() {
    var totalHarga = 0;
    $('#detail-table tr.main-tr').each(function() {
      var qty = parseFloat($(this).find('.qty').val()) || 0;
      var price = unformatRupiah($(this).find('.price').val()) || 0; // Unformat harga sebelum perhitungan
      var total = qty * price;
      $(this).find('.total').text(formatRupiah(total)); // Format dan atur teks pada elemen <td class="total">
      totalHarga += total; // Tambahkan total baris ke total harga
    });
    $('#total-harga').text(formatRupiah(
      totalHarga)); // Format dan atur teks total harga pada elemen dengan id "total-harga"
  }

  // Fungsi untuk memperbarui grand total
  function updateGrandTotal() {
    var totalHarga = unformatRupiah($('#total-harga').text()) || 0;
    var diskonPersen = parseFloat($('#diskon').val()) || 0;
    var diskon = (totalHarga * diskonPersen) / 100; // Hitung nilai diskon dari persentase
    var tarifPPN = parseFloat($('#tarif-ppn').text()) || 0;
    var totalPPN = (totalHarga - diskon) * (tarifPPN / 100);
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

  $(document).on('change', '.id_pesanan_info', function() {
    var id_pesanan = $(this).val();
    var id_produk = $(this).find(':selected').data(
      'id-produk'); // Mengambil data-id-produk dari opsi yang dipilih
    var infoCell = $(this).closest('tr').find('.pesanan-info');

    $.ajax({
      url: '<?= base_url("pages/invoices/getPesananInfo.php") ?>',

      method: 'POST',
      data: {
        id_pesanan: id_pesanan,
        id_produk: id_produk
      },
      success: function(response) {
        infoCell.html(response);
      },
      error: function(xhr, status, error) {
        console.error('AJAX error:', status, error);
        infoCell.html('<p>Gagal memuat data.</p>');
      }
    });
  });

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
    imgElement.src = default_logo_path;
    imgElement.style.display = "block";
    placeholderContainer.style.display = "none";
    imagePreviewContainer.style.display = "block";
    cancelButton.style.display = "block";
    console.log("No file selected. Using default image.");
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

    if (default_signature_path.trim() !== "") { // Pengecekan jika default_signature_path tidak kosong
      signElement.src = default_signature_path;
      signElement.style.display = "block";
      signaturePlaceholderContainer.style.display = "none";
      signaturePreviewContainer.style.display = "block";
      cancelButtonSignature.style.display = "block";
      console.log("No file selected. Using default signature.");
    } else {
      // Hide preview if default_signature_path is empty
      signElement.style.display = "none";
      signaturePlaceholderContainer.style.display = "block";
      signaturePreviewContainer.style.display = "none";
      cancelButtonSignature.style.display = "none";
      console.log("No default signature path provided. Signature preview not displayed.");
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