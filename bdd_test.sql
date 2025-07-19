-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : database
-- Généré le : sam. 19 juil. 2025 à 15:13
-- Version du serveur : 8.0.41
-- Version de PHP : 8.2.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `team2i`
--

-- --------------------------------------------------------

--
-- Structure de la table `candidatures`
--

CREATE TABLE `candidatures` (
  `id` int NOT NULL,
  `offres_id` int NOT NULL,
  `clients_id` int NOT NULL,
  `consulte` tinyint(1) DEFAULT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `is_retenue` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `candidatures`
--

INSERT INTO `candidatures` (`id`, `offres_id`, `clients_id`, `consulte`, `created_at`, `is_retenue`) VALUES
(1, 145, 50, 0, '2025-06-16 14:58:24', NULL),
(2, 146, 50, 0, '2025-06-16 14:58:28', NULL),
(3, 147, 50, 0, '2025-06-16 14:58:31', NULL),
(4, 144, 51, 0, '2025-06-16 15:02:11', NULL),
(5, 145, 51, 0, '2025-06-16 15:02:42', NULL),
(6, 150, 51, 0, '2025-06-16 15:02:44', NULL),
(7, 151, 51, 0, '2025-06-16 15:02:47', NULL),
(8, 145, 52, 0, '2025-06-16 15:06:04', NULL),
(9, 146, 52, 0, '2025-06-16 15:06:08', NULL),
(10, 148, 52, 0, '2025-06-16 15:06:11', NULL),
(11, 150, 52, 0, '2025-06-16 15:06:13', NULL),
(12, 148, 50, 0, '2025-06-16 16:40:15', NULL),
(13, 148, 58, 0, '2025-06-16 16:45:13', NULL),
(14, 144, 55, 0, '2025-06-22 14:31:04', NULL),
(15, 145, 55, 0, '2025-06-22 14:31:08', NULL),
(16, 146, 55, 0, '2025-06-22 14:31:10', NULL),
(17, 148, 55, 0, '2025-06-22 14:31:12', NULL),
(18, 150, 55, 0, '2025-06-22 15:24:08', NULL),
(22, 149, 55, 0, '2025-06-22 16:24:28', NULL),
(23, 149, 51, 0, '2025-06-22 16:27:58', NULL),
(24, 147, 52, 0, '2025-07-01 15:14:50', NULL),
(25, 149, 52, 0, '2025-07-01 15:16:51', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `clients`
--

CREATE TABLE `clients` (
  `id` int NOT NULL,
  `tjm` int DEFAULT NULL,
  `dispo` tinyint(1) DEFAULT NULL,
  `date_dispo_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  `cv_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `siren` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cv_file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `clients`
--

INSERT INTO `clients` (`id`, `tjm`, `dispo`, `date_dispo_at`, `cv_name`, `siren`, `cv_file`) VALUES
(50, 454, NULL, NULL, NULL, '481500932', NULL),
(51, 356, NULL, NULL, NULL, '671400580', NULL),
(52, 700, NULL, NULL, NULL, '416100077', NULL),
(53, 600, NULL, NULL, NULL, '344180852', NULL),
(54, 450, NULL, NULL, NULL, '824955850', NULL),
(55, 620, NULL, NULL, NULL, '464972753', NULL),
(58, 420, NULL, NULL, NULL, '758632148', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Déchargement des données de la table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20250129030749', '2025-07-19 15:07:01', 115),
('DoctrineMigrations\\Version20250209151923', '2025-07-19 15:07:02', 0),
('DoctrineMigrations\\Version20250210135752', '2025-07-19 15:07:02', 0),
('DoctrineMigrations\\Version20250224150101', '2025-07-19 15:07:02', 0),
('DoctrineMigrations\\Version20250528221629', '2025-07-19 15:07:02', 0),
('DoctrineMigrations\\Version20250529185538', '2025-07-19 15:07:02', 0),
('DoctrineMigrations\\Version20250601050611', '2025-07-19 15:07:02', 0),
('DoctrineMigrations\\Version20250621160448', '2025-06-21 16:38:16', 744),
('DoctrineMigrations\\Version20250622150438', '2025-06-22 15:14:21', 334),
('DoctrineMigrations\\Version20250719150027', '2025-07-19 15:07:02', 333);

-- --------------------------------------------------------

--
-- Structure de la table `messenger_messages`
--

CREATE TABLE `messenger_messages` (
  `id` bigint NOT NULL,
  `body` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `headers` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue_name` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `available_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `delivered_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `offres`
--

CREATE TABLE `offres` (
  `id` int NOT NULL,
  `societes_id` int NOT NULL,
  `nom` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tarif` int DEFAULT NULL,
  `duree` int DEFAULT NULL,
  `lieu_mission` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `experience` int DEFAULT NULL,
  `profil` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `contraintes` longtext COLLATE utf8mb4_unicode_ci,
  `ref_mission` tinytext COLLATE utf8mb4_unicode_ci,
  `start_date_at` date NOT NULL COMMENT '(DC2Type:date_immutable)',
  `nb_candidatures` int DEFAULT NULL,
  `is_archive` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `offres`
--

INSERT INTO `offres` (`id`, `societes_id`, `nom`, `description`, `slug`, `tarif`, `duree`, `lieu_mission`, `is_active`, `experience`, `profil`, `contraintes`, `ref_mission`, `start_date_at`, `nb_candidatures`, `is_archive`) VALUES
(144, 43, 'Réseau', 'Saepe dolores illum aut beatae maiores. Impedit sed alias enim molestiae et vel sit magnam. Odio repudiandae a accusantium aliquid earum veniam assumenda porro. Placeat quia labore ducimus dolor est voluptatem atque qui.', 'reseau', 827, 17, 'Hebertdan', 0, 5, 'Neque quod magni quo officiis ut labore consequuntur. Est explicabo rerum a quasi debitis minus qui. Molestiae culpa occaecati placeat id ratione vel harum. Numquam non vel quod ducimus.', 'Possimus delectus deserunt eius molestiae quia voluptas nulla. Dolorum aut rerum et. Maxime placeat voluptatem sit.', '#YTGG545852', '2025-03-02', 2, 0),
(145, 48, 'Contrôleur de Gestion IT 75', 'qsdqsdqsdqsdqs', 'controleur-de-gestion-it-75', 452, 12, 'Paris', 1, 6, 'qsdqsdqsdqs', NULL, '#UJHG54516', '2025-03-27', 4, 0),
(146, 49, 'Dev  numérique 270', 'Dev ops en TT', 'dev-numerique-270-azer12', 740, 2, 'Clichy', 1, 3, 'Un profil professionnel', NULL, '#AZER12', '2025-05-29', 3, 0),
(147, 49, 'Développeur API', 'Harum inventore dolorem molestias adipisci officia voluptatum qui. Suscipit suscipit quis eum non. Molestias illum placeat delectus quis vel. Laboriosam provident qui officia rerum est natus. Beatae repellendus dolorem asperiores est quia voluptates nesciunt. Odit ratione a fac', 'developpeur-api', 405, 6, 'Paris', 1, 6, 'De formation Bac+2 à Bac +5 Ingénieur ou formation équivalente en informatique, vous justifiez d’une expérience similaire.\r\nVous êtes à l’aise dans l’environnement technique suivant :\r\nMaîtriser les outils Selenium, Robotframework, Jenkins, SoaopUI, NeoLoad, Squash, SonarQube\r\nMaîtriser la qualimétrie, les tests de performance et les tests de sécurité\r\nMaîtriser les concepts de la livraison continue et du déploiement continu\r\nExpérience : 5 ans minimum hors stage et alternance\r\nRémunération selon profil\r\nCet environnement technique vous parle et vous souhaitez vous investir sur des missions innovantes ? N’attendez plus et envoyez votre CV pour rejoindre nos équipes.', NULL, '#PHPPARIS8', '2025-05-29', 2, 0),
(148, 49, 'Développeur Front', 'Vous êtes à l’aise dans l’environnement technique suivant :\r\nMaîtriser les outils Selenium, Robotframework, Jenkins, SoaopUI,', 'developpeur-front', 250, 6, 'Lille', 1, 6, 'De formation Bac+2 à Bac +5 Ingénieur ou formation équivalente en informatique, vous justifiez d’une expérience similaire.\r\nVous êtes à l’aise dans l’environnement technique suivant :\r\nMaîtriser les outils Selenium, Robotframework, Jenkins, SoaopUI, NeoLoad, Squash, SonarQube\r\nMaîtriser la qualimétrie, les tests de performance et les tests de sécurité\r\nMaîtriser les concepts de la livraison continue et du déploiement continu\r\nExpérience : 5 ans minimum hors stage et alternance\r\nCV pour rejoindre nos équipes.', NULL, '#FRONTPHP8', '2025-05-29', 4, 0),
(149, 49, 'Reseau linux 75', 'De formation Bac+2 à Bac +5 Ingénieur ou formation équivalente en informatique, vous justifiez d’une expérience similaire.\r\nVous êtes à l’aise dans l’environnement technique suivant :\r\nMaîtriser les outils Selenium, Robotframework, Jenkins, SoaopUI, NeoLoad, Squash, SonarQube\r\nMaîtriser la qualimétrie, les tests de performance et les tests de sécurité', 'reseau-linux-75-nginx12sd3', 852, 3, 'Lens', 1, 3, 'De formation Bac+2 à Bac +5 Ingénieur ou formation équivalente en informatique, vous justifiez d’une expérience similaire.\r\nVous êtes à l’aise dans l’environnement technique suivant :\r\nMaîtriser les outils Selenium, Robotframework, Jenkins, SoaopUI, NeoLoad, S', NULL, '#Nginx12sd3', '2025-05-29', 3, 0),
(150, 49, 'Reseau Nginx', 'De formation Bac+2 à Bac +5 Ingénieur ou formation équivalente en informatique, vous justifiez d’une expérience similaire.\r\nVous êtes à l’aise dans l’environnement technique suivant :\r\nMaîtriser les outils Selenium, Robotframework, Jenkins, SoaopUI, NeoLoad, Squash, SonarQube\r\nMaîtriser la qualimétrie, les tests de performance et les tests de sécurité', 'reseau-nginx', 600, 6, 'Lens', 1, 3, 'De formation Bac+2 à Bac +5 Ingénieur ou formation équivalente en informatique, vous justifiez d’une expérience similaire.', NULL, '#Nginx12745', '2025-05-29', 2, 0),
(151, 49, 'Graphiste 92', 'De formation Bac+2 à Bac +5 Ingénieur ou formation équivalente en informatique, vous justifiez d’une expérience similaire.\r\nVous êtes à l’aise dans l’environnement technique suivant :\r\nMaîtriser les outils Selenium, Robotframework, Jenkins, SoaopUI, NeoLoad, Squash, SonarQube', 'graphiste-92-graph12', 650, 3, 'Monaco', 0, 4, 'De formation Bac+2 à Bac +5 Ingénieur ou formation équivalente en informatique, vous justifiez d’une expérience similaire.\r\nVous êtes à l’aise dans l’environnement technique suivant :\r\nMaîtriser les outils Selenium, Robotframework, Jenkins, SoaopUI, NeoLoad, Squash, SonarQube', '5 jours présentiel', '#Graph12', '2025-05-29', 1, 0),
(152, 47, 'Contrôleur de Gestion en finance', 'zedefzfsd fdgsdffgfdgdf', 'controleur-de-gestion-en-finance', 500, 6, 'Paris', 1, 2, 'dzefzef', NULL, 'UJHG74852', '2025-06-20', 0, 0),
(153, 48, 'Graphiste 3d', 'Graphiste en 3D', 'graphiste-3d', 450, 6, 'Paris', 1, 6, 'junior', 'Aucune', 'UJHG54516', '2025-07-03', 0, 0);

-- --------------------------------------------------------

--
-- Structure de la table `offres_skills`
--

CREATE TABLE `offres_skills` (
  `offres_id` int NOT NULL,
  `skills_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `regions`
--

CREATE TABLE `regions` (
  `id` int NOT NULL,
  `nom` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `reset_password_request`
--

CREATE TABLE `reset_password_request` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `selector` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hashed_token` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `requested_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `expires_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `skills`
--

CREATE TABLE `skills` (
  `id` int NOT NULL,
  `users_id` int NOT NULL,
  `nom` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `societes`
--

CREATE TABLE `societes` (
  `id` int NOT NULL,
  `nom_contact` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `num_contact` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `secteur_activite` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_contact` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `siret` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `societes`
--

INSERT INTO `societes` (`id`, `nom_contact`, `num_contact`, `image_name`, `description`, `secteur_activite`, `phone_contact`, `siret`) VALUES
(43, NULL, NULL, NULL, 'Assumenda similique modi voluptatem autem. Tempora quod aut repellat voluptas eum. Ullam culpa et eos. Perferendis aut maiores officiis ea dolor voluptatibus. Ea alias corporis distinctio harum asperiores rerum unde. Voluptates ratione rem esse nihil quam recusandae. Magnam et enim ut incidunt ut.', NULL, NULL, '57794303800072'),
(44, NULL, NULL, NULL, 'Dolores est explicabo vel. Sed omnis et qui veniam eos quia unde. Nemo quia distinctio eius. Est culpa voluptatem sed ab sequi a consequuntur. Beatae in harum et harum inventore aspernatur voluptas delectus. Quia quae modi rerum provident.', NULL, NULL, '34031867402617'),
(45, NULL, NULL, NULL, 'Dolore perspiciatis qui aspernatur fugiat corrupti ea. Quisquam aut aut ea magni neque amet inventore. Officia aut non rerum nihil praesentium voluptates sit possimus.', NULL, NULL, '88708440807918'),
(46, NULL, NULL, NULL, 'Optio esse error aut. Omnis explicabo incidunt et quis ratione velit eum. Perspiciatis ipsam vel ex qui necessitatibus fugit.', NULL, NULL, '69825479459727'),
(47, NULL, NULL, NULL, 'Nulla quidem vel sint amet corporis. Aut voluptatem fugit quaerat numquam laborum id. Alias dignissimos debitis assumenda omnis. Impedit fugit officia voluptatem sit soluta.', 'Sport', NULL, '36847680006543'),
(48, NULL, NULL, NULL, 'Sit voluptatem tempore fugiat voluptatibus inventore excepturi vitae. Ea minima alias eum eaque vel et. Ipsum nemo voluptatem possimus facilis quia autem facilis. Ut ut aliquam asperiores magnam. Nihil laudantium consequatur accusantium enim.', NULL, NULL, '18795725300771'),
(49, NULL, NULL, NULL, 'Le sport le sport', 'Sport', NULL, '17604235600814'),
(56, NULL, NULL, NULL, 'dfsfsdfsdf', 'SS2i', NULL, '66515174853698'),
(57, NULL, NULL, NULL, 'qdsqsdqsd', 'Info', NULL, '85178523698745'),
(59, 'Tony', NULL, NULL, 'o', 'Finance', '0685742586', '74542589635874');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` json NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `adresse` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cp` int NOT NULL,
  `ville` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type_user` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_inscription_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  `is_verified` tinyint(1) NOT NULL,
  `is_newsletter` tinyint(1) DEFAULT NULL,
  `last_longin_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  `discr` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `confirmation_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `email`, `roles`, `password`, `nom`, `adresse`, `cp`, `ville`, `phone`, `type_user`, `date_inscription_at`, `is_verified`, `is_newsletter`, `last_longin_at`, `discr`, `confirmation_token`) VALUES
(43, 'blanchard@gmail.com', '[\"ROLE_USER\", \"ROLE_ADMIN\"]', '$2y$13$Mq0QaDOlv7r2itUs/t.k5OcBkkLyb0Z6tIH3N6nZ/C6e3VFtFhqEy', 'Marchal S.A.', 'Suite 764', 74289, 'Morin', '+33 (0)7 74 81 27 96', 'societes', NULL, 1, 0, NULL, 'societes', NULL),
(44, 'allain.roland@poulain.org', '[\"ROLE_USER\", \"ROLE_SOCIETE\"]', '$2y$13$OV2g/5o8xoU6b93zdeB3aepxYll3yLMDxFFHRbZioHrxo/GARdIXS', 'Blanc SARL', 'Suite 285', 66335, 'Bernard', '+33 (0)6 20 40 61 34', 'societes', NULL, 1, 0, NULL, 'societes', NULL),
(45, 'nmercier@delmas.fr', '[\"ROLE_USER\", \"ROLE_SOCIETE\"]', '$2y$13$SeP5LXtgu5q4cxSUu5xpX.OOPgMEGJ05/6kOWImmi/ZbaqEhp1ehW', 'Schneider', 'Suite 150', 34220, 'Fischer', '06 82 34 65 15', 'societes', NULL, 1, 0, NULL, 'societes', NULL),
(46, 'laurent.valette@louis.fr', '[\"ROLE_USER\", \"ROLE_SOCIETE\"]', '$2y$13$kTOOXF661mg4lD1vhHXl5e9kNgFyquInpLsJPDhjD506AiRA0xb2G', 'Gay Buisson SA', 'Étage 952', 43717, 'Marin', '0695592260', 'societes', NULL, 1, 0, NULL, 'societes', NULL),
(47, 'josette.traore@letellier.fr', '[\"ROLE_USER\", \"ROLE_SOCIETE\"]', '$2y$13$vkIS2AVJrMDR1j.mxDAwPeurPKm48RGelJjm22LKfWZ4cQDaHiyOC', 'Brun', 'Bât. 517', 48290, 'Torresnec', '0761661146', 'societes', NULL, 1, 1, NULL, 'societes', NULL),
(48, 'raymond.anouk@wanadoo.fr', '[\"ROLE_USER\", \"ROLE_SOCIETE\"]', '$2y$13$r7QAbnmwyEBwiI.UFQhTFODvxm3kcuH1/xkGZA5fkzmaxnw8Se5tO', 'Pierre Garcia SAS', 'Suite 553', 51325, 'Lecoqdan', '07 36 90 90 53', 'societes', NULL, 1, 0, NULL, 'societes', NULL),
(49, 'nrousseau@gmail.com', '[\"ROLE_USER\", \"ROLE_SOCIETE\"]', '$2y$13$Mvg56Kp0NIt4JlfVquv1wuV/8DQJLfSQrnjV1j8YfO8hGQ0/geZ5e', 'Marion V', 'Bât. 166', 70614, 'Riviere', '0637765375', 'societes', NULL, 1, 1, NULL, 'societes', NULL),
(50, 'guillaume06@vallet.com', '[\"ROLE_USER\", \"ROLE_CLIENT\"]', '$2y$13$BfPJJoAF7k5L/YATLaq67OB2V17KG3HWexOburvHnDR8.80LK2ZXK', 'boyer.adelaide', 'Bât. 248', 75014, 'Paris', '0735769840', 'clients', NULL, 1, 1, NULL, 'clients', NULL),
(51, 'bertrand.elisabeth@raymond.fr', '[\"ROLE_USER\", \"ROLE_CLIENT\"]', '$2y$13$dRBnYCAls9P3UY3UGfaxGu4GvqEIf1F71G6KcikhfZoMHAA/lkczG', 'npierre', 'Suite 502', 49488, 'Rochernec', '07 56 79 80 66', 'clients', NULL, 1, 0, NULL, 'clients', NULL),
(52, 'clerc.manon@alexandre.fr', '[\"ROLE_USER\", \"ROLE_CLIENT\"]', '$2y$13$ReudDu2gPfoiP./c1BYrV.3VF7fdK5PZGFckfDqgG5vti/hA7pFA.', 'dias.jacqueline', 'Suite 153', 75394, 'Brunet-sur-Philippe', '07 61 26 28 65', 'clients', NULL, 1, 0, NULL, 'clients', NULL),
(53, 'suzanne50@berger.fr', '[\"ROLE_USER\", \"ROLE_CLIENT\"]', '$2y$13$/Fga6NMganhOZK7jIeWk1eOWKlW5.w0/qwjG2aE2DrWSw5m/Uv7KG', 'adrien15', 'Suite 006', 6085, 'Le Roux-la-Forêt', '+33 6 36 73 52 35', 'clients', NULL, 1, 0, NULL, 'clients', NULL),
(54, 'gauthier.emmanuelle@laposte.net', '[\"ROLE_USER\", \"ROLE_CLIENT\"]', '$2y$13$6fsPZk4RYGMRQOm.phzWP.wDFNGt1C8C88ab57szH1clCzTWtrrt2', 'imary', 'Étage 201', 80705, 'Blin', '+33 6 23 27 18 81', 'clients', NULL, 1, 0, NULL, 'clients', NULL),
(55, 'gilles.lesage@noos.fr', '[\"ROLE_USER\", \"ROLE_CLIENT\"]', '$2y$13$244VY.XXhSB1bXONA5qtLuKbqCt48tQ2R7mu.gCC0Iboba9.iX2Sy', 'laurent.maillard', 'Apt. 175', 65170, 'Ruiz', '0739539597', 'clients', NULL, 1, 0, NULL, 'clients', NULL),
(56, 'blan8552@gmail.com', '[\"ROLE_USER\", \"ROLE_SOCIETE\"]', '$2y$13$CVOSxPaAPp0JZZExCCejY.VPUaw9XhOwBK9Riijroa/azSQQlB84m', 'Weer Ar', '23 avenue du docteur Fleming', 92600, 'Asnières sur seine', '0626179334', 'societes', NULL, 1, 0, NULL, 'societes', NULL),
(57, 'blancho74@gmail.com', '[\"ROLE_USER\", \"ROLE_SOCIETE\"]', '$2y$13$.N3tADw9z3G27jlY621I6u.SNu50McIBoBOq2Xubebd1FTFGtR.zW', 'MPO', '23 avenue du docteur Fleming', 92600, 'Asnières sur seine', '0626179337', 'societes', NULL, 1, 0, NULL, 'societes', NULL),
(58, 'g.roux@gmail.com', '[\"ROLE_USER\", \"ROLE_CLIENT\"]', '$2y$13$DvrlbkcO8Wjuj/1d5U3diuwoMSmrXBFI7Iz3K/bEMrc4.YhrTOQT6', 'Guy Roux', '14 rue lecombe', 92600, 'Nantes', '0685742574', 'clients', NULL, 1, 1, NULL, 'clients', NULL),
(59, 'promod@gmail.com', '[\"ROLE_USER\", \"ROLE_SOCIETE\"]', '$2y$13$zWFHh7LMP5oqJMW34L0Oi.wHlilyj6DU6fyelb8.z/vY4UZnteiR2', 'Boi', 'or', 74125, 'Paris', '0125854796', 'societes', NULL, 1, 1, NULL, 'societes', NULL);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `candidatures`
--
ALTER TABLE `candidatures`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_DE57CF666C83CD9F` (`offres_id`),
  ADD KEY `IDX_DE57CF66AB014612` (`clients_id`);

--
-- Index pour la table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Index pour la table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  ADD KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  ADD KEY `IDX_75EA56E016BA31DB` (`delivered_at`);

--
-- Index pour la table `offres`
--
ALTER TABLE `offres`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_C6AC35447E841BEA` (`societes_id`);

--
-- Index pour la table `offres_skills`
--
ALTER TABLE `offres_skills`
  ADD PRIMARY KEY (`offres_id`,`skills_id`),
  ADD KEY `IDX_2E00F5CA6C83CD9F` (`offres_id`),
  ADD KEY `IDX_2E00F5CA7FF61858` (`skills_id`);

--
-- Index pour la table `regions`
--
ALTER TABLE `regions`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `reset_password_request`
--
ALTER TABLE `reset_password_request`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_7CE748AA76ED395` (`user_id`);

--
-- Index pour la table `skills`
--
ALTER TABLE `skills`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_D531167067B3B43D` (`users_id`);

--
-- Index pour la table `societes`
--
ALTER TABLE `societes`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_IDENTIFIER_EMAIL` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `candidatures`
--
ALTER TABLE `candidatures`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT pour la table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `offres`
--
ALTER TABLE `offres`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=154;

--
-- AUTO_INCREMENT pour la table `regions`
--
ALTER TABLE `regions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `reset_password_request`
--
ALTER TABLE `reset_password_request`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `skills`
--
ALTER TABLE `skills`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `candidatures`
--
ALTER TABLE `candidatures`
  ADD CONSTRAINT `FK_DE57CF666C83CD9F` FOREIGN KEY (`offres_id`) REFERENCES `offres` (`id`),
  ADD CONSTRAINT `FK_DE57CF66AB014612` FOREIGN KEY (`clients_id`) REFERENCES `clients` (`id`);

--
-- Contraintes pour la table `clients`
--
ALTER TABLE `clients`
  ADD CONSTRAINT `FK_C82E74BF396750` FOREIGN KEY (`id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `offres`
--
ALTER TABLE `offres`
  ADD CONSTRAINT `FK_C6AC35447E841BEA` FOREIGN KEY (`societes_id`) REFERENCES `societes` (`id`);

--
-- Contraintes pour la table `offres_skills`
--
ALTER TABLE `offres_skills`
  ADD CONSTRAINT `FK_2E00F5CA6C83CD9F` FOREIGN KEY (`offres_id`) REFERENCES `offres` (`id`),
  ADD CONSTRAINT `FK_2E00F5CA7FF61858` FOREIGN KEY (`skills_id`) REFERENCES `skills` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `reset_password_request`
--
ALTER TABLE `reset_password_request`
  ADD CONSTRAINT `FK_7CE748AA76ED395` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `skills`
--
ALTER TABLE `skills`
  ADD CONSTRAINT `FK_D531167067B3B43D` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `societes`
--
ALTER TABLE `societes`
  ADD CONSTRAINT `FK_AEC76507BF396750` FOREIGN KEY (`id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
