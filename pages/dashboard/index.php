<?php
$page_title = "Dashboard";
require_once '../../includes/header.php';

if ($_SESSION['peran_pengguna'] !== 'superadmin' && $_SESSION['peran_pengguna'] !== 'staff' && $_SESSION['peran_pengguna'] !== 'kepala_perusahaan') {
  header("Location: " . base_url('pages/access-denied.php'));
  exit();
}

// Bulan dan tahun saat ini
$current_month = date('m');
$current_year = date('Y');

// Bulan lalu dan tahun lalu
$last_month = $current_month - 1;
$last_year = $current_year;
if ($last_month == 0) {
    $last_month = 12;
    $last_year = $current_year - 1;
}

// INFO PENDAPATAN
include 'incomeInfo.php';

// INFO DATA PO
include 'purchaseOrderInfo.php';

// INFO DATA INVOICE
include 'invoiceInfo.php';

// INFO DATA PENAWARAN HARGA
include 'quotationInfo.php';
?>

<div class="row mb-4">
  <!-- Greeting -->
  <div class="col">
    <div class="card custom-card d-flex flex-column h-100">
      <div class="card-body">
        <h5 class="card-title">Selamat Datang, <?= ucwords($nama_lengkap); ?></h5>
        <h6 class="card-subtitle">Invoice Management System, PT. MTG</h6>
      </div>
    </div>
  </div>

  <!-- Status Pendapatan -->
  <div class="col">
    <div class="card text-bg-light d-flex flex-column h-100">
      <div class="card-body">
        <h5 class="card-subtitle"><?= "Rp " . number_format($income_info['subtotal_this_month'], 0, ',', '.') ?></h5>
        <h6 class="card-title">Total Pendapatan</h6>
        <?php
            if ($difference > 0) {
              echo "<span class='fw-bolder text-success'>+ " . number_format(abs($income_info['percentage_change']), 2) . "% </span><span style='font-size: .9rem;'>Dibandingkan Bulan Lalu</span>";
            } elseif ($subtotal_this_month == 0) {
              echo "Belum ada pendapatan bulan ini.";
            } elseif ($difference < 0) {
              echo "<span class='fw-bolder text-danger'>- " . number_format(abs($income_info['percentage_change']), 2) . "% </span><span style='font-size: .9rem;'>Dibandingkan Bulan Lalu</span>";
            }
          ?>
      </div>
    </div>
  </div>
</div>

<div class="row justify-content-between">
  <!-- Tab Info -->
  <nav class="mb-4 col-auto">
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
  <div class="col-auto fw-bolder"><?= dateID(date('Y-m-d')) ?></div>
</div>

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
                <?= $invoice_info['total_inv_outgoing_curent_year'] ?>
              </span>
            </h6>
          </div>
          <div class="card-footer">
            <a href="<?= base_url('pages/invoices/index.php?category=outgoing'); ?>"
              class="card-link link-underline link-underline-opacity-0 <?= setActivePage('pages/invoices/index.php?category=outgoing'); ?>">
              Lihat Data
              <svg xmlns="http://www.w3.org/2000/svg" height="16px" viewBox="0 -960 960 960" width="16px"
                fill="#0077b6">
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
              Invoice Tunggu Kirim
              <span class="badge text-bg-light fs-5">
                <?= $invoice_info['total_waiting_inv_outgoing_curent_year'] ?>
              </span>
            </h6>
          </div>
          <div class="card-footer">
            <a href="<?= base_url('pages/invoices/index.php?category=outgoing'); ?>"
              class="card-link link-underline link-underline-opacity-0 <?= setActivePage('pages/invoices/index.php?category=outgoing'); ?>">
              Lihat Data
              <svg xmlns="http://www.w3.org/2000/svg" height="16px" viewBox="0 -960 960 960" width="16px"
                fill="#0077b6">
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
                <?= $invoice_info['total_sending_inv_outgoing_curent_year'] ?>
              </span>
            </h6>
          </div>
          <div class="card-footer">
            <a href="<?= base_url('pages/invoices/index.php?category=outgoing'); ?>"
              class="card-link link-underline link-underline-opacity-0 <?= setActivePage('pages/invoices/index.php?category=outgoing'); ?>">
              Lihat Data
              <svg xmlns="http://www.w3.org/2000/svg" height="16px" viewBox="0 -960 960 960" width="16px"
                fill="#0077b6">
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
                <?= $invoice_info['total_paid_inv_outgoing_curent_year'] ?>
              </span>
            </h6>
          </div>
          <div class="card-footer">
            <a href="<?= base_url('pages/invoices/index.php?category=outgoing'); ?>"
              class="card-link link-underline link-underline-opacity-0 <?= setActivePage('pages/invoices/index.php?category=outgoing'); ?>">
              Lihat Data
              <svg xmlns="http://www.w3.org/2000/svg" height="16px" viewBox="0 -960 960 960" width="16px"
                fill="#0077b6">
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
                <?= $invoice_info['total_unpaid_inv_outgoing_curent_year'] ?>
              </span>
            </h6>
          </div>
          <div class="card-footer">
            <a href="<?= base_url('pages/invoices/index.php?category=outgoing'); ?>"
              class="card-link link-underline link-underline-opacity-0 <?= setActivePage('pages/invoices/index.php?category=outgoing'); ?>">
              Lihat Data
              <svg xmlns="http://www.w3.org/2000/svg" height="16px" viewBox="0 -960 960 960" width="16px"
                fill="#0077b6">
                <path
                  d="m480-320 160-160-160-160-56 56 64 64H320v80h168l-64 64 56 56Zm0 240q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z" />
              </svg>
            </a>
          </div>
        </div>
      </div>
    </div>

    <!-- Detail Invoice -->
    <div class="row">
      <div class="col">
        <div class="card card-sticky p-0">
          <div class="card-header card-header-sticky">
            Status Invoice
          </div>
          <div class="card-body" style="height:400px; overflow-y:scroll; font-size:.9rem;">
            <table class="table table-bordered">
              <thead class="thead-sticky fw-bolder">
                <tr>
                  <th>No.</th>
                  <th>No. Invoice</th>
                  <th>Tanggal</th>
                  <th>Status</th>
                  <th>Total</th>
                  <th>Produk</th>
                  <th>Satuan</th>
                  <th>Kuantitas</th>
                </tr>
              </thead>
              <tbody class="zebra-tbody">
                <?php if (empty($data_inv)) : ?>
                <tr>
                  <td colspan="8">Tidak ada data Invoice</td>
                </tr>
                <?php else : $no = 1; foreach ($data_inv as $inv) : ?>
                <?php
                  $id_faktur = $inv['id_faktur'];
                  $mainDetailTable = 'detail_faktur';
                  $joinDetailTables = [
                      ['faktur', 'detail_faktur.id_faktur = faktur.id_faktur'], 
                      ['produk', 'detail_faktur.id_produk = produk.id_produk']
                  ];
                  $columns = 'detail_faktur.*, produk.*';
                  $conditions = "detail_faktur.id_faktur = '$id_faktur'";

                  // Panggil fungsi selectDataJoin dengan ORDER BY
                  $data_detail_inv = selectDataJoin($mainDetailTable, $joinDetailTables, $columns, $conditions);
                ?>

                <?php if (!empty($data_detail_inv)): ?>
                <?php foreach ($data_detail_inv as $index => $detail): ?>
                <tr>
                  <?php if ($index == 0): ?>
                  <td class="text-start" rowspan="<?= count($data_detail_inv); ?>"><?= $no; ?></td>
                  <td class="text-primary" rowspan="<?= count($data_detail_inv); ?>">
                    <?= strtoupper($inv['no_faktur']); ?></td>
                  <td rowspan="<?= count($data_detail_inv); ?>"><?= dateID($inv['tanggal']); ?></td>
                  <td rowspan="<?= count($data_detail_inv); ?>">
                    <?php
                      // Tentukan kelas bootstrap berdasarkan nilai status
                      $status_class = '';
                      if ($inv['status'] == 'draft') {
                          $status_class = 'text-bg-warning';
                      } elseif ($inv['status'] == 'belum dibayar') {
                          $status_class = 'text-bg-info';
                      } elseif ($inv['status'] == 'dibayar') {
                          $status_class = 'text-bg-success';
                      }
                      ?>
                    <span class="badge <?= $status_class ?>"><?= strtoupper($inv['status']) ?></span>
                  </td>
                  <td rowspan="<?= count($data_detail_inv); ?>"><?= formatRupiah($inv['total']); ?></td>
                  <?php endif; ?>
                  <td><?= ucwords($detail['nama_produk']); ?></td>
                  <td><?= strtoupper($detail['satuan']); ?></td>
                  <td class="text-end"><?= number_format($detail['jumlah'], 0, ',', '.'); ?></td>
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

    <!-- Grafik Pendapatan Bulanan -->
    <div class="row mb-4">
      <div class="col">
        <canvas id="revenueChart" width="400" height="200"></canvas>
      </div>
    </div>
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
                <?= $po_info['total_po_incoming_curent_year'] ?>
              </span>
              <h6 class="card-subtitle"></h6>
          </div>
          <div class="card-footer">
            <a href="<?= base_url('pages/purchase-orders/index.php?category=incoming'); ?>"
              class="card-link link-underline link-underline-opacity-0 <?= setActivePage('pages/purchase-orders/index.php?category=incoming'); ?>">
              Lihat Data
              <svg xmlns="http://www.w3.org/2000/svg" height="16px" viewBox="0 -960 960 960" width="16px"
                fill="#0077b6">
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
                <?= $po_info['total_new_po_incoming_curent_year'] ?>
              </span>
            </h6>
          </div>
          <div class="card-footer">
            <a href="<?= base_url('pages/purchase-orders/index.php?category=incoming'); ?>"
              class="card-link link-underline link-underline-opacity-0 <?= setActivePage('pages/purchase-orders/index.php?category=incoming'); ?>">
              Lihat Data
              <svg xmlns="http://www.w3.org/2000/svg" height="16px" viewBox="0 -960 960 960" width="16px"
                fill="#0077b6">
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
                <?= $po_info['total_process_po_incoming_curent_year'] ?>
              </span>
            </h6>
          </div>
          <div class="card-footer">
            <a href="<?= base_url('pages/purchase-orders/index.php?category=incoming'); ?>"
              class="card-link link-underline link-underline-opacity-0 <?= setActivePage('pages/purchase-orders/index.php?category=incoming'); ?>">
              Lihat Data
              <svg xmlns="http://www.w3.org/2000/svg" height="16px" viewBox="0 -960 960 960" width="16px"
                fill="#0077b6">
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
                <?= $po_info['total_close_po_incoming_curent_year'] ?>
              </span>
            </h6>
          </div>
          <div class="card-footer">
            <a href="<?= base_url('pages/purchase-orders/index.php?category=incoming'); ?>"
              class="card-link link-underline link-underline-opacity-0 <?= setActivePage('pages/purchase-orders/index.php?category=incoming'); ?>">
              Lihat Data
              <svg xmlns="http://www.w3.org/2000/svg" height="16px" viewBox="0 -960 960 960" width="16px"
                fill="#0077b6">
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
          <div class="card-body" style="height:400px;overflow-y:scroll; font-size:.9rem;">
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
                  <td class="text-primary" rowspan="<?= count($data_detail_po); ?>">
                    <?= strtoupper($po['no_pesanan']); ?></td>
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

    <!-- Grafik Pendapatan Bulanan -->
    <div class="row mb-4">
      <div class="col">
        <canvas id="sisaPesananChart" width="400" height="200"></canvas>
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
                <?= $ph_info['total_ph_outgoing_curent_year'] ?>
              </span>
              <h6 class="card-subtitle"></h6>
          </div>
          <div class="card-footer">
            <a href="<?= base_url('pages/quotation/index.php?category=outgoing'); ?>"
              class="card-link link-underline link-underline-opacity-0 <?= setActivePage('pages/quotation/index.php?category=outgoing'); ?>">
              Lihat Data
              <svg xmlns="http://www.w3.org/2000/svg" height="16px" viewBox="0 -960 960 960" width="16px"
                fill="#0077b6">
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
                <?= $ph_info['total_draft_ph_outgoing_curent_year'] ?>
              </span>
            </h6>
          </div>
          <div class="card-footer">
            <a href="<?= base_url('pages/quotation/index.php?category=outgoing'); ?>"
              class="card-link link-underline link-underline-opacity-0 <?= setActivePage('pages/quotation/index.php?category=outgoing'); ?>">
              Lihat Data
              <svg xmlns="http://www.w3.org/2000/svg" height="16px" viewBox="0 -960 960 960" width="16px"
                fill="#0077b6">
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
                <?= $ph_info['total_approved_ph_outgoing_curent_year'] ?>
              </span>
            </h6>
          </div>
          <div class="card-footer">
            <a href="<?= base_url('pages/quotation/index.php?category=outgoing'); ?>"
              class="card-link link-underline link-underline-opacity-0 <?= setActivePage('pages/quotation/index.php?category=outgoing'); ?>">
              Lihat Data
              <svg xmlns="http://www.w3.org/2000/svg" height="16px" viewBox="0 -960 960 960" width="16px"
                fill="#0077b6">
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
                <?= $ph_info['total_rejected_ph_outgoing_curent_year'] ?>
              </span>
            </h6>
          </div>
          <div class="card-footer">
            <a href="<?= base_url('pages/quotation/index.php?category=outgoing'); ?>"
              class="card-link link-underline link-underline-opacity-0 <?= setActivePage('pages/quotation/index.php?category=outgoing'); ?>">
              Lihat Data
              <svg xmlns="http://www.w3.org/2000/svg" height="16px" viewBox="0 -960 960 960" width="16px"
                fill="#0077b6">
                <path
                  d="m480-320 160-160-160-160-56 56 64 64H320v80h168l-64 64 56 56Zm0 240q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z" />
              </svg>
            </a>
          </div>
        </div>
      </div>
    </div>

    <!-- Detail PH -->
    <div class="row">
      <div class="col">
        <div class="card card-sticky p-0">
          <div class="card-header card-header-sticky">
            Status Penawaran Harga
          </div>
          <div class="card-body" style="height:400px;overflow-y:scroll; font-size:.9rem;">
            <table class="table table-bordered">
              <thead class="thead-sticky fw-bolder">
                <tr>
                  <th>No.</th>
                  <th>No. Penawaran</th>
                  <th>Tanggal</th>
                  <th>Status</th>
                  <th>Produk</th>
                  <th>Satuan</th>
                  <th>Kuantitas</th>
                  <th>Harga Satuan</th>
                </tr>
              </thead>
              <tbody class="zebra-tbody">
                <?php if (empty($data_ph)) : ?>
                <tr>
                  <td colspan="8">Tidak ada data Penawaran Harga</td>
                </tr>
                <?php else : $no = 1; foreach ($data_ph as $ph) : ?>
                <?php
                  $id_ph = $ph['id_penawaran'];
                  $mainDetailTable = 'detail_penawaran';
                  $joinDetailTables = [
                      ['penawaran_harga', 'detail_penawaran.id_penawaran = penawaran_harga.id_penawaran'], 
                      ['produk', 'detail_penawaran.id_produk = produk.id_produk']
                  ];
                  $columns = 'detail_penawaran.*, produk.*';
                  $conditions = "detail_penawaran.id_penawaran = '$id_ph'";

                  // Panggil fungsi selectDataJoin dengan ORDER BY
                  $data_detail_ph = selectDataJoin($mainDetailTable, $joinDetailTables, $columns, $conditions);
                ?>

                <?php if (!empty($data_detail_ph)): ?>
                <?php foreach ($data_detail_ph as $index => $detail): ?>
                <tr>
                  <?php if ($index == 0): ?>
                  <td class="text-start" rowspan="<?= count($data_detail_ph); ?>"><?= $no; ?></td>
                  <td class="text-primary" rowspan="<?= count($data_detail_ph); ?>">
                    <?= strtoupper($ph['no_penawaran']); ?></td>
                  <td rowspan="<?= count($data_detail_ph); ?>"><?= dateID($ph['tanggal']); ?></td>
                  <td rowspan="<?= count($data_detail_ph); ?>">
                    <?php
                      // Tentukan kelas bootstrap berdasarkan nilai status
                      $status_class = '';
                      if ($ph['status'] == 'draft') {
                          $status_class = 'text-bg-warning';
                      } elseif ($ph['status'] == 'ditolak') {
                          $status_class = 'text-bg-info';
                      } elseif ($ph['status'] == 'disetujui') {
                          $status_class = 'text-bg-success';
                      }
                      ?>
                    <span class="badge <?= $status_class ?>"><?= strtoupper($ph['status']) ?></span>
                  </td>
                  <?php endif; ?>
                  <td><?= ucwords($detail['nama_produk']); ?></td>
                  <td><?= strtoupper($detail['satuan']); ?></td>
                  <td class="text-end"><?= number_format($detail['jumlah'], 0, ',', '.'); ?></td>
                  <td><?= formatRupiah($detail['harga_satuan']); ?></td>
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
  <!-- <div class="tab-pane fade" id="nav-disabled" role="tabpanel" aria-labelledby="nav-disabled-tab" tabindex="0">...
    </div> -->
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
  // Data untuk grafik
  var data = {
    labels: ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober",
      "November", "Desember"
    ],
    datasets: [{
      label: 'Pendapatan Sebelum PPN',
      data: [
        <?= implode(',', $invoice_info['monthly_revenue']); ?>
      ],
      backgroundColor: 'rgba(54, 162, 235, 0.2)',
      borderColor: 'rgba(54, 162, 235, 1)',
      borderWidth: 1
    }]
  };

  // Konfigurasi untuk grafik
  var config = {
    type: 'bar',
    data: data,
    options: {
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  };

  // Render grafik
  var ctx = document.getElementById('revenueChart').getContext('2d');
  var revenueChart = new Chart(ctx, config);
});

document.addEventListener("DOMContentLoaded", function() {
  var ctx = document.getElementById('sisaPesananChart').getContext('2d');
  var sisaPesananChart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: <?= json_encode($po_info['labels']); ?>,
      datasets: [{
        label: 'Sisa Pesanan',
        data: <?= json_encode($po_info['sisa_pesanan_data']); ?>,
        backgroundColor: 'rgba(75, 192, 192, 0.2)',
        borderColor: 'rgba(75, 192, 192, 1)',
        borderWidth: 1
      }]
    },
    options: {
      indexAxis: 'y',
      scales: {
        x: {
          beginAtZero: true
        }
      }
    }
  });
});
</script>

<?php require '../../includes/footer.php'; ?>