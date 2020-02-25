<?php
/*********************************************************************
*	XLAgenda 4 par Xavier LE QUERE
*   Web : http://xavier.lequere.net/xlagenda
*   (C) Xavier LE QUERE, 2003-2020
*   Version 4.5.1 - 25/02/20
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
//RECUPERATION DES DONNEES
$date=(!empty($_POST['date'])) ? $_POST['date'] : "jj/mm/aaaa";
$submit_form1=(!empty($_POST['submit_form1'])) ? $_POST['submit_form1'] : Null;
$submit_form2=(!empty($_POST['submit_form2'])) ? $_POST['submit_form2'] : Null;
$submit_form3=(!empty($_POST['submit_form3'])) ? $_POST['submit_form3'] : Null;
$id=(!empty($_POST['id'])) ? $_POST['id'] : Null;
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
$url=(!empty($_POST['url'])) ? $_POST['url'] : Null;
$date_debut=(!empty($_POST['date_debut'])) ? $_POST['date_debut'] : "jj/mm/aaaa";
$date_fin=(!empty($_POST['date_fin'])) ? $_POST['date_fin'] : "jj/mm/aaaa";
$heure_debut=(!empty($_POST['heure_debut'])) ? $_POST['heure_debut'] : "hh:mm";
$heure_fin=(!empty($_POST['heure_fin'])) ? $_POST['heure_fin'] : "hh:mm";
$actif=(!empty($_POST['actif'])) ? $_POST['actif'] : 0;
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
<?php
if ($submit_form2 || $submit_form3)
{
?>
<script type="text/javascript" src="../include/check_event.js"></script>
<?php
}
?>
<script type="text/javascript">
<!--
<?php
if (($submit_form2 || $submit_form3) && $editeur_html)
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
	$('.date-pick').datePicker({startDate:'01/01/2009'});
});

function checkDate()
{
	var message="";
	var erreur="";
	var date = document.getElementById('date').value;
	var regDate=new RegExp ("^[0-9]{2}[/]{1}[0-9]{2}[/]{1}[0-9]{4}$");
	var regSeparateur=new RegExp("[/]+", "g");
	if (date == "" || date == "jj/mm/aaaa")
	{
		var message=message+erreur_date_debut_absente+"\n";
		var erreur = 1;
	}
	if (date != "" && date != "jj/mm/aaaa" && (!regDate.test(date)))
	{
		var message=message+erreur_date_debut+"\n";
		var erreur = 1;
	}
	if (erreur)
	{
		alert(message);
		return false;
	}
}

function showDescription(id)
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
	$auth=array('modifier','valider','actif');
	$auth=isAuthorized($auth);
	$auth_modif=$auth['modifier'];
	$auth_valider=$auth['valider'];
	$auth_actif=$auth['actif'];
	if (!$auth_modif)
	{
		echo "<p>".$lang['admin_unauthorized1']."<br />".
		addLink($lang['admin_unauthorized2'],"index.php")."</p>\n";
	}
	else
	{
		$authorised=1;
		if (!$auth_actif && !$auth_valider || (!$auth_valider && getEventCreator($id) != $_SESSION['user_id']))
		{
			$hide_status_selector=true;
		}
	}
}

//SI l'UTILISATEUR EST AUTORISE A ACCEDER A LA PAGE
if ($authorised == 1)
{
	include ("menu.php");
	echo "<h2>".$lang['admin_title_modifier']."</h2>\n";
	
	//TRAITEMENT DU FORMULAIRE DE MODIFICATION
	if ($submit_form3)
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
				$url=Null;
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
			//ON VERIFIE QUE L'UTILISATEUR EST BIEN AUTORISE A SUPPRIMER CET EVENEMENT
			if (($auth_modif == 1 && (getEventCreator($id) != $_SESSION['user_id'])) || !$auth_modif)
			{
				echo "<p class=\"erreur\">".$lang['admin_erreur_modif_unauthorized']."</p>\n";
				$submit_form3=0;
			}
			else
			{
				//MODIFICATION DE L'EVENEMENT
				if (empty($hide_status_selector))
				{
					$stmt=$connexion->prepare("
					UPDATE
						$table_agenda
					SET
						date_debut = ?,
						date_fin = ?,
						heure_debut = ?,
						heure_fin = ?,
						nom = ?,
						description = ?,
						categorie = ?,
						lieu = ?,
						contact = ?,
						adresse = ?,
						email = ?,
						telephone = ?,
						fax = ?,
						lien = ?,
						url = ?,
						actif = ?
					WHERE
						id = ?");
					$stmt->bind_param('ssssssissssssssii',$date_debut,$date_fin,$heure_debut,$heure_fin,$nom,$description,$categorie,$lieu,$contact,$adresse,$email,$telephone,$fax,$lien,$url,$actif,$id);
				}
				else
				{
					$stmt = $connexion->prepare("
					UPDATE
						$table_agenda
					SET
						date_debut=?,
						date_fin=?,
						heure_debut=?,
						heure_fin=?,
						nom=?,
						description=?,
						categorie=?,
						lieu=?,
						contact=?,
						adresse=?,
						email=?,
						telephone=?,
						fax=?,
						lien=?,
						url=?
					WHERE
						id=?");
						$stmt->bind_param('ssssssissssssssi',$date_debut,$date_fin,$heure_debut,$heure_fin,$nom,$description,$categorie,$lieu,$contact,$adresse,$email,$telephone,$fax,$lien,$url,$id);
				}
				if ($stmt->execute())
				{
					$nom=stripslashes($nom);
					echo "<p class=\"confirmation\">".sprintf($lang['admin_confirmation_evenement_modifie'],$nom)."</p>\n";
					$submit_form3=0;
				}
				else
				{
					echo "<p class=\"erreur\">".sprintf($lang['admin_erreur_evenement_modifie'],$nom)."</p>\n";
				}
				$stmt->close();
			}
		}
	}
	
	
	//AFFICHAGE DU FORMULAIRE DE CHOIX DE DATE
	if (!$submit_form2 && !$submit_form3)
	{
		if ($auth_modif == 1)
		{
			echo "<p><b>".$lang['admin_info_auth_modif']."</b></p>\n";
		}
		echo "
		<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"height:350px;\">
		<tr valign=\"top\">
		<td width=\"50%\">
		<h3>".$lang['admin_title_modifier_step1']."</h3>
		<form name=\"chooseDateForm\" id=\"chooseDateForm\" method=\"post\" action=\"modifier.php\" onsubmit=\"return checkDate(this)\">
		<p>
		<label for=\"date\">".$lang['admin_label_date_debut']."</label><br />
		<input type=\"text\" name=\"date\" id=\"date\" class=\"date-pick\" value=\"$date\" /></p>
		<hr style=\"visibility:hidden;clear:both;\" />
		<p>
		<input type=\"submit\" name=\"submit_form1\" value=\"".$lang['admin_label_rechercher']."\" />
		</p>
		</form>
		</td>
		<td width=\"50\">
		<h3>".$lang['admin_title_modifier_step2']."</h3>\n";
		//TRAITEMENT DU FORMULAIRE DE CHOIX DE DATE
		if ($submit_form1)
		{
			if (!$date || $date == "jj/mm/aaaa")
			{
				echo "<p class=\"erreur\">".$lang['admin_erreur_date_debut_absente']."</p>\n";
			}
			elseif (!testDate($date))
			{
				echo "<p class=\"erreur\">".$lang['admin_erreur_date_debut']."</p>\n";
			}
			else
			{
				//MISE EN FORME DES DATES
				$date2=mysqlDate($date);
				//RECHERCHE DES EVENEMENTS CONCERNES
				if ($auth_modif == 1 AND $auth_actif) //UTILISATEUR NON SOUMIS A VALIDATION : PEUT MODIFIER TOUS SES EVENEMENTS
				{
					$stmt=$connexion->prepare("SELECT * FROM $table_agenda WHERE id_user=? AND date_debut=? ORDER BY date_debut ASC, heure_debut ASC");
					$stmt->bind_param('is',$_SESSION['user_id'],$date2);
				}
				elseif ($auth_modif == 1) //UTILISATEUR SOUMIS A VALIDATION : NE PEUT MODIFIER QUE SES EVENEMENTS NON VALIDES
				{
					$stmt=$connexion->prepare("SELECT * FROM $table_agenda WHERE id_user=? AND date_debut=? AND actif = 0 ORDER BY date_debut ASC, heure_debut ASC");
					$stmt->bind_param('is',$_SESSION['user_id'],$date2);
				}
				elseif ($auth_modif == 2)
				{
					$stmt=$connexion->prepare("SELECT * FROM $table_agenda WHERE date_debut=? ORDER BY date_debut ASC, heure_debut ASC");
					$stmt->bind_param('s',$date2);
				}
				$stmt->execute();
				if ($result=$stmt->get_result())
				{
					if (!$result->num_rows)
					{
						echo "<p class=\"erreur\">".$lang['admin_erreur_aucun_evenement']."</p>\n";
					}
					else
					{
						while ($ligne=$result->fetch_array())
						{
							//AFFICHAGE DE LA LISTE DES EVENEMENTS
							$id=$ligne["id"];
							$date_fin=$ligne["date_fin"];
							$heure_debut=$ligne["heure_debut"];
							$heure_fin=$ligne["heure_fin"];
							$categorie=$ligne["categorie"];
							$nom=$ligne["nom"];
							$description=$ligne["description"];
							$actif=$ligne["actif"];
							$nom_categorie=getCategory($categorie);
							$heure_debut=formatTime($heure_debut);
							$heure_fin=formatTime($heure_fin);
							echo "<div id=\"evenement_$id\" class=\"event\">
							<p>";
							if ($date_fin == $date2)
							{
								echo sprintf($lang['common_le'],$date);
							}
							else
							{
								$date_fin2=formatDate($date_fin);
								echo sprintf($lang['common_du_au'],$date,$date_fin2);
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
							echo "<strong>$nom_categorie - <a href=\"javascript:showDescription($id)\">$nom</a></strong></p>\n";
							if ($actif)
							{
								echo "<p class=\"actif\">".$lang['admin_evenement_actif']."</p>\n";
							}
							else
							{
								echo "<p class=\"inactif\">".$lang['admin_evenement_inactif']."</p>\n";
							}
							if ($editeur_html)
							{
								echo "<div id=\"description_evenement_$id\" style=\"display:none;\">$description</div>\n";
							}
							else
							{
								$description=nl2br($description);
								echo "<p id=\"description_evenement_$id\" style=\"display:none;\">$description</p>\n";
							}
							echo "<form method=\"post\" action=\"modifier.php\"><p><input type=\"hidden\" name=\"id\" value=\"$id\"><input type=\"submit\" name=\"submit_form2\" value=\"".$lang['admin_label_modifier']."\"></p></form>\n";
							echo "</div>\n";
						}
					}
				}
			}
		}
		echo "
		</td>
		</tr>
		</table>\n";
	}
	//TRAITEMENT DU FORMULAIRE DE CHOIX D'ÉVENEMENT
	if ($submit_form2 && is_numeric($id))
	{
		//RECUPERATION DES INFORMATIONS SUR L'EVENEMENT
		$result=$connexion->query("SELECT * FROM $table_agenda WHERE id = $id");
		if ($result)
		{
			if (!$result->num_rows)
			{
				echo "<p class=\"erreur\">".$lang['admin_erreur_evenement_inexistant']."</p>\n";
				$submit_form2=0;
			}
			else
			{
				$ligne=$result->fetch_array();
				$id=$ligne["id"];
				$date_debut=$ligne["date_debut"];
				$date_fin=$ligne["date_fin"];
				$heure_debut=$ligne["heure_debut"];
				$heure_fin=$ligne["heure_fin"];
				$nom=$ligne["nom"];
				$description=$ligne["description"];
				$categorie=$ligne["categorie"];
				$lieu=$ligne["lieu"];
				$contact=$ligne["contact"];
				$adresse=$ligne["adresse"];
				$email=$ligne["email"];
				$telephone=$ligne["telephone"];
				$fax=$ligne["fax"];
				$lien=$ligne["lien"];
				$url=$ligne["url"];
				$actif=$ligne["actif"];
				$date_debut=formatDate($date_debut);
				$date_fin=formatDate($date_fin);
				$heure_debut=formatTime($heure_debut,true);
				$heure_fin=formatTime($heure_fin,true);
			}
		}
	}
	//AFFICHAGE DU FORMULAIRE DE MODIFICATION
	if ($submit_form2 || ($submit_form3 && $erreur))
	{
		if (!$url)
		{
			$url="http://";
		}
		echo "
		<p>".$lang['admin_info_champs_obligatoires']."</p>
		<form name=\"chooseDateForm\" id=\"chooseDateForm\" method=\"post\" action=\"modifier.php\" onsubmit=\"return verifierFormulaire(this)\">\n";
		//FORMULAIRE DE GESTION D'EVENEMENT
		include("../include/form-event.php");
		echo "
		<p> 
		<input type=\"hidden\" name=\"id\" value=\"$id\" />
		<input type=\"submit\" name=\"submit_form3\" value=\"Modifier\" />
		</p>
		</form>\n";
	}
	echo "<p>&nbsp;</p>
	<p>
	&gt; <a href=\"index.php\">".$lang['admin_link_menu']."</a><br />
	&gt; <a href=\"close.php\">".$lang['admin_link_deconnexion']."</a>
	</p>\n";
}
include("footer.php");
?>