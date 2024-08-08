-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 26 Feb 2023 pada 00.07
-- Versi server: 10.4.22-MariaDB
-- Versi PHP: 8.1.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `absensi`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `activity` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `user_id`, `activity`, `description`, `created_at`, `updated_at`) VALUES
(1, 1, 'create', 'Menambahkan data karyawan baru sfsdf', '2023-02-20 09:45:57', '2023-02-20 09:45:57'),
(2, 1, 'create', 'Menambahkan data karyawan baru asdad', '2023-02-20 10:00:46', '2023-02-20 10:00:46'),
(3, 1, 'tambah', 'Menambahkan data dokumen karyawan dengan nama dokumen SIP', '2023-02-20 10:16:33', '2023-02-20 10:16:33'),
(4, 1, 'edit', 'Mengedit data dokumen karyawan dengan nama dokumen SIP', '2023-02-20 10:17:20', '2023-02-20 10:17:20'),
(5, 1, 'hapus', 'Menghapus data dokumen karyawan dengan nama dokumen SIP', '2023-02-20 10:17:28', '2023-02-20 10:17:28'),
(6, 1, 'delete', 'Menghapus data karyawan asdad', '2023-02-20 10:33:31', '2023-02-20 10:33:31'),
(7, 1, 'create', 'Menambahkan data karyawan baru Jago Software', '2023-02-20 10:45:07', '2023-02-20 10:45:07'),
(8, 6, 'tambah', 'Menambahkan data cuti baru dengan nama cuti Cuti Dadakan', '2023-02-20 17:48:33', '2023-02-20 17:48:33'),
(9, 1, 'create', 'Menambahkan shift karyawan ', '2023-02-20 17:49:22', '2023-02-20 17:49:22'),
(10, 1, 'create', 'Menambahkan shift karyawan ', '2023-02-20 17:54:35', '2023-02-20 17:54:35'),
(11, 1, 'create', 'Menambahkan shift karyawan ', '2023-02-20 18:29:03', '2023-02-20 18:29:03'),
(12, 1, 'update', 'Mengubah password karyawan ', '2023-02-20 11:29:17', '2023-02-20 11:29:17'),
(13, 1, 'create', 'Menambahkan shift karyawan ', '2023-02-25 00:44:40', '2023-02-25 00:44:40'),
(14, 1, 'update', 'Mengubah password karyawan ', '2023-02-24 23:30:42', '2023-02-24 23:30:42'),
(15, 1, 'update', 'Mengubah password karyawan ', '2023-02-24 23:32:45', '2023-02-24 23:32:45'),
(16, 1, 'create', 'Menambahkan shift karyawan ', '2023-02-25 07:03:21', '2023-02-25 07:03:21'),
(17, 1, 'create', 'Menambahkan shift karyawan ', '2023-02-25 07:03:36', '2023-02-25 07:03:36'),
(18, 1, 'tambah', 'Absen Masuk Pada Tanggal 2023-02-25', '2023-02-25 07:04:00', '2023-02-25 07:04:00'),
(19, 3, 'tambah', 'Absen Masuk Pada Tanggal 2023-02-25', '2023-02-25 07:06:50', '2023-02-25 07:06:50'),
(20, 3, 'tambah', 'Absen Pulang Pada Tanggal 2023-02-25', '2023-02-25 07:07:10', '2023-02-25 07:07:10'),
(21, 3, 'tambah', 'Menambahkan data cuti baru dengan nama cuti Cuti Dadakan', '2023-02-25 07:07:57', '2023-02-25 07:07:57'),
(22, 1, 'edit', 'Mengubah data cuti dengan id 96 oleh Admin', '2023-02-25 00:08:41', '2023-02-25 00:08:41'),
(23, 1, 'edit', 'Mengubah data cuti dengan id 95 oleh Admin', '2023-02-25 00:08:51', '2023-02-25 00:08:51'),
(24, 1, 'update', 'Mengubah data lokasi kantor', '2023-02-25 14:52:26', '2023-02-25 14:52:26'),
(25, 1, 'tambah', 'Absen Pulang Pada Tanggal 2023-02-25', '2023-02-25 21:52:49', '2023-02-25 21:52:49'),
(26, 1, 'create', 'Absen Lembur Masuk', '2023-02-25 21:53:15', '2023-02-25 21:53:15'),
(27, 1, 'create', 'Menambahkan shift karyawan ', '2023-02-25 21:56:32', '2023-02-25 21:56:32'),
(28, 1, 'update', 'Mengubah password karyawan ', '2023-02-25 14:56:47', '2023-02-25 14:56:47'),
(29, 2, 'tambah', 'Absen Masuk Pada Tanggal 2023-02-26', '2023-02-25 21:57:19', '2023-02-25 21:57:19'),
(30, 2, 'tambah', 'Absen Pulang Pada Tanggal 2023-02-26', '2023-02-25 21:57:39', '2023-02-25 21:57:39'),
(31, 2, 'tambah', 'Menambahkan data cuti baru dengan nama cuti Cuti Bersama', '2023-02-25 21:58:24', '2023-02-25 21:58:24'),
(32, 1, 'update', 'Mengubah data karyawan User', '2023-02-25 14:59:20', '2023-02-25 14:59:20'),
(33, 1, 'update', 'Mengubah data lokasi kantor', '2023-02-25 22:26:24', '2023-02-25 22:26:24'),
(34, 1, 'create', 'Menambahkan shift karyawan ', '2023-02-25 22:28:28', '2023-02-25 22:28:28'),
(35, 3, 'tambah', 'Absen Masuk Pada Tanggal 2023-02-26', '2023-02-25 22:28:43', '2023-02-25 22:28:43'),
(36, 3, 'tambah', 'Absen Pulang Pada Tanggal 2023-02-26', '2023-02-25 22:29:02', '2023-02-25 22:29:02'),
(37, 3, 'tambah', 'Menambahkan data cuti baru dengan nama cuti Cuti Dadakan', '2023-02-25 22:32:32', '2023-02-25 22:32:32'),
(38, 1, 'edit', 'Mengubah data cuti dengan id 1 oleh Admin', '2023-02-25 22:33:17', '2023-02-25 22:33:17');

-- --------------------------------------------------------

--
-- Struktur dari tabel `cutis`
--

CREATE TABLE `cutis` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `nama_cuti` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alasan_cuti` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `foto_cuti` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_cuti` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `catatan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `cutis`
--

INSERT INTO `cutis` (`id`, `user_id`, `nama_cuti`, `tanggal`, `alasan_cuti`, `foto_cuti`, `status_cuti`, `catatan`, `created_at`, `updated_at`) VALUES
(1, 3, 'Cuti Dadakan', '2023-02-27', 'cuti menghadiri pernikahan', 'foto_cuti/dRByAutG8LarkjbFbMFFhvFgejMjOn50KR7BJ7G7.jpg', 'Diterima', 'ok', '2023-02-25 22:32:32', '2023-02-25 22:33:17');

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jabatans`
--

CREATE TABLE `jabatans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_jabatan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `jabatans`
--

INSERT INTO `jabatans` (`id`, `nama_jabatan`, `created_at`, `updated_at`) VALUES
(1, 'Teknologi Informasi', '2022-12-27 08:23:58', '2022-12-27 08:23:58'),
(2, 'Medik dan Keperawatan', '2022-12-27 08:23:59', '2022-12-27 08:23:59'),
(3, 'Keuangan dan Akutansi', '2022-12-27 08:23:59', '2022-12-27 08:23:59'),
(4, 'Administrasi & Umum', '2022-12-27 08:23:59', '2022-12-27 08:23:59'),
(5, 'Humas & Pemasaran', '2022-12-27 08:23:59', '2022-12-27 08:23:59'),
(6, 'Sekretariat', '2022-12-27 08:23:59', '2022-12-27 08:23:59'),
(7, 'PT. Permata Husada Sakti', '2022-12-27 08:23:59', '2022-12-27 08:23:59'),
(8, 'Dokter Full Timer', '2022-12-27 08:23:59', '2022-12-27 08:23:59'),
(9, 'Casemix', '2022-12-27 08:24:00', '2022-12-27 08:24:00'),
(10, 'Direktur', '2022-12-27 08:24:00', '2022-12-27 08:24:00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `lemburs`
--

CREATE TABLE `lemburs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `tanggal` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jam_masuk` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lat_masuk` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `long_masuk` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jarak_masuk` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `foto_jam_masuk` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jam_keluar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lat_keluar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `long_keluar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jarak_keluar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `foto_jam_keluar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_lembur` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `lemburs`
--

INSERT INTO `lemburs` (`id`, `user_id`, `tanggal`, `jam_masuk`, `lat_masuk`, `long_masuk`, `jarak_masuk`, `foto_jam_masuk`, `jam_keluar`, `lat_keluar`, `long_keluar`, `jarak_keluar`, `foto_jam_keluar`, `total_lembur`, `created_at`, `updated_at`) VALUES
(1, 3, '2023-01-24', '2023-01-24 22:59', '-7.4213083', '110.9731847', '0', 'foto_jam_masuk_lembur/63d0007c07837.png', '2023-01-24 23:00', '-7.4213083', '110.9731847', '0', 'foto_jam_keluar_lembur/63d0008ba5cf1.png', '60', '2023-01-24 15:59:56', '2023-01-24 16:00:11'),
(2, 1, '2023-02-26', '2023-02-26 04:53', '-7.4213053', '110.9731921', '0', 'foto_jam_masuk_lembur/63fa834b877b7.png', NULL, NULL, NULL, NULL, NULL, NULL, '2023-02-25 21:53:15', '2023-02-25 21:53:15');

-- --------------------------------------------------------

--
-- Struktur dari tabel `lokasis`
--

CREATE TABLE `lokasis` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `lat_kantor` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `long_kantor` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `radius` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `lokasis`
--

INSERT INTO `lokasis` (`id`, `lat_kantor`, `long_kantor`, `radius`, `created_at`, `updated_at`) VALUES
(1, '-7.4213053', '110.9731921', '2000', '2022-12-27 08:24:01', '2023-02-25 22:26:24');

-- --------------------------------------------------------

--
-- Struktur dari tabel `mapping_shifts`
--

CREATE TABLE `mapping_shifts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `shift_id` bigint(20) UNSIGNED NOT NULL,
  `tanggal` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jam_absen` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telat` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lat_absen` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `long_absen` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jarak_masuk` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `foto_jam_absen` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jam_pulang` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pulang_cepat` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `foto_jam_pulang` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lat_pulang` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `long_pulang` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jarak_pulang` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_absen` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `mapping_shifts`
--

INSERT INTO `mapping_shifts` (`id`, `user_id`, `shift_id`, `tanggal`, `jam_absen`, `telat`, `lat_absen`, `long_absen`, `jarak_masuk`, `foto_jam_absen`, `jam_pulang`, `pulang_cepat`, `foto_jam_pulang`, `lat_pulang`, `long_pulang`, `jarak_pulang`, `status_absen`, `created_at`, `updated_at`) VALUES
(1, 3, 4, '2023-02-26', '05:28', '0', '-7.4213053', '110.9731921', '0', 'foto_jam_absen/63fa8b9b9f296.png', '05:28', '91920', 'foto_jam_pulang/63fa8baded521.png', '-7.4213053', '110.9731921', '0', 'Masuk', '2023-02-25 22:28:28', '2023-02-25 22:29:01'),
(2, 3, 4, '2023-02-27', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Cuti', '2023-02-25 22:28:28', '2023-02-25 22:33:17');

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2022_09_16_095447_create_shifts_table', 1),
(6, '2022_09_19_032649_create_mapping_shifts_table', 1),
(7, '2022_09_20_074944_create_lemburs_table', 1),
(8, '2022_09_20_092230_create_cutis_table', 1),
(9, '2022_10_31_083510_create_lokasis_table', 1),
(10, '2022_11_02_061554_create_reset_cutis_table', 1),
(11, '2022_12_01_041742_create_sips_table', 1),
(12, '2022_12_14_080034_create_jabatans_table', 1),
(13, '2023_02_20_161543_create_activity_logs_table', 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `reset_cutis`
--

CREATE TABLE `reset_cutis` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `cuti_dadakan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cuti_bersama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cuti_menikah` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cuti_diluar_tanggungan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cuti_khusus` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cuti_melahirkan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `izin_telat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `izin_pulang_cepat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `reset_cutis`
--

INSERT INTO `reset_cutis` (`id`, `cuti_dadakan`, `cuti_bersama`, `cuti_menikah`, `cuti_diluar_tanggungan`, `cuti_khusus`, `cuti_melahirkan`, `izin_telat`, `izin_pulang_cepat`, `created_at`, `updated_at`) VALUES
(1, '10', '10', '10', '10', '10', '10', '10', '10', '2022-12-27 08:24:01', '2023-02-20 09:36:52');

-- --------------------------------------------------------

--
-- Struktur dari tabel `shifts`
--

CREATE TABLE `shifts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_shift` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jam_masuk` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jam_keluar` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `shifts`
--

INSERT INTO `shifts` (`id`, `nama_shift`, `jam_masuk`, `jam_keluar`, `created_at`, `updated_at`) VALUES
(1, 'Libur', '00:00', '00:00', '2022-12-27 08:24:00', '2022-12-27 08:24:00'),
(2, 'Office', '08:00', '17:00', '2022-12-27 08:24:01', '2022-12-27 08:24:01'),
(3, 'Siang', '13:00', '21:00', '2022-12-27 08:24:01', '2022-12-27 08:24:01'),
(4, 'Malam', '21:00', '07:00', '2022-12-27 08:24:01', '2023-02-20 09:38:33');

-- --------------------------------------------------------

--
-- Struktur dari tabel `sips`
--

CREATE TABLE `sips` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `nama_dokumen` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_berakhir` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `foto_karyawan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telepon` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tgl_lahir` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gender` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tgl_join` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_nikah` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `cuti_dadakan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cuti_bersama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cuti_menikah` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cuti_diluar_tanggungan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cuti_khusus` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cuti_melahirkan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `izin_telat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `izin_pulang_cepat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_admin` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jabatan_id` bigint(20) UNSIGNED NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `name`, `foto_karyawan`, `email`, `telepon`, `username`, `password`, `tgl_lahir`, `gender`, `tgl_join`, `status_nikah`, `alamat`, `cuti_dadakan`, `cuti_bersama`, `cuti_menikah`, `cuti_diluar_tanggungan`, `cuti_khusus`, `cuti_melahirkan`, `izin_telat`, `izin_pulang_cepat`, `is_admin`, `jabatan_id`, `email_verified_at`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin', '', 'admin@gmail.com', '0987654321', 'jagosoftware', '$2y$10$tGasCjWAOM2Wn/O8CGSvsOgbvdrV6BGTxuAa3CxaOSZKVh8ivpaye', '2022-12-27', 'Laki-Laki', '2022-12-27', 'Menikah', 'alamat admin', '12', '5', '2', '10', '8', '6', '16', '9', 'admin', 1, NULL, NULL, '2022-12-27 08:23:58', '2023-02-24 23:30:42'),
(2, 'User', '', 'user@gmail.com', '123456789', 'user', '$2y$10$/GVqWE1qXgpsXbVAYjnB4.4MT8b1c8mbfPSRLFdusB6Neqw9naBmC', '2022-12-27', 'Laki-Laki', '2023-02-26', 'Menikah', 'alamat user', '12', '5', '2', '10', '8', '6', '16', '9', 'user', 2, NULL, NULL, '2022-12-27 08:23:58', '2023-02-25 14:59:20'),
(3, 'Nasrul Kurniawan', NULL, 'nasrulkurniawan@gmail.com', '082228412275', 'nasrul', '$2y$10$rTMgr.WrWzkQ27KXaTkqleGY/dhm9KDf8McTpQ4AH9EWISdtTNUoi', '1988-07-28', 'Laki-Laki', '2022-02-26', 'Menikah', 'Perum Griya Sidoharjo Asri Blok K7,Singopadu,Sidoharjo,Sragen', '10', '10', '10', '10', '10', '10', '10', '10', 'user', 6, NULL, 'IvWV2QT7BKLWx0U6SjNYzACuBW56l5NFF3qrI3PSs0GZqxxtqzJtvVy3jXYR', '2023-01-24 08:53:37', '2023-02-25 22:34:01'),
(6, 'Jago Software', 'foto_karyawan/HRcfSje4sHdmzVR10BQKNcbnztM0ulKBBJWjo7eq.png', 'shopeecoid-jagosoftware@gmail.com', '082228412275', 'jagosoftware', '$2y$10$GuXnBseSASrAK5xrhA7Mx.dOPT/rzwZEc/lDrzDib1f7F6.irxkDW', '2006-02-20', 'Laki-Laki', '2023-02-26', 'Lajang', 'Sragen,Jawa Tengah', '7', '10', '10', '10', '10', '10', '10', '10', 'user', 10, NULL, NULL, '2023-02-20 10:45:07', '2023-02-20 11:13:53');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activity_logs_user_id_foreign` (`user_id`);

--
-- Indeks untuk tabel `cutis`
--
ALTER TABLE `cutis`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indeks untuk tabel `jabatans`
--
ALTER TABLE `jabatans`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `lemburs`
--
ALTER TABLE `lemburs`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `lokasis`
--
ALTER TABLE `lokasis`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `mapping_shifts`
--
ALTER TABLE `mapping_shifts`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indeks untuk tabel `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indeks untuk tabel `reset_cutis`
--
ALTER TABLE `reset_cutis`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `shifts`
--
ALTER TABLE `shifts`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `sips`
--
ALTER TABLE `sips`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT untuk tabel `cutis`
--
ALTER TABLE `cutis`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jabatans`
--
ALTER TABLE `jabatans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `lemburs`
--
ALTER TABLE `lemburs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `lokasis`
--
ALTER TABLE `lokasis`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `mapping_shifts`
--
ALTER TABLE `mapping_shifts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT untuk tabel `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `reset_cutis`
--
ALTER TABLE `reset_cutis`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `shifts`
--
ALTER TABLE `shifts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `sips`
--
ALTER TABLE `sips`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
