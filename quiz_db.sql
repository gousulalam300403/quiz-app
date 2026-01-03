-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 03 Jan 2026 pada 03.40
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `quiz_db`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `questions`
--

CREATE TABLE `questions` (
  `id` int(11) NOT NULL,
  `quiz_id` int(11) DEFAULT NULL,
  `pertanyaan` text NOT NULL,
  `opsi_a` varchar(500) DEFAULT NULL,
  `opsi_b` varchar(500) DEFAULT NULL,
  `opsi_c` varchar(500) DEFAULT NULL,
  `opsi_d` varchar(500) DEFAULT NULL,
  `jawaban_benar` enum('a','b','c','d') NOT NULL,
  `poin` int(11) DEFAULT 10
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `questions`
--

INSERT INTO `questions` (`id`, `quiz_id`, `pertanyaan`, `opsi_a`, `opsi_b`, `opsi_c`, `opsi_d`, `jawaban_benar`, `poin`) VALUES
(1, 1, 'Berapakah hasil dari 7 + 8 x 2?', '22', '30', '15', '23', 'd', 10),
(2, 1, 'Berapakah akar kuadrat dari 144?', '11', '12', '13', '14', 'b', 10),
(3, 1, 'Berapakah 25% dari 200?', '25', '50', '75', '100', 'b', 10),
(4, 1, 'Jika x = 5, berapakah nilai dari 2x² - 3x + 7?', '42', '32', '52', '62', 'a', 10),
(5, 1, 'Berapakah keliling persegi dengan sisi 8 cm?', '16 cm', '24 cm', '32 cm', '64 cm', 'c', 10),
(6, 2, 'Siapa presiden pertama Indonesia?', 'Soeharto', 'B.J. Habibie', 'Soekarno', 'Megawati', 'c', 10),
(7, 2, 'Kapan Indonesia merdeka?', '17 Agustus 1945', '27 Desember 1949', '1 Juni 1945', '28 Oktober 1928', 'a', 10),
(8, 2, 'Sumpah Pemuda diikrarkan pada tanggal?', '20 Mei 1908', '28 Oktober 1928', '10 November 1945', '17 Agustus 1945', 'b', 10),
(9, 2, 'Siapa pencipta lagu Indonesia Raya?', 'W.R. Supratman', 'C. Simanjuntak', 'Ismail Marzuki', 'Ibu Sud', 'a', 10),
(10, 2, 'Peristiwa Bandung Lautan Api terjadi pada tahun?', '1945', '1946', '1947', '1948', 'b', 10);

-- --------------------------------------------------------

--
-- Struktur dari tabel `quizzes`
--

CREATE TABLE `quizzes` (
  `id` int(11) NOT NULL,
  `judul` varchar(200) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `durasi` int(11) DEFAULT 60,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `quizzes`
--

INSERT INTO `quizzes` (`id`, `judul`, `deskripsi`, `durasi`, `created_by`, `created_at`) VALUES
(1, 'Matematika Dasar', 'Kuis matematika dasar untuk pemula', 30, 1, '2026-01-03 02:31:25'),
(2, 'Sejarah Indonesia', 'Kuis tentang sejarah Indonesia dari masa ke masa', 45, 1, '2026-01-03 02:31:25'),
(3, 'Bahasa Inggris', 'Kuis grammar dan vocabulary bahasa Inggris', 40, 1, '2026-01-03 02:31:25'),
(4, 'Teknologi Informasi', 'Kuis tentang dasar-dasar teknologi informasi', 50, 1, '2026-01-03 02:31:25');

-- --------------------------------------------------------

--
-- Struktur dari tabel `results`
--

CREATE TABLE `results` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `quiz_id` int(11) DEFAULT NULL,
  `score` int(11) DEFAULT NULL,
  `total_questions` int(11) DEFAULT NULL,
  `correct_answers` int(11) DEFAULT NULL,
  `waktu_pengerjaan` int(11) DEFAULT NULL,
  `completed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `results`
--

INSERT INTO `results` (`id`, `user_id`, `quiz_id`, `score`, `total_questions`, `correct_answers`, `waktu_pengerjaan`, `completed_at`) VALUES
(1, 2, 1, 40, 5, 4, 1200, '2026-01-03 02:31:25'),
(2, 2, 2, 30, 5, 3, 1800, '2026-01-03 02:31:25'),
(3, 3, 1, 50, 5, 5, 900, '2026-01-03 02:31:25'),
(4, 3, 2, 20, 5, 2, 1500, '2026-01-03 02:31:25'),
(5, 4, 1, 30, 5, 3, 1100, '2026-01-03 02:31:25');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `role` enum('admin','peserta','pimpinan') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `nama_lengkap`, `email`, `role`, `created_at`) VALUES
(1, 'admin', '$2y$10$YourHashedPasswordHere', 'Administrator', 'admin@quiz.com', 'admin', '2026-01-03 02:05:11'),
(2, 'peserta1', '$2y$10$YourHashedPasswordHere', 'John Doe', 'john@example.com', 'peserta', '2026-01-03 02:05:11'),
(3, 'pimpinan1', '$2y$10$YourHashedPasswordHere', 'Jane Smith', 'jane@example.com', 'pimpinan', '2026-01-03 02:05:11'),
(4, 'peserta2', '$2y$10$demo', 'Budi Santoso', 'budi@example.com', 'peserta', '2026-01-03 02:31:25'),
(5, 'peserta3', '$2y$10$demo', 'Siti Rahayu', 'siti@example.com', 'peserta', '2026-01-03 02:31:25'),
(6, 'peserta4', '$2y$10$demo', 'Ahmad Fauzi', 'ahmad@example.com', 'peserta', '2026-01-03 02:31:25'),
(7, 'pimpinan2', '$2y$10$demo', 'Robert Johnson', 'robert@example.com', 'pimpinan', '2026-01-03 02:31:25');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quiz_id` (`quiz_id`);

--
-- Indeks untuk tabel `quizzes`
--
ALTER TABLE `quizzes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indeks untuk tabel `results`
--
ALTER TABLE `results`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `quiz_id` (`quiz_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `quizzes`
--
ALTER TABLE `quizzes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `results`
--
ALTER TABLE `results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `quizzes`
--
ALTER TABLE `quizzes`
  ADD CONSTRAINT `quizzes_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Ketidakleluasaan untuk tabel `results`
--
ALTER TABLE `results`
  ADD CONSTRAINT `results_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `results_ibfk_2` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
