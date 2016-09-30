<?php
include "connect.php";
$tacan=LoadSettings('pogadjanje-brojeva');
			$tacan_br=intval($tacan['opcija']);
			$juce=izracunaj_datum(date("d-m-Y H:i"),-1);
			$tmp=explode(' ',$juce);
			$juce=$tmp[0];
echo $juce;
			$dobitnici=mysql_query("SELECT * FROM mini_igra WHERE datum='$juce' AND broj='$tacan_br'");
			$brdob=mysql_num_rows($dobitnici);
			if($brdob!=0)
			{
				$pot=LoadSettings('mini-igra-total');
				$nagrada=ceil(intval($pot['opcija'])/$brdob);
				while($niz=mysql_fetch_assoc($dobitnici))
				{
					add_tokens($niz['nick'],$nagrada);
					kreiraj_notifikaciju($niz['nick'],'Mini igra',"Broj ".$tacan_br." je bio dobitan, i osvojili ste ".$nagrada." tokena. Cestitamo!");
				}
				SaveSettings('mini-igra-total','0');
			}
			SaveSettings('mini-igra-broj-dobitnika',$brdob);
			mysql_query("DELETE FROM mini_igra WHERE datum='$juce'");
			$random=rand(1,200);
			SaveSettings('pogadjanje-brojeva',$random);
?>