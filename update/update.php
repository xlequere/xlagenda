<?php
/******************************************************************
*	XLAgenda 4 par Xavier LE QUERE
*   Web : http://xavier.lequere.net/xlagenda
*   (C) Xavier LE QUERE, 2003-2020
*   Version 4.5.2 - 26/02/20
*
*  This program is free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*  
*  This program is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
*  GNU General Public License for more details.
*  
*  You should have received a copy of the GNU General Public License
*  along with this program; if not, write to the Free Software
*  Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
*********************************************************************/

include("../include/data.php");
include("../include/connexion.php");
include("../include/functions.php");
include('include.php');
//RECUPERATION DES DONNEES
$user=(isset($_POST['user'])) ? $_POST['user'] : Null;
$pass=(isset($_POST['pass'])) ? $_POST['pass'] : Null;
//PROTECTION POUR EVITER LES INJECTIONS SQL
$user=mysqli_real_escape_string($connexion,$user);
$pass=mysqli_real_escape_string($connexion,$pass);
//INITIALISATION DES VARIABLES
$erreur="";
$valid="";
$code="";
$update_users="";
$update_pass="";
$test="";
$mdp41="";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>XLAgenda <?php echo $version ?> | Mise à jour</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="robots" content="noindex, nofollow" />
<link href="style.css" rel="stylesheet" type="text/css" />
</head>

<body>
<?php
if (!$user OR !$pass)
{
	$erreur=1;
}
else
{
	$query="SELECT password FROM $table_users";
	$result=mysqli_query($connexion,$query);
	if ($result) $mdp41=1;
	
	if ($mdp41)
	{
		$query="SELECT id FROM $table_users WHERE user='$user' AND password != ''";
		$result=mysqli_query($connexion,$query);
		if ($result AND mysqli_num_rows($result)) $test=1;
		else
		{
			$query="SELECT id FROM $table_users WHERE user='$user' AND password = ''";
			$result=mysqli_query($connexion,$query);
			if ($result AND mysqli_num_rows($result)) $test=2;
		}
	}
	else
	{
		$test=2;
	}

	if ($test == 1)
	{
		//MDP EN MODE 4.1
		$pass=cryptPass($pass);
		$query="SELECT id FROM $table_users WHERE user='$user' AND password='$pass'";
		$result=mysqli_query($connexion,$query);
		if ($result AND mysqli_num_rows($result))
		{
			$valid=1;
			$code=1;
		}
	}

	elseif ($test == 2)
	{
		//MDP EN MODE AVANT 4.1
		$pass_crypt=crypt($pass,$user);
		$query="SELECT id FROM $table_users WHERE user='$user' AND pass='$pass_crypt'";
		$result=mysqli_query($connexion,$query);
		if ($result AND mysqli_num_rows($result))
		{
			$valid=1;
			$code=1;
		}
		else
		{
			//MDP EN MODE AVANT 4.1 NON CRYPTE
			$query="SELECT id FROM $table_users WHERE user='$user' AND pass='$pass'";
			$result=mysqli_query($connexion,$query);
			if ($result AND mysqli_num_rows($result))
			{
				$valid=1;
			}
		}
	}
	if (!$erreur AND !$valid) $erreur=2;
}

if ($erreur == 1)
{
	echo "<h1>Mise à jour - Etape 2/3</h1>";
	echo "<p class=\"erreur\">Erreur : merci d'introduire votre nom d'utilisateur et votre mot de passe.</p>";
	include("form.php");
}
elseif ($erreur == 2)
{
	echo "<h1>Mise à jour - Etape 2/3</h1>";
	echo "<p class=\"erreur\">Erreur : mot de passe ou nom d'utilisateur incorrect.</p>";
	include("form.php");
}
elseif ($valid)
{
	echo "<h1>Mise à jour - Etape 3/3</h1>";
	//CONTROLE DE LA CONNEXION A LA BASE DE DONNEES
	if ((!$dbserver) OR (!$dbdb) OR (!$dbuser))
	{
		echo "<p class=\"erreur\">Erreur : vous n'avez pas édité le fichier data.php. L'installation ne peut pas être effectuée.</p>";
	}
	else
	{
		echo "<p>";
		//AJOUT DE CHAMPS A LA TABLE AGENDA
		$query="SELECT lieu FROM $table_agenda";
		$result=mysqli_query($connexion,$query);
		if (!$result)
		{
			$query="ALTER TABLE $table_agenda ADD lieu TEXT NOT NULL AFTER description";
			$result=mysqli_query($connexion,$query);
			if ($result) echo "Un champ <b>lieu</b> a été ajouté à la table <b>$table_agenda</b>.<br />\n";
		}
		$query="SELECT adresse FROM $table_agenda";
		$result=mysqli_query($connexion,$query);
		if (!$result)
		{
			$query="ALTER TABLE $table_agenda ADD adresse TEXT NOT NULL AFTER contact";
			$result=mysqli_query($connexion,$query);
			if ($result) echo "Un champ <b>adresse</b> a été ajouté à la table <b>$table_agenda</b>.<br />\n";
		}
		$query="SELECT lien FROM $table_agenda";
		$result=mysqli_query($connexion,$query);
		if (!$result)
		{
			$query="ALTER TABLE $table_agenda ADD lien TEXT NOT NULL AFTER fax";
			$result=mysqli_query($connexion,$query);
			if ($result) echo "Un champ <b>lien</b> a été ajouté à la table <b>$table_agenda</b>.<br />\n";
		}
		$query="SELECT heure_debut FROM $table_agenda";
		$result=mysqli_query($connexion,$query);
		if (!$result)
		{
			$query="ALTER TABLE $table_agenda ADD heure_debut TIME NULL DEFAULT NULL AFTER date_fin";
			$result=mysqli_query($connexion,$query);
			if ($result) echo "Un champ <b>heure_debut</b> a été ajouté à la table <b>$table_agenda</b>.<br />\n";
		}
		$query="SELECT heure_fin FROM $table_agenda";
		$result=mysqli_query($connexion,$query);
		if (!$result)
		{
			$query="ALTER TABLE $table_agenda ADD heure_fin TIME NULL DEFAULT NULL AFTER heure_debut";
			$result=mysqli_query($connexion,$query);
			if ($result) echo "Un champ <b>heure_fin</b> a été ajouté à la table <b>$table_agenda</b>.<br />\n";
		}
		
		//AJOUT DE CHAMPS A LA TABLE USERS
		$query="SELECT password FROM $table_users";
		$result=mysqli_query($connexion,$query);
		if (!$result)
		{
			$query="ALTER TABLE $table_users ADD password TEXT NOT NULL AFTER pass";
			$result=mysqli_query($connexion,$query);
			if ($result) echo "Un champ <b>password</b> a été ajouté à la table <b>$table_users</b>.<br />\n";
		}
		$query="SELECT nom FROM $table_users";
		$result=mysqli_query($connexion,$query);
		if (!$result)
		{
			$query="ALTER TABLE $table_users ADD nom TEXT NOT NULL AFTER password";
			$result=mysqli_query($connexion,$query);
			if ($result) echo "Un champ <b>nom</b> a été ajouté à la table <b>$table_users</b>.<br />\n";
		}
		$query="SELECT prenom FROM $table_users";
		$result=mysqli_query($connexion,$query);
		if (!$result)
		{
			$query="ALTER TABLE $table_users ADD prenom TEXT NOT NULL AFTER nom";
			$result=mysqli_query($connexion,$query);
			if ($result) echo "Un champ <b>prenom</b> a été ajouté à la table <b>$table_users</b>.<br />\n";
		}
		
		//AJOUT DE CHAMPS A LA TABLE CATEGORIES
		$query="SELECT actif FROM $table_categories";
		$result=mysqli_query($connexion,$query);
		if (!$result)
		{
			$query="ALTER TABLE $table_categories ADD actif TINYINT NOT NULL DEFAULT '1'";
			$result=mysqli_query($connexion,$query);
			if ($result) echo "Un champ <b>actif</b> a été ajouté à la table <b>$table_categories</b>.<br />\n";
		}
		$query="SELECT couleur FROM $table_categories";
		$result=mysqli_query($connexion,$query);
		if (!$result)
		{
			$query="ALTER TABLE $table_categories ADD couleur TEXT NOT NULL AFTER nom";
			$result=mysqli_query($connexion,$query);
			if ($result) echo "Un champ <b>nom</b> a été ajouté à la table <b>$table_categories</b>.<br />\n";
		}
		
		//AJOUT DU CHAMP ID_USER A LA TABLE AGENDA
		$query="SELECT id_user FROM $table_agenda";
		$result=mysqli_query($connexion,$query);
		if (!$result)
		{
			$query="ALTER TABLE $table_agenda ADD id_user INT NOT NULL DEFAULT '0' AFTER user";
			$result=mysqli_query($connexion,$query);
			if ($result) echo "Un champ <b>id_user</b> a été ajouté à la table <b>$table_agenda</b>.<br />\n";
			$update_users=1;
		}
		
		//CREATION DE LA TABLE DEMANDE
		$query="SELECT id FROM $table_demande";
		$result=mysqli_query($connexion,$query);
		if (!$result)
		{
			$query="CREATE TABLE $table_demande (
			 id int(11) NOT NULL auto_increment,
			 nom text NOT NULL,
			 prenom text NOT NULL,
			 email text NOT NULL,
			 user text NOT NULL,
			 pass text NOT NULL,
			 motif text NOT NULL,
			 PRIMARY KEY id (id)
			 )
			 COMMENT = 'XLAgenda - Table des demandes de comptes'";
			$result=mysqli_query($connexion,$query);
			if ($result) echo "La table <b>$table_demande</b> a été créée.<br />\n";
		}
		
		//CREATION DE LA TABLE CONFIG
		$query="SELECT id FROM $table_config";
		$result=mysqli_query($connexion,$query);
		if (!$result)
		{
			$query="CREATE TABLE $table_config (
			  id int(11) NOT NULL auto_increment,
			  nom text NOT NULL,
			  valeur text NOT NULL,
			  PRIMARY KEY id (id)
			  )
			  COMMENT = 'XLAgenda - Paramètres'";
			$result=mysqli_query($connexion,$query);
			if ($result) echo "La table <b>$table_config</b> a été créée.<br />\n";
		}
		
		if (!$code)
		{
			//CHIFFRAGE DES MOTS DE PASSE
			$query="SELECT * FROM $table_users";
			$result=mysqli_query($connexion,$query);
			if($result)
			{
				while($ligne=mysqli_fetch_array($result))
				{
					$id=$ligne["id"];
					$pass=$ligne["pass"];
					$user=$ligne["user"];
					$pass=cryptPass($pass);
					$query2="UPDATE $table_users SET password='$pass' WHERE id='$id'";
					$result2=mysqli_query($connexion,$query2);
					//SUPPRESSION DU CHAMP PASS
					$query3="ALTER TABLE $table_users DROP pass";
					$result3=mysqli_query($query3);
				}
			}
			echo "Les mots de passe ont été chiffrés dans la table <b>$table_users</b>.<br />\n";
			echo "Le champ <b>pass</b> a été supprimé de la table <b>$table_users</b>.<br />\n";
		}
		
		if ($update_users)
		{
			//AJOUT DES ID DES UTILISATEURS DANS LA TABLE AGENDA
			$query="SELECT id,user FROM $table_users";
			$result=mysqli_query($connexion,$query);
			if($result)
			{
				while($ligne=mysqli_fetch_array($result))
				{
					$id_utilisateur=$ligne["id"];
					$nom_utilisateur=$ligne["user"];
					$query2="UPDATE $table_agenda SET id_user='$id_utilisateur' WHERE user='$nom_utilisateur'";
					$result2=mysqli_query($connexion,$query2);
				}
			}
		}
		
		//SUPPRESSION DU CHAMP USER
		$query="SELECT user FROM $table_agenda";
		$result=mysqli_query($connexion,$query);
		if($result)
		{
			$query="ALTER TABLE $table_agenda DROP user";
			$result=mysqli_query($connexion,$query);
			if ($result) echo "Le champ <b>user</b> a été supprimé de la table <b>$table_agenda</b>.";
		}
		
		//RECUPERATION DU NUMERO DE VERSION
		$query="SELECT valeur FROM $table_config WHERE nom = 'current_version'";
		$result=mysqli_query($connexion,$query);
		if($result AND mysqli_num_rows($result))
		{
			$ligne=mysqli_fetch_array($result);
			$old_version=$ligne["valeur"];
		}
		else
		{
			$old_version=Null;
		}
		
		if (!$old_version || $old_version != "4.3")
		{
			//CONVERSION EN UTF8
			$query="ALTER TABLE $table_agenda CHARACTER SET UTF8";
			$result=mysqli_query($connexion,$query);
			$query="ALTER TABLE $table_categories CHARACTER SET UTF8";
			$result=mysqli_query($connexion,$query);
			$query="ALTER TABLE $table_config CHARACTER SET UTF8";
			$result=mysqli_query($connexion,$query);
			$query="ALTER TABLE $table_demande CHARACTER SET UTF8";
			$result=mysqli_query($connexion,$query);
			$query="ALTER TABLE $table_logs CHARACTER SET UTF8";
			$result=mysqli_query($connexion,$query);
			$query="ALTER TABLE $table_users CHARACTER SET UTF8";
			$result=mysqli_query($connexion,$query);
			
			$query="ALTER TABLE $table_agenda CONVERT TO CHARACTER SET UTF8";
			$result=mysqli_query($connexion,$query);
			$query="ALTER TABLE $table_categories CONVERT TO CHARACTER SET UTF8";
			$result=mysqli_query($connexion,$query);
			$query="ALTER TABLE $table_config CONVERT TO CHARACTER SET UTF8";
			$result=mysqli_query($connexion,$query);
			$query="ALTER TABLE $table_demande CONVERT TO CHARACTER SET UTF8";
			$result=mysqli_query($connexion,$query);
			$query="ALTER TABLE $table_logs CONVERT TO CHARACTER SET UTF8";
			$result=mysqli_query($connexion,$query);
			$query="ALTER TABLE $table_users CONVERT TO CHARACTER SET UTF8";
			$result=mysqli_query($connexion,$query);
			
			$query="SELECT id,nom FROM $table_categories";
			$result=mysqli_query($connexion,$query);
			if ($result)
			{
				while($ligne=mysqli_fetch_array($result))
				{
					$query2="UPDATE $table_categories SET nom = '".addslashes(html_entity_decode($ligne['nom'],ENT_COMPAT,"UTF-8"))."' WHERE id = '".$ligne['id']."'";
					$result2=mysqli_query($connexion,$query2);
				}
			}
		}
		
		if (!$old_version || $old_version != "4.5")
		{
			//AJOUT DES VALEURS PAR DEFAUT
			$query="ALTER TABLE $table_agenda CHANGE nom nom TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL, CHANGE date_debut date_debut DATE NULL DEFAULT NULL, CHANGE date_fin date_fin DATE NULL DEFAULT NULL, CHANGE heure_debut heure_debut TIME NULL DEFAULT NULL, CHANGE heure_fin heure_fin TIME NULL DEFAULT NULL, CHANGE description description TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL, CHANGE lieu lieu TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL, CHANGE categorie categorie INT(11) NULL DEFAULT NULL, CHANGE contact contact TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL, CHANGE adresse adresse TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL, CHANGE email email TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL, CHANGE telephone telephone TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL, CHANGE fax fax TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL, CHANGE lien lien TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL, CHANGE url url TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL, CHANGE id_user id_user INT(11) NULL DEFAULT NULL";
			$result=mysqli_query($connexion,$query);
					
			$query="ALTER TABLE $table_categories CHANGE nom nom TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL, CHANGE couleur couleur TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL";
			$result=mysqli_query($connexion,$query);			
			$query="ALTER TABLE $table_config CHANGE nom nom TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL, CHANGE valeur valeur TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL";
			$result=mysqli_query($connexion,$query);
					
			$query="ALTER TABLE $table_demande CHANGE nom nom TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL, CHANGE prenom prenom TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL, CHANGE email email TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL, CHANGE user user TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL, CHANGE pass pass TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL, CHANGE motif motif TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL";
			$result=mysqli_query($connexion,$query);
				
			$query="ALTER TABLE $table_users CHANGE user user TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL, CHANGE password password TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL, CHANGE nom nom TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL, CHANGE prenom prenom TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL, CHANGE email email TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL";
			$result=mysqli_query($connexion,$query);
					
			$query="ALTER TABLE $table_logs CHANGE user user TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL, CHANGE pass pass TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL, CHANGE date date DATE NULL DEFAULT NULL, CHANGE time time TIME NULL DEFAULT NULL, CHANGE ip ip TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL, CHANGE domain domain TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL, CHANGE result result TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL";
			$result=mysqli_query($connexion,$query);
						
			//CONVERSION DES DATES ET HEURES PAR DEFAUT
			$query="UPDATE $table_agenda SET date_debut = NULL WHERE date_debut = '0000-00-00'";
			$result=mysqli_query($connexion,$query);
			$query="UPDATE $table_agenda SET date_fin = NULL WHERE date_fin = '0000-00-00'";
			$result=mysqli_query($connexion,$query);			
			$query="UPDATE $table_agenda SET heure_debut = NULL WHERE heure_debut = '00:00:00'";
			$result=mysqli_query($connexion,$query);
			$query="UPDATE $table_agenda SET heure_fin = NULL WHERE heure_fin = '00:00:00'";
			$result=mysqli_query($connexion,$query);
			$query="UPDATE $table_logs SET date = NULL where date = '0000-00-00'";
			$result=mysqli_query($connexion,$query);
			$query="UPDATE $table_logs SET time = NULL where time = '00:00:00'";
			$result=mysqli_query($connexion,$query);
			
			//CONVERSION DES VALEURS PAR DEFAUT
			$query="UPDATE $table_agenda set categorie = NULL where categorie = 0";
			$result=mysqli_query($connexion,$query);
			$query="UPDATE $table_agenda set id_user = NULL where id_user = 0";
			$result=mysqli_query($connexion,$query);
			
			//AJOUT DES FOREIGN KEYS
			$query="ALTER TABLE $table_agenda ADD CONSTRAINT fk_categorie FOREIGN KEY (categorie) REFERENCES ".$table_categories."(id)";
			$result=mysqli_query($connexion,$query);
			
			$query="ALTER TABLE $table_agenda ADD CONSTRAINT fk_user FOREIGN KEY (id_user) REFERENCES ".$table_users."(id)";
			$result=mysqli_query($connexion,$query);
			
			//AJOUT DES INDEX
			$query="ALTER TABLE $table_agenda ADD INDEX (date_debut)";
			$result=mysqli_query($connexion,$query);
			$query="ALTER TABLE $table_agenda ADD INDEX (date_fin)";
			$result=mysqli_query($connexion,$query);
		}
		
		//MISE A JOUR DU NUMERO DE VERSION
		if (!$old_version)
		{
			$query="INSERT INTO $table_config (nom,valeur) VALUES ('current_version','$version')";
			$result=mysqli_query($connexion,$query);
			$date_update=date('d/m/Y');
			$query="INSERT INTO $table_config (nom,valeur) VALUES ('date_update','$date_update')";
			$result=mysqli_query($connexion,$query);
		}
		elseif ($old_version && $old_version != $version)
		{
			$query="UPDATE $table_config SET valeur = '$version' WHERE nom = 'current_version'";
			$result=mysqli_query($connexion,$query);
			$query="INSERT INTO $table_config (nom,valeur) VALUES ('old_version','$old_version')";
			$result=mysqli_query($connexion,$query);
			$date_update=date('d/m/Y');
			$query="INSERT INTO $table_config (nom,valeur) VALUES ('date_update','$date_update')";
			$result=mysqli_query($connexion,$query);
		}
		
		echo "</p>";
		echo "<p class=\"confirmation\">La mise à jour a été effectuée avec succès.</p>\n";
		echo "<p>Vous pouvez désormais vous rendre sur <a href=\"../$repertoire_admin/index.php\">l'interface d'administration</a>.<br />\n";
		echo "N'oubliez pas de supprimer du serveur le répertoire <b>update</b>.</p>\n";
	}
}
?>
</body>
</html>