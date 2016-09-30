<?php
	include "../connect.php";
	if(!isset($_SESSION['userName']))
	{
		header("Location: ../login.php");
		exit();
	}
	$nick=$_SESSION['userName'];
	if(!isset($_GET['datum']))
	{
		header("Location: kovceg.php");
		exit();
	}
	$datum=$_GET['datum'];
	$status=otkljucajDnevnuPartiju($nick,$datum);
	switch($status)
	{
		case 1:
		header("Location: kovceg.php?ot_err=nmr");
		break;
		case 2:
		header("Location: kovceg.php?ot_err=sc");
		break;
		case 3:
		header("Location: kovceg.php?ot_err=nk");
		break;
		case 4:
		header("Location: kovceg.php?ot_err=nm");
		break;
	}
?>