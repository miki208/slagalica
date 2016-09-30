<?php
	include "connect.php";
	if(isset($_POST['data']))
	{
		$podaci=explode('|',$_POST['data']);
		mysql_query("INSERT INTO asocijacije SET konacno='".$podaci[0]."', S1='".$podaci[1]."', S2='".$podaci[2]."', S3='".$podaci[3]."', S4='".$podaci[4]."', A1='".$podaci[5]."', A2='".$podaci[6]."', A3='".$podaci[7]."', A4='".$podaci[8]."', B1='".$podaci[9]."', B2='".$podaci[10]."', B3='".$podaci[11]."', B4='".$podaci[12]."', C1='".$podaci[13]."', C2='".$podaci[14]."', C3='".$podaci[15]."', C4='".$podaci[16]."', D1='".$podaci[17]."', D2='".$podaci[18]."', D3='".$podaci[19]."', D4='".$podaci[20]."'");
	}
?>