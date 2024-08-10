<?php
require_once 'function.php';

if (!checkLoginStatus()) {
    header("Location: " . base_url('auth/login.php'));
    exit();
}

// Ambil id_pengguna dari sesi atau cookie untuk pencatatan log aktivitas
$id_pengguna = $_SESSION['id_pengguna'] ?? $_COOKIE['ingat_user_id'] ?? '';
$nama_pengguna = $_SESSION['nama_pengguna'] ?? $_COOKIE['nama_pengguna'] ?? '';
$nama_lengkap = $_SESSION['nama_lengkap'] ?? $_COOKIE['nama_lengkap'] ?? '';
$peran_pengguna = $_SESSION['peran_pengguna'] ?? $_COOKIE['peran_pengguna'] ?? '';

// Tampilkan pesan sukses jika ada
if (isset($_SESSION['success_message'])) {
  $success_message = $_SESSION['success_message'];
  unset($_SESSION['success_message']);
} else {
  $success_message = '';
}

// Tampilkan pesan error jika ada
if (isset($_SESSION['error_message'])) {
  $error_message = $_SESSION['error_message'];
  unset($_SESSION['error_message']);
} else {
  $error_message = '';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= isset($page_title) ? "$page_title | FIM By AMC" : 'FIM By AMC' ?></title>
  <script src="<?= base_url('assets/js/jquery.js'); ?>"></script>
  <!-- DataTables Responsive Bootstrap5 CSS-->
  <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css'); ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/dataTables.bootstrap5.css'); ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/responsive.bootstrap5.css'); ?>">

  <!-- DataTables Button CSS-->
  <link rel="stylesheet" href="<?= base_url('assets/css/buttons.dataTables.css'); ?>">
  <!-- Custom CSS -->
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css'); ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/print.css'); ?>">
  <!-- SweetAlert2 CSS -->
  <link rel="stylesheet" href="<?= base_url('assets/css/sweetalert2.min.css'); ?>">
  <!-- SweetAlert2 JS -->
  <script src="<?= base_url('assets/js/sweetalert2.all.min.js'); ?>"></script>
  <!-- ChartJS -->
  <script src="<?= base_url('assets/js/chart.js'); ?>"></script>
  <script src="<?= base_url('assets/js/chartjs-plugin-datalabels.js'); ?>"></script>

  <style>
  .show-immediate {
    display: block !important;
    height: auto !important;
    transition: none !important;
  }

  .no-transition .accordion-button:not(.collapsed)::after {
    transition: none !important;
  }

  .loader-container {
    display: none;
    /* Awalnya sembunyikan loader */
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    min-height: 100%;
    background-color: #f2fafd;
    z-index: 9999;
  }

  .loader {
    border: 8px solid #f3f3f3;
    border-radius: 50%;
    border-top: 8px solid #3498db;
    width: 60px;
    height: 60px;
    animation: spin 2s linear infinite;
    position: absolute;
    left: 50%;
    top: 100px;
    transform: translateX(-50%);
  }

  @keyframes spin {
    0% {
      transform: rotate(0deg);
    }

    100% {
      transform: rotate(360deg);
    }
  }

  /* Custom sweetalert */
  .swal2-popup {
    font-size: 0.8rem;
    width: 300px;
    background: #0077b6 !important;
    color: white;
  }

  .swal2-content {
    color: white !important;
  }

  .swal2-close {
    font-size: 1rem;
  }
  </style>
</head>

<body>
  <div class="sb-cover d-print-none">
    <div class="sidebar">
      <div class="resizer"></div>
      <div class="sidebar-top">
        <div class="header">
          <a href="/" class="nav-link">
            <svg xmlns="http://www.w3.org/2000/svg" height="18" viewBox="0 -960 960 960" width="18">
              <path
                d="M120-120v-80l80-80v160h-80Zm160 0v-240l80-80v320h-80Zm160 0v-320l80 81v239h-80Zm160 0v-239l80-80v319h-80Zm160 0v-400l80-80v480h-80ZM120-327v-113l280-280 160 160 280-280v113L560-447 400-607 120-327Z" />
            </svg>
            <span class="text-link">
              MTG FIM
            </span>
          </a>
        </div>
        <hr>
        <ul>
          <li>
            <a href="<?= base_url('pages/dashboard'); ?>"
              class="nav-link text-dark <?= setActivePage('pages/dashboard'); ?>">
              <svg xmlns="http://www.w3.org/2000/svg" height="18" viewBox="0 -960 960 960" width="18">
                <path
                  d="M520-600v-240h320v240H520ZM120-440v-400h320v400H120Zm400 320v-400h320v400H520Zm-400 0v-240h320v240H120Zm80-400h160v-240H200v240Zm400 320h160v-240H600v240Zm0-480h160v-80H600v80ZM200-200h160v-80H200v80Zm160-320Zm240-160Zm0 240ZM360-280Z" />
              </svg>
              <span class="text-link">
                Dashboard
              </span>
            </a>
          </li>

          <!-- Accordion Master Data -->
          <li>
            <div class="accordion accordion-flush" style="background-color: transparent;" id="accordionFlushMasterData">
              <div class="accordion-item">
                <h2 class="accordion-header">
                  <button class="accordion-button collapsed nav-link" type="button" data-bs-toggle="collapse"
                    data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">

                    <svg xmlns="http://www.w3.org/2000/svg" height="18" viewBox="0 -960 960 960" width="18">
                      <path
                        d="M280-280h160v-160H280v160Zm240 0h160v-160H520v160ZM280-520h160v-160H280v160Zm240 0h160v-160H520v160ZM200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v560q0 33-23.5 56.5T760-120H200Zm0-80h560v-560H200v560Zm0-560v560-560Z" />
                    </svg>
                    <span class="text-link">
                      Master Data
                    </span>
                  </button>
                </h2>
                <div id="flush-collapseOne" class="accordion-collapse collapse"
                  data-bs-parent="#accordionFlushMasterData">
                  <div class="accordion-body">
                    <ul>
                      <li>
                        <a href="<?= base_url('pages/master-data/products'); ?>"
                          class="nav-link <?= setActivePage('pages/master-data/products'); ?>">
                          <svg xmlns="http://www.w3.org/2000/svg" height="18" viewBox="0 -960 960 960" width="18">
                            <path
                              d="M440-183v-274L200-596v274l240 139Zm80 0 240-139v-274L520-457v274Zm-40-343 237-137-237-137-237 137 237 137ZM160-252q-19-11-29.5-29T120-321v-318q0-22 10.5-40t29.5-29l280-161q19-11 40-11t40 11l280 161q19 11 29.5 29t10.5 40v318q0 22-10.5 40T800-252L520-91q-19 11-40 11t-40-11L160-252Zm320-228Z" />
                          </svg>
                          <span class="text-link">
                            Produk
                          </span>
                        </a>
                      </li>

                      <li>
                        <a href="<?= base_url('pages/master-data/contacts/internal'); ?>"
                          class="nav-link <?= setActivePage('pages/master-data/contacts/internal'); ?>">
                          <svg xmlns="http://www.w3.org/2000/svg" height="18" viewBox="0 -960 960 960" width="18">
                            <path
                              d="M475-160q4 0 8-2t6-4l328-328q12-12 17.5-27t5.5-30q0-16-5.5-30.5T817-607L647-777q-11-12-25.5-17.5T591-800q-15 0-30 5.5T534-777l-11 11 74 75q15 14 22 32t7 38q0 42-28.5 70.5T527-522q-20 0-38.5-7T456-550l-75-74-175 175q-3 3-4.5 6.5T200-435q0 8 6 14.5t14 6.5q4 0 8-2t6-4l136-136 56 56-135 136q-3 3-4.5 6.5T285-350q0 8 6 14t14 6q4 0 8-2t6-4l136-135 56 56-135 136q-3 2-4.5 6t-1.5 8q0 8 6 14t14 6q4 0 7.5-1.5t6.5-4.5l136-135 56 56-136 136q-3 3-4.5 6.5T454-180q0 8 6.5 14t14.5 6Zm-1 80q-37 0-65.5-24.5T375-166q-34-5-57-28t-28-57q-34-5-56.5-28.5T206-336q-38-5-62-33t-24-66q0-20 7.5-38.5T149-506l232-231 131 131q2 3 6 4.5t8 1.5q9 0 15-5.5t6-14.5q0-4-1.5-8t-4.5-6L398-777q-11-12-25.5-17.5T342-800q-15 0-30 5.5T285-777L144-635q-9 9-15 21t-8 24q-2 12 0 24.5t8 23.5l-58 58q-17-23-25-50.5T40-590q2-28 14-54.5T87-692l141-141q24-23 53.5-35t60.5-12q31 0 60.5 12t52.5 35l11 11 11-11q24-23 53.5-35t60.5-12q31 0 60.5 12t52.5 35l169 169q23 23 35 53t12 61q0 31-12 60.5T873-437L545-110q-14 14-32.5 22T474-80Zm-99-560Z" />
                          </svg>
                          <span class="text-link">
                            Kontak Internal
                          </span>
                        </a>
                      </li>

                      <li>
                        <a href="<?= base_url('pages/master-data/contacts/customer'); ?>"
                          class="nav-link <?= setActivePage('pages/master-data/contacts/customer'); ?>">
                          <svg xmlns="http://www.w3.org/2000/svg" height="18" viewBox="0 -960 960 960" width="18">
                            <path
                              d="M475-160q4 0 8-2t6-4l328-328q12-12 17.5-27t5.5-30q0-16-5.5-30.5T817-607L647-777q-11-12-25.5-17.5T591-800q-15 0-30 5.5T534-777l-11 11 74 75q15 14 22 32t7 38q0 42-28.5 70.5T527-522q-20 0-38.5-7T456-550l-75-74-175 175q-3 3-4.5 6.5T200-435q0 8 6 14.5t14 6.5q4 0 8-2t6-4l136-136 56 56-135 136q-3 3-4.5 6.5T285-350q0 8 6 14t14 6q4 0 8-2t6-4l136-135 56 56-135 136q-3 2-4.5 6t-1.5 8q0 8 6 14t14 6q4 0 7.5-1.5t6.5-4.5l136-135 56 56-136 136q-3 3-4.5 6.5T454-180q0 8 6.5 14t14.5 6Zm-1 80q-37 0-65.5-24.5T375-166q-34-5-57-28t-28-57q-34-5-56.5-28.5T206-336q-38-5-62-33t-24-66q0-20 7.5-38.5T149-506l232-231 131 131q2 3 6 4.5t8 1.5q9 0 15-5.5t6-14.5q0-4-1.5-8t-4.5-6L398-777q-11-12-25.5-17.5T342-800q-15 0-30 5.5T285-777L144-635q-9 9-15 21t-8 24q-2 12 0 24.5t8 23.5l-58 58q-17-23-25-50.5T40-590q2-28 14-54.5T87-692l141-141q24-23 53.5-35t60.5-12q31 0 60.5 12t52.5 35l11 11 11-11q24-23 53.5-35t60.5-12q31 0 60.5 12t52.5 35l169 169q23 23 35 53t12 61q0 31-12 60.5T873-437L545-110q-14 14-32.5 22T474-80Zm-99-560Z" />
                          </svg>
                          <span class="text-link">
                            Pelanggan
                          </span>
                        </a>
                      </li>

                      <li>
                        <a href="<?= base_url('pages/master-data/contacts/supplier'); ?>"
                          class="nav-link <?= setActivePage('pages/master-data/contacts/supplier'); ?>">
                          <svg xmlns="http://www.w3.org/2000/svg" height="18" viewBox="0 -960 960 960" width="18">
                            <path
                              d="M475-160q4 0 8-2t6-4l328-328q12-12 17.5-27t5.5-30q0-16-5.5-30.5T817-607L647-777q-11-12-25.5-17.5T591-800q-15 0-30 5.5T534-777l-11 11 74 75q15 14 22 32t7 38q0 42-28.5 70.5T527-522q-20 0-38.5-7T456-550l-75-74-175 175q-3 3-4.5 6.5T200-435q0 8 6 14.5t14 6.5q4 0 8-2t6-4l136-136 56 56-135 136q-3 3-4.5 6.5T285-350q0 8 6 14t14 6q4 0 8-2t6-4l136-135 56 56-135 136q-3 2-4.5 6t-1.5 8q0 8 6 14t14 6q4 0 7.5-1.5t6.5-4.5l136-135 56 56-136 136q-3 3-4.5 6.5T454-180q0 8 6.5 14t14.5 6Zm-1 80q-37 0-65.5-24.5T375-166q-34-5-57-28t-28-57q-34-5-56.5-28.5T206-336q-38-5-62-33t-24-66q0-20 7.5-38.5T149-506l232-231 131 131q2 3 6 4.5t8 1.5q9 0 15-5.5t6-14.5q0-4-1.5-8t-4.5-6L398-777q-11-12-25.5-17.5T342-800q-15 0-30 5.5T285-777L144-635q-9 9-15 21t-8 24q-2 12 0 24.5t8 23.5l-58 58q-17-23-25-50.5T40-590q2-28 14-54.5T87-692l141-141q24-23 53.5-35t60.5-12q31 0 60.5 12t52.5 35l11 11 11-11q24-23 53.5-35t60.5-12q31 0 60.5 12t52.5 35l169 169q23 23 35 53t12 61q0 31-12 60.5T873-437L545-110q-14 14-32.5 22T474-80Zm-99-560Z" />
                          </svg>
                          <span class="text-link">
                            Pemasok
                          </span>
                        </a>
                      </li>

                      <li>
                        <a href="<?= base_url('pages/master-data/contact-bank-accounts'); ?>"
                          class="nav-link <?= setActivePage('pages/master-data/contact-bank-accounts'); ?>">
                          <svg xmlns="http://www.w3.org/2000/svg" height="18" viewBox="0 -960 960 960" width="18">
                            <path
                              d="M160-160q-33 0-56.5-23.5T80-240v-480q0-33 23.5-56.5T160-800h640q33 0 56.5 23.5T880-720v240H160v240h400v80H160Zm0-480h640v-80H160v80ZM760-80v-120H640v-80h120v-120h80v120h120v80H840v120h-80ZM160-240v-480 480Z" />
                          </svg>
                          <span class="text-link">Rekening Kontak</span>
                        </a>
                      </li>

                      <li>
                        <a href="<?= base_url('pages/master-data/ppn'); ?>"
                          class="nav-link <?= setActivePage('pages/master-data/ppn'); ?>">
                          <svg xmlns="http://www.w3.org/2000/svg" height="18" viewBox="0 -960 960 960" width="18">
                            <path
                              d="M517-518 347-688l57-56 113 113 227-226 56 56-283 283ZM280-220l278 76 238-74q-5-9-14.5-15.5T760-240H558q-27 0-43-2t-33-8l-93-31 22-78 81 27q17 5 40 8t68 4q0-11-6.5-21T578-354l-234-86h-64v220ZM40-80v-440h304q7 0 14 1.5t13 3.5l235 87q33 12 53.5 42t20.5 66h80q50 0 85 33t35 87v40L560-60l-280-78v58H40Zm80-80h80v-280h-80v280Z" />
                          </svg>
                          <span class="text-link">
                            PPN
                          </span>
                        </a>
                      </li>

                      <?php if ($peran_pengguna === 'superadmin' || $peran_pengguna === 'staff'): ?>
                      <li>
                        <a href="<?= base_url('pages/master-data/users'); ?>"
                          class="nav-link <?= setActivePage('pages/master-data/users'); ?>">
                          <svg xmlns="http://www.w3.org/2000/svg" height="18" viewBox="0 -960 960 960" width="18">
                            <path
                              d="M237-285q54-38 115.5-56.5T480-360q66 0 127.5 18.5T723-285q35-41 52-91t17-104q0-129.67-91.23-220.84-91.23-91.16-221-91.16Q350-792 259-700.84 168-609.67 168-480q0 54 17 104t52 91Zm243-123q-60 0-102-42t-42-102q0-60 42-102t102-42q60 0 102 42t42 102q0 60-42 102t-102 42Zm.28 312Q401-96 331-126t-122.5-82.5Q156-261 126-330.96t-30-149.5Q96-560 126-629.5q30-69.5 82.5-122T330.96-834q69.96-30 149.5-30t149.04 30q69.5 30 122 82.5T834-629.28q30 69.73 30 149Q864-401 834-331t-82.5 122.5Q699-156 629.28-126q-69.73 30-149 30Zm-.28-72q52 0 100-16.5t90-48.5q-43-27-91-41t-99-14q-51 0-99.5 13.5T290-233q42 32 90 48.5T480-168Zm0-312q30 0 51-21t21-51q0-30-21-51t-51-21q-30 0-51 21t-21 51q0 30 21 51t51 21Zm0-72Zm0 319Z" />
                          </svg>
                          <span class="text-link">
                            Pengguna
                          </span>
                        </a>
                      </li>
                      <?php endif; ?>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </li>

          <!-- Outgoing -->
          <li>
            <div class="accordion accordion-flush" style="background-color: transparent;" id="accordionFlushOutgoing">
              <div class="accordion-item">
                <h2 class="accordion-header">
                  <button class="accordion-button collapsed nav-link" type="button" data-bs-toggle="collapse"
                    data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                    <svg xmlns="http://www.w3.org/2000/svg" height="18" viewBox="0 -960 960 960" width="18">
                      <path
                        d="M480-564 336-420l51 51 57-57v150h72v-150l57 57 51-51-144-144Zm-264-60v408h528v-408H216Zm0 480q-29.7 0-50.85-21.15Q144-186.3 144-216v-474q0-14 5.25-27T165-741l54-54q11-11 23.94-16 12.94-5 27.06-5h420q14.12 0 27.06 5T741-795l54 54q10.5 11 15.75 24t5.25 27v474q0 29.7-21.15 50.85Q773.7-144 744-144H216Zm6-552h516l-48-48H270l-48 48Zm258 276Z" />
                    </svg>
                    <span class="text-link">
                      Outgoing
                    </span>
                  </button>
                </h2>
                <div id="flush-collapseTwo" class="accordion-collapse collapse"
                  data-bs-parent="#accordionFlushOutgoing">
                  <div class="accordion-body">
                    <ul>
                      <li>
                        <a href="<?= base_url('pages/quotation/outgoing'); ?>"
                          class="nav-link text-dark <?= setActivePage('pages/quotation/outgoing'); ?>">
                          <svg xmlns="http://www.w3.org/2000/svg" height="18" viewBox="0 -960 960 960" width="18">
                            <path
                              d="M440-200h80v-40h40q17 0 28.5-11.5T600-280v-120q0-17-11.5-28.5T560-440H440v-40h160v-80h-80v-40h-80v40h-40q-17 0-28.5 11.5T360-520v120q0 17 11.5 28.5T400-360h120v40H360v80h80v40ZM240-80q-33 0-56.5-23.5T160-160v-640q0-33 23.5-56.5T240-880h320l240 240v480q0 33-23.5 56.5T720-80H240Zm280-560v-160H240v640h480v-480H520ZM240-800v160-160 640-640Z" />
                          </svg>
                          <span class="text-link">
                            Penawaran Harga
                          </span>
                        </a>
                      </li>

                      <li>
                        <a href="<?= base_url('pages/purchase-orders/outgoing'); ?>"
                          class="nav-link text-dark <?= setActivePage('pages/purchase-orders/outgoing'); ?>">
                          <svg xmlns="http://www.w3.org/2000/svg" height="18" viewBox="0 -960 960 960" width="18">
                            <path
                              d="m691-150 139-138-42-42-97 95-39-39-42 43 81 81ZM240-600h480v-80H240v80ZM720-40q-83 0-141.5-58.5T520-240q0-83 58.5-141.5T720-440q83 0 141.5 58.5T920-240q0 83-58.5 141.5T720-40ZM120-80v-680q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v267q-19-9-39-15t-41-9v-243H200v562h243q5 31 15.5 59T486-86l-6 6-60-60-60 60-60-60-60 60-60-60-60 60Zm120-200h203q3-21 9-41t15-39H240v80Zm0-160h284q38-37 88.5-58.5T720-520H240v80Zm-40 242v-562 562Z" />
                          </svg>
                          <span class="text-link">
                            Purchase Order
                          </span>
                        </a>
                      </li>

                      <li>
                        <a href="<?= base_url('pages/invoices/outgoing'); ?>"
                          class="nav-link text-dark <?= setActivePage('pages/invoices/outgoing'); ?>">
                          <svg xmlns="http://www.w3.org/2000/svg" height="18" viewBox="0 -960 960 960" width="18">
                            <path
                              d="M560-440q-50 0-85-35t-35-85q0-50 35-85t85-35q50 0 85 35t35 85q0 50-35 85t-85 35ZM280-320q-33 0-56.5-23.5T200-400v-320q0-33 23.5-56.5T280-800h560q33 0 56.5 23.5T920-720v320q0 33-23.5 56.5T840-320H280Zm80-80h400q0-33 23.5-56.5T840-480v-160q-33 0-56.5-23.5T760-720H360q0 33-23.5 56.5T280-640v160q33 0 56.5 23.5T360-400Zm440 240H120q-33 0-56.5-23.5T40-240v-440h80v440h680v80ZM280-400v-320 320Z" />
                          </svg>
                          <span class="text-link">
                            Invoice
                          </span>
                        </a>
                      </li>

                      <li>
                        <a href="<?= base_url('pages/delivery-order/outgoing'); ?>"
                          class="nav-link text-dark <?= setActivePage('pages/delivery-order/outgoing'); ?>">
                          <svg xmlns="http://www.w3.org/2000/svg" height="18" viewBox="0 -960 960 960" width="18">
                            <path
                              d="M240-160q-50 0-85-35t-35-85H40v-440q0-33 23.5-56.5T120-800h560v160h120l120 160v200h-80q0 50-35 85t-85 35q-50 0-85-35t-35-85H360q0 50-35 85t-85 35Zm0-80q17 0 28.5-11.5T280-280q0-17-11.5-28.5T240-320q-17 0-28.5 11.5T200-280q0 17 11.5 28.5T240-240ZM120-360h32q17-18 39-29t49-11q27 0 49 11t39 29h272v-360H120v360Zm600 120q17 0 28.5-11.5T760-280q0-17-11.5-28.5T720-320q-17 0-28.5 11.5T680-280q0 17 11.5 28.5T720-240Zm-40-200h170l-90-120h-80v120ZM360-540Z" />
                          </svg>
                          <span class="text-link">
                            Delivery Order
                          </span>
                        </a>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </li>

          <!-- Incoming -->
          <li>
            <div class="accordion accordion-flush" style="background-color: transparent;" id="accordionFlushIncoming">
              <div class="accordion-item">
                <h2 class="accordion-header">
                  <button class="accordion-button collapsed nav-link" type="button" data-bs-toggle="collapse"
                    data-bs-target="#flush-collapseThree" aria-expanded="false" aria-controls="flush-collapseThree">
                    <svg xmlns="http://www.w3.org/2000/svg" height="18" viewBox="0 -960 960 960" width="18">
                      <path
                        d="m480-276 144-144-51-51-57 57v-150h-72v150l-57-57-51 51 144 144ZM216-624v408h528v-408H216Zm0 480q-29.7 0-50.85-21.15Q144-186.3 144-216v-474q0-14 5.25-27T165-741l54-54q11-11 23.94-16 12.94-5 27.06-5h420q14.12 0 27.06 5T741-795l54 54q10.5 11 15.75 24t5.25 27v474q0 29.7-21.15 50.85Q773.7-144 744-144H216Zm6-552h516l-48-48H270l-48 48Zm258 276Z" />
                    </svg>
                    <span class="text-link">
                      Incoming
                    </span>
                  </button>
                </h2>
                <div id="flush-collapseThree" class="accordion-collapse collapse"
                  data-bs-parent="#accordionFlushIncoming">
                  <div class="accordion-body">
                    <ul>
                      <li>
                        <a href="<?= base_url('pages/quotation/incoming'); ?>"
                          class="nav-link text-dark <?= setActivePage('pages/quotation/incoming'); ?>">
                          <svg xmlns="http://www.w3.org/2000/svg" height="18" viewBox="0 -960 960 960" width="18">
                            <path
                              d="M440-200h80v-40h40q17 0 28.5-11.5T600-280v-120q0-17-11.5-28.5T560-440H440v-40h160v-80h-80v-40h-80v40h-40q-17 0-28.5 11.5T360-520v120q0 17 11.5 28.5T400-360h120v40H360v80h80v40ZM240-80q-33 0-56.5-23.5T160-160v-640q0-33 23.5-56.5T240-880h320l240 240v480q0 33-23.5 56.5T720-80H240Zm280-560v-160H240v640h480v-480H520ZM240-800v160-160 640-640Z" />
                          </svg>
                          <span class="text-link">
                            Penawaran Harga
                          </span>
                        </a>
                      </li>

                      <li>
                        <a href="<?= base_url('pages/purchase-orders/incoming'); ?>"
                          class="nav-link text-dark <?= setActivePage('pages/purchase-orders/incoming'); ?>">
                          <svg xmlns="http://www.w3.org/2000/svg" height="18" viewBox="0 -960 960 960" width="18">
                            <path
                              d="m691-150 139-138-42-42-97 95-39-39-42 43 81 81ZM240-600h480v-80H240v80ZM720-40q-83 0-141.5-58.5T520-240q0-83 58.5-141.5T720-440q83 0 141.5 58.5T920-240q0 83-58.5 141.5T720-40ZM120-80v-680q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v267q-19-9-39-15t-41-9v-243H200v562h243q5 31 15.5 59T486-86l-6 6-60-60-60 60-60-60-60 60-60-60-60 60Zm120-200h203q3-21 9-41t15-39H240v80Zm0-160h284q38-37 88.5-58.5T720-520H240v80Zm-40 242v-562 562Z" />
                          </svg>
                          <span class="text-link">
                            Purchase Order
                          </span>
                        </a>
                      </li>

                      <li>
                        <a href="<?= base_url('pages/invoices/incoming'); ?>"
                          class="nav-link text-dark <?= setActivePage('pages/invoices/incoming'); ?>">
                          <svg xmlns="http://www.w3.org/2000/svg" height="18" viewBox="0 -960 960 960" width="18">
                            <path
                              d="M560-440q-50 0-85-35t-35-85q0-50 35-85t85-35q50 0 85 35t35 85q0 50-35 85t-85 35ZM280-320q-33 0-56.5-23.5T200-400v-320q0-33 23.5-56.5T280-800h560q33 0 56.5 23.5T920-720v320q0 33-23.5 56.5T840-320H280Zm80-80h400q0-33 23.5-56.5T840-480v-160q-33 0-56.5-23.5T760-720H360q0 33-23.5 56.5T280-640v160q33 0 56.5 23.5T360-400Zm440 240H120q-33 0-56.5-23.5T40-240v-440h80v440h680v80ZM280-400v-320 320Z" />
                          </svg>
                          <span class="text-link">
                            Invoice
                          </span>
                        </a>
                      </li>

                      <li>
                        <a href="<?= base_url('pages/delivery-order/incoming'); ?>"
                          class="nav-link text-dark <?= setActivePage('pages/delivery-order/incoming'); ?>">
                          <svg xmlns="http://www.w3.org/2000/svg" height="18" viewBox="0 -960 960 960" width="18">
                            <path
                              d="M240-160q-50 0-85-35t-35-85H40v-440q0-33 23.5-56.5T120-800h560v160h120l120 160v200h-80q0 50-35 85t-85 35q-50 0-85-35t-35-85H360q0 50-35 85t-85 35Zm0-80q17 0 28.5-11.5T280-280q0-17-11.5-28.5T240-320q-17 0-28.5 11.5T200-280q0 17 11.5 28.5T240-240ZM120-360h32q17-18 39-29t49-11q27 0 49 11t39 29h272v-360H120v360Zm600 120q17 0 28.5-11.5T760-280q0-17-11.5-28.5T720-320q-17 0-28.5 11.5T680-280q0 17 11.5 28.5T720-240Zm-40-200h170l-90-120h-80v120ZM360-540Z" />
                          </svg>
                          <span class="text-link">
                            Delivery Order
                          </span>
                        </a>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </li>

          <?php if ($peran_pengguna === 'superadmin'): ?>
          <li>
            <a href="<?= base_url('pages/activity-log'); ?>"
              class="nav-link text-dark <?= setActivePage('pages/activity-log'); ?>">
              <svg xmlns="http://www.w3.org/2000/svg" height="18" viewBox="0 -960 960 960" width="18">
                <path
                  d="M80-680v-120q0-33 23.5-56.5T160-880h120v80H160v120H80ZM280-80H160q-33 0-56.5-23.5T80-160v-120h80v120h120v80Zm400 0v-80h120v-120h80v120q0 33-23.5 56.5T800-80H680Zm120-600v-120H680v-80h120q33 0 56.5 23.5T880-800v120h-80ZM540-580q-33 0-56.5-23.5T460-660q0-33 23.5-56.5T540-740q33 0 56.5 23.5T620-660q0 33-23.5 56.5T540-580Zm-28 340H352l40-204-72 28v136h-80v-188l158-68q35-15 51.5-19.5T480-560q21 0 39 11t29 29l40 64q26 42 70.5 69T760-360v80q-66 0-123.5-27.5T540-380l-28 140Z" />
              </svg>
              <span class="text-link">
                Log Aktivitas
              </span>
            </a>
          </li>
          <?php endif; ?>
        </ul>
      </div>

      <div class="sidebar-bottom">
        <hr>
        <div class="dropdown">
          <a href="#" class="d-flex align-items-center text-dark text-decoration-none nav-link"
            data-bs-toggle="dropdown" aria-expanded="false">
            <span class="rounded-circle bg-secondary text-white"
              style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;">
              <?php
                // Ubah nama pengguna menjadi huruf besar untuk memastikan konsistensi
                
                // Memisahkan nama pengguna menjadi kata-kata terpisah
                $kata = explode(" ", $nama_lengkap);

                // Inisialisasi variabel untuk menyimpan inisial
                $inisial = '';

                // Iterasi melalui setiap kata dalam nama pengguna
                foreach ($kata as $kata_satu) {
                    // Ambil huruf pertama dari setiap kata dan tambahkan ke inisial
                    $inisial .= substr($kata_satu, 0, 1);
                }

                // Tampilkan inisial
                echo strtoupper($inisial);
                ?>
            </span>
            <span class="text-link"><?= ucwords($nama_lengkap); ?></span>
          </a>
          <ul class="dropdown-menu dropdown-menu-dark text-small shadow">
            <li class="mt-2">
              <span class="p-3">Data Pengguna</span>
            </li>

            <li class="mt-2">
              <span class="ps-3">
                <svg xmlns="http://www.w3.org/2000/svg" height="18" viewBox="0 -960 960 960" width="18" fill="#fff">
                  <path
                    d="M560-440h200v-80H560v80Zm0-120h200v-80H560v80ZM200-320h320v-22q0-45-44-71.5T360-440q-72 0-116 26.5T200-342v22Zm160-160q33 0 56.5-23.5T440-560q0-33-23.5-56.5T360-640q-33 0-56.5 23.5T280-560q0 33 23.5 56.5T360-480ZM160-160q-33 0-56.5-23.5T80-240v-480q0-33 23.5-56.5T160-800h640q33 0 56.5 23.5T880-720v480q0 33-23.5 56.5T800-160H160Zm0-80h640v-480H160v480Zm0 0v-480 480Z" />
                </svg>
              </span>
              <span><?= ucwords($nama_lengkap) ?></span>
            </li>

            <li class="mt-2">
              <span class="ps-3">
                <svg xmlns="http://www.w3.org/2000/svg" height="18" viewBox="0 -960 960 960" width="18" fill="#fff">
                  <path
                    d="M237-285q54-38 115.5-56.5T480-360q66 0 127.5 18.5T723-285q35-41 52-91t17-104q0-129.67-91.23-220.84-91.23-91.16-221-91.16Q350-792 259-700.84 168-609.67 168-480q0 54 17 104t52 91Zm243-123q-60 0-102-42t-42-102q0-60 42-102t102-42q60 0 102 42t42 102q0 60-42 102t-102 42Zm.28 312Q401-96 331-126t-122.5-82.5Q156-261 126-330.96t-30-149.5Q96-560 126-629.5q30-69.5 82.5-122T330.96-834q69.96-30 149.5-30t149.04 30q69.5 30 122 82.5T834-629.28q30 69.73 30 149Q864-401 834-331t-82.5 122.5Q699-156 629.28-126q-69.73 30-149 30Zm-.28-72q52 0 100-16.5t90-48.5q-43-27-91-41t-99-14q-51 0-99.5 13.5T290-233q42 32 90 48.5T480-168Zm0-312q30 0 51-21t21-51q0-30-21-51t-51-21q-30 0-51 21t-21 51q0 30 21 51t51 21Zm0-72Zm0 319Z" />
                </svg>
              </span>
              <span><?= $nama_pengguna ?></span>
            </li>

            <li class="mt-2">
              <span class="ps-3">
                <svg xmlns="http://www.w3.org/2000/svg" height="18" viewBox="0 -960 960 960" width="18" fill="#fff">
                  <path
                    d="m438-338 226-226-57-57-169 169-84-84-57 57 141 141Zm42 258q-139-35-229.5-159.5T160-516v-244l320-120 320 120v244q0 152-90.5 276.5T480-80Zm0-84q104-33 172-132t68-220v-189l-240-90-240 90v189q0 121 68 220t172 132Zm0-316Z" />
                </svg>
              </span>
              <span><?= ucwords($peran_pengguna) ?></span>
            </li>

            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="mb-2">
              <a class="dropdown-item text-warning" href="<?= base_url('auth/logout'); ?>">Sign out</a>
            </li>
          </ul>
        </div>

      </div>
    </div>
  </div>

  <div class="rs-content" style="position: relative;">
    <div id="loader" class="loader-container">
      <div class="loader"></div>
    </div>