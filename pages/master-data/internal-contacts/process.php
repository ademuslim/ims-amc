<?php // master-data/product/proses
require '../../../includes/function.php';
require '../../../includes/vendor/autoload.php';

if (isset($_POST['add'])) {
  // Ambil nilai-nlai langsung dari $_POST
  $nama_pengirim = strtolower($_POST['nama_pengirim']);
  $alamat_pengirim = strtolower($_POST['alamat_pengirim']);
  $telepon = $_POST['telepon'];
  $email = $_POST['email'];

  // Periksa apakah nomor hp sudah ada
  if (isValueExists('kontak_internal', 'telepon', $telepon)) {
    $_SESSION['error_message'] = "Nomor handphone sudah ada dalam database.";
    header("Location: index.php");
    exit();
  }

  // Generate UUID untuk kolom id_pengirim
  $id_pengirim = Ramsey\Uuid\Uuid::uuid4()->toString();

  // Data yang akan dimasukkan ke dalam tabel produk
  $data = [
      'id_pengirim' => $id_pengirim,
      'nama_pengirim' => $nama_pengirim,
      'alamat_pengirim' => $alamat_pengirim,
      'telepon' => $telepon,
      'email' => $email
  ];

  // Panggil fungsi insertData untuk menambahkan data ke dalam tabel produk
  $result = insertData('kontak_internal', $data);

  // Periksa apakah data berhasil ditambahkan
  if ($result > 0) {
      // Jika berhasil, simpan pesan sukses ke dalam session
      $_SESSION['success_message'] = "Kontak internal berhasil ditambahkan!";
  } else {
      // Jika gagal, simpan pesan error ke dalam session
      $_SESSION['error_message'] = "Terjadi kesalahan saat menambahkan kontak internal.";
  }
}elseif (isset($_POST['edit'])) {
  // Ambil nilai-nilai dari form edit
  $id_pengirim = $_POST['id_pengirim'];
  $nama_pengirim = strtolower($_POST['nama_pengirim']);
  $alamat_pengirim = strtolower($_POST['alamat_pengirim']);
  $telepon = $_POST['telepon'];
  $email = $_POST['email'];

  // Periksa apakah nomor hp sudah ada
  if (isValueExists('kontak_internal', 'telepon', $telepon, $id_pengirim, 'id_pengirim')) {
    $_SESSION['error_message'] = "Nomor handphone sudah ada dalam database.";
    header("Location: index.php");
    exit();
  }

  // Data yang akan diupdate di tabel produk
  $data = [
    'nama_pengirim' => $nama_pengirim,
    'alamat_pengirim' => $alamat_pengirim,
    'telepon' => $telepon,
    'email' => $email
  ];

  // Kondisi untuk menentukan kontak mana yang akan diupdate
  $conditions = "id_pengirim = '$id_pengirim'";

  // Panggil fungsi updateData untuk mengupdate data di tabel kontak internal
  $result = updateData('kontak_internal', $data, $conditions);

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
header("Location: index.php");
exit();