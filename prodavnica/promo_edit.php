<?php
	include "../connect.php";
	checkUser();
	$nick=$_SESSION['userName'];
	$niz=array();
	$errTxt="";
	$listaGresaka=array("Vrednost mora biti izmedju 1 i 5000.","Vrednost mora biti izmedju 1 i 100.","Vrednost mora biti izmedju 1 i 50.","Vrednost mora biti izmedju 1 i 10000.","Kolicina mora biti izmedju 1 i 1000.","Promocija uspesno dodata.","Promocija je uspesno izbrisana.","Vrednost mora biti izmedju 1 i 10.");
	if(isset($_GET['errid']))
	$errTxt=$listaGresaka[intval($_GET['errid'])];
	$error=false;
	if(!checkPerm($nick,"promo_edit"))
	{
		header("Location: ../index.php");
	}
	else
	{
		if(isset($_POST['TIP']))
		{
			if($_POST['VR']=="")
			$_POST['VR']="0";
			if($_POST['KOL']=="")
			$_POST['KOL']="0";
			$vrednost=intval($_POST['VR']);
			$kol=intval($_POST['KOL']);
			switch($_POST['TIP'])
			{
				case 'tokeni':
					if($vrednost>5000||$vrednost==0)
					{
						$error=true;
						header("Location: promo_edit.php?errid=0");
					}
					break;
				case 'rp':
					if($vrednost>100||$vrednost==0)
					{
						$error=true;
						header("Location: promo_edit.php?errid=1");
					}
					break;
				case 'pi':
					if($vrednost>50||$vrednost==0)
					{
						$error=true;
						header("Location: promo_edit.php?errid=2");
					}
					break;
				case 'exp':
					if($vrednost>10000||$vrednost==0)
					{
						$error=true;
						header("Location: promo_edit.php?errid=3");
					}
					break;
				case 'kljuc':
					if($vrednost>10||$vrednost==0)
					{
						$error=true;
						header("Location: promo_edit.php?errid=7");
					}
					break;
			}
			if($kol>1000||$kol==0)
			{
				$error=true;
				header("Location: promo_edit.php?errid=4");
			}
			if(!$error)
			{
				$klj=substr(md5(uniqid(rand(),true)),0,8);
				mysql_query("INSERT INTO promocije(tip,vrednost,kolicina,secret_key) VALUES('".$_POST['TIP']."','$vrednost','$kol','$klj')");
				header("Location: promo_edit.php?errid=5");
			}
			
		}
		elseif(isset($_POST['idbr']))
		{
			$id=$_POST['idbr'];
			$d=mysql_query("SELECT * FROM promocije WHERE id='$id'");
			$dd=mysql_fetch_assoc($d);
			mysql_query("DELETE FROM promocije WHERE id='$id'");
			mysql_query("DELETE FROM promo_user WHERE secret_key='".$dd['secret_key']."'");
			header("Location: promo_edit.php?errid=6");
		}
	}
	$data=mysql_query("SELECT * FROM promocije");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Administracija promocija</title>
<!--MENI-->
  	<?php meni_css(); ?>
<!--MENI-->
<style type="text/css">
	* {
		margin:0px;
	}
	
	body {
		background-image: url(<?php $bg=LoadSettings("pozadina"); echo "../".$bg['opcija']; ?>);
		background-repeat: repeat;
	}
	
	#wrapper {
		width:800px;
		min-height:500px;
		margin-left:auto;
		margin-right:auto;
		background-image: url(../dost-ind1.jpg);
		color:red;
	}
</style>

<script type="text/javascript">

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
			if($errTxt!="")
			echo "msgboxshow('msgbox','Promo','$errTxt');";
		?>
	}

</script>

</head>

<body onload='init()'>
	<div id="msgboxbg" style="width:100%; visibility:hidden; height:100%; z-index:2; position:absolute; background-color:none;">
		<div id="msgbox" style="width:300px; height:auto; position:absolute; border-radius:10px; border-radius:10px; border:1px solid #F00; background-color:yellow; left:35%; top:30%;">
			<p id="msgboxtitle" style="text-align:left; width:100%; border-top-left-radius:8px; border-top-right-radius:8px; height:18px; color:red; cursor:pointer; background-color:#0F0; border-bottom:1px solid #F00; font-size:15px;">&nbsp;</p>
        	<p id="msgboxtxt" style="word-wrap: break-word; margin-top:10px; color:red; margin-left:5px; margin-right:5px;"></p>
    		<input type="button" value="OK" onclick="msgboxhide('msgbox')" style="margin-left:130px; cursor:pointer; text-align:center; margin-top:10px; margin-bottom:10px; width:40px; margin-right:130px;" />
		</div>
	</div>
	<div id='wrapper'>
        	<!--MENI-->
  		<?php meni("prodavnica"); ?>
  		<!--MENI-->
		<h1 style="text-align:center;">Promocije</h1>
        <form action='promo_edit.php' method='post' style='margin:50px; width:698px; border:1px solid red; background-color:#999;'>
				<label for='TIP' style='color:red; margin-left:10px; margin-top:10px; margin-bottom:10px;'>Odaberi promociju: </label>
                
				<select id='TIP' name='TIP' style='color:red; margin-top:10px; margin-bottom:10px; background-color:yellow; border-radius:10px;'>
					<option value='tokeni'>Tokeni</option>
					<option value='rp'>Reputacija</option>
					<option value='pi'>PI</option>
                    <option value='exp'>Iskustvo</option>
                    <option value='kljuc'>Kljucevi</option>
				</select>
						
				vr: <input name='VR' id='VR' value='0' type='text' onkeypress='validate()' maxlength='4' style='color:red; margin-top:10px; margin-bottom:10px; text-align:center; background-color:yellow; width:50px; border-radius:10px;' />
                kol: <input name='KOL' id='KOL' value='0' type='text' onkeypress='validate()' maxlength='4' style='color:red; margin-top:10px; margin-bottom:10px; text-align:center; background-color:yellow; width:50px; border-radius:10px;' />
						
				<input type='submit' style='color:red; margin-top:10px; margin-bottom:10px; background-color:yellow; border-radius:10px;' value='Potvrdi'/>
                
                
		</form>
        <table style="width:450px; background-color:#999; margin-bottom:30px; border:1px solid red; margin-left:50px; color:red;">
                	<tr>
                    	<td style="text-align:center; border-right:1px solid red; width:100px;">Tip promocije</td>
                        <td style="text-align:center; border-right:1px solid red; width:100px;">Kolicina</td>
                        <td style="text-align:center; border-right:1px solid red; width:100px;">Vrednost</td>
                        <td style="text-align:center; border-right:1px solid red; width:100px;">Promo kod</td>
                        <td style="text-align:center; width:50px;">Brisanje</td>
                    </tr>
                    
                    <?php
						while($niz=mysql_fetch_assoc($data))
						{
							echo "<tr>
                    				<td style=\"text-align:center; border-top:1px solid red; border-right:1px solid red; width:100px;\">".$niz['tip']."</td>
                        			<td style=\"text-align:center; border-top:1px solid red; border-right:1px solid red; width:100px;\">".$niz['kolicina']."</td>
                        			<td style=\"text-align:center; border-top:1px solid red; border-right:1px solid red; width:100px;\">".$niz['vrednost']."</td>
                        			<td style=\"text-align:center; border-top:1px solid red; border-right:1px solid red; width:100px;\">".$niz['secret_key']."</td>
                        			<td style=\"text-align:center; border-top:1px solid red; width:50px;\"><form action='promo_edit.php' method='post'><input type='hidden' name='idbr' value='".$niz['id']."'/><input type='submit' value='Obrisi' style='border-radius:10px;'/></form></td>
                    			  </tr>";
						}
					?>
                </table>
        
    </div>
</body>
</html>