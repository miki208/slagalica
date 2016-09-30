<?php
	include "connect.php";
	checkUser();
	if(!checkPerm($_SESSION['userName'],"baner_add"))
	header("Location: index.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Dodaj baner</title>
<!--MENI-->
  	<?php meni_css(); ?>
<!--MENI-->
<script type="text/javascript">

</script>
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
	min-height: 600px;
	width: 800px;
	margin-right: auto;
	margin-left: auto;
	border: 1px solid #F00;
}

#dodaj {
	font-family: "Courier New", Courier, monospace;
	font-size: 36px;
	color: #F00;
	margin-right: 200px;
	margin-left: 200px;
	width: 200px;
	margin-bottom: 100px;
	border-radius:15px;
	margin-top:30px;
}
</style>
</head>
<body>
    <div id="wrapper">
    <!--MENI-->
  	<?php meni("glavni"); ?>
  <!--MENI-->
    	<form action="upload_file.php" method="post" enctype="multipart/form-data">
        	<div style="width:600px; margin-left:100px; margin-right:100px; text-align:center; margin-top:20px; margin-bottom:20px; border-radius:10px; background-color:yellow;">
    			<label for="file" style="margin-top:10px; margin-bottom:10px; font-family: 'Courier New', Courier, monospace; font-size:20px;">Odaberi baner:</label>
        		<input type="file" style="margin-top:10px; margin-bottom:10px; font-size:20px;" name="file" id="file" /> 
                <label for="adresa" style="margin-top:10px; margin-bottom:10px; font-family: 'Courier New', Courier, monospace; font-size:20px;">Unesi adresu:</label>
                <input type="text" style="margin-top:10px; margin-bottom:10px; font-family: 'Courier New', Courier, monospace; width:300px; font-size:20px;" name="adresa" />
                </br>
                <label for="title" style="margin-top:10px; margin-bottom:10px; font-family: 'Courier New', Courier, monospace; font-size:20px;">Unesi natpis:</label>
                <input type="text" style="margin-top:10px; margin-bottom:10px; font-family: 'Courier New', Courier, monospace; width:300px; font-size:20px;" name="title" />
                <input type="submit" id="dodaj" value="DODAJ"/>
            </div>
        </form>  
    </div>
</body>
</html>