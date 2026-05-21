SET FOREIGN_KEY_CHECKS=0;

START TRANSACTION;

DROP TABLE IF EXISTS `cache`;
DROP TABLE IF EXISTS `cache_locks`;
DROP TABLE IF EXISTS `failed_jobs`;
DROP TABLE IF EXISTS `job_batches`;
DROP TABLE IF EXISTS `jobs`;
DROP TABLE IF EXISTS `migrations`;
DROP TABLE IF EXISTS `password_reset_tokens`;
DROP TABLE IF EXISTS `sessions`;
DROP TABLE IF EXISTS `penilaian_pkl`;
DROP TABLE IF EXISTS `laporan_pkl`;
DROP TABLE IF EXISTS `jurnal_pkl`;
DROP TABLE IF EXISTS `pengajuan_pkl`;
DROP TABLE IF EXISTS `guru`;
DROP TABLE IF EXISTS `siswa`;
DROP TABLE IF EXISTS `tempat_pkl`;
DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `email_verified_at` TIMESTAMP NULL DEFAULT NULL,
  `password` VARCHAR(255) NOT NULL,
  `role` VARCHAR(50) NOT NULL DEFAULT 'siswa',
  `remember_token` VARCHAR(100) NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `users` VALUES
(1, 'Admin PKL', 'admin@pkl.test', '2026-05-14 12:13:06', '$2y$12$lkKpp.n73YEY5vToLHZRV.SDmBGocUCF/W9/iv7NILKmZFHCxNFO6', 'admin', 'Ya52KbkgBj4h6pYdJAGxttw9JaGz7NxPlLKWZf08MRjgxVfCzgm4vvriynqP', '2026-05-14 12:13:07', '2026-05-14 12:13:07'),
(2, 'Guru Satu', 'guru1@pkl.test', '2026-05-14 12:13:07', '$2y$12$QMzf9sHwpN3XDQe6Y7JAAeg91d7i0/5hLFdngPkEv8.5tfuDGnbmG', 'guru', '5FQJWDOhXl', '2026-05-14 12:13:07', '2026-05-14 12:13:07'),
(3, 'Guru Dua', 'guru2@pkl.test', '2026-05-14 12:13:07', '$2y$12$rOjZTgl4kzOzB5Bcsoyxce/MLwMTl6Gj.5qvVqK9nqhKfHgryhtLm', 'guru', 'HqHWplStYk', '2026-05-14 12:13:07', '2026-05-14 12:13:07'),
(4, 'Siswa 1', 'siswa1@pkl.test', '2026-05-14 12:13:08', '$2y$12$9aUfxx7w.afDr1ejaPym4OrQBC24DsQSSN9dlccBenwp6sKPUQyDC', 'siswa', 'B91LKFH4ovXP7iXfCZbfSacNjb2RAY1bG2XZD5LwOH37cdxfKeIz6JikX9hY', '2026-05-14 12:13:08', '2026-05-14 12:13:08'),
(5, 'Siswa 2', 'siswa2@pkl.test', '2026-05-14 12:13:08', '$2y$12$3dQYNXZzh5cbXi3zLyK9l.kuZwFyCadHGuMbTq8hD9wLGi53Bd/1G', 'siswa', 'IDcctHHigQ', '2026-05-14 12:13:08', '2026-05-14 12:13:08'),
(6, 'Siswa 3', 'siswa3@pkl.test', '2026-05-14 12:13:08', '$2y$12$8pgNpU63XZOUmX8vZRfomuS9OrPkxaxjl6RPvso3XRxf3Jg5JB5We', 'siswa', 'JMgWuKT9Hn', '2026-05-14 12:13:08', '2026-05-14 12:13:08'),
(7, 'Siswa 4', 'siswa4@pkl.test', '2026-05-14 12:13:09', '$2y$12$4gTyXSnE4tsDINM8GelA9uAuey9oioA5UXLuMhIK8bAiPy8uaztRG', 'siswa', 'bwSD4nzPve', '2026-05-14 12:13:09', '2026-05-14 12:13:09'),
(8, 'Siswa 5', 'siswa5@pkl.test', '2026-05-14 12:13:09', '$2y$12$mWlk9IngnJy8o6Kon34V8u7wz9juxnxkIqVHt/GZLOXBZ9swYDVg6', 'siswa', 'PMWo1hfHX0', '2026-05-14 12:13:09', '2026-05-14 12:13:09');

CREATE TABLE `guru` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `nip` VARCHAR(50) NOT NULL,
  `alamat` TEXT NULL,
  `no_hp` VARCHAR(20) NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `guru_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `guru` VALUES
(1, 2, '198001012010011001', 'Jl. Contoh No. 1', '081111111111', '2026-05-14 12:13:07', '2026-05-14 12:13:07'),
(2, 3, '198001012010011002', 'Jl. Contoh No. 2', '082222222222', '2026-05-14 12:13:07', '2026-05-14 12:13:07');

CREATE TABLE `siswa` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `nis` VARCHAR(50) NOT NULL,
  `kelas` VARCHAR(50) NOT NULL,
  `jurusan` VARCHAR(255) NOT NULL,
  `alamat` TEXT NULL,
  `no_hp` VARCHAR(20) NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `siswa_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `siswa` VALUES
(1, 4, '20240001', 'XII', 'Rekayasa Perangkat Lunak', 'Jl. Siswa No. 1', '081000000001', '2026-05-14 12:13:08', '2026-05-14 12:13:08'),
(2, 5, '20240002', 'XII', 'Rekayasa Perangkat Lunak', 'Jl. Siswa No. 2', '082000000002', '2026-05-14 12:13:08', '2026-05-14 12:13:08'),
(3, 6, '20240003', 'XII', 'Rekayasa Perangkat Lunak', 'Jl. Siswa No. 3', '083000000003', '2026-05-14 12:13:08', '2026-05-14 12:13:08'),
(4, 7, '20240004', 'XII', 'Rekayasa Perangkat Lunak', 'Jl. Siswa No. 4', '084000000004', '2026-05-14 12:13:09', '2026-05-14 12:13:09'),
(5, 8, '20240005', 'XII', 'Rekayasa Perangkat Lunak', 'Jl. Siswa No. 5', '085000000005', '2026-05-14 12:13:09', '2026-05-14 12:13:09');

CREATE TABLE `tempat_pkl` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama_tempat` VARCHAR(255) NOT NULL,
  `alamat` TEXT NOT NULL,
  `bidang_usaha` VARCHAR(255) NOT NULL,
  `kontak_person` VARCHAR(255) NULL,
  `no_hp` VARCHAR(20) NULL,
  `email` VARCHAR(255) NULL,
  `kuota` INT NOT NULL DEFAULT 1,
  `keterangan` TEXT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `tempat_pkl` VALUES
(1, 'PT Teknologi Maju', 'Jl. Industri No. 10', 'Teknologi Informasi', NULL, NULL, NULL, 3, NULL, '2026-05-14 12:13:09', '2026-05-14 12:13:09'),
(2, 'CV Kreatif Digital', 'Jl. Inovasi No. 25', 'Desain Grafis', NULL, NULL, NULL, 2, NULL, '2026-05-14 12:13:09', '2026-05-14 12:13:09'),
(3, 'Rumah Sakit Sehat', 'Jl. Kesehatan No. 5', 'Kesehatan', NULL, NULL, NULL, 2, NULL, '2026-05-14 12:13:09', '2026-05-14 12:13:09'),
(4, 'Bank Mandiri Cab. Kolaka', 'Jl. Ahmad Yani No. 1', 'Perbankan', NULL, NULL, NULL, 4, NULL, '2026-05-14 12:13:09', '2026-05-14 12:13:09'),
(5, 'Dinas Pendidikan Kolaka', 'Jl. Pendidikan No. 7', 'Pendidikan', NULL, NULL, NULL, 3, NULL, '2026-05-14 12:13:09', '2026-05-14 12:13:09');

CREATE TABLE `pengajuan_pkl` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `siswa_id` BIGINT UNSIGNED NOT NULL,
  `tempat_pkl_id` BIGINT UNSIGNED NOT NULL,
  `guru_id` BIGINT UNSIGNED NULL,
  `tanggal_mulai` DATE NOT NULL,
  `tanggal_selesai` DATE NOT NULL,
  `alasan` TEXT NOT NULL,
  `file_dokumen` VARCHAR(255) NULL,
  `status` VARCHAR(50) NOT NULL DEFAULT 'draft',
  `catatan` TEXT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `pengajuan_pkl_siswa_id_foreign` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pengajuan_pkl_tempat_pkl_id_foreign` FOREIGN KEY (`tempat_pkl_id`) REFERENCES `tempat_pkl` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pengajuan_pkl_guru_id_foreign` FOREIGN KEY (`guru_id`) REFERENCES `guru` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `pengajuan_pkl` VALUES
(1, 1, 1, NULL, '2026-05-14', '2026-05-31', 'test', NULL, 'draft', NULL, '2026-05-14 12:36:31', '2026-05-14 12:36:31');

CREATE TABLE `jurnal_pkl` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `pengajuan_pkl_id` BIGINT UNSIGNED NOT NULL,
  `tanggal` DATE NOT NULL,
  `kegiatan` TEXT NOT NULL,
  `kendala` TEXT NULL,
  `dokumentasi` VARCHAR(255) NULL,
  `status` VARCHAR(50) NOT NULL DEFAULT 'menunggu_validasi',
  `catatan_guru` TEXT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `jurnal_pkl_pengajuan_pkl_id_foreign` FOREIGN KEY (`pengajuan_pkl_id`) REFERENCES `pengajuan_pkl` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `laporan_pkl` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `pengajuan_pkl_id` BIGINT UNSIGNED NOT NULL,
  `file_laporan` VARCHAR(255) NOT NULL,
  `status` VARCHAR(50) NOT NULL DEFAULT 'menunggu_review',
  `catatan_guru` TEXT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `laporan_pkl_pengajuan_pkl_id_foreign` FOREIGN KEY (`pengajuan_pkl_id`) REFERENCES `pengajuan_pkl` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `penilaian_pkl` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `pengajuan_pkl_id` BIGINT UNSIGNED NOT NULL,
  `nilai_sikap` INT NOT NULL,
  `nilai_keterampilan` INT NOT NULL,
  `nilai_laporan` INT NOT NULL,
  `nilai_akhir` INT NOT NULL,
  `catatan_evaluasi` TEXT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `penilaian_pkl_pengajuan_pkl_id_foreign` FOREIGN KEY (`pengajuan_pkl_id`) REFERENCES `pengajuan_pkl` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `cache` (
  `key` VARCHAR(255) NOT NULL,
  `value` MEDIUMTEXT NOT NULL,
  `expiration` INT NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `cache` VALUES
('laravel-cache-siswa@pkl.test|127.0.0.1:timer', 'i:1778762826;', 1778762826),
('laravel-cache-siswa@pkl.test|127.0.0.1', 'i:1;', 1778762826);

CREATE TABLE `cache_locks` (
  `key` VARCHAR(255) NOT NULL,
  `owner` VARCHAR(255) NOT NULL,
  `expiration` INT NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `failed_jobs` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` VARCHAR(255) NOT NULL,
  `connection` TEXT NOT NULL,
  `queue` TEXT NOT NULL,
  `payload` LONGTEXT NOT NULL,
  `exception` LONGTEXT NOT NULL,
  `failed_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `job_batches` (
  `id` VARCHAR(255) NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `total_jobs` INT NOT NULL,
  `pending_jobs` INT NOT NULL,
  `failed_jobs` INT NOT NULL,
  `failed_job_ids` LONGTEXT NOT NULL,
  `options` MEDIUMTEXT NULL,
  `cancelled_at` INT NULL,
  `created_at` INT NOT NULL,
  `finished_at` INT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `jobs` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` VARCHAR(255) NOT NULL,
  `payload` LONGTEXT NOT NULL,
  `attempts` TINYINT UNSIGNED NOT NULL,
  `reserved_at` INT UNSIGNED NULL,
  `available_at` INT UNSIGNED NOT NULL,
  `created_at` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `migrations` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` VARCHAR(255) NOT NULL,
  `batch` INT NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `migrations` VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_05_14_000001_create_siswa_table', 1),
(5, '2026_05_14_000002_create_guru_table', 1),
(6, '2026_05_14_000003_create_tempat_pkl_table', 1),
(7, '2026_05_14_000004_create_pengajuan_pkl_table', 1),
(8, '2026_05_14_000005_create_jurnal_pkl_table', 1),
(9, '2026_05_14_000006_create_laporan_pkl_table', 1),
(10, '2026_05_14_000007_create_penilaian_pkl_table', 1);

CREATE TABLE `password_reset_tokens` (
  `email` VARCHAR(255) NOT NULL,
  `token` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `sessions` (
  `id` VARCHAR(255) NOT NULL,
  `user_id` BIGINT UNSIGNED NULL,
  `ip_address` VARCHAR(45) NULL,
  `user_agent` TEXT NULL,
  `payload` LONGTEXT NOT NULL,
  `last_activity` INT NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `sessions` VALUES
('HGYneAd2VHC9QhvqjwPdF14US3NZbvIWxtDEIfjC', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoibWFMbWNQN1JCNGVtdU96aklaZU5JOXRacDlqZGdQbDJDVWR3MklTWSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9ndXJ1L3BlbmlsYWlhbiI7czo1OiJyb3V0ZSI7czoyMDoiZ3VydS5wZW5pbGFpYW4uaW5kZXgiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToyO30=', 1778762257),
('Qxa1bz97FgrMyyOBh3EVR6vi5C4WVBYGs5ingQvw', 4, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoibzVQZTRkNFhrNGJROXJlNml2NWtKcjZ5ZGd6MzlpS283ekhVSFFoUyI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjM3OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvc2lzd2EvZGFzaGJvYXJkIjtzOjU6InJvdXRlIjtzOjE1OiJzaXN3YS5kYXNoYm9hcmQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTo0O30=', 1778763353);

COMMIT;

SET FOREIGN_KEY_CHECKS=1;