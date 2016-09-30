<?php
	include "connect.php";
	if(isset($_GET['key']))
	{
		$key=mysql_escape_string($_GET['key']);
		if(mysql_num_rows(mysql_query("SELECT * FROM lista WHERE conf_code='$key' AND (status='neaktivan' OR status='none')"))>0)
		{
			mysql_query("UPDATE lista SET status='aktivan' WHERE conf_code='$key' AND (status='neaktivan' OR status='none')");
			$data=mysql_query("SELECT username FROM lista WHERE conf_code='$key'");
			$niz=mysql_fetch_assoc($data);
			kreiraj_notifikaciju($niz['username'],"Uspesno ste registrovani","Dobrodosli, drago nam je sto ste odlucili da nam se pridruzite. Ukoliko Vam je potrebna pomoc posetite nasu stranicu na fejsbuku \"Mikisoft Slagalica\", ili se obratite u inbox, poruku posaljite na nick Admin. Zelimo Vam puno zabave i mnogo znanja!");
			header('Location: login.php?potvrda=ok');
		}
		else
			header('Location: login.php?potvrda=no');
	}
	else
		header('Location: login.php?potvrda=no');
?>