<?php
	include "../connect.php";
	checkUser();
	$nick=$_SESSION['userName'];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Kovceg</title>
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
	height: 650px;
	width: 800px;
	margin-right: auto;
	margin-left: auto;
	border: 1px solid #F00;
}

#polje{
	border:1px solid #369;
	width:12.5%;
	text-align:center;
	color:black;
	font-size:12px;
	border-radius:7px;
}
</style>
<!--MENI-->
  	<?php meni_css(); ?>
<!--MENI-->
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
		<?php if(isset($_GET['status'])){ if($_GET['status']=='ok') echo "msgboxshow('msgbox','Ponisteno','Danasnja igra je uspesno ponistena.');"; if($_GET['status']=='none') echo "msgboxshow('msgbox','Neuspesno','Nemate paketa za ponistavanje dnevne igre.</br>Posetite nasu prodavnicu i kupite jedan!');"; if($_GET['status']=='notall') echo "msgboxshow('msgbox','Neuspesno','Da bi ste ponistili igru, morate imati sve odigrane igre.');"; if($_GET['status']=='maxdel') echo "msgboxshow('msgbox','Neuspesno','Dnevno mozete ponistiti max 2 igre.');"; if($_GET['status']=='nop') echo "msgboxshow('msgbox','Neuspesno','Da bi ste ponistili igru, morate imati sve odigrane igre.');"; }?>
		<?php if(isset($_GET['mg_err'])){ switch($_GET['mg_err']){ case 'oflmt':echo "msgboxshow('msgbox','Mini igra-Neuspesno','Broj mora biti u opsegu od 1 do 200.');"; break; case 'nonum':echo "msgboxshow('msgbox','Mini igra-Neuspesno','Morate uneti validan broj od 1 do 200.');"; break; case 'mg_nv':echo "msgboxshow('msgbox','Mini igra-Neuspesno','Niste upisali broj.');"; break; case 'notok':echo "msgboxshow('msgbox','Mini igra-Neuspesno','Nemate dovoljno tokena!');"; break;} } ?>
		<?php if(isset($_GET['ot_err'])){ switch($_GET['ot_err']){ case 'nmr':echo "msgboxshow('msgbox','Otkljucavanje-Neuspesno','Nije moguce otkljucati partiju za ovaj dan.');"; break; case 'sc':echo "msgboxshow('msgbox','Otkljucavanje-Uspesno','Partija uspesno otkljucana.');"; break; case 'nk':echo "msgboxshow('msgbox','Otkljucavanje-Neuspesno','Nemate kljuceva za otkljucavanje.');"; break; case 'nm':echo "msgboxshow('msgbox','Otkljucavanje-Neuspesno','Nije moguce otkljucati partiju za ovaj mesec.');"; break;} } ?>
	}
	
</script>
</head>

<body onload="init()">
<div id="msgboxbg" style="width:100%; visibility:hidden; font-family: 'Courier New', Courier, monospace; height:100%; z-index:2; position:absolute; background-color:none;">
	<div id="msgbox" style="width:300px; height:auto; position:absolute; border-radius:10px; border-radius:10px; border:1px solid #F00; background-color:yellow; left:35%; top:30%;">
		<p id="msgboxtitle" style="text-align:left; width:100%; border-top-left-radius:8px; border-top-right-radius:8px; height:18px; color:red; cursor:pointer; background-color:#0F0; border-bottom:1px solid #F00; font-size:15px;">&nbsp;</p>
        <p id="msgboxtxt" style="word-wrap: break-word; margin-top:10px; color:red; margin-left:5px; margin-right:5px;"></p>
    	<input type="button" value="OK" onclick="msgboxhide('msgbox')" style="margin-left:130px; cursor:pointer; text-align:center; margin-top:10px; margin-bottom:10px; width:40px; margin-right:130px;" />
	</div>
</div>
	<div id="wrapper">
    <!--MENI-->
  		<?php meni("prodavnica"); ?>
  		<!--MENI-->
        <div style="background-color:#699; height:40px; width:750px; margin-left:25px; margin-right:25px; margin-top:10px; border-radius:10px;">
        	<img src="../images/pack.png" title="Paketi za ponistavanje igara" width="30" height="30" style="margin-top:5px; margin-bottom:5px; float:left; margin-left:5px;" />
            <p title="Paketi za ponistavanje igara" style="color:white; float:left; margin-left:2px; margin-top:10px; margin-bottom:10px; font-size:20px;"><?php echo get_ponistavanje_igre_value($nick); ?></p>
            <img src="../images/tokeni.png" title="Tokeni" width="30" height="30" style="margin-top:5px; margin-bottom:5px; float:left; margin-left:15px;" />
            <p title="Tokeni" style="color:white; float:left; margin-left:2px; margin-top:10px; margin-bottom:10px; font-size:20px;"><?php echo get_token_value($nick); ?></p>
            <img src="../images/vip.png" title="Reputacija" width="30" height="30" style="margin-top:5px; margin-bottom:5px; float:left; margin-left:15px;" />
            <p title="Reputacija" style="color:white; float:left; margin-left:2px; margin-top:10px; margin-bottom:10px; font-size:20px;"><?php echo get_reputacija($nick)."&nbsp;"; ?></p>
            <img src="../images/kljuc.png" title="Kljuc za otkljucavanje partija" width="30" height="30" style="margin-top:5px; margin-bottom:5px; float:left; margin-left:15px;" />
            <p title="Kljuc za otkljucavanje partija" style="color:white; float:left; margin-left:2px; margin-top:10px; margin-bottom:10px; font-size:20px;"><?php echo getKljuc($nick); ?></p>
        </div>
        <div id="leva_strana" style="float:left; clear:left; margin:0px;">
        <div style="margin-left:25px; margin-top:5px; width:200px; color:white; background-color:#699; border-radius:10px;">
        	<div style="background-color:#369; width:200px;">
            	<p style="color:red; text-align:center; margin-top:2px; margin-bottom:2px; font-size:15px;">Ponisti danasnju igru</p>
            </div>
            <form id="ponisti" method="get" style="font-family: 'Courier New', Courier, monospace;" action="../ponisti_igru.php">
            <select name="igre" id="igre" style="color: red; border-radius:10px; margin-top:10px; margin-bottom:10px; width:170px; margin-left:15px; margin-right:15px;">
            	<option value="slagalica">Slagalica</option>
                <option value="moj_broj">Moj Broj</option>
                <option value="spojnice">Spojnice</option>
                <option value="skocko">Skocko</option>
                <option value="koznazna">Ko zna zna</option>
                <option value="asocijacije">Asocijacije</option>
            </select>
            <input type="submit" value="Ponisti" style="color:red; background-color:#369; margin-bottom:10px; border-radius:10px; width:80px; margin-left:60px; margin-right:60px;" />
        	</form>
        </div>
        
        <div style="margin-left:25px; margin-top:5px; float:left; width:200px; color:white; background-color:#699; border-radius:10px;">
        	<div style="background-color:#369; float:left; width:200px;">
            	<p style="color:red; text-align:center;  font-size:15px;">Rang lista</p>
            </div>
            <?php
				list($rank,$poeni)=getRank($nick,'celokupna');
			?>
            <div style="background-color:#9C0; float:left; margin-left:10px; border-radius:10px; margin-right:10px; margin-top:5px; width:180px;">
            	<img src="../images/gold tr.png" width="20" height="20" style=" margin-top:5px; float:left; margin-bottom:5px;" />
                <p style="color:red; float:left; font-size:14px; margin-top:8px; margin-bottom:8px;">Celokupna <?php echo "#$rank ($poeni)"; ?></p>
            </div>
            <?php
				list($rank,$poeni)=getRank($nick,'mesecna');
			?>
            <div style="background-color:#9C0; float:left; margin-left:10px; border-radius:10px; margin-right:10px; margin-top:5px; width:180px;">
            	<img src="../images/silver tr.png" width="20" height="20" style=" margin-top:5px; float:left; margin-bottom:5px;" />
                <p style="color:red; float:left; font-size:14px; margin-top:8px; margin-bottom:8px;">Mesecna <?php echo "#$rank ($poeni)"; ?></p>
            </div>
           <?php
				list($rank,$poeni)=getRank($nick,'nedeljna');
			?>
            <div style="background-color:#9C0; float:left; margin-left:10px; border-radius:10px; margin-right:10px; margin-top:5px; width:180px;">
            	<img src="../images/bronze tr.png" width="20" height="20" style=" margin-top:5px; float:left; margin-bottom:5px;" />
                <p style="color:red; float:left; font-size:14px; margin-top:8px; margin-bottom:8px;">Nedeljna <?php echo "#$rank ($poeni)"; ?></p>
            </div>
            <?php
				list($rank,$poeni)=getRank($nick,'dnevna');
			?>
            <div style="background-color:#9C0; float:left; margin-left:10px; border-radius:10px; margin-bottom:5px; margin-right:10px; margin-top:5px; width:180px;">
            	<img src="../images/kalendar.png" width="15" height="20" style=" margin-top:5px; margin-left:3px; margin-right:3px; float:left; margin-bottom:5px;" />
                <p style="color:red; float:left; font-size:14px; margin-top:8px; margin-bottom:8px;">Dnevna <?php echo "#$rank ($poeni)"; ?></p>
            </div>
        </div>
        
        <div style="margin-left:25px; margin-top:5px; float:left; clear:both; width:200px; color:white; background-color:#699; border-radius:10px;">
        	<div style="background-color:#369; float:left; width:200px;">
            	<p style="color:red; text-align:center; font-size:15px;">Mini igra</p>
            </div>
            <?php
				$data=mysql_query("SELECT * FROM mini_igra WHERE nick='$nick' AND datum='".date("d-m-Y")."'");
				if(mysql_num_rows($data)==0)
				{
					echo "<div style='float:left; border-radius:10px; background-color:#9F6; margin-right:2px; margin-left:2px; margin-top:5px;'><p style='color:red; font-size:13px; word-wrap:normal; width:196px;'> Nova mini igra: Unesite broj 1-200 i ako pogodite broj, osvajate jackpot.</p></div>";
					echo "<form method='post' action='../mini_igra.php'><input type='text' autocomplete='off' name='mg_broj' maxlength='3' style='color:red; float:left; border-radius:10px; margin-left:2px; margin-top:10px; width:80px;' /><input type='submit' value='Uplati (10 tok.)' style='color:red; background-color:#369; margin-top:11px; border-radius:10px; width:100px; float:left; margin-left:10px; ' /></form>";
				}
				else
				{
					$obj=mysql_fetch_assoc($data);
					echo "<div style='float:left; border-radius:10px; background-color:#9F6; margin-right:2px; margin-left:2px; margin-top:5px;'><p style='color:red; font-size:13px; word-wrap:normal; width:196px;'>Odigrali ste vasu srecku i uplatili 10 tokena na broj ".$obj['broj'].". Ukoliko bas vi budete srecni dobitnik, bicete obavesteni sutra.</p></div>";
				}
			?>
            <div style='float:left; width:196px; border-radius:10px; background-color:#9F6; margin-bottom:10px; margin-right:2px; margin-left:2px; margin-top:5px;'><p style='color:red; margin-left:1px; margin-right:1px; font-size:13px; word-wrap:normal; width:194px;'>Jucerasnjih dobitnika: <?php $brdob=LoadSettings('mini-igra-broj-dobitnika'); echo $brdob['opcija']; ?> </br> Jackpot: <?php $brdob=LoadSettings('mini-igra-total'); echo $brdob['opcija']." tokena"; ?></p></div>
        </div>
        </div>
        <div id="desna_strana" style="float:right; max-height:500px; overflow-y:scroll; clear:right; border-radius:10px; width:525px; margin-left:25px; margin-right:25px; margin-top:5px;">
        	<div style="background-color:#369; width:100%;">
            	<p style="color:red; text-align:center; font-size:15px;">Otkljucavanje prethodnih partija</p>
            </div>
            <table width="100%" style="border:1px solid #369; background-color:#699; color:black;">
            	<tr>
                	<td id="polje">Datum</td>
                    <td id="polje">Slagalica</td>
                    <td id="polje">Moj Broj</td>
                    <td id="polje">Spojnice</td>
                    <td id="polje">Skocko</td>
                    <td id="polje">Ko zna zna</td>
                    <td id="polje">Asocijacije</td>
                    <td id="polje">Status</td>
                </tr>
                <?php
					$date=date("d-m-Y");
					$komponente_vremena=explode('-',$date);
					$broj_dana=date("t");
					$br='';
					for($i=1;$i<=$broj_dana;$i++)
					{
						if($i<10)
						$br="0".$i;
						else
						$br=$i;
						$status=getGameStatus($nick,$br."-".$komponente_vremena[1]."-".$komponente_vremena[2]);
						$sd=$br."-".$komponente_vremena[1]."-".$komponente_vremena[2];
						switch($status['status'])
						{
							case 'danasnja_igra_0':
							$status['slagalica']='<a href="../slagalica.php" style="text-decoration:none; font-size:12px; color:black; display:block; width:100%;">IGRAJ</a>';
							$status['moj_broj']='<a href="../mojbroj.php" style="text-decoration:none; font-size:12px; color:black; display:block; width:100%;">IGRAJ</a>';
							$status['spojnice']='<a href="../spojnice.php" style="text-decoration:none; font-size:12px; color:black; display:block; width:100%;">IGRAJ</a>';
							$status['skocko']='<a href="../skocko.php" style="text-decoration:none; font-size:12px; color:black; display:block; width:100%;">IGRAJ</a>';
							$status['koznazna']='<a href="../koznazna.php" style="text-decoration:none; font-size:12px; color:black; display:block; width:100%;">IGRAJ</a>';
							$status['asocijacije']='<a href="../asocijacije.php" style="text-decoration:none; font-size:12px; color:black; display:block; width:100%;">IGRAJ</a>';
							$status['polje']='<a href="#" title="OTKLJUCANO" style="text-decoration:none; font-size:12px; color:black; display:block; width:100%;">OTKLJ.</a>';
							$status['stil']='background-color:green;';
							break;
							case 'zakljucano_0':
							$status['slagalica']='<a href="#" style="text-decoration:none; font-size:12px; color:black; display:block; width:100%;">IGRAJ</a>';
							$status['moj_broj']='<a href="#" style="text-decoration:none; font-size:12px; color:black; display:block; width:100%;">IGRAJ</a>';
							$status['spojnice']='<a href="#" style="text-decoration:none; font-size:12px; color:black; display:block; width:100%;">IGRAJ</a>';
							$status['skocko']='<a href="#" style="text-decoration:none; font-size:12px; color:black; display:block; width:100%;">IGRAJ</a>';
							$status['koznazna']='<a href="#" style="text-decoration:none; font-size:12px; color:black; display:block; width:100%;">IGRAJ</a>';
							$status['asocijacije']='<a href="#" style="text-decoration:none; font-size:12px; color:black; display:block; width:100%;">IGRAJ</a>';
							$status['polje']='<a href="otkljucaj.php?datum='.$sd.'" title="ZAKLJUCANO" style="text-decoration:none; font-size:12px; color:black; display:block; width:100%;"><img src="../images/kljuc1.png" height="12" width="50" /></a>';
							$status['stil']='background-color:#CCC;';
							break;
							case 'blokirano_0':
							$status['slagalica']='<a href="#" style="text-decoration:none; font-size:12px; color:black; display:block; width:100%;">IGRAJ</a>';
							$status['moj_broj']='<a href="#" style="text-decoration:none; font-size:12px; color:black; display:block; width:100%;">IGRAJ</a>';
							$status['spojnice']='<a href="#" style="text-decoration:none; font-size:12px; color:black; display:block; width:100%;">IGRAJ</a>';
							$status['skocko']='<a href="#" style="text-decoration:none; font-size:12px; color:black; display:block; width:100%;">IGRAJ</a>';
							$status['koznazna']='<a href="#" style="text-decoration:none; font-size:12px; color:black; display:block; width:100%;">IGRAJ</a>';
							$status['asocijacije']='<a href="#" style="text-decoration:none; font-size:12px; color:black; display:block; width:100%;">IGRAJ</a>';
							$status['polje']='<a href="#" title="NEDOSTUPNO" style="text-decoration:none; font-size:12px; color:black; display:block; width:100%;">NEDOST.</a>';
							$status['stil']='background-color:#666;';
							break;
							case 'danasnja_igra_1':
							$status['slagalica']=($status['igrana_slagalica']=="da")?'<a href="#" style="text-decoration:none; font-size:12px; color:black; display:block; width:100%;">'.$status['slagalica'].'</a>':'<a href="../slagalica.php" style="text-decoration:none; font-size:12px; color:black; display:block; width:100%;">IGRAJ</a>';
							$status['moj_broj']=($status['igrana_moj_broj']=="da")?'<a href="#" style="text-decoration:none; font-size:12px; color:black; display:block; width:100%;">'.$status['moj_broj'].'</a>':'<a href="../mojbroj.php" style="text-decoration:none; font-size:12px; color:black; display:block; width:100%;">IGRAJ</a>';
							$status['spojnice']=($status['igrana_spojnice']=="da")?'<a href="#" style="text-decoration:none; font-size:12px; color:black; display:block; width:100%;">'.$status['spojnice'].'</a>':'<a href="../spojnice.php" style="text-decoration:none; font-size:12px; color:black; display:block; width:100%;">IGRAJ</a>';
							$status['skocko']=($status['igrana_skocko']=="da")?'<a href="#" style="text-decoration:none; font-size:12px; color:black; display:block; width:100%;">'.$status['skocko'].'</a>':'<a href="../skocko.php" style="text-decoration:none; font-size:12px; color:black; display:block; width:100%;">IGRAJ</a>';
							$status['koznazna']=($status['igrana_koznazna']=="da")?'<a href="#" style="text-decoration:none; font-size:12px; color:black; display:block; width:100%;">'.$status['koznazna'].'</a>':'<a href="../koznazna.php" style="text-decoration:none; font-size:12px; color:black; display:block; width:100%;">IGRAJ</a>';
							$status['asocijacije']=($status['igrana_asocijacije']=="da")?'<a href="#" style="text-decoration:none; font-size:12px; color:black; display:block; width:100%;">'.$status['asocijacije'].'</a>':'<a href="../asocijacije.php" style="text-decoration:none; font-size:12px; color:black; display:block; width:100%;">IGRAJ</a>';
							$status['polje']='<a href="#" title="OTKLJUCANO" style="text-decoration:none; font-size:12px; color:black; display:block; width:100%;">OTKLJ.</a>';
							$status['stil']='background-color:green;';
							break;
							case 'zakljucano_1':
							$status['slagalica']=($status['igrana_slagalica']=="da")?'<a href="#" style="text-decoration:none; font-size:12px; color:black; display:block; width:100%;">'.$status['slagalica'].'</a>':'<a href="#" style="text-decoration:none; font-size:12px; color:black; display:block; width:100%;">IGRAJ</a>';
							$status['moj_broj']=($status['igrana_moj_broj']=="da")?'<a href="#" style="text-decoration:none; font-size:12px; color:black; display:block; width:100%;">'.$status['moj_broj'].'</a>':'<a href="#" style="text-decoration:none; font-size:12px; color:black; display:block; width:100%;">IGRAJ</a>';
							$status['spojnice']=($status['igrana_spojnice']=="da")?'<a href="#" style="text-decoration:none; font-size:12px; color:black; display:block; width:100%;">'.$status['spojnice'].'</a>':'<a href="#" style="text-decoration:none; font-size:12px; color:black; display:block; width:100%;">IGRAJ</a>';
							$status['skocko']=($status['igrana_skocko']=="da")?'<a href="#" style="text-decoration:none; font-size:12px; color:black; display:block; width:100%;">'.$status['skocko'].'</a>':'<a href="#" style="text-decoration:none; font-size:12px; color:black; display:block; width:100%;">IGRAJ</a>';
							$status['koznazna']=($status['igrana_koznazna']=="da")?'<a href="#" style="text-decoration:none; font-size:12px; color:black; display:block; width:100%;">'.$status['koznazna'].'</a>':'<a href="#" style="text-decoration:none; font-size:12px; color:black; display:block; width:100%;">IGRAJ</a>';
							$status['asocijacije']=($status['igrana_asocijacije']=="da")?'<a href="#" style="text-decoration:none; font-size:12px; color:black; display:block; width:100%;">'.$status['asocijacije'].'</a>':'<a href="#" style="text-decoration:none; font-size:12px; color:black; display:block; width:100%;">IGRAJ</a>';
							$status['polje']='<a href="#" title="NEDOSTUPNO" style="text-decoration:none; font-size:12px; color:black; display:block; width:100%;">NEDOST.</a>';
							$status['stil']='background-color:#666;';
							break;
							case 'otkljucano_1':
							$status['slagalica']=($status['igrana_slagalica']=="da")?'<a href="#" style="text-decoration:none; font-size:12px; color:black; display:block; width:100%;">'.$status['slagalica'].'</a>':'<a href="../slagalica.php?datum='.$sd.'" style="text-decoration:none; font-size:12px; color:black; display:block; width:100%;">IGRAJ</a>';
							$status['moj_broj']=($status['igrana_moj_broj']=="da")?'<a href="#" style="text-decoration:none; font-size:12px; color:black; display:block; width:100%;">'.$status['moj_broj'].'</a>':'<a href="../mojbroj.php?datum='.$sd.'" style="text-decoration:none; font-size:12px; color:black; display:block; width:100%;">IGRAJ</a>';
							$status['spojnice']=($status['igrana_spojnice']=="da")?'<a href="#" style="text-decoration:none; font-size:12px; color:black; display:block; width:100%;">'.$status['spojnice'].'</a>':'<a href="../spojnice.php?datum='.$sd.'" style="text-decoration:none; font-size:12px; color:black; display:block; width:100%;">IGRAJ</a>';
							$status['skocko']=($status['igrana_skocko']=="da")?'<a href="#" style="text-decoration:none; font-size:12px; color:black; display:block; width:100%;">'.$status['skocko'].'</a>':'<a href="../skocko.php?datum='.$sd.'" style="text-decoration:none; font-size:12px; color:black; display:block; width:100%;">IGRAJ</a>';
							$status['koznazna']=($status['igrana_koznazna']=="da")?'<a href="#" style="text-decoration:none; font-size:12px; color:black; display:block; width:100%;">'.$status['koznazna'].'</a>':'<a href="../koznazna.php?datum='.$sd.'" style="text-decoration:none; font-size:12px; color:black; display:block; width:100%;">IGRAJ</a>';
							$status['asocijacije']=($status['igrana_asocijacije']=="da")?'<a href="#" style="text-decoration:none; font-size:12px; color:black; display:block; width:100%;">'.$status['asocijacije'].'</a>':'<a href="../asocijacije.php?datum='.$sd.'" style="text-decoration:none; font-size:12px; color:black; display:block; width:100%;">IGRAJ</a>';
							$status['polje']='<a href="#" title="OTKLJUCANO" style="text-decoration:none; font-size:12px; color:black; display:block; width:100%;">OTKLJ.</a>';
							$status['stil']='background-color:green;';
							break;
						}
						echo "<tr>
                	<td id='polje'>$br-".$komponente_vremena[1]."-".$komponente_vremena[2]."</td>
                    <td id='polje' style='".$status['stil']."'>".$status['slagalica']."</td>
                    <td id='polje' style='".$status['stil']."'>".$status['moj_broj']."</td>
                    <td id='polje' style='".$status['stil']."'>".$status['spojnice']."</td>
                    <td id='polje' style='".$status['stil']."'>".$status['skocko']."</td>
                    <td id='polje' style='".$status['stil']."'>".$status['koznazna']."</td>
                    <td id='polje' style='".$status['stil']."'>".$status['asocijacije']."</td>
                    <td id='polje' style='".$status['stil']."'>".$status['polje']."</td>
                </tr>";
					}
				?>
            </table>
        </div>
    </div>
</body>
</html>