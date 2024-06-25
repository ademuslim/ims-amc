<?php
// Ambil parameter kategori dan status dari URL
$category_param = isset($_GET['category']) ? $_GET['category'] : '';
$status_param = isset($_GET['status']) ? $_GET['status'] : 'all';

// Menentukan judul halaman (tab browser) berdasarkan kategori
$category_title_en = $category_param === 'outgoing' ? 'Invoice Outgoing' : 'Invoice Incoming';

// Menentukan judul tabel berdasarkan kategori
$content_title_base_id = $category_param === 'outgoing' ? 'Invoice Keluar' : 'Invoice Masuk';

// Menentukan judul status dalam bahasa Inggris untuk page_title
$status_map_en = [
    'waiting' => 'Waiting',
    'sending' => 'Sending',
    'paid' => 'Paid',
    'unpaid' => 'Unpaid',
    'all' => 'All Invoices'
];

// Menentukan judul status dalam bahasa Indonesia untuk content_title
$status_map_id = [
    'waiting' => 'Tunggu Kirim',
    'sending' => 'Terkirim',
    'paid' => 'Dibayar',
    'unpaid' => 'Belum Dibayar',
    'all' => 'Semua Invoice'
];

$actual_status_title_en = isset($status_map_en[$status_param]) ? $status_map_en[$status_param] : 'Unknown Status';
$actual_status_title_id = isset($status_map_id[$status_param]) ? $status_map_id[$status_param] : 'Status Tidak Dikenal';

// Menggabungkan kategori dan status untuk judul halaman dan konten
$page_title = $category_title_en . ' - ' . $actual_status_title_en;
$content_title = $content_title_base_id . ' (' . $actual_status_title_id . ')';

require '../../includes/header.php';

// Menentukan kategori dan memverifikasi validitasnya
$category = ($category_param === 'outgoing') ? 'keluar' : (($category_param === 'incoming') ? 'masuk' : die("Kategori tidak valid"));
$current_year = date('Y');

// Mapping status ke nilai database
$status_db_map = [
    'waiting' => 'tunggu kirim',
    'sending' => ['dibayar', 'belum dibayar'],
    'paid' => 'dibayar',
    'unpaid' => 'belum dibayar'
];

$actual_status = isset($status_db_map[$status_param]) ? $status_db_map[$status_param] : 'all';

// Membuat kondisi pencarian berdasarkan kategori dan status
$conditionsInv = "kategori = '$category' AND YEAR(tanggal) = ? AND status_hapus = 0";
$bind_paramsInv = array(
    array('type' => 'i', 'value' => $current_year)
);

if ($actual_status !== 'all') {
    if (is_array($actual_status)) {
        $placeholders = implode(',', array_fill(0, count($actual_status), '?'));
        $conditionsInv .= " AND status IN ($placeholders)";
        foreach ($actual_status as $status) {
            $bind_paramsInv[] = array('type' => 's', 'value' => $status);
        }
    } else {
        $conditionsInv .= " AND status = ?";
        $bind_paramsInv[] = array('type' => 's', 'value' => $actual_status);
    }
} else {
    $conditionsInv .= " AND status IN ('tunggu kirim', 'belum dibayar', 'dibayar')";
}

$data_inv = selectData('faktur', $conditionsInv, "tanggal DESC", "", $bind_paramsInv);
?>

<div class="d-flex justify-content-between align-items-center mb-4">
  <h1 class="fs-5 mb-4">Detail Invoice</h1>
  <div>
    <a href="<?= base_url("pages/dashboard") ?>" class="btn-act btn-back" title="Kembali"></a>
    <a class="btn-act btn-print ms-4" onclick="printContent()" title="Cetak Data"></a>
  </div>
</div>

<!-- Detail Invoice -->
<div class="row">
  <div class="col">
    <div class="card card-sticky p-0">
      <div class="card-header card-header-sticky">
        <!-- Menggunakan judul tabel dalam bahasa Indonesia -->
        <?= htmlspecialchars($content_title); ?>
      </div>
      <div class="card-body fix-card-body">
        <table class="table table-bordered">
          <thead class="thead-sticky fw-bolder">
            <tr>
              <th>No.</th>
              <th>No. Invoice</th>
              <th>Tanggal</th>
              <th>Status</th>
              <th>Penerima</th>
              <th>Total</th>
              <th>PPN</th>
              <th>Diskon</th>
              <th>Pengirim</th>
              <th>No. Pengiriman</th>
              <th>Produk / Jasa</th>
              <th>Kuantitas</th>
              <th>Harga Satuan</th>
              <th>No. Purchase Order</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($data_inv)) : ?>
            <tr>
              <td colspan="14">Tidak ada data Invoice</td>
            </tr>
            <?php else : $no = 1; foreach ($data_inv as $inv) : ?>
            <?php
              $id_faktur = $inv['id_faktur'];
              $mainDetailTable = 'detail_faktur';
              $joinDetailTables = [
                  ['faktur', 'detail_faktur.id_faktur = faktur.id_faktur'],
                  ['pesanan_pembelian', 'detail_faktur.id_pesanan = pesanan_pembelian.id_pesanan'],
                  ['produk', 'detail_faktur.id_produk = produk.id_produk'],
                  ['kontak as penerima', 'faktur.id_penerima = penerima.id_kontak'],
                  ['ppn', 'faktur.id_ppn = ppn.id_ppn'],
                  ['kontak as pengirim', 'faktur.id_pengirim = pengirim.id_kontak']
              ];
              $columns = 'detail_faktur.*, produk.nama_produk, produk.satuan, penerima.nama_kontak as nama_penerima, ppn.jenis_ppn, pengirim.nama_kontak as nama_pengirim, pesanan_pembelian.no_pesanan';
              $conditions = "detail_faktur.id_faktur = '$id_faktur'";

              $data_detail_inv = selectDataJoin($mainDetailTable, $joinDetailTables, $columns, $conditions);
            ?>

            <?php if (!empty($data_detail_inv)): ?>
            <?php foreach ($data_detail_inv as $index => $detail): ?>
            <tr>
              <?php if ($index === 0): ?>
              <td class="text-start" rowspan="<?= count($data_detail_inv); ?>"><?= $no; ?></td>
              <td class="text-primary" rowspan="<?= count($data_detail_inv); ?>"><?= strtoupper($inv['no_faktur']); ?>
              </td>
              <td rowspan="<?= count($data_detail_inv); ?>"><?= dateID($inv['tanggal']); ?></td>
              <td rowspan="<?= count($data_detail_inv); ?>">
                <?php
                  $status_class = '';
                  if ($inv['status'] == 'tunggu kirim') {
                      $status_class = 'text-bg-warning';
                  } elseif ($inv['status'] == 'belum dibayar') {
                      $status_class = 'text-bg-danger';
                  } elseif ($inv['status'] == 'dibayar') {
                      $status_class = 'text-bg-success';
                  }
                ?>
                <span class="badge rounded-pill <?= $status_class ?>"><?= strtoupper($inv['status']) ?></span>
              </td>
              <td rowspan="<?= count($data_detail_inv); ?>">
                <?= htmlspecialchars(strtoupper($detail['nama_penerima'])); ?></td>
              <td rowspan="<?= count($data_detail_inv); ?>"><?= formatRupiah($inv['total']); ?></td>
              <td rowspan="<?= count($data_detail_inv); ?>">
                <?= ucwords($detail['jenis_ppn']); ?>
              </td>
              <td rowspan="<?= count($data_detail_inv); ?>">
                <?= $inv['diskon']; ?>
              </td>
              <td rowspan="<?= count($data_detail_inv); ?>">
                <?= htmlspecialchars(strtoupper($detail['nama_pengirim'])); ?>
              </td>
              <?php endif; ?>
              <td><?= strtoupper($detail['no_pengiriman_barang']); ?></td>
              <td><?= strtoupper($detail['nama_produk']); ?></td>
              <td class="text-end no-wrap">
                <?= number_format($detail['jumlah'], 0, ',', '.') . ' ' . strtoupper($detail['satuan']); ?>
              </td>
              <td class="text-end no-wrap">
                <?= formatRp($detail['harga_satuan']); ?>
              </td>
              <td><?= strtoupper($detail['no_pesanan']); ?></td>
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

<?php require '../../includes/footer.php'; ?>