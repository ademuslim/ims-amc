<?php
require_once '../includes/function.php';

// Hapus semua data sesi
$_SESSION = array();

// Hapus cookie ingat_user_id
if (isset($_COOKIE['ingat_user_id'])) {
    setcookie('ingat_user_id', '', time() - 3600, '/');
}

// Hapus sesi
session_destroy();

// Redirect ke halaman login
header("Location: " . base_url('auth/login.php'));
exit();
?>