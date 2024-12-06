-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 01, 2024 at 08:00 PM
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
-- Database: `matematika_online`
--
CREATE DATABASE IF NOT EXISTS `matematika_online` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `matematika_online`;

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

DROP TABLE IF EXISTS `activity_logs`;
CREATE TABLE `activity_logs` (
  `log_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `activity_type` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `forum_diskusi`
--

DROP TABLE IF EXISTS `forum_diskusi`;
CREATE TABLE `forum_diskusi` (
  `diskusi_id` int(11) NOT NULL,
  `judul` varchar(200) NOT NULL,
  `konten` text NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `materi_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `forum_komentar`
--

DROP TABLE IF EXISTS `forum_komentar`;
CREATE TABLE `forum_komentar` (
  `komentar_id` int(11) NOT NULL,
  `topik_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `konten` text NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `forum_topik`
--

DROP TABLE IF EXISTS `forum_topik`;
CREATE TABLE `forum_topik` (
  `topik_id` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `konten` text NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `kategori` enum('umum','pelajaran','pengumuman') DEFAULT 'umum',
  `status` enum('active','closed') DEFAULT 'active',
  `is_sticky` tinyint(1) DEFAULT 0,
  `view_count` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kelas`
--

DROP TABLE IF EXISTS `kelas`;
CREATE TABLE `kelas` (
  `kelas_id` int(11) NOT NULL,
  `nama_kelas` varchar(50) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `komentar_forum`
--

DROP TABLE IF EXISTS `komentar_forum`;
CREATE TABLE `komentar_forum` (
  `komentar_id` int(11) NOT NULL,
  `diskusi_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `konten` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kuis`
--

DROP TABLE IF EXISTS `kuis`;
CREATE TABLE `kuis` (
  `kuis_id` int(11) NOT NULL,
  `judul` varchar(200) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `materi_id` int(11) DEFAULT NULL,
  `waktu_pengerjaan` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `durasi` int(11) NOT NULL DEFAULT 30,
  `nilai_lulus` int(11) NOT NULL DEFAULT 70,
  `status` enum('draft','published') DEFAULT 'draft',
  `is_active` tinyint(1) DEFAULT 1,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kuis_hasil`
--

DROP TABLE IF EXISTS `kuis_hasil`;
CREATE TABLE `kuis_hasil` (
  `hasil_id` int(11) NOT NULL,
  `kuis_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `nilai` decimal(5,2) DEFAULT 0.00,
  `waktu_mulai` timestamp NULL DEFAULT NULL,
  `waktu_selesai` timestamp NULL DEFAULT NULL,
  `status` enum('belum_selesai','selesai') DEFAULT 'belum_selesai',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kuis_jawaban`
--

DROP TABLE IF EXISTS `kuis_jawaban`;
CREATE TABLE `kuis_jawaban` (
  `jawaban_id` int(11) NOT NULL,
  `hasil_id` int(11) DEFAULT NULL,
  `soal_id` int(11) DEFAULT NULL,
  `pilihan_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kuis_pilihan`
--

DROP TABLE IF EXISTS `kuis_pilihan`;
CREATE TABLE `kuis_pilihan` (
  `pilihan_id` int(11) NOT NULL,
  `soal_id` int(11) DEFAULT NULL,
  `teks` text NOT NULL,
  `is_benar` tinyint(1) DEFAULT 0,
  `urutan` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kuis_soal`
--

DROP TABLE IF EXISTS `kuis_soal`;
CREATE TABLE `kuis_soal` (
  `kuis_id` int(11) NOT NULL,
  `soal_id` int(11) NOT NULL,
  `pertanyaan` text NOT NULL,
  `jenis` enum('pilihan_ganda','benar_salah') DEFAULT 'pilihan_ganda',
  `bobot` int(11) DEFAULT 1,
  `urutan` int(11) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `learning_statistics`
--

DROP TABLE IF EXISTS `learning_statistics`;
CREATE TABLE `learning_statistics` (
  `stat_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `materi_id` int(11) DEFAULT NULL,
  `time_spent` int(11) DEFAULT 0,
  `progress` int(11) DEFAULT 0,
  `last_accessed` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `materi`
--

DROP TABLE IF EXISTS `materi`;
CREATE TABLE `materi` (
  `materi_id` int(11) NOT NULL,
  `judul` varchar(200) NOT NULL,
  `konten` text NOT NULL,
  `kelas_id` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `deskripsi` text DEFAULT NULL,
  `status` enum('draft','published') DEFAULT 'draft',
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `nilai_kuis`
--

DROP TABLE IF EXISTS `nilai_kuis`;
CREATE TABLE `nilai_kuis` (
  `nilai_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `kuis_id` int(11) DEFAULT NULL,
  `nilai` decimal(5,2) DEFAULT NULL,
  `waktu_mulai` timestamp NOT NULL DEFAULT current_timestamp(),
  `waktu_selesai` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `progress_siswa`
--

DROP TABLE IF EXISTS `progress_siswa`;
CREATE TABLE `progress_siswa` (
  `progress_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `materi_id` int(11) DEFAULT NULL,
  `status` enum('belum_mulai','sedang_dipelajari','selesai') DEFAULT 'belum_mulai',
  `last_accessed` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reset_password`
--

DROP TABLE IF EXISTS `reset_password`;
CREATE TABLE `reset_password` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `soal`
--

DROP TABLE IF EXISTS `soal`;
CREATE TABLE `soal` (
  `soal_id` int(11) NOT NULL,
  `materi_id` int(11) DEFAULT NULL,
  `pertanyaan` text NOT NULL,
  `jawaban_benar` text NOT NULL,
  `pilihan_a` text NOT NULL,
  `pilihan_b` text NOT NULL,
  `pilihan_c` text NOT NULL,
  `pilihan_d` text NOT NULL,
  `level_kesulitan` enum('mudah','sedang','sulit') NOT NULL,
  `created_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `role` enum('admin','guru','siswa') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `email`, `full_name`, `role`, `created_at`) VALUES
(1, 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@localhost', 'Administrator', 'admin', '2024-12-01 17:10:32');

-- --------------------------------------------------------

--
-- Table structure for table `user_logs`
--

DROP TABLE IF EXISTS `user_logs`;
CREATE TABLE `user_logs` (
  `log_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `action` varchar(255) NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `forum_diskusi`
--
ALTER TABLE `forum_diskusi`
  ADD PRIMARY KEY (`diskusi_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `materi_id` (`materi_id`);

--
-- Indexes for table `forum_komentar`
--
ALTER TABLE `forum_komentar`
  ADD PRIMARY KEY (`komentar_id`),
  ADD KEY `topik_id` (`topik_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Indexes for table `forum_topik`
--
ALTER TABLE `forum_topik`
  ADD PRIMARY KEY (`topik_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `kelas`
--
ALTER TABLE `kelas`
  ADD PRIMARY KEY (`kelas_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `komentar_forum`
--
ALTER TABLE `komentar_forum`
  ADD PRIMARY KEY (`komentar_id`),
  ADD KEY `diskusi_id` (`diskusi_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `kuis`
--
ALTER TABLE `kuis`
  ADD PRIMARY KEY (`kuis_id`),
  ADD KEY `materi_id` (`materi_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `kuis_hasil`
--
ALTER TABLE `kuis_hasil`
  ADD PRIMARY KEY (`hasil_id`),
  ADD KEY `kuis_id` (`kuis_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `kuis_jawaban`
--
ALTER TABLE `kuis_jawaban`
  ADD PRIMARY KEY (`jawaban_id`),
  ADD KEY `hasil_id` (`hasil_id`),
  ADD KEY `soal_id` (`soal_id`),
  ADD KEY `pilihan_id` (`pilihan_id`);

--
-- Indexes for table `kuis_pilihan`
--
ALTER TABLE `kuis_pilihan`
  ADD PRIMARY KEY (`pilihan_id`),
  ADD KEY `soal_id` (`soal_id`);

--
-- Indexes for table `kuis_soal`
--
ALTER TABLE `kuis_soal`
  ADD PRIMARY KEY (`kuis_id`,`soal_id`),
  ADD KEY `soal_id` (`soal_id`);

--
-- Indexes for table `learning_statistics`
--
ALTER TABLE `learning_statistics`
  ADD PRIMARY KEY (`stat_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `materi_id` (`materi_id`);

--
-- Indexes for table `materi`
--
ALTER TABLE `materi`
  ADD PRIMARY KEY (`materi_id`),
  ADD KEY `kelas_id` (`kelas_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `nilai_kuis`
--
ALTER TABLE `nilai_kuis`
  ADD PRIMARY KEY (`nilai_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `kuis_id` (`kuis_id`);

--
-- Indexes for table `progress_siswa`
--
ALTER TABLE `progress_siswa`
  ADD PRIMARY KEY (`progress_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `materi_id` (`materi_id`);

--
-- Indexes for table `reset_password`
--
ALTER TABLE `reset_password`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `soal`
--
ALTER TABLE `soal`
  ADD PRIMARY KEY (`soal_id`),
  ADD KEY `materi_id` (`materi_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_logs`
--
ALTER TABLE `user_logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `forum_diskusi`
--
ALTER TABLE `forum_diskusi`
  MODIFY `diskusi_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `forum_komentar`
--
ALTER TABLE `forum_komentar`
  MODIFY `komentar_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `forum_topik`
--
ALTER TABLE `forum_topik`
  MODIFY `topik_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kelas`
--
ALTER TABLE `kelas`
  MODIFY `kelas_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `komentar_forum`
--
ALTER TABLE `komentar_forum`
  MODIFY `komentar_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kuis`
--
ALTER TABLE `kuis`
  MODIFY `kuis_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kuis_hasil`
--
ALTER TABLE `kuis_hasil`
  MODIFY `hasil_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kuis_jawaban`
--
ALTER TABLE `kuis_jawaban`
  MODIFY `jawaban_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kuis_pilihan`
--
ALTER TABLE `kuis_pilihan`
  MODIFY `pilihan_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `learning_statistics`
--
ALTER TABLE `learning_statistics`
  MODIFY `stat_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `materi`
--
ALTER TABLE `materi`
  MODIFY `materi_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `nilai_kuis`
--
ALTER TABLE `nilai_kuis`
  MODIFY `nilai_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `progress_siswa`
--
ALTER TABLE `progress_siswa`
  MODIFY `progress_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reset_password`
--
ALTER TABLE `reset_password`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `soal`
--
ALTER TABLE `soal`
  MODIFY `soal_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user_logs`
--
ALTER TABLE `user_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `forum_diskusi`
--
ALTER TABLE `forum_diskusi`
  ADD CONSTRAINT `forum_diskusi_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `forum_diskusi_ibfk_2` FOREIGN KEY (`materi_id`) REFERENCES `materi` (`materi_id`);

--
-- Constraints for table `forum_komentar`
--
ALTER TABLE `forum_komentar`
  ADD CONSTRAINT `forum_komentar_ibfk_1` FOREIGN KEY (`topik_id`) REFERENCES `forum_topik` (`topik_id`),
  ADD CONSTRAINT `forum_komentar_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `forum_komentar_ibfk_3` FOREIGN KEY (`parent_id`) REFERENCES `forum_komentar` (`komentar_id`);

--
-- Constraints for table `forum_topik`
--
ALTER TABLE `forum_topik`
  ADD CONSTRAINT `forum_topik_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `kelas`
--
ALTER TABLE `kelas`
  ADD CONSTRAINT `kelas_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `komentar_forum`
--
ALTER TABLE `komentar_forum`
  ADD CONSTRAINT `komentar_forum_ibfk_1` FOREIGN KEY (`diskusi_id`) REFERENCES `forum_diskusi` (`diskusi_id`),
  ADD CONSTRAINT `komentar_forum_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `kuis`
--
ALTER TABLE `kuis`
  ADD CONSTRAINT `kuis_ibfk_1` FOREIGN KEY (`materi_id`) REFERENCES `materi` (`materi_id`),
  ADD CONSTRAINT `kuis_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `kuis_ibfk_3` FOREIGN KEY (`created_by`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `kuis_hasil`
--
ALTER TABLE `kuis_hasil`
  ADD CONSTRAINT `kuis_hasil_ibfk_1` FOREIGN KEY (`kuis_id`) REFERENCES `kuis` (`kuis_id`),
  ADD CONSTRAINT `kuis_hasil_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `kuis_jawaban`
--
ALTER TABLE `kuis_jawaban`
  ADD CONSTRAINT `kuis_jawaban_ibfk_1` FOREIGN KEY (`hasil_id`) REFERENCES `kuis_hasil` (`hasil_id`),
  ADD CONSTRAINT `kuis_jawaban_ibfk_2` FOREIGN KEY (`soal_id`) REFERENCES `kuis_soal` (`soal_id`),
  ADD CONSTRAINT `kuis_jawaban_ibfk_3` FOREIGN KEY (`pilihan_id`) REFERENCES `kuis_pilihan` (`pilihan_id`);

--
-- Constraints for table `kuis_pilihan`
--
ALTER TABLE `kuis_pilihan`
  ADD CONSTRAINT `kuis_pilihan_ibfk_1` FOREIGN KEY (`soal_id`) REFERENCES `kuis_soal` (`soal_id`);

--
-- Constraints for table `kuis_soal`
--
ALTER TABLE `kuis_soal`
  ADD CONSTRAINT `kuis_soal_ibfk_1` FOREIGN KEY (`kuis_id`) REFERENCES `kuis` (`kuis_id`),
  ADD CONSTRAINT `kuis_soal_ibfk_2` FOREIGN KEY (`soal_id`) REFERENCES `soal` (`soal_id`),
  ADD CONSTRAINT `kuis_soal_ibfk_3` FOREIGN KEY (`kuis_id`) REFERENCES `kuis` (`kuis_id`);

--
-- Constraints for table `learning_statistics`
--
ALTER TABLE `learning_statistics`
  ADD CONSTRAINT `learning_statistics_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `learning_statistics_ibfk_2` FOREIGN KEY (`materi_id`) REFERENCES `materi` (`materi_id`);

--
-- Constraints for table `materi`
--
ALTER TABLE `materi`
  ADD CONSTRAINT `materi_ibfk_1` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`kelas_id`),
  ADD CONSTRAINT `materi_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `nilai_kuis`
--
ALTER TABLE `nilai_kuis`
  ADD CONSTRAINT `nilai_kuis_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `nilai_kuis_ibfk_2` FOREIGN KEY (`kuis_id`) REFERENCES `kuis` (`kuis_id`);

--
-- Constraints for table `progress_siswa`
--
ALTER TABLE `progress_siswa`
  ADD CONSTRAINT `progress_siswa_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `progress_siswa_ibfk_2` FOREIGN KEY (`materi_id`) REFERENCES `materi` (`materi_id`);

--
-- Constraints for table `reset_password`
--
ALTER TABLE `reset_password`
  ADD CONSTRAINT `reset_password_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `soal`
--
ALTER TABLE `soal`
  ADD CONSTRAINT `soal_ibfk_1` FOREIGN KEY (`materi_id`) REFERENCES `materi` (`materi_id`),
  ADD CONSTRAINT `soal_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `user_logs`
--
ALTER TABLE `user_logs`
  ADD CONSTRAINT `user_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
