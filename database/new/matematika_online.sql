-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 08, 2024 at 08:05 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `matematika_online`
--
CREATE DATABASE IF NOT EXISTS `matematika_online` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `matematika_online`;

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--
-- Creation: Dec 06, 2024 at 05:45 PM
-- Last update: Dec 08, 2024 at 06:50 AM
--

DROP TABLE IF EXISTS `activity_logs`;
CREATE TABLE IF NOT EXISTS `activity_logs` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `action` varchar(255) NOT NULL,
  PRIMARY KEY (`log_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=87 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELATIONSHIPS FOR TABLE `activity_logs`:
--   `user_id`
--       `users` -> `user_id`
--   `user_id`
--       `users` -> `user_id`
--

--
-- Truncate table before insert `activity_logs`
--

TRUNCATE TABLE `activity_logs`;
-- --------------------------------------------------------

--
-- Table structure for table `materi`
--
-- Creation: Dec 07, 2024 at 07:31 PM
-- Last update: Dec 08, 2024 at 06:30 AM
--

DROP TABLE IF EXISTS `materi`;
CREATE TABLE IF NOT EXISTS `materi` (
  `materi_id` int(11) NOT NULL AUTO_INCREMENT,
  `judul` varchar(200) NOT NULL,
  `konten` text NOT NULL,
  `keterangan` text DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `deskripsi` text DEFAULT NULL,
  `status` enum('draft','published') DEFAULT 'draft',
  `is_active` tinyint(1) DEFAULT 1,
  `jumlah_dibaca` int(11) DEFAULT 0,
  PRIMARY KEY (`materi_id`),
  KEY `created_by` (`created_by`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELATIONSHIPS FOR TABLE `materi`:
--   `created_by`
--       `users` -> `user_id`
--

--
-- Truncate table before insert `materi`
--

TRUNCATE TABLE `materi`;
--
-- Dumping data for table `materi`
--

INSERT DELAYED IGNORE INTO `materi` (`materi_id`, `judul`, `konten`, `keterangan`, `created_by`, `created_at`, `deskripsi`, `status`, `is_active`, `jumlah_dibaca`) VALUES
(1, 'Pengenalan Aljabar', '<img src=\"assets/img/MateriAljabar.jpg\" alt=\"Pengenalan Aljabar\" style=\"max-width:100%; height:auto;\">', 'Aljabar adalah cabang matematika yang mempelajari simbol-simbol dan aturan-aturan untuk memanipulasinya. Dalam aljabar, simbol-simbol digunakan untuk mewakili angka dan operasi matematika, sehingga memungkinkan penyelesaian masalah yang lebih umum dan abstrak. Konsep-konsep dasar aljabar mencakup bilangan variabel, konstanta, operasi penjumlahan, pengurangan, perkalian, pembagian, serta pemfaktoran. Aljabar menjadi landasan bagi banyak bidang matematika lainnya, seperti persamaan, fungsi, dan analisis data.', 1, '2024-12-07 17:00:00', 'Memahami dasar-dasar aljabar dan penggunaannya dalam matematika.', 'published', 1, 3),
(2, 'Geometri Dasar', '<img src=\"assets/img/MateriGeometri.jpg\" alt=\"Geometri Dasar\" style=\"max-width:100%; height:auto;\">\n', 'Geometri adalah cabang matematika yang berfokus pada studi tentang bentuk, ukuran, posisi relatif, dan sifat ruang. Geometri dasar mencakup konsep-konsep seperti titik, garis, sudut, segitiga, persegi, lingkaran, dan berbagai bentuk dua dimensi lainnya. Selain itu, konsep-konsep seperti luas, keliling, volume, dan teorema penting seperti Teorema Pythagoras sering digunakan untuk menganalisis hubungan antara elemen-elemen geometris. Geometri juga membantu memahami dunia fisik melalui representasi visual.', 1, '2024-12-07 17:00:00', 'Memahami konsep-konsep dasar dalam geometri.', 'published', 1, 4),
(3, 'Statistika Dasar', '<img src=\"assets/img/MateriStatistika.jpg\" alt=\"Statistika Dasar\" style=\"max-width:100%; height:auto;\">', 'Statistika adalah ilmu yang mempelajari cara mengumpulkan, menganalisis, menyajikan, dan menginterpretasikan data. Dalam statistika dasar, konsep-konsep seperti data tunggal dan kelompok, rata-rata (mean), median, modus, penyebaran data (simpangan baku), dan distribusi sering digunakan. Statistika membantu dalam pengambilan keputusan berdasarkan data dan memiliki aplikasi luas dalam berbagai bidang, termasuk ekonomi, kesehatan, dan penelitian ilmiah.', 1, '2024-12-07 17:00:00', 'Memahami konsep dasar statistika dan aplikasinya.', 'published', 1, 0),
(4, 'Persamaan Linier', '<img src=\"assets/img/MateriLinear.jpg\" alt=\"Persamaan Linier\" style=\"max-width:100%; height:auto;\">', 'Persamaan linear adalah persamaan yang merepresentasikan hubungan linier antara variabel-variabelnya. Bentuk umum dari persamaan linear adalah \r\n𝑎\r\n𝑥\r\n+\r\n𝑏\r\n=\r\n0\r\nax+b=0, di mana \r\n𝑎\r\na dan \r\n𝑏\r\nb adalah konstanta. Persamaan linear sering digunakan untuk memodelkan hubungan sederhana antara variabel, seperti harga dan jumlah, atau jarak dan waktu. Metode penyelesaian persamaan linear mencakup substitusi, eliminasi, atau penggunaan grafik.', 1, '2024-12-07 17:00:00', 'Memahami konsep dan cara menyelesaikan persamaan linier.', 'published', 1, 1),
(5, 'Fungsi Kuadrat', '<img src=\"assets/img/MateriKuadrat.jpg\" alt=\"Fungsi Kuadrat\" style=\"max-width:100%; height:auto;\">', 'Fungsi kuadrat adalah fungsi matematika yang dinyatakan dalam bentuk \r\n𝑦\r\n=\r\n𝑎\r\n𝑥\r\n2\r\n+\r\n𝑏\r\n𝑥\r\n+\r\n𝑐\r\ny=ax \r\n2\r\n +bx+c, di mana \r\n𝑎\r\n≠\r\n0\r\na\r\n\r\n=0. Grafik fungsi kuadrat berbentuk parabola yang dapat membuka ke atas atau ke bawah, tergantung pada nilai \r\n𝑎\r\na. Fungsi kuadrat sering digunakan untuk memodelkan fenomena fisik seperti gerak benda di bawah pengaruh gravitasi. Sifat-sifat penting fungsi kuadrat termasuk titik puncak (vertex), sumbu simetri, dan akar-akar persamaan kuadrat.', 1, '2024-12-07 17:00:00', 'Memahami grafik dan sifat-sifat fungsi kuadrat.', 'published', 1, 0),
(6, 'Trigonometri Dasar', '<img src=\"assets/img/MateriTrigonometri.jpg\" alt=\"Trigonometri Dasar\" style=\"max-width:100%; height:auto;\">', 'Trigonometri adalah cabang matematika yang mempelajari hubungan antara sudut dan sisi-sisi segitiga. Konsep dasar trigonometri mencakup sinus, kosinus, tangen, serta hubungan-hubungan seperti identitas trigonometri dan hukum sinus-kosinus. Trigonometri sering digunakan dalam berbagai bidang, termasuk teknik, astronomi, dan navigasi, karena kemampuannya dalam menganalisis dan memodelkan fenomena yang melibatkan sudut dan jarak.', 1, '2024-12-07 17:00:00', 'Memahami konsep dasar trigonometri dan fungsinya.', 'published', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--
-- Creation: Dec 07, 2024 at 09:54 PM
-- Last update: Dec 08, 2024 at 06:48 AM
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `last_login` datetime DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `role` enum('admin','siswa') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `profile_picture` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELATIONSHIPS FOR TABLE `users`:
--

--
-- Truncate table before insert `users`
--

TRUNCATE TABLE `users`;
--
-- Dumping data for table `users`
--

INSERT DELAYED IGNORE INTO `users` (`user_id`, `last_login`, `username`, `password`, `email`, `full_name`, `role`, `created_at`, `profile_picture`, `description`) VALUES
(1, '2024-12-08 13:30:54', 'maesaroh', '$2y$10$ETlyyd09X6wBzZCVfSyIWu1E10K4mFeW4p5O0wXiYiv2RgPYWPoi.', 'adminmaesaroh@gmail.com', 'Siti Maesaroh', 'admin', '2024-12-07 09:30:57', '<img src=\"assets/img/adminsaroh.jpg\" alt=\"Siti Maesaroh\" style=\"width:200px; height:auto; max-width:50%;\">', 'Apa yang terjadi itu yang terbaik.'),
(3, '2024-12-08 13:45:11', 'denay', '$2y$10$zW/nYR9ePBu.14eifRxs.eS/gQlYDqKSDCvN4hL7y8TxDg9RILHDW', 'denay@gmail.com', 'Naya Putra', 'siswa', '2024-12-05 19:00:53', '<img src=\"assets/img/denay.jpg\" alt=\"Naya Putra\" style=\"width:600px; height:auto; max-width:50%;\">', 'Saya ada seorang yang gemar sekali bermain game online. Banyak waktu dihabiskan untuk bermain game dan itu sangatlah membosankan. Jangan jadi orang yang seperti itu ya');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `materi`
--
ALTER TABLE `materi`
  ADD CONSTRAINT `materi_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`user_id`);


--
-- Metadata
--
USE `phpmyadmin`;

--
-- Metadata for table activity_logs
--

--
-- Truncate table before insert `pma__column_info`
--

TRUNCATE TABLE `pma__column_info`;
--
-- Truncate table before insert `pma__table_uiprefs`
--

TRUNCATE TABLE `pma__table_uiprefs`;
--
-- Truncate table before insert `pma__tracking`
--

TRUNCATE TABLE `pma__tracking`;
--
-- Metadata for table materi
--

--
-- Truncate table before insert `pma__column_info`
--

TRUNCATE TABLE `pma__column_info`;
--
-- Truncate table before insert `pma__table_uiprefs`
--

TRUNCATE TABLE `pma__table_uiprefs`;
--
-- Truncate table before insert `pma__tracking`
--

TRUNCATE TABLE `pma__tracking`;
--
-- Metadata for table users
--

--
-- Truncate table before insert `pma__column_info`
--

TRUNCATE TABLE `pma__column_info`;
--
-- Truncate table before insert `pma__table_uiprefs`
--

TRUNCATE TABLE `pma__table_uiprefs`;
--
-- Truncate table before insert `pma__tracking`
--

TRUNCATE TABLE `pma__tracking`;
--
-- Metadata for database matematika_online
--

--
-- Truncate table before insert `pma__bookmark`
--

TRUNCATE TABLE `pma__bookmark`;
--
-- Truncate table before insert `pma__relation`
--

TRUNCATE TABLE `pma__relation`;
--
-- Truncate table before insert `pma__savedsearches`
--

TRUNCATE TABLE `pma__savedsearches`;
--
-- Truncate table before insert `pma__central_columns`
--

TRUNCATE TABLE `pma__central_columns`;SET FOREIGN_KEY_CHECKS=1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
