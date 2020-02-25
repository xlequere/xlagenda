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
$form=(!empty($_POST["form"])) ? $_POST['form'] : Null;
$pass_user=(!empty($_POST["pass_user"])) ? $_POST['pass_user'] : Null;
$pass=(!empty($_POST["pass"])) ? $_POST['pass'] : Null;
$pass2=(!empty($_POST["pass2"])) ? $_POST['pass2'] : Null;
$nom=(!empty($_POST["nom"])) ? $_POST['nom'] : Null;
$prenom=(!empty($_POST["prenom"])) ? $_POST['prenom'] : Null;
$email=(!empty($_POST["email"])) ? $_POST['email'] : Null;
$email_old=(!empty($_POST["email_old"])) ? $_POST['email_old'] : Null;
$envoye=(!empty($_POST["envoye"])) ? $_POST['envoye'] : Null;
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
<script type="text/javascript" src="lang-js.php"></script>
<script language = "JavaScript" type="text/javascript">
<!--
function checkEmail()
{
	document.getElementById("valid_email").value="";
	document.getElementById("divEmail").innerHTML="";
	document.getElementById("divEmail").className="";
	var email=document.getElementById("email").value;
	var email_old=document.getElementById("email_old").value;
	var id=document.getElementById("id").value;
	var regEmail=new RegExp ("^[0-9a-z._-]+@{1}[0-9a-z._-]{2,}[.]{1}[a-z]{2,5}$");
	if (email != "" && (!regEmail.test(email)))
	{
	document.getElementById("divEmail").innerHTML=email_invalide;
	document.getElementById("divEmail").className="valid_ko";
	}
	else if (email != "" && email != email_old)
	{
	$.ajax({
	type: 'POST',
	url: 'checkEmail.php',
	data: "email="+$('#email').val()+"&"+"user_id="+$('#id').val(),
	dataType: 'text',
	success: validerEmail
	});
	}
}

function checkPassword()
{
	document.getElementById("divPassword").innerHTML="";
	document.getElementById("divPassword").className="";
	var pass_perso=document.getElementById("pass_user").value;
	if (pass_perso != "")
	{
	$.ajax({
	type: 'POST',
	url: 'checkPassword.php',
	data: "password="+$('#pass_user').val(),
	dataType: 'text',
	success: validerPassword
	});
	}
}

function validerEmail(reponse)
{
	if (reponse == 1)
	{
	document.getElementById("divEmail").innerHTML=email_indisponible;
	document.getElementById("divEmail").className="valid_ko";
	document.getElementById("valid_email").value=0;
	}
	else
	{
	document.getElementById("divEmail").innerHTML=email_disponible;
	document.getElementById("divEmail").className="valid_ok";
	document.getElementById("valid_email").value=1;
	}
}

function validerPassword(reponse)
{
	if (reponse == 1)
	{
	document.getElementById("divPassword").innerHTML=password_invalide;
	document.getElementById("divPassword").className="valid_ko";
	}
	else
	{
	document.getElementById("divPassword").innerHTML=password_valide;
	document.getElementById("divPassword").className="valid_ok";
	}
}


function verifierFormulaire()
{
	var user=document.getElementById("user").value;
	var pass_user=document.getElementById("pass_user").value;
	var pass=document.getElementById("pass").value;
	var pass2=document.getElementById("pass2").value;
	var email=document.getElementById("email").value;
	var emailOld=document.getElementById("email_old").value;
	var validEmail=document.getElementById("valid_email").value;
	var regEmail=new RegExp ("^[0-9a-z._-]+@{1}[0-9a-z._-]{2,}[.]{1}[a-z]{2,5}$");
	var erreur = 0;
	var message = "";
	if (email == "")
	{
		var message=erreur_email_manquant+"\n";
		var erreur = 1;
	}
	if (email != "" && (!regEmail.test(email)))
	{
		var message=message+erreur_email_invalide+"\n";
		var erreur = 1;
	}
	if (pass != "" && pass_user == "")
	{
		var message=message+erreur_password_manquant+"\n";
		var erreur = 1;
	}
	if (pass != "" && user != "" && (pass == user))
	{
		var message=message+erreur_pass_iso_user+"\n";
		var erreur = 1;
	}
	if (pass != "" && (pass.length < 6 || pass.length > 15))
	{
		var message=message+erreur_password_taille+"\n";
		var erreur = 1;
	}
	if (pass != "" && pass2 != "" && pass != pass2)
	{
		var message=message+erreur_email_differents+"\n";
		var erreur = 1;
	}
	if (email != "" && validEmail == 0 && email != emailOld && regEmail.test(email))
	{
		var message=message+erreur_email_existe+"\n";
		var erreur = 1;
	}
	if (erreur)
	{
		alert(message);
		return false;
	}
}
// -->
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
	$authorised=1;
}

//SI l'UTILISATEUR EST AUTORISE A ACCEDER A LA PAGE
if ($authorised == 1)
{
	include ("menu.php");
	echo "<h2>".$lang['admin_title_profil']."</h2>\n";
	if ($envoye)
	{
		$nombre=strlen($pass);
		if (!$email)
		{
			echo "<p class=\"erreur\">".$lang['admin_erreur_email_manquant']."</p>\n";
			$erreur = "1";
		}
		if ($email && !checkEmail($email))
		{
			echo "<p class=\"erreur\">".$lang['admin_erreur_email_incorrect']."</p>\n";
			$erreur = "1";
		}
		if (emailExists($email,$_SESSION['user_id']))
		{
			echo "<p class=\"erreur\">".sprintf($lang['admin_erreur_email_utilise'],$email)."</p>\n";
			$erreur = "1";
		}
		if ($pass && !$pass_user)
		{
			echo "<p class=\"erreur\">".$lang['admin_erreur_pass_manquant']."</p>\n";
			$erreur = "1";
		}
		if ($pass_user && (cryptPass($pass_user) != $_SESSION['password']))
		{
			echo "<p class=\"erreur\">".$lang['admin_erreur_pass_invalide']."</p>\n";
			$erreur = "1";
		}
		if ($pass != $pass2)
		{
			echo "<p class=\"erreur\">".$lang['admin_erreur_pass_differents']."</p>\n";
			$erreur = "1";
		}
		if ($_SESSION['username'] == $pass)
		{
			echo "<p class=\"erreur\">".$lang['admin_erreur_pass_iso_user']."</p>\n";
			$erreur = "1";
		}
		if ($pass && $nombre < 6)
		{
			echo "<p class=\"erreur\">".$lang['admin_erreur_taille_pass']."</p>\n";
			$erreur=1;
		}
		if (!$erreur)
		{
			if ($pass)
			{
				$pass=cryptPass($pass);
				$result = $connexion->prepare("
					UPDATE
						$table_users
					SET
						password = ?,
						nom = ?,
						prenom = ?,
						email = ?
					WHERE
						id = ?");
					$result->bind_param('ssssi',$pass,$nom,$prenom,$email,$_SESSION['user_id']);
				$_SESSION['password'] = "$pass";
			}
			if (!$pass)
			{
				$result = $connexion->prepare("
				UPDATE
					$table_users
				SET
					nom = ?,
					prenom = ?,
					email = ?
				WHERE
					id = ?");
				$result->bind_param('sssi',$nom,$prenom,$email,$_SESSION['user_id']);
			}
			if ($result->execute())
			{
				echo "<p class=\"confirmation\">".sprintf($lang['admin_confirmation_profil_modifie'],$nom)."</p>\n";
				$result->close();
			}
		}
	}
	if (!$envoye)
	{
		$result=$connexion->query("SELECT email,nom,prenom FROM $table_users WHERE id=".$_SESSION['user_id']);
		if ($result)
		{
			$ligne=$result->fetch_array();
			$email=$ligne["email"];
			$nom=$ligne["nom"];
			$prenom=$ligne["prenom"];
		}
	}
	//MODE DEMO
	if ((!empty($demo) && $demo) && $_SESSION['username'] == "demo")
	{
		echo "<p class=\"erreur\">".$lang['admin_erreur_mode_demo']."</p>\n";
	}
	else
	{
		echo "
		<form action=\"profil.php\" method=\"post\" onsubmit=\"return verifierFormulaire(this)\">
		<h3>".$lang['admin_title_nom_email']."</h3>
		<p>
		<label for=\"nom\">".$lang['admin_label_nom']."</label><br />
		<input name=\"nom\" type=\"text\" id=\"nom\" value=\"".input($nom)."\" size=\"30\" />
		</p>
		<p>
		<label for=\"prenom\">".$lang['admin_label_prenom']."</label><br />
		<input name=\"prenom\" type=\"text\" id=\"prenom\" value=\"".input($prenom)."\" size=\"30\" />
		</p>
		<p>
		<label for=\"email\">".$lang['admin_label_email']."</label>
		*<br />
		<input name=\"email\" type=\"text\" id=\"email\" value=\"$email\" size=\"30\" onkeyup=\"checkEmail()\" /> <span id=\"divEmail\"></span>
		</p>
		<h3>".$lang['admin_title_changer_password']."</h3>
		<p>
		<label for=\"pass_user\">".$lang['admin_label_present_password']."</label><br />
		<input name=\"pass_user\" type=\"password\" id=\"pass_user\" size=\"20\" maxlength=\"15\" onblur=\"checkPassword()\" /> <span id=\"divPassword\"></span>
		</p>
		<p>
		<label for=\"pass\">".$lang['admin_label_new_password']."</label><br />
		<input name=\"pass\" type=\"password\" id=\"pass\" size=\"20\" maxlength=\"15\" /><br />".
		$lang['admin_label_password_taille']."</p>
		<p>
		<label for=\"pass2\">".$lang['admin_label_password_again']."</label><br />
		<input name=\"pass2\" type=\"password\" id=\"pass2\" size=\"20\" maxlength=\"15\" />
		</p>
		<p> 
		<input name=\"Submit\" type=\"submit\" id=\"Submit\" value=\"".$lang['admin_label_modifier']."\" />
		<input name=\"envoye\" type=\"hidden\" id=\"envoye\" value=\"1\" />
		<input name=\"user\" type=\"hidden\" id=\"user\" value=\"".$_SESSION['username']."\" />
		<input name=\"valid_email\" type=\"hidden\" id=\"valid_email\" value=\"\" />
		<input name=\"id\" type=\"hidden\" id=\"id\" value=\"".$_SESSION['user_id']."\" />
		<input name=\"email_old\" type=\"hidden\" id=\"email_old\" value=\"$email\" />
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