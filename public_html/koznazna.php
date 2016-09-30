<?php
	include "connect.php";
	checkUser();
	$datum=(isset($_GET['datum']))?$_GET['datum']:date("d-m-Y");
	if(!dozvoljenoIgranje($_SESSION['userName'],$datum))
	{
		header("Location: index.php");
		exit();
	}
	$mikisoft=mysql_query("SELECT * FROM slagalica_rank WHERE nick='".$_SESSION['userName']."'");
	$mikisoftd=mysql_fetch_assoc($mikisoft);
	$timer=LoadSettings("koznazna-time");
	if(!isset($_GET['datum'])||($datum==date("d-m-Y")))
	if($mikisoftd['koznazna']==$datum)
	{
		header("Location: index.php");
		exit();
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Ko zna zna</title>
<style type="text/css">
<!--
body {
	background-image: url(<?php $bg=LoadSettings("pozadina"); echo $bg['opcija']; ?>);
	background-repeat: repeat;
}
-->
</style>
<link href="koznazna.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
	var nick=<?php echo '"'.$_SESSION['userName'].'"'; ?>;
	var ACTIVE=false;
	var ENABLED=false;
	var ODG="";
	var GLOB="";
	var IDS=new Array("A","B","C","D");
	
	function vratise()
	{
		window.location="index.php";
	}
	
	function postavi()
	{
		var data=GLOB.split("|");
		GLOB="";
		for(var i=0;i<4;i++)
		{
			document.getElementById(IDS[i]).style.backgroundColor="#06C";
			document.getElementById(IDS[i]).innerHTML=data[i];
		}
		document.getElementById("x").style.backgroundColor="#06C";
		document.getElementById("aaa").innerHTML=data[4];
		document.getElementById("kategorija").innerHTML=data[9];
		ENABLED=true;
	}
	
	function klik(a)
	{
		if(!ENABLED)
		return false;
		ENABLED=false;
		ODG=a;
		if(ODG=="x")
		document.getElementById("x").style.backgroundColor="rgb(255, 153, 0)";
		else
		document.getElementById(IDS[parseInt(ODG)]).style.backgroundColor="rgb(255, 153, 0)";
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
				var podaci=xmlhttp.responseText;
				var data=podaci.split("|");
				document.getElementById("poeni").innerHTML=(parseInt(document.getElementById("poeni").innerHTML)+parseInt(data[8])).toString();
				document.getElementById(IDS[parseInt(data[6])]).style.backgroundColor="rgb(0, 255, 0)";
				if((ODG!=data[6])&&(ODG!="x"))
				document.getElementById(IDS[parseInt(ODG)]).style.backgroundColor="rgb(255, 0, 0)";
				if(data[5]=="kraj")
				{
					ENABLED=false;
					ACTIVE=false;
					document.getElementById("aaa").style.textAlign="center";
					document.getElementById("aaa").innerHTML="KRAJ!";
					document.getElementById("aaa").style.fontSize="60px";
					setTimeout("vratise()",2000);
				}
				else
				{
					GLOB=podaci;
					setTimeout("postavi()",1500);
				}
    		}
		}
		xmlhttp.open("POST","procesiraj_pitanje.php",true);
		xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		xmlhttp.send("nick="+nick+"&odg="+a<?php if(isset($_GET['datum']))echo "+'&datum=$datum'"; ?>);
	}
	
	function prvo_pitanje()
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
				var podaci=xmlhttp.responseText;
				var data=podaci.split("|");
				document.getElementById("aaa").innerHTML=data[4];
				for(var i=0;i<4;i++)
				document.getElementById(IDS[i]).innerHTML=data[i];
				document.getElementById("kategorija").innerHTML=data[5];
				ENABLED=true;
    		}
		}
		xmlhttp.open("POST","init_pitanje.php",true);
		xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		xmlhttp.send("nick="+nick);
	}
	
	function timer()
	{
		var vreme=parseInt(document.getElementById("timer").innerHTML);
		if((ACTIVE==true)&&(vreme>0))
		{
			--vreme;
			document.getElementById("timer").innerHTML=vreme.toString();
			setTimeout("timer()",1000);
		}
		else
		{
			if(vreme==0)
			{
				ACTIVE=false;
			}	
			ENABLED=false;
			setTimeout("vratise()",2000);
		}
	}
	
	/*varanjeeee*/
	var warn_op=0;
	var warn_sec=0;
	var upozoren=0;
	function varanje()
	{
		if(ACTIVE)
		{
			if(warn_op!=1)
			{
				if(upozoren==1)
				window.location="index.php?cheat=yes";
				else
				{
					warn_sec=10;
					upozoren=1;
					warn_op=1;
					msgboxshow('msgbox','Anti-Cheat','Zabranjeno varanje! Imate jos <font style="color:#00F;">'+warn_sec+'</font> sekundi da kliknete na OK ili automatski gubite partiju! Zabranjeno fokusiranje na druge prozore u toku igre!');
					setTimeout("prekini_igru()",warn_sec*1000);
					setTimeout("msgboxodbr()",1000);
				}
			}
		}
	}
	function prekini_igru()
	{
		if(warn_op==1)
		{
			window.location="index.php?cheat=yes";
		}
	}
	function msgboxodbr()
	{
		if(warn_sec!=0)
		{
			--warn_sec;
			document.getElementById("msgboxtxt").innerHTML='Zabranjeno varanje! Imate jos <font style="color:#00F;">'+warn_sec+'</font> sekundi da kliknete na OK ili automatski gubite partiju! Zabranjeno fokusiranje na druge prozore u toku igre!';
			setTimeout("msgboxodbr()",1000);
		}
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
		warn_op=0;
	}
	/*varanjeeee*/
	
	function init()
	{
		ACTIVE=true;
		timer();
		prvo_pitanje();
	}
</script>

</head>

<body onload="init()" <?php $anti_cheat=LoadSettings("anti-cheat"); if($anti_cheat['opcija']=="1")echo 'onblur="varanje()"';?>>
<!--varanjeeee*/-->
<div id="msgboxbg" style="width:100%; visibility:hidden; height:100%; z-index:2; position:absolute; background-color:#999;">
	<div id="msgbox" style="width:200px; height:auto; position:absolute; border-radius:10px; border-radius:10px; border:1px solid #F00; background-color:yellow; left:40%; top:40%;">
		<p id="msgboxtitle" style="text-align:left; width:100%; border-top-left-radius:8px; border-top-right-radius:8px; height:18px; color:red; cursor:pointer; background-color:#0F0; border-bottom:1px solid #F00; font-size:15px;">&nbsp;</p>
        <p id="msgboxtxt" style="word-wrap: break-word; margin-top:10px; color:red; margin-left:5px; margin-right:5px;"></p>
    	<input type="button" value="OK" onclick="msgboxhide('msgbox')" style="margin-left:80px; cursor:pointer; text-align:center; margin-top:10px; margin-bottom:10px; width:40px; margin-right:80px;" />
	</div>
</div>
<!--varanjeeee*/-->
<?php
		if(isset($_SESSION['userName']))
		{
				/////////////////////////DNEVNA LISTA////////////////////////
				$data_dnevno=mysql_query("SELECT * FROM slagalica_dnevne_liste WHERE nick='".$_SESSION['userName']."' AND datum='$datum'");
				if(mysql_num_rows($data_dnevno)==0)
				{
					mysql_query("INSERT INTO slagalica_dnevne_liste(igrana_koznazna,datum,nick) VALUES('da','$datum','".$_SESSION['userName']."')");
				}
				else
				{
					mysql_query("UPDATE slagalica_dnevne_liste SET igrana_koznazna='da' WHERE nick='".$_SESSION['userName']."' AND datum='$datum'");
				}
				/////////////////////////DNEVNA LISTA KRAJ////////////////////////
				/********ciscenje starih soba*********/
				$pod=mysql_query("SELECT * FROM slagalica_sesije WHERE igra='koznazna'");
				$dat=date("Y-m-d H:i:s");
				while($niz=mysql_fetch_assoc($pod))
				{
					$proslo=round(time()-strtotime($niz['datum']));
					if($proslo>(intval($timer['opcija'])+10))
					{
						mysql_query("DELETE FROM slagalica_sesije WHERE id='".$niz['id']."'");
					}
				}
				$pod=mysql_query("SELECT * FROM slagalica_sesije WHERE igra='koznazna'");
				while($niz=mysql_fetch_assoc($pod))
				{
					if($niz['nick']==$_SESSION['userName'])
					{
						mysql_query("DELETE FROM slagalica_sesije WHERE id='".$niz['id']."'");
					}
				}
				/********ciscenje starih soba*********/
				/********dodavanje na rank***********/
				$rankr=mysql_query("SELECT * FROM slagalica_rank WHERE nick='".$_SESSION['userName']."'");
				if(mysql_num_rows($rankr)==0)
				{
					mysql_query("INSERT INTO slagalica_rank(nick,koznazna) VALUES('".$_SESSION['userName']."','01-01-2001')");
				}
				else
				{
					mysql_query("UPDATE slagalica_rank SET koznazna='$datum' WHERE nick='".$_SESSION['userName']."'");
				}
				/********dodavanje na rank***********/
				$dat=date("Y-m-d H:i:s");
				mysql_query("INSERT INTO slagalica_sesije(nick,rec,igra,datum) VALUES('".$_SESSION['userName']."','$datum','koznazna','$dat')");
		}
	?>
<div id="wrapper">
	<div id="bar">
    	<div id="points">
      		<p id="poeni">0</p>
    	</div>
    	<div id="time">
      		<p id="timer"><?php echo $timer['opcija']; ?></p>
    	</div>
  	</div>
    <div id="igra">
      <div id="kategorija"></div>
      <div id="pitanje">
        <p id="aaa"></p>
      </div>
      <div id="A" onclick="klik('0')"></div>
      <div id="B" onclick="klik('1')"></div>
      <div id="C" onclick="klik('2')"></div>
      <div id="D" onclick="klik('3')"></div>
      <div id="x" onclick="klik('x')">Bez odgovora</div>
    </div>
</div>
</body>
</html>
