<?php
	include "connect.php";
	if(isset($_POST['nick']))
	{
		$nick=$_POST['nick'];
		$kom=$_POST['kom'];
		$br=$_POST['br'];
		$data=mysql_query("SELECT * FROM slagalica_sesije WHERE nick='$nick' AND igra='skocko'");
		$res=mysql_fetch_assoc($data);	
		$pom="";
		if($kom==$res['rec'])
		{
			$bodovi=0;
			if(intval($br)<6)
			{
				$bodovi=30;
				addExp($nick,15+floor(intval(get_reputacija($nick))/100));
			}
			else
			{
				if($br==6)
				{
					$bodovi=20;
					addExp($nick,10+floor(intval(get_reputacija($nick))/100));
				}
				else
				{
					$bodovi=10;
					addExp($nick,5+floor(intval(get_reputacija($nick))/100));
				}
			}
			addExp($nick,5+floor(intval(get_reputacija($nick))/100));
		    echo strval($bodovi)."*"."ok";
			$mikisoftr=mysql_query("SELECT * FROM slagalica_rank WHERE nick='$nick'");
			$mikisoftd=mysql_fetch_assoc($mikisoftr);
			/////////////////////////DNEVNA LISTA////////////////////////
			$datum=(isset($_POST['datum']))?$_POST['datum']:date("d-m-Y");
			mysql_query("UPDATE slagalica_dnevne_liste SET skocko='$bodovi' WHERE nick='$nick' AND datum='$datum'");
			/////////////////////////DNEVNA LISTA KRAJ////////////////////////
			mysql_query("UPDATE slagalica_rank SET bodovi='".strval($bodovi+intval($mikisoftd['bodovi']))."' WHERE nick='$nick'");
			mysql_query("DELETE FROM slagalica_sesije WHERE id='".$res['id']."' AND igra='skocko'");
		}
		else
		{
			$kom_t=$res['rec'];
			$t=0;
			$p=0;
			for($i=0;$i<4;$i++)
			{
				if($kom[$i]==$kom_t[$i])
				{
					++$t;
					$kom_t[$i]=' ';
					$kom[$i]='*';
				}
			}
			for($i=0;$i<4;$i++)
			{
				for($y=0;$y<4;$y++)
				{
					if($kom[$i]==$kom_t[$y])
					{
						++$p;
						$kom_t[$y]=' ';
						$kom[$i]='*';
						break;
					}
				}
			}
			for($i=0;$i<$t;$i++)
			$pom.="Y";
			for($i=0;$i<$p;$i++)
			$pom.="N";
			while(strlen($pom)!=4)
			$pom.="U";
			echo $pom."*no";
		}
	}
?>