<?php
	include "../connect.php";
	checkUser();
	$nick=$_SESSION['userName'];
	$page=0;
	if(!isset($_GET['page']))
	{
		$page=1;
	}
	else
	{
		$page=intval(mysql_escape_string($_GET['page']));
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Obavestenja</title>
<?php meni_css(); ?>
<style type="text/css">
body {
	background-image: url(<?php $bg=LoadSettings("pozadina"); echo "../".$bg['opcija']; ?>);
	background-repeat: repeat;
}

*{
	margin:0px;
}

#wrapper
{
	min-height:500px;
	color: #F00;
	background-image: url(../dost-ind1.jpg);
	width:800px;
	margin-left:auto;
	margin-right:auto;
}
</style>
<script type="text/javascript">

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
</script>
</head>

<body>
	<div id="wrapper">
    <div style='width:800px; height:550px; overflow-y:scroll; '>
    <?php meni("profil"); ?>
    <?php
		$color="";
		$min=abs(intval($page)-1)*10;
		$data=mysql_query("SELECT * FROM notifikacije WHERE nick='$nick' ORDER BY pogledano ASC, id DESC LIMIT $min, 10");
		while($niz=mysql_fetch_assoc($data))
		{
			if($niz['pogledano']=="0")
			{
				$color="#3F0";
				mysql_query("UPDATE notifikacije SET pogledano='1' WHERE id='".$niz['id']."'");
			}
			else
			$color="#FF0";
			echo "
        			<div id='stavka' style='width:770px; margin-left:10px; margin-right:10px; margin-top:10px; background-color:".$color."; opacity:0.8; border-radius:10px; min-height:40px;'>
            			<p style='width:750px; margin-left:10px; margin-right:10px; word-wrap:normal; text-align:center'><b>".$niz['naslov']."</b></p>
                		<p style='width:750px; margin-left:10px; margin-right:10px; word-wrap:normal; margin-top:5px; text-align:left'>".$niz['text']."</p>
            		</div>";
		}
		$num=mysql_num_rows(mysql_query("SELECT * FROM notifikacije WHERE nick='$nick'"));
		$br=ceil($num/10);
	?>
     </div>
    <div style="width:800px; margin-left:auto; margin-right:auto; background-color:yellow;" >
    <p style="color:red; float:left; margin-left:4px;"><?php echo $page."/".$br; ?></p> <form action="obavestenja.php" style="" method="get"><input type="text" style="background-color:white; margin-left:5px; color:red; border-radius:10px; width:40px; float:left;" onkeypress="validate()" maxlength="4" name="page" /> <input type="submit" value="OK" style="color:red; border-radius:10px; background-color:white;"  /></form>
    </div>
    </div>
</body>
</html>