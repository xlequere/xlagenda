<?php
/******************************************************************
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

echo "<div id=\"cadre_menu\">
<h2>".$lang['common_title_menu']."</h2>
<p>\n";
//LIEN CALENDRIER
if (!isIncluded($url_page,$page)) echo "&gt; <a href=\"$url_page\">".$lang['common_link_calendrier']."</a><br />\n";
else echo "&gt; ".$lang['common_link_calendrier']."<br />\n";
//LIEN RECHERCHER
if (!isIncluded($url_recherche,$page)) echo "&gt; <a href=\"$url_recherche\">".$lang['common_link_rechercher']."</a><br />\n";
else echo "&gt; ".$lang['common_link_rechercher']."<br />\n";
//LIENS AJOUTER ET PROPOSER
if (isSessionValid())
{
	echo "&gt; <a href=\"$repertoire_admin/ajouter.php\">".$lang['common_link_ajouter']."</a><br />\n";
}
else
{
	if ($menu_ajouter)
	{
		echo "&gt; <a href=\"$repertoire_admin/index.php\">".$lang['common_link_ajouter']."</a><br />\n";
	}
	if ($menu_proposer && !isIncluded($url_proposition,$page))
	{
		echo "&gt; <a href=\"$url_proposition\">".$lang['common_link_proposer']."</a><br />\n";
	}
	elseif ($menu_proposer)
	{
		echo "&gt; ".$lang['common_link_proposer']."<br />\n";
	}
	//LIEN DEMANDER UN COMPTE
	if ($menu_compte && !isIncluded($url_compte,$page))
	{
		echo "&gt; <a href=\"$url_compte\">".$lang['common_link_compte']."</a>\n";
	}
	elseif ($menu_compte)
	{
		echo "&gt; ".$lang['common_link_compte']."\n";
	}
}
//LIEN DECONNEXION
if (isSessionValid()) echo "&gt; <a href=\"$repertoire_admin/close.php\">".$lang['common_link_deconnexion']."</a>\n";
echo "</p>
</div>\n"
?>