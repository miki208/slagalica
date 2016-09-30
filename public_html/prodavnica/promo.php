<?php
	include "../connect.php";
	checkUser();
	
	if(isset($_SESSION['userName'])&&(isset($_GET['secret'])||isset($_POST['secret'])))
	{
		$nick=$_SESSION['userName'];
		if(isset($_GET['secret']))
		$key=mysql_escape_string($_GET['secret']);
		else
		$key=mysql_escape_string($_POST['secret']);
		
		$data=mysql_query("SELECT * FROM promocije WHERE secret_key='$key'");
		$data1=mysql_query("SELECT * FROM promo_user WHERE secret_key='$key' AND nick='$nick'");
		if((mysql_num_rows($data)==0)||(mysql_num_rows($data1)==1))
		{
			if(mysql_num_rows($data)==0)
			header("Location: index.php?errp=us");
			else
			header("Location: index.php?errp=ui");
		}
		else
		{
			$niz=mysql_fetch_assoc($data);
			if($niz['kolicina']=="1")
			{
				mysql_query("DELETE FROM promocije WHERE secret_key='$key'");
				mysql_query("DELETE FROM promo_user WHERE secret_key='$key'");
			}
			else
			{
				mysql_query("UPDATE promocije SET kolicina=kolicina-'1' WHERE secret_key='$key'");
				mysql_query("INSERT INTO promo_user(secret_key,nick) VALUES('$key','$nick')");
			}
			switch($niz['tip'])
			{
				case "tokeni":
					add_tokens($nick,intval($niz['vrednost']));
					kreiraj_notifikaciju($nick,"Promocija aktivirana","Uspesno aktivirana promocija u vrednosti od ".$niz['vrednost']." tokena.");
					header("Location: index.php?errp=tkn");
					break;
				case "exp":
					addExp($nick,$niz['vrednost']);
					kreiraj_notifikaciju($nick,"Promocija aktivirana","Uspesno aktivirana promocija u vrednosti od ".$niz['vrednost']." iskustva.");
					header("Location: index.php?errp=exp");
					break;
				case "pi":
					add_ponistavanje_igre($nick,$niz['vrednost']);
					kreiraj_notifikaciju($nick,"Promocija aktivirana","Uspesno aktivirana promocija u vrednosti od ".$niz['vrednost']." PI.");
					header("Location: index.php?errp=pi");
					break;
				case "rp":
					add_reputacija($nick,$niz['vrednost']);
					kreiraj_notifikaciju($nick,"Promocija aktivirana","Uspesno aktivirana promocija u vrednosti od ".$niz['vrednost']." reputacionih bodova.");
					header("Location: index.php?errp=rp");
					break;
				case "kljuc":
					kljuc_func($nick,$niz['vrednost'],0);
					kreiraj_notifikaciju($nick,"Promocija aktivirana","Uspesno aktivirana promocija u vrednosti od ".$niz['vrednost']." kljuceva.");
					header("Location: index.php?errp=kl");
					break;
				default:
					header("Location: index.php?errp=unkprom");
					break;
			}
		}
	}
	else
	{
		if(!isset($_GET['secret']))
		header("Location: index.php?errp=ns");
	}
?>