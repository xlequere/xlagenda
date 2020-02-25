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

//INITALISATION DES VARIABLES
$texte=Null;
$request=Null;
$auth_modif=Null;
$auth_supprim=Null;

//RECUPERATION DES DONNEES
$page=(!empty($_SERVER['PHP_SELF'])) ? $_SERVER['PHP_SELF'] : Null;
$year=(!empty($_REQUEST["year"])) ? $_REQUEST["year"] : Null;
$month=(!empty($_REQUEST["month"])) ? $_REQUEST["month"] : Null;
$day=(!empty($_REQUEST["day"])) ? $_REQUEST["day"] : Null;
$categorie=(!empty($_REQUEST["categorie"])) ? $_REQUEST["categorie"] : Null;
$navigation=(!empty($_REQUEST["navigation"])) ? $_REQUEST["navigation"] : Null;
$limit=(!empty($_REQUEST["limit"])) ? $_REQUEST["limit"] : Null;

//SI AUCUNE DATE N'A ETE SELECTIONNEE ON SELECTIONNE LA DATE DU JOUR
if (!$month)
{
	$month = date("m");
	//DANS CE CAS SEULS LES EVENEMENTS POSTERIEURS A LA DATE DU JOUR SERONT AFFICHES
	$limit = 1;
}
if (!$year)
{
	$year = date("Y");
}

//DETERMINATION DU NOM DU MOIS
$nom_mois = monthName($month);

//MEMORISATION DE LA DATE DU JOUR
$this_year = date("Y");
$this_month = date("m");
$this_day = date("d");
$this_date = date("Y-m-d");

if (isSessionValid())
{
	//SI L'UTILISATEUR EST CONNECTE
	//CONTROLE DE AUTORISATIONS
	$auth=array('modifier','supprimer','actif');
	$auth=isAuthorized($auth);
	$auth_modif=$auth['modifier'];
	$auth_supprim=$auth['supprimer'];
	$auth_actif=$auth['actif'];
}

//AFFICHAGE DE L'ENTETE
include("include/header.php");

//CODE HTML DE LA PAGE
?>
<div id="left">
	<?php
	include("cal.php");
	echo "<br />
	<div id=\"cadre_filtre\">
	<h2>".$lang['common_title_filtre']."</h2>
	<form name=\"form1\" method=\"post\" action=\"$url_page\">
	<p> 
	<select name=\"month\">";
	//CONSTRUCTION DU MENU MOIS
	for ($i = 1; $i <= 12; $i++)
	{
		echo "<option value=\"$i\"";
		if ($month == $i) echo " selected=\"selected\"";
		echo ">".ucfirst(monthName($i))."</option>\n";
	}
	echo "</select>
	<select name=\"year\">\n";
	//CONSTRUCTION DU MENU ANNEES
	for ($moins = $max_year; $moins > 0; $moins--)
	{
		$this_year2=$this_year-$moins;
		echo "<option value=\"$this_year2\"";
		if ($this_year2 == $year)
		{
			echo " selected=\"selected\"";
		}
		echo ">$this_year2</option>\n";
	}
	echo "<option value=\"$this_year\"";
	if ($this_year == $year)
	{
		echo " selected=\"selected\"";
	}
	echo ">$this_year</option>\n";
	for ($plus = 1; $plus <= $max_year; $plus++)
	{
		$this_year2=$this_year+$plus;
		echo "<option value=\"$this_year2\"";
		if ($this_year2 == $year) echo " selected=\"selected\">";
		echo ">$this_year2</option>\n";
	}
	echo "</select>
	</p>
	<p> 
	<select name=\"categorie\">
	<option value=\"0\">".$lang['common_toutes_categories']."</option>\n";
	//CONSTRUCTION DU MENU CATEGORIES
	foreach (getCategories() as $categorie_id => $categorie_name)
	{
		echo "<option value=\"$categorie_id\"";
		if ($categorie == $categorie_id) echo " selected=\"selected\"";
		echo ">$categorie_name</option>\n";
	}
	echo "</select>
	</p>
	<p> 
	<input type=\"submit\" name=\"Submit\" value=\"".$lang['common_label_afficher']."\" />
	<input name=\"navigation\" type=\"hidden\" id=\"navigation\" value=\"1\" />
	</p>
	</form>
	</div>
	<br />\n";
	//AFFICHAGE DU MENU
	include("include/menu.php");
	echo "</div>
	<div id=\"main\">\n";
	//TITRE DE LA PAGE
	echo "<h1>";
	if ($day)
	{
		echo sprintf($lang['common_title_calendrier_du'],$day,$nom_mois,$year);
	}
	else
	{
		echo sprintf($lang['common_title_calendrier_du_mois'],$nom_mois,$year);
	}
	echo "</h1>\n";
	if ($nom_mois && $categorie)
	{
		echo "<h2>".$lang['common_title_filtre_applique']."</h2>\n";
	}
	//LIEN DE SWITCH VUE DEVELOPPEE / VUE REDUITE
	if ($menu_vue)
	{
		echo "<p id=\"switch\">";
		if ($reduit)
		{
			echo "<a href=\"$url_page?cookie=2&amp;year=$year&amp;month=$month&amp;day=$day&amp;categorie=$categorie&amp;navigation=$navigation&amp;limit=$limit\">".$lang['common_link_vue_detaillee']."</a>";
		}
		else
		{
			echo $lang['common_link_vue_detaillee'];
		}
		echo " | ";
		if (!$reduit)
		{
			echo "<a href=\"$url_page?cookie=1&amp;year=$year&amp;month=$month&amp;day=$day&amp;categorie=$categorie&amp;navigation=$navigation&amp;limit=$limit\">".$lang['common_link_vue_reduite']."</a>";
		}
		else
		{
			echo $lang['common_link_vue_reduite'];
		}
		echo "</p>\n";
	}
	/***********************************************************************************
	*	AFFICHAGE DU MOIS DEMANDE (LIENS DE NAVIGATION, CALENDRIER, CHARGEMENT DE LA PAGE)
	************************************************************************************/
	if (!$navigation)
	{
	$date1=$year."-".$month."-01";
	$date2=date("Y-m-t", strtotime($date1));				
	if ($limit)
	{
		//CAS DU CHARGEMENT DE LA PAGE - ON CHERCHE SEULEMENT LES EVENEMENTS POSTERIEURS A LA DATE DU JOUR
		$date1=$this_date;
	}
	if ($day)
	{
		//CAS OU UN JOUR A ETE CHOISI
		$date_search="$year-$month-$day";
		$stmt=$connexion->prepare("SELECT * FROM $table_agenda WHERE ((date_debut <= ? AND date_fin >= ?) OR date_debut=?) AND actif=1 ORDER BY heure_debut ASC");
		$stmt->bind_param('ss',$date_search);
	}
	else
	{
		//CAS OU UN MOIS A ETE CHOISI
		$stmt=$connexion->prepare("SELECT * FROM $table_agenda WHERE date_debut <= ? AND date_fin >= ? AND actif = 1 ORDER BY date_debut ASC, heure_debut ASC");
		$stmt->bind_param('ss',$date2,$date1);
	}
	if ($stmt->execute() && $result=$stmt->get_result())
	{
		$total=$result->num_rows;
		//AFFICHAGE DES RESULTATS
		include("affiche.php");
	}
	$stmt->close();
}
/***********************************************************************
*	AFFICHAGE DES EVENEMENTS DEMANDES (FORMULAIRE DE FILTRAGE)
************************************************************************/
if ($navigation)
{
	$date1=$year."-".$month."-01";
	$date2=date("Y-m-t", strtotime($date1));
	if (is_numeric($categorie))
	{
		$request="AND categorie = $categorie";
	}
	$stmt=$connexion->prepare("SELECT * FROM $table_agenda
	WHERE
	(
		(date_debut >= ? AND date_debut <= ?)
		OR (date_debut <= ? AND date_fin >= ?)
		OR (date_debut <= ? AND date_fin >= ?)
		OR (date_debut <= ? AND date_fin >= ?)
	)
	AND actif = 1
	$request
	ORDER BY date_debut ASC, heure_debut ASC");
	$stmt->bind_param('ssssssss',$date1,$date2,$date2,$date2,$date1,$date1,$date1,$date2);
	if ($stmt->execute() && $result=$stmt->get_result())
	{
		if (!$result->num_rows)
		{
			$texte=sprintf($lang['common_aucun_evenement_mois'],$nom_mois,$year);
		}
		//AFFICHAGE DES RESULTATS
		include("affiche.php");
	}
	$stmt->close();
}
echo "
</div>
<p>&nbsp;</p>\n";
//AFFICHAGE DU PIED DE PAGE
include("include/footer.php");
?>