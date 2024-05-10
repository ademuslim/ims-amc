<?php
$page_title = "Dashboard";
require_once '../../includes/header.php';

// Jika pengguna bukan super_admin dan staff, arahkan ke halaman akses ditolak
if ($_SESSION['peran_pengguna'] !== 'superadmin' && $_SESSION['peran_pengguna'] !== 'staff') {
  header("Location: " . base_url('pages/access-denied.php'));
  exit();
}

// Periksa apakah ada pesan login berhasil dalam session
if (isset($_SESSION['login_success'])) {
  ?>
<div id="myAlert" class="alert alert-success" role="alert"><?= $_SESSION['login_success'] ?></div>
<?php
    unset($_SESSION['login_success']);
  }
?>

<h1>pages/dashboard</h1>

<script>
// Pemanggilan showAlert() dipindahkan ke bagian bawah halaman setelah elemen alert dibuat
window.onload = function() {
  showAlert();
};

// Fungsi untuk menampilkan alert
function showAlert() {
  // Menampilkan alert
  var alert = document.getElementById('myAlert');
  alert.style.display = 'block';

  // Menghilangkan alert setelah 5 detik
  setTimeout(function() {
    alert.style.display = 'none';
  }, 5000); // Waktu dalam milidetik (misalnya, 5000 ms = 5 detik)
}
</script>
<?php
require_once '../../includes/footer.php';
?>