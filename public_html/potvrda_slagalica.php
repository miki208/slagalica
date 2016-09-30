<?php
	include "connect.php";
	$datum=(isset($_POST['datum']))?$_POST['datum']:date("d-m-Y");
	if(isset($_POST['nick']))
	{
		$nick=$_POST['nick'];
		$rec=$_POST['rec'];
		
		addExp($nick,5+floor(intval(get_reputacija($nick))/100));
		$data=mysql_query("SELECT * FROM slagalica_sesije WHERE nick='$nick' AND igra='slagalica'");
		$res=mysql_fetch_assoc($data);
		$komp=$res['rec'];
		$upit=mysql_query("SELECT * FROM sve_reci WHERE rec='$rec'");
		$valid="netacno";
		$bodovi=0;
		$mnoz=round(30/strlen($komp),2);
		$nick123=array();
		$naziv123=array();
		$prazan=1;
		$dostignuca_data=mysql_query("SELECT * FROM dostignuca_slagalica WHERE nick='".$nick."'");
		$dostignuca=array();
		mysql_query("DELETE FROM buffer_reci WHERE nick='$nick' AND datum='$datum'");
		while($dostignuca=mysql_fetch_assoc($dostignuca_data))
		{
			$prazan=0;
			array_push($naziv123,$dostignuca['naziv']);
			array_push($nick123,$dostignuca['nick']);
		}
		if(mysql_num_rows($upit)!=0)
		{
			
			$valid="tacno";
			addExp($nick,strlen($rec)*2+floor(intval(get_reputacija($nick))/100));
			$bodovi=round(strlen($rec)*$mnoz);
			if(strlen($rec)>strlen($komp))
			$bodovi+=3;
			$mikisoftr=mysql_query("SELECT * FROM slagalica_rank WHERE nick='$nick'");
			$mikisoftd=mysql_fetch_assoc($mikisoftr);
			/////////////////////////DNEVNA LISTA////////////////////////
			$datum=(isset($_POST['datum']))?$_POST['datum']:date("d-m-Y");
			mysql_query("UPDATE slagalica_dnevne_liste SET slagalica='$bodovi' WHERE nick='$nick' AND datum='$datum'");
			/////////////////////////DNEVNA LISTA KRAJ////////////////////////
			mysql_query("UPDATE slagalica_rank SET bodovi='".strval($bodovi+intval($mikisoftd['bodovi']))."' WHERE nick='$nick'");
			
			////////////////////////DOSTIGNUCA Citac Misli////////////////////////////////
			if($rec==$komp)
			{
				if(in_array("citac_misli",$naziv123)==false)
				mysql_query("INSERT INTO dostignuca_slagalica(naziv,nick) VALUES('citac_misli','$nick')");
				addExp($nick,30+floor(intval(get_reputacija($nick))/100));
			}
			////////////////////////DOSTIGNUCA KRAJ////////////////////////////////
			////////////////////////DOSTIGNUCA Klikerko////////////////////////////////
			if(strlen($rec)>strlen($komp))
			{
				if(in_array("klikerko",$naziv123)==false)
				mysql_query("INSERT INTO dostignuca_slagalica(naziv,nick) VALUES('klikerko','$nick')");
				addExp($nick,80+floor(intval(get_reputacija($nick))/100));
			}
			////////////////////////DOSTIGNUCA KRAJ////////////////////////////////
		}
		else
		{
			$tempb=0;
			$tempe=0;
			$tempd="";
			if(strlen($rec)>strlen($komp))
			{
				$tempb+=3;
				if(in_array("klikerko",$naziv123)==false)
				{
					if($tempd!="")
					$tempd.="*";
					$tempd.="klikerko";
				}
				$tempe+=80+floor(intval(get_reputacija($nick))/100);
			}
			else if(strlen($rec)==strlen($komp))
			{
				if((in_array("citac_misli",$naziv123)==false)&&($rec==$komp))
				{
					if($tempd!="")
					$tempd.="*";
					$tempd.="citac_misli";
					$tempe+=30+floor(intval(get_reputacija($nick))/100);
				}
			}
			$tempe+=strlen($rec)*2+floor(intval(get_reputacija($nick))/100);
			$tempb+=round(strlen($rec)*$mnoz);
			mysql_query("INSERT INTO buffer_reci(nick,rec,datum,bodovi,exp,dostignuca) VALUES('$nick','$rec','$datum','$tempb','$tempe','$tempd')");
			$idrec=mysql_query("SELECT id FROM buffer_reci WHERE nick='$nick' AND datum='$datum' AND rec='$rec'");
			$getid=mysql_fetch_assoc($idrec);
			kreiraj_notifikaciju($nick,"Slagalica-rec prosledjena supervizoru","Vasa rec ".toReal($rec)." je prosledjena supervizoru i ukoliko je tacna osvojicete ".$tempb." bodova. Vasa rec ce biti prihvacena u roku od 48h i manje. Ukoliko hocete, mozete dodati termin u kojem se vasa rec koristi: <a href=\"../opis_supervizor.php?id=".$getid['id']."\" style=\"color:blue;\">termin</a>");
		}
		echo $valid."*".$komp."*".strval($bodovi);
		mysql_query("DELETE FROM slagalica_sesije WHERE id='".$res['id']."' AND igra='slagalica'");	
	}
?>