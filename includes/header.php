<?php
require_once 'function.php';

if (!checkLoginStatus()) {
    header("Location: " . base_url('auth/login.php'));
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= isset($title) ? $title : 'IMS-MTG | By AMC' ?></title>
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css'); ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css'); ?>">
  <script src="<?= base_url('assets/js/jquery.js'); ?>"></script>
</head>

<body>
  <div class="sb-cover">
    <div class="sidebar">
      <div class="resizer"></div>
      <div class="sidebar-top">
        <div class="header">
          <a href="/" class="nav-link text-dark">
            <svg xmlns="http://www.w3.org/2000/svg" height="18" viewBox="0 -960 960 960" width="18">
              <path
                d="M120-120v-80l80-80v160h-80Zm160 0v-240l80-80v320h-80Zm160 0v-320l80 81v239h-80Zm160 0v-239l80-80v319h-80Zm160 0v-400l80-80v480h-80ZM120-327v-113l280-280 160 160 280-280v113L560-447 400-607 120-327Z" />
            </svg>
            <span class="text-link">
              MTG IMS
            </span>
          </a>
        </div>
        <hr>
        <ul>
          <li>
            <a href="<?= base_url('pages/dashboard/index.php'); ?>" class="nav-link text-dark">
              <svg xmlns="http://www.w3.org/2000/svg" height="18" viewBox="0 -960 960 960" width="18">
                <path
                  d="M520-600v-240h320v240H520ZM120-440v-400h320v400H120Zm400 320v-400h320v400H520Zm-400 0v-240h320v240H120Zm80-400h160v-240H200v240Zm400 320h160v-240H600v240Zm0-480h160v-80H600v80ZM200-200h160v-80H200v80Zm160-320Zm240-160Zm0 240ZM360-280Z" />
              </svg>
              <span class="text-link">
                Dashboard
              </span>
            </a>
          </li>
          <li>
            <a href="<?= base_url('pages/dashboard-views/index.php'); ?>" class="nav-link text-dark">
              <svg xmlns="http://www.w3.org/2000/svg" height="18" viewBox="0 -960 960 960" width="18">
                <path
                  d="M320-414v-306h120v306l-60-56-60 56Zm200 60v-526h120v406L520-354ZM120-216v-344h120v224L120-216Zm0 98 258-258 142 122 224-224h-64v-80h200v200h-80v-64L524-146 382-268 232-118H120Z" />
              </svg>
              <span class="text-link">
                Dashboard Views
              </span>
            </a>
          </li>

          <li>
            <div class="accordion accordion-flush" id="accordionFlushMasterData">
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
                        <a href="<?= base_url('pages/master-data/products/index.php'); ?>" class="nav-link">
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
                        <a href="<?= base_url('pages/master-data/internal-contacts/index.php'); ?>" class="nav-link">
                          <svg xmlns="http://www.w3.org/2000/svg" height="18" viewBox="0 -960 960 960" width="18">
                            <path
                              d="M520-600v-240h320v240H520ZM120-440v-400h320v400H120Zm400 320v-400h320v400H520Zm-400 0v-240h320v240H120Zm80-400h160v-240H200v240Zm400 320h160v-240H600v240Zm0-480h160v-80H600v80ZM200-200h160v-80H200v80Zm160-320Zm240-160Zm0 240ZM360-280Z" />
                          </svg>
                          <span class="text-link">
                            Kontak Internal
                          </span>
                        </a>
                      </li>
                      <li>
                        <a href="<?= base_url('pages/master-data/customers/index.php'); ?>" class="nav-link">
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
                        <a href="<?= base_url('pages/master-data/ppn/index.php'); ?>" class="nav-link">
                          <svg xmlns="http://www.w3.org/2000/svg" height="18" viewBox="0 -960 960 960" width="18">
                            <path
                              d="M517-518 347-688l57-56 113 113 227-226 56 56-283 283ZM280-220l278 76 238-74q-5-9-14.5-15.5T760-240H558q-27 0-43-2t-33-8l-93-31 22-78 81 27q17 5 40 8t68 4q0-11-6.5-21T578-354l-234-86h-64v220ZM40-80v-440h304q7 0 14 1.5t13 3.5l235 87q33 12 53.5 42t20.5 66h80q50 0 85 33t35 87v40L560-60l-280-78v58H40Zm80-80h80v-280h-80v280Z" />
                          </svg>
                          <span class="text-link">
                            PPN
                          </span>
                        </a>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </li>

          <li>
            <a href="<?= base_url('pages/quotation/index.php'); ?>" class="nav-link text-dark">
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
            <a href="<?= base_url('pages/purchase-orders/index.php'); ?>" class="nav-link text-dark">
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
            <a href="<?= base_url('pages/invoices/index.php'); ?>" class="nav-link text-dark">
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
            <a href="<?= base_url('pages/activity-log/index.php'); ?>" class="nav-link text-dark">
              <svg xmlns="http://www.w3.org/2000/svg" height="18" viewBox="0 -960 960 960" width="18">
                <path
                  d="M80-680v-120q0-33 23.5-56.5T160-880h120v80H160v120H80ZM280-80H160q-33 0-56.5-23.5T80-160v-120h80v120h120v80Zm400 0v-80h120v-120h80v120q0 33-23.5 56.5T800-80H680Zm120-600v-120H680v-80h120q33 0 56.5 23.5T880-800v120h-80ZM540-580q-33 0-56.5-23.5T460-660q0-33 23.5-56.5T540-740q33 0 56.5 23.5T620-660q0 33-23.5 56.5T540-580Zm-28 340H352l40-204-72 28v136h-80v-188l158-68q35-15 51.5-19.5T480-560q21 0 39 11t29 29l40 64q26 42 70.5 69T760-360v80q-66 0-123.5-27.5T540-380l-28 140Z" />
              </svg>
              <span class="text-link">
                Log Aktivitas
              </span>
            </a>
          </li>
        </ul>
      </div>

      <div class="sidebar-bottom">
        <hr>
        <div class="dropdown">
          <a href="#" class="d-flex align-items-center text-dark text-decoration-none nav-link"
            data-bs-toggle="dropdown" aria-expanded="false">
            <img src="https://github.com/mdo.png" alt="" width="18" height="18" class="rounded-circle">
            <span class="text-link">
              <?= ucwords(isset($_SESSION['nama_pengguna']) ? $_SESSION['nama_pengguna'] : ''); ?>
            </span>
          </a>
          <ul class="dropdown-menu dropdown-menu-dark text-small shadow">
            <li><a class="dropdown-item" href="<?= base_url('pages/master-data/users/index.php'); ?>">User</a></li>
            <li><a class="dropdown-item" href="#">Settings</a></li>
            <li><a class="dropdown-item" href="#">Profile</a></li>
            <li>
              <hr class="dropdown-divider">
            </li>
            <li><a class="dropdown-item" href="<?= base_url('auth/logout.php'); ?>">Sign out</a></li>
          </ul>
        </div>

      </div>
    </div>
  </div>

  <div class="rs-content">