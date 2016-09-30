<?php
	include "connect.php";
                        /********BRISANJE NALOGA*****/
                        obrisi_nepotvrdjene_naloge();
                        /****************************/
						/********DNEVNE NAGRADE******/
			 $datum=explode(' ',izracunaj_datum(date("d-m-Y H:i"),-1));
			$data=mysql_query("SELECT nick FROM slagalica_dnevne_liste WHERE datum='".$datum[0]."' ORDER BY (slagalica+moj_broj+skocko+spojnice+koznazna+asocijacije) DESC LIMIT 3");
			$i=1;
                        $wptext="Tri dobitnika za dan ".$datum[0]." su:";
			while($niz=mysql_fetch_assoc($data))
			{
				$exp=0;
				$tokeni=0;
				$pi=0;
				switch($i)
				{
					case 1:
					$tokeni=80;
					$exp=100;
					$pi=1;
					dodaj_status($niz['nick'],"Juce sam bio prvi na rang listi, ima li ko bolji?");
					break;
					case 2:
					$tokeni=40;
					$exp=50;
					$pi=0;
					break;
					case 3:
					$tokeni=20;
					$exp=30;
					$pi=0;
					break;
				}
                                $wptext.="</br>$i. <b>".$niz['nick']."</b> (<span style='color: #ff0000;'>$tokeni</span> tokena, <span style='color: #ff0000;'>$exp</span> exp, <span style='color: #ff0000;'>$pi</span> PI)";
				addExp($niz['nick'],$exp);
				add_tokens($niz['nick'],$tokeni);
				add_ponistavanje_igre($niz['nick'],$pi);
				$query="";
				if($pi>0)
				{
					if($query!="")
					$query.=", ";
					$query.="$pi PI";
				}
				if($exp>0)
				{
					if($query!="")
					$query.=", ";
					$query.="$exp iskustvenih poena";
				}
				if($tokeni>0)
				{
					if($query!="")
					$query.=", ";
					$query.="$tokeni tokena";
				}
				if($query!="")
				{
					kreiraj_notifikaciju($niz['nick'],"Dnevna rang lista - Poklon","Bili ste ".$i.". na dnevnoj tabeli dana ".$datum[0]." i osvojili ste ".$query.".");
				}
				++$i;
			}
                        $wptext.="</br>Igrajte i vi, pokazite vase znanje!";
                        dodajWP("Dnevne nagrade (".$datum[0].")",$wptext,7);
			/****************************/

?>