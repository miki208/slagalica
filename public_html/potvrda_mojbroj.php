<?php
	include "connect.php";
	include('evalmath.class.php');
	if(isset($_POST['nick']))
	{	
		$nick=$_POST['nick'];
		addExp($nick,5+floor(intval(get_reputacija($nick))/100));
		$d=$_POST['izraz'];
		$data=mysql_query("SELECT * FROM slagalica_sesije WHERE nick='$nick' AND igra='moj_broj'");
		$res=mysql_fetch_assoc($data);
		$pod=$res['rec'];
		$brojevi_str_srv=explode('|',$pod);
		$data_user=explode('|',$d);
		$izraz=$data_user[0];
		$izraz=str_replace("p","+",$izraz);
		$brojevi_user=explode('*',$data_user[1]);
		if(($brojevi_user[0]==$brojevi_str_srv[1])&&($brojevi_user[1]==$brojevi_str_srv[2])&&($brojevi_user[2]==$brojevi_str_srv[3])&&($brojevi_user[3]==$brojevi_str_srv[4])&&($brojevi_user[4]==$brojevi_str_srv[5])&&($brojevi_user[5]==$brojevi_str_srv[6]))
		{
			$br=0;
			$m = new EvalMath;
			$m->suppress_errors = true;
			if($m->evaluate($izraz))
			{
				$br=$m->evaluate($izraz);
				$trazeni=intval($brojevi_str_srv[0]);
				$bodovi=0;
				if(abs($trazeni-$br)==0)
				{
					$bodovi=30;
					addExp($nick,20+floor(intval(get_reputacija($nick))/100));
				}
				else
				{
					if(abs($trazeni-$br)==1)
					{
						$bodovi=20;
						addExp($nick,15+floor(intval(get_reputacija($nick))/100));
					}
					else
					{
						if(abs($trazeni-$br)<=5)
						{
							$bodovi=15;
							addExp($nick,10+floor(intval(get_reputacija($nick))/100));
						}
						else
						{
							if(abs($trazeni-$br)<=10)
							{
								$bodovi=5;
								addExp($nick,5+floor(intval(get_reputacija($nick))/100));
							}
						}
					}
				}
				$mikisoftr=mysql_query("SELECT * FROM slagalica_rank WHERE nick='$nick'");
				$mikisoftd=mysql_fetch_assoc($mikisoftr);
				mysql_query("UPDATE slagalica_rank SET bodovi='".strval($bodovi+intval($mikisoftd['bodovi']))."' WHERE nick='$nick'");
				/////////////////////////DNEVNA LISTA////////////////////////
				$datum=(isset($_POST['datum']))?$_POST['datum']:date("d-m-Y");
				mysql_query("UPDATE slagalica_dnevne_liste SET moj_broj='$bodovi' WHERE nick='$nick' AND datum='$datum'");
				/////////////////////////DNEVNA LISTA KRAJ////////////////////////
				echo strval($br)."*".strval($bodovi);
			}
		}
		else
		echo "error";
		mysql_query("DELETE FROM slagalica_sesije WHERE id='".$res['id']."' AND igra='moj_broj'");	
	}
?>