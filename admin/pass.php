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
//RECUPERATION DES DONNEES
$server=(!empty($_SERVER["SERVER_NAME"])) ? $_SERVER["SERVER_NAME"] : Null;
$email=(!empty($_POST['email'])) ? $_POST['email'] : Null;
//INITIALISATION DES VARIABLES
$form=Null;
$body=Null;
$test_serveur=0;
$test=Null;
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
</head>

<body>
<?php
echo "<h1>".$lang['admin_title_password_oublie']."</h1>\n";
if ($email)
{
	if ($email && !checkEmail($email))
	{
		echo "<p class=\"erreur\">".$lang['admin_erreur_email_incorrect']."</p>\n";
		echo "<p>&nbsp;</p>\n";
		$form=1;
	}	  
	elseif (!emailExists($email))
	{
		echo "<p class=\"erreur\">".$lang['admin_erreur_email_non_trouve']."</p>\n";
		echo "<p>&nbsp;</p>\n";
		$form=1;
	}
	else
	{
		$stmt=$connexion->prepare("SELECT user,nom,prenom FROM $table_users WHERE email=?");
		$stmt->bind_param('s',$email);
		$stmt->execute();
		if ($result=$stmt->get_result())
		{
			$ligne=$result->fetch_array();
			$user=$ligne["user"];
			$prenom=$ligne["prenom"];
			$nom=$ligne["nom"];
			$stmt->close();
			$sujet=$lang['admin_mail_new_password_objet'];
			//GENERATION DU NOUVEAU MOT DE PASSE
			$tableau = array("a", "b", "c", "d", "e", "f", "g", "h", "2", "3", "4", "5", "6", "7", "8", "9");
			$valeurs_aleatoires = array_rand($tableau, 6);
			$pass= "";
			foreach($valeurs_aleatoires as $i)
			{
				$pass = $pass . $tableau[$i];
			}
			//CRYPTAGE DU NOUVEAU MOT DE PASSE
			$pass2=cryptPass($pass);
			//INSERTION DU NOUVEAU MOT DE PASSE DANS LA BASE
			$stmt=$connexion->prepare("UPDATE $table_users SET password = ? WHERE email = ?");
			$stmt->bind_param('ss',$pass2,$email);
			$stmt->execute();
			$stmt->close();
			$user=stripslashes($user);
			$pass=stripslashes($pass);
			//ENVOI DU MOT DE PASSE
			$url="http://$server/$path_agenda/admin";
			if ($nom && $prenom)
			{
				$body .=$lang['admin_mail_new_password1']."$prenom $nom.\n";
			}
			else
			{
				$body .=$lang['admin_mail_new_password1']."\n";
			}
			$body .=sprintf($lang['admin_mail_new_password2'],$server)."\n";
			$body .=sprintf($lang['admin_mail_new_password3'],$user)."\n";
			$body .=sprintf($lang['admin_mail_new_password4'],$pass)."\n";
			$body .=sprintf($lang['admin_mail_new_password5'],$url)."\n";
			$body .=$lang['admin_mail_new_password6']."\n";
			$tab=explode(".",$server);
			if (isset($tab[1]) && isset($tab[2]))
			{
				$server=array("$tab[1]","$tab[2]");
				$server=implode(".",$server);
				$test_serveur=1;
			}
			if ($email_exp)
			{
				$headers = "From: $email_exp\n";
				$headers .= "MIME-Version: 1.0\n";
				$headers .= "Content-type: text/plain; charset=utf-8\n";
			}
			elseif ($test_serveur && ($server != "free.fr"))
			{
				$headers = "From: webmaster@{$server}\n";
				$headers .= "MIME-Version: 1.0\n";
				$headers .= "Content-type: text/plain; charset=utf-8\n";
			}
			else
			{
				$headers = "MIME-Version: 1.0\n";
				$headers .= "Content-type: text/plain; charset=utf-8\n";
			}
			@mail($email, $sujet, $body, $headers);
			echo "<h2>".$lang['admin_title_new_password_sent']."</h2>
			<p class=\"confirmation\">".sprintf($lang['admin_confirmation_new_email_sent1'],$email)."<br />".
			$lang['admin_confirmation_new_email_sent2']."</p>
			<p>&gt; <a href=\"javascript:window.close()\">".$lang['admin_link_fermer_fenetre']."</a></p>\n";
		}
		else
		{
			$stmt->close();
		}
	}
}
else
{
	$form=1;
	echo "<h2>".$lang['admin_title_comment_recuperer_password']."</h2>
	<p style=\"text-align:justify\">".$lang['admin_comment_recuperer_password']."</p>
	<h2>".$lang['admin_title_comment_changer_password']."</h2>
	<p style=\"text-align:justify\">".$lang['admin_comment_changer_password']."</p>
	<p>&nbsp;</p>\n";
}
if ($form)
{
	echo "<div style=\"margin-left:20px;width:380px;border:2px solid #009;text-align:center;padding:5px;\">
	<form action=\"pass.php\" method=\"post\" name=\"form1\">
	<p>".$lang['admin_introduire_email_pour_password']."</p>
	<p><input name=\"email\" type=\"text\" id=\"email\" value=\"".$email."\" maxlength=\"30\" /></p>
	<p><input type=\"submit\" name=\"Submit\" value=\"".$lang['admin_label_envoyer']."\" /></p>\n";
}
echo "</form>
</div>\n";
?>
</body>
</html>
