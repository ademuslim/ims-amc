-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 01 Jun 2024 pada 09.56
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
  `id_produk` varchar(36) NOT NULL,
  `jumlah` int NOT NULL,
  `harga_satuan` int NOT NULL,
  `id_pesanan` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
('1e69ccc2-7187-4591-9630-1ae5c467980d', 'e20249ef-2679-40fc-bf68-413df25756d4', '88dd7064-ccd4-4f7f-9c3d-7b1722066d4f', 1, 2575),
('21fcfb4d-3c94-4bb3-b988-c8eb07510ead', '374f4016-e3c3-4040-9279-cfa2bf6f7cd1', 'ab312b6c-516e-492e-93fc-da4d59e643e8', 1, 85100),
('b328e01c-c0f6-4f7b-84e8-a3df8a88964e', '78aa73c7-908b-4ba6-b448-1b5397bf8048', 'b944165a-f5c3-4916-8aed-eee1ff3dbc01', 1, 115000),
('bcee8cf7-e546-45e2-827a-3dd5475677a0', '7b57cabc-716d-41d1-8dee-551e5ba8becc', '51f151a4-ac95-4782-818f-b56d9a780123', 1, 110),
('be4e6214-7c68-4352-858f-5cb48c3e1039', '6ced62ab-1bf1-4e1f-8ba7-811dd79440d0', 'a4dfdb31-8293-4658-af4b-29e5b329ffc7', 1, 85100),
('c24b7ccb-122d-4994-a99b-f36d7bd40356', 'b5734157-3145-465a-8be6-6f312e84ac1e', '2c3242ff-6b53-4ced-a89c-e08039ef6a77', 1, 2575),
('d9d05740-8817-40f8-9e3b-59df8b513310', '0d9bb748-91e5-49ee-9248-15b160dd8ec1', '981cc618-9587-45e1-b10b-b702604a50d7', 1, 240350);

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
('2a2b9f0b-2663-4d99-a462-fb1d14a61c2a', '0abdf579-a5a6-41ae-8b5a-eebade7f39e2', '2c3242ff-6b53-4ced-a89c-e08039ef6a77', 30000, 0, 30000, 2575, 'b5734157-3145-465a-8be6-6f312e84ac1e'),
('652f884b-d935-4128-9f4f-9977a32fb681', 'afd4bc07-ea98-41c2-853c-bac2bc5207af', '51f151a4-ac95-4782-818f-b56d9a780123', 30000, 0, 30000, 110, '7b57cabc-716d-41d1-8dee-551e5ba8becc'),
('6e6b2bab-a09e-4871-a42c-36161b8cbaad', '7fa75e3f-c92b-4e3b-b8e7-ff30848f4aca', '88dd7064-ccd4-4f7f-9c3d-7b1722066d4f', 200000, 0, 200000, 2575, 'e20249ef-2679-40fc-bf68-413df25756d4'),
('b1c0b8fb-a4a1-4ba1-a76f-4de174d30edd', '0abdf579-a5a6-41ae-8b5a-eebade7f39e2', '88dd7064-ccd4-4f7f-9c3d-7b1722066d4f', 200000, 0, 200000, 2575, 'e20249ef-2679-40fc-bf68-413df25756d4'),
('bb8fbbae-c046-48f1-bc7e-950c9e2ba3e4', '7fa75e3f-c92b-4e3b-b8e7-ff30848f4aca', '2c3242ff-6b53-4ced-a89c-e08039ef6a77', 50000, 0, 50000, 2575, 'b5734157-3145-465a-8be6-6f312e84ac1e'),
('cbddebdc-d89b-47b3-ab03-c633fa8269f1', 'db1f18d1-d6ca-4fe3-9fd8-fd6af3e0511a', '51f151a4-ac95-4782-818f-b56d9a780123', 20000, 0, 20000, 110, '7b57cabc-716d-41d1-8dee-551e5ba8becc');

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
  `status` enum('tunggu kirim','belum dibayar','dibayar') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
  `keterangan` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `kontak`
--

INSERT INTO `kontak` (`id_kontak`, `nama_kontak`, `kategori`, `alamat`, `telepon`, `email`, `keterangan`) VALUES
('0851461f-f566-4979-92a7-6e9c4c6ef7d9', 'supriyadi', 'internal', 'jl. rusa', '', 'supriyadi.mtg@gmail.com', 'kontak pribadi direktur pt. mitra'),
('31c50f21-12b9-4826-9345-52c604ec4ee2', 'pt. mitra tehno gemilang', 'internal', 'office : jl. rawa banteng rt.01 rw.02 kel. jaya mukti kec. cikarang pusat - bekasi', '02129481360', 'mitra_tehnogemilang@yahoo.co.id', 'kontak utama perusahaan.'),
('5c091a0a-9796-4d5d-b009-526830bd6386', 'pt. yamaha motor parts mfg. indonesia', 'customer', 'jl. permata raya lot f2& f6 po box. 157 kiic. karawang - jawa barat', '', '', 'kontak utama ypmi'),
('66fe9784-7a4f-4097-b25c-57872b4bd101', 'pt. stilmetindo prima', 'supplier', 'jl. marina indah golf,rukan eksklusive blok i no 6-7 bgm pikrt.004 rw.003 kamal muara,penjaringan,jakarta utara 14470', '02155965878', '', '-'),
('7bf7dcf6-e4d2-48fe-bc45-c5b590b8aa73', 'pt. tritama teknik indo', 'supplier', 'deltamas', '', 'mail@m', 'tes'),
('bc24623f-d5ed-44e5-b13a-fdf04fbdf61c', 'pt. panasonic gobel energy indonesia', 'customer', 'kawasan industri gobel, jl. teuku umar km.44, telaga asih, cikarang barat, bekasi, jawa barat 17530', '02188324681', '', 'kontak utama pecgi');

-- --------------------------------------------------------

--
-- Struktur dari tabel `log_aktivitas`
--

CREATE TABLE `log_aktivitas` (
  `id_log` varchar(36) NOT NULL,
  `id_pengguna` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `aktivitas` varchar(225) NOT NULL,
  `tabel` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `keterangan` text NOT NULL,
  `tanggal` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
  `status` enum('draft','ditolak','disetujui') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `penawaran_harga`
--

INSERT INTO `penawaran_harga` (`id_penawaran`, `id_pengirim`, `no_penawaran`, `tanggal`, `total`, `catatan`, `id_penerima`, `up`, `id_ppn`, `diskon`, `logo`, `signature_info`, `kategori`, `status`) VALUES
('0d9bb748-91e5-49ee-9248-15b160dd8ec1', '31c50f21-12b9-4826-9345-52c604ec4ee2', '002/ph/mtg/01/2024', '2024-01-12 11:33:00', 240350, 'harga belum termasuk ppn 11%', 'bc24623f-d5ed-44e5-b13a-fdf04fbdf61c', '', '3395fb1f-a551-4bfd-a9ff-8ce407a108bc', 0, '../../assets/image/uploads/logo/6656a751ee2e7_20240529.jpeg', 'Location: cikarang, Date: 2024-01-12, Name: supriyadi, Position: direktur, Path: ', 'keluar', 'disetujui'),
('374f4016-e3c3-4040-9279-cfa2bf6f7cd1', '31c50f21-12b9-4826-9345-52c604ec4ee2', '004/ph/mtg/01/2024', '2024-01-12 11:37:00', 85100, 'harga belum termasuk ppn 11%', 'bc24623f-d5ed-44e5-b13a-fdf04fbdf61c', '', '3395fb1f-a551-4bfd-a9ff-8ce407a108bc', 0, '../../assets/image/uploads/logo/6656a751ee2e7_20240529.jpeg', 'Location: cikarang, Date: 2024-01-12, Name: supriyadi, Position: direktur, Path: ', 'keluar', 'draft'),
('6ced62ab-1bf1-4e1f-8ba7-811dd79440d0', '31c50f21-12b9-4826-9345-52c604ec4ee2', '003/ph/mtg/01/2024', '2024-01-12 11:35:00', 85100, 'harga belum termasuk ppn 11%', 'bc24623f-d5ed-44e5-b13a-fdf04fbdf61c', '', '3395fb1f-a551-4bfd-a9ff-8ce407a108bc', 0, '../../assets/image/uploads/logo/6656a751ee2e7_20240529.jpeg', 'Location: cikarang, Date: 2024-01-12, Name: supriyadi, Position: direktur, Path: ', 'keluar', 'disetujui'),
('78aa73c7-908b-4ba6-b448-1b5397bf8048', '31c50f21-12b9-4826-9345-52c604ec4ee2', '005/ph/mtg/01/2024', '2024-01-12 11:39:00', 115000, 'harga belum termasuk ppn 11%', 'bc24623f-d5ed-44e5-b13a-fdf04fbdf61c', '', '3395fb1f-a551-4bfd-a9ff-8ce407a108bc', 0, '../../assets/image/uploads/logo/6656a751ee2e7_20240529.jpeg', 'Location: cikarang, Date: 2024-10-12, Name: supriyadi, Position: direktur, Path: ', 'keluar', 'ditolak'),
('7b57cabc-716d-41d1-8dee-551e5ba8becc', '31c50f21-12b9-4826-9345-52c604ec4ee2', '001/ph/mtg/09/2019', '2019-09-06 10:53:00', 110, 'harga di atas belum termasuk ppn 10%', '5c091a0a-9796-4d5d-b009-526830bd6386', 'bpk. rudi rusminarno', '3395fb1f-a551-4bfd-a9ff-8ce407a108bc', 0, '../../assets/image/uploads/logo/6656a751ee2e7_20240529.jpeg', 'Location: cikarang, Date: 2019-09-06, Name: supriyadi, Position: direktur, Path: ', 'keluar', 'disetujui'),
('b5734157-3145-465a-8be6-6f312e84ac1e', '31c50f21-12b9-4826-9345-52c604ec4ee2', '019/ph/mtg/12/2023', '2023-12-06 11:01:00', 2575, 'harga tersebut belum termasuk ppn 11%', '5c091a0a-9796-4d5d-b009-526830bd6386', 'bpk. rudi rusminarno', '3395fb1f-a551-4bfd-a9ff-8ce407a108bc', 0, '../../assets/image/uploads/logo/6656a751ee2e7_20240529.jpeg', 'Location: cikarang, Date: 2023-12-06, Name: supriyadi, Position: direktur, Path: ', 'keluar', 'disetujui'),
('e20249ef-2679-40fc-bf68-413df25756d4', '31c50f21-12b9-4826-9345-52c604ec4ee2', '018/ph/mtg/12/2023', '2023-12-06 10:58:00', 2575, 'harga tersebut belum termasuk ppn 11%.', '5c091a0a-9796-4d5d-b009-526830bd6386', 'bpk. rudi rusminarno', '3395fb1f-a551-4bfd-a9ff-8ce407a108bc', 0, '../../assets/image/uploads/logo/6656a751ee2e7_20240529.jpeg', 'Location: cikarang, Date: 2023-12-06, Name: supriyadi, Position: direktur, Path: ', 'keluar', 'disetujui');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengguna`
--

CREATE TABLE `pengguna` (
  `id_pengguna` varchar(36) NOT NULL,
  `nama_pengguna` varchar(100) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `tipe_pengguna` enum('superadmin','staff','kepala_perusahaan','tes') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'staff'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `pengguna`
--

INSERT INTO `pengguna` (`id_pengguna`, `nama_pengguna`, `email`, `password`, `tipe_pengguna`) VALUES
('2cc88eb8-1bca-4120-b42b-437f9b248ac2', 'supriyadi', 'supriyadi.mtg@gmail.com', '$2y$10$ul0X9oGe0tCuFHMu9LkM8eM.H8i3tBx3ImW2GAVVxNaw2J70l7buG', 'kepala_perusahaan'),
('9167ed40-435e-4e18-a4d0-59676a89c511', 'ade muslim', 'amuslim@mhs.pelitabangsa.ac.id', '$2y$10$GmXtGEBcCEe5x89dksFw0uOUrVErfX7EwHjffm3ujhd9Fxc1i4rxm', 'superadmin'),
('f3193612-5711-424e-843d-73501ade776d', 'ade muslim', 'amuslim.ppic.mtg@gmail.com', '$2y$10$R0NMJa.VXVCz3DHb1DFm6uYx2hWrKTYQhQ0Zme1qfNFLMGwd5cDby', 'staff');

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
  `status` enum('draft','terkirim','diproses','selesai') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `pesanan_pembelian`
--

INSERT INTO `pesanan_pembelian` (`id_pesanan`, `id_pengirim`, `no_pesanan`, `tanggal`, `total`, `catatan`, `id_penerima`, `up`, `diskon`, `id_ppn`, `logo`, `signature_info`, `kategori`, `status`) VALUES
('0abdf579-a5a6-41ae-8b5a-eebade7f39e2', '5c091a0a-9796-4d5d-b009-526830bd6386', 'te/wh2/ypmi/mpo/2024/03/38971', '2024-03-25 11:50:00', 657397500, '', '31c50f21-12b9-4826-9345-52c604ec4ee2', '', 0, 'e502e6a9-e463-4bb0-9a78-0e30dc229722', NULL, 'Location: ypmi, Date: 2024-03-25, Name: yamada yoshio, Position: gm purchasing, Path: ', 'masuk', 'diproses'),
('7fa75e3f-c92b-4e3b-b8e7-ff30848f4aca', '5c091a0a-9796-4d5d-b009-526830bd6386', 'te/wh2/ypmi/mpo/2024/04/39436', '2024-04-29 11:57:00', 714562500, '', '31c50f21-12b9-4826-9345-52c604ec4ee2', '', 0, 'e502e6a9-e463-4bb0-9a78-0e30dc229722', NULL, 'Location: ypmi, Date: 2024-04-29, Name: yamada yoshio, Position: gm purchasing, Path: ', 'masuk', 'diproses'),
('afd4bc07-ea98-41c2-853c-bac2bc5207af', '5c091a0a-9796-4d5d-b009-526830bd6386', 'te/wh2/ypmi/mpo/2024/04/39437', '2024-04-29 11:53:00', 3663000, '', '31c50f21-12b9-4826-9345-52c604ec4ee2', '', 0, 'e502e6a9-e463-4bb0-9a78-0e30dc229722', NULL, 'Location: ypmi, Date: 2024-04-29, Name: yamada yoshio, Position: gm purchasing, Path: ', 'masuk', 'diproses'),
('db1f18d1-d6ca-4fe3-9fd8-fd6af3e0511a', '5c091a0a-9796-4d5d-b009-526830bd6386', 'te/wh2/ypmi/mpo/2024/03/38970', '2024-03-25 11:47:00', 2442000, '', '31c50f21-12b9-4826-9345-52c604ec4ee2', '', 0, 'e502e6a9-e463-4bb0-9a78-0e30dc229722', NULL, 'Location: ypmi, Date: 2024-03-25, Name: yamada yoshio, Position: gm purchasing, Path: ', 'masuk', 'diproses');

-- --------------------------------------------------------

--
-- Struktur dari tabel `ppn`
--

CREATE TABLE `ppn` (
  `id_ppn` varchar(36) NOT NULL,
  `jenis_ppn` varchar(100) NOT NULL,
  `tarif` int NOT NULL,
  `keterangan` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `ppn`
--

INSERT INTO `ppn` (`id_ppn`, `jenis_ppn`, `tarif`, `keterangan`) VALUES
('3395fb1f-a551-4bfd-a9ff-8ce407a108bc', 'tanpa ppn', 0, 'pilih jenis ppn &quot;tanpa ppn&quot; jika transaksi tanpa ppn.'),
('e502e6a9-e463-4bb0-9a78-0e30dc229722', 'ppn 11%', 11, 'tarif dalam persen');

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
  `keterangan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `produk`
--

INSERT INTO `produk` (`id_produk`, `no_produk`, `nama_produk`, `satuan`, `harga`, `status`, `keterangan`) VALUES
('2c3242ff-6b53-4ced-a89c-e08039ef6a77', 'S-2852', 'repair deburring + transportasi ypmi - mitra', 'pcs', 2575, 'disetujui', ''),
('51f151a4-ac95-4782-818f-b56d9a780123', 'S-6309', 'proses forming wiremesh', 'pcs', 110, 'disetujui', ''),
('7e360f04-140f-407a-a314-9af07bfdc778', '4340-R/SNCM439-R', '4340-r/sncm439-r dia 28 x 1000 mm', 'pcs', 232750, 'disetujui', ''),
('88dd7064-ccd4-4f7f-9c3d-7b1722066d4f', 'S-10445', 'proses deburring + transportasi part head cylinder', 'pcs', 2575, 'disetujui', 'jasa untuk ypmi'),
('981cc618-9587-45e1-b10b-b702604a50d7', 'FAB-L-COM1-lith-0013', 'roller pressing', 'pcs', 240350, 'disetujui', ''),
('a4dfdb31-8293-4658-af4b-29e5b329ffc7', 'FAB-L-COM1-LITH-0016', 'tip pressing cr2032l', 'pcs', 85100, 'disetujui', ''),
('ab312b6c-516e-492e-93fc-da4d59e643e8', 'FAB-L-COM1-LITH-0018', 'tip pressing cr2450', 'pcs', 85100, 'disetujui', ''),
('b944165a-f5c3-4916-8aed-eee1ff3dbc01', 'FAB-L-COM1-LITH-0024', 'cutter lithium pressing', 'pcs', 115000, 'disetujui', ''),
('c9473cc4-a0dc-4c55-a783-8a2a9616e7fd', 'fab-l-com1-tamp-0042', 'chopper blade', 'set', 2700200, 'disetujui', ''),
('e1c0f235-f098-4043-bcdb-16b298c2383a', 'fa10031902300', 'h/c heli stacker full electric', 'unit', 3600000, 'disetujui', '');

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
