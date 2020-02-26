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
if (isSessionValid())
{
	$nom=(!empty($_POST['nom'])) ? $_POST['nom'] : Null;
	$categorie_id=(!empty($_POST['categorie_id'])) ? $_POST['categorie_id'] : Null;
	$total=categoryExists($nom,$categorie_id);
	if ($categorie_id)
	{
		echo "$total;$categorie_id";
	}
	else
	{
		echo $total;
	}
}
?>