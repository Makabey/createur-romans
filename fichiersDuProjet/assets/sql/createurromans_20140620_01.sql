-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Ven 20 Juin 2014 à 20:14
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

-- --------------------------------------------------------

--
-- Structure de la table `genres_litteraires`
--

DROP TABLE IF EXISTS `genres_litteraires`;
CREATE TABLE IF NOT EXISTS `genres_litteraires` (
  `ID_ligne_genre` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `ID_genre` smallint(5) unsigned NOT NULL,
  `nom` varchar(50) NOT NULL,
  `nro_question` tinyint(3) unsigned NOT NULL,
  `texte` varchar(255) NOT NULL,
  `type_input` enum('text','select') NOT NULL,
  `valeurs_defaut` text,
  `bouton_fonction` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`ID_ligne_genre`),
  UNIQUE KEY `ID_genre` (`ID_ligne_genre`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Contenu de la table `genres_litteraires`
--

INSERT INTO `genres_litteraires` (`ID_ligne_genre`, `ID_genre`, `nom`, `nro_question`, `texte`, `type_input`, `valeurs_defaut`, `bouton_fonction`) VALUES
(1, 1, 'Policier', 1, 'Quel est le nom du Protagoniste A ?', 'text', 'Nom du protagoniste A¤Ti-Jos¤Max¤Sammy', 'generer_nom(4,6,2)¤Générer'),
(2, 1, 'Policier', 2, 'Quel est son sexe?', 'select', 'Femme¤Homme', NULL),
(3, 2, 'Drame', 1, 'Quel est l''intérêt amoureux B ?', 'text', NULL, NULL);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
