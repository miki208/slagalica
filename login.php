<?php
	require_once('connect.php');
	$error = '0';
	//checkBrowser();
	if(isset($_SESSION['userName']))
	header("Location: index.php");

if (isset($_POST['submitBtn'])){
	// Get user input
	$username = isset($_POST['username']) ? $_POST['username'] : '';
	$password = isset($_POST['password']) ? $_POST['password'] : '';
        
	// Try to login the user
	$error = loginUser($username,$password);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<link href="style/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
<title>Mikisoft Slagalica</title>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<meta name="author" content="mikisoft.slagalica@gmail.com" />
<meta name="keywords" content="mikisoft,slagalica,igrica,ko zna zna,asocijacije,spojnice,tv,kviz" />
<meta name="description" content="Ljubitelj ste TV slagalice? I Vi biste hteli da se oprobate? Pa sta cekate onda, nasa igrica je prava stvar za Vas!" />
<script type="text/javascript">

	function init()
	{
		<?php if(isset($_GET['potvrda'])){ if($_GET['potvrda']=='ok')echo "alert('Vas nalog je uspesno potvrdjen. Sada se mozete prijaviti.');"; else echo "alert('Ovaj nalog ne moze biti potvrdjen ili je doslo do greske.');";}?>
	}



</script>

<link rel="stylesheet" href="touchimg/touching.css" type="text/css" />
</head>
<body onload="init()">
	
 <!-- Generated at www.csscreator.com -->
<div id="container">

	<div id="banner">
		<div id='bannertitle'>Mikisoft Slagalica</div>
	</div>

	<div id="outer">
 		<div id="inner">
 			<div id="left">
  <div class="verticalmenu">
   <ul>
     <?php
	 	if(isset($_SESSION['userName']))
		{
			echo '<li><a title="Profil" href="profil/index.php">'."Vas Profil".'</a></li>';
			echo '<li><a href="logout.php">Izloguj se</a></li>';
		}
		else
		{
			echo '<li><a href="login.php">Uloguj se</a></li>';
			echo '<li><a href="register.php">Registruj se</a></li>';
		}
	 ?>
   </ul>
 </div> 

   		</div>
   		<div id="content"><h2 align="center"></h2>
        <div id="main">
<?php if ($error != '') {?>
      <div class="caption">Logovanje</div>
      <div id="icon">&nbsp;</div>
      <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="loginform">
        <table width="100%">
          <tr><td>Korisnicko ime:</td><td> <input class="text" name="username" type="text"  /></td></tr>
          <tr><td>Sifra:</td><td> <input class="text" name="password" type="password" /></td></tr>
          <tr><td colspan="2" align="center"><input class="text" type="submit" name="submitBtn" value="Login" /></td></tr>
        </table>  
      </form>
      
      &nbsp;<a href="register.php">Registruj se</a>
      
<?php 
}   
    if (isset($_POST['submitBtn'])){

?>
      <div class="caption">Login result:</div>
      <div id="icon2">&nbsp;</div>
      <div id="result">
        <table width="100%"><tr><td><br/>
<?php
	if ($error == '') {
		echo "Dobrodosao $username! <br/>Ulogovan si!<br/>";
		if(mysql_num_rows(mysql_query("SELECT * FROM lista WHERE username='".$_SESSION['userName']."' AND bonus_prihvacen='ne'"))==1)
		{
			$combo=get_combo($_SESSION['userName']);
			mysql_query("UPDATE lista SET bonus_prihvacen='da' WHERE username='".$_SESSION['userName']."'");
			$exp=0;
			$tok=0;
			switch($combo)
			{
				case 1:
					$tok=50;
					$exp=4;
					break;
				case 2:
					$tok=60;
					$exp=8;
					break;
				case 3:
					$tok=70;
					$exp=12;
					break;
				case 4:
					$tok=80;
					$exp=16;
					break;
				case 5:
					$tok=100;
					$exp=20;
					break;
			}
			$temp=$exp;
			$exp=$exp+floor(intval(get_reputacija($_SESSION['userName']))/100);
			addExp($_SESSION['userName'],$exp);
			$temp1=$tok;
			$tok=$tok+intval(getLevel($_SESSION['userName']));
			add_tokens($_SESSION['userName'],$tok);
			kreiraj_notifikaciju($_SESSION['userName'],"Dnevni poklon","Dolazite $combo dana za redom i osvajate $exp iskustva i $tok tokena. Zelimo Vam srecno igranje!");
			echo "Dolazis <font style='color:red;'>$combo</font> dana za redom, i osvajas:<br/>-<font style='color:red;'>$tok</font> tokena<br/>-<font style='color:red;'>$exp</font> iskustva.<br/><br/>";
		}
		echo '<a href="index.php">Sada mozes posetiti slagalicu!</a>';
	}
	else echo $error;

?>
		<br/><br/><br/></td></tr></table>
	</div>
<?php            
    }
?>
	<div id="source" style="color:red;">Mikisoft Logovanje</br>
    <?php
	$rezultat=mysql_query("SELECT username FROM lista");
	$num=mysql_num_rows($rezultat);
	echo " Registrovanih korisnika: $num</br>";
    ?>
    </div>
    </div>
		</div><!-- end content -->
		</div><!-- end inner -->
	</div><!-- end outer -->
 	<div id="footer">
 	  <h1><a href="#">MIKISOFT</a></h1>
 	</div>
</div><!-- end container -->
</body></html>
	