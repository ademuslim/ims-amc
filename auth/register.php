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
<html lang="en" data-bs-theme="auto">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register | Invoice Management System</title>
  <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css'); ?>">
  <script src="<?= base_url('assets/js/jquery.js'); ?>"></script>
</head>

<body class="bg-light">
  <div class="container min-vh-100 d-flex flex-column align-items-center justify-content-center  position-relative">
    <h1 class="fs-2 fw-bold text-center mb-5" style="color: transparent; -webkit-text-stroke: 1px #666;">IMS
      AMC</h1>
    <div class="card border border-light shadow p-4 w-50" style="min-width:350px;">
      <form method="post" action="process.php">
        <p>Silahkan daftar</p>

        <div class="form-floating mb-3">
          <input type="text" class="form-control" id="nama_pengguna" name="nama_pengguna"
            placeholder="Masukkan nama pengguna">
          <label for="nama_pengguna">Nama Pengguna</label>
        </div>

        <div class="form-floating mb-3">
          <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com">
          <label for="email">Email</label>
        </div>

        <div class="form-floating mb-3">
          <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password">
          <label for="password">Password</label>
        </div>

        <div class="form-floating mb-3">
          <input type="password" class="form-control" id="confirm_password" name="confirm_password"
            placeholder="Ulangi password">
          <label for="confirm_password">Ulangi Password</label>
        </div>

        <?php if (!empty($registration_error)) { ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <p class="fs-6 mb-0"><?php echo $registration_error; ?></p>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php } ?>
        <div class="d-flex flex-row justify-content-end align-items-center">
          <p class="my-auto">Sudah punya akun? <a href="login.php" class="text-dark">Login</a>.</p>
          <button class="btn btn-dark ms-3" type="submit" name="register_submit">REGISTER</button>
        </div>
      </form>
    </div>

    <p class="fs-6 position-absolute bottom-0 end-0 mt-5 mb-3 text-body-secondary">&copy; 2024 IMS By AMC
    </p>
  </div>

  <script src="<?= base_url('assets/js/popper.min.js'); ?>"></script>
  <script src="<?= base_url('assets/js/bootstrap.min.js'); ?>"></script>
  <script>
  // Memberikan fokus otomatis pada input email saat halaman dimuat
  window.onload = function() {
    document.getElementById("nama_pengguna").focus();
  };
  </script>
</body>

</html>