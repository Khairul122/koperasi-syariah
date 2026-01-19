-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 18, 2026 at 04:33 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kp-simpan-pinjam`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_anggota`
--

CREATE TABLE `tb_anggota` (
  `id_anggota` int NOT NULL,
  `no_anggota` varchar(20) COLLATE utf8mb3_swedish_ci NOT NULL,
  `nik` varchar(16) COLLATE utf8mb3_swedish_ci NOT NULL,
  `nama_lengkap` varchar(100) COLLATE utf8mb3_swedish_ci NOT NULL,
  `jenis_kelamin` enum('L','P') COLLATE utf8mb3_swedish_ci NOT NULL,
  `tempat_lahir` varchar(50) COLLATE utf8mb3_swedish_ci DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `alamat` text COLLATE utf8mb3_swedish_ci,
  `no_hp` varchar(15) COLLATE utf8mb3_swedish_ci DEFAULT NULL,
  `pekerjaan` varchar(50) COLLATE utf8mb3_swedish_ci DEFAULT NULL,
  `username` varchar(50) COLLATE utf8mb3_swedish_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb3_swedish_ci DEFAULT NULL,
  `status_aktif` enum('Aktif','Non-Aktif') COLLATE utf8mb3_swedish_ci DEFAULT 'Aktif',
  `tanggal_daftar` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;

--
-- Dumping data for table `tb_anggota`
--

INSERT INTO `tb_anggota` (`id_anggota`, `no_anggota`, `nik`, `nama_lengkap`, `jenis_kelamin`, `tempat_lahir`, `tanggal_lahir`, `alamat`, `no_hp`, `pekerjaan`, `username`, `password`, `status_aktif`, `tanggal_daftar`) VALUES
(1, 'ANG-001', '1371010000000001', 'Noval Hammadi', 'L', 'Padang', '2000-01-01', 'Jl. Lubeg Padang', '08123456789', 'Mahasiswa', 'noval', '3334bd2d15055f558c84b08bfa3c42bb', 'Aktif', '2026-01-18');

-- --------------------------------------------------------

--
-- Table structure for table `tb_angsuran`
--

CREATE TABLE `tb_angsuran` (
  `id_angsuran` int NOT NULL,
  `no_kwitansi` varchar(20) COLLATE utf8mb3_swedish_ci DEFAULT NULL,
  `tanggal_bayar` datetime DEFAULT CURRENT_TIMESTAMP,
  `angsuran_ke` int DEFAULT NULL,
  `jumlah_bayar` decimal(15,2) DEFAULT NULL,
  `sisa_tagihan` decimal(15,2) DEFAULT NULL,
  `denda` decimal(15,2) DEFAULT '0.00',
  `id_pembiayaan` int DEFAULT NULL,
  `id_petugas` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_jenis_simpanan`
--

CREATE TABLE `tb_jenis_simpanan` (
  `id_jenis` int NOT NULL,
  `nama_simpanan` varchar(50) COLLATE utf8mb3_swedish_ci DEFAULT NULL,
  `akad` varchar(50) COLLATE utf8mb3_swedish_ci DEFAULT NULL,
  `minimal_setor` decimal(15,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;

--
-- Dumping data for table `tb_jenis_simpanan`
--

INSERT INTO `tb_jenis_simpanan` (`id_jenis`, `nama_simpanan`, `akad`, `minimal_setor`) VALUES
(1, 'Simpanan Pokok', 'Wadiah', '100000.00'),
(2, 'Simpanan Wajib', 'Wadiah', '20000.00'),
(3, 'Simpanan Sukarela', 'Wadiah', '5000.00');

-- --------------------------------------------------------

--
-- Table structure for table `tb_pembiayaan`
--

CREATE TABLE `tb_pembiayaan` (
  `id_pembiayaan` int NOT NULL,
  `no_akad` varchar(30) COLLATE utf8mb3_swedish_ci DEFAULT NULL,
  `tanggal_pengajuan` date DEFAULT NULL,
  `keperluan` text COLLATE utf8mb3_swedish_ci,
  `jenis_akad` varchar(50) COLLATE utf8mb3_swedish_ci DEFAULT NULL,
  `jumlah_pokok` decimal(15,2) DEFAULT NULL,
  `margin_koperasi` decimal(15,2) DEFAULT NULL,
  `total_bayar` decimal(15,2) DEFAULT NULL,
  `tenor_bulan` int DEFAULT NULL,
  `cicilan_per_bulan` decimal(15,2) DEFAULT NULL,
  `status` enum('Pending','Disetujui','Ditolak','Lunas') COLLATE utf8mb3_swedish_ci DEFAULT 'Pending',
  `catatan_bendahara` text COLLATE utf8mb3_swedish_ci,
  `id_anggota` int DEFAULT NULL,
  `id_petugas_acc` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_petugas`
--

CREATE TABLE `tb_petugas` (
  `id_petugas` int NOT NULL,
  `username` varchar(50) COLLATE utf8mb3_swedish_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb3_swedish_ci NOT NULL,
  `nama_lengkap` varchar(100) COLLATE utf8mb3_swedish_ci NOT NULL,
  `level` enum('Admin','Bendahara') COLLATE utf8mb3_swedish_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;

--
-- Dumping data for table `tb_petugas`
--

INSERT INTO `tb_petugas` (`id_petugas`, `username`, `password`, `nama_lengkap`, `level`, `created_at`) VALUES
(1, 'admin', '0192023a7bbd73250516f069df18b500', 'Budi Operasional', 'Admin', '2026-01-17 18:13:33'),
(2, 'bendahara', '62f7dec74b78ba0398e6a9f317f55126', 'Siti Keuangan', 'Bendahara', '2026-01-17 18:13:33');

-- --------------------------------------------------------

--
-- Table structure for table `tb_simpanan_anggota`
--

CREATE TABLE `tb_simpanan_anggota` (
  `id_simpanan` int NOT NULL,
  `no_rekening` varchar(20) COLLATE utf8mb3_swedish_ci DEFAULT NULL,
  `total_setoran` decimal(15,2) NOT NULL DEFAULT '0.00' COMMENT 'Total uang yang masuk',
  `total_penarikan` decimal(15,2) NOT NULL DEFAULT '0.00' COMMENT 'Total uang yang diambil',
  `saldo_terakhir` decimal(15,2) DEFAULT '0.00',
  `status` enum('Aktif','Tutup') COLLATE utf8mb3_swedish_ci DEFAULT 'Aktif',
  `id_anggota` int DEFAULT NULL,
  `id_jenis` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;

--
-- Dumping data for table `tb_simpanan_anggota`
--

INSERT INTO `tb_simpanan_anggota` (`id_simpanan`, `no_rekening`, `total_setoran`, `total_penarikan`, `saldo_terakhir`, `status`, `id_anggota`, `id_jenis`) VALUES
(1, 'REK-001-PK', '0.00', '0.00', '100000.00', 'Aktif', 1, 1),
(2, 'REK-001-WJ', '0.00', '0.00', '0.00', 'Aktif', 1, 2),
(3, 'REK-001-SK', '0.00', '0.00', '500000.00', 'Aktif', 1, 3);

-- --------------------------------------------------------

--
-- Table structure for table `tb_transaksi_simpanan`
--

CREATE TABLE `tb_transaksi_simpanan` (
  `id_transaksi` int NOT NULL,
  `no_transaksi` varchar(20) COLLATE utf8mb3_swedish_ci DEFAULT NULL,
  `tanggal_transaksi` datetime DEFAULT CURRENT_TIMESTAMP,
  `jenis_transaksi` enum('Setor','Tarik','Bagi Hasil') COLLATE utf8mb3_swedish_ci DEFAULT NULL,
  `jumlah` decimal(15,2) DEFAULT NULL,
  `keterangan` text COLLATE utf8mb3_swedish_ci,
  `id_simpanan` int DEFAULT NULL,
  `id_petugas` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;

--
-- Dumping data for table `tb_transaksi_simpanan`
--

INSERT INTO `tb_transaksi_simpanan` (`id_transaksi`, `no_transaksi`, `tanggal_transaksi`, `jenis_transaksi`, `jumlah`, `keterangan`, `id_simpanan`, `id_petugas`) VALUES
(1, 'TRX-001', '2026-01-18 01:13:33', 'Setor', '500000.00', 'Setoran Awal', 3, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_anggota`
--
ALTER TABLE `tb_anggota`
  ADD PRIMARY KEY (`id_anggota`),
  ADD UNIQUE KEY `no_anggota` (`no_anggota`),
  ADD UNIQUE KEY `nik` (`nik`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `tb_angsuran`
--
ALTER TABLE `tb_angsuran`
  ADD PRIMARY KEY (`id_angsuran`),
  ADD KEY `id_pembiayaan` (`id_pembiayaan`),
  ADD KEY `id_petugas` (`id_petugas`);

--
-- Indexes for table `tb_jenis_simpanan`
--
ALTER TABLE `tb_jenis_simpanan`
  ADD PRIMARY KEY (`id_jenis`);

--
-- Indexes for table `tb_pembiayaan`
--
ALTER TABLE `tb_pembiayaan`
  ADD PRIMARY KEY (`id_pembiayaan`),
  ADD UNIQUE KEY `no_akad` (`no_akad`),
  ADD KEY `id_anggota` (`id_anggota`),
  ADD KEY `id_petugas_acc` (`id_petugas_acc`);

--
-- Indexes for table `tb_petugas`
--
ALTER TABLE `tb_petugas`
  ADD PRIMARY KEY (`id_petugas`);

--
-- Indexes for table `tb_simpanan_anggota`
--
ALTER TABLE `tb_simpanan_anggota`
  ADD PRIMARY KEY (`id_simpanan`),
  ADD UNIQUE KEY `no_rekening` (`no_rekening`),
  ADD KEY `id_anggota` (`id_anggota`),
  ADD KEY `id_jenis` (`id_jenis`);

--
-- Indexes for table `tb_transaksi_simpanan`
--
ALTER TABLE `tb_transaksi_simpanan`
  ADD PRIMARY KEY (`id_transaksi`),
  ADD KEY `id_simpanan` (`id_simpanan`),
  ADD KEY `id_petugas` (`id_petugas`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_anggota`
--
ALTER TABLE `tb_anggota`
  MODIFY `id_anggota` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tb_angsuran`
--
ALTER TABLE `tb_angsuran`
  MODIFY `id_angsuran` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_jenis_simpanan`
--
ALTER TABLE `tb_jenis_simpanan`
  MODIFY `id_jenis` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tb_pembiayaan`
--
ALTER TABLE `tb_pembiayaan`
  MODIFY `id_pembiayaan` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_petugas`
--
ALTER TABLE `tb_petugas`
  MODIFY `id_petugas` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tb_simpanan_anggota`
--
ALTER TABLE `tb_simpanan_anggota`
  MODIFY `id_simpanan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tb_transaksi_simpanan`
--
ALTER TABLE `tb_transaksi_simpanan`
  MODIFY `id_transaksi` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tb_angsuran`
--
ALTER TABLE `tb_angsuran`
  ADD CONSTRAINT `tb_angsuran_ibfk_1` FOREIGN KEY (`id_pembiayaan`) REFERENCES `tb_pembiayaan` (`id_pembiayaan`),
  ADD CONSTRAINT `tb_angsuran_ibfk_2` FOREIGN KEY (`id_petugas`) REFERENCES `tb_petugas` (`id_petugas`);

--
-- Constraints for table `tb_pembiayaan`
--
ALTER TABLE `tb_pembiayaan`
  ADD CONSTRAINT `tb_pembiayaan_ibfk_1` FOREIGN KEY (`id_anggota`) REFERENCES `tb_anggota` (`id_anggota`) ON DELETE CASCADE,
  ADD CONSTRAINT `tb_pembiayaan_ibfk_2` FOREIGN KEY (`id_petugas_acc`) REFERENCES `tb_petugas` (`id_petugas`);

--
-- Constraints for table `tb_simpanan_anggota`
--
ALTER TABLE `tb_simpanan_anggota`
  ADD CONSTRAINT `tb_simpanan_anggota_ibfk_1` FOREIGN KEY (`id_anggota`) REFERENCES `tb_anggota` (`id_anggota`) ON DELETE CASCADE,
  ADD CONSTRAINT `tb_simpanan_anggota_ibfk_2` FOREIGN KEY (`id_jenis`) REFERENCES `tb_jenis_simpanan` (`id_jenis`);

--
-- Constraints for table `tb_transaksi_simpanan`
--
ALTER TABLE `tb_transaksi_simpanan`
  ADD CONSTRAINT `tb_transaksi_simpanan_ibfk_1` FOREIGN KEY (`id_simpanan`) REFERENCES `tb_simpanan_anggota` (`id_simpanan`) ON DELETE CASCADE,
  ADD CONSTRAINT `tb_transaksi_simpanan_ibfk_2` FOREIGN KEY (`id_petugas`) REFERENCES `tb_petugas` (`id_petugas`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
