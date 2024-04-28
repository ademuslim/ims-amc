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
}

// Inisialisasi variabel error
$login_error = "";

// Jika terdapat pesan kesalahan dari URL
if (isset($_GET['error'])) {
    $login_error = $_GET['error'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
</head>

<body>
  <h2>Login</h2>
  <?php if (!empty($login_error)) { ?>
  <p><?php echo $login_error; ?></p>
  <?php } ?>
  <form method="post" action="process.php">
    <label for="email">Email:</label><br>
    <input type="text" id="email" name="email"><br>
    <label for="password">Password:</label><br>
    <input type="password" id="password" name="password"><br>
    <input type="checkbox" id="remember_me" name="remember_me">
    <label for="remember_me">Ingat Saya</label><br>
    <button type="submit" name="login_submit">Login</button> <!-- Tandai tombol submit untuk login -->
  </form>
  <a href="register.php">Daftar</a>
</body>

</html>