-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : ven. 22 août 2025 à 09:17
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
-- Base de données : `wat`
--

-- --------------------------------------------------------

--
-- Structure de la table `characters`
--

DROP TABLE IF EXISTS `characters`;
CREATE TABLE IF NOT EXISTS `characters` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `hp` int NOT NULL,
  `power` int NOT NULL,
  `defense` int NOT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `image_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `type_id` int NOT NULL,
  `role_id` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `characters`
--

INSERT INTO `characters` (`id`, `name`, `hp`, `power`, `defense`, `description`, `image_name`, `created_at`, `updated_at`, `type_id`, `role_id`) VALUES
(8, 'Christobal le Hurleur', 120, 30, 40, 'Cri Métallique → Provoque tous les ennemis pendant 2 tours, réduit leur précision (ils se couvrent les oreilles).\r\n\r\nBarde de métal qui s’improvise guerrier, ses hurlements couvrent la salle entière.\r\n\r\n« Le chant, c’est pas que pour séduire les dames, c’est aussi pour faire trembler les murs ! »', 'christopher-68a712632f0a5800458353.png', '2025-08-21 09:18:15', '2025-08-21 12:34:43', 1, 1),
(9, 'Ludomir le Paisible', 80, 20, 20, 'Lien Félin → Soigne l’allié le plus faible chaque tour, mais avec un léger retard.\r\n\r\nMoine errant, toujours détendu, qui s’inspire de la sagesse des chats pour soigner.\r\n\r\n« J’arrive… mais d’abord une sieste. »', 'yuumi-61-68a6e4bf69441712873873.jpg', '2025-08-21 09:19:59', '2025-08-21 09:19:59', 3, 3),
(10, 'Normund le Grognon', 130, 25, 45, 'Râlerie Défensive → Augmente sa défense et attire une attaque par ses protestations incessantes.\r\n\r\nGuerrier robuste, plus coriace que joyeux, qui se plaint autant qu’il encaisse.\r\n\r\n« C’est toujours pour ma pomme, c’est incroyable ça ! »', 'image-cartes-a-collectionner-lorcana-disney-game-tcg-lorcanacards-sept-nains-grincheux-68a6e5140b0bc403855213.webp', '2025-08-21 09:21:23', '2025-08-21 09:21:24', 3, 1),
(11, 'Kyllianek le Fêtard', 95, 40, 20, 'Binouze & Tekno → Double attaque, mais peut se blesser lui-même 10% du temps (trop bourré).\r\n\r\nGuerrier des tavernes, danseur infatigable, son arme résonne comme une bassline.\r\n\r\n« Mets le son plus fort, j’vais lui péter la gueule en rythme ! »', 'ab6761610000e5eb38354a921fabe908436bfe65-68a6e55377863257908243.jpg', '2025-08-21 09:22:27', '2025-08-21 09:22:27', 1, 2),
(12, 'Théobald le Stratège', 85, 35, 30, 'Discours Politique → Buffe un allié (attaque +20%), mais a 50% de chance d’énerver un ennemi qui l’attaque direct.\r\n\r\nOrateur né, toujours à tirer les ficelles, même au milieu d’une bagarre.\r\n\r\n« La victoire, c’est une question de négociation. »', 'gecko-68a6e5a875a05219387841.png', '2025-08-21 09:23:52', '2025-08-21 09:23:52', 2, 2),
(13, 'Sashan le Capuché', 70, 50, 15, 'Coup de Capuche → Devient intouchable 1 tour puis frappe avec un critique assuré.\r\n\r\nAssassin encapuchonné, toujours en retrait, mais quand il frappe, c’est létal.\r\n\r\n« On ne me voit pas… mais on me sent passer. »', 'sasha-2-68a7124f689e6867056669.png', '2025-08-21 09:25:59', '2025-08-21 12:34:23', 3, 2),
(14, 'Santon Rochambeaut', 75, 45, 20, 'Inflige des dégâts psychiques à un ennemi ET à lui-même (10%).\r\n\r\nMaître des mots, mais ses sortilèges l’atteignent souvent en retour.\r\n\r\n« Je t’explique… attends, même moi je comprends plus. P\'t\'ain j\'ai envie de crever»', 'chatgpt-image-15-juil-2025-11-47-54-68a6e6c0be0f6903944379.png', '2025-08-21 09:28:32', '2025-08-21 09:28:32', 1, 2),
(15, 'Dame Virgiane l’Ordonnée', 115, 25, 40, 'Planification Maternelle → Donne +15% DEF à tous ses alliés au début du combat.\r\n\r\nProtectrice naturelle, son aura d’organisation inspire stabilité et discipline.\r\n\r\n« Chacun à sa place, et ça se passera très bien. »', 'virginia2-68a7123e969ae433049049.png', '2025-08-21 09:30:04', '2025-08-21 12:34:06', 2, 1),
(16, 'Dame Mariane la Grammairienne', 80, 45, 20, 'Correction Fatale → Chaque faute (buff négatif) sur un ennemi augmente ses dégâts de 20%.\r\n\r\nMagicienne érudite, ses sorts sont aussi précis qu’implacables.\r\n\r\n« Une erreur, c’est une condamnation. »', 'marie-68a6e7add3e32253708461.png', '2025-08-21 09:32:29', '2025-08-21 09:32:29', 1, 2),
(17, 'Abdoulian le Retardataire', 85, 20, 25, 'Arrivée Tardive → N’apparaît qu’au 2e tour, mais soigne massivement tous les alliés à son entrée.\r\n\r\nSoutien imprévisible, jamais à l’heure, mais toujours au bon moment.\r\n\r\n« J’vous ai manqué ? »', 'abdoul-68a6e868dc8e7717894035.png', '2025-08-21 09:35:36', '2025-08-21 09:35:36', 3, 3),
(18, 'Dame Anethel & Jinx', 75, 50, 20, 'Surchauffe Cérébrale → Fait exploser la tête d’un ennemi par surcharge mentale (dégâts magiques), son chat Jinx ajoute 10 dégâts bonus.\r\n\r\nSorcière érudite, accompagnée de son fidèle familier, elle brûle les esprits plus vite qu’un feu de cheminée.\r\n\r\n« Réfléchis trop fort… tu vas fondre. »', 'anett-68a6e8d42d282294841384.png', '2025-08-21 09:37:24', '2025-08-21 09:37:24', 2, 2),
(19, 'Houriane l’Harmonieuse', 85, 25, 25, 'Design Parfait → Réorganise les alliés : donne +10% attaque et +10% défense pendant 2 tours.\r\n\r\nEnchanteresse de la beauté et des formes, son art équilibre même les batailles.\r\n\r\n« Si c’est moche, c’est raté. Si c’est harmonieux, c’est gagné. »', 'houria-68a6e972074f2142442237.png', '2025-08-21 09:40:01', '2025-08-21 09:40:02', 2, 2),
(20, 'Dame Kateline la Soporeuse', 80, 20, 30, 'Discours Ennuyant → Endort une cible pendant 1 tour.\r\n\r\nGentille et appliquée, mais sa voix berce même les ennemis les plus féroces.\r\n\r\n« Vous voyez… enfin si vous m’écoutez encore… »', 'kathleen-68a6e9d9a7b6d122492870.png', '2025-08-21 09:41:45', '2025-08-21 09:41:45', 2, 2),
(21, 'Mathisar le Râleur', 70, 45, 30, 'Bougon Furtif → Se cache dans l’ombre, puis frappe par derrière (attaque critique garantie).\r\n\r\nAssassin qui ne se déplace jamais sans râler. Même ses ennemis savent qu’il approche.\r\n\r\n« J’suis là, mais fallait pas compter sur moi trop tôt. »', 'mathis-68a6ea297d166839567969.png', '2025-08-21 09:43:05', '2025-08-21 09:43:05', 1, 2),
(22, 'Camilius le Forgeron Noir', 125, 35, 40, 'Riff Métallique → Frappe en zone en frappant son marteau sur son enclume.\r\n\r\nGuerrier tatoué, maître du métal sous toutes ses formes : musical et martial.\r\n\r\nCitation : « Le métal, ça forge le corps… et les tympans. »', 'camille-68a6eaa79bd2b803022368.png', '2025-08-21 09:45:11', '2025-08-21 09:45:11', 1, 1),
(23, 'Dame Gypsiane la Guérisseuse', 90, 25, 25, 'Soin Parfait → Soigne l’allié le plus blessé de façon critique (100% PV restaurés si soin critique).\r\n\r\nAncienne infirmière, perfectionniste acharnée, rien ne lui échappe.\r\n\r\n« Si tu crèves sous ma garde, c’est que t’as fait exprès. »', 'gispy-68a6eb044089c063250170.png', '2025-08-21 09:46:44', '2025-08-21 09:46:44', 3, 3),
(24, 'Benedicus l’Orateur', 85, 30, 25, 'Discours Intarissable → Inspire ses alliés (+15% attaque) mais perd 5% s’il est allié à Houriane (ils se contredisent).\r\n\r\nToujours ponctuel, bavard et enthousiaste, sa voix motive ou fatigue selon la compagnie.\r\n\r\n« J’ai encore deux trois trucs à dire… »', 'ben-68a6eb5e45f95633402786.png', '2025-08-21 09:48:14', '2025-08-21 09:48:14', 1, 2);

-- --------------------------------------------------------

--
-- Structure de la table `doctrine_migration_versions`
--

DROP TABLE IF EXISTS `doctrine_migration_versions`;
CREATE TABLE IF NOT EXISTS `doctrine_migration_versions` (
  `version` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Déchargement des données de la table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20250820062913', '2025-08-20 06:30:44', 172),
('DoctrineMigrations\\Version20250820081013', '2025-08-20 08:10:25', 48),
('DoctrineMigrations\\Version20250820081339', '2025-08-20 08:13:46', 32),
('DoctrineMigrations\\Version20250821112538', NULL, NULL),
('DoctrineMigrations\\Version20250821112907', NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `messenger_messages`
--

DROP TABLE IF EXISTS `messenger_messages`;
CREATE TABLE IF NOT EXISTS `messenger_messages` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `body` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `headers` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue_name` varchar(190) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `available_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `delivered_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`),
  KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  KEY `IDX_75EA56E016BA31DB` (`delivered_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `roles`
--

INSERT INTO `roles` (`id`, `name`) VALUES
(1, 'Tank'),
(2, 'DPS'),
(3, 'Heal');

-- --------------------------------------------------------

--
-- Structure de la table `teams`
--

DROP TABLE IF EXISTS `teams`;
CREATE TABLE IF NOT EXISTS `teams` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `total_power` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_96C22258A76ED395` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `teams`
--

INSERT INTO `teams` (`id`, `user_id`, `total_power`) VALUES
(1, 1, 130),
(2, 2, 165),
(6, 5, 150),
(7, 6, 195);

-- --------------------------------------------------------

--
-- Structure de la table `teams_characters`
--

DROP TABLE IF EXISTS `teams_characters`;
CREATE TABLE IF NOT EXISTS `teams_characters` (
  `teams_id` int NOT NULL,
  `characters_id` int NOT NULL,
  PRIMARY KEY (`teams_id`,`characters_id`),
  KEY `IDX_AF69C842D6365F12` (`teams_id`),
  KEY `IDX_AF69C842C70F0E28` (`characters_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `teams_characters`
--

INSERT INTO `teams_characters` (`teams_id`, `characters_id`) VALUES
(1, 9),
(1, 10),
(1, 11),
(1, 19),
(1, 20),
(2, 18),
(2, 19),
(2, 22),
(2, 23),
(2, 24),
(6, 8),
(6, 9),
(6, 10),
(6, 11),
(6, 12),
(7, 10),
(7, 11),
(7, 12),
(7, 13),
(7, 14);

-- --------------------------------------------------------

--
-- Structure de la table `types`
--

DROP TABLE IF EXISTS `types`;
CREATE TABLE IF NOT EXISTS `types` (
  `id` int NOT NULL AUTO_INCREMENT,
  `perso_id` int DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_593089301221E019` (`perso_id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `types`
--

INSERT INTO `types` (`id`, `perso_id`, `name`) VALUES
(1, NULL, 'Taverne'),
(2, NULL, 'Académie'),
(3, NULL, 'Ombre');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(180) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` json NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `profile_image_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_IDENTIFIER_USERNAME` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `username`, `roles`, `password`, `profile_image_name`, `created_at`, `updated_at`) VALUES
(1, 'toto', '[\"ROLE_USER\"]', '$2y$13$4SOYUQ2Y/HpTCvvt.mEk6OL5FXKg.up1dqv2Q8ZWsPlLBEbAFbYki', NULL, '2025-08-20 10:13:48', '2025-08-20 10:13:48'),
(2, 'test', '[\"ROLE_USER\"]', '$2y$13$MeciLzq34JjSEeMeqxPH9urokaWaWslLF6PfckJbqnHoqekEIJHQe', NULL, '2025-08-20 10:18:56', '2025-08-20 10:18:56'),
(5, 'lapinou', '[\"ROLE_USER\"]', '$2y$13$Ab/ymSLxZPMjAiz9nFiV2emOzgnVCD6jiEL3j2CX1HnldNDoFPoWO', NULL, '2025-08-20 14:01:34', '2025-08-20 14:01:34'),
(6, 'Swap', '[\"ROLE_USER\", \"ROLE_ADMIN\"]', '$2y$13$h673Z4dQl9NhMzGHZn8/Luj/LvVW.EK0YKXYzHtQX/dkVDx6dBUNy', 'rakan-remastered-68a70864d2a22133051910.jpg', '2025-08-21 11:52:04', '2025-08-21 11:52:04');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `teams`
--
ALTER TABLE `teams`
  ADD CONSTRAINT `FK_96C22258A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `teams_characters`
--
ALTER TABLE `teams_characters`
  ADD CONSTRAINT `FK_AF69C842C70F0E28` FOREIGN KEY (`characters_id`) REFERENCES `characters` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_AF69C842D6365F12` FOREIGN KEY (`teams_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `types`
--
ALTER TABLE `types`
  ADD CONSTRAINT `FK_593089301221E019` FOREIGN KEY (`perso_id`) REFERENCES `characters` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
