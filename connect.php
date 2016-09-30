<?php
	$con = mysql_connect("localhost","mikslag_admin","");
	if (!$con)
	{
		die('Could not connect: ' . mysql_error());
	}
	mysql_select_db("mikslag_slagalica", $con);
	
	session_start();
	
	function checkName($usnm)
	{
		for($i=0;$i<strlen($usnm);$i++)
		{
			if((ord($usnm[$i])>=48&&ord($usnm[$i])<=57)||(ord($usnm[$i])>=64&&ord($usnm[$i])<=90)||(ord($usnm[$i])>=97&&ord($usnm[$i])<=122)||$usnm[$i]==' '||$usnm[$i]=='_'||$usnm[$i]=='.')
			{
				;
			}
			else
			return false;
		}
		return true;
	}

function registerUser($user,$mail,$pass1,$pass2){
	$errorText = '';
	$pass1=mysql_escape_string($pass1);
	$pass2=mysql_escape_string($pass2);
	$user=mysql_escape_string($user);
	$mail=mysql_escape_string($mail);
	// Check passwords
	if ($pass1 != $pass2) $errorText = "Sifre se ne slazu!";
	elseif (strlen($pass1) < 6) $errorText = "Sifra je prekratka!";
	if (strlen($user)>17) $errorText = "Nick moze sadrzati maksimalno 17 karaktera!";
	if (strlen($user)<3) $errorText = "Nick mora sadrzati minimalno 3 karaktera!";
	$rezultat=mysql_query("SELECT username FROM lista WHERE username='$user'");
	if(mysql_num_rows($rezultat)!=0)
	$errorText = "Korisnicko ime je zauzeto!";
	if(!checkName($user))
	{
		$errorText="Nedozvoljeni znaci!";
	}
	if(mysql_num_rows(mysql_query("SELECT * FROM lista WHERE email='$mail'"))!=0)
		$errorText='Ova email adresa je vec registrovana!';
	if(!strstr($mail,'@'))
		$errorText='Neispravna email adresa!';
	if($mail=='')
		$errorText='Morate uneti e-mail adresu!';
    // If everything is OK -> store user data
	$ip=$_SERVER['REMOTE_ADDR'];
    if ($errorText == ''){
		// Secure password string
		$key=substr(md5(uniqid(rand(),true)),0,10);
		$us=explode('@', $mail);
		$to = "$mail";
		$subject = 'Mikisoft Slagalica - Potvrda naloga';
		$headers = "To: ".$us[0]." <$mail>\n" .
		$nl='
		';
		"From: admin <mikslag@mikisoft-slagalica.in.rs>\n" .
		"MIME-Version: 1.0\n" .
		"Content-Type: text/html; charset=iso-8859-1";
		$message = " Pozdrav $user,$nl Da bi ste mogli da aktivirate vas Slagalica nalog, morate potvrditi da je ovo zaista Vas mail. $nl To cete uraditi vrlo jednostavno, klikom na sledeci link: $nl http://mikisoft-slagalica.in.rs/mail_potvrda.php?key=$key $nl Vas username: $user $nl Vasa lozinka: $pass1 $nl Uzivajte!!! Mikisoft Slagalica Admin";
		mail( $to, $subject, $message, $headers );
		$userpass = md5($pass1);
    	mysql_query("INSERT INTO lista(username, pw, ip,email,conf_code,status,registrovan) VALUES('$user', '$userpass', '$ip','$mail','$key','neaktivan','".date("d-m-Y H:i")."')");
	}
	return $errorText;
}

function loginUser($user,$pass){
	$errorText = '';
	$validUser = false;
	$pass=mysql_escape_string($pass);
	$user=mysql_escape_string($user);
	$rezultat=mysql_query("SELECT username, pw, datum, combo FROM lista WHERE username='$user'");
	if(mysql_num_rows($rezultat)==1)
	{
		$niz=mysql_fetch_assoc($rezultat);
		if($niz['username']==$user && trim($niz['pw'])==trim(md5($pass)))
		{
			if(mysql_num_rows(mysql_query("SELECT * FROM lista WHERE username='$user' AND status='neaktivan'"))>0)
    		{
    			$validUser=false;
    			$errorText='Prvo potvrdite Vas nalog tako sto cete otvoriti i procitati poruku koju smo uputili na Vas mail!';
    		}
    		else
    		{
				$validUser=true;
				$_SESSION['userName'] = $user;
				$datum=date("d-m-Y H:i");
				if(substr($datum,0,5)!=substr($niz['datum'],0,5))
				{
					if(substr(izracunaj_datum($datum,-1),0,5)==substr($niz['datum'],0,5))
					{
						$niz['combo']=strval(intval($niz['combo'])+1);
						if($niz['combo']=="6")
						$niz['combo']="1";
						mysql_query("UPDATE lista SET datum='$datum', combo='".$niz['combo']."', bonus_prihvacen='ne' WHERE username='".$_SESSION['userName']."'");
					}
					else
					{
						$niz['combo']="1";
						mysql_query("UPDATE lista SET datum='$datum', combo='".$niz['combo']."', bonus_prihvacen='ne' WHERE username='".$_SESSION['userName']."'");
					}
				}
			}
		}
		else
    	{
    		$errorText = "Nepravilno korisnicko ime ili sifra!";
    		$validUser=false;
		}
	}
    else
    {
    	$errorText = "Nepravilno korisnicko ime ili sifra!";
    	$validUser=false;
	}
    	
    if ($validUser == true)
    {
    	$_SESSION['validUser'] = true;
	}
    else $_SESSION['validUser'] = false;
	
	return $errorText;	
}

function logoutUser(){
	unset($_SESSION['validUser']);
	unset($_SESSION['userName']);
}

function checkUser(){
	if ((!isset($_SESSION['validUser'])) || ($_SESSION['validUser'] != true)||($_SESSION['userName']=='')){
		header('Location: login.php');
	}
}


function trenutniKorisnik($kor)
{
	$result=mysql_query("SELECT ID FROM lista WHERE username='$kor'");
	$podatak=mysql_fetch_assoc($result);
	return $podatak['ID'];
}

function checkPerm($nick,$perm)
{
	$admini=mysql_query("SELECT * FROM admini WHERE nick='$nick'");
	if(mysql_num_rows($admini)!=1)
	return false;
	else
	{
		$admin_data=mysql_fetch_assoc($admini);
		$ovlascenja=explode("*",$admin_data['ovlascenje']);
		if(!in_array($perm,$ovlascenja))
		return false;
	}
	return true;
}

function isPerm($perm)
{
	if(mysql_num_rows(mysql_query("SELECT * FROM ovlascenja WHERE kod='$perm'"))==1)
	return true;
	else
	return false;
}

function isUser($usr)
{
	if(mysql_num_rows(mysql_query("SELECT * FROM lista WHERE username='$usr'"))==1)
	return true;
	else
	return false;
}

function isGame($game)
{
	if($game=="slagalica"||$game=="moj_broj"||$game=="spojnice"||$game=="skocko"||$game=="koznazna"||$game=="asocijacije")
	return true;
	else
	return false;
}

function checkBrowser()
{
	$user_agent=$_SERVER['HTTP_USER_AGENT'];
	if(strpos($user_agent,"Chrome")===false)
	header("Location: obavestenje.php");
}

function granicni_exp($level)
{
	$exp=0;
	for($i=0;$i<=$level;$i++)
	$exp+=$i*100;
	return $exp;
}

function addExp($nick,$kol)
{
	$data=mysql_query("SELECT * FROM poeni WHERE nick='$nick'");
	if(mysql_num_rows($data)==1)
	{
		$d=mysql_fetch_assoc($data);
		$lvl=intval($d['level']);
		$kol+=intval($d['exp']);
		while($kol>=granicni_exp($lvl))
		++$lvl;
		mysql_query("UPDATE poeni SET exp='$kol', level='$lvl', next='".granicni_exp($lvl)."' WHERE nick='$nick'");
	}
	else
	{
		mysql_query("INSERT INTO poeni(nick,exp,level,next) VALUES('$nick','$kol','1','".granicni_exp(1)."')");
		addExp($nick,0);
	}
}

function getExp($nick)
{
	$lvl="";
	$exp="";
	$next="";
	$data=mysql_query("SELECT * FROM poeni WHERE nick='$nick'");
	if(mysql_num_rows($data)==1)
	{
		$d=mysql_fetch_assoc($data);
		$lvl=$d['level'];
		$exp=$d['exp'];
		$next=$d['next'];
	}
	else
	{
		mysql_query("INSERT INTO poeni(nick,exp,level,next) VALUES('$nick','0','1','".granicni_exp(1)."')");
		$lvl="1";
		$exp="0";
		$next="100";
	}
	return "$lvl*$exp*$next";
}

function ispisDostignuca($ukupno,$share)
{
	$naziv=array();
	$nick=array();
	$leveli=explode('*',getExp($_SESSION['userName']));
	$prazan=1;
	$dostignuca_data=mysql_query("SELECT * FROM dostignuca_slagalica WHERE nick='".$_SESSION['userName']."'");
	$dostignuca=array();
	while($dostignuca=mysql_fetch_assoc($dostignuca_data))
	{
		$prazan=0;
		array_push($naziv,$dostignuca['naziv']);
		array_push($nick,$dostignuca['nick']);
	}
	////////////////////////Legenda je rodjena///////////////////
	if(in_array("Legenda je rodjena",$naziv)==false)
	{
		if(mysql_num_rows(mysql_query("SELECT * FROM slagalica_dnevne_liste WHERE nick='".$_SESSION['userName']."' AND igrana_slagalica='da' AND igrana_moj_broj='da' AND igrana_spojnice='da' AND igrana_skocko='da' AND igrana_koznazna='da' AND igrana_asocijacije='da'"))>0)
		{
			echo '<img src="achievements/1.jpg" title="Legenda je rodjena - Odigraj sve igre prvi put"/>';
			mysql_query("INSERT INTO dostignuca_slagalica(naziv,nick) VALUES('Legenda je rodjena','".$_SESSION['userName']."')");
			kreiraj_notifikaciju($_SESSION['userName'],"Dostignuce otkljucano - Legenda je rodjena","Odigrali ste sve igre prvi put i otkljucali ste dostignuce: Legenda je rodjena.");
			dodaj_status($_SESSION['userName'],"Odigrao sam sve igre prvi put i otkljucao sam dostignuce: Legenda je rodjena. Ja sam buduca zvezda ove igrice!");
		}
		else
		echo '<img src="U.png" title="Legenda je rodjena - Odigraj sve igre prvi put"/>';
	}
	else
	{
		echo '<img src="achievements/1.jpg" title="Legenda je rodjena - Odigraj sve igre prvi put"/>';
	}
	////////////////////////Legenda je rodjena///////////////////
	
	////////////////////////Varalica///////////////////
	if(in_array("Varalica",$naziv)==false)
	{
		if(isset($_GET['cheat']))
		{
			echo '<img src="achievements/2.png" title="Varalica - Izbacen iz igre zbog varanja bar jedan put"/>';
			mysql_query("INSERT INTO dostignuca_slagalica(naziv,nick) VALUES('Varalica','".$_SESSION['userName']."')");
			kreiraj_notifikaciju($_SESSION['userName'],"Dostignuce otkljucano - Varalica","Niste bili korektni, hteli ste da varate i otkljucali ste dostignuce: Varalica.");
			dodaj_status($_SESSION['userName'],"Hteo sam maksimalan broj bodova pa sam odlucio da varam, i otkljucao sam dostignuce: Varalica. Nisam znao da cu izgubiti partiju, obecavam da vise necu varati!");
		}
		else
		{
			echo '<img src="U.png" title="Varalica - Izbacen iz igre zbog varanja bar jedan put"/>';
		}
	}
	else
	{
		echo '<img src="achievements/2.png" title="Varalica - Izbacen iz igre zbog varanja bar jedan put"/>';
	}
	////////////////////////Varalica///////////////////
	
	////////////////////////Citac misli///////////////////
	if(in_array("citac_misli",$naziv)==false)
	{
		echo '<img src="U.png" title="Citac misli - Pogodi istu rec kao i kompjuter"/>';
	}
	else
	{
		echo '<img src="achievements/3.jpg" title="Citac misli - Pogodi istu rec kao i kompjuter"/>';
	}
	////////////////////////Citac misli///////////////////
	
	////////////////////////Klikerko///////////////////
	if(in_array("klikerko",$naziv)==false)
	{
		echo '<img src="U.png" title="Klikerko - Pronadji vecu rec nego kompjuter"/>';
	}
	else
	{
		echo '<img src="achievements/4.jpg" title="Klikerko - Pronadji vecu rec nego kompjuter"/>';
	}
	////////////////////////Klikerko///////////////////
	
	////////////////////////Veliki Mudrac///////////////////
	if(in_array("mudrac",$naziv)==false)
	{
		if($ukupno>=180&&$share=="dost")
		{
			echo '<img src="achievements/5.jpg" title="Veliki Mudrac - Osvoji najmanje 180 bodova"/>';
			mysql_query("INSERT INTO dostignuca_slagalica(naziv,nick) VALUES('mudrac','".$_SESSION['userName']."')");
			kreiraj_notifikaciju($_SESSION['userName'],"Dostignuce otkljucano - Veliki mudrac","Osvojili ste vise od 180 bodova i otkljucali ste dostignuce: Veliki mudrac.");
			dodaj_status($_SESSION['userName'],"Osvojio sam vise od 180 bodova i otkljucao sam dostignuce: Veliki mudrac. Ova igrica je prosta kao pasulj za ljude kao sto sam ja!");
		}
		else
		echo '<img src="U.png" title="Veliki Mudrac - Osvoji najmanje 180 bodova"/>';
	}
	else
	{
		echo '<img src="achievements/5.jpg" title="Veliki Mudrac - Osvoji najmanje 180 bodova"/>';
	}
	////////////////////////Veliki Mudrac///////////////////
	
	////////////////////////Bronza///////////////////
	if(in_array("bronza",$naziv)==false)
	{
		if($ukupno>=150&&$share=="dost")
		{
			echo '<img src="achievements/6.jpg" title="Bronzana medalja - Osvoji najmanje 150 bodova"/>';
			mysql_query("INSERT INTO dostignuca_slagalica(naziv,nick) VALUES('bronza','".$_SESSION['userName']."')");
			kreiraj_notifikaciju($_SESSION['userName'],"Dostignuce otkljucano - Bronzana medalja","Osvojili ste vise od 150 bodova i otkljucali ste dostignuce: Bronzana medalja.");
			dodaj_status($_SESSION['userName'],"Osvojio sam vise od 150 bodova i otkljucao sam dostignuce: Bronzana medalja. Srebrna medalja je u najavi!");
		}
		else
		echo '<img src="U.png" title="Bronzana medalja - Osvoji najmanje 150 bodova"/>';
	}
	else
	{
		echo '<img src="achievements/6.jpg" title="Bronzana medalja - Osvoji najmanje 150 bodova"/>';
	}
	////////////////////////Bronza///////////////////
	
	////////////////////////Srebro///////////////////
	if(in_array("srebro",$naziv)==false)
	{
		if($ukupno>=160&&in_array("bronza",$naziv)&&$share=="dost")
		{
			echo '<img src="achievements/7.jpg" title="Srebrna medalja - Osvoji najmanje 160 bodova, sa prethodno osvojenom bronzanom medaljom"/>';
			mysql_query("INSERT INTO dostignuca_slagalica(naziv,nick) VALUES('srebro','".$_SESSION['userName']."')");
			kreiraj_notifikaciju($_SESSION['userName'],"Dostignuce otkljucano - Srebrna medalja","Osvojili ste vise od 160 bodova, sa prethodno osvojenom bronzanom medaljom i otkljucali ste dostignuce: Srebrna medalja.");
			dodaj_status($_SESSION['userName'],"Osvojio sam vise od 160 bodova sa prethodno osvojenom bronzanom medaljom i otkljucao sam dostignuce: Srebrna medalja. Zlatna medalja ceka samo na mene. Dolazim!");
		}
		else
		echo '<img src="U.png" title="Srebrna medalja - Osvoji najmanje 160 bodova, sa prethodno osvojenom bronzanom medaljom"/>';
	}
	else
	{
		echo '<img src="achievements/7.jpg" title="Srebrna medalja - Osvoji najmanje 160 bodova, sa prethodno osvojenom bronzanom medaljom"/>';
	}
	////////////////////////Srebro///////////////////
	
	////////////////////////Zlato///////////////////
	if(in_array("zlato",$naziv)==false)
	{
		if($ukupno>=170&&in_array("bronza",$naziv)&&in_array("srebro",$naziv)&&$share=="dost")
		{
			echo '<img src="achievements/8.jpg" title="Zlatni trofej - Osvoji najmanje 170 bodova, sa prethodno osvojenom srebrnom i bronzanom medaljom"/>';
			mysql_query("INSERT INTO dostignuca_slagalica(naziv,nick) VALUES('zlato','".$_SESSION['userName']."')");
			kreiraj_notifikaciju($_SESSION['userName'],"Dostignuce otkljucano - Zlatni trofej","Osvojili ste vise od 170 bodova sa prethodno osvojenom bronzanom i srebrnom medaljom i otkljucali ste dostignuce: Zlatni trofej.");
			dodaj_status($_SESSION['userName'],"Osvojio sam vise od 170 bodova sa prethodno osvojenom bronzanom i srebrnom medaljom i otkljucao sam dostignuce: Zlatni trofej. Oduvek sam sanjao da postanem sampion, taj san mi se konacno ostvario!");
		}
		else
		echo '<img src="U.png" title="Zlatni trofej - Osvoji najmanje 170 bodova, sa prethodno osvojenom srebrnom i bronzanom medaljom"/>';
	}
	else
	{
		echo '<img src="achievements/8.jpg" title="Zlatni trofej - Osvoji najmanje 170 bodova, sa prethodno osvojenom srebrnom i bronzanom medaljom"/>';
	}
	////////////////////////Zlato///////////////////
	
	////////////////////////Nemoguca misija///////////////////
	if(in_array("nemoguca_misija",$naziv)==false)
	{
		if($ukupno>=190&&$share=="dost")
		{
			echo '<img src="achievements/9.jpg" title="Nemoguca misija - Osvoji najmanje 190 bodova"/>';
			mysql_query("INSERT INTO dostignuca_slagalica(naziv,nick) VALUES('nemoguca_misija','".$_SESSION['userName']."')");
			kreiraj_notifikaciju($_SESSION['userName'],"Dostignuce otkljucano - Nemoguca misija","Osvojili ste vise od 190 bodova i otkljucali ste dostignuce: Nemoguca misija.");
			dodaj_status($_SESSION['userName'],"Osvojio sam vise od 190 bodova i otkljucao sam dostignuce: Nemoguca misija. Nista nije nemoguce, sve se moze kad se hoce!");
		}
		else
		echo '<img src="U.png" title="Nemoguca misija - Osvoji najmanje 190 bodova"/>';
	}
	else
	{
		echo '<img src="achievements/9.jpg" title="Nemoguca misija - Osvoji najmanje 190 bodova"/>';
	}
	////////////////////////Nemoguca misija///////////////////
	
	////////////////////////Nalozen na igricu///////////////////
	if(in_array("nalozen",$naziv)==false)
	{
		if(intval($leveli[0])>=10)
		{
			echo '<img src="achievements/10.jpg" title="Nalozen na igricu - Dostigni deseti nivo"/>';
			mysql_query("INSERT INTO dostignuca_slagalica(naziv,nick) VALUES('nalozen','".$_SESSION['userName']."')");
			kreiraj_notifikaciju($_SESSION['userName'],"Dostignuce otkljucano - Nalozen na igricu","Dostigli ste deseti nivo i otkljucali ste dostignuce: Nalozen na igricu.");
			dodaj_status($_SESSION['userName'],"Dostigao sam deseti nivo i otkljucao sam dostignuce: Nalozen na igricu. Sada ne znam kako da prestanem sa igranjem! Jedva cekam sutrasnju partiju.");
		}
		else
		echo '<img src="U.png" title="Nalozen na igricu - Dostigni deseti nivo"/>';
	}
	else
	{
		echo '<img src="achievements/10.jpg" title="Nalozen na igricu - Dostigni deseti nivo"/>';
	}
	////////////////////////Nalozen na igricu///////////////////
	
	////////////////////////Upornost, samo upornost///////////////////
	if(in_array("uporan",$naziv)==false)
	{
		if(intval($leveli[0])>=30)
		{
			echo '<img src="achievements/11.jpg" title="Upornost, samo upornost - Dostigni trideseti nivo"/>';
			mysql_query("INSERT INTO dostignuca_slagalica(naziv,nick) VALUES('uporan','".$_SESSION['userName']."')");
			kreiraj_notifikaciju($_SESSION['userName'],"Dostignuce otkljucano - Upornost, samo upornost","Dostigli ste trideseti nivo i otkljucali ste dostignuce: Upornost, samo upornost.");
			dodaj_status($_SESSION['userName'],"Dostigao sam trideseti nivo i otkljucao sam dostignuce: Upornost, samo upornost. Zelite da budete kao ja? Onda cete morati da budete veoma uporni!");
		}
		else
		echo '<img src="U.png" title="Upornost, samo upornost - Dostigni trideseti nivo"/>';
	}
	else
	{
		echo '<img src="achievements/11.jpg" title="Upornost, samo upornost - Dostigni trideseti nivo"/>';
	}
	////////////////////////Upornost, samo upornost///////////////////
	
	////////////////////////Ziva legenda///////////////////
	if(in_array("ziva_legenda",$naziv)==false)
	{
		if(intval($leveli[0])>=50)
		{
			echo '<img src="achievements/12.jpg" title="Ziva legenda - Dostigni pedeseti nivo, da da, dobro si cuo, pedeseti"/>';
			mysql_query("INSERT INTO dostignuca_slagalica(naziv,nick) VALUES('ziva_legenda','".$_SESSION['userName']."')");
			kreiraj_notifikaciju($_SESSION['userName'],"Dostignuce otkljucano - Ziva legenda","Dostigli ste pedeseti nivo i otkljucali ste dostignuce: Ziva legenda.");
			dodaj_status($_SESSION['userName'],"Dostigao sam pedeseti nivo i otkljucao sam dostignuce: Ziva legenda. Secate se kad sam rekao da sam buduca zvezda? Pa sada znate da vas nisam lagao!");
		}
		else
		echo '<img src="U.png" title="Ziva legenda - Dostigni pedeseti nivo, da da, dobro si cuo, pedeseti"/>';
	}
	else
	{
		echo '<img src="achievements/12.jpg" title="Ziva legenda - Dostigni pedeseti nivo, da da, dobro si cuo, pedeseti"/>';
	}
	////////////////////////Ziva legenda///////////////////
}

function getShortNick($nick)
{
	if(strlen($nick)>16)
	return substr($nick,0,13)."...";
	else
	return $nick;
}

function LoadSettings($setting)
{
	$data=mysql_query("SELECT * FROM podesavanja WHERE stavka='$setting'");
	return mysql_fetch_assoc($data);
}

function SaveSettings($setting,$option)
{
	mysql_query("UPDATE podesavanja SET opcija='$option' WHERE stavka='$setting'");
}

function razlikaDatuma($dat1,$dat2)//novo,staro
{
	$rast=explode(" ",$dat1);
	$vreme=$rast[1];
	$datum=$rast[0];
	$datum_isc=explode("-",$datum);
	$vreme_isc=explode(":",$vreme);
	$d1=intval($datum_isc[0]);
	$m1=intval($datum_isc[1]);
	$g1=intval($datum_isc[2]);
	$s1=intval($vreme_isc[0]);
	$min1=intval($vreme_isc[1]);
	
	$rast=explode(" ",$dat2);
	$vreme=$rast[1];
	$datum=$rast[0];
	$datum_isc=explode("-",$datum);
	$vreme_isc=explode(":",$vreme);
	$d2=intval($datum_isc[0]);
	$m2=intval($datum_isc[1]);
	$g2=intval($datum_isc[2]);
	$s2=intval($vreme_isc[0]);
	$min2=intval($vreme_isc[1]);
	
	$vr1=mktime($s1,$min1,0,$m1,$d1,$g1);
	$vr2=mktime($s2,$min2,0,$m2,$d2,$g2);
	
	return $vr1-$vr2;
}

function get_token_value($nick)
{
	$data=mysql_query("SELECT tokeni FROM lista WHERE username='$nick'");
	$niz=mysql_fetch_assoc($data);
	return $niz['tokeni'];
}

function add_tokens($nick,$value)
{
	mysql_query("UPDATE lista SET tokeni=tokeni+'$value' WHERE username='$nick'");
}

function use_tokens($nick,$value)
{
	if(intval(get_token_value($nick))<$value)
	{
		return false;
	}
	
	else
	{
		mysql_query("UPDATE lista SET tokeni=tokeni-'$value' WHERE username='$nick'");
		return true;
	}
}

function get_ponistavanje_igre_value($nick)
{
	$data=mysql_query("SELECT ponistavanje_igre FROM lista WHERE username='$nick'");
	$niz=mysql_fetch_assoc($data);
	return $niz['ponistavanje_igre'];
}

function use_ponistavanje_igre($nick)
{
	$value=1;
	if(intval(get_ponistavanje_igre_value($nick))<1)
	{
		return false;
	}
	else
	{
		mysql_query("UPDATE lista SET ponistavanje_igre=ponistavanje_igre-'$value' WHERE username='$nick'");
		return true;
	}
}

function add_ponistavanje_igre($nick,$value)
{
	mysql_query("UPDATE lista SET ponistavanje_igre=ponistavanje_igre+'$value' WHERE username='$nick'");
}

function date_mktime($date)
{
	$rast=explode(" ",$date);
	$vreme=$rast[1];
	$datum=$rast[0];
	$datum_isc=explode("-",$datum);
	$vreme_isc=explode(":",$vreme);
	$d1=intval($datum_isc[0]);
	$m1=intval($datum_isc[1]);
	$g1=intval($datum_isc[2]);
	$s1=intval($vreme_isc[0]);
	$min1=intval($vreme_isc[1]);
	$vr=mktime($s1,$min1,0,$m1,$d1,$g1);
	return $vr;
}

function izracunaj_datum($sada,$dani)
{
	$sada=date_mktime($sada)+$dani*24*3600;
	return date("d-m-Y H:i",$sada);
}

function add_reputacija($nick,$value)
{
	mysql_query("UPDATE lista SET reputacija=reputacija+'$value' WHERE username='$nick'");
}

function get_reputacija($nick)
{
	$data=mysql_query("SELECT reputacija FROM lista WHERE username='$nick'");
	$niz=mysql_fetch_assoc($data);
	return $niz['reputacija'];
}

function broj_zvezdica($nick)
{
	$nivoi=array(50,150,350,600,800,1100,1400,1800,2000,2500);
	$rep=intval(get_reputacija($nick));
	if($rep<$nivoi[0])
	return 0;
	if(($rep>=$nivoi[0])&&($rep<$nivoi[1]))
	return 1;
	if(($rep>=$nivoi[1])&&($rep<$nivoi[2]))
	return 2;
	if(($rep>=$nivoi[2])&&($rep<$nivoi[3]))
	return 3;
	if(($rep>=$nivoi[3])&&($rep<$nivoi[4]))
	return 4;
	if(($rep>=$nivoi[4])&&($rep<$nivoi[5]))
	return 5;
	if(($rep>=$nivoi[5])&&($rep<$nivoi[6]))
	return 6;
	if(($rep>=$nivoi[6])&&($rep<$nivoi[7]))
	return 7;
	if(($rep>=$nivoi[7])&&($rep<$nivoi[8]))
	return 8;
	if(($rep>=$nivoi[8])&&($rep<$nivoi[9]))
	return 9;
	if($rep>=$nivoi[9])
	return 10;
}

function get_combo($nick)
{
	$data=mysql_query("SELECT combo FROM lista WHERE username='$nick'");
	$niz=mysql_fetch_assoc($data);
	return intval($niz['combo']);
}

function obrisi_nepotvrdjene_naloge()
{
	$data=mysql_query("SELECT * FROM lista WHERE status='neaktivan' AND registrovan!='nepoznato'");
	while($niz=mysql_fetch_assoc($data))
	{
		if(date_mktime(izracunaj_datum($niz['registrovan'],7))<date_mktime(date("d-m-Y H:i")))
		{
			mysql_query("DELETE FROM dostignuca_slagalica WHERE nick='".$niz['username']."'");
			mysql_query("DELETE FROM lista WHERE username='".$niz['username']."'");
			mysql_query("DELETE FROM slagalica_dnevne_liste WHERE nick='".$niz['username']."'");
			mysql_query("DELETE FROM slagalica_rank WHERE nick='".$niz['username']."'");
			mysql_query("DELETE FROM slagalica_sesije WHERE nick='".$niz['username']."'");
			mysql_query("DELETE FROM sumnjivi_korisnici WHERE nick='".$niz['username']."'");
			mysql_query("DELETE FROM admini WHERE nick='".$niz['username']."'");
			mysql_query("DELETE FROM poeni WHERE nick='".$niz['username']."'");
		}
	}
}

function meni_css()
{
	echo '<style type="text/css">

		div.topbar{ /* bar that runs across the top of the menu */
		height: 16px;
		background: #e16031;
		}

		ul.claybricks{ /* main menu UL */
		font-weight: bold;
		width: 100%;
		background: #e3e490;
		padding: 6px 0 6px 0; /* padding of the 4 sides of the menu */
		margin: 0;
		text-align: left; /* set value to "left", "center", or "right" to align menu accordingly */
		}

		ul.claybricks li{
		display: inline;
		}

		ul.claybricks li a{
		color:black;
		padding: 6px 8px 4px 8px; 
		margin-right: 0px; 
		text-decoration: none;
		}

		ul.claybricks li a:hover, ul.claybricks li a.selected{
		color: white;
		background: #5d4137;
		background: -moz-linear-gradient(top, #5d4137 0%, #41251b 12%, #2c0f05 100%); 
		background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#5d4137), color-stop(12%,#41251b), color-stop(100%,#2c0f05));
		background: -webkit-linear-gradient(top, #5d4137 0%,#41251b 12%,#2c0f05 100%); 
		background: -o-linear-gradient(top, #5d4137 0%,#41251b 12%,#2c0f05 100%); 
		background: -ms-linear-gradient(top, #5d4137 0%,#41251b 12%,#2c0f05 100%);
		background: linear-gradient(top, #5d4137 0%,#41251b 12%,#2c0f05 100%);
		filter: progid:DXImageTransform.Microsoft.gradient( startColorstr=\'#5d4137\', endColorstr=\'#2c0f05\',GradientType=0 );
		-moz-box-shadow: 0 0 5px #595959; /* moz syntax for CSS3 box shadows */
		-webkit-box-shadow: 0 0 5px #595959;
		box-shadow: 0 0 5px #595959;
		padding-top: 17px;
		padding-bottom: 6px;
		}

		</style>';
		
		echo '<script type="text/javascript" src="https://www.centili.com/widget/js/c-mobile-payment-scripts.js"></script>';
}

function meni($folder)
{
	$dodatak=array("","","","");
	switch($folder)
	{
		case "glavni":
		$dodatak[0]="";
		$dodatak[1]="";
		$dodatak[2]="";
		$dodatak[3]="";
		break;
		case "profil":
		$dodatak[0]="../";
		$dodatak[1]="";
		$dodatak[2]="../";
		$dodatak[3]="../";
		break;
		case "poruke":
		$dodatak[0]="../";
		$dodatak[1]="../";
		$dodatak[2]="";
		$dodatak[3]="../";
		break;
		case "prodavnica":
		$dodatak[0]="../";
		$dodatak[1]="../";
		$dodatak[2]="../";
		$dodatak[3]="";
		break;
	}
	$dodatak1="";
	$img="";
	if($folder!="glavni")
	$img="../";
	$prod="";
	$prof="";
	$por="";
	if($dodatak[3]!=""||$folder=="glavni")
	$prod="prodavnica/";
	if($dodatak[1]!=""||$folder=="glavni")
	$prof="profil/";
	if($dodatak[2]!=""||$folder=="glavni")
	$por="poruke/";
	if((mysql_num_rows(mysql_query("SELECT * FROM admini WHERE nick='".$_SESSION['userName']."'"))>0)||($_SESSION['userName']=="Admin"))
	$dodatak1="<li><a href='".$dodatak[0]."admin_panel.php'>Admin Panel</a></li>";
	echo "<div class='topbar'></div>
	<ul class='claybricks'>
		<li><a href='".$dodatak[0]."index.php'>Pocetna</a></li>
        <li title='".$_SESSION['userName']."'><a href='".$dodatak[1].$prof."index.php'>Profil</a></li>
        <li title='Obavestenja'><a href='".$dodatak[1].$prof."obavestenja.php'>Obavestenja (".mysql_num_rows(mysql_query("SELECT * FROM notifikacije WHERE nick='".$_SESSION['userName']."' AND pogledano='0'")).")</a></li>
        <li><a href='".$dodatak[2].$por."index.php'>Poruke (".mysql_num_rows(mysql_query("SELECT * FROM MSG_INFO WHERE (AUTOR='".$_SESSION['userName']."' AND AUT_STT='nije_procitano') OR (PRIM='".$_SESSION['userName']."' AND PRIM_STT='nije_procitano')")).")</a></li>
        $dodatak1
        <li title='Nabavite jos tokena'><a id='c-mobile-payment-widget' href='https://www.centili.com/widget/WidgetModule?api=77132b3684b1fb5c7817ac9b7570a02a&clientid=".$_SESSION['userName']."'><img src='".$img."images/tokeni.png' style='vertical-align:middle;' width='20' height='20' /> (".get_token_value($_SESSION['userName']).")</a></li>
        <li><a href='".$dodatak[3].$prod."index.php'>Prodavnica</a></li>
        <li><a href='".$dodatak[3].$prod."kovceg.php'>Kovceg</a></li>
        <li><a href='".$dodatak[0]."logout.php'>Izlaz</a></li>
	</ul>";
}

function getLevel($nick)
{
	$data=mysql_query("SELECT level FROM poeni WHERE nick='$nick'");
	$niz=mysql_fetch_assoc($data);
	return $niz['level'];
}
function kreiraj_notifikaciju($nick,$naslov,$tekst)
{
	mysql_query("INSERT INTO notifikacije(nick,naslov,text) VALUES('$nick','$naslov','$tekst')");
}

function dodaj_status($nick,$text)
{
	$text=mysql_escape_string($text);
	mysql_query("INSERT INTO statusi(nick,text,datum) VALUES('$nick','$text','".date("H:i d/m/Y")."')");
}

function sveOdigrane($nick)
{
	if(mysql_num_rows(mysql_query("SELECT * FROM slagalica_dnevne_liste WHERE nick='$nick' AND datum='".date("d-m-Y")."' AND igrana_slagalica='da' AND igrana_moj_broj='da' AND igrana_spojnice='da' AND igrana_skocko='da' AND igrana_koznazna='da' AND igrana_asocijacije='da'"))==1)
	return true;
	return false;
}

function dodajPonistenu($nick)
{
	mysql_query("UPDATE slagalica_dnevne_liste SET ponistavano=ponistavano+'1' WHERE nick='$nick' AND datum='".date("d-m-Y")."'");
}

function ponisteno($nick)
{
	$data=mysql_query("SELECT ponistavano FROM slagalica_dnevne_liste WHERE nick='$nick' AND datum='".date("d-m-Y")."'");
	$niz=mysql_fetch_assoc($data);
	$br=intval($niz['ponistavano']);
	if($br<2)
	{
		return true;
	}
	return false;
}

function facebookReg($username,$email)
{
	kreiraj_notifikaciju($username,"Uspesno ste registrovani","Dobrodosli, drago nam je sto ste odlucili da nam se pridruzite. Ukoliko Vam je potrebna pomoc posetite nasu stranicu na fejsbuku \"Mikisoft Slagalica\", ili se obratite u inbox, poruku posaljite na nick Admin. Zelimo Vam puno zabave i mnogo znanja!");
	mysql_query("INSERT INTO lista(username, pw, ip,email,conf_code,status,registrovan,tokeni,reputacija,combo,bonus_prihvacen) VALUES('$username', 'fbapp', 'none','$email','none','aktivan','".date("d-m-Y H:i")."','0','0','1','ne')");
}

function facebookLog($username,$email)
{
	$rezultat=mysql_query("SELECT username, datum, combo FROM lista WHERE username='$username'");
	$niz=mysql_fetch_assoc($rezultat);
	$validUser=true;
	$_SESSION['userName'] = $username;
	$_SESSION['validUser'] = true;
	$datum=date("d-m-Y H:i");
	if(substr($datum,0,5)!=substr($niz['datum'],0,5))
	{
		if(substr(izracunaj_datum($datum,-1),0,5)==substr($niz['datum'],0,5))
		{
			$niz['combo']=strval(intval($niz['combo'])+1);
			if($niz['combo']=="6")
			$niz['combo']="1";
			mysql_query("UPDATE lista SET datum='$datum', combo='".$niz['combo']."', bonus_prihvacen='ne' WHERE username='$username'");
		}
		else
		{
			$niz['combo']="1";
			mysql_query("UPDATE lista SET datum='$datum', combo='".$niz['combo']."', bonus_prihvacen='ne' WHERE username='$username'");
		}
	}
	
	if(mysql_num_rows(mysql_query("SELECT * FROM lista WHERE username='$username' AND bonus_prihvacen='ne'"))==1)
	{
			$combo=get_combo($username);
			mysql_query("UPDATE lista SET bonus_prihvacen='da' WHERE username='$username'");
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
			$exp=$exp+floor(intval(get_reputacija($username))/100);
			addExp($username,$exp);
			$temp1=$tok;
			$tok=$tok+intval(getLevel($username));
			add_tokens($username,$tok);
			kreiraj_notifikaciju($username,"Dnevni poklon","Dolazite $combo dana za redom i osvajate $exp iskustva i $tok tokena. Zelimo Vam srecno igranje!");		}
}

function poslednji_ponedeljak()
{
	$danas=intval(date("w"));
	if($danas==0)
	$danas=7;
	$dat=explode(' ',izracunaj_datum(date("d-m-Y H:i"),-($danas-1)));
	return $dat[0];
}

function toEntity($znak)
{
	switch($znak)
	{
		case "1":
		$znak="&#273;";
		break;
		case "2":
		$znak="nj";
		break;
		case "3":
		$znak="lj";
		break;
		case "4":
		$znak="&#353;";
		break;
		case "5":
		$znak="&#263;";
		break;
		case "6":
		$znak="&#269;";
		break;
		case "7":
		$znak="d&#382;";
		break;
		case "8":
		$znak="&#382;";
		break;
		default:
		$znak=$znak;
		break;
	}
	return $znak;
}

function toReal($rec)
{
	$rec=str_replace("1","_dj_",$rec);
	$rec=str_replace("2","_nj_",$rec);
	$rec=str_replace("3","_lj_",$rec);
	$rec=str_replace("4","_sh_",$rec);
	$rec=str_replace("5","_tj_",$rec);
	$rec=str_replace("6","_ch_",$rec);
	$rec=str_replace("7","_dzh_",$rec);
	$rec=str_replace("8","_zh_",$rec);
	
	$rec=str_replace("_dj_","&#273;",$rec);
	$rec=str_replace("_nj_","nj",$rec);
	$rec=str_replace("_lj_","lj",$rec);
	$rec=str_replace("_sh_","&#353;",$rec);
	$rec=str_replace("_tj_","&#263;",$rec);
	$rec=str_replace("_ch_","&#269;",$rec);
	$rec=str_replace("_dzh_","d&#382;",$rec);
	$rec=str_replace("_zh_","&#382;",$rec);
	return $rec;
}

function toReal1($rec)
{
        $rec=str_replace("1","_dj_",$rec);
	$rec=str_replace("2","_nj_",$rec);
	$rec=str_replace("3","_lj_",$rec);
	$rec=str_replace("4","_sh_",$rec);
	$rec=str_replace("5","_tj_",$rec);
	$rec=str_replace("6","_ch_",$rec);
	$rec=str_replace("7","_dzh_",$rec);
	$rec=str_replace("8","_zh_",$rec);
	
	$rec=str_replace("_dj_","&#273;",$rec);
	$rec=str_replace("_nj_","[nj]",$rec);
	$rec=str_replace("_lj_","[lj]",$rec);
	$rec=str_replace("_sh_","&#353;",$rec);
	$rec=str_replace("_tj_","&#263;",$rec);
	$rec=str_replace("_ch_","&#269;",$rec);
	$rec=str_replace("_dzh_","[d&#382;]",$rec);
	$rec=str_replace("_zh_","&#382;",$rec);
	return $rec;
}

function dodajWP($naslov,$text,$kategorija)
{
    require_once("pomoc/wp-config.php");
    $new_post = array(
    'post_title' => $naslov,
    'post_content' => $text,
    'post_status' => 'publish',
    'post_date' => date('Y-m-d H:i:s'),
    'post_author' => 1,
    'post_type' => 'post',
    'post_category' => array($kategorija)
    );
    wp_insert_post($new_post);
}

function getRank($nick,$tip)
{
	$query="";
	switch($tip)
	{
		case 'celokupna':
		$query="SELECT rnk, suma, nick
				FROM (
				SELECT nick, suma, @rank := @rank +1 AS rnk
				FROM (
				SELECT @rank:=0,nick, SUM( slagalica + moj_broj + skocko + spojnice + koznazna + asocijacije ) AS suma
				FROM slagalica_dnevne_liste
				GROUP BY nick
				ORDER BY suma DESC
				) AS tbl1
				) AS tbl2
				WHERE nick ='$nick'";
		break;
		case 'mesecna':
		$query="SELECT rnk, suma, nick
				FROM (
				SELECT nick, suma, @rank := @rank +1 AS rnk
				FROM (
				SELECT @rank:=0,nick, SUM( slagalica + moj_broj + skocko + spojnice + koznazna + asocijacije ) AS suma
				FROM slagalica_dnevne_liste
				WHERE datum LIKE '__-".date("m")."-".date("Y")."'
				GROUP BY nick
				ORDER BY suma DESC
				) AS tbl1
				) AS tbl2
				WHERE nick ='$nick'";
		break;
		case 'nedeljna':
		$danas=intval(date("w"));
		if($danas==0)
		$danas=7;
		$datumi=array();
		$ponedeljak=poslednji_ponedeljak();
		for($i=1;$i<=$danas;$i++)
		{
			$tmp=explode(' ',izracunaj_datum($ponedeljak." 01:01",$i-1));
			array_push($datumi,"datum='".$tmp[0]."'");
		}
		$qu=join(" OR ",$datumi);
		$query="SELECT rnk, suma, nick
				FROM (
				SELECT nick, suma, @rank := @rank +1 AS rnk
				FROM (
				SELECT @rank:=0,nick, SUM( slagalica + moj_broj + skocko + spojnice + koznazna + asocijacije ) AS suma
				FROM slagalica_dnevne_liste
				WHERE $qu
				GROUP BY nick
				ORDER BY suma DESC
				) AS tbl1
				) AS tbl2
				WHERE nick ='$nick'";
		break;
		case 'dnevna':
		$query="SELECT rnk, suma, nick
				FROM (
				SELECT nick, suma, @rank := @rank +1 AS rnk
				FROM (
				SELECT @rank:=0,nick, SUM( slagalica + moj_broj + skocko + spojnice + koznazna + asocijacije ) AS suma
				FROM slagalica_dnevne_liste
				WHERE datum='".date("d-m-Y")."'
				GROUP BY nick
				ORDER BY suma DESC
				) AS tbl1
				) AS tbl2
				WHERE nick ='$nick'";
		break;
	}
	$data=mysql_query($query);
	$obj=mysql_fetch_assoc($data);
	if(!isset($obj['rnk']))
	$obj['rnk']='/';
	if(!isset($obj['suma']))
	$obj['suma']='/';
	return array($obj['rnk'],$obj['suma']);
}

function getGameStatus($nick,$datum)
{
	$data=mysql_query("SELECT * FROM slagalica_dnevne_liste WHERE nick='$nick' AND datum='$datum'");
	$status=mysql_num_rows($data);
	$niz=mysql_fetch_assoc($data);
	$komp=explode('-',$datum);
	$danas=intval(date("d"));
	if($status==0)
	{
		if(intval($komp[0])==$danas)
		$niz['status']='danasnja_igra_0';
		if(intval($komp[0])<$danas)
		$niz['status']='zakljucano_0';
		if(intval($komp[0])>$danas)
		$niz['status']='blokirano_0';
	}
	else
	{
		if(intval($komp[0])==$danas)
		$niz['status']='danasnja_igra_1';
		if(intval($komp[0])<$danas)
		{
			if($niz['zakljucano']=="1")
			{
				$niz['status']='zakljucano_1';
			}
			else
			{
				$niz['status']='otkljucano_1';
			}
		}
	}
	return $niz;
}

function kljuc_func($nick,$vrednost,$tip)//0 dodavanje,1 izmena, 2 oduzimanje
{
	switch ($tip)
	{
		case 0:
		mysql_query("UPDATE lista SET kljuc=kljuc+'$vrednost' WHERE username='$nick'");
		break;
		case 1:
		mysql_query("UPDATE lista SET kljuc='$vrednost' WHERE username='$nick'");
		break;
		case 2:
		mysql_query("UPDATE lista SET kljuc=kljuc-'$vrednost' WHERE username='$nick'");
		break;
	}
}

function getKljuc($nick)
{
	$data=mysql_query("SELECT kljuc FROM lista WHERE username='$nick'");
	$niz=mysql_fetch_assoc($data);
	return intval($niz['kljuc']);
}

function otkljucajDnevnuPartiju($nick,$datum)//1 nije moguce restartovati,2 uspesno,3 nema kljuceva,4 nije tacan mesec
{
	$data=mysql_query("SELECT * FROM slagalica_dnevne_liste WHERE nick='$nick' AND datum='$datum' AND zakljucano='1'");
	$br=mysql_num_rows($data);
	if($br==0)
	{
		$komp=explode('-',$datum);
		if(($komp[1]."-".$komp[2])==date("m-Y"))
		{
			if(intval(date("d"))<=intval($komp[0]))
			{
				return 1;
			}
			else
			{
				if(getKljuc($nick)>=1)
				{
					kljuc_func($nick,1,2);
					mysql_query("INSERT INTO slagalica_dnevne_liste(igrana_slagalica,igrana_moj_broj,igrana_spojnice,igrana_skocko,igrana_koznazna,igrana_asocijacije,slagalica,moj_broj,spojnice,skocko,koznazna,asocijacije,datum,nick,share,ponistavano,zakljucano) VALUES('ne','ne','ne','ne','ne','ne','0','0','0','0','0','0','$datum','$nick','ne','0','0')");
					return 2;
				}
				else
				{
					return 3;
				}
			}
		}
		else
		{
			return 4;
		}
	}
	else
	{
		$komp=explode('-',$datum);
		if(($komp[1]."-".$komp[2])==date("m-Y"))
		{
			if(getKljuc($nick)>=1)
			{
				if(mysql_num_rows(mysql_query("SELECT * FROM slagalica_dnevne_liste WHERE nick='$nick' AND datum='$datum' AND igrana_slagalica='ne' AND igrana_moj_broj='ne' AND igrana_spojnice='ne' AND igrana_skocko='ne' AND igrana_koznazna='ne' AND igrana_asocijacije='ne'"))==1)
				{
					kljuc_func($nick,1,2);
					mysql_query("UPDATE slagalica_dnevne_liste SET zakljucano='0' WHERE $nick='$nick' AND datum='$datum'");
					return 2;
				}
				else
				{
					return 4;
				}
			}
			else
			{
				return 3;
			}
		}
		else
		{
			return 4;
		}
	}
}

function dozvoljenoIgranje($nick,$datum)
{
	if($datum==date("d-m-Y"))
	return true;
	else
	{
		$komp=explode('-',$datum);
		if(($komp[1]."-".$komp[2])==date("m-Y"))
		{
			if(mysql_num_rows(mysql_query("SELECT * FROM slagalica_dnevne_liste WHERE nick='$nick' AND datum='$datum' AND zakljucano='0'"))==1)
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		else
		return false;
	}
}

function kript($text,$kljuc)
{
	$kriptovano=$kljuc[0];
	$pozicija_kljuca=0;
	$duzina_kljuca=strlen($kljuc);
	$duzina=strlen($text);
	for($i=0;$i<$duzina;$i++)
	{
		if($pozicija_kljuca==$duzina_kljuca)
		$pozicija_kljuca=0;
		$zbir=ord($text[$i])+intval($kljuc[$pozicija_kljuca]);
		if($zbir>122)
		$zbir-=77;
		$kriptovano.=chr($zbir);
		++$pozicija_kljuca;
	}
	$kriptovano.=$kljuc[$duzina_kljuca-1];
	
	return htmlentities($kriptovano);
}

function dekript($text,$kljuc)
{
	$dekriptovano="";
	$text=html_entity_decode($text);
	$pozicija_kljuca=0;
	$duzina_kljuca=strlen($kljuc);
	$duzina=strlen($text);
	if(($text[0]!=$kljuc[0])||($text[$duzina-1]!=$kljuc[$duzina_kljuca-1]))
	return "";
	$text=substr($text,1,$duzina-2);
	$duzina-=2;
	for($i=0;$i<$duzina;$i++)
	{
		if($pozicija_kljuca==$duzina_kljuca)
		$pozicija_kljuca=0;
		$zbir=ord($text[$i])-intval($kljuc[$pozicija_kljuca]);
		if($zbir<46)
		$zbir+=77;
		$dekriptovano.=chr($zbir);
		++$pozicija_kljuca;
	}
	return $dekriptovano;
}

function dozvoliTransakciju($tid)
{
        if(mysql_num_rows(mysql_query("SELECT id FROM transakcije WHERE tid='$tid'"))==0)
        return true;
        return false;
}
?>