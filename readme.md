# MTG-FIM

## PT. MTG FINANCE INFORMATION MANAGEMENT

---

# Dokumentasi Struktur Proyek

| **_.htaccess**
| **_assets**
| | **_css**
| | **_image**
| | **_js**
| | |_ **_bootstrap.bundle.min.js**
| | |_ **_buttons.colVis.min.js**
| | |_ **_buttons.dataTables.js**
| | |_ **_buttons.html5.min.js**
| | |_ **_buttons.print.min.js**
| | |_ **_chart.js**
| | |_ **_chartjs-plugin-datalabels.js**
| | |_ **_dataTables.bootstrap5.js**
| | |_ **_dataTables.buttons.js**
| | |_ **_dataTables.js**
| | |_ **_dataTables.responsive.js**
| | |_ **_jquery.js**
| | |_ **_jszip.min.js**
| | |_ **_pdfmake.min.js**
| | |_ **_responsive.bootstrap5.js**
| | |_ **_script.js**
| | |_ **_sweetalert2.all.min.js**
| | |_ **_vfs_fonts.js**
| **_auth**
| | **_login.php**
| | **_logout.php**
| | **_process.php**
| **_includes**
| |_ **_composer.json**
| |_ **_composer.lock**
| |_ **_config.php**
| |_ **_footer.php**
| |_ **_function.php**
| |_ **_header.php**
| |_ **_mtg_ims_amc_23_06_24.sql**
| |_ **_vendor**
| | |_ **_composer**
| | |_ **_ramsey**
| | |_ **_uuid**
| **_index.php**
| **_pages**
| | **_access-denied.php**
| | |_ **_activity-log**
| | | |_ **_del.php**
| | | |_ **_index.php**
| | **_dashboard**
| | |_ **_incomeInfo.php**
| | |_ **_index.php**
| | |_ **_invoiceInfo.php**
| | |_ **_purchaseOrderInfo.php**
| | |_ **_quotationInfo.php**
| | **_delivery-order**
| | |_ **_detail.php**
| | |_ **_index.php**
| | |_ **_process.php**
| | **_index.php**
| | |_ **_invoices**
| | | |_ **_add.php**
| | | |_ **_approve.php**
| | | |_ **_del.php**
| | | |_ **_detail.php**
| | | |_ **_edit.php**
| | | |_ **_getDocumentNumber.php**
| | | |_ **_getPesananInfo.php**
| | | |_ **_index.php**
| | | |_ **_list-detail.php**
| | | |_ **_process.php**
| | **_master-data**
| | |_ **_contact-bank-accounts**
| | | |_ **_add.php**
| | | |_ **_del.php**
| | | |_ **_edit.php**
| | | |_ **_index.php**
| | | |_ **_process.php**
| | |_ **_contacts**
| | | |_ **_add.php**
| | | |_ **_del.php**
| | | |_ **_edit.php**
| | | |_ **_index.php**
| | | |_ **_process.php**
| | |_ **_index.php**
| | |_ **_ppn**
| | | |_ **_add.php**
| | | |_ **_del.php**
| | | |_ **_edit.php**
| | | |_ **_index.php**
| | | |_ **_process.php**
| | |_ **_products**
| | | |_ **_add.php**
| | | |_ **_del.php**
| | | |_ **_edit.php**
| | | |_ **_index.php**
| | | |_ **_process.php**
| | |_ **_users**
| | | |_ **_add.php**
| | | |_ **_change_password.php**
| | | |_ **_del.php**
| | | |_ **_edit.php**
| | | |_ **_index.php**
| | | |_ **_process.php**
| |_ **_purchase-orders**
| | |_ **_add.php**
| | |_ **_approve.php**
| | |_ **_del.php**
| | |_ **_detail.php**
| | |_ **_edit.php**
| | |_ **_getDocumentNumber.php**
| | |_ **_getPenawaranInfo.php**
| | |_ **_index.php**
| | |_ **_list-detail.php**
| | |_ **_process.php**
| | **_quotation**
| | |_ **_add.php**
| | |_ **_approve.php**
| | |_ **_del.php**
| | |_ **_detail.php**
| | |_ **_edit.php**
| | |_ **_getDocumentNumber.php**
| | |_ **_index.php**
| | |_ **_list-detail.php**
| | |_ **_process.php**


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

- **invoice**: Halaman untuk mengelola invoice.
- **purchase_order**: Halaman untuk mengelola purchase order.
- **quotation**: Halaman untuk mengelola penawaran harga.

## index.php

- **Halaman utama** yang merupakan entry point dari sistem, biasanya menampilkan halaman login atau dashboard utama setelah login.

## README.md

- **File dokumentasi proyek** yang berisi penjelasan umum tentang proyek ini.
