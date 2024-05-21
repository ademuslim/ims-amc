<?php
// Ambil nilai kategori dari parameter URL
$category_param = isset($_GET['category']) ? $_GET['category'] : '';

// Atur judul halaman berdasarkan kategori
$page_title = $category_param === 'outgoing' ? 'Detail Quotation Outgoing' : 'Detail Quotation Incoming';

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

// Variabel untuk menyimpan data penawaran harga dan detail
$data_penawaran_harga = [];
$data_penawaran_harga_detail = [];
$signatureDetails = []; // Array untuk menyimpan detail signature info
$error_message = '';

// Ambil Data Penawaran Harga berdasarkan id
if (isset($_GET['id']) && $_GET['id'] !== '') {
  $id_penawaran = $_GET['id'];
  $mainTable = 'penawaran_harga';
  $joinTables = [
      ['kontak_internal', 'penawaran_harga.id_pengirim = kontak_internal.id_pengirim'], 
      ['pelanggan', 'penawaran_harga.id_penerima = pelanggan.id_pelanggan'],
      ['ppn', 'penawaran_harga.id_ppn = ppn.id_ppn']
  ];
  $columns = 'penawaran_harga.*, kontak_internal.*, pelanggan.nama_pelanggan AS nama_penerima, pelanggan.alamat, ppn.jenis_ppn, ppn.tarif';
  $conditions = "penawaran_harga.id_penawaran = '$id_penawaran'";

  // Panggil fungsi selectDataJoin dengan ORDER BY
  $data_penawaran_harga = selectDataJoin($mainTable, $joinTables, $columns, $conditions);

  // Cek apakah data ditemukan
  if (!empty($data_penawaran_harga)) {
    $data = $data_penawaran_harga[0]; // Karena kita mengharapkan satu hasil saja berdasarkan id

    if (!empty($data["signature_info"])) {
      // Pisahkan data signature_info berdasarkan koma (,) untuk mendapatkan setiap elemen
      $signature_info_parts = explode(", ", $data["signature_info"]);
      
      // Loop melalui setiap elemen untuk menyimpan pasangan kunci dan nilai
      foreach ($signature_info_parts as $part) {
        // Pecah setiap elemen menjadi pasangan kunci dan nilai
        $pair = explode(": ", $part);
        
        // Simpan pasangan kunci dan nilai dalam array asosiatif
        if (count($pair) == 2) {
          $signatureDetails[$pair[0]] = $pair[1];
        }
      }
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
    $data_penawaran_detail = selectDataJoin($mainDetailTable, $joinDetailTables, $columns, $conditions);
    
  } else {
      echo "Penawaran tidak ditemukan.";
  }
} else {
echo "ID penawaran tidak ditemukan.";
}

if ($error_message): ?>
<p><?php echo $error_message; ?></p>
<?php else: ?>
<?php if (!empty($data_penawaran_harga)): ?>
<div class="d-flex justify-content-between align-items-center mb-4">
  <h1 class="fs-5 mb-4">Detail Penawaran Harga</h1>
  <!-- Tombol untuk memicu cetak -->
  <button onclick="printContent()">Cetak Dokumen</button>
</div>
<div class="paper-wrapper">
  <div class="container">

    <div class="row">
      <!-- Logo -->
      <div class="col-md-6 p-0">
        <div>
          <img class="image" src="<?= $data['logo'] ?>" alt="Detail Logo">
        </div>
      </div>
      <!-- Judul Dokumen -->
      <div class="col-md-6 p-0">
        <p class="fs-2 text-end">Penawaran Harga</p>
      </div>
    </div>

    <div class="row justify-content-between align-items-end">
      <!-- Pengirim -->
      <div class="col-md-7 p-0 mt-3">
        <p><?= strtoupper($data['nama_pengirim']) ?></p>
        <p><?= ucwords($data['alamat_pengirim']) ?></p>
        <p><?= "Telp: " . $data['telepon'] . " Email: " . $data['email']?></p>
      </div>
      <!-- Info Dokumen -->
      <div class="col-md-5 p-0">
        <div class="row justify-content-end">
          <div class="col-auto">
            <p>No.</p>
            <p>Tanggal</p>
            <p>Status</p>
          </div>
          <div class="col-auto">
            <p><?= ": " . strtoupper($data['no_penawaran']) ?></p>
            <p><?= ": " . dateID(date('Y-m-d', strtotime($data['tanggal']))) ?></p>
            <?php
            // Tentukan kelas bootstrap berdasarkan nilai status
            $status_class = '';
            if ($data['status'] == 'draft') {
                $status_class = 'text-bg-warning';
            } elseif ($data['status'] == 'terkirim') {
                $status_class = 'text-bg-info';
            } elseif ($data['status'] == 'ditolak') {
                $status_class = 'text-bg-danger';
            } elseif ($data['status'] == 'dibayar') {
                $status_class = 'text-bg-success';
            }
            ?>
            <span class="badge <?= $status_class ?>"><?= strtoupper($data['status']) ?></span>
          </div>
        </div>
      </div>
    </div>

    <hr class="row mb-4 border border-secondary border-1 opacity-25">

    <?php if ($category_param == 'outgoing') : ?>
    <div class="row">
      <p class="p-0">Kepada Yth,</p>
      <p class="p-0"><?= strtoupper($data['nama_penerima']) ?></p>
      <p class="p-0"><?= ucwords($data['alamat']) ?></p>
    </div>
    <?php endif; ?>

    <div class="row mb-3">
      <div class="col-sm-2 p-0">U.P.</div>
      <div class="col-auto">
        <p><?= ": " . ucwords($data['up']) ?></p>
      </div>
    </div>

    <div class="row">
      <p class="p-0">Dengan hormat,</p>
      <p class="p-0">Kami ingin menawarkan harga untuk layanan dan produk kami. Berikut detailnya:</p>
      <!-- Tampil detail produk -->
      <table class="table table-light table-striped">
        <thead>
          <tr class="fw-bolder">
            <td>No.</td>
            <td>No. Produk</td>
            <td>Nama Produk</td>
            <td colspan="2">Kuantitas</td>
            <td>Harga</td>
            <td>Total Harga</td>
          </tr>
        </thead>
        <tbody id="detail-table">
          <?php
          $subtotal = 0;
          if (!empty($data_penawaran_detail)): ?>
          <?php $no = 1; ?>
          <?php foreach ($data_penawaran_detail as $detail): ?>
          <?php
            // Hitung total harga untuk setiap baris
            $total_harga = $detail['jumlah'] * $detail['harga_satuan'];
            // Tambahkan total harga ke subtotal
            $subtotal += $total_harga;
          ?>
          <tr>
            <td><?= $no ?></td>
            <td><?= strtoupper($detail['no_produk']); ?></td>
            <td><?= strtoupper($detail['nama_produk']); ?></td>
            <td><?= $detail['jumlah']; ?></td>
            <td><?= strtoupper($detail['satuan']); ?></td>
            <td><?= formatRupiah($detail['harga_satuan']); ?></td>
            <td><?= formatRupiah($total_harga); ?></td>
          </tr>
          <?php $no++ ?>
          <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
        <tfoot>
          <?php
            // Hitung nilai diskon
            $diskon = isset($data['diskon']) ? $data['diskon'] : 0;
            $nilai_diskon = ($subtotal * $diskon) / 100;
            // Hitung subtotal setelah diskon
            $subtotal_setelah_diskon = $subtotal - $nilai_diskon;
            // Hitung PPN berdasarkan subtotal setelah diskon
            $tarif_ppn = isset($data['tarif']) ? $data['tarif'] : 0;
            $nilai_ppn = ($subtotal_setelah_diskon * $tarif_ppn) / 100;
            // Hitung total setelah PPN
            $total_setelah_ppn = $subtotal_setelah_diskon + $nilai_ppn;
            $tampil_subtotal = ($diskon > 0 || $tarif_ppn > 0);
          ?>
          <?php if ($tampil_subtotal): ?>
          <tr>
            <td colspan="3" style="background-color: transparent;"></td>
            <td colspan="3">Subtotal</td>
            <td colspan="2"><?= formatRupiah($subtotal) ?></td>
          </tr>
          <?php endif; ?>
          <?php if ($diskon > 0): ?>
          <tr>
            <td colspan="3" style="background-color: transparent;"></td>
            <td colspan="2">Diskon</td>
            <td><?= $data['diskon'] . " %" ?></td>
            <td colspan="2"><?= formatRupiah($nilai_diskon) ?></td>
          </tr>
          <?php endif; ?>
          <?php if ($tarif_ppn > 0): ?>
          <tr>
            <td colspan="3" style="background-color: transparent;"></td>
            <td colspan="2">PPN</td>
            <td><?= $data['jenis_ppn'] . " (" . $tarif_ppn . " %)" ?></td>
            <td><?= formatRupiah($nilai_ppn); ?></td>
          </tr>
          <?php endif; ?>
          <tr>
            <td colspan="3" style="background-color: transparent;"></td>
            <td colspan="3">Total</td>
            <!-- <td colspan="2">Dari DB: <?= $data['total'] ?></td> -->
            <td colspan="2"><?= formatRupiah($total_setelah_ppn); ?></td>
          </tr>
        </tfoot>
      </table>
    </div>

    <div class="row mb-3">
      <div class="col-md-5">
        <p>Keterangan:</p>
        <p><?= ucfirst($data['catatan']) ?></p>
      </div>
    </div>

    <div class="row mb-3">
      <p>Kami berharap penawaran ini dapat memenuhi kebutuhan yang Bapak/Ibu miliki. Apabila terdapat pertanyaan atau
        klarifikasi
        lebih lanjut mengenai penawaran ini, silakan hubungi kami. Kami sangat menghargai kerjasama dan dukungan yang
        berkelanjutan.</p>
      <p>Terima kasih atas perhatian dan kerjasamanya.</p>
    </div>

    <div class="row justify-content-end">
      <div class="col-md-5">
        <div class="row justify-content-center mb-3">
          <div class="col-auto">
            <?= isset($signatureDetails['Location']) ? ucfirst($signatureDetails['Location']) : '' ?>,
            <?= isset($signatureDetails['Date']) ? dateID($signatureDetails['Date']) : '' ?>
          </div>
        </div>
        <div class="row justify-content-center mb-3">
          <p class="col-auto">Hormat Kami,</p>
        </div>
        <div class="row justify-content-center mb-3">
          <img class="image" src="<?= $signatureDetails['Path'] ?>" alt="Preview Signature.">
        </div>
        <div class="row justify-content-center mb-3">
          <div class="col-auto"><?= isset($signatureDetails['Name']) ? ucwords($signatureDetails['Name']) : '' ?></div>
        </div>
        <div class="row justify-content-center mb-3">
          <div class="col-auto">
            <?= isset($signatureDetails['Position']) ? ucwords($signatureDetails['Position']) : '' ?></div>
        </div>
      </div>
    </div>
  </div>
  <?php endif; ?>
  <?php endif; ?>

  <div class="row justify-content-end mt-5 mb-4">
    <div class="col-auto">
      <a href="edit.php?id=<?= $id_penawaran ?>">
        <button type="button" class="btn btn-warning btn-lg">Ubah Penawaran Harga</button>
      </a>
    </div>

    <div class="col-auto">
      <a href="index.php">
        <button type="button" class="btn btn-secondary btn-lg">Kembali</button>
      </a>
    </div>
  </div>
</div>
</div>
<?php
require '../../includes/footer.php';
?>