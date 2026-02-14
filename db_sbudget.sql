-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 10 Feb 2026 pada 13.00
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_sbudget`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `atur_budget`
--

CREATE TABLE `atur_budget` (
  `id_budget` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `budget` int(11) NOT NULL,
  `bulan` int(11) NOT NULL,
  `tahun` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `atur_budget`
--

INSERT INTO `atur_budget` (`id_budget`, `user_id`, `budget`, `bulan`, `tahun`) VALUES
(1, 5, 2000000, 2, 2026),
(2, 1, 5000000, 2, 2026);

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksi`
--

CREATE TABLE `transaksi` (
  `id_transaksi` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `jenis` enum('Pemasukan','Pengeluaran') NOT NULL,
  `kategori` varchar(50) NOT NULL,
  `catatan` varchar(255) NOT NULL,
  `tanggal` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `transaksi`
--

INSERT INTO `transaksi` (`id_transaksi`, `user_id`, `jumlah`, `jenis`, `kategori`, `catatan`, `tanggal`) VALUES
(1, 5, 290000, 'Pengeluaran', '🎮 Hiburan', 'Skin ML', '2026-02-10'),
(11, 5, 50000, 'Pengeluaran', '🍔 Makanan', 'ayam', '2026-02-10'),
(12, 5, 300000, 'Pengeluaran', '📦 Lainnya', 'skinker', '2026-02-10'),
(13, 5, 120000, 'Pengeluaran', '🎮 Hiburan', 'Kuota', '2026-02-10'),
(14, 5, 79000, 'Pengeluaran', '🎮 Hiburan', 'Spotify', '2026-02-10'),
(15, 5, 350000, 'Pengeluaran', '🚗 Transportasi', 'Bensin', '2026-02-10'),
(16, 1, 600000, 'Pengeluaran', '🚗 Transportasi', 'Tiket Kereta', '2026-02-10'),
(17, 1, 600000, 'Pemasukan', '🍔 Makanan', 'adwwa', '2026-02-10'),
(19, 1, 5000000, 'Pemasukan', '🍔 Makanan', '', '2026-02-10');

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`id`, `nama`, `email`, `password`) VALUES
(1, 'Dean KT', 'deankt@example.com', '202cb962ac59075b964b07152d234b70'),
(3, 'kairi', 'kairi@example.com', '$2y$10$xH/aYcr7udffPQo33wFDzulNFkegGWcVI7dpIGXDqivCVv97PKT/y'),
(4, 'mas ade', 'masade@example.com', '202cb962ac59075b964b07152d234b70'),
(5, 'Nino Sabar', 'ninosabar@example.com', '202cb962ac59075b964b07152d234b70'),
(6, '', '', 'd41d8cd98f00b204e9800998ecf8427e');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `atur_budget`
--
ALTER TABLE `atur_budget`
  ADD PRIMARY KEY (`id_budget`),
  ADD UNIQUE KEY `id_user` (`user_id`);

--
-- Indeks untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id_transaksi`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `atur_budget`
--
ALTER TABLE `atur_budget`
  MODIFY `id_budget` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id_transaksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `atur_budget`
--
ALTER TABLE `atur_budget`
  ADD CONSTRAINT `atur_budget_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
