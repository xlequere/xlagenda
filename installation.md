# XLAgenda 4.5

XLAgenda 4 par Xavier LE QUERE
Web : <https://xavier.lequere.net/xlagenda>
(C) Xavier LE QUERE, 2003-2020
Version 4.5.1 - 25/02/20

Ce programme utilise des applications et bibliothèques tierces :
- Tiny MCE, par Moxiecode Systems : http://tinymce.moxiecode.com
- DatePicker, par Kelvin Luck : http://www.kelvinluck.com
- Jscolor 1.4.1, par Jan Odvarko : http://odvarko.cz
- jQuery 1.2.6, par John Resig : www.jquery.com

## Installation

1. Editez le fichier data.php (placé dans le répertoire include)
2. Copiez sur votre serveur le répertoire xlagenda complet
3. Avec certains hébergeurs, vous devez créer un répertoire sessions à la racine du site.
Cette étape est indispensable au bon fonctionnement de l'interface d'administration.
4. Modifiez les droits du répertoire xlagenda/img en effectuant un CHMOD 777.
Avec certains hébergeurs, cette opération n'est pas possible, mais les répertoires ont déjà tous les droits nécessaires. 
5. Rendez-vous sur xlagenda/install/index.php et lancez l'installation automatique 
6. Supprimez les répertoires update et install
7. Rendez-vous sur xlagenda/admin/index.php et connectez vous à l'aide du mot de passe défini pendant l'installation 

## Mise à jour

1. Editez le fichier data.php (placé dans le répertoire include)
2. Supprimez de votre serveur l'ensemble des fichiers de l'application et remplacez les par les nouveaux.
3. Modifiez les droits du répertoire xlagenda/img en effectuant un CHMOD 777.
Avec certains hébergeurs, cette opération n'est pas possible, mais les répertoires ont déjà tous les droits nécessaires. 
4. Rendez-vous sur xlagenda/update/index.php et lancez la mise à jour automatique
5. Supprimez les répertoires update et install
6. Rendez-vous sur xlagenda/admin/index.php et connectez vous à l'aide de votre mot de passe habituel

## Personnaliser

- Pour insérer l'agenda dans votre site, vous pouvez ajouter votre code HTML avant et après l'agenda en éditant les fichiers xlagenda/include/header.php et xlagenda/include/footer.php
- Pour modifier le style, éditez le fichier xlagenda/include/style.css
- Pour modifier les textes, éditez les fichiers xlagenda/lang/fr/common.php et xlagenda/lang/fr/admin.xlagenda