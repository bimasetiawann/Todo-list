-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 09, 2024 at 02:23 PM
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
-- Database: `todo`
--

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id_task` int(11) NOT NULL,
  `task` varchar(50) NOT NULL,
  `keterangan` varchar(255) NOT NULL,
  `due_date` datetime DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` enum('pending','completed') DEFAULT 'pending',
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id_task`, `task`, `keterangan`, `due_date`, `description`, `status`, `id`) VALUES
(1, 'kuliah', '', '2024-06-09 06:00:00', 'huhu haha', 'completed', 0),
(11, 'Kelas Back-End', '', '2024-06-09 23:20:00', 'Kelas ada di lab', 'pending', 0),
(12, 'Kelas Administrasi Basis Data', '', '2024-06-09 11:31:00', 'Kelas 3.4', 'completed', 0);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `nama`, `username`, `password`) VALUES
(1, 'ajus', 'ganteng', '$2y$10$SrCfcXqdO9QYmOTom/n.Nuc3VImmq5k.OKdvvdgQkd//UpCLP2E0q'),
(2, 'Ajus Ganteng', 'user', '$2y$10$W5iky9nCkNxi/vw6wQUd5.8KAPGB/XH.axObL7C83sCclbFB.Q.CO'),
(3, 'Jihyo', 'zyozyo', '$2y$10$fQ7GHPpGEjl/.DZzdhOQD.ld9s84JWeCfgnK7LuMnW59NTjG6UCAG'),
(4, 'wildan antoni', 'wildan', '$2y$10$P64WbyF8A//SP7Ex8S4FaOxHxjckUnumqtHjo.Y0FEBgaR8AEE4IC');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id_task`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id_task` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
