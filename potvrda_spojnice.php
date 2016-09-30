<?php
	include "connect.php";
	if(isset($_POST['nick']))
	{
		$nick=$_POST['nick'];
		addExp($nick,5+floor(intval(get_reputacija($nick))/100));
		$d=$_POST['data'];
		$data=mysql_query("SELECT * FROM slagalica_sesije WHERE nick='$nick' AND igra='spojnice'");
		$res=mysql_fetch_assoc($data);
		$splitovano=explode('=',$res['rec']);
		$rows_srv=explode('*',$splitovano[1]);
		$rows_cl=explode('*',$d);
		$bodovi=0;
		for($x=0;$x<count($rows_srv);$x++)
		{
			for($y=0;$y<count($rows_cl);$y++)
			{
				if($rows_srv[$x]==$rows_cl[$y])
				{
					$bodovi+=4;
					break;
				}
			}
		}
		addExp($nick,$bodovi+floor(intval(get_reputacija($nick))/100));
		echo strval($bodovi)."=".$splitovano[1];
		$mikisoftr=mysql_query("SELECT * FROM slagalica_rank WHERE nick='$nick'");
		$mikisoftd=mysql_fetch_assoc($mikisoftr);
		/////////////////////////DNEVNA LISTA////////////////////////
		$datum=(isset($_POST['datum']))?$_POST['datum']:date("d-m-Y");
		mysql_query("UPDATE slagalica_dnevne_liste SET spojnice='$bodovi' WHERE nick='$nick' AND datum='$datum'");
		/////////////////////////DNEVNA LISTA KRAJ////////////////////////
		mysql_query("UPDATE slagalica_rank SET bodovi='".strval($bodovi+intval($mikisoftd['bodovi']))."' WHERE nick='$nick'");
		mysql_query("DELETE FROM slagalica_sesije WHERE id='".$res['id']."' AND igra='spojnice'");	
	}
?>