<?php
    include "../connect.php";
	if(isset($_SESSION['userName']))
	{
		if($_SESSION['userName']=="Admin"&&isset($_GET['nick']))
		{
			echo "http://mikisoft-slagalica.in.rs/fbreg.php?email=&usnm=".kript($_GET['nick'],'23052983742');
		}
		else
		{
			header("Location: ../index.php");
		}
	}
	else
	header("Location: ../login.php");
?>