<?php
	include "connect.php";
	checkUser();
	if(!checkPerm($_SESSION['userName'],"info_add"))
	header("Location: index.php");
	else
	{
		if(isset($_GET['akcija'])&&isset($_GET['id']))
		{
			switch($_GET['akcija'])
			{
				case "aktivacija":
				{
					mysql_query("UPDATE obavestenja SET status='aktivan' WHERE id='".$_GET['id']."'");
					break;
				}
				case "deaktivacija":
				{
					mysql_query("UPDATE obavestenja SET status='neaktivan' WHERE id='".$_GET['id']."'");
					break;
				}
				case "brisanje":
				{
					mysql_query("DELETE FROM obavestenja WHERE id='".$_GET['id']."'");
					break;
				}
			}
		}
		header("Location: manager_obavestenja.php");
	}
?>