<?php
require_once '../includes/function.php';

// Periksa apakah pengguna sudah login
if (isset($_SESSION['peran_pengguna'])) {
    // Jika sudah login, arahkan pengguna sesuai peran
    redirectUser($_SESSION['peran_pengguna']);
} elseif (isset($_COOKIE['ingat_user_id'])) {
    // Jika tidak ada sesi, tapi ada cookie "ingat akun", coba lakukan otentikasi berdasarkan cookie
    $ingat_user_id = $_COOKIE['ingat_user_id'];
    
    // Lakukan otentikasi berdasarkan cookie
    if (authenticateByUserId($ingat_user_id)) {
        $peran_pengguna = getUserRoleById($ingat_user_id); // Dapatkan peran pengguna berdasarkan ID pengguna
        $_SESSION['peran_pengguna'] = $peran_pengguna;
        redirectUser($peran_pengguna);
    } else {
        // Jika otentikasi gagal, arahkan ke halaman login
        header("Location: " . base_url('auth/login.php'));
        exit();
    }
} else {
    // Jika tidak ada sesi dan tidak ada cookie "ingat akun", arahkan ke halaman login
    header("Location: " . base_url('auth/login.php'));
    exit();
}