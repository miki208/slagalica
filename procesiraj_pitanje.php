<?php
	include "connect.php";
	if(isset($_POST['nick']))
	{
		$nick=$_POST['nick'];
		$odg=$_POST['odg'];
		$data=mysql_query("SELECT * FROM slagalica_sesije WHERE nick='$nick' AND igra='koznazna'");
		$res=mysql_fetch_assoc($data);
		$podaci=explode("|",$res['rec']);
		$bodovi=0;
		$tn="";
		$kraj="ok";
		if($odg==$podaci[5])
		{
			$bodovi=3;
			addExp($nick,3+floor(intval(get_reputacija($nick))/100));
			$tn="t";
		}
		else
		{
			
			$tn="n";
			if($odg=="x")
			$bodovi=0;
			else
			$bodovi=-1;
		}
		$a=intval($podaci[6]);
		++$a;
		$podaci[6]=strval($a);
		$data1=mysql_query("SELECT * FROM pitanja");
		$br1=mysql_num_rows($data1);
		$data1=mysql_query("SELECT * FROM pitanja WHERE id='".strval(rand(1,$br1))."'");
		$res1=mysql_fetch_assoc($data1);
		if($podaci[6]=="11")
		{
			addExp($nick,5+floor(intval(get_reputacija($nick))/100));
			$kraj="kraj";
			echo " "."|"." "."|"." "."|"." "."|"." "."|".$kraj."|".$podaci[5]."|".$tn."|".strval($bodovi)."| ";
			mysql_query("DELETE FROM slagalica_sesije WHERE id='".$res['id']."' AND igra='koznazna'");
		}
		else
		{
			mysql_query("UPDATE slagalica_sesije SET rec='".$res1['odga']."|".$res1['odgb']."|".$res1['odgc']."|".$res1['odgd']."|".$res1['pitanje']."|".$res1['tacan']."|".$podaci[6]."' WHERE nick='$nick' AND igra='koznazna'");
			echo $res1['odga']."|".$res1['odgb']."|".$res1['odgc']."|".$res1['odgd']."|".$res1['pitanje']."|".$kraj."|".$podaci[5]."|".$tn."|".strval($bodovi)."|".$res1['kategorija'];
		}
		$mikisoftr=mysql_query("SELECT * FROM slagalica_rank WHERE nick='$nick'");
		$mikisoftd=mysql_fetch_assoc($mikisoftr);
		/////////////////////////DNEVNA LISTA////////////////////////
		$datum=(isset($_POST['datum']))?$_POST['datum']:date("d-m-Y");
		$blad=mysql_query("SELECT * FROM slagalica_dnevne_liste WHERE nick='$nick' AND datum='$datum'");
		$bla=mysql_fetch_assoc($blad);
		mysql_query("UPDATE slagalica_dnevne_liste SET koznazna='".strval($bodovi+intval($bla['koznazna']))."' WHERE nick='$nick' AND datum='$datum'");
		/////////////////////////DNEVNA LISTA KRAJ////////////////////////
		mysql_query("UPDATE slagalica_rank SET bodovi='".strval($bodovi+intval($mikisoftd['bodovi']))."' WHERE nick='$nick'");
	}
?>