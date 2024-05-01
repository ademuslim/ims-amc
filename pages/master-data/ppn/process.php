<?php // master-data/ppn/proses
require '../../../includes/function.php';
require '../../../includes/vendor/autoload.php';

if (isset($_POST['add'])) {
  // Ambil nilai-nlai langsung dari $_POST
  $jenis_ppn = strtolower($_POST['jenis_ppn']);
  $tarif = $_POST['tarif'];
  $keterangan = strtolower($_POST['keterangan']);

  // Periksa apakah nomor ppn sudah ada
  if (isValueExists('ppn', 'jenis_ppn', $jenis_ppn)) {
    $_SESSION['error_message'] = "Jenis PPN sudah ada dalam database.";
    header("Location: index.php");
    exit();
  }

  // Generate UUID untuk kolom id_ppn
  $id_ppn = Ramsey\Uuid\Uuid::uuid4()->toString();

  // Data yang akan dimasukkan ke dalam tabel PPN
  $data = [
      'id_ppn' => $id_ppn,
      'jenis_ppn' => $jenis_ppn,
      'tarif' => $tarif,
      'keterangan' => $keterangan
  ];

  // Panggil fungsi insertData untuk menambahkan data ke dalam tabel PPN
  $result = insertData('ppn', $data);

  // Periksa apakah data berhasil ditambahkan
  if ($result > 0) {
      // Jika berhasil, simpan pesan sukses ke dalam session
      $_SESSION['success_message'] = "PPN berhasil ditambahkan!";
  } else {
      // Jika gagal, simpan pesan error ke dalam session
      $_SESSION['error_message'] = "Terjadi kesalahan saat menambahkan PPN.";
  }
}elseif (isset($_POST['edit'])) {
  // Ambil nilai-nilai dari form edit
  $id_ppn = $_POST['id_ppn'];
  $jenis_ppn = strtolower($_POST['jenis_ppn']);
  $tarif = $_POST['tarif'];
  $keterangan = strtolower($_POST['keterangan']);

  // Periksa apakah nomor PPN sudah ada (kecuali untuk PPN yang sedang diedit)
  if (isValueExists('ppn', 'jenis_ppn', $jenis_ppn, $id_ppn, 'id_ppn')) {
    $_SESSION['error_message'] = "Jenis PPN sudah ada dalam database.";
    header("Location: index.php");
    exit();
  }

  // Data yang akan diupdate di tabel PPN
  $data = [
    'jenis_ppn' => $jenis_ppn,
    'tarif' => $tarif,
    'keterangan' => $keterangan
  ];

  // Kondisi untuk menentukan PPN mana yang akan diupdate
  $conditions = "id_ppn = '$id_ppn'";

  // Panggil fungsi updateData untuk mengupdate data di tabel PPN
  $result = updateData('ppn', $data, $conditions);

  // Periksa apakah data berhasil diupdate
  if ($result > 0) {
      // Jika berhasil, simpan pesan sukses ke dalam session
      $_SESSION['success_message'] = "PPN berhasil diupdate!";
  } else {
      // Jika gagal, simpan pesan error ke dalam session
      $_SESSION['error_message'] = "Terjadi kesalahan saat mengupdate PPN.";
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