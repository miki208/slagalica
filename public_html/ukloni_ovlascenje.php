<?php
	include "connect.php";
	checkUser();
	if($_SESSION['userName']=="Admin")
	{
		if(isset($_GET['nick'])&&isset($_GET['perms']))
		{
			if(isUser($_GET['nick']))
			{
				$data=mysql_query("SELECT * FROM admini WHERE nick='".$_GET['nick']."'");
				if(mysql_num_rows($data)==1)
				{
					$obrisano=false;
					$notif="Od strane administratora Vam je uklonjeno administratorsko pravo: ";
					$notbol=false;
					$data1=mysql_fetch_assoc($data);
					$perms_server=explode("*",$data1['ovlascenje']);
					$perms_cmd=explode(",",$_GET['perms']);
					for($i=0;$i<count($perms_cmd);$i++)
					{
						for($x=0;$x<count($perms_server);$x++)
						{
							if($perms_cmd[$i]==$perms_server[$x])
							{
								if($notbol!=false)
								$notif.=", ";
								$notif.=$perms_server[$x];
								$notbol=true;
								unset($perms_server[$x]);
								$obrisano=true;
								$perms_server=array_values($perms_server);
								break;
							}
						}
					}
					$notif.=".";
					if($notbol==true)
					kreiraj_notifikaciju($_GET['nick'],"Administratorsko ovlascenje uklonjeno",$notif);
					if(count($perms_server)==0)
					{
						mysql_query("DELETE FROM admini WHERE nick='".$_GET['nick']."'");
					}
					else
					{
						$zabazu="";
						for($i=0;$i<count($perms_server);$i++)
						{
							if($zabazu!="")
							$zabazu.="*";
							$zabazu.=$perms_server[$i];
						}
						mysql_query("UPDATE admini SET ovlascenje='".$zabazu."' WHERE nick='".$_GET['nick']."'");
					}
					if($obrisano)
					{
						header("Location: admin_panel.php?err=scsdelperm");
					}
					else
					{
						header("Location: admin_panel.php?err=unscsdelperm");
					}
				}
				else
				{
					header("Location: admin_panel.php?err=noadm");
				}
			}
			else
			{
				header("Location: admin_panel.php?err=nouser");
			}
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