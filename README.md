createur-romans
===============

Application web local de création de romans écris, nouvelle, mémoire ou autre demandant une organisation assez simple des informations et des liens entre elles.

L'application supportera la création d'entitées telles que : notes, personnages, lieux/véhicules de très grande taille (vaisseaux spaciaux, navires tel porte-avions, avions), objets/machines/véhicules de moyenne à petite taille (véhicule utilitaire sport à trottinette), chapitres, oeuvres(roman, nouvelle, mémoire)

Les entitées pourront êtres liées entre elles, surtout les notes qui pourraient se référencer l'une-l'autre et/ou référencer un personnage et/ou un lieu (et/ou toute autre entitées); chaque note/personnage/lieu nommés auraient son propre lien HTML dans le texte de la note(/personnage/lieu) vers l'entitée elle-même. ex une note nomme 1 lieu, 2 personnages et fait référence (pour rappel à l'auteur) à 4 autres notes qui iraient, dans l'ordre chronologique de lecture de l'oeuvre et selon ces notes elles-même, autant en aval qu'en amont de la présente note (ou transversalement pour les personnages et lieux). L'ordre des entitées-notes sera dictée par le lien direct (par opposition à discret, c'est à dire la mécanique de référencement) entre elles et non par leur ordre numérologique ou de création. Ex: les notes #23, 493 et 129 sont en ordre pour raconter l'histoire mais elles ont été créée dans l'ordre chronologique de 23, 129 et 493 (avec tout les numéros entre) puis réordonnées. Des notes pourront être liées à un chapitre ou un paragraphe afin de permettre à l'auteur de se rappeller les notes qui ont été réellement utilisées et à quel moment du récit.

Les gabarits de bases seront modifiables (par projet et par installation de l'application) pour refléter les besoins de l'auteur, tel qu'un besoin chez l'un de spécifier l'origine ethnique de chaques personnages au travers d'un choix (plutôt que dans la description générale) ou chez un autre auteur la préférence de mode de vie alimentaire (omnivore, végétaRien, végétaLien, etc)

J'aimerai ajouter un "moodboard", une bibliothèque (commune à tout les projets sur un même ordinateur) pouvant contenir des liens, des images, des bribes de texte/citation, donc des inspirations.

Les technologies prévues tournent autour du web : HTML5, JS, Ajax, SQL, etc.

Les données seront toutes initialements dans une BD MySQL parce qu'il se pourrais que le début du projet soit fait comme un travail de fin d'étude en collaboration et donc en besoin de petites simplification technologique. Si la chose est bien fait, il ne sera pas trop difficile de réécrire certaines parties pour utiliser du XML et du CSV dans le but de pouvoir tout recréer (la structure des oeuvres) sans trop de tracas en advenant une corruption de la BD ou un désir de recopier dans un autre projet/logiciel le contenu généré.
