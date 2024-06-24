<?php
$category_param = isset($_GET['category']) ? $_GET['category'] : '';
$page_title = $category_param === 'outgoing' ? 'Detail Delivery Order Outgoing' : 'Detail Delivery Order Incoming';
$content_title = $category_param === 'outgoing' ? 'Keluar' : 'Masuk';
require '../../includes/header.php';

$data_detail_faktur = [];
$signatureConfirmDetails = []; // Array untuk menyimpan detail signature info
$error_message = '';

// Ambil Data faktur berdasarkan id
if (isset($_GET['id']) && $_GET['id'] !== '') {
  $id_detail_faktur = $_GET['id'];
  $mainTable = 'detail_faktur';
  $joinTables = [
    ['faktur', 'detail_faktur.id_faktur = faktur.id_faktur'],
    ['kontak pengirim', 'faktur.id_pengirim = pengirim.id_kontak'], 
    ['kontak penerima', 'faktur.id_penerima = penerima.id_kontak'],
    ['produk', 'detail_faktur.id_produk = produk.id_produk'],
    ['pesanan_pembelian', 'detail_faktur.id_pesanan = pesanan_pembelian.id_pesanan']
  ];
  $columns =  'detail_faktur.*,
              faktur.*,
              produk.nama_produk,
              produk.satuan, 
              pesanan_pembelian.no_pesanan,
              pengirim.nama_kontak AS nama_pengirim,
              pengirim.alamat AS alamat_pengirim,
              pengirim.telepon AS telepon_pengirim,
              pengirim.email AS email_pengirim,
              penerima.nama_kontak AS nama_penerima,
              penerima.alamat AS alamat_penerima';

  $conditions = "detail_faktur.id_detail_faktur = '$id_detail_faktur'";

  // Panggil fungsi selectDataJoin dengan ORDER BY
  $data_detail_faktur = selectDataJoin($mainTable, $joinTables, $columns, $conditions);

  // Cek apakah data ditemukan
  if (!empty($data_detail_faktur)) {
    $data = $data_detail_faktur[0]; // Karena kita mengharapkan satu hasil saja berdasarkan id

    if (!empty($data["signature_confirm"])) {
      // Pisahkan data signature_confirm berdasarkan koma (,) untuk mendapatkan setiap elemen
      $signature_confirm_parts = explode(", ", $data["signature_confirm"]);
      
      // Loop melalui setiap elemen untuk menyimpan pasangan kunci dan nilai
      foreach ($signature_confirm_parts as $part) {
        // Pecah setiap elemen menjadi pasangan kunci dan nilai
        $pair = explode(": ", $part);
        
        // Simpan pasangan kunci dan nilai dalam array asosiatif
        if (count($pair) == 2) {
          $signatureConfirmDetails[$pair[0]] = $pair[1];
        }
      }
    }
  } else {
    $error_message = "Delivery order tidak ditemukan.";
  }
} else {
  $error_message = "ID detail faktur tidak ditemukan.";
}

if ($error_message) : ?>
<div class="alert alert-danger alert-lg d-flex align-items-center" role="alert">
  <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" class="bi bi-exclamation-triangle-fill me-3"
    viewBox="0 0 16 16" role="img" aria-label="Warning:" style="fill:currentColor;">
    <path
      d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
  </svg>
  <?= $error_message; ?>
</div>
<?php else : ?>
<?php if (!empty($data_detail_faktur)): ?>
<div class="d-flex justify-content-between align-items-center mb-4 d-print-none">
  <h1 class="fs-5 mb-4">Detail Delivery Order <?= $content_title ?></h1>
  <div>
    <a href="<?= base_url("pages/delivery-order/$category_param") ?>" class="btn-act btn-back" title="Kembali"></a>

    <a onclick="window.print()" class="btn-act btn-print ms-4" title="Cetak Dokumen"></a>
  </div>
</div>

<div class="paper-wrapper p-5">
  <div class="row mb-2">
    <!-- Logo -->
    <div class="col">
      <?php if ($category_param === 'outgoing' && !empty($data['logo'])) : ?>
      <div>
        <img class="image" src="<?= base_url($data['logo']) ?>" alt="Detail Logo">
      </div>
      <?php endif; ?>
    </div>

    <!-- Info Dokumen -->
    <div class="col">
      <div class="row text-end">
        <p class="fs-4 mb-0">SURAT PENGIRIMAN BARANG</p>
        <?php if ($category_param === 'outgoing'): ?>
        <p class="fs-5 text-info d-print-none">[ OUTGOING ]</p>
        <?php else: ?>
        <p class="fs-5 text-info d-print-none">[ INCOMING ]</p>
        <?php endif; ?>
      </div>

      <div class="row d-flex justify-content-end text-end pe-3 mt-2">
        <table class="table table-striped no-border-print" style="max-width: 250px;">
          <tr>
            <th class="text-start">No.</th>
            <td>:</td>
            <td><?= strtoupper($data['no_pengiriman_barang']) ?></td>
          </tr>
          <tr>
            <th class="text-start">Tanggal</th>
            <td>:</td>
            <td><?= dateID(date('Y-m-d', strtotime($data['tanggal']))) ?></td>
          </tr>
          <tr>
            <th>No. PO</th>
            <td>:</td>
            <td><?= strtoupper($data['no_pesanan']) ?></td>
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
  <div class="row mb-5">
    <p class="mb-0">
      <?= $category_param === 'outgoing' ? 'Kepada Yth,' : 'Penerima :' ?>
    </p>
    <p class="mb-0"><?= strtoupper($data['nama_penerima']) ?></p>
    <p class="mb-1"><?= ucwords($data['alamat_penerima']) ?></p>
  </div>

  <?php if ($category_param == 'outgoing') : ?>
  <div class="row mb-2">
    <p class="mb-0 text-justify">Bersamaan ini, kami kirimkan produk/jasa yang tercantum dibawah ini.
    </p>
  </div>
  <?php endif; ?>

  <!-- Detail produk -->
  <div class="row ps-3 pe-3 mb-5">
    <table class="table table-light table-bordered table-striped special-table">
      <thead>
        <tr class="fw-bolder">
          <td>Deskripsi</td>
          <td>Kuantitas</td>
          <td class="fix-width-col-md">Remark</td>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td class="text-wrap"><?= strtoupper($data['nama_produk']); ?></td>
          <td class="no-wrap"><?= number_format($data['jumlah'], 0, ',', '.') . " " . strtoupper($data['satuan']); ?>
          </td>
          <td></td>
        </tr>
      </tbody>
    </table>
  </div>

  <div class="row p-3 approved-signature">
    <!-- Approved Signature -->
    <div class="col d-flex flex-column align-items-center text-center border p-2">
      <span class="mb-3 fw-bold">Diterima Oleh</span>
      <?php if (!empty($signatureConfirmDetails['Approved Path'])) { ?>
      <!-- Tampilkan gambar jika ada -->
      <img class="img-fluid mb-2" src="<?= base_url($signatureConfirmDetails['Approved Path']) ?>"
        alt="Preview Signature" style="max-height: 100px;">
      <?php } else { ?>
      <!-- Placeholder untuk tandatangan -->
      <div class="mb-2 border signature-placeholder" style="width: 100px; height: 100px;"></div>
      <?php } ?>
      <span><?= isset($signatureConfirmDetails['Approved Name']) ? ucfirst($signatureConfirmDetails['Approved Name']) : '' ?></span>
    </div>

    <!-- Checked Signature -->
    <div class="col d-flex flex-column align-items-center text-center border p-2">
      <span class="mb-3 fw-bold">Mengetahui</span>
      <?php if (!empty($signatureConfirmDetails['Checked Path'])) { ?>
      <!-- Tampilkan gambar jika ada -->
      <img class="img-fluid mb-2" src="<?= base_url($signatureConfirmDetails['Checked Path']) ?>"
        alt="Preview Signature" style="max-height: 100px;">
      <?php } else { ?>
      <!-- Placeholder untuk tandatangan -->
      <div class="mb-2 border signature-placeholder" style="width: 100px; height: 100px;"></div>
      <?php } ?>
      <span><?= isset($signatureConfirmDetails['Checked Name']) ? ucfirst($signatureConfirmDetails['Checked Name']) : '' ?></span>
    </div>

    <!-- Issued Signature -->
    <div class="col d-flex flex-column align-items-center text-center border p-2">
      <span class="mb-3 fw-bold">Dibuat</span>
      <?php if (!empty($signatureConfirmDetails['Issued Path'])) { ?>
      <!-- Tampilkan gambar jika ada -->
      <img class="img-fluid mb-2" src="<?= base_url($signatureConfirmDetails['Issued Path']) ?>" alt="Preview Signature"
        style="max-height: 100px;">
      <?php } else { ?>
      <!-- Placeholder untuk tandatangan -->
      <div class="mb-2 border signature-placeholder" style="width: 100px; height: 100px;"></div>
      <?php } ?>
      <span><?= isset($signatureConfirmDetails['Issued Name']) ? ucfirst($signatureConfirmDetails['Issued Name']) : '' ?></span>
    </div>
  </div>

  <?php endif; endif; ?>

  <div class="row justify-content-end mt-5 mb-4 d-print-none">
    <div class="col-auto">
      <button type="button" class="btn btn-warning btn-lg" data-bs-toggle="modal"
        data-bs-target="#approveModal">Konfirmasi Delivery Order</button>
    </div>

    <!-- Modal Approve -->
    <div class="modal fade" id="approveModal" data-bs-backdrop="static" tabindex="-1"
      aria-labelledby="approveModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="approveModalLabel">Konfirmasi Delivery Order
              <?= strtoupper($data['no_pengiriman_barang']) ?></h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <!-- Form Pembaruan Status -->
            <form action="<?= base_url('pages/delivery-order/process.php') ?>" method="POST"
              enctype="multipart/form-data">
              <div class="row text-center mb-3 p-4">
                <div class="col-md border p-2">
                  <label class="mb-2" for="approved-name">Diterima Oleh</label>

                  <label class="mb-3 p-3 custom-file-label" for="approved-signature">
                    <svg xmlns="http://www.w3.org/2000/svg" height="18" viewBox="0 -960 960 960" width="18">
                      <path
                        d="M440-320v-326L336-542l-56-58 200-200 200 200-56 58-104-104v326h-80ZM240-160q-33 0-56.5-23.5T160-240v-120h80v120h480v-120h80v120q0 33-23.5 56.5T720-160H240Z" />
                    </svg>
                    Unggah Signature
                    <input type="file" class="mt-3 form-control form-control-sm" id="approved-signature"
                      name="approved_signature" accept="image/*">
                    <?php if (!empty($signatureConfirmDetails['Approved Path'])) { ?>
                    <img class="img-fluid m-2" src="<?= base_url($signatureConfirmDetails['Approved Path']) ?>"
                      alt="Preview Signature" style="max-height: 100px;">
                    <?php } ?>
                  </label>

                  <input type="text" class="form-control form-control-sm" id="approved-name" name="approved_name"
                    placeholder="Nama penerima"
                    value="<?= isset($signatureConfirmDetails['Approved Name']) ? ucfirst($signatureConfirmDetails['Approved Name']) : '' ?>">

                  <input type="hidden" name="existing_approved_path"
                    value="<?= htmlspecialchars($signatureConfirmDetails['Approved Path'] ?? '') ?>">
                </div>

                <div class="col-md border p-2">
                  <label class="mb-2" for="checked-name">Mengetahui</label>

                  <label class="mb-3 p-3 custom-file-label" for="checked-signature">
                    <svg xmlns="http://www.w3.org/2000/svg" height="18" viewBox="0 -960 960 960" width="18">
                      <path
                        d="M440-320v-326L336-542l-56-58 200-200 200 200-56 58-104-104v326h-80ZM240-160q-33 0-56.5-23.5T160-240v-120h80v120h480v-120h80v120q0 33-23.5 56.5T720-160H240Z" />
                    </svg>
                    Unggah Signature
                    <input type="file" class="mt-3 form-control form-control-sm" id="checked-signature"
                      name="checked_signature" accept="image/*">
                    <?php if (!empty($signatureConfirmDetails['Checked Path'])) { ?>
                    <img class="img-fluid m-2" src="<?= base_url($signatureConfirmDetails['Checked Path']) ?>"
                      alt="Preview Signature" style="max-height: 100px;">
                    <?php } ?>
                  </label>

                  <input type="text" class="form-control form-control-sm" id="checked-name" name="checked_name"
                    placeholder="Nama pengirim"
                    value="<?= isset($signatureConfirmDetails['Checked Name']) ? ucfirst($signatureConfirmDetails['Checked Name']) : '' ?>">

                  <input type="hidden" name="existing_checked_path"
                    value="<?= htmlspecialchars($signatureConfirmDetails['Checked Path'] ?? '') ?>">
                </div>

                <div class="col-md border p-2">
                  <label class="mb-2" for="issued-name">Dibuat</label><br>

                  <label class="mb-3 p-3 custom-file-label" for="issued-signature">
                    <svg xmlns="http://www.w3.org/2000/svg" height="18" viewBox="0 -960 960 960" width="18">
                      <path
                        d="M440-320v-326L336-542l-56-58 200-200 200 200-56 58-104-104v326h-80ZM240-160q-33 0-56.5-23.5T160-240v-120h80v120h480v-120h80v120q0 33-23.5 56.5T720-160H240Z" />
                    </svg>
                    Unggah Signature
                    <input type="file" class="mt-3 form-control form-control-sm" id="issued-signature"
                      name="issued_signature" accept="image/*">
                    <?php if (!empty($signatureConfirmDetails['Issued Path'])) { ?>
                    <img class="img-fluid m-2" src="<?= base_url($signatureConfirmDetails['Issued Path']) ?>"
                      alt="Preview Signature" style="max-height: 100px;">
                    <?php } ?>
                  </label>

                  <input type="text" class="form-control form-control-sm" id="issued-name" name="issued_name"
                    placeholder="Nama pembuat"
                    value="<?= isset($signatureConfirmDetails['Issued Name']) ? ucfirst($signatureConfirmDetails['Issued Name']) : '' ?>">

                  <input type="hidden" name="existing_issued_path"
                    value="<?= htmlspecialchars($signatureConfirmDetails['Issued Path'] ?? '') ?>">
                </div>
              </div>

              <input type="hidden" name="id_detail_faktur" value="<?= $data['id_detail_faktur']; ?>">
              <input type="hidden" name="category" value="<?= htmlspecialchars($category_param) ?>">
              <button type="submit" class="btn btn-primary" name="approve">Simpan</button>
            </form>
          </div>
        </div>
      </div>
    </div>

    <div class="col-auto">
      <a href="<?= base_url("pages/delivery-order/$category_param") ?>" class="btn btn-secondary btn-lg">Kembali</a>
    </div>
  </div>
</div>

<?php
require '../../includes/footer.php';
?>