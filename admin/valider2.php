<?php
/*********************************************************************
*	XLAgenda 4 par Xavier LE QUERE
*   Web : http://xavier.lequere.net/xlagenda
*   (C) Xavier LE QUERE, 2003-2020
*   Version 4.5.2 - 26/02/20
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
$hide_status_selector=true;
//RECUPERATION DES DONNEES
$id=(!empty($_GET['id'])) ? $_GET['id'] : Null;
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
$(function()
{
	$('.date-pick').datePicker({startDate:'01/01/2009'});
});
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
if ($authorised == 1 && is_numeric($id))
{
	include ("menu.php");
	$result=$connexion->query("SELECT * FROM $table_agenda WHERE id= $id");
	if ($result)
	{
		$ligne = $result->fetch_array();
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
		$date_debut=formatDate($date_debut);
		$date_fin=formatDate($date_fin);
		$heure_debut=formatTime($heure_debut);
		$heure_fin=formatTime($heure_fin);
		if (!$url)
		{
			$url="http://";
		}
	}
	echo "<form name=\"chooseDateForm\" id=\"chooseDateForm\" method=\"post\" action=\"valider1.php\" onsubmit=\"return verifierFormulaire(this)\">
	<h2>".$lang['admin_title_valider']."</h2>
	<p>&gt; <a href=\"valider1.php\">".$lang['admin_link_annuler']."</a></p>\n";
	include("../include/form-event.php");
	echo "<p> 
    <input type=\"submit\" name=\"modifier_event\" value=\"".$lang['admin_label_modifier']."\" />
	<input type=\"submit\" name=\"modifier_valider_event\" value=\"".$lang['admin_label_modifier_valider']."\" />
    <input type=\"hidden\" name=\"id\" value=\"$id\" />
    </p>
    </form>
    <p>
	&gt; <a href=\"index.php\">".$lang['admin_link_menu']."</a><br />
	&gt; <a href=\"close.php\">".$lang['admin_link_deconnexion']."</a>
	</p>\n";
}
include('footer.php');
?>