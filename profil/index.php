<?php
	include "../connect.php";
	if ((!isset($_SESSION['validUser'])) || ($_SESSION['validUser'] != true)){
		header('Location: ../login.php');
	}
	$nick=$_SESSION['userName'];
	$mod="";
	if(!isset($_GET['id']))
	$mod="user";
	else
	{
		$id=mysql_escape_string($_GET['id']);
		$lista_data=mysql_query("SELECT * FROM lista WHERE ID='$id'");
		if(mysql_num_rows($lista_data)==0)
		{
			header("Location: index.php");
			return;
		}
		else
		{
			$lista_niz=mysql_fetch_assoc($lista_data);
			if($lista_niz['username']==$nick)
			$mod="user";
			else
			{
				$mod="search";
				$nick=$lista_niz['username'];
			}
		}
	}
	$lista_data=mysql_query("SELECT * FROM lista WHERE username='$nick'");
	$lista_niz=mysql_fetch_assoc($lista_data);
	$poeni_data=mysql_query("SELECT exp, level FROM poeni WHERE nick='$nick'");
	$poeni_niz=mysql_fetch_assoc($poeni_data);
	$dnevna_data=mysql_query("SELECT MAX(slagalica+moj_broj+spojnice+skocko+koznazna+asocijacije) AS max_p, ROUND(AVG(slagalica+moj_broj+spojnice+skocko+koznazna+asocijacije),0) AS pros_p FROM slagalica_dnevne_liste WHERE nick='$nick'");
	$dnevna_niz=mysql_fetch_assoc($dnevna_data);
	$admini_data=mysql_query("SELECT COUNT(id) AS adm FROM admini WHERE nick='$nick'");
	$admini_niz=mysql_fetch_assoc($admini_data);
	$dnevna1_data=mysql_query("SELECT SUM(slagalica+moj_broj+spojnice+skocko+koznazna+asocijacije) AS suma_svih FROM slagalica_dnevne_liste WHERE nick='$nick'");
	$dnevna1_niz=mysql_fetch_assoc($dnevna1_data);
	$page=1;
	if(isset($_GET['page']))
	$page=intval(mysql_escape_string($_GET['page']));
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $nick; ?></title>
<?php meni_css(); ?>
<style type="text/css">
body {
	background-image: url(<?php $bg=LoadSettings("pozadina"); echo "../".$bg['opcija']; ?>);
	background-repeat: repeat;
}

*{
	margin:0px;
}

#wrapper
{
	height:500px;
	color: #F00;
	background-image: url(../dost-ind1.jpg);
	width:800px;
	margin-left:auto;
	margin-right:auto;
	overflow-y:scroll;
}

#levi
{
	border-top-right-radius:10px;
	color:red;
	border-bottom-right-radius:10px;
	opacity:0.8;
	background-color:#0F9;
	float:left;
	clear:both;
	min-height:50px;
	margin-top:20px;
	width:700px;
}

#desni
{
	border-top-left-radius:10px;
	color:red;
	border-bottom-left-radius:10px;
	opacity:0.8;
	background-color:#3F0;
	float:right;
	clear:both;
	min-height:50px;
	margin-top:20px;
	width:700px;
}
</style>

<script type="text/javascript">

function validate(evt) {
 var theEvent = evt || window.event;
var key = theEvent.keyCode || theEvent.which;
key = String.fromCharCode( key );
 var regex = /[0-9]/;
 if( !regex.test(key) ) {
  theEvent.returnValue = false;
   if(theEvent.preventDefault) theEvent.preventDefault();
  }
}
</script>
</head>

<body>
	<div style="width:800px; margin-left:auto; margin-right:auto; height:40px; border-bottom:1px solid red; background-color:#CCC; vertical-align:middle;">
        	<form style="margin-left:150px;" method="get" action="search.php">Pretraga: <input type="text" name="nick" style="background-color:yellow; width:200px; margin:5px; border-radius:10px; color:red;" /><input type="submit" value="" style="background-image:url(../images/search.png); width:20px; height:20px; background-size:100%;" /></form>
        </div>
    	<table id="INFORMACIJE" width="800" style="margin-left:auto; margin-right:auto;" height="150" border="0" cellpadding="0" cellspacing="0">
        	<tr>
        		<td rowspan="2">
            		<img style="" src="<?php echo "../profilne/".$lista_niz['profilna']; ?>" width="150" height="150">
            	</td>
            	<td title="<?php echo "Reputacija: ".$lista_niz['reputacija']; ?>" style="width:650px; height:30px; background-color:#CCC; color:black;">
					<h1 style="text-align:center; font-size:25px;"><?php echo $nick; for($i=0;$i<broj_zvezdica($nick);$i++) echo " <img src='../images/star.png' style='vertical-align:middle;' width='20' height='20'/>"; ?></h1>
            	</td>
			</tr>
            <tr>
				<td style="width:650px; height:120px; vertical-align:top; background-color:#CCC; color:black;">
                	<p style="font-size:15px; float:left; width:195px; margin-top:5px; margin-left:5px;"><?php echo "Reputacija: ".$lista_niz['reputacija']; ?></p>
                    <p style="font-size:15px; float:left; width:195px; margin-top:5px; margin-left:5px;"><?php echo "Iskustvo: ".$poeni_niz['exp']; ?></p>
                    <p style="font-size:15px; float:left; width:195px; margin-top:5px; margin-left:5px;"><?php echo "Level: ".$poeni_niz['level']; ?></p>
                    <p style="font-size:15px; float:left; width:195px; margin-top:10px; margin-left:5px;"><?php echo "Broj partija: ".mysql_num_rows(mysql_query("SELECT id FROM slagalica_dnevne_liste WHERE nick='$nick'")); ?></p>
                    <p style="font-size:15px; float:left; width:195px; margin-top:10px; margin-left:5px;"><?php echo "Broj partija (mesec): ".mysql_num_rows(mysql_query("SELECT id FROM slagalica_dnevne_liste WHERE nick='$nick' AND datum LIKE '___".date("m-Y")."'")); ?></p>
                    <p style="font-size:15px; float:left; width:195px; margin-top:10px; margin-left:5px;"><?php echo "Max poena: ".$dnevna_niz['max_p']; ?></p>
                    <p style="font-size:15px; float:left; width:195px; margin-top:10px; margin-left:5px;"><?php echo "Pros. broj poena: ".$dnevna_niz['pros_p']; ?></p>
                    <p style="font-size:15px; float:left; width:195px; margin-top:10px; margin-left:5px;"><?php if($admini_niz['adm']=="1")$admini_niz['adm']="da"; else $admini_niz['adm']="ne"; echo "Administrator: ".$admini_niz['adm']; ?></p>
                    <p style="font-size:15px; float:left; width:195px; margin-top:10px; margin-left:5px;"><?php echo "Ukupno bodova: ".$dnevna1_niz['suma_svih']; ?></p>
                </td>
			</tr>
        </table>
        <?php if($mod=="user") echo'<div style="width:800px; margin-left:auto; margin-right:auto; border-top:1px solid red; font-size:12px; background-color:#CCC;"><form action="upload_file.php" method="post" enctype="multipart/form-data">Pomoc: Ne svidja Vam se profilna? Promenite je...&nbsp;<input name="file" type="file" style=" text-align:center;" value="Promeni sliku"/><input type="submit" style="border-radius:10px; color:red; background-color:yellow;" value="Promeni" /></form></div>';?>
        <?php echo "<div style='margin-left:auto; margin-right:auto; width:800px;'>"; meni("profil"); echo "</div>"; ?>
	<div id="wrapper">
    	
        <?php
			$min=abs(intval($page)-1)*10;
			$data=mysql_query("SELECT * FROM statusi WHERE nick='$nick' ORDER BY id DESC LIMIT $min, 10");
			$num=mysql_num_rows(mysql_query("SELECT * FROM statusi WHERE nick='$nick'"));
			$br=ceil($num/10);
			$strana=false;
			while($niz=mysql_fetch_assoc($data))
			{
				if(!$strana)
				{
					echo '<div id="levi">
        				  <p style="margin:5px; word-wrap:normal;">'.$niz['text'].'</br>('.$niz['datum'].')</p>
        				  </div>';
				}
				else
				{
					echo '<div id="desni">
        				  <p style="margin:5px; word-wrap:normal;">'.$niz['text'].'</br>('.$niz['datum'].')</p>
         				  </div>';
				}
				$strana=(!$strana);
			}
$prom="";
if(isset($_GET['id']))
$prom=$_GET['id'];
?>
    </div>
    <div style="width:800px; margin-left:auto; margin-right:auto; background-color:yellow;" >
    		<p style="color:red; float:left; margin-left:4px;"><?php echo $page."/".$br; ?></p> <form action="index.php" style="" method="get"><input type="text" style="background-color:white; margin-left:5px; color:red; border-radius:10px; width:40px; float:left;" onkeypress="validate()" maxlength="4" name="page" />
<?php if($prom!="")echo'<input type="hidden" name="id" value="'.$prom.'">';?> <input type="submit" value="OK" style="color:red; border-radius:10px; background-color:white;"  /></form>
    </div>
</body>
</html>