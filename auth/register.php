<?php
require_once '../includes/function.php';

// Jika pengguna sudah login, arahkan ke halaman utama
if (isset($_SESSION['peran_pengguna'])) {
    redirectUser($_SESSION['peran_pengguna']);
}

// Inisialisasi variabel error
$registration_error = "";

// Jika terdapat pesan kesalahan dari URL
if (isset($_GET['error'])) {
    $registration_error = $_GET['error'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registrasi</title>
</head>

<body>
  <h2>Registrasi</h2>
  <?php if (!empty($registration_error)) { ?>
  <p><?php echo $registration_error; ?></p>
  <?php } ?>
  <form method="post" action="process.php">
    <label for="nama_pengguna">Nama Pengguna:</label><br>
    <input type="text" id="nama_pengguna" name="nama_pengguna" required><br>
    <label for="email">Email:</label><br>
    <input type="email" id="email" name="email" required><br>
    <label for="password">Password:</label><br>
    <input type="password" id="password" name="password" required><br>
    <label for="confirm_password">Ulangi Password:</label><br>
    <input type="password" id="confirm_password" name="confirm_password" required><br>
    <button type="submit" name="register_submit">Registrasi</button> <!-- Tandai tombol submit untuk registrasi -->
  </form>
  <a href="login.php">Login</a>
</body>

</html>