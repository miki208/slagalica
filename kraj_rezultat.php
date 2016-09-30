<?php
	include "asocijacije_lib.php";
	if(isset($_POST['nick']))
	{
		$nick=$_POST['nick'];
		$data=mysql_query("SELECT * FROM slagalica_sesije WHERE nick='$nick' AND igra='asocijacije'");
		$res=mysql_fetch_assoc($data);
		$podaci=explode('*',$res['rec']);
		$bodovi=intval($podaci[22])*5;
		$mikisoftr=mysql_query("SELECT * FROM slagalica_rank WHERE nick='$nick'");
		$mikisoftd=mysql_fetch_assoc($mikisoftr);
		/////////////////////////DNEVNA LISTA////////////////////////
		$datum=(isset($_POST['datum']))?$_POST['datum']:date("d-m-Y");
		addExp($nick,$bodovi+floor(intval(get_reputacija($nick))/100));
		mysql_query("UPDATE slagalica_dnevne_liste SET asocijacije='$bodovi' WHERE nick='$nick' AND datum='$datum'");
		/////////////////////////DNEVNA LISTA KRAJ////////////////////////
		mysql_query("UPDATE slagalica_rank SET bodovi='".strval($bodovi+intval($mikisoftd['bodovi']))."' WHERE nick='$nick'");
		mysql_query("DELETE FROM slagalica_sesije WHERE id='".$res['id']."' AND igra='asocijacije'");
		$resenja=pojam($podaci[5]).";".pojam($podaci[6]).";".pojam($podaci[7]).";".pojam($podaci[8]).";".pojam($podaci[9]).";".pojam($podaci[10]).";".pojam($podaci[11]).";".pojam($podaci[12]).";".pojam($podaci[13]).";".pojam($podaci[14]).";".pojam($podaci[15]).";".pojam($podaci[16]).";".pojam($podaci[17]).";".pojam($podaci[18]).";".pojam($podaci[19]).";".pojam($podaci[20]).";".pojam($podaci[1]).";".pojam($podaci[2]).";".pojam($podaci[3]).";".pojam($podaci[4]).";".pojam($podaci[0]);
		echo strval($bodovi)."*".$resenja;
	}
?>