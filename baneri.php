<?php
	include "connect.php";
	$data=mysql_query("SELECT * FROM baneri WHERE status='aktivan' ORDER BY RAND() LIMIT 5");
	$za_slanje="";
	$br=0;
	while($niz=mysql_fetch_assoc($data))
	{
		if($br!=0)
		$za_slanje.="*";
		$za_slanje.=$niz['lnk']."+".$niz['adresa']."+".$niz['title'];
		++$br;
	}
	echo stripslashes($za_slanje);
?>