<?php
	include "asocijacije_lib.php";
	checkUser();
	$datum=(isset($_GET['datum']))?$_GET['datum']:date("d-m-Y");
	if(!dozvoljenoIgranje($_SESSION['userName'],$datum))
	{
		header("Location: index.php");
		exit();
	}
	$mikisoft=mysql_query("SELECT * FROM slagalica_rank WHERE nick='".$_SESSION['userName']."'");
	$mikisoftd=mysql_fetch_assoc($mikisoft);
	$timer=LoadSettings("asocijacije-time");
	if(!isset($_GET['datum'])||($datum==date("d-m-Y")))
	if($mikisoftd['asocijacije']==$datum)
	{
		header("Location: index.php");
		exit();
	}

		$data_dnevno=mysql_query("SELECT * FROM slagalica_dnevne_liste WHERE nick='".$_SESSION['userName']."' AND datum='$datum'");
				if(mysql_num_rows($data_dnevno)==0)
				{
					mysql_query("INSERT INTO slagalica_dnevne_liste(igrana_asocijacije,datum,nick) VALUES('da','$datum','".$_SESSION['userName']."')");
				}
				else
				{
					mysql_query("UPDATE slagalica_dnevne_liste SET igrana_asocijacije='da' WHERE nick='".$_SESSION['userName']."' AND datum='$datum'");
				}
				/////////////////////////DNEVNA LISTA KRAJ////////////////////////
				/********ciscenje starih soba*********/
				$pod=mysql_query("SELECT * FROM slagalica_sesije WHERE igra='asocijacije'");
				$dat=date("Y-m-d H:i:s");
				while($niz=mysql_fetch_assoc($pod))
				{
					$proslo=round(time()-strtotime($niz['datum']));
					if($proslo>(intval($timer['opcija'])+10))
					{
						mysql_query("DELETE FROM slagalica_sesije WHERE id='".$niz['id']."'");
					}
				}
				$pod=mysql_query("SELECT * FROM slagalica_sesije WHERE igra='asocijacije'");
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
					mysql_query("INSERT INTO slagalica_rank(nick,asocijacije) VALUES('".$_SESSION['userName']."','$datum')");
				}
				else
				{
					mysql_query("UPDATE slagalica_rank SET asocijacije='$datum' WHERE nick='".$_SESSION['userName']."'");
				}
				/********dodavanje na rank***********/
				$dat=date("Y-m-d H:i:s");
				
				$asoc=sastavi_asocijaciju();
				$ubazu=$asoc[0][0]."*".$asoc[1][0]."*".$asoc[1][1]."*".$asoc[1][2]."*".$asoc[1][3]."*".$asoc[2][0][0]."*".$asoc[2][0][1]."*".$asoc[2][0][2]."*".$asoc[2][0][3]."*".$asoc[2][1][0]."*".$asoc[2][1][1]."*".$asoc[2][1][2]."*".$asoc[2][1][3]."*".$asoc[2][2][0]."*".$asoc[2][2][1]."*".$asoc[2][2][2]."*".$asoc[2][2][3]."*".$asoc[2][3][0]."*".$asoc[2][3][1]."*".$asoc[2][3][2]."*".$asoc[2][3][3]."*0*0";//0-resenje,1-4 kolone,5-8,9-12,13-16,17-20
				
				mysql_query("INSERT INTO slagalica_sesije(nick,rec,igra,datum) VALUES('".$_SESSION['userName']."','$ubazu','asocijacije','$dat')");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Asocijacije</title>
<style type="text/css">
body {
	background-image: url(<?php $bg=LoadSettings("pozadina"); echo $bg['opcija']; ?>);
	background-repeat: repeat;
}
			
</style>
		<!-- Dependencies -->
		<script src="jquery.js" type="text/javascript"></script>
		<script src="jquery.ui.draggable.js" type="text/javascript"></script>
		
        
        <script type="text/javascript">
	var nick="<?php echo $_SESSION['userName']; ?>";
	var ACTIVE=false;
	var SPOREDNA=false;
	var ENABLED=false;
	var KONACNO=false; 
	var priv="";
	var POLJA=Array("A1","A2","A3","A4","B1","B2","B3","B4","C1","C2","C3","C4","D1","D2","D3","D4");
	var POLJA_ENABLED=Array(true,true,true,true,true,true,true,true,true,true,true,true,true,true,true,true);
	var SPOREDNO=Array("S1","S2","S3","S4");
	var SPOREDNO_ENABLED=Array(true,true,true,true);
	var raz;
	
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
	
	function vratise()
	{
		window.location="index.php";
	}
	
	function mogucnost()
	{
		for(var i=0;i<POLJA_ENABLED.length;i++)
		if(POLJA_ENABLED[i]==true)
		return true;
		return false;
	}
	
	function konacno()
	{
		if(!ENABLED)
		return false;
		if((KONACNO==false)&&(mogucnost()==true))
		{
			if(SPOREDNA){
				msgboxshow('msgbox1','Asocijacije','Mozete samo da otvorite novo polje ili da pogodite sporednu asocijaciju.');
					}
			else
			{
				msgboxshow('msgbox1','Asocijacije','Mozete samo da otvorite novo polje.');
			}
			return false;
		}
		inpboxshow('Konacno resenje','Unesi konacno resenje...','konacno');
	}
	
	function precisti(s)
	{
		var temp="";
		for(var i=0;i<s.length;i++)
		if(((s[i].charCodeAt(0)>=48)&&(s[i].charCodeAt(0)<=90))||((s[i].charCodeAt(0)>=97)&&(s[i].charCodeAt(0)<=122))||(s[i].charCodeAt(0)==32)||(s[i]=='.')||(s[i]=='/'))
		temp+=s[i];
		return temp;
	}
	
	function sporedna(a)
	{
		priv=a;
		var b=document.getElementById(a);
		if(SPOREDNO_ENABLED[SPOREDNO_ENABLED.indexOf(a)]==false)
		return false;
		if(!ENABLED)
		return false;
		if((SPOREDNA==false)&&(mogucnost()==true))
		{
			if(KONACNO){
						msgboxshow('msgbox1','Asocijacije','Mozete samo da otvorite novo polje ili da pokusate konacno resenje.');
					}
			else
			{
				msgboxshow('msgbox1','Asocijacije','Mozete samo da otvorite novo polje.');
			}
			return false;
		}
		inpboxshow('Sporedno resenje','Unesite sporedno resenje...','sporedno');
	}
	
	function otkrij(a)
	{
		if(POLJA_ENABLED[POLJA.indexOf(a)]==false)
		return false;
		if(!ENABLED)
		return false;
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
				var podaci=xmlhttp.responseText; //polje
				document.getElementById(a).innerHTML=podaci;
				document.getElementById(a).title=podaci;
				document.getElementById(a).style.backgroundColor="rgb(255, 0, 0)";
				SPOREDNA=true;
				KONACNO=true;
				POLJA_ENABLED[POLJA.indexOf(a)]=false;
    		}
  		}
		xmlhttp.open("POST","otkrij_asocijacija.php",true);
		xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		xmlhttp.send("polje="+a+"&nick="+nick);
	}
	
	function kraj_rezultat()
	{
		if(!ENABLED)
		return false;
		ENABLED=false;
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
				var podaci=xmlhttp.responseText; //bodovi*res1;..
				var data=podaci.split("*");
				document.getElementById("poeni").innerHTML=data[0];
				var resenja=data[1].split(";");
				for(var i=0;i<POLJA.length;i++)
				{
					document.getElementById(POLJA[i]).innerHTML=resenja[i];
					document.getElementById(POLJA[i]).title=resenja[i];
					document.getElementById(POLJA[i]).style.backgroundColor="#060";
					POLJA_ENABLED[i]=false;
				}
				for(var i=0;i<SPOREDNO.length;i++)
				{
					document.getElementById(SPOREDNO[i]).innerHTML=resenja[i+16];
					document.getElementById(SPOREDNO[i]).title=resenja[i+16];
					document.getElementById(SPOREDNO[i]).style.backgroundColor="#060";
					SPOREDNO_ENABLED[i]=false;
				}
				document.getElementById("konacno").innerHTML=resenja[20];
				document.getElementById("konacno").title=resenja[20];
				document.getElementById("konacno").style.backgroundColor="#060";
				setTimeout("vratise()",2000);
    		}
  		}

		xmlhttp.open("POST","kraj_rezultat.php",true);
		xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		xmlhttp.send("nick="+nick<?php if(isset($_GET['datum']))echo "+'&datum=$datum'"; ?>);
	}

	function potvrdi()
	{
		if(ACTIVE)
		{
			ACTIVE=false;
			kraj_rezultat();
		}
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
				kraj_rezultat();
			}
		}
	}
	
	function init()
	{
		ACTIVE=true;
		ENABLED=true;
		timer();
	}
	
	/*input*/
	function inpboxshow(title,deftxt,reason)
	{
		document.getElementById("inpboxtitle").innerHTML="&nbsp;"+title;
		document.getElementById("inpboxtxt").value=deftxt;
		document.getElementById("inpboxbg").style.visibility="visible";
		document.getElementById("inpboxtxt").focus();
    	document.getElementById("inpboxtxt").select();
		ENABLED=false;
		raz=reason;
	}
	
	function inpboxok(stts)
	{
		if(raz=="konacno")
		{
		if( stts=="enter" ){
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
				var podaci=xmlhttp.responseText; //ok|no*resenje
				var resenja=podaci.split("*");
				if(resenja[0]=="ok")
				{
					ACTIVE=false;
					msgboxshow('msgbox1','Asocijacije','Bravo! Ovo je tacno resenje.');
					//////////////////////////////////////////////////
				for(var i=0;i<POLJA.length;i++)
				{
					document.getElementById(POLJA[i]).innerHTML=resenja[i+1];
					document.getElementById(POLJA[i]).title=resenja[i+1];
					document.getElementById(POLJA[i]).style.backgroundColor="#060";
					POLJA_ENABLED[i]=false;
				}
				for(var i=0;i<SPOREDNO.length;i++)
				{
					document.getElementById(SPOREDNO[i]).innerHTML=resenja[i+17];
					document.getElementById(SPOREDNO[i]).title=resenja[i+17];
					document.getElementById(SPOREDNO[i]).style.backgroundColor="#060";
					SPOREDNO_ENABLED[i]=false;
				}
				document.getElementById("konacno").innerHTML=resenja[21];
				document.getElementById("konacno").title=resenja[21];
				document.getElementById("konacno").style.backgroundColor="#060";
				document.getElementById("poeni").innerHTML=resenja[22];
				setTimeout("vratise()",2000);
				////////////////////////////////
				}
				else
				{
				msgboxshow('msgbox1','Asocijacije','Ovo nije tacno resenje.');
				SPOREDNA=false;
				KONACNO=false;
				ENABLED=true;
				}
			}
  		}
			ENABLED=false;
			var r=document.getElementById("inpboxtxt").value;
			r=precisti(r);
			r=r.toLowerCase();
			xmlhttp.open("POST","konacna_asocijacija.php",true);
			xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
			xmlhttp.send("as="+r+"&nick="+nick<?php if(isset($_GET['datum']))echo "+'&datum=$datum'"; ?>);
			}else{priv=""; ENABLED=true;}
		}
		if(raz=="sporedno")
		{
			if( stts=="enter" ){
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
				var podaci=xmlhttp.responseText; //ok|no*resenje
				var res=podaci.split("*");
				if(res[0]=="ok")
				{
					ENABLED=true;
					SPOREDNA=false;
					KONACNO=true;
					document.getElementById(priv).innerHTML=res[1];
					document.getElementById(priv).title=res[1];
					document.getElementById(priv).style.backgroundColor="#060";
					var ind=SPOREDNO.indexOf(priv);
					SPOREDNO_ENABLED[ind]=false;
					var b=2;
					for(var i=4*ind;i<(ind+1)*4;i++)
					{
						document.getElementById(POLJA[i]).innerHTML=res[b];
						document.getElementById(POLJA[i]).title=res[b];
						document.getElementById(POLJA[i]).style.backgroundColor="#060";
						POLJA_ENABLED[i]=false;
						++b;
					}
				}
				else
				{
				msgboxshow('msgbox1','Asocijacije','Ovo nije tacno resenje.');
				SPOREDNA=false;
				KONACNO=false;
				}
			ENABLED=true;
			priv="";
			}
  		}
			var r=document.getElementById("inpboxtxt").value;
			r=precisti(r);
			r=r.toLowerCase();
			xmlhttp.open("POST","sporedna_asocijacija.php",true);
			xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
			xmlhttp.send("polje="+priv+"&as="+r+"&nick="+nick);
			}else{priv=""; ENABLED=true;}
		}
		document.getElementById("inpboxbg").style.visibility="hidden";
	}
	/*input*/
	
</script>
        
<link href="asocijacije.css" rel="stylesheet" type="text/css" />
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
<!--dialog*/-->
<div id="msgbox1bg" style="width:100%; visibility:hidden; height:100%; z-index:1; position:absolute; background-color:none;">
	<div id="msgbox1" style="width:400px; height:auto; position:absolute; border-radius:10px; border-radius:10px; border:1px solid #F00; background-color:yellow; left:40%; top:40%;">
		<p id="msgbox1title" style="text-align:left; width:100%; border-top-left-radius:8px; border-top-right-radius:8px; height:18px; color:red; cursor:pointer; background-color:#0F0; border-bottom:1px solid #F00; font-size:15px;">&nbsp;</p>
        <p id="msgbox1txt" style="word-wrap: break-word; margin-top:10px; color:red; margin-left:5px; margin-right:5px;"></p>
    	<input type="button" value="OK" onclick="msgboxhide('msgbox1')" style="margin-left:180px; cursor:pointer; text-align:center; margin-top:10px; margin-bottom:10px; width:40px; margin-right:180px;" />
	</div>
</div>
<!--dialog*/-->
<!--prompt*/-->
<div id="inpboxbg" style="width:100%; visibility:hidden; height:100%; z-index:1; position:absolute; background-color:none;">
	<div id="inpbox" style="width:300px; height:auto; position:absolute; border-radius:10px; border-radius:10px; border:1px solid #F00; background-color:yellow; left:40%; top:40%;">
		<p id="inpboxtitle" style="text-align:left; width:100%; border-top-left-radius:8px; border-top-right-radius:8px; height:18px; color:red; cursor:pointer; background-color:#0F0; border-bottom:1px solid #F00; font-size:15px;">&nbsp;</p>
        <input type="text" id="inpboxtxt" style="width:250px; margin-top:10px; margin-left:25px; margin-right:25px; color:red; text-align:left;" />
    	<input type="button" value="OK" onclick="inpboxok('enter')" style="margin-left:75px; cursor:pointer; text-align:center; margin-top:10px; margin-bottom:10px; width:40px;" />
        <input type="button" value="Cancel" onclick="inpboxok('cancel')" style="cursor:pointer; text-align:center; margin-top:10px; margin-bottom:10px; width:60px; margin-right:75px;" />
	</div>
</div>
<!--prompt*/-->
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
       	  <div id="A1" onclick="otkrij('A1')" style="color:#FFF; cursor:pointer; overflow:hidden; background-color:#06C; border-radius:10px; font-family:'Courier New', Courier, monospace; font-size:35px; margin-left:80px; float:left; width:280px; margin-top:20px; text-align:center; height:36px;">A1</div>
          <div id="B1" onclick="otkrij('B1')" style="color:#FFF; cursor:pointer; overflow:hidden; background-color:#06C; border-radius:10px; font-family:'Courier New', Courier, monospace; font-size:35px; margin-right:80px; float:right; margin-top:20px; width:280px; text-align:center; height:36px;">B1</div>
            
          <div id="A2" onclick="otkrij('A2')" style="color:#FFF; cursor:pointer; overflow:hidden; background-color:#06C; border-radius:10px; font-family:'Courier New', Courier, monospace; font-size:35px; margin-left:80px; float:left; width:280px; margin-top:10px; text-align:center; height:36px;">A2</div>
          <div id="B2" onclick="otkrij('B2')" style="color:#FFF; cursor:pointer; overflow:hidden;; background-color:#06C; border-radius:10px; font-family:'Courier New', Courier, monospace; font-size:35px; margin-right:80px; float:right; margin-top:10px; width:280px; text-align:center; height:36px;">B2</div>
            
          <div id="A3" onclick="otkrij('A3')" style="color:#FFF; cursor:pointer; overflow:hidden; background-color:#06C; border-radius:10px; font-family:'Courier New', Courier, monospace; font-size:35px; margin-left:80px; float:left; width:280px; margin-top:10px; text-align:center; height:36px;">A3</div>
          <div id="B3" onclick="otkrij('B3')" style="color:#FFF; cursor:pointer; overflow:hidden; background-color:#06C; border-radius:10px; font-family:'Courier New', Courier, monospace; font-size:35px; margin-right:80px; float:right; margin-top:10px; width:280px; text-align:center; height:36px;">B3</div>
            
          <div id="A4" onclick="otkrij('A4')" style="color:#FFF; cursor:pointer; overflow:hidden; background-color:#06C; border-radius:10px; font-family:'Courier New', Courier, monospace; font-size:35px; margin-left:80px; float:left; width:280px; margin-top:10px; text-align:center; height:36px;">A4</div>
          <div id="B4" onclick="otkrij('B4')" style="color:#FFF; cursor:pointer; overflow:hidden; background-color:#06C; border-radius:10px; font-family:'Courier New', Courier, monospace; font-size:35px; margin-right:80px; float:right; margin-top:10px; width:280px; text-align:center; height:36px;">B4</div>
            
          <div id="S1" onclick="sporedna('S1')" style="color:#FFF; cursor:pointer; overflow:hidden; background-color:#06C; border-radius:10px; font-family:'Courier New', Courier, monospace; font-size:35px; margin-left:110px; float:left; width:280px; margin-top:10px; text-align:center; height:36px;">A</div>
          <div id="S2" onclick="sporedna('S2')" style="color:#FFF; cursor:pointer; overflow:hidden; background-color:#06C; border-radius:10px; font-family:'Courier New', Courier, monospace; font-size:35px; margin-right:110px; float:right; margin-top:10px; width:280px; text-align:center; height:36px;">B</div>
            
          <div id="konacno" onclick="konacno()" style="color:#FFF; cursor:pointer; overflow:hidden; background-color:#06C; border-radius:10px; font-family:'Courier New', Courier, monospace; font-size:35px; margin-left:265px; float:left; margin-top:10px; width:270px; text-align:center; height:36px;">????</div>
            
          <div id="S3" onclick="sporedna('S3')" style="color:#FFF; cursor:pointer; overflow:hidden; background-color:#06C; border-radius:10px; font-family:'Courier New', Courier, monospace; font-size:35px; margin-left:110px; float:left; width:280px; margin-top:10px; text-align:center; height:36px;">C</div>
          <div id="S4" onclick="sporedna('S4')" style="color:#FFF; cursor:pointer; overflow:hidden; background-color:#06C; border-radius:10px; font-family:'Courier New', Courier, monospace; font-size:35px; margin-right:110px; float:right; margin-top:10px; width:280px; text-align:center; height:36px;">D</div>
            
          <div id="C4" onclick="otkrij('C4')" style="color:#FFF; cursor:pointer; overflow:hidden; background-color:#06C; border-radius:10px; font-family:'Courier New', Courier, monospace; font-size:35px; margin-left:80px; float:left; width:280px; margin-top:10px; text-align:center; height:36px;">C4</div>
          <div id="D4" onclick="otkrij('D4')" style="color:#FFF; cursor:pointer; overflow:hidden; background-color:#06C; border-radius:10px; font-family:'Courier New', Courier, monospace; font-size:35px; margin-right:80px; float:right; margin-top:10px; width:280px; text-align:center; height:36px;">C4</div>
            
          <div id="C3" onclick="otkrij('C3')" style="color:#FFF; cursor:pointer; overflow:hidden; background-color:#06C; border-radius:10px; font-family:'Courier New', Courier, monospace; font-size:35px; margin-left:80px; float:left; width:280px; margin-top:10px; text-align:center; height:36px;">C3</div>
          <div id="D3" onclick="otkrij('D3')" style="color:#FFF; cursor:pointer; overflow:hidden; background-color:#06C; border-radius:10px; font-family:'Courier New', Courier, monospace; font-size:35px; margin-right:80px; float:right; margin-top:10px; width:280px; text-align:center; height:36px;">D3</div>
            
          <div id="C2" onclick="otkrij('C2')" style="color:#FFF; cursor:pointer; overflow:hidden; background-color:#06C; border-radius:10px; font-family:'Courier New', Courier, monospace; font-size:35px; margin-left:80px; float:left; width:280px; margin-top:10px; text-align:center; height:36px;">C2</div>
          <div id="D2" onclick="otkrij('D2')" style="color:#FFF; cursor:pointer; overflow:hidden; background-color:#06C; border-radius:10px; font-family:'Courier New', Courier, monospace; font-size:35px; margin-right:80px; float:right; margin-top:10px; width:280px; text-align:center; height:36px;">D2</div>
            
          <div id="C1" onclick="otkrij('C1')" style="color:#FFF; cursor:pointer; overflow:hidden; background-color:#06C; border-radius:10px; font-family:'Courier New', Courier, monospace; font-size:35px; margin-left:80px; float:left; width:280px; margin-top:10px; margin-bottom:20px; text-align:center; height:36px;">C1</div>
          <div id="D1" onclick="otkrij('D1')" style="color:#FFF; cursor:pointer; overflow:hidden; background-color:#06C; border-radius:10px; font-family:'Courier New', Courier, monospace; font-size:35px; margin-right:80px; float:right; margin-top:10px; width:280px; text-align:center; height:36px;">D1</div>
          
          <div onclick="potvrdi()" style="color:#FFF; cursor:pointer; overflow:hidden; background-color:#06C;  border-radius:10px; font-family:'Courier New', Courier, monospace; font-size:35px; margin-left:265px; float:left; margin-top:10px; width:270px; margin-bottom:20px; text-align:center; height:36px;">ODUSTANI</div>
        </div>
	</div>
</body>
</html>