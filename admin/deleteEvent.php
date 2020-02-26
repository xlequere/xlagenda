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
initSession();
if (!isSessionValid())
{
	echo 0;
}
else
{
	$auth=array('supprimer');
	$auth=isAuthorized($auth);
	$auth_supprim=$auth['supprimer'];
	//RECUPERATION DU CONTENU DES VARIABLES
	$id=(!empty($_POST['id'])) ? $_POST['id'] : Null;
	if (empty($id) || !is_numeric($id))
	{
		echo 0;
	}
	else
	{
	//ON VERIFIE QUE L'UTILISATEUR EST BIEN AUTORISE A SUPPRIMER CET EVENEMENT
		if (($auth_supprim == 1 && (getEventCreator($id) != $_SESSION['user_id'])) || !$auth_supprim)
		{
			echo 0;
		}
		else
		{
			//SUPPRESSION
			$stmt=$connexion->prepare("DELETE FROM $table_agenda WHERE id=?");
			$stmt->bind_param('i',$id);
			if ($stmt->execute())
			{
				echo 1;
				$connexion->query("OPTIMIZE TABLE $table_agenda");
			}
			$stmt->close();
		}
	}
}
?>