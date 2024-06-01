<?php
require '../../../includes/function.php';
require '../../../includes/vendor/autoload.php';

// Ambil id_pengguna dari sesi atau cookie untuk pencatatan log aktivitas
$id_pengguna = $_SESSION['id_pengguna'] ?? $_COOKIE['ingat_user_id'] ?? '';

if (isset($_POST['add'])) {
  $no_produk = $_POST['no_produk'];
  $nama_produk = strtolower($_POST['nama_produk']);
  $satuan = strtolower($_POST['satuan']);
  $harga = $_POST['harga'];
  $keterangan = strtolower($_POST['keterangan']);

  // Periksa apakah nomor produk sudah ada
  if (isValueExists('produk', 'no_produk', $no_produk)) {
    $_SESSION['error_message'] = "Nomor produk sudah ada dalam database.";
    header("Location: index.php");
    exit();
  }

  // Generate UUID untuk kolom id_produk
  $id_produk = Ramsey\Uuid\Uuid::uuid4()->toString();

  // Data yang akan dimasukkan ke dalam tabel produk
  $data = [
      'id_produk' => $id_produk,
      'no_produk' => $no_produk,
      'nama_produk' => $nama_produk,
      'satuan' => $satuan,
      'harga' => $harga,
      'status' => 'draft', // Nilai default untuk status
      'keterangan' => $keterangan // Nilai default untuk status
  ];

  // Panggil fungsi insertData untuk menambahkan data ke dalam tabel produk
  $result = insertData('produk', $data);

  // Periksa apakah data berhasil ditambahkan
  if ($result > 0) {
      // Jika berhasil, simpan pesan sukses ke dalam session
      $_SESSION['success_message'] = "Produk berhasil ditambahkan!";

      // Pencatatan log aktivitas
      $id_log = Ramsey\Uuid\Uuid::uuid4()->toString();
      $aktivitas = 'Berhasil tambah produk';
      $tabel = 'produk';
      $keterangan = 'Pengguna dengan ID ' . $id_pengguna . ' berhasil tambah produk dengan ID ' . $id_produk;
      $log_data = [
          'id_log' => $id_log,
          'id_pengguna' => $id_pengguna,
          'aktivitas' => $aktivitas,
          'tabel' => $tabel,
          'keterangan' => $keterangan
      ];
      insertData('log_aktivitas', $log_data);
  } else {
      // Jika gagal, simpan pesan error ke dalam session
      $_SESSION['error_message'] = "Terjadi kesalahan saat menambahkan produk.";

      // Pencatatan log aktivitas
      $id_log = Ramsey\Uuid\Uuid::uuid4()->toString();
      $aktivitas = 'Gagal tambah produk';
      $tabel = 'produk';
      $keterangan = 'Pengguna dengan ID ' . $id_pengguna . ' gagal tambah produk dengan nama produk ' . $nama_produk;
      $log_data = [
          'id_log' => $id_log,
          'id_pengguna' => $id_pengguna,
          'aktivitas' => $aktivitas,
          'tabel' => $tabel,
          'keterangan' => $keterangan
      ];
      insertData('log_aktivitas', $log_data);
  }
}elseif (isset($_POST['edit'])) {
  // Ambil nilai-nilai dari form edit
  $id_produk = $_POST['id_produk'];
  $no_produk = $_POST['no_produk'];
  $nama_produk = strtolower($_POST['nama_produk']);
  $satuan = $_POST['satuan'];
  $harga = $_POST['harga'];
  $status = $_POST['status'];
  $keterangan = strtolower($_POST['keterangan']);

  // Periksa apakah nomor produk sudah ada (kecuali untuk produk yang sedang diedit)
  if (isValueExists('produk', 'no_produk', $no_produk, $id_produk, 'id_produk')) {
    $_SESSION['error_message'] = "Nomor produk sudah ada dalam database.";
    header("Location: index.php");
    exit();
  }

  // Ambil data lama sebelum diubah
  $oldData = selectData('produk', 'id_produk = ?', '', '', [['type' => 's', 'value' => $id_produk]]);

  // Data yang akan diupdate di tabel produk
  $data = [
    'no_produk' => $no_produk,
    'nama_produk' => $nama_produk,
    'satuan' => $satuan,
    'harga' => $harga,
    'status' => $status,
    'keterangan' => $keterangan
  ];

  // Kondisi untuk menentukan produk mana yang akan diupdate
  $conditions = "id_produk = '$id_produk'";

  // Panggil fungsi updateData untuk mengupdate data di tabel produk
  $result = updateData('produk', $data, $conditions);

  // Periksa apakah data berhasil diupdate
  if ($result > 0) {
      // Jika berhasil, simpan pesan sukses ke dalam session
      $_SESSION['success_message'] = "Produk berhasil diupdate!";

      // Ambil data setelah diubah
      $newData = selectData('produk', 'id_produk = ?', '', '', [['type' => 's', 'value' => $id_produk]]);

      // Data sebelum dan sesudah perubahan untuk log
      $before = $oldData[0]; // Ambil baris pertama dari hasil query
      $after = $newData[0]; // Ambil baris pertama dari hasil query

      // Keterangan perubahan
      $changeDescription = "Perubahan data produk: | ";

      // Periksa setiap kolom untuk menemukan perubahan
      $counter = 1;
      foreach ($before as $column => $value) {
          if ($value !== $after[$column]) {
              $changeDescription .= "$counter. $column: \"$value\" diubah menjadi \"$after[$column]\" | ";
              $counter++;
          }
      }
      
      // Catat aktivitas
      $id_log = Ramsey\Uuid\Uuid::uuid4()->toString();
      $logData = [
        'id_log' => $id_log,
        'id_pengguna' => $_SESSION['id_pengguna'], // pastikan ini sesuai dengan session atau cara penyimpanan ID pengguna di aplikasi kamu
        'aktivitas' => 'Ubah Data Produk',
        'tabel' => 'produk',
        'keterangan' => $changeDescription,
      ];

      insertData('log_aktivitas', $logData);
  } else {
      // Jika gagal, simpan pesan error ke dalam session
      $_SESSION['error_message'] = "Terjadi kesalahan saat mengupdate produk.";
  }
} else {
// Jika tidak ada permintaan tambah atau edit, simpan pesan error ke dalam session
$_SESSION['error_message'] = "Permintaan tidak valid!";
}

// Tutup koneksi ke database
mysqli_close($conn);

// Redirect kembali ke index.php setelah proses selesai
header("Location: index.php");
exit();