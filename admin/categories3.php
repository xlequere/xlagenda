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
$ok=0;
//RECUPERATION DES DONNEES
$form=(!empty($_POST["form"])) ? $_POST['form'] : Null;
$nom2=(!empty($_POST["nom2"])) ? $_POST['nom2'] : Null;
$categorie1=(!empty($_REQUEST["categorie1"])) ? $_REQUEST['categorie1'] : Null;
$categorie2=(!empty($_POST["categorie2"])) ? $_POST['categorie2'] : Null;
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
<script type="text/javascript" src="../include/jquery.js"></script>
<script type="text/javascript">
<!--
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
	echo "<h2>".$lang['admin_title_categories']."</h2>
	<p>&gt; <a href=\"categories1.php\">".$lang['admin_link_annuler']."</a></p>\n";
	if ($form)
	{
		if ($nom2 && $categorie2)
		{
			$stmt=$connexion->prepare("SELECT id FROM $table_categories WHERE nom = ? AND id != ? AND id != ?");
			$stmt->bind_param('sii',$nom2,$categorie1,$categorie2);
			$stmt->execute();
			$result=$stmt->get_result();
			$stmt->close();
			if ($result && $result->num_rows)
			{
				echo "<p class=\"erreur\">".sprintf($lang['admin_erreur_categorie_existe'],$nom2)."</p>\n";
			}
			else
			{
				$stmt=$connexion->prepare("INSERT INTO $table_categories (nom) VALUES (?)");
				$stmt->bind_param('s',$nom2);
				$stmt->execute();
				$categorie3=$stmt->insert_id;
				$stmt->close();
				
				$stmt=$connexion->prepare("UPDATE $table_agenda SET categorie=? WHERE categorie=?");
				$stmt->bind_param('ii',$categorie3,$categorie1);
				$stmt->execute();
				$stmt->bind_param('ii',$categorie3,$categorie2);
				$stmt->execute();
				$stmt->close();
				
				$stmt=$connexion->prepare("DELETE FROM $table_categories WHERE id=?");
				$stmt->bind_param('i',$categorie1);
				$stmt->execute();
				$stmt->bind_param('i',$categorie2);
				$stmt->execute();
				$stmt->close();
				
				$connexion->query("OPTIMIZE TABLE $table_categories");
				
				echo "<p class=\"confirmation\">".$lang['admin_confirmation_fusion']."</p>\n";
				echo "<p>&gt; <a href=\"categories1.php\">".$lang['admin_link_retour']."</a></p>\n";
			}

		}
		else
		{
			echo "<p class=\"erreur\">".$lang['admin_erreur_fusion_categories_vide']."</p>\n";
			echo "ok";
		}
	}
	if (!$ok)
	{
		echo "<form name=\"form1\" method=\"post\" action=\"categories3.php\" class=\"event\">
		<h3>".sprintf($lang['admin_title_fusionner_categorie'],getCategory($categorie1))."</h3>
		<p>
		<label for=\"categorie2\">".$lang['admin_label_fusionner_avec']."</label><br />
		<select name=\"categorie2\" id=\"categorie2\">
		<option value=\"0\">-- ".$lang['admin_label_selectionner']." --</option>\n";
		//CONSTRUCTION DU MENU CATEGORIES
		foreach (getCategories($categorie1) as $categorie_id => $categorie_name)
		{
			echo "<option value=\"$categorie_id\"";
			if ($categorie2 == $categorie_id)
			{
				echo " selected=\"selected\"";
			}
			echo ">$categorie_name</option>\n";
		}
		echo "</select>
		</p>
		<p>
		<label for=\"nom2\">".$lang['admin_label_renommer_en']."</label><br />
		<input name=\"nom2\" type=\"text\" id=\"nom2\" value=\"".input($nom2)."\" size=\"30\" />
		<input name=\"categorie1\" type=\"hidden\" id=\"categorie1\" value=\"".$categorie1."\" />
		<input name=\"form\" type=\"hidden\" id=\"form\" value=\"1\" />
		</p>
		<p>
		<input type=\"submit\" name=\"Submit\" value=\"".$lang['admin_label_fusionner']."\" />
		</p>
		</form>\n";
	}
	echo "<p>
	&gt; <a href=\"index.php\">".$lang['admin_link_menu']."</a><br />
	&gt; <a href=\"close.php\">".$lang['admin_link_deconnexion']."</a>
	</p>\n";
}
include("footer.php");
?>