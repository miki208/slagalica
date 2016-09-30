<?php
	include "connect.php";
	checkUser();
	if($_SESSION['userName']=="Admin")
	{
		if(isset($_GET['nick'])&&isset($_GET['perms']))
		{
			if(isUser($_GET['nick'])){
			$dodato=false;
			$prazno=true;
			$a=mysql_query("SELECT * FROM admini WHERE nick='".$_GET['nick']."'");
			if(mysql_num_rows($a)!=1)
			mysql_query("INSERT INTO admini(nick,ovlascenje) VALUES('".$_GET['nick']."','')");
			$a=mysql_query("SELECT * FROM admini WHERE nick='".$_GET['nick']."'");
			$b=mysql_fetch_assoc($a);
			if($b['ovlascenje']!="")
			$prazno=false;
			$data=explode(",",$_GET['perms']);
			$zabazu="";
			$notif="Od strane administratora Vam je dodeljeno administratorsko pravo: ";
			$notbol=false;
			for($i=0;$i<count($data);$i++)
			{
				if((isPerm($data[$i])==true)&&(checkPerm($_GET['nick'],$data[$i])==false))
				{
					if($prazno==false)
					{
						$zabazu.="*";
					}
					else
					{
						$prazno=false;
					}
					$zabazu.=$data[$i];
					if($notbol!=false)
					$notif.=", ";
					$notif.=$data[$i];
					$notbol=true;
					$dodato=true;
				}
			}
			$notif.=".";
			if($notbol==true)
			kreiraj_notifikaciju($_GET['nick'],"Administratorsko ovlascenje dodeljeno",$notif);
			$c=mysql_query("SELECT * FROM admini WHERE nick='".$_GET['nick']."'");
			$d=mysql_fetch_assoc($c);
			mysql_query("UPDATE admini SET ovlascenje='".$d['ovlascenje'].$zabazu."' WHERE nick='".$_GET['nick']."'");
			if($dodato)
			header("Location: admin_panel.php?err=scsaddperm");
			else
			header("Location: admin_panel.php?err=unscsaddperm");
			}//isuser
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