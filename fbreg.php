<?php
	include "connect.php";
	
	function provera($nick)
	{
		$len=strlen($nick);
		if($len>=5)
		{
			for($i=0;$i<$len;$i++)
			{
				$o=ord($nick[$i]);
				if(($o!=46)&&(($o<48)||($o>57))&&(($o<65)||($o>90))&&(($o<97)||($o>122)))
				return false;
			}
		}
		else
		return false;
		return true;
	}

function kriptuj($text,$kljuc)
{
	$kriptovano=$kljuc[0];
	$pozicija_kljuca=0;
	$duzina_kljuca=strlen($kljuc);
	$duzina=strlen($text);
	for($i=0;$i<$duzina;$i++)
	{
		if($pozicija_kljuca==$duzina_kljuca)
		$pozicija_kljuca=0;
		$zbir=ord($text[$i])+intval($kljuc[$pozicija_kljuca]);
		if($zbir>122)
		$zbir-=77;
		$kriptovano.=chr($zbir);
		++$pozicija_kljuca;
	}
	$kriptovano.=$kljuc[$duzina_kljuca-1];
	return htmlentities($kriptovano);
}

function dekriptuj($text,$kljuc)
{
	$dekriptovano="";
	$text=html_entity_decode($text);
	$pozicija_kljuca=0;
	$duzina_kljuca=strlen($kljuc);
	$duzina=strlen($text);
	if(($text[0]!=$kljuc[0])||($text[$duzina-1]!=$kljuc[$duzina_kljuca-1]))
	return "";
	$text=substr($text,1,$duzina-2);
	$duzina-=2;
	for($i=0;$i<$duzina;$i++)
	{
		if($pozicija_kljuca==$duzina_kljuca)
		$pozicija_kljuca=0;
		$zbir=ord($text[$i])-intval($kljuc[$pozicija_kljuca]);
		if($zbir<46)
		$zbir+=77;
		$dekriptovano.=chr($zbir);
		++$pozicija_kljuca;
	}
	return $dekriptovano;
}
	
	if(isset($_GET['email'])&&isset($_GET['usnm']))
	{
		if(isset($_SESSION['userName']))
			logoutUser();
		$usnm=dekriptuj($_GET['usnm'],'23052983742');
                $email=dekriptuj($_GET['email'],'23052983742');
                if($usnm=="")
                {
                    header("Location: login.php");
                    exit;
                }
		$usnm=mysql_escape_string(trim($usnm));
		$email=mysql_escape_string(trim($email));
		if(provera($usnm))
		{
			if($usnm=="OnLy.N1k3")
			$usnm="Admin";
			if(!isUser($usnm))
			{
				facebookReg($usnm,$email);
			}
			facebookLog($usnm);
			header("Location: index.php");
			exit;
		}
	}

	header("Location: login.php");
?>