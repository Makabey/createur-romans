-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jun 22, 2014 at 04:15 AM
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
CREATE DATABASE IF NOT EXISTS `createurromans` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `createurromans`;

-- --------------------------------------------------------

--
-- Table structure for table `entites`
--

DROP TABLE IF EXISTS `entites`;
CREATE TABLE IF NOT EXISTS `entites` (
  `ID_entite` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ID_roman` int(10) unsigned NOT NULL,
  `ID_prev` int(10) unsigned NOT NULL DEFAULT '0',
  `ID_next` int(10) unsigned NOT NULL DEFAULT '0',
  `type` enum('qui','quoi','ou','comment','pourquoi','note') NOT NULL DEFAULT 'note',
  `titre` varchar(50) NOT NULL,
  `contenu` text NOT NULL,
  `note` text,
  `deleted` bit(1) NOT NULL DEFAULT b'0',
  PRIMARY KEY (`ID_entite`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `entites`
--

INSERT INTO `entites` (`ID_entite`, `ID_roman`, `ID_prev`, `ID_next`, `type`, `titre`, `contenu`, `note`, `deleted`) VALUES
(1, 1, 3, 0, 'qui', 'Protagoniste A', 'vrai test<br>', 'adipiscing facilisis nibh ut mattis. Aenean tempor sodales urna, sit amet iaculis mauris dapibus et. Aenean nunc velit, tristique tristique fermentum ut, ...', b'0'),
(2, 1, 0, 3, 'qui', 'Interet amoureux', 'suscipit vel ipsum. Cras congue iaculis est, non adipiscing lectus tincidunt et. Nam eros nibh, fringilla bibendum aliquet ac, vehicula vitae leo. Nunc eros ante, suscipit in nisi nec, lacinia commodo nibh. Praesent ut congue arcu. Suspendisse ', NULL, b'0'),
(3, 1, 2, 1, 'qui', 'Antagoniste C', 'Jquery code check array is nll or <br>', '9 - What is the mos reliable way if I want to check if the variable is null or is not present?. There are diferent examples: if (n', b'0');

-- --------------------------------------------------------

--
-- Table structure for table `genres_litteraires`
--

DROP TABLE IF EXISTS `genres_litteraires`;
CREATE TABLE IF NOT EXISTS `genres_litteraires` (
  `ID_ligne_genre` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `ID_genre` smallint(5) unsigned NOT NULL,
  `nom` varchar(50) NOT NULL,
  `nro_question` tinyint(3) unsigned NOT NULL,
  `texte` varchar(255) NOT NULL,
  `type_input` enum('text','select') NOT NULL DEFAULT 'text',
  `valeurs_defaut` text,
  `bouton_fonction` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`ID_ligne_genre`),
  UNIQUE KEY `ID_genre` (`ID_ligne_genre`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `genres_litteraires`
--

INSERT INTO `genres_litteraires` (`ID_ligne_genre`, `ID_genre`, `nom`, `nro_question`, `texte`, `type_input`, `valeurs_defaut`, `bouton_fonction`) VALUES
(1, 1, 'Policier', 1, 'Quel est le nom du Protagoniste A ?', 'text', 'Nom du protagoniste A¤Ti-Jos¤Max¤Sammy', 'generer_nom(4,6,2)¤Générer'),
(2, 1, 'Policier', 2, 'Quel est son sexe?', 'select', 'Femme¤Homme', NULL),
(3, 2, 'Drame', 1, 'Quel est l''intérêt amoureux B ?', 'text', NULL, NULL),
(4, 1, 'Policier', 3, 'Quel est le nom du Protagoniste B ?', 'text', NULL, 'generer_nom(4,6,2)¤Générer');

-- --------------------------------------------------------

--
-- Table structure for table `roman_details`
--

DROP TABLE IF EXISTS `roman_details`;
CREATE TABLE IF NOT EXISTS `roman_details` (
  `ID_roman` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ID_usager` mediumint(8) unsigned NOT NULL,
  `ID_genre` bit(8) NOT NULL DEFAULT b'1',
  `titre` varchar(50) NOT NULL,
  `date_creation` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_dnrEdition` datetime NOT NULL,
  `deleted` bit(1) NOT NULL DEFAULT b'0',
  PRIMARY KEY (`ID_roman`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Tout sauf le texte' AUTO_INCREMENT=2 ;

--
-- Dumping data for table `roman_details`
--

INSERT INTO `roman_details` (`ID_roman`, `ID_usager`, `ID_genre`, `titre`, `date_creation`, `date_dnrEdition`, `deleted`) VALUES
(1, 1, b'00000001', 'Test', '2014-06-21 11:23:24', '2014-06-21 11:23:24', b'0');

-- --------------------------------------------------------

--
-- Table structure for table `roman_texte`
--

DROP TABLE IF EXISTS `roman_texte`;
CREATE TABLE IF NOT EXISTS `roman_texte` (
  `ID_roman_texte` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ID_roman` int(10) unsigned NOT NULL,
  `synopsis` text NOT NULL,
  `contenu` mediumtext NOT NULL,
  PRIMARY KEY (`ID_roman_texte`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Texte seulement' AUTO_INCREMENT=2 ;

--
-- Dumping data for table `roman_texte`
--

INSERT INTO `roman_texte` (`ID_roman_texte`, `ID_roman`, `synopsis`, `contenu`) VALUES
(1, 1, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin commodo sapien eu nisi viverra, et facilisis elit convallis. Vestibulum in orci non sapien laoreet suscipit. In hac habitasse platea dictumst. Nullam aliquet eros tortor, at aliquet quam porta vitae. In facilisis tortor quis venenatis pretium. Pellentesque vel sagittis dolor. Etiam sit amet egestas enim. Morbi elementum mauris et pharetra rhoncus. Etiam molestie eleifend quam sed sagittis. Curabitur sit amet purus non turpis aliquam dignissim.', 'Cras luctus condimentum posuere.6789012345 k');

-- --------------------------------------------------------

--
-- Table structure for table `usagers`
--

DROP TABLE IF EXISTS `usagers`;
CREATE TABLE IF NOT EXISTS `usagers` (
  `ID_usager` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `pseudo` varchar(30) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `motdepasse` varchar(20) NOT NULL,
  `courriel` varchar(40) NOT NULL,
  `dateInscription` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID_usager`),
  UNIQUE KEY `ID_usager` (`ID_usager`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `usagers`
--

INSERT INTO `usagers` (`ID_usager`, `pseudo`, `nom`, `motdepasse`, `courriel`, `dateInscription`) VALUES
(1, 'MrTest', 'Test Heur', 'elssetumoez', 'personne@nullpart.com', '2014-06-21 11:24:50');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
