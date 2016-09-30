<?php

	/*
	Naziv fajla: poruka.php
	Namena: Prikazuje odabranu konverzaciju kao i polje za odgovor
	Autor: Milos Samardzija
	Eksterne datoteke: ../connect.php
	Tabele kojima pristupa: MSG_INFO, MSG_TXT
	*/

	include "../connect.php";
	checkUser();// da li je korisnik ulogovan?
	$poruke;
	$info;
	$prim;
	if(isset($_SESSION['userName']))
	{
		$nick=$_SESSION['userName'];//cuvanje nicka korisnika
		if(isset($_GET['id']))//da li je odabran ID
		{
			$id=mysql_escape_string($_GET['id']);
			$tip="";
			if(mysql_num_rows(mysql_query("SELECT * FROM MSG_INFO WHERE ID='$id' AND AUTOR='$nick'"))==1)//da li smo autor ili primalac konverzacije?
			$tip="AUT";
			else
			$tip="PRIM";
			if(mysql_num_rows(mysql_query("SELECT * FROM MSG_INFO WHERE AUTOR='$nick' AND AUT_STT='nije_procitano' AND ID='$id'"))==1)//sve statuse poruka koje nisu procitane setuje kao procitane
			{
				mysql_query("UPDATE MSG_INFO SET AUT_STT='procitano' WHERE ID='$id'");
			}
			if(mysql_num_rows(mysql_query("SELECT * FROM MSG_INFO WHERE PRIM='$nick' AND PRIM_STT='nije_procitano' AND ID='$id'"))==1)
			{
				mysql_query("UPDATE MSG_INFO SET PRIM_STT='procitano' WHERE ID='$id'");
			}
			if(mysql_num_rows(mysql_query("SELECT * FROM MSG_INFO WHERE (AUTOR='$nick' OR PRIM='$nick') AND id='$id'"))==1)//selektuje sadrzaj i informacije poruka iz konverzacije
			{
				$poruke=mysql_query("SELECT * FROM MSG_TXT WHERE MSG_ID='$id' AND ".$tip."_OBR='ne' ORDER BY ID DESC");
				$info=mysql_query("SELECT * FROM MSG_INFO WHERE ID='$id'");
			}
			else
			{
				header("Location: index.php");
			}
		}
		else
		{
			header("Location: index.php");
		}
	}
	$niz_i=mysql_fetch_assoc($info);
	if($nick==$niz_i['AUTOR'])//ako smo mi autor, druga osoba je primalac
	{
		$prim=$niz_i['PRIM'];
	}
	else
	{
		$prim=$niz_i['AUTOR'];
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo stripslashes($niz_i['SUB']); ?></title>
<!--MENI-->
  	<?php meni_css(); ?>
<!--MENI-->
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
</head>

<body>
	<div id="wrapper">
    	<!--MENI-->
  		<?php meni("poruke"); ?>
  		<!--MENI-->
    	<form method="post" action="posalji_poruku.php?tip=odgovor&id=<?php echo $niz_i['ID']; ?>" style="border:1px solid #F00; font-family: 'Courier New', Courier, monospace; background-color:black; margin-left:50px; width:698px; margin-right:50px; margin-top:10px;" >
        	<p style="width:698px; color:red; text-align:center; margin-top:10px;">Odgovori (<font style="color:blue;"><?php echo stripslashes($niz_i['SUB']); ?></font>) od <?php echo $niz_i['DAT'];?> izmedju <font style="color:blue;"><?php echo $niz_i['AUTOR']; ?></font> i <font style="color:blue;"><?php echo $niz_i['PRIM']; ?></font></p>
        	<label style="color:red; margin-top:10px; margin-left:5px;" for="primaoci">Primaoci</label>
            <input type="text" readonly="readonly" style="color:red; margin-top:10px; margin-left:5px; width:400px; text-align:left;" value="<?php echo $prim; ?>" id="primaoci" name="primaoci" />
            </br>
            <label style="color:red; margin-top:10px; margin-left:5px;" for="predmet">&nbsp;Predmet</label>
            <input type="text" readonly="readonly" style="color:red; margin-top:10px; margin-left:5px; margin-right:5px; width:400px; text-align:left;" value="<?php echo stripslashes($niz_i['SUB']); ?>" id="predmet" name="predmet" />
            </br>
            <textarea name="poruka" rows="5" style="color:red; overflow-y:scroll; margin-right:5px; margin-top:10px; width:494px; resize:none; margin-left:5px;"></textarea>
            </br>
            <input type="submit" value="POSALJI" style="color:red; margin-left:45%; margin-top:10px; margin-bottom:20px; background-color:yellow; border-radius:10px;" />
        </form>
        <?php
			while($niz_p=mysql_fetch_assoc($poruke))
			{
				echo '<div style="margin-left:50px; border-radius:10px; margin-top:10px; font-family: \'Courier New\', Courier, monospace; margin-right:50px; width:700px; opacity:0.8; background-color:#FFF; border:1px solid #F00;">
        				<p style="margin-top:5px; color:red; margin-left:10px;"><font style="color:blue;">'.$niz_p['POS'].'</font> ['.$niz_p['DAT'].']</p>
            			<div style="margin-top:10px; margin-bottom:10px; margin-right:10px; width:680px; border:1px solid #F00; margin-left:10px;">
                			<p style="color:red; margin-left:5px; margin-right:5px; width:670px; word-wrap:break-word; margin-top:5px;">'.stripslashes($niz_p['TXT']).'</p>
            			</div>
        			  </div>';
			}
		?>
    </div>
</body>
</html>