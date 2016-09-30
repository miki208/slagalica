<?php

	/*
	Naziv fajla: obrisi.php
	Namena: Brise konverzaciju sa zadatim ID-om
	Autor: Milos Samardzija
	Eksterne datoteke: ../connect.php
	Tabele kojima pristupa: MSG_INFO, MSG_TXT
	*/

	include "../connect.php";
	checkUser();//da li je korisnik ulogovan?
	if(isset($_SESSION['userName'])&&isset($_GET['id']))//da li je setovan id poruke za brisanje?
	{
		$nick=$_SESSION['userName'];//cuvanje nicka korisnika
		$id=mysql_escape_string($_GET['id']);
		if(mysql_num_rows(mysql_query("SELECT * FROM MSG_INFO WHERE ID='$id'"))==1)// da li poruka sa datim ID-om postoji?
		{
			if(mysql_num_rows(mysql_query("SELECT * FROM MSG_INFO WHERE AUTOR='$nick' AND ID='$id'"))==1)//da li smo autor date poruke?
			{
				mysql_query("UPDATE MSG_INFO SET AUT_STT='sakriveno' WHERE ID='$id'");//status poruke za autora je sakriven
				mysql_query("UPDATE MSG_TXT SET AUT_OBR='da' WHERE MSG_ID='$id' AND AUT_OBR='ne'");//markiranje svih poruka koje je obrisao autor
			}
			if(mysql_num_rows(mysql_query("SELECT * FROM MSG_INFO WHERE PRIM='$nick' AND ID='$id'"))==1)//da li smo primalac date poruke?
			{
				mysql_query("UPDATE MSG_INFO SET PRIM_STT='sakriveno' WHERE ID='$id'");//status poruke za primaoca je sakriven
				mysql_query("UPDATE MSG_TXT SET PRIM_OBR='da' WHERE MSG_ID='$id' AND PRIM_OBR='ne'");//markiranje svih poruka koje je obrisao primalac
			}
			mysql_query("DELETE FROM MSG_TXT WHERE MSG_ID='$id' AND PRIM_OBR='da' AND AUT_OBR='da'");//ako je i primaocu i autoru obrisana poruka, ukloni iz baze
			if(mysql_num_rows(mysql_query("SELECT * FROM MSG_TXT WHERE MSG_ID='$id' AND (PRIM_OBR='ne' OR AUT_OBR='ne')"))==0)//ako su sve poruke iz date konverzacije obrisane i autoru i primaocu, ukloni iz baze
			{
				mysql_query("DELETE FROM MSG_INFO WHERE ID='$id'");
			}
		}
		header("Location: index.php");//vracanje u inbox
	}
?>