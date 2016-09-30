<?php
	include "connect.php";
	checkUser();
	$nick=$_SESSION['userName'];
	if(!isset($_GET['id']))
	{
		header("Location: index.php");
		exit;
	}
	$id=mysql_escape_string($_GET['id']);
	$citaj=mysql_query("SELECT rec FROM buffer_reci WHERE id='$id' AND ima_termin='0' AND nick='$nick'");
	if(mysql_num_rows($citaj)!=1)
	{
		header("Location: index.php");
		exit;
	}
	$niz=mysql_fetch_assoc($citaj);
	if(isset($_POST['termin']))
	{
		$termin=mysql_escape_string($_POST['termin']);
		mysql_query("UPDATE buffer_reci SET termin='$termin',ima_termin='1' WHERE ima_termin='0' AND nick='$nick' AND id='$id'");
		header("Location: index.php");
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Opis reci</title>
<?php meni_css(); ?>
<style type="text/css">
<!--
body {
	background-image: url(<?php $bg=LoadSettings("pozadina"); echo $bg['opcija']; ?>);
	background-repeat: repeat;
}
* {
	margin: 0px;
}
#wrapper {
	min-height: 500px;
	width: 800px;
	margin-right: auto;
	margin-left: auto;
	background-image: url(dost-ind1.jpg);
	background-repeat: repeat;
	color: #FF0000;
}
-->
</style>
</head>

<body>
	<div id="wrapper">
    	<?php meni("glavni"); ?>
        <h1 style="color:red; font-size:20px; text-align:left; margin-left:10px;">Osnovna pravila u vezi prihvatanja reci</h1>
        </br>
        <p style="color:red; font-size:15px; text-align:left; width:600px; word-wrap:normal; margin-left:25px;">*Ne prihvataju se vlastite imenice (gradovi, imena...)</p>
        <p style="color:red; font-size:15px; text-align:left; width:600px; word-wrap:normal; margin-left:25px;">*Reci ce biti prihvacene od strane supervizora u roku od 48h i manje</p>
        <p style="color:red; font-size:15px; text-align:left; width:600px; word-wrap:normal; margin-left:25px;">*Unosenjem termina olaksavate supervizoru tako sto mu nagovestavate na koju ste rec zapravo mislili</p>
        <p style="color:red; font-size:15px; text-align:left; width:600px; word-wrap:normal; margin-left:25px;">*Unosenje termina nije obavezno, i rec ce vam biti pregledana i bez toga</p>
        <p style="color:red; font-size:15px; text-align:left; width:600px; word-wrap:normal; margin-left:25px;">*U terminu naglasavate npr. tu rec u nekom kontekstu, ili u kom je vremenu, itd...</p>
        <p style="color:red; font-size:15px; text-align:left; width:600px; word-wrap:normal; margin-left:25px;">*Primer termina za rec limeni: (limeni krov...)</p>
        <p style="color:red; font-size:15px; text-align:left; width:600px; word-wrap:normal; margin-left:25px;">*Kombinovanje slova "n" i "j" za slovo nj nije dozvoljeno(isto vazi i za dz, dj, lj)</p>
        <form style="margin-top:30px; margin-left:10px; color:red;" action="opis_supervizor.php?id=<?php echo $id; ?>" method="post">
        	Termin: <input style="color:red; background-color:yellow; border-radius:10px; width:200px;" type="text" name="termin" />
            Rec: <input style="color:red; margin-left:10px; background-color:yellow; width:150px;" readonly value="<?php echo toReal($niz['rec']); ?>" />
            <input type="submit" value="POTVRDI" style="color:red; background-color:yellow; border-radius:10px;" name="sub" />
        </form>
    </div>
</body>
</html>