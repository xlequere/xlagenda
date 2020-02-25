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
include("../include/functions.php");
initSession();
if (isSessionValid())
{
	$password=(!empty($_POST['password'])) ? $_POST['password'] : Null;
	$the_pass=(!empty($_SESSION['password'])) ? $_SESSION['password'] : Null;
	$password=cryptPass($password);
	$password2=utf8_decode($password);
	$password2=cryptPass($password2);
	if ($the_pass == $password || $the_pass == $password2)
	{
		echo 0;
	}
	else
	{
		echo 1;
	}
}
?>