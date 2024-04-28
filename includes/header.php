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
</head>

<body class="fade-out">
  <ul class="menu">
    <li>Data Master
      <ul>
        <li><a href="<?= base_url('pages/master-data/products/index.php'); ?>">Produk</a></li>
        <li><a href="<?= base_url('pages/master-data/internal-contacts/index.php'); ?>">Kontak Internal</a></li>
        <li><a href="<?= base_url('pages/master-data/customers/index.php'); ?>">Pelanggan</a></li>
        <li><a href="<?= base_url('pages/master-data/users/index.php'); ?>">User</a></li>
      </ul>
    </li>
    <li>
      <a href="<?= base_url('pages/dashboard/index.php'); ?>">
        <span class="menu-item">Dashboard</span>
      </a>
    </li>
    <li>
      <a href="<?= base_url('pages/dashboard-views/index.php'); ?>">
        <span class="menu-item">Dashboard Views</span>
      </a>
    </li>
    <li>
      <a href="<?= base_url('pages/quotation/index.php'); ?>">
        <span class="menu-item">Penawaran Harga</span>
      </a>
    </li>
    <li>
      <a href="<?= base_url('pages/purchase-orders/index.php'); ?>">
        <span class="menu-item">Purchase Order</span>
      </a>
    </li>
    <li>
      <a href="<?= base_url('pages/invoices/index.php'); ?>">
        <span class="menu-item">Invoice</span>
      </a>
    </li>
    <li>
      <a href="<?= base_url('pages/activity-log/index.php'); ?>">
        <span class="menu-item">Log Aktivitas</span>
      </a>
    </li>

    <li>
      <a href="<?= base_url('auth/logout.php'); ?>">
        <span class="menu-item">Logout</span>
      </a>
    </li>
  </ul>