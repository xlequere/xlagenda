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

//RECUPERATION DES DONNEES
$server=(!empty($_SERVER["SERVER_NAME"])) ? $_SERVER["SERVER_NAME"] : Null;
$page=(!empty($_SERVER['PHP_SELF'])) ? $_SERVER['PHP_SELF'] : Null;
$nom=(!empty($_POST["nom"])) ? $_POST["nom"] : Null;
$prenom=(!empty($_POST["prenom"])) ? $_POST["prenom"] : Null;
$email=(!empty($_POST["email"])) ? $_POST["email"] : Null;
$user=(!empty($_POST["user"])) ? $_POST["user"] : Null;
$pass1=(!empty($_POST["pass1"])) ? $_POST["pass1"] : Null;
$pass2=(!empty($_POST["pass2"])) ? $_POST["pass2"] : Null;
$motif=(!empty($_POST["motif"])) ? $_POST["motif"] : Null;
$send=(!empty($_POST["send"])) ? $_POST["send"] : Null;
$controle=(!empty($_POST["controle"])) ? $_POST["controle"] : Null;
$code=(!empty($_POST["code"])) ? $_POST["code"] : Null;

//INITIALISATION DES VARIABLES
$texte="";
$afficher="";
$user_exists="";
$test_serveur=0;

//AFFICHAGE DE L'ENTETE
include("include/header.php");
?>
<div id="left">
<?php
//AFFICHAGE DU MENU
include("include/menu.php");
?>
</div>
<div id="main">
	<?php
	echo "<h1>".$lang['compte_title_demander_compte']."</h1>\n";
	//SI LE FORMULAIRE N'A PAS ETE ENVOYE
	if (!$send)
	{
		$afficher = 1;
	}
	//SI LE FORMULAIRE VIENT D'ETRE ENVOYE
	if ($send)
	{
		if ($nom && $prenom && $email && $user && $pass1 && $pass2 && $motif && $code)
		{
			if (cryptPass($code) != $controle)
			{
				echo "<p class=\"erreur\">".$lang['compte_erreur_code']."</p>\n";
				$afficher=1;
			}
			elseif ($pass1 != $pass2)
			{
				echo "<p class=\"erreur\">".$lang['compte_erreur_pass_differents']."</p>\n";
				$afficher=1;
			}
			elseif ($email && !checkEmail($email))
			{
				echo "<p class=\"erreur\">".$lang['compte_erreur_email']."</p>\n";
				$afficher=1;
			}
			elseif (userExists($user) || userRequestExists($user))
			{
				echo "<p class=\"erreur\">".sprintf($lang['compte_erreur_user_existe'],$user)."</b></p>\n";
				$afficher=1;
			}
			elseif (emailExists($email) || emailRequestExists($email))
			{
				echo "<p class=\"erreur\">".sprintf($lang['compte_erreur_email_existe'],$email)."</p>\n";
				$afficher=1;
			}
			elseif (strlen($pass1) < 6)
			{
			echo "<p class=\"erreur\">".$lang['compte_erreur_pass_invalide']."</p>";
			$afficher=1;
		}
		else
		{
			//AJOUT DE LA DEMANDE DANS LA BASE DE DONNEES
			$stmt=$connexion->prepare("INSERT INTO $table_demande (nom,prenom,email,user,pass,motif) VALUES (?,?,?,?,?,?)");
			$stmt->bind_param('ssssss',$nom,$prenom,$email,$user,$pass1,$motif);
			if ($stmt->execute())
			{
				$nom=stripslashes($nom);
				$prenom=stripslashes($prenom);
				$user=stripslashes($user);
				$pass1=stripslashes($pass1);
				$motif=stripslashes($motif);
				//SI LES CONDITIONS POUR ENVOYER UN EMAIL SONT REUNIES
				if (emailAdmin())
				{
					$texte .= sprintf($lang['mails_compte_nom'],$nom);
					$texte .= sprintf($lang['mails_compte_prenom'],$prenom);
					$texte .= sprintf($lang['mails_compte_email'],$email);
					$texte .= sprintf($lang['mails_compte_username'],$user);
					$texte .= sprintf($lang['mails_compte_password'],$pass1);
					$texte .= sprintf($lang['mails_compte_motif'],$motif);
					$texte .= $lang['mails_compte_fin'];
					$texte .="http://$server/$path_agenda/admin";$tab=explode(".",$server);
					if (isset($tab[1]) && isset($tab[2]))
					{
						$server=array("$tab[1]","$tab[2]");
						$server=implode(".",$server);
						$test_serveur=1;
					}
					foreach (getEmailsAdmin() as $admin_email)
					{
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
						@mail($admin_email, $lang['mails_compte_objet'], $texte, $headers);
					}
				}
				echo "<p class=\"confirmation\">".$lang['compte_confirmation1']."<br />";
				echo $lang['compte_confirmation2']."</p>\n";
			}
			else
			{
				echo "<p class=\"erreur\">".$lang['compte_erreur']."</p>\n";
			}
		}
	}
	if (!$nom || !$prenom || !$email || !$user || !$pass1 || !$pass2 || !$motif || !$code)
	{
		echo "<p class=\"erreur\">".$lang['compte_erreur_champs']."</p>\n";
		$afficher=1;
	}
}
if ($afficher)
{
?>
	<p><?php echo $lang['compte_intro'] ?></p>
	<form name="form1" method="post" action="<?php echo $url_compte ?>">
	<p>
		<label for="nom"><?php echo $lang['compte_label_nom'] ?></label><br />
		<input name="nom" type="text" id="nom" value="<?php echo input($nom) ?>" size="30" />
	</p>
	<p>
		<label for="prenom"><?php echo $lang['compte_label_prenom'] ?></label><br />
		<input name="prenom" type="text" id="prenom" value="<?php echo input($prenom) ?>" size="30" />
	</p>
	<p>
		<label for="email"><?php echo $lang['compte_label_email'] ?></label><br />
		<input name="email" type="text" id="email" value="<?php echo input($email) ?>" size="30" />
	</p>
	<p>
		<label for="user"><?php echo $lang['compte_label_username'] ?></label><br />
		<input name="user" type="text" id="user" value="<?php echo input($user) ?>" size="25" maxlength="20" />
	</p>
	<p>
		<label for="pass1"><?php echo $lang['compte_label_password'] ?></label><br />
		<input name="pass1" type="password" id="pass1" size="20" maxlength="15" /><br />
		<?php echo $lang['compte_label_taille'] ?>
	</p>
	<p>
		<label for="pass2"><?php echo $lang['compte_label_password2'] ?></label><br />
		<input name="pass2" type="password" id="pass2" size="20" maxlength="15" />
	</p>
	<p>
		<label for="motif"><?php echo $lang['compte_label_motif'] ?></label><br />
		<textarea name="motif" cols="40" rows="5" id="motif"><?php echo input($motif) ?></textarea>
	</p>
	<?php
	$code = rand(10000,99999);
	generateCaptcha($code,5);
	echo "<img src=\"img/code.png?".time()."\" alt=\"code\" />\n";
	$controle=cryptPass($code);
	?>
	<p>
		<label for="code"><?php echo $lang['common_validation_explain1'] ?></label> <input name="code" type="text" id="code" size="6" maxlength="5" /><br />
		<?php echo $lang['common_validation_explain2'] ?>
	</p>
	<?php
	echo "<p>".
		$lang['compte_footer1']."<br />".
		$lang['compte_footer2'].
	"</p>\n";
	?>
	<p> 
		<input type="submit" name="Submit" value="<?php echo $lang['compte_label_envoyer'] ?>" class="bouton" />
		<input name="send" type="hidden" id="send" value="1" />
		<input name="controle" type="hidden" id="sent" value="<?php echo $controle ?>" />
	</p> 
	</form>
	<?php
	}
	?>
</div>
<?php
include("include/footer.php");
?>