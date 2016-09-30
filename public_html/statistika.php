<?php
	include "connect.php";
	checkUser();
	if(isset($_SESSION['userName']))
	{
		if(!checkPerm($_SESSION['userName'],"statistika"))
		{
			header('Location: index.php');
		}
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Statistika</title>
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
	color: #FF0000;
	margin-right: auto;
	margin-left: auto;
	height: auto;
	width: 800px;
	background-image: url(dost-ind1.jpg);
	background-repeat: repeat;
}
-->
</style>
</head>

<body>
<div id="wrapper">
	<!--MENI-->
  	<?php meni("glavni"); ?>
  	<!--MENI-->
	<table style="border:2px solid red; width:800px; margin-left:0px; margin-right:0px; margin-top:5px; text-align:center;">
    	<tr>
        	<td style="text-align:center; color:red; font-size:30px; border:2px solid #FF0000;">Statistika</td>
            </td>
        </tr>
        <tr>
        	<td style="text-align:center; color:red; border:2px solid #FF0000; width:500px;">Ukupno odigrano partija</td>
            <td style="text-align:center; color:red; border:2px solid #FF0000;"><?php echo mysql_num_rows(mysql_query("SELECT * FROM slagalica_dnevne_liste")); ?></td>
        </tr>
        <tr>
        	<td style="text-align:center; color:red; border:2px solid #FF0000; width:500px;">Ukupno partija ovog meseca</td>
            <td style="text-align:center; color:red; border:2px solid #FF0000;"><?php echo mysql_num_rows(mysql_query("SELECT * FROM slagalica_dnevne_liste WHERE datum LIKE '___".date("m")."_".date("Y")."'")); ?></td>
        </tr>
        <tr>
        	<td style="text-align:center; color:red; border:2px solid #FF0000; width:500px;">Ukupno danasnjih partija</td>
            <td style="text-align:center; color:red; border:2px solid #FF0000;"><?php echo mysql_num_rows(mysql_query("SELECT * FROM slagalica_dnevne_liste WHERE datum LIKE '".date("d")."_".date("m")."_".date("Y")."'")); ?></td>
        </tr>
        <tr>
        	<td style="text-align:center; color:red; border:2px solid #FF0000; width:500px;">Najbolja partija ikada</td>
            <td style="text-align:center; color:red; border:2px solid #FF0000;"><?php $data=mysql_query("SELECT SUM(slagalica+moj_broj+skocko+spojnice+koznazna+asocijacije) AS maksimalno,nick,datum FROM slagalica_dnevne_liste GROUP BY id ORDER BY maksimalno DESC, id ASC LIMIT 1"); 
			$max=mysql_fetch_assoc($data);
			echo "[".$max['maksimalno']."], ".$max['nick'].", ".$max['datum'];
			?>
            </td>
        </tr>
        <tr>
        	<td style="text-align:center; color:red; border:2px solid #FF0000; width:500px;">Najbolja partija ovog meseca</td>
            <td style="text-align:center; color:red; border:2px solid #FF0000;"><?php $data=mysql_query("SELECT SUM(slagalica+moj_broj+skocko+spojnice+koznazna+asocijacije) AS maksimalno,nick,datum FROM slagalica_dnevne_liste WHERE datum LIKE '___".date("m")."_".date("Y")."' GROUP BY id ORDER BY maksimalno DESC, id ASC LIMIT 1"); 
			$max=mysql_fetch_assoc($data);
			echo "[".$max['maksimalno']."], ".$max['nick'].", ".$max['datum'];
			?>
            </td>
        </tr>
        <tr>
        	<td style="text-align:center; color:red; border:2px solid #FF0000; width:500px;">Najaktivniji korisnik (partije)</td>
            <td style="text-align:center; color:red; border:2px solid #FF0000;"><?php
			$data=mysql_query("SELECT nick,COUNT(id) AS br FROM slagalica_dnevne_liste GROUP BY nick ORDER BY br DESC LIMIT 1");
			$max=mysql_fetch_assoc($data);
			echo $max['nick']." (".$max['br'].")";
            ?></td>
        </tr>
        <tr>
        	<td style="text-align:center; color:red; border:2px solid #FF0000; width:500px;">Najiskusniji korisnik (exp)</td>
            <td style="text-align:center; color:red; border:2px solid #FF0000;"><?php
            $data=mysql_query("SELECT * FROM poeni ORDER BY exp DESC LIMIT 1");
			$niz=mysql_fetch_assoc($data);
			echo $niz['nick']." (".$niz['exp'].")";
			?></td>
        </tr>
        <tr>
        	<td style="text-align:center; color:red; border:2px solid #FF0000; width:500px;">Najefikasniji korisnik (prosek bodovi/partija)</td>
            <td style="text-align:center; color:red; border:2px solid #FF0000;"><?php
            $data=mysql_query("SELECT nick,AVG(slagalica+moj_broj+spojnice+skocko+koznazna+asocijacije) AS prosek FROM slagalica_dnevne_liste GROUP BY nick ORDER BY prosek DESC LIMIT 1");
			$niz=mysql_fetch_assoc($data);
			echo $niz['nick']." (".round(intval($niz['prosek'])).")";
			?></td>
        </tr>
        <tr>
        	<td style="text-align:center; color:red; border:2px solid #FF0000; width:500px;">Najefikasniji korisnik meseca (prosek bodovi/partija)</td>
            <td style="text-align:center; color:red; border:2px solid #FF0000;"><?php
            $data=mysql_query("SELECT * FROM slagalica_dnevne_liste WHERE datum LIKE '___".date("m")."_".date("Y")."'");
			$data=mysql_query("SELECT nick,AVG(slagalica+moj_broj+spojnice+skocko+koznazna+asocijacije) AS prosek FROM slagalica_dnevne_liste WHERE datum LIKE '___".date("m")."_".date("Y")."' GROUP BY nick ORDER BY prosek DESC LIMIT 1");
			$niz=mysql_fetch_assoc($data);
			echo $niz['nick']." (".round(intval($niz['prosek'])).")";
			?></td>
        </tr>
        <tr>
        	<td style="text-align:center; color:red; border:2px solid #FF0000; width:500px;">Prosecno bodova po partiji</td>
            <td style="text-align:center; color:red; border:2px solid #FF0000;"><?php
            $data=mysql_query("SELECT AVG(slagalica+moj_broj+spojnice+skocko+koznazna+asocijacije) AS prosek FROM slagalica_dnevne_liste");
			$niz=mysql_fetch_assoc($data);
			echo round(intval($niz['prosek']));
			?></td>
        </tr>
        <tr>
        	<td style="text-align:center; color:red; border:2px solid #FF0000; width:500px;">Asocijacija u bazi</td>
            <td style="text-align:center; color:red; border:2px solid #FF0000;"><?php echo mysql_num_rows(mysql_query("SELECT DISTINCT objekat FROM asocijacija_veze WHERE sinonim='0' AND tip_veze='gr-rk'")); ?></td>
        </tr>
        <tr>
        	<td style="text-align:center; color:red; border:2px solid #FF0000; width:500px;">Aktivni baneri u bazi</td>
            <td style="text-align:center; color:red; border:2px solid #FF0000;"><?php echo mysql_num_rows(mysql_query("SELECT * FROM baneri WHERE status='aktivan'")); ?></td>
        </tr>
        <tr>
        	<td style="text-align:center; color:red; border:2px solid #FF0000; width:500px;">Nepriznatih reci u bufferu</td>
            <td style="text-align:center; color:red; border:2px solid #FF0000;"><?php echo mysql_num_rows(mysql_query("SELECT * FROM buffer_reci")); ?></td>
        </tr>
        <tr>
        	<td style="text-align:center; color:red; border:2px solid #FF0000; width:500px;">Broj registrovanih korisnika</td>
            <td style="text-align:center; color:red; border:2px solid #FF0000;"><?php echo mysql_num_rows(mysql_query("SELECT * FROM lista")); ?></td>
        </tr>
        <tr>
        	<td style="text-align:center; color:red; border:2px solid #FF0000; width:500px;">Broj aktivnih obavestenja u bazi</td>
            <td style="text-align:center; color:red; border:2px solid #FF0000;"><?php echo mysql_num_rows(mysql_query("SELECT * FROM obavestenja WHERE status='aktivan'")); ?></td>
        </tr>
        <tr>
        	<td style="text-align:center; color:red; border:2px solid #FF0000; width:500px;">Tipovi ovlascenja</td>
            <td style="text-align:center; color:red; border:2px solid #FF0000;"><?php echo mysql_num_rows(mysql_query("SELECT * FROM ovlascenja")); ?></td>
        </tr>
        <tr>
        	<td style="text-align:center; color:red; border:2px solid #FF0000; width:500px;">Broj pitanja u bazi</td>
            <td style="text-align:center; color:red; border:2px solid #FF0000;"><?php echo mysql_num_rows(mysql_query("SELECT * FROM pitanja")); ?></td>
        </tr>
        <tr>
        	<td style="text-align:center; color:red; border:2px solid #FF0000; width:500px;">Broj nekategorizovanih pitanja</td>
            <td style="text-align:center; color:red; border:2px solid #FF0000;"><?php echo mysql_num_rows(mysql_query("SELECT * FROM pitanja WHERE kategorija='Van kategorije'")); ?></td>
        </tr>
        <tr>
        	<td style="text-align:center; color:red; border:2px solid #FF0000; width:500px;">Broj svih reci u bazi</td>
            <td style="text-align:center; color:red; border:2px solid #FF0000;"><?php echo mysql_num_rows(mysql_query("SELECT * FROM sve_reci")); ?></td>
        </tr>
        <tr>
        	<td style="text-align:center; color:red; border:2px solid #FF0000; width:500px;">Broj reci za slagalicu</td>
            <td style="text-align:center; color:red; border:2px solid #FF0000;"><?php echo mysql_num_rows(mysql_query("SELECT * FROM reci")); ?></td>
        </tr>
        <tr>
        	<td style="text-align:center; color:red; border:2px solid #FF0000; width:500px;">Spojnica u bazi</td>
            <td style="text-align:center; color:red; border:2px solid #FF0000;"><?php echo mysql_num_rows(mysql_query("SELECT * FROM spojnice")); ?></td>
        </tr>
    </table>
</div>
</body>
</html>
