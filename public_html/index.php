<?php
	include "connect.php";
	checkUser();
    $share="odigrano";
	$datum=date("d-m-Y");
	$db=mysql_query("SELECT * FROM slagalica_dnevne_liste WHERE datum='$datum' AND nick='".$_SESSION['userName']."'");
	$broj_dn=mysql_num_rows($db);
	if($broj_dn==1)
	{
		$niz=mysql_fetch_assoc($db);
	}
	$logo=LoadSettings("index-logo");
	$uk123data=mysql_query("SELECT SUM(slagalica+moj_broj+skocko+spojnice+koznazna+asocijacije) AS ukupno FROM slagalica_dnevne_liste WHERE nick='".$_SESSION['userName']."' AND datum='$datum'");
	$uk123=mysql_fetch_assoc($uk123data);
	if(mysql_num_rows(mysql_query("SELECT * FROM slagalica_dnevne_liste WHERE nick='".$_SESSION['userName']."' AND datum='$datum' AND igrana_slagalica='da' AND igrana_moj_broj='da' AND igrana_spojnice='da' AND igrana_skocko='da' AND igrana_koznazna='da' AND igrana_asocijacije='da' AND share='ne'"))){kreiraj_notifikaciju($_SESSION['userName'],"Dnevna partija odigrana","Uspesno ste odigrali dnevnu partiju i osvojili ste ".$uk123['ukupno']." bodova. Ukoliko niste zadovoljni, mozete resetovati neku od igara."); mysql_query("UPDATE slagalica_dnevne_liste SET share='potv' WHERE nick='".$_SESSION['userName']."' AND datum='$datum'");
		dodaj_status($_SESSION['userName'],"Uspesno sam odigrao dnevnu partiju i osvojio sam ".$uk123['ukupno']." bodova. Mozes li bolje od mene? Samo pokusaj!.");
                $share="dost";
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Mikisoft Slagalica</title>
<link href="progresscss.css" rel="stylesheet" type="text/css"/>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>

<style>
.ui-progressbar .ui-progressbar-value { background-color:#33FF33; }
</style>

<style type="text/css">
body {
	background-image: url(<?php $bg=LoadSettings("pozadina"); echo $bg['opcija']; ?>);
	background-repeat: repeat;
}
</style>

<!--MENI-->
  	<?php meni_css(); ?>
<!--MENI-->

<link href="index.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">

var BANERI=Array();
var BANERI_ADR=Array();
var BANERI_TTL=Array();
INDEX=0;

var obavestenja=new Array();

var oblen;
var idob=0;
function menjaj_ob()
{
	if(oblen>0)
	{
		document.getElementById("info").innerHTML="<p style='margin:2px; word-wrap:normal;' >"+obavestenja[idob]+"</p>";
		if(idob==(oblen-1))
		idob=0;
		else
		++idob;
		setTimeout("menjaj_ob()",10000);
	}
}

function poseta()
{
	if (window.XMLHttpRequest)
  	{// code for IE7+, Firefox, Chrome, Opera, Safari
  		xmlhttp=new XMLHttpRequest();
  	}
	else
  	{// code for IE6, IE5
  		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  	}
	xmlhttp.onreadystatechange=function()
  	{
  		if (xmlhttp.readyState==4 && xmlhttp.status==200)
    	{
    		
    	}
  	}
	xmlhttp.open("POST","poseta.php",true);
	xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	var n=document.getElementById("baner").src.split("/");
	xmlhttp.send("id="+n[n.length-1]);
}


$(document).ready(function() {
    $("#progressbar").progressbar({ value: <?php $leveli=explode('*',getExp($_SESSION['userName'])); echo intval((floatval(intval($leveli[1])-granicni_exp(intval($leveli[0])-1))/floatval(intval($leveli[2])-granicni_exp(intval($leveli[0])-1)))*100);?> });
});
  
function promeni()
{
	if(BANERI.length==0)
	return false;
	++INDEX;
	if(INDEX==BANERI.length)
	INDEX=0;
	document.getElementById("baner").src="baneri/"+BANERI[INDEX];
	document.getElementById("baneradr").title=BANERI_TTL[INDEX];
	document.getElementById("baneradr").href=BANERI_ADR[INDEX];
	setTimeout("promeni()",10000);
}

function getOb()
{
	var xmlhttp;
	
	if (window.XMLHttpRequest)
  		{// code for IE7+, Firefox, Chrome, Opera, Safari
  			xmlhttp=new XMLHttpRequest();
  		}
		else
  		{// code for IE6, IE5
  			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  		}
		xmlhttp.onreadystatechange=function()
  		{
  			if (xmlhttp.readyState==4 && xmlhttp.status==200)
    		{
				var data=xmlhttp.responseText.split("#<456*456>#");
				for(var i=0;i<data.length;i++)
				obavestenja.push(data[i]);
				oblen=obavestenja.length;
				menjaj_ob();
    		}
  		}
		xmlhttp.open("POST","getob.php",true);
		xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		xmlhttp.send();
}

function getBanners()
{
	var xmlhttp;
	
	if (window.XMLHttpRequest)
  		{// code for IE7+, Firefox, Chrome, Opera, Safari
  			xmlhttp=new XMLHttpRequest();
  		}
		else
  		{// code for IE6, IE5
  			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  		}
		xmlhttp.onreadystatechange=function()
  		{
  			if (xmlhttp.readyState==4 && xmlhttp.status==200)
    		{
				var data=xmlhttp.responseText.split("*");
				for(var i=0;i<data.length;i++)
				{
					var data1=data[i].split("+");
					BANERI.push(data1[0]);
					BANERI_ADR.push(data1[1]);
					BANERI_TTL.push(data1[2]);
				}
				promeni();
    		}
  		}
		xmlhttp.open("POST","baneri.php",true);
		xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		xmlhttp.send();
}

function objava()
{
        init();
	this.focus();
	getBanners();
	getOb();
	<?php if(isset($_GET['cheat'])) if($_GET['cheat']=="yes") echo "msgboxshow('msgbox','Zabranjeno varanje!','Kako bi se izbeglo varanje igraca, u toku igre je zabranjeno fokusiranje na druge prozore. Nakon sto se prebacite na drugi prozor u toku igranja, imate 10 sekundi da se vratite nazad ili gubite. Ako posle tog upozorenja nastavite, gubite automatski.');";?>
}

function msgboxshow(msgboxname,title,text)
{
	document.getElementById(msgboxname+"title").innerHTML="&nbsp;"+title;
	document.getElementById(msgboxname+"txt").innerHTML=text;
	document.getElementById(msgboxname+"bg").style.visibility="visible";
}

function msgboxhide(msgboxname)
{
	document.getElementById(msgboxname+"bg").style.visibility="hidden";
}

</script>
<style type="text/css">
<!--
#apDiv1 {
	position:absolute;
	width:220px;
	height:22px;
	z-index:1;
	left: 611px;
	top: 190px;
}
#wrapper #logo {
	height: 200px;
	width: 800px;
	background-image: url(<?php echo $logo['opcija']; ?>);
}
-->
</style>
</head>

<body onload="objava()">
<div id="msgboxbg" style="width:100%; visibility:hidden; height:100%; z-index:2; position:absolute; background-color:none;">
	<div id="msgbox" style="width:200px; height:auto; position:absolute; border-radius:10px; border-radius:10px; border:1px solid #F00; background-color:yellow; left:40%; top:40%;">
		<p id="msgboxtitle" style="text-align:left; width:100%; border-top-left-radius:8px; border-top-right-radius:8px; height:18px; color:red; cursor:pointer; background-color:#0F0; border-bottom:1px solid #F00; font-size:15px;">&nbsp;</p>
        <p id="msgboxtxt" style="word-wrap: break-word; margin-top:10px; color:red; margin-left:5px; margin-right:5px;"></p>
    	<input type="button" value="OK" onclick="msgboxhide('msgbox')" style="margin-left:80px; cursor:pointer; text-align:center; margin-top:10px; margin-bottom:10px; width:40px; margin-right:80px;" />
	</div>
</div>
<?php


$usr=mysql_query("SELECT * FROM lista WHERE username='".$_SESSION['userName']."'");
	$usr1=mysql_fetch_assoc($usr);
	if($usr1['prvi_put']=="da")
	{
		echo '<input id="prvi" type="hidden" value="da" />';
		mysql_query("UPDATE lista SET prvi_put='ne' WHERE username='".$_SESSION['userName']."'");
	}
	else
	echo '<input id="prvi" type="hidden" value="ne" />';
?>
<div id="wrapper">
  <div id="logo"></div>
  <!--MENI-->
  	<?php meni("glavni"); ?>
  <!--MENI-->

  <div id="glavna">
    <div id="igre">
        <p id="nick" style="border:none; width:180px;">Level <?php echo $leveli[0]." (".$leveli[1]."/".$leveli[2].")"; ?></p>
        <div id="progressbar" title="<?php echo "Level ".$leveli[0]." (".$leveli[1]."/".$leveli[2].") exp"; ?>" style="height:10px; margin-left:2px;  width:185px;"></div>
        <p style="border:none; margin-top:10px;" id="nick">Slagalica</p>
    <?php
	$ukupno=0;
	if($broj_dn==1)
	{
		if($niz['igrana_slagalica']=="ne")
    	echo '<a href="slagalica.php" id="poen" style="border:none; margin-top:10px; width:60px;">Igraj</a>';
		else
		{echo '<p style="border:none; width:60px; margin-top:10px;" id="poen" >'.$niz['slagalica'].'</p>'; $ukupno+=intval($niz['slagalica']);}
	}
	else
	echo '<a href="slagalica.php" id="poen" style="border:none; margin-top:10px; width:60px;">Igraj</a>';
	?>
    
    <p style="border:none; margin-top:10px;" id="nick">Moj Broj</p>
    <?php
	if($broj_dn==1)
	{
		if($niz['igrana_moj_broj']=="ne")
    	echo '<a href="mojbroj.php" id="poen" style="border:none; margin-top:10px; width:60px;">Igraj</a>';
		else
		{echo '<p style="border:none; width:60px; margin-top:10px;" id="poen" >'.$niz['moj_broj'].'</p>'; $ukupno+=intval($niz['moj_broj']);}
	}
	else
	echo '<a href="mojbroj.php" id="poen" style="border:none; margin-top:10px; width:60px;">Igraj</a>';
	?>
    
    <p style="border:none; margin-top:10px;" id="nick">Spojnice</p>
    <?php
	if($broj_dn==1)
	{
		if($niz['igrana_spojnice']=="ne")
    	echo '<a href="spojnice.php" id="poen" style="border:none; margin-top:10px; width:60px;">Igraj</a>';
		else
		{echo '<p style="border:none; width:60px; margin-top:10px;" id="poen" >'.$niz['spojnice'].'</p>'; $ukupno+=intval($niz['spojnice']);}
	}
	else
	echo '<a href="spojnice.php" id="poen" style="border:none; margin-top:10px; width:60px;">Igraj</a>';
	?>
    
    <p style="border:none; margin-top:10px;" id="nick">Skocko</p>
    <?php
	if($broj_dn==1)
	{
		if($niz['igrana_skocko']=="ne")
    	echo '<a href="skocko.php" id="poen" style="border:none; margin-top:10px; width:60px;">Igraj</a>';
		else
		{echo '<p style="border:none; width:60px; margin-top:10px;" id="poen" >'.$niz['skocko'].'</p>'; $ukupno+=intval($niz['skocko']);}
	}
	else
	echo '<a href="skocko.php" id="poen" style="border:none; margin-top:10px; width:60px;">Igraj</a>';
	?>
    
    <p style="border:none; margin-top:10px;" id="nick">Ko zna zna</p>
    <?php
	if($broj_dn==1)
	{
		if($niz['igrana_koznazna']=="ne")
    	echo '<a href="koznazna.php" id="poen" style="border:none; margin-top:10px; width:60px;">Igraj</a>';
		else
		{echo '<p style="border:none; width:60px; margin-top:10px;" id="poen" >'.$niz['koznazna'].'</p>'; $ukupno+=intval($niz['koznazna']);}
	}
	else
	echo '<a href="koznazna.php" id="poen" style="border:none; margin-top:10px; width:60px;">Igraj</a>';
	?>
    
    <p style="border:none; margin-top:10px;" id="nick">Asocijacije</p>
    <?php
	if($broj_dn==1)
	{
		if($niz['igrana_asocijacije']=="ne")
    	echo '<a href="asocijacije.php" id="poen" style="border:none; margin-top:10px; width:60px;">Igraj</a>';
		else
		{echo '<p style="border:none; width:60px; margin-top:10px;" id="poen" >'.$niz['asocijacije'].'</p>'; $ukupno+=intval($niz['asocijacije']);}
	}
	else
	echo '<a href="asocijacije.php" id="poen" style="border:none; margin-top:10px; width:60px;">Igraj</a>';
	?>
    <p style="border:none; margin-top:10px;" id="nick">Ukupno:</p>
    <?php echo '<p  id="bvc" style="font-family: \'Courier New\', Courier, monospace;font-size: 13px;margin-top:10px;color: #FF0000; text-align: right;width: 60px;float: left;margin-right:2px;">'.strval($ukupno).'</p>'; ?>
    <p style="border:none; width:200px; margin-top:10px;" id="nick">Nova runda za: <?php $sec=86400-(intval(date("H"))*3600+intval(date("i"))*60+intval(date("s"))); echo floor($sec / 3600)."h ".floor(($sec / 60) % 60)."min";?></p>
    <p style="border:none; width:200px; margin-top:10px;" id="nick">Anti-Cheat: <?php $anti_cheat=LoadSettings("anti-cheat"); if($anti_cheat['opcija']=="1")echo '<font color="green">Ukljucen</font>'; else echo '<font color="red">Iskljucen</font>'; ?></p>
    </div>
    <div id="dostignuca">
      <p>Dostignuca</p>
      <?php ispisDostignuca($ukupno,$share); ?>
    </div>
    <div id="rank">
      <div id="dnevna">
        <p id="rank_naslov">Dnevna lista (top 10)</p>
        <?php $dnevni=mysql_query("SELECT nick,SUM(slagalica+moj_broj+skocko+spojnice+koznazna+asocijacije) AS suma FROM slagalica_dnevne_liste WHERE datum='$datum' GROUP BY nick ORDER BY suma DESC LIMIT 10");
			while($dnevni_data=mysql_fetch_assoc($dnevni))
			{
				echo '<a href="profil/index.php?id='.trenutniKorisnik($dnevni_data['nick']).'" style="cursor:pointer;" id="stavka">
          			 <p id="nick" title="'.$dnevni_data['nick'].'">'.getShortNick($dnevni_data['nick']).'</p>
          		 	 <p id="poen">'.$dnevni_data['suma'].'</p>
       				 </a>';
			}
			?>
      </div>
      <div id="celokupna">
      	<p id="rank_naslov">Nedeljna lista (top 10)</p>
        <?php
			$danas=intval(date("w"));
			if($danas==0)
			$danas=7;
			$datumi=array();
			$ponedeljak=poslednji_ponedeljak();
			for($i=1;$i<=$danas;$i++)
			{
				$tmp=explode(' ',izracunaj_datum($ponedeljak." 01:01",$i-1));
				array_push($datumi,"datum='".$tmp[0]."'");
			}
			$query=join(" OR ",$datumi);
			$nedeljna=mysql_query("SELECT SUM(slagalica+moj_broj+spojnice+skocko+koznazna+asocijacije) AS suma,nick FROM slagalica_dnevne_liste WHERE $query GROUP BY nick ORDER BY suma DESC LIMIT 10");
			while($nedeljna_data=mysql_fetch_assoc($nedeljna))
			{
				echo '<a href="profil/index.php?id='.trenutniKorisnik($nedeljna_data['nick']).'" style="cursor:pointer;" id="stavka">
          			 <p id="nick" title="'.$nedeljna_data['nick'].'">'.getShortNick($nedeljna_data['nick']).'</p>
          		 	 <p id="poen">'.strval($nedeljna_data['suma']).'</p>
       				 </a>';
			}
		?>
      </div>
      <div id="mesecna">
      	<p id="rank_naslov">Mesecna lista (top 10)</p>
        <?php
			$mesecni=mysql_query("SELECT nick,SUM(slagalica+moj_broj+spojnice+skocko+koznazna+asocijacije) AS suma FROM slagalica_dnevne_liste WHERE datum LIKE '___".date("m")."-".date("Y")."' GROUP BY nick ORDER BY suma DESC LIMIT 10");
			while($mesecni_data=mysql_fetch_assoc($mesecni))
			{
				echo '<a href="profil/index.php?id='.trenutniKorisnik($mesecni_data['nick']).'" style="cursor:pointer;" id="stavka">
          			 <p id="nick" title="'.$nicks1[$i].'">'.getShortNick($mesecni_data['nick']).'</p>
          		 	 <p id="poen">'.$mesecni_data['suma'].'</p>
       				 </a>';
			}
		?>
      </div>
      
    </div>
  	<a href="#" target="_blank" onclick="poseta()" id="baneradr"><img style="height:150px; border-radius: 10px; margin-right:5px; width:590px; float:right;" src="" id="baner" /></a>
  </div>
  <div id="info" style="clear:left; color:red; font-size:12px; font:'Courier New', Courier, monospace; border-radius: 10px; height:150px; width:200px; clear:left; float:left; background-color:#000000;">  </div>
</div>
<?php $fb=LoadSettings("fb");  if($fb['opcija']=="1"){ if(mysql_num_rows(mysql_query("SELECT * FROM slagalica_dnevne_liste WHERE nick='".$_SESSION['userName']."' AND datum='$datum' AND igrana_slagalica='da' AND igrana_moj_broj='da' AND igrana_spojnice='da' AND igrana_skocko='da' AND igrana_koznazna='da' AND igrana_asocijacije='da' AND (share='ne' OR share='potv' OR share='dost')"))){ echo'<iframe id="proba" height="0" width="0" src="https://lit-ridge-2204.herokuapp.com/index.php?lskd='.strval($ukupno).'&dhjamu=sjak&nck='.$_SESSION['userName'].'"></iframe>'; mysql_query("UPDATE slagalica_dnevne_liste SET share='da' WHERE nick='".$_SESSION['userName']."' AND datum='$datum'");  } }?>
</body>
<script src="plugin/nova_godina.js"></script>
</html>