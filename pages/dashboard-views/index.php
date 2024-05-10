<?php
$page_title = "Dashboard View";
require_once '../../includes/header.php';

// Jika pengguna bukan kepala_perusahaan dan bukan super_admin, arahkan ke halaman akses ditolak
if ($_SESSION['peran_pengguna'] !== 'kepala_perusahaan' && $_SESSION['peran_pengguna'] !== 'superadmin') {
  header("Location: " . base_url('pages/access-denied.php'));
  exit();
}

// Periksa apakah ada pesan login berhasil dalam session
if (isset($_SESSION['login_success'])) {
  // Tampilkan pesan login berhasil
  echo '<div class="alert alert-success" role="alert">' . $_SESSION['login_success'] . '</div>';

  // Setelah menampilkan pesan, hapus pesan dari session agar tidak ditampilkan lagi setelah reload
  unset($_SESSION['login_success']);
}
?>

<h1>pages/dashboard-views</h1>

<?php
require_once '../../includes/footer.php';
?>