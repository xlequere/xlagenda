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

//CONNEXION A LA BASE DE DONNEES
$connexion = @mysqli_connect($dbserver,$dbuser,$dbpass,$dbdb);
if (mysqli_connect_errno())
{
    echo "<p>Erreur : impossible de communiquer avec la base de données.<br />Vérifiez les donnés du fichier <b>data.php</b>.</p>";
    exit();
}
mysqli_query($connexion,"SET NAMES 'utf8'");
?>