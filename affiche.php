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

******************************************************************/

/******************************************************************
*	AFFICHAGE DES RESULTATS DE LA REQUETE
******************************************************************/
echo "<p id=\"feedback\" style=\"display:none;\">&nbsp;</p>\n";
if ($texte)
{
	echo "<p>$texte</p>";
}
while($ligne=$result->fetch_array())
{
	$id=$ligne["id"];
	$user_event=$ligne["id_user"];
	$date_debut=$ligne["date_debut"];
	$date_fin=$ligne["date_fin"];
	$heure_debut=$ligne["heure_debut"];
	$heure_fin=$ligne["heure_fin"];
	$nom=$ligne["nom"];
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
	$nom=input($nom);
	$description=stripslashes($description);
	$lieu=input($lieu);
	$contact=input($contact);
	$adresse=input($adresse);
	$telephone=input($telephone);
	$fax=input($fax);
	$lien=input($lien);
	$lien=input($lien);
	$date_debut=formatDate($date_debut);
	$date_fin=formatDate($date_fin);
	$heure_debut=formatTime($heure_debut);
	$heure_fin=formatTime($heure_fin);
	$nom_categorie=getCategory($categorie);
	$couleur=getColor($categorie);
	$email=emailEncode($email);
	if (!$couleur)
	{
		echo "<div class=\"event\" id=\"event_$id\">\n";
	}
	else
	{
		echo "<div class=\"event\" id=\"event_$id\" style=\"border-left:5px solid $couleur\">\n";
	}
	if ($date_fin == $date_debut)
	{
		echo "<p>".sprintf($lang['common_le'],$date_debut);
		if ($heure_debut && $heure_fin) echo " ".sprintf($lang['common_de_a'],$heure_debut,$heure_fin);
		elseif ($heure_debut) echo " ".sprintf($lang['common_a'],$heure_debut);
		elseif ($heure_fin) echo " ".sprintf($lang['common_jusqua'],$heure_fin);
	}
	else
	{
		echo "<p>".sprintf($lang['common_du'],$date_debut);
		if ($heure_debut)
		{
			echo " ".sprintf($lang['common_a'],$heure_debut);
		}
		echo " ".sprintf($lang['common_au'],$date_fin);
		if ($heure_fin)
		{
			echo " ".sprintf($lang['common_a'],$heure_fin);
		}
	}
	echo "<br />\n";
	if ($reduit)
	{
		echo "<b>$nom_categorie : <a href=\"javascript:showEvent($id)\">$nom</a></b></p>\n";
	}
	else
	{
		echo "<b>$nom_categorie : $nom</b></p>\n";
	}
	if ($reduit)
	{
		echo "<div id=\"texte_event_$id\" style=\"display:none\">\n";
	}
	if ($description && $editeur_html)
	{
		echo "<div>$description</div>\n";
	}
	elseif ($description && !$editeur_html)
	{
		$description=nl2br($description);
		echo "<p>$description</p>\n";
	}
	echo "<p>";
	if ($lieu)
	{
		echo "<b>".$lang['common_lieu']."</b>$lieu";
	}
	if ($lieu && $contact)
	{
		echo "<br />";
	}
	if ($contact)
	{
		echo "<b>".$lang['common_contact']."</b>$contact ";
	}
	if ($contact && $email)
	{
		echo " - <a href=\"mailto:$email\">$email</a> ";
	}
	elseif ($email)
	{
		echo "<br /><b>".$lang['common_contact']."</b> <a href=\"mailto:$email\">$email</a>";
	}
	if ($adresse)
	{
		echo "<br />$adresse ";
	}
	if ($telephone)
	{
		echo "<br /><b>".$lang['common_tel']."</b>$telephone ";
	}
	if ($telephone && $fax)
	{
		echo "- <b>".$lang['common_fax']."</b>$fax ";
	}
	elseif ($fax)
	{
		echo "<br /><b>".$lang['common_fax']."</b>$fax ";
	}
	if ($url && $lien)
	{
		echo "<br /><b>".$lang['common_plus']."</b> <a href=\"$url\" target=\"_blank\">$lien</a>";
	}
	echo "</p>\n";
	if ($reduit)
	{
		echo "</div>\n";
	}
	echo "<p class=\"liens_action\">\n";
	if ($auth_modif == 2 || ($auth_modif == 1 && $_SESSION['user_id'] == $user_event && $auth_actif)) echo "<a href=\"#\" onclick=\"openWithPostData('$repertoire_admin/modifier.php',{id:'$id',submit_form2:'1'});\">".$lang['common_link_modifier']."</a>";
	if (($auth_modif == 2 || ($auth_modif == 1 && $_SESSION['user_id'] == $user_event && $auth_actif)) && ($auth_supprim == 2 || ($auth_supprim == 1 && $_SESSION['user_id'] == $user_event))) echo " | ";
	if ($auth_supprim == 2 || ($auth_supprim == 1 && $_SESSION['user_id'] == $user_event)) echo "<a href=\"#\" onclick=\"supprimerEvent($id)\">".$lang['common_link_supprimer']."</a>";
	echo "</p>\n";
	echo "</div>\n";
}
?>