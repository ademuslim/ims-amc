-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 24 Jun 2024 pada 16.32
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
  `id_pesanan` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `signature_confirm` longtext
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `detail_faktur`
--

INSERT INTO `detail_faktur` (`id_detail_faktur`, `id_faktur`, `no_pengiriman_barang`, `id_produk`, `jumlah`, `harga_satuan`, `id_pesanan`, `signature_confirm`) VALUES
('18bb10c0-b448-4ebd-b102-01738fc4c8fa', '9522fe97-ef99-4899-bf95-234ff7e075ab', '003/spb/mtg/05/2024', '2c3242ff-6b53-4ced-a89c-e08039ef6a77', 81, 2575, '426403bf-01af-47d2-be69-b2a2342bfbf9', NULL),
('3def370a-df66-4d62-98db-22ec087452a7', 'a45d4f31-4070-453e-8b86-b155b63e7556', '001/spb/mtg/03/2024', '88dd7064-ccd4-4f7f-9c3d-7b1722066d4f', 161983, 2575, '426403bf-01af-47d2-be69-b2a2342bfbf9', NULL),
('5ad69e3a-dc46-4eaf-81f7-ddf295a27d04', 'b5436367-2055-4432-ac67-7395d50509f6', '002/spb/mtg/04/2024', '2c3242ff-6b53-4ced-a89c-e08039ef6a77', 18358, 2575, '6115b451-d038-4fdd-bc52-98ecbe5677b9', NULL),
('5c8a9c8c-7b4d-410a-8612-ca4b50e8e288', 'b5436367-2055-4432-ac67-7395d50509f6', '001/spb/mtg/04/2024', '88dd7064-ccd4-4f7f-9c3d-7b1722066d4f', 100754, 2575, '6115b451-d038-4fdd-bc52-98ecbe5677b9', NULL),
('743db926-27f4-4d0c-802f-e794aaa4c52e', '9522fe97-ef99-4899-bf95-234ff7e075ab', '001/spb/mtg/05/2024', '88dd7064-ccd4-4f7f-9c3d-7b1722066d4f', 38017, 2575, '426403bf-01af-47d2-be69-b2a2342bfbf9', NULL),
('757dc3f8-bbf3-46ff-9301-f18d8bf5c3bf', 'a45d4f31-4070-453e-8b86-b155b63e7556', '003/spb/mtg/03/2024', '51f151a4-ac95-4782-818f-b56d9a780123', 3500, 110, 'b1e34522-b731-4de1-b55c-659c3f3b272a', NULL),
('776bb195-5557-4691-8252-c877e0c5479b', 'b5436367-2055-4432-ac67-7395d50509f6', '003/spb/mtg/04/2024', '51f151a4-ac95-4782-818f-b56d9a780123', 3000, 110, '530b9a70-ef09-4aab-a1bc-3b9e52c9619a', NULL),
('97fac98e-97f3-45b3-96a0-e0f8c8fc83e8', '9522fe97-ef99-4899-bf95-234ff7e075ab', '002/spb/mtg/05/2024', '88dd7064-ccd4-4f7f-9c3d-7b1722066d4f', 96946, 2575, '6115b451-d038-4fdd-bc52-98ecbe5677b9', NULL),
('a60eaa5e-fae4-4641-8e6c-08f9ec84eb9b', '2cccce06-2c27-454c-bf66-cb177d47110b', '001/spb/mtg/05/2024', '981cc618-9587-45e1-b10b-b702604a50d7', 32, 240350, 'a1d6a969-7771-4bd8-af9c-18f238ea3741', NULL),
('a9719dff-8ea9-4ea8-bb1c-9809de3ac7b8', '2cccce06-2c27-454c-bf66-cb177d47110b', '002/spb/mtg/05/2024', 'a4dfdb31-8293-4658-af4b-29e5b329ffc7', 32, 85100, 'a1d6a969-7771-4bd8-af9c-18f238ea3741', NULL),
('bf935fb2-3660-4e2f-8058-d7af917160a9', '9522fe97-ef99-4899-bf95-234ff7e075ab', '004/spb/mtg/05/2024', '2c3242ff-6b53-4ced-a89c-e08039ef6a77', 31640, 2575, '6115b451-d038-4fdd-bc52-98ecbe5677b9', NULL),
('c3ce5bd3-d5ab-4c4d-94aa-03e75bb7b3dd', '8e15b457-2347-45d9-b7a1-9841e6d4e264', '001/spb/mtg/05/2024', 'c9473cc4-a0dc-4c55-a783-8a2a9616e7fd', 7, 2700200, 'b1907071-51ab-4e99-bbe1-85047b1d4c2c', NULL),
('f14c7200-5b52-4090-8dd6-0e27989d5cfc', '2cccce06-2c27-454c-bf66-cb177d47110b', '003/spb/mtg/05/2024', 'ab312b6c-516e-492e-93fc-da4d59e643e8', 6, 85100, 'a1d6a969-7771-4bd8-af9c-18f238ea3741', NULL),
('f2f559d3-2c34-4b0b-98d6-5743536d8974', 'a45d4f31-4070-453e-8b86-b155b63e7556', '002/spb/mtg/03/2024', '2c3242ff-6b53-4ced-a89c-e08039ef6a77', 29919, 2575, '426403bf-01af-47d2-be69-b2a2342bfbf9', 'Approved Name: shinta, Checked Name: supriyadi, Issued Name: ade muslim');

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
('0146ef32-23ae-4174-b53a-9f4af06ee00e', '1b32fe50-f2bf-413a-91a7-662cd0b5a89f', 'b944165a-f5c3-4916-8aed-eee1ff3dbc01', 1, 115000),
('332467a4-87f5-498f-b446-7a821a69a480', '4db33884-68f2-4406-aefd-194fb825b94d', '88dd7064-ccd4-4f7f-9c3d-7b1722066d4f', 1, 2575),
('7123d5f1-c577-405b-bb12-e9c5a9fc4bd8', 'a7d42051-a9ad-4c53-82f8-f101abd6ea2a', '981cc618-9587-45e1-b10b-b702604a50d7', 1, 240350),
('73cd5aef-89e3-4279-a813-090ff9c921fb', '4c7c1250-49a7-46a8-8e9b-45bc70ef10e8', 'a4dfdb31-8293-4658-af4b-29e5b329ffc7', 1, 85100),
('87c82198-8b41-4384-b7cb-39195503ca62', 'fed3688d-4d34-4b79-b317-7781b393551c', 'ab312b6c-516e-492e-93fc-da4d59e643e8', 1, 85100),
('8983f913-8c13-4528-9643-00942d86a65d', '94d5cd5d-9019-4cb8-b688-107a41d2f826', '2c3242ff-6b53-4ced-a89c-e08039ef6a77', 1, 2575),
('a6fef581-66e7-4386-8401-62b4e2368b06', '48b76561-90b5-4597-801c-f57ccc4c23ce', 'c9473cc4-a0dc-4c55-a783-8a2a9616e7fd', 1, 2700200),
('c2258d5b-6cb7-4f9b-9c9f-76536ffc8020', '08c31f22-b80e-478f-a84f-2c1df3918887', '51f151a4-ac95-4782-818f-b56d9a780123', 1, 110);

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
('2d3dcde9-1641-499e-be0d-f3efb59e77c7', 'a1d6a969-7771-4bd8-af9c-18f238ea3741', '981cc618-9587-45e1-b10b-b702604a50d7', 32, 32, 0, 240350, 'a7d42051-a9ad-4c53-82f8-f101abd6ea2a'),
('30b2b1e9-4018-4b97-8298-00ca0f5ebf58', '6115b451-d038-4fdd-bc52-98ecbe5677b9', '2c3242ff-6b53-4ced-a89c-e08039ef6a77', 50000, 49998, 2, 2575, '94d5cd5d-9019-4cb8-b688-107a41d2f826'),
('357dea73-118e-4530-9c44-30d46b83fa63', 'a1d6a969-7771-4bd8-af9c-18f238ea3741', 'ab312b6c-516e-492e-93fc-da4d59e643e8', 6, 6, 0, 85100, 'fed3688d-4d34-4b79-b317-7781b393551c'),
('a0cec0cc-b0d9-4859-92a9-5936e44560eb', '6115b451-d038-4fdd-bc52-98ecbe5677b9', '88dd7064-ccd4-4f7f-9c3d-7b1722066d4f', 200000, 197700, 2300, 2575, '4db33884-68f2-4406-aefd-194fb825b94d'),
('a4d70274-6ee9-4597-be8d-b3fe826cd15f', '426403bf-01af-47d2-be69-b2a2342bfbf9', '2c3242ff-6b53-4ced-a89c-e08039ef6a77', 30000, 30000, 0, 2575, '94d5cd5d-9019-4cb8-b688-107a41d2f826'),
('b983404f-c743-4eea-826e-b1868243805a', 'b1907071-51ab-4e99-bbe1-85047b1d4c2c', 'c9473cc4-a0dc-4c55-a783-8a2a9616e7fd', 7, 7, 0, 2700200, '48b76561-90b5-4597-801c-f57ccc4c23ce'),
('cb6a53a0-a326-43d7-903d-e13a3f383f56', '530b9a70-ef09-4aab-a1bc-3b9e52c9619a', '51f151a4-ac95-4782-818f-b56d9a780123', 30000, 3000, 27000, 110, '08c31f22-b80e-478f-a84f-2c1df3918887'),
('d22b6fd2-04a0-4298-b071-07249eb147dd', 'b1e34522-b731-4de1-b55c-659c3f3b272a', '51f151a4-ac95-4782-818f-b56d9a780123', 20000, 3500, 16500, 110, '08c31f22-b80e-478f-a84f-2c1df3918887'),
('f38280a4-d9c8-44f7-a667-0572f0f034ba', 'a1d6a969-7771-4bd8-af9c-18f238ea3741', 'a4dfdb31-8293-4658-af4b-29e5b329ffc7', 32, 32, 0, 85100, '4c7c1250-49a7-46a8-8e9b-45bc70ef10e8'),
('ffa273d0-89ba-4eff-bf53-8aa212204360', '426403bf-01af-47d2-be69-b2a2342bfbf9', '88dd7064-ccd4-4f7f-9c3d-7b1722066d4f', 200000, 200000, 0, 2575, '4db33884-68f2-4406-aefd-194fb825b94d');

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
  `id_ppn` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
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
('2cccce06-2c27-454c-bf66-cb177d47110b', '31c50f21-12b9-4826-9345-52c604ec4ee2', '012/inv/mtg/05/2024', '2024-05-14 00:00:00', 10925000, '', 'bc24623f-d5ed-44e5-b13a-fdf04fbdf61c', 0, '3395fb1f-a551-4bfd-a9ff-8ce407a108bc', '../../assets/image/uploads/logo/66782f4ae81fa_20240623.jpeg', 'Location: cikarang, Date: 2024-05-14, Name: supriyadi, Position: direktur, Path: ../../assets/image/uploads/signature/66782f109b2cb_20240623.png', 'keluar', 'dibayar', 0),
('8e15b457-2347-45d9-b7a1-9841e6d4e264', '31c50f21-12b9-4826-9345-52c604ec4ee2', '011/inv/mtg/05/2024', '2024-05-03 00:00:00', 18901400, '', 'bc24623f-d5ed-44e5-b13a-fdf04fbdf61c', 0, '3395fb1f-a551-4bfd-a9ff-8ce407a108bc', '../../assets/image/uploads/logo/66782f4ae81fa_20240623.jpeg', 'Location: cikarang, Date: 2024-05-03, Name: supriyadi, Position: direktur, Path: ../../assets/image/uploads/signature/66782f109b2cb_20240623.png', 'keluar', 'dibayar', 0),
('9522fe97-ef99-4899-bf95-234ff7e075ab', '31c50f21-12b9-4826-9345-52c604ec4ee2', '013/inv/mtg/05/2024', '2024-05-31 00:00:00', 476424543, '', '5c091a0a-9796-4d5d-b009-526830bd6386', 0, 'e502e6a9-e463-4bb0-9a78-0e30dc229722', '../../assets/image/uploads/logo/66782f4ae81fa_20240623.jpeg', 'Location: cikarang pusat, Date: 2024-05-31, Name: supriyadi, Position: direktur, Path: ../../assets/image/uploads/signature/66782f109b2cb_20240623.png', 'keluar', 'tunggu kirim', 0),
('a45d4f31-4070-453e-8b86-b155b63e7556', '31c50f21-12b9-4826-9345-52c604ec4ee2', '009/inv/mtg/03/2024', '2024-03-30 00:00:00', 548931242, 'lima ratus empat puluh delapan juta sembilan ratus tiga puluh satu ribu dua ratus empat puluh dua rupiah', '5c091a0a-9796-4d5d-b009-526830bd6386', 0, 'e502e6a9-e463-4bb0-9a78-0e30dc229722', '../../assets/image/uploads/logo/66782f4ae81fa_20240623.jpeg', 'Location: cikarang, Date: 2024-03-30, Name: supriyadi, Position: direktur, Path: ../../assets/image/uploads/signature/66782f109b2cb_20240623.png', 'keluar', 'dibayar', 0),
('b5436367-2055-4432-ac67-7395d50509f6', '31c50f21-12b9-4826-9345-52c604ec4ee2', '010/inv/mtg/04/2024', '2024-04-30 00:00:00', 340818174, '', '5c091a0a-9796-4d5d-b009-526830bd6386', 0, 'e502e6a9-e463-4bb0-9a78-0e30dc229722', '../../assets/image/uploads/logo/66782f4ae81fa_20240623.jpeg', 'Location: cikarang, Date: 2024-04-30, Name: supriyadi, Position: direktur, Path: ../../assets/image/uploads/signature/66782f109b2cb_20240623.png', 'keluar', 'dibayar', 0);

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
('31c50f21-12b9-4826-9345-52c604ec4ee2', 'pt. mitra tehno gemilang', 'internal', 'office:  jl. rawa banteng rt.01 rw.02 kel. jaya mukti kec. cikarang pusat - bekasi', '02129481360', 'mitra_tehnogemilang@yahoo.co.id', 'kontak utama perusahaan.', 0),
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
(271, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil membuat PH', 'Penawaran Harga', 'Berhasil membuat penawaran harga baru dengan ID 08c31f22-b80e-478f-a84f-2c1df3918887', '2024-06-23 14:20:00', 0),
(272, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil ubah PH', 'Penawaran Harga', 'Perubahan data penawaran harga dengan ID 08c31f22-b80e-478f-a84f-2c1df3918887: | 1. logo: &quot;&quot; diubah menjadi &quot;../../assets/image/uploads/logo/66782f4ae81fa_20240623.jpeg&quot; | ', '2024-06-23 14:20:58', 0),
(273, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil ubah PH', 'Penawaran Harga', 'Perubahan data penawaran harga dengan ID 08c31f22-b80e-478f-a84f-2c1df3918887: | 1. no_penawaran: &quot;001/ph/mtg/10/2018&quot; diubah menjadi &quot;002/ph/mtg/10/2018&quot; | ', '2024-06-23 14:21:22', 0),
(274, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil ubah status PH', 'Penawaran Harga', 'Perubahan status penawaran harga dengan ID 08c31f22-b80e-478f-a84f-2c1df3918887: | . status: &quot;draft&quot; diubah menjadi &quot;disetujui&quot; | ', '2024-06-23 14:23:23', 0),
(275, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil membuat PH', 'Penawaran Harga', 'Berhasil membuat penawaran harga baru dengan ID 4db33884-68f2-4406-aefd-194fb825b94d', '2024-06-23 14:26:29', 0),
(276, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil ubah PH', 'Penawaran Harga', 'Perubahan data penawaran harga dengan ID 4db33884-68f2-4406-aefd-194fb825b94d: | 1. no_penawaran: &quot;001/ph/mtg/12/2023&quot; diubah menjadi &quot;018/ph/mtg/12/2023&quot; | 2. status: &quot;draft&quot; diubah menjadi &quot;disetujui&quot; | ', '2024-06-23 14:26:54', 0),
(277, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil membuat PH', 'Penawaran Harga', 'Berhasil membuat penawaran harga baru dengan ID 94d5cd5d-9019-4cb8-b688-107a41d2f826', '2024-06-23 14:28:37', 0),
(278, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil ubah PH', 'Penawaran Harga', 'Perubahan data penawaran harga dengan ID 94d5cd5d-9019-4cb8-b688-107a41d2f826: | 1. status: &quot;draft&quot; diubah menjadi &quot;disetujui&quot; | ', '2024-06-23 14:28:52', 0),
(279, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil membuat PO', 'Pesanan Pembelian', 'Berhasil membuat pesanan pembelian baru dengan ID b1e34522-b731-4de1-b55c-659c3f3b272a', '2024-06-23 14:31:18', 0),
(280, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil ubah status PO', 'Pesanan Pembelian', 'Perubahan status pesanan pembelian dengan ID b1e34522-b731-4de1-b55c-659c3f3b272a: &quot;status&quot; diubah dari &quot;draft&quot; menjadi &quot;diproses&quot;.', '2024-06-23 14:32:29', 0),
(281, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil membuat PO', 'Pesanan Pembelian', 'Berhasil membuat pesanan pembelian baru dengan ID 426403bf-01af-47d2-be69-b2a2342bfbf9', '2024-06-23 14:34:28', 0),
(282, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil ubah status PO', 'Pesanan Pembelian', 'Perubahan status pesanan pembelian dengan ID 426403bf-01af-47d2-be69-b2a2342bfbf9: &quot;status&quot; diubah dari &quot;draft&quot; menjadi &quot;diproses&quot;.', '2024-06-23 14:34:40', 0),
(283, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil membuat invoice baru', 'faktur', 'Pengguna dengan ID 9167ed40-435e-4e18-a4d0-59676a89c511 berhasil membuat invoice baru dengan ID a45d4f31-4070-453e-8b86-b155b63e7556', '2024-06-23 14:40:28', 0),
(284, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil ubah status invoice', 'Faktur', 'Perubahan status faktur dengan ID a45d4f31-4070-453e-8b86-b155b63e7556: &quot;status&quot; diubah dari &quot;tunggu kirim&quot; menjadi &quot;dibayar&quot;.', '2024-06-23 14:40:49', 0),
(285, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil memperbarui konfirmasi delivery order.', 'Faktur', 'Perubahan konfirmasi dokumen detail faktur dengan ID f2f559d3-2c34-4b0b-98d6-5743536d8974: &quot;konfirmasi&quot; diubah dari &quot;&quot; menjadi &quot;Approved Name: shinta, Checked Name: supriyadi, Issued Name: ade muslim&quot;.', '2024-06-24 07:05:29', 0),
(286, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil membuat PO', 'Pesanan Pembelian', 'Berhasil membuat pesanan pembelian baru dengan ID 6115b451-d038-4fdd-bc52-98ecbe5677b9', '2024-06-24 08:55:14', 0),
(287, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil membuat PO', 'Pesanan Pembelian', 'Berhasil membuat pesanan pembelian baru dengan ID 530b9a70-ef09-4aab-a1bc-3b9e52c9619a', '2024-06-24 08:57:40', 0),
(288, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil ubah status PO', 'Pesanan Pembelian', 'Perubahan status pesanan pembelian dengan ID 530b9a70-ef09-4aab-a1bc-3b9e52c9619a: &quot;status&quot; diubah dari &quot;draft&quot; menjadi &quot;diproses&quot;.', '2024-06-24 08:58:10', 0),
(289, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil ubah status PO', 'Pesanan Pembelian', 'Perubahan status pesanan pembelian dengan ID 6115b451-d038-4fdd-bc52-98ecbe5677b9: &quot;status&quot; diubah dari &quot;draft&quot; menjadi &quot;diproses&quot;.', '2024-06-24 08:58:20', 0),
(290, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil membuat invoice baru', 'faktur', 'Pengguna dengan ID 9167ed40-435e-4e18-a4d0-59676a89c511 berhasil membuat invoice baru dengan ID b5436367-2055-4432-ac67-7395d50509f6', '2024-06-24 09:10:02', 0),
(291, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil ubah status invoice', 'Faktur', 'Perubahan status faktur dengan ID b5436367-2055-4432-ac67-7395d50509f6: &quot;status&quot; diubah dari &quot;tunggu kirim&quot; menjadi &quot;dibayar&quot;.', '2024-06-24 09:10:34', 0),
(292, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil membuat PH', 'Penawaran Harga', 'Berhasil membuat penawaran harga baru dengan ID 48b76561-90b5-4597-801c-f57ccc4c23ce', '2024-06-24 09:31:54', 0),
(293, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil membuat PH', 'Penawaran Harga', 'Berhasil membuat penawaran harga baru dengan ID a7d42051-a9ad-4c53-82f8-f101abd6ea2a', '2024-06-24 09:34:20', 0),
(294, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil ubah PH', 'Penawaran Harga', 'Perubahan data penawaran harga dengan ID a7d42051-a9ad-4c53-82f8-f101abd6ea2a: | 1. total: &quot;266789&quot; diubah menjadi &quot;240350&quot; | 2. id_ppn: &quot;e502e6a9-e463-4bb0-9a78-0e30dc229722&quot; diubah menjadi &quot;3395fb1f-a551-4bfd-a9ff-8ce407a108bc&quot; | ', '2024-06-24 09:35:00', 0),
(295, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil membuat PH', 'Penawaran Harga', 'Berhasil membuat penawaran harga baru dengan ID 4c7c1250-49a7-46a8-8e9b-45bc70ef10e8', '2024-06-24 09:36:36', 0),
(296, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil membuat PH', 'Penawaran Harga', 'Berhasil membuat penawaran harga baru dengan ID fed3688d-4d34-4b79-b317-7781b393551c', '2024-06-24 09:38:43', 0),
(297, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil membuat PH', 'Penawaran Harga', 'Berhasil membuat penawaran harga baru dengan ID 1b32fe50-f2bf-413a-91a7-662cd0b5a89f', '2024-06-24 09:41:15', 0),
(298, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil ubah status PH', 'Penawaran Harga', 'Perubahan status penawaran harga dengan ID fed3688d-4d34-4b79-b317-7781b393551c: | . status: &quot;draft&quot; diubah menjadi &quot;disetujui&quot; | ', '2024-06-24 09:43:08', 0),
(299, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil ubah status PH', 'Penawaran Harga', 'Perubahan status penawaran harga dengan ID a7d42051-a9ad-4c53-82f8-f101abd6ea2a: | . status: &quot;draft&quot; diubah menjadi &quot;disetujui&quot; | ', '2024-06-24 09:43:17', 0),
(300, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil ubah status PH', 'Penawaran Harga', 'Perubahan status penawaran harga dengan ID 4c7c1250-49a7-46a8-8e9b-45bc70ef10e8: | . status: &quot;draft&quot; diubah menjadi &quot;disetujui&quot; | ', '2024-06-24 09:43:24', 0),
(301, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil ubah status PH', 'Penawaran Harga', 'Perubahan status penawaran harga dengan ID 48b76561-90b5-4597-801c-f57ccc4c23ce: | . status: &quot;draft&quot; diubah menjadi &quot;disetujui&quot; | ', '2024-06-24 09:43:30', 0),
(302, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil ubah status PH', 'Penawaran Harga', 'Perubahan status penawaran harga dengan ID 1b32fe50-f2bf-413a-91a7-662cd0b5a89f: | . status: &quot;draft&quot; diubah menjadi &quot;disetujui&quot; | ', '2024-06-24 09:43:36', 0),
(303, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil membuat PO', 'Pesanan Pembelian', 'Berhasil membuat pesanan pembelian baru dengan ID b1907071-51ab-4e99-bbe1-85047b1d4c2c', '2024-06-24 09:52:00', 0),
(304, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil ubah status PO', 'Pesanan Pembelian', 'Perubahan status pesanan pembelian dengan ID b1907071-51ab-4e99-bbe1-85047b1d4c2c: &quot;status&quot; diubah dari &quot;draft&quot; menjadi &quot;selesai&quot;.', '2024-06-24 09:53:52', 0),
(305, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil ubah status PO', 'Pesanan Pembelian', 'Perubahan status pesanan pembelian dengan ID b1907071-51ab-4e99-bbe1-85047b1d4c2c: &quot;status&quot; diubah dari &quot;selesai&quot; menjadi &quot;diproses&quot;.', '2024-06-24 09:55:13', 0),
(306, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil membuat invoice baru', 'faktur', 'Pengguna dengan ID 9167ed40-435e-4e18-a4d0-59676a89c511 berhasil membuat invoice baru dengan ID 8e15b457-2347-45d9-b7a1-9841e6d4e264', '2024-06-24 09:57:27', 0),
(307, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil ubah status invoice', 'Faktur', 'Perubahan status faktur dengan ID 8e15b457-2347-45d9-b7a1-9841e6d4e264: &quot;status&quot; diubah dari &quot;tunggu kirim&quot; menjadi &quot;dibayar&quot;.', '2024-06-24 09:57:56', 0),
(308, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil membuat PO', 'Pesanan Pembelian', 'Berhasil membuat pesanan pembelian baru dengan ID a1d6a969-7771-4bd8-af9c-18f238ea3741', '2024-06-24 13:33:37', 0),
(309, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil ubah status PO', 'Pesanan Pembelian', 'Perubahan status pesanan pembelian dengan ID a1d6a969-7771-4bd8-af9c-18f238ea3741: &quot;status&quot; diubah dari &quot;draft&quot; menjadi &quot;diproses&quot;.', '2024-06-24 13:34:19', 0),
(310, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil membuat invoice baru', 'faktur', 'Pengguna dengan ID 9167ed40-435e-4e18-a4d0-59676a89c511 berhasil membuat invoice baru dengan ID 2cccce06-2c27-454c-bf66-cb177d47110b', '2024-06-24 13:40:08', 0),
(311, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil ubah status invoice', 'Faktur', 'Perubahan status faktur dengan ID 2cccce06-2c27-454c-bf66-cb177d47110b: &quot;status&quot; diubah dari &quot;tunggu kirim&quot; menjadi &quot;dibayar&quot;.', '2024-06-24 13:55:19', 0),
(312, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil membuat invoice baru', 'faktur', 'Pengguna dengan ID 9167ed40-435e-4e18-a4d0-59676a89c511 berhasil membuat invoice baru dengan ID 9522fe97-ef99-4899-bf95-234ff7e075ab', '2024-06-24 16:17:31', 0),
(313, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil ubah status PO', 'Pesanan Pembelian', 'Perubahan status pesanan pembelian dengan ID a1d6a969-7771-4bd8-af9c-18f238ea3741: &quot;status&quot; diubah dari &quot;diproses&quot; menjadi &quot;selesai&quot;.', '2024-06-24 16:27:24', 0),
(314, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil ubah status PO', 'Pesanan Pembelian', 'Perubahan status pesanan pembelian dengan ID b1907071-51ab-4e99-bbe1-85047b1d4c2c: &quot;status&quot; diubah dari &quot;diproses&quot; menjadi &quot;selesai&quot;.', '2024-06-24 16:27:51', 0),
(315, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil ubah status PO', 'Pesanan Pembelian', 'Perubahan status pesanan pembelian dengan ID 6115b451-d038-4fdd-bc52-98ecbe5677b9: &quot;status&quot; diubah dari &quot;diproses&quot; menjadi &quot;selesai&quot;.', '2024-06-24 16:27:59', 0),
(316, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil ubah status PO', 'Pesanan Pembelian', 'Perubahan status pesanan pembelian dengan ID 530b9a70-ef09-4aab-a1bc-3b9e52c9619a: &quot;status&quot; diubah dari &quot;diproses&quot; menjadi &quot;selesai&quot;.', '2024-06-24 16:28:07', 0),
(317, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil ubah status PO', 'Pesanan Pembelian', 'Perubahan status pesanan pembelian dengan ID 6115b451-d038-4fdd-bc52-98ecbe5677b9: &quot;status&quot; diubah dari &quot;selesai&quot; menjadi &quot;diproses&quot;.', '2024-06-24 16:28:27', 0),
(318, '9167ed40-435e-4e18-a4d0-59676a89c511', 'Berhasil ubah status PO', 'Pesanan Pembelian', 'Perubahan status pesanan pembelian dengan ID 530b9a70-ef09-4aab-a1bc-3b9e52c9619a: &quot;status&quot; diubah dari &quot;selesai&quot; menjadi &quot;diproses&quot;.', '2024-06-24 16:28:34', 0);

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
('08c31f22-b80e-478f-a84f-2c1df3918887', '31c50f21-12b9-4826-9345-52c604ec4ee2', '002/ph/mtg/10/2018', '2018-10-05 00:00:00', 110, 'harga belum termasuk ppn 11%, penawaran harga telah di approve kembali 05 maret 2024 (bu nurlaili rahma).', '5c091a0a-9796-4d5d-b009-526830bd6386', 'bpk. rudi rusminarno', '3395fb1f-a551-4bfd-a9ff-8ce407a108bc', 0, '../../assets/image/uploads/logo/66782f4ae81fa_20240623.jpeg', 'Location: cikarang pusat, Date: 2018-10-05, Name: supriyadi, Position: direktur, Path: ../../assets/image/uploads/signature/66782f109b2cb_20240623.png', 'keluar', 'disetujui', 0),
('1b32fe50-f2bf-413a-91a7-662cd0b5a89f', '31c50f21-12b9-4826-9345-52c604ec4ee2', '005/ph/mtg/01/2024', '2024-01-12 00:00:00', 115000, 'harga belum termasuk ppn 11%.', 'bc24623f-d5ed-44e5-b13a-fdf04fbdf61c', '', '3395fb1f-a551-4bfd-a9ff-8ce407a108bc', 0, '../../assets/image/uploads/logo/66782f4ae81fa_20240623.jpeg', 'Location: cikarang, Date: 2024-01-12, Name: supriyadi, Position: direktur, Path: ../../assets/image/uploads/signature/66782f109b2cb_20240623.png', 'keluar', 'disetujui', 0),
('48b76561-90b5-4597-801c-f57ccc4c23ce', '31c50f21-12b9-4826-9345-52c604ec4ee2', '001/ph/mtg/01/2024', '2024-01-12 00:00:00', 2700200, 'harga belum termasuk ppn 11%.', 'bc24623f-d5ed-44e5-b13a-fdf04fbdf61c', '', '3395fb1f-a551-4bfd-a9ff-8ce407a108bc', 0, '../../assets/image/uploads/logo/66782f4ae81fa_20240623.jpeg', 'Location: cikarang, Date: 2024-01-12, Name: supriyadi, Position: direktur, Path: ../../assets/image/uploads/signature/66782f109b2cb_20240623.png', 'keluar', 'disetujui', 0),
('4c7c1250-49a7-46a8-8e9b-45bc70ef10e8', '31c50f21-12b9-4826-9345-52c604ec4ee2', '003/ph/mtg/01/2024', '2024-01-12 00:00:00', 85100, 'harga belum termasuk ppn 11%.', 'bc24623f-d5ed-44e5-b13a-fdf04fbdf61c', '', '3395fb1f-a551-4bfd-a9ff-8ce407a108bc', 0, '../../assets/image/uploads/logo/66782f4ae81fa_20240623.jpeg', 'Location: cikarang, Date: 2024-01-12, Name: supriyadi, Position: direktur, Path: ../../assets/image/uploads/signature/66782f109b2cb_20240623.png', 'keluar', 'disetujui', 0),
('4db33884-68f2-4406-aefd-194fb825b94d', '31c50f21-12b9-4826-9345-52c604ec4ee2', '018/ph/mtg/12/2023', '2023-12-06 00:00:00', 2575, 'harga belum termasuk ppn 11%.', '5c091a0a-9796-4d5d-b009-526830bd6386', 'bpk. rudi rusminarno', '3395fb1f-a551-4bfd-a9ff-8ce407a108bc', 0, '../../assets/image/uploads/logo/66782f4ae81fa_20240623.jpeg', 'Location: cikarang, Date: 2023-12-06, Name: supriyadi, Position: direktur, Path: ../../assets/image/uploads/signature/66782f109b2cb_20240623.png', 'keluar', 'disetujui', 0),
('94d5cd5d-9019-4cb8-b688-107a41d2f826', '31c50f21-12b9-4826-9345-52c604ec4ee2', '019/ph/mtg/12/2023', '2023-12-06 00:00:00', 2575, 'harga belum termasuk ppn 11%.', '5c091a0a-9796-4d5d-b009-526830bd6386', 'bpk. rudi rusminarno', '3395fb1f-a551-4bfd-a9ff-8ce407a108bc', 0, '../../assets/image/uploads/logo/66782f4ae81fa_20240623.jpeg', 'Location: cikarang, Date: 2024-12-06, Name: supriyadi, Position: direktur, Path: ../../assets/image/uploads/signature/66782f109b2cb_20240623.png', 'keluar', 'disetujui', 0),
('a7d42051-a9ad-4c53-82f8-f101abd6ea2a', '31c50f21-12b9-4826-9345-52c604ec4ee2', '002/ph/mtg/01/2024', '2024-01-12 00:00:00', 240350, 'harga belum termasuk ppn 11%.', 'bc24623f-d5ed-44e5-b13a-fdf04fbdf61c', '', '3395fb1f-a551-4bfd-a9ff-8ce407a108bc', 0, '../../assets/image/uploads/logo/66782f4ae81fa_20240623.jpeg', 'Location: cikarang, Date: 2024-01-12, Name: supriyadi, Position: direktur, Path: ../../assets/image/uploads/signature/66782f109b2cb_20240623.png', 'keluar', 'disetujui', 0),
('fed3688d-4d34-4b79-b317-7781b393551c', '31c50f21-12b9-4826-9345-52c604ec4ee2', '004/ph/mtg/01/2024', '2024-01-12 00:00:00', 85100, 'harga belum termasuk ppn 11%.', 'bc24623f-d5ed-44e5-b13a-fdf04fbdf61c', '', '3395fb1f-a551-4bfd-a9ff-8ce407a108bc', 0, '../../assets/image/uploads/logo/66782f4ae81fa_20240623.jpeg', 'Location: cikarang, Date: 2024-01-12, Name: supriyadi, Position: direktur, Path: ../../assets/image/uploads/signature/66782f109b2cb_20240623.png', 'keluar', 'disetujui', 0);

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
('5f8e47d7-7fea-4a8d-8641-a071007f59da', 'dinda silvia', 'staff_1', '$2y$10$688ccUtC2sV68aLU1TYSyeYecVLjtZRtckDcztq4cFkg6.vvL3qI.', 'staff', 0),
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
('426403bf-01af-47d2-be69-b2a2342bfbf9', '5c091a0a-9796-4d5d-b009-526830bd6386', 'te/wh2/ypmi/mpo/2024/03/38971', '2024-03-25 00:00:00', 657397500, '', '31c50f21-12b9-4826-9345-52c604ec4ee2', '', 0, 'e502e6a9-e463-4bb0-9a78-0e30dc229722', '', 'Location: ypmi kiic, Date: 2024-03-25, Name: yamada yoshio, Position: gm purchasing, Path: ', 'masuk', 'selesai', 0),
('530b9a70-ef09-4aab-a1bc-3b9e52c9619a', '5c091a0a-9796-4d5d-b009-526830bd6386', 'te/wh2/ypmi/mpo/2024/04/39437', '2024-04-29 00:00:00', 3663000, '', '31c50f21-12b9-4826-9345-52c604ec4ee2', '', 0, 'e502e6a9-e463-4bb0-9a78-0e30dc229722', '', 'Location: ypmi, Date: 2024-04-29, Name: yamada yoshio, Position: gm purchasing, Path: ', 'masuk', 'diproses', 0),
('6115b451-d038-4fdd-bc52-98ecbe5677b9', '5c091a0a-9796-4d5d-b009-526830bd6386', 'te/wh2/ypmi/mpo/2024/04/39436', '2024-04-29 00:00:00', 714562500, '', '31c50f21-12b9-4826-9345-52c604ec4ee2', '', 0, 'e502e6a9-e463-4bb0-9a78-0e30dc229722', '', 'Location: ypmi, Date: 2024-04-29, Name: yamada yoshio, Position: gm purchasing, Path: ', 'masuk', 'diproses', 0),
('a1d6a969-7771-4bd8-af9c-18f238ea3741', 'bc24623f-d5ed-44e5-b13a-fdf04fbdf61c', 'pslbd_24040164', '2024-04-22 00:00:00', 10925000, 'estimasi delivery 10 mei 2024', '31c50f21-12b9-4826-9345-52c604ec4ee2', '', 0, '3395fb1f-a551-4bfd-a9ff-8ce407a108bc', '', 'Location: pecgi, Date: 2024-04-22, Name: susan viniayu, Position: -, Path: ', 'masuk', 'selesai', 0),
('b1907071-51ab-4e99-bbe1-85047b1d4c2c', 'bc24623f-d5ed-44e5-b13a-fdf04fbdf61c', 'pslbd__24040191', '2024-04-25 00:00:00', 18901400, 'estimasi delivery 10-05-2024', '31c50f21-12b9-4826-9345-52c604ec4ee2', '', 0, '3395fb1f-a551-4bfd-a9ff-8ce407a108bc', '', 'Location: pecgi, Date: 2024-04-25, Name: susan viniayu, Position: -, Path: ', 'masuk', 'selesai', 0),
('b1e34522-b731-4de1-b55c-659c3f3b272a', '5c091a0a-9796-4d5d-b009-526830bd6386', 'te/wh2/ypmi/mpo/2024/03/38970', '2024-03-25 00:00:00', 2442000, '', '31c50f21-12b9-4826-9345-52c604ec4ee2', '', 0, 'e502e6a9-e463-4bb0-9a78-0e30dc229722', '', 'Location: ypmi kiic, Date: 2024-03-25, Name: yamada yoshio, Position: gm purchasing, Path: ', 'masuk', 'diproses', 0);

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
  MODIFY `id_log` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=319;

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
