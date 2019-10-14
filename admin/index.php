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

//VERIFICATION DE L'EXISTENCE DES TABLES
if (!checkInstall())
{
	die ("<p>".$lang['common_uninstalled1']."<br />".addLink($lang['common_uninstalled2'],"../install/index.php")."</p>");
}

//INITIALISATION DES VARIABLES
$new=0;
$form=0;
$menu=0;
$connected=0;
$test=0;
$login_ok=0;
$prenom=Null;
$nom=Null;

//VARIABLES GENERALES
$date=date("Y-m-d");
$time=date("H:i:s");
$ip=$_SERVER["REMOTE_ADDR"];
$domain=gethostbyaddr($ip);

//NUMERO DE LA VERSION
$this_version="4.5";
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
<script type="text/javascript">
<!--
function windowOpen(window_url)
{
	helpWin = window.open(window_url,'','toolbar=no,status=no,scrollbars=yes,menubar=no,resizable=yes,directories=no,location=no,width=450,height=500');
	if (document.images)
	{ 
		if (helpWin) helpWin.focus()
	}
}
-->
</script>
<?php
include("header.php");
//ON TESTE SI L'UTILISATEUR EST CONNECTE
if (isSessionValid())
{
	$connected=1;
	$user_username=(isset($_SESSION['username'])) ? $_SESSION['username'] : Null;
	$user_id=(isset($_SESSION['user_id'])) ? $_SESSION['user_id'] : Null;
	$user_firstname=(isset($_SESSION['firstname'])) ? $_SESSION['firstname'] : Null;
	$user_name=(isset($_SESSION['name'])) ? $_SESSION['name'] : Null;
	//RECUPERATION DES INFORMATIONS SUR L'UTILISATEUR
	$auth=array('ajouter','modifier','supprimer','valider','gerer');
	$auth=isAuthorized($auth);
	$auth_ajouter=$auth['ajouter'];
	$auth_modifier=$auth['modifier'];
	$auth_supprimer=$auth['supprimer'];
	$auth_valider=$auth['valider'];
	$auth_gerer=$auth['gerer'];
}

else
{
	//CAS OU l'UTILISATEUR N'EST PAS CONNECTE
	$user=(!empty($_POST['user'])) ? $_POST['user'] : Null;
	$pass_user=(!empty($_POST['pass'])) ? $_POST['pass'] : Null;
	if (!$user)
	{
		//SI LE FORMULAIRE N'A PAS ETE ENVOYE
		$form=1;
	}
	else
	{
		//SI LE FORMULAIRE A ETE ENVOYE
		//ON TESTE SI L'UTILISATEUR N'A PAS ETE BLOQUE
		$stmt = $connexion->prepare("SELECT id FROM $table_logs WHERE ip=? AND date=? AND result='erreur'");
		$stmt->bind_param('ss',$ip,$date);
		$stmt->execute();
		$result=$stmt->get_result();
		$bloque1=$result->num_rows;
		$stmt->close();
		if ($bloque1 > 4)
		{
			//SI L'UTILISATEUR EST BLOQUE
			echo "<p class=\"erreur\">".$lang['admin_erreur_ip_bloque1']."<br />".$lang['admin_erreur_ip_bloque2']."</p>\n";
		}
		$stmt = $connexion->prepare("SELECT id FROM $table_logs WHERE user=? AND date=? AND result='erreur'");
		$stmt->bind_param('ss',$user,$date);
		$stmt->execute();
		$result=$stmt->get_result();
		$bloque2=$result->num_rows;
		$stmt->close();
		if ($bloque2 > 4)
		{
			//SI L'UTILISATEUR EST BLOQUE
			echo "<p class=\"erreur\">".$lang['admin_erreur_ip_bloque1']."<br />".$lang['admin_erreur_ip_bloque2']."</p>\n";
		}
		if ($bloque1 <= 4 && $bloque2 <=  4)
		{
			//SI L'UTILISATEUR N'EST PAS BLOQUE
			$pass_user=cryptPass($pass_user);
			$stmt=$connexion->prepare("SELECT id,nom,prenom,ajouter,modifier,supprimer,valider,gerer FROM $table_users WHERE user=? AND password=?");
			$stmt->bind_param('ss',$user,$pass_user);
			$stmt->execute();
			$result=$stmt->get_result();
			if ($result && $result->num_rows)
			{
				$ligne=$result->fetch_array();
				$user_username=$user;
				$user_password=$pass_user;
				$user_id=$ligne["id"];
				$user_name=$ligne["nom"];
				$user_firstname=$ligne["prenom"];
				$auth_ajouter=$ligne['ajouter'];
				$auth_modifier=$ligne['modifier'];
				$auth_supprimer=$ligne['supprimer'];
				$auth_valider=$ligne['valider'];
				$auth_gerer=$ligne['gerer'];
				$login_ok=1;
			}
			$stmt->close();
			
			$stmt=$connexion->prepare("INSERT INTO $table_logs (user,pass,date,time,ip,domain,result) VALUES (?,?,?,?,?,?,?)");
			if ($login_ok)
			{
				$status="ok";
				$stmt->bind_param('sssssss',$user,$pass_user,$date,$time,$ip,$domain,$status);
				$stmt->execute();
				$_SESSION['username'] = $user_username;
				$_SESSION['password'] = $user_password;
				$_SESSION['name'] = $user_name;
				$_SESSION['firstname'] = $user_firstname;
				$_SESSION['user_id'] = $user_id;
				$_SESSION['timeout'] = time()+$session_timeout;
				$menu=1;
			}
			else
			{
				$status="erreur";
				$stmt->bind_param('sssssss',$user,$pass_user,$date,$time,$ip,$domain,$status);
				$stmt->execute();
				echo "<p class=\"erreur\">".$lang['admin_erreur_login_invalide']."</p>\n";
				$form=1;
			}
			
			$stmt->close();
		}
	}
}

if ($connected == 1)
{
	//CAS OU l'UTILISATEUR EST CONNECTE
	$menu = 1;
}

//VERIFICATION DE LA VERSION
if (!$menu && (getVersion() != $this_version))
{
	echo "<p class=\"warning\">".addLink(sprintf($lang['admin_alerte_version'],$this_version),"../update")."</p>\n";
}

if ($menu == 1)
{
	echo "<h1>XLAgenda ".getVersion()." &gt; admin</h1>";
	//VERIFICATION DE LA VERSION ET DE LA CONFIGURATION
	if (getVersion() != $this_version)
	{
		echo "<p class=\"warning\">".addLink(sprintf($lang['admin_alerte_version'],$this_version),"../update")."</p>\n";
	}
	if (!is_writable('../img'))
	{
		echo "<p class=\"warning\">".sprintf($lang['admin_alerte_img_readonly1'],"$path_agenda/img")."<br />".
		$lang['admin_alerte_img_readonly2']."</p>\n";
	}
	if (@opendir('../install'))
	{
		echo "<p class=\"warning\">".$lang['admin_alerte_install']."</p>\n";
	}
	if (@opendir('../update') AND getVersion() == $this_version)
	{
		echo "<p class=\"warning\">".$lang['admin_alerte_update']."</p>\n";
	}
	if ($user_firstname && $user_name)
	{
		echo "<p>".$lang['admin_bienvenue'].", $user_firstname $user_name !<br />\n";
	}
	else
	{
		echo "<p>".$lang['admin_bienvenue'].", $user_username !<br />\n";
	}
	//NOMBRE D'UTILISATEURS EN ATTENTE DE VALIDATION
	if ($auth_gerer && $pending_users = getPendingUsers())
	{
		echo "<br />".sprintf($lang['admin_utilisateurs_en_attente'],$pending_users)." - <a href=\"demande1.php\">".$lang['admin_link_gerer']."</a><br />\n";
	}
	//NOMBRE D'EVENEMENTS EN ATTENTE DE VALIDATION
	if ($auth_valider && $pending_events = getPendingEvents())
	{
		echo "<br />".sprintf($lang['admin_evenements_en_attente'],$pending_events)." - <a href=\"valider1.php\">".$lang['admin_link_gerer']."</a><br />\n";
	}
	echo "</p>\n";
	echo "<p>".$lang['admin_acces_services']."</p>\n";
	echo "<p\n>";
	echo "&gt; <a href=\"profil.php\">".$lang['admin_link_modifier_profil']."</a><br />\n";
	if ($auth_ajouter)
	{
		echo "&gt; <a href=\"ajouter.php\">".$lang['admin_link_ajouter_evenement']."</a><br />\n";
	}
	if ($auth_modifier)
	{
		echo "&gt; <a href=\"modifier.php\">".$lang['admin_link_modifier_evenement']."</a><br />\n";
	}
	if ($auth_supprimer)
	{
		echo "&gt; <a href=\"supprimer.php\">".$lang['admin_link_supprimer_evenement']."</a><br />\n";
	}
	if ($auth_valider)
	{
		echo "&gt; <a href=\"valider1.php\">".$lang['admin_link_valider_evenement']."</a><br />\n";
	}
	if ($auth_gerer)
	{
		echo "&gt; <a href=\"gerer1.php\">".$lang['admin_link_gerer_utilisateurs']."</a><br />\n";
		echo "&gt; <a href=\"categories1.php\">".$lang['admin_link_gerer_categories']."</a><br />\n";
		echo "&gt; <a href=\"infos.php\">".$lang['admin_link_infos_application']."</a><br />\n";
		echo "&gt; <a href=\"logs1.php\">".$lang['admin_link_consulter_logs']."</a><br />\n";
	}
	echo "&gt; <a href=\"close.php\">".$lang['admin_link_deconnexion']."</a>\n";
	echo "</p>\n";
}
if ($form == 1)
{
//AFFICHAGE DU FORMULAIRE
?>
<p>&nbsp;</p>
<p style="text-align:center"><?php echo $lang['admin_bienvenue_identifier'] ?></p>
<form name="form1" method="post" action="index.php">
	<p style="text-align:center">
		<label for="user"><?php echo $lang['admin_label_username'] ?></label>
		<input name="user" id="user" type="text" value="<?php echo stripslashes(stripslashes(htmlspecialchars($user))) ?>" maxlength="20" />
	</p>
	<p style="text-align:center">
		<label for="pass"><?php echo $lang['admin_label_password'] ?></label>
		<input name="pass" id="pass" type="password" maxlength="15" />
	</p>
	<p style="text-align:center">
		<?php echo $lang['admin_label_conservation_logs'] ?>
	</p>
	<p style="text-align:center"> 
		<input type="submit" name="Submit" value="Envoyer" />
	</p>
	<p style="text-align:center">
		<?php
		echo "<a href=\"javascript:windowOpen('pass.php')\">".$lang['admin_link_mdp_oublie']."</a> | <a href=\"../$url_page\">".$lang['admin_link_retour_agenda']."</a>\n";
		?>
	</p>
</form>
<?php
}
include("footer.php");
?>