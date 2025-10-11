-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : database
-- Généré le : jeu. 09 oct. 2025 à 00:04
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
  `is_retenue` tinyint(1) DEFAULT NULL,
  `slug` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` longtext COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `candidatures`
--

INSERT INTO `candidatures` (`id`, `offres_id`, `clients_id`, `consulte`, `created_at`, `is_retenue`, `slug`, `message`) VALUES
(39, 150, 75, 1, '2025-09-15 15:50:58', 1, 'reseau-nginx15', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley'),
(40, 159, 75, 1, '2025-09-15 15:51:14', 1, 'technicien-de-maintenance', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley'),
(41, 151, 75, 1, '2025-09-15 15:51:21', 0, 'graphiste-92-graph12', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley'),
(42, 158, 75, 0, '2025-09-15 15:54:09', NULL, 'controleur-de-gestion-it-85', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.'),
(43, 151, 69, 1, '2025-09-15 15:58:23', 0, 'graphiste-92-graph12', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.'),
(44, 158, 69, 0, '2025-09-15 15:58:26', NULL, 'controleur-de-gestion-it-85', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.');

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
(69, 454, NULL, NULL, 'cv-2025-68c8377cb119d.pdf', '748521458', NULL),
(75, 745, NULL, NULL, 'cv-2025-68c6ceaf8067d.pdf', '062617852', NULL),
(82, NULL, NULL, NULL, NULL, '626179374', NULL),
(83, NULL, NULL, NULL, NULL, '626179214', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `clients_skills`
--

CREATE TABLE `clients_skills` (
  `clients_id` int NOT NULL,
  `skills_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
('DoctrineMigrations\\Version20250129030749', '2025-10-01 12:43:49', 59),
('DoctrineMigrations\\Version20250209151923', '2025-10-01 12:43:49', 0),
('DoctrineMigrations\\Version20250210135752', '2025-10-01 12:43:49', 0),
('DoctrineMigrations\\Version20250224150101', '2025-10-01 12:43:49', 0),
('DoctrineMigrations\\Version20250528221629', '2025-10-01 12:43:49', 0),
('DoctrineMigrations\\Version20250529185538', '2025-10-01 12:43:49', 0),
('DoctrineMigrations\\Version20250601050611', '2025-10-01 12:43:49', 0),
('DoctrineMigrations\\Version20250621160448', '2025-10-01 12:43:49', 0),
('DoctrineMigrations\\Version20250622150438', '2025-10-01 12:43:49', 0),
('DoctrineMigrations\\Version20250719150027', '2025-10-01 12:43:50', 0),
('DoctrineMigrations\\Version20250909003102', '2025-10-01 12:43:50', 0),
('DoctrineMigrations\\Version20251001123838', '2025-10-01 12:43:50', 1164);

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
(149, 57, 'Reseau linux 75', 'De formation Bac+2 à Bac +5 Ingénieur ou formation équivalente en informatique, vous justifiez d’une expérience similaire.\r\nVous êtes à l’aise dans l’environnement technique suivant :\r\nMaîtriser les outils Selenium, Robotframework, Jenkins, SoaopUI, NeoLoad, Squash, SonarQube\r\nMaîtriser la qualimétrie, les tests de performance et les tests de sécurité', 'reseau-linux-75-nginx12sd3', 852, 3, 'Lens', 0, 3, 'De formation Bac+2 à Bac +5 Ingénieur ou formation équivalente en informatique, vous justifiez d’une expérience similaire.\r\nVous êtes à l’aise dans l’environnement technique suivant :\r\nMaîtriser les outils Selenium, Robotframework, Jenkins, SoaopUI, NeoLoad, S', NULL, '#Nginx12sd3', '2025-05-29', 0, 0),
(150, 49, 'Reseau Nginx15', 'De formation Bac+2 à Bac +5 Ingénieur ou formation équivalente en informatique, vous justifiez d’une expérience similaire.\r\nVous êtes à l’aise dans l’environnement technique suivant :\r\nMaîtriser les outils Selenium, Robotframework, Jenkins, SoaopUI, NeoLoad, Squash, SonarQube\r\nMaîtriser la qualimétrie, les tests de performance et les tests de sécurité', 'reseau-nginx15', 600, 6, 'Lens', 1, 3, 'De formation Bac+2 à Bac +5 Ingénieur ou formation équivalente en informatique, vous justifiez d’une expérience similaire.', NULL, '#Nginx12745', '2025-05-29', 0, 0),
(151, 49, 'Graphiste 92', 'De formation Bac+2 à Bac +5 Ingénieur ou formation équivalente en informatique, vous justifiez d’une expérience similaire.\r\nVous êtes à l’aise dans l’environnement technique suivant :\r\nMaîtriser les outils Selenium, Robotframework, Jenkins, SoaopUI, NeoLoad, Squash, SonarQube', 'graphiste-92-graph12', 650, 3, 'Monaco', 1, 4, 'De formation Bac+2 à Bac +5 Ingénieur ou formation équivalente en informatique, vous justifiez d’une expérience similaire.\r\nVous êtes à l’aise dans l’environnement technique suivant :\r\nMaîtriser les outils Selenium, Robotframework, Jenkins, SoaopUI, NeoLoad, Squash, SonarQube', '5 jours présentiel', '#Graph12', '2025-05-29', 1, 0),
(158, 57, 'Contrôleur de Gestion IT 85', 'qsdqsdqs', 'controleur-de-gestion-it-85', 750, 5, 'Lille', 1, 8, 'qsdsdqsd', NULL, NULL, '2025-08-24', 2, 0),
(159, 49, 'Technicien de Maintenance', 'Vos missions : \r\n\r\n- Préparation des interventions planifiées (préparation du matériel, diagnostic de pannes...)\r\n\r\n- Intervention chez les clients pour installer ou maintenir le matériel informatique\r\n\r\n- Détection de l\'origine des pannes/dysfonctionnements \r\n\r\n- Formation et accompagnement des clients en fonction des besoins\r\n\r\n- Clôture des interventions réalisées (actions informatiques, logistiques, administratives)', 'technicien-de-maintenance', 410, 5, 'Lille', 1, 2, 'Votre Profil :\r\n\r\n- Vous disposez d\'un diplôme en informatique niveau Bac+2\r\n\r\n- Vous avez une première expérience ou non sur un poste similaire\r\n\r\n- Vous êtes rigoureux et organisé', 'Adsearch vous propose des milliers d\'opportunités de carrières dans toute la France pour les profils d\'experts, cadres et managers. Que ce soit pour un emploi ou pour une mission, Adsearch c\'est le cabinet de recrutement qu\'il vous faut ! Retrouvez toutes nos offres d’emploi en CDI, CDD, Intérim et Freelance sur notre site internet !', NULL, '2025-09-25', 0, 0),
(162, 49, 'Graphiste Amo', 'Graphiste Amo', 'graphiste-amo', 852, 6, 'Clichy', 1, 2, 'Graphiste AmoGraphiste Amo', NULL, NULL, '2025-10-08', 0, 0);

-- --------------------------------------------------------

--
-- Structure de la table `offres_skills`
--

CREATE TABLE `offres_skills` (
  `offres_id` int NOT NULL,
  `skills_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `offres_skills`
--

INSERT INTO `offres_skills` (`offres_id`, `skills_id`) VALUES
(162, 1),
(162, 2),
(162, 3),
(162, 4);

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

--
-- Déchargement des données de la table `reset_password_request`
--

INSERT INTO `reset_password_request` (`id`, `user_id`, `selector`, `hashed_token`, `requested_at`, `expires_at`) VALUES
(6, 75, 'iDxhmOjRup4l0slAPUlh', 'k7NNHkl52tKhisIiCG+rQdMLdaYasmDj3yHhd7KkA9s=', '2025-09-15 00:18:21', '2025-09-15 01:18:21'),
(7, 75, '6vw2xEo0xAwYQ8QIt6A0', 'on4JbaF89ee+PHwrxvIPu0UIBgsRg22unahfFLXkB1w=', '2025-09-15 01:28:00', '2025-09-15 02:28:00'),
(9, 69, 'fepdr0ze4aumsLO9YMC9', 'fwrQ89SOhaZNRrWZ1qIVDcTTmKqLDBmWuR3+UIO3SRg=', '2025-09-18 16:15:09', '2025-09-18 17:15:09');

-- --------------------------------------------------------

--
-- Structure de la table `skills`
--

CREATE TABLE `skills` (
  `id` int NOT NULL,
  `users_id` int NOT NULL,
  `nom` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `skills`
--

INSERT INTO `skills` (`id`, `users_id`, `nom`, `slug`, `parent_id`) VALUES
(1, 49, 'Programmation/codage', 'Programmation/codage', NULL),
(2, 49, 'SEO', 'SEO', NULL),
(3, 49, 'réseaux sociaux', 'réseaux sociaux', NULL),
(4, 49, 'linkedin', 'linkedin', 3),
(5, 49, 'facebook', 'facebook', 3),
(6, 49, 'twitter', 'twitter', 3),
(7, 49, 'keyword planner', 'keyword planner', 2),
(8, 49, 'analytics', 'analytics', 2),
(9, 49, 'java', 'java', 1),
(10, 49, 'ruby on rails', 'ruby on rails', 1),
(11, 49, 'python', 'python', 1),
(12, 49, 'gestion de bases de données', 'gestion de bases de données', NULL),
(13, 49, 'oracle', 'oracle', 12),
(14, 49, 'mysql', 'mysql', 12);

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
(49, NULL, NULL, NULL, 'Le sport le sport', 'Sport', NULL, '17604235600814'),
(57, NULL, NULL, NULL, 'qdsqsdqsd', 'Info', NULL, '85178523698745');

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
(49, 'nrousseau@gmail.com', '[\"ROLE_USER\", \"ROLE_SOCIETE\"]', '$2y$13$Mvg56Kp0NIt4JlfVquv1wuV/8DQJLfSQrnjV1j8YfO8hGQ0/geZ5e', 'Marion V', 'Bât. 166', 70614, 'Riviere', '0637765375', 'societes', NULL, 1, 1, NULL, 'societes', NULL),
(57, 'blancho74@gmail.com', '[\"ROLE_USER\", \"ROLE_SOCIETE\", \"ROLE_ADMIN\"]', '$2y$13$.N3tADw9z3G27jlY621I6u.SNu50McIBoBOq2Xubebd1FTFGtR.zW', 'MPO', '23 avenue du docteur Fleming', 92600, 'Asnières sur seine', '0626179337', 'societes', NULL, 1, 0, NULL, 'societes', NULL),
(69, 'toto-freelance856@yopmail.com', '[\"ROLE_USER\", \"ROLE_CLIENT\"]', '$2y$13$6iO5F6r6HQBv74yMxkkDtO9/tidZpIIO4z.KaPz2Zk31jn8i4YEd6', 'Blanchard Banyingela', '23 avenue du docteur Fleming', 92600, 'Asnières sur seine', '0626179337', 'clients', NULL, 1, 1, NULL, 'clients', NULL),
(75, 'toto-freelance103@yopmail.com', '[\"ROLE_USER\", \"ROLE_CLIENT\"]', '$2y$13$VHe7lAdqy1f3i8kgfGbnZOl28qrgIMPwWB4wK6O7HZ/eSK7L1Lb1i', 'Blanchard Banyingela', '23 avenue du docteur Fleming', 92600, 'Asnières sur seine', '0626178524', 'clients', NULL, 1, 1, NULL, 'clients', NULL),
(82, 'martin93@yopmail.com', '[\"ROLE_USER\", \"ROLE_CLIENT\"]', '$2y$13$zEcD8PvsyAGmV8vQujBo0.Aht0gwLz.vyKH0RFGp7MgIa1GKieR3e', 'Blanchard Banyingela', '23 avenue du docteur Fleming', 92600, 'Asnières sur seine', '0626179374', 'clients', NULL, 0, 0, NULL, 'clients', 'c0c1e462366de9753c8a2edd63193209e46f5632aad422e3b702eaf104050d47'),
(83, 'martin94@yopmail.com', '[\"ROLE_USER\", \"ROLE_CLIENT\"]', '$2y$13$BmrqeK4c1jZ.THLtQmPT7.7CIDGBQlFDW9RaMwdXHlNnhN6g2pX2q', 'Blanchard Banyingela', '23 avenue du docteur Fleming', 92600, 'Asnières sur seine', '0626179374', 'clients', NULL, 1, 0, NULL, 'clients', NULL);

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
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_C82E74DB8BBA08` (`siren`);

--
-- Index pour la table `clients_skills`
--
ALTER TABLE `clients_skills`
  ADD PRIMARY KEY (`clients_id`,`skills_id`),
  ADD KEY `IDX_D0E6C297AB014612` (`clients_id`),
  ADD KEY `IDX_D0E6C2977FF61858` (`skills_id`);

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
  ADD KEY `IDX_D531167067B3B43D` (`users_id`),
  ADD KEY `IDX_D5311670727ACA70` (`parent_id`);

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
  ADD UNIQUE KEY `UNIQ_1483A5E9E7927C74` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `candidatures`
--
ALTER TABLE `candidatures`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT pour la table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `offres`
--
ALTER TABLE `offres`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=163;

--
-- AUTO_INCREMENT pour la table `regions`
--
ALTER TABLE `regions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `reset_password_request`
--
ALTER TABLE `reset_password_request`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `skills`
--
ALTER TABLE `skills`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

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
-- Contraintes pour la table `clients_skills`
--
ALTER TABLE `clients_skills`
  ADD CONSTRAINT `FK_D0E6C2977FF61858` FOREIGN KEY (`skills_id`) REFERENCES `skills` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_D0E6C297AB014612` FOREIGN KEY (`clients_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `offres`
--
ALTER TABLE `offres`
  ADD CONSTRAINT `FK_C6AC35447E841BEA` FOREIGN KEY (`societes_id`) REFERENCES `societes` (`id`);

--
-- Contraintes pour la table `offres_skills`
--
ALTER TABLE `offres_skills`
  ADD CONSTRAINT `FK_2E00F5CA6C83CD9F` FOREIGN KEY (`offres_id`) REFERENCES `offres` (`id`) ON DELETE CASCADE,
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
  ADD CONSTRAINT `FK_D531167067B3B43D` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `FK_D5311670727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `skills` (`id`);

--
-- Contraintes pour la table `societes`
--
ALTER TABLE `societes`
  ADD CONSTRAINT `FK_AEC76507BF396750` FOREIGN KEY (`id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
