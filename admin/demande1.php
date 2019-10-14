<?php
/*********************************************************************
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
include("../lang/".getLang()."/common.php");
include("../lang/".getLang()."/admin.php");
initSession();
//INITIALISATION DES VARIABLES
$authorised=0;
$erreur=0;
$envoi_confirmation=0;
$test_serveur=0;
//RECUPERATION DES DONNEES
$id=(!empty($_REQUEST["id"])) ? $_REQUEST['id'] : Null;
$action=(!empty($_REQUEST["action"])) ? $_REQUEST['action'] : Null;
$email=(!empty($_POST["email"])) ? $_POST['email'] : Null;
$user=(!empty($_POST["user"])) ? $_POST['user'] : Null;
$pass=(!empty($_POST["pass"])) ? $_POST['pass'] : Null;
$nom=(!empty($_POST["nom"])) ? $_POST['nom'] : Null;
$prenom=(!empty($_POST["prenom"])) ? $_POST['prenom'] : Null;
$ajouter=(!empty($_POST["ajouter"])) ? $_POST['ajouter'] : 0;
$modifier=(!empty($_POST["modifier"])) ? $_POST['modifier'] : 0;
$supprimer=(!empty($_POST["supprimer"])) ? $_POST['supprimer'] : 0;
$valider=(!empty($_POST["valider"])) ? $_POST['valider'] : 0;
$gerer=(!empty($_POST["gerer"])) ? $_POST['gerer'] : 0;
$actif=(!empty($_POST["actif"])) ? $_POST['actif'] : 0;
//PARAMETRES POUR LE MAIL DE CONFIRMATION
if ($email_exp)
{
	$headers_email = "From: $email_exp\n";
	$headers_email .= "MIME-Version: 1.0\n";
	$headers_email .= "Content-type: text/plain; charset=utf-8\n";
}
elseif ($test_serveur && ($server != "free.fr"))
{
	$headers_email = "From: webmaster@{$server}\n";
	$headers_email .= "MIME-Version: 1.0\n";
	$headers_email .= "Content-type: text/plain; charset=utf-8\n";
}
else
{
	$headers_email = "MIME-Version: 1.0\n";
	$headers_email .= "Content-type: text/plain; charset=utf-8\n";
}
$objet_email=$lang['admin_mail_confirmation_compte_objet'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php
echo "<title>$titre_page | ".$lang['admin_meta_administration']."</title>\n";
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="author" content="XLAgenda <?php echo getVersion() ?>" />
<meta name="author-url" content="http://xavier.lequere.net/xlagenda" />
<link rel="stylesheet" href="style.css" type="text/css" />
<meta name="robots" content="noindex, nofollow" />
<meta http-equiv="pragma" content="no_cache" />
<script type="text/javascript" src="lang-js.php"></script>
<script language = "JavaScript" type="text/javascript">
<!--
function confirmerValidation(id)
{
	if (confirm(confirmer_valider_demande))
	{
	document.location.href="demande1.php?action=valider&id="+id;
	}
}

function confirmerSuppression(id)
{
	if (confirm(confirmer_supprimer_demande))
	{
	document.location.href="demande1.php?action=supprimer&id="+id;
	}
}
// -->
</script>
<?php
include("header.php");
//ON TESTE SI L"UTILISATEUR EST CONNECTE
if (!isSessionValid())
{
//SI l'UTILISATEUR N'EST PAS CONNECTE
echo "<p>".addLink($lang['admin_session_off'],"index.php")."</p>\n";
}

else
{
//SI L'UTILISATEUR EST CONNECTE
//CONTROLE DE L'AUTORISATION D'ACCEDER A LA PAGE
$auth=array('gerer');
$auth=isAuthorized($auth);
$auth_gerer=$auth['gerer'];
if (!$auth_gerer)
	{
		echo "<p>".$lang['admin_unauthorized1']."<br />".
		addLink($lang['admin_unauthorized2'],"index.php")."</p>\n";
	}
	else
	{
		$authorised=1;
	}
}

//SI l'UTILISATEUR EST AUTORISE A ACCEDER A LA PAGE
if ($authorised == 1)
{
	include ("menu.php");
	echo "<h2>".$lang['admin_title_gerer_utilisateurs_attente']."</h2>\n";
	//SUPPRESSION
	if ($action == "supprimer" && is_numeric($id))
	{
		$result=$connexion->query("SELECT prenom,nom FROM $table_demande WHERE id = $id");
		$test=$result->num_rows;
		$ligne = $result->fetch_array();
		$prenom=$ligne["prenom"];
		$nom=$ligne["nom"];
		if (!$test)
		{
			echo "<p class=\"erreur\">".$lang['admin_erreur_demande_inexistante']."</p>\n";
		}
		else
		{
			$result=$connexion->query("DELETE FROM $table_demande WHERE id = $id");
			if ($result)
			{
				$prenom=stripslashes($prenom);
				$nom=stripslashes($nom);
				echo "<p class=\"confirmation\">".sprintf($lang['admin_confirmation_demande_supprime'],"$prenom $nom")."</p>\n";
			}
			$connexion->query("OPTIMIZE TABLE $table_demande");
		}
	}
	//VALIDATION DIRECTE
	if ($action == "valider" && is_numeric($id))
	{
		$result=$connexion->query("SELECT user,pass,email,nom,prenom FROM $table_demande WHERE id= $id");
		$test=$result->num_rows;
		$ligne = $result->fetch_array();
		$user=$ligne["user"];
		$pass=$ligne["pass"];
		$email=$ligne["email"];
		$nom=$ligne["nom"];
		$prenom=$ligne["prenom"];
		if (!$test)
		{
			echo "<p class=\"erreur\">".$lang['admin_erreur_demande_inexistante']."</p>\n";
		}
		else
		{
			if (emailExists($email))
			{
				echo "<p class=\"erreur\">".sprintf($lang['admin_erreur_email_utilise'],$email)."</p>\n";
			}
			elseif (userExists($user))
			{
				echo "<p class=\"erreur\">".sprintf($lang['admin_erreur_user_utilise'],$user)."</p>\n";
			}
			else
			{
				$cryptPass=cryptPass($pass);
				$result=$connexion->query("INSERT INTO $table_users (user, password, email, nom, prenom, ajouter, modifier, supprimer, valider, gerer, actif) (select user,'$cryptPass',email,nom,prenom,'1', '1', '1', '0', '0', '0' FROM $table_demande WHERE id = $id)");
				if ($result)
				{
					$prenom=stripslashes($prenom);
					$nom=stripslashes($nom);
					$user=stripslashes($user);
					$pass=stripslashes($pass);
					if ($confirmation_compte)
					{
						$envoi_confirmation=1;
					}
					if ($prenom || $nom)
					{
						$utilisateur="$prenom $nom";
					}
					else
					{
						$utilisateur=$user;
					}
					echo "<p class=\"confirmation\">".sprintf($lang['admin_confirmation_demande_valide'],$utilisateur)."</p>\n";
					$connexion->query("DELETE FROM $table_demande WHERE id = $id");
					$connexion->query("OPTIMIZE TABLE $table_demande");
				}
				else
				{
					echo "<p class=\"erreur\">".$lang['admin_erreur_validation_demande']."</p>\n";
				}
			}
		}
	}
	//MODIFICATION ET VALIDATION
	if ($action == "modifier" && is_numeric($id))
	{
		if (!$user)
		{
			echo "<p class=\"erreur\">".$lang['admin_erreur_user_manquant']."</p>\n";
			$erreur = 1;
		}
		if (!$pass)
		{
			echo "<p class=\"erreur\">".$lang['admin_erreur_password_manquant']."</p>\n";
			$erreur = 1;
		}
		if ($pass && strlen($pass) < 6)
		{
			echo "<p class=\"erreur\">".$lang['admin_js_erreur_password_taille']."</p>\n";
			$erreur = 1;
		}
		if ($email && !checkEmail($email))
		{
			echo "<p class=\"erreur\">".$lang['admin_erreur_email_incorrect']."</p>\n";
			$erreur = 1;
		}
		if (userExists($user))
		{
			echo "<p class=\"erreur\">".sprintf($lang['admin_erreur_user_utilise'],$user)."</p>\n";
			$erreur = 1;
		}
		if ($email && emailExists($email))
		{
			echo "<p class=\"erreur\">".sprintf($lang['admin_erreur_email_utilise'],$email)."</p>\n";
			$erreur = 1;
		}
		if (!$erreur)
		{
			$cryptPass=cryptPass($pass);
			$stmt=$connexion->prepare("INSERT INTO $table_users (user, password, email, nom, prenom, ajouter, modifier, supprimer, valider, gerer, actif) VALUES (?,?,?,?,?,?,?,?,?,?,?)");
			$stmt->bind_param('sssssiiiiii',$user,$cryptPass,$email,$nom,$prenom,$ajouter,$modifier,$supprimer,$valider,$gerer,$actif);
			if ($stmt->execute())
			{
				$prenom=stripslashes($prenom);
				$nom=stripslashes($nom);
				$user=stripslashes($user);
				$pass=stripslashes($pass);
				if ($confirmation_compte)
				{
					$envoi_confirmation=1;
				}
				if ($prenom || $nom)
				{
					echo "<p class=\"confirmation\">".sprintf($lang['admin_confirmation_demande_valide'],"$prenom $nom")."</p>\n";
				}
				else
				{
					echo "<p class=\"confirmation\">".sprintf($lang['admin_confirmation_demande_valide'],$user)."</p>\n";
				}
				$connexion->query("DELETE FROM $table_demande WHERE id = $id");
				$connexion->query("OPTIMIZE TABLE $table_demande");
			}
			else
			{
				echo "<p class=\"erreur\">".$lang['admin_erreur_validation_demande']."</p>\n";
			}
			$stmt->close();
		}
	}
	//ENVOI DU MESSAGE DE CONFIRMATION
	if ($envoi_confirmation)
	{
		$url_agenda="http://$server/$path_agenda/admin";
		$texte_email = $lang['admin_mail_confirmation_compte1']." $prenom $nom\n";
		$texte_email .= sprintf($lang['admin_mail_confirmation_compte2'],$server,$url_agenda)."\n\n";
		$texte_email .= sprintf($lang['admin_mail_confirmation_compte3'],$user,$pass);
		@mail($email,$objet_email,$texte_email,$headers_email);
	}
	//LISTE DES UTILISATEURS EN ATTENTE
	$result=$connexion->query("SELECT * FROM $table_demande ORDER BY nom ASC, prenom ASC");
	if ($result)
	{
		$num=$result->num_rows;
		while($ligne = $result->fetch_array())
		{
			$id=$ligne["id"];
			$nom=$ligne["nom"];
			$prenom=$ligne["prenom"];
			$email=$ligne["email"];
			$user=$ligne["user"];
			$pass=$ligne["pass"];
			$motif=$ligne["motif"];
			$nom = stripslashes($nom);
			$prenom = stripslashes($prenom);
			$user = stripslashes($user);
			$pass = stripslashes($pass);
			$motif = nl2br(stripslashes($motif));
			echo "<div class=\"event\">\n";
			echo "<p>\n";
			echo "<b>".$lang['admin_label_nom']."</b> $nom<br />\n";
			echo "<b>".$lang['admin_label_prenom']."</b> $prenom<br />\n";
			echo "<b>".$lang['admin_label_email']."</b> $email\n";
			echo "</p>\n";
			echo "<p>\n";
			echo "<b>".$lang['admin_label_username_choisi']."</b> $user<br />\n";
			echo "<b>".$lang['admin_label_password_choisi']."</b> $pass\n";
			echo "</p>\n";
			echo "<p><b>".$lang['admin_label_motif_demande']."</b><br />$motif</p>\n";
			echo "<p><a href=\"javascript:confirmerValidation($id)\">".$lang['admin_link_valider']."</a> | <a href=\"demande2.php?id=$id\">".$lang['admin_link_modifier_valider']."</a> | <a href=\"javascript:confirmerSuppression($id)\">".$lang['admin_link_rejeter']."</a></p>\n";
			echo "</div>\n";
		}
	}
	if (!$num)
	{
		echo "<p>".$lang['admin_aucun_utilisateur_attente']."</p>\n";
		echo "<p>&gt; <a href=\"gerer1.php\">".$lang['admin_link_gerer_utilisateurs']."</a></p>\n";
	}
	echo "<p>
		&gt; <a href=\"index.php\">".$lang['admin_link_menu']."</a><br />
		&gt; <a href=\"close.php\">".$lang['admin_link_deconnexion']."</a>
	</p>\n";
}
include("footer.php");
?>