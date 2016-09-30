<?php
	include "connect.php";
	checkUser();
	$nick=$_SESSION['userName'];
	$data=mysql_query("SELECT * FROM ovlascenja");
	$ovlascenja=array();
	while($niz=mysql_fetch_assoc($data))
	array_push($ovlascenja,$niz);
	if((mysql_num_rows(mysql_query("SELECT * FROM admini WHERE nick='$nick'"))==0)&&($nick!="Admin"))
	{
		header("Location: index.php");
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php meni_css(); ?>
<style type="text/css">
body {
	background-image: url(<?php $bg=LoadSettings("pozadina"); echo $bg['opcija']; ?>);
	background-repeat: repeat;
}

*{
	margin:0px;
}

#wrapper
{
	height:600px;
	color: #F00;
	background-image: url(dost-ind1.jpg);
	width:800px;
	margin-left:auto;
	margin-right:auto;
	overflow-y:scroll;
}

#polja
{
	border-radius:10px;
	background-color:#CCC;
	width:750px;
	margin-left:auto;
	margin-right:auto;
	margin-top:10px;
	min-height:50px;
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
				case "scsaddperm":
				echo "msgboxshow('msgbox','Obavestenje','Ovlascenje uspesno dodato.');";
				break;
				
				case "nouser":
				echo "msgboxshow('msgbox','Obavestenje','Ovaj korisnik ne postoji.');";
				break;
				
				case "noadm":
				echo "msgboxshow('msgbox','Obavestenje','Ovaj korisnik nema administratorskih prava.');";
				break;
				
				case "scsdelperm":
				echo "msgboxshow('msgbox','Obavestenje','Uspesno uklonjeno administratorsko pravo.');";
				break;
				
				case "unscsdelperm":
				echo "msgboxshow('msgbox','Obavestenje','Ovaj korisnik ne poseduje ovo administratorsko pravo.');";
				break;
				
				case "unscsaddperm":
				echo "msgboxshow('msgbox','Obavestenje','Ovaj korisnik vec poseduje ovo administratorsko pravo.');";
				break;
				
				case "noadmdel":
				echo "msgboxshow('msgbox','Obavestenje','Glavni admin ne moze biti obrisan.');";
				break;
				
				case "noimdel":
				echo "msgboxshow('msgbox','Obavestenje','Ovaj korisnik ima imunitet i ne moze biti obrisan.');";
				break;
				
				case "scsuserdel":
				echo "msgboxshow('msgbox','Obavestenje','Korisnik je uspesno obrisan.');";
				break;
				
				case "notplay":
				echo "msgboxshow('msgbox','Obavestenje','Ovaj korisnik jos uvek nije odigrao igru ili datum ne postoji.');";
				break;
				
				case "nogame":
				echo "msgboxshow('msgbox','Obavestenje','Ova igra ne postoji.');";
				break;
				
				case "resetplay":
				echo "msgboxshow('msgbox','Obavestenje','Igra je uspesno resetovana.');";
				break;
			}
		}
	?>
}
</script>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Admin panel</title>
</head>

<body onload="init()">
	<div id="msgboxbg" style="width:100%; visibility:hidden; height:100%; z-index:2; position:absolute; background-color:none;">
		<div id="msgbox" style="width:300px; margin-left:auto; margin-right:auto; margin-top:25%; min-height:50px; text-align:center; border-radius:10px; border:1px solid #F00; background-color:yellow;">
			<p id="msgboxtitle" style="text-align:left; width:100%; border-top-left-radius:8px; border-top-right-radius:8px; height:18px; color:red; cursor:pointer; background-color:#0F0; border-bottom:1px solid #F00; font-size:15px;">&nbsp;</p>
        	<p id="msgboxtxt" style="word-wrap: break-word; margin-top:10px; color:red; margin-left:5px; margin-right:5px;"></p>
    		<input type="button" value="OK" onclick="msgboxhide('msgbox')" style="margin-left:80px; cursor:pointer; text-align:center; margin-top:10px; margin-bottom:10px; width:40px; margin-right:80px;" />
		</div>
	</div>
    <div style="margin-left:auto; margin-right:auto; width:800px;">
    	<?php meni("glavni"); ?>
    </div>
	<div id="wrapper">
    <?php
		$pristupi=array();
		$opisi=array();
		$opcije="";
		foreach($ovlascenja as $stavka) $opcije.="<option value='$stavka[kod]' title='$stavka[opis]'>$stavka[kod]</option>";
		if($nick=="Admin")
		{
			echo "<table id='admini' style='width:700px; margin-left:auto; font-size:12px; border:1px solid red; margin-right:auto;'>
    				<tr>
        				<td style='background-color:white; color:red;'>nick</td>";
    		foreach($ovlascenja as $stavka) echo "<td style='background-color:white; color:red;'>$stavka[kod]</td>";
			echo "</tr>";
			$admini_data=mysql_query("SELECT * FROM admini");
			while($admini_niz=mysql_fetch_assoc($admini_data))
			{
				echo "<tr>";
				echo "<td style='background-color:white; color:red;'>$admini_niz[nick]</td>";
				foreach($ovlascenja as $stavka)
				{
					$color="red";
					$str=strpos($admini_niz['ovlascenje'],$stavka['kod']);
					if($str!==false)
					$color="green";
					echo "<td style='background-color:$color; color:red;'><p style=''>&nbsp;</p></td>";
				}
				echo "</tr>";
			}
    		echo "</table>";
			echo "
				<div id='polja' title='Panel za glavna podesavanja igrice'>
    				<p style='color:red; text-align:center;'>Panel za glavna podesavanja</p>
        			<div style='text-align:center; margin-top:5px;'><a href='settings_panel.php' style='text-decoration:none; text-align:center; border-radius:8px; color:red; background-color:yellow;'><b>Pristupi</b></a></div>
    			</div>
				
				<div id='polja' title='Dodeljivanje ovlascenja korisnicima'>
    				<p style='color:red; text-align:center;'>Dodeljivanje ovlascenja</p>
        			<form style='color:red; margin-top:5px;' action='dodaj_ovlascenje.php' method='get'>
        				<p style='margin-left:5px; float:left;'>Nick:</p> <input type='text' name='nick' style='color:red; float:left; margin-left:3px; background-color:yellow; width:200px; border-radius:10px;' maxlength='100'/>
            			<p style='margin-left:5px;  float:left;'>Ovlascenje:</p> <select style='color:red; margin-left:3px; background-color:yellow; border-radius:10px;' name='perms'>$opcije</select>
            			<input type='submit' style='border-radius:10px; color:red; background-color:yellow; margin-left:5px;' value='Dodeli'/>
        			</form>
        			<p style='margin-top:5px;'>&nbsp;</p>
    			</div>
				
				<div id='polja' title='Uklanjanje ovlascenja korisnicima'>
    				<p style='color:red; text-align:center;'>Uklanjanje ovlascenja</p>
        			<form style='color:red; margin-top:5px;' action='ukloni_ovlascenje.php' method='get'>
        				<p style='margin-left:5px; float:left;'>Nick:</p> <input type='text' name='nick' style='color:red; float:left; margin-left:3px; background-color:yellow; width:200px; border-radius:10px;' maxlength='100'/>
            			<p style='margin-left:5px;  float:left;'>Ovlascenje:</p> <select style='color:red; margin-left:3px; background-color:yellow; border-radius:10px;' name='perms'>$opcije</select>
            			<input type='submit' style='border-radius:10px; color:red; background-color:yellow; margin-left:5px;' value='Ukloni'/>
        			</form>
        			<p style='margin-top:5px;'>&nbsp;</p>
    			</div>
			";
		}
		
		if(checkPerm($nick,"baner_add"))
		{
			array_push($pristupi,"Dodavanje banera");
			array_push($opisi,"Daje ovlascenja za dodavanje banera");
			array_push($pristupi,"Menadzer banera");
			array_push($opisi,"Daje ovlascenja za izmenu banera");
		}
		
		if(checkPerm($nick,"delete_user"))
		{
			echo "
				<div id='polja' title='Ovlascenje za brisanje korisnika'>
    				<p style='color:red; text-align:center;'>Brisanje korisnika</p>
            		<form style='color:red; margin-top:5px;' action='delete_user.php' method='get'>
            			<p style='margin-left:5px; float:left;'>Nick:</p> <input type='text' name='nick' style='color:red; float:left; margin-left:3px; background-color:yellow; width:200px; border-radius:10px;' maxlength='100'/>
                		<input type='submit' style='border-radius:10px; color:red; background-color:yellow; margin-left:5px;' value='Obrisi'/>
            		</form>
            		<p style='margin-top:5px;'>&nbsp;</p>
    			</div>
			";
		}
		
		if(checkPerm($nick,"imunitet"))
		{
			echo "
				<div id='polja' title='Daje korisniku imunitet, tj ne moze biti obrisan od strane obicnog admina'>
        			<p style='color:red; text-align:center;'>Imunitet</p>
           		    <p style='margin-top:5px; text-align:center;'>Imate imunitet i zasticeni ste od brisanja naloga.</p>
    			</div>
			";
		}
		
		if(checkPerm($nick,"info_add"))
		{
			array_push($pristupi,"Dodavanje obavestenja");
			array_push($opisi,"Daje ovlascenja za dodavanje obavestenja");
			array_push($pristupi,"Menadzer obavestenja");
			array_push($opisi,"Daje ovlascenja za izmenu obavestenja");
		}
		
		if(checkPerm($nick,"asocijacije_add"))
		{
			array_push($pristupi,"Dodavanje asocijacije");
			array_push($opisi,"Ovlascenje za dodavanje asocijacija");
		}
		
		if(checkPerm($nick,"unset_game"))
		{
			echo "
				<div id='polja' title='Ovlascenje za brisanje dnevne igre'>
    				<p style='color:red; text-align:center;'>Ponistavanje dnevnih igara</p>
        			<form style='color:red; margin-top:5px;' action='ponisti_igru.php' method='get'>
        				<p style='margin-left:5px; float:left;'>Nick:</p> <input type='text' name='nick' style='color:red; float:left; margin-left:3px; background-color:yellow; width:200px; border-radius:10px;' maxlength='100'/>
						<p style='margin-left:5px; float:left;'>Datum:</p> <input title='(DD-MM-GGGG)' type='text' name='datum' style='color:red; float:left; margin-left:3px; background-color:yellow; width:100px; border-radius:10px;' maxlength='11'/>
						<p style='margin-left:5px;  float:left;'>Igra:</p> <select style='color:red; margin-left:3px; background-color:yellow; border-radius:10px;' name='igre'><option value='slagalica'>Slagalica</option><option value='moj_broj'>Moj Broj</option><option value='spojnice'>Spojnice</option><option value='skocko'>Skocko</option><option value='koznazna'>Ko Zna Zna</option><option value='asocijacije'>Asocijacije</option></select>
            			<input type='submit' style='border-radius:10px; color:red; background-color:yellow; margin-left:5px;' value='Ponisti'/>
        			</form>
        			<p style='margin-top:5px;'>&nbsp;</p>
    			</div>
			";
		}
		
		if(checkPerm($nick,"view_info"))
		{
			array_push($pristupi,"Pregled korisnika");
			array_push($opisi,"Daje ovlascenja za pregledanje korisnickih podataka");
		}
		
		if(checkPerm($nick,"supervizor"))
		{
			array_push($pristupi,"Supervizor (".mysql_num_rows(mysql_query("SELECT * FROM buffer_reci")).")");
			array_push($opisi,"Ima mogucnost pristupu strani za identifikaciju nepriznatih reci");
		}
		
		if(checkPerm($nick,"statistika"))
		{
			array_push($pristupi,"Statistika");
			array_push($opisi,"Daje ovlascenje za pregledanje opsirne statistike");
		}
		
		if(checkPerm($nick,"editor"))
		{
			array_push($pristupi,"Edit pitanja");
			array_push($opisi,"Ima mogucnost editovanja i dodavanja novih pitanja");
		}
		
		if(checkPerm($nick,"shuffle"))
		{
			echo "
				<div id='polja' title='Daje ovlascenje za mesanje pitanja u bazi'>
    				<p style='color:red; text-align:center;'>Mesanje pitanja</p>
        			<div style='text-align:center; margin-top:5px;'><a href='izmesaj_pitanja.php' style='text-decoration:none; text-align:center; border-radius:8px; color:red; background-color:yellow;'><b>Promesaj</b></a></div>
    			</div>
			";
		}
		
		if(checkPerm($nick,"shop_change"))
		{
			array_push($pristupi,"Korisnicki invertari");
			array_push($opisi,"Moze da menja informacije vezane za reputaciju, exp, tokene, pakete");
		}
		
		if(checkPerm($nick,"promo_edit"))
		{
			array_push($pristupi,"Edit promocija");
			array_push($opisi,"Ima dozvolu za dodavanje i brisanje promocija");
		}
		$html="";
		for($i=0;$i<count($pristupi);$i++)
		{
			if(substr($pristupi[$i],0,10)!="Supervizor")
			$html.="<option value='".$pristupi[$i]."' title='".$opisi[$i]."'>".$pristupi[$i]."</option>";
			else
			$html.="<option value='Supervizor' title='".$opisi[$i]."'>".$pristupi[$i]."</option>";
		}
		if(count($pristupi)>0)
		echo "
				<div id='polja' title='Pristupni panel za osnovna podesavanja'>
    				<p style='color:red; text-align:center;'>Pristupni panel za osnovna podesavanja</p>
        			<form style='color:red; margin-top:5px;' action='redirect.php' method='get'>
            			<p style='margin-left:5px; float:left;'>Odaberite panel:</p> <select name='pristup' style='color:red; margin-left:3px; background-color:yellow; border-radius:10px;'>$html</select>
                		<input type='submit' style='border-radius:10px; color:red; background-color:yellow; margin-left:5px;' value='Pristupi'/>
            		</form>
    			</div>
			";
	?>
     
    <p style='margin-top:10px;'>&nbsp;</p>
    </div>
</body>
</html>