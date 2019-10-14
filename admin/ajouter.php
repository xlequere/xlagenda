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
$select=0;
$texte=Null;
$test_serveur=0;
//RECUPERATION DES DONNEES
$server=(!empty($_SERVER["SERVER_NAME"])) ? $_SERVER["SERVER_NAME"] : Null;
$envoye=(!empty($_POST['envoye'])) ? $_POST['envoye'] : Null;
$nom=(!empty($_POST['nom'])) ? $_POST['nom'] : Null;
$description=(!empty($_POST['description'])) ? $_POST['description'] : Null;
$categorie=(!empty($_POST['categorie'])) ? $_POST['categorie'] : Null;
$lieu=(!empty($_POST['lieu']))  ? $_POST['lieu'] : Null;
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
<link rel="stylesheet" href="../include/datepicker/datepicker.css" type="text/css" />
<meta name="robots" content="noindex, nofollow" />
<meta http-equiv="pragma" content="no_cache" />
<script type="text/javascript" src="../include/lang-js.php"></script>
<script type="text/javascript" src="../include/jquery.js"></script>
<script type="text/javascript" src="../include/datepicker/datepicker.js"></script>
<script type="text/javascript" src="../include/date.js"></script>
<script type="text/javascript" src="../include/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript" src="../include/check_event.js"></script>
<script type="text/javascript">
<!--
<?php
if ($editeur_html)
{
?>
tinyMCE.init({
	mode: "exact",
	elements : "description",
	theme : "simple",
	language : language
});
<?php
}
?>	
$(function()
{
	$('.date-pick').datePicker();
});
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
	$auth=array('ajouter','actif');
	$auth=isAuthorized($auth);
	$auth_ajout=$auth['ajouter'];
	$auth_actif=$auth['actif'];
	if (!$auth_ajout)
	{
		echo "<p>".$lang['admin_unauthorized1']."<br />".
		addLink($lang['admin_unauthorized2'],"index.php")."</p>\n";
	}
	else
	{
		$authorised=1;
		$actif=(!empty($_POST['actif'])) ? $_POST['actif'] : $auth_actif;
		if (!$auth_actif)
		{
			$hide_status_selector=true;
		}
	}
}
//SI l'UTILISATEUR EST AUTORISE A ACCEDER A LA PAGE
if ($authorised == 1)
{
	include ("menu.php");
	echo "<h2>".$lang['admin_title_ajouter']."</h2>\n";
	//SI LE FORMULAIRE VIENT D'ETRE ENVOYE
	if ($envoye)
	{
		if (!$nom || !$description || !$categorie || !$date_debut || $date_debut == "jj/mm/aaaa")
		{
			echo "<p class=\"erreur\">".$lang['admin_erreur_champs']."</p>\n";
			$erreur="1";
		}
		elseif (!testDate($date_debut))
		{
			echo "<p class=\"erreur\">".$lang['admin_erreur_date_debut']."</p>\n";
			$erreur="1";
		}
		elseif ($date_fin && $date_fin != "jj/mm/aaaa" && !testDate($date_fin))
		{
			echo "<p class=\"erreur\">".$lang['admin_erreur_date_fin']."</p>\n";
			$erreur="1";
		}
		elseif ($heure_debut && $heure_debut != "hh:mm" && !testTime($heure_debut))
		{
			echo "<p class=\"erreur\">".$lang['admin_erreur_heure_debut']."</p>\n";
			$erreur="1";
		}
		elseif ($heure_fin && $heure_fin != "hh:mm" && !testTime($heure_fin))
		{
			echo "<p class=\"erreur\">".$lang['admin_erreur_heure_fin']."</p>\n";
			$erreur="1";
		}
		elseif ($email && !checkEmail($email))
		{
			echo "<p class=\"erreur\">".$lang['admin_erreur_email_incorrect']."</p>\n";
			$erreur="1";
		}
		else
		{
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
				$url=NULL;
			}
			if (!$lien && $url)
			{
				$lien=$url;
			}
			//SUPPRESION DU CODE HTML
			if ($nom)
			{
				$nom=strip_tags($nom);
			}
			if ($lieu)
			{
				$lieu=strip_tags($lieu);
			}
			if ($contact)
			{
				$contact=strip_tags($contact);
			}
			if ($adresse)
			{
				$adresse=strip_tags($adresse);
			}
			if ($description)
			{
				$description=cleanHtml($description);
			}
			//AJOUT DES SAUTS DE LIGNE
			if (!$editeur_html)
			{
				$description=nl2br($description);
			}
			//INSERTION DE L'EVENEMENT
			$stmt=$connexion->prepare("
				INSERT INTO
					$table_agenda
				(
					date_debut,
					date_fin,
					heure_debut,
					heure_fin,
					nom,
					description,
					categorie,
					lieu,
					contact,
					adresse,
					email,
					telephone,
					fax,
					lien,
					url,
					id_user,
					actif
				)
					VALUES
				(
					?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?
				)");
			$stmt->bind_param('ssssssissssssssii',$date_debut,$date_fin,$heure_debut,$heure_fin,$nom,$description,$categorie,$lieu,$contact,$adresse,$email,$telephone,$fax,$lien,$url,$_SESSION['user_id'],$auth_actif);
			if ($stmt->execute())
			{
				$stmt->close();
				//ENVOI D'UN EMAIL A L'ADMINISTRATEUR
				if ($auth_actif == 0 && $propositions_utilisateurs)
				{
					//ON VERIFIE SI AU MOINS UNE ADRESSE EMAIL EST DANS LA BASE DE DONNEES
					if (emailAdmin())
					{
						//ON ENVOIE LE MAIL
						$nom=stripslashes($nom);
						$texte =$lang['admin_mail_evenement_attente1'].$_SESSION['username']."\n\n";
						$texte .=$lang['admin_mail_evenement_attente2'].$nom."\n\n";
						$texte .=$lang['admin_mail_evenement_attente3']."\n\n";
						$texte .="http://$server/$path_agenda/admin";
						$tab=explode(".",$server);
						if (isset($tab[1]) && isset($tab[2]))
						{
							$server=array("$tab[1]","$tab[2]");
							$server=implode(".",$server);
							$test_serveur=1;
						}
						$result=$connexion->query("SELECT email FROM $table_users WHERE email LIKE '%@%' AND gerer = '1'");
						while($ligne = $result->fetch_array())
						{
							$adresse=$ligne['email'];
							if ($email_exp)
							{
								$headers = "From: $email_exp\n";
								$headers .= "MIME-Version: 1.0\n";
								$headers .= "Content-type: text/plain; charset=iso-8859-1\n";
							}
							elseif ($test_serveur && ($server != "free.fr"))
							{
								$headers = "From: webmaster@{$server}\n";
								$headers .= "MIME-Version: 1.0\n";
								$headers .= "Content-type: text/plain; charset=iso-8859-1\n";
							}
							else
							{
								$headers = "MIME-Version: 1.0\n";
								$headers .= "Content-type: text/plain; charset=iso-8859-1\n";
							}
							@mail($adresse, $lang['admin_mail_evenement_objet'], $texte, $headers);
						}
					}
				}
				$nom = stripslashes($nom);
				echo "<p class=\"confirmation\">".sprintf($lang['admin_confirmation_evenement_ajoute'],$nom)."</p>\n";
				$date_debut="jj/mm/aaaa";
				$date_fin="jj/mm/aaaa";
				$heure_debut="hh:mm";
				$heure_fin="hh:mm";
				$url="http://";
			}
			else
			{
				echo "<p class=\"erreur\">".sprintf($lang['admin_erreur_evenement_ajoute'],$nom)."</p>\n";
			}
		}
	}
	if (!$auth_actif)
	{
		echo "<p><b>".$lang['admin_evenement_soumis_validation']."</b></p>\n";
	}
	echo "<p>".$lang['admin_info_champs_obligatoires']."</p>\n";
	echo "<form name=\"chooseDateForm\" id=\"chooseDateForm\" action=\"ajouter.php\" method=\"post\" onsubmit=\"return verifierFormulaire(this)\">";
	//FORMULAIRE DE GESTION D'EVENEMENT
	include("../include/form-event.php");
	echo "
	<p> 
	<input type=\"submit\" name=\"Submit2\" value=\"".$lang['admin_label_ajouter']."\" />
	<input name=\"envoye\" type=\"hidden\" id=\"envoye\" value=\"1\" />
	</p>
	</form>
	<p>
		&gt; <a href=\"index.php\">".$lang['admin_link_menu']."</a><br />
		&gt; <a href=\"close.php\">".$lang['admin_link_deconnexion']."</a>
	</p>\n";
}
include("footer.php");
?>