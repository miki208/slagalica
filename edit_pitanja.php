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
	$data=mysql_query("SELECT * FROM pitanja WHERE kategorija='Van kategorije' LIMIT 1");
	$read=mysql_fetch_assoc($data);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Editovanje pitanja</title>

<!--MENI-->
  	<?php meni_css(); ?>
<!--MENI-->

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
	background-image: url(dost-ind1.jpg);
	background-repeat: repeat;
	width: 800px;
	min-height:500px;
	margin-right: auto;
	border: 1px solid #FF0000;
	margin-left: auto;
}
-->
</style>
</head>

<body>
<div id="wrapper">
        <!--MENI-->
  		<?php meni("glavni"); ?>
        <!--MENI-->
	<form action="edituj_pitanje.php?id=<?php echo $read['id']; ?>" method="post">
	<textarea name="pitanje" style="background-color:yellow; font-size:24px; margin-top:40px; width:750px; margin-left:25px; resize: none; border-radius:10px; text-align:center; height:100px; color:red; word-wrap:break-word;" cols="5"><?php echo $read['pitanje'];?></textarea>
    <textarea name="A" style="background-color:yellow; font-size:24px; float:left; margin-top:40px; width:350px; margin-left:25px; resize: none; border-radius:10px; text-align:center; height:50px; color:red; word-wrap:break-word;" cols="5"><?php echo $read['odga'];?></textarea>
    <textarea name="B" style="background-color:yellow; font-size:24px; float:right; margin-top:40px; width:350px; margin-right:25px; resize: none; border-radius:10px; text-align:center; height:50px; color:red; word-wrap:break-word;" cols="5"><?php echo $read['odgb'];?></textarea>
    <textarea name="C" style="background-color:yellow; font-size:24px; float:left; margin-top:15px; width:350px; margin-left:25px; resize: none; border-radius:10px; text-align:center; height:50px; color:red; word-wrap:break-word;" cols="5"><?php echo $read['odgc'];?></textarea>
    <textarea name="D" style="background-color:yellow; font-size:24px; float:right; margin-top:15px; width:350px; margin-right:25px; resize: none; border-radius:10px; text-align:center; height:50px; color:red; word-wrap:break-word;" cols="5"><?php echo $read['odgd'];?></textarea>
    <select name="tacno" style="margin-top:10px; margin-left:25px; font-size:24px;">
    	<option <?php if($read['tacan']==0) echo "selected=\"selected\""; ?> value="1">1</option>
  		<option <?php if($read['tacan']==1) echo "selected=\"selected\""; ?> value="2">2</option>
  		<option <?php if($read['tacan']==2) echo "selected=\"selected\""; ?> value="3">3</option>
  		<option <?php if($read['tacan']==3) echo "selected=\"selected\""; ?> value="4">4</option>
	</select>
    <select name="kateg" style="margin-top:10px; margin-left:25px; font-size:20px;">
    	<option value="Van kategorije">Van kategorije</option>
  		<option value="Istorija">Istorija</option>
  		<option value="Geografija">Geografija</option>
  		<option value="Muzika, film i umetnost">Muzika, film i umetnost</option>
  		<option value="Sport">Sport</option>
        <option value="Knjizevnost i gramatika">Knjizevnost i gramatika</option>
        <option value="Nauka i tehnologija">Nauka i tehnologija</option>
        <option value="Biologija">Biologija</option>
        <option value="Politika">Politika</option>
        <option value="Izreke">Izreke</option>
        <option value="Opsta kultura">Opsta kultura</option>
        <option value="Zargon">Zargon</option>
        <option value="Jezici">Jezici</option>
        <option value="Medicina">Medicina</option>
        <option value="Matematika">Matematika</option>
        <option value="Igre">Igre</option>
        <option value="Ostalo">Ostalo</option>
	</select>
    <input type="submit" style="font-size:24px;" value="Edituj" />
    </form>
</div>
</body>
</html>
	