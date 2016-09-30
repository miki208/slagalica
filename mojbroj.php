<?php 
	include "connect.php";
	checkUser();
	$datum=(isset($_GET['datum']))?$_GET['datum']:date("d-m-Y");
	$timer=LoadSettings("mojbroj-time");
	if(isset($_SESSION['userName']))
		{
			if(!dozvoljenoIgranje($_SESSION['userName'],$datum))
			{
				header("Location: index.php");
				exit();
			}
			$mikisoft=mysql_query("SELECT * FROM slagalica_rank WHERE nick='".$_SESSION['userName']."'");
			$mikisoftd=mysql_fetch_assoc($mikisoft);
			if(!isset($_GET['datum'])||($datum==date("d-m-Y")))
			if($mikisoftd['moj_broj']==$datum)
			{
				header("Location: index.php");
				exit();
			}
			
				/********ciscenje starih soba*********/
				$pod=mysql_query("SELECT * FROM slagalica_sesije WHERE igra='moj_broj'");
				$dat=date("Y-m-d H:i:s");
				while($niz=mysql_fetch_assoc($pod))
				{
					$proslo=round(time()-strtotime($niz['datum']));
					if($proslo>(intval($timer['opcija'])+10))
					{
						mysql_query("DELETE FROM slagalica_sesije WHERE id='".$niz['id']."'");
					}
				}
				$pod=mysql_query("SELECT * FROM slagalica_sesije WHERE igra='moj_broj'");
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
				/////////////////////////DNEVNA LISTA////////////////////////
				$data_dnevno=mysql_query("SELECT * FROM slagalica_dnevne_liste WHERE nick='".$_SESSION['userName']."' AND datum='$datum'");
				if(mysql_num_rows($data_dnevno)==0)
				{
					mysql_query("INSERT INTO slagalica_dnevne_liste(igrana_moj_broj,datum,nick) VALUES('da','$datum','".$_SESSION['userName']."')");
				}
				else
				{
					mysql_query("UPDATE slagalica_dnevne_liste SET igrana_moj_broj='da' WHERE nick='".$_SESSION['userName']."' AND datum='$datum'");
				}
				/////////////////////////DNEVNA LISTA KRAJ////////////////////////
				if(mysql_num_rows($rankr)==0)
				{
					mysql_query("INSERT INTO slagalica_rank(nick,moj_broj) VALUES('".$_SESSION['userName']."','$datum')");
				}
				else
				{
					mysql_query("UPDATE slagalica_rank SET moj_broj='$datum' WHERE nick='".$_SESSION['userName']."'");
				}
				/********dodavanje na rank***********/
				$trazeni=rand(100,999);
				$prvi=rand(1,9);
				$drugi=rand(1,9);
				$treci=rand(1,9);
				$cetvrti=rand(1,9);
				$niz1=array(10,15,20,25);
				$niz2=array(25,50,75,100);
				$peti=$niz1[rand(0,3)];
				$sesti=$niz2[rand(0,3)];
				$dat=date("Y-m-d H:i:s");
				$brojevi=strval($trazeni)."|".strval($prvi)."|".strval($drugi)."|".strval($treci)."|".strval($cetvrti)."|".strval($peti)."|".strval($sesti);
				mysql_query("INSERT INTO slagalica_sesije(nick,rec,igra,datum) VALUES('".$_SESSION['userName']."','$brojevi','moj_broj','$dat')");
			
		}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Moj Broj</title>
<style type="text/css">
<!--
body {
	background-image: url(<?php $bg=LoadSettings("pozadina"); echo $bg['opcija']; ?>);
	background-repeat: repeat;
}
-->
</style>
<link href="mojbroj.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
	var dodato=new Array();
	var nick=<?php echo '"'.$_SESSION['userName'].'"'; ?>;
	var prvi="";
	var drugi="";
	var treci="";
	var cetvrti="";
	var peti="";
	var sesti="";
	ACTIVE=false;
	
	function vratise()
	{
		window.location="index.php";
	}
	
	function isNum(s)
	{
		if((s=="prvi")||(s=="drugi")||(s=="treci")||(s=="cetvrti")||(s=="peti")||(s=="sesti"))
		{
			return true;
		}
		else
		return false;
	}
	
	function click1(a)
	{
		if(document.getElementById(a).style.backgroundColor=="rgb(153, 153, 153)")
		return;
		if(dodato.length!=0)
		{
			if(isNum(dodato[dodato.length-1])&&isNum(a))
			{
				alert("Nemoguce je uneti dva broja, jedan do drugog.");
				return;
			}
		}
		if(a=="prvi")
		{
			dodato.push("prvi");
			document.getElementById("prvi").style.backgroundColor="rgb(153, 153, 153)";
			document.getElementById("izraz").innerHTML+=prvi;
		}
		if(a=="drugi")
		{
			dodato.push("drugi");
			document.getElementById("drugi").style.backgroundColor="rgb(153, 153, 153)";
			document.getElementById("izraz").innerHTML+=drugi;
		}
		if(a=="treci")
		{
			dodato.push("treci");
			document.getElementById("treci").style.backgroundColor="rgb(153, 153, 153)";
			document.getElementById("izraz").innerHTML+=treci;
		}
		if(a=="cetvrti")
		{
			dodato.push("cetvrti");
			document.getElementById("cetvrti").style.backgroundColor="rgb(153, 153, 153)";
			document.getElementById("izraz").innerHTML+=cetvrti;
		}
		if(a=="peti")
		{
			dodato.push("peti");
			document.getElementById("peti").style.backgroundColor="rgb(153, 153, 153)";
			document.getElementById("izraz").innerHTML+=peti;
		}
		if(a=="sesti")
		{
			dodato.push("sesti");
			document.getElementById("sesti").style.backgroundColor="rgb(153, 153, 153)";
			document.getElementById("izraz").innerHTML+=sesti;
		}
		if(a=="mnozenje")
		{
			dodato.push("mnozenje");
			document.getElementById("izraz").innerHTML+="*";
		}
		if(a=="deljenje")
		{
			dodato.push("deljenje");
			document.getElementById("izraz").innerHTML+="/";
		}
		if(a=="sabiranje")
		{
			dodato.push("sabiranje");
			document.getElementById("izraz").innerHTML+="+";
		}
		if(a=="oduzimanje")
		{
			dodato.push("oduzimanje");
			document.getElementById("izraz").innerHTML+="-";
		}
		if(a=="otvorena")
		{
			dodato.push("otvorena");
			document.getElementById("izraz").innerHTML+="(";
		}
		if(a=="zatvorena")
		{
			dodato.push("zatvorena");
			document.getElementById("izraz").innerHTML+=")";
		}
	}
	
	function brisi()
	{
		if(document.getElementById("brisanje").style.backgroundColor=="rgb(153, 153, 153)")
		return;
		if(dodato.length!=0)
		{
			if(dodato[dodato.length-1]=="prvi")
			{
				document.getElementById("prvi").style.backgroundColor="rgb(255, 255, 0)";
				document.getElementById("izraz").innerHTML=document.getElementById("izraz").innerHTML.substring(0,document.getElementById("izraz").innerHTML.length-prvi.length);
			}
			if(dodato[dodato.length-1]=="drugi")
			{
				document.getElementById("drugi").style.backgroundColor="rgb(255, 255, 0)";
				document.getElementById("izraz").innerHTML=document.getElementById("izraz").innerHTML.substring(0,document.getElementById("izraz").innerHTML.length-drugi.length);
			}
			if(dodato[dodato.length-1]=="treci")
			{
				document.getElementById("treci").style.backgroundColor="rgb(255, 255, 0)";
				document.getElementById("izraz").innerHTML=document.getElementById("izraz").innerHTML.substring(0,document.getElementById("izraz").innerHTML.length-treci.length);
			}
			if(dodato[dodato.length-1]=="cetvrti")
			{
				document.getElementById("cetvrti").style.backgroundColor="rgb(255, 255, 0)";
				document.getElementById("izraz").innerHTML=document.getElementById("izraz").innerHTML.substring(0,document.getElementById("izraz").innerHTML.length-cetvrti.length);
			}
			if(dodato[dodato.length-1]=="peti")
			{
				document.getElementById("peti").style.backgroundColor="rgb(255, 255, 0)";
				document.getElementById("izraz").innerHTML=document.getElementById("izraz").innerHTML.substring(0,document.getElementById("izraz").innerHTML.length-peti.length);
			}
			if(dodato[dodato.length-1]=="sesti")
			{
				document.getElementById("sesti").style.backgroundColor="rgb(255, 255, 0)";
				document.getElementById("izraz").innerHTML=document.getElementById("izraz").innerHTML.substring(0,document.getElementById("izraz").innerHTML.length-sesti.length);
			}
			if(dodato[dodato.length-1]=="mnozenje")
			{
				document.getElementById("izraz").innerHTML=document.getElementById("izraz").innerHTML.substring(0,document.getElementById("izraz").innerHTML.length-1);
			}
			if(dodato[dodato.length-1]=="deljenje")
			{
				document.getElementById("izraz").innerHTML=document.getElementById("izraz").innerHTML.substring(0,document.getElementById("izraz").innerHTML.length-1);
			}
			if(dodato[dodato.length-1]=="sabiranje")
			{
				document.getElementById("izraz").innerHTML=document.getElementById("izraz").innerHTML.substring(0,document.getElementById("izraz").innerHTML.length-1);
			}
			if(dodato[dodato.length-1]=="oduzimanje")
			{
				document.getElementById("izraz").innerHTML=document.getElementById("izraz").innerHTML.substring(0,document.getElementById("izraz").innerHTML.length-1);
			}
			if(dodato[dodato.length-1]=="otvorena")
			{
				document.getElementById("izraz").innerHTML=document.getElementById("izraz").innerHTML.substring(0,document.getElementById("izraz").innerHTML.length-1);
			}
			if(dodato[dodato.length-1]=="zatvorena")
			{
				document.getElementById("izraz").innerHTML=document.getElementById("izraz").innerHTML.substring(0,document.getElementById("izraz").innerHTML.length-1);
			}
			dodato.pop();
		}
	}
	
function potvrda()
	{
		if(document.getElementById("potvrda").style.backgroundColor=="rgb(153, 153, 153)"||ACTIVE==false)
		return;
		
		ACTIVE=false;
		document.getElementById("brisanje").style.backgroundColor="rgb(153, 153, 153)";
		document.getElementById("potvrda").style.backgroundColor="rgb(153, 153, 153)";
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
				if(podaci=="error")
				document.getElementById("izraz").innerHTML+=" - [X]";
				else
				{
					var data=podaci.split("*");
					document.getElementById("izraz").innerHTML+="="+data[0];
					if(parseInt(data[1])>0)
					document.getElementById("izraz").innerHTML+=" - [OK]";
					else
					document.getElementById("izraz").innerHTML+=" - [X]";
					if(data[1]!="undefined")
					document.getElementById("poeni").innerHTML=data[1];
					else
					document.getElementById("poeni").innerHTML="0";
				}
				setTimeout("vratise()",2000);
    		}
  		}
		var izraz=document.getElementById("izraz").innerHTML;
		prvi=document.getElementById("prvi").innerHTML;
		drugi=document.getElementById("drugi").innerHTML;
		treci=document.getElementById("treci").innerHTML;
		cetvrti=document.getElementById("cetvrti").innerHTML;
		peti=document.getElementById("peti").innerHTML;
		sesti=document.getElementById("sesti").innerHTML;
		izraz+="|"+prvi+"*"+drugi+"*"+treci+"*"+cetvrti+"*"+peti+"*"+sesti;
		izraz=izraz.replace(/\+/g,'p');
		xmlhttp.open("POST","potvrda_mojbroj.php",true);
		xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		xmlhttp.send("izraz="+izraz+"&nick="+nick<?php if(isset($_GET['datum']))echo "+'&datum=$datum'"; ?>);
	}
	
	function timer()
	{
		var vreme=parseInt(document.getElementById("timer").innerHTML);
		if(document.getElementById("potvrda").style.backgroundColor!="rgb(153, 153, 153)"&&vreme>0)
		{
			ACTIVE=true;
			--vreme;
			document.getElementById("timer").innerHTML=vreme.toString();
			setTimeout("timer()",1000);
		}
		else
		{
			var dugmici=document.getElementsByName("broj");
			for(var i=0;i<dugmici.length;i++){
			dugmici[i].style.backgroundColor="rgb(153, 153, 153)";
			}
			if(document.getElementById("potvrda").style.backgroundColor!="rgb(153, 153, 153)")
			potvrda();
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
		prvi=document.getElementById("prvi").innerHTML;
		drugi=document.getElementById("drugi").innerHTML;
		treci=document.getElementById("treci").innerHTML;
		cetvrti=document.getElementById("cetvrti").innerHTML;
		peti=document.getElementById("peti").innerHTML;
		sesti=document.getElementById("sesti").innerHTML;
		document.getElementById("prvi").style.backgroundColor="rgb(255, 255, 0)";
		document.getElementById("drugi").style.backgroundColor="rgb(255, 255, 0)";
		document.getElementById("treci").style.backgroundColor="rgb(255, 255, 0)";
		document.getElementById("cetvrti").style.backgroundColor="rgb(255, 255, 0)";
		document.getElementById("peti").style.backgroundColor="rgb(255, 255, 0)";
		document.getElementById("sesti").style.backgroundColor="rgb(255, 255, 0)";
		document.getElementById("brisanje").style.backgroundColor="rgb(255, 255, 0)";
		document.getElementById("potvrda").style.backgroundColor="rgb(255, 255, 0)";
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
          <div id='trazeni' name='broj1' style="margin-top:60px; float:none; width:90px; margin-left:100px; clear:both;" ><?php echo "$trazeni"; ?></div>
          <div id="izraz"></div>
          <div id="brisanje" onclick="brisi()" style="margin-top:-54px; width:50px; float:right; margin-right:88px; clear:right;">X</div>
          <div id="potvrda" onclick="potvrda()" style="margin-top:-54px; width:55px; float:right; margin-right:23px; clear:right;">OK</div>

          <div id="dugmici">
            <div id="prvi" name="broj" onclick="click1('prvi')" style="width:50px;"><?php echo "$prvi"; ?></div>
            <div id="drugi" name="broj" onclick="click1('drugi')" style="margin-left:10px; width:50px;"><?php echo "$drugi"; ?></div>
            <div id="treci" name="broj" onclick="click1('treci')" style="margin-left:10px; width:50px;"><?php echo "$treci"; ?></div>
            <div id="cetvrti" name="broj" onclick="click1('cetvrti')" style="margin-left:10px; width:50px;"><?php echo "$cetvrti"; ?></div>
            <div id="peti" name="broj" onclick="click1('peti')" style="margin-left:10px;"><?php echo "$peti"; ?></div>
            <div id="sesti" name="broj" onclick="click1('sesti')" style="margin-left:10px;"><?php echo "$sesti"; ?></div>
          </div>
          <div id="dugmici" style="clear:both; margin-bottom:150px; margin-top:15px; float:left;">
          	<div id="sabiranje" name="broj" onclick="click1('sabiranje')" style="width:50px;">+</div>
            <div id="oduzimanje" name="broj" onclick="click1('oduzimanje')" style="margin-left:10px; width:50px;">-</div>
            <div id="mnozenje" name="broj" onclick="click1('mnozenje')" style="margin-left:10px; width:50px;">*</div>
            <div id="deljenje" name="broj" onclick="click1('deljenje')" style="margin-left:10px; width:50px;">/</div>
            <div id="otvorena" name="broj" onclick="click1('otvorena')" style="margin-left:10px; width:50px;">(</div>
            <div id="zatvorena" name="broj" onclick="click1('zatvorena')" style="margin-left:10px; width:50px;">)</div>
          </div>
        </div>
  	</div>
</body>
</html>
