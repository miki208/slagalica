<?php
	include "connect.php";
	checkUser();
	if(!checkPerm($_SESSION['userName'],"baner_add"))
	header("Location: index.php");
	else
	{
		if(isset($_GET['akcija'])&&isset($_GET['id']))
		{
			switch($_GET['akcija'])
			{
				case "aktivacija":
				{
					mysql_query("UPDATE baneri SET status='aktivan' WHERE id='".$_GET['id']."'");
					break;
				}
				case "deaktivacija":
				{
					mysql_query("UPDATE baneri SET status='neaktivan' WHERE id='".$_GET['id']."'");
					break;
				}
				case "brisanje":
				{
					$data=mysql_query("SELECT * FROM baneri WHERE id='".$_GET['id']."'");
					if(mysql_num_rows($data)==1)
					{
						$niz=mysql_fetch_assoc($data);
						unlink("baneri/".$niz['lnk']);
						mysql_query("DELETE FROM baneri WHERE id='".$_GET['id']."'");
					}
					break;
				}
			}
		}
		header("Location: manager_banera.php");
	}
?>