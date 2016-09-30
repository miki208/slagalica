<?php

	/*
	Naziv fajla: index.php
	Namena: Ima namenu inbox-a, izlistava sve konverzacije i obrazac za slanje na poruka
	Autor: Milos Samardzija
	Eksterne datoteke: ../connect.php
	Tabele kojima pristupa: MSG_INFO, MSG_TXT
	*/

	include "../connect.php";
	checkUser(); //da li je korisnik ulogovan?
	if(isset($_SESSION['userName']))//da li je setovana sesija userName?
	{
		$nick=$_SESSION['userName'];//cuvanje nick-a korisnika
		$n_poruke=mysql_query("SELECT * FROM MSG_INFO WHERE (AUTOR='$nick' AND AUT_STT='nije_procitano') OR (PRIM='$nick' AND PRIM_STT='nije_procitano') ORDER BY ID DESC");//dobavljanje neprocitanih poruka
		$p_poruke=mysql_query("SELECT * FROM MSG_INFO WHERE (AUTOR='$nick' AND AUT_STT='procitano') OR (PRIM='$nick' AND PRIM_STT='procitano') ORDER BY ID DESC");//dobavljanje procitanih poruka
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Inbox</title>
<style type="text/css">
body {
	background-image: url(<?php $bg=LoadSettings("pozadina"); echo "../".$bg['opcija']; ?>);
	background-repeat: repeat;
}
* {
	margin: 0px;
}
#wrapper {
	
	color: #F00;
	background-image: url(../dost-ind1.jpg);
	background-repeat: repeat;
	height: auto;
	width: 800px;
	margin-right: auto;
	margin-left: auto;
	border: 1px solid #F00;
}
</style>

<!--MENI-->
  	<?php meni_css(); ?>
<!--MENI-->

<script type="text/javascript">
	function otvori(poruka)//otvaranje selektovane poruke klikom misa
	{
		window.location=poruka;
	}

	function msgboxshow(msgboxname,title,text)//prikazivanje MsgBox-a
	{
		document.getElementById(msgboxname+"title").innerHTML="&nbsp;"+title;
		document.getElementById(msgboxname+"txt").innerHTML=text;
		document.getElementById(msgboxname+"bg").style.visibility="visible";
	}

	function msgboxhide(msgboxname)//sakrivanje MsgBox-a
	{
		document.getElementById(msgboxname+"bg").style.visibility="hidden";
	}
	
	function init()//inicijalizacija
	{
		<?php
			$txt="";//cuva sve pronadjene greske
			if(isset($_GET['error']))//da li je setovano polje za gresku?
			{
				if($_GET['error']=="yes")//da li je doslo do greske?
				{
					if(isset($_GET['err_type']))//da li je setovano polje za tip greske?
					{
						$greske=explode("_",$_GET['errtxt']);//rasclanjivanje svih gresaka
						switch($_GET['err_type'])
						{
							case "odg"://greska pri odgovaranju na poruku
							foreach($greske as $greska)
							{
								if($greska=="unkmsg")//poruka ne postoji
								$txt.="-Nepostojeca poruka.</br>";
								if($greska=="noperm")//bez dozvole za citanje poruke
								$txt.="-Nemate dozvolu za odgovor na ovu poruku.</br>";
								if($greska=="max")//prekoracenje broja karaktera
								$txt.="-Dozvoljeno maksimalno 400 karaktera.</br>";
								if($greska=="none")//prazna poruka
								$txt.="-Poruka ne sme biti prazna.</br>";
							}
							break;
							case "slanje"://greska pri slanju poruke
							foreach($greske as $greska)
							{
								if(substr($greska,0,5)=="prim*")//da li je greska u nicku korisnika?
								{
									$niz=explode("*",$greska);//parsiranje imena korisnika
									$txt.="-Korisnik ".$niz[1]." ne postoji.</br>";
								}
								if($greska=="auto")//slanje poruka samom sebi nije dozvoljeno
								$txt.="-Ne mozete poslati poruku samom sebi.</br>";
							}
							break;
							case "poruka"://greska u sastavu poruke
							foreach($greske as $greska)
							{
								if($greska=="max")//prekoracenje broja karaktera
								$txt.="-Dozvoljeno maksimalno 400 karaktera.</br>";
								if($greska=="none")//prazna poruka
								$txt.="-Poruka ne sme biti prazna.</br>";
								if($greska=="sub")//nema predmet
								$txt.="-Polje za predmet ne sme biti prazno.</br>";
							}
							break;
							default:
							$txt.="-Nepoznata greska.</br>";
							break;
						}
						echo "msgboxshow('msgbox','Greska','$txt');";//prikazivanje MsgBox-a sa greskom
					}
				}
			}
		?>
	}
</script>
</head>

<body onload="init()">

<!--MsgBox POCETAK-->
<div id="msgboxbg" style="width:100%; font-family: 'Courier New', Courier, monospace; visibility:hidden; height:100%; z-index:2; position:absolute; background-color:none;">
	<div id="msgbox" style="width:400px; height:auto; position:absolute; border-radius:10px; border-radius:10px; border:1px solid #F00; background-color:yellow; left:30%; top:30%;">
		<p id="msgboxtitle" style="text-align:left; width:100%; border-top-left-radius:8px; border-top-right-radius:8px; height:18px; color:red; cursor:pointer; background-color:#0F0; border-bottom:1px solid #F00; font-size:15px;">&nbsp;</p>
        <p id="msgboxtxt" style="word-wrap: break-word; margin-top:10px; color:red; margin-left:5px; margin-right:5px;"></p>
    	<input type="button" value="OK" onclick="msgboxhide('msgbox')" style="margin-left:180px; cursor:pointer; text-align:center; margin-top:10px; margin-bottom:10px; width:40px; margin-right:180px;" />
	</div>
</div>
<!--MsgBox KRAJ-->

	<div id="wrapper">
     <!--MENI-->
  	<?php meni("poruke"); ?>
  <!--MENI-->
    	<form method="post" action="posalji_poruku.php?tip=nova" style="border:1px solid #F00; background-color:black; margin-left:50px; width:698px; font-family: 'Courier New', Courier, monospace; margin-right:50px; margin-top:10px;" >
        	<p style="width:698px; color:red; text-align:center; margin-top:10px;">Nova poruka</p>
        	<label style="color:red; margin-top:10px; margin-left:5px;" for="primaoci">Primaoci</label>
            <input type="text" style="color:red; margin-top:10px; margin-left:5px; width:400px; text-align:left;" id="primaoci" name="primaoci" />
            </br>
            <label style="color:red; margin-top:10px; margin-left:5px;" for="predmet">&nbsp;Predmet</label>
            <input type="text" style="color:red; margin-top:10px; margin-left:5px; margin-right:5px; width:400px; text-align:left;" id="predmet" name="predmet" />
            </br>
            <textarea name="poruka" rows="5" style="color:red; overflow-y:scroll; margin-right:5px; margin-top:10px; width:494px; resize:none; margin-left:5px;"></textarea>
            </br>
            <input type="submit" value="POSALJI" style="color:red; margin-left:45%; margin-top:10px; margin-bottom:20px; background-color:yellow; border-radius:10px;" />
        </form>
        <div style="background-color:#FFF; margin-left:50px; font-family: 'Courier New', Courier, monospace; margin-top:10px; height:15px; width:30px;"><p style="color:red; font-size:15px; margin-left:35px;">Procitano</p></div>
        <div style="background-color:#3C9; margin-left:50px; font-family: 'Courier New', Courier, monospace; margin-top:10px; height:15px; width:30px;"><p style="color:red; font-size:15px; margin-left:35px;">Neprocitano</p></div>
    	<?php
			while($niz=mysql_fetch_assoc($n_poruke))//fetch neprocitanih poruka
			{
				$tip="";
				if(mysql_num_rows(mysql_query("SELECT * FROM MSG_INFO WHERE ID='".$niz['ID']."' AND AUTOR='$nick'"))==1)//da li ste autor te neprocitane poruke?
				$tip="AUT";
				else
				$tip="PRIM";
				$poruka=mysql_query("SELECT * FROM MSG_TXT WHERE MSG_ID='".$niz['ID']."' AND ".$tip."_OBR='ne' ORDER BY ID DESC LIMIT 1");//dobavljanje sadrzaja poruke
				$por=mysql_fetch_assoc($poruka);//fetch poruke
				$tacke="";
				if(strlen($por['TXT'])>100)//dodavanje tri tacke nakon 100 karaktera
				$tacke="...";
				echo '<div style="margin-left:50px; font-family: \'Courier New\', Courier, monospace; border-radius:10px; margin-top:10px; margin-right:50px; width:700px; opacity:0.8; background-color:#3C9; cursor:pointer; border:1px solid #F00;" onclick="otvori(\'poruka.php?id='.$niz['ID'].'\')">
        				<p style="margin-top:5px; color:red; margin-left:10px;">Konverzacija izmedju <font style="color:blue;">'.$niz['AUTOR'].'</font> i <font style="color:blue;">'.$niz['PRIM'].'</font> ['.$niz['DAT'].']</p>
            			<div style="margin-top:10px; margin-bottom:10px; margin-right:10px; width:680px; border:1px solid #F00; margin-left:10px;">
            				<p style="color:blue; margin-left:5px; margin-right:5px; margin-top:5px;">'.$por['POS'].'<font style="color:red;">('.$por['DAT'].') - '.stripslashes($niz['SUB']).'</font></p>
                			<p style="color:red; margin-left:5px; margin-right:5px; width:670px; word-wrap:break-word; margin-top:5px;">'.substr(stripslashes($por['TXT']),0,100).$tacke.'</p>
            			</div>
						<a href="obrisi.php?id='.$niz['ID'].'" style="margin-top:10px; margin-left:10px; margin-bottom:10px; text-decoration:none; color:red;">Obrisi</a>
        			  </div>';
			}
			while($niz=mysql_fetch_assoc($p_poruke))//fetch procitanih poruka
			{
				$poruka=mysql_query("SELECT * FROM MSG_TXT WHERE MSG_ID='".$niz['ID']."' ORDER BY ID DESC LIMIT 1");//dobavljanje sadrzaja poruke
				$por=mysql_fetch_assoc($poruka);//fetch poruke
				$tacke="";
				if(strlen($por['TXT'])>100)//dodavanje tri tacke nakon 100 karaktera
				$tacke="...";
				echo '<div style="margin-left:50px; font-family: \'Courier New\', Courier, monospace; border-radius:10px; margin-top:10px; margin-right:50px; width:700px; opacity:0.8; background-color:#FFF; cursor:pointer; border:1px solid #F00;" onclick="otvori(\'poruka.php?id='.$niz['ID'].'\')">
        				<p style="margin-top:5px; color:red; margin-left:10px;">Konverzacija izmedju <font style="color:blue;">'.$niz['AUTOR'].'</font> i <font style="color:blue;">'.$niz['PRIM'].'</font> ['.$niz['DAT'].']</p>
            			<div style="margin-top:10px; margin-bottom:10px; margin-right:10px; width:680px; border:1px solid #F00; margin-left:10px;">
            				<p style="color:blue; margin-left:5px; margin-right:5px; margin-top:5px;">'.$por['POS'].'<font style="color:red;">('.$por['DAT'].') - '.stripslashes($niz['SUB']).'</font></p>
                			<p style="color:red; margin-left:5px; margin-bottom:10px; margin-right:5px; width:670px; word-wrap:break-word; margin-top:5px;">'.substr(stripslashes($por['TXT']),0,100).$tacke.'</p>
            			</div>
						<a href="obrisi.php?id='.$niz['ID'].'" style="margin-top:10px; margin-left:10px; margin-bottom:10px; text-decoration:none; color:red;">Obrisi</a>
        			  </div>';
			}
		?>
    </div>
</body>
</html>