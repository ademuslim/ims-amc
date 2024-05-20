<?php
$page_title = "Detail Quotation";
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
$data_pesanan_pembelian = [];
$data_pesanan_pembelian_detail = [];
$signatureDetails = []; // Array untuk menyimpan detail signature info
$error_message = '';

// Ambil Data Penawaran Harga berdasarkan id
if (isset($_GET['id']) && $_GET['id'] !== '') {
  $id_pesanan = $_GET['id'];
  $mainTable = 'pesanan_pembelian';
  $joinTables = [
      ['kontak_internal', 'pesanan_pembelian.id_pengirim = kontak_internal.id_pengirim'], 
      ['pelanggan', 'pesanan_pembelian.id_penerima = pelanggan.id_pelanggan'],
      ['ppn', 'pesanan_pembelian.id_ppn = ppn.id_ppn']
  ];
  $columns = 'pesanan_pembelian.*, kontak_internal.*, pelanggan.nama_pelanggan AS nama_penerima, pelanggan.alamat, ppn.jenis_ppn, ppn.tarif';
  $conditions = "pesanan_pembelian.id_pesanan = '$id_pesanan'";

  // Panggil fungsi selectDataJoin dengan ORDER BY
  $data_pesanan_pembelian = selectDataJoin($mainTable, $joinTables, $columns, $conditions);

  // Cek apakah data ditemukan
  if (!empty($data_pesanan_pembelian)) {
    $data = $data_pesanan_pembelian[0]; // Karena kita mengharapkan satu hasil saja berdasarkan id

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

    // Jika data po ditemukan lanjut mengambil detail po berdasarkan id
    $mainDetailTable = 'detail_pesanan';
    $joinDetailTables = [
        ['pesanan_pembelian', 'detail_pesanan.id_pesanan = pesanan_pembelian.id_pesanan'], 
        ['produk', 'detail_pesanan.id_produk = produk.id_produk']
    ];
    $columns = 'detail_pesanan.*, produk.*';
    $conditions = "detail_pesanan.id_pesanan = '$id_pesanan'";

    // Panggil fungsi selectDataJoin dengan ORDER BY
    $data_pesanan_pembelian_detail = selectDataJoin($mainDetailTable, $joinDetailTables, $columns, $conditions);
    
  } else {
      echo "Penawaran tidak ditemukan.";
  }
} else {
echo "ID penawaran tidak ditemukan.";
}

if ($error_message): ?>
<p><?php echo $error_message; ?></p>
<?php else: ?>
<?php if (!empty($data_pesanan_pembelian)): ?>
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
        <p class="fs-2 text-end">Purchase Order</p>
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
          </div>
          <div class="col-auto">
            <p><?= ": " . strtoupper($data['no_pesanan']) ?></p>
            <p><?= ": " . $data['tanggal'] ?></p>
          </div>
        </div>
      </div>
    </div>

    <hr class="row mb-4 border border-secondary border-1 opacity-25">

    <div class="row">
      <p class="p-0">Kepada Yth,</p>
      <p class="p-0"><?= strtoupper($data['nama_penerima']) ?></p>
      <p class="p-0"><?= ucwords($data['alamat']) ?></p>
    </div>

    <div class="row mb-3">
      <div class="col-sm-2 p-0">U.P.</div>
      <div class="col-auto">
        <p><?= ": " . ucwords($data['up']) ?></p>
      </div>
    </div>

    <div class="row">
      <p class="p-0">Dengan hormat,</p>
      <p class="p-0">Dengan ini, kami mengajukan pesanan pembelian untuk produk/jasa yang tercantum di bawah ini. </p>
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
          if (!empty($data_pesanan_pembelian_detail)): ?>
          <?php $no = 1; ?>
          <?php foreach ($data_pesanan_pembelian_detail as $detail): ?>
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
          ?>
          <tr>
            <td colspan="3" rowspan="4" style="background-color: transparent;"></td>
            <td colspan="3">Subtotal</td>
            <td colspan="2"><?= formatRupiah($subtotal) ?></td>
          </tr>
          <tr>
            <td colspan="2">Diskon</td>
            <td><?= $data['diskon'] . " %" ?></td>
            <td colspan="2"><?= formatRupiah($nilai_diskon) ?></td>
          </tr>
          <tr>
            <td colspan="2">PPN</td>
            <td><?= $data['jenis_ppn'] . "( " . $tarif_ppn . " %)" ?></td>
            <td><?= formatRupiah($nilai_ppn); ?></td>
          </tr>
          <tr>
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
      <p>Demikian pesanan pembelian dari Kami, mohon diproses dengan baik. Apabila terdapat pertanyaan atau klarifikasi
        lebih lanjut mengenai pesanan ini, silakan hubungi kami. Kami sangat menghargai kerjasama dan dukungan yang
        berkelanjutan.</p>
      <p>Terima kasih atas perhatian dan kerjasamanya.</p>
    </div>

    <div class="row justify-content-end">
      <div class="col-md-5">
        <div class="row justify-content-center mb-3">
          <div class="col-auto">
            <?= isset($signatureDetails['Location']) ? ucfirst($signatureDetails['Location']) : '' ?>,
            <?= isset($signatureDetails['Date']) ? $signatureDetails['Date'] : '' ?>
          </div>
        </div>
        <div class="row justify-content-center mb-3">
          <p class="col-auto">Hormat Kami,</p>
        </div>
        <div class="row justify-content-center mb-3">
          <img class="image" src="<?= isset($signatureDetails['Path']) ? $signatureDetails['Path'] : '' ?>"
            alt="Preview Signature.">
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
      <a href="edit.php?id=<?= $id_pesanan ?>">
        <button type="button" class="btn btn-warning btn-lg">Ubah Purchase Order Harga</button>
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