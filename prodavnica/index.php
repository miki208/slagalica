<?php
	include "../connect.php";
	checkUser();
	$nick=$_SESSION['userName'];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Prodavnica</title>
<!--MENI-->
  	<?php meni_css(); ?>
<!--MENI-->
<style type="text/css">
body {
	background-image: url(<?php $bg=LoadSettings("pozadina"); echo "../".$bg['opcija']; ?>);
	background-repeat: repeat;
}
* {
	margin: 0px;
}
#wrapper {
	
	color: #F00;
	background-image: url(../dost-ind1.jpg);
	background-repeat: repeat;
	height: auto;
	width: 800px;
	margin-right: auto;
	margin-left: auto;
	border: 1px solid #F00;
}
</style>

<script type="text/javascript">
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
	
	function init()
	{
		<?php
			if(isset($_GET['err']))
			{
				switch($_GET['err'])
				{
					case "no":
						echo "msgboxshow('msgbox','Kupljeno','Uspesno ste kupili izabrane proizvode.');";
						break;
					case "none":
						echo "msgboxshow('msgbox','Neuspesno','Nemate dovoljno tokena.</br>Mozete sacekati dnevni bonus ili iskoristiti promociju.');";
						break;
					case "empty":
						echo "msgboxshow('msgbox','Neuspesno','Niste izabrali proizvode za kupovinu.');";
						break;
				}
			}
			if(isset($_GET['errp']))
			{
				switch($_GET['errp'])
				{
					case "us":
						echo "msgboxshow('msgbox','Promo','Promocija ne postoji ili je istekla.');";
						break;
					case "ui":
						echo "msgboxshow('msgbox','Promo','Vec ste iskoristili ovu promociju.');";
						break;
					case "tkn":
						echo "msgboxshow('msgbox','Promo','Promocija uspesno aktivirana. Tokeni uplaceni.');";
						break;
					case "unkprom":
						echo "msgboxshow('msgbox','Promo','Ovakav tip promocije ne postoji.');";
						break;
					case "ns":
						echo "msgboxshow('msgbox','Promo','Promo kod nije unet.');";
						break;
					case "exp":
						echo "msgboxshow('msgbox','Promo','Promocija uspesno aktivirana. Iskustvo dodato.');";
						break;
					case "pi":
						echo "msgboxshow('msgbox','Promo','Promocija uspesno aktivirana. PI uplaceni.');";
						break;
					case "rp":
						echo "msgboxshow('msgbox','Promo','Promocija uspesno aktivirana. Reputacija dodata.');";
						break;
					case "kl":
						echo "msgboxshow('msgbox','Promo','Promocija uspesno aktivirana. Kljucevi dodati.');";
						break;
				}
			}
		?>
	}
	
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
	 
	 function racunaj()
	 {
		 var cena1=parseInt(document.getElementById("pi").value)*95;
		 var cena2=parseInt(document.getElementById("exp").value)*3;
		 var cena3=parseInt(document.getElementById("rep").value)*20;
		 var cena4=parseInt(document.getElementById("start").value)*380;
		 var cena5=parseInt(document.getElementById("extra").value)*1350;
		 var cena6=parseInt(document.getElementById("premium").value)*3500;
		 var cena7=parseInt(document.getElementById("smart").value)*850;
		 var cena8=parseInt(document.getElementById("legendary").value)*2500;
		 var cena9=parseInt(document.getElementById("vip").value)*1100;
		 var cena10=parseInt(document.getElementById("kljuc").value)*250;
		 if(isNaN(cena1)==true)
		 cena1=0;
		 if(isNaN(cena2)==true)
		 cena2=0;
		 if(isNaN(cena3)==true)
		 cena3=0;
		 if(isNaN(cena4)==true)
		 cena4=0;
		 if(isNaN(cena5)==true)
		 cena5=0;
		 if(isNaN(cena6)==true)
		 cena6=0;
		 if(isNaN(cena7)==true)
		 cena7=0;
		 if(isNaN(cena8)==true)
		 cena8=0;
		 if(isNaN(cena9)==true)
		 cena9=0;
		 if(isNaN(cena10)==true)
		 cena10=0;
		 document.getElementById("cena").innerHTML='<img src="../images/kasa.png" style="vertical-align:middle;" width="20" height="20" /> <font style="color:blue; font:bold;">'+(cena1+cena2+cena3+cena4+cena5+cena6+cena7+cena8+cena9+cena10).toString()+'</font>';
	 }
	
</script>
</head>

<body onload="init()">
<div id="msgboxbg" style="width:100%; visibility:hidden; height:100%; font-family: 'Courier New', Courier, monospace; z-index:2; position:absolute; background-color:none;">
	<div id="msgbox" style="width:300px; height:auto; position:absolute; border-radius:10px; border-radius:10px; border:1px solid #F00; background-color:yellow; left:35%; top:30%;">
		<p id="msgboxtitle" style="text-align:left; width:100%; border-top-left-radius:8px; border-top-right-radius:8px; height:18px; color:red; cursor:pointer; background-color:#0F0; border-bottom:1px solid #F00; font-size:15px;">&nbsp;</p>
        <p id="msgboxtxt" style="word-wrap: break-word; margin-top:10px; color:red; margin-left:5px; margin-right:5px;"></p>
    	<input type="button" value="OK" onclick="msgboxhide('msgbox')" style="margin-left:130px; cursor:pointer; text-align:center; margin-top:10px; margin-bottom:10px; width:40px; margin-right:130px;" />
	</div>
</div>
	<div id="wrapper" style="">
    	<!--MENI-->
  		<?php meni("prodavnica"); ?>
  		<!--MENI-->
        <form action="promo.php" method="post" style="margin-left:40px; font-family: 'Courier New', Courier, monospace; margin-bottom:10px; width:720px; height:40px; border:1px solid red; border-radius:10px; background-image:url(../rank-ind1.png); color:red; margin-top:10px;">
        <p style="margin-left:5px; margin-top:10px; float:left;">Unesite promo kod: </p>
        <input type="text" maxlength="8" name="secret" style="margin-top:10px; margin-left:5px; float:left; color:red; background-color:yellow; border-radius:5px;"/>
        <input type="submit" style="float:left; margin-left:5px; border-radius:10px; margin-top:10px; background-color:yellow; color:red;" value="Potvrdi" />
        </form>
      <form action="kupi.php" style="font-family: 'Courier New', Courier, monospace;" method="post">
            <div id="stavka" style="background-color:yellow; float:left; margin-left:40px; border-radius:10px; width:150px; height:250px;">
            	<img src="../images/ponistavanje_igre_paket.png" style="border-bottom:1px solid red;" width="150" height="150" />
                <label for="pi" style="color:red;">Paket za ponistavanje igre [PI]</label>
                </br>
                <div style="text-align:center; border-top:1px solid red;">
                	<p style="color:blue; margin-top:10px; margin-left:5px; float:left; font:bold;">95.0</p>
                	<input type="text" id="pi" oninput="racunaj()" name="pi" onkeypress="validate()" maxlength="4" value="0" style="width:40px; float:right; margin-right:5px; margin-top:10px; color:red; font:bold;" />
                </div>
            </div>
            
            <div id="stavka" style="background-color:yellow; float:left; margin-left:40px; border-radius:10px; width:150px; height:250px;">
            	<img src="../images/exp_paket.png" style="border-bottom:1px solid red;" width="150" height="150" />
                <label for="exp" style="color:red;">Iskustvo [EXP]</label>
                </br>
                <div style="text-align:center; border-top:1px solid red;">
                	<p style="color:blue; margin-top:10px; margin-left:5px; float:left; font:bold;">3.0</p>
                	<input type="text" id="exp" oninput="racunaj()" name="exp" onkeypress="validate()" maxlength="4" value="0" style="width:40px; float:right; margin-right:5px; margin-top:10px; color:red; font:bold;" />
                </div>
            </div>
            
            <div id="stavka" style="background-color:yellow; float:left; margin-left:40px; border-radius:10px; width:150px; height:250px;">
            	<img src="../images/rep_paket.png" style="border-bottom:1px solid red;" width="150" height="150" />
                <label for="rep" style="color:red;">Reputacija [RP]</label>
                </br>
                <div style="text-align:center; border-top:1px solid red;">
                	<p style="color:blue; margin-top:10px; margin-left:5px; float:left; font:bold;">20.0</p>
                	<input type="text" id="rep" oninput="racunaj()" name="rep" onkeypress="validate()" maxlength="4" value="0" style="width:40px; float:right; margin-right:5px; margin-top:10px; color:red; font:bold;" />
                </div>
            </div>
            
            <div id="stavka" style="background-color:yellow; float:left; margin-left:40px; border-radius:10px; width:150px; height:250px;">
            	<img src="../images/kljuc.png" style="border-bottom:1px solid red;" width="150" height="150" />
                <label for="vip" style="color:red;">Kljuc</label>
                </br>
                <div style="text-align:center; border-top:1px solid red;">
                	<p style="color:blue; margin-top:10px; margin-left:5px; float:left; font:bold;">250.0</p>
                	<input type="text" id="kljuc" oninput="racunaj()" name="kljuc" onkeypress="validate()" maxlength="4" value="0" style="width:40px; float:right; margin-right:5px; margin-top:10px; color:red; font:bold;" />
                </div>
            </div>
            
            <div id="stavka" style="background-color:yellow; float:left; margin-left:40px; margin-top:20px; border-radius:10px; width:150px; height:250px;">
            	<img src="../images/start_paket.png" style="border-bottom:1px solid red;" width="150" height="150" />
                <label for="start" style="color:red;">Start Pack [50EXP+5RP+2PI]</label>
                </br>
                <div style="text-align:center; border-top:1px solid red;">
                	<p style="color:blue; margin-top:10px; margin-left:5px; float:left; font:bold;">380.0</p>
                	<input type="text" id="start" oninput="racunaj()" name="start" onkeypress="validate()" maxlength="4" value="0" style="width:40px; float:right; margin-right:5px; margin-top:10px; color:red; font:bold;" />
                </div>
            </div>
            
            <div id="stavka" style="background-color:yellow; float:left; margin-left:40px; margin-top:20px; border-radius:10px; width:150px; height:250px;">
            	<img src="../images/extra_paket.png" style="border-bottom:1px solid red;" width="150" height="150" />
                <label for="extra" style="color:red;">Extra Pack [200EXP + 20RP + 5PI]</label>
                </br>
                <div style="text-align:center; border-top:1px solid red;">
                	<p style="color:blue; margin-top:10px; margin-left:5px; float:left; font:bold;">1350.0</p>
                	<input type="text" id="extra" oninput="racunaj()" name="extra" onkeypress="validate()" maxlength="4" value="0" style="width:40px; float:right; margin-right:5px; margin-top:10px; color:red; font:bold;" />
                </div>
            </div>
            
            <div id="stavka" style="background-color:yellow; float:left; margin-left:40px; margin-top:20px; border-radius:10px; width:150px; height:250px;">
            	<img src="../images/premium_paket.png" style="border-bottom:1px solid red;" width="150" height="150" />
                <label for="premium" style="color:red;">Premium Pack [500EXP + 50RP + 15PI]</label>
                </br>
                <div style="text-align:center; border-top:1px solid red;">
                	<p style="color:blue; margin-top:10px; margin-left:5px; float:left; font:bold;">3500.0</p>
                	<input type="text" id="premium" oninput="racunaj()" name="premium" onkeypress="validate()" maxlength="4" value="0" style="width:40px; float:right; margin-right:5px; margin-top:10px; color:red; font:bold;" />
                </div>
            </div>
            
            <div id="stavka" style="background-color:yellow; float:left; margin-left:40px; margin-top:20px; border-radius:10px; width:150px; height:250px;">
            	<img src="../images/smart_paket.png" style="border-bottom:1px solid red;" width="150" height="150" />
                <label for="smart" style="color:red;">Smart Pack [10PI]</label>
                </br>
                <div style="text-align:center; border-top:1px solid red;">
                	<p style="color:blue; margin-top:10px; margin-left:5px; float:left; font:bold;">850.0</p>
                	<input type="text" id="smart" oninput="racunaj()" name="smart" onkeypress="validate()" maxlength="4" value="0" style="width:40px; float:right; margin-right:5px; margin-top:10px; color:red; font:bold;" />
                </div>
            </div>
            
            <div id="stavka" style="background-color:yellow; float:left; margin-left:40px; margin-top:20px; border-radius:10px; width:150px; height:250px;">
            	<img src="../images/legendary_paket.png" style="border-bottom:1px solid red;" width="150" height="150" />
                <label for="legendary" style="color:red;">Legendary Pack [1000EXP]</label>
                </br>
                <div style="text-align:center; border-top:1px solid red;">
                	<p style="color:blue; margin-top:10px; margin-left:5px; float:left; font:bold;">2500.0</p>
                	<input type="text" id="legendary" oninput="racunaj()" name="legendary" onkeypress="validate()" maxlength="4" value="0" style="width:40px; float:right; margin-right:5px; margin-top:10px; color:red; font:bold;" />
                </div>
            </div>
            
            <div id="stavka" style="background-color:yellow; float:left; margin-left:40px; margin-top:20px; border-radius:10px; width:150px; height:250px;">
            	<img src="../images/vip_paket.png" style="border-bottom:1px solid red;" width="150" height="150" />
                <label for="vip" style="color:red;">VIP Pack [70RP]</label>
                </br>
                <div style="text-align:center; border-top:1px solid red;">
                	<p style="color:blue; margin-top:10px; margin-left:5px; float:left; font:bold;">1100.0</p>
                	<input type="text" id="vip" oninput="racunaj()" name="vip" onkeypress="validate()" maxlength="4" value="0" style="width:40px; float:right; margin-right:5px; margin-top:10px; color:red; font:bold;" />
                </div>
            </div>
            
            <div id="stavka" style="background-color:yellow; float:left; margin-left:40px; margin-top:20px; border-radius:10px; width:150px; height:250px;">
            	<img src="../images/cena.png" style="border-bottom:1px solid red;" width="150" height="150" />
                <label  style="color:red;">Vasi tokeni / Ukupan iznos</label>
                </br>
                <div style="text-align:center; border-top:1px solid red;">
                	<p title="Tokeni" style="color:red; float:left; margin-left:5px; margin-top:5px;"><img src="../images/tokeni.png" style="vertical-align:middle;" width="20" height="20" /> <font style="color:blue; font:bold;"><?php echo get_token_value($nick); ?></font></p>
                    <p id="cena" title="Ukupan iznos" style="margin-top:10px; float:left; clear:both; margin-left:5px;"><img src="../images/kasa.png" style="vertical-align:middle;" width="20" height="20" /> <font style="color:blue; font:bold;">0</font></p>
                </div>
            </div>
           
            <input type="submit" style="margin-top:15px; text-align:left; border-radius:10px; margin-left:350px; margin-right:380px; font:bold; color:red; background-color:yellow; background-image:url(shop.jpg); height:35px; width:85px; " value="Kupi" />
        </form>
    </div>
</body>
</html>