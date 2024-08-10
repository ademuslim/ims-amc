<?php // master-data/user/process
require '../../../includes/function.php';
require '../../../includes/vendor/autoload.php';

// Ambil id_pengguna dari sesi atau cookie untuk pencatatan log aktivitas
$id_pengguna_log = $_SESSION['id_pengguna'] ?? $_COOKIE['ingat_user_id'] ?? '';
$nama_pengguna_log = $_SESSION['nama_pengguna'] ?? '';

if (isset($_POST['add'])) {
  $nama_lengkap = strtolower($_POST['nama_lengkap']);
  $nama_pengguna = $_POST['nama_pengguna'];
  $password = $_POST['password'];
  $confirm_password = $_POST["confirm_password"];
  $tipe_pengguna = $_POST['tipe_pengguna'];

  // Pastikan kedua password tidak kosong
  if (empty($password) || empty($confirm_password)) {
    $_SESSION['error_message'] = "Password dan Ulangi Password harus diisi.";
    header("Location: " . base_url('pages/master-data/users'));
    exit();
  }

  // Periksa password dan ulangi password cocok
  if ($password !== $confirm_password) {
      $_SESSION['error_message'] = "Password dan Ulangi Password tidak cocok.";
      header("Location: " . base_url('pages/master-data/users'));
      exit();
  }

  // Periksa apakah nama_pengguna sudah ada
  if (isValueExists('pengguna', 'nama_pengguna', $nama_pengguna)) {
    $_SESSION['error_message'] = "Username sudah terdaftar.";
    header("Location: " . base_url('pages/master-data/users'));
    exit();
  }

  // Lakukan sanitasi pada input
  $nama_lengkap = sanitizeInput($nama_lengkap);
  $nama_pengguna = sanitizeInput($nama_pengguna);
  $password = sanitizeInput($password);
  
  // Generate UUID untuk kolom id_pengguna
  $id_pengguna = Ramsey\Uuid\Uuid::uuid4()->toString();

  // Lakukan registrasi pengguna
  $result = register($id_pengguna, $nama_lengkap, $nama_pengguna, $password, $tipe_pengguna);

  // Periksa apakah data berhasil ditambahkan
  if ($result > 0) {
      $_SESSION['success_message'] = "Pengguna berhasil ditambahkan!";

      // Pencatatan log aktivitas
      $aktivitas = 'Berhasil tambah data';
      $tabel = 'Pengguna';
      $keterangan = 'Pengguna dengan username ' . $nama_pengguna_log . ' berhasil tambah pengguna dengan username ' . $nama_pengguna;
      $log_data = [
          'id_pengguna' => $id_pengguna_log,
          'aktivitas' => $aktivitas,
          'tabel' => $tabel,
          'keterangan' => $keterangan
      ];
      insertData('log_aktivitas', $log_data);
      header("Location: " . base_url('pages/master-data/users'));
      exit();
  } else {
      // Jika gagal, simpan pesan error ke dalam session
      $_SESSION['error_message'] = "Terjadi kesalahan saat menambahkan pengguna.";
      header("Location: " . base_url('pages/master-data/users'));
      exit();
  }
} elseif (isset($_POST['edit'])) {
  // Ambil nilai-nilai dari form edit
  $id_pengguna = $_POST['id_pengguna'];
  $nama_lengkap = strtolower($_POST['nama_lengkap']);
  $nama_pengguna = strtolower($_POST['nama_pengguna']);
  $password_saat_ini = $_POST['password_saat_ini'];

  // Ambil data pengguna saat ini dari database
  $userData = selectData('pengguna', 'id_pengguna = ?', '', '', [['type' => 's', 'value' => $id_pengguna]]);
  $userData = $userData[0]; // Ambil baris pertama dari hasil query

   // Verifikasi password saat ini
  if (password_verify($password_saat_ini, $userData['password'])) {
    // Data yang akan diupdate di tabel pengguna
    $data = [
        'nama_lengkap' => $nama_lengkap,
        'nama_pengguna' => $nama_pengguna
    ];

    // Kondisi untuk menentukan pengguna mana yang akan diupdate
    $conditions = "id_pengguna = '$id_pengguna'";

    // Panggil fungsi updateData untuk mengupdate data di tabel pengguna
    $result = updateData('pengguna', $data, $conditions);

    // Periksa apakah data berhasil diupdate
    if ($result > 0) {
        $_SESSION['success_message'] = "Pengguna berhasil diupdate!";

        // Catat aktivitas
        $logData = [
            'id_pengguna' => $_SESSION['id_pengguna'],
            'aktivitas' => 'Berhasil ubah data',
            'tabel' => 'pengguna',
            'keterangan' => 'Pengguna berhasil mengubah data',
        ];

        insertData('log_aktivitas', $logData);
        header("Location: " . base_url('pages/master-data/users'));
        exit();
    } else {
        // Jika gagal, simpan pesan error ke dalam session
        $_SESSION['error_message'] = "Terjadi kesalahan saat mengupdate pengguna.";
        header("Location: " . base_url('pages/master-data/users'));
        exit();
    }
  } else {
      // Jika password salah, simpan pesan error ke dalam session
      $_SESSION['error_message'] = "Password tidak valid.";
      header("Location: " . base_url('pages/master-data/users'));
      exit();
  }
} elseif (isset($_POST['change_password'])) {
  // Ambil nilai-nilai dari form change password
  $id_pengguna = $_POST['id_pengguna'];
  $password_lama = $_POST['password_lama'];
  $password_baru = $_POST['password_baru'];
  $ulangi_password = $_POST['ulangi_password'];

  // Ambil data pengguna saat ini dari database
  $userData = selectData('pengguna', 'id_pengguna = ?', '', '', [['type' => 's', 'value' => $id_pengguna]]);
  $userData = $userData[0]; // Ambil baris pertama dari hasil query

  // Periksa apakah password lama benar
  if (!password_verify($password_lama, $userData['password'])) {
    $_SESSION['error_message'] = "Password lama salah.";
    header("Location: " . base_url('pages/master-data/users'));
    exit();
  }

  // Periksa apakah password baru cocok dengan ulangi password
  if ($password_baru !== $ulangi_password) {
    $_SESSION['error_message'] = "Password baru dan ulangi password tidak cocok.";
    header("Location: " . base_url('pages/master-data/users'));
    exit();
  }

  // Hash password baru
  $password_baru_hashed = password_hash($password_baru, PASSWORD_DEFAULT);

  // Data yang akan diupdate di tabel pengguna
  $data = [
    'password' => $password_baru_hashed,
  ];

  // Kondisi untuk menentukan pengguna mana yang akan diupdate
  $conditions = "id_pengguna = '$id_pengguna'";

  // Panggil fungsi updateData untuk mengupdate data di tabel pengguna
  $result = updateData('pengguna', $data, $conditions);

  // Periksa apakah data berhasil diupdate
  if ($result > 0) {
    $_SESSION['success_message'] = "Password berhasil diubah!";

    // Catat aktivitas
    $logData = [
      'id_pengguna' => $_SESSION['id_pengguna'],
      'aktivitas' => 'Berhasil ubah password',
      'tabel' => 'pengguna',
      'keterangan' => 'Pengguna berhasil mengubah password',
    ];

    insertData('log_aktivitas', $logData);
  } else {
    // Jika gagal, simpan pesan error ke dalam session
    $_SESSION['error_message'] = "Terjadi kesalahan saat mengubah password.";
    header("Location: " . base_url('pages/master-data/users'));
    exit();
  }
} else {
  // Jika tidak ada permintaan tambah, edit, atau ubah password, simpan pesan error ke dalam session
  $_SESSION['error_message'] = "Permintaan tidak valid!";
}

// Tutup koneksi ke database
mysqli_close($conn);

// Redirect kembali ke halaman pengguna setelah proses selesai
header("Location: " . base_url('pages/master-data/users'));
exit();