-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 20, 2014 at 05:14 AM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `createurromans`
--
USE `createurromans`;

-- --------------------------------------------------------

--
-- Table structure for table `genres_litteraires`
--

DROP TABLE IF EXISTS `genres_litteraires`;
CREATE TABLE IF NOT EXISTS `genres_litteraires` (
  `ID_ligne_genre` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `ID_genre` smallint(5) unsigned NOT NULL,
  `nom` varchar(50) COLLATE utf8_general_ci NOT NULL,
  `nro_question` tinyint(3) unsigned NOT NULL,
  `texte` varchar(255) COLLATE utf8_general_ci NOT NULL,
  `type_input` enum('text','select') COLLATE utf8_general_ci NOT NULL,
  `valeurs_defaut` text COLLATE utf8_general_ci,
  `bouton_fonction` varchar(40) COLLATE utf8_general_ci DEFAULT NULL,
  PRIMARY KEY (`ID_ligne_genre`),
  UNIQUE KEY `ID_genre` (`ID_ligne_genre`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `genres_litteraires`
--

INSERT INTO `genres_litteraires` (`ID_ligne_genre`, `ID_genre`, `nom`, `nro_question`, `texte`, `type_input`, `valeurs_defaut`, `bouton_fonction`) VALUES
(1, 1, 'Policier', 1, 'Quel est le nom du Protagoniste A ?', 'text', 'Nom du protagoniste A', 'generer_nom(4,6,2)¤Générer'),
(2, 1, 'Policier', 2, 'Quel est son sexe?', 'select', 'Femme¤Homme', NULL),
(3, 2, 'Drame', 1, 'Quel est l''intérêt amoureux B ?', 'text', NULL, NULL);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
