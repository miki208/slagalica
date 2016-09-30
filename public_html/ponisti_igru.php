<?php
	include "connect.php";
	checkUser();
	if(checkPerm($_SESSION['userName'],"unset_game")&&isset($_GET['nick'])&&isset($_GET['datum'])&&isset($_GET['igre']))
	{
		if(isUser($_GET['nick']))
		{
			$igre=mysql_escape_string($_GET['igre']);
			$nick=mysql_escape_string($_GET['nick']);
			$datum=mysql_escape_string($_GET['datum']);
			$podaci=mysql_query("SELECT $igre, igrana_".$igre." FROM slagalica_dnevne_liste WHERE nick='$nick' AND datum='$datum'");
			if(mysql_num_rows($podaci)==1)
			{
				$read=mysql_fetch_assoc($podaci);
				if(isGame($igre))
				{
					if($read['igrana_'.$igre]!="ne")
					{
						if($igre=="slagalica")
						mysql_query("DELETE FROM buffer_reci WHERE nick='$nick' AND datum='$datum'");
						mysql_query("UPDATE slagalica_dnevne_liste SET igrana_".$igre."='ne', $igre='0' WHERE nick='$nick' AND datum='$datum'");
						mysql_query("UPDATE slagalica_rank SET bodovi=bodovi-'".$read[$igre]."', $igre='01-01-2001' WHERE nick='$nick'");
						header("Location: admin_panel.php?err=resetplay");
					}
					else
					{
						header("Location: admin_panel.php?err=notplay");
					}
				}
				else
				{
					header("Location: admin_panel.php?err=nogame");
				}
			}
			else
			{
				header("Location: admin_panel.php?err=notplay");
			}
		}
		else
		{
			header("Location: admin_panel.php?err=nouser");
		}
	}
	else
	{
		if(isset($_SESSION['userName'])&&isset($_GET['igre']))
		{
			$nick=$_SESSION['userName'];
			$podaci=mysql_query("SELECT * FROM slagalica_dnevne_liste WHERE nick='$nick' AND datum='".date("d-m-Y")."'");
			if(mysql_num_rows($podaci)==1)
			{
				$read=mysql_fetch_assoc($podaci);
				$igre=mysql_escape_string($_GET['igre']);
				if(isGame($igre))
				{
					if(sveOdigrane($nick))
					{
						if(ponisteno($nick))
						{
							if(use_ponistavanje_igre($nick))
							{
								dodajPonistenu($nick);
								if($igre=="slagalica")
									mysql_query("DELETE FROM buffer_reci WHERE nick='$nick' AND datum='".date("d-m-Y")."'");
								mysql_query("UPDATE slagalica_dnevne_liste SET igrana_".$igre."='ne', ".$igre."='0' WHERE nick='$nick' AND datum='".date("d-m-Y")."'");
								mysql_query("UPDATE slagalica_rank SET bodovi=bodovi-'".$read[$igre]."', ".$igre."='01-01-2001' WHERE nick='$nick'");
								header("Location: prodavnica/kovceg.php?status=ok");
							}
							else
							{
								header("Location: prodavnica/kovceg.php?status=none");
							}
						}
						else
						{
							header("Location: prodavnica/kovceg.php?status=maxdel");
						}
					}
					else
					{
						header("Location: prodavnica/kovceg.php?status=notall");
					}
				}
				else
				{
					header("Location: prodavnica/kovceg.php");
				}
			}
			else
			{
				header("Location: prodavnica/kovceg.php?status=nop");
			}
		}
		else
		{
			header("Location: prodavnica/kovceg.php");
		}
	}
?>