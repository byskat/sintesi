-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Temps de generació: 07-06-2015 a les 21:26:17
-- Versió del servidor: 5.5.43-0ubuntu0.14.04.1
-- Versió de PHP: 5.5.9-1ubuntu4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de dades: `creditsintesi`
--
CREATE DATABASE IF NOT EXISTS `creditsintesi` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `creditsintesi`;

-- --------------------------------------------------------

--
-- Estructura de la taula `centers`
--

CREATE TABLE IF NOT EXISTS `centers` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `city` varchar(30) NOT NULL,
  `zipCode` varchar(10) NOT NULL,
  `address` varchar(150) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Estructura de la taula `connections`
--

CREATE TABLE IF NOT EXISTS `connections` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `idcenter1` int(11) unsigned NOT NULL,
  `idcenter2` int(11) unsigned NOT NULL,
  `name` varchar(50) NOT NULL,
  `startDate` date NOT NULL,
  `endDate` date NOT NULL,
  `outdated` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_connections_centers1_idx` (`idcenter1`),
  KEY `fk_connections_centers2_idx` (`idcenter2`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Estructura de la taula `connectionsprojects`
--

CREATE TABLE IF NOT EXISTS `connectionsprojects` (
  `connections_id` int(11) unsigned NOT NULL,
  `projects_id` int(11) unsigned NOT NULL,
  KEY `fk_centers_projects_idx` (`connections_id`),
  KEY `fk_centers_projects1_idx` (`projects_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de la taula `forumanswer`
--

CREATE TABLE IF NOT EXISTS `forumanswer` (
  `question_id` int(4) NOT NULL DEFAULT '0',
  `a_id` int(4) NOT NULL DEFAULT '0',
  `a_answer` longtext NOT NULL,
  `a_datetime` varchar(25) NOT NULL DEFAULT '',
  `id_user` int(4) NOT NULL,
  KEY `a_id` (`a_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de la taula `forumquestion`
--

CREATE TABLE IF NOT EXISTS `forumquestion` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `id_team` int(11) NOT NULL,
  `open` tinyint(1) NOT NULL DEFAULT '1',
  `topic` varchar(255) NOT NULL DEFAULT '',
  `detail` longtext NOT NULL,
  `datetime` varchar(25) NOT NULL DEFAULT '',
  `view` int(4) NOT NULL DEFAULT '0',
  `reply` int(4) NOT NULL DEFAULT '0',
  `id_user` int(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Estructura de la taula `inscriptions`
--

CREATE TABLE IF NOT EXISTS `inscriptions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `users_id` int(10) unsigned NOT NULL,
  `centers_id` int(10) unsigned NOT NULL,
  `startYear` int(4) unsigned NOT NULL,
  `endYear` int(4) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_inscription_user_idx` (`centers_id`),
  KEY `fk_inscription_centers_idx` (`users_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Estructura de la taula `inscriptionsteams`
--

CREATE TABLE IF NOT EXISTS `inscriptionsteams` (
  `teams_id` int(11) unsigned NOT NULL,
  `inscription_id` int(11) unsigned NOT NULL,
  KEY `fk_inscriptions_inscriptions_idx` (`inscription_id`),
  KEY `fk_inscription_teams_idx` (`teams_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de la taula `projects`
--

CREATE TABLE IF NOT EXISTS `projects` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `startDate` date NOT NULL,
  `endDate` date NOT NULL,
  `description` text NOT NULL,
  `outdated` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Estructura de la taula `teachersvalidations`
--

CREATE TABLE IF NOT EXISTS `teachersvalidations` (
  `orderNum` int(10) unsigned NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `lastName` varchar(100) DEFAULT NULL,
  `used` varchar(100) DEFAULT 'no',
  PRIMARY KEY (`orderNum`),
  UNIQUE KEY `orderNum_UNIQUE` (`orderNum`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de la taula `teams`
--

CREATE TABLE IF NOT EXISTS `teams` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `startDate` date NOT NULL,
  `endDate` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Estructura de la taula `teamsprojects`
--

CREATE TABLE IF NOT EXISTS `teamsprojects` (
  `projects_id` int(11) unsigned NOT NULL,
  `teams_id` int(11) unsigned NOT NULL,
  KEY `fk_teams_projects_idx` (`projects_id`),
  KEY `fk_teams_projects1_idx` (`teams_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de la taula `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `lastName` varchar(100) NOT NULL,
  `email` varchar(50) NOT NULL,
  `birthday` date NOT NULL,
  `profileImg` varchar(200) NOT NULL DEFAULT 'user_placeholder_res.jpeg',
  `username` varchar(50) NOT NULL,
  `password` varchar(45) NOT NULL,
  `role` tinyint(3) unsigned NOT NULL COMMENT '1 - Alumnes\\n2 - Professors\\n3 - Admin',
  `orderNum` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `orderNum` (`orderNum`),
  KEY `idx_orderNum` (`orderNum`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Restriccions per taules bolcades
--

--
-- Restriccions per la taula `connections`
--
ALTER TABLE `connections`
  ADD CONSTRAINT `fk_connections_centers1` FOREIGN KEY (`idcenter1`) REFERENCES `centers` (`id`),
  ADD CONSTRAINT `fk_connections_centers2` FOREIGN KEY (`idcenter2`) REFERENCES `centers` (`id`);

--
-- Restriccions per la taula `connectionsprojects`
--
ALTER TABLE `connectionsprojects`
  ADD CONSTRAINT `fk_centers_projects` FOREIGN KEY (`connections_id`) REFERENCES `connections` (`id`),
  ADD CONSTRAINT `fk_centers_projects1` FOREIGN KEY (`projects_id`) REFERENCES `projects` (`id`);

--
-- Restriccions per la taula `inscriptions`
--
ALTER TABLE `inscriptions`
  ADD CONSTRAINT `fk_inscription_centers` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `fk_inscription_user` FOREIGN KEY (`centers_id`) REFERENCES `centers` (`id`);

--
-- Restriccions per la taula `inscriptionsteams`
--
ALTER TABLE `inscriptionsteams`
  ADD CONSTRAINT `fk_inscriptions_inscriptions` FOREIGN KEY (`inscription_id`) REFERENCES `inscriptions` (`id`),
  ADD CONSTRAINT `fk_inscription_teams` FOREIGN KEY (`teams_id`) REFERENCES `teams` (`id`);

--
-- Restriccions per la taula `teamsprojects`
--
ALTER TABLE `teamsprojects`
  ADD CONSTRAINT `fk_teams_projects` FOREIGN KEY (`projects_id`) REFERENCES `projects` (`id`),
  ADD CONSTRAINT `fk_teams_projects1` FOREIGN KEY (`teams_id`) REFERENCES `teams` (`id`);

--
-- Restriccions per la taula `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_teachersValidations` FOREIGN KEY (`orderNum`) REFERENCES `teachersvalidations` (`orderNum`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
