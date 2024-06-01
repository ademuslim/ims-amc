<?php
require_once '../includes/function.php';
require '../includes/vendor/autoload.php';


// Periksa apakah ada id_pengguna dalam sesi
if(isset($_SESSION['id_pengguna'])){
    // Simpan id_pengguna sebelum dihapus dari sesi
    $id_pengguna = $_SESSION['id_pengguna'];
} else {
    // Jika tidak ada id_pengguna dalam sesi, set nilai default
    $id_pengguna = 'unknown';
}

// Hapus semua data sesi
$_SESSION = array();

// Hapus cookie ingat_user_id
if (isset($_COOKIE['ingat_user_id'])) {
    setcookie('ingat_user_id', '', time() - 3600, '/');
}

// Hapus sesi
session_destroy();

// Pencatatan log aktivitas
$id_log = Ramsey\Uuid\Uuid::uuid4()->toString();
$aktivitas = 'Log Out';
$keterangan = 'Pengguna dengan ID ' . $id_pengguna . ' keluar dari sistem.';
$log_data = [
    'id_log' => $id_log,
    'id_pengguna' => $id_pengguna, // Menambahkan id_pengguna dalam pencatatan log
    'aktivitas' => $aktivitas,
    'keterangan' => $keterangan,
];
insertData('log_aktivitas', $log_data);

// Redirect ke halaman login
header("Location: " . base_url('auth/login.php'));
exit();
?>