-- SIPENA GenBI Database Export
-- Generated for InfinityFree Deployment

SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS `akun_instagrams`;
CREATE TABLE `akun_instagrams` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nama_akun` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `akun_instagrams` (`id`, `nama_akun`, `username`, `keterangan`, `status`, `created_at`, `updated_at`) VALUES ('1', 'BI Sulut', '@bi_sulut', 'Akun resmi Bank Indonesia Sulawesi Utara', 'aktif', '2026-08-10 06:39:15', '2026-08-10 06:39:15');
INSERT INTO `akun_instagrams` (`id`, `nama_akun`, `username`, `keterangan`, `status`, `created_at`, `updated_at`) VALUES ('2', 'GenBI Indonesia', '@genbi_indonesia', 'Akun pusat Generasi Baru Indonesia', 'aktif', '2026-08-10 06:39:15', '2026-08-10 06:39:15');
INSERT INTO `akun_instagrams` (`id`, `nama_akun`, `username`, `keterangan`, `status`, `created_at`, `updated_at`) VALUES ('3', 'QRIS Indonesia', '@qris.id', 'Edukasi dan sosialisasi QRIS', 'aktif', '2026-08-10 06:39:15', '2026-08-10 06:39:15');
INSERT INTO `akun_instagrams` (`id`, `nama_akun`, `username`, `keterangan`, `status`, `created_at`, `updated_at`) VALUES ('4', 'Bank Indonesia', '@bank_indonesia', 'Akun utama Bank Indonesia', 'aktif', '2026-08-10 06:39:15', '2026-08-10 06:39:15');
INSERT INTO `akun_instagrams` (`id`, `nama_akun`, `username`, `keterangan`, `status`, `created_at`, `updated_at`) VALUES ('5', 'GenBI Sulut', '@genbi_sulut', 'Akun GenBI Wilayah Sulut', 'aktif', '2026-08-10 06:39:15', '2026-08-10 06:39:15');

DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `job_batches`;
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
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `laporans`;
CREATE TABLE `laporans` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `akun_instagram_id` bigint(20) unsigned NOT NULL,
  `target_harian_id` bigint(20) unsigned DEFAULT NULL,
  `tanggal_postingan` date NOT NULL,
  `link_postingan` varchar(500) DEFAULT NULL,
  `judul_postingan` varchar(255) DEFAULT NULL,
  `bukti_like` varchar(255) DEFAULT NULL,
  `bukti_komen` varchar(255) DEFAULT NULL,
  `bukti_share` varchar(255) DEFAULT NULL,
  `hash_like` varchar(64) DEFAULT NULL,
  `hash_komen` varchar(64) DEFAULT NULL,
  `hash_share` varchar(64) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'menunggu',
  `catatan_admin` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `laporans_user_id_foreign` (`user_id`),
  KEY `laporans_akun_instagram_id_foreign` (`akun_instagram_id`),
  KEY `laporans_target_harian_id_foreign` (`target_harian_id`),
  KEY `laporans_hash_like_index` (`hash_like`),
  KEY `laporans_hash_komen_index` (`hash_komen`),
  KEY `laporans_hash_share_index` (`hash_share`),
  CONSTRAINT `laporans_akun_instagram_id_foreign` FOREIGN KEY (`akun_instagram_id`) REFERENCES `akun_instagrams` (`id`) ON DELETE CASCADE,
  CONSTRAINT `laporans_target_harian_id_foreign` FOREIGN KEY (`target_harian_id`) REFERENCES `target_harians` (`id`) ON DELETE SET NULL,
  CONSTRAINT `laporans_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `laporans` (`id`, `user_id`, `akun_instagram_id`, `target_harian_id`, `tanggal_postingan`, `link_postingan`, `judul_postingan`, `bukti_like`, `bukti_komen`, `bukti_share`, `hash_like`, `hash_komen`, `hash_share`, `keterangan`, `status`, `catatan_admin`, `created_at`, `updated_at`) VALUES ('1', '2', '1', '1', '2026-06-26', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Sudah like, komen, dan share sesuai instruksi.', 'valid', 'Laporan lengkap dan valid.', '2026-08-10 06:39:15', '2026-08-10 06:39:15');
INSERT INTO `laporans` (`id`, `user_id`, `akun_instagram_id`, `target_harian_id`, `tanggal_postingan`, `link_postingan`, `judul_postingan`, `bukti_like`, `bukti_komen`, `bukti_share`, `hash_like`, `hash_komen`, `hash_share`, `keterangan`, `status`, `catatan_admin`, `created_at`, `updated_at`) VALUES ('2', '3', '2', '2', '2026-06-26', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Sudah upload screenshot.', 'menunggu', NULL, '2026-08-10 06:39:15', '2026-08-10 06:39:15');
INSERT INTO `laporans` (`id`, `user_id`, `akun_instagram_id`, `target_harian_id`, `tanggal_postingan`, `link_postingan`, `judul_postingan`, `bukti_like`, `bukti_komen`, `bukti_share`, `hash_like`, `hash_komen`, `hash_share`, `keterangan`, `status`, `catatan_admin`, `created_at`, `updated_at`) VALUES ('3', '4', '3', '3', '2026-06-27', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Laporan pertama.', 'ditolak', 'Screenshot share belum sesuai.', '2026-08-10 06:39:15', '2026-08-10 06:39:15');

DROP TABLE IF EXISTS `notifikasis`;
CREATE TABLE `notifikasis` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `judul` varchar(255) NOT NULL,
  `pesan` text NOT NULL,
  `tipe` varchar(255) NOT NULL DEFAULT 'target',
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifikasis_user_id_foreign` (`user_id`),
  CONSTRAINT `notifikasis_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `notifikasis` (`id`, `user_id`, `judul`, `pesan`, `tipe`, `is_read`, `created_at`, `updated_at`) VALUES ('1', NULL, 'Target baru ditambahkan', 'Admin menambahkan target untuk akun BI Sulut tanggal 27 Juni 2026 sebanyak 5 postingan.', 'target', '0', '2026-08-10 06:39:15', '2026-08-10 06:39:15');
INSERT INTO `notifikasis` (`id`, `user_id`, `judul`, `pesan`, `tipe`, `is_read`, `created_at`, `updated_at`) VALUES ('2', '3', 'Laporan belum lengkap', 'Laporan GenBI Indonesia tanggal 26 Juni 2026 masih kurang 1 laporan.', 'warning', '0', '2026-08-10 06:39:15', '2026-08-10 06:39:15');
INSERT INTO `notifikasis` (`id`, `user_id`, `judul`, `pesan`, `tipe`, `is_read`, `created_at`, `updated_at`) VALUES ('3', '4', 'Laporan ditolak', 'Laporan QRIS Indonesia ditolak karena screenshot share belum sesuai.', 'ditolak', '0', '2026-08-10 06:39:15', '2026-08-10 06:39:15');
INSERT INTO `notifikasis` (`id`, `user_id`, `judul`, `pesan`, `tipe`, `is_read`, `created_at`, `updated_at`) VALUES ('4', '2', 'Laporan divalidasi', 'Laporan BI Sulut tanggal 26 Juni 2026 telah divalidasi oleh admin.', 'valid', '1', '2026-08-10 06:39:15', '2026-08-10 06:39:15');

DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `periodes`;
CREATE TABLE `periodes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nama_periode` varchar(255) NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `periodes` (`id`, `nama_periode`, `tanggal_mulai`, `tanggal_selesai`, `status`, `created_at`, `updated_at`) VALUES ('1', 'Minggu 4 Juni 2026', '2026-06-23', '2026-06-29', 'aktif', '2026-08-10 06:39:15', '2026-08-10 06:39:15');
INSERT INTO `periodes` (`id`, `nama_periode`, `tanggal_mulai`, `tanggal_selesai`, `status`, `created_at`, `updated_at`) VALUES ('2', 'Minggu 3 Juni 2026', '2026-06-16', '2026-06-22', 'arsip', '2026-08-10 06:39:15', '2026-08-10 06:39:15');

DROP TABLE IF EXISTS `postingan_instagrams`;
CREATE TABLE `postingan_instagrams` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `akun_instagram_id` bigint(20) unsigned NOT NULL,
  `media_id` varchar(255) DEFAULT NULL,
  `caption` text DEFAULT NULL,
  `thumbnail_url` varchar(255) DEFAULT NULL,
  `permalink` varchar(255) DEFAULT NULL,
  `tanggal_postingan` date NOT NULL,
  `status` enum('aktif','diarsipkan') NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `postingan_instagrams_akun_instagram_id_foreign` (`akun_instagram_id`),
  KEY `postingan_instagrams_media_id_index` (`media_id`),
  CONSTRAINT `postingan_instagrams_akun_instagram_id_foreign` FOREIGN KEY (`akun_instagram_id`) REFERENCES `akun_instagrams` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `target_harians`;
CREATE TABLE `target_harians` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `akun_instagram_id` bigint(20) unsigned NOT NULL,
  `periode_id` bigint(20) unsigned DEFAULT NULL,
  `tanggal` date NOT NULL,
  `jumlah_target` int(11) NOT NULL DEFAULT 1,
  `deadline` datetime DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `target_harians_akun_instagram_id_foreign` (`akun_instagram_id`),
  KEY `target_harians_periode_id_foreign` (`periode_id`),
  CONSTRAINT `target_harians_akun_instagram_id_foreign` FOREIGN KEY (`akun_instagram_id`) REFERENCES `akun_instagrams` (`id`) ON DELETE CASCADE,
  CONSTRAINT `target_harians_periode_id_foreign` FOREIGN KEY (`periode_id`) REFERENCES `periodes` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `target_harians` (`id`, `akun_instagram_id`, `periode_id`, `tanggal`, `jumlah_target`, `deadline`, `keterangan`, `created_at`, `updated_at`) VALUES ('1', '1', '1', '2026-06-26', '5', '2026-06-28 23:59:00', 'Wajib like, komen, dan share 5 postingan terbaru', '2026-08-10 06:39:15', '2026-08-10 06:39:15');
INSERT INTO `target_harians` (`id`, `akun_instagram_id`, `periode_id`, `tanggal`, `jumlah_target`, `deadline`, `keterangan`, `created_at`, `updated_at`) VALUES ('2', '2', '1', '2026-06-26', '3', '2026-06-28 23:59:00', 'Wajib like, komen, dan share 3 postingan tentang beasiswa', '2026-08-10 06:39:15', '2026-08-10 06:39:15');
INSERT INTO `target_harians` (`id`, `akun_instagram_id`, `periode_id`, `tanggal`, `jumlah_target`, `deadline`, `keterangan`, `created_at`, `updated_at`) VALUES ('3', '3', '1', '2026-06-27', '4', '2026-06-29 23:59:00', 'Wajib like, komen, dan share postingan Pekan QRIS', '2026-08-10 06:39:15', '2026-08-10 06:39:15');

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'anggota',
  `status` varchar(255) NOT NULL DEFAULT 'aktif',
  `phone` varchar(255) DEFAULT NULL,
  `nim` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `status`, `phone`, `nim`, `remember_token`, `created_at`, `updated_at`) VALUES ('1', 'Admin Sistem', 'admin@genbi.id', NULL, '$2y$12$9C90in7a0OybrH52Ve2FqOgsVZx5cCZsYZr6.9TgTz08bwjU1mMV2', 'admin', 'aktif', NULL, NULL, NULL, '2026-08-10 06:39:15', '2026-08-10 06:39:15');
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `status`, `phone`, `nim`, `remember_token`, `created_at`, `updated_at`) VALUES ('2', 'Gideon Tondatuon', 'gideon@email.com', NULL, '$2y$12$BzK4Jly1ipS.DCGf9Y.y5ulcO1tCmyr5mZILn.YE.yoHDp1.KZMO6', 'anggota', 'aktif', '081234567890', '21021101', NULL, '2026-08-10 06:39:15', '2026-08-10 06:39:15');
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `status`, `phone`, `nim`, `remember_token`, `created_at`, `updated_at`) VALUES ('3', 'Andi Pratama', 'andi@email.com', NULL, '$2y$12$ko.jOMEYKb5VvqI2S43g8uUrrVg27EAP2LBgdlvoU7f30dbzncqdi', 'anggota', 'aktif', '081234567891', '21021102', NULL, '2026-08-10 06:39:15', '2026-08-10 06:39:15');
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `status`, `phone`, `nim`, `remember_token`, `created_at`, `updated_at`) VALUES ('4', 'Sari Wulandari', 'sari@email.com', NULL, '$2y$12$LGkK8pIaqRyutXpPWeoX1u29ThbAur8OWUphEPyE/o/58I2Uqg72e', 'anggota', 'aktif', '081234567892', '21021103', NULL, '2026-08-10 06:39:15', '2026-08-10 06:39:15');

SET FOREIGN_KEY_CHECKS=1;
