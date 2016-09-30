<?php

	/*
	Naziv fajla: posalji_poruku.php
	Namena: Sluzi za slanje poruka, i odgovore na njih, uz detekciju gresaka prilikom slanja
	Autor: Milos Samardzija
	Eksterne datoteke: ../connect.php
	Tabele kojima pristupa: MSG_INFO, MSG_TXT, lista
	*/

	include "../connect.php";
	date_default_timezone_set("Europe/Belgrade");
	checkUser();
	
	if(isset($_GET['tip'])&&isset($_SESSION['userName']))
	{
		$_POST['poruka']=str_replace(">","&#8250;",$_POST['poruka']);//sprecavanje umetanja HTML koda
		$_POST['poruka']=str_replace("<","&#8249;",$_POST['poruka']);//sprecavanje umetanja HTML koda
		if($_GET['tip']=="odgovor"&&isset($_GET['id']))//odgovor na poruku
		{
			$id=mysql_escape_string($_GET['id']);
			$poruka=mysql_escape_string($_POST['poruka']);
			$pos=$_SESSION['userName'];
			$datum=date("d-m-Y H:i");
			if((mysql_num_rows(mysql_query("SELECT * FROM MSG_INFO WHERE ID='$id'"))==1)&&(mysql_num_rows(mysql_query("SELECT * FROM MSG_INFO WHERE (PRIM='$pos' OR AUTOR='$pos') AND ID='$id'"))==1)&&(strlen($_POST['poruka'])<=400)&&(strlen($_POST['poruka'])>0))//da li smo primalac ili autor, i da li je poruka u redu?
			{
				if(mysql_num_rows(mysql_query("SELECT * FROM MSG_INFO WHERE AUTOR='$pos' AND ID='$id'"))==1)//ako smo autor, setujemo primaocu status na neprocitano, i obrnuto
				mysql_query("UPDATE MSG_INFO SET PRIM_STT='nije_procitano' WHERE ID='$id'");
				else
				mysql_query("UPDATE MSG_INFO SET AUT_STT='nije_procitano' WHERE ID='$id'");
				mysql_query("INSERT INTO MSG_TXT(MSG_ID,TXT,POS,DAT) VALUES('$id','$poruka','$pos','$datum')");//dodavanje poruke u bazu podataka
				header("Location: poruka.php?error=no&id=".$_GET['id']);
			}
			else
			{
				$err_text="";
				if(mysql_num_rows(mysql_query("SELECT * FROM MSG_INFO WHERE ID='$id'"))==0)//nepoznata poruka
				{
					if($err_text!="")
					$err_text.="_";
					$err_text.="unkmsg";
				}
				if(mysql_num_rows(mysql_query("SELECT * FROM MSG_INFO WHERE (PRIM='$pos' OR AUTOR='$pos') AND ID='$id'"))==0)//nemamo dozvolu za odgovor na poruku
				{
					if($err_text!="")
					$err_text.="_";
					$err_text.="noperm";
				}
				if(strlen($_POST['poruka'])>400)//premasenje max broja karaktera
				{
					if($err_text!="")
					$err_text.="_";
					$err_text.="max";
				}
				if(strlen($_POST['poruka'])==0)//prazna poruka
				{
					if($err_text!="")
					$err_text.="_";
					$err_text.="none";
				}
				header("Location: index.php?error=yes&err_type=odg&errtxt=$err_text&id=".$_GET['id']);
			}
		}
		if($_GET['tip']=="nova")//slanje nove poruke
		{
			$poruka=mysql_escape_string($_POST['poruka']);
			$pos=$_SESSION['userName'];
			$datum=date("d-m-Y H:i");
			$primaoci=explode(";",mysql_escape_string($_POST['primaoci']));//u slucaju vise primaoca, rasclanjuju se nickovi
			$predmet=mysql_escape_string($_POST['predmet']);
			if((strlen($_POST['poruka'])<=400)&&(strlen($_POST['poruka'])>0)&&($predmet!=""))//svi zahtevi poruke zadovoljeni
			{
				$err_buf="";
				foreach($primaoci as $primaoc)
				{
					if((mysql_num_rows(mysql_query("SELECT * FROM lista WHERE username='$primaoc'"))==1)&&($primaoc!=$pos))//da li je primalac validan?
					{
						$key=md5(uniqid(rand(),true));//generisanje nasumicnog kljuca kako bi svaka konverzacija bila jedinstvena
						mysql_query("INSERT INTO MSG_INFO(asd,AUTOR,PRIM,AUT_STT,PRIM_STT,SUB,DAT) VALUES('$key','$pos','$primaoc','procitano','nije_procitano','$predmet','$datum')");//dodavanje informacija o konverzaciji u bazu
						$data=mysql_query("SELECT ID FROM MSG_INFO WHERE asd='$key' AND DAT='$datum' AND AUTOR='$pos' AND PRIM='$primaoc'");//pretraga informacija o konverzacijama kako bi izvukli ID zapocete konverzacije, uz pretragu poruka koristimo i generisani kljuc kako bi eliminisali mogucnost podudaranja konverzacija na osnovu zadatih parametara
						$niz=mysql_fetch_assoc($data);
						$id=$niz['ID'];
						mysql_query("INSERT INTO MSG_TXT(MSG_ID,TXT,POS,DAT) VALUES('$id','$poruka','$pos','$datum')");//popuna sadrzaja za poruke
					}
					else
					{
						if(mysql_num_rows(mysql_query("SELECT * FROM lista WHERE username='$primaoc'"))==0)//dati nick ne postoji
						{
							if($err_buf!="")
							$err_buf.="_";
							$err_buf.="prim*".$primaoc;
						}
						if($primaoc==$pos)//zabranjeno slanje poruka samom sebi
						{
							if($err_buf!="")
							$err_buf.="_";
							$err_buf.="auto";
						}
					}
				}
				$err="no";//nema gresaka
				if($err_buf!="")//da li je doslo do gresaka?
				{
					$err="yes&err_type=slanje&errtxt=$err_buf";
				}
				header("Location: index.php?error=$err");
			}
			else
			{
				if(strlen($_POST['poruka'])>400)//previse karaktera
				{
					if($err_text!="")
					$err_text.="_";
					$err_text.="max";
				}
				if(strlen($_POST['poruka'])==0)//prazna poruka
				{
					if($err_text!="")
					$err_text.="_";
					$err_text.="none";
				}
				if($predmet=="")//nema predmeta
				{
					if($err_text!="")
					$err_text.="_";
					$err_text.="sub";
				}
				header("Location: index.php?error=yes&err_type=poruka&errtxt=$err_text");
			}
		}
		if($_GET['tip']=="odgovor"&&!isset($_GET['id']))//ako ID nije setovan prilikom odgovora, dolazi do greske
		{
			header("Location: index.php?error=yes&err_type=odg&errtxt=unkmsg");
		}
	}
?>