<?php
	include "../connect.php";
	checkUser();
	if(isset($_SESSION['userName']))
	{
		$nick=$_SESSION['userName'];
		$cena=0;
		$pi=0;
		$exp=0;
		$rep=0;
		$start=0;
		$extra=0;
		$premium=0;
		$smart=0;
		$legendary=0;
		$vip=0;
		$kljuc=0;
		if(!is_numeric($_POST['pi']))
		$pi=0;
		else
		$pi=intval($_POST['pi']);
		
		if(!is_numeric($_POST['exp']))
		$exp=0;
		else
		$exp=intval($_POST['exp']);
		
		if(!is_numeric($_POST['rep']))
		$rep=0;
		else
		$rep=intval($_POST['rep']);
		
		if(!is_numeric($_POST['start']))
		$start=0;
		else
		$start=intval($_POST['start']);
		
		if(!is_numeric($_POST['extra']))
		$extra=0;
		else
		$extra=intval($_POST['extra']);
		
		if(!is_numeric($_POST['premium']))
		$premium=0;
		else
		$premium=intval($_POST['premium']);
		
		if(!is_numeric($_POST['smart']))
		$smart=0;
		else
		$smart=intval($_POST['smart']);
		
		if(!is_numeric($_POST['legendary']))
		$legendary=0;
		else
		$legendary=intval($_POST['legendary']);
		
		if(!is_numeric($_POST['vip']))
		$vip=0;
		else
		$vip=intval($_POST['vip']);
		
		if(!is_numeric($_POST['kljuc']))
		$kljuc=0;
		else
		$kljuc=intval($_POST['kljuc']);
		
		$cena=$pi*95+$exp*3+$rep*20+$start*380+$extra*1350+$premium*3500+$smart*850+$legendary*2500+$vip*1100+$kljuc*250;;
		
		if($cena>0)
		{
			if(use_tokens($nick,$cena)==true)
			{
				if($pi!=0)
				{
					add_ponistavanje_igre($nick,$pi);
				}
				if($exp!=0)
				{
					addExp($nick,$exp);
				}
				if($rep!=0)
				{
					add_reputacija($nick,$rep);
				}
				if($start!=0)
				{
					addExp($nick,$start*50);
					add_reputacija($nick,$start*5);
					add_ponistavanje_igre($nick,$start*2);
				}
				if($extra!=0)
				{
					addExp($nick,$extra*200);
					add_reputacija($nick,$extra*20);
					add_ponistavanje_igre($nick,$extra*5);
				}
				if($premium!=0)
				{
					addExp($nick,$premium*500);
					add_reputacija($nick,$premium*50);
					add_ponistavanje_igre($nick,$premium*15);
				}
				if($smart!=0)
				{
					add_ponistavanje_igre($nick,$smart*10);
				}
				if($legendary!=0)
				{
					addExp($nick,$legendary*1000);
				}
				if($vip!=0)
				{
					add_reputacija($nick,$vip*70);
				}
				if($kljuc!=0)
				{
					kljuc_func($nick,$kljuc,0);
				}
				header("Location: index.php?err=no");
			}
			else
			{
				header("Location: index.php?err=none");
			}
		}
		else
		{
			header("Location: index.php?err=empty");
		}
	}
	else
	{
		header("Location: ../login.php");
	}
?>