<?php // master-data/user/process
require '../../../includes/function.php';
require '../../../includes/vendor/autoload.php';

if (isset($_POST['add'])) {
  // Ambil nilai-nlai langsung dari $_POST
  $nama_pengguna = strtolower($_POST['nama_pengguna']);
  $email = $_POST['email'];
  $password = $_POST['password'];
  $confirm_password = $_POST["confirm_password"];
  $tipe_pengguna = $_POST['tipe_pengguna'];

  // Pastikan kedua password tidak kosong
  if (empty($password) || empty($confirm_password)) {
    $register_error = "Password dan Ulangi Password harus diisi.";
    header("Location: " . base_url('auth/register.php?error=' . urlencode($register_error)));
    exit();
  }

  // Periksa apakah password dan ulangi password cocok
  if ($password !== $confirm_password) {
      // Jika tidak cocok, kembalikan ke halaman registrasi dengan pesan kesalahan
      $register_error = "Password dan Ulangi Password tidak cocok.";
      header("Location: " . base_url('auth/register.php?error=' . urlencode($register_error)));
      exit();
  }

  // Periksa apakah email sudah ada
  if (isValueExists('pengguna', 'email', $email)) {
    $_SESSION['error_message'] = "Email sudah terdaftar.";
    header("Location: index.php");
    exit();
  }

  // Lakukan sanitasi pada input
  $nama_pengguna = sanitizeInput($nama_pengguna);
  $email = sanitizeInput($email);
  $password = sanitizeInput($password);
  
  // Generate UUID untuk kolom id_pengguna
  $id_pengguna = Ramsey\Uuid\Uuid::uuid4()->toString();

  // Lakukan registrasi pengguna
  $result = register($id_pengguna, $nama_pengguna, $email, $password, $tipe_pengguna);

  // Periksa apakah data berhasil ditambahkan
  if ($result > 0) {
      // Jika berhasil, simpan pesan sukses ke dalam session
      $_SESSION['success_message'] = "Pengguna berhasil ditambahkan!";
  } else {
      // Jika gagal, simpan pesan error ke dalam session
      $_SESSION['error_message'] = "Terjadi kesalahan saat menambahkan pengguna.";
  }
} elseif (isset($_POST['edit'])) {
  // Ambil nilai-nilai dari form edit
  $id_pengguna = $_POST['id_pengguna'];
  $nama_pengguna = strtolower($_POST['nama_pengguna']);
  $email = $_POST['email'];
  $password = $_POST['password'];
  $tipe_pengguna = $_POST['tipe_pengguna'];

  // Periksa apakah email sudah ada (kecuali untuk pengguna yang sedang diedit)
  if (isValueExists('pengguna', 'email', $email, $id_pengguna, 'id_pengguna')) {
    $_SESSION['error_message'] = "Email sudah terdaftar.";
    header("Location: index.php");
    exit();
  }

  // Data yang akan diupdate di tabel pengguna
  $data = [
    'nama_pengguna' => $nama_pengguna,
    'email' => $email,
    'password' => $password,
    'tipe_pengguna' => $tipe_pengguna
  ];

  // Kondisi untuk menentukan pengguna mana yang akan diupdate
  $conditions = "id_pengguna = '$id_pengguna'";

  // Panggil fungsi updateData untuk mengupdate data di tabel pengguna
  $result = updateData('pengguna', $data, $conditions);

  // Periksa apakah data berhasil diupdate
  if ($result > 0) {
      // Jika berhasil, simpan pesan sukses ke dalam session
      $_SESSION['success_message'] = "Pengguna berhasil diupdate!";
  } else {
      // Jika gagal, simpan pesan error ke dalam session
      $_SESSION['error_message'] = "Terjadi kesalahan saat mengupdate pengguna.";
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