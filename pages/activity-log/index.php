<?php
// Mulai output buffering
ob_start();

$page_title = "Activity Log";
require_once '../../includes/header.php';

// Jika pengguna bukan super_admin dan staff, arahkan ke halaman akses ditolak
if ($_SESSION['peran_pengguna'] !== 'superadmin') {
  header("Location: " . base_url('pages/access-denied.php'));
  exit();
}

// Akhirkan output buffering
ob_end_flush();

// $category = ($category_param === 'outgoing') ? 'keluar' : (($category_param === 'incoming') ? 'masuk' : die("Kategori tidak valid"));

$mainTable = 'log_aktivitas';
$joinTables = [
    ["pengguna", "log_aktivitas.id_pengguna = pengguna.id_pengguna"], 
];

// Kolom-kolom yang ingin diambil dari tabel utama dan tabel-tabel yang di-join
$columns = 'log_aktivitas.*, pengguna.nama_pengguna';

// Klausa ORDER BY
$orderBy = 'log_aktivitas.tanggal DESC';

// Panggil fungsi selectDataLeftJoin dengan ORDER BY
$data_log_aktivitas = selectDataLeftJoin($mainTable, $joinTables, $columns, "", $orderBy);
?>

<div class="d-flex justify-content-between align-items-center mb-4">
  <h1 class="fs-5 m-0">Log Aktivitas</h1>
</div>
<table id="example" class="table nowrap table-hover" style="width:100%">
  <thead>
    <tr>
      <th class="text-start">No.</th>
      <th>ID Pengguna</th>
      <th>Nama Pengguna</th>
      <th>Tanggal</th>
      <th>Aktivitas</th>
      <th>Data Tabel</th>
      <th>Keterangan</th>
      <th>Detail</th>
    </tr>
  </thead>
  <tbody>
    <?php if (empty($data_log_aktivitas)) : ?>
    <tr>
      <td colspan="7">Tidak ada data</td>
    </tr>
    <?php else: $no = 1; foreach ($data_log_aktivitas as $log): ?>
    <tr>
      <td class="text-start"><?= $no; ?></td>
      <td><?= $log['id_pengguna']; ?></td>
      <td><?= isset($log['nama_pengguna']) ? ucwords($log['nama_pengguna']) : 'N/A'; ?></td>
      <td><?= $log['tanggal']; ?></td>
      <td><?= $log['aktivitas']; ?></td>
      <td><?= $log['tabel'] ? ucwords($log['tabel']) : '-'; ?></td>
      <td><?= str_replace(" | ", "<br>", $log['keterangan']); ?></td>
      <td></td>
    </tr>
    <?php $no++; endforeach; endif; ?>
  </tbody>
</table>

<?php require '../../includes/footer.php'; ?>