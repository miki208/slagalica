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
	if(!isset($_GET['datum'])||($datum==date("d-m-Y")))
	if($mikisoftd['slagalica']==$datum)
	{
		header("Location: index.php");
		exit();
	}
	$anti_cheat=LoadSettings("anti-cheat");
	$timer=LoadSettings("slagalica-time");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Slagalica</title>
<style type="text/css">
<!--
body {
	background-image: url(<?php $bg=LoadSettings("pozadina"); echo $bg['opcija']; ?>);
	background-repeat: repeat;
}
-->
</style>
<link href="slagalica.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">

	function vratise()
	{
		window.location="index.php";
	}

	var nick=<?php echo '"'.$_SESSION['userName'].'"'; ?>;
	var ACTIVE=false;
	var GLOBR="";
	
	function toEntity(temp1)
	{
		var pron=false;
		if(temp1=="đ")
		{
			temp1="&#273;";
			pron=true;
		}
		if(temp1=="nj")
		{
			temp1="nj";
			pron=true;
		}
		if(temp1=="lj")
		{
			temp1="lj";
			pron=true;
		}
		if(temp1=="š")
		{
			temp1="&#353;";
			pron=true;
		}
		if(temp1=="ć")
		{
			temp1="&#263;";
			pron=true;
		}
		if(temp1=="č")
		{
			temp1="&#269;";
			pron=true;
		}
		if(temp1=="dž")
		{
			temp1="d&#382;";
			pron=true;
		}
		if(temp1=="ž")
		{
			temp1="&#382;";
			pron=true;
		}
		return temp1;
	}
	
	function maska(temp1)
	{
		var pron=false;
		if(temp1=="nj")
		{
			temp1="X";
			pron=true;
		}
		if(temp1=="lj")
		{
			temp1="X";
			pron=true;
		}
		if(temp1=="dž")
		{
			temp1="X";
			pron=true;
		}
		if(pron==false)
		temp1="_";
		return temp1;
	}
	
	function kodiraj(rc)
	{
		rc=rc.replace(/đ/g, '1');
		var pozi=0;
		for(var i=0;i<GLOBR.length;i++)
		{
			if(GLOBR[i]=="X")
			{
				if(rc.substring(pozi,pozi+2)=="nj")
				{
					rc=rc.substring(0,pozi)+"92"+rc.substring(pozi+2);
				}
				pozi+=2;
			}
			else
			{
				pozi+=1;
			}
		}
		pozi=0;
		for(var i=0;i<GLOBR.length;i++)
		{
			if(GLOBR[i]=="X")
			{
				if(rc.substring(pozi,pozi+2)=="lj")
				{
					rc=rc.substring(0,pozi)+"93"+rc.substring(pozi+2);
				}
				pozi+=2;
			}
			else
			{
				pozi+=1;
			}
		}
		rc=rc.replace(/š/g, '4');
		rc=rc.replace(/ć/g, '5');
		rc=rc.replace(/č/g, '6');
		pozi=0;
		for(var i=0;i<GLOBR.length;i++)
		{
			if(GLOBR[i]=="X")
			{
				if(rc.substring(pozi,pozi+2)=="dž")
				{
					rc=rc.substring(0,pozi)+"97"+rc.substring(pozi+2);
				}
				pozi+=2;
			}
			else
			{
				pozi+=1;
			}
		}
		rc=rc.replace(/92/g, '2');
		rc=rc.replace(/93/g, '3');
		rc=rc.replace(/97/g, '7');
		rc=rc.replace(/ž/g, '8');
		return rc;
	}
	
	function dekodiraj(rc)
	{
		rc=rc.replace(/1/g, 'đ');
		rc=rc.replace(/2/g, 'nj');
		rc=rc.replace(/3/g, 'lj');
		rc=rc.replace(/4/g, 'š');
		rc=rc.replace(/5/g, 'ć');
		rc=rc.replace(/6/g, 'č');
		rc=rc.replace(/7/g, 'dž');
		rc=rc.replace(/8/g, 'ž');
		return rc;
	}
	
	
	
	function klik(vr)
	{
		var dugmici=document.getElementsByName("slovo");
		var dugmiciC=document.getElementsByName("dugme");
		for(var i=0;i<dugmici.length;i++)
		{
			if(vr==i&&dugmiciC[i].style.backgroundColor!="rgb(153, 153, 153)")
			{
				dugmiciC[i].style.backgroundColor = "rgb(153, 153, 153)";
				document.getElementById("rec_usr").innerHTML+=toEntity(dugmici[i].innerHTML);
				GLOBR+=maska(dugmici[i].innerHTML);
				break;
			}
		}
	}
	
	function timer()
	{
		var vreme=parseInt(document.getElementById("timer").innerHTML);
		if(document.getElementById("potvrdi").style.backgroundColor!="rgb(153, 153, 153)"&&vreme>0)
		{
			ACTIVE=true;
			--vreme;
			document.getElementById("timer").innerHTML=vreme.toString();
			setTimeout("timer()",1000);
		}
		else
		{
			var dugmici=document.getElementsByName("dugme");
			for(var i=0;i<dugmici.length;i++)
			dugmici[i].style.backgroundColor="rgb(153, 153, 153)";
			if(document.getElementById("potvrdi").style.backgroundColor!="rgb(153, 153, 153)")
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
		var dugmiciC=document.getElementsByName("dugme");
		for(var i=0;i<dugmiciC.length;i++)
		{
			dugmiciC[i].style.backgroundColor="rgb(255, 255, 0)";
		}
	}
	
	function potvrda()
	{
		if(ACTIVE==false)
			return;
		ACTIVE=false;
		document.getElementById("potvrdi").style.backgroundColor="rgb(153, 153, 153)";
		document.getElementById("brisi").style.backgroundColor="rgb(153, 153, 153)";
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
				var podaci=xmlhttp.responseText; //priznata rec*nasa rec*broj bodova
				var data=podaci.split("*");
				if(data[0]=="tacno")
				{
					document.getElementById("rec_usr").innerHTML+=" - [OK]";
					document.getElementById("poeni").innerHTML=data[2];
				}
				else
				{
					document.getElementById("rec_usr").innerHTML+=" - [X]";
				}
				document.getElementById("rec_komp").innerHTML=dekodiraj(data[1]);
				setTimeout("vratise()",2000);
    		}
  		}
		var rec=kodiraj(document.getElementById("rec_usr").innerHTML);
		xmlhttp.open("POST","potvrda_slagalica.php",true);
		xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		xmlhttp.send('rec='+rec+'&nick='+nick<?php if(isset($_GET['datum']))echo "+'&datum=$datum'"; ?>);
	}
	
	function brisi()
	{
		var dugmici=document.getElementsByName("slovo");
		var dugmiciC=document.getElementsByName("dugme");
		var text=document.getElementById("rec_usr").innerHTML;
		if(text!="")
		{
			var slovo="";
			if(GLOBR[GLOBR.length-1]=="X")
			{
				slovo=text[text.length-2]+text[text.length-1];
				if(text.length==2)
				text="";
				else
				{
					text=text.substring(0,text.length-2);
				}
			}
			else
			{
				slovo=text[text.length-1];
				if(text.length==1)
				text="";
				else
				{
					text=text.substring(0,text.length-1);
				}
			}
			GLOBR=GLOBR.substring(0,GLOBR.length-1);
			for(var i=0;i<dugmici.length;i++)
			{
				if(dugmici[i].innerHTML==slovo&&dugmiciC[i].style.backgroundColor=="rgb(153, 153, 153)")
				{
					dugmiciC[i].style.backgroundColor="rgb(255, 255, 0)";
					document.getElementById("rec_usr").innerHTML=text;
					break;
				}
			}
			
		}
	}
	
</script>
</head>

<body onload="init()" <?php if($anti_cheat['opcija']=="1")echo 'onblur="varanje()"';?>>
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
		//dozvoljena partija
		/////////////////////////DNEVNA LISTA////////////////////////
		$data_dnevno=mysql_query("SELECT * FROM slagalica_dnevne_liste WHERE nick='".$_SESSION['userName']."' AND datum='$datum'");
		if(mysql_num_rows($data_dnevno)==0)
		{
			mysql_query("INSERT INTO slagalica_dnevne_liste(igrana_slagalica,datum,nick) VALUES('da','$datum','".$_SESSION['userName']."')");
		}
		else
		{
			mysql_query("UPDATE slagalica_dnevne_liste SET igrana_slagalica='da' WHERE nick='".$_SESSION['userName']."' AND datum='$datum'");
		}
		/////////////////////////DNEVNA LISTA KRAJ////////////////////////
		/********ciscenje starih soba*********/
		$pod=mysql_query("SELECT * FROM slagalica_sesije WHERE igra='slagalica'");
		$dat=date("Y-m-d H:i:s");
		while($niz=mysql_fetch_assoc($pod))
		{
			$proslo=round(time()-strtotime($niz['datum']));
			if($proslo>(intval($timer['opcija'])+10))
			{
				mysql_query("DELETE FROM slagalica_sesije WHERE id='".$niz['id']."'");
			}
		}
		$pod=mysql_query("SELECT * FROM slagalica_sesije WHERE igra='slagalica'");
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
			mysql_query("INSERT INTO slagalica_rank(nick,slagalica) VALUES('".$_SESSION['userName']."','$datum')");
		}
		else
		{
			mysql_query("UPDATE slagalica_rank SET slagalica='$datum' WHERE nick='".$_SESSION['userName']."'");
		}
		/********dodavanje na rank***********/
		$data1=mysql_query("SELECT * FROM reci");
		$br1=mysql_num_rows($data1);
		$br=rand(1,$br1);
		$res=mysql_query("SELECT * FROM reci WHERE id='$br'");
		$data=mysql_fetch_assoc($res);
		$rec=$data['rec'];
		$dat=date("Y-m-d H:i:s");
		mysql_query("INSERT INTO slagalica_sesije(nick,rec,igra,datum) VALUES('".$_SESSION['userName']."','$rec','slagalica','$dat')");
		while(strlen($rec)<12)
		{
			$brr=0;
			do
			{
				$brr=rand(97,130);
			}
			while($brr==113||$brr==119||$brr==120||$brr==121);
			switch($brr)
			{
				case 123:
				$rec.="1";
				break;
				case 124:
				$rec.="2";
				break;
				case 125:
				$rec.="3";
				break;
				case 126:
				$rec.="4";
				break;
				case 127:
				$rec.="5";
				break;
				case 128:
				$rec.="6";
				break;
				case 129:
				$rec.="7";
				break;
				case 130:
				$rec.="8";
				break;
				default:
				$rec.=chr($brr);
				break;
			}
			
		}
		$temp=$rec;
		$rec="";
		while(strlen($temp)!=0)
		{
			if(strlen($temp)==1)
			{
				$rec.=$temp[0];
				$temp="";
			}
			else
			{
				$n=rand(0,strlen($temp)-1);
				$rec.=$temp[$n];
				$temp1=$temp;
				$temp="";
				for($i=0;$i<strlen($temp1);$i++)
				{
					if($i!=$n)
					$temp.=$temp1[$i];
				}
			}
		}?>
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
    <div id="dugmici">
      <?php
	  	for($i=0;$i<strlen($rec);$i++)
	  	echo '<div onclick="klik('.strval($i).')" name="dugme" id="dugme">
        <p name="slovo">'.toEntity($rec[$i]).'</p>
      	</div>';}
	  ?>
    </div>
    <div id="rec">
      <p id="rec_usr"></p>
    </div>
    <div id="brisi" onclick="brisi()" style="float:left; border:solid 2px #FF0000; cursor:pointer; text-align:center; background-color:yellow; float:right; margin-right:130px; margin-top:20px; width:40px; height:40px; color:red; font-size:30px;">
      <p>X</p>
    </div>
    <div id="komp">
    	<p id="rec_komp"></p>
    </div>
    <div id="potvrdi" onclick="potvrda()" style="float:left; border:solid 2px #FF0000; cursor:pointer; text-align:center; background-color:yellow; float:right; margin-right:130px; margin-top:10px; width:50px; height:40px; color:red; font-size:30px;">
      <p>OK</p>
    </div>
  </div>
</div>
</body>
</html>