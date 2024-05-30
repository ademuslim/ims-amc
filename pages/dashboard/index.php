<?php
$page_title = "Dashboard";
require_once '../../includes/header.php';

// Jika pengguna bukan super_admin dan staff, arahkan ke halaman akses ditolak
if ($_SESSION['peran_pengguna'] !== 'superadmin' && $_SESSION['peran_pengguna'] !== 'staff') {
  header("Location: " . base_url('pages/access-denied.php'));
  exit();
}

// Ambil bulan dan tahun saat ini
$currentMonth = date('m');
$currentYear = date('Y');

// Mendapatkan bulan lalu
$lastMonth = $currentMonth - 1;
$lastYear = $currentYear;
if ($lastMonth == 0) {
    $lastMonth = 12;
    $lastYear = $currentYear - 1;
}

// INFO PENDAPATAN ////////////////////////////////////////////////////////////////////////////////
// Bangun kondisi untuk mendapatkan id_faktur dari tabel faktur berdasarkan kategori keluar dan bulan saat ini
$conditionsFaktur = "kategori = 'keluar' AND MONTH(tanggal) = ? AND YEAR(tanggal) = ?";
$bind_paramsFaktur = array(
    array('type' => 'i', 'value' => $currentMonth),
    array('type' => 'i', 'value' => $currentYear)
);

// Panggil fungsi selectData untuk mendapatkan id_faktur yang sesuai dengan kondisi
$data_faktur = selectData('faktur', $conditionsFaktur, "", "", $bind_paramsFaktur);

// Inisialisasi variabel subtotal bulan ini
$subtotalThisMonth = 0;

// Iterasi setiap id_faktur yang sesuai dengan kondisi bulan ini
foreach ($data_faktur as $faktur) {
    $id_faktur = $faktur['id_faktur'];

    // Bangun kondisi untuk menghitung subtotal dari tabel detail_faktur berdasarkan id_faktur
    $conditionsDetailFaktur = "id_faktur = ?";
    $bind_paramsDetailFaktur = array(
        array('type' => 'i', 'value' => $id_faktur)
    );

    // Panggil fungsi selectData untuk menghitung subtotal
    $data_faktur_detail = selectData('detail_faktur', $conditionsDetailFaktur, "", "", $bind_paramsDetailFaktur);

    // Hitung subtotal bulan ini dan tambahkan ke variabel subtotal bulan ini
    foreach ($data_faktur_detail as $detail) {
        $subtotalThisMonth += ($detail['jumlah'] * $detail['harga_satuan']);
    }
}

// Bangun kondisi untuk mendapatkan id_faktur dari tabel faktur berdasarkan kategori keluar dan bulan lalu
$conditionsFakturLastMonth = "kategori = 'keluar' AND MONTH(tanggal) = ? AND YEAR(tanggal) = ?";
$bind_paramsFakturLastMonth = array(
    array('type' => 'i', 'value' => $lastMonth),
    array('type' => 'i', 'value' => $lastYear)
);

// Panggil fungsi selectData untuk mendapatkan id_faktur yang sesuai dengan kondisi bulan lalu
$data_faktur_last_month = selectData('faktur', $conditionsFakturLastMonth, "", "", $bind_paramsFakturLastMonth);

// Inisialisasi variabel subtotal bulan lalu
$subtotalLastMonth = 0;

// Iterasi setiap id_faktur yang sesuai dengan kondisi bulan lalu
foreach ($data_faktur_last_month as $faktur_last_month) {
    $id_faktur_last_month = $faktur_last_month['id_faktur'];

    // Bangun kondisi untuk menghitung subtotal dari tabel detail_faktur berdasarkan id_faktur
    $conditionsDetailFakturLastMonth = "id_faktur = ?";
    $bind_paramsDetailFakturLastMonth = array(
        array('type' => 'i', 'value' => $id_faktur_last_month)
    );

    // Panggil fungsi selectData untuk menghitung subtotal
    $data_faktur_detail_last_month = selectData('detail_faktur', $conditionsDetailFakturLastMonth, "", "", $bind_paramsDetailFakturLastMonth);

    // Hitung subtotal bulan lalu dan tambahkan ke variabel subtotal bulan lalu
    foreach ($data_faktur_detail_last_month as $detail_last_month) {
        $subtotalLastMonth += ($detail_last_month['jumlah'] * $detail_last_month['harga_satuan']);
    }
}

// // Tampilkan pendapatan dengan pemisah ribuan atau titik ribuan
// echo "Pendapatan bulan saat ini: Rp " . number_format($subtotalThisMonth, 0, ',', '.') . "<br>";
// echo "Pendapatan bulan lalu: Rp " . number_format($subtotalLastMonth, 0, ',', '.') . "<br>";

// Menghitung perbedaan subtotal bulan ini dan bulan lalu
$difference = $subtotalThisMonth - $subtotalLastMonth;

// Menghitung persentase perubahan pendapatan
if ($subtotalLastMonth != 0) {
    $percentageChange = ($difference / $subtotalLastMonth) * 100;
} else {
    $percentageChange = 0; // Untuk menghindari pembagian dengan nol
}

// INFO DATA PO  ////////////////////////////////////////////////////////////////////////////////
// Hitung total PO masuk tahun ini
$conditionsPO = "kategori = 'masuk' AND YEAR(tanggal) = ?";
$bind_paramsPO = array(
    array('type' => 'i', 'value' => $currentYear)
);

$data_po = selectData('pesanan_pembelian', $conditionsPO, "", "", $bind_paramsPO);

$total_po_incoming_curent_year = count($data_po);

// Hitung total PO masuk tahun ini dengan status draft
$conditionsPO = "kategori = 'masuk' AND YEAR(tanggal) = ? AND status = 'draft'";
$bind_paramsPO = array(
    array('type' => 'i', 'value' => $currentYear)
);

$data_po = selectData('pesanan_pembelian', $conditionsPO, "", "", $bind_paramsPO);
$total_new_po_incoming_curent_year = count($data_po);

// Hitung total PO masuk tahun ini status = selesai
$conditionsPO = "kategori = 'masuk' AND YEAR(tanggal) = ? AND status = 'selesai'";
$bind_paramsPO = array(
    array('type' => 'i', 'value' => $currentYear)
);

$data_po = selectData('pesanan_pembelian', $conditionsPO, "", "", $bind_paramsPO);
$total_close_po_incoming_curent_year = count($data_po);

// Hitung total PO masuk tahun ini dengan status diproses
$conditionsPO = "kategori = 'masuk' AND YEAR(tanggal) = ? AND status = 'diproses'";
$bind_paramsPO = array(
    array('type' => 'i', 'value' => $currentYear)
);

$data_po = selectData('pesanan_pembelian', $conditionsPO, "tanggal DESC", "", $bind_paramsPO);
$total_process_po_incoming_curent_year = count($data_po);

// INFO DATA INVOICE  ////////////////////////////////////////////////////////////////////////////////
// Hitung total Invoice keluar tahun ini
$conditionsInv = "kategori = 'keluar' AND YEAR(tanggal) = ?";
$bind_paramsInv = array(
    array('type' => 'i', 'value' => $currentYear)
);

$data_inv = selectData('faktur', $conditionsInv, "", "", $bind_paramsInv);

$total_inv_outgoing_curent_year = count($data_inv);

// Hitung total Invoice keluar tahun ini dengan status draft
$conditionsInv = "kategori = 'keluar' AND YEAR(tanggal) = ? AND status = 'draft'";
$bind_paramsInv = array(
    array('type' => 'i', 'value' => $currentYear)
);

$data_inv = selectData('faktur', $conditionsInv, "", "", $bind_paramsInv);
$total_draft_inv_outgoing_curent_year = count($data_inv);

// Hitung total Invoice keluar tahun ini status = terkirim
$conditionsInv = "kategori = 'keluar' AND YEAR(tanggal) = ? AND status = 'terkirim'";
$bind_paramsInv = array(
    array('type' => 'i', 'value' => $currentYear)
);

$data_inv = selectData('faktur', $conditionsInv, "", "", $bind_paramsInv);
$total_sending_inv_outgoing_curent_year = count($data_inv);

// Hitung total Invoice keluar tahun ini dengan status dibayar
$conditionsInv = "kategori = 'keluar' AND YEAR(tanggal) = ? AND status = 'dibayar'";
$bind_paramsInv = array(
    array('type' => 'i', 'value' => $currentYear)
);

$data_inv = selectData('faktur', $conditionsInv, "tanggal DESC", "", $bind_paramsInv);
$total_paid_inv_outgoing_curent_year = count($data_inv);

// Hitung total Invoice keluar tahun ini dengan status belum dibayar
$conditionsInv = "kategori = 'keluar' AND YEAR(tanggal) = ? AND status = 'belum dibayar'";
$bind_paramsInv = array(
    array('type' => 'i', 'value' => $currentYear)
);

$data_inv = selectData('faktur', $conditionsInv, "tanggal DESC", "", $bind_paramsInv);
$total_unpaid_inv_outgoing_curent_year = count($data_inv);

// INFO DATA PENAWARAN HARGA  ////////////////////////////////////////////////////////////////////////////////
// Hitung total PH keluar tahun ini
$conditionsPH = "kategori = 'keluar' AND YEAR(tanggal) = ?";
$bind_paramsPH = array(
    array('type' => 'i', 'value' => $currentYear)
);

$data_ph = selectData('penawaran_harga', $conditionsPH, "", "", $bind_paramsPH);

$total_ph_outgoing_curent_year = count($data_ph);

// Hitung total PH keluar tahun ini dengan status draft
$conditionsPH = "kategori = 'keluar' AND YEAR(tanggal) = ? AND status = 'draft'";
$bind_paramsPH = array(
    array('type' => 'i', 'value' => $currentYear)
);

$data_ph = selectData('penawaran_harga', $conditionsPH, "", "", $bind_paramsPH);
$total_draft_ph_outgoing_curent_year = count($data_ph);

// Hitung total PH keluar tahun ini status = terkirim
$conditionsPH = "kategori = 'keluar' AND YEAR(tanggal) = ? AND status = 'terkirim'";
$bind_paramsPH = array(
    array('type' => 'i', 'value' => $currentYear)
);

$data_ph = selectData('penawaran_harga', $conditionsPH, "", "", $bind_paramsPH);
$total_sending_ph_outgoing_curent_year = count($data_ph);

// Hitung total PH keluar tahun ini dengan status disetujui
$conditionsPH = "kategori = 'keluar' AND YEAR(tanggal) = ? AND status = 'disetujui'";
$bind_paramsPH = array(
    array('type' => 'i', 'value' => $currentYear)
);

$data_ph = selectData('penawaran_harga', $conditionsPH, "tanggal DESC", "", $bind_paramsPH);
$total_approved_ph_outgoing_curent_year = count($data_ph);

// Hitung total PH keluar tahun ini dengan status ditolak
$conditionsPH = "kategori = 'keluar' AND YEAR(tanggal) = ? AND status = 'ditolak'";
$bind_paramsPH = array(
    array('type' => 'i', 'value' => $currentYear)
);

$data_ph = selectData('penawaran_harga', $conditionsPH, "tanggal DESC", "", $bind_paramsPH);
$total_rejected_ph_outgoing_curent_year = count($data_ph);


?>

<div class="row mb-4">
  <div class="col">
    <div class="card custom-card d-flex flex-column h-100">
      <div class="card-body">
        <h5 class="card-title">Selamat Datang, <?= ucwords($nama_pengguna); ?></h5>
        <h6 class="card-subtitle">Invoice Management System, PT. MTG</h6>
      </div>
    </div>
  </div>
  <div class="col">
    <div class="card text-bg-light d-flex flex-column h-100">
      <div class="card-body">
        <h5 class="card-title"><?= "Rp " . number_format($subtotalThisMonth, 0, ',', '.') ?></h5>
        <h6 class="card-subtitle">Total Pendapatan</h6>
        <?php
            if ($difference > 0) {
              echo "<span class='fw-bolder text-success'>+ " . number_format(abs($percentageChange), 2) . "% </span><span>Dibandingkan Bulan Lalu</span>";
            } elseif ($difference < 0) {
              echo "<span class='fw-bolder text-danger'>- " . number_format(abs($percentageChange), 2) . "% </span><span'>Dibandingkan Bulan Lalu</span>";
            } else {
              echo "Pendapatan bulan ini sama dengan pendapatan bulan lalu.";
            }
          ?>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <nav class="mb-4">
    <div class="nav nav-underline" id="nav-tab" role="tablist">
      <button class="nav-link active" id="nav-invoice-tab" data-bs-toggle="tab" data-bs-target="#nav-invoice"
        type="button" role="tab" aria-controls="nav-invoice" aria-selected="true">Invoice</button>

      <button class="nav-link" id="nav-po-tab" data-bs-toggle="tab" data-bs-target="#nav-po" type="button" role="tab"
        aria-controls="nav-po" aria-selected="false">Purchase Order</button>

      <button class="nav-link" id="nav-ph-tab" data-bs-toggle="tab" data-bs-target="#nav-ph" type="button" role="tab"
        aria-controls="nav-ph" aria-selected="false">Penawaran Harga</button>

      <!-- <button class="nav-link" id="nav-disabled-tab" data-bs-toggle="tab" data-bs-target="#nav-disabled" type="button"
        role="tab" aria-controls="nav-disabled" aria-selected="false" disabled>Disabled</button> -->
    </div>
  </nav>

  <div class="tab-content" id="nav-tabContent">
    <!-- Data Invoice -->
    <div class="tab-pane fade show active" id="nav-invoice" role="tabpanel" aria-labelledby="nav-invoice-tab"
      tabindex="0">
      <div class="row mb-4">
        <div class="col">
          <div class="card d-flex flex-column h-100">
            <div class="card-body">
              <h6 class="card-title mb-0 d-flex justify-content-between align-items-center">
                Semua Invoice
                <span class="badge text-bg-light fs-5">
                  <?= $total_inv_outgoing_curent_year ?>
                </span>
                <h6 class="card-subtitle"></h6>
            </div>
            <div class="card-footer">
              <a href="<?= base_url('pages/purchase-orders/index.php?category=incoming'); ?>"
                class="card-link link-underline link-underline-opacity-0 <?= setActivePage('pages/purchase-orders/index.php?category=incoming'); ?>">
                Lihat Data
                <svg xmlns="http://www.w3.org/2000/svg" height="16px" viewBox="0 -960 960 960" width="16px"
                  fill="#0d6efd">
                  <path
                    d="m480-320 160-160-160-160-56 56 64 64H320v80h168l-64 64 56 56Zm0 240q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z" />
                </svg>
              </a>
            </div>
          </div>
        </div>

        <div class="col">
          <div class="card d-flex flex-column h-100">
            <div class="card-body">
              <h6 class="card-title mb-0 d-flex justify-content-between align-items-center">
                Draft Invoice
                <span class="badge text-bg-light fs-5">
                  <?= $total_draft_inv_outgoing_curent_year ?>
                </span>
              </h6>
            </div>
            <div class="card-footer">
              <a href="<?= base_url('pages/purchase-orders/index.php?category=incoming'); ?>"
                class="card-link link-underline link-underline-opacity-0 <?= setActivePage('pages/purchase-orders/index.php?category=incoming'); ?>">
                Lihat Data
                <svg xmlns="http://www.w3.org/2000/svg" height="16px" viewBox="0 -960 960 960" width="16px"
                  fill="#0d6efd">
                  <path
                    d="m480-320 160-160-160-160-56 56 64 64H320v80h168l-64 64 56 56Zm0 240q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z" />
                </svg>
              </a>
            </div>
          </div>
        </div>

        <div class="col">
          <div class="card d-flex flex-column h-100">
            <div class="card-body">
              <h6 class="card-title mb-0 d-flex justify-content-between align-items-center">
                Invoice Terkirim
                <span class="badge text-bg-light fs-5">
                  <?= $total_sending_inv_outgoing_curent_year ?>
                </span>
              </h6>
            </div>
            <div class="card-footer">
              <a href="<?= base_url('pages/purchase-orders/index.php?category=incoming'); ?>"
                class="card-link link-underline link-underline-opacity-0 <?= setActivePage('pages/purchase-orders/index.php?category=incoming'); ?>">
                Lihat Data
                <svg xmlns="http://www.w3.org/2000/svg" height="16px" viewBox="0 -960 960 960" width="16px"
                  fill="#0d6efd">
                  <path
                    d="m480-320 160-160-160-160-56 56 64 64H320v80h168l-64 64 56 56Zm0 240q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z" />
                </svg>
              </a>
            </div>
          </div>
        </div>

        <div class="col">
          <div class="card d-flex flex-column h-100">
            <div class="card-body">
              <h6 class="card-title mb-0 d-flex justify-content-between align-items-center">
                Invoice Dibayar
                <span class="badge text-bg-light fs-5">
                  <?= $total_paid_inv_outgoing_curent_year ?>
                </span>
              </h6>
            </div>
            <div class="card-footer">
              <a href="<?= base_url('pages/purchase-orders/index.php?category=incoming'); ?>"
                class="card-link link-underline link-underline-opacity-0 <?= setActivePage('pages/purchase-orders/index.php?category=incoming'); ?>">
                Lihat Data
                <svg xmlns="http://www.w3.org/2000/svg" height="16px" viewBox="0 -960 960 960" width="16px"
                  fill="#0d6efd">
                  <path
                    d="m480-320 160-160-160-160-56 56 64 64H320v80h168l-64 64 56 56Zm0 240q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z" />
                </svg>
              </a>
            </div>
          </div>
        </div>

        <div class="col">
          <div class="card d-flex flex-column h-100">
            <div class="card-body">
              <h6 class="card-title mb-0 d-flex justify-content-between align-items-center">
                Invoice Belum Dibayar
                <span class="badge text-bg-light fs-5">
                  <?= $total_unpaid_inv_outgoing_curent_year ?>
                </span>
              </h6>
            </div>
            <div class="card-footer">
              <a href="<?= base_url('pages/purchase-orders/index.php?category=incoming'); ?>"
                class="card-link link-underline link-underline-opacity-0 <?= setActivePage('pages/purchase-orders/index.php?category=incoming'); ?>">
                Lihat Data
                <svg xmlns="http://www.w3.org/2000/svg" height="16px" viewBox="0 -960 960 960" width="16px"
                  fill="#0d6efd">
                  <path
                    d="m480-320 160-160-160-160-56 56 64 64H320v80h168l-64 64 56 56Zm0 240q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z" />
                </svg>
              </a>
            </div>
          </div>
        </div>
      </div>

      <!-- Detail Invoice Pending -->
      <!-- <div class="row">
        <div class="col">
          <div class="card card-sticky p-0">
            <div class="card-header card-header-sticky">
              Info PO Open
            </div>
            <div class="card-body" style="height:220px; overflow-y:scroll; font-size:.9rem;">
              <table class="table table-bordered">
                <thead class="thead-sticky fw-bolder">
                  <tr>
                    <th>No.</th>
                    <th>No. PO</th>
                    <th>Tanggal</th>
                    <th>Produk</th>
                    <th>Satuan</th>
                    <th>Kuantitas</th>
                    <th>Dikirim</th>
                    <th>Sisa</th>
                  </tr>
                </thead>
                <tbody class="zebra-tbody">
                  <?php if (empty($data_po)) : ?>
                  <tr>
                    <td colspan="8">Tidak ada data PO Open</td>
                  </tr>
                  <?php else : $no = 1; foreach ($data_po as $po) : ?>
                  <?php
            $id_pesanan = $po['id_pesanan'];
            $mainDetailTable = 'detail_pesanan';
            $joinDetailTables = [
                ['pesanan_pembelian', 'detail_pesanan.id_pesanan = pesanan_pembelian.id_pesanan'], 
                ['produk', 'detail_pesanan.id_produk = produk.id_produk']
            ];
            $columns = 'detail_pesanan.*, produk.*';
            $conditions = "detail_pesanan.id_pesanan = '$id_pesanan'";

            // Panggil fungsi selectDataJoin dengan ORDER BY
            $data_detail_po = selectDataJoin($mainDetailTable, $joinDetailTables, $columns, $conditions);
          ?>

                  <?php if (!empty($data_detail_po)): ?>
                  <?php foreach ($data_detail_po as $index => $detail): ?>
                  <tr>
                    <?php if ($index == 0): ?>
                    <td class="text-start" rowspan="<?= count($data_detail_po); ?>"><?= $no; ?></td>
                    <td rowspan="<?= count($data_detail_po); ?>"><?= strtoupper($po['no_pesanan']); ?></td>
                    <td rowspan="<?= count($data_detail_po); ?>"><?= dateID($po['tanggal']); ?></td>
                    <?php endif; ?>
                    <td><?= ucwords($detail['nama_produk']); ?></td>
                    <td><?= strtoupper($detail['satuan']); ?></td>
                    <td class="text-end"><?= number_format($detail['jumlah'], 0, ',', '.'); ?></td>
                    <td class="text-end"><?= number_format($detail['jumlah_dikirim'], 0, ',', '.'); ?></td>
                    <td class="text-end"><?= number_format($detail['sisa_pesanan'], 0, ',', '.'); ?></td>
                  </tr>
                  <?php endforeach; ?>
                  <?php endif; ?>
                  <?php $no++; ?>
                  <?php endforeach; ?>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div> -->
    </div>

    <!-- Data PO -->
    <div class="tab-pane fade" id="nav-po" role="tabpanel" aria-labelledby="nav-po-tab" tabindex="0">
      <div class="row mb-4">
        <div class="col">
          <div class="card d-flex flex-column h-100">
            <div class="card-body">
              <h6 class="card-title mb-0 d-flex justify-content-between align-items-center">
                Semua PO
                <span class="badge text-bg-light fs-5">
                  <?= $total_po_incoming_curent_year ?>
                </span>
                <h6 class="card-subtitle"></h6>
            </div>
            <div class="card-footer">
              <a href="<?= base_url('pages/purchase-orders/index.php?category=incoming'); ?>"
                class="card-link link-underline link-underline-opacity-0 <?= setActivePage('pages/purchase-orders/index.php?category=incoming'); ?>">
                Lihat Data
                <svg xmlns="http://www.w3.org/2000/svg" height="16px" viewBox="0 -960 960 960" width="16px"
                  fill="#0d6efd">
                  <path
                    d="m480-320 160-160-160-160-56 56 64 64H320v80h168l-64 64 56 56Zm0 240q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z" />
                </svg>
              </a>
            </div>
          </div>
        </div>

        <div class="col">
          <div class="card d-flex flex-column h-100">
            <div class="card-body">
              <h6 class="card-title mb-0 d-flex justify-content-between align-items-center">
                PO Baru
                <span class="badge text-bg-light fs-5">
                  <?= $total_new_po_incoming_curent_year ?>
                </span>
              </h6>
            </div>
            <div class="card-footer">
              <a href="<?= base_url('pages/purchase-orders/index.php?category=incoming'); ?>"
                class="card-link link-underline link-underline-opacity-0 <?= setActivePage('pages/purchase-orders/index.php?category=incoming'); ?>">
                Lihat Data
                <svg xmlns="http://www.w3.org/2000/svg" height="16px" viewBox="0 -960 960 960" width="16px"
                  fill="#0d6efd">
                  <path
                    d="m480-320 160-160-160-160-56 56 64 64H320v80h168l-64 64 56 56Zm0 240q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z" />
                </svg>
              </a>
            </div>
          </div>
        </div>

        <div class="col">
          <div class="card d-flex flex-column h-100">
            <div class="card-body">
              <h6 class="card-title mb-0 d-flex justify-content-between align-items-center">
                PO Dikerjakan
                <span class="badge text-bg-light fs-5">
                  <?= $total_process_po_incoming_curent_year ?>
                </span>
              </h6>
            </div>
            <div class="card-footer">
              <a href="<?= base_url('pages/purchase-orders/index.php?category=incoming'); ?>"
                class="card-link link-underline link-underline-opacity-0 <?= setActivePage('pages/purchase-orders/index.php?category=incoming'); ?>">
                Lihat Data
                <svg xmlns="http://www.w3.org/2000/svg" height="16px" viewBox="0 -960 960 960" width="16px"
                  fill="#0d6efd">
                  <path
                    d="m480-320 160-160-160-160-56 56 64 64H320v80h168l-64 64 56 56Zm0 240q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z" />
                </svg>
              </a>
            </div>
          </div>
        </div>

        <div class="col">
          <div class="card d-flex flex-column h-100">
            <div class="card-body">
              <h6 class="card-title mb-0 d-flex justify-content-between align-items-center">
                PO Close
                <span class="badge text-bg-light fs-5">
                  <?= $total_close_po_incoming_curent_year ?>
                </span>
              </h6>
            </div>
            <div class="card-footer">
              <a href="<?= base_url('pages/purchase-orders/index.php?category=incoming'); ?>"
                class="card-link link-underline link-underline-opacity-0 <?= setActivePage('pages/purchase-orders/index.php?category=incoming'); ?>">
                Lihat Data
                <svg xmlns="http://www.w3.org/2000/svg" height="16px" viewBox="0 -960 960 960" width="16px"
                  fill="#0d6efd">
                  <path
                    d="m480-320 160-160-160-160-56 56 64 64H320v80h168l-64 64 56 56Zm0 240q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z" />
                </svg>
              </a>
            </div>
          </div>
        </div>
      </div>

      <!-- Detail PO -->
      <div class="row">
        <div class="col">
          <div class="card card-sticky p-0">
            <div class="card-header card-header-sticky">
              Info PO Open
            </div>
            <div class="card-body" style="height:220px; overflow-y:scroll; font-size:.9rem;">
              <table class="table table-bordered">
                <thead class="thead-sticky fw-bolder">
                  <tr>
                    <th>No.</th>
                    <th>No. PO</th>
                    <th>Tanggal</th>
                    <th>Produk</th>
                    <th>Satuan</th>
                    <th>Kuantitas</th>
                    <th>Dikirim</th>
                    <th>Sisa</th>
                  </tr>
                </thead>
                <tbody class="zebra-tbody">
                  <?php if (empty($data_po)) : ?>
                  <tr>
                    <td colspan="8">Tidak ada data PO Open</td>
                  </tr>
                  <?php else : $no = 1; foreach ($data_po as $po) : ?>
                  <?php
            $id_pesanan = $po['id_pesanan'];
            $mainDetailTable = 'detail_pesanan';
            $joinDetailTables = [
                ['pesanan_pembelian', 'detail_pesanan.id_pesanan = pesanan_pembelian.id_pesanan'], 
                ['produk', 'detail_pesanan.id_produk = produk.id_produk']
            ];
            $columns = 'detail_pesanan.*, produk.*';
            $conditions = "detail_pesanan.id_pesanan = '$id_pesanan'";

            // Panggil fungsi selectDataJoin dengan ORDER BY
            $data_detail_po = selectDataJoin($mainDetailTable, $joinDetailTables, $columns, $conditions);
          ?>

                  <?php if (!empty($data_detail_po)): ?>
                  <?php foreach ($data_detail_po as $index => $detail): ?>
                  <tr>
                    <?php if ($index == 0): ?>
                    <td class="text-start" rowspan="<?= count($data_detail_po); ?>"><?= $no; ?></td>
                    <td rowspan="<?= count($data_detail_po); ?>"><?= strtoupper($po['no_pesanan']); ?></td>
                    <td rowspan="<?= count($data_detail_po); ?>"><?= dateID($po['tanggal']); ?></td>
                    <?php endif; ?>
                    <td><?= ucwords($detail['nama_produk']); ?></td>
                    <td><?= strtoupper($detail['satuan']); ?></td>
                    <td class="text-end"><?= number_format($detail['jumlah'], 0, ',', '.'); ?></td>
                    <td class="text-end"><?= number_format($detail['jumlah_dikirim'], 0, ',', '.'); ?></td>
                    <td class="text-end"><?= number_format($detail['sisa_pesanan'], 0, ',', '.'); ?></td>
                  </tr>
                  <?php endforeach; ?>
                  <?php endif; ?>
                  <?php $no++; ?>
                  <?php endforeach; ?>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Data Penawaran Harga -->
    <div class="tab-pane fade" id="nav-ph" role="tabpanel" aria-labelledby="nav-ph-tab" tabindex="0">
      <div class="row mb-4">
        <div class="col">
          <div class="card d-flex flex-column h-100">
            <div class="card-body">
              <h6 class="card-title mb-0 d-flex justify-content-between align-items-center">
                Semua Penawaran Harga
                <span class="badge text-bg-light fs-5">
                  <?= $total_ph_outgoing_curent_year ?>
                </span>
                <h6 class="card-subtitle"></h6>
            </div>
            <div class="card-footer">
              <a href="<?= base_url('pages/purchase-orders/index.php?category=incoming'); ?>"
                class="card-link link-underline link-underline-opacity-0 <?= setActivePage('pages/purchase-orders/index.php?category=incoming'); ?>">
                Lihat Data
                <svg xmlns="http://www.w3.org/2000/svg" height="16px" viewBox="0 -960 960 960" width="16px"
                  fill="#0d6efd">
                  <path
                    d="m480-320 160-160-160-160-56 56 64 64H320v80h168l-64 64 56 56Zm0 240q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z" />
                </svg>
              </a>
            </div>
          </div>
        </div>

        <div class="col">
          <div class="card d-flex flex-column h-100">
            <div class="card-body">
              <h6 class="card-title mb-0 d-flex justify-content-between align-items-center">
                Draft Penawaran Harga
                <span class="badge text-bg-light fs-5">
                  <?= $total_draft_ph_outgoing_curent_year ?>
                </span>
              </h6>
            </div>
            <div class="card-footer">
              <a href="<?= base_url('pages/purchase-orders/index.php?category=incoming'); ?>"
                class="card-link link-underline link-underline-opacity-0 <?= setActivePage('pages/purchase-orders/index.php?category=incoming'); ?>">
                Lihat Data
                <svg xmlns="http://www.w3.org/2000/svg" height="16px" viewBox="0 -960 960 960" width="16px"
                  fill="#0d6efd">
                  <path
                    d="m480-320 160-160-160-160-56 56 64 64H320v80h168l-64 64 56 56Zm0 240q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z" />
                </svg>
              </a>
            </div>
          </div>
        </div>

        <div class="col">
          <div class="card d-flex flex-column h-100">
            <div class="card-body">
              <h6 class="card-title mb-0 d-flex justify-content-between align-items-center">
                Penawaran Harga Disetujui
                <span class="badge text-bg-light fs-5">
                  <?= $total_approved_ph_outgoing_curent_year ?>
                </span>
              </h6>
            </div>
            <div class="card-footer">
              <a href="<?= base_url('pages/purchase-orders/index.php?category=incoming'); ?>"
                class="card-link link-underline link-underline-opacity-0 <?= setActivePage('pages/purchase-orders/index.php?category=incoming'); ?>">
                Lihat Data
                <svg xmlns="http://www.w3.org/2000/svg" height="16px" viewBox="0 -960 960 960" width="16px"
                  fill="#0d6efd">
                  <path
                    d="m480-320 160-160-160-160-56 56 64 64H320v80h168l-64 64 56 56Zm0 240q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z" />
                </svg>
              </a>
            </div>
          </div>
        </div>

        <div class="col">
          <div class="card d-flex flex-column h-100">
            <div class="card-body">
              <h6 class="card-title mb-0 d-flex justify-content-between align-items-center">
                Penawaran Harga Ditolak
                <span class="badge text-bg-light fs-5">
                  <?= $total_rejected_ph_outgoing_curent_year ?>
                </span>
              </h6>
            </div>
            <div class="card-footer">
              <a href="<?= base_url('pages/purchase-orders/index.php?category=incoming'); ?>"
                class="card-link link-underline link-underline-opacity-0 <?= setActivePage('pages/purchase-orders/index.php?category=incoming'); ?>">
                Lihat Data
                <svg xmlns="http://www.w3.org/2000/svg" height="16px" viewBox="0 -960 960 960" width="16px"
                  fill="#0d6efd">
                  <path
                    d="m480-320 160-160-160-160-56 56 64 64H320v80h168l-64 64 56 56Zm0 240q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z" />
                </svg>
              </a>
            </div>
          </div>
        </div>
      </div>

      <!-- Detail PH -->
      <!-- <div class="row">
        <div class="col">
          <div class="card card-sticky p-0">
            <div class="card-header card-header-sticky">
              Info PO Open
            </div>
            <div class="card-body" style="height:220px; overflow-y:scroll; font-size:.9rem;">
              <table class="table table-bordered">
                <thead class="thead-sticky fw-bolder">
                  <tr>
                    <th>No.</th>
                    <th>No. PO</th>
                    <th>Tanggal</th>
                    <th>Produk</th>
                    <th>Satuan</th>
                    <th>Kuantitas</th>
                    <th>Dikirim</th>
                    <th>Sisa</th>
                  </tr>
                </thead>
                <tbody class="zebra-tbody">
                  <?php if (empty($data_po)) : ?>
                  <tr>
                    <td colspan="8">Tidak ada data PO Open</td>
                  </tr>
                  <?php else : $no = 1; foreach ($data_po as $po) : ?>
                  <?php
            $id_pesanan = $po['id_pesanan'];
            $mainDetailTable = 'detail_pesanan';
            $joinDetailTables = [
                ['pesanan_pembelian', 'detail_pesanan.id_pesanan = pesanan_pembelian.id_pesanan'], 
                ['produk', 'detail_pesanan.id_produk = produk.id_produk']
            ];
            $columns = 'detail_pesanan.*, produk.*';
            $conditions = "detail_pesanan.id_pesanan = '$id_pesanan'";

            // Panggil fungsi selectDataJoin dengan ORDER BY
            $data_detail_po = selectDataJoin($mainDetailTable, $joinDetailTables, $columns, $conditions);
          ?>

                  <?php if (!empty($data_detail_po)): ?>
                  <?php foreach ($data_detail_po as $index => $detail): ?>
                  <tr>
                    <?php if ($index == 0): ?>
                    <td class="text-start" rowspan="<?= count($data_detail_po); ?>"><?= $no; ?></td>
                    <td rowspan="<?= count($data_detail_po); ?>"><?= strtoupper($po['no_pesanan']); ?></td>
                    <td rowspan="<?= count($data_detail_po); ?>"><?= dateID($po['tanggal']); ?></td>
                    <?php endif; ?>
                    <td><?= ucwords($detail['nama_produk']); ?></td>
                    <td><?= strtoupper($detail['satuan']); ?></td>
                    <td class="text-end"><?= number_format($detail['jumlah'], 0, ',', '.'); ?></td>
                    <td class="text-end"><?= number_format($detail['jumlah_dikirim'], 0, ',', '.'); ?></td>
                    <td class="text-end"><?= number_format($detail['sisa_pesanan'], 0, ',', '.'); ?></td>
                  </tr>
                  <?php endforeach; ?>
                  <?php endif; ?>
                  <?php $no++; ?>
                  <?php endforeach; ?>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div> -->
    </div>
    <!-- <div class="tab-pane fade" id="nav-disabled" role="tabpanel" aria-labelledby="nav-disabled-tab" tabindex="0">...
    </div> -->
  </div>
</div>





<hr>

<!-- Ringkasan Data -->
<!-- <div class="summary">
  <div class="card text-white bg-primary mb-3">
    <div class="card-body">
      <h3 class="card-title">Total Invoice</h3>
      <p class="card-text"><?php echo $total_invoice; ?></p>
    </div>
  </div>
  <div class="card text-white bg-success mb-3">
    <div class="card-body">
      <h3 class="card-title">Total Penawaran Harga</h3>
      <p class="card-text"><?php echo $total_penawaran; ?></p>
    </div>
  </div>
  <div class="card text-white bg-danger mb-3">
    <div class="card-body">
      <h3 class="card-title">Total Purchase Order Tahun Ini</h3>
      <p class="card-text"><?php echo $total_po; ?></p>
    </div>
  </div>
  <div class="card text-white bg-warning mb-3">
    <div class="card-body">
      <h3 class="card-title">Total Produk</h3>
      <p class="card-text"><?php echo $total_produk; ?></p>
    </div>
  </div>
  <div class="card text-white bg-info mb-3">
    <div class="card-body">
      <h3 class="card-title">Total Kontak</h3>
      <p class="card-text"><?php echo $total_kontak; ?></p>
    </div>
  </div>
</div> -->

<!-- Aktivitas Terbaru -->
<!-- <div class="latest-activities">
  <h3>Invoice Terbaru</h3>
  <ul class="list-group" id="latest-invoice">
    <?php foreach ($latest_invoices as $invoice): ?>
    <li class="list-group-item"><?php echo $invoice['no_faktur']; ?> - <?php echo $invoice['tanggal']; ?></li>
    <?php endforeach; ?>
  </ul>

  <h3>Penawaran Harga Terbaru</h3>
  <ul class="list-group" id="latest-penawaran">
    <?php foreach ($latest_penawaran as $penawaran): ?>
    <li class="list-group-item"><?php echo $penawaran['no_penawaran']; ?> - <?php echo $penawaran['tanggal']; ?></li>
    <?php endforeach; ?>
  </ul>

  <h3>Purchase Order Terbaru</h3>
  <ul class="list-group" id="latest-po">
    <?php foreach ($latest_po as $po): ?>
    <li class="list-group-item"><?php echo $po['no_pesanan']; ?> - <?php echo $po['tanggal']; ?></li>
    <?php endforeach; ?>
  </ul>
</div> -->

<script>
document.addEventListener('DOMContentLoaded', (event) => {
  // Data for Invoice Chart
  const invoiceChartCtx = document.getElementById('invoice-chart').getContext('2d');
  const invoiceChart = new Chart(invoiceChartCtx, {
    type: 'line',
    data: {
      labels: ['January', 'February', 'March', 'April', 'May', 'June'],
      datasets: [{
        label: 'Invoice per Month',
        data: [10, 20, 30, 40, 50, 60],
        backgroundColor: 'rgba(54, 162, 235, 0.2)',
        borderColor: 'rgba(54, 162, 235, 1)',
        borderWidth: 1
      }]
    },
    options: {
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });

  // Data for PO Chart
  const poChartCtx = document.getElementById('po-chart').getContext('2d');
  const poChart = new Chart(poChartCtx, {
    type: 'bar',
    data: {
      labels: ['January', 'February', 'March', 'April', 'May', 'June'],
      datasets: [{
        label: 'PO per Month',
        data: [15, 25, 35, 45, 55, 65],
        backgroundColor: 'rgba(255, 99, 132, 0.2)',
        borderColor: 'rgba(255, 99, 132, 1)',
        borderWidth: 1
      }]
    },
    options: {
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });
});
</script>

<?php require '../../includes/footer.php'; ?>