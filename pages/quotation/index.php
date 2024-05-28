<?php
// Ambil nilai kategori dari parameter URL
$category_param = isset($_GET['category']) ? $_GET['category'] : '';

// Atur judul halaman berdasarkan kategori
$page_title = $category_param === 'outgoing' ? 'Quotation Outgoing' : 'Quotation Incoming';

require '../../includes/header.php';

$category = ($category_param === 'outgoing') ? 'keluar' : (($category_param === 'incoming') ? 'masuk' : die("Kategori tidak valid"));

$mainTable = 'penawaran_harga';
$joinTables = [
    ["kontak pengirim", "penawaran_harga.id_pengirim = pengirim.id_kontak"], 
    ["kontak penerima", "penawaran_harga.id_penerima = penerima.id_kontak"],
    ['ppn', 'penawaran_harga.id_ppn = ppn.id_ppn']
];

// Kolom-kolom yang ingin diambil dari tabel utama dan tabel-tabel yang di-join
$columns = 'penawaran_harga.*, pengirim.nama_kontak AS nama_pengirim, penerima.nama_kontak AS nama_penerima, ppn.jenis_ppn';

// Kondisi tambahan untuk seleksi data (opsional)
$conditions = "penawaran_harga.kategori = '$category'";

// Klausa ORDER BY
$orderBy = 'penawaran_harga.tanggal DESC';

// Panggil fungsi selectDataJoin dengan ORDER BY
$data_penawaran_harga = selectDataJoin($mainTable, $joinTables, $columns, $conditions, $orderBy);
?>

<div class="d-flex justify-content-between align-items-center mb-4">
  <h1 class="fs-5 m-0">Data Penawaran Harga</h1>
  <a href="add.php?category=<?= $category_param ?>" class="btn btn-primary btn-lg btn-icon btn-add">Buat Penawaran
    harga</a>
</div>
<table id="example" class="table nowrap table-hover" style="width:100%">
  <thead>
    <tr>
      <th class="text-start">No.</th>
      <th>Nomor Penawaran</th>
      <th>Tanggal</th>
      <th>Penerima</th>
      <th>Status</th>
      <th>Total</th>
      <th>PPN</th>
      <th>Diskon</th>
      <th>U.P.</th>
      <th>Pengirim</th>
      <th>Catatan</th>
      <th>Detail</th>
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody>
    <?php if (empty($data_penawaran_harga)) : ?>
    <tr>
      <td colspan="11">Tidak ada data</td>
    </tr>
    <?php else: $no = 1; foreach ($data_penawaran_harga as $ph): ?>
    <tr>
      <td class="text-start"><?= $no; ?></td>
      <td><?= strtoupper($ph['no_penawaran']); ?></td>
      <td><?= dateID($ph['tanggal']); ?></td>
      <td class="text-wrap"><?= ucwords($ph['nama_penerima']); ?></td>
      <td>
        <?php
        // Tentukan kelas bootstrap berdasarkan nilai status
        $status_class = '';
        if ($ph['status'] == 'draft') {
            $status_class = 'text-bg-warning';
        } elseif ($ph['status'] == 'terkirim') {
            $status_class = 'text-bg-info';
        } elseif ($ph['status'] == 'ditolak') {
            $status_class = 'text-bg-danger';
        } elseif ($ph['status'] == 'disetujui') {
            $status_class = 'text-bg-success';
        }
        ?>
        <span class="badge <?= $status_class ?>"><?= strtoupper($ph['status']) ?></span>
      </td>
      <td><?= formatRupiah($ph['total']); ?></td>
      <td><?= ucwords($ph['jenis_ppn']); ?></td>
      <td><?= $ph['diskon'] != 0 ? $ph['diskon'] : "-"; ?></td>
      <td><?= !empty($ph['up']) ? ucwords($ph['up']) : "-"; ?></td>
      <td>
        <?php if (!empty($ph['logo'])) : ?>
        <img class="me-3" src="<?= base_url($ph['logo']); ?>" alt="Logo" width="50">
        <?php endif; ?>
        <?= ucwords($ph['nama_pengirim']); ?>
      </td>
      <td><?= !empty($ph['catatan']) ? ucfirst($ph['catatan']) : "-"; ?></td>

      <!-- Detail PH -->
      <td>
        <?php
        $id_penawaran = $ph['id_penawaran'];
        $data_penawaran_harga_detail = [];
        $mainDetailTable = 'detail_penawaran';
        $joinDetailTables = [
            ['penawaran_harga', 'detail_penawaran.id_penawaran = penawaran_harga.id_penawaran'], 
            ['produk', 'detail_penawaran.id_produk = produk.id_produk']
        ];
        $columns = 'detail_penawaran.*, produk.*';
        $conditions = "detail_penawaran.id_penawaran = '$id_penawaran'";

        // Panggil fungsi selectDataJoin dengan ORDER BY
        $data_penawaran_harga_detail = selectDataJoin($mainDetailTable, $joinDetailTables, $columns, $conditions);

        $subtotal = 0;
        if (!empty($data_penawaran_harga_detail)): ?>

        <div class="row fw-bold border-bottom">
          <div class="col">No.</div>
          <div class="col">Deskripsi</div>
          <div class="col">Kuantitas</div>
          <div class="col">Harga</div>
          <div class="col">Total Harga</div>
        </div>
        <?php
            $no = 1; 
            foreach ($data_penawaran_harga_detail as $detail): 
            
            // Hitung total harga untuk setiap baris
            $total_harga = $detail['jumlah'] * $detail['harga_satuan'];
            // Tambahkan total harga ke subtotal
            $subtotal += $total_harga;
        ?>
        <div class="row border-bottom">
          <div class="col"><?= $no ?></div>
          <div class="col"><?= strtoupper($detail['nama_produk']); ?></div>
          <div class="col"><?= $detail['jumlah'] . " " . strtoupper($detail['satuan']); ?></div>
          <div class="col"><?= formatRupiah($detail['harga_satuan']); ?></div>
          <div class="col"><?= formatRupiah($total_harga); ?></div>
        </div>
        <?php $no++; endforeach; ?>
        <?php else: ?>
        <span class="text-center">-</span>
        <?php endif; ?>
      </td>

      <td>
        <div class="btn-group">
          <a href="detail.php?category=<?= $category_param ?>&id=<?= $ph['id_penawaran']; ?>" class="btn-act btn-view"
            title="Lihat Detail"></a>
          <a href="edit.php?category=<?= $category_param ?>&id=<?= $ph['id_penawaran']; ?>" class="btn-act btn-edit"
            title="Ubah Data"></a>
          <a href="javascript:void(0);"
            onclick="confirmDelete('del.php?category=<?= $category_param ?>&id=<?= $ph['id_penawaran']; ?>', 'Apakah Anda yakin ingin menghapus data penawaran ini? Semua detail penawaran terkait juga akan dihapus.')"
            class="btn-act btn-del" title="Hapus Data"></a>
        </div>
      </td>
    </tr>
    <?php $no++; endforeach; endif; ?>
  </tbody>
</table>

<script>
$(document).ready(function() {
  // Tangkap klik pada setiap tombol di dalam .btn-group
  $('.btn-group').on('click', function(event) {
    // Hentikan propagasi event agar tidak mencapai elemen td
    event.stopPropagation();
  });
});
</script>

<?php require '../../includes/footer.php'; ?>