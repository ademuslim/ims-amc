<?php
$category_param = isset($_GET['category']) ? $_GET['category'] : '';
$page_title = $category_param === 'outgoing' ? 'Detail Invoice Outgoing' : 'Detail Invoice Incoming';
require '../../includes/header.php';

// Variabel untuk menyimpan data faktur dan detail
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
              ppn.*';

  $conditions = "faktur.id_faktur = '$id_faktur'";

  // Panggil fungsi selectDataJoin dengan ORDER BY
  $data_faktur = selectDataJoin($mainTable, $joinTables, $columns, $conditions);

  // Cek apakah data ditemukan
  if (!empty($data_faktur)) {
    $data = $data_faktur[0]; // Karena kita mengharapkan satu hasil saja berdasarkan id

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

    $mainDetailTable = 'detail_faktur';
    $joinDetailTables = [
        ['faktur', 'detail_faktur.id_faktur = faktur.id_faktur'], 
        ['produk', 'detail_faktur.id_produk = produk.id_produk'],
        ['pesanan_pembelian', 'detail_faktur.id_pesanan = pesanan_pembelian.id_pesanan']
    ];
    $columns = 'detail_faktur.*, produk.*, pesanan_pembelian.no_pesanan';
    $conditions = "detail_faktur.id_faktur = '$id_faktur'";

    // Panggil fungsi selectDataJoin dengan ORDER BY
    $data_faktur_detail = selectDataJoin($mainDetailTable, $joinDetailTables, $columns, $conditions);
    // var_dump($data_faktur_detail);
    
    // Array untuk menyimpan no_pesanan tanpa duplikasi
    $no_pesanan_list = [];

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
      echo "Faktur tidak ditemukan.";
  }
} else {
echo "ID faktur tidak ditemukan.";
}

if ($error_message): ?>
<p><?php echo $error_message; ?></p>
<?php else: ?>
<?php if (!empty($data_faktur)): ?>
<div class="d-flex justify-content-between align-items-center mb-4">
  <h1 class="fs-5 mb-4">Detail Invoice</h1>
  <div>
    <a href="<?= base_url("pages/invoices/$category_param") ?>" class="btn-act btn-back" title="Kembali"></a>

    <button class="ms-3" onclick="printContent()">Cetak Dokumen</button>
  </div>
</div>

<div class="paper-wrapper">
  <div class="container">

    <div class="row">
      <!-- Logo -->
      <div class="col-md-6 p-0">
        <?php if (!empty($data['logo'])): ?>
        <div>
          <img class="image" src="<?= base_url($data['logo']) ?>" alt="Detail Logo">
        </div>
        <?php endif; ?>
      </div>
      <!-- Judul Dokumen -->
      <div class="col-md-6 p-0">
        <p class="fs-2 text-end">Invoice</p>
      </div>
    </div>

    <div class="row justify-content-between align-items-end">
      <!-- Pengirim -->
      <div class="col-md-7 p-0 mt-3">
        <p><?= strtoupper($data['nama_pengirim']) ?></p>
        <p><?= ucwords($data['alamat_pengirim']) ?></p>
        <p><?= "Telp: " . $data['telepon_pengirim'] . " Email: " . $data['email_pengirim']?></p>
      </div>

      <!-- Info Dokumen -->
      <div class="col-md-5 p-0">
        <div class="row justify-content-end">
          <div class="col-auto">
            <table class="table table-light table-striped">
              <tr>
                <th>No.</th>
                <td><?= strtoupper($data['no_faktur']) ?></td>
              </tr>
              <tr>
                <th>Tanggal</th>
                <td><?= dateID(date('Y-m-d', strtotime($data['tanggal']))) ?></td>
              </tr>
              <tr>
                <th>No. PO</th>
                <td><?= strtoupper($no_pesanan_info) ?></td>
              </tr>
              <tr>
                <th>Status</th>
                <td>
                  <?php
                  // Tentukan kelas bootstrap berdasarkan nilai status
                  $status_class = '';
                  if ($data['status'] == 'tunggu kirim') {
                      $status_class = 'text-bg-warning';
                  } elseif ($data['status'] == 'belum dibayar') {
                      $status_class = 'text-bg-info';
                  } elseif ($data['status'] == 'dibayar') {
                      $status_class = 'text-bg-success';
                  }
                  ?>
                  <span class="badge rounded-pill <?= $status_class ?>"><?= strtoupper($data['status']) ?></span>
                </td>
              </tr>
            </table>
          </div>
        </div>
      </div>
    </div>

    <hr class="row mb-4 border border-secondary border-1 opacity-25">

    <?php if ($category_param == 'outgoing') : ?>
    <div class="row">
      <p class="p-0">Kepada Yth,</p>
      <p class="p-0"><?= strtoupper($data['nama_penerima']) ?></p>
      <p class="p-0"><?= ucwords($data['alamat_penerima']) ?></p>
    </div>
    <?php endif; ?>

    <div class="row">
      <!-- Tampil detail produk -->
      <table class="table table-light table-striped" style="width: 100%;">
        <thead>
          <tr class="fw-bolder">
            <td>No.</td>
            <td>Deskripsi</td>
            <td colspan="2">Kuantitas</td>
            <td>Harga</td>
            <td>Total Harga</td>
          </tr>
        </thead>
        <tbody id="detail-table">
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
            <td><?= $detail['jumlah']; ?></td>
            <td><?= strtoupper($detail['satuan']); ?></td>
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
          ?>
          <?php if ($tampil_subtotal): ?>
          <tr>
            <td colspan="2" class="bg-transparent"></td>
            <td colspan="3">Subtotal</td>
            <td colspan="2"><?= formatRupiah($subtotal) ?></td>
          </tr>
          <?php endif; ?>
          <?php if ($diskon > 0): ?>
          <tr>
            <td colspan="2" class="bg-transparent"></td>
            <td colspan="2">Diskon</td>
            <td><?= $data['diskon'] . " %" ?></td>
            <td colspan="2"><?= formatRupiah($nilai_diskon) ?></td>
          </tr>
          <?php endif; ?>
          <?php if ($tarif_ppn > 0): ?>
          <tr>
            <td colspan="2" class="bg-transparent"></td>
            <td colspan="2">PPN</td>
            <td><?= $data['jenis_ppn'] . "( " . $tarif_ppn . " %)" ?></td>
            <td><?= formatRupiah($nilai_ppn); ?></td>
          </tr>
          <?php endif; ?>
          <tr>
            <td colspan="2" class="bg-transparent"></td>
            <td colspan="3">Total</td>
            <!-- <td colspan="2">Dari DB: <?= $data['total'] ?></td> -->
            <td colspan="2"><?= formatRupiah($total_setelah_ppn); ?></td>
          </tr>
        </tfoot>
      </table>
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
          <?php if (!empty($signatureDetails['Path'])) {?>
          <img class="image" src="<?= $signatureDetails['Path'] ?>" alt="Preview Signature.">
          <?php }else { ?>
          <div style="width: 100px; height: 100px"></div>
          <?php } ?>
        </div>

        <div class="row justify-content-center mb-3">
          <div class="col-auto"><?= isset($signatureDetails['Name']) ? ucwords($signatureDetails['Name']) : '' ?>
          </div>
        </div>
        <div class="row justify-content-center mb-3">
          <div class="col-auto">
            <?= isset($signatureDetails['Position']) ? ucwords($signatureDetails['Position']) : '' ?></div>
        </div>
      </div>
    </div>
    <?php endif; endif; ?>

    <div class="row justify-content-end mt-5 mb-4">
      <div class="col-auto">
        <a href="<?= base_url("pages/invoices/edit/$category_param/$id_faktur") ?>">
          <button type="button" class="btn btn-warning btn-lg">Ubah Invoices</button>
        </a>
      </div>

      <div class="col-auto">
        <a href="<?= base_url("pages/invoices/$category_param") ?>">
          <button type="button" class="btn btn-secondary btn-lg">Kembali</button>
        </a>
      </div>
    </div>
  </div>
</div>
<?php
require '../../includes/footer.php';
?>