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
?>
<form name="form1" method="post" action="install2.php">
	<h3>Choisissez un nom d'utilisateur et un mot de passe d'administration</h3>
	<p>
		<label for="user">Nom d'utilisateur :</label><br>
		<input name="user" type="text" id="user" value="<?php if (isset($user)) echo input($user) ?>" maxlength="20" size="30" /> *<br>
		(20 caractères maximum)
	</p>
	<p>
		<label for="pass">Mot de passe :</label><br>
		<input name="pass" type="password" id="pass" maxlength="15" size="30" /> *<br>
		(6 à 15 caractères)
	</p>
	<p>
		<label for="pass2">Introduisez à nouveau le mot de passe :</label><br>
		<input name="pass2" type="password" id="pass2" maxlength="15" size="30" /> *
	</p>
	<p>
		<label for="nom">Nom :</label><br>
		<input name="nom" type="text" id="nom" value="<?php if (isset($nom)) echo input($nom) ?>" size="30" />
	</p>
	<p>
		<label for="prenom">Prénom :</label><br> 
		<input name="prenom" type="text" id="prenom" value="<?php if (isset($prenom)) echo input($prenom) ?>" size="30" />
	</p>
	<p>
		<label for="email">Adresse email :</label><br>
		<input name="email" type="text" id="email" value="<?php if (isset($email)) echo $email ?>" size="30" /> *
	</p>
	<p>
		Les champs marqués d'un * sont obligatoires.<br>
		Votre adresse email n'appara&icirc;tra pas sur l'agenda mais est nécessaire pour vous permettre de récupérer votre mot de passe si vous l'avez oublié.
	</p>
	<p style="text-align:center;"> 
	<input type="submit" name="Submit" value="Continuer >>" />
	</p>
</form>