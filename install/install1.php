<?php
/******************************************************************
*	XLAgenda 4 par Xavier LE QUERE
*   Web : http://xavier.lequere.net/xlagenda
*   (C) Xavier LE QUERE, 2003-2019
*   Version 4.5 - 13/10/19
*
*   This program is free software: you can redistribute it and/or modify
*   it under the terms of the GNU General Public License as published by
*   the Free Software Foundation, either version 3 of the License, or
*   (at your option) any later version.
*   
*   This program is distributed in the hope that it will be useful,
*   but WITHOUT ANY WARRANTY; without even the implied warranty of
*   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
*   GNU General Public License for more details.
*
*   You should have received a copy of the GNU General Public License
*   along with this program. If not, see https://www.gnu.org/licenses
*
*********************************************************************/

include("../include/data.php");
include("../include/connexion.php");
include("../include/functions.php");
include('include.php');
//INITALISATION DES VARIABLES
$erreur="";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>XLAgenda <?php echo $version ?> | Installation</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="robots" content="noindex, nofollow" />
<link href="style.css" rel="stylesheet" type="text/css" />
</head>

<body>
<h1>Installation - Etape 2/3</h1>
<?php
//CONTROLE DE LA CONNEXION A LA BASE DE DONNEES
if ((!$dbserver) OR (!$dbdb) OR (!$dbuser))
{
	echo "<p class=\"erreur\">Erreur : vous n'avez pas édité le fichier data.php. L'installation ne peut pas être effectuée.</p>";
}
else
{
	//VERIFICATION DE LA PRESENCE DES TABLES
	$query="SELECT id FROM $table_agenda";
	$result=mysqli_query($connexion,$query);
	if ($result)
	{
		echo "<p class=\"erreur\">Erreur : la table <b>$table_agenda</b> existe déjà.</p>";
		$erreur=1;
	}
	$query="SELECT id FROM $table_users";
	$result=mysqli_query($connexion,$query);
	if ($result)
	{
		echo "<p class=\"erreur\">Erreur : la table <b>$table_users</b> existe déjà.</p>";
		$erreur=1;
	}
	$query="SELECT id FROM $table_logs";
	$result=mysqli_query($connexion,$query);
	if ($result)
	{
		echo "<p class=\"erreur\">Erreur : la table <b>$table_logs</b> existe déjà.</p>";
		$erreur=1;
	}
	$query="SELECT id FROM $table_demande";
	$result=mysqli_query($connexion,$query);
	if ($result)
	{
		echo "<p class=\"erreur\">Erreur : la table <b>$table_demande</b> existe déjà.</p>";
		$erreur=1;
	}
	$query="SELECT id FROM $table_categories";
	$result=mysqli_query($connexion,$query);
	if ($result)
	{
		echo "<p class=\"erreur\">Erreur : la table <b>$table_categories</b> existe déjà.</p>";
		$erreur=1;
	}
	$query="SELECT id FROM $table_config";
	$result=mysqli_query($connexion,$query);
	if ($result)
	{
		echo "<p class=\"erreur\">Erreur : la table <b>$table_config</b> existe déjà.</p>";
		$erreur=1;
	}
	
	if ($erreur)
	{
		echo "<p>Une ou plusieurs tables existent déjà. L'installation ne peut pas se poursuivre.</p>";
	}
	else
	{
		//CREATION DE LA TABLE AGENDA
		$query="CREATE TABLE $table_agenda (
		  id int(11) NOT NULL auto_increment,
		  nom text,
		  date_debut date DEFAULT NULL,
		  date_fin date DEFAULT NULL,
		  heure_debut time DEFAULT NULL,
		  heure_fin time DEFAULT NULL,
		  description text,
		  lieu text,
		  categorie int(11) DEFAULT NULL,
		  contact text,
		  adresse text,
		  email text,
		  telephone text,
		  fax text,
		  lien text,
		  url text,
		  id_user int(11) DEFAULT NULL,
		  actif tinyint(4) NOT NULL DEFAULT '0',
		  PRIMARY KEY id (id)
		)
		CHARSET=utf8 COMMENT='XLAgenda - Table principale';";
		$result=mysqli_query($connexion,$query);
		if (!$result)
		{
			echo "<p class=\"erreur\">Une erreur est survenue. La table <b>$table_agenda</b> n'a pas pu être créée.</p>";
			$erreur="2";
		}
	
		//CREATION DE LA TABLE LOGS
		$query="CREATE TABLE $table_logs (
		  id int(11) NOT NULL auto_increment,
		  user text default NULL,
		  pass text default NULL,
		  date date default NULL,
		  time time default NULL,
		  ip text default NULL,
		  domain text default NULL,
		  result text default NULL,
		  PRIMARY KEY id (id)
		  )
		  CHARSET=utf8 COMMENT='XLAgenda - Table des logs'";
		$result=mysqli_query($connexion,$query);
		if (!$result)
		{
			echo "<p class=\"erreur\">Une erreur est survenue. La table <b>$table_logs</b> n'a pas pu être créée.</p>";
			$erreur="2";
		}
	
		//CREATION DE LA TABLE CATEGORIES
		$query="CREATE TABLE $table_categories (
		  id int(11) NOT NULL auto_increment,
		  nom text default NULL,
		  couleur text default NULL,
		  actif tinyint(4) NOT NULL default '1',
		  PRIMARY KEY id (id)
		  )
		  CHARSET=utf8 COMMENT='XLAgenda - Table des categories'";
		$result=mysqli_query($connexion,$query);
		if (!$result)
		{
			echo "<p class=\"erreur\">Une erreur est survenue. La table <b>$table_categories</b> n'a pas pu être créée.</p>";
			$erreur="2";
		}
	
		//CREATION DE LA TABLE USERS
		$query="CREATE TABLE $table_users (
		  id int(11) NOT NULL auto_increment,
		  user text default NULL,
		  password text default NULL,
		  nom text default NULL,
		  prenom text default NULL,
		  email text default NULL,
		  ajouter tinyint(4) NOT NULL default '0',
		  modifier tinyint(4) NOT NULL default '0',
		  supprimer tinyint(4) NOT NULL default '0',
		  valider tinyint(4) NOT NULL default '0',
		  gerer tinyint(4) NOT NULL default '0',
		  actif tinyint(4) NOT NULL default '0',
		  PRIMARY KEY id (id)
		  )
		  CHARSET=utf8 COMMENT='XLAgenda - Table des utilisateurs'";
		$result=mysqli_query($connexion,$query);
		if (!$result)
		{
			echo "<p class=\"erreur\">Une erreur est survenue. La table <b>$table_users</b> n'a pas pu être créée.</p>";
			$erreur="2";
		}
	
		//CREATION DE LA TABLE DEMANDE
		$query="CREATE TABLE $table_demande (
		  id int(11) NOT NULL auto_increment,
		  nom text default NULL,
		  prenom text default NULL,
		  email text default NULL,
		  user text default NULL,
		  pass text default NULL,
		  motif text default NULL,
		  PRIMARY KEY id (id)
		  )
		  CHARSET=utf8 COMMENT = 'XLAgenda - Table des demandes de comptes'";
		$result=mysqli_query($connexion,$query);
		if (!$result)
		{
			echo "<p class=\"erreur\">Une erreur est survenue. La table <b>$table_demande</b> n'a pas pu être créée.</p>";
			$erreur="2";
		}
	
		//CREATION DE LA TABLE CONFIG
		$query="CREATE TABLE $table_config (
		  id int(11) NOT NULL auto_increment,
		  nom text default NULL,
		  valeur text default NULL,
		  PRIMARY KEY id (id)
		  )
		  CHARSET=utf8 COMMENT = 'XLAgenda - Paramètres'";
		$result=mysqli_query($connexion,$query);
		if (!$result)
		{
			echo "<p class=\"erreur\">Une erreur est survenue. La table <b>$table_config</b> n'a pas pu être créée.</p>";
			$erreur="2";
		}
		
		//AJOUT DES CONTRAINTES
		$query="ALTER TABLE $table_agenda ADD CONSTRAINT fk_categorie FOREIGN KEY (categorie) REFERENCES ".$table_categories."(id)";
		$result=mysqli_query($connexion,$query);
		$query="ALTER TABLE $table_agenda ADD CONSTRAINT fk_user FOREIGN KEY (id_user) REFERENCES ".$table_users."(id)";
		$result=mysqli_query($connexion,$query);
	
		if ($erreur)
		{
			echo "<p>Une ou plusieurs tables n'ont pas pu être créées. L'installation ne peut pas se poursuivre.<br />";
			echo "Vous devriez vérifier si la base de données <b>$dbdb</b> existe.</p>";
		}
		else
		{
			//AJOUT DES CATEGORIES D'EVENEMENTS
			$query="INSERT INTO $table_categories (id,nom) VALUES (1, 'Animation')";
			$result=mysqli_query($connexion,$query);
			$query="INSERT INTO $table_categories (id,nom) VALUES (2, 'Assemblée')";
			$result=mysqli_query($connexion,$query);
			$query="INSERT INTO $table_categories (id,nom) VALUES (3, 'Assises')";
			$result=mysqli_query($connexion,$query);
			$query="INSERT INTO $table_categories (id,nom) VALUES (4, 'Atelier')";
			$result=mysqli_query($connexion,$query);
			$query="INSERT INTO $table_categories (id,nom) VALUES (5, 'Café itinérant')";
			$result=mysqli_query($connexion,$query);
			$query="INSERT INTO $table_categories (id,nom) VALUES (6, 'Campagne d\'information')";
			$result=mysqli_query($connexion,$query);
			$query="INSERT INTO $table_categories (id,nom) VALUES (7, 'Colloque')";
			$result=mysqli_query($connexion,$query);
			$query="INSERT INTO $table_categories (id,nom) VALUES (8, 'Concert')";
			$result=mysqli_query($connexion,$query);
			$query="INSERT INTO $table_categories (id,nom) VALUES (9, 'Concours')";
			$result=mysqli_query($connexion,$query);
			$query="INSERT INTO $table_categories (id,nom) VALUES (10, 'Conférence')";
			$result=mysqli_query($connexion,$query);
			$query="INSERT INTO $table_categories (id,nom) VALUES (11, 'Conférence-débat')";
			$result=mysqli_query($connexion,$query);
			$query="INSERT INTO $table_categories (id,nom) VALUES (12, 'Conférence sandwich')";
			$result=mysqli_query($connexion,$query);
			$query="INSERT INTO $table_categories (id,nom) VALUES (13, 'Congrès')";
			$result=mysqli_query($connexion,$query);
			$query="INSERT INTO $table_categories (id,nom) VALUES (14, 'Convention')";
			$result=mysqli_query($connexion,$query);
			$query="INSERT INTO $table_categories (id,nom) VALUES (15, 'Débat')";
			$result=mysqli_query($connexion,$query);
			$query="INSERT INTO $table_categories (id,nom) VALUES (16, 'Déjeuner-débat')";
			$result=mysqli_query($connexion,$query);
			$query="INSERT INTO $table_categories (id,nom) VALUES (17, 'Emission de radio')";
			$result=mysqli_query($connexion,$query);
			$query="INSERT INTO $table_categories (id,nom) VALUES (18, 'Emission télévisée')";
			$result=mysqli_query($connexion,$query);
			$query="INSERT INTO $table_categories (id,nom) VALUES (19, 'Exposition')";
			$result=mysqli_query($connexion,$query);
			$query="INSERT INTO $table_categories (id,nom) VALUES (20, 'Festival')";
			$result=mysqli_query($connexion,$query);
			$query="INSERT INTO $table_categories (id,nom) VALUES (21, 'Film')";
			$result=mysqli_query($connexion,$query);
			$query="INSERT INTO $table_categories (id,nom) VALUES (22, 'Formation')";
			$result=mysqli_query($connexion,$query);
			$query="INSERT INTO $table_categories (id,nom) VALUES (23, 'Forum')";
			$result=mysqli_query($connexion,$query);
			$query="INSERT INTO $table_categories (id,nom) VALUES (24, 'Fête')";
			$result=mysqli_query($connexion,$query);
			$query="INSERT INTO $table_categories (id,nom) VALUES (25, 'Inauguration')";
			$result=mysqli_query($connexion,$query);
			$query="INSERT INTO $table_categories (id,nom) VALUES (27, 'Journée d\'information')";
			$result=mysqli_query($connexion,$query);
			$query="INSERT INTO $table_categories (id,nom) VALUES (28, 'Journée d\'étude')";
			$result=mysqli_query($connexion,$query);
			$query="INSERT INTO $table_categories (id,nom) VALUES (29, 'Manifestation')";
			$result=mysqli_query($connexion,$query);
			$query="INSERT INTO $table_categories (id,nom) VALUES (30, 'Opération culturelle')";
			$result=mysqli_query($connexion,$query);
			$query="INSERT INTO $table_categories (id,nom) VALUES (31, 'Petit déjeuner')";
			$result=mysqli_query($connexion,$query);
			$query="INSERT INTO $table_categories (id,nom) VALUES (32, 'Prix')";
			$result=mysqli_query($connexion,$query);
			$query="INSERT INTO $table_categories (id,nom) VALUES (33, 'Présentation d\'ouvrage')";
			$result=mysqli_query($connexion,$query);
			$query="INSERT INTO $table_categories (id,nom) VALUES (34, 'Rallye')";
			$result=mysqli_query($connexion,$query);
			$query="INSERT INTO $table_categories (id,nom) VALUES (35, 'Randonnée')";
			$result=mysqli_query($connexion,$query);
			$query="INSERT INTO $table_categories (id,nom) VALUES (36, 'Rencontre-débat')";
			$result=mysqli_query($connexion,$query);
			$query="INSERT INTO $table_categories (id,nom) VALUES (37, 'Réunion')";
			$result=mysqli_query($connexion,$query);
			$query="INSERT INTO $table_categories (id,nom) VALUES (38, 'Salon')";
			$result=mysqli_query($connexion,$query);
			$query="INSERT INTO $table_categories (id,nom) VALUES (39, 'Sommet')";
			$result=mysqli_query($connexion,$query);
			$query="INSERT INTO $table_categories (id,nom) VALUES (40, 'Spectacle')";
			$result=mysqli_query($connexion,$query);
			$query="INSERT INTO $table_categories (id,nom) VALUES (41, 'Stage')";
			$result=mysqli_query($connexion,$query);
			$query="INSERT INTO $table_categories (id,nom) VALUES (42, 'Symposium')";
			$result=mysqli_query($connexion,$query);
			$query="INSERT INTO $table_categories (id,nom) VALUES (43, 'Séminaire')";
			$result=mysqli_query($connexion,$query);
			$query="INSERT INTO $table_categories (id,nom) VALUES (44, 'Table ronde')"; 
			$result=mysqli_query($connexion,$query);
			$query="INSERT INTO $table_categories (id,nom) VALUES (45, 'Université d\'été')"; 
			$result=mysqli_query($connexion,$query);
			
			//INSERTION DU NUMERO DE VERSION
			$query="INSERT INTO $table_config (nom,valeur) VALUES ('current_version','$version')";
			$result=mysqli_query($connexion,$query);
			$date_install=date('d/m/Y');
			$query="INSERT INTO $table_config (nom,valeur) VALUES ('date_install','$date_install')";
			$result=mysqli_query($connexion,$query);
			
			echo "<p class=\"confirmation\">Les tables ont été créées avec succès dans votre base de données.</p>\n";
			//FORMULAIRE DE CREATION D'UTILISATEUR
			include("form.php");
		}
	}
}
?>
</body>
</html>