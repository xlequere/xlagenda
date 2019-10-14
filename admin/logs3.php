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
	header("Content-type: application/force-download");
	header('Content-Type: text/html; Charset=UTF-8');
	header('Content-Disposition: attachment; filename="logs.csv"');
	$result=$connexion->query("SELECT * FROM $table_logs");
	if ($result)
	{
		echo "'".$lang['admin_logs_utilisateur']."';'".$lang['admin_logs_date']."';'".$lang['admin_logs_heure']."';'".$lang['admin_logs_ip']."';'".$lang['admin_logs_domaine']."';'".$lang['admin_logs_resultat']."'\r";
		while ($ligne = $result->fetch_array())
		{
			$user=$ligne["user"];
			$date=$ligne["date"];
			$time=$ligne["time"];
			$ip=$ligne["ip"];
			$domain=$ligne["domain"];
			$resultat=$ligne["result"];
			$date=formatDate($date);
			$user=utf8_encode($user);
			echo "'$user';'$date';'$time';'$ip';'$domain';'$resultat'\r";
		}
	}
}
?>