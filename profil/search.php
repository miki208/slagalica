<?php
	include "../connect.php";
	if ((!isset($_SESSION['validUser'])) || ($_SESSION['validUser'] != true)){
		header('Location: ../login.php');
	}
	if(isset($_GET['nick']))
	{
		$nick=mysql_escape_string($_GET['nick']);
		if(isUser($nick))
		{
			$data=mysql_query("SELECT ID FROM lista WHERE username='$nick'");
			$niz=mysql_fetch_assoc($data);
			$id=$niz['ID'];
			header("Location: index.php?id=$id");
		}
		else
		{
			header("Location: index.php");
		}
	}
	else
	{
		header("Location: index.php");
	}
?>