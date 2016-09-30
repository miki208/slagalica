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
	$timer=LoadSettings("skocko-time");
	if(!isset($_GET['datum'])||($datum==date("d-m-Y")))
	if($mikisoftd['skocko']==$datum)
	{
		header("Location: index.php");
		exit();
	}

		if(isset($_SESSION['userName']))
		{
			/////////////////////////DNEVNA LISTA////////////////////////
			$data_dnevno=mysql_query("SELECT * FROM slagalica_dnevne_liste WHERE nick='".$_SESSION['userName']."' AND datum='$datum'");
			if(mysql_num_rows($data_dnevno)==0)
			{
				mysql_query("INSERT INTO slagalica_dnevne_liste(igrana_skocko,datum,nick) VALUES('da','$datum','".$_SESSION['userName']."')");
			}
			else
			{
				mysql_query("UPDATE slagalica_dnevne_liste SET igrana_skocko='da' WHERE nick='".$_SESSION['userName']."' AND datum='$datum'");
			}
			/////////////////////////DNEVNA LISTA KRAJ////////////////////////
			/********ciscenje starih soba*********/
			$pod=mysql_query("SELECT * FROM slagalica_sesije WHERE igra='skocko'");
			$dat=date("Y-m-d H:i:s");
			while($niz=mysql_fetch_assoc($pod))
			{
				$proslo=round(time()-strtotime($niz['datum']));
				if($proslo>(intval($timer['opcija'])+10))
				{
					mysql_query("DELETE FROM slagalica_sesije WHERE id='".$niz['id']."'");
				}
			}
			$pod=mysql_query("SELECT * FROM slagalica_sesije WHERE igra='skocko'");
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
				mysql_query("INSERT INTO slagalica_rank(nick,skocko) VALUES('".$_SESSION['userName']."','$datum')");
			}
			else
			{
				mysql_query("UPDATE slagalica_rank SET skocko='$datum' WHERE nick='".$_SESSION['userName']."'");
			}
			/********dodavanje na rank***********/
			$kombinacije=array("T","S","C","L","D","Z");
			$tacno="";
			for($i=0;$i<4;$i++)
			{
				$tacno.=$kombinacije[rand(0,5)];
			}
			$dat=date("Y-m-d H:i:s");
			mysql_query("INSERT INTO slagalica_sesije(nick,rec,igra,datum) VALUES('".$_SESSION['userName']."','$tacno','skocko','$dat')");
		}
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Skocko</title>
<style type="text/css">
body {
	background-image: url(<?php $bg=LoadSettings("pozadina"); echo $bg['opcija']; ?>);
	background-repeat: repeat;
}
#wrapper {
	font-family: "Courier New", Courier, monospace;
	color: #F00;
	background-image: url(dost-ind1.jpg);
	background-repeat: repeat;
	height: auto;
	width: 800px;
	margin-right: auto;
	margin-left: auto;
	border: 1px solid #F00;
}
</style>
<link href="skocko.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
	var nick="<?php echo $_SESSION['userName']; ?>";
	var ACTIVE=false;
	var kolona=0,vrsta=0;
	var vb="";
	var KRAJ=false;
	var zavr=false;
	
	function vratise()
	{
		window.location="index.php";
	}
	
	function klik(a)
	{
		if(!ACTIVE)
		return;
		document.getElementById(vrsta.toString()+"_"+kolona.toString()).src=a+".png";
		vb+=a;
		++kolona;
		if(kolona==4)
		{
			++vrsta;
			kolona=0;
			salji();
		}
		if(vrsta==7&&KRAJ!=true)
		{
			//kraj igre
			KRAJ=true;
			ACTIVE=false;
			zavrseno();
		}
	}
	
	function brisanje()
	{
		if(!ACTIVE)
		return false;
		if(kolona<1)
		return false;
		vb=vb.substring(0,vb.length-1);
		--kolona;
		document.getElementById(vrsta.toString()+"_"+kolona.toString()).src="U.png";
	}
	
	function zavrseno()
	{
		zavr=true;
		ACTIVE=false;
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
				var podaci=xmlhttp.responseText;
				if(podaci.length==4)
				for(var i=0;i<4;i++)
				{
					document.getElementById("res"+i.toString()).src=podaci[i]+".png";
				}
				setTimeout("vratise()",2000);
    		}
  		}
		xmlhttp.open("POST","session_skocko.php",true);
		xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		xmlhttp.send("nick="+nick);
	}
	
	function salji()
	{
		ACTIVE=false;
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
				var podaci=xmlhttp.responseText;
				var dat=podaci.split("*"); //poen*ok ili pom*no
				if(dat[1]=="no")
				{
					ACTIVE=true;
					for(var i=0;i<4;i++)
					{
						document.getElementById((vrsta-1).toString()+"_"+i.toString()+"p").src=dat[0][i]+".png";
					}
				}
				else
				{
					document.getElementById("poeni").innerHTML=dat[0];
					zavr=true;
					KRAJ=true;
					for(var i=0;i<4;i++)
					{
						document.getElementById((vrsta-1).toString()+"_"+i.toString()+"p").src="Y.png";
					}
					setTimeout("vratise()",2000);
				}
    		}
  		}
		var kom=vb;
		vb="";
		xmlhttp.open("POST","potvrda_skocko.php",true);
		xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		xmlhttp.send("kom="+kom+"&nick="+nick+"&br="+vrsta.toString()<?php if(isset($_GET['datum']))echo "+'&datum=$datum'"; ?>);
	}
	
	function timer()
	{
		var vreme=parseInt(document.getElementById("timer").innerHTML);
		if(KRAJ!=true&&vreme>0)
		{
			ACTIVE=true;
			--vreme;
			document.getElementById("timer").innerHTML=vreme.toString();
			setTimeout("timer()",1000);
		}
		else
		{
			KRAJ=true;
			ACTIVE=false;
			if(zavr==false)
			zavrseno();
		}
	}
	
	function init()
	{
		kolona=0;
		vrsta=0;
		ACTIVE=true;
		KRAJ=false;
		timer();
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
</script>
</head>

<body <?php $anti_cheat=LoadSettings("anti-cheat"); if($anti_cheat['opcija']=="1")echo 'onblur="varanje()"';?> onload="init()">
<!--varanjeeee*/-->
<div id="msgboxbg" style="width:100%; visibility:hidden; height:100%; z-index:2; position:absolute; background-color:#999;">
	<div id="msgbox" style="width:200px; height:auto; position:absolute; border-radius:10px; border-radius:10px; border:1px solid #F00; background-color:yellow; left:40%; top:40%;">
		<p id="msgboxtitle" style="text-align:left; width:100%; border-top-left-radius:8px; border-top-right-radius:8px; height:18px; color:red; cursor:pointer; background-color:#0F0; border-bottom:1px solid #F00; font-size:15px;">&nbsp;</p>
        <p id="msgboxtxt" style="word-wrap: break-word; margin-top:10px; color:red; margin-left:5px; margin-right:5px;"></p>
    	<input type="button" value="OK" onclick="msgboxhide('msgbox')" style="margin-left:80px; cursor:pointer; text-align:center; margin-top:10px; margin-bottom:10px; width:40px; margin-right:80px;" />
	</div>
</div>
<!--varanjeeee*/-->
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
        	<div style="width:500px; margin-left:200px; margin-top:20px;">
            	<img src="T.png" onclick="klik('T')" width="45px" height="45px" style="border-radius:10px;" />
                <img src="S.png" onclick="klik('S')" width="45px" height="45px" style="border-radius:10px;" />
                <img src="C.png" onclick="klik('C')" width="45px" height="45px" style="border-radius:10px;" />
                <img src="L.png" onclick="klik('L')" width="45px" height="45px" style="border-radius:10px;" />
                <img src="D.png" onclick="klik('D')" width="45px" height="45px" style="border-radius:10px;" />
                <img src="Z.png" onclick="klik('Z')" width="45px" height="45px" style="border-radius:10px;" />
                <img src="X.png" onclick="brisanje()" width="45px" height="45px" style="border-radius:10px;" />
            </div>
            
            <div style="width:500px; margin-left:200px; margin-top:20px;">
            	<img id="0_0" src="U.png" width="45px" height="45px" style="border-radius:10px;" />
                <img id="0_1" src="U.png" width="45px" height="45px" style="border-radius:10px;" />
                <img id="0_2" src="U.png" width="45px" height="45px" style="border-radius:10px;" />
                <img id="0_3" src="U.png" width="45px" height="45px" style="border-radius:10px;" />
                <img id="0_0p" src="U.png" width="30px" height="30px" style="border-radius:10px;" />
                <img id="0_1p" src="U.png" width="30px" height="30px" style="border-radius:10px;" />
                <img id="0_2p" src="U.png" width="30px" height="30px" style="border-radius:10px;" />
                <img id="0_3p" src="U.png" width="30px" height="30px" style="border-radius:10px;" />
            </div>
            <div style="width:500px; margin-left:200px; margin-top:20px;">
            	<img id="1_0" src="U.png" width="45px" height="45px" style="border-radius:10px;" />
                <img id="1_1" src="U.png" width="45px" height="45px" style="border-radius:10px;" />
                <img id="1_2" src="U.png" width="45px" height="45px" style="border-radius:10px;" />
                <img id="1_3" src="U.png" width="45px" height="45px" style="border-radius:10px;" />
                <img id="1_0p" src="U.png" width="30px" height="30px" style="border-radius:10px;" />
                <img id="1_1p" src="U.png" width="30px" height="30px" style="border-radius:10px;" />
                <img id="1_2p" src="U.png" width="30px" height="30px" style="border-radius:10px;" />
                <img id="1_3p" src="U.png" width="30px" height="30px" style="border-radius:10px;" />
            </div>
            <div style="width:500px; margin-left:200px; margin-top:20px;">
            	<img id="2_0" src="U.png" width="45px" height="45px" style="border-radius:10px;" />
                <img id="2_1" src="U.png" width="45px" height="45px" style="border-radius:10px;" />
                <img id="2_2" src="U.png" width="45px" height="45px" style="border-radius:10px;" />
                <img id="2_3" src="U.png" width="45px" height="45px" style="border-radius:10px;" />
                <img id="2_0p" src="U.png" width="30px" height="30px" style="border-radius:10px;" />
                <img id="2_1p" src="U.png" width="30px" height="30px" style="border-radius:10px;" />
                <img id="2_2p" src="U.png" width="30px" height="30px" style="border-radius:10px;" />
                <img id="2_3p" src="U.png" width="30px" height="30px" style="border-radius:10px;" />
            </div>
            <div style="width:500px; margin-left:200px; margin-top:20px;">
            	<img id="3_0" src="U.png" width="45px" height="45px" style="border-radius:10px;" />
                <img id="3_1" src="U.png" width="45px" height="45px" style="border-radius:10px;" />
                <img id="3_2" src="U.png" width="45px" height="45px" style="border-radius:10px;" />
                <img id="3_3" src="U.png" width="45px" height="45px" style="border-radius:10px;" />
                <img id="3_0p" src="U.png" width="30px" height="30px" style="border-radius:10px;" />
                <img id="3_1p" src="U.png" width="30px" height="30px" style="border-radius:10px;" />
                <img id="3_2p" src="U.png" width="30px" height="30px" style="border-radius:10px;" />
                <img id="3_3p" src="U.png" width="30px" height="30px" style="border-radius:10px;" />
            </div>
            <div style="width:500px; margin-left:200px; margin-top:20px;">
            	<img id="4_0" src="U.png" width="45px" height="45px" style="border-radius:10px;" />
                <img id="4_1" src="U.png" width="45px" height="45px" style="border-radius:10px;" />
                <img id="4_2" src="U.png" width="45px" height="45px" style="border-radius:10px;" />
                <img id="4_3" src="U.png" width="45px" height="45px" style="border-radius:10px;" />
                <img id="4_0p" src="U.png" width="30px" height="30px" style="border-radius:10px;" />
                <img id="4_1p" src="U.png" width="30px" height="30px" style="border-radius:10px;" />
                <img id="4_2p" src="U.png" width="30px" height="30px" style="border-radius:10px;" />
                <img id="4_3p" src="U.png" width="30px" height="30px" style="border-radius:10px;" />
            </div>
            <div style="width:500px; margin-left:200px; margin-top:20px;">
            	<img id="5_0" src="U.png" width="45px" height="45px" style="border-radius:10px;" />
                <img id="5_1" src="U.png" width="45px" height="45px" style="border-radius:10px;" />
                <img id="5_2" src="U.png" width="45px" height="45px" style="border-radius:10px;" />
                <img id="5_3" src="U.png" width="45px" height="45px" style="border-radius:10px;" />
                <img id="5_0p" src="U.png" width="30px" height="30px" style="border-radius:10px;" />
                <img id="5_1p" src="U.png" width="30px" height="30px" style="border-radius:10px;" />
                <img id="5_2p" src="U.png" width="30px" height="30px" style="border-radius:10px;" />
                <img id="5_3p" src="U.png" width="30px" height="30px" style="border-radius:10px;" />
            </div>
            <div style="width:500px; margin-left:200px; margin-top:20px;">
            	<img id="6_0" src="U.png" width="45px" height="45px" style="border-radius:10px;" />
                <img id="6_1" src="U.png" width="45px" height="45px" style="border-radius:10px;" />
                <img id="6_2" src="U.png" width="45px" height="45px" style="border-radius:10px;" />
                <img id="6_3" src="U.png" width="45px" height="45px" style="border-radius:10px;" />
                <img id="6_0p" src="U.png" width="30px" height="30px" style="border-radius:10px;" />
                <img id="6_1p" src="U.png" width="30px" height="30px" style="border-radius:10px;" />
                <img id="6_2p" src="U.png" width="30px" height="30px" style="border-radius:10px;" />
                <img id="6_3p" src="U.png" width="30px" height="30px" style="border-radius:10px;" />
            </div>
            <div style="width:500px; margin-left:200px; margin-top:20px;">
            	<img id="res0" src="U.png" width="45px" height="45px" style="border-radius:10px;" />
                <img id="res1" src="U.png" width="45px" height="45px" style="border-radius:10px;" />
                <img id="res2" src="U.png" width="45px" height="45px" style="border-radius:10px;" />
                <img id="res3" src="U.png" width="45px" height="45px" style="border-radius:10px;" />
            </div>
        </div>
	</div>
</body>
</html>