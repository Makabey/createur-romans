-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jul 11, 2014 at 05:18 AM
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
  `typeEntite` enum('qui','quoi','ou','comment','pourquoi','quand','note') NOT NULL DEFAULT 'note',
  `titre` varchar(50) NOT NULL,
  `contenu` text NOT NULL,
  `note` text,
  `deleted` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID_entite`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `genres_litteraires_noms`
--

DROP TABLE IF EXISTS `genres_litteraires_noms`;
CREATE TABLE IF NOT EXISTS `genres_litteraires_noms` (
  `ID_genre` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `nom` tinytext NOT NULL,
  PRIMARY KEY (`ID_genre`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `genres_litteraires_questions`
--

DROP TABLE IF EXISTS `genres_litteraires_questions`;
CREATE TABLE IF NOT EXISTS `genres_litteraires_questions` (
  `ID_question` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `ID_genre` smallint(5) unsigned NOT NULL,
  `nro_question` tinyint(3) unsigned NOT NULL,
  `typeEntite` enum('qui','quoi','ou','comment','pourquoi','quand','note') NOT NULL DEFAULT 'note',
  `texte` varchar(255) NOT NULL,
  `forme_synopsis` varchar(100) NOT NULL,
  `type_input` enum('text','select') NOT NULL DEFAULT 'text',
  `suggestions` text,
  `bouton_fonction` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`ID_question`),
  UNIQUE KEY `ID_genre` (`ID_question`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='nro_question est pour réordonner' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `roman_details`
--

DROP TABLE IF EXISTS `roman_details`;
CREATE TABLE IF NOT EXISTS `roman_details` (
  `ID_roman` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ID_usager` mediumint(8) unsigned NOT NULL,
  `ID_genre` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `titre` varchar(60) NOT NULL,
  `date_creation` datetime NOT NULL,
  `date_dnrEdition` datetime NOT NULL,
  `deleted` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID_roman`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Tout sauf le texte' AUTO_INCREMENT=1 ;

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
  `notes_globales` text,
  PRIMARY KEY (`ID_roman_texte`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Texte seulement' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `usagers`
--

DROP TABLE IF EXISTS `usagers`;
CREATE TABLE IF NOT EXISTS `usagers` (
  `ID_usager` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `pseudo` varchar(30) NOT NULL,
  `nom` varchar(50) DEFAULT NULL,
  `motdepasse` varchar(20) NOT NULL,
  `courriel` varchar(40) NOT NULL,
  `dateInscription` datetime NOT NULL,
  `est_admin` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `deleted` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID_usager`),
  UNIQUE KEY `ID_usager` (`ID_usager`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
