<?php
// Ambil parameter kategori dan status dari URL
$category_param = isset($_GET['category']) ? $_GET['category'] : '';
$status_param = isset($_GET['status']) ? $_GET['status'] : 'all';

// Menentukan judul halaman (tab browser) berdasarkan kategori
$category_title_en = $category_param === 'outgoing' ? 'PO Outgoing' : 'PO Incoming';

// Menentukan judul tabel berdasarkan kategori
$content_title_base_id = $category_param === 'outgoing' ? 'PO Keluar' : 'PO Masuk';

// Menentukan judul status dalam bahasa Inggris untuk page_title
$status_map_en = [
    'new' => 'New',
    'processed' => 'Processed',
    'closed' => 'Closed',
    'all' => 'All PO'
];

// Menentukan judul status dalam bahasa Indonesia untuk content_title
$status_map_id = [
    'new' => 'Baru',
    'processed' => 'Dikerjakan',
    'closed' => 'Close',
    'all' => 'Semua PO'
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
    'new' => 'draft',
    'processed' => 'diproses',
    'closed' => 'selesai',
];

$actual_status = isset($status_db_map[$status_param]) ? $status_db_map[$status_param] : 'all';

// Membuat kondisi pencarian berdasarkan kategori dan status
$conditionsPO = "kategori = '$category' AND YEAR(tanggal) = ? AND status_hapus = 0";
$bind_paramsPO = array(
    array('type' => 'i', 'value' => $current_year)
);

if ($actual_status !== 'all') {
    if (is_array($actual_status)) {
        $placeholders = implode(',', array_fill(0, count($actual_status), '?'));
        $conditionsPO .= " AND status IN ($placeholders)";
        foreach ($actual_status as $status) {
            $bind_paramsPO[] = array('type' => 's', 'value' => $status);
        }
    } else {
        $conditionsPO .= " AND status = ?";
        $bind_paramsPO[] = array('type' => 's', 'value' => $actual_status);
    }
} else {
    $conditionsPO .= " AND status IN ('draft', 'terkirim', 'diproses', 'selesai')";
}

$data_po = selectData('pesanan_pembelian', $conditionsPO, "tanggal DESC", "", $bind_paramsPO);
?>

<div class="d-flex justify-content-between align-items-center mb-4">
  <h1 class="fs-5 mb-4">Detail Purchase Order</h1>
  <div>
    <a href="<?= base_url("pages/dashboard") ?>" class="btn-act btn-back" title="Kembali"></a>
    <a href="#" class="btn-act btn-print ms-4" onclick="printContent()" title="Cetak Data"></a>
  </div>
</div>

<!-- Detail PO -->
<div class="row">
  <div class="col">
    <div class="card p-0">
      <div class="card-header card-header-sticky">
        <!-- Menggunakan judul tabel dalam bahasa Indonesia -->
        <?= htmlspecialchars($content_title); ?>
      </div>
      <div class="card-body" style="overflow-y:auto; font-size:.9rem;">
        <table class="table table-bordered table-primary">
          <thead class="thead-sticky fw-bolder">
            <tr>
              <th>No.</th>
              <th>No. Purchase Order</th>
              <th>Tanggal</th>
              <th>Status</th>
              <th>Pengirim</th>
              <th>Total</th>
              <th>PPN</th>
              <th>Diskon</th>
              <th>Penerima</th>
              <th>Produk / Jasa</th>
              <th>Kuantitas</th>
              <th>Harga Satuan</th>
              <th>Dikirim</th>
              <th>Sisa</th>
              <th>No. Penawaran</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($data_po)) : ?>
            <tr>
              <td colspan="15">Tidak ada data PO</td>
            </tr>
            <?php else : $no = 1; foreach ($data_po as $po) : ?>
            <?php
              $id_pesanan = $po['id_pesanan'];
              $mainDetailTable = 'detail_pesanan';
              $joinDetailTables = [
                  ['pesanan_pembelian', 'detail_pesanan.id_pesanan = pesanan_pembelian.id_pesanan'],
                  ['penawaran_harga', 'detail_pesanan.id_penawaran = penawaran_harga.id_penawaran'],
                  ['produk', 'detail_pesanan.id_produk = produk.id_produk'],
                  ['kontak as penerima', 'pesanan_pembelian.id_penerima = penerima.id_kontak'],
                  ['ppn', 'pesanan_pembelian.id_ppn = ppn.id_ppn'],
                  ['kontak as pengirim', 'pesanan_pembelian.id_pengirim = pengirim.id_kontak']
              ];
              $columns = 'detail_pesanan.*, produk.nama_produk, produk.satuan, penerima.nama_kontak as nama_penerima, ppn.jenis_ppn, pengirim.nama_kontak as nama_pengirim, penawaran_harga.no_penawaran';
              $conditions = "detail_pesanan.id_pesanan = '$id_pesanan'";

              $data_detail_po = selectDataJoin($mainDetailTable, $joinDetailTables, $columns, $conditions);
            ?>

            <?php if (!empty($data_detail_po)): ?>
            <?php foreach ($data_detail_po as $index => $detail): ?>
            <tr>
              <?php if ($index === 0): ?>
              <td class="text-start" rowspan="<?= count($data_detail_po); ?>"><?= $no; ?></td>
              <td class="text-primary" rowspan="<?= count($data_detail_po); ?>"><?= strtoupper($po['no_pesanan']); ?>
              </td>
              <td rowspan="<?= count($data_detail_po); ?>"><?= dateID($po['tanggal']); ?></td>
              <td rowspan="<?= count($data_detail_po); ?>">
                <?php
                  $status_class = '';
                  if ($po['status'] == 'terkirim') {
                      $status_class = 'text-bg-warning';
                  } elseif ($po['status'] == 'draft') {
                      $status_class = 'text-bg-danger';
                  } elseif ($po['status'] == 'diproses' || $po['status'] == 'selesai') {
                      $status_class = 'text-bg-success';
                  }
                ?>
                <span class="badge rounded-pill <?= $status_class ?>"><?= strtoupper($po['status']) ?></span>
              </td>
              <td rowspan="<?= count($data_detail_po); ?>">
                <?= htmlspecialchars(strtoupper($detail['nama_pengirim'])); ?>
              </td>
              <td rowspan="<?= count($data_detail_po); ?>"><?= formatRupiah($po['total']); ?></td>
              <td rowspan="<?= count($data_detail_po); ?>">
                <?= ucwords($detail['jenis_ppn']); ?>
              </td>
              <td rowspan="<?= count($data_detail_po); ?>">
                <?= $po['diskon']; ?>
              </td>
              <td rowspan="<?= count($data_detail_po); ?>">
                <?= htmlspecialchars(strtoupper($detail['nama_penerima'])); ?></td>
              <?php endif; ?>
              <td><?= strtoupper($detail['nama_produk']); ?></td>
              <td class="text-end no-wrap">
                <?= number_format($detail['jumlah'], 0, ',', '.') . ' ' . strtoupper($detail['satuan']); ?>
              </td>
              <td class="text-end no-wrap">
                <?= formatRp($detail['harga_satuan']); ?>
              </td>
              <td class="text-end no-wrap">
                <?= number_format($detail['jumlah_dikirim'], 0, ',', '.') . ' ' . strtoupper($detail['satuan']); ?>
              </td>
              <td class="text-end no-wrap">
                <?= number_format($detail['sisa_pesanan'], 0, ',', '.') . ' ' . strtoupper($detail['satuan']); ?>
              </td>
              <td><?= strtoupper($detail['no_penawaran']); ?></td>
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
<script>
function printContent() {
  var printWindow = window.open('', '', 'height=600,width=800');
  printWindow.document.write('<html><head><title>IMS By AMC</title>');
  printWindow.document.write('<style>');
  printWindow.document.write('@page { size: A4 landscape; margin: 1cm; }');
  printWindow.document.write('body { font-family: Arial, sans-serif; }');
  printWindow.document.write('table { border-collapse: collapse; width: 100%; }');
  printWindow.document.write(
    'th, td { border: 1px solid #469bf7; padding: 8px; text-align: left; vertical-align: top; }');
  printWindow.document.write('h1 { text-align: center; }');
  printWindow.document.write('</style>');
  printWindow.document.write('</head><body>');
  printWindow.document.write('<h1><?= $content_title ?></h1>');

  // Ambil tabel dari halaman utama
  var table = document.querySelector('.card-body table');
  if (table) {
    // Salin tabel ke dokumen cetak
    printWindow.document.write('<table>');
    printWindow.document.write(table.innerHTML);
    printWindow.document.write('</table>');
  } else {
    // Tampilkan pesan jika tidak ada tabel
    printWindow.document.write('<p>Tidak ada data PO</p>');
  }

  printWindow.document.write('</body></html>');
  printWindow.document.close();
  printWindow.print();
}
</script>