-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 19 Bulan Mei 2024 pada 15.57
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
  `id_faktur` int DEFAULT NULL,
  `id_produk` varchar(36) NOT NULL,
  `jumlah` int NOT NULL,
  `harga_satuan` int NOT NULL
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
('49b3d5db-6699-4034-823e-348695351671', '6e8da39a-7820-4c10-b80f-a3061793acf5', 'e84eb7fc-029a-4c40-b3d0-a66d166bdb7b', 1, 10000),
('ec691f40-b2fe-42dc-8332-ad057e3fa886', '6e8da39a-7820-4c10-b80f-a3061793acf5', 'ede04344-c744-4428-a6a4-2b214b512e3c', 1, 10000);

-- --------------------------------------------------------

--
-- Struktur dari tabel `detail_pesanan`
--

CREATE TABLE `detail_pesanan` (
  `id_detail_pesanan` varchar(36) NOT NULL,
  `id_pesanan` varchar(36) DEFAULT NULL,
  `id_produk` varchar(36) NOT NULL,
  `jumlah` int NOT NULL,
  `harga_satuan` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `detail_pesanan`
--

INSERT INTO `detail_pesanan` (`id_detail_pesanan`, `id_pesanan`, `id_produk`, `jumlah`, `harga_satuan`) VALUES
('219b5014-1b02-4999-931c-5a5d11b7edf3', 'b5c89a75-74f7-4abb-9316-c9b104b423ce', 'e84eb7fc-029a-4c40-b3d0-a66d166bdb7b', 9, 80);

-- --------------------------------------------------------

--
-- Struktur dari tabel `faktur`
--

CREATE TABLE `faktur` (
  `id_faktur` varchar(36) NOT NULL,
  `id_pengirim` varchar(36) NOT NULL,
  `no_faktur` varchar(50) NOT NULL,
  `tanggal` date NOT NULL,
  `total` int NOT NULL,
  `catatan` text,
  `id_penerima` varchar(36) NOT NULL,
  `up` varchar(100) DEFAULT NULL,
  `diskon` int DEFAULT NULL,
  `id_ppn` varchar(36) DEFAULT NULL,
  `logo` varchar(100) DEFAULT NULL,
  `signature_info` longtext
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `kontak_internal`
--

CREATE TABLE `kontak_internal` (
  `id_pengirim` varchar(36) NOT NULL,
  `nama_pengirim` varchar(100) NOT NULL,
  `alamat_pengirim` varchar(255) NOT NULL,
  `telepon` varchar(20) NOT NULL,
  `email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `kontak_internal`
--

INSERT INTO `kontak_internal` (`id_pengirim`, `nama_pengirim`, `alamat_pengirim`, `telepon`, `email`) VALUES
('24dbcda4-01a9-4285-824c-6ca04d8f063d', 'ade muslim', 'jl. moch torpi kel. jaya mukti kec. cikarang pusat kab. bekasi prov. jawa barat', '085770772712', 'amuslim@mail.com'),
('df38f68e-ad09-4bc2-ac56-19b36dcb69cc', 'pt. mitra tehno gemilang', 'jl. rawa banteng', '123456789012', 'mitra_tehnogemilang@yahoo.co.id');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pelanggan`
--

CREATE TABLE `pelanggan` (
  `id_pelanggan` varchar(36) NOT NULL,
  `nama_pelanggan` varchar(100) NOT NULL,
  `alamat` varchar(255) NOT NULL,
  `telepon` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `keterangan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `pelanggan`
--

INSERT INTO `pelanggan` (`id_pelanggan`, `nama_pelanggan`, `alamat`, `telepon`, `email`, `keterangan`) VALUES
('593fce6e-77d2-4a4d-a6ce-5a52692e2998', 'pt. panasonic gobel energy indonesia ghfhfhgf', 'jl teuku', '1234567890112', '', ''),
('b6eb5463-43c5-4309-9722-76de47d01844', 'pt. yamaha motor part manufacturing indonesia', 'kiic karawang', '123456789012', 'ypmi@ypmi.com', '');

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
  `signature_info` longtext
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `penawaran_harga`
--

INSERT INTO `penawaran_harga` (`id_penawaran`, `id_pengirim`, `no_penawaran`, `tanggal`, `total`, `catatan`, `id_penerima`, `up`, `id_ppn`, `diskon`, `logo`, `signature_info`) VALUES
('6e8da39a-7820-4c10-b80f-a3061793acf5', 'df38f68e-ad09-4bc2-ac56-19b36dcb69cc', '001/ph/mtg/05/2024', '2024-05-19 21:38:00', 21090, 'tambah dua data detail baru. tambah produk 3, hapus produk 2, ubah produk 1, ubah diskon menjadi 5%, dan ppn11%. ubah ttd.', 'b6eb5463-43c5-4309-9722-76de47d01844', 'rudi rusminarno', '3b7d0596-b043-44e1-bd02-37731c66764f', 5, '../../assets/image/uploads/logo/664a0f4453945_20240519.jpeg', 'Location: Bekasi, Date: 2024-05-19, Name: Supriyadi Subiyanto, Position: Direktur, Path: ../../assets/image/uploads/signature/664a10e541778_20240519.png');

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
('8b03b2e8-84f6-4b53-b583-897d6afb83a7', 'mimin abdullah', 'mimin@mtg.com', '$2y$10$YtqVHX0MXTMcD5el/DfOTer1efONVWbQQ6U3lsQ46Ty.Q3IbB5QTq', 'staff'),
('9167ed40-435e-4e18-a4d0-59676a89c511', 'ade muslim', 'amuslim@mail.com', '$2y$10$GmXtGEBcCEe5x89dksFw0uOUrVErfX7EwHjffm3ujhd9Fxc1i4rxm', 'superadmin'),
('a86d168b-7e7c-4c00-b9c7-49f495d50c32', 'supri', 'supri@mtg.com', '$2y$10$dSZS6IWjEt/HZU2PvJiWYu5UEUrv4xXQR3CPBmEW7rBrLV1WOTWUK', 'kepala_perusahaan');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pesanan_pembelian`
--

CREATE TABLE `pesanan_pembelian` (
  `id_pesanan` varchar(36) NOT NULL,
  `id_pengirim` varchar(36) NOT NULL,
  `no_pesanan` varchar(50) NOT NULL,
  `tanggal` date NOT NULL,
  `total` int NOT NULL,
  `catatan` text,
  `id_penerima` varchar(36) NOT NULL,
  `up` varchar(100) DEFAULT NULL,
  `diskon` int DEFAULT NULL,
  `id_ppn` varchar(36) DEFAULT NULL,
  `logo` varchar(100) DEFAULT NULL,
  `signature_info` longtext
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `pesanan_pembelian`
--

INSERT INTO `pesanan_pembelian` (`id_pesanan`, `id_pengirim`, `no_pesanan`, `tanggal`, `total`, `catatan`, `id_penerima`, `up`, `diskon`, `id_ppn`, `logo`, `signature_info`) VALUES
('b5c89a75-74f7-4abb-9316-c9b104b423ce', 'df38f68e-ad09-4bc2-ac56-19b36dcb69cc', '001/po/mtg/02/2024', '2024-02-14', 792, 'tes po', '593fce6e-77d2-4a4d-a6ce-5a52692e2998', '-', 0, '5b1c4434-265c-40b3-b6ed-7638d3444ad1', '../../assets/image/uploads/logo/664a1e1592dea_20240519.jpeg', 'Location: ckr, Date: 2024-05-19, Name: ade, Position: hhhh, Path: ../../assets/image/uploads/signature/664a1e15934b1_20240519.png');

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
('3b7d0596-b043-44e1-bd02-37731c66764f', 'ppn11%', 11, 'opsional lagi\r\n'),
('525060b3-66ea-48c4-a68c-803cfdf409c3', 'tanpa ppn', 0, 'tanpa ppn'),
('5b1c4434-265c-40b3-b6ed-7638d3444ad1', 'ppn10%', 10, 'opsional');

-- --------------------------------------------------------

--
-- Struktur dari tabel `produk`
--

CREATE TABLE `produk` (
  `id_produk` varchar(36) NOT NULL,
  `no_produk` varchar(50) NOT NULL,
  `nama_produk` varchar(100) NOT NULL,
  `satuan` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `harga` decimal(10,2) UNSIGNED NOT NULL,
  `status` enum('Baru','Disetujui','Ditolak') NOT NULL DEFAULT 'Baru'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `produk`
--

INSERT INTO `produk` (`id_produk`, `no_produk`, `nama_produk`, `satuan`, `harga`, `status`) VALUES
('4f6e8a99-06ba-4d20-8717-db55a7f11cbe', 'odol', 'odol', 'pcs', 0.00, 'Disetujui'),
('746c1f9a-7d3a-49ae-90de-1c744b43ac1d', '2', 'produk 2', 'pcs', 200.00, 'Baru'),
('aa99eb73-96f8-413c-8e75-5db6b9cc9668', 'wm0', 'proses forming wiremesh', 'pcs', 110.00, 'Disetujui'),
('e84eb7fc-029a-4c40-b3d0-a66d166bdb7b', '1', 'produk 1', 'pcs', 1000.00, 'Baru'),
('ede04344-c744-4428-a6a4-2b214b512e3c', '3', 'produk 3', 'pcs', 2575.00, 'Disetujui'),
('f619d147-697c-40f6-847a-ef771bd4647f', '4', 'produk 4', 'pcs', 2575.00, 'Baru'),
('fff8cf5c-90d9-4a30-8aa5-a3115be29b55', '1234', 'chopper', 'pcs', 2000.00, 'Baru');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `detail_faktur`
--
ALTER TABLE `detail_faktur`
  ADD PRIMARY KEY (`id_detail_faktur`),
  ADD KEY `id_faktur` (`id_faktur`),
  ADD KEY `id_produk` (`id_produk`);

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
  ADD KEY `id_pesanan` (`id_pesanan`);

--
-- Indeks untuk tabel `faktur`
--
ALTER TABLE `faktur`
  ADD PRIMARY KEY (`id_faktur`),
  ADD KEY `id_pengirim` (`id_pengirim`),
  ADD KEY `id_penerima` (`id_penerima`),
  ADD KEY `id_ppn` (`id_ppn`);

--
-- Indeks untuk tabel `kontak_internal`
--
ALTER TABLE `kontak_internal`
  ADD PRIMARY KEY (`id_pengirim`);

--
-- Indeks untuk tabel `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`id_pelanggan`);

--
-- Indeks untuk tabel `penawaran_harga`
--
ALTER TABLE `penawaran_harga`
  ADD PRIMARY KEY (`id_penawaran`),
  ADD KEY `id_pengirim` (`id_pengirim`),
  ADD KEY `id_penerima` (`id_penerima`),
  ADD KEY `id_ppn` (`id_ppn`);

--
-- Indeks untuk tabel `pengguna`
--
ALTER TABLE `pengguna`
  ADD PRIMARY KEY (`id_pengguna`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indeks untuk tabel `pesanan_pembelian`
--
ALTER TABLE `pesanan_pembelian`
  ADD PRIMARY KEY (`id_pesanan`),
  ADD KEY `id_pengirim` (`id_pengirim`),
  ADD KEY `id_penerima` (`id_penerima`),
  ADD KEY `id_ppn` (`id_ppn`);

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
  ADD CONSTRAINT `detail_faktur_ibfk_2` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`);

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
  ADD CONSTRAINT `detail_pesanan_ibfk_3` FOREIGN KEY (`id_pesanan`) REFERENCES `pesanan_pembelian` (`id_pesanan`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ketidakleluasaan untuk tabel `faktur`
--
ALTER TABLE `faktur`
  ADD CONSTRAINT `faktur_ibfk_4` FOREIGN KEY (`id_pengirim`) REFERENCES `kontak_internal` (`id_pengirim`),
  ADD CONSTRAINT `faktur_ibfk_5` FOREIGN KEY (`id_penerima`) REFERENCES `pelanggan` (`id_pelanggan`),
  ADD CONSTRAINT `faktur_ibfk_6` FOREIGN KEY (`id_ppn`) REFERENCES `ppn` (`id_ppn`);

--
-- Ketidakleluasaan untuk tabel `penawaran_harga`
--
ALTER TABLE `penawaran_harga`
  ADD CONSTRAINT `penawaran_harga_ibfk_4` FOREIGN KEY (`id_pengirim`) REFERENCES `kontak_internal` (`id_pengirim`),
  ADD CONSTRAINT `penawaran_harga_ibfk_5` FOREIGN KEY (`id_penerima`) REFERENCES `pelanggan` (`id_pelanggan`),
  ADD CONSTRAINT `penawaran_harga_ibfk_6` FOREIGN KEY (`id_ppn`) REFERENCES `ppn` (`id_ppn`);

--
-- Ketidakleluasaan untuk tabel `pesanan_pembelian`
--
ALTER TABLE `pesanan_pembelian`
  ADD CONSTRAINT `pesanan_pembelian_ibfk_1` FOREIGN KEY (`id_pengirim`) REFERENCES `kontak_internal` (`id_pengirim`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `pesanan_pembelian_ibfk_2` FOREIGN KEY (`id_penerima`) REFERENCES `pelanggan` (`id_pelanggan`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `pesanan_pembelian_ibfk_3` FOREIGN KEY (`id_ppn`) REFERENCES `ppn` (`id_ppn`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
