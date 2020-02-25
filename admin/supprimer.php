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
$id=(!empty($_POST['id'])) ? $_POST['id'] : Null;
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
<script type="text/javascript" src="lang-js.php"></script>
<script type="text/javascript" src="../include/jquery.js"></script>
<script type="text/javascript" src="../include/datepicker/datepicker.js"></script>
<script type="text/javascript" src="../include/date.js"></script>
<script type="text/javascript">
<!--
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

function confirmerSuppression(id)
{
	if (confirm(confirmer_suppr_evenement)) 
	{
		return true;
	}
	else
	{
		return false;
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
	$auth=array('supprimer');
	$auth=isAuthorized($auth);
	$auth_supprim=$auth['supprimer'];
	if (!$auth_supprim)
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
	echo "<h2>".$lang['admin_title_supprimer']."</h2>\n";
	//TRAITEMENT DU FORMULAIRE DE SUPPRESSION
	if ($submit_form2)
	{
		//ON VERIFIE QUE L'UTILISATEUR EST BIEN AUTORISE A SUPPRIMER CET EVENEMENT
		if (($auth_supprim == 1 && (getEventCreator($id) != $_SESSION['user_id'])) || !$auth_supprim)
		{
			echo "<p class=\"erreur\">".$lang['admin_erreur_suppr_unauthorized']."</p>\n";
		}
		else
		{
			//SUPPRESSION
			$result = $connexion->prepare("DELETE FROM $table_agenda WHERE id=?");
			$result->bind_param('i',$id);
			if ($result->execute())
			{
				echo "<p class=\"confirmation\">".$lang['admin_confirmation_evenement_supprime']."</p>\n";
				$connexion->query("OPTIMIZE TABLE $table_agenda");
			}
			else
			{
				echo "<p class=\"erreur\">".$lang['admin_erreur_evenement_supprime']."</p>\n";
			}
			$result->close();
		}
	}
	echo "<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"height:350px;\">
	<tr valign=\"top\">
	<td width=\"50%\"><h3>".$lang['admin_title_supprimer_step1']."</h3>
	<form name=\"chooseDateForm\" id=\"chooseDateForm\" method=\"post\" action=\"supprimer.php\" onsubmit=\"return checkDate(this)\">
	<p>
	<label for=\"date\">".$lang['admin_label_date_debut']."</label>
	<br />
	<input type=\"text\" name=\"date\" id=\"date\" class=\"date-pick\" value=\"$date\" />
	</p>
	<hr style=\"visibility:hidden;clear:both;\" />
	<p>
	<input type=\"submit\" name=\"submit_form1\" value=\"".$lang['admin_label_rechercher']."\" />
	</p>
	</form></td>
	<td width=\"50%\"><h3>".$lang['admin_title_supprimer_step2']."</h3>\n";
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
			if ($auth_supprim == 1)
			{
				$stmt=$connexion->prepare("SELECT * FROM $table_agenda WHERE id_user=? AND date_debut=? ORDER BY date_debut ASC, heure_debut ASC");
				$stmt->bind_param('is',$_SESSION['user_id'],$date2);
			}
			elseif ($auth_supprim == 2)
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
						$nom_categorie=getCategory($categorie);
						$heure_debut=formatTime($heure_debut);
						$heure_fin=formatTime($heure_fin);
						echo "<div id=\"evenement_$id\" class=\"event\">\n";
						echo "<p>";
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
						if ($editeur_html)
						{
							echo "<div id=\"description_evenement_$id\" style=\"display:none;\">$description</div>\n";
						}
						else
						{
							$description=nl2br($description);
							echo "<p id=\"description_evenement_$id\" style=\"display:none;\">$description</p>\n";
						}
						echo "<form method=\"post\" action=\"supprimer.php\" onSubmit=\"return confirmerSuppression($id)\"><input type=\"hidden\" name=\"id\" value=\"$id\"><input type=\"submit\" name=\"submit_form2\" value=\"".$lang['admin_label_supprimer']."\"></form>\n";
						echo "</div>\n";
					}
				}
			}
		}
	}
	echo "
	</td>
	</tr>
	</table>
	<p>&nbsp;</p>
	<p>
		&gt; <a href=\"index.php\">".$lang['admin_link_menu']."</a><br />
		&gt; <a href=\"close.php\">".$lang['admin_link_deconnexion']."</a>
	</p>\n";
}
include("footer.php");
?>