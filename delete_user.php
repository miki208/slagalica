<?php
	include "connect.php";
	checkUser();
	if(isset($_GET['nick'])&&checkPerm($_SESSION['userName'],"delete_user"))
	{
		$data=$_GET['nick'];
		if(isUser($data))
		{
			if($data!="Admin")
			{
				if(!checkPerm($data,"imunitet"))
				{
					$lista_data=mysql_query("SELECT profilna FROM lista WHERE username='".$data."'");
					$lista_niz=mysql_fetch_assoc($lista_data);
					if($lista_niz['profilna']!="def/unknown.jpg")
					unlink("profilne/".$lista_niz['profilna']);
					mysql_query("DELETE FROM dostignuca_slagalica WHERE nick='".$data."'");
					mysql_query("DELETE FROM lista WHERE username='".$data."'");
					mysql_query("DELETE FROM slagalica_dnevne_liste WHERE nick='".$data."'");
					mysql_query("DELETE FROM slagalica_rank WHERE nick='".$data."'");
					mysql_query("DELETE FROM slagalica_sesije WHERE nick='".$data."'");
					mysql_query("DELETE FROM sumnjivi_korisnici WHERE nick='".$data."'");
					mysql_query("DELETE FROM admini WHERE nick='".$data."'");
					mysql_query("DELETE FROM poeni WHERE nick='".$data."'");
					mysql_query("DELETE FROM notifikacije WHERE nick='".$data."'");
					mysql_query("DELETE FROM statusi WHERE nick='".$data."'");
					header("Location: admin_panel.php?err=scsuserdel");
				}
				else
				{
					header("Location: admin_panel.php?err=noimdel");
				}
			}
			else
			{
				header("Location: admin_panel.php?err=noadmdel");
			}
		}
		else
		{
			header("Location: admin_panel.php?err=nouser");
		}
	}
	else
	{
		header("Location: index.php");
	}
?>