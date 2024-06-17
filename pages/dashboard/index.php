<?php
$page_title = "Dashboard";
require_once '../../includes/header.php';

// Jika pengguna bukan super_admin dan staff, arahkan ke halaman akses ditolak
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

// INFO PENDAPATAN ////////////////////////////////////////////////////////////////////////////////
// Bangun kondisi untuk mendapatkan id_faktur dari tabel faktur berdasarkan kategori keluar, tanggal bulan saat ini, dan status dibayar
$conditions_faktur_this_month = "kategori = 'keluar' AND MONTH(tanggal) = ? AND YEAR(tanggal) = ? AND status = 'dibayar'";
$bind_params_faktur_this_month = array(
    array('type' => 'i', 'value' => $current_month),
    array('type' => 'i', 'value' => $current_year)
);

// Panggil fungsi selectData untuk mendapatkan id_faktur yang sesuai dengan kondisi
$data_faktur_this_month = selectData('faktur', $conditions_faktur_this_month, "", "", $bind_params_faktur_this_month);

// Iterasi setiap id_faktur yang sesuai dengan kondisi bulan ini
$subtotal_this_month = 0;
foreach ($data_faktur_this_month as $faktur_this_month) {
    $id_faktur = $faktur_this_month['id_faktur'];

    // Bangun kondisi untuk menghitung subtotal dari tabel detail_faktur berdasarkan id_faktur
    $conditions_detail_faktur_this_month = "id_faktur = ?";
    $bind_params_detail_faktur_this_month = array(
        array('type' => 'i', 'value' => $id_faktur)
    );

    // Panggil fungsi selectData untuk menghitung subtotal
    $data_faktur_detail_this_month = selectData('detail_faktur', $conditions_detail_faktur_this_month, "", "", $bind_params_detail_faktur_this_month);

    // Hitung subtotal bulan ini dan tambahkan ke variabel subtotal bulan ini
    foreach ($data_faktur_detail_this_month as $detail_faktur_this_month) {
        $subtotal_this_month += ($detail_faktur_this_month['jumlah'] * $detail_faktur_this_month['harga_satuan']);
    }
}

// Bangun kondisi untuk mendapatkan id_faktur dari tabel faktur berdasarkan kategori keluar, tanggal bulan lalu, dan status dibayar
$conditions_faktur_last_month = "kategori = 'keluar' AND MONTH(tanggal) = ? AND YEAR(tanggal) = ? AND status = 'dibayar'";
$bind_params_faktur_last_month = array(
    array('type' => 'i', 'value' => $last_month),
    array('type' => 'i', 'value' => $last_year)
);

// Panggil fungsi selectData untuk mendapatkan id_faktur yang sesuai dengan kondisi bulan lalu
$data_faktur_last_month = selectData('faktur', $conditions_faktur_last_month, "", "", $bind_params_faktur_last_month);

// Iterasi setiap id_faktur yang sesuai dengan kondisi bulan lalu
$subtotal_last_month = 0;
foreach ($data_faktur_last_month as $faktur_last_month) {
    $id_faktur_last_month = $faktur_last_month['id_faktur'];

    // Bangun kondisi untuk menghitung subtotal dari tabel detail_faktur berdasarkan id_faktur
    $conditions_detail_faktur_last_month = "id_faktur = ?";
    $bind_params_detail_faktur_last_month = array(
        array('type' => 'i', 'value' => $id_faktur_last_month)
    );

    // Panggil fungsi selectData untuk menghitung subtotal
    $data_faktur_detail_last_month = selectData('detail_faktur', $conditions_detail_faktur_last_month, "", "", $bind_params_detail_faktur_last_month);

    // Hitung subtotal bulan lalu dan tambahkan ke variabel subtotal bulan lalu
    foreach ($data_faktur_detail_last_month as $detail_last_month) {
        $subtotal_last_month += ($detail_last_month['jumlah'] * $detail_last_month['harga_satuan']);
    }
}

// // Tampilkan pendapatan dengan pemisah ribuan atau titik ribuan
// echo "Pendapatan bulan saat ini: Rp " . number_format($subtotal_this_month, 0, ',', '.') . "<br>";
// echo "Pendapatan bulan lalu: Rp " . number_format($subtotal_last_month, 0, ',', '.') . "<br>";

// Menghitung perbedaan subtotal bulan ini dan bulan lalu
$difference = $subtotal_this_month - $subtotal_last_month;

// Menghitung persentase perubahan pendapatan
if ($subtotal_last_month != 0) {
    $percentage_change = ($difference / $subtotal_last_month) * 100;
} else {
    $percentage_change = 0; // Untuk menghindari pembagian dengan nol
}

// INFO DATA PO  ////////////////////////////////////////////////////////////////////////////////
// Hitung total PO masuk tahun ini
$conditionsPO = "kategori = 'masuk' AND YEAR(tanggal) = ?";
$bind_paramsPO = array(
    array('type' => 'i', 'value' => $current_year)
);
$data_po = selectData('pesanan_pembelian', $conditionsPO, "", "", $bind_paramsPO);
$total_po_incoming_curent_year = count($data_po);

// Hitung total PO masuk tahun ini dengan status draft
$conditionsPO = "kategori = 'masuk' AND YEAR(tanggal) = ? AND status = 'draft'";
$bind_paramsPO = array(
    array('type' => 'i', 'value' => $current_year)
);
$data_po = selectData('pesanan_pembelian', $conditionsPO, "", "", $bind_paramsPO);
$total_new_po_incoming_curent_year = count($data_po);

// Hitung total PO masuk tahun ini status = selesai
$conditionsPO = "kategori = 'masuk' AND YEAR(tanggal) = ? AND status = 'selesai'";
$bind_paramsPO = array(
    array('type' => 'i', 'value' => $current_year)
);
$data_po = selectData('pesanan_pembelian', $conditionsPO, "", "", $bind_paramsPO);
$total_close_po_incoming_curent_year = count($data_po);

// Hitung total PO masuk tahun ini dengan status diproses
$conditionsPO = "kategori = 'masuk' AND YEAR(tanggal) = ? AND status = 'diproses'";
$bind_paramsPO = array(
    array('type' => 'i', 'value' => $current_year)
);
$data_po = selectData('pesanan_pembelian', $conditionsPO, "tanggal DESC", "", $bind_paramsPO);
$total_process_po_incoming_curent_year = count($data_po);

// Grafik PO Open
// Ekstraksi Data
$data_sisa_pesanan = array(); // Array untuk menyimpan data sisa pesanan

foreach ($data_po as $po) {
    $id_pesanan = $po['id_pesanan'];
    $mainDetailTable = 'detail_pesanan';
    $joinDetailTables = [
        ['pesanan_pembelian', 'detail_pesanan.id_pesanan = pesanan_pembelian.id_pesanan'], 
        ['produk', 'detail_pesanan.id_produk = produk.id_produk']
    ];
    $columns = 'detail_pesanan.*, produk.*';
    $conditions = "detail_pesanan.id_pesanan = '$id_pesanan'";

    $data_detail_po = selectDataJoin($mainDetailTable, $joinDetailTables, $columns, $conditions);

    if (!empty($data_detail_po)) {
        foreach ($data_detail_po as $detail) {
            // Split nama_produk into words
            $words = explode(' ', ucwords($detail['nama_produk']));
            
            // Check if there are more than 4 words
            if (count($words) > 4) {
                // Take the first 4 words and add ellipsis
                $shortened_nama_produk = implode(' ', array_slice($words, 0, 4)) . '...';
            } else {
                // Otherwise, use the full name
                $shortened_nama_produk = implode(' ', $words);
            }
            
            $data_sisa_pesanan[] = array(
                'no_pesanan' => strtoupper($po['no_pesanan']),
                'nama_produk' => $shortened_nama_produk,
                'sisa_pesanan' => $detail['sisa_pesanan']
            );
        }
    }
}

// Format Data untuk Grafik
$labels = array();
$sisa_pesanan_data = array();

foreach ($data_sisa_pesanan as $sisa) {
    $labels[] = $sisa['no_pesanan'] . ' - ' . $sisa['nama_produk'];
    $sisa_pesanan_data[] = $sisa['sisa_pesanan'];
}

// INFO DATA INVOICE  ////////////////////////////////////////////////////////////////////////////////
// Hitung total Invoice keluar tahun ini
$conditionsInv = "kategori = 'keluar' AND YEAR(tanggal) = ?";
$bind_paramsInv = array(
    array('type' => 'i', 'value' => $current_year)
);
$data_inv = selectData('faktur', $conditionsInv, "", "", $bind_paramsInv);
$total_inv_outgoing_curent_year = count($data_inv);

// Hitung total Invoice keluar tahun ini dengan status tunggu kirim
$conditionsInv = "kategori = 'keluar' AND YEAR(tanggal) = ? AND status = 'tunggu kirim'";
$bind_paramsInv = array(
    array('type' => 'i', 'value' => $current_year)
);
$data_inv = selectData('faktur', $conditionsInv, "", "", $bind_paramsInv);
$total_waiting_inv_outgoing_curent_year = count($data_inv);

// Hitung total Invoice keluar tahun ini dengan status dibayar
$conditionsInv = "kategori = 'keluar' AND YEAR(tanggal) = ? AND status = 'dibayar'";
$bind_paramsInv = array(
    array('type' => 'i', 'value' => $current_year)
);
$data_inv = selectData('faktur', $conditionsInv, "tanggal DESC", "", $bind_paramsInv);
$total_paid_inv_outgoing_curent_year = count($data_inv);

// Hitung total Invoice keluar tahun ini dengan status belum dibayar
$conditionsInv = "kategori = 'keluar' AND YEAR(tanggal) = ? AND status = 'belum dibayar'";
$bind_paramsInv = array(
    array('type' => 'i', 'value' => $current_year)
);
$data_inv = selectData('faktur', $conditionsInv, "tanggal DESC", "", $bind_paramsInv);
$total_unpaid_inv_outgoing_curent_year = count($data_inv);

// Hitung total Invoice keluar
$conditionsInv = "kategori = 'keluar' AND YEAR(tanggal) = ? AND status IN ('dibayar', 'belum dibayar')";
$bind_paramsInv = array(
    array('type' => 'i', 'value' => $current_year)
);
$data_inv = selectData('faktur', $conditionsInv, "tanggal DESC", "", $bind_paramsInv);
$total_sending_inv_outgoing_curent_year = count($data_inv);

// Grapik pendapatan perbulan
$monthly_revenue = array_fill(0, 12, 0); // Array untuk menyimpan total pendapatan per bulan

for ($month = 1; $month <= 12; $month++) {
    $conditionsFaktur = "kategori = 'keluar' AND MONTH(tanggal) = ? AND YEAR(tanggal) = ? AND status = 'dibayar'";
    $bind_paramsFaktur = array(
        array('type' => 'i', 'value' => $month),
        array('type' => 'i', 'value' => $current_year)
    );

    $data_faktur = selectData('faktur', $conditionsFaktur, "", "", $bind_paramsFaktur);

    $subtotalPerMonth = 0;

    foreach ($data_faktur as $faktur) {
        $id_faktur = $faktur['id_faktur'];

        $conditionsDetailFaktur = "id_faktur = ?";
        $bind_paramsDetailFaktur = array(
            array('type' => 'i', 'value' => $id_faktur)
        );

        $data_faktur_detail = selectData('detail_faktur', $conditionsDetailFaktur, "", "", $bind_paramsDetailFaktur);

        foreach ($data_faktur_detail as $detail) {
            $subtotalPerMonth += ($detail['jumlah'] * $detail['harga_satuan']);
        }
    }

    $monthly_revenue[$month - 1] = $subtotalPerMonth;
}

// INFO DATA PENAWARAN HARGA  ////////////////////////////////////////////////////////////////////////////////
// Hitung total PH keluar tahun ini
$conditionsPH = "kategori = 'keluar' AND YEAR(tanggal) = ?";
$bind_paramsPH = array(
    array('type' => 'i', 'value' => $current_year)
);
$data_ph = selectData('penawaran_harga', $conditionsPH, "", "", $bind_paramsPH);
$total_ph_outgoing_curent_year = count($data_ph);

// Hitung total PH keluar tahun ini dengan status draft
$conditionsPH = "kategori = 'keluar' AND YEAR(tanggal) = ? AND status = 'draft'";
$bind_paramsPH = array(
    array('type' => 'i', 'value' => $current_year)
);
$data_ph = selectData('penawaran_harga', $conditionsPH, "", "", $bind_paramsPH);
$total_draft_ph_outgoing_curent_year = count($data_ph);

// Hitung total PH keluar tahun ini dengan status disetujui
$conditionsPH = "kategori = 'keluar' AND YEAR(tanggal) = ? AND status = 'disetujui'";
$bind_paramsPH = array(
    array('type' => 'i', 'value' => $current_year)
);
$data_ph = selectData('penawaran_harga', $conditionsPH, "tanggal DESC", "", $bind_paramsPH);
$total_approved_ph_outgoing_curent_year = count($data_ph);

// Hitung total PH keluar tahun ini dengan status ditolak
$conditionsPH = "kategori = 'keluar' AND YEAR(tanggal) = ? AND status = 'ditolak'";
$bind_paramsPH = array(
    array('type' => 'i', 'value' => $current_year)
);
$data_ph = selectData('penawaran_harga', $conditionsPH, "tanggal DESC", "", $bind_paramsPH);
$total_rejected_ph_outgoing_curent_year = count($data_ph);
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
        <h5 class="card-subtitle"><?= "Rp " . number_format($subtotal_this_month, 0, ',', '.') ?></h5>
        <h6 class="card-title">Total Pendapatan</h6>
        <?php
            if ($difference > 0) {
              echo "<span class='fw-bolder text-success'>+ " . number_format(abs($percentage_change), 2) . "% </span><span style='font-size: .9rem;'>Dibandingkan Bulan Lalu</span>";
            } elseif ($subtotal_this_month == 0) {
              echo "Belum ada pendapatan bulan ini.";
            } elseif ($difference < 0) {
              echo "<span class='fw-bolder text-danger'>- " . number_format(abs($percentage_change), 2) . "% </span><span style='font-size: .9rem;'>Dibandingkan Bulan Lalu</span>";
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
                <?= $total_inv_outgoing_curent_year ?>
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
                <?= $total_waiting_inv_outgoing_curent_year ?>
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
                <?= $total_sending_inv_outgoing_curent_year ?>
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
                <?= $total_paid_inv_outgoing_curent_year ?>
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
                <?= $total_unpaid_inv_outgoing_curent_year ?>
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
                  <td colspan="9">Tidak ada data Invoice</td>
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
                <?= $total_po_incoming_curent_year ?>
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
                <?= $total_new_po_incoming_curent_year ?>
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
                <?= $total_process_po_incoming_curent_year ?>
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
                <?= $total_close_po_incoming_curent_year ?>
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
                <?= $total_ph_outgoing_curent_year ?>
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
                <?= $total_draft_ph_outgoing_curent_year ?>
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
                <?= $total_approved_ph_outgoing_curent_year ?>
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
                <?= $total_rejected_ph_outgoing_curent_year ?>
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
                  <th>No. Invoice</th>
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
                  <td colspan="9">Tidak ada data Penawaran Harga</td>
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
        <?php echo implode(',', $monthly_revenue); ?>
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
      labels: <?= json_encode($labels); ?>,
      datasets: [{
        label: 'Sisa Pesanan',
        data: <?= json_encode($sisa_pesanan_data); ?>,
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