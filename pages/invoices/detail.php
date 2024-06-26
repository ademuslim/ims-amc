<?php
require '../../includes/function.php';

$category_param = isset($_GET['category']) ? $_GET['category'] : '';
$page_title = $category_param === 'outgoing' ? 'Detail Invoice Outgoing' : 'Detail Invoice Incoming';
$content_title = $category_param === 'outgoing' ? 'Keluar' : 'Masuk';
require '../../includes/header.php';

$data_faktur = [];
$data_faktur_detail = [];
$signatureDetails = []; // Array untuk menyimpan detail signature info
$error_message = '';

// Ambil Data faktur berdasarkan id
if (isset($_GET['id']) && $_GET['id'] !== '') {
  $id_faktur = $_GET['id'];
  $mainTable = 'faktur';
  $joinTables = [
    ["kontak pengirim", "faktur.id_pengirim = pengirim.id_kontak"], 
    ["rekening_kontak", "pengirim.id_kontak = rekening_kontak.id_kontak"], 
    ["kontak penerima", "faktur.id_penerima = penerima.id_kontak"],
    ['ppn', 'faktur.id_ppn = ppn.id_ppn']
  ];
  $columns =  'faktur.*, 
              pengirim.nama_kontak AS nama_pengirim,
              pengirim.alamat AS alamat_pengirim, 
              pengirim.telepon AS telepon_pengirim, 
              pengirim.email AS email_pengirim, 
              penerima.nama_kontak AS nama_penerima, 
              penerima.alamat AS alamat_penerima,
              rekening_kontak.*,
              ppn.*';

  $conditions = "faktur.id_faktur = '$id_faktur'";

  $data_faktur = selectDataJoin($mainTable, $joinTables, $columns, $conditions);

  // Jika data ditemukan
  if (!empty($data_faktur)) {
    $data = $data_faktur[0]; // Satu hasil berdasarkan id

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

    // Jika data faktur ditemukan, ambil detail faktur berdasarkan id
    $mainDetailTable = 'detail_faktur';
    $joinDetailTables = [
        ['faktur', 'detail_faktur.id_faktur = faktur.id_faktur'], 
        ['produk', 'detail_faktur.id_produk = produk.id_produk'],
        ['pesanan_pembelian', 'detail_faktur.id_pesanan = pesanan_pembelian.id_pesanan']
    ];
    $columns = 'detail_faktur.*, produk.*, pesanan_pembelian.no_pesanan';
    $conditions = "detail_faktur.id_faktur = '$id_faktur'";

    $data_faktur_detail = selectDataJoin($mainDetailTable, $joinDetailTables, $columns, $conditions);

    // var_dump($data_faktur_detail);
    
    $no_pesanan_list = []; // Array untuk menyimpan no_pesanan tanpa duplikasi

    if (!empty($data_faktur_detail)) {
        foreach ($data_faktur_detail as $detail) {
            if (!in_array($detail['no_pesanan'], $no_pesanan_list)) {
                $no_pesanan_list[] = $detail['no_pesanan'];
            }
        }
    }
    
    // Gabungkan no_pesanan yang difilter menjadi satu string dengan <br> sebagai pemisah
    $no_pesanan_info = implode("<br>", array_map('htmlspecialchars', $no_pesanan_list));

    // Gabungkan data berdasarkan id_produk
    $mergedData = [];
    foreach ($data_faktur_detail as $detail) {
        $id_produk = $detail['id_produk'];
        if (isset($mergedData[$id_produk])) {
            // Jika id_produk sudah ada, gabungkan data
            $mergedData[$id_produk]['jumlah'] += $detail['jumlah'];
        } else {
            // Jika id_produk belum ada, tambahkan data baru
            $mergedData[$id_produk] = $detail;
        }
    }

  } else {
      $error_message = "Faktur tidak ditemukan.";
  }
} else {
    $error_message = "ID faktur tidak ditemukan.";
}

if ($error_message): ?>
<div class="alert alert-danger alert-lg d-flex align-items-center" role="alert">
  <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" class="bi bi-exclamation-triangle-fill me-3"
    viewBox="0 0 16 16" role="img" aria-label="Warning:" style="fill:currentColor;">
    <path
      d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
  </svg>
  <?= $error_message; ?>
</div>
<?php else: ?>
<?php if (!empty($data_faktur)) : ?>
<div class="d-flex justify-content-between align-items-center mb-4 d-print-none">
  <h1 class="fs-5 mb-4">Detail Invoice <?= $content_title ?></h1>
  <div>
    <a href="<?= base_url("pages/invoices/$category_param") ?>" class="btn-act btn-back" title="Kembali"></a>

    <a onclick="window.print()" class="btn-act btn-print ms-4" title="Cetak Dokumen"></a>
  </div>
</div>

<div class="paper-wrapper p-5">
  <div class="row mb-2">
    <!-- Logo -->
    <div class="col">
      <?php if (!empty($data['logo'])) : ?>
      <div>
        <img class="image" src="<?= base_url($data['logo']) ?>" alt="Detail Logo">
      </div>
      <?php endif; ?>
    </div>

    <!-- Info Dokumen -->
    <div class="col">
      <div class="row text-end">
        <p class="fs-1 mb-2">INVOICE</p>
        <?php if ($category_param === 'outgoing'): ?>
        <p class="fs-5 text-info d-print-none">[ OUTGOING ]</p>
        <?php else: ?>
        <p class="fs-5 text-info d-print-none">[ INCOMING ]</p>
        <?php endif; ?>
      </div>

      <div class="row d-flex justify-content-end text-end pe-3 mt-2">
        <table class="table table-striped no-border-print" style="max-width: 380px;">
          <tr>
            <th class="text-start">No.</th>
            <td>:</td>
            <td><?= strtoupper($data['no_faktur']) ?></td>
          </tr>
          <tr>
            <th class="text-start">Tanggal</th>
            <td>:</td>
            <td><?= dateID(date('Y-m-d', strtotime($data['tanggal']))) ?></td>
          </tr>
          <tr>
            <th class="text-start">No. PO</th>
            <td>:</td>
            <td><?= strtoupper($no_pesanan_info) ?></td>
          </tr>
          <tr class="d-print-none">
            <th class="text-start">Status</th>
            <td>:</td>
            <td>
              <?php
              // Tentukan kelas bootstrap berdasarkan nilai status
              $status_classes = [
                  'tunggu kirim' => 'text-bg-warning',
                  'belum dibayar' => 'text-bg-danger',
                  'dibayar' => 'text-bg-success'
              ];
              $status_class = $status_classes[$data['status']] ?? '';
              ?>
              <span
                class="badge rounded-pill mb-0 <?= $status_class ?> d-print-none"><?= strtoupper($data['status']) ?></span>
            </td>
          </tr>
        </table>
      </div>
    </div>
  </div>

  <!-- Pengirim -->
  <div class="row mb-2">
    <?php if ($category_param == 'incoming'): ?>
    <p class="mb-0">Pengirim :</p>
    <?php endif; ?>

    <p class="mb-0"><?= strtoupper($data['nama_pengirim']) ?></p>
    <p class="mb-0 text-justify">
      <?= isset($data['alamat_pengirim']) && !empty($data['alamat_pengirim']) ? ucwords($data['alamat_pengirim']) : '' ?>
      <?= isset($data['telepon_pengirim']) && !empty($data['telepon_pengirim']) ? " Telp: " . $data['telepon_pengirim'] : '' ?>
      <?= isset($data['email_pengirim']) && !empty($data['email_pengirim']) ? " Email: " . $data['email_pengirim'] : '' ?>
    </p>
  </div>

  <hr class="row border border-secondary border-1 opacity-25 mb-3" style="margin: 0;">

  <!-- Penerima -->
  <div class="row mb-3">
    <p class="mb-0">
      <?= $category_param === 'outgoing' ? 'Kepada Yth,' : 'Penerima :' ?>
    </p>
    <p class="mb-0"><?= strtoupper($data['nama_penerima']) ?></p>
    <p class="mb-1"><?= ucwords($data['alamat_penerima']) ?></p>
  </div>

  <!-- Detail produk -->
  <div class="row ps-3 pe-3 mb-4">
    <table class="table table-light table-striped">
      <thead>
        <tr class="fw-bolder">
          <td>No.</td>
          <td>Deskripsi</td>
          <td colspan="2">Kuantitas</td>
          <td>Harga</td>
          <td>Total Harga</td>
        </tr>
      </thead>
      <tbody>
        <?php
          $subtotal = 0;
          if (!empty($mergedData)):
            $no = 1; 
            foreach ($mergedData as $detail): 
            
            // Hitung total harga untuk setiap baris
            $total_harga = $detail['jumlah'] * $detail['harga_satuan'];
            // Tambahkan total harga ke subtotal
            $subtotal += $total_harga;
          ?>
        <tr>
          <td><?= $no ?></td>
          <td class="text-wrap"><?= strtoupper($detail['nama_produk']); ?></td>
          <td class="no-border-right"><?= number_format($detail['jumlah'], 0, ',', '.'); ?></td>
          <td class="no-border-left"><?= strtoupper($detail['satuan']); ?></td>
          <td><?= formatRupiah($detail['harga_satuan']); ?></td>
          <td><?= formatRupiah($total_harga); ?></td>
        </tr>
        <?php $no++; endforeach; endif; ?>
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

            // Hitung terbilang
            $terbilang_total = bagiTerbilang($total_setelah_ppn);
          ?>
        <?php if ($tampil_subtotal): ?>
        <tr>
          <td colspan="2" class="bg-transparent"></td>
          <td class="fw-bolder" colspan="3">Subtotal</td>
          <td colspan="2"><?= formatRupiah($subtotal) ?></td>
        </tr>
        <?php endif; ?>
        <?php if ($diskon > 0): ?>
        <tr>
          <td colspan="2" class="bg-transparent"></td>
          <td class="fw-bolder" colspan="2">Diskon</td>
          <td><?= $data['diskon'] . " %" ?></td>
          <td colspan="2"><?= formatRupiah($nilai_diskon) ?></td>
        </tr>
        <?php endif; ?>
        <?php if ($tarif_ppn > 0): ?>
        <tr>
          <td colspan="2" class="bg-transparent"></td>
          <td class="no-border-right fw-bolder">PPN</td>
          <td class="no-border-left" colspan="2"><?= strtoupper($data['jenis_ppn']) . "( " . $tarif_ppn . " %)" ?></td>
          <td><?= formatRupiah($nilai_ppn); ?></td>
        </tr>
        <?php endif; ?>
        <tr>
          <td colspan="2" class="bg-transparent"></td>
          <td class="fw-bolder" colspan="3">Total</td>
          <!-- <td colspan="2">Dari DB: <?= $data['total'] ?></td> -->
          <td colspan="2"><?= formatRupiah($total_setelah_ppn); ?></td>
        </tr>
      </tfoot>
    </table>
  </div>

  <div class="row justify-content-between">
    <div class="col-auto">
      <table class="table table-striped mb-5 text-center no-border-print">
        <?php if (!empty($terbilang_total['jutaan'])): ?>
        <tr>
          <td><?= strtoupper($terbilang_total['jutaan']); ?></td>
        </tr>
        <?php endif; ?>
        <?php if (!empty($terbilang_total['ribuan'])): ?>
        <tr>
          <td><?= strtoupper($terbilang_total['ribuan']); ?></td>
        </tr>
        <?php endif; ?>
        <?php if (!empty($terbilang_total['ratusan'])): ?>
        <tr>
          <td><?= strtoupper($terbilang_total['ratusan']); ?></td>
        </tr>
        <?php endif; ?>
      </table>

      <p>PLACE, PAYMENT REMIT TO :</p>
      <table class="table table-striped">
        <!-- Informasi rekening kontak pengirim -->
        <tr>
          <th>ACCOUNT.</th>
          <td><?= ": " . strtoupper($data['pemegang_rekening']); ?></td>
        </tr>
        <tr>
          <th>BANK</th>
          <td><?= ": " . strtoupper($data['nama_bank']); ?>
            <?= !empty(strtoupper($data['cabang_bank'])) ? '(' . $data['cabang_bank'] . ')' : ''; ?>
          </td>
        </tr>
        <tr>
          <th>ACC NO.</th>
          <td><?= ": " . $data['nomor_rekening']; ?></td>
        </tr>
      </table>
    </div>

    <div class="col-auto text-center">
      <?php if ($category_param === 'outgoing') : ?>
      <p>
        <?= ucfirst($signatureDetails['Location'] ?? '') ?>
        <?= isset($signatureDetails['Date']) ? ', ' . dateID($signatureDetails['Date']) : '' ?>
      </p>
      <p>Hormat Kami,</p>
      <?php if (!empty($signatureDetails['Path'])) : ?>
      <img class="image" src="<?= base_url($signatureDetails['Path']) ?>" alt="Preview Signature.">
      <?php else : ?>
      <div style="width: 100px; height: 100px"></div>
      <?php endif; ?>
      <p><?= ucwords($signatureDetails['Name'] ?? '') ?></p>
      <p><?= ucwords($signatureDetails['Position'] ?? '') ?></p>
      <?php else : ?>
      <p><?= ucwords($signatureDetails['Name'] ?? '') ?></p>
      <p><?= ucwords($signatureDetails['Position'] ?? '') ?></p>
      <?php endif; ?>
    </div>
  </div>

  <div class="row justify-content-end mt-5 mb-4 d-print-none">
    <div class="col-auto">
      <a href="<?= base_url("pages/invoices/edit/$category_param/$id_faktur") ?>">
        <button type="button" class="btn btn-warning btn-lg">Ubah Invoices</button>
      </a>
    </div>

    <div class="col-auto">
      <a href="<?= base_url("pages/invoices/$category_param") ?>" class="btn btn-secondary btn-lg">Kembali</a>
    </div>
  </div>
</div>
</div>

<?php
endif; endif;
require '../../includes/footer.php';
?>