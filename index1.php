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

<html>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Mikisoft Slagalica</title>
	<link href="progresscss.css" rel="stylesheet" type="text/css"/>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>
	<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
    
    <style>
		.ui-progressbar .ui-progressbar-value { background-color:#33FF33; }
		body { background-image: url(<?php $bg=LoadSettings("pozadina"); echo $bg['opcija']; ?>);
				background-repeat: repeat;}
	</style>
    
    <!--MENI-->
  	<?php meni_css(); ?>
<!--MENI-->

<link href="index.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
/*
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
*/
function getOb()
{
	
	$.post("getob.php",
    function(data, textStatus, jqXHR)
    {
		alert(data);
    });
}

/*function getBanners()
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
}*/

    
    
</script>
</head>
<body onLoad="getOb()">
</body>
</html>