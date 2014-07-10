-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jul 10, 2014 at 12:22 AM
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
-- CREATE DATABASE IF NOT EXISTS `createurromans` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
-- USE `createurromans`;


--
-- Dumping data for table `genres_litteraires_noms`
--

INSERT INTO `genres_litteraires_noms` (`ID_genre`, `nom`) VALUES
(1, 'policier'),
(2, 'drame');

--
-- Dumping data for table `genres_litteraires_questions`
--

INSERT INTO `genres_litteraires_questions` (`ID_question`, `ID_genre`, `nro_question`, `typeEntite`, `texte`, `forme_synopsis`, `type_input`, `suggestions`, `bouton_fonction`) VALUES
(1, 1, 1, 'quoi', 'Choisir un crime ou entrer une valeur', 'Le crime', 'text', 'ex:meurtre¤meurtre¤vol¤viol¤enlèvement¤chantage¤torture¤terrorisme¤conspiration', NULL),
(2, 1, 2, 'pourquoi', 'Choisir un mobile ou entrer une valeur', 'Le mobile', 'text', 'ex:vengeance¤vengeance¤passionnel¤folie¤financier¤rançon¤idéologique¤survie', NULL),
(3, 1, 3, 'qui', 'Choisir un coupable ou entrer une valeur', 'Le coupable', 'text', 'ex: tueur à gage¤tueur à gage¤psychopathe¤tueur en série¤terroriste¤monsieur tout le monde¤femme fatale¤victime d''un complot', NULL),
(4, 1, 4, 'qui', 'Choisir une victime ou entrer une valeur', 'La victime', 'text', 'ex: homme¤homme¤femme¤enfant¤groupe¤animal', NULL),
(5, 1, 5, 'comment', 'Le crime s''est déroulé de quelle façon?', 'Déroulement', 'text', 'ex: prémédité¤prémédité¤accidentel¤dans le vif de l''action¤auto-défense¤erreur sur la personne', NULL),
(6, 1, 6, 'ou', 'À quel endroit se déroule principalement l''histoire?', 'Endroit', 'text', 'ex:dans une petite ville¤une petite ville¤une citée¤une grande ville¤un bar¤une chambre d''hôtel¤les docks¤un entrepôt¤une fabrique¤un manoir¤une bibliothèque publique¤un appartement¤sur la rue¤dans la forêt¤dans les égouts¤dans une ruelle', NULL),
(7, 1, 7, 'quand', 'À quelle époque ou moment se passe l''action?', 'Époque', 'text', 'ex: le présent¤le présent¤indéfini¤les années 50¤les années 70¤les années 2000¤le futur¤tôt le matin¤en après-midi¤une journée chaude¤une journée humide¤une soirée sans lune¤un soir de pleine lune¤à Noël¤à la Pâques¤alors que tout dors', NULL),
(8, 2, 1, 'quand', 'À quelle époque ou moment se passe l''action?', 'Époque', 'text', 'ex: le présent¤le présent¤indéfini¤les années 50¤les années 70¤les années 2000¤le futur¤tôt le matin¤en après-midi¤une journée chaude¤une journée humide¤une soirée sans lune¤un soir de pleine lune¤à Noël¤à la Pâques¤alors que tout dors', NULL),
(9, 2, 2, 'qui', 'Acteur/trice principal(e)', 'Protagoniste A', 'text', 'ex:une femme¤une femme¤un homme¤un enfant¤un garçonnet¤une fillette¤un bébé', NULL),
(10, 2, 4, 'qui', 'Acteur/trice secondaire #1', 'Protagoniste B', 'text', 'ex:une femme¤une femme¤un homme¤un enfant¤un garçonnet¤une fillette¤un bébé', NULL),
(11, 2, 3, 'quoi', 'Quelle est la situation du Protagoniste A', 'Situation', 'text', 'ex: adopté¤adopté¤amour interdit¤amour incestueux¤déshérité¤héritage problématique¤maladie terminale¤maladie dégénérative¤débalancement intellectuel progressif¤perte d''êtres chers¤enlèvement', NULL),
(12, 2, 5, 'quoi', 'Lien entre Acteur principal et secondaire', 'Lien entre les acteurs', 'text', 'ex:familial¤familial¤affectif¤amis¤amants¤connaissances¤ennemis¤némésis¤conjoins¤ex-quelque chose¤', NULL),
(13, 2, 6, 'ou', 'À quel endroit se passe principalement l''action', 'Endroit', 'text', 'ex:dans une petite ville¤une petite ville¤une citée¤une grande ville¤un bar¤une chambre d''hôtel¤les docks¤un entrepôt¤une fabrique¤un manoir¤une bibliothèque publique¤un appartement¤sur la rue¤dans la forêt¤dans les égouts¤dans une ruelle', NULL),
(14, 2, 7, 'comment', 'Quel évènement as entamé la situation?', 'Évènement déclencheur', 'text', 'ex: la vie¤la vie¤enfant indésiré¤par accident¤vengeance personnelle¤vengeance d''un tierce¤visite impromptue', NULL),
(15, 2, 8, 'pourquoi', 'Quelle est la raison fondamentale de la situation?', 'Pourquoi', 'text', 'ex: sans le sous¤sans le sous¤rejet parental¤vivres coupées¤décès naturel¤rivalitée¤ignorance lien parenté¤volontée divine', NULL);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
