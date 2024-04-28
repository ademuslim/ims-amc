<?php // master-data/product/proses
require '../../../includes/function.php';
require '../../../includes/vendor/autoload.php';

if (isset($_POST['add'])) {
  // Ambil nilai-nlai langsung dari $_POST
  $nama_pelanggan = strtolower($_POST['nama_pelanggan']);
  $alamat = strtolower($_POST['alamat']);
  $telepon = $_POST['telepon'];
  $email = $_POST['email'];
  $keterangan = strtolower($_POST['keterangan']);

  // Periksa apakah nomor hp sudah ada
  if (isValueExists('pelanggan', 'telepon', $telepon)) {
    $_SESSION['error_message'] = "Nomor handphone sudah ada dalam database.";
    header("Location: index.php");
    exit();
  }

  // Generate UUID untuk kolom id_pelanggan
  $id_pelanggan = Ramsey\Uuid\Uuid::uuid4()->toString();

  // Data yang akan dimasukkan ke dalam tabel produk
  $data = [
      'id_pelanggan' => $id_pelanggan,
      'nama_pelanggan' => $nama_pelanggan,
      'alamat' => $alamat,
      'telepon' => $telepon,
      'email' => $email,
      'keterangan' => $keterangan
  ];

  // Panggil fungsi insertData untuk menambahkan data ke dalam tabel produk
  $result = insertData('pelanggan', $data);

  // Periksa apakah data berhasil ditambahkan
  if ($result > 0) {
      // Jika berhasil, simpan pesan sukses ke dalam session
      $_SESSION['success_message'] = "Data pelanggan berhasil ditambahkan!";
  } else {
      // Jika gagal, simpan pesan error ke dalam session
      $_SESSION['error_message'] = "Terjadi kesalahan saat menambahkan data pelanggan.";
  }
}elseif (isset($_POST['edit'])) {
  // Ambil nilai-nilai dari form edit
  $id_pelanggan = $_POST['id_pelanggan'];
  $nama_pelanggan = strtolower($_POST['nama_pelanggan']);
  $alamat = strtolower($_POST['alamat']);
  $telepon = $_POST['telepon'];
  $email = $_POST['email'];
  $keterangan = strtolower($_POST['keterangan']);

  // Periksa apakah nomor hp sudah ada
  if (isValueExists('pelanggan', 'telepon', $telepon, $id_pelanggan, 'id_pelanggan')) {
    $_SESSION['error_message'] = "Nomor handphone sudah ada dalam database.";
    header("Location: index.php");
    exit();
  }

  // Data yang akan diupdate di tabel produk
  $data = [
    'nama_pelanggan' => $nama_pelanggan,
    'alamat' => $alamat,
    'telepon' => $telepon,
    'email' => $email,
    'keterangan' => $keterangan
  ];

  // Kondisi untuk menentukan kontak mana yang akan diupdate
  $conditions = "id_pelanggan = '$id_pelanggan'";

  // Panggil fungsi updateData untuk mengupdate data di tabel data pelanggan
  $result = updateData('pelanggan', $data, $conditions);

  // Periksa apakah data berhasil diupdate
  if ($result > 0) {
      // Jika berhasil, simpan pesan sukses ke dalam session
      $_SESSION['success_message'] = "data pelanggan berhasil diupdate!";
  } else {
      // Jika gagal, simpan pesan error ke dalam session
      $_SESSION['error_message'] = "Terjadi kesalahan saat mengupdate data pelanggan.";
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