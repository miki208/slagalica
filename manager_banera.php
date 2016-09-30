<?php
	include "connect.php";
	checkUser();
	if(!checkPerm($_SESSION['userName'],"baner_add"))
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
<title>Manager banera</title>

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
</style>
<script type="text/javascript">
	function prikazi(slika)
	{
		document.getElementById("slika").src="baneri/"+slika;
		document.getElementById("slika").style.left=window.event.clientX+5+"px";
		document.getElementById("slika").style.top=window.event.clientY+5+"px";
		document.getElementById("slika").style.visibility="visible";
	}
	
	function ukloni()
	{
		document.getElementById("slika").style.visibility="hidden";
	}
</script>
</head>

<body>
	<img src="#" id="slika" style="width:200px; position:absolute; height:130px; z-index:1; visibility:hidden;" />
	<div id="wrapper">
         <!--MENI-->
  		<?php meni("glavni"); ?>
        <!--MENI-->
    	<table border="1" bordercolor="#FF0000"; style="width:700px; background-color:#FFF; font-family: 'Courier New', Courier, monospace; margin-top:10px; margin-left:50px; margin-right:50px; margin-bottom:5px;">
        	<tr>
            	<td style="color:red; text-align:left; width:100px;">
                	ID
                </td>
                <td style="color:red; text-align:left; width:100px;">
                	SLIKA
                </td>
                <td style="color:red; text-align:left; width:100px; overflow:hidden;">
                	ADRESA
                </td>
                <td style="color:red; text-align:left; width:310px; overflow:hidden;">
                	NATPIS
                </td>
                <td style="color:red; text-align:left; width:40px; overflow:hidden;">
                	POSETE
                </td>
                <td style="color:red; text-align:left; width:150px;">
                	STATUS
                </td>
          </tr>
                <?php
					$min=abs(intval($_GET['page']-1))*5;
					$data=mysql_query("SELECT * FROM baneri ORDER BY id LIMIT $min, 5");
					$num=mysql_num_rows(mysql_query("SELECT * FROM baneri"));
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
                			  <td onmouseover="prikazi(\''.$niz['lnk'].'\')" onmouseout="ukloni()" style="color:red; cursor:pointer; text-align:left; width:100px;">
                				'.$niz['lnk'].'
                			  </td>
                			  <td style="color:red; text-align:left; width:100px;">
                				'.$niz['adresa'].'
                			  </td>
                			  <td style="color:red; title="'.stripslashes($niz['title']).'" text-align:left; width:310px;">
                				'.stripslashes($niz['title']).'
                			  </td>
							  <td style="color:red; text-align:left; width:40px;">
                				'.$niz['posete'].'
                			  </td>
                			  <td style="color:red; text-align:left; width:150px;">
                				<a style="color:'.$color_a.'; text-decoration:none; margin-left:10px; margin-top:10px; background-color:yellow; border-radius:5px; " href="uredi_baner.php?akcija=aktivacija&id='.$niz['id'].'">Aktivan</a>
								</br>
								<a style="color:'.$color_n.'; text-decoration:none; margin-left:10px; margin-top:10px; background-color:yellow; border-radius:5px; " href="uredi_baner.php?akcija=deaktivacija&id='.$niz['id'].'">Neaktivan</a>
								</br>
								<a style="color:red; text-decoration:none; margin-top:10px; margin-left:10px; background-color:yellow; border-radius:5px;" href="uredi_baner.php?akcija=brisanje&id='.$niz['id'].'">Obrisati</a>
                			  </td></tr>';
					}
					?>
        </table>
        <label style="margin-left:50px;"></label>
        <?php
			$br=ceil($num/5);
			for($i=1;$i<=$br;$i++)
			if($_GET['page']!=$i)
			echo '<a style="color:red; margin-left:4px;" href="manager_banera.php?page='.$i.'">'.$i.'</a>';
			else
			echo '<a style="color:green; margin-left:4px;" href="manager_banera.php?page='.$i.'">'.$i.'</a>';
		?>
	</div>
</body>
</html>	