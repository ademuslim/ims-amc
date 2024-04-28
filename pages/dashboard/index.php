<?php
require_once '../../includes/header.php';

// Jika pengguna bukan super_admin dan staff, arahkan ke halaman akses ditolak
if ($_SESSION['peran_pengguna'] !== 'superadmin' && $_SESSION['peran_pengguna'] !== 'staff') {
  header("Location: " . base_url('pages/access-denied.php'));
  exit();
}
?>

<h1>pages/dashboard</h1>

<?php
require_once '../../includes/footer.php';
?>