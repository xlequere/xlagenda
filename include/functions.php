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


function initSession()
{
	global $path_agenda;
	if ($path_agenda) $chemin = "/$path_agenda/";
	else $chemin = "/";
	ini_set("session.use_cookies", 1);
	ini_set("session.use_only_cookies", 1);
	ini_set('session.use_trans_sid', 0);
	if (phpversion() > 5.2)
	{
		session_set_cookie_params(0, $chemin, '', false, true);
	}
	else
	{
		session_set_cookie_params(0, $chemin, '', false);
	}
	session_start();
}

function destroySession()
{
	global $path_agenda;
	if ($path_agenda) $chemin = "/$path_agenda/";
	else $chemin = "/";
	if (phpversion() > 5.2)
	{
		setcookie(session_name(), '', time()-3600, $chemin, '', false, true);
	}
	else
	{
		setcookie(session_name(), '', time()-3600, $chemin, '', false);
	}
	
	$_SESSION = array();
	session_destroy();
}

function isSessionValid()
{
	if (isset($_SESSION['username']) && isset($_SESSION['timeout']) && (time() < $_SESSION['timeout']))
	{
		global $session_timeout;
		$_SESSION['timeout'] = time()+$session_timeout;
		return true;
	}
}

function isAuthorized($rights)
{
	global $connexion;
	global $table_users;
	$auth=Null;
	$request=Null;
	if (!is_array($rights)) return $auth;
	else
	{
		$count = count($rights);
		for ($i = 0; $i < $count; $i++)
		{
			$request .="$rights[$i],";
		}
		$request=rtrim($request,',');
		$result=$connexion->query("SELECT ".$request." FROM $table_users WHERE id=".$_SESSION['user_id']);
		if ($result && $result->num_rows)
		{
			$ligne=$result->fetch_array();
			$auth=array();
			for ($i = 0; $i < $count; $i++)
			{
				$droit=$rights[$i];
				$var=$droit;
				$$var=$ligne[$droit];
				$auth["$var"] = $$var;
			}
		}
		return $auth;
	}
}

function testDate($date)
{
	$test_form=0;
	$test_date=0;
	$test_form=preg_match('`^\d{2}/\d{2}/\d{4}$`',$date);
	if ($test_form)
	{
		$tab=explode("/",$date);
		$test_date=checkdate($tab[1],$tab[0],$tab[2]);
	}
	if ($test_form && $test_date)
	{
		return true;
	}
	else
	{
		return false;
	}
}

function testTime($time)
{
	$test_form=0;
	$test_heure=0;
	$test_form=preg_match('`^\d{2}:\d{2}$`',$time);
	if ($test_form)
	{
		$tab=explode(":",$time);
		if ($tab[0] < 24 && $tab[1] < 60)
		{
			$test_heure=1;
		}
	}
	if ($test_form && $test_heure)
	{
		return true;
	}
	else
	{
		return false;
	}
}

function testColor($color)
{
	$color=strtoupper($color);
	if(isIncluded("^[a-f0-9]{6}$",$color) && $color != 'FFFFFF')
	{
	    $color = '#'.$color;
	    return $color;
	}
	elseif(isIncluded("^#[a-f0-9]{6}$",$color) && $color != '#FFFFFF')
	{
	    return $color;
	}
	else
	{
	    return false;
	}
}

function cleanHtml($text)
{
	global $editeur_html;
	if ($editeur_html)
	{
		$text=strip_tags($text,"<p>,<span>,<ul>,<ol>,<strong>,<em>,<li>");
		$expression="font-family[^>]*\;";
		$text=replace($expression,"",$text);
		$expression="font-size[^>]*\;";
		$text=replace($expression,"",$text);
	}
	else
	{
		$text=strip_tags($text);
	}
	return $text;
}

function getCategories($excluded_category=Null)
{
	global $connexion;
	global $table_categories;
	if (!is_numeric($excluded_category))
	{
		$filter=Null;
	}
	else
	{
		$filter=" WHERE id != $excluded_category";
	}
	$result=$connexion->query("SELECT id,nom FROM $table_categories".$filter." ORDER BY nom ASC");
	if($result)
	{
		$categories=Array();
		while($ligne=$result->fetch_array())
		{
			$categories[$ligne["id"]]=$ligne["nom"];
		}
		return $categories;
	}
	return false;
}

function getCategory($id_category)
{
	global $connexion;
	global $table_categories;
	if (is_numeric($id_category))
	{
		$result=$connexion->query("SELECT nom FROM $table_categories WHERE id = $id_category");
		if($result)
		{
			$ligne=$result->fetch_array();
			return $ligne['nom'];
		}
		else
		{
			return false;
		}
	}
	else
	{
		return false;
	}
}

function getColor($category)
{
	global $connexion;
	global $table_categories;
	if (is_numeric($category))
	{
		$result=$connexion->query("SELECT couleur FROM $table_categories WHERE id = $category");
		if($result)
		{
			$ligne=$result->fetch_array();
			$couleur = $ligne['couleur'];
			$couleur = testColor($couleur);
			if ($couleur)
			{
				return $couleur;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}
	else
	{
		return false;
	}
}

function getUser($user)
{
	global $connexion;
	global $table_users;
	if (!$user)
	{
		global $lang;
		return $lang['common_un_invite'];
	}
	elseif (is_numeric($user))
	{
		$result=$connexion->query("SELECT user FROM $table_users WHERE id = $user");
		if($result)
		{
			$ligne=$result->fetch_array();
			return $ligne['user'];
		}
		else
		{
			return false;
		}
	}
	else
	{
		return false;
	}
}

function formatDate($date)
{
	$tab=explode("-",$date);
	$date=array("$tab[2]","$tab[1]","$tab[0]");
	$date=implode("/",$date);
	return $date;
}

function formatTime($time,$input=false)
{
	if ($time)
	{
		$tab=explode(":",$time);
		$time=array("$tab[0]","$tab[1]");
		$time=implode(":",$time);
		return $time;
	}
	elseif ($input)
	{
		return 'hh:mm';
	}
	else
	{
		return Null;
	}
}

function mysqlDate($date)
{
	$tab=explode("/",$date);
	$date=array("$tab[2]","$tab[1]","$tab[0]");
	$date=implode("-",$date);
	return $date;
}

function cryptPass($pass)
{
	$majorsalt="";
	if(!function_exists('str_split'))
	{
		function str_split($string,$string_length=1)
		{
			if(strlen($string)>$string_length || !$string_length)
			{
				do
				{
				$c = strlen($string);
				$parts[] = substr($string,0,$string_length);
				$string = substr($string,$string_length);
				}
				while($string !== false);
			}
			else
			{
				$parts = array($string);
			}
			return $parts;
		}
	}
	$pass = str_split($pass);
	foreach ($pass as $hashpass)
	{
	$majorsalt .= md5($hashpass);
	}
	$corehash = md5($majorsalt);
	return $corehash;
}

function checkEmail($email)
{
	$result=1;
	if (!isIncluded(".+@.+\\..+",$email))
	{
		$result=0;
	}
	if (isIncluded(" ",$email))
	{
		$result=0;
	}
	if (isIncluded("'",$email))
	{
		$result=0;
	}
	if (htmlentities($email) != $email)
	{
		$result=0;
	}
	return $result;
}

function monthName($month)
{
	$month=ltrim($month,"0");
    if (is_numeric($month) && $month < 13)
    {
	    global $lang;
	    $var="common_month_$month";
	    return $lang[$var];
    }
    else
    {
	    return false;
	}
}

function getVersion()
{
	global $connexion;
	global $table_config;
	$result=$connexion->query("SELECT valeur FROM $table_config WHERE nom = 'current_version'");
	if ($result && $result->num_rows)
	{
		$ligne=$result->fetch_array();
		$version=$ligne["valeur"];
		return $version;
	}
	else
	{
		return false;
	}
}

function isIncluded($pattern,$subject)
{
	if (function_exists('preg_match'))
	{
		$pattern="/$pattern/i";
		return preg_match($pattern,$subject);
	}
	else
	{
		return eregi($pattern,$subject);
	}
}

function replace($pattern,$replacement,$subject)
{
	if (function_exists('preg_replace'))
	{
		$pattern="/$pattern/i";
		return preg_replace($pattern,$replacement,$subject);
	}
	else
	{
		return eregi_replace($pattern,$replacement,$subject);
	}
}

function emailEncode($email)
{
	$email_encode = '';
	$nb_caractere = strlen($email);
	for ($a = 0; $a < $nb_caractere; $a ++)
	{
	$ord = ord(substr($email, $a, 1) );
	$email_encode .= '&#'.$ord.';';
	}
	return $email_encode;
}

function addLink($text,$url)
{
	$match["surl"] = "/\[url\](.*?)\[\/url\]/is";
	$replace["surl"] = "<a href=\"$url\">$1</a>";
	$result = preg_replace($match, $replace, $text);
	return $result;
}

function checkInstall()
{
	global $connexion;
	global $table_agenda;
	$result=$connexion->query("SELECT id FROM $table_agenda");
	return $result;
}

function generateCaptcha($string,$font_size)
{
	$width=imagefontwidth($font_size)*strlen($string)*2;
	$height=imagefontheight($font_size)*2;
	$img = imagecreate($width,$height);
	$bg = imagecolorallocate($img,225,225,225);
	$black = imagecolorallocate($img,0,0,0);
	$len=strlen($string);
	for($i=0;$i<$len;$i++)
	{
		$xpos=$i*imagefontwidth($font_size)*2;
		$ypos=rand(0,imagefontheight($font_size));
		imagechar($img,$font_size,$xpos,$ypos,$string,$black);
		$string = substr($string,1);      
	}
	imagepng ($img,"img/code.png");
	imagedestroy($img);
}

function emailAdmin()
{
	global $connexion;
	global $table_users;
	$result=$connexion->query("SELECT email FROM $table_users WHERE email LIKE '%@%' AND gerer = 1");
	if($result)
	{
		return $result->num_rows;
	}
	else
	{
		return false;
	}
}

function getEmailsAdmin()
{
	$emails=array();
	global $connexion;
	global $table_users;
	$result=$connexion->query("SELECT email FROM $table_users WHERE email LIKE '%@%' AND gerer = 1");
	if ($result->num_rows)
	{
		while($ligne=$result->fetch_array())
		{
			$emails[]=$ligne['email'];
		}
		return $emails;
	}
	else
	{
		return false;
	}
}

function emailExists($email,$id_user=Null)
{
	global $connexion;
	global $table_users;
	if (is_numeric($id_user))
	{
		$stmt=$connexion->prepare("SELECT id FROM $table_users WHERE email = ? AND id != ?");
		$stmt->bind_param('si',$email,$id_user);
	}
	else
	{
		$stmt=$connexion->prepare("SELECT id FROM $table_users WHERE email = ?");
		$stmt->bind_param('s',$email);
	}
	if ($stmt->execute() && $result=$stmt->get_result())
	{
		$stmt->close();
		return $result->num_rows;
	}
	else
	{
		$stmt->close();
		return false;
	}
}

function emailRequestExists($email)
{
	global $connexion;
	global $table_demande;
	$stmt=$connexion->prepare("SELECT id FROM $table_demande WHERE email = ?");
	$stmt->bind_param('s',$email);
	if ($stmt->execute() && $result=$stmt->get_result())
	{
		$stmt->close();
		return $result->num_rows;
	}
	else
	{
		$stmt->close();
		return false;
	}
}

function userExists($username,$id_user=Null)
{
	global $connexion;
	global $table_users;
	if (is_numeric($id_user))
	{
		$stmt=$connexion->prepare("SELECT id FROM $table_users WHERE user = ? AND id != ?");
		$stmt->bind_param('si',$username,$id_user);
	}	
	else
	{
		$stmt=$connexion->prepare("SELECT id FROM $table_users WHERE user = ?");
		$stmt->bind_param('s',$username);
	}
	if ($stmt->execute() && $result=$stmt->get_result())
	{
		$stmt->close();
		return $result->num_rows;
	}
	else
	{
		$stmt->close();
		return false;
	}
}

function userRequestExists($username)
{
	global $connexion;
	global $table_demande;
	$stmt=$connexion->prepare("SELECT id FROM $table_demande WHERE user = ?");
	$stmt->bind_param('s',$username);
	if ($stmt->execute() && $result=$stmt->get_result())
	{
		$stmt->close();
		return $result->num_rows;
	}
	else
	{
		$stmt->close();
		return false;
	}
}

function categoryExists($category,$id_category1=Null,$id_category2=Null)
{
	global $connexion;
	global $table_categories;
	if (is_numeric($id_category1) && is_numeric($id_category2))
	{
		$stmt=$connexion->prepare("SELECT id FROM $table_categories WHERE nom = ? AND id != ?  AND id != ?");
		$stmt->bind_param('sii',$category,$id_category1,$id_category2);
	}
	elseif (is_numeric($id_category1) || is_numeric($id_category2))
	{
		$stmt=$connexion->prepare("SELECT id FROM $table_categories WHERE nom = ? AND id != ?");
		if ($id_category1)
		{
			$stmt->bind_param('si',$category,$id_category1);
		}
		else
		{
			$stmt->bind_param('si',$category,$id_category2);
		}
	}
	else
	{
		$stmt=$connexion->prepare("SELECT id FROM $table_categories WHERE nom = ?");
		$stmt->bind_param('s',$category);
	}
	if ($stmt->execute() && $result=$stmt->get_result())
	{
		$stmt->close();
		return $result->num_rows;
	}
	else
	{
		$stmt->close();
		return false;
	}
}

function input($text)
{
	$text = stripslashes(htmlspecialchars($text));
	return $text;
}

function getLang()
{
	global $language;
	if (isset($language) && $language)
	{
		return $language;
	}
	else
	{
		return 'fr';
	}
}

function getEventCreator($id_event)
{
	if (is_numeric($id_event))
	{
		global $connexion;
		global $table_agenda;
		$result=$connexion->query("SELECT id_user FROM $table_agenda WHERE id = $id_event");
		$ligne=$result->fetch_array();
		return $ligne['id_user'];
	}
	else
	{
		return false;
	}
}

function getEventName($id_event)
{
	if (is_numeric($id_event))
	{
		global $connexion;
		global $table_agenda;
		$result=$connexion->query("SELECT nom FROM $table_agenda WHERE id = $id_event");
		$ligne=$result->fetch_array();
		return $ligne['nom'];
	}
	else
	{
		return false;
	}
}

function getPendingUsers()
{
	global $connexion;
	global $table_demande;
	$result=$connexion->query("SELECT id FROM $table_demande");
	if ($result)
	{
		return $result->num_rows;
	}
	else
	{
		return false;
	}
}

function getPendingEvents()
{
	global $connexion;
	global $table_agenda;
	$result=$connexion->query("SELECT id FROM $table_agenda where actif = 0");
	if ($result)
	{
		return $result->num_rows;
	}
	else
	{
		return false;
	}
}
?>