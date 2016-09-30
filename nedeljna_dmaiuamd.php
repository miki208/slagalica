<?php 
include "connect.php";
$ponedeljak=izracunaj_datum(date("d-m-Y H:i"),-7);
				$tmp1=explode(' ',$ponedeljak);
				$ponedeljak=$tmp1[0];
                                $datumi=array();
				for($i=1;$i<=7;$i++)
				{
					$tmp=explode(' ',izracunaj_datum($ponedeljak." 01:01",$i-1));
					array_push($datumi,"datum='".$tmp[0]."'");
				}
                                $dtm1=str_replace("datum='","",$datumi[0]);
                                $dtm1=str_replace("'","",$dtm1);
                                $dtm2=str_replace("datum='","",$datumi[6]);
                                $dtm2=str_replace("'","",$dtm2);
                                $dtm1=str_replace("-","/",$dtm1);
                                $dtm2=str_replace("-","/",$dtm2);
                                $wptext="Tri dobitnika za nedelju (".$dtm1."-".$dtm2.")";
				$query=join(" OR ",$datumi);
				$nedeljna=mysql_query("SELECT SUM(slagalica+moj_broj+spojnice+skocko+koznazna+asocijacije) AS suma,nick FROM slagalica_dnevne_liste WHERE $query GROUP BY nick ORDER BY suma DESC LIMIT 3");
				$i=1;
				while($nedeljna_data=mysql_fetch_assoc($nedeljna))
				{
					$exp=0;
					$tokeni=0;
					$pi=0;
					switch($i)
					{
						case 1;
						$tokeni=150;
						$exp=200;
						$pi=3;
						dodaj_status($nedeljna_data['nick'],"Prosle nedelje sam bio prvi na rang listi, nema dileme, najbolji sam!");
						break;
						case 2;
						$tokeni=100;
						$exp=120;
						$pi=2;
						break;
						case 3;
						$tokeni=70;
						$exp=80;
						$pi=1;
						break;
					}
                                        $wptext.="</br>$i. <b>".$nedeljna_data['nick']."</b> (<span style='color: #ff0000;'>$tokeni</span> tokena, <span style='color: #ff0000;'>$exp</span> exp, <span style='color: #ff0000;'>$pi</span> PI)";
					addExp($nedeljna_data['nick'],$exp);
					add_tokens($nedeljna_data['nick'],$tokeni);
					add_ponistavanje_igre($nedeljna_data['nick'],$pi);
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
					kreiraj_notifikaciju($nedeljna_data['nick'],"Nedeljna rang lista - Poklon","Bili ste ".$i.". na nedeljnoj tabeli i osvojili ste ".$query.".");
					++$i;
				}
                                $wptext.="</br>Igrajte i vi, pokazite vase znanje!";
                                dodajWP("Nedeljne nagrade (".$dtm1."-".$dtm2.")",$wptext,8);
?>