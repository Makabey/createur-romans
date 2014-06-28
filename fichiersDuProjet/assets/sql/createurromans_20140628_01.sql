-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jun 29, 2014 at 12:30 AM
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
CREATE TABLE `entites` (
  `ID_entite` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ID_roman` int(10) unsigned NOT NULL,
  `ID_prev` int(10) unsigned NOT NULL DEFAULT '0',
  `ID_next` int(10) unsigned NOT NULL DEFAULT '0',
  `typeEntite` enum('qui','quoi','ou','comment','pourquoi','quand','note') NOT NULL DEFAULT 'note',
  `titre` varchar(50) NOT NULL,
  `contenu` text NOT NULL,
  `note` text,
  `deleted` bit(1) NOT NULL DEFAULT b'0',
  PRIMARY KEY (`ID_entite`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `genres_litteraires_noms`
--

DROP TABLE IF EXISTS `genres_litteraires_noms`;
CREATE TABLE `genres_litteraires_noms` (
  `ID_genre` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `nom` tinytext NOT NULL,
  PRIMARY KEY (`ID_genre`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `genres_litteraires_noms`
--

INSERT INTO `genres_litteraires_noms` (`ID_genre`, `nom`) VALUES
(1, 'policier'),
(2, 'drame');

-- --------------------------------------------------------

--
-- Table structure for table `genres_litteraires_questions`
--

DROP TABLE IF EXISTS `genres_litteraires_questions`;
CREATE TABLE `genres_litteraires_questions` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='nro_question est pour réordonner' AUTO_INCREMENT=9 ;

--
-- Dumping data for table `genres_litteraires_questions`
--

INSERT INTO `genres_litteraires_questions` (`ID_question`, `ID_genre`, `nro_question`, `typeEntite`, `texte`, `forme_synopsis`, `type_input`, `suggestions`, `bouton_fonction`) VALUES
(1, 1, 1, 'quoi', 'Choisir un crime ou entrer une valeur', 'Le crime', 'text', '¤meurtre¤vol¤viol¤enlèvement¤chantage¤torture¤terrorisme¤conspiration', NULL),
(2, 1, 2, 'pourquoi', 'Choisir un mobile ou entrer une valeur', 'Le mobile', 'text', '¤passionnel¤vengeance¤folie¤financier¤rançon¤idéologique¤survie', NULL),
(3, 1, 3, 'qui', 'Choisir un coupable ou entrer une valeur', 'Le coupable', 'text', '¤psychopathe¤tueur en série¤terroriste¤monsieur tout le monde¤femme fatale¤tueur à gage¤victime d''un complot', NULL),
(4, 1, 4, 'qui', 'Choisir une victime ou entrer une valeur', 'La victime', 'text', '¤homme¤femme¤enfant¤groupe¤animal', NULL),
(5, 1, 5, 'comment', 'Le crime s''est déroulé de quelle façon?', 'Déroulement', 'text', '¤prémédité¤accidentel¤dans le vif de l''action¤auto-défense¤erreur sur la personne', NULL),
(6, 1, 6, 'ou', 'À quel endroit se déroule principalement l''histoire?', 'Endroit', 'text', 'variable¤une petite ville¤une citée¤une grande ville¤un bar¤une chambre d''hôtel¤les docks¤un entrepôt¤une fabrique¤un manoir¤une bibliothèque publique¤un appartement¤sur la rue¤dans la forêt¤dans les égouts¤dans une ruelle', NULL),
(7, 1, 7, 'quand', 'À quelle époque ou moment se passe l''action?', 'Époque', 'text', 'variable¤les années 50¤les années 70¤les années 2000¤le futur¤tôt le matin¤en après-midi¤une journée chaude¤une journée humide¤une soirée sans lune¤un soir de pleine lune¤à Noël¤à la Pâques¤alors que tout dors', NULL),
(8, 2, 1, 'note', 'foo', 'bar', 'text', 'baz', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `roman_details`
--

DROP TABLE IF EXISTS `roman_details`;
CREATE TABLE `roman_details` (
  `ID_roman` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ID_usager` mediumint(8) unsigned NOT NULL,
  `ID_genre` bit(8) NOT NULL DEFAULT b'1',
  `titre` varchar(60) NOT NULL,
  `date_creation` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_dnrEdition` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted` bit(1) NOT NULL DEFAULT b'0',
  PRIMARY KEY (`ID_roman`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Tout sauf le texte' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `roman_texte`
--

DROP TABLE IF EXISTS `roman_texte`;
CREATE TABLE `roman_texte` (
  `ID_roman_texte` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ID_roman` int(10) unsigned NOT NULL,
  `synopsis` text NOT NULL,
  `contenu` mediumtext NOT NULL,
  `notes_globales` text,
  PRIMARY KEY (`ID_roman_texte`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Texte seulement' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `usagers`
--

DROP TABLE IF EXISTS `usagers`;
CREATE TABLE `usagers` (
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
