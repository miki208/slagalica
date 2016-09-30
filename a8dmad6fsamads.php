<?php
	include "connect.php";
	
	if(isset($_POST['phone'])&&isset($_POST['transactionid'])&&isset($_POST['enduserprice'])&&isset($_POST['status'])&&isset($_POST['clientid'])&&isset($_POST['country'])&&isset($_POST['mnocode'])&&isset($_POST['revenue'])&&isset($_POST['amount']))
	{
		$id=$_POST['transactionid'];
		$phone=$_POST['phone'];
		$placeno=$_POST['enduserprice'];
		$status=$_POST['status'];
		$usnm=$_POST['clientid'];
		$drzava=$_POST['country'];
		$kodoper=$_POST['mnocode'];
		$zaradjeno=$_POST['revenue'];
		$kolicina=$_POST['amount'];
		
		if(!dozvoliTransakciju($id))
		{
			http_response_code(406);
			exit;
		}
		
		mysql_query("INSERT INTO transakcije(tid,broj_telefona,placeno,status,nick,drzava,opkod,zaradjeno,kolicina) VALUES('$id','$phone','$placeno','$status','$usnm','$drzava','$kodoper','$zaradjeno','$kolicina')");
		
		if(!isUser($usnm)||$status=="failed")
		{
			if($status=="failed")
			{
				kreiraj_notifikaciju($usnm,"Transakcija neuspesna","Transakcija nije obavljena uspesno. Proverite imate li dovoljno kredita. Ukoliko mislite da je doslo do problema, kontaktirajte nas. Hvala na poverenju!");
			}
			http_response_code(406);
			exit;
		}
		
		add_tokens($usnm,$kolicina);
		kreiraj_notifikaciju($usnm,"Transakcija uspesna","Uspesno uplaceno $kolicina tokena. Hvala Vam na poverenju!");
		http_response_code(200);
		exit;
	}
	http_response_code(406);
?>