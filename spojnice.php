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
	$timer=LoadSettings("spojnice-time");
	if(!isset($_GET['datum'])||($datum==date("d-m-Y")))
	if($mikisoftd['spojnice']==$datum)
	{
		header("Location: index.php");
		exit();
	}

				$data_dnevno=mysql_query("SELECT * FROM slagalica_dnevne_liste WHERE nick='".$_SESSION['userName']."' AND datum='$datum'");
				if(mysql_num_rows($data_dnevno)==0)
				{
					mysql_query("INSERT INTO slagalica_dnevne_liste(igrana_spojnice,datum,nick) VALUES('da','$datum','".$_SESSION['userName']."')");
				}
				else
				{
					mysql_query("UPDATE slagalica_dnevne_liste SET igrana_spojnice='da' WHERE nick='".$_SESSION['userName']."' AND datum='$datum'");
				}
				/////////////////////////DNEVNA LISTA KRAJ////////////////////////
				/********ciscenje starih soba*********/
				$pod=mysql_query("SELECT * FROM slagalica_sesije WHERE igra='spojnice'");
				$dat=date("Y-m-d H:i:s");
				while($niz=mysql_fetch_assoc($pod))
				{
					$proslo=round(time()-strtotime($niz['datum']));
					if($proslo>(intval($timer['opcija'])+10))
					{
						mysql_query("DELETE FROM slagalica_sesije WHERE id='".$niz['id']."'");
					}
				}
				$pod=mysql_query("SELECT * FROM slagalica_sesije WHERE igra='spojnice'");
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
					mysql_query("INSERT INTO slagalica_rank(nick,spojnice) VALUES('".$_SESSION['userName']."','$datum')");
				}
				else
				{
					mysql_query("UPDATE slagalica_rank SET spojnice='$datum' WHERE nick='".$_SESSION['userName']."'");
				}
				/********dodavanje na rank***********/
				$data=mysql_query("SELECT * FROM spojnice");
				$broj_spojnica=mysql_num_rows($data);
				$id=rand(1,$broj_spojnica);
				$data=mysql_query("SELECT * FROM spojnice WHERE id='$id'");
				$sp=mysql_fetch_assoc($data);
				$dat=date("Y-m-d H:i:s");
				mysql_query("INSERT INTO slagalica_sesije(nick,rec,igra,datum) VALUES('".$_SESSION['userName']."','".$sp['naslov']."=".$sp['spojnice']."','spojnice','$dat')");
				
				$rows=explode('*',$sp['spojnice']);
				
				$prvi=array();
				$drugi=array();
				for($i=0;$i<count($rows);$i++)
				{
					$polje=explode('!',$rows[$i]);
					array_push($prvi,$polje[0]);
					array_push($drugi,$polje[1]);
				}
				shuffle($prvi);
				shuffle($drugi);
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Spojnice</title>
<style type="text/css">
body {
	background-image: url(<?php $bg=LoadSettings("pozadina"); echo $bg['opcija']; ?>);
}
</style>
<link href="spojnice.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
	
	var nick="<?php echo $_SESSION['userName']; ?>";
	
	var CON_1="none";
	var CON_2="none";
	
	var ACTIVE=false;
	var POTVRDI_DISABLED=false;
	
	var potvrdjeno=false;
	
	var BOJE=Array("#663300","#000000","#808080","#720026","#3e436a","#bda400","#eb0258","#6e87cc");
	
	function vratise()
	{
		window.location="index.php";
	}
	
	var listaSelektovanih=Array();
	
	function selektovanaOba()
	{
		if((CON_1!="none")&&(CON_2!="none"))
		{
			listaSelektovanih.push(CON_1+"-"+CON_2);
			document.getElementById(CON_1).style.backgroundColor=BOJE[parseInt(CON_1[CON_1.length-1])];
			document.getElementById(CON_2).style.backgroundColor=BOJE[parseInt(CON_1[CON_1.length-1])];

			CON_1=CON_2="none";
		}
	}
	
	function klikPovezani(a)
	{
		var pos=-1;
		for(var i=0;i<listaSelektovanih.length;i++)
		{
			if(listaSelektovanih[i].indexOf(a)!=-1)
			{
				pos=i;
				break;
			}
		}
		if(pos==-1)
		return;
		var selektovani=listaSelektovanih[pos].split("-");
		document.getElementById(selektovani[0]).style.backgroundColor="#06C";
		document.getElementById(selektovani[1]).style.backgroundColor="#06C";
		listaSelektovanih.splice(pos,1);
	}
	
	function isSpojeno(a)
	{
		for(var i=0;i<listaSelektovanih.length;i++)
		{
			if(listaSelektovanih[i].indexOf(a)!=-1)
			{
				return true;
			}
		}
		return false;
	}
	
	function isOdabran(a)
	{
		if((a==CON_1)||(a==CON_2))
		return true;
		return false;
	}
	
	function klikSelektovani(a)
	{
		if(a==CON_1)
		CON_1="none";
		else
		CON_2="none";
		document.getElementById(a).style.backgroundColor="#06C";
	}
	
	function dvaKliknuta(a)
	{
		if(a.indexOf("prv_")!=-1)
		{
			if(CON_1!="none")
			{
				document.getElementById(CON_1).style.backgroundColor="#06C";
				document.getElementById(a).style.backgroundColor="#C30";
				CON_1=a;
				return true;
			}
			else return false;
		}
		else
		{
			if(CON_2!="none")
			{
				document.getElementById(CON_2).style.backgroundColor="#06C";
				document.getElementById(a).style.backgroundColor="#C30";
				CON_2=a;
				return true;
			}
			else return false;
		}
	}
	
	function timer()
	{
		var vreme=parseInt(document.getElementById("timer").innerHTML);
		if(POTVRDI_DISABLED!=true&&vreme>0)
		{
			ACTIVE=true;
			--vreme;
			document.getElementById("timer").innerHTML=vreme.toString();
			setTimeout("timer()",1000);
		}
		else
		{
			if(POTVRDI_DISABLED==false)
			potvrda();
		}
	}
	
	function selected(a)
	{
		if(potvrdjeno==true)
		return;
		if(isSpojeno(a)==true)
		klikPovezani(a);
		else
		{
			if(isOdabran(a)==true)
			klikSelektovani(a);
			else
			{
				if(dvaKliknuta(a)==true)
				return;
				document.getElementById(a).style.backgroundColor="#C30";
				if(a.indexOf("prv_")!=-1)
				CON_1=a;
				else
				CON_2=a;
				selektovanaOba();
			}
		}
	}
	
	function init()
	{
		ACTIVE=true;
		timer();
	}
	
	function resenja(a)
	{
		var temp="";
		var t=0,f=0;
		var spl=a.split("*");
		var listaF=Array();
		var listaT=Array();
		for(var i=0;i<8;i++)
		{
			t=0,f=0;
			temp=document.getElementById("prv_"+i.toString()).innerHTML;
			for(var x=0;x<listaSelektovanih.length;x++)
			{
				var p=listaSelektovanih[x].split("-");
				if(document.getElementById(p[0]).innerHTML.indexOf(temp)!=-1)
				{
					f=1;
					for(var y=0;y<spl.length;y++)
					{
						if(spl[y]==(document.getElementById(p[0]).innerHTML+"!"+document.getElementById(p[1]).innerHTML))
						{
							t=1;
							break;
						}
					}
					break;
				}	
			}
			listaF.push(f);
			listaT.push(t);
		}
		
		for(var i=0;i<8;i++)
		{
			var prom=Array();
			temp=document.getElementById("prv_"+i.toString()).innerHTML;
			for(var x=0;x<spl.length;x++)
			{
				if(spl[x].indexOf(temp)!=-1)
				{
					prom=spl[x].split("!");
					break;
				}
			}
			if(listaF[i]==0)
			{
				document.getElementById("prv_"+i.toString()).style.backgroundColor="#06C";
				document.getElementById("dru_"+i.toString()).style.backgroundColor="#06C";
				document.getElementById("dru_"+i.toString()).innerHTML=prom[1];
			}
			else
			{
				if(listaT[i]==1)
				{
					document.getElementById("prv_"+i.toString()).style.backgroundColor="#00FF00";
					document.getElementById("dru_"+i.toString()).style.backgroundColor="#00FF00";
					document.getElementById("dru_"+i.toString()).innerHTML=prom[1];
				}
				else
				{
					document.getElementById("prv_"+i.toString()).style.backgroundColor="#FF0000";
					document.getElementById("dru_"+i.toString()).style.backgroundColor="#FF0000";
					document.getElementById("dru_"+i.toString()).innerHTML=prom[1];
				}
			}
		}
		setTimeout("vratise()",2000);
	}
	
	function potvrda()
	{
		if(POTVRDI_DISABLED)
		return false;
		ACTIVE=false;
		POTVRDI_DISABLED=true;
		document.getElementById("potvrdi").style.backgroundColor="#33FF33";
		potvrdjeno=true;
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
				var splitovano=podaci.split("=");
				document.getElementById("poeni").innerHTML=splitovano[0];
				resenja(splitovano[1]);
    		}
  		}
		var data="";
		
		for(var i=0;i<listaSelektovanih.length;i++)
		{
			if(data!="")
			data+="*";
			var selektovani=listaSelektovanih[i].split("-");
			data+=document.getElementById(selektovani[0]).innerHTML+"!"+document.getElementById(selektovani[1]).innerHTML;
		}
		xmlhttp.open("POST","potvrda_spojnice.php",true);
		xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		xmlhttp.send("data="+data+"&nick="+nick<?php if(isset($_GET['datum']))echo "+'&datum=$datum'"; ?>);
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
        	<div id="naslov" title="<?php echo $sp['naslov']; ?>" style="color:white; background-color:#06C; margin-top:20px; margin-left:30px; margin-right:30px; float:left; width:740px; height:21px; border-radius:10px; font-size:20px; text-align:left; overflow:hidden;"><?php echo $sp['naslov']; ?></div>
        
        	<div id="prv_0" title="<?php echo $prvi[0]; ?>" onclick="selected('prv_0')" style="color:white; background-color:#06C; margin-top:20px; margin-left:30px; float:left; width:350px; height:40px; cursor:pointer; border-radius:10px; font-size:38px; text-align:center; overflow:hidden;"><?php echo $prvi[0]; ?></div>
            <div id="dru_0" title="<?php echo $drugi[0]; ?>" onclick="selected('dru_0')" style="color:white; background-color:#06C; margin-top:20px; margin-right:30px; float:right; width:350px; height:40px; cursor:pointer; border-radius:10px; font-size:38px; text-align:center; overflow:hidden;"><?php echo $drugi[0]; ?></div>
            
            <div id="prv_1" title="<?php echo $prvi[1]; ?>" onclick="selected('prv_1')" style="color:white; background-color:#06C; margin-top:20px; margin-left:30px; float:left; width:350px; height:40px; cursor:pointer; border-radius:10px; font-size:38px; text-align:center; overflow:hidden;"><?php echo $prvi[1]; ?></div>
            <div id="dru_1" title="<?php echo $drugi[1]; ?>" onclick="selected('dru_1')" style="color:white; background-color:#06C; margin-top:20px; margin-right:30px; float:right; width:350px; height:40px; cursor:pointer; border-radius:10px; font-size:38px; text-align:center; overflow:hidden;"><?php echo $drugi[1]; ?></div>
            
            <div id="prv_2" title="<?php echo $prvi[2]; ?>" onclick="selected('prv_2')" style="color:white; background-color:#06C; margin-top:20px; margin-left:30px; float:left; width:350px; height:40px; cursor:pointer; border-radius:10px; font-size:38px; text-align:center; overflow:hidden;"><?php echo $prvi[2]; ?></div>
            <div id="dru_2" title="<?php echo $drugi[2]; ?>" onclick="selected('dru_2')" style="color:white; background-color:#06C; margin-top:20px; margin-right:30px; float:right; width:350px; height:40px; cursor:pointer; border-radius:10px; font-size:38px; text-align:center; overflow:hidden;"><?php echo $drugi[2]; ?></div>
            
            <div id="prv_3" title="<?php echo $prvi[3]; ?>" onclick="selected('prv_3')" style="color:white; background-color:#06C; margin-top:20px; margin-left:30px; float:left; width:350px; height:40px; cursor:pointer; border-radius:10px; font-size:38px; text-align:center; overflow:hidden;"><?php echo $prvi[3]; ?></div>
            <div id="dru_3" title="<?php echo $drugi[3]; ?>" onclick="selected('dru_3')" style="color:white; background-color:#06C; margin-top:20px; margin-right:30px; float:right; width:350px; height:40px; cursor:pointer; border-radius:10px; font-size:38px; text-align:center; overflow:hidden;"><?php echo $drugi[3]; ?></div>
            
            <div id="prv_4" title="<?php echo $prvi[4]; ?>" onclick="selected('prv_4')" style="color:white; background-color:#06C; margin-top:20px; margin-left:30px; float:left; width:350px; height:40px; cursor:pointer; border-radius:10px; font-size:38px; text-align:center; overflow:hidden;"><?php echo $prvi[4]; ?></div>
            <div id="dru_4" title="<?php echo $drugi[4]; ?>" onclick="selected('dru_4')" style="color:white; background-color:#06C; margin-top:20px; margin-right:30px; float:right; width:350px; height:40px; cursor:pointer; border-radius:10px; font-size:38px; text-align:center; overflow:hidden;"><?php echo $drugi[4]; ?></div>
            
            <div id="prv_5" title="<?php echo $prvi[5]; ?>" onclick="selected('prv_5')" style="color:white; background-color:#06C; margin-top:20px; margin-left:30px; float:left; width:350px; height:40px; cursor:pointer; border-radius:10px; font-size:38px; text-align:center; overflow:hidden;"><?php echo $prvi[5]; ?></div>
            <div id="dru_5" title="<?php echo $drugi[5]; ?>" onclick="selected('dru_5')" style="color:white; background-color:#06C; margin-top:20px; margin-right:30px; float:right; width:350px; height:40px; cursor:pointer; border-radius:10px; font-size:38px; text-align:center; overflow:hidden;"><?php echo $drugi[5]; ?></div>
            
            <div id="prv_6" title="<?php echo $prvi[6]; ?>" onclick="selected('prv_6')" style="color:white; background-color:#06C; margin-top:20px; margin-left:30px; float:left; width:350px; height:40px; cursor:pointer; border-radius:10px; font-size:38px; text-align:center; overflow:hidden;"><?php echo $prvi[6]; ?></div>
            <div id="dru_6" title="<?php echo $drugi[6]; ?>" onclick="selected('dru_6')" style="color:white; background-color:#06C; margin-top:20px; margin-right:30px; float:right; width:350px; height:40px; cursor:pointer; border-radius:10px; font-size:38px; text-align:center; overflow:hidden;"><?php echo $drugi[6]; ?></div>
            
            <div id="prv_7" title="<?php echo $prvi[7]; ?>" onclick="selected('prv_7')" style="color:white; background-color:#06C; margin-top:20px; margin-left:30px; float:left; width:350px; height:40px; cursor:pointer; border-radius:10px; font-size:38px; text-align:center; overflow:hidden;"><?php echo $prvi[7]; ?></div>
            <div id="dru_7" title="<?php echo $drugi[7]; ?>" onclick="selected('dru_7')" style="color:white; background-color:#06C; margin-top:20px; margin-right:30px; float:right; width:350px; height:40px; cursor:pointer; border-radius:10px; font-size:38px; text-align:center; overflow:hidden;"><?php echo $drugi[7]; ?></div>
            <div id="potvrdi" onclick="potvrda()" style="color:white; background-color:#06C; margin-top:20px; margin-right:310px; margin-left:310px; float:right; width:180px; height:40px; cursor:pointer; border-radius:10px; font-size:38px; text-align:center; margin-bottom:30px; overflow:hidden;">POTVRDI</div>
        </div>
	</div>
</body>
</html>