-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jul 02, 2014 at 04:41 PM
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

CREATE TABLE IF NOT EXISTS `entites` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

--
-- Dumping data for table `entites`
--

INSERT INTO `entites` (`ID_entite`, `ID_roman`, `ID_prev`, `ID_next`, `typeEntite`, `titre`, `contenu`, `note`, `deleted`) VALUES
(1, 1, 0, 0, 'quoi', 'Le crime', 'chantage', 'Une photo compromettante', b'0'),
(2, 1, 0, 0, 'pourquoi', 'Le mobile', 'financier', 'Meilleur position dans la compagnie', b'0'),
(3, 1, 0, 4, 'qui', 'Le coupable', 'femme fatale', 'Elle sait ce qu''elle veux mais préfère utiliser sa tête plutôt que son corps', b'0'),
(4, 1, 3, 0, 'qui', 'La victime', 'homme', 'Le président de la compagnie', b'0'),
(5, 1, 0, 0, 'comment', 'Déroulement', 'accidentel', 'La magie des téléphone intelligent, durant le "party" de Noël', b'0'),
(6, 1, 0, 0, 'ou', 'Endroit', 'Immeuble', 'Les locaux de la Compagnie "Birex Intl"', b'0'),
(7, 1, 0, 0, 'quand', 'Époque', 'les années 2000', 'En 2012-2016, à voir', b'0'),
(8, 2, 0, 0, 'quoi', 'Le crime', 'vol', 'Une émeraude grosse comme le poing', b'0'),
(9, 2, 0, 0, 'pourquoi', 'Le mobile', 'vengeance', 'Antagoniste courroucée', b'0'),
(10, 2, 0, 11, 'qui', 'Le coupable', 'victime d''un complot', 'Elle as tout perdu, même ses enfants', b'0'),
(11, 2, 10, 0, 'qui', 'La victime', 'homme d''affaire', ' sans scrupules et sans coeur', b'0'),
(12, 2, 0, 0, 'comment', 'Déroulement', 'prémédité', 'durant une réception qui devait être privée et sur invitation', b'0'),
(13, 2, 0, 0, 'ou', 'Endroit', 'un manoir', 'construit sur le sang et les pleurs des victimes de l''homme d''affaire', b'0'),
(14, 2, 0, 0, 'quand', 'Époque', 'les années 50', 'quand tout était plus facile et qu''il suffisait d''amadouer un garde avec des larmes de crocodile', b'0');

-- --------------------------------------------------------

--
-- Table structure for table `genres_litteraires_noms`
--

CREATE TABLE IF NOT EXISTS `genres_litteraires_noms` (
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
(8, 2, 1, 'quand', 'foo', 'bar', 'text', 'baz', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `roman_details`
--

CREATE TABLE IF NOT EXISTS `roman_details` (
  `ID_roman` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ID_usager` mediumint(8) unsigned NOT NULL,
  `ID_genre` bit(8) NOT NULL DEFAULT b'1',
  `titre` varchar(60) NOT NULL,
  `date_creation` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_dnrEdition` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted` bit(1) NOT NULL DEFAULT b'0',
  PRIMARY KEY (`ID_roman`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Tout sauf le texte' AUTO_INCREMENT=3 ;

--
-- Dumping data for table `roman_details`
--

INSERT INTO `roman_details` (`ID_roman`, `ID_usager`, `ID_genre`, `titre`, `date_creation`, `date_dnrEdition`, `deleted`) VALUES
(1, 1, b'00000001', 'Cette Rousse qui détrousse.', '2014-07-02 10:25:29', '2014-07-02 10:25:29', b'0'),
(2, 1, b'00000001', 'La vengeance est Rousse', '2014-07-02 10:36:46', '2014-07-02 10:36:46', b'0');

-- --------------------------------------------------------

--
-- Table structure for table `roman_texte`
--

CREATE TABLE IF NOT EXISTS `roman_texte` (
  `ID_roman_texte` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ID_roman` int(10) unsigned NOT NULL,
  `synopsis` text NOT NULL,
  `contenu` mediumtext NOT NULL,
  `notes_globales` text,
  PRIMARY KEY (`ID_roman_texte`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Texte seulement' AUTO_INCREMENT=3 ;

--
-- Dumping data for table `roman_texte`
--

INSERT INTO `roman_texte` (`ID_roman_texte`, `ID_roman`, `synopsis`, `contenu`, `notes_globales`) VALUES
(1, 1, '=Le crime :\n	-chantage\n	-Une photo compromettante\n\n=Le mobile :\n	-financier\n	-Meilleur position dans la compagnie\n\n=Le coupable :\n	-femme fatale\n	-Elle sait ce qu''elle veux mais préfère utiliser sa tête plutôt que son corps\n\n=La victime :\n	-homme\n	-Le président de la compagnie\n\n=Déroulement :\n	-accidentel\n	-La magie des téléphone intelligent, durant le "party" de Noël\n\n=Endroit :\n	-Immeuble\n	-Les locaux de la Compagnie "Birex Intl"\n\n=Époque :\n	-les années 2000\n	-En 2012-2016, à voir\n\n', 'Bienvenue dans votre roman! Quel sera le commencement de votre histoire? :)', NULL),
(2, 2, '=Le crime :\n	-vol\n	-Une émeraude grosse comme le poing\n\n=Le mobile :\n	-vengeance\n	-Antagoniste courroucée\n\n=Le coupable :\n	-victime d''un complot\n	-Elle as tout perdu, même ses enfants\n\n=La victime :\n	-homme d''affaire\n	- sans scrupules et sans coeur\n\n=Déroulement :\n	-prémédité\n	-durant une réception qui devait être privée et sur invitation\n\n=Endroit :\n	-un manoir\n	-construit sur le sang et les pleurs des victimes de l''homme d''affaire\n\n=Époque :\n	-les années 50\n	-quand tout était plus facile et qu''il suffisait d''amadouer un garde avec des larmes de crocodile\n\n', 'Bienvenue dans votre roman! Quel sera le commencement de votre histoire? :)', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `usagers`
--

CREATE TABLE IF NOT EXISTS `usagers` (
  `ID_usager` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `pseudo` varchar(30) NOT NULL,
  `nom` varchar(50) DEFAULT NULL,
  `motdepasse` varchar(20) NOT NULL,
  `courriel` varchar(40) NOT NULL,
  `dateInscription` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted` bit(1) NOT NULL DEFAULT b'0',
  PRIMARY KEY (`ID_usager`),
  UNIQUE KEY `ID_usager` (`ID_usager`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `usagers`
--

INSERT INTO `usagers` (`ID_usager`, `pseudo`, `nom`, `motdepasse`, `courriel`, `dateInscription`, `deleted`) VALUES
(1, 'MrTest', 'Test Heur', 'elssetumoez', 'personne@nullpart.com', '2014-06-21 11:24:50', b'0');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
