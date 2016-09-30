<?php
	include "connect.php";
	checkUser();
	if(isset($_SESSION['userName']))
	{
		if(!checkPerm($_SESSION['userName'],"supervizor"))
		{
			header('Location: index.php');
		}
	}
	$data=mysql_query("SELECT * FROM buffer_reci");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Supervizor</title>
<!--MENI-->
  	<?php meni_css(); ?>
<!--MENI-->
<style type="text/css">
<!--
body {
	background-image: url(<?php $bg=LoadSettings("pozadina"); echo $bg['opcija']; ?>);
	background-repeat: repeat;
}
* {
	margin: 0px;
}
#wrapper {
	min-height: 500px;
	width: 800px;
	margin-right: auto;
	margin-left: auto;
	background-image: url(dost-ind1.jpg);
	background-repeat: repeat;
	color: #FF0000;
}
-->
</style>
</head>

<body>
<div id="wrapper">
	<!--MENI-->
  	<?php meni("glavni"); ?>
  <!--MENI-->
	<table style="width:800px; margin-top:5px; margin-left:0px; margin-right:0px; border:2px solid red;;">
    	<tr>
        	<td style="text-align:center; border:2px solid red;">Nick</td>
            <td style="text-align:center; border:2px solid red;">Rec</td>
            <td style="text-align:center; border:2px solid red;">Bodovi</td>
            <td style="text-align:center; border:2px solid red;">Potvrda</td>
        </tr>
        <?php
		while($niz=mysql_fetch_assoc($data))
		{
			echo'
			<tr>
        	<td style="text-align:center; border:2px solid red;">'.$niz['nick'].'</td>
            <td style="text-align:center; border:2px solid red;" title="'.$niz['termin'].'" ">'.toReal1($niz['rec']).'</td>
            <td style="text-align:center; border:2px solid red;">'.$niz['bodovi'].'</td>
            <td style="text-align:center; border:2px solid red;">
            	<a style="text-decoration:none; color:yellow; background-color:green; border-radius:3px; margin-right:15px;" href="supervizor_potvrda.php?ok=tacno&id='.$niz['id'].'">Tacno</a> 
                <a style="text-decoration:none; color:yellow; background-color:red; border-radius:3px;" href="supervizor_potvrda.php?ok=netacno&id='.$niz['id'].'">Netacno</a>
            </td></tr>';
		}
        ?>
    </table>
</div>
</body>
</html>
