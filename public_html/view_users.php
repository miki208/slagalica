<?php
	include "connect.php";
	checkUser();
	if(!checkPerm($_SESSION['userName'],"view_info"))
	{
		header("Location: index.php");
	}
	if(!isset($_GET['page'])||$_GET['page']=="")
	{
		$_GET['page']="1";
	}
	if(!isset($_GET['nacin']))
	{
		$_GET['nacin']="ASC";
	}
	if(!isset($_GET['sort']))
	{
		$_GET['sort']="username";
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Slagalica Korisnici</title>
<!--MENI-->
  	<?php meni_css(); ?>
<!--MENI-->
<script type="text/javascript">
function profil()
{
					
}
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

<body background="<?php $bg=LoadSettings("pozadina"); echo $bg['opcija']; ?>">
                <!--MENI-->
  		<?php meni("glavni"); ?>
  		<!--MENI-->
	<div style="width:800px;  margin-left:auto; margin-right:auto;  overflow-x:hidden; overflow-y:scroll; height:500px; background-color:yellow;" id="okvir">
    	 
    	<?php
			$num=mysql_num_rows(mysql_query("SELECT * FROM lista"));
			if($num==0)
			{
				echo "Nema korisnika!!!";
			}
			else
			{
				$lk="";
				if($_GET['nicksearch']!=""&&isset($_GET['nicksearch']))
				$lk=" WHERE username LIKE '".$_GET['nicksearch']."%' ";
				$min=abs(intval($_GET['page']-1))*10;
				$dataa=mysql_query("SELECT * FROM lista $lk ORDER BY ".$_GET['sort']." ".$_GET['nacin']." LIMIT $min, 10");
                                $num=mysql_num_rows(mysql_query("SELECT * FROM lista $lk ORDER BY ".$_GET['sort']." ".$_GET['nacin']));
				while($niza=mysql_fetch_assoc($dataa))
				{
					$data=mysql_query("SELECT tokeni, profilna, reputacija, ponistavanje_igre, email, registrovan, status FROM lista WHERE username='".mysql_escape_string($niza['username'])."'");
					$niz=mysql_fetch_assoc($data);
						
					$data1=mysql_query("SELECT exp, level FROM poeni WHERE nick='".mysql_escape_string($niza['username'])."'");
					$niz1=mysql_fetch_assoc($data1);
						
					$data2=mysql_query("SELECT MAX(slagalica+moj_broj+spojnice+skocko+koznazna+asocijacije) AS max_p, ROUND(AVG(slagalica+moj_broj+spojnice+skocko+koznazna+asocijacije),0) AS pros_p FROM slagalica_dnevne_liste WHERE nick='".mysql_escape_string($niza['username'])."'");
					$niz2=mysql_fetch_assoc($data2);
						
					$data3=mysql_query("SELECT COUNT(id) AS adm FROM admini WHERE nick='".mysql_escape_string($niza['username'])."'");
					$niz3=mysql_fetch_assoc($data3);

                                        $data4=mysql_query("SELECT SUM(slagalica+moj_broj+spojnice+skocko+koznazna+asocijacije) AS suma_svih FROM slagalica_dnevne_liste WHERE nick='".mysql_escape_string($niza['username'])."'");
					$niz4=mysql_fetch_assoc($data4);

                                        $data5=mysql_query("SELECT SUM(slagalica+moj_broj+spojnice+skocko+koznazna+asocijacije) AS suma_mesec FROM slagalica_dnevne_liste WHERE nick='".mysql_escape_string($niza['username'])."' AND datum LIKE '___".date("m-Y")."'");
                                        $niz5=mysql_fetch_assoc($data5);
                                      
					echo "<div id='user' onclick='profil()' style='margin-left:50px; margin-right:50px; margin-top:20px; cursor:pointer; min-height:150px; border:1px solid red; width:700px; background-image:url(rank-ind1.png);'>
							<p style='color:red; margin-bottom:1px; text-align:center;'>".mysql_escape_string($niza['username'])."</p>
							<img src='profilne/".$niz['profilna']."' width='80' height='80' style='margin:10px; float:left;'/>
							<p style='color:red; margin-top:1px; margin-right:10px; border:1px solid red; float:left;'>Tokeni: <font style='color:white;'>".$niz['tokeni']."</font></br>Reputacija: <font style='color:white;'>".$niz['reputacija']."</font></br>PI: <font style='color:white;'>".$niz['ponistavanje_igre']."</font></br>Iskustvo: <font style='color:white;'>".$niz1['exp']."</font></br>Level: <font style='color:white;'>".$niz1['level']."</font></p>
							<p style='color:red; margin-top:1px; margin-right:10px; float:left; border:1px solid red;'>Broj partija: <font style='color:white;'>".mysql_num_rows(mysql_query("SELECT id FROM slagalica_dnevne_liste WHERE nick='".mysql_escape_string($niza['username'])."'"))."</font></br>Broj partija (mesec): <font style='color:white;'>".mysql_num_rows(mysql_query("SELECT id FROM slagalica_dnevne_liste WHERE nick='".mysql_escape_string($niza['username'])."' AND datum LIKE '___".date("m-Y")."'"))."</font></br>Max poena: <font style='color:white;'>".$niz2['max_p']."</font></br>Pros. broj poena: <font style='color:white;'>".$niz2['pros_p']."</font></br>Administrator: <font style='color:white;'>".$niz3['adm']."</font></p>
							<p style='color:red; margin-top:1px; margin-right:10px; float:left; border:1px solid red;'>E-mail: <font style='color:white;'>".$niz['email']."</font></br>Ukupno bodova: <font style='color:white;'>".$niz4['suma_svih']."</font></br>Ukupno bodova (mesec): <font style='color:white;'>".$niz5['suma_mesec']."</font></br>Datum registracije: <font style='color:white;'>".$niz['registrovan']."</font></br>Status: <font style='color:white;'>".$niz['status']."</font></p>
						</div>";
				}
			}
		?>
	</div>
    <div style="width:800px; margin-left:auto; margin-right:auto; overflow-x:hidden; background-color:yellow;" id="okvir">
        <?php $br=ceil($num/10);
              $sr=array("","",""); if($_GET['sort']=="username"){$sr[0]="selected='selected'"; $sr[1]=""; $sr[2]="";}
	      if($_GET['sort']=="tokeni"){$sr[0]=""; $sr[1]="selected='selected'"; $sr[2]="";}
	      if($_GET['sort']=="reputacija"){$sr[0]=""; $sr[1]=""; $sr[2]="selected='selected'";}
	      $nac=array("","");
	      if($_GET['nacin']=="ASC"){$nac[0]="selected='selected'"; $nac[1]="";}
	      if($_GET['nacin']=="DESC"){$nac[0]=""; $nac[1]="selected='selected'";}
        ?>
<p style="color:red; float:left; margin-left:4px;"><?php echo $_GET['page']."/".$br; ?></p> <form action="view_users.php" style="margin-top:13px; " method="get"><input type="text" style="background-color:white; margin-left:5px; color:red; border-radius:10px; width:40px; float:left;" onkeypress="validate()" maxlength="4" name="page" /><select style="color:red; border-radius:10px;" name="sort"><option <?php echo $sr[0]; ?> value="username">Nick</option><option <?php echo $sr[1]; ?> value="tokeni">Tokeni</option><option <?php echo $sr[2]; ?> value="reputacija">Reputacija</option></select><select style="color:red; border-radius:10px;" name="nacin"><option <?php echo $nac[0]; ?> value="ASC">Rastuce</option><option <?php echo $nac[1]; ?> value="DESC">Opadajuce</option></select><font style="color:red;">Nick (opciono): </font><input type="text" style="background-color:white; color:red; border-radius:10px; width:100px;" maxlength="100" value="<?php if($_GET['nicksearch']!=""&&isset($_GET['nicksearch'])) echo $_GET['nicksearch'];?>" name="nicksearch" /> <input type="submit" value="OK" style="color:red; border-radius:10px; background-color:white;"  /></form> 
</div>
</body>				