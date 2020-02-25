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


//RECUPERATION DES INFORMATIONS SUR LE SITE
$page=(!empty($_SERVER['PHP_SELF'])) ? $_SERVER['PHP_SELF'] : Null;
$server=(!empty($_SERVER['SERVER_NAME'])) ? $_SERVER['SERVER_NAME'] : Null;
//RECUPERATION DES INFORMATIONS SUR L'UTILISATEUR
$auth=array('ajouter','modifier','supprimer','valider','gerer');
$auth=isAuthorized($auth);
$auth_ajouter=$auth['ajouter'];
$auth_modifier=$auth['modifier'];
$auth_supprimer=$auth['supprimer'];
$auth_valider=$auth['valider'];
$auth_gerer=$auth['gerer'];
//RECUPERATION DU NOMBRE D'EVENEMENTS EN ATTENTE
$attente=getPendingEvents();
echo "<p style=\"text-align:center\">";
echo "<a href=\"index.php\">".$lang['admin_link_accueil']."</a>";
echo "&nbsp;|&nbsp;";
if (isIncluded("profil",$page))
{
	echo $lang['admin_link_profil'];
}
else
{
	echo "<a href=\"profil.php\">".$lang['admin_link_profil']."</a>";
}
echo "&nbsp;|&nbsp;";
if ($auth_ajouter)
{
	if (isIncluded("ajouter",$page))
	{
		echo $lang['admin_link_ajouter'];
	}
	else
	{
		echo "<a href=\"ajouter.php\">".$lang['admin_link_ajouter']."</a>";
	}
	echo "&nbsp;|&nbsp;";
}
if ($auth_modifier)
{
	if (isIncluded("modifier",$page))
	{
		echo $lang['admin_link_modifier'];
	}
	else
	{
		echo "<a href=\"modifier.php\">".$lang['admin_link_modifier']."</a>";
	}
	echo "&nbsp;|&nbsp;";
}
if ($auth_supprimer)
{
	if (isIncluded("supprimer",$page))
	{
		echo $lang['admin_link_supprimer'];
	}
	else
	{
		echo "<a href=\"supprimer.php\">".$lang['admin_link_supprimer']."</a>";
	}
	echo "&nbsp;|&nbsp;";
	}
if ($auth_valider)
{
	if (isIncluded("valider",$page))
	{
		echo $lang['admin_link_valider'];
	}
	else
	{
		echo "<a href=\"valider1.php\">".$lang['admin_link_valider']." ($attente)</a>";
	}
	echo "&nbsp;|&nbsp;";
}
if ($auth_gerer)
{
	if (isIncluded("gerer",$page) || isIncluded("demande",$page))
	{
		echo $lang['admin_link_utilisateurs'];
	}
	else
	{
		echo "<a href=\"gerer1.php\">".$lang['admin_link_utilisateurs']."</a>";
	}
	echo "&nbsp;|&nbsp;";
	if (isIncluded("categories",$page))
	{
		echo $lang['admin_link_categories'];
	}
	else
	{
		echo "<a href=\"categories1.php\">".$lang['admin_link_categories']."</a>";
	}
	echo "&nbsp;|&nbsp;";
	if (isIncluded("infos",$page))
	{
		echo $lang['admin_link_infos'];
	}
	else
	{
		echo "<a href=\"infos.php\">".$lang['admin_link_infos']."</a>";
	}
	echo "&nbsp;|&nbsp;";
	if (isIncluded("logs",$page))
	{
		echo "Logs de connexion";
	}
	else
	{
		echo "<a href=\"logs1.php\">".$lang['admin_link_logs']."</a>";
	}
	echo "&nbsp;|&nbsp;";
}
echo "<a href=\"close.php\">".$lang['admin_link_deconnexion']." (".$_SESSION['username'].")</a>";
echo "</p>\n";
?>