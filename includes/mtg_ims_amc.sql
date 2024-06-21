-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 19 Jun 2024 pada 15.46
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
('02338dc2-4423-49a2-8ec3-70c21a52a11a', '6385fa38-bd94-4897-b9dc-11fce76a27ce', '001/spb/mtg/03/2024', '88dd7064-ccd4-4f7f-9c3d-7b1722066d4f', 161983, 2575, '2605208c-e6c0-4dbd-a212-2b0b0efb59f4'),
('3bb237e5-368b-41e2-bb75-4d6c8c1333e7', '6385fa38-bd94-4897-b9dc-11fce76a27ce', '003/spb/mtg/03/2024', '51f151a4-ac95-4782-818f-b56d9a780123', 3500, 110, '91f91c90-cd93-4f4a-bc2e-a05ae0fc4a48'),
('5eefc12b-1172-4974-acc1-a328299e7f28', '6385fa38-bd94-4897-b9dc-11fce76a27ce', '002/spb/mtg/03/2024', '2c3242ff-6b53-4ced-a89c-e08039ef6a77', 29919, 2575, '2605208c-e6c0-4dbd-a212-2b0b0efb59f4'),
('6d21426a-b3de-4867-985b-f4b118e17224', 'bbe2ecf9-076b-44a6-8134-0f9163d5ca59', '001/spb/mtg/06/2024', '51f151a4-ac95-4782-818f-b56d9a780123', 500, 110, '91f91c90-cd93-4f4a-bc2e-a05ae0fc4a48');

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
('061e4827-3e3b-4158-9f0f-ec9863afb919', '1dccfe5a-9050-4070-887c-41340aaa3f9a', '2c3242ff-6b53-4ced-a89c-e08039ef6a77', 1, 2575),
('1d39c611-d0f7-49e7-91b9-cc5a6a05ebce', '9e3dac37-d8e3-404b-9e63-2603244c81fb', '88dd7064-ccd4-4f7f-9c3d-7b1722066d4f', 1, 2575),
('3c0db790-e462-4bd5-8799-224ebd9e189e', '0119b7a6-454a-4113-a273-0612b7b3b5c1', '51f151a4-ac95-4782-818f-b56d9a780123', 1, 110);

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
('0d29e1d1-fb2b-4488-981a-b671af424aa5', '91f91c90-cd93-4f4a-bc2e-a05ae0fc4a48', '51f151a4-ac95-4782-818f-b56d9a780123', 20000, 3500, 16500, 110, '0119b7a6-454a-4113-a273-0612b7b3b5c1'),
('b43616ac-405c-4231-a855-aaa481cf9d39', '2605208c-e6c0-4dbd-a212-2b0b0efb59f4', '2c3242ff-6b53-4ced-a89c-e08039ef6a77', 30000, 29919, 81, 2575, '1dccfe5a-9050-4070-887c-41340aaa3f9a'),
('c5ba0537-a9f0-4609-888d-a995352d6618', '2605208c-e6c0-4dbd-a212-2b0b0efb59f4', '88dd7064-ccd4-4f7f-9c3d-7b1722066d4f', 200000, 161983, 38017, 2575, '9e3dac37-d8e3-404b-9e63-2603244c81fb');

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
('6385fa38-bd94-4897-b9dc-11fce76a27ce', '31c50f21-12b9-4826-9345-52c604ec4ee2', '001/inv/mtg/03/2024', '2024-03-30 00:00:00', 548931242, '', '5c091a0a-9796-4d5d-b009-526830bd6386', 0, 'e502e6a9-e463-4bb0-9a78-0e30dc229722', '../../assets/image/uploads/logo/6671b6f4e9299_20240618.jpeg', 'Location: cikarang pusat, Date: 2024-03-30, Name: supriyadi, Position: direktur, Path: ', 'keluar', 'belum dibayar', 0),
('bbe2ecf9-076b-44a6-8134-0f9163d5ca59', '31c50f21-12b9-4826-9345-52c604ec4ee2', '002/inv/mtg/06/2024', '2024-06-19 21:50:00', 61050, '', '5c091a0a-9796-4d5d-b009-526830bd6386', 0, 'e502e6a9-e463-4bb0-9a78-0e30dc229722', '../../assets/image/uploads/logo/6671b6f4e9299_20240618.jpeg', 'Location: bekasi, Date: 2024-06-19, Name: supriyadi, Position: direktur, Path: ', 'keluar', 'tunggu kirim', 1);

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
(161, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Login berhasil', 'Pengguna', 'Pengguna dengan username s_admin berhasil login.', '2024-06-18 16:27:38', 0),
(162, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil membuat PH', 'Penawaran Harga', 'Berhasil membuat penawaran harga baru dengan ID 0119b7a6-454a-4113-a273-0612b7b3b5c1', '2024-06-18 16:33:57', 0),
(163, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil ubah PH', 'Penawaran Harga', 'Perubahan data penawaran harga dengan ID 0119b7a6-454a-4113-a273-0612b7b3b5c1: | 1. no_penawaran: &quot;001/ph/mtg/10/2018&quot; diubah menjadi &quot;002/ph/mtg/10/2018&quot; | ', '2024-06-18 16:55:56', 0),
(164, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil ubah status PH', 'Penawaran Harga', 'Perubahan status penawaran harga dengan ID 0119b7a6-454a-4113-a273-0612b7b3b5c1: | . status: &quot;draft&quot; diubah menjadi &quot;disetujui&quot; | ', '2024-06-18 16:56:05', 0),
(165, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil membuat PH', 'Penawaran Harga', 'Berhasil membuat penawaran harga baru dengan ID 9e3dac37-d8e3-404b-9e63-2603244c81fb', '2024-06-18 16:58:15', 0),
(166, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil ubah PH', 'Penawaran Harga', 'Perubahan data penawaran harga dengan ID 9e3dac37-d8e3-404b-9e63-2603244c81fb: | 1. no_penawaran: &quot;001/ph/mtg/12/2023&quot; diubah menjadi &quot;018/ph/mtg/12/2023&quot; | 2. status: &quot;draft&quot; diubah menjadi &quot;disetujui&quot; | ', '2024-06-18 16:58:43', 0),
(167, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil membuat PH', 'Penawaran Harga', 'Berhasil membuat penawaran harga baru dengan ID 1dccfe5a-9050-4070-887c-41340aaa3f9a', '2024-06-18 17:00:26', 0),
(168, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil ubah PH', 'Penawaran Harga', 'Perubahan data penawaran harga dengan ID 1dccfe5a-9050-4070-887c-41340aaa3f9a: | 1. no_penawaran: &quot;001/ph/mtg/12/2024&quot; diubah menjadi &quot;019/ph/mtg/12/2024&quot; | 2. status: &quot;draft&quot; diubah menjadi &quot;disetujui&quot; | ', '2024-06-18 17:00:45', 0),
(169, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil membuat PO', 'Pesanan Pembelian', 'Berhasil membuat pesanan pembelian baru dengan ID 91f91c90-cd93-4f4a-bc2e-a05ae0fc4a48', '2024-06-18 17:04:31', 0),
(170, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil ubah status PO', 'Pesanan Pembelian', 'Perubahan status pesanan pembelian dengan ID 91f91c90-cd93-4f4a-bc2e-a05ae0fc4a48: &quot;status&quot; diubah dari &quot;draft&quot; menjadi &quot;diproses&quot;.', '2024-06-18 17:04:50', 0),
(171, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil membuat PO', 'Pesanan Pembelian', 'Berhasil membuat pesanan pembelian baru dengan ID 2605208c-e6c0-4dbd-a212-2b0b0efb59f4', '2024-06-18 17:07:56', 0),
(172, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil ubah status PO', 'Pesanan Pembelian', 'Perubahan status pesanan pembelian dengan ID 2605208c-e6c0-4dbd-a212-2b0b0efb59f4: &quot;status&quot; diubah dari &quot;draft&quot; menjadi &quot;diproses&quot;.', '2024-06-18 17:08:09', 0),
(173, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil membuat invoice baru', 'faktur', 'Pengguna dengan ID 9167ed40-435e-4e18-a4d0-59676a89c511 berhasil membuat invoice baru dengan ID 6385fa38-bd94-4897-b9dc-11fce76a27ce', '2024-06-18 17:14:42', 0),
(174, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil ubah status invoice', 'Faktur', 'Perubahan status faktur dengan ID 6385fa38-bd94-4897-b9dc-11fce76a27ce: &quot;status&quot; diubah dari &quot;tunggu kirim&quot; menjadi &quot;dibayar&quot;.', '2024-06-18 17:15:15', 0),
(175, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil ubah PH', 'Penawaran Harga', 'Perubahan data penawaran harga dengan ID 1dccfe5a-9050-4070-887c-41340aaa3f9a: | 1. no_penawaran: &quot;019/ph/mtg/12/2024&quot; diubah menjadi &quot;019/ph/mtg/12/2023&quot; | 2. tanggal: &quot;2024-12-06 00:00:00&quot; diubah menjadi &quot;2023-12-06 00:00:00&quot; | ', '2024-06-18 17:20:33', 0),
(176, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Login berhasil', 'Pengguna', 'Pengguna dengan username s_admin berhasil login.', '2024-06-19 11:17:40', 0),
(177, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil ubah status invoice', 'Faktur', 'Perubahan status faktur dengan ID 6385fa38-bd94-4897-b9dc-11fce76a27ce: &quot;status&quot; diubah dari &quot;dibayar&quot; menjadi &quot;tunggu kirim&quot;.', '2024-06-19 12:26:01', 0),
(178, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil ubah status invoice', 'Faktur', 'Perubahan status faktur dengan ID 6385fa38-bd94-4897-b9dc-11fce76a27ce: &quot;status&quot; diubah dari &quot;tunggu kirim&quot; menjadi &quot;dibayar&quot;.', '2024-06-19 12:28:46', 0),
(179, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil ubah status invoice', 'Faktur', 'Perubahan status faktur dengan ID 6385fa38-bd94-4897-b9dc-11fce76a27ce: &quot;status&quot; diubah dari &quot;dibayar&quot; menjadi &quot;tunggu kirim&quot;.', '2024-06-19 12:33:21', 0),
(180, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil ubah status invoice', 'Faktur', 'Perubahan status faktur dengan ID 6385fa38-bd94-4897-b9dc-11fce76a27ce: &quot;status&quot; diubah dari &quot;tunggu kirim&quot; menjadi &quot;belum dibayar&quot;.', '2024-06-19 13:20:51', 0),
(181, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil membuat invoice baru', 'faktur', 'Pengguna dengan ID 9167ed40-435e-4e18-a4d0-59676a89c511 berhasil membuat invoice baru dengan ID bbe2ecf9-076b-44a6-8134-0f9163d5ca59', '2024-06-19 14:52:14', 0),
(182, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil ubah status invoice', 'Faktur', 'Perubahan status faktur dengan ID bbe2ecf9-076b-44a6-8134-0f9163d5ca59: &quot;status&quot; diubah dari &quot;tunggu kirim&quot; menjadi &quot;belum dibayar&quot;.', '2024-06-19 14:52:30', 0),
(183, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil ubah status invoice', 'Faktur', 'Perubahan status faktur dengan ID bbe2ecf9-076b-44a6-8134-0f9163d5ca59: &quot;status&quot; diubah dari &quot;belum dibayar&quot; menjadi &quot;tunggu kirim&quot;.', '2024-06-19 15:11:26', 0),
(184, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil ubah status invoice', 'Faktur', 'Perubahan status faktur dengan ID bbe2ecf9-076b-44a6-8134-0f9163d5ca59: &quot;status&quot; diubah dari &quot;tunggu kirim&quot; menjadi &quot;belum dibayar&quot;.', '2024-06-19 15:11:32', 0),
(185, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil ubah status invoice', 'Faktur', 'Perubahan status faktur dengan ID bbe2ecf9-076b-44a6-8134-0f9163d5ca59: &quot;status&quot; diubah dari &quot;belum dibayar&quot; menjadi &quot;tunggu kirim&quot;.', '2024-06-19 15:22:52', 0),
(186, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil hapus data', 'Faktur', 'Berhasil hapus invoice dengan ID bbe2ecf9-076b-44a6-8134-0f9163d5ca59 (soft delete).', '2024-06-19 15:22:56', 0);

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
('0119b7a6-454a-4113-a273-0612b7b3b5c1', '31c50f21-12b9-4826-9345-52c604ec4ee2', '002/ph/mtg/10/2018', '2018-10-05 00:00:00', 110, 'harga belum termasuk ppn 11%, penawaran harga di update dan telah di approve kembali tgl 04 maret 2024.', '5c091a0a-9796-4d5d-b009-526830bd6386', 'bpk. rudi rusminarno', '3395fb1f-a551-4bfd-a9ff-8ce407a108bc', 0, '../../assets/image/uploads/logo/6671b6f4e9299_20240618.jpeg', 'Location: cikarang pusat, Date: 2024-05-04, Name: supriyadi, Position: direktur, Path: ', 'keluar', 'disetujui', 0),
('1dccfe5a-9050-4070-887c-41340aaa3f9a', '31c50f21-12b9-4826-9345-52c604ec4ee2', '019/ph/mtg/12/2023', '2023-12-06 00:00:00', 2575, 'harga belum termasuk ppn 11%', '5c091a0a-9796-4d5d-b009-526830bd6386', 'bpk rudi rusminarno', '3395fb1f-a551-4bfd-a9ff-8ce407a108bc', 0, '../../assets/image/uploads/logo/6671b6f4e9299_20240618.jpeg', 'Location: cikarang pusat, Date: 2024-12-06, Name: supriyadi, Position: direktur, Path: ', 'keluar', 'disetujui', 0),
('9e3dac37-d8e3-404b-9e63-2603244c81fb', '31c50f21-12b9-4826-9345-52c604ec4ee2', '018/ph/mtg/12/2023', '2023-12-06 00:00:00', 2575, 'harga belum termasuk ppn 11%.', '5c091a0a-9796-4d5d-b009-526830bd6386', 'bpk. rudi rusminarno', '3395fb1f-a551-4bfd-a9ff-8ce407a108bc', 0, '../../assets/image/uploads/logo/6671b6f4e9299_20240618.jpeg', 'Location: cikarang pusat, Date: 2024-12-06, Name: supriyadi, Position: direktur, Path: ', 'keluar', 'disetujui', 0);

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
('2605208c-e6c0-4dbd-a212-2b0b0efb59f4', '5c091a0a-9796-4d5d-b009-526830bd6386', 'te/wh2/ypmi/mpo/2024/03/38971', '2024-03-25 00:00:00', 657397500, '', '31c50f21-12b9-4826-9345-52c604ec4ee2', '', 0, 'e502e6a9-e463-4bb0-9a78-0e30dc229722', '', 'Location: ypmi, Date: 2024-03-25, Name: yamada yoshio, Position: gm purchasing, Path: ', 'masuk', 'diproses', 0),
('91f91c90-cd93-4f4a-bc2e-a05ae0fc4a48', '5c091a0a-9796-4d5d-b009-526830bd6386', 'te/wh2/ypmi/mpo/2024/03/38970', '2024-03-25 00:00:00', 2442000, '', '31c50f21-12b9-4826-9345-52c604ec4ee2', '', 0, 'e502e6a9-e463-4bb0-9a78-0e30dc229722', '', 'Location: ypmi, Date: 2024-03-25, Name: yamada yoshio, Position: gm purchasing, Path: ', 'masuk', 'diproses', 0);

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
  MODIFY `id_log` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=187;

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
