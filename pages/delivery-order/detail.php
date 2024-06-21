<?php
$category_param = isset($_GET['category']) ? $_GET['category'] : '';
$page_title = $category_param === 'outgoing' ? 'Detail Delivery Order Outgoing' : 'Detail Delivery Order Incoming';
require '../../includes/header.php';

// Variabel untuk menyimpan data faktur dan detail
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
      echo "Delivery order tidak ditemukan.";
  }
} else {
echo "ID detail faktur tidak ditemukan.";
}

if ($error_message): ?>
<p><?php echo $error_message; ?></p>
<?php else: ?>
<?php if (!empty($data_detail_faktur)): ?>
<div class="d-flex justify-content-between align-items-center mb-4">
  <h1 class="fs-5 mb-4">Detail Delivery Order</h1>
  <div>
    <a href="<?= base_url("pages/delivery-order/$category_param") ?>" class="btn-act btn-back" title="Kembali"></a>

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
        <p class="fs-2 text-end">Delivery Order</p>
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
                <td><?= strtoupper($data['no_pengiriman_barang']) ?></td>
              </tr>
              <tr>
                <th>Tanggal</th>
                <td><?= dateID(date('Y-m-d', strtotime($data['tanggal']))) ?></td>
              </tr>
              <tr>
                <th>No. PO</th>
                <td><?= strtoupper($data['no_pesanan']) ?></td>
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
                      $status_class = 'text-bg-danger';
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
    <div class="row mb-3">
      <p class="p-0">Kepada Yth,</p>
      <p class="p-0"><?= strtoupper($data['nama_penerima']) ?></p>
      <p class="p-0"><?= ucwords($data['alamat_penerima']) ?></p>
    </div>
    <?php endif; ?>

    <div class="row mb-5">
      <!-- Tampil detail produk -->
      <table class="table table-bordered table-light table-striped" style="width: 100%;">
        <thead>
          <tr class="fw-bolder">
            <td>Deskripsi</td>
            <td>Kuantitas</td>
            <td>Remark</td>
          </tr>
        </thead>
        <tbody id="detail-table">
          <tr>
            <td class="text-wrap"><?= strtoupper($data['nama_produk']); ?></td>
            <td class="no-wrap"><?= number_format($data['jumlah'], 0, ',', '.') . " " . strtoupper($data['satuan']); ?>
            </td>
            <td></td>
          </tr>
        </tbody>
      </table>
    </div>

    <div class="row">
      <!-- Approved Signature -->
      <div class="col-md-4 d-flex flex-column align-items-center text-center border p-2">
        <span class="fw-bold">Diterima Oleh</span>
        <?php if (!empty($signatureConfirmDetails['Approved Path'])) { ?>
        <!-- Tampilkan gambar jika ada -->
        <img class="img-fluid mb-2" src="<?= base_url($signatureConfirmDetails['Approved Path']) ?>"
          alt="Preview Signature" style="max-height: 100px;">
        <?php } else { ?>
        <!-- Placeholder untuk gambar -->
        <div class="mb-2" style="width: 100px; height: 100px; border: 1px solid #ccc;"></div>
        <?php } ?>
        <span><?= isset($signatureConfirmDetails['Approved Name']) ? ucfirst($signatureConfirmDetails['Approved Name']) : '' ?></span>
      </div>

      <!-- Checked Signature -->
      <div class="col-md-4 d-flex flex-column align-items-center text-center border p-2">
        <span class="fw-bold">Mengetahui</span>
        <?php if (!empty($signatureConfirmDetails['Checked Path'])) { ?>
        <!-- Tampilkan gambar jika ada -->
        <img class="img-fluid mb-2" src="<?= base_url($signatureConfirmDetails['Checked Path']) ?>"
          alt="Preview Signature" style="max-height: 100px;">
        <?php } else { ?>
        <!-- Placeholder untuk gambar -->
        <div class="mb-2" style="width: 100px; height: 100px; border: 1px solid #ccc;"></div>
        <?php } ?>
        <span><?= isset($signatureConfirmDetails['Checked Name']) ? ucfirst($signatureConfirmDetails['Checked Name']) : '' ?></span>
      </div>

      <!-- Issued Signature -->
      <div class="col-md-4 d-flex flex-column align-items-center text-center border p-2">
        <span class="fw-bold">Dibuat</span>
        <?php if (!empty($signatureConfirmDetails['Issued Path'])) { ?>
        <!-- Tampilkan gambar jika ada -->
        <img class="img-fluid mb-2" src="<?= base_url($signatureConfirmDetails['Issued Path']) ?>"
          alt="Preview Signature" style="max-height: 100px;">
        <?php } else { ?>
        <!-- Placeholder untuk gambar -->
        <div class="mb-2" style="width: 100px; height: 100px; border: 1px solid #ccc;"></div>
        <?php } ?>
        <span><?= isset($signatureConfirmDetails['Issued Name']) ? ucfirst($signatureConfirmDetails['Issued Name']) : '' ?></span>
      </div>
    </div>

    <?php endif; endif; ?>

    <div class="row justify-content-end mt-5 mb-4">
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

                    <label class="mt-3 custom-file-label" for="approved-signature">
                      <svg xmlns="http://www.w3.org/2000/svg" height="18" viewBox="0 -960 960 960" width="18">
                        <path
                          d="M440-320v-326L336-542l-56-58 200-200 200 200-56 58-104-104v326h-80ZM240-160q-33 0-56.5-23.5T160-240v-120h80v120h480v-120h80v120q0 33-23.5 56.5T720-160H240Z" />
                      </svg>
                      Unggah Signature</label>
                    <input type="file" class="custom-file-input form-control form-control-sm" id="approved-signature"
                      name="approved_signature" accept="image/*">
                    <?php if (!empty($signatureConfirmDetails['Approved Path'])) { ?>
                    <img class="img-fluid m-2" src="<?= base_url($signatureConfirmDetails['Approved Path']) ?>"
                      alt="Preview Signature" style="max-height: 100px;">
                    <?php } ?>

                    <input type="text" class="form-control form-control-sm" id="approved-name" name="approved_name"
                      value="<?= isset($signatureConfirmDetails['Approved Name']) ? ucfirst($signatureConfirmDetails['Approved Name']) : '' ?>">
                  </div>

                  <div class="col-md border p-2">
                    <label class="mb-2" for="checked-name">Mengetahui</label>

                    <label class="mt-3 custom-file-label" for="checked-signature">
                      <svg xmlns="http://www.w3.org/2000/svg" height="18" viewBox="0 -960 960 960" width="18">
                        <path
                          d="M440-320v-326L336-542l-56-58 200-200 200 200-56 58-104-104v326h-80ZM240-160q-33 0-56.5-23.5T160-240v-120h80v120h480v-120h80v120q0 33-23.5 56.5T720-160H240Z" />
                      </svg>
                      Unggah Signature</label>
                    <input type="file" class="custom-file-input form-control form-control-sm" id="checked-signature"
                      name="checked_signature" accept="image/*">
                    <?php if (!empty($signatureConfirmDetails['Checked Path'])) { ?>
                    <img class="img-fluid m-2" src="<?= base_url($signatureConfirmDetails['Checked Path']) ?>"
                      alt="Preview Signature" style="max-height: 100px;">
                    <?php } ?>

                    <input type="text" class="form-control form-control-sm" id="checked-name" name="checked_name"
                      value="<?= isset($signatureConfirmDetails['Checked Name']) ? ucfirst($signatureConfirmDetails['Checked Name']) : '' ?>">
                  </div>

                  <div class="col-md border p-2">
                    <label class="mb-2" for="issued-name">Dibuat</label><br>

                    <label class="mt-3 custom-file-label" for="issued-signature">
                      <svg xmlns="http://www.w3.org/2000/svg" height="18" viewBox="0 -960 960 960" width="18">
                        <path
                          d="M440-320v-326L336-542l-56-58 200-200 200 200-56 58-104-104v326h-80ZM240-160q-33 0-56.5-23.5T160-240v-120h80v120h480v-120h80v120q0 33-23.5 56.5T720-160H240Z" />
                      </svg>
                      Unggah Signature</label>
                    <input type="file" class="custom-file-input form-control form-control-sm" id="issued-signature"
                      name="issued_signature" accept="image/*">
                    <?php if (!empty($signatureConfirmDetails['Issued Path'])) { ?>
                    <img class="img-fluid m-2" src="<?= base_url($signatureConfirmDetails['Issued Path']) ?>"
                      alt="Preview Signature" style="max-height: 100px;">
                    <?php } ?>

                    <input type="text" class="form-control form-control-sm" id="issued-name" name="issued_name"
                      value="<?= isset($signatureConfirmDetails['Issued Name']) ? ucfirst($signatureConfirmDetails['Issued Name']) : '' ?>">
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
        <a href="<?= base_url("pages/delivery-order/$category_param") ?>">
          <button type="button" class="btn btn-secondary btn-lg">Kembali</button>
        </a>
      </div>
    </div>
  </div>
</div>
<?php
require '../../includes/footer.php';
?>