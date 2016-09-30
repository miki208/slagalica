<?php
	include "connect.php";
	if(!isset($_SESSION['userName']))
	{
		header("Location: login.php");
		exit();
	}
	$nick=$_SESSION['userName'];
	$broj=0;
	if(isset($_POST['mg_broj']))
	{
		if(is_numeric($_POST['mg_broj']))
		{
			$broj=floor(intval($_POST['mg_broj']));
			if($broj<1||$broj>200)
			{
				header("Location: prodavnica/kovceg.php?mg_err=oflmt");
				exit();
			}
		}
		else
		{
			header("Location: prodavnica/kovceg.php?mg_err=nonum");
			exit();
		}
	}
	else
	{
		header("Location: prodavnica/kovceg.php?mg_err=mg_nv");
		exit();
	}
	if(mysql_num_rows(mysql_query("SELECT * FROM mini_igra WHERE nick='$nick' AND datum='".date("d-m-Y")."'"))>0)
	{
		header("Location: prodavnica/kovceg.php");
		exit();
	}
	if(!use_tokens($nick,10))
	{
		header("Location: prodavnica/kovceg.php?mg_err=notok");
		exit();
	}
	else
	{
		mysql_query("INSERT INTO mini_igra(nick,broj,datum) VALUES('$nick','$broj','".date("d-m-Y")."')");
		$jp=LoadSettings('mini-igra-total');
		$jack=intval($jp['opcija'])+10;
		mysql_query("UPDATE podesavanja SET opcija='$jack' WHERE stavka='mini-igra-total'");
	}
	header("Location: prodavnica/kovceg.php");
?>