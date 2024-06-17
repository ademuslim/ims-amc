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
$columns = 'log_aktivitas.*, pengguna.nama_pengguna';
$conditions = 'log_aktivitas.status_hapus = 0';
$orderBy = 'log_aktivitas.tanggal DESC';

// Panggil fungsi selectDataLeftJoin dengan ORDER BY
$data_log_aktivitas = selectDataLeftJoin($mainTable, $joinTables, $columns, $conditions, $orderBy);
?>

<div class="d-flex justify-content-between align-items-center mb-4">
  <h1 class="fs-5 m-0">Log Aktivitas</h1>
</div>
<table id="example" class="table nowrap table-hover" style="width:100%">
  <thead>
    <tr>
      <th class="text-start">No.</th>
      <th>Nama Pengguna</th>
      <th>Waktu</th>
      <th>Aktivitas</th>
      <th>Data Tabel</th>
      <th>Keterangan</th>
      <th>Aksi</th>
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
      <td><?= isset($log['nama_pengguna']) ? $log['nama_pengguna'] : 'N/A'; ?></td>
      <td><?= $log['tanggal']; ?></td>
      <td><?= $log['aktivitas']; ?></td>
      <td><?= $log['tabel'] ? ucwords($log['tabel']) : '-'; ?></td>
      <td><?= str_replace(" | ", "<br>", $log['keterangan']); ?></td>
      <td>
        <div class="btn-group">
          <a href="javascript:void(0);"
            onclick="confirmDelete('del.php?id=<?= $log['id_log']; ?>', 'Apakah Anda yakin ingin menghapus data ini? Data yang sudah dihapus tetap ada dalam database dan tidak dapat ditampilkan kembali.')"
            class="btn-act btn-del" title="Hapus Data"></a>
        </div>
      </td>
    </tr>
    <?php $no++; endforeach; endif; ?>
  </tbody>
</table>

<?php require '../../includes/footer.php'; ?>