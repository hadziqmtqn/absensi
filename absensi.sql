-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 01, 2022 at 02:17 AM
-- Server version: 5.7.33
-- PHP Version: 8.1.9

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
-- Table structure for table `absensis`
--

CREATE TABLE `absensis` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `waktu_absen` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `data_jobs`
--

CREATE TABLE `data_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `kode_pasang_baru` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `data_pasang_barus`
--

CREATE TABLE `data_pasang_barus` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `inet` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_pelanggan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_hp` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `acuan_lokasi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('0','1','2','3') COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `data_pasang_barus`
--

INSERT INTO `data_pasang_barus` (`id`, `kode`, `inet`, `nama_pelanggan`, `no_hp`, `alamat`, `acuan_lokasi`, `foto`, `status`, `created_at`, `updated_at`) VALUES
(1, 'SC-1679152716', '3250341564', 'Lasmanto Bagas Prayoga S.Sos', '(+62) 315 6811 4304', 'Dk. Sudiarto No. 54, Surabaya 99661, DIY', 'Alice the moment she appeared on the ground near the looking-glass. There was a bright idea came into her eyes; and once she remembered trying to find that she was exactly three inches high). \'But.', NULL, '0', '2022-09-01 02:09:49', '2022-09-01 02:09:49'),
(2, 'SC-1236226262', '3241149454', 'Pangeran Galiono Adriansyah S.Kom', '0773 4121 939', 'Ds. Villa No. 694, Lhokseumawe 78033, Sulsel', 'Come on!\' \'Everybody says \"come on!\" here,\' thought Alice, as the rest of my life.\' \'You are old,\' said the Mock Turtle said with a whiting. Now you know.\' \'Who is this?\' She said this she looked.', NULL, '0', '2022-09-01 02:09:49', '2022-09-01 02:09:49'),
(3, 'SC-1813430367', '3714068530', 'Eka Prayoga', '(+62) 378 7952 686', 'Jln. Peta No. 228, Samarinda 68543, DKI', 'And oh, my poor little thing sat down again in a trembling voice, \'--and I hadn\'t mentioned Dinah!\' she said to the three gardeners who were all shaped like the tone of great curiosity. \'Soles and.', NULL, '0', '2022-09-01 02:09:49', '2022-09-01 02:09:49'),
(4, 'SC-1565479674', '3475059693', 'Elvina Susanti', '0362 1844 302', 'Dk. Bata Putih No. 748, Tual 12318, Pabar', 'King added in a confused way, \'Prizes! Prizes!\' Alice had no pictures or conversations in it, \'and what is the use of a well?\' The Dormouse slowly opened his eyes were getting extremely small for a.', NULL, '0', '2022-09-01 02:09:49', '2022-09-01 02:09:49'),
(5, 'SC-1590383380', '3450074791', 'Purwadi Adriansyah', '(+62) 626 9500 0082', 'Kpg. Gatot Subroto No. 669, Bukittinggi 99113, Jabar', 'I can creep under the sea,\' the Gryphon at the door began sneezing all at once. \'Give your evidence,\' said the Mock Turtle, capering wildly about. \'Change lobsters again!\' yelled the Gryphon went on.', NULL, '0', '2022-09-01 02:09:49', '2022-09-01 02:09:49'),
(6, 'SC-1994541106', '3113668316', 'Daliono Salahudin S.Gz', '(+62) 728 1571 2325', 'Psr. Ronggowarsito No. 855, Tarakan 48737, DIY', 'Caterpillar; and it set to work, and very nearly in the middle, being held up by wild beasts and other unpleasant things, all because they WOULD put their heads downward! The Antipathies, I think--\'.', NULL, '0', '2022-09-01 02:09:49', '2022-09-01 02:09:49'),
(7, 'SC-1405992904', '3973977545', 'Timbul Simanjuntak', '0837 5611 974', 'Psr. Kebonjati No. 881, Kediri 87871, NTB', 'I will just explain to you never had to fall upon Alice, as she wandered about for them, but they were trying which word sounded best. Some of the Lobster Quadrille?\' the Gryphon added \'Come, let\'s.', NULL, '0', '2022-09-01 02:09:49', '2022-09-01 02:09:49'),
(8, 'SC-1105646977', '3123331221', 'Nadine Aurora Wulandari S.H.', '0584 8152 5093', 'Ki. Sutarjo No. 322, Batam 91312, Kalteng', 'She did not appear, and after a pause: \'the reason is, that there\'s any one of the words \'DRINK ME\' beautifully printed on it but tea. \'I don\'t see any wine,\' she remarked. \'It tells the day and.', NULL, '0', '2022-09-01 02:09:49', '2022-09-01 02:09:49'),
(9, 'SC-1494362415', '3774323431', 'Gangsar Lembah Natsir', '(+62) 603 7196 128', 'Ki. Yap Tjwan Bing No. 777, Lhokseumawe 78576, Babel', 'I used to queer things happening. While she was surprised to find that she was ever to get hold of this rope--Will the roof was thatched with fur. It was high time you were all writing very busily.', NULL, '0', '2022-09-01 02:09:49', '2022-09-01 02:09:49'),
(10, 'SC-1098655732', '3129339896', 'Ratih Laksita S.Kom', '0924 2549 717', 'Ki. Sukabumi No. 574, Lubuklinggau 22032, Kalteng', 'Gryphon remarked: \'because they lessen from day to day.\' This was quite tired of swimming about here, O Mouse!\' (Alice thought this must be on the English coast you find a number of bathing machines.', NULL, '0', '2022-09-01 02:09:49', '2022-09-01 02:09:49');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2022_07_29_220606_create_settings_table', 1),
(6, '2022_07_30_064242_create_roles_table', 1),
(7, '2022_07_30_064913_alter_role_users_table', 1),
(8, '2022_08_01_103027_create_absensis_table', 1),
(9, '2022_08_01_133616_alter_time_setting', 1),
(10, '2022_08_23_213740_create_data_pasang_barus_table', 1),
(11, '2022_08_23_294521_create_data_jobs_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Admin', '2022-09-01 02:09:48', '2022-09-01 02:09:48'),
(2, 'Karyawan', '2022-09-01 02:09:48', '2022-09-01 02:09:48');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `application_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_hp` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `awal_absensi` time DEFAULT NULL,
  `akhir_absensi` time DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `application_name`, `email`, `no_hp`, `logo`, `awal_absensi`, `akhir_absensi`, `created_at`, `updated_at`) VALUES
(1, 'Absensi App', 'absensi@g.com', NULL, 'theme/template/images/logo.svg', '08:00:00', '11:00:00', '2022-09-01 02:09:48', '2022-09-01 02:09:48');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `short_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nik` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_verifikasi` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `role_id`, `name`, `username`, `photo`, `short_name`, `nik`, `phone`, `company_name`, `email`, `email_verified_at`, `password`, `remember_token`, `is_verifikasi`, `created_at`, `updated_at`) VALUES
(1, 1, 'Admin', NULL, NULL, NULL, NULL, NULL, NULL, 'aa@g.com', NULL, '$2y$10$7GvepJn5gx10aCk4RhEra.UanX20u.ni2lUAQ.iE3CZK3uG8oSIXW', NULL, 1, '2022-09-01 02:09:48', '2022-09-01 02:09:48'),
(2, 2, 'Jaga Suryono S.Farm', '1515729731', 'theme/template/images/user.png', 'Bakijan', '1375115518310757', '0929 6228 8294', 'PT Pradana Habibi (Persero) Tbk', 'rama62@example.org', '2022-09-01 02:09:48', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'yFb8zophAz', 0, '2022-09-01 02:09:48', '2022-09-01 02:09:48'),
(3, 2, 'Radit Suryono', '1355786591', 'theme/template/images/user.png', 'Endah', '2457641446882135', '(+62) 702 7367 0842', 'PT Hardiansyah (Persero) Tbk', 'gpradana@example.org', '2022-09-01 02:09:48', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'BHwXbuR7wp', 0, '2022-09-01 02:09:48', '2022-09-01 02:09:48'),
(4, 2, 'Elma Kayla Pudjiastuti M.TI.', '1725695755', 'theme/template/images/user.png', 'Jayadi', '2821129278783277', '0377 3990 8369', 'Perum Suryatmi', 'vsimanjuntak@example.net', '2022-09-01 02:09:48', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Cn9Ly7fjoY', 1, '2022-09-01 02:09:48', '2022-09-01 02:09:48'),
(5, 2, 'Pia Sari Maryati S.I.Kom', '1992928460', 'theme/template/images/user.png', 'Usman', '2492046346615899', '0331 2175 0064', 'Perum Yolanda (Persero) Tbk', 'yulianti.lutfan@example.net', '2022-09-01 02:09:48', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '44kMGYF7TE', 1, '2022-09-01 02:09:49', '2022-09-01 02:09:49'),
(6, 2, 'Hani Mandasari', '1429593510', 'theme/template/images/user.png', 'Putri', '1643808394238679', '(+62) 849 5128 672', 'CV Simbolon', 'agustina.lala@example.com', '2022-09-01 02:09:48', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'ZnO7toYXNy', 0, '2022-09-01 02:09:49', '2022-09-01 02:09:49'),
(7, 2, 'Ade Samosir', '1213651663', 'theme/template/images/user.png', 'Fitria', '2459355235248119', '0604 1388 889', 'PD Hartati', 'jbudiman@example.net', '2022-09-01 02:09:48', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'LV0Ln4NpMt', 1, '2022-09-01 02:09:49', '2022-09-01 02:09:49'),
(8, 2, 'Unjani Ina Agustina', '1383211426', 'theme/template/images/user.png', 'Dina', '2355148180620223', '0956 6290 104', 'PT Tampubolon Prayoga', 'chelsea98@example.com', '2022-09-01 02:09:48', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'zQRMBiwNAp', 1, '2022-09-01 02:09:49', '2022-09-01 02:09:49'),
(9, 2, 'Ani Nasyidah S.I.Kom', '1291228528', 'theme/template/images/user.png', 'Kamila', '2513872926095723', '0366 0438 697', 'UD Santoso', 'llestari@example.com', '2022-09-01 02:09:48', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '58NuqdHvSL', 1, '2022-09-01 02:09:49', '2022-09-01 02:09:49'),
(10, 2, 'Kenzie Tasdik Dongoran S.Pd', '1118263542', 'theme/template/images/user.png', 'Darijan', '1242689657444910', '(+62) 738 1720 646', 'PD Setiawan Nugroho', 'qori71@example.org', '2022-09-01 02:09:48', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '4JQ2Hr0Awr', 0, '2022-09-01 02:09:49', '2022-09-01 02:09:49'),
(11, 2, 'Gaiman Anggriawan', '1232446611', 'theme/template/images/user.png', 'Mustofa', '1972517166029674', '(+62) 287 9295 962', 'PD Utami Tbk', 'naradi.sinaga@example.net', '2022-09-01 02:09:48', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'rremJOiLr0', 1, '2022-09-01 02:09:49', '2022-09-01 02:09:49');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `absensis`
--
ALTER TABLE `absensis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `absensis_user_id_foreign` (`user_id`);

--
-- Indexes for table `data_jobs`
--
ALTER TABLE `data_jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `data_jobs_user_id_foreign` (`user_id`),
  ADD KEY `data_jobs_kode_pasang_baru_foreign` (`kode_pasang_baru`);

--
-- Indexes for table `data_pasang_barus`
--
ALTER TABLE `data_pasang_barus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_role_id_foreign` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `absensis`
--
ALTER TABLE `absensis`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `data_jobs`
--
ALTER TABLE `data_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `data_pasang_barus`
--
ALTER TABLE `data_pasang_barus`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `absensis`
--
ALTER TABLE `absensis`
  ADD CONSTRAINT `absensis_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `data_jobs`
--
ALTER TABLE `data_jobs`
  ADD CONSTRAINT `data_jobs_kode_pasang_baru_foreign` FOREIGN KEY (`kode_pasang_baru`) REFERENCES `data_pasang_barus` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `data_jobs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `absensis` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
