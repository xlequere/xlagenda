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
$check_id=false;
$authorised=Null;
$x=Null;
//RECUPERATION DES DONNEES
$id=(!empty($_REQUEST['id'])) ? $_REQUEST['id'] : Null;
$action=(!empty($_GET['action'])) ? $_GET['action'] : Null;
$nom=(!empty($_POST['nom'])) ? $_POST['nom'] : Null;
$description=(!empty($_POST['description'])) ? $_POST['description'] : Null;
$categorie=(!empty($_POST['categorie'])) ? $_POST['categorie'] : Null;
$lieu=(!empty($_POST['lieu'])) ? $_POST['lieu'] : Null;
$contact=(!empty($_POST['contact'])) ? $_POST['contact'] : Null;
$adresse=(!empty($_POST['adresse'])) ? $_POST['adresse'] : Null;
$email=(!empty($_POST['email'])) ? $_POST['email'] : Null;
$telephone=(!empty($_POST['telephone'])) ? $_POST['telephone'] : Null;
$fax=(!empty($_POST['fax'])) ? $_POST['fax'] : Null;
$lien=(!empty($_POST['lien'])) ? $_POST['lien'] : Null;
$url=(!empty($_POST['url'])) ? $_POST['url'] : "http://";
$date_debut=(!empty($_POST['date_debut'])) ? $_POST['date_debut'] : "jj/mm/aaaa";
$date_fin=(!empty($_POST['date_fin'])) ? $_POST['date_fin'] : "jj/mm/aaaa";
$heure_debut=(!empty($_POST['heure_debut'])) ? $_POST['heure_debut'] : "hh:mm";
$heure_fin=(!empty($_POST['heure_fin'])) ? $_POST['heure_fin'] : "hh:mm";
$modifier_event=(!empty($_POST['modifier_event'])) ? $_POST['modifier_event'] : Null;
$modifier_valider_event=(!empty($_POST['modifier_valider_event'])) ? $_POST['modifier_valider_event'] : Null;
$nom=(!empty($_POST['nom'])) ? $_POST['nom'] : Null;
$user=(!empty($_POST['user'])) ? $_POST['user'] : Null;
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
function showEvent(id)
{
	if (document.getElementById('description_evenement_'+id).style.display == "none")
	{
		document.getElementById('description_evenement_'+id).style.display="block";
	}
	else
	{
		document.getElementById('description_evenement_'+id).style.display="none";
	}
}

function confirmerValidation(id)
{
	if (confirm(confirmer_valider_evenement)) 
	{
	document.location.href="valider1.php?action=valider&id="+id;
	}
}

function confirmerSuppression(id)
{
	if (confirm(confirmer_suppr_evenement)) 
	{
	document.location.href="valider1.php?action=supprimer&id="+id;
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
	$auth=array('valider');
	$auth=isAuthorized($auth);
	$auth_valider=$auth['valider'];
	if (!$auth_valider)
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
	echo "<h2>".$lang['admin_title_valider']."</h2>\n";
	//SI L'UTILISATEUR A CLIQUE SUR UN LIEN DE SUPPRESSION
	if ($id && $action && $nom_evenement=getEventName($id))
	{
		$check_id=true;
	}
	if ($action && $id && !$check_id)
	{
		echo "<p class=\"erreur\">".$lang['admin_erreur_evenement_inexistant']."</p>\n";
	}
	if ($action == "supprimer" && $check_id)
	{
		$result=$connexion->query("DELETE FROM $table_agenda WHERE id = $id");
		if ($result)
		{
			$nom_evenement=stripslashes($nom_evenement);
			echo "<p class=\"confirmation\">".sprintf($lang['admin_confirmation_evenement_supprime2'],$nom_evenement)."</p>\n";
		}
		$result=$connexion->query("OPTIMIZE TABLE $table_agenda");
	}
	//SI L'UTILISATEUR A CLIQUE SUR UN LIEN DE VALIDATION
	if ($action == "valider" && $check_id)
	{
		$result=$connexion->query("UPDATE $table_agenda SET actif = 1 WHERE id = $id");
		if ($result)
		{
			$nom_evenement=stripslashes($nom_evenement);
			echo "<p class=\"confirmation\">".sprintf($lang['admin_confirmation_evenement_valide'],$nom_evenement)."</p>\n";
		}
	}
	//SI L'UTILISATEUR A CLIQUE SUR LE LIEN DE VALIDATION GLOBALE
	if ($action == "tout_valider")
	{
		$result=$connexion->query("UPDATE $table_agenda SET actif = 1 WHERE actif = 0");
		if ($result)
		{
			echo "<p class=\"confirmation\">".$lang['admin_confirmation_tous_valides']."</p>\n";
		}
	}
	//SI L'UTILISATEUR VIENT DE RENVOYER LE FORMULAIRE DE MODIFICATION
	if (is_numeric($id) && ($modifier_event || $modifier_valider_event))
	{
		if (!$nom || !$description || !$categorie || !$date_debut || $date_debut == "jj/mm/aaaa")
		{
			echo "<p class=\"erreur\">".$lang['admin_erreur_champs_non_remplis']."<br />".$lang['admin_erreur_modif_non_faite']."</p>\n";
			$erreur="1";
		}
		elseif (!testDate($date_debut))
		{
			echo "<p class=\"erreur\">".$lang['admin_erreur_date_debut']."<br />".$lang['admin_erreur_modif_non_faite']."</p>\n";
			$erreur="1";
		}
		elseif ($date_fin && $date_fin != "jj/mm/aaaa" && !testDate($date_fin))
		{
			echo "<p class=\"erreur\">".$lang['admin_erreur_date_fin']."<br />".$lang['admin_erreur_modif_non_faite']."</p>\n";
			$erreur="1";
		}
		elseif ($heure_debut && $heure_debut != "hh:mm" && !testTime($heure_debut))
		{
			echo "<p class=\"erreur\">".$lang['admin_erreur_heure_debut']."<br />".$lang['admin_erreur_modif_non_faite']."</p>\n";
			$erreur="1";
		}
		elseif ($heure_fin && $heure_fin != "hh:mm" && !testTime($heure_fin))
		{
			echo "<p class=\"erreur\">".$lang['admin_erreur_heure_fin']."<br />".$lang['admin_erreur_modif_non_faite']."</p>\n";
			$erreur="1";
		}
		else
		{
			//FORMATAGE DES DATES ET DE L'URL
			$date_debut=mysqlDate($date_debut);
			if ($date_fin && $date_fin != "jj/mm/aaaa")
			{
				$date_fin=mysqlDate($date_fin);
			}
			else
			{
				$date_fin=$date_debut;
			}
			if ($heure_debut == "hh:mm")
			{
				$heure_debut=Null;
			}
			if ($heure_fin == "hh:mm")
			{
				$heure_fin=Null;
			}
			if ($url == "http://")
			{
				$url="";
			}
			if (!$lien && $url)
			{
				$lien=$url;
			}
			//SUPPRESION DU CODE HTML
			$nom=strip_tags($nom);
			$lieu=strip_tags($lieu);
			$contact=strip_tags($contact);
			$adresse=strip_tags($adresse);
			$description=cleanHtml($description);
			//AJOUT DES SAUTS DE LIGNE
			if (!$editeur_html)
			{
				$description=nl2br($description);
			}
			$stmt=$connexion->prepare("UPDATE $table_agenda SET date_debut=?,date_fin=?,heure_debut=?,heure_fin=?,nom=?,description=?,categorie=?,lieu=?,contact=?,email=?,adresse=?,fax=?,telephone=?,lien=?,url=? WHERE id=?");
			$stmt->bind_param('ssssssissssssssi',$date_debut,$date_fin,$heure_debut,$heure_fin,$nom,$description,$categorie,$lieu,$contact,$email,$adresse,$fax,$telephone,$lien,$url);
			if ($stmt->execute())
			{
				$nom=stripslashes($nom);
				echo "<p class=\"confirmation\">".sprintf($lang['admin_confirmation_evenement_modifie'],$nom)."</p>\n";
			}
			if ($modifier_valider_event)
			{
				$result=$connexion->query("UPDATE $table_agenda SET actif = 1 WHERE id = $id");
			if ($result)
			{
				$nom=stripslashes($nom);
				echo "<p class=\"confirmation\">".sprintf($lang['admin_confirmation_evenement_valide'],$nom)."</p>\n";
			}
		}
	}
}
$result=$connexion->query("SELECT * FROM $table_agenda WHERE actif=0 ORDER BY date_debut ASC, heure_debut ASC");
if ($result)
{
	$num=$result->num_rows;
	while ($ligne = $result->fetch_array())
	{
		$id=$ligne['id'];
		$date_debut=$ligne['date_debut'];
		$date_fin=$ligne['date_fin'];
		$heure_debut=$ligne['heure_debut'];
		$heure_fin=$ligne['heure_fin'];
		$nom=$ligne['nom'];
		$description=$ligne['description'];
		$categorie=$ligne['categorie'];
		$lieu=$ligne['lieu'];
		$contact=$ligne['contact'];
		$adresse=$ligne['adresse'];
		$email=$ligne['email'];
		$telephone=$ligne['telephone'];
		$fax=$ligne['fax'];
		$lien=$ligne['lien'];
		$url=$ligne['url'];
		$id_user=$ligne['id_user'];
		$date_debut=formatDate($date_debut);
		$date_fin=formatDate($date_fin);
		$heure_debut=formatTime($heure_debut);
		$heure_fin=formatTime($heure_fin);
		$categorie=getCategory($categorie);
		$auteur=getUser($id_user);
		$nom = stripslashes($nom);
		$description = stripslashes($description);
		$lieu = stripslashes($lieu);
		$contact = stripslashes($contact);
		$adresse = stripslashes($adresse);
		$lien = stripslashes($lien);
		echo "<div class=\"event\">";
		echo "<p>\n";
		if ($date_fin != $date_debut)
		{
			echo sprintf($lang['common_du_au'],$date_debut,$date_fin);
		}
		else
		{
			echo sprintf($lang['common_le'],$date_debut);
		}
		if ($heure_debut && $heure_fin)
		{
			echo " ".sprintf($lang['common_de_a'],$heure_debut,$heure_fin);
		}
			elseif ($heure_debut)
		{
		echo " ".sprintf($lang['common_a'],$heure_debut);
		}
		echo "<br />\n";
		echo "<strong>$categorie - <a href=\"javascript:showEvent($id)\">$nom</a></strong>\n";
		echo "</p>\n";
		echo "<div id=\"description_evenement_$id\" style=\"display:none\">\n";
		if ($editeur_html)
		{
			echo $description;
		}
		else
		{
			$description=nl2br($description);
			echo "<p>$description</p>\n";
		}
		echo "<p>\n";
		if ($email)
		{
			echo "<b>".$lang['admin_label_contact_email']."</b> $contact - <a href=\"mailto:$email\">$email</a><br />\n";
		}
		else
		{
			echo "<b>".$lang['admin_label_contact_email']."</b> $contact<br />\n";
		}
		echo "<b>".$lang['admin_label_tel']."</b> $telephone<br />\n";
		echo "<b>".$lang['admin_label_fax']."</b> $fax<br />\n";
		echo "<b>".$lang['admin_label_adresse']."</b> $adresse<br />\n";
		echo "<b>".$lang['admin_label_lien_url']."</b> <a href=\"$url\" target=\"_blank\">$lien</a>\n";
		echo "</p>\n";
		echo "</div>\n";
		echo "<p>".sprintf($lang['admin_poste_par'],$auteur)."</p>\n";
		echo "<p><a href=\"valider2.php?id=$id\">".$lang['admin_link_modifier']."</a> | <a href=\"javascript:confirmerValidation($id)\">".$lang['admin_link_valider']."</a> | <a href=\"javascript:confirmerSuppression($id)\">".$lang['admin_link_supprimer']."</a></p>\n";
		echo "</div>\n";
		}
	}
	if ($num)
	{
		echo "<p>&gt; <a href=\"valider1.php?action=tout_valider\">".$lang['admin_link_tout_valider']."</a></p>\n";
	}
	if (!$num)
	{
		echo "<p>".$lang['admin_aucun_evenement_attente']."</a></p>\n";
	}
	echo "<p>
	&gt; <a href=\"index.php\">".$lang['admin_link_menu']."</a><br />
	&gt; <a href=\"close.php\">".$lang['admin_link_deconnexion']."</a>
	</p>\n";
}
include("footer.php");
?>