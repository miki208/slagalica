<?php
	include "connect.php";
	checkUser();
	$datum=date("d-m-Y");
	if(isset($_SESSION['userName']))
	{
		if(!checkPerm($_SESSION['userName'],"asocijacije_add"))
		{
			header('Location: index.php');
		}
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Mikisoft Slagalica</title>
<!--MENI-->
  	<?php meni_css(); ?>
<!--MENI-->
<script type="text/javascript">

	function precisti(s)
	{
		var temp="";
		for(var i=0;i<s.length;i++)
		if(((s[i].charCodeAt(0)>=48)&&(s[i].charCodeAt(0)<=90))||((s[i].charCodeAt(0)>=97)&&(s[i].charCodeAt(0)<=122))||(s[i]=='|')||(s[i]=='*')||(s[i]==' ')||(s[i]=='.')||(s[i]=='/'))
		temp+=s[i];
		temp=temp.toLowerCase();
		return temp;
	}

	function potvrdi()
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
				alert("Poslato!");
				var a=document.getElementsByName("polje");
				var b=document.getElementsByName("ime");
				for(var i=0;i<a.length;i++)
				a[i].value="";
				for(var i=0;i<b.length;i++)
				b[i].value="";
    		}
  		}
		var data="";
		data+=document.getElementById("konacno").value;
		for(var i=1;i<=4;i++)
		{
			data+="|"+document.getElementById("S"+i.toString()).value;
		}
		for(var i=1;i<=4;i++)
		{
			data+="|"+document.getElementById("A"+i.toString()).value;
		}
		for(var i=1;i<=4;i++)
		{
			data+="|"+document.getElementById("B"+i.toString()).value;
		}
		for(var i=1;i<=4;i++)
		{
			data+="|"+document.getElementById("C"+i.toString()).value;
		}
		for(var i=1;i<=4;i++)
		{
			data+="|"+document.getElementById("D"+i.toString()).value;
		}
		data=precisti(data);
		xmlhttp.open("POST","sacuvaj_asocijacija.php",true);
		xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		xmlhttp.send("data="+data);
	}
</script>
</head>

<body background="bg.jpg">
				<!--MENI-->
  				<?php meni("glavni"); ?>
  				<!--MENI-->
				<div style=" margin-left:10%; width:80%;" align="center">
    			<input type="text" name="polje" value="A1" id="A1" style="color:white; font-size:30px; text-align:center; background-color:#FF6600; width:35%; float:left; margin-left:10%; border-radius:15px;" />
    			<input type="text" name="polje" value="B1" id="B1" style="color:white; font-size:30px; text-align:center; background-color:#FF6600; width:35%; float:right; margin-right:10%; border-radius:15px;" />
    			</div>
    
    			<div style=" margin-left:10%; width:80%;" align="center">
    			<input type="text" name="polje" value="A2" id="A2" style="color:white; font-size:30px; text-align:center; background-color:#FF6600; width:35%; float:left; margin-left:10%; border-radius:15px;" />
    			<input type="text" name="polje" value="B2" id="B2" style="color:white; font-size:30px; text-align:center; background-color:#FF6600; width:35%; float:right; margin-right:10%; border-radius:15px;" />
    			</div>
    
    			<div style=" margin-left:10%; width:80%;" align="center">
    			<input type="text" name="polje" value="A3" id="A3" style="color:white; font-size:30px; text-align:center; background-color:#FF6600; width:35%; float:left; margin-left:10%; border-radius:15px;" />
    			<input type="text" name="polje" value="B3" id="B3" style="color:white; font-size:30px; text-align:center; background-color:#FF6600; width:35%; float:right; margin-right:10%; border-radius:15px;" />
    			</div>
    
    			<div style=" margin-left:10%; width:80%;" align="center">
    			<input type="text" name="polje" value="A4" id="A4" style="color:white; font-size:30px; text-align:center; background-color:#FF6600; width:35%; float:left; margin-left:10%; border-radius:15px;" />
    			<input type="text" name="polje" value="B4" id="B4" style="color:white; font-size:30px; text-align:center; background-color:#FF6600; width:35%; float:right; margin-right:10%; border-radius:15px;" />
    			</div>
    
    			<div style=" margin-left:10%; width:80%;" align="center">
    			<input type="text" value="A" name="ime" class="alert_style_example" onclick="sporedna('S1')" id="S1" style="color:white; font-size:30px; text-align:center; background-color:red; width:35%; float:left; margin-left:10%; border-radius:15px;" />
    			<input type="text" value="B" name="ime" class="alert_style_example" onclick="sporedna('S2')" id="S2" style="color:white; font-size:30px; text-align:center; background-color:red; width:35%; float:right; margin-right:10%; border-radius:15px;" />
    			</div>
    
    			<div style=" margin-left:10%; width:80%;" align="center">
    			<input type="text" value="????" name="ime" id="konacno" class="alert_style_example" style="color:white; font-size:30px; text-align:center; background-color:red; width:80%; border-radius:15px;" />
    			</div>
    
    			<div style=" margin-left:10%; width:80%;" align="center">
    			<input type="text" value="C" name="ime" class="alert_style_example" id="S3" style="color:white; font-size:30px; text-align:center; background-color:red; width:35%; float:left; margin-left:10%; border-radius:15px;" />
    			<input type="text" value="D" name="ime" class="alert_style_example" id="S4" style="color:white; font-size:30px; text-align:center; background-color:red; width:35%; float:right; margin-right:10%; border-radius:15px;" />
    			</div>
    
    			<div style=" margin-left:10%; width:80%;" align="center">
    			<input type="text" name="polje" value="C4" id="C4" style="color:white; font-size:30px; text-align:center; background-color:#FF6600; width:35%; float:left; margin-left:10%; border-radius:15px;" />
    			<input type="text" name="polje" value="D4" id="D4" style="color:white; font-size:30px; text-align:center; background-color:#FF6600; width:35%; float:right; margin-right:10%; border-radius:15px;" />
    			</div>
    
    			<div style=" margin-left:10%; width:80%;" align="center">
    			<input type="text" name="polje" value="C3" id="C3" style="color:white; font-size:30px; text-align:center; background-color:#FF6600; width:35%; float:left; margin-left:10%; border-radius:15px;" />
    			<input type="text" name="polje" value="D3" id="D3" style="color:white; font-size:30px; text-align:center; background-color:#FF6600; width:35%; float:right; margin-right:10%; border-radius:15px;" />
    			</div>
    
    			<div style=" margin-left:10%; width:80%;" align="center">
    			<input type="text" name="polje" value="C2" id="C2" style="color:white; font-size:30px; text-align:center; background-color:#FF6600; width:35%; float:left; margin-left:10%; border-radius:15px;" />
    			<input type="text" name="polje" value="D2" id="D2" style="color:white; font-size:30px; text-align:center; background-color:#FF6600; width:35%; float:right; margin-right:10%; border-radius:15px;" />
    			</div>
    
    			<div style=" margin-left:10%; width:80%;" align="center">
    			<input type="text" name="polje" value="C1" id="C1" style="color:white; font-size:30px; text-align:center; background-color:#FF6600; width:35%; float:left; margin-left:10%; border-radius:15px;" />
    			<input type="text" name="polje" value="D1" id="D1" style="color:white; font-size:30px; text-align:center; background-color:#FF6600; width:35%; float:right; margin-right:10%; border-radius:15px;" />
    			</div>
				<div style=" margin-left:10%; width:80%;" align="center">
    			<input type="text" onclick="potvrdi()" readonly="readonly" name="potvrda" value="Salji" id="potvrda" style="color:white; font-size:30px; text-align:center; background-color:#FF6600; width:35%; border-radius:15px;" />
    			</div>
</body>