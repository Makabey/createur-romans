CREATE DATABASE  IF NOT EXISTS `createurromans`;
USE `createurromans`;

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
  `deleted` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID_entite`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4;

LOCK TABLES `entites` WRITE;
INSERT INTO `entites` VALUES (1,1,0,0,'quoi','Le crime','enlèvement','Tout c\'est passé dans l\'endroit prévu mais un lampadaire faisait défaut...',0),(2,1,0,10,'pourquoi','Le mobile','idéologique; Environnement, protection des penguins.',NULL,0),(3,1,0,4,'qui','Le coupable','monsieur tout le monde; Fanatique',NULL,0),(4,1,3,0,'qui','La victime','Cadre d\'une compagnie pétrolière','Mme Ginette Archambault, co-CEO',0),(5,1,0,8,'comment','Déroulement','&lt;span style=\"display: inline !important;\"&gt;erreur sur la personne&lt;/span&gt;','Pour une fois, elle avait mis son complet, pour une fois elle avait décidée de mettre sa cravate pour faire plaisir à sa conjointe... le mauvais jour.',0),(6,1,0,0,'ou','Endroit','dans les égouts','Elle est retenue dans les égouts à l\'autre bout de la ville.',0),(7,1,0,0,'quand','Époque','le présent',NULL,0),(8,1,5,9,'comment','Lieu de l\'enlèvement','Une ruelle adjacente à un guichet automatique','Un lampadaire défectueux non loin crée une pénombre suffisante pour qu\'elle soit prise pour l\'homme réellement ciblé, son partenaire d\'affaire. Cet homme s\'arrête habituellement à ce guichet, à la même heure, à quelques minutes près.',0),(9,1,8,0,'comment','Confusion','Raison de la confusion','&lt;span style=\"display: inline !important;\" contenteditable=\"true\"&gt;Elle est arrivée près de 20minutes plus tôt que son partenaire; nerveux, les comploteurs n\'ont pas questionné et sont passés à l\'acte...&lt;/span&gt;',0),(10,1,2,0,'pourquoi','Rouler avec ce qu\'on as','Ils décident qu\'une personne ou une autre est ok','Ce n\'est pas la bonne victime mais elles est #2 dans la compagnie, alors ça fera l\'affaire et puis c\'est une femme, ils vont peut-être plus plier.',0);
UNLOCK TABLES;

DROP TABLE IF EXISTS `genres_litteraires_noms`;
CREATE TABLE `genres_litteraires_noms` (
  `ID_genre` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `nom` tinytext NOT NULL,
  PRIMARY KEY (`ID_genre`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

LOCK TABLES `genres_litteraires_noms` WRITE;
INSERT INTO `genres_litteraires_noms` VALUES (1,'policier'),(2,'drame');
UNLOCK TABLES;

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
  PRIMARY KEY (`ID_question`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COMMENT='nro_question est pour réordonner';

LOCK TABLES `genres_litteraires_questions` WRITE;
INSERT INTO `genres_litteraires_questions` VALUES (1,1,1,'quoi','Choisir un crime ou entrer une valeur','Le crime','text','ex:meurtre¤meurtre¤vol¤viol¤enlèvement¤chantage¤torture¤terrorisme¤conspiration',NULL),(2,1,2,'pourquoi','Choisir un mobile ou entrer une valeur','Le mobile','text','ex:vengeance¤vengeance¤passionnel¤folie¤financier¤rançon¤idéologique¤survie',NULL),(3,1,3,'qui','Choisir un coupable ou entrer une valeur','Le coupable','text','ex: tueur à gage¤tueur à gage¤psychopathe¤tueur en série¤terroriste¤monsieur tout le monde¤femme fatale¤victime d\'un complot',NULL),(4,1,4,'qui','Choisir une victime ou entrer une valeur','La victime','text','ex: homme¤homme¤femme¤enfant¤groupe¤animal',NULL),(5,1,5,'comment','Le crime s\'est déroulé de quelle façon?','Déroulement','text','ex: prémédité¤prémédité¤accidentel¤dans le vif de l\'action¤auto-défense¤erreur sur la personne',NULL),(6,1,6,'ou','À quel endroit se déroule principalement l\'histoire?','Endroit','text','ex:dans une petite ville¤une petite ville¤une citée¤une grande ville¤un bar¤une chambre d\'hôtel¤les docks¤un entrepôt¤une fabrique¤un manoir¤une bibliothèque publique¤un appartement¤sur la rue¤dans la forêt¤dans les égouts¤dans une ruelle',NULL),(7,1,7,'quand','À quelle époque ou moment se passe l\'action?','Époque','text','ex: le présent¤le présent¤indéfini¤les années 50¤les années 70¤les années 2000¤le futur¤tôt le matin¤en après-midi¤une journée chaude¤une journée humide¤une soirée sans lune¤un soir de pleine lune¤à Noël¤à la Pâques¤alors que tout dors',NULL),(8,2,1,'quand','À quelle époque ou moment se passe l\'action?','Époque','text','ex: le présent¤le présent¤indéfini¤les années 50¤les années 70¤les années 2000¤le futur¤tôt le matin¤en après-midi¤une journée chaude¤une journée humide¤une soirée sans lune¤un soir de pleine lune¤à Noël¤à la Pâques¤alors que tout dors',NULL),(9,2,2,'qui','Acteur/trice principal(e)','Protagoniste A','text','ex:une femme¤une femme¤un homme¤un enfant¤un garçonnet¤une fillette¤un bébé',NULL),(10,2,4,'qui','Acteur/trice secondaire #1','Protagoniste B','text','ex:une femme¤une femme¤un homme¤un enfant¤un garçonnet¤une fillette¤un bébé',NULL),(11,2,3,'quoi','Quelle est la situation du Protagoniste A','Situation','text','ex: adopté¤adopté¤amour interdit¤amour incestueux¤déshérité¤héritage problématique¤maladie terminale¤maladie dégénérative¤débalancement intellectuel progressif¤perte d\'êtres chers¤enlèvement',NULL),(12,2,5,'quoi','Lien entre Acteur principal et secondaire','Lien entre les acteurs','text','ex:familial¤familial¤affectif¤amis¤amants¤connaissances¤ennemis¤némésis¤conjoins¤ex-quelque chose¤',NULL),(13,2,6,'ou','À quel endroit se passe principalement l\'action','Endroit','text','ex:dans une petite ville¤une petite ville¤une citée¤une grande ville¤un bar¤une chambre d\'hôtel¤les docks¤un entrepôt¤une fabrique¤un manoir¤une bibliothèque publique¤un appartement¤sur la rue¤dans la forêt¤dans les égouts¤dans une ruelle',NULL),(14,2,7,'comment','Quel évènement as entamé la situation?','Évènement déclencheur','text','ex: la vie¤la vie¤enfant indésiré¤par accident¤vengeance personnelle¤vengeance d\'un tierce¤visite impromptue',NULL),(15,2,8,'pourquoi','Quelle est la raison fondamentale de la situation?','Pourquoi','text','ex: sans le sous¤sans le sous¤rejet parental¤vivres coupées¤décès naturel¤rivalitée¤ignorance lien parenté¤volontée divine',NULL);
UNLOCK TABLES;

DROP TABLE IF EXISTS `roman_details`;
CREATE TABLE `roman_details` (
  `ID_roman` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ID_usager` mediumint(8) unsigned NOT NULL,
  `ID_genre` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `titre` varchar(60) NOT NULL,
  `date_creation` datetime NOT NULL,
  `date_dnrEdition` datetime NOT NULL,
  `deleted` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID_roman`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COMMENT='Tout sauf le texte';

LOCK TABLES `roman_details` WRITE;
INSERT INTO `roman_details` VALUES (1,2,1,'J\'était seule avec un rat.','2017-08-25 10:42:30','2017-08-25 11:12:39',0);
UNLOCK TABLES;

DROP TABLE IF EXISTS `roman_texte`;
CREATE TABLE `roman_texte` (
  `ID_roman_texte` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ID_roman` int(10) unsigned NOT NULL,
  `synopsis` text NOT NULL,
  `contenu` mediumtext NOT NULL,
  `notes_globales` text,
  PRIMARY KEY (`ID_roman_texte`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COMMENT='Texte seulement';

LOCK TABLES `roman_texte` WRITE;
INSERT INTO `roman_texte` VALUES (1,1,'=Le crime :¤enlèvement¯=Le mobile :¤idéologique¤Environnement, protection des penguins.¯=Le coupable :¤monsieur tout le monde¤Fanatique¯=La victime :¤groupe¯=Déroulement :¤erreur sur la personne¯=Endroit :¤dans les égouts¯=Époque :¤le présent¯','Bienvenue dans votre roman! Quel sera le commencement de votre histoire? :)\n\nJe ne me réveille pas habituellement avec un cou endolori, je fait attention de bien me positionner quand je me couche. Nadia n\'arrête pas de me le repèter, elle qui est posturologue. Mais dites-moi, comment y arriver quand on vous enlève et que vous passez je ne sais combien de minutes attachée en position assise? Je ne sais pas mais pour sûr, ma tête est lourde... ou c\'est à cause de la matraque qu\'ils ont utilisé, ou les deux?\n\nLa dernière chose dont je me souviens est d\'avoir été au guichet recommendé par Marcus, mon partenaire d\'affaire. Qui enlève quelqu\'un pour 40$ ? [...]','=Le crime : enlèvement\n=Le mobile : idéologique; Environnement, protection des penguins.\n=Le coupable : monsieur tout le monde; Fanatique\n=La victime : groupe\n=Déroulement : erreur sur la personne\n=Endroit : dans les égouts\n=Époque : le présent');
UNLOCK TABLES;

DROP TABLE IF EXISTS `usagers`;
CREATE TABLE `usagers` (
  `ID_usager` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `pseudo` varchar(30) NOT NULL,
  `nom` varchar(50) DEFAULT NULL,
  `motdepasse` varchar(20) NOT NULL,
  `courriel` varchar(40) NOT NULL,
  `dateInscription` datetime NOT NULL,
  `est_admin` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `deleted` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID_usager`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

LOCK TABLES `usagers` WRITE;
INSERT INTO `usagers` VALUES (1,'admin','Administrateur','adminadmin','admin@domaine.com','2017-08-24 00:00:00',1,0),(2,'usager1','Joe Untel','motdepasse','','2017-08-24 16:41:06',0,0);
UNLOCK TABLES;
