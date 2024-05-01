<?php // penawaran_harga/index.php
require '../../includes/header.php';
// Select data "penawaran_harga"
$mainTable = 'penawaran_harga';
$joinTables = [
    ['kontak_internal', 'penawaran_harga.id_pengirim = kontak_internal.id_pengirim'], 
    ['pelanggan', 'penawaran_harga.id_penerima = pelanggan.id_pelanggan'],
    ['ppn', 'penawaran_harga.id_ppn = ppn.id_ppn']
];

// Kolom-kolom yang ingin diambil dari tabel utama dan tabel-tabel yang di-join
$columns = 'penawaran_harga.*, kontak_internal.nama_pengirim AS nama_pengirim, pelanggan.nama_pelanggan AS nama_penerima, ppn.jenis_ppn AS jenis_ppn';

// Kondisi tambahan untuk seleksi data (opsional)
$conditions = "";

// Klausa ORDER BY
$orderBy = 'penawaran_harga.tanggal DESC';

// Panggil fungsi selectDataJoin dengan ORDER BY
$data_penawaran_harga = selectDataJoin($mainTable, $joinTables, $columns, $conditions, $orderBy);
?>

<a href="add.php" class="btn btn-primary">Buat Penawaran Harga</a>

<!-- Tampil data -->
<div class="mt-3">
  <h2>Data Penawaran Harga</h2>
  <table class="table">
    <thead>
      <tr>
        <!-- <th>ID Penawaran</th> -->
        <th>Nomor Penawaran</th>
        <th>Tanggal</th>
        <th>ID Pengirim</th>
        <th>ID Penerima</th>
        <th>U.P.</th>
        <th>Diskon</th>
        <th>Jenis PPN</th>
        <th>Total</th>
        <th>Catatan</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($data_penawaran_harga)) : ?>
      <tr>
        <td colspan="10">Tidak ada data</td>
      </tr>
      <?php else : ?>
      <?php foreach ($data_penawaran_harga as $ph) : ?>
      <tr>
        <!-- <td><?= $ph['id_penawaran']; ?></td> -->
        <td><?= strtoupper($ph['no_penawaran']); ?></td>
        <td><?= $ph['tanggal']; ?></td>
        <td><?= ucwords($ph['nama_pengirim']); ?></td>
        <td><?= ucwords($ph['nama_penerima']); ?></td>
        <td><?= ucwords($ph['up']); ?></td>
        <td><?= $ph['diskon']; ?></td>
        <td><?= ucwords($ph['jenis_ppn']); ?></td>
        <td><?= $ph['total']; ?></td>
        <td><?= ucfirst($ph['catatan']); ?></td>
        <td>
          <button type="button" class="btn btn-primary" data-bs-toggle="modal"
            data-bs-target="#editModal<?= $ph['id_penawaran']; ?>">Edit</button>
          <a href="del.php?id=<?= $ph['id_penawaran']; ?>" class="btn btn-danger">Hapus</a>
        </td>
      </tr>

      <!-- Modal Edit -->
      <div class="modal fade" id="editModal<?= $ph['id_penawaran']; ?>" data-bs-backdrop="static" tabindex="-1"
        aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
          <div class="modal-content">
            <div class="modal-header">
              <h1 class="modal-title fs-5" id="editModalLabel">Edit Data Penawaran Harga</h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <?php include 'edit.php'; ?>
              <!-- Include file edit.php untuk konten modal edit -->
            </div>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<?php
require '../../includes/footer.php';
?>