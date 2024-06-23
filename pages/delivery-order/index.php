<?php
// Ambil parameter kategori dari URL
$category_param = isset($_GET['category']) ? $_GET['category'] : '';

// Menentukan judul halaman (tab browser) berdasarkan kategori
$category_title_en = $category_param === 'outgoing' ? 'Delivery Order Outgoing' : 'Delivery Order Incoming';

// Menentukan judul tabel berdasarkan kategori
$content_title_base_id = $category_param === 'outgoing' ? 'Delivery Order Keluar' : 'Delivery Order Masuk';

// Menggabungkan kategori dan status untuk judul halaman dan konten
$page_title = $category_title_en . ' - Unpaid and Paid Invoices';
$content_title = $content_title_base_id . ' (Belum Dibayar dan Dibayar)';

require '../../includes/header.php';

// Menentukan kategori dan memverifikasi validitasnya
$category = ($category_param === 'outgoing') ? 'keluar' : (($category_param === 'incoming') ? 'masuk' : die("Kategori tidak valid"));
$current_year = date('Y');

// Status untuk query
$status_db_values = array('belum dibayar', 'dibayar');

// Membuat kondisi pencarian berdasarkan kategori dan status
$conditionsInv = "kategori = '$category' AND YEAR(tanggal) = ? AND status_hapus = 0 AND status IN (?, ?)";
$bind_paramsInv = [
    ['type' => 'i', 'value' => $current_year],
    ['type' => 's', 'value' => $status_db_values[0]],  // 'belum dibayar'
    ['type' => 's', 'value' => $status_db_values[1]]   // 'dibayar'
];

$data_inv = selectData('faktur', $conditionsInv, "tanggal DESC", "", $bind_paramsInv);
?>

<h1 class="fs-5 mb-3">Data Delivery Order</h1>
<span class="badge rounded-pill text-bg-info mb-5"><?= htmlspecialchars($content_title); ?></span>

<!-- Detail Invoice -->
<div class="row">
  <div class="col">
    <div class="card p-0">
      <div class="card-body" style="overflow-y:auto; font-size:.9rem;">
        <table class="table table-bordered table-primary">
          <thead class="thead-sticky fw-bolder">
            <tr>
              <th>No.</th>
              <th>No. Invoice</th>
              <th>Tanggal</th>
              <th>Status</th>
              <th>No. Delivery Order</th>
              <th>Produk / Jasa</th>
              <th>Kuantitas</th>
              <th>No. Purchase Order</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($data_inv)) : ?>
            <tr>
              <td colspan="9">Tidak ada data Delivery Order</td>
            </tr>
            <?php else : $no = 1; foreach ($data_inv as $inv) : ?>
            <?php
              $id_faktur = $inv['id_faktur'];
              $mainDetailTable = 'detail_faktur';
              $joinDetailTables = [
                  ['faktur', 'detail_faktur.id_faktur = faktur.id_faktur'],
                  ['pesanan_pembelian', 'detail_faktur.id_pesanan = pesanan_pembelian.id_pesanan'],
                  ['produk', 'detail_faktur.id_produk = produk.id_produk']
              ];
              $columns = 'detail_faktur.*, produk.nama_produk, produk.satuan, pesanan_pembelian.no_pesanan';
              $conditions = "detail_faktur.id_faktur = '$id_faktur'";

              $data_detail_inv = selectDataJoin($mainDetailTable, $joinDetailTables, $columns, $conditions);
            ?>

            <?php if (!empty($data_detail_inv)): ?>
            <?php foreach ($data_detail_inv as $index => $detail): ?>
            <tr>
              <?php if ($index === 0): ?>
              <td class="text-start" rowspan="<?= count($data_detail_inv); ?>"><?= $no; ?></td>
              <td rowspan="<?= count($data_detail_inv); ?>"><?= strtoupper($inv['no_faktur']); ?>
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
              <?php endif; ?>
              <td class="text-primary"><?= strtoupper($detail['no_pengiriman_barang']); ?></td>
              <td><?= strtoupper($detail['nama_produk']); ?></td>
              <td class="text-end no-wrap">
                <?= number_format($detail['jumlah'], 0, ',', '.') . ' ' . strtoupper($detail['satuan']); ?>
              </td>
              <td><?= strtoupper($detail['no_pesanan']); ?></td>
              <td>
                <div class="btn-group">
                  <a href="<?= base_url("pages/delivery-order/detail/$category_param/{$detail['id_detail_faktur']}") ?>"
                    class="btn-act btn-view" title="Lihat Detail"></a>
                </div>
              </td>
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
    'th, td { border: 1px solid lightgrey; padding: 8px; text-align: left; vertical-align: top; }');
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
    printWindow.document.write('<p>Tidak ada data Invoice</p>');
  }

  printWindow.document.write('</body></html>');
  printWindow.document.close();
  printWindow.print();
}
</script>