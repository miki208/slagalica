<?php
	include "connect.php";
	if(isset($_SESSION['userName']))
	header("Location: index.php");
	if (isset($_POST['submitBtn'])){
		// Get user input
		$username  = isset($_POST['username']) ? $_POST['username'] : '';
		$password1 = isset($_POST['password1']) ? $_POST['password1'] : '';
		$password2 = isset($_POST['password2']) ? $_POST['password2'] : '';
		$mail=isset($_POST['email']) ? $_POST['email'] : '';
        
		// Try to register the user
		$error = registerUser($username,$mail,$password1,$password2);
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
<meta name="description" content="Mikisoft Slagalica" />


<link rel="stylesheet" href="touchimg/touching.css" type="text/css" />
</head>
<body>
 <!-- Generated at www.csscreator.com -->
<div id="container">
	<div id="banner">
		<div id='bannertitle'>Registracija</div>
	</div>

	<div id="outer">
 		<div id="inner">
 			<div id="left">
  <div class="verticalmenu">
   <ul>
     <?php
	 	if(isset($_SESSION['userName']))
		{
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
<?php if ((!isset($_POST['submitBtn'])) || ($error != '')) {?>
      <div class="caption">Registruj se</div>
      <div id="icon">&nbsp;</div>
      <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="registerform">
        <table width="100%">
          <tr><td>Korisnicko ime:</td><td> <input class="text" name="username" type="text"  /></td></tr>
          <tr><td>E-mail:</td><td> <input class="text" name="email" type="text"  /></td></tr>
          <tr><td>Sifra:</td><td> <input class="text" name="password1" type="password" /></td></tr>
          <tr><td>Potvrdi sifru:</td><td> <input class="text" name="password2" type="password" /></td></tr>
          <tr><td colspan="2" align="center"><input class="text" type="submit" name="submitBtn" value="Register" /></td></tr>
        </table>  
      </form>
     
<?php 
}   
    if (isset($_POST['submitBtn'])){

?>
      <div class="caption">Rezultat registracije:</div>
      <div id="icon2">&nbsp;</div>
      <div id="result">
        <table width="100%"><tr><td><br/>
<?php
	if ($error == '') {
		echo " Korisnik: $username je registrovan uspesno!<br/><br/>";
		echo ' Potvrdna mail poruka je poslata na vas mail, kako bi aktivirali nalog, potvrdite je u roku od 7 dana.<br/><br/>';
		echo '<a href="login.php">Nakon potvrde, ovde se mozes ulogovati</a>';
	}
	else echo $error;

?>
		<br/><br/><br/></td></tr></table>
	</div>
<?php            
    }
?>
	<div id="source" style="color:red;">Mikisoft Registracija</br>
    <?php
	$rezultat=mysql_query("SELECT username FROM lista");
	$num=mysql_num_rows($rezultat);
	echo " Registrovanih korisnika: $num </br>";
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