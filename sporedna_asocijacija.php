<?php
	include "asocijacije_lib.php";
	if(isset($_POST['nick']))
	{
		$nick=$_POST['nick'];
		$polje=$_POST['polje'];//naziv polja
		$as=$_POST['as'];//input
		$data=mysql_query("SELECT * FROM slagalica_sesije WHERE nick='$nick' AND igra='asocijacije'");
		$res=mysql_fetch_assoc($data);
		$podaci=explode('*',$res['rec']);
		$tacno=false;
		switch($polje)
		{
			case "S1":
			if(proveri_resenje(pojam($podaci[5]),pojam($podaci[6]),pojam($podaci[7]),pojam($podaci[8]),$as,'rk-po'))
			{
				$tacno=true;
				echo "ok"."*".pojam($podaci[1])."*".pojam($podaci[5])."*".pojam($podaci[6])."*".pojam($podaci[7])."*".pojam($podaci[8]);
			}
			else
			{
				echo "no"."*"."xD";
			}
			break;
			case "S2":
			if(proveri_resenje(pojam($podaci[9]),pojam($podaci[10]),pojam($podaci[11]),pojam($podaci[12]),$as,'rk-po'))
			{
				$tacno=true;
				echo "ok"."*".pojam($podaci[2])."*".pojam($podaci[9])."*".pojam($podaci[10])."*".pojam($podaci[11])."*".pojam($podaci[12]);
			}
			else
			{
				echo "no"."*"."xD";
			}
			break;
			case "S3":
			if(proveri_resenje(pojam($podaci[13]),pojam($podaci[14]),pojam($podaci[15]),pojam($podaci[16]),$as,'rk-po'))
			{
				$tacno=true;
				echo "ok"."*".pojam($podaci[3])."*".pojam($podaci[13])."*".pojam($podaci[14])."*".pojam($podaci[15])."*".pojam($podaci[16]);
			}
			else
			{
				echo "no"."*"."xD";
			}
			break;
			case "S4":
			if(proveri_resenje(pojam($podaci[17]),pojam($podaci[18]),pojam($podaci[19]),pojam($podaci[20]),$as,'rk-po'))
			{
				$tacno=true;
				echo "ok"."*".pojam($podaci[4])."*".pojam($podaci[17])."*".pojam($podaci[18])."*".pojam($podaci[19])."*".pojam($podaci[20]);
			}
			else
			{
				echo "no"."*"."xD";
			}
			break;
		}
		if($tacno)
		{
			$podaci[22]=strval(intval($podaci[22])+1);
			$zabazu=join("*",$podaci);
			mysql_query("UPDATE slagalica_sesije SET rec='$zabazu' WHERE nick='$nick' AND igra='asocijacije'");
		}
	}
?>