-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 30, 2025 at 11:39 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `spk_laptop`
--

-- --------------------------------------------------------

--
-- Table structure for table `data_laptop`
--

CREATE TABLE `data_laptop` (
  `id_laptop` int(11) NOT NULL,
  `merk` varchar(10) NOT NULL,
  `nama_laptop` varchar(255) NOT NULL,
  `harga_angka` int(10) NOT NULL,
  `processor_angka` int(11) NOT NULL,
  `ram_angka` int(11) NOT NULL,
  `vga_angka` int(11) NOT NULL,
  `memori_angka` int(11) NOT NULL,
  `lcd_angka` int(11) NOT NULL,
  `processor_h` int(10) NOT NULL,
  `lcd_oled` int(10) NOT NULL,
  `processor_teks` varchar(100) NOT NULL,
  `vga_teks` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `data_laptop`
--

INSERT INTO `data_laptop` (`id_laptop`, `merk`, `nama_laptop`, `harga_angka`, `processor_angka`, `ram_angka`, `vga_angka`, `memori_angka`, `lcd_angka`, `processor_h`, `lcd_oled`, `processor_teks`, `vga_teks`) VALUES
(1, 'Acer', 'Acer AI14 31P-COG4', 4949000, 35, 8, 40, 512, 14, 1, 1, 'Intel N100', 'Intel UHD'),
(2, 'Acer', 'Acer A314 42P-R7E5', 8649000, 60, 16, 45, 512, 14, 1, 1, 'RYZEN 7 5700U', 'Radeon Graphics'),
(3, 'Acer', 'ACER NITRO ANV15 41-79Y1', 12649000, 85, 16, 65, 512, 15, 15, 1, 'I7-13620H', 'RTX 2050'),
(4, 'Asus', 'ASUS E410KA', 4645000, 20, 8, 40, 256, 14, 1, 1, 'CELERON N4500', 'Intel UHD'),
(5, 'Acer', 'Acer TMP40-53', 5299000, 71, 8, 505, 256, 15, 1, 1, 'i5-1035G7', 'Intel iris Xe'),
(6, 'Acer', 'Acer PHN16-72', 24399000, 85, 8, 95, 512, 26, 15, 10, 'i9-12900H', 'RTX 4050'),
(7, 'Acer', 'Acer Swift SFG14', 11599000, 61, 16, 45, 512, 15, 1, 1, 'amd 7-7300U', 'AMD Radeon'),
(8, 'Asus', 'Asus A416MAO', 4299000, 36, 4, 40, 256, 15, 1, 1, 'Intel N4020', 'Intel UHD'),
(9, 'Asus', 'Asus A1400KA', 4799000, 36, 4, 40, 512, 15, 1, 1, 'Intel N4510', 'Intel UHD'),
(10, 'Asus', 'Asus A416FA', 5599000, 71, 4, 40, 256, 15, 1, 1, 'i3-10110U', 'Intel UHD'),
(11, 'Asus', 'Asus ROG-G614JU', 24299000, 85, 16, 95, 512, 26, 15, 10, 'i7-13650HX', 'RTX 4050'),
(12, 'HP', 'HP 14S-DQ3109TU', 4399000, 36, 8, 40, 256, 15, 1, 1, 'Intel N4500', 'Intel UHD'),
(13, 'HP', 'HP 14S-DQ3888TU', 5199000, 36, 8, 40, 512, 15, 1, 1, 'Intel N4500', 'Intel UHD'),
(14, 'HP', 'HP Victus 16', 20999000, 85, 16, 95, 1024, 26, 15, 10, 'i7-13700HX', 'RTX 4050'),
(15, 'Lenovo', 'Lenovo 14IJL7', 4799000, 36, 8, 40, 256, 15, 1, 1, 'Intel N4500', 'Intel UHD'),
(16, 'Lenovo', 'Lenovo Yoga Slim 7', 11499000, 71, 8, 505, 512, 15, 1, 1, 'I5-1135G7', 'Intel iris Xe'),
(17, 'Lokal', 'Axioo HYPE3', 4399000, 71, 8, 40, 256, 15, 1, 1, 'i3-1005G1', 'Intel UHD'),
(18, 'Lokal', 'Axioo Pongo 735', 11599000, 85, 16, 75, 512, 16, 15, 1, 'i7-13620H', 'RTX 3050'),
(19, 'Lenovo', 'Lenovo ideapad 5', 14599000, 85, 16, 40, 512, 24, 15, 10, 'i7-13620H', 'Intel UHD'),
(20, 'Lenovo', 'Lenovo Slim 3', 8499000, 51, 8, 45, 512, 15, 1, 1, 'Amd 5-5500U', 'AMD Radeon'),
(21, 'HP', 'HP Pav Plus 14e', 11699000, 51, 16, 45, 512, 24, 1, 10, 'Amd 5-7540U', 'AMD Radeon'),
(22, 'Asus', 'Asus A1400EA', 5699000, 71, 4, 40, 256, 15, 1, 1, 'I3-1115G4', 'Intel UHD');

-- --------------------------------------------------------

--
-- Table structure for table `preset`
--

CREATE TABLE `preset` (
  `id` int(11) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `w1` int(11) NOT NULL,
  `w2` int(11) NOT NULL,
  `w3` int(11) NOT NULL,
  `w4` int(11) NOT NULL,
  `w5` int(11) NOT NULL,
  `w6` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `preset`
--

INSERT INTO `preset` (`id`, `nama`, `w1`, `w2`, `w3`, `w4`, `w5`, `w6`) VALUES
(1, 'gaming', 1, 5, 5, 5, 4, 4),
(2, 'Perkantoran', 4, 2, 2, 1, 2, 2),
(3, 'Pelajar', 5, 2, 2, 2, 1, 1),
(4, 'Desain/Multimedia', 2, 3, 3, 4, 3, 5),
(5, 'Low Budget', 5, 1, 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `created_at`) VALUES
(1, 'admin', '$2y$10$r2y8GW0YWQ9H/KFjj2ngJ.V7CrFr/j/A0XuA3RGwX68c4GYqXiaLa', 'admin', '2025-06-08 10:51:41'),
(2, 'user1', '$2y$10$MFcWD2lNhsotqtlTeu.ovOZGKRQIn36F.BP4EJ9JdkE5d1cN8o2bi', 'user', '2025-07-30 09:31:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `data_laptop`
--
ALTER TABLE `data_laptop`
  ADD PRIMARY KEY (`id_laptop`);

--
-- Indexes for table `preset`
--
ALTER TABLE `preset`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `data_laptop`
--
ALTER TABLE `data_laptop`
  MODIFY `id_laptop` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `preset`
--
ALTER TABLE `preset`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
