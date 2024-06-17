-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 17 Jun 2024 pada 16.38
-- Versi server: 8.0.30
-- Versi PHP: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mtg_ims_amc`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `detail_faktur`
--

CREATE TABLE `detail_faktur` (
  `id_detail_faktur` varchar(36) NOT NULL,
  `id_faktur` varchar(36) NOT NULL,
  `no_pengiriman_barang` varchar(50) NOT NULL,
  `id_produk` varchar(36) NOT NULL,
  `jumlah` int NOT NULL,
  `harga_satuan` int NOT NULL,
  `id_pesanan` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `detail_faktur`
--

INSERT INTO `detail_faktur` (`id_detail_faktur`, `id_faktur`, `no_pengiriman_barang`, `id_produk`, `jumlah`, `harga_satuan`, `id_pesanan`) VALUES
('1142593d-25fd-4d49-996b-de05d769ea47', '65f4fd6c-6cc9-400f-80e3-0bd97c9fa5de', '003/spb/mtg/03/2024', '51f151a4-ac95-4782-818f-b56d9a780123', 3500, 110, '86cf7390-c11a-4a40-ae14-2d97269b3efb'),
('25699e19-ef9b-4b0b-aa55-71cf9f4a33eb', '65f4fd6c-6cc9-400f-80e3-0bd97c9fa5de', '002/spb/mtg/03/2024', '2c3242ff-6b53-4ced-a89c-e08039ef6a77', 29919, 2575, '485d95f1-fd75-4949-aaa8-9f1586b8c7ec'),
('568002d0-2c3d-4e4b-bbc3-ae4ee692190e', '65f4fd6c-6cc9-400f-80e3-0bd97c9fa5de', '001/spb/mtg/03/2024', '88dd7064-ccd4-4f7f-9c3d-7b1722066d4f', 161983, 2575, '485d95f1-fd75-4949-aaa8-9f1586b8c7ec');

-- --------------------------------------------------------

--
-- Struktur dari tabel `detail_penawaran`
--

CREATE TABLE `detail_penawaran` (
  `id_detail_penawaran` varchar(36) NOT NULL,
  `id_penawaran` varchar(36) NOT NULL,
  `id_produk` varchar(36) NOT NULL,
  `jumlah` int NOT NULL,
  `harga_satuan` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `detail_penawaran`
--

INSERT INTO `detail_penawaran` (`id_detail_penawaran`, `id_penawaran`, `id_produk`, `jumlah`, `harga_satuan`) VALUES
('0a8733f4-ef18-44c1-8dec-47a44b065b46', '1f5af713-47cc-4000-981e-25ae0dba6dd7', '88dd7064-ccd4-4f7f-9c3d-7b1722066d4f', 1, 1),
('3d580c61-4f60-4988-8aef-6cede81dfccf', '13d2c29c-49ad-497c-abd2-43f58b9ff477', '482df9fe-64af-40c2-a9f6-0017939ceddc', 1, 3600000),
('7b056cbc-1b4e-49bd-993b-2c2ebc14b199', 'b205f982-33f0-4c4a-9e60-f1d04e34c59d', '88dd7064-ccd4-4f7f-9c3d-7b1722066d4f', 1, 2575),
('cd01f19f-b941-4b66-ab6d-351dca825963', 'a1c9d0b0-906d-4a07-b0fc-55761c092da5', '7e360f04-140f-407a-a314-9af07bfdc778', 1, 1),
('edad3fc6-3508-4253-bd38-eaa9874893dc', 'bf14e250-cb62-4f29-99a2-b9ff92609de8', '51f151a4-ac95-4782-818f-b56d9a780123', 1, 110),
('f7ea5058-14fd-4b77-9bbd-21b3ac779aca', '13d2c29c-49ad-497c-abd2-43f58b9ff477', 'e1c0f235-f098-4043-bcdb-16b298c2383a', 1, 3600000),
('f9340be3-bc30-46f7-80a1-c8f6e1afd69a', 'a1c9d0b0-906d-4a07-b0fc-55761c092da5', '88dd7064-ccd4-4f7f-9c3d-7b1722066d4f', 1, 1),
('fbc728d2-84de-4756-9a31-dfa989823b0f', '0ff05aa8-a8dc-478b-8120-7f2b8222b410', '2c3242ff-6b53-4ced-a89c-e08039ef6a77', 1, 2575);

-- --------------------------------------------------------

--
-- Struktur dari tabel `detail_pesanan`
--

CREATE TABLE `detail_pesanan` (
  `id_detail_pesanan` varchar(36) NOT NULL,
  `id_pesanan` varchar(36) DEFAULT NULL,
  `id_produk` varchar(36) NOT NULL,
  `jumlah` int NOT NULL,
  `jumlah_dikirim` int NOT NULL,
  `sisa_pesanan` int NOT NULL,
  `harga_satuan` int NOT NULL,
  `id_penawaran` varchar(36) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `detail_pesanan`
--

INSERT INTO `detail_pesanan` (`id_detail_pesanan`, `id_pesanan`, `id_produk`, `jumlah`, `jumlah_dikirim`, `sisa_pesanan`, `harga_satuan`, `id_penawaran`) VALUES
('0fd9dfe5-3857-4a81-8cab-a1652fae1513', '485d95f1-fd75-4949-aaa8-9f1586b8c7ec', '2c3242ff-6b53-4ced-a89c-e08039ef6a77', 30000, 29919, 81, 2575, '0ff05aa8-a8dc-478b-8120-7f2b8222b410'),
('22341d58-0107-4047-9386-745b62d55a81', 'e6ef7a30-4ed2-4a33-b0f5-6f2408bd9cc4', '482df9fe-64af-40c2-a9f6-0017939ceddc', 1, 0, 1, 3600000, '13d2c29c-49ad-497c-abd2-43f58b9ff477'),
('2a2500f3-5a3e-4005-ad1e-af9de2f94374', '70fbf929-ddbe-4024-baff-00b09b3af0b1', '88dd7064-ccd4-4f7f-9c3d-7b1722066d4f', 200000, 0, 200000, 2575, 'b205f982-33f0-4c4a-9e60-f1d04e34c59d'),
('30232b4f-b227-42f5-b85a-a3ab8e3fec30', 'e6ef7a30-4ed2-4a33-b0f5-6f2408bd9cc4', 'e1c0f235-f098-4043-bcdb-16b298c2383a', 1, 0, 1, 3600000, '13d2c29c-49ad-497c-abd2-43f58b9ff477'),
('55be096b-ab90-49e3-b605-9ee45501598f', '86cf7390-c11a-4a40-ae14-2d97269b3efb', '51f151a4-ac95-4782-818f-b56d9a780123', 20000, 3500, 16500, 110, 'bf14e250-cb62-4f29-99a2-b9ff92609de8'),
('a873aa2a-01c8-49d7-be3d-f64213e5947a', '485d95f1-fd75-4949-aaa8-9f1586b8c7ec', '88dd7064-ccd4-4f7f-9c3d-7b1722066d4f', 200000, 161983, 38017, 2575, 'b205f982-33f0-4c4a-9e60-f1d04e34c59d'),
('a88d2e9a-5ae7-4649-aecb-2072aca59ba7', '70fbf929-ddbe-4024-baff-00b09b3af0b1', '2c3242ff-6b53-4ced-a89c-e08039ef6a77', 50000, 0, 50000, 2575, '0ff05aa8-a8dc-478b-8120-7f2b8222b410'),
('c4db29d3-d165-46be-a76c-ccf5f6c6113c', '340ce501-5f1d-4235-9c4f-4af2b22037f8', '51f151a4-ac95-4782-818f-b56d9a780123', 30000, 0, 30000, 110, 'bf14e250-cb62-4f29-99a2-b9ff92609de8'),
('cb44bd95-0964-49bd-8d96-8c95813bb0ba', 'dfb2c5b4-c758-4aa8-96f1-734c46430eab', '88dd7064-ccd4-4f7f-9c3d-7b1722066d4f', 200000, 0, 200000, 2575, 'b205f982-33f0-4c4a-9e60-f1d04e34c59d'),
('cee23c34-9e9e-4aff-88b3-b119d34c8242', 'dfb2c5b4-c758-4aa8-96f1-734c46430eab', '2c3242ff-6b53-4ced-a89c-e08039ef6a77', 50000, 0, 50000, 2575, '0ff05aa8-a8dc-478b-8120-7f2b8222b410');

-- --------------------------------------------------------

--
-- Struktur dari tabel `faktur`
--

CREATE TABLE `faktur` (
  `id_faktur` varchar(36) NOT NULL,
  `id_pengirim` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `no_faktur` varchar(50) NOT NULL,
  `tanggal` datetime NOT NULL,
  `total` int NOT NULL,
  `catatan` text,
  `id_penerima` varchar(36) NOT NULL,
  `diskon` int DEFAULT NULL,
  `id_ppn` varchar(36) DEFAULT NULL,
  `logo` varchar(100) DEFAULT NULL,
  `signature_info` longtext,
  `kategori` enum('keluar','masuk') NOT NULL,
  `status` enum('tunggu kirim','belum dibayar','dibayar') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `status_hapus` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `faktur`
--

INSERT INTO `faktur` (`id_faktur`, `id_pengirim`, `no_faktur`, `tanggal`, `total`, `catatan`, `id_penerima`, `diskon`, `id_ppn`, `logo`, `signature_info`, `kategori`, `status`, `status_hapus`) VALUES
('65f4fd6c-6cc9-400f-80e3-0bd97c9fa5de', '31c50f21-12b9-4826-9345-52c604ec4ee2', '001/inv/mtg/03/2024', '2024-03-30 00:01:00', 548931242, '', '5c091a0a-9796-4d5d-b009-526830bd6386', 0, 'e502e6a9-e463-4bb0-9a78-0e30dc229722', '../../assets/image/uploads/logo/6670224d3328a_20240617.jpeg', 'Location: cikarang pusat, Date: 2024-03-30, Name: supriyadi, Position: direktur, Path: ', 'keluar', 'tunggu kirim', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `kontak`
--

CREATE TABLE `kontak` (
  `id_kontak` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `nama_kontak` varchar(100) NOT NULL,
  `kategori` enum('internal','customer','supplier') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `alamat` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `telepon` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `status_hapus` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `kontak`
--

INSERT INTO `kontak` (`id_kontak`, `nama_kontak`, `kategori`, `alamat`, `telepon`, `email`, `keterangan`, `status_hapus`) VALUES
('31c50f21-12b9-4826-9345-52c604ec4ee2', 'pt. mitra tekno gemilang', 'internal', 'office : jl. rawa banteng rt.01 rw.02 kel. jaya mukti kec. cikarang pusat - bekasi', '02129481360', 'mitra_teknogemilang@yahoo.co.id', 'kontak utama perusahaan.', 0),
('5c091a0a-9796-4d5d-b009-526830bd6386', 'pt. yamaha motor parts mfg. indonesia', 'customer', 'jl. permata raya lot f2& f6 po box. 157 kiic. karawang - jawa barat', '', '', 'kontak utama ypmi', 0),
('66fe9784-7a4f-4097-b25c-57872b4bd101', 'pt. stilmetindo prima', 'supplier', 'jl. marina indah golf,rukan eksklusive blok i no 6-7 bgm pikrt.004 rw.003 kamal muara,penjaringan,jakarta utara 14470', '02155965878', '', '-', 0),
('7bf7dcf6-e4d2-48fe-bc45-c5b590b8aa73', 'pt. tritama teknik indo', 'supplier', 'deltamas', '', 'mail@m', 'tes', 0),
('b949e00a-c473-49fb-86b1-48d79bca71e0', 'tes', 'internal', 'jklasjl', '', '', '', 1),
('bc24623f-d5ed-44e5-b13a-fdf04fbdf61c', 'pt. panasonic gobel energy indonesia', 'customer', 'kawasan industri gobel, jl. teuku umar km.44, telaga asih, cikarang barat, bekasi, jawa barat 17530', '02188324681', '', 'kontak utama pecgi', 0),
('e1b6f8ca-dc26-4975-b8b6-7e7c52a57635', 'tes kontak internal', 'internal', 'jdksjakl', '', '', '', 1),
('e484aa25-f2ee-422b-8e55-63e8d556e6cb', 'tes customer', 'customer', 'njdalk', '', '', '', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `log_aktivitas`
--

CREATE TABLE `log_aktivitas` (
  `id_log` int NOT NULL,
  `id_pengguna` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `aktivitas` varchar(225) NOT NULL,
  `tabel` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `keterangan` text NOT NULL,
  `tanggal` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status_hapus` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `log_aktivitas`
--

INSERT INTO `log_aktivitas` (`id_log`, `id_pengguna`, `aktivitas`, `tabel`, `keterangan`, `tanggal`, `status_hapus`) VALUES
(108, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil membuat PH', 'Penawaran Harga', 'Berhasil membuat penawaran harga baru dengan ID bf14e250-cb62-4f29-99a2-b9ff92609de8', '2024-06-17 11:47:26', 0),
(109, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil ubah PH', 'Penawaran Harga', 'Perubahan data penawaran harga dengan ID bf14e250-cb62-4f29-99a2-b9ff92609de8: | 1. catatan: &quot;harga belum termasuk ppn 10%, dokumen di approve kembali tgl 05 maret 2024.&quot; diubah menjadi &quot;harga belum termasuk ppn 11% (dokumen telah di approve kembali tgl 05 maret 2024)&quot; | ', '2024-06-17 11:48:11', 0),
(110, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil ubah status PH', 'Penawaran Harga', 'Perubahan status penawaran harga dengan ID bf14e250-cb62-4f29-99a2-b9ff92609de8: | . status: &quot;draft&quot; diubah menjadi &quot;disetujui&quot; | ', '2024-06-17 11:52:25', 0),
(111, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil membuat PH', 'Penawaran Harga', 'Berhasil membuat penawaran harga baru dengan ID b205f982-33f0-4c4a-9e60-f1d04e34c59d', '2024-06-17 12:00:43', 0),
(112, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil ubah PH', 'Penawaran Harga', 'Perubahan data penawaran harga dengan ID b205f982-33f0-4c4a-9e60-f1d04e34c59d: | 1. no_penawaran: &quot;001/ph/mtg/12/2023&quot; diubah menjadi &quot;018/ph/mtg/12/2023&quot; | ', '2024-06-17 12:01:13', 0),
(113, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil ubah status PH', 'Penawaran Harga', 'Perubahan status penawaran harga dengan ID b205f982-33f0-4c4a-9e60-f1d04e34c59d: | . status: &quot;draft&quot; diubah menjadi &quot;disetujui&quot; | ', '2024-06-17 12:01:25', 0),
(114, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil ubah PH', 'Penawaran Harga', 'Perubahan data penawaran harga dengan ID bf14e250-cb62-4f29-99a2-b9ff92609de8: | 1. no_penawaran: &quot;001/ph/mtg/10/2018&quot; diubah menjadi &quot;002/ph/mtg/10/2018&quot; | ', '2024-06-17 12:01:44', 0),
(115, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil ubah PH', 'Penawaran Harga', 'Perubahan data penawaran harga dengan ID b205f982-33f0-4c4a-9e60-f1d04e34c59d: | 1. tanggal: &quot;2023-12-16 06:00:00&quot; diubah menjadi &quot;2023-12-06 06:00:00&quot; | ', '2024-06-17 12:02:37', 0),
(116, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil membuat PH', 'Penawaran Harga', 'Berhasil membuat penawaran harga baru dengan ID 0ff05aa8-a8dc-478b-8120-7f2b8222b410', '2024-06-17 12:04:09', 0),
(117, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil ubah status PH', 'Penawaran Harga', 'Perubahan status penawaran harga dengan ID 0ff05aa8-a8dc-478b-8120-7f2b8222b410: | . status: &quot;draft&quot; diubah menjadi &quot;disetujui&quot; | ', '2024-06-17 12:04:55', 0),
(118, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil tambah data', 'Produk', 'Berhasil tambah produk dengan ID 482df9fe-64af-40c2-a9f6-0017939ceddc', '2024-06-17 12:16:09', 0),
(119, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil ubah data', 'Produk', 'Perubahan data produk dengan ID 482df9fe-64af-40c2-a9f6-0017939ceddc: | 1. nama_produk: &quot;electro lifter &amp;amp; charger sugico 3fw915v for gear&quot; diubah menjadi &quot;electro lifter &amp; charger sugico 3fw915v for gear&quot; | 2. status: &quot;draft&quot; diubah menjadi &quot;disetujui&quot; | ', '2024-06-17 12:17:01', 0),
(120, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil membuat PH', 'Penawaran Harga', 'Berhasil membuat penawaran harga baru dengan ID 13d2c29c-49ad-497c-abd2-43f58b9ff477', '2024-06-17 12:22:18', 0),
(121, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil membuat PH', 'Penawaran Harga', 'Berhasil membuat penawaran harga baru dengan ID a1c9d0b0-906d-4a07-b0fc-55761c092da5', '2024-06-17 13:00:05', 0),
(122, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil hapus data', 'Penawaran Harga', 'Berhasil hapus penawaran harga dengan ID a1c9d0b0-906d-4a07-b0fc-55761c092da5, data tetap ada dalam database!', '2024-06-17 13:06:27', 0),
(123, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil membuat PH', 'Penawaran Harga', 'Berhasil membuat penawaran harga baru dengan ID 1f5af713-47cc-4000-981e-25ae0dba6dd7', '2024-06-17 13:54:55', 0),
(124, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil hapus data', 'Penawaran Harga', 'Berhasil hapus penawaran harga dengan ID 1f5af713-47cc-4000-981e-25ae0dba6dd7, data tetap ada dalam database!', '2024-06-17 13:56:46', 0),
(125, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil ubah status PH', 'Penawaran Harga', 'Perubahan status penawaran harga dengan ID 13d2c29c-49ad-497c-abd2-43f58b9ff477: | . status: &quot;draft&quot; diubah menjadi &quot;disetujui&quot; | ', '2024-06-17 14:35:13', 0),
(126, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil membuat PO', 'Pesanan Pembelian', 'Berhasil membuat pesanan pembelian baru dengan ID dfb2c5b4-c758-4aa8-96f1-734c46430eab', '2024-06-17 14:52:26', 0),
(127, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil ubah status PO', 'Pesanan Pembelian', 'Perubahan status pesanan pembelian dengan ID dfb2c5b4-c758-4aa8-96f1-734c46430eab: | . status: &quot;draft&quot; diubah menjadi &quot;terkirim&quot; | ', '2024-06-17 15:03:41', 0),
(128, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil ubah status PO', 'Pesanan Pembelian', 'Perubahan status pesanan pembelian dengan ID dfb2c5b4-c758-4aa8-96f1-734c46430eab: | . status: &quot;terkirim&quot; diubah menjadi &quot;diproses&quot; | ', '2024-06-17 15:05:53', 0),
(129, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil membuat PO', 'Pesanan Pembelian', 'Berhasil membuat pesanan pembelian baru dengan ID e6ef7a30-4ed2-4a33-b0f5-6f2408bd9cc4', '2024-06-17 15:15:59', 0),
(130, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil ubah PO', 'Pesanan Pembelian', 'Perubahan data pesanan pembelian dengan ID e6ef7a30-4ed2-4a33-b0f5-6f2408bd9cc4: | 1. no_pesanan: &quot;001/po/mtg/03/2024&quot; diubah menjadi &quot;002/po/mtg/03/2024&quot; | ', '2024-06-17 15:25:07', 0),
(131, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil ubah status PO', 'Pesanan Pembelian', 'Perubahan status pesanan pembelian dengan ID e6ef7a30-4ed2-4a33-b0f5-6f2408bd9cc4: | . status: &quot;draft&quot; diubah menjadi &quot;selesai&quot; | ', '2024-06-17 15:25:23', 0),
(132, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil membuat PO', 'Pesanan Pembelian', 'Berhasil membuat pesanan pembelian baru dengan ID 340ce501-5f1d-4235-9c4f-4af2b22037f8', '2024-06-17 15:38:55', 0),
(133, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil membuat PO', 'Pesanan Pembelian', 'Berhasil membuat pesanan pembelian baru dengan ID 70fbf929-ddbe-4024-baff-00b09b3af0b1', '2024-06-17 15:41:10', 0),
(134, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil ubah status PO', 'Pesanan Pembelian', 'Perubahan status pesanan pembelian dengan ID 340ce501-5f1d-4235-9c4f-4af2b22037f8: | . status: &quot;draft&quot; diubah menjadi &quot;diproses&quot; | ', '2024-06-17 15:41:29', 0),
(135, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil ubah status PO', 'Pesanan Pembelian', 'Perubahan status pesanan pembelian dengan ID 70fbf929-ddbe-4024-baff-00b09b3af0b1: | . status: &quot;draft&quot; diubah menjadi &quot;diproses&quot; | ', '2024-06-17 15:41:37', 0),
(136, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil membuat PO', 'Pesanan Pembelian', 'Berhasil membuat pesanan pembelian baru dengan ID 86cf7390-c11a-4a40-ae14-2d97269b3efb', '2024-06-17 15:46:57', 0),
(137, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil membuat PO', 'Pesanan Pembelian', 'Berhasil membuat pesanan pembelian baru dengan ID 485d95f1-fd75-4949-aaa8-9f1586b8c7ec', '2024-06-17 15:48:55', 0),
(138, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil ubah status PO', 'Pesanan Pembelian', 'Perubahan status pesanan pembelian dengan ID 485d95f1-fd75-4949-aaa8-9f1586b8c7ec: | . status: &quot;draft&quot; diubah menjadi &quot;diproses&quot; | ', '2024-06-17 15:49:14', 0),
(139, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil ubah status PO', 'Pesanan Pembelian', 'Perubahan status pesanan pembelian dengan ID 86cf7390-c11a-4a40-ae14-2d97269b3efb: | . status: &quot;draft&quot; diubah menjadi &quot;diproses&quot; | ', '2024-06-17 15:49:21', 0),
(140, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil membuat invoice baru', 'faktur', 'Pengguna dengan ID 9167ed40-435e-4e18-a4d0-59676a89c511 berhasil membuat invoice baru dengan ID 65f4fd6c-6cc9-400f-80e3-0bd97c9fa5de', '2024-06-17 16:30:27', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `penawaran_harga`
--

CREATE TABLE `penawaran_harga` (
  `id_penawaran` varchar(36) NOT NULL,
  `id_pengirim` varchar(36) NOT NULL,
  `no_penawaran` varchar(50) NOT NULL,
  `tanggal` datetime NOT NULL,
  `total` int NOT NULL,
  `catatan` text,
  `id_penerima` varchar(36) NOT NULL,
  `up` varchar(100) DEFAULT NULL,
  `id_ppn` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `diskon` int NOT NULL,
  `logo` varchar(100) DEFAULT NULL,
  `signature_info` longtext,
  `kategori` enum('masuk','keluar') NOT NULL,
  `status` enum('draft','ditolak','disetujui') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `status_hapus` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `penawaran_harga`
--

INSERT INTO `penawaran_harga` (`id_penawaran`, `id_pengirim`, `no_penawaran`, `tanggal`, `total`, `catatan`, `id_penerima`, `up`, `id_ppn`, `diskon`, `logo`, `signature_info`, `kategori`, `status`, `status_hapus`) VALUES
('0ff05aa8-a8dc-478b-8120-7f2b8222b410', '31c50f21-12b9-4826-9345-52c604ec4ee2', '019/ph/mtg/12/2023', '2023-12-06 06:00:00', 2575, 'harga belum termasuk ppn 11%.', '5c091a0a-9796-4d5d-b009-526830bd6386', 'bpk. rudi rusminarno', '3395fb1f-a551-4bfd-a9ff-8ce407a108bc', 0, '../../assets/image/uploads/logo/6670224d3328a_20240617.jpeg', 'Location: cikarang pusat, Date: 2023-12-06, Name: supriyadi, Position: direktur, Path: ', 'keluar', 'disetujui', 0),
('13d2c29c-49ad-497c-abd2-43f58b9ff477', '5c091a0a-9796-4d5d-b009-526830bd6386', '003/iii/fa/2024', '2024-03-08 06:00:00', 7992000, 'payment term : 100% advance incoterm : ex-work', '31c50f21-12b9-4826-9345-52c604ec4ee2', '', 'e502e6a9-e463-4bb0-9a78-0e30dc229722', 0, '', 'Location: ypmi, Date: 2024-03-08, Name: finance division pt. ypmi, Position: finance division, Path: ', 'masuk', 'disetujui', 0),
('1f5af713-47cc-4000-981e-25ae0dba6dd7', '31c50f21-12b9-4826-9345-52c604ec4ee2', '002/ph/mtg/06/2024', '2024-06-17 20:54:00', 1, '', 'bc24623f-d5ed-44e5-b13a-fdf04fbdf61c', '', 'c60dba5e-bd05-4d68-8d2e-7d8c519a9ca7', 0, '../../assets/image/uploads/logo/6670224d3328a_20240617.jpeg', 'Location: dsa, Date: 2024-01-01, Name: lkamsdf, Position: mkmda, Path: ', 'keluar', 'draft', 1),
('a1c9d0b0-906d-4a07-b0fc-55761c092da5', 'b949e00a-c473-49fb-86b1-48d79bca71e0', '001/ph/mtg/06/2024', '2024-06-17 19:59:00', 2, '', 'e484aa25-f2ee-422b-8e55-63e8d556e6cb', '', '3395fb1f-a551-4bfd-a9ff-8ce407a108bc', 0, '../../assets/image/uploads/logo/6670224d3328a_20240617.jpeg', 'Location: hsafkjh;a, Date: 2024-11-11, Name: adjfsakj, Position: jdsal, Path: ', 'keluar', 'draft', 1),
('b205f982-33f0-4c4a-9e60-f1d04e34c59d', '31c50f21-12b9-4826-9345-52c604ec4ee2', '018/ph/mtg/12/2023', '2023-12-06 06:00:00', 2575, 'harga belum termasuk ppn 11%.', '5c091a0a-9796-4d5d-b009-526830bd6386', 'bpk. rudi rusminarno', '3395fb1f-a551-4bfd-a9ff-8ce407a108bc', 0, '../../assets/image/uploads/logo/6670224d3328a_20240617.jpeg', 'Location: cikarang pusat, Date: 2023-12-16, Name: supriyadi, Position: direktur, Path: ', 'keluar', 'disetujui', 0),
('bf14e250-cb62-4f29-99a2-b9ff92609de8', '31c50f21-12b9-4826-9345-52c604ec4ee2', '002/ph/mtg/10/2018', '2018-10-05 06:00:00', 110, 'harga belum termasuk ppn 11% (dokumen telah di approve kembali tgl 05 maret 2024)', '5c091a0a-9796-4d5d-b009-526830bd6386', 'bpk. rudi rusminarno', '3395fb1f-a551-4bfd-a9ff-8ce407a108bc', 0, '../../assets/image/uploads/logo/6670224d3328a_20240617.jpeg', 'Location: cikarang pusat, Date: 2018-10-05, Name: supriyadi, Position: direktur, Path: ', 'keluar', 'disetujui', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengguna`
--

CREATE TABLE `pengguna` (
  `id_pengguna` varchar(36) NOT NULL,
  `nama_lengkap` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `nama_pengguna` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `password` varchar(100) NOT NULL,
  `tipe_pengguna` enum('superadmin','staff','kepala_perusahaan','tes') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'staff',
  `status_hapus` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `pengguna`
--

INSERT INTO `pengguna` (`id_pengguna`, `nama_lengkap`, `nama_pengguna`, `password`, `tipe_pengguna`, `status_hapus`) VALUES
('5f8e47d7-7fea-4a8d-8641-a071007f59da', 'ayu lestari', 'staff_1', '$2y$10$688ccUtC2sV68aLU1TYSyeYecVLjtZRtckDcztq4cFkg6.vvL3qI.', 'staff', 0),
('9167ed40-435e-4e18-a4d0-59676a89c511', 'ade muslim', 's_admin', '$2y$10$GmXtGEBcCEe5x89dksFw0uOUrVErfX7EwHjffm3ujhd9Fxc1i4rxm', 'superadmin', 0),
('c8f7e282-b9ec-478f-a479-8219a9d5c85a', 'supriyadi', 'direktur', '$2y$10$KP/AxDH4mLaqCxT.SHgOaexoMfFQRrcfUSPzesJ74NWd5OsJsQv2y', 'kepala_perusahaan', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pesanan_pembelian`
--

CREATE TABLE `pesanan_pembelian` (
  `id_pesanan` varchar(36) NOT NULL,
  `id_pengirim` varchar(36) NOT NULL,
  `no_pesanan` varchar(50) NOT NULL,
  `tanggal` datetime NOT NULL,
  `total` int NOT NULL,
  `catatan` text,
  `id_penerima` varchar(36) NOT NULL,
  `up` varchar(100) DEFAULT NULL,
  `diskon` int DEFAULT NULL,
  `id_ppn` varchar(36) DEFAULT NULL,
  `logo` varchar(100) DEFAULT NULL,
  `signature_info` longtext,
  `kategori` enum('masuk','keluar') NOT NULL,
  `status` enum('draft','terkirim','diproses','selesai') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `status_hapus` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `pesanan_pembelian`
--

INSERT INTO `pesanan_pembelian` (`id_pesanan`, `id_pengirim`, `no_pesanan`, `tanggal`, `total`, `catatan`, `id_penerima`, `up`, `diskon`, `id_ppn`, `logo`, `signature_info`, `kategori`, `status`, `status_hapus`) VALUES
('340ce501-5f1d-4235-9c4f-4af2b22037f8', '5c091a0a-9796-4d5d-b009-526830bd6386', 'te/wh2/ypmi/mpo/2024/04/39437', '2024-04-29 00:01:00', 3663000, '', '31c50f21-12b9-4826-9345-52c604ec4ee2', '', 0, 'e502e6a9-e463-4bb0-9a78-0e30dc229722', '', 'Location: ypmi, Date: 2024-04-29, Name: yamada toshio, Position: gm purchasing, Path: ', 'masuk', 'diproses', 0),
('485d95f1-fd75-4949-aaa8-9f1586b8c7ec', '5c091a0a-9796-4d5d-b009-526830bd6386', 'te/wh2/ypmi/mpo/2024/03/38971', '2024-03-25 00:01:00', 657397500, '', '31c50f21-12b9-4826-9345-52c604ec4ee2', '', 0, 'e502e6a9-e463-4bb0-9a78-0e30dc229722', '', 'Location: ypmi, Date: 2024-03-25, Name: yamada yoshio, Position: gm purchasing, Path: ', 'masuk', 'diproses', 0),
('70fbf929-ddbe-4024-baff-00b09b3af0b1', '5c091a0a-9796-4d5d-b009-526830bd6386', 'te/wh2/ypmi/mpo/2024/04/39436', '2024-04-29 00:01:00', 714562500, '', '31c50f21-12b9-4826-9345-52c604ec4ee2', '', 0, 'e502e6a9-e463-4bb0-9a78-0e30dc229722', '', 'Location: ypmi, Date: 2024-04-29, Name: yamada yoshio, Position: gm purchasing, Path: ', 'masuk', 'diproses', 0),
('86cf7390-c11a-4a40-ae14-2d97269b3efb', '5c091a0a-9796-4d5d-b009-526830bd6386', 'te/wh2/ypmi/mpo/2024/03/38970', '2024-03-25 00:01:00', 2442000, '', '31c50f21-12b9-4826-9345-52c604ec4ee2', '', 0, 'e502e6a9-e463-4bb0-9a78-0e30dc229722', '', 'Location: ypmi, Date: 2024-03-25, Name: yamada yoshio, Position: gm purchasing, Path: ', 'masuk', 'diproses', 0),
('dfb2c5b4-c758-4aa8-96f1-734c46430eab', '5c091a0a-9796-4d5d-b009-526830bd6386', 'te/wh2/ypmi/mpo/2024/05/39815', '2024-05-28 00:01:00', 714562500, '', '31c50f21-12b9-4826-9345-52c604ec4ee2', '', 0, 'e502e6a9-e463-4bb0-9a78-0e30dc229722', '', 'Location: karawang, Date: 2024-05-28, Name: yamada yoshio, Position: gm purchasing, Path: ', 'masuk', 'diproses', 0),
('e6ef7a30-4ed2-4a33-b0f5-6f2408bd9cc4', '31c50f21-12b9-4826-9345-52c604ec4ee2', '002/po/mtg/03/2024', '2024-03-25 00:01:00', 7992000, '', '5c091a0a-9796-4d5d-b009-526830bd6386', '', 0, 'e502e6a9-e463-4bb0-9a78-0e30dc229722', '../../assets/image/uploads/logo/6670224d3328a_20240617.jpeg', 'Location: cikarang pusat, Date: 2024-03-25, Name: supriyadi, Position: direktur, Path: ', 'keluar', 'selesai', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `ppn`
--

CREATE TABLE `ppn` (
  `id_ppn` varchar(36) NOT NULL,
  `jenis_ppn` varchar(100) NOT NULL,
  `tarif` int NOT NULL,
  `keterangan` text,
  `status_hapus` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `ppn`
--

INSERT INTO `ppn` (`id_ppn`, `jenis_ppn`, `tarif`, `keterangan`, `status_hapus`) VALUES
('3395fb1f-a551-4bfd-a9ff-8ce407a108bc', 'tanpa ppn', 0, 'pilih jenis ppn &quot;tanpa ppn&quot; jika transaksi tanpa ppn.', 0),
('c60dba5e-bd05-4d68-8d2e-7d8c519a9ca7', 'ppn 10%', 10, 'tarif dalam persen', 1),
('e502e6a9-e463-4bb0-9a78-0e30dc229722', 'ppn 11%', 11, 'tarif dalam persen', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `produk`
--

CREATE TABLE `produk` (
  `id_produk` varchar(36) NOT NULL,
  `no_produk` varchar(50) NOT NULL,
  `nama_produk` varchar(100) NOT NULL,
  `satuan` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `harga` int UNSIGNED NOT NULL,
  `status` enum('draft','pending','ditolak','disetujui') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'draft',
  `keterangan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `status_hapus` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `produk`
--

INSERT INTO `produk` (`id_produk`, `no_produk`, `nama_produk`, `satuan`, `harga`, `status`, `keterangan`, `status_hapus`) VALUES
('2c3242ff-6b53-4ced-a89c-e08039ef6a77', 'S-2852', 'repair deburring + transportasi ypmi - mitra', 'pcs', 2575, 'disetujui', '', 0),
('482df9fe-64af-40c2-a9f6-0017939ceddc', 'fa20031006600', 'electro lifter & charger sugico 3fw915v for gear', 'unit', 3600000, 'disetujui', 'produk penawaran dari pt. ypmi', 0),
('51f151a4-ac95-4782-818f-b56d9a780123', 'S-6309', 'proses forming wiremesh', 'pcs', 110, 'disetujui', '', 0),
('7e360f04-140f-407a-a314-9af07bfdc778', '4340-R/SNCM439-R', '4340-r/sncm439-r dia 28 x 1000 mm', 'pcs', 232750, 'disetujui', '', 0),
('88dd7064-ccd4-4f7f-9c3d-7b1722066d4f', 'S-10445', 'proses deburring + transportasi part head cylinder', 'pcs', 2575, 'disetujui', 'jasa untuk ypmi', 0),
('981cc618-9587-45e1-b10b-b702604a50d7', 'FAB-L-COM1-lith-0013', 'roller pressing', 'pcs', 240350, 'disetujui', '', 0),
('a4dfdb31-8293-4658-af4b-29e5b329ffc7', 'FAB-L-COM1-LITH-0016', 'tip pressing cr2032l', 'pcs', 85100, 'disetujui', '', 0),
('ab312b6c-516e-492e-93fc-da4d59e643e8', 'FAB-L-COM1-LITH-0018', 'tip pressing cr2450', 'pcs', 85100, 'disetujui', '', 0),
('b944165a-f5c3-4916-8aed-eee1ff3dbc01', 'FAB-L-COM1-LITH-0024', 'cutter lithium pressing', 'pcs', 115000, 'disetujui', '', 0),
('c9473cc4-a0dc-4c55-a783-8a2a9616e7fd', 'fab-l-com1-tamp-0042', 'chopper blade', 'set', 2700200, 'disetujui', '', 0),
('e1c0f235-f098-4043-bcdb-16b298c2383a', 'fa10031902300', 'h/c heli stacker full electric', 'unit', 3600000, 'disetujui', '', 0);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `detail_faktur`
--
ALTER TABLE `detail_faktur`
  ADD PRIMARY KEY (`id_detail_faktur`),
  ADD KEY `id_produk` (`id_produk`),
  ADD KEY `id_faktur` (`id_faktur`),
  ADD KEY `id_pesanan` (`id_pesanan`);

--
-- Indeks untuk tabel `detail_penawaran`
--
ALTER TABLE `detail_penawaran`
  ADD PRIMARY KEY (`id_detail_penawaran`),
  ADD KEY `id_produk` (`id_produk`),
  ADD KEY `detail_penawaran_ibfk_3` (`id_penawaran`);

--
-- Indeks untuk tabel `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  ADD PRIMARY KEY (`id_detail_pesanan`),
  ADD KEY `id_produk` (`id_produk`),
  ADD KEY `id_pesanan` (`id_pesanan`),
  ADD KEY `id_penawaran` (`id_penawaran`);

--
-- Indeks untuk tabel `faktur`
--
ALTER TABLE `faktur`
  ADD PRIMARY KEY (`id_faktur`),
  ADD KEY `id_pengirim` (`id_pengirim`),
  ADD KEY `id_ppn` (`id_ppn`),
  ADD KEY `faktur_ibfk_5` (`id_penerima`);

--
-- Indeks untuk tabel `kontak`
--
ALTER TABLE `kontak`
  ADD PRIMARY KEY (`id_kontak`);

--
-- Indeks untuk tabel `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  ADD PRIMARY KEY (`id_log`),
  ADD KEY `id_pengguna` (`id_pengguna`);

--
-- Indeks untuk tabel `penawaran_harga`
--
ALTER TABLE `penawaran_harga`
  ADD PRIMARY KEY (`id_penawaran`),
  ADD KEY `id_pengirim` (`id_pengirim`),
  ADD KEY `id_ppn` (`id_ppn`),
  ADD KEY `id_penerima` (`id_penerima`);

--
-- Indeks untuk tabel `pengguna`
--
ALTER TABLE `pengguna`
  ADD PRIMARY KEY (`id_pengguna`);

--
-- Indeks untuk tabel `pesanan_pembelian`
--
ALTER TABLE `pesanan_pembelian`
  ADD PRIMARY KEY (`id_pesanan`),
  ADD KEY `id_pengirim` (`id_pengirim`),
  ADD KEY `id_ppn` (`id_ppn`),
  ADD KEY `pesanan_pembelian_ibfk_2` (`id_penerima`);

--
-- Indeks untuk tabel `ppn`
--
ALTER TABLE `ppn`
  ADD PRIMARY KEY (`id_ppn`);

--
-- Indeks untuk tabel `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id_produk`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  MODIFY `id_log` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=141;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `detail_faktur`
--
ALTER TABLE `detail_faktur`
  ADD CONSTRAINT `detail_faktur_ibfk_2` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`),
  ADD CONSTRAINT `detail_faktur_ibfk_3` FOREIGN KEY (`id_faktur`) REFERENCES `faktur` (`id_faktur`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `detail_faktur_ibfk_4` FOREIGN KEY (`id_pesanan`) REFERENCES `pesanan_pembelian` (`id_pesanan`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ketidakleluasaan untuk tabel `detail_penawaran`
--
ALTER TABLE `detail_penawaran`
  ADD CONSTRAINT `detail_penawaran_ibfk_2` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`),
  ADD CONSTRAINT `detail_penawaran_ibfk_3` FOREIGN KEY (`id_penawaran`) REFERENCES `penawaran_harga` (`id_penawaran`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ketidakleluasaan untuk tabel `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  ADD CONSTRAINT `detail_pesanan_ibfk_2` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`),
  ADD CONSTRAINT `detail_pesanan_ibfk_3` FOREIGN KEY (`id_pesanan`) REFERENCES `pesanan_pembelian` (`id_pesanan`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `detail_pesanan_ibfk_4` FOREIGN KEY (`id_penawaran`) REFERENCES `penawaran_harga` (`id_penawaran`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ketidakleluasaan untuk tabel `faktur`
--
ALTER TABLE `faktur`
  ADD CONSTRAINT `faktur_ibfk_4` FOREIGN KEY (`id_pengirim`) REFERENCES `kontak` (`id_kontak`),
  ADD CONSTRAINT `faktur_ibfk_5` FOREIGN KEY (`id_penerima`) REFERENCES `kontak` (`id_kontak`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `faktur_ibfk_6` FOREIGN KEY (`id_ppn`) REFERENCES `ppn` (`id_ppn`);

--
-- Ketidakleluasaan untuk tabel `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  ADD CONSTRAINT `log_aktivitas_ibfk_1` FOREIGN KEY (`id_pengguna`) REFERENCES `pengguna` (`id_pengguna`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ketidakleluasaan untuk tabel `penawaran_harga`
--
ALTER TABLE `penawaran_harga`
  ADD CONSTRAINT `penawaran_harga_ibfk_4` FOREIGN KEY (`id_pengirim`) REFERENCES `kontak` (`id_kontak`),
  ADD CONSTRAINT `penawaran_harga_ibfk_6` FOREIGN KEY (`id_ppn`) REFERENCES `ppn` (`id_ppn`),
  ADD CONSTRAINT `penawaran_harga_ibfk_7` FOREIGN KEY (`id_penerima`) REFERENCES `kontak` (`id_kontak`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ketidakleluasaan untuk tabel `pesanan_pembelian`
--
ALTER TABLE `pesanan_pembelian`
  ADD CONSTRAINT `pesanan_pembelian_ibfk_1` FOREIGN KEY (`id_pengirim`) REFERENCES `kontak` (`id_kontak`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `pesanan_pembelian_ibfk_2` FOREIGN KEY (`id_penerima`) REFERENCES `kontak` (`id_kontak`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `pesanan_pembelian_ibfk_3` FOREIGN KEY (`id_ppn`) REFERENCES `ppn` (`id_ppn`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
