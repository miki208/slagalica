<?php
	include "connect.php";
	checkUser();
	if(isset($_SESSION['userName']))
	{
		if($_SESSION['userName']!="Admin")
		{
			header('Location: index.php');
		}
		$cheat=LoadSettings("anti-cheat");
		$logo=LoadSettings("index-logo");
		$pozadina=LoadSettings("pozadina");
		$obavestenja=LoadSettings("buffer-obavestenja");
		$fb=LoadSettings("fb");
		$slagalica_time=LoadSettings("slagalica-time");
		$mojbroj_time=LoadSettings("mojbroj-time");
		$spojnice_time=LoadSettings("spojnice-time");
		$skocko_time=LoadSettings("skocko-time");
		$koznazna_time=LoadSettings("koznazna-time");
		$asocijacije_time=LoadSettings("asocijacije-time");
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Settings Panel</title>
<!--MENI-->
  	<?php meni_css(); ?>
<!--MENI-->
<style type="text/css">
body {
	background-image: url(<?php $bg=LoadSettings("pozadina"); echo $bg['opcija']; ?>);
	background-repeat: repeat;
}
* {
	margin: 0px;
}
#wrapper {
	
	color: #F00;
	background-image: url(dost-ind1.jpg);
	background-repeat: repeat;
	height: auto;
	width: 100%;
	margin-right: auto;
	margin-left: auto;
}
</style>
</head>

<body>
	<div id="wrapper" style="float:left;">
    	<!--MENI-->
  		<?php meni("glavni"); ?>
 		 <!--MENI-->
    	<p style="width:100%; font-size:36px; font-family: 'Courier New', Courier, monospace; color:red; text-align:center;">Podesavanja</p>
        <form action="save_settings.php" style="font-family: 'Courier New', Courier, monospace;" method="post">
        <p style="color:red; float:left; font-size:15px; margin-left:20px; margin-top:50px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Anti-cheat (0-1)</p>
        <input type="text" style="width:40px; margin-top:50px; float:left; font-size:13px; text-align:left; margin-left:5px;" name="cheat" value="<?php echo $cheat['opcija']; ?>" />
        
        <p style="color:red; clear:left; float:left; font-size:15px; margin-left:20px; margin-top:10px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Logo (local-url)</p>
        <input type="text" style="width:250px; float:left; margin-top:10px; font-size:13px; text-align:left; margin-left:5px;" name="logo" value="<?php echo $logo['opcija']; ?>"  />
        
        <p style="color:red; clear:left; float:left; font-size:15px; margin-left:20px; margin-top:10px;">&nbsp;&nbsp;&nbsp;Pozadina (local-url)</p>
        <input type="text" style="width:250px; float:left; margin-top:10px; font-size:13px; text-align:left; margin-left:5px;" name="pozadina" value="<?php echo $pozadina['opcija']; ?>"  />
        
        <p style="color:red; clear:left; float:left; font-size:15px; margin-left:20px; margin-top:10px;">Buffer obavestenja (br)</p>
        <input type="text" style="width:40px; float:left; margin-top:10px; font-size:13px; text-align:left; margin-left:5px;" name="obavestenja" value="<?php echo $obavestenja['opcija']; ?>"  />
        
        <p style="color:red; clear:left; float:left; font-size:15px; margin-left:20px; margin-top:10px;">&nbsp;&nbsp;Slagalica timer (sec)</p>
        <input type="text" style="width:40px; float:left; margin-top:10px; font-size:13px; text-align:left; margin-left:5px;" name="slagalica_time" value="<?php echo $slagalica_time['opcija']; ?>"  />
        
        <p style="color:red; clear:left; float:left; font-size:15px; margin-left:20px; margin-top:10px;">&nbsp;&nbsp;&nbsp;Moj broj timer (sec)</p>
        <input type="text" style="width:40px; float:left; margin-top:10px; font-size:13px; text-align:left; margin-left:5px;" name="mojbroj_time" value="<?php echo $mojbroj_time['opcija']; ?>"  />
        
        <p style="color:red; clear:left; float:left; font-size:15px; margin-left:20px; margin-top:10px;">&nbsp;&nbsp;&nbsp;Spojnice timer (sec)</p>
        <input type="text" style="width:40px; float:left; margin-top:10px; font-size:13px; text-align:left; margin-left:5px;" name="spojnice_time" value="<?php echo $spojnice_time['opcija']; ?>"  />
        
        <p style="color:red; clear:left; float:left; font-size:15px; margin-left:20px; margin-top:10px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Skocko timer (sec)</p>
        <input type="text" style="width:40px; float:left; margin-top:10px; font-size:13px; text-align:left; margin-left:5px;" name="skocko_time" value="<?php echo $skocko_time['opcija']; ?>"  />
        
        <p style="color:red; clear:left; float:left; font-size:15px; margin-left:20px; margin-top:10px;">&nbsp;Ko zna zna timer (sec)</p>
        <input type="text" style="width:40px; float:left; margin-top:10px; font-size:13px; text-align:left; margin-left:5px;" name="koznazna_time" value="<?php echo $koznazna_time['opcija']; ?>"  />
        
        <p style="color:red; clear:left; float:left; font-size:15px; margin-left:20px; margin-top:10px;">Asocijacije timer (sec)</p>
        <input type="text" style="width:40px; float:left; margin-top:10px; font-size:13px; text-align:left; margin-left:5px;" name="asocijacije_time" value="<?php echo $asocijacije_time['opcija']; ?>"  />
        
        <p style="color:red; clear:left; float:left; font-size:15px; margin-left:20px; margin-top:10px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Fb statusi (0-1)</p>
        <input type="text" style="width:40px; float:left; margin-top:10px; font-size:13px; text-align:left; margin-left:5px;" name="fb" value="<?php echo $fb['opcija']; ?>"  />
        
        <input type="submit" style="text-align:center; width:200px; margin-left:20px; font-size:36px; float:left; margin-top:30px; margin-bottom:50px; clear:both; color:white; background-color:#06C; border-radius:10px;" value="SACUVAJ" />
        
        </form>
	</div>
</body>
</html>