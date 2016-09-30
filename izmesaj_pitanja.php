<?php
	include "connect.php";
	checkUser();
	if(isset($_SESSION['userName']))
	{
		if(!checkPerm($_SESSION['userName'],"shuffle"))
		{
			header('Location: index.php');
		}
		else
		{
			$temp=array();
			$pitanje=array();
			$a=array();
			$b=array();
			$c=array();
			$d=array();
			$tacan=array();
			$kategorija=array();
			$data=mysql_query("SELECT * FROM pitanja");
			while($niz=mysql_fetch_assoc($data))
			{
				array_push($temp,str_replace("'","",$niz['pitanje']."(posaft)".$niz['odga']."(posaft)".$niz['odgb']."(posaft)".$niz['odgc']."(posaft)".$niz['odgd']."(posaft)".$niz['tacan']."(posaft)".$niz['kategorija']));
			}
			shuffle($temp);
			for($i=0;$i<count($temp);$i++)
			{
				$ns=array();
				$ns=explode('(posaft)',$temp[$i]);
				array_push($pitanje,$ns[0]);
				array_push($a,$ns[1]);
				array_push($b,$ns[2]);
				array_push($c,$ns[3]);
				array_push($d,$ns[4]);
				array_push($tacan,$ns[5]);
				array_push($kategorija,$ns[6]);
			}
			mysql_query("TRUNCATE pitanja");
			for($i=0;$i<count($pitanje);$i++)
			{
				mysql_query("INSERT INTO pitanja(pitanje,odga,odgb,odgc,odgd,tacan,kategorija) VALUES('".$pitanje[$i]."','".$a[$i]."','".$b[$i]."','".$c[$i]."','".$d[$i]."','".$tacan[$i]."','".$kategorija[$i]."')");
			}
			header("Location: admin_panel.php");
		}
	}
?>