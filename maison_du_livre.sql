-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : jeu. 01 mai 2025 à 07:42
-- Version du serveur : 9.1.0
-- Version de PHP : 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `maison_du_livre`
--

-- --------------------------------------------------------

--
-- Structure de la table `cache`
--

DROP TABLE IF EXISTS `cache`;
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel_cache_root@gmail.com|127.0.0.1:timer', 'i:1746073391;', 1746073391),
('laravel_cache_root@gmail.com|127.0.0.1', 'i:1;', 1746073391),
('laravel_cache_da4b9237bacccdf19c0760cab7aec4a8359010b0:timer', 'i:1746079731;', 1746079731),
('laravel_cache_da4b9237bacccdf19c0760cab7aec4a8359010b0', 'i:1;', 1746079731),
('laravel_cache_test@gmail.com|127.0.0.1:timer', 'i:1746082511;', 1746082511),
('laravel_cache_test@gmail.com|127.0.0.1', 'i:2;', 1746082511),
('laravel_cache_livewire-rate-limiter:a17961fa74e9275d529f489537f179c05d50c2f3:timer', 'i:1746073461;', 1746073461),
('laravel_cache_livewire-rate-limiter:a17961fa74e9275d529f489537f179c05d50c2f3', 'i:1;', 1746073461);

-- --------------------------------------------------------

--
-- Structure de la table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `cds`
--

DROP TABLE IF EXISTS `cds`;
CREATE TABLE IF NOT EXISTS `cds` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `titre` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `artiste` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `genre` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nb_pistes` int DEFAULT NULL,
  `duree` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_sortie` date DEFAULT NULL,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nb_exemplaires` int NOT NULL DEFAULT '1',
  `disponible` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `cds`
--

INSERT INTO `cds` (`id`, `titre`, `artiste`, `genre`, `nb_pistes`, `duree`, `date_sortie`, `image`, `nb_exemplaires`, `disponible`, `created_at`, `updated_at`) VALUES
(4, 'Thriller ', 'Michael Jackson', 'pop', 9, '42:19', '1982-11-30', 'cd-images/01JT56VPP8DTRERJFTFJCX7KKS.jpg', 5, 1, '2025-05-01 03:59:37', '2025-05-01 03:59:37'),
(5, 'Random Access Memories', 'Daft Punk', 'electro', 13, '74:24', '2013-05-17', 'cd-images/01JT56Z5YSR1C2SK9CEJW15SC0.jpg', 1, 1, '2025-05-01 04:01:31', '2025-05-01 04:32:39'),
(6, 'Nevermind', 'Nirvana', 'rock', 13, '49:15', '1991-09-24', 'cd-images/01JT575PV8NGR0RMQ3BPV6QPMD.jpg', 13, 1, '2025-05-01 04:05:05', '2025-05-01 04:05:05'),
(7, 'Awaiting Extinction', 'Horror Within', 'bande originale', 6, '20 ', '2022-07-15', 'cd-images/01JT57AV070ZWFDRJAV3SC2RA1.jpg', 5, 1, '2025-05-01 04:07:53', '2025-05-01 04:31:46');

-- --------------------------------------------------------

--
-- Structure de la table `emprunts`
--

DROP TABLE IF EXISTS `emprunts`;
CREATE TABLE IF NOT EXISTS `emprunts` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `livre_id` bigint UNSIGNED DEFAULT NULL,
  `cd_id` bigint UNSIGNED DEFAULT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `date_emprunt` date NOT NULL,
  `date_retour_prevue` date NOT NULL,
  `date_retour_effective` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `emprunts_livre_id_foreign` (`livre_id`),
  KEY `emprunts_adherent_id_foreign` (`user_id`),
  KEY `emprunts_cd_id_foreign` (`cd_id`)
) ENGINE=MyISAM AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `emprunts`
--

INSERT INTO `emprunts` (`id`, `livre_id`, `cd_id`, `user_id`, `date_emprunt`, `date_retour_prevue`, `date_retour_effective`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 2, '2025-04-30', '2025-05-14', '2025-05-01', '2025-04-30 18:22:01', '2025-04-30 20:53:48'),
(2, 1, NULL, 2, '2025-04-30', '2025-05-14', '2025-04-30', '2025-04-30 18:52:03', '2025-04-30 19:03:29'),
(3, 1, NULL, 2, '2025-04-30', '2025-05-14', '2025-04-30', '2025-04-30 18:52:09', '2025-04-30 19:03:30'),
(4, 1, NULL, 2, '2025-04-30', '2025-05-14', '2025-04-30', '2025-04-30 18:52:21', '2025-04-30 19:03:31'),
(5, 1, NULL, 2, '2025-04-30', '2025-05-14', '2025-04-30', '2025-04-30 18:52:23', '2025-04-30 19:03:32'),
(6, 1, NULL, 2, '2025-04-30', '2025-05-14', '2025-04-30', '2025-04-30 18:53:09', '2025-04-30 19:03:33'),
(7, 1, NULL, 2, '2025-04-30', '2025-05-21', '2025-04-30', '2025-04-30 19:04:03', '2025-04-30 19:06:29'),
(8, 1, NULL, 2, '2025-04-30', '2025-05-21', '2025-04-30', '2025-04-30 19:10:31', '2025-04-30 19:18:44'),
(9, 1, NULL, 2, '2025-04-30', '2025-05-08', '2025-04-30', '2025-04-30 19:45:43', '2025-04-30 20:15:08'),
(10, 1, NULL, 8, '2025-04-30', '2025-05-09', '2025-04-30', '2025-04-30 19:56:45', '2025-04-30 20:15:09'),
(11, 7, NULL, 2, '2025-04-30', '2025-05-07', '2025-04-30', '2025-04-30 20:37:27', '2025-04-30 20:56:38'),
(14, 1, NULL, 2, '2025-04-30', '2025-05-07', '2025-04-30', '2025-04-30 21:02:44', '2025-04-30 21:03:06'),
(15, 1, NULL, 2, '2025-05-01', '2025-05-08', '2025-05-01', '2025-04-30 22:14:53', '2025-04-30 23:05:10'),
(16, 9, NULL, 2, '2025-05-01', '2025-05-08', '2025-05-01', '2025-04-30 23:03:04', '2025-04-30 23:05:07'),
(17, NULL, 1, 2, '2025-05-01', '2025-05-08', '2025-05-01', '2025-04-30 23:08:25', '2025-04-30 23:10:03'),
(18, 1, NULL, 2, '2025-05-01', '2025-05-02', '2025-05-01', '2025-04-30 23:08:39', '2025-05-01 00:18:24'),
(19, 9, NULL, 10, '2025-05-01', '2025-05-11', '2025-05-01', '2025-05-01 00:07:59', '2025-05-01 00:14:07'),
(20, 9, NULL, 2, '2025-05-01', '2025-05-08', '2025-05-01', '2025-05-01 00:12:50', '2025-05-01 00:14:10'),
(21, 1, NULL, 2, '2025-04-15', '2025-04-29', '2025-05-01', '2025-05-01 00:17:48', '2025-05-01 02:34:54'),
(22, 1, NULL, 10, '2025-05-01', '2025-05-11', '2025-05-01', '2025-05-01 02:22:48', '2025-05-01 02:34:48'),
(23, NULL, 1, 10, '2025-05-01', '2025-05-05', '2025-05-01', '2025-05-01 02:22:58', '2025-05-01 02:34:51'),
(24, NULL, 3, 2, '2025-05-01', '2025-05-08', '2025-05-01', '2025-05-01 03:05:28', '2025-05-01 03:07:15'),
(25, 1, NULL, 2, '2025-05-01', '2025-05-08', '2025-05-01', '2025-05-01 03:05:38', '2025-05-01 03:07:20'),
(31, 1, NULL, 10, '2025-04-09', '2025-04-20', NULL, '2025-05-01 04:53:37', '2025-05-01 05:00:05'),
(32, 1, NULL, 11, '2025-05-01', '2025-05-08', '2025-05-01', '2025-05-01 05:06:47', '2025-05-01 05:08:03');

-- --------------------------------------------------------

--
-- Structure de la table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `livres`
--

DROP TABLE IF EXISTS `livres`;
CREATE TABLE IF NOT EXISTS `livres` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `titre` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `auteur` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `genre` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nb_exemplaires` int NOT NULL DEFAULT '1',
  `disponible` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `isbn` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nombre_pages` int DEFAULT NULL,
  `edition` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `livres`
--

INSERT INTO `livres` (`id`, `titre`, `auteur`, `description`, `genre`, `nb_exemplaires`, `disponible`, `created_at`, `updated_at`, `image`, `isbn`, `nombre_pages`, `edition`) VALUES
(1, '1984 ', 'Goerge orwell', '1984 ou en toutes lettres Mil neuf cent quatre-vingt-quatre, est un roman dystopique de l\'écrivain britannique George Orwell. Publié le 8 juin 1949 par Secker & Warburg, il s\'agit du neuvième et dernier livre d\'Orwell achevé de son vivant.', 'essai', 1, 1, '2025-04-30 18:21:30', '2025-05-01 05:08:03', 'livres/01JT45S4FXJWYWGJM3FEJ2CW9X.jpg', '9780451524935', 328, 'Plume'),
(11, 'L\'Étranger', 'Albert Camus', 'L\'Étranger est le premier roman publié d\'Albert Camus, paru en 1942. Les premières esquisses datent de 1938, mais le roman ne prend vraiment forme que dans les premiers mois de 1940 et sera travaillé par Camus jusqu’en 1941. Il prend place dans la tétralogie que Camus nommera « cycle de l\'absurde » qui décrit les fondements de la philosophie camusienne : l\'absurde. Cette tétralogie comprend également l\'essai Le Mythe de Sisyphe ainsi que les pièces de théâtre Caligula et Le Malentendu.', 'roman', 0, 0, '2025-05-01 02:40:35', '2025-05-01 02:40:35', 'livres/01JT52AZGV6GP4KB67MQ4H2TE6.jpg', '2336542443', 159, 'Éditions Gallimard'),
(10, 'Fahrenheit 451', 'Ray Bradbury', 'Fahrenheit 451 est un roman d\'anticipation dystopique[1] de Ray Bradbury publié en 1953 aux États-Unis. Il présente une société américaine future où les livres ont été interdits et les « pompiers » brûlent ceux qu\'ils trouvent. Le roman suit le point de vue de Guy Montag, un pompier prenant goût à la lecture et quittant finalement son travail de censure et de destruction des connaissances pour s\'engager dans la préservation des écrits littéraires et culturels.', 'roman', 6, 1, '2025-05-01 02:39:01', '2025-05-01 04:31:50', 'livres/01JT5284JWREDHXDNM3RAMRCCM.jpg', '2321334432', 236, 'Ballantine Books'),
(9, 'Harry Potter à l’école des sorciers', 'J.K. Rowling', 'Harry découvre le jour de ses 11 ans qu’il est un sorcier. Il entre à Poudlard, l’école de sorcellerie, où il vivra sa première année pleine de mystères, de magie et de dangers.', 'roman', 4, 1, '2025-04-30 21:03:42', '2025-05-01 02:54:42', 'livres/01JT4F252R27B8B29SQA1JYY9Z.jpg', '9782070643028', 320, 'Gallimard Jeunesse'),
(12, 'Les Misérables', 'Victor Hugo', 'C\'est un roman historique, social et philosophique dans lequel on retrouve les idéaux du romantisme et ceux de Victor Hugo concernant la nature humaine. La préface résume clairement les intentions de l\'auteur : « Tant que les trois problèmes du siècle, la dégradation de l’homme par le prolétariat, la déchéance de la femme par la faim, l\'atrophie de l\'enfant par la nuit, ne seront pas résolus ; en d’autres termes, et à un point de vue plus étendu encore, tant qu’il y aura sur la terre ignorance et misère, des livres de la nature de celui-ci pourront ne pas être inutiles ».', 'roman', 1, 1, '2025-05-01 02:44:50', '2025-05-01 02:44:50', 'livres/01JT52JS8QC1Q0W4T09B172ZZ8.jpg', '23426844', 259, 'Albert Lacroix'),
(13, 'Le Petit Prince', 'Antoine de Saint-Exupéry', 'Le Petit Prince est un roman français. Livre à succès, il est l\'œuvre la plus connue d\'Antoine de Saint-Exupéry. Publié en français en 1943 à New York, simultanément à sa traduction anglaise, c\'est une œuvre poétique et philosophique sous l\'apparence d\'un conte illustré pour enfants.\nParu en plus de six cents langues et dialectes différents, Le Petit Prince est l\'ouvrage le plus traduit au monde après la Bible.', 'jeunesse', 4, 1, '2025-05-01 02:47:38', '2025-05-01 02:47:38', 'livres/01JT52QX4708CPYRTFJMCSP45J.jpg', '9782070612758', 96, 'Gallimard'),
(14, 'Sapiens : Une brève histoire de l’humanité', 'Yuval Noah Harari', 'Une exploration fascinante de l’histoire de l’humanité, de l’âge de pierre à nos jours.', 'essai', 3, 1, '2025-05-01 02:49:02', '2025-05-01 02:49:02', 'livres/01JT52TF1Z3SW6C8KVJK6RVJHD.jpg', '9782226394123', 500, 'Albin Michel'),
(15, 'Tintin au Tibet', 'Hergé', 'Tintin au Tibet est le vingtième album de la série de bande dessinée Les Aventures de Tintin, créée par le dessinateur belge Hergé. L\'histoire est d\'abord prépubliée du 17 septembre 1958 au 25 novembre 1959 dans les pages du journal Tintin avant d\'être éditée en album de soixante-deux planches aux éditions Casterman.', 'bd', 10, 1, '2025-05-01 02:51:01', '2025-05-01 02:51:01', 'livres/01JT52Y2XTK0K0GMJAHAHEZJE7.jpg', '9782203001174', 62, 'Casterman'),
(16, 'La Passe-miroir - Les fiancés de l’hiver', 'Christelle Dabos', 'Sous ses lunettes de myope, Ophélie cache des dons singuliers: elle peut lire le passé des objets et traverser les miroirs.\nQuand on la fiance à Thorn, du puissant clan des Dragons, la jeune fille doit quitter sa famille et le suivre à la Citacielle, capitale flottante du Pôle.\nA quelle fin a-t-elle été choisie? Sans le savoir, Ophélie devient le jouet d\'un complot mortel.', 'jeunesse', 6, 1, '2025-05-01 02:52:45', '2025-05-01 02:52:45', 'livres/01JT5318QNCM044VPFVVQ1HR7K.jpg', '9782070661985', 528, 'Gallimard Jeunesse');

-- --------------------------------------------------------

--
-- Structure de la table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_04_26_032714_create_livres_table', 1),
(5, '2025_04_26_110116_create_emprunts_table', 1),
(6, '2025_04_27_194123_add_image_to_livres_table', 1),
(7, '2025_04_29_202222_add_infos_to_livres_table', 1),
(8, '2025_04_30_010358_add_role_to_users_table', 1),
(9, '2025_04_30_010556_add_adherent_fields_to_users_table', 1),
(10, '2025_04_30_015746_drop_adherents_table', 1),
(11, '2025_04_30_050200_rename_adherent_id_to_user_id_in_emprunts_table', 1),
(12, '2025_04_30_230952_create_cds_table', 2),
(15, '2025_04_30_231750_add_stock_to_cds_table', 3),
(17, '2025_04_30_235739_add_cd_id_to_emprunts_table', 4);

-- --------------------------------------------------------

--
-- Structure de la table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('37U7vK6QYh9NEEQ1uh8BmI7dNofpCJ98p2KQWmxn', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoic0x4TURDWE05N3JEaEtZRjJzNTdjQTVOWlBOWFV2RHdZZmtaV0RJYiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbiI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjI7czoxNzoicGFzc3dvcmRfaGFzaF93ZWIiO3M6NjA6IiQyeSQxMiRQLy54Z0tXQUU5S3ZqMG8zWFIwejBPMi42bWRoR1UzUVBkWXl0NE1oaTNyZU0yLkV6d1hRdSI7czo4OiJmaWxhbWVudCI7YTowOnt9fQ==', 1746084408);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'adherent',
  `prenom` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telephone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `adresse` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ville` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_postal` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_inscription` date DEFAULT NULL,
  `date_expiration` date DEFAULT NULL,
  `actif` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `role`, `prenom`, `telephone`, `adresse`, `ville`, `code_postal`, `date_inscription`, `date_expiration`, `actif`) VALUES
(2, 'Admin', 'admin@example.com', '2025-04-30 17:00:05', '$2y$12$P/.xgKWAE9Kvj0o3XR0z0O2.6mdhGU3QPdYyt4Mhi3reM2.EzwXQu', NULL, '2025-04-30 17:00:05', '2025-05-01 05:26:41', 'admin', 'User', '05-05-05-05-05', '180 avenues', 'Montpellier', '34000', '2025-04-30', '2027-02-01', 1),
(8, 'Benadrouche', 'mohamed.benadrouche@gmail.com', NULL, '$2y$12$6VwkDRgyDzvJkeh8QqQ6oOHQjpDGy6l5nciRXLe5BgjCiwQQnLHUG', NULL, '2025-04-30 19:55:55', '2025-05-01 04:48:29', 'adherent', 'Yanis', '06-80-42-47-96', '198 avenues des droits', 'Montpellier', '34000', '2025-02-26', '2025-04-15', 0),
(10, 'Clara', 'Clara@gmail.com', NULL, '$2y$12$AX4QXppT3jnlJy9PAq8kv.QJo6XdZYE2MvVo3ORu1ZKunGsYiVobS', NULL, '2025-04-30 20:03:04', '2025-05-01 04:49:51', 'adherent', 'Martin', '06-83-77-96-64', '14 Rue du Faubourg du Courreau', 'Montpellier', '34000', '2025-04-30', '2025-05-30', 1);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
