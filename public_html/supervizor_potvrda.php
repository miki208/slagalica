<?php
	include "connect.php";
	checkUser();
	if(isset($_SESSION['userName']))
	{
		if(!checkPerm($_SESSION['userName'],"supervizor"))
		{
			header('Location: index.php');
		}
		else
		{
			if(isset($_GET['ok'])&&isset($_GET['id']))
			{
				if($_GET['ok']=="tacno")
				{
					$data=mysql_query("SELECT * FROM buffer_reci WHERE id='".$_GET['id']."'");
					if(mysql_num_rows($data)==1)
					{
						$d=mysql_fetch_assoc($data);
						kreiraj_notifikaciju($d['nick'],"Rec je prihvacena","Supervizor je prihvatio vasu rec ".toReal($d['rec'])." i naknadno Vam je dodeljeno ".$d['bodovi']." bodova.");
						mysql_query("INSERT INTO sve_reci(rec) VALUES('".$d['rec']."')");
						if(strlen($d['rec'])>=7)
						{
							mysql_query("INSERT INTO reci(rec) VALUES('".$d['rec']."')");
						}
						$mikisoftr=mysql_query("SELECT * FROM slagalica_rank WHERE nick='".$d['nick']."'");
						$mikisoftd=mysql_fetch_assoc($mikisoftr);
						mysql_query("UPDATE slagalica_rank SET bodovi='".strval(intval($d['bodovi'])+intval($mikisoftd['bodovi']))."' WHERE nick='".$d['nick']."'");
						mysql_query("UPDATE slagalica_dnevne_liste SET slagalica='".$d['bodovi']."' WHERE nick='".$d['nick']."' AND datum='".$d['datum']."'");
						addExp($d['nick'],intval($d['exp']));
						if($d['dostignuca']!="")
						{
							$dost=explode('*',$d['dostignuca']);
							for($i=0;$i<count($dost);$i++)
							mysql_query("INSERT INTO dostignuca_slagalica(naziv,nick) VALUES('".$dost[$i]."','".$d['nick']."')");
						}
					}
				}
				mysql_query("DELETE FROM buffer_reci WHERE id='".$_GET['id']."'");
			}
		}
	}
	header('Location: supervizor.php');
?>