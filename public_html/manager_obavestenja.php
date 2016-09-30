<?php
	include "connect.php";
	checkUser();
	if(!checkPerm($_SESSION['userName'],"info_add"))
	header("Location: index.php");
	if(!isset($_GET['page']))
	{
		$_GET['page']="1";
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Manager obavestenja</title>
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
	width: 800px;
	margin-right: auto;
	margin-left: auto;
	border: 1px solid #F00;
}
</style>
<!--MENI-->
  	<?php meni_css(); ?>
<!--MENI-->
</head>

<body>
	<div id="wrapper">
    	<?php meni("glavni"); ?>
    	<table border="1" bordercolor="#FF0000"; style="width:700px; margin-top:10px; font-family: 'Courier New', Courier, monospace; margin-left:50px; margin-right:50px; margin-bottom:5px;">
        	<tr>
            	<td style="color:red; text-align:left; width:100px;">
                	ID
                </td>
                <td style="color:red; text-align:left; width:100px;">
                	NICK
                </td>
                <td style="color:red; text-align:left; width:100px;">
                	DATUM
                </td>
                <td style="color:red; text-align:left; width:350px; overflow:hidden;">
                	TEXT
                </td>
                <td style="color:red; text-align:left; width:150px;">
                	STATUS
                </td>
             </tr>
                <?php
					$min=abs(intval($_GET['page']-1))*5;
					$data=mysql_query("SELECT * FROM obavestenja ORDER BY id LIMIT $min, 5");
					$num=mysql_num_rows(mysql_query("SELECT * FROM obavestenja"));
					while($niz=mysql_fetch_assoc($data))
					{
						$color_a="";
						$color_n="";
						if($niz['status']=="aktivan")
						{
							$color_a="green";
							$color_n="red";
						}
						else
						{
							$color_a="red";
							$color_n="green";
						}
						echo '<tr><td style="color:red; text-align:left; width:100px;">
                				'.$niz['id'].'
                			  </td>
                			  <td style="color:red; text-align:left; width:100px;">
                				'.$niz['nick'].'
                			  </td>
                			  <td style="color:red; text-align:left; width:100px;">
                				'.$niz['datum'].'
                			  </td>
                			  <td style="color:red; title="'.stripslashes($niz['stavka']).'" text-align:left; width:350px;">
                				'.stripslashes($niz['stavka']).'
                			  </td>
                			  <td style="color:red; text-align:left; width:150px;">
                				<a style="color:'.$color_a.'; text-decoration:none; margin-left:10px; margin-top:10px; background-color:yellow; border-radius:5px; " href="uredi_obavestenje.php?akcija=aktivacija&id='.$niz['id'].'">Aktivan</a>
								</br>
								<a style="color:'.$color_n.'; text-decoration:none; margin-left:10px; margin-top:10px; background-color:yellow; border-radius:5px; " href="uredi_obavestenje.php?akcija=deaktivacija&id='.$niz['id'].'">Neaktivan</a>
								</br>
								<a style="color:red; text-decoration:none; margin-top:10px; margin-left:10px; background-color:yellow; border-radius:5px;" href="uredi_obavestenje.php?akcija=brisanje&id='.$niz['id'].'">Obrisati</a>
                			  </td></tr>';
					}
					?>
        </table>
        <a href="index.php" style="color:red; font-family: 'Courier New', Courier, monospace; margin-left:10px;">H</a>
        <?php
			$br=ceil($num/5);
			for($i=1;$i<=$br;$i++)
			if($_GET['page']!=$i)
			echo '<a style="color:red; margin-left:4px; font-family: \'Courier New\', Courier, monospace;" href="manager_obavestenja.php?page='.$i.'">'.$i.'</a>';
			else
			echo '<a style="color:green; font-family: \'Courier New\', Courier, monospace; margin-left:4px;" href="manager_obavestenja.php?page='.$i.'">'.$i.'</a>';
		?>
	</div>
</body>
</html>