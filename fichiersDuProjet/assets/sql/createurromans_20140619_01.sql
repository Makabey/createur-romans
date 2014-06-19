-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Jeu 19 Juin 2014 à 20:14
-- Version du serveur: 5.5.24-log
-- Version de PHP: 5.3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `createurromans`
--
DROP DATABASE `createurromans`;
CREATE DATABASE `createurromans` DEFAULT CHARACTER SET latin1 COLLATE latin1_general_ci;
USE `createurromans`;

-- --------------------------------------------------------

--
-- Structure de la table `genres_litteraires`
--

DROP TABLE IF EXISTS `genres_litteraires`;
CREATE TABLE `genres_litteraires` (
  `ID_genre` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `nro_question` tinyint(3) unsigned NOT NULL,
  `texte` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `type_input` enum('text','select') COLLATE latin1_general_ci NOT NULL,
  `valeurs_defaut` text COLLATE latin1_general_ci NOT NULL,
  `bouton_fonction` varchar(40) COLLATE latin1_general_ci DEFAULT NULL,
  PRIMARY KEY (`ID_genre`),
  UNIQUE KEY `ID_genre` (`ID_genre`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=3 ;

--
-- Contenu de la table `genres_litteraires`
--

INSERT INTO `genres_litteraires` (`ID_genre`, `nom`, `nro_question`, `texte`, `type_input`, `valeurs_defaut`, `bouton_fonction`) VALUES
(1, 'Policier', 1, 'Quel est le nom du Protagoniste A ?', 'text', 'Nom du protagoniste A', ''),
(2, 'Policier', 2, 'Quel est son sexe?', 'select', 'Femme¤Homme', '');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
