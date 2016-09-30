<?php
	include "connect.php";
	$data="";
	$br=LoadSettings("buffer-obavestenja");
	$dt=mysql_query("SELECT * FROM obavestenja WHERE status='aktivan' ORDER BY id DESC LIMIT ".$br['opcija']);
	while($niz=mysql_fetch_assoc($dt))
	{
    	if($data!="")
		{
		$data.="#<456*456>#";
		}
		$data.="[".$niz['nick']." - ".$niz['datum']."]&#10;".$niz['stavka'];
	}
	echo stripslashes($data);
?>