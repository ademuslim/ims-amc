<?php
require '../../../includes/function.php';
require '../../../includes/vendor/autoload.php';

// echo var_dump($_POST);
// Memberhentikan eksekusi setelah var_dump
// exit();

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
  } else {
      // Jika gagal, simpan pesan error ke dalam session
      $_SESSION['error_message'] = "Terjadi kesalahan saat menambahkan produk.";
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