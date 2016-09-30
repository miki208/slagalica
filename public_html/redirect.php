<?php
	if(isset($_GET['pristup']))
	{
		switch($_GET['pristup'])
		{
			case "Dodavanje banera";
			header("Location: dodaj_baner.php");
			break;
			
			case "Menadzer banera";
			header("Location: manager_banera.php");
			break;
			
			case "Dodavanje obavestenja";
			header("Location: dodaj_obavestenje.php");
			break;
			
			case "Menadzer obavestenja";
			header("Location: manager_obavestenja.php");
			break;
			
			case "Dodavanje asocijacije";
			header("Location: ubaci_asocijacije.php");
			break;
			
			case "Pregled korisnika";
			header("Location: view_users.php");
			break;
			
			case "Supervizor";
			header("Location: supervizor.php");
			break;
			
			case "Statistika";
			header("Location: statistika.php");
			break;
			
			case "Edit pitanja";
			header("Location: edit_pitanja.php");
			break;
			
			case "Korisnicki invertari";
			header("Location: prodavnica/izmena.php");
			break;
			
			case "Edit promocija";
			header("Location: prodavnica/promo_edit.php");
			break;
			
			default:
			header("Location: index.php");
			break;
		}
	}
	else
	{
		header("Location: index.php");
	}
?>