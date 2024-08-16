# MTG-FIM

## PT. MTG FINANCE INFORMATION MANAGEMENT

---

# Dokumentasi Struktur Proyek

|**_.htaccess
|_**assets
| |**_css
| |_**image
| | |**_uploads
| | | |_**logo
| | | |**_signature
| |_**js
| | |**_bootstrap.bundle.min.js
| | |_**buttons.colVis.min.js
| | |**_buttons.dataTables.js
| | |_**buttons.html5.min.js
| | |**_buttons.print.min.js
| | |_**chart.js
| | |**_chartjs-plugin-datalabels.js
| | |_**dataTables.bootstrap5.js
| | |**_dataTables.buttons.js
| | |_**dataTables.js
| | |**_dataTables.responsive.js
| | |_**jquery.js
| | |**_jszip.min.js
| | |_**pdfmake.min.js
| | |**_responsive.bootstrap5.js
| | |_**script.js
| | |**_sweetalert2.all.min.js
| | |_**vfs\*fonts.js
|\*\*\_auth
| |**\*login.php
| |**_logout.php
| |_**process.php
|**_includes
| |_**composer.json
| |**_composer.lock
| |_**config.php
| |**_footer.php
| |_**function.php
| |**_header.php
| |_**mtg_ims_amc_23_06_24.sql
| |**_vendor
| | |_**composer
| | |**_ramsey
| | | |_**uuid
|**_index.php
|_**pages
| |**_access-denied.php
| |_**activity-log
| | |**_del.php
| | |_**index.php
| |**_dashboard
| | |_**incomeInfo.php
| | |**_index.php
| | |_**invoiceInfo.php
| | |**_purchaseOrderInfo.php
| | |_**quotationInfo.php
| |**_delivery-order
| | |_**detail.php
| | |**_index.php
| | |_**process.php
| |**_index.php
| |_**invoices
| | |**_add.php
| | |_**approve.php
| | |**_del.php
| | |_**detail.php
| | |**_edit.php
| | |_**getDocumentNumber.php
| | |**_getPesananInfo.php
| | |_**index.php
| | |**_list-detail.php
| | |_**process.php
| |**_master-data
| | |_**contact-bank-accounts
| | | |**_add.php
| | | |_**del.php
| | | |**_edit.php
| | | |_**index.php
| | | |**_process.php
| | |_**contacts
| | | |**_add.php
| | | |_**del.php
| | | |**_edit.php
| | | |_**index.php
| | | |**_process.php
| | |_**index.php
| | |**_ppn
| | | |_**add.php
| | | |**_del.php
| | | |_**edit.php
| | | |**_index.php
| | | |_**process.php
| | |**_products
| | | |_**add.php
| | | |**_del.php
| | | |_**edit.php
| | | |**_index.php
| | | |_**process.php
| | |**_users
| | | |_**add.php
| | | |**_change_password.php
| | | |_**del.php
| | | |**_edit.php
| | | |_**index.php
| | | |**_process.php
| |_**purchase-orders
| | |**_add.php
| | |_**approve.php
| | |**_del.php
| | |_**detail.php
| | |**_edit.php
| | |_**getDocumentNumber.php
| | |**_getPenawaranInfo.php
| | |_**index.php
| | |**_list-detail.php
| | |_**process.php
| |**_quotation
| | |_**add.php
| | |**_approve.php
| | |_**del.php
| | |**_detail.php
| | |_**edit.php
| | |**_getDocumentNumber.php
| | |_**index.php
| | |**_list-detail.php
| | |_\*\*process.php

---

## .htaccess

- **Konfigurasi server** untuk mengelola aturan server.

## assets

- **Direktori untuk aset statis** seperti CSS, gambar, dan JavaScript.

### assets/css

- **bootstrap.min.css**: Framework CSS untuk desain responsif.
- **buttons.dataTables.css**: Gaya untuk tombol pada DataTables.
- **dataTables.bootstrap5.css**: Integrasi gaya Bootstrap 5 untuk DataTables.
- **print.css**: Gaya untuk cetak halaman.
- **responsive.bootstrap5.css**: Gaya responsif untuk DataTables dengan Bootstrap 5.
- **style.css**: Gaya khusus untuk tampilan situs.
- **sweetalert2.min.css**: Gaya untuk SweetAlert2 (popup notifikasi).

### assets/image

- **Direktori untuk menyimpan gambar** yang digunakan di proyek.

#### assets/image/uploads

- **Direktori untuk menyimpan gambar upload** seperti logo dan tanda tangan.

##### assets/image/uploads/logo

- **66782f4ae81fa_20240623.jpeg**: Logo perusahaan (file contoh).
- **no_logo.png**: Placeholder untuk logo yang belum diunggah.

##### assets/image/uploads/signature

- **66782f109b2cb_20240623.png**: Tanda tangan digital (file contoh).
- **no_sign.png**: Placeholder untuk tanda tangan yang belum diunggah.

### assets/js

- **Direktori untuk menyimpan file JavaScript** yang digunakan di proyek.

- **bootstrap.bundle.min.js**: Skrip Bootstrap dengan popper.js terintegrasi.
- **buttons.colVis.min.js**: Plugin untuk menampilkan/menyembunyikan kolom pada DataTables.
- **buttons.dataTables.js**: Skrip untuk tombol pada DataTables.
- **chart.js**: Library untuk membuat grafik.
- **jquery.js**: Library JavaScript untuk manipulasi DOM.
- **sweetalert2.all.min.js**: Skrip untuk SweetAlert2 (popup notifikasi).

## auth

- **Direktori untuk autentikasi pengguna**.

- **login.php**: Halaman login.
- **logout.php**: Proses logout.
- **process.php**: Proses autentikasi login.

## includes

- **Direktori untuk menyimpan file yang di-include** di halaman lain.

- **composer.json**: File konfigurasi Composer.
- **config.php**: Pengaturan konfigurasi utama.
- **footer.php**: Template footer halaman.
- **function.php**: Kumpulan fungsi umum.
- **header.php**: Template header halaman.
- **mtg_ims_amc_23_06_24.sql**: Backup database pada tanggal 23 Juni 2024.
- **vendor/autoload.php**: Autoloader untuk library pihak ketiga.

### includes/vendor

- **Direktori untuk menyimpan library pihak ketiga** yang dikelola oleh Composer.

- **brick/math**: Library untuk operasi matematika besar.
- **ramsey/uuid**: Library untuk menghasilkan UUID.
- **composer**: File autoload dan konfigurasi lainnya dari Composer.

## pages

- **Direktori untuk halaman utama** dari sistem yang mencakup berbagai modul seperti dashboard, manajemen pengguna, dokumen transaksi, dan lainnya.

### pages/dashboard

- **index.php**: Halaman utama dashboard yang menampilkan ringkasan informasi.

### pages/user_management

- **add_user.php**: Halaman untuk menambahkan pengguna baru.
- **delete_user.php**: Proses untuk menghapus pengguna.
- **edit_user.php**: Halaman untuk mengubah data pengguna.
- **index.php**: Halaman utama manajemen pengguna yang menampilkan daftar pengguna.
- **process_add_user.php**: Proses penambahan pengguna.
- **process_edit_user.php**: Proses pengubahan data pengguna.

### pages/documents

- **invoice_incoming.php**: Halaman untuk mengelola invoice yang masuk.
- **invoice_outgoing.php**: Halaman untuk mengelola invoice yang keluar.
- **purchase_order.php**: Halaman untuk mengelola purchase order.
- **quotation.php**: Halaman untuk mengelola penawaran harga.

### pages/reports

- **index.php**: Halaman utama laporan yang menampilkan berbagai laporan keuangan.
- **invoice_report.php**: Halaman laporan untuk invoice.
- **purchase_order_report.php**: Halaman laporan untuk purchase order.
- **quotation_report.php**: Halaman laporan untuk penawaran harga.

## uploads

- **Direktori untuk file yang diunggah oleh pengguna** seperti dokumen transaksi, gambar, atau file lainnya.

### uploads/documents

- **Direktori untuk menyimpan dokumen-dokumen transaksi** seperti invoice, purchase order, dan penawaran harga.

### uploads/signatures

- **Direktori untuk menyimpan tanda tangan digital** dari pengguna yang berwenang.

## index.php

- **Halaman utama** yang merupakan entry point dari sistem, biasanya menampilkan halaman login atau dashboard utama setelah login.

## README.md

- **File dokumentasi proyek** yang berisi penjelasan umum tentang proyek ini.
