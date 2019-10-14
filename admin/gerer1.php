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
$x=0;
$erreur=0;
//RECUPERATION DES DONNEES
$id=(!empty($_REQUEST["id"])) ? $_REQUEST['id'] : Null;
$action=(!empty($_REQUEST["action"])) ? $_REQUEST['action'] : Null;
$form=(!empty($_POST["form"])) ? $_POST['form'] : Null;
$user=(!empty($_POST["user"])) ? $_POST['user'] : Null;
$user_old=(!empty($_POST["user_old"])) ? $_POST['user_old'] : Null;
$pass_perso=(!empty($_POST["pass_perso"])) ? $_POST['pass_perso'] : Null;
$pass=(!empty($_POST["pass"])) ? $_POST['pass'] : Null;
$pass2=(!empty($_POST["pass2"])) ? $_POST['pass2'] : Null;
$nom=(!empty($_POST["nom"])) ? $_POST['nom'] : Null;
$prenom=(!empty($_POST["prenom"])) ? $_POST['prenom'] : Null;
$email=(!empty($_POST["email"])) ? $_POST['email'] : Null;
$email_old=(!empty($_POST["email_old"])) ? $_POST['email_old'] : Null;
$ajouter_user=(!empty($_POST["ajouter"])) ? $_POST['ajouter'] : 0;
$modifier_user=(!empty($_POST["modifier"])) ? $_POST['modifier'] : 0;
$supprimer_user=(!empty($_POST["supprimer"])) ? $_POST['supprimer'] : 0;
$valider_user=(!empty($_POST["valider"])) ? $_POST['valider'] : 0;
$gerer_user=(!empty($_POST["gerer"])) ? $_POST['gerer'] : 0;
$actif=(!empty($_POST["actif"])) ? $_POST['actif'] : 0;
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
<script type="text/javascript">
<!--
function confirmerSuppression(id)
{
	if (confirm(confirmer_suppr_utilisateur)) 
	{
	document.location.href="gerer1.php?action=supprimer&id="+id;
	}
}
-->
</script>
<?php
include("header.php");
//ON TESTE SI L'UTILISATEUR EST CONNECTE
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
	echo "<h2>".$lang['admin_title_gerer_utilisateurs']."</h2>\n";
	//SI LE FORMULAIRE D'AJOUT D'UTILISATEUR A ETE ENVOYE
	if ($action == "ajouter")
	{
		if (!$user || !$pass || !$email)
		{
			echo "<p class=\"erreur\">".$lang['admin_erreur_champs_non_remplis']."<br />".$lang['admin_erreur_ajout_non_fait']."</p>\n";
			$erreur = 1;
		}
		if ($pass && ($pass != $pass2))
		{
			echo "<p class=\"erreur\">".$lang['admin_erreur_pass_differents']."<br />".$lang['admin_erreur_ajout_non_fait']."</p>\n";
			$erreur = 1;
		}
		if ($pass && ($user == $pass))
		{
			echo "<p class=\"erreur\">".$lang['admin_erreur_pass_iso_user']."<br />".$lang['admin_erreur_ajout_non_fait']."</p>\n";
			$erreur = 1;;
		}
		if (userExists($user))
		{
			echo "<p class=\"erreur\">".sprintf($lang['admin_erreur_user_utilise'],$user)."<br />".$lang['admin_erreur_ajout_non_fait']."</p>\n";
			$erreur = 1;
		}
		if (emailExists($email))
		{
			echo "<p class=\"erreur\">".sprintf($lang['admin_erreur_email_utilise'],$email)."<br />".$lang['admin_erreur_ajout_non_fait']."</p>\n";
			$erreur = 1;
		}
		if ($email && !checkEmail($email))
		{
			echo "<p class=\"erreur\">".$lang['admin_erreur_email_incorrect']."<br />".$lang['admin_erreur_ajout_non_fait']."</p>\n";
			$erreur = 1;
		}
		if ($pass && strlen($pass) < 6)
		{
			echo "<p class=\"erreur\">".$lang['admin_erreur_taille_pass']."<br />".$lang['admin_erreur_ajout_non_fait']."</p>\n";
			$erreur = 1;
		}
		if (!$erreur)
		{
			$pass=cryptPass($pass);
			$stmt=$connexion->prepare("INSERT INTO $table_users (user,password,email,nom,prenom,ajouter,modifier,supprimer,valider,gerer,actif) VALUES (?,?,?,?,?,?,?,?,?,?,?)");
			$stmt->bind_param('sssssiiiiii',$user,$pass,$email,$nom,$prenom,$ajouter_user,$modifier_user,$supprimer_user,$valider_user,$gerer_user,$actif);
			if ($stmt->execute())
			{
				$user = stripslashes($user);
				echo "<p class=\"confirmation\">".sprintf($lang['admin_confirmation_user_ajoute'],$user)."</p>\n";
			}
			$stmt->close();
		}
	}
	//SI L'UTILISATEUR A CLIQUE SUR UN LIEN DE SUPPRESSION
	if ($action == "supprimer" && is_numeric($id))
	{
		if (!getUser($id))
		{
			echo "<p class=\"erreur\">".$lang['admin_erreur_user_inexistant']."</p>\n";
		}
		//MODE DEMO
		elseif ((isset($demo) && $demo) && $user == "demo")
		{
			echo "<p class=\"erreur\">".$lang['admin_erreur_suppr_user_unauthorized']."</p>\n";
		}
		else
		{
			$result=$connexion->query("DELETE FROM $table_users WHERE id = $id");
			if ($result)
			{
				echo "<p class=\"confirmation\">".sprintf($lang['admin_confirmation_user_supprime'],$user)."</p>\n";
			}
			$connexion->query("OPTIMIZE TABLE $table_users");
		}
	}
	//SI L'UTILISATEUR VIENT DE RENVOYER LE FORMULAIRE DE MODIFICATION
	if ($action == "modifier" && is_numeric($id))
	{
		if (!$user)
		{
			echo "<p class=\"erreur\">".$lang['admin_erreur_user_manquant']."</p>\n";
			$erreur = 1;
		}
		if ($email && !checkEmail($email))
		{
			echo "<p class=\"erreur\">".$lang['admin_erreur_email_incorrect']."</p>\n";
			$erreur = 1;
		}
		if (userExists($user,$id))
		{
			echo "<p class=\"erreur\">".sprintf($lang['admin_erreur_user_utilise'],$user)."</p>\n";
			$erreur = 1;
		}
		if ($email && emailExists($email,$id))
		{
			echo "<p class=\"erreur\">".sprintf($lang['admin_erreur_email_utilise'],$email)."</p>\n";
			$erreur = 1;
		}
		if ($pass)
		{
			$nombre=strlen($pass);
			if ($nombre < 6)
			{
				echo "<p class=\"erreur\">".$lang['admin_erreur_taille_new_mdp']."</p>\n";
				$erreur = 1;
			}
			if ($pass != $pass2)
			{
				echo "<p class=\"erreur\">".$lang['admin_erreur_pass_differents']."</p>\n";
				$erreur = 1;
			}
			if (cryptPass($pass_perso) != $_SESSION['password'])
			{
				echo "<p class=\"erreur\">".$lang['admin_erreur_votre_mdp_invalide']."</p>\n";
				$erreur = 1;
			}
		}
		if ($erreur)
		{
			$user_old=stripslashes($user_old);
			echo "<p class=\"erreur\">".sprintf($lang['admin_erreur_compte_nom_modifie'],$user_old)."</p>\n";
		}
		else
		{
			if ($pass)
			{
				$pass=cryptPass($pass);
				$stmt=$connexion->prepare("UPDATE $table_users SET user=?,password=?,nom=?,prenom=?,email=?,ajouter=?,modifier=?,supprimer=?,valider=?,gerer=?,actif=? WHERE id = $id");
				$stmt->bind_param('sssssiiiiii',$user,$pass,$nom,$prenom,$email,$ajouter_user,$modifier_user,$supprimer_user,$valider_user,$gerer_user,$actif);
			}
			else
			{
				$stmt=$connexion->prepare("UPDATE $table_users SET user=?,nom=?,prenom=?,email=?,ajouter=?,modifier=?,supprimer=?,valider=?,gerer=?,actif=? WHERE id = $id");
				$stmt->bind_param('ssssiiiiii',$user,$nom,$prenom,$email,$ajouter_user,$modifier_user,$supprimer_user,$valider_user,$gerer_user,$actif);
			}
			if ($stmt->execute())
			{
				$user_old=stripslashes($user_old);
				echo "<p class=\"confirmation\">".sprintf($lang['admin_confirmation_compte_modifie'],$user_old)."</p>\n";
			}
			$stmt->close();
		}
	}
	echo "<p class=\"warning\">".sprintf($lang['admin_users_intro1'],$_SESSION['username'])."<br />".
	addLink($lang['admin_users_intro2'],"profil.php")."</p>\n";
	//NOMBRE D'UTILISATEURS EN ATTENTE DE VALIDATION
	if ($auth_gerer && $pending_users=getPendingUsers())
	{

		echo "<p>".sprintf($lang['admin_utilisateurs_en_attente'],$pending_users)." - <a href=\"demande1.php\">".$lang['admin_link_gerer']."</a></p>\n";
	}
	echo "<p>&gt; <a href=\"gerer2.php?action=ajouter\">".$lang['admin_link_ajouter_user']."</a></p>
	<table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"5\">
	<tr style=\"background-color:#ccc;\"> 
	<td width=\"11%\"><div align=\"center\"><strong>".$lang['admin_utilisateur']."</strong></div></td>
	<td width=\"14%\"><div align=\"center\"><strong>".$lang['admin_email']."</strong></div></td>
	<td width=\"12%\"><div align=\"center\"><strong>".$lang['admin_can_add_event']."</strong></div></td>
	<td width=\"12%\"><div align=\"center\"><strong>".$lang['admin_can_edit_event']."</strong></div></td>
	<td width=\"12%\"><div align=\"center\"><strong>".$lang['admin_can_delete_event']."</strong></div></td>
	<td width=\"12%\"><div align=\"center\"><strong>".$lang['admin_can_validate_event']."</strong></div></td>
	<td width=\"12%\"><div align=\"center\"><strong>".$lang['admin_can_manage_users']."</strong></div></td>
	<td width=\"15%\"><div align=\"center\"><strong>".$lang['admin_events_must_be_approved']."</strong></div></td>
	<td width=\"15%\"><div align=\"center\"><strong>".$lang['admin_action']."</strong></div></td>
	</tr>\n";
	$result=$connexion->query("SELECT * FROM $table_users ORDER BY user ASC");
	if ($result)
	{
		while($ligne=$result->fetch_array())
		{
			$id=$ligne["id"];
			$user=$ligne["user"];
			$email=$ligne["email"];
			$ajouter=$ligne["ajouter"];
			$modifier=$ligne["modifier"];
			$supprimer=$ligne["supprimer"];
			$valider=$ligne["valider"];
			$gerer=$ligne["gerer"];
			$actif=$ligne["actif"];
			$user=stripslashes($user);
			$pass=stripslashes($pass);
			if(( $x - ( 2*floor ($x /2))) == 0)
			{
				echo "<tr style=\"background-color:#ddd;\">\n";
			}
			else
			{
				echo "<tr style=\"background-color:#ccc;\">\n";
			}
			echo "<td>$user</td>\n";
			echo "<td>$email</td>\n";
			if (!$ajouter)
			{
				echo "<td>".$lang['admin_non']."</td>\n";
			}
			if ($ajouter)
			{
				echo "<td>".$lang['admin_oui']."</td>\n";
			}
			if ($modifier == 1)
			{
				echo "<td>".$lang['admin_seulement_siens']."</td>\n";
			}
			if ($modifier == 2)
			{
				echo "<td>".$lang['admin_tous']."</td>\n";
			}
			if (!$modifier)
			{
				echo "<td>".$lang['admin_non']."</td>\n";
			}
			if ($supprimer == 1)
			{
				echo "<td>".$lang['admin_seulement_siens']."</td>\n";
			}
			if ($supprimer == 2)
			{
				echo "<td>".$lang['admin_tous']."</td>\n";
			}
			if (!$supprimer)
			{
				echo "<td>".$lang['admin_non']."</td>\n";
			}
			if (!$valider)
			{
				echo "<td>".$lang['admin_non']."</td>\n";
			}
			if ($valider)
			{
				echo "<td>".$lang['admin_oui']."</td>\n";
			}
			if (!$gerer)
			{
				echo "<td>".$lang['admin_non']."</td>";
			}
			if ($gerer)
			{
				echo "<td>".$lang['admin_oui']."</td>\n";
			}
			if (!$actif)
			{
				echo "<td>".$lang['admin_oui']."</td>\n";
			}
			if ($actif)
			{
				echo "<td>".$lang['admin_non']."</td>\n";
			}
			if ($_SESSION['username'] == $user)
			{
				echo "<td>".$lang['admin_link_modifier']."<br />".$lang['admin_link_supprimer']."</td>\n";
			}
			//MODE DEMO
			elseif ((isset($demo) && $demo) && $user == "demo")
			{
				echo "<td>".$lang['admin_link_modifier']."<br />".$lang['admin_link_supprimer']."</td>\n";
			}
			else
			{
				echo "<td><a href=\"gerer2.php?action=modifier&amp;id=$id\">".$lang['admin_link_modifier']."</a><br /><a href=\"javascript:confirmerSuppression($id)\">".$lang['admin_link_supprimer']."</a></td>\n";
			}
			echo "</tr>\n";
			$x=$x+1;
		}
	}
	echo "
	</table>
	<p>&gt; <a href=\"gerer2.php?action=ajouter\">".$lang['admin_link_ajouter_user']."</a></p>
	<p>
		&gt; <a href=\"index.php\">".$lang['admin_link_menu']."</a><br />
		&gt; <a href=\"close.php\">".$lang['admin_link_deconnexion']."</a>
	</p>\n";
}
include("footer.php");
?>