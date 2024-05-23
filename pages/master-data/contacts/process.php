<?php // master-data/contacts/process
require '../../../includes/function.php';
require '../../../includes/vendor/autoload.php';

// Tentukan tabel kontak
$table_name = 'kontak';

// Proses penambahan data
if (isset($_POST['add'])) {
    $nama_kontak = strtolower($_POST['nama_kontak']);
    $email = $_POST['email'];
    $telepon = $_POST['telepon'];
    $alamat = strtolower($_POST['alamat']);
    $keterangan = strtolower($_POST['keterangan']);
    $kategori = strtolower($_POST['category']);

    // Periksa apakah nomor HP sudah ada, jika nomor HP tidak kosong
    if (!empty($telepon) && isValueExists('kontak', 'telepon', $telepon)) {
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

  // Panggil fungsi insertData untuk menambahkan data ke dalam tabel produk
  $result = insertData($table_name, $data);

  // Periksa apakah data berhasil ditambahkan
  if ($result > 0) {
      // Jika berhasil, simpan pesan sukses ke dalam session
      $_SESSION['success_message'] = "Kontak internal berhasil ditambahkan!";
  } else {
      // Jika gagal, simpan pesan error ke dalam session
      $_SESSION['error_message'] = "Terjadi kesalahan saat menambahkan kontak internal.";
  }
}elseif (isset($_POST['edit'])) {
  $id_kontak = $_POST['id_kontak'];
  $nama_kontak = strtolower($_POST['nama_kontak']);
  $email = $_POST['email'];
  $telepon = $_POST['telepon'];
  $alamat = strtolower($_POST['alamat']);
  $keterangan = strtolower($_POST['keterangan']);
  $kategori = strtolower($_POST['category']);

  // Periksa apakah nomor hp sudah ada
  if (isValueExists($table_name, 'telepon', $telepon, $id_kontak, 'id_kontak')) {
      $_SESSION['error_message'] = "Nomor handphone sudah ada dalam database.";
      header("Location: index.php?category=$kategori");
      exit();
  }

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

  // Panggil fungsi updateData untuk mengupdate data di tabel
  $result = updateData($table_name, $data, $conditions);

  // Periksa apakah data berhasil diupdate
  if ($result > 0) {
      // Jika berhasil, simpan pesan sukses ke dalam session
      $_SESSION['success_message'] = "Kontak internal berhasil diupdate!";
  } else {
      // Jika gagal, simpan pesan error ke dalam session
      $_SESSION['error_message'] = "Terjadi kesalahan saat mengupdate kontak internal.";
  }
} else {
// Jika tidak ada permintaan tambah atau edit, simpan pesan error ke dalam session
$_SESSION['error_message'] = "Permintaan tidak valid!";
}

// Tutup koneksi ke database
mysqli_close($conn);

// Redirect kembali ke index.php setelah proses selesai
header("Location: index.php?category=$kategori");
exit();