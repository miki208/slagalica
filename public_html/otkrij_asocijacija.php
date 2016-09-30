<?php
	include "asocijacije_lib.php";
	if(isset($_POST['nick']))
	{
		$nick=$_POST['nick'];
		$d=$_POST['polje'];
		$data=mysql_query("SELECT * FROM slagalica_sesije WHERE nick='$nick' AND igra='asocijacije'");
		$res=mysql_fetch_assoc($data);
		$podaci=explode('*',$res['rec']);
		$podaci[21]=strval(intval($podaci[21])+1);
		$zabazu=join("*",$podaci);
		mysql_query("UPDATE slagalica_sesije SET rec='$zabazu' WHERE nick='$nick' AND igra='asocijacije'");//konacno resenje,broj otvorenih polja,dodatni poeni
		switch($d[0])
		{
			case 'A':
			$id=intval($d[1])+4;
			break;
			case 'B':
			$id=intval($d[1])+8;
			break;
			case 'C':
			$id=intval($d[1])+12;
			break;
			case 'D':
			$id=intval($d[1])+16;
			break;
		}
		echo pojam($podaci[$id]);
	}
?>