<?php
	include "connect.php";
	if(isset($_POST['nick']))
	{
		
		$nick=$_POST['nick'];
		$data=mysql_query("SELECT * FROM pitanja");
		$br=mysql_num_rows($data);
		$data=mysql_query("SELECT * FROM pitanja WHERE id='".strval(rand(1,$br))."'");
		$res=mysql_fetch_assoc($data);
		mysql_query("UPDATE slagalica_sesije SET rec='".$res['odga']."|".$res['odgb']."|".$res['odgc']."|".$res['odgd']."|".$res['pitanje']."|".$res['tacan']."|"."1"."' WHERE nick='$nick' AND igra='koznazna'");
		echo $res['odga']."|".$res['odgb']."|".$res['odgc']."|".$res['odgd']."|".$res['pitanje']."|".$res['kategorija'];
	}
?>