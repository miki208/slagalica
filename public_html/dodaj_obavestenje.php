<?php
	include "connect.php";
	checkUser();
	if(!checkPerm($_SESSION['userName'],"info_add"))
	header("Location: index.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Dodaj obavestenje</title>

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
	min-height: 500px;
	width: 800px;
	margin-right: auto;
	margin-left: auto;
	border: 1px solid #F00;
}
#tekst {
	font-family: "Courier New", Courier, monospace;
	font-size: 24px;
	color: #F00;
	width: 700px;
	height: 200px;
	margin-right: 50px;
	margin-left: 50px;
	text-align: left;
	margin-top: 10px;
	margin-bottom: 10px;
	resize:none;
	border-radius:15px;
}
#objavi {
	font-family: "Courier New", Courier, monospace;
	font-size: 36px;
	color: #F00;
	margin-right: 300px;
	margin-left: 300px;
	width: 200px;
	margin-bottom: 100px;
	border-radius:15px;
}
</style>
</head>
<body>
	<div id="wrapper">
        <!--MENI-->
  		<?php meni("glavni"); ?>
        <!--MENI-->
    	<form action="cuvaj_obavestenje.php" method="post">
    		<textarea style="font-family: 'Courier New', Courier, monospace;" name="tekst" id="tekst"></textarea>
          <input type="submit" style="font-family: 'Courier New', Courier, monospace;" id="objavi" value="OBJAVI" />
        </form>
	</div>
</body>
</html>
	