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
initSession();
if (isSessionValid())
{
	$email=(!empty($_POST['email'])) ? $_POST['email'] : Null;
	$user_id=(!empty($_POST['user_id'])) ? $_POST['user_id'] : Null;
	if ($user_id)
	{
		$test=emailExists($email,$user_id);
	}
	else
	{
		$test=emailExists($email);
	}
	echo $test;
}
?>