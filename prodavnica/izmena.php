<?php
	include "../connect.php";
	checkUser();

	$nick=$_SESSION['userName'];
	$bg=LoadSettings("pozadina");
	if(checkPerm($nick,"shop_change"))
	{
		$errcode=-1;
		if(isset($_GET['errcode']))
		$errcode=intval($_GET['errcode']);
		$listaGresaka=array("Korisniku ".$_GET['target']." uspesno dodeljeno ".$_GET['num']." tokena.","Korisniku ".$_GET['target']." uspesno dodato ".$_GET['num']." tokena.","U jednom trenutku je moguce dodati/dodeliti max 5000 tokena.","Korisniku ".$_GET['target']." uspesno dodeljeno ".$_GET['num']." PI.","Korisniku ".$_GET['target']." uspesno dodato ".$_GET['num']." PI.","U jednom trenutku je moguce dodati/dodeliti max 50 PI.","Korisniku ".$_GET['target']." uspesno dodeljeno ".$_GET['num']." RP.","Korisniku ".$_GET['target']." uspesno dodato ".$_GET['num']." RP.","U jednom trenutku je moguce dodati/dodeliti max 100 RP.","Taj korisnik ne postoji!","Korisniku ".$_GET['target']." uspesno dodeljeno ".$_GET['num']." kljuceva.","Korisniku ".$_GET['target']." uspesno dodato ".$_GET['num']." kljuceva.","U jednom trenutku je moguce dodati/dodeliti max 10 kljuceva.");
		$target=mysql_escape_string($_POST['NICK']);
		if($_POST['VR']=="")
			$_POST['VR']="0";
		$vrednost=intval(mysql_escape_string($_POST['VR']));
		if(isUser($target)){
		if(isset($_POST['TIP']))
		{
			switch ($_POST['TIP']) {
				case 'TOKEN':
					if($vrednost<=5000)
					{
						if($_POST['AKCIJA']=="IZMENA")
						{
							mysql_query("UPDATE lista SET tokeni='$vrednost' WHERE username='$target'");
							kreiraj_notifikaciju($target,"Admin Transfer","Vasi tokeni inicijalizovani na vrednost $vrednost.");
							header("Location: izmena.php?errcode=0&target=$target&num=$vrednost&g=0");
						}
						else
						{
							add_tokens($target,$vrednost);
							kreiraj_notifikaciju($target,"Admin Transfer","Na Vas nalog dodato $vrednost tokena.");
							header("Location: izmena.php?errcode=1&target=$target&num=$vrednost&g=0");
						}
					}
					else
					{
						header("Location: izmena.php?errcode=2&e=0");
					}
					break;
				case 'PI':
					if($vrednost<=50)
					{
						if($_POST['AKCIJA']=="IZMENA")
						{
							mysql_query("UPDATE lista SET ponistavanje_igre='$vrednost' WHERE username='$target'");
							kreiraj_notifikaciju($target,"Admin Transfer","Vasi PI inicijalizovani na vrednost $vrednost.");
							header("Location: izmena.php?errcode=3&target=$target&num=$vrednost&g=0");
						}
						else
						{
							add_ponistavanje_igre($target,$vrednost);
							kreiraj_notifikaciju($target,"Admin Transfer","Na Vas nalog dodato $vrednost PI.");
							header("Location: izmena.php?errcode=4&target=$target&num=$vrednost&g=0");
						}
					}
					else
					{
						header("Location: izmena.php?errcode=5&e=0");
					}
					break;
				case 'REP':
					if($vrednost<=100)
					{
						if($_POST['AKCIJA']=="IZMENA")
						{
							mysql_query("UPDATE lista SET reputacija='$vrednost' WHERE username='$target'");
							kreiraj_notifikaciju($target,"Admin Transfer","Vasa reputacija inicijalizovana na vrednost $vrednost.");
							header("Location: izmena.php?errcode=6&target=$target&num=$vrednost&g=0");
						}
						else
						{
								add_reputacija($target,$vrednost);
								kreiraj_notifikaciju($target,"Admin Transfer","Na Vas nalog dodato $vrednost reputacije.");
								header("Location: izmena.php?errcode=7&target=$target&num=$vrednost&g=0");
						}
					}
					else
					{
						header("Location: izmena.php?errcode=8&e=0");
					}
					break;
				case 'KLJUC':
					if($vrednost<=10)
					{
						if($_POST['AKCIJA']=="IZMENA")
						{
							kljuc_func($target,$vrednost,1);
							kreiraj_notifikaciju($target,"Admin Transfer","Vasi kljucevi inicijalizovani na vrednost $vrednost.");
							header("Location: izmena.php?errcode=10&target=$target&num=$vrednost&g=0");
						}
						else
						{
								kljuc_func($target,$vrednost,0);
								kreiraj_notifikaciju($target,"Admin Transfer","Na Vas nalog dodato $vrednost kljuceva.");
								header("Location: izmena.php?errcode=11&target=$target&num=$vrednost&g=0");
						}
					}
					else
					{
						header("Location: izmena.php?errcode=12&e=0");
					}
					break;
			}
		}}
		else
		if($target!="")
		{
			header("Location: izmena.php?errcode=9&e=0");
		}
		echo "
		<html>
			<head>
				<style type=\"text/css\">
					body {
						background-image: url(../{$bg['opcija']});
						background-repeat: repeat;
						 }
					* {
						margin: 0px;
					}
					
					#wrapper{
						margin-left:auto;
						margin-right:auto;
						width:800px;
						color: #F00;
						background-image: url(../dost-ind1.jpg);
						background-repeat: repeat;
						height: 500px;
					}
				</style>";
				meni_css();
				echo "<script type='text/javascript'>
				
				function msgboxshow(msgboxname,title,text)
				{
					document.getElementById(msgboxname+\"title\").innerHTML=\"&nbsp;\"+title;
					document.getElementById(msgboxname+\"txt\").innerHTML=text;
					document.getElementById(msgboxname+\"bg\").style.visibility=\"visible\";
				}

				function msgboxhide(msgboxname)
				{
					document.getElementById(msgboxname+\"bg\").style.visibility=\"hidden\";
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
				
				function profil()
				{
					
				}
				
				function init()
				{";
					if($errcode!=-1&&isset($_GET['g']))
					{
						echo "msgboxshow('msgbox','Greska','".$listaGresaka[$errcode]."');";
					}
					if($errcode!=-1&&isset($_GET['e']))
					{
						echo "msgboxshow('msgbox','Uspesno','".$listaGresaka[$errcode]."');";
					}
				echo
				"}
				
				</script>
			</head>

			<body onload=\"init()\">
				<div id=\"msgboxbg\" style=\"width:100%; font-family: 'Courier New', Courier, monospace; visibility:hidden; height:100%; z-index:2; position:absolute; background-color:none;\">
					<div id=\"msgbox\" style=\"width:300px; height:auto; position:absolute; border-radius:10px; border-radius:10px; border:1px solid #F00; background-color:yellow; left:35%; top:30%;\">
						<p id=\"msgboxtitle\" style=\"text-align:left; width:100%; border-top-left-radius:8px; border-top-right-radius:8px; height:18px; color:red; cursor:pointer; background-color:#0F0; border-bottom:1px solid #F00; font-size:15px;\">&nbsp;</p>
        				<p id=\"msgboxtxt\" style=\"word-wrap: break-word; margin-top:10px; color:red; margin-left:5px; margin-right:5px;\"></p>
    					<input type=\"button\" value=\"OK\" onclick=\"msgboxhide('msgbox')\" style=\"margin-left:130px; cursor:pointer; text-align:center; margin-top:10px; margin-bottom:10px; width:40px; margin-right:130px;\" />
					</div>
				</div>
				<div id='wrapper'>";
						meni("prodavnica");
					echo "<h1 style='text-align:center; font-family: \"Courier New\", Courier, monospace; color:red; font-size:20px; width:800px;'>Izmena korisnickih invertara</h1>
					<form action='izmena.php' method='post' style=\"margin:50px; font-family: \"Courier New\", Courier, monospace; width:698px; border:1px solid red; background-color:#999;\">
						<label for='NICK' style='color:red; margin-left:10px;'>Nick</label>
						<input type=\"text\" id='NICK' name='NICK' style=\"border-radius:10px; color:red; background-color:yellow; margin-top:10px; width:200px;\"/>

						<select id='TIP' name='TIP' style='color:red; background-color:yellow; border-radius:10px;'>
							<option value='TOKEN'>Tokeni</option>
							<option value='REP'>Reputacija</option>
							<option value='PI'>PI</option>
							<option value='KLJUC'>Kljucevi</option>
						</select>
						
						<input name='VR' id='VR' value='0' type='text' onkeypress='validate()' maxlength='4' style='color:red; text-align:center; background-color:yellow; width:50px; border-radius:10px;' />
						
						<select id='AKCIJA' name='AKCIJA' style='color:red; margin-bottom:30px; background-color:yellow; border-radius:10px;'>
							<option value='IZMENA'>Izmeni</option>
							<option value='DODAJ'>Dodaj</option>
						</select>
						
						<input type='submit' style='color:red; background-color:yellow; border-radius:10px;' value='Potvrdi'/>
					</form>";
					if(isset($_GET['g']))
					{
						$data=mysql_query("SELECT tokeni, profilna, reputacija, ponistavanje_igre FROM lista WHERE username='".mysql_escape_string($_GET['target'])."'");
						$niz=mysql_fetch_assoc($data);
						
						$data1=mysql_query("SELECT exp, level FROM poeni WHERE nick='".mysql_escape_string($_GET['target'])."'");
						$niz1=mysql_fetch_assoc($data1);
						
						$data2=mysql_query("SELECT MAX(slagalica+moj_broj+spojnice+skocko+koznazna+asocijacije) AS max_p, ROUND(AVG(slagalica+moj_broj+spojnice+skocko+koznazna+asocijacije),0) AS pros_p FROM slagalica_dnevne_liste WHERE nick='".mysql_escape_string($_GET['target'])."'");
						$niz2=mysql_fetch_assoc($data2);
						
						$data3=mysql_query("SELECT COUNT(id) AS adm FROM admini WHERE nick='".mysql_escape_string($_GET['target'])."'");
						$niz3=mysql_fetch_assoc($data3);
						
						echo "<div id='user' onclick='profil()' style='margin:50px; font-family: \"Courier New\", Courier, monospace; cursor:pointer; height:120px; border:1px solid red; width:698px; background-image:url(../rank-ind1.png);'>
							<p style='color:red; text-align:center;'>".mysql_escape_string($_GET['target'])."</p>
							<img src='../profilne/".$niz['profilna']."' width='80' height='80' style='margin:10px; float:left;'/>
							<p style='color:red; margin-top:5px; margin-right:10px; border:1px solid red; float:left;'>Tokeni: <font style='color:white;'>".$niz['tokeni']."</font></br>Reputacija: <font style='color:white;'>".$niz['reputacija']."</font></br>PI: <font style='color:white;'>".$niz['ponistavanje_igre']."</font></br>Iskustvo: <font style='color:white;'>".$niz1['exp']."</font></br>Level: <font style='color:white;'>".$niz1['level']."</font></p>
							<p style='color:red; margin-top:5px; margin-right:10px; float:left; border:1px solid red;'>Broj partija: <font style='color:white;'>".mysql_num_rows(mysql_query("SELECT id FROM slagalica_dnevne_liste WHERE nick='".mysql_escape_string($_GET['target'])."'"))."</font></br>Broj partija (mesec): <font style='color:white;'>".mysql_num_rows(mysql_query("SELECT id FROM slagalica_dnevne_liste WHERE nick='".mysql_escape_string($_GET['target'])."' AND datum LIKE '___".date("m-Y")."'"))."</font></br>Max poena: <font style='color:white;'>".$niz2['max_p']."</font></br>Pros. broj poena: <font style='color:white;'>".$niz2['pros_p']."</font></br>Administrator: <font style='color:white;'>".$niz3['adm']."</font></p>
						</div>";
					}
					echo "
				</div>
			</body>
		</html>
		";
	}
	else
	{
		header("Location: ../index.php");
	}
?>	