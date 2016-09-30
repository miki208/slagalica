<? include "connect.php";
$pm=izracunaj_datum(date("d-m-Y H:i"),-1);
				$tmp1=explode(' ',$pm);
				$pm=$tmp1[0];
				$tmp1=explode('-',$pm);
				$pm="__-".$tmp1[1]."-".$tmp1[2];
                                $wptext="Tri dobitnika za mesec (".$tmp1[1]."/".$tmp1[2].")";
				$mesec=mysql_query("SELECT nick,SUM(slagalica+moj_broj+spojnice+skocko+koznazna+asocijacije) AS suma FROM slagalica_dnevne_liste WHERE datum LIKE '".$pm."' GROUP BY nick ORDER BY suma DESC LIMIT 3");
				$i=1;
				while($mesec_data=mysql_fetch_assoc($mesec))
				{
					$exp=0;
					$tokeni=0;
					$pi=0;
					switch($i)
					{
						case 1;
						$tokeni=450;
						$exp=700;
						$pi=6;
						dodaj_status($mesec_data['nick'],"Proslog meseca sam bio prvi, odlucio sam, prijavljujem se za pravu TV slagalicu!");
						break;
						case 2;
						$tokeni=360;
						$exp=500;
						$pi=4;
						break;
						case 3;
						$tokeni=220;
						$exp=350;
						$pi=2;
						break;
					}
					addExp($mesec_data['nick'],$exp);
					add_tokens($mesec_data['nick'],$tokeni);
					add_ponistavanje_igre($mesec_data['nick'],$pi);
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
					kreiraj_notifikaciju($mesec_data['nick'],"Mesecna rang lista - Poklon","Bili ste ".$i.". na mesecnoj tabeli i osvojili ste ".$query.".");
                                        $wptext.="</br>$i. <b>".$mesec_data['nick']."</b> (<span style='color: #ff0000;'>$tokeni</span> tokena, <span style='color: #ff0000;'>$exp</span> exp, <span style='color: #ff0000;'>$pi</span> PI)";
					++$i;
				}
                         $wptext.="</br>Igrajte i vi, pokazite vase znanje!";
                         dodajWP("Mesecne nagrade (".$tmp1[1]."/".$tmp1[2].")",$wptext,9);
?>