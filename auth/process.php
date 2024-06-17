<?php
require_once '../includes/function.php';

// Jika pengguna sudah login, arahkan ke halaman utama
if (isset($_SESSION['peran_pengguna'])) {
    redirectUser($_SESSION['peran_pengguna']);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Tentukan waktu kedaluwarsa cookie (dalam detik)
    $expiry_time = 86400 * 6; // 6 hari (6 * 24 jam * 60 menit * 60 detik)
    if (isset($_POST['login_submit'])) {
        // Tangani form login jika dikirimkan
        $username = $_POST["username"];
        $password = $_POST["password"];

        // Autentikasi pengguna
        $user = authenticateUser($username, $password);

        if ($user) {
            // Simpan informasi pengguna ke sesi
            $_SESSION['id_pengguna'] = $id_pengguna = $user['id_pengguna'];
            $_SESSION['peran_pengguna'] = $user['tipe_pengguna'];
            $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
            $_SESSION['nama_pengguna'] = $user['nama_pengguna'];

            // Pencatatan log aktivitas
            $aktivitas = 'Login berhasil';
            $tabel = 'Pengguna';
            $keterangan = 'Pengguna dengan username ' . $username . ' berhasil login.';
            $log_data = [
                'id_pengguna' => $id_pengguna,
                'aktivitas' => $aktivitas,
                'tabel' => $tabel,
                'keterangan' => $keterangan
            ];
            insertData('log_aktivitas', $log_data);

            // Set pesan login berhasil
            $_SESSION['success_message'] = "Login berhasil! Selamat datang, " . ucwords($user['nama_lengkap']) . ".";
    
            // Jika "ingat akun", set cookie
            if (isset($_POST['remember_me'])) {
                // Atur cookie dengan ID pengguna dan waktu kedaluwarsa yang ditentukan
                $user_id = $user['id_pengguna'];
                setcookie("ingat_user_id", $user_id, time() + $expiry_time, "/"); // Cookie berlaku selama waktu yang ditentukan
                setcookie("nama_pengguna", $user['nama_pengguna'], time() + $expiry_time, "/"); // Cookie berlaku selama waktu yang ditentukan
            } else {
                // Jika tidak "ingat akun", hapus cookie "ingat akun" jika ada
                if (isset($_COOKIE['ingat_user_id'])) {
                    setcookie("ingat_user_id", "", time() - 3600, "/"); // Hapus cookie "ingat akun"
                }
            }
    
            // Redirect ke halaman utama jika berhasil login
            header("Location: " . base_url('index.php'));
            exit();
        } else {
            // Jika autentikasi gagal, simpan pesan kesalahan
            $_SESSION['login_error'] = "Username atau password salah. Silakan coba lagi.";

            // Pencatatan log aktivitas
            $aktivitas = 'Login gagal';
            $tabel = 'Pengguna';
            $keterangan = 'Gagal login dengan username ' . $username . '. Username atau password salah.';
            $log_data = [
                'aktivitas' => $aktivitas,
                'tabel' => $tabel,
                'keterangan' => $keterangan
            ];
            insertData('log_aktivitas', $log_data);

            header("Location: " . base_url('auth/login'));
            exit();
        }
    } 
} else {
    // Jika bukan metode POST, arahkan ke halaman login
    header("Location: " . base_url('auth/login'));
    exit();
}