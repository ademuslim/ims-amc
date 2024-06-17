<?php
require '../../../includes/function.php';
require '../../../includes/vendor/autoload.php';

// Ambil id_pengguna dari sesi atau cookie untuk pencatatan log aktivitas
$id_pengguna = $_SESSION['id_pengguna'] ?? $_COOKIE['ingat_user_id'] ?? '';

$table_name = 'kontak';

if (isset($_POST['add'])) {
    $nama_kontak = strtolower($_POST['nama_kontak']);
    $email = $_POST['email'];
    $telepon = $_POST['telepon'];
    $alamat = strtolower($_POST['alamat']);
    $keterangan = strtolower($_POST['keterangan']);
    $kategori = strtolower($_POST['category']);

    // Periksa apakah email atau nomor HP sudah ada, jika tidak kosong
    if (!empty($email) && isValueExists($table_name, 'email', $email)) {
        $_SESSION['error_message'] = "Email sudah ada dalam database.";
        header("Location: index.php?category=$kategori");
        exit();
    }
    
    if (!empty($telepon) && isValueExists($table_name, 'telepon', $telepon)) {
        $_SESSION['error_message'] = "Nomor handphone sudah ada dalam database.";
        header("Location: index.php?category=$kategori");
        exit();
    }
    
  // Generate UUID untuk kolom id_pengirim
  $id_kontak = Ramsey\Uuid\Uuid::uuid4()->toString();

  // Data yang akan dimasukkan ke dalam tabel produk
  $data = [
      'id_kontak' => $id_kontak,
      'nama_kontak' => $nama_kontak,
      'email' => $email,
      'telepon' => $telepon,
      'alamat' => $alamat,
      'keterangan' => $keterangan,
      'kategori' => $kategori
  ];

  // Panggil fungsi insertData untuk menambahkan data ke dalam tabel kontak
  $result = insertData($table_name, $data);

  // Periksa apakah data berhasil ditambahkan
  if ($result > 0) {
      $_SESSION['success_message'] = "Kontak berhasil ditambahkan!";

      // Pencatatan log aktivitas
      $aktivitas = 'Berhasil tambah data';
      $tabel = 'Kontak';
      $keterangan = "Berhasil tambah kontak dengan ID $id_kontak";
      $log_data = [
          'id_pengguna' => $id_pengguna,
          'aktivitas' => $aktivitas,
          'tabel' => $tabel,
          'keterangan' => $keterangan
      ];
      insertData('log_aktivitas', $log_data);
  } else {
      $_SESSION['error_message'] = "Terjadi kesalahan saat menambahkan kontak.";

      // Pencatatan log aktivitas
      $aktivitas = 'Gagal tambah data';
      $tabel = 'Kontak';
      $keterangan = "Gagal tambah kontak dengan kategori $kategori";
      $log_data = [
          'id_pengguna' => $id_pengguna,
          'aktivitas' => $aktivitas,
          'tabel' => $tabel,
          'keterangan' => $keterangan
      ];
      insertData('log_aktivitas', $log_data);
  }

  header("Location: index.php?category=$kategori");
  exit();
}elseif (isset($_POST['edit'])) {
  $id_kontak = $_POST['id_kontak'];
  $nama_kontak = strtolower($_POST['nama_kontak']);
  $email = $_POST['email'];
  $telepon = $_POST['telepon'];
  $alamat = strtolower($_POST['alamat']);
  $keterangan = strtolower($_POST['keterangan']);
  $kategori = strtolower($_POST['category']);

  // Periksa apakah email atau nomor HP sudah ada, jika tidak kosong dan bukan milik kontak yang sedang diedit
  if (!empty($email) && isValueExists($table_name, 'email', $email, $id_kontak, 'id_kontak')) {
    $_SESSION['error_message'] = "Email sudah ada dalam database.";
    header("Location: index.php?category=$kategori");
    exit();
  }

  if (!empty($telepon) && isValueExists($table_name, 'telepon', $telepon, $id_kontak, 'id_kontak')) {
      $_SESSION['error_message'] = "Nomor handphone sudah ada dalam database.";
      header("Location: index.php?category=$kategori");
      exit();
  }

  // Ambil data lama sebelum diubah
  $oldData = selectData('kontak', 'id_kontak = ?', '', '', [['type' => 's', 'value' => $id_kontak]]);

  // Data yang akan diupdate di tabel
  $data = [
    'nama_kontak' => $nama_kontak,
    'email' => $email,
    'telepon' => $telepon,
    'alamat' => $alamat,
    'keterangan' => $keterangan
  ];

  // Kondisi untuk menentukan kontak mana yang akan diupdate
  $conditions = "id_kontak = '$id_kontak'";
  // Panggil fungsi updateData untuk mengupdate data di tabel kontak
  $result = updateData($table_name, $data, $conditions);

  // Periksa apakah data berhasil diupdate
  if ($result > 0) {
      $_SESSION['success_message'] = "Kontak berhasil diupdate!";

      // Ambil data setelah diubah
      $newData = selectData('kontak', 'id_kontak = ?', '', '', [['type' => 's', 'value' => $id_kontak]]);

      // Data sebelum dan sesudah perubahan untuk log
      $before = $oldData[0]; // Ambil baris pertama dari hasil query
      $after = $newData[0]; // Ambil baris pertama dari hasil query

      // Keterangan perubahan
      $changeDescription = "Perubahan data kontak dengan ID $id_kontak: | ";

      // Nomor urut untuk tanda "-"
      $counter = 1;

      // Periksa setiap kolom untuk menemukan perubahan
      foreach ($before as $column => $value) {
          if ($value !== $after[$column]) {
              $changeDescription .= "$counter. $column: \"$value\" diubah menjadi \"$after[$column]\" | ";
              $counter++;
          }
      }
      
      $logData = [
        'id_pengguna' => $_SESSION['id_pengguna'], // pastikan ini sesuai dengan session atau cara penyimpanan ID pengguna di aplikasi kamu
        'aktivitas' => 'Berhasil ubah data',
        'tabel' => 'kontak',
        'keterangan' => $changeDescription,
      ];

      insertData('log_aktivitas', $logData);
  } else {
      $_SESSION['error_message'] = "Terjadi kesalahan saat mengupdate kontak.";
  }

  header("Location: index.php?category=$kategori");
  exit();
} else {
  // Jika tidak ada permintaan tambah atau edit, simpan pesan error ke dalam session
  $_SESSION['error_message'] = "Permintaan tidak valid!";
  header("Location: index.php?category=$kategori");
  exit();
}

// Tutup koneksi ke database
mysqli_close($conn);
?>