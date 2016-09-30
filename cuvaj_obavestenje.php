<?php
	include "connect.php";
	checkUser();
	if(!checkPerm($_SESSION['userName'],"info_add"))
	header("Location: index.php");
	else
	{
		if(isset($_POST['tekst']))
		{
			$nick=$_SESSION['userName'];
			$stavka=mysql_escape_string($_POST['tekst']);
			$datum=date("d/m/Y H:i");
			mysql_query("INSERT INTO obavestenja(nick,stavka,datum,status) VALUES('$nick','$stavka','$datum','aktivan')");
		}
	}
	header("Location: dodaj_obavestenje.php");
?>