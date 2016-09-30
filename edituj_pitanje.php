<?php
	include "connect.php";
	checkUser();
	if(isset($_SESSION['userName']))
	{
		if(!checkPerm($_SESSION['userName'],"editor"))
		{
			header('Location: index.php');
		}
	}
	if(isset($_POST['pitanje'])&&isset($_POST['A'])&&isset($_POST['B'])&&isset($_POST['C'])&&isset($_POST['D'])&&isset($_POST['tacno'])&&isset($_POST['kateg'])&&isset($_GET['id']))
	{
		$znakovi=array("(sh)","(SH)","(dj)","(DJ)","(ch)","(CH)","(tj)","(TJ)","(zh)","(ZH)");
		$repl=array("&#353;","&#352;","&#273;","&#272;","&#269;","&#268;","&#263;","&#262;","&#382;","&#381;");
		for($i=0;$i<count($znakovi);$i++)
		{
			$_POST['pitanje']=str_replace($znakovi[$i],$repl[$i],$_POST['pitanje']);
			$_POST['A']=str_replace($znakovi[$i],$repl[$i],$_POST['A']);
			$_POST['B']=str_replace($znakovi[$i],$repl[$i],$_POST['B']);
			$_POST['C']=str_replace($znakovi[$i],$repl[$i],$_POST['C']);
			$_POST['D']=str_replace($znakovi[$i],$repl[$i],$_POST['D']);
		}
		mysql_query("UPDATE pitanja SET pitanje='".$_POST['pitanje']."', odga='".$_POST['A']."', odgb='".$_POST['B']."', odgc='".$_POST['C']."', odgd='".$_POST['D']."', kategorija='".$_POST['kateg']."', tacan='".strval(intval($_POST['tacno'])-1)."' WHERE id='".$_GET['id']."'");
	}
	header("Location: edit_pitanja.php");
?>