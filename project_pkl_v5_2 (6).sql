-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 16 Jun 2026 pada 14.12
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
-- Database: `project_pkl_v5.2`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `absensi_pkl`
--

CREATE TABLE `absensi_pkl` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `pengajuan_pkl_id` bigint(20) UNSIGNED NOT NULL,
  `tanggal` date NOT NULL,
  `jam_masuk` time NOT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `foto_selfie` varchar(255) NOT NULL,
  `status` enum('hadir','terlambat') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `absensi_pkl`
--

INSERT INTO `absensi_pkl` (`id`, `pengajuan_pkl_id`, `tanggal`, `jam_masuk`, `latitude`, `longitude`, `foto_selfie`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, '2026-06-09', '07:54:10', NULL, NULL, 'absensi/selfie_6a2756220a793.jpg', 'hadir', '2026-06-08 23:54:10', '2026-06-08 23:54:10'),
(2, 2, '2026-06-10', '13:45:24', NULL, NULL, 'absensi/selfie_6a28f9f43f85f.jpg', 'terlambat', '2026-06-10 05:45:24', '2026-06-10 05:45:24');

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `guru`
--

CREATE TABLE `guru` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `nip` varchar(255) NOT NULL,
  `alamat` text DEFAULT NULL,
  `no_hp` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `guru`
--

INSERT INTO `guru` (`id`, `user_id`, `nip`, `alamat`, `no_hp`, `created_at`, `updated_at`) VALUES
(1, 2, '198001012010011001', 'Jl. Contoh No. 1', '081111111111', '2026-06-08 06:23:57', '2026-06-08 06:23:57'),
(2, 3, '198001012010011002', 'Jl. Contoh No. 2', '082222222222', '2026-06-08 06:23:58', '2026-06-08 06:23:58');

-- --------------------------------------------------------

--
-- Struktur dari tabel `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jurnal_pkl`
--

CREATE TABLE `jurnal_pkl` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `pengajuan_pkl_id` bigint(20) UNSIGNED NOT NULL,
  `tanggal` date NOT NULL,
  `kegiatan` text NOT NULL,
  `kendala` text DEFAULT NULL,
  `dokumentasi` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'menunggu_validasi',
  `catatan_guru` text DEFAULT NULL,
  `catatan_pembimbing` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `jurnal_pkl`
--

INSERT INTO `jurnal_pkl` (`id`, `pengajuan_pkl_id`, `tanggal`, `kegiatan`, `kendala`, `dokumentasi`, `status`, `catatan_guru`, `catatan_pembimbing`, `created_at`, `updated_at`) VALUES
(1, 1, '2026-06-09', 'tess', 'aman aja', 'jurnal/IfTQTxPGYws9kBiwcy3PYku2elu0fgvB0MyiiDAp.jpg', 'valid', 'ok', 'ok', '2026-06-08 23:55:09', '2026-06-09 00:15:48'),
(3, 2, '2026-06-10', 'tes', 'yy', 'jurnal/HzmWuHKn5WpIjW6gZkGcTZpfh7hGS9v1i5Gnvnqo.pdf', 'valid', '-', NULL, '2026-06-10 05:39:00', '2026-06-10 05:40:56');

-- --------------------------------------------------------

--
-- Struktur dari tabel `laporan_pkl`
--

CREATE TABLE `laporan_pkl` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `pengajuan_pkl_id` bigint(20) UNSIGNED NOT NULL,
  `file_laporan` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'menunggu_review',
  `catatan_guru` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `laporan_pkl`
--

INSERT INTO `laporan_pkl` (`id`, `pengajuan_pkl_id`, `file_laporan`, `status`, `catatan_guru`, `created_at`, `updated_at`) VALUES
(1, 1, 'laporan/tymHetohIupUvkENm0kVf4OLehB7itLesA0T9plE.pdf', 'diterima', 'ok', '2026-06-09 00:17:05', '2026-06-09 00:18:19'),
(2, 2, 'laporan/LohRbvqemhZdE9zhExOc4FynIkqy1e7sikQ8rFGP.pdf', 'diterima', 'yy', '2026-06-10 05:48:14', '2026-06-10 05:49:10');

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_05_14_000001_create_siswa_table', 1),
(5, '2026_05_14_000002_create_guru_table', 1),
(6, '2026_05_14_000003_create_tempat_pkl_table', 1),
(7, '2026_05_14_000004_create_pengajuan_pkl_table', 1),
(8, '2026_05_14_000005_create_jurnal_pkl_table', 1),
(9, '2026_05_14_000006_create_laporan_pkl_table', 1),
(10, '2026_05_14_000007_create_penilaian_pkl_table', 1),
(11, '2026_05_15_113702_add_avatar_to_users_table', 1),
(12, '2026_05_17_085532_add_is_approved_to_users_table', 1),
(13, '2026_05_21_142701_create_notifications_table', 1),
(14, '2026_05_21_144333_create_pembimbing_industri_table', 1),
(15, '2026_05_21_150000_create_absensi_pkl_table', 1),
(21, '2026_05_22_000001_add_catatan_pembimbing_to_jurnal_pkl_table', 2),
(22, '2026_06_13_161701_modify_penilaian_pkl_table', 3),
(23, '2026_06_16_151746_add_tanda_tangan_to_pembimbing_industri_table', 4),
(24, '2026_06_16_171723_add_logo_to_pembimbing_industri_table', 5);

-- --------------------------------------------------------

--
-- Struktur dari tabel `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) NOT NULL,
  `type` varchar(255) NOT NULL,
  `notifiable_type` varchar(255) NOT NULL,
  `notifiable_id` bigint(20) UNSIGNED NOT NULL,
  `data` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `notifications`
--

INSERT INTO `notifications` (`id`, `type`, `notifiable_type`, `notifiable_id`, `data`, `read_at`, `created_at`, `updated_at`) VALUES
('0532b9ac-aabb-4510-98f0-936037ed0d4b', 'App\\Notifications\\JurnalPklDiperbarui', 'App\\Models\\User', 5, '{\"jurnal_id\":3,\"tanggal\":\"2026-06-10\",\"status\":\"valid\",\"message\":\"Jurnal PKL Anda pada tanggal 10 June 2026 telah divalidasi.\",\"title\":\"Jurnal PKL divalidasi\"}', NULL, '2026-06-10 05:40:56', '2026-06-10 05:40:56'),
('07adbba3-9c38-4ca3-ae45-74e5018ed0e9', 'App\\Notifications\\LaporanPklDiperbarui', 'App\\Models\\User', 5, '{\"laporan_id\":2,\"pengajuan_id\":2,\"status\":\"diterima\",\"message\":\"Laporan akhir PKL Anda telah diterima.\",\"title\":\"Laporan PKL diterima\"}', NULL, '2026-06-10 05:49:11', '2026-06-10 05:49:11'),
('2b7cc6c8-758e-472c-94f3-19f04c5b350f', 'App\\Notifications\\SiswaUploadLaporan', 'App\\Models\\User', 2, '{\"laporan_id\":2,\"siswa_name\":\"Siswa 2\",\"pengajuan_id\":2,\"message\":\"Siswa bimbingan Anda, Siswa 2, telah mengunggah laporan akhir PKL.\",\"title\":\"Laporan Akhir Diunggah\"}', NULL, '2026-06-10 05:48:14', '2026-06-10 05:48:14'),
('6876bb30-2c92-4084-bbfd-effaf042f3f6', 'App\\Notifications\\JurnalPklDiperbarui', 'App\\Models\\User', 4, '{\"jurnal_id\":1,\"tanggal\":\"2026-06-09\",\"status\":\"valid\",\"message\":\"Jurnal PKL Anda pada tanggal 09 June 2026 telah divalidasi.\",\"title\":\"Jurnal PKL divalidasi\"}', NULL, '2026-06-09 00:15:03', '2026-06-09 00:15:03'),
('875493db-9a17-400e-ab5d-2465719f1d46', 'App\\Notifications\\JurnalPklDiperbarui', 'App\\Models\\User', 4, '{\"jurnal_id\":1,\"tanggal\":\"2026-06-09\",\"status\":\"valid\",\"message\":\"Jurnal PKL Anda pada tanggal 09 June 2026 telah divalidasi.\",\"title\":\"Jurnal PKL divalidasi\"}', NULL, '2026-06-08 23:55:57', '2026-06-08 23:55:57'),
('9efb7ff2-6399-4073-8678-1677eaf001ae', 'App\\Notifications\\JurnalPklDiperbarui', 'App\\Models\\User', 4, '{\"jurnal_id\":1,\"tanggal\":\"2026-06-09\",\"status\":\"revisi\",\"message\":\"Jurnal PKL Anda pada tanggal 09 June 2026 telah diminta revisi.\",\"title\":\"Jurnal PKL diminta revisi\"}', NULL, '2026-06-09 00:09:49', '2026-06-09 00:09:49'),
('ba696b60-40fc-45c6-82b2-dd939d5c900e', 'App\\Notifications\\SiswaUploadJurnal', 'App\\Models\\User', 2, '{\"jurnal_id\":3,\"siswa_name\":\"Siswa 2\",\"tanggal\":\"2026-06-10\",\"kegiatan\":\"tes\",\"message\":\"Siswa bimbingan Anda, Siswa 2, telah mengunggah jurnal harian baru.\",\"title\":\"Jurnal Baru Diunggah\"}', NULL, '2026-06-10 05:39:00', '2026-06-10 05:39:00'),
('c5216ebd-c805-4fc4-91d6-212d31af24ec', 'App\\Notifications\\PengajuanPklStatusChanged', 'App\\Models\\User', 5, '{\"pengajuan_id\":2,\"tempat_pkl_nama\":\"PT Teknologi Maju\",\"status\":\"disetujui\",\"catatan\":null,\"message\":\"Status pengajuan PKL Anda di PT Teknologi Maju telah diperbarui menjadi Disetujui.\",\"title\":\"Pembaruan Pengajuan PKL\"}', NULL, '2026-06-10 05:37:08', '2026-06-10 05:37:08'),
('c668d956-ffb5-4314-a0e7-07286c479290', 'App\\Notifications\\JurnalPklDiperbarui', 'App\\Models\\User', 4, '{\"jurnal_id\":1,\"tanggal\":\"2026-06-09\",\"status\":\"valid\",\"message\":\"Jurnal PKL Anda pada tanggal 09 June 2026 telah divalidasi.\",\"title\":\"Jurnal PKL divalidasi\"}', NULL, '2026-06-09 00:15:48', '2026-06-09 00:15:48'),
('ca3ccd4a-47f3-40ab-a453-c84dfb5e6bd1', 'App\\Notifications\\SiswaUploadLaporan', 'App\\Models\\User', 2, '{\"laporan_id\":1,\"siswa_name\":\"Siswa 1\",\"pengajuan_id\":1,\"message\":\"Siswa bimbingan Anda, Siswa 1, telah mengunggah laporan akhir PKL.\",\"title\":\"Laporan Akhir Diunggah\"}', NULL, '2026-06-09 00:17:05', '2026-06-09 00:17:05'),
('dbc8d979-21bc-42f0-97ea-80c9f08b7862', 'App\\Notifications\\SiswaUploadJurnal', 'App\\Models\\User', 2, '{\"jurnal_id\":1,\"siswa_name\":\"Siswa 1\",\"tanggal\":\"2026-06-09\",\"kegiatan\":\"tess\",\"message\":\"Siswa bimbingan Anda, Siswa 1, telah mengunggah jurnal harian baru.\",\"title\":\"Jurnal Baru Diunggah\"}', NULL, '2026-06-08 23:55:09', '2026-06-08 23:55:09'),
('ef3b7e80-4880-45b1-9219-589fabf8b0d9', 'App\\Notifications\\LaporanPklDiperbarui', 'App\\Models\\User', 4, '{\"laporan_id\":1,\"pengajuan_id\":1,\"status\":\"diterima\",\"message\":\"Laporan akhir PKL Anda telah diterima.\",\"title\":\"Laporan PKL diterima\"}', NULL, '2026-06-09 00:18:19', '2026-06-09 00:18:19'),
('fbd7ed60-358a-4fe4-bba6-2be32a173566', 'App\\Notifications\\PengajuanPklStatusChanged', 'App\\Models\\User', 4, '{\"pengajuan_id\":1,\"tempat_pkl_nama\":\"PT Teknologi Maju\",\"status\":\"disetujui\",\"catatan\":null,\"message\":\"Status pengajuan PKL Anda di PT Teknologi Maju telah diperbarui menjadi Disetujui.\",\"title\":\"Pembaruan Pengajuan PKL\"}', NULL, '2026-06-08 23:52:21', '2026-06-08 23:52:21');

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pembimbing_industri`
--

CREATE TABLE `pembimbing_industri` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `tempat_pkl_id` bigint(20) UNSIGNED NOT NULL,
  `no_hp` varchar(255) DEFAULT NULL,
  `jabatan` varchar(255) DEFAULT NULL,
  `tanda_tangan` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `pembimbing_industri`
--

INSERT INTO `pembimbing_industri` (`id`, `user_id`, `tempat_pkl_id`, `no_hp`, `jabatan`, `tanda_tangan`, `logo`, `created_at`, `updated_at`) VALUES
(1, 9, 1, '087777777777', 'HRD Manager', 'signatures/4LrowUvRPjok0d6gtkwi3We2u2tb7SFH0d4a172P.jpg', 'logos/4pvI9Bz8JT4ED72xEbuRhTBGjE1nO1GMeLBWA04b.jpg', '2026-06-08 06:24:00', '2026-06-16 12:06:13');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengajuan_pkl`
--

CREATE TABLE `pengajuan_pkl` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `siswa_id` bigint(20) UNSIGNED NOT NULL,
  `tempat_pkl_id` bigint(20) UNSIGNED NOT NULL,
  `guru_id` bigint(20) UNSIGNED DEFAULT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `alasan` text NOT NULL,
  `file_dokumen` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'draft',
  `catatan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `pengajuan_pkl`
--

INSERT INTO `pengajuan_pkl` (`id`, `siswa_id`, `tempat_pkl_id`, `guru_id`, `tanggal_mulai`, `tanggal_selesai`, `alasan`, `file_dokumen`, `status`, `catatan`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, '2026-06-09', '2026-06-30', 'tes', 'pengajuan/x3sZgtUsElhITFd6K2JovVpxPrOjNOLCYLLTjBXC.docx', 'selesai', NULL, '2026-06-08 23:49:15', '2026-06-09 00:18:42'),
(2, 2, 1, 1, '2026-06-10', '2026-06-11', 'test', 'pengajuan/19kBb2Zmkq3x5zDTYEn8wpvBqIerCYUHeIWRMogO.pdf', 'menunggu_penilaian', NULL, '2026-06-10 05:29:48', '2026-06-13 08:34:57');

-- --------------------------------------------------------

--
-- Struktur dari tabel `penilaian_pkl`
--

CREATE TABLE `penilaian_pkl` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `pengajuan_pkl_id` bigint(20) UNSIGNED NOT NULL,
  `nilai_sikap` int(11) DEFAULT NULL,
  `nilai_keterampilan` int(11) DEFAULT NULL,
  `nilai_laporan` int(11) DEFAULT NULL,
  `nilai_akhir` decimal(5,2) NOT NULL,
  `catatan_evaluasi` text DEFAULT NULL,
  `detail_nilai` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`detail_nilai`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `penilaian_pkl`
--

INSERT INTO `penilaian_pkl` (`id`, `pengajuan_pkl_id`, `nilai_sikap`, `nilai_keterampilan`, `nilai_laporan`, `nilai_akhir`, `catatan_evaluasi`, `detail_nilai`, `created_at`, `updated_at`) VALUES
(1, 1, 60, 50, 70, 60.00, 'ok', NULL, '2026-06-09 00:18:42', '2026-06-09 00:18:42');

-- --------------------------------------------------------

--
-- Struktur dari tabel `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('mRgDRfxq6mwAgfOavWdx5PoVCrwgMtxss7qxwmHg', 4, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiUmRXWndJZDBxdElqeXc5NHJxanZXY1BxTUpXb21wa2RzS2d5azNkZyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zaXN3YS9wZW5nYWp1YW4vMS9zZXJ0aWZpa2F0IjtzOjU6InJvdXRlIjtzOjI2OiJzaXN3YS5wZW5nYWp1YW4uc2VydGlmaWthdCI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjQ7fQ==', 1781341746),
('nPEDEE5DX0TjaGFVqf8Ys0Ed5iQuZGQMCb2Zy7uL', 4, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoid1RCS24zc213dzJjYkd4dk8yTEZXVnBCQ2NpZlR0bFhyT2ZKOXFlRiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zaXN3YS9wZW5nYWp1YW4iO3M6NToicm91dGUiO3M6MjE6InNpc3dhLnBlbmdhanVhbi5pbmRleCI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjQ7fQ==', 1781602011),
('UihNGJFEeYf5XkbFRRqOV1hPZTVaqGdkv5WWVuum', 9, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiTUNxZnBHcXpuRUxNVTBucTdWa2hLS2tDZEM5SEdEZmh1SXZ5WG1CTyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9wZW1iaW1iaW5nL2Rhc2hib2FyZCI7czo1OiJyb3V0ZSI7czoyMDoicGVtYmltYmluZy5kYXNoYm9hcmQiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTo5O30=', 1781341667),
('UkpML0xMgrCcDMKSwxi9Vk8ewrKFv30nLHTSNkzZ', 4, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiMXByaHVZd2JuTnlzWm91R0RCY1V6bkxkWU1haWhzQlNsRmZXdWxVOCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zaXN3YS9wZW5nYWp1YW4vMS9zZXJ0aWZpa2F0IjtzOjU6InJvdXRlIjtzOjI2OiJzaXN3YS5wZW5nYWp1YW4uc2VydGlmaWthdCI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjQ7fQ==', 1781611601);

-- --------------------------------------------------------

--
-- Struktur dari tabel `siswa`
--

CREATE TABLE `siswa` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `nis` varchar(255) NOT NULL,
  `kelas` varchar(255) NOT NULL,
  `jurusan` varchar(255) NOT NULL,
  `alamat` text DEFAULT NULL,
  `no_hp` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `siswa`
--

INSERT INTO `siswa` (`id`, `user_id`, `nis`, `kelas`, `jurusan`, `alamat`, `no_hp`, `created_at`, `updated_at`) VALUES
(1, 4, '20240001', 'XII', 'Rekayasa Perangkat Lunak', 'Jl. Siswa No. 1', '081000000001', '2026-06-08 06:23:58', '2026-06-08 06:23:58'),
(2, 5, '20240002', 'XII', 'Rekayasa Perangkat Lunak', 'Jl. Siswa No. 2', '082000000002', '2026-06-08 06:23:59', '2026-06-08 06:23:59'),
(3, 6, '20240003', 'XII', 'Rekayasa Perangkat Lunak', 'Jl. Siswa No. 3', '083000000003', '2026-06-08 06:23:59', '2026-06-08 06:23:59'),
(4, 7, '20240004', 'XII', 'Rekayasa Perangkat Lunak', 'Jl. Siswa No. 4', '084000000004', '2026-06-08 06:24:00', '2026-06-08 06:24:00'),
(5, 8, '20240005', 'XII', 'Rekayasa Perangkat Lunak', 'Jl. Siswa No. 5', '085000000005', '2026-06-08 06:24:00', '2026-06-08 06:24:00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tempat_pkl`
--

CREATE TABLE `tempat_pkl` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_tempat` varchar(255) NOT NULL,
  `alamat` text NOT NULL,
  `bidang_usaha` varchar(255) NOT NULL,
  `kontak_person` varchar(255) DEFAULT NULL,
  `no_hp` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `kuota` int(11) NOT NULL DEFAULT 1,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `tempat_pkl`
--

INSERT INTO `tempat_pkl` (`id`, `nama_tempat`, `alamat`, `bidang_usaha`, `kontak_person`, `no_hp`, `email`, `kuota`, `keterangan`, `created_at`, `updated_at`) VALUES
(1, 'PT Teknologi Maju', 'Jl. Industri No. 10', 'Teknologi Informasi', NULL, NULL, NULL, 3, NULL, '2026-06-08 06:24:00', '2026-06-08 06:24:00'),
(2, 'CV Kreatif Digital', 'Jl. Inovasi No. 25', 'Desain Grafis', NULL, NULL, NULL, 2, NULL, '2026-06-08 06:24:00', '2026-06-08 06:24:00'),
(3, 'Rumah Sakit Sehat', 'Jl. Kesehatan No. 5', 'Kesehatan', NULL, NULL, NULL, 2, NULL, '2026-06-08 06:24:00', '2026-06-08 06:24:00'),
(4, 'Bank Mandiri Cab. Kolaka', 'Jl. Ahmad Yani No. 1', 'Perbankan', NULL, NULL, NULL, 4, NULL, '2026-06-08 06:24:00', '2026-06-08 06:24:00'),
(5, 'Dinas Pendidikan Kolaka', 'Jl. Pendidikan No. 7', 'Pendidikan', NULL, NULL, NULL, 3, NULL, '2026-06-08 06:24:00', '2026-06-08 06:24:00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'siswa',
  `is_approved` tinyint(1) NOT NULL DEFAULT 0,
  `avatar` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `is_approved`, `avatar`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin PKL', 'admin@pkl.test', '2026-06-08 06:23:56', '$2y$12$iGyAwrYvZ5HATysDpm0ouOx81dZCjs89rVKf1GQbi2Cgrpl2C4L8y', 'admin', 1, NULL, 'n20xdsHdo6uvHDm831B6kZHEH08r08iChx1WUqkORAkp8y9XNP9IWT67C40g', '2026-06-08 06:23:57', '2026-06-08 06:23:57'),
(2, 'Guru Satu', 'guru1@pkl.test', '2026-06-08 06:23:57', '$2y$12$rTl5AAusU3IECkE3ORHAk.21Q.bl8VWJW9fCydLndDNMRGFWbBmhq', 'guru', 1, NULL, 'JDuhRv5xbZQQatVjp7qcYW1wsE3j9nk0fVTcjv8b9vv1I7CKc4lZ94fvwc40', '2026-06-08 06:23:57', '2026-06-08 06:23:57'),
(3, 'Guru Dua', 'guru2@pkl.test', '2026-06-08 06:23:58', '$2y$12$6eGSEMOZDWF6t2g0NJga2eIJ56MoIRf7VKMIJFC/vBk9TrE1TokT.', 'guru', 1, NULL, 'lnBMD8jwc9', '2026-06-08 06:23:58', '2026-06-08 06:23:58'),
(4, 'Siswa 1', 'siswa1@pkl.test', '2026-06-08 06:23:58', '$2y$12$1xRFfRvKW98iB7orn5bv3.yTLFZLeyKRvHEZoEkJOcns1iBa.Q.5.', 'siswa', 1, NULL, '9XkUGdAyEaXzRE9QFetKhgOjzdnvr9Q4HxHyouxFNrcZdlHW9PErTSw0tUsV', '2026-06-08 06:23:58', '2026-06-08 06:23:58'),
(5, 'Siswa 2', 'siswa2@pkl.test', '2026-06-08 06:23:59', '$2y$12$X2zGxYeUeiKymSixaFae5OiqKe7/Z.9c24zI9TqcukUVk2WbY0niq', 'siswa', 1, NULL, 'qoDkIQodwZKrwmjyukujrHtEHCe30AMePBaeMGyE1yIzrLuh5qZBgraJn7XZ', '2026-06-08 06:23:59', '2026-06-08 06:23:59'),
(6, 'Siswa 3', 'siswa3@pkl.test', '2026-06-08 06:23:59', '$2y$12$T6ms9XZEKDvSDxfA/yBDgekflQndNDiKYQoTjW/x464sSqtx1eXcK', 'siswa', 1, NULL, 'ZnehROXPtk', '2026-06-08 06:23:59', '2026-06-08 06:23:59'),
(7, 'Siswa 4', 'siswa4@pkl.test', '2026-06-08 06:24:00', '$2y$12$ut2ljiTWs5rrHLdq43IwBeZgLCQVsosaOyDIq2aXTtU5CSZhIqrFW', 'siswa', 1, NULL, 'hQnqefBU6Z', '2026-06-08 06:24:00', '2026-06-08 06:24:00'),
(8, 'Siswa 5', 'siswa5@pkl.test', '2026-06-08 06:24:00', '$2y$12$tQWQJit57y8Djgr0m3iJQODNVVnE38CUfIK0SDcmFJgmHz2x81i8y', 'siswa', 1, NULL, 'UBFF6XmQGq', '2026-06-08 06:24:00', '2026-06-08 06:24:00'),
(9, 'Budi Industri', 'pembimbing@pkl.test', '2026-06-08 06:24:00', '$2y$12$oMe.StYfRssNN3DR8XPvEe8WsBIpNFtPrF/fCqMel19LpDa6idNUi', 'pembimbing_industri', 1, NULL, 'W0aBhLAzCPkdwpXctaaJcyg4wkvP0fyUe5x0KwMvW1QwO0srVpZDeHRRA9kq', '2026-06-08 06:24:00', '2026-06-08 06:24:00');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `absensi_pkl`
--
ALTER TABLE `absensi_pkl`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `absensi_pkl_pengajuan_pkl_id_tanggal_unique` (`pengajuan_pkl_id`,`tanggal`);

--
-- Indeks untuk tabel `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indeks untuk tabel `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indeks untuk tabel `guru`
--
ALTER TABLE `guru`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `guru_user_id_unique` (`user_id`),
  ADD UNIQUE KEY `guru_nip_unique` (`nip`);

--
-- Indeks untuk tabel `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indeks untuk tabel `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `jurnal_pkl`
--
ALTER TABLE `jurnal_pkl`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `jurnal_pkl_pengajuan_pkl_id_tanggal_unique` (`pengajuan_pkl_id`,`tanggal`);

--
-- Indeks untuk tabel `laporan_pkl`
--
ALTER TABLE `laporan_pkl`
  ADD PRIMARY KEY (`id`),
  ADD KEY `laporan_pkl_pengajuan_pkl_id_foreign` (`pengajuan_pkl_id`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`);

--
-- Indeks untuk tabel `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indeks untuk tabel `pembimbing_industri`
--
ALTER TABLE `pembimbing_industri`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pembimbing_industri_user_id_unique` (`user_id`),
  ADD KEY `pembimbing_industri_tempat_pkl_id_foreign` (`tempat_pkl_id`);

--
-- Indeks untuk tabel `pengajuan_pkl`
--
ALTER TABLE `pengajuan_pkl`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pengajuan_pkl_siswa_id_foreign` (`siswa_id`),
  ADD KEY `pengajuan_pkl_tempat_pkl_id_foreign` (`tempat_pkl_id`),
  ADD KEY `pengajuan_pkl_guru_id_foreign` (`guru_id`);

--
-- Indeks untuk tabel `penilaian_pkl`
--
ALTER TABLE `penilaian_pkl`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `penilaian_pkl_pengajuan_pkl_id_unique` (`pengajuan_pkl_id`);

--
-- Indeks untuk tabel `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indeks untuk tabel `siswa`
--
ALTER TABLE `siswa`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `siswa_user_id_unique` (`user_id`),
  ADD UNIQUE KEY `siswa_nis_unique` (`nis`);

--
-- Indeks untuk tabel `tempat_pkl`
--
ALTER TABLE `tempat_pkl`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `absensi_pkl`
--
ALTER TABLE `absensi_pkl`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `guru`
--
ALTER TABLE `guru`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jurnal_pkl`
--
ALTER TABLE `jurnal_pkl`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `laporan_pkl`
--
ALTER TABLE `laporan_pkl`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT untuk tabel `pembimbing_industri`
--
ALTER TABLE `pembimbing_industri`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `pengajuan_pkl`
--
ALTER TABLE `pengajuan_pkl`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `penilaian_pkl`
--
ALTER TABLE `penilaian_pkl`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `siswa`
--
ALTER TABLE `siswa`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `tempat_pkl`
--
ALTER TABLE `tempat_pkl`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `absensi_pkl`
--
ALTER TABLE `absensi_pkl`
  ADD CONSTRAINT `absensi_pkl_pengajuan_pkl_id_foreign` FOREIGN KEY (`pengajuan_pkl_id`) REFERENCES `pengajuan_pkl` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `guru`
--
ALTER TABLE `guru`
  ADD CONSTRAINT `guru_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `jurnal_pkl`
--
ALTER TABLE `jurnal_pkl`
  ADD CONSTRAINT `jurnal_pkl_pengajuan_pkl_id_foreign` FOREIGN KEY (`pengajuan_pkl_id`) REFERENCES `pengajuan_pkl` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `laporan_pkl`
--
ALTER TABLE `laporan_pkl`
  ADD CONSTRAINT `laporan_pkl_pengajuan_pkl_id_foreign` FOREIGN KEY (`pengajuan_pkl_id`) REFERENCES `pengajuan_pkl` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pembimbing_industri`
--
ALTER TABLE `pembimbing_industri`
  ADD CONSTRAINT `pembimbing_industri_tempat_pkl_id_foreign` FOREIGN KEY (`tempat_pkl_id`) REFERENCES `tempat_pkl` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pembimbing_industri_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pengajuan_pkl`
--
ALTER TABLE `pengajuan_pkl`
  ADD CONSTRAINT `pengajuan_pkl_guru_id_foreign` FOREIGN KEY (`guru_id`) REFERENCES `guru` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `pengajuan_pkl_siswa_id_foreign` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pengajuan_pkl_tempat_pkl_id_foreign` FOREIGN KEY (`tempat_pkl_id`) REFERENCES `tempat_pkl` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `penilaian_pkl`
--
ALTER TABLE `penilaian_pkl`
  ADD CONSTRAINT `penilaian_pkl_pengajuan_pkl_id_foreign` FOREIGN KEY (`pengajuan_pkl_id`) REFERENCES `pengajuan_pkl` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `siswa`
--
ALTER TABLE `siswa`
  ADD CONSTRAINT `siswa_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
