<?php
	include "asocijacije_lib.php";
	checkUser();
	if($_SESSION['userName']=="Admin")
	{
		parsiraj_fajl("import_skripte/asocijacije.txt");
	}
	header("Location: index.php");
?>