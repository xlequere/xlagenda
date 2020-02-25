<?php
/******************************************************************
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

include("include/data.php");
include("include/connexion.php");
include("include/functions.php");
include("lang/".getLang()."/common.php");
initSession();

//VERIFICATION DE L'EXISTENCE DES TABLES
if (!checkInstall())
{
	die ("<p>".$lang['common_uninstalled1']."<br />".addLink($lang['common_uninstalled2'],"install/index.php")."</p>");
}

//INITIALISATION DES VARIABLES
$texte=Null;
$auth_modif=Null;
$auth_supprim=Null;

//RECUPERATION DES DONNEES
$page=(!empty($_SERVER['PHP_SELF'])) ? $_SERVER['PHP_SELF'] : Null;
$recherche=(!empty($_REQUEST["recherche"])) ? $_REQUEST["recherche"] : Null;
$mois=(!empty($_REQUEST["mois"])) ? $_REQUEST["mois"] : Null;

//RECHERCHE DE LA DATE DU JOUR
$this_year = date("Y");
$this_month = date("m");
$this_day = date("d");
$this_date = date("Y-m-d");

//DETERMINATION DU NOM DU MOIS
$nom_mois=monthName($this_month);

//AFFICHAGE DE L'ENTETE
include("include/header.php");

//CODE HTML DE LA PAGE
?>
<div id="left">
	<div id="cadre_recherche">
		<h2><?php echo $lang['search_title_recherche'] ?></h2>
		<form name="form1" method="post" action="<?php echo $url_recherche ?>">
			<p> 
				<input name="recherche" id="recherche" type="text" value="<?php echo stripslashes(stripslashes(htmlspecialchars($recherche))) ?>" />
			</p>
			<p> 
				<input type="radio" name="mois" id="en_cours" value="1" <?php if ($mois) echo " checked=\"checked\"" ?> />
				<?php
				echo "<label for=\"en_cours\">".sprintf($lang['search_label_evenements_de'],$nom_mois,$this_year)."</label>
				<br />
				<input type=\"radio\" name=\"mois\" id=\"a_venir\" value=\"0\"";
				if (!$mois) echo " checked=\"checked\"";
				echo "/>
				<label for=\"a_venir\">".$lang['search_label_evenements_futurs']."</label>
			</p>
			<p> 
				<input type=\"submit\" name=\"Submit2\" value=\"".$lang['search_label_rechercher']."\" />
			</p>\n";
			?>
		</form>
	</div>
	<br />
	<?php
	//AFFICHAGE DU MENU
	include("include/menu.php");
	?>
</div>
<div id="main">
	<?php
	echo "<h1>".$lang['search_title_rechercher_evenement']."</h1>\n";
	//LIEN DE SWITCH VUE DEVELOPPEE / VUE REDUITE
	if ($menu_vue)
	{
		echo "<p style=\"text-align:right;\">";
		if ($reduit)
		{
			echo "<a href=\"$url_recherche?cookie=2&amp;recherche=$recherche&amp;mois=$mois\">".$lang['common_link_vue_detaillee']."</a>";
		}
		else
		{
			echo $lang['common_link_vue_detaillee'];
		}
		echo " | ";
		if (!$reduit)
		{
			echo "<a href=\"$url_recherche?cookie=1&amp;recherche=$recherche&amp;mois=$mois\">".$lang['common_link_vue_reduite']."</a>";
		}
		else
		{
			echo $lang['common_link_vue_reduite'];
		}
		echo "</p>\n";
	}
	//SI AUCUNE RECHERCHE N'A ETE LANCEE
	if (!$recherche)
	{
		echo "<p>".$lang['search_intro1']."</p>\n";
		echo "<p>".$lang['search_intro2']."</p>\n";
	}
	//SI UNE RECHERCHE A ETE LANCEE
	if ($recherche)
	{
		$taille=strlen($recherche);
		if ($taille > 1)
		{
			$prepare_search='%'.$recherche.'%';
			//ON CHERCHE SI DES EVENEMENTS A VENIR OU EN COURS CORRRESPONDENT A LA DEMANDE
			if ($mois)
			{
				$date1=$this_year."-".$this_month."-01";
				$date2=date("Y-m-t", strtotime($date1));
				$stmt=$connexion->prepare("
				SELECT
				*
				FROM $table_agenda
				WHERE
				(
				(date_debut >= ? AND date_debut <= ? AND date_fin >= ?)
				OR (date_fin <= ? AND date_debut >= ?)
				OR (date_debut <= ? AND date_fin >= ?)
				)
				AND
				(nom like ? or description like ?)
				AND actif = 1
				ORDER BY date_debut ASC, nom ASC");
				$stmt->bind_param('sssssssss',$date1,$date2,$date2,$date2,$date1,$date1,$date2,$prepare_search,$prepare_search);
			}
			else
			{
				$stmt=$connexion->prepare("
				SELECT
				*
				FROM $table_agenda
				WHERE
				date_fin >= ?
				AND (nom like ? or description like ?)
				AND actif = 1
				ORDER BY date_debut ASC, nom ASC");
				$stmt->bind_param('sss',$this_date,$prepare_search,$prepare_search);
			}
			if ($stmt->execute() && $result=$stmt->get_result())
			{
				$num=$result->num_rows;
				if (!$num AND !$mois)
				{
					$texte=$lang['search_no_result'];
				}
				if (!$num AND $mois)
				{
					$texte=sprintf($lang['search_no_result_mois'],$nom_mois,$this_year);
				}
				//AFFICHAGE DES RESULTATS
				include("affiche.php");
			}
		}
		if ($taille < 2)
		{
			echo "<p class=\"erreur\">".$lang['search_error_taille']."</p>";
		}
	}
echo "
</div>
<p>&nbsp;</p>\n";
include("include/footer.php");
?>