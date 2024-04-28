<?php
require_once '../../includes/header.php';

// Jika pengguna bukan kepala_perusahaan dan bukan super_admin, arahkan ke halaman akses ditolak
if ($_SESSION['peran_pengguna'] !== 'kepala_perusahaan' && $_SESSION['peran_pengguna'] !== 'superadmin') {
  header("Location: " . base_url('pages/access-denied.php'));
  exit();
}
?>

<h1>pages/dashboard-views</h1>

<?php
require_once '../../includes/footer.php';
?>