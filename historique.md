# XLAgenda 4.5

XLAgenda 4 par Xavier LE QUERE
Web : <https://xavier.lequere.net/xlagenda>
(C) Xavier LE QUERE, 2003-2019
Version 4.5 - 13/10/19

Ce programme utilise des applications et bibliothèques tierces :
- Tiny MCE, par Moxiecode Systems : http://tinymce.moxiecode.com
- DatePicker, par Kelvin Luck : http://www.kelvinluck.com
- Jscolor 1.4.1, par Jan Odvarko : http://odvarko.cz
- jQuery 1.2.6, par John Resig : www.jquery.com

## Evolution du produit

### 13/19/19 - version 4.5

**Principales nouveautés**

- Optimisation du modèle de données

**Principaux bugs corrigés**

- Résolution d'un problème pouvant empêcher l'installation de l'application


### 08/05/16 - version 4.4

**Principales nouveautés**

- Comptabilité PHP 7

**Principaux bugs corrigés**

- Résolution d’un problème de comptabilité avec PHP 5.0 et 5.1
- Correction d’un bug à la fusion entre deux catégories
- Correction de problèmes entraînant l’affichage de messages de type "Undefined variable"


### 16/06/13 - version 4.3

**Principales nouveautés**

- L'application est désormais localisable
- Encodage en UTF-8
- Passage en XHTML 1.0
- Optimisations et corrections de bugs mineurs


### 13/02/11 - version 4.2

**Principales nouveautés**

- Ajout de la gestion des couleurs des catégories
- Il est maintenant possible d'autoriser les visiteurs à proposer des événements
- Possibilité de modifier et de supprimer un événement directement depuis l'agenda
- XLAgenda est maintenant compatible avec PHP 5.3
- Modification de la gestion des sessions et du contrôle des autorisations dans l'interface d'administration
- La durée de timeout des sessions est maintenant paramétrable
- Possibilité de désactiver l'envoi d'un mail à l'administrateur lorsqu'un événement est soumis
- Modification des liens affichés par défaut dans le menu de l'agenda
- Ajout d'une balise meta noindex sur les modules d'installation et de mise à jour
- La version de l'agenda est maintenant est lue en table : suppression de la variable $session du fichier data.php


### 03/01/11 - version 4.1.1

**Principaux bugs corrigés**

- Correction d'un bug dans l'affichage du nom du mois


### 26/12/10 - version 4.1

**Principales nouveautés**

- Modification du codage du mot de passe
- Possibilité d'envoyer un e-mail de confirmation à la validation d'une demande de compte
- Possibilité de personnaliser l'affichage du calendrier et de définir un style particulier pour les jours avec un événement
- Ajout d'un module d'informations sur l'application dans l'admin
- Possibilité de vérifier l'existence d'une mise à jour depuis l'admin
- Modification des noms par défaut des tables
- Ajout d'une table de paramètres

**Principaux bugs corrigés**

- Correction d'un bug qui empêchait l'enregistrement du mot de passe lors d'une demande de nouveau mot de passe dans certaines configurations
- Correction d'un bug qui empêchait la validation d'une demande de compte dans certaines configurations
- Correction d'un bug qui rendait impossible la connexion après un changement de nom d'utilisateur


### 21/11/09 - version 4.0.3

**Principales nouveautés**

- Modification de l'affichage des heures dans le cas d'un événement sur deux jours
- Lorsqu'une heure de fin est renseignée mais pas d'heure de début, l'heure de début est désormais affichée
- Suppression du champ "user" de la table agenda, inutilisé depuis la version 3.3

**Principaux bugs corrigés**

- Correction d'un bug qui empêchait l'ajout d'un événement avec certaines configurations de MySQL
- Correction d'un bug qui empêchait la modification d'un événement avec certaines configurations de MySQL


### 27/06/09 - version 4.0.2

**Principaux bugs corrigés**

- Correction d'un bug qui empêchait un utilisateur ne pouvant modifier que ses événements de les modifier
- Correction d'un bug qui empêchait un utilisateur ne pouvant supprimer que ses événements de les supprimer


### 16/05/09 - version 4.0.1

**Principaux bugs corrigés**

- Correction d'un bug qui empêchait un utilisateur ne pouvant modifier que ses événements de les modifier
- Correction d'un bug qui empêchait un utilisateur ne pouvant supprimer que ses événements de les supprimer


### 07/03/09 - version 4.0

**Principales nouveautés**

- Nouvelle interface d'administration
- Ajout de la gestion des heures
- Ajout d'un éditeur HTML
- Ajout d'un mini-calendrier pour sélectionner les dates au lieu de les saisir à la main
- Possibilité de désactiver des catégories
- La suppression d'une catégorie supprime les événements qu'elle contient
- Lors de la fusion de deux catégories, il est désormais possible de donner à la nouvelle catégorie le nom d'une des catégories fusionnées
- A l'ajout ou à la modification d'un événement, l'application vérifie que la date de début est bien antérieure à la date de fin
- Nouveau système de visualisation des événements en mode "vue réduite"
- Contrôles en temps réel sur la disponibilité du nom d'utilisateur et de l'adresse e-mail lors de l'ajout / modification d'un compte
- Prévention des injections SQL dans l'admin
- Modification du code HTML de l'agenda pour faciliter la personnalisation en CSS


13/01/08 - version 3.3

**Principales nouveautés**

- Ajout d'un menu de haut de page dans l'admin pour faciliter la navigation entre les différentes rubriques
- Ajout d'un formulaire pour obtenir un nouveau mot de passe en cas de perte de son mot de passe
- Tous les utilisateurs peuvent désormais modifier leur mot de passe et leur adresse email à l'aide de la nouvelle rubrique "Modifier votre profil"
- Les mots de passe doivent désormais faire au moins 6 caractères
- Dans la table "agenda", les utilisateurs sont désormais identifiés par leur id et plus par leur username
- Ajout d'une demande de confirmation lors de la suppression d'un événement
- Le nom et le prénom sont désormais enregistrés dans les paramètres des comptes
- Lors d'une demande de création de compte, ajout d'un contrôle par code caché dans une image pour éviter les soumissions automatiques par des robots
- Il est désormais possible de personnaliser l'adresse d'expédition des notifications par email 
- Il est désormais possible de valider les événements un par un
- Les administrateurs peuvent désormais désactiver un événement
- Dans l'admin, rubrique "Gérer les utilisateurs", un lien vers la validation des inscriptions s'affiche lorsqu'il y a des demandes d'inscriptions en attente
- Il n'est plus possible de modifier les paramètres du compte courant dans la rubrique "Gérer les utilisateurs"
- Modification de l'interface d'installation automatique afin d'éviter qu'elle puisse être utilisée pour générer un nouveau compte une fois l'application installée
- Modification de l'interface de mise à jour automatique pour la rendre compatible avec toutes les versions de l'application

**Principaux bugs corrigés**

- Suppression des messages d'erreur "Undefined variable" lors d'une demande de création de compte et lors de l'ajout d'un événement par un utilisateur dont les nouveaux événements sont soumis à validation
- Correction d'un bug qui pouvait conduire à l'affichage d'événements non validés dans l'agenda
- Il n'est plus possible de créer deux utilisateurs avec un même username ou une même adresse email
- Correction d'un bug qui permettait à un utilisateur de supprimer un événement qu'il n'avait pas créé sans en avoir le droit
- Lors de la modification d'un événement, l'application contrôle désormais que les champs obligatoires ont bien été renseignés


### 02/03/06 - version 3.2.7

**Principales nouveautés**

- Remplacement des <? par des <?php dans les fichiers header.php pour améliorer la compatibilité
- Prévention renforcée des injections SQL dans la partie publique de l'agenda

**Principaux bugs corrigés**

- Correction d'un bug qui permettait d'accéder à l'admin sans avoir de compte lorsque magic_quote était désactivé dans la configuration de PHP


### 28/12/05 - version 3.2.6

**Principales nouveautés**

- L'agenda peut désormais être placé à la racine du site


### 11/11/05 - version 3.2.5

**Principales nouveautés**

- Dans le fichier data.php, possibilité de configurer la vue par défaut
- Dans le fichier data.php, possibilité de désactiver le menu de choix des vues

**Principaux bugs corrigés**

- Correction d'un bug qui affichait l'année en cours au lieu de l'année demandée dans le message indiquant qu'aucun événement n'est trouvé
- Correction d'un bug qui affichait des messages "Undefined variable" dans les menus déroulants permettant de sélectionner la date de fin d'un événement


### 13/10/05 - version 3.2.4

**Principaux bugs corrigés**

- Correction d'un bug qui conduisait à l'affichage d'événements pas encore validés par l'administrateur

### 27/05/05 - version 3.2.3

**Principales nouveautés**

- Possibilité de choisir le nombre d'années à afficher avant et après l'année en cours dans les menus "années"
- Amélioration du code HTML pour le rendre plus lisible

**Principaux bugs corrigés**

- Correction d'un bug qui masquait certains événements lors du filtrage
- Correction d'un bug qui empêchait l'affichage de la description de l'événement dans le formulaire de saisie en cas d'erreur


### 12/03/05 - version 3.2.2

**Principaux bugs corrigés**

- Correction d'un bug qui empêchait de changer le mot de passe d'un utilisateur
- Correction d'un bug qui affichait un message d'erreur lors de l'édition du compte d'un utilisateur


### 30/12/04 - version 3.2.1

**Principaux bugs corrigés**
- Correction d'un bug qui affichait un message d'erreur dans la pop up, lors de l'affichage en mode réduit. 


### 15/12/04 - version 3.2

**Principales nouveautés**

- Possibilité de consulter les logs de connexion via l'admin, et de les initialiser
- Dans le texte des événements, filtrage des tags HTML afin d'améliorer la sécurité
- Dans le texte des événements, conversion des caractères accentués en HTML afin d'améliorer la compatibilité
- Sur la partie publique de l'agenda, prévention des injections SQL par le contrôle du contenu des variables et par le filtrage des requêtes
- Sur l'écran de connexion à l'admin, limitation du nombre de caractères des champs afin de réduire les risques de connexion par injection SQL
- Amélioration de la connexion à la base de données
- Dans la partie publique de l'agenda, ajout d'un message signalant que l'application n'est pas installée lorsque les tables n'existent pas

**Principaux bugs corrigés**

- Modification du code de la page de recherche afin d'éviter un message d'erreur du type "undefined index" 
- Correction d'un bug qui ajoutait des \ après les apostrophes dans la fenêtre d'affichage des événements


### 09/10/04 - version 3.1.1

**Principaux bugs corrigés**

- Correction d'un bug qui effaçait la date des événements modifiés avec une configuration de PHP en register_globals = OFF


### 23/09/04 - version 3.1

**Principales nouveautés**

- Lorsqu'un événement est en attente de validation, un email est envoyé aux administrateurs
- Lorsqu'un événement est en attente de validation, un message s'affiche sur la page d'accueil de l'interface d'administration
- Après cinq tentatives de connexion à partir d'un même nom d'utilisateur, le compte concerné est bloqué jusqu'au lendemain
- Les mots de passe sont désormais codés dans la base de données
- Les mots de passe n'apparaissent plus dans l'interface d'administration
- Il n'est plus possible de supprimer le compte en cours d'utilisation
- L'agenda est désormais en HTML 4.0.1 valide
- La couleur des cadres est désormais gérée par la feuille de style CSS
- Dans le code HTML, remplacement des ' par des "

**Principaux bugs corrigés**
- Correction d'une faille de sécurité qui permettait d'accéder à l'interface d'administration sans être connecté
- Modification du code afin d'éviter les messages d'erreur du type "undefined index" et "undefined variable"
- Lors d'une erreur dans l'ajout d'un événement, le menu "années" s'affiche correctement


### 20/08/04 - version 3.0.1

**Principales nouveautés**

- Modification de la feuille de style afin d'améliorer la compatibilité
- Modification du code HTML afin d'améliorer la compatibilité
- Remplacement des <? par des <?php afin d'améliorer la compatibilité
- Possibilité de personnaliser les URL des pages de recherche et de demande de compte, ainsi que le nom du répertoire de l'admin

**Principaux bugs corrigés**

- Correction d'un bug qui empêchait les événements commencés avant la date du jour et pas encore achevés de s'afficher correctement
- Correction d'un bug qui ajoutait un espace après l'apostrophe dans le formulaire de recherche


### 16/06/04 - version 3.0

**Principales nouveautés**

- L'utilisateur peut désormais choisir le nom des tables
- Ajout du calendrier du mois en cours
- Possibilité d'afficher les événements d'une seule journée
- Le formulaire de recherche est désormais sur une page séparée
- Possibilité de demander un compte en ligne
- Nouveaux champs : lieu, adresse du contact, texte du lien

**Principaux bugs corrigés**

- Lorsque magic_quotes est activé, l'agenda n'ajoute plus de \ avant les '
- Lorsque le nom du mois commence par une consonne, l'agenda affiche d' et non plus de
- Amélioration de la gestion des URL


### 10/02/04 - version 2.3.1

**Principaux bugs corrigés**

- L'installation automatique peut se lancer si le serveur SQL n'a pas de mot de passe
- Lors de l'installation automatique, les catégories comprenant des ' sont introduites dans la table des catégories
- Lors de l'installation automatique, les catégories comprenant des mots accentués sont introduites correctement dans la table des catégories


### 05/02/04 - version 2.3

**Principales nouveautés**

- Affichage de l'agenda en version longue ou en version courte
- Possibilité de rechercher ou bien dans le mois en cours ou bien dans les événements à venir
- Le nom du mois s'affiche dans le titre de la page
- Ajout de nouveaux messages d'information
- Amélioration du script de construction du menu "années"

**Principaux bugs corrigés**

- Les messages comprenant des mots accentués s'affichent correctement


### 18/01/04 - version 2.2

- Amélioration de l'interface de validation afin de rendre plus agréable la lecture des événements en attente
- Amélioration du script de navigation entre les mois


### 05/01/04 - version 2.1

- Modification de l'interface d'administration afin de permettre de modifier ou de supprimer un rendez-vous de l'année passée


### 20/12/03 - version 2.0

- XLAgenda est désormais compatible avec la configuration par défaut de PHP 4.2.0 et ultérieur (registrer_globals et magic_qutotes désactivés)
- Amélioration de l'interface d'installation automatique
- Correction de plusieurs bugs mineurs


### 20/09/03 - version 1.0

Première version publique de XLAgenda