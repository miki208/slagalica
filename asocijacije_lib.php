<?php
	include "connect.php";
	
	function parsiraj_tag($str,$tag,$tag_close)
	{
		$lista=array();
		$poc=0;
		$kraj=0;
		while(strpos($str,$tag,$poc)!==FALSE)
		{
			$poc=strpos($str,$tag,$poc);
			$kraj=strpos($str,$tag_close,$poc);
			array_push($lista,trim(substr($str,$poc+strlen($tag),$kraj-($poc+strlen($tag)))));
			$poc=$kraj+strlen($tag_close);
		}
		return $lista;
	}

	
	function pronadji($str)
	{
		$query=mysql_query("SELECT id FROM asocijacije_new WHERE pojam='$str'");
		if(mysql_num_rows($query)==1)
		{
			$data=mysql_fetch_assoc($query);
			return $data['id'];
		}
		return "-1";
	}
	
	function proveri_vezu($objekat,$odrediste,$tipveze)
	{
		if(mysql_num_rows(mysql_query("SELECT * FROM asocijacija_veze WHERE objekat='$objekat' AND odrediste='$odrediste' AND tip_veze='$tipveze'"))==0)
		return true;
		return false;
	}
	
	function parsiraj_fajl($fajl)
	{
		if(file_exists($fajl))
		{
			$fp=fopen($fajl,"r");
			$struktura=array();
			$kolone=array();
			$polja=array();
			$kon_res=array();
			$res_kol=array();
			$buffer=fread($fp,filesize($fajl));
			fclose($fp);
			$struktura=parsiraj_tag($buffer,"<asocijacija>","</asocijacija>");
			foreach($struktura as $asoc)
			{
				$kolone=parsiraj_tag($asoc,"<kolona>","</kolona>");
				$kon_res=parsiraj_tag($asoc,"<kon_resenje>","</kon_resenje>");
				foreach($kolone as $kol)
				{
					$polja=parsiraj_tag($kol,"<polje>","</polje>");
					$res_kol=parsiraj_tag($kol,"<resenje>","</resenje>");
					if(count($kolone)<4||count($polja)<4||count($res_kol)<1||count($kon_res)<1)
					{
						return false;
					}
					foreach($kon_res as $kr)
					{
						$pos=pronadji($kr);
						if($pos=="-1")
						{
							mysql_query("INSERT INTO asocijacije_new(pojam) VALUES('$kr')");
							$pos=pronadji($kr);
						}
						foreach($res_kol as $rk)
						{
							$posk=pronadji($rk);
							if($posk=="-1")
							{
								mysql_query("INSERT INTO asocijacije_new(pojam) VALUES('$rk')");
								$posk=pronadji($rk);
							}
							if(proveri_vezu($pos,$posk,"gr-rk"))
							{
								$sin='0';
								if($rk!=$res_kol[0])
								$sin='1';
								mysql_query("INSERT INTO asocijacija_veze(objekat,odrediste,tip_veze,sinonim) VALUES('$pos','$posk','gr-rk','$sin')");
							}
						}
					}
					foreach($res_kol as $rk)
					{
						$posk=pronadji($rk);
						if($posk=="-1")
						{
							mysql_query("INSERT INTO asocijacije_new(pojam) VALUES('$rk')");
							$posk=pronadji($rk);
						}
						foreach($polja as $polje)
						{
							$posp=pronadji($polje);
							if($posp=="-1")
							{
								mysql_query("INSERT INTO asocijacije_new(pojam) VALUES('$polje')");
								$posp=pronadji($polje);
							}
							if(proveri_vezu($posk,$posp,"rk-po"))
							{
								$sin='0';
								if($rk!=$res_kol[0])
								$sin='1';
								mysql_query("INSERT INTO asocijacija_veze(objekat,odrediste,tip_veze,sinonim) VALUES('$posk','$posp','rk-po','$sin')");
							}
						}
					}
				}
			}
			return true;
		}
		else
		return false;
	}
	
	function pojam($id)
	{
		$query=mysql_query("SELECT pojam FROM asocijacije_new WHERE id='$id'");
		$niz=mysql_fetch_assoc($query);
		return $niz['pojam'];
	}
	
	function proveri_resenje($p1,$p2,$p3,$p4,$odg,$veza)
	{
		$p1vr=array();
		$p2vr=array();
		$p3vr=array();
		$p4vr=array();
		$odgid=pronadji($odg);
		if($odgid=="-1")
		return false;
		$p1id=pronadji($p1);
		$p2id=pronadji($p2);
		$p3id=pronadji($p3);
		$p4id=pronadji($p4);
		$query=mysql_query("SELECT objekat FROM asocijacija_veze WHERE odrediste='$p1id' AND tip_veze='$veza'");
		while($niz=mysql_fetch_assoc($query))
		{
			array_push($p1vr,$niz['objekat']);
		}
		$query=mysql_query("SELECT objekat FROM asocijacija_veze WHERE odrediste='$p2id' AND tip_veze='$veza'");
		while($niz=mysql_fetch_assoc($query))
		{
			array_push($p2vr,$niz['objekat']);
		}
		$query=mysql_query("SELECT objekat FROM asocijacija_veze WHERE odrediste='$p3id' AND tip_veze='$veza'");
		while($niz=mysql_fetch_assoc($query))
		{
			array_push($p3vr,$niz['objekat']);
		}
		$query=mysql_query("SELECT objekat FROM asocijacija_veze WHERE odrediste='$p4id' AND tip_veze='$veza'");
		while($niz=mysql_fetch_assoc($query))
		{
			array_push($p4vr,$niz['objekat']);
		}
		$resid=array_intersect($p1vr,$p2vr,$p3vr,$p4vr);
		foreach($resid as $res)
		if($res==$odgid)
		return true;
		return false;
	}
	
	function broj_objekata($tip_veze,$uslov)
	{
		$query=mysql_query("SELECT COUNT(id) AS broj FROM asocijacija_veze WHERE tip_veze='$tip_veze' $uslov");
		$niz=mysql_fetch_assoc($query);
		return intval($niz['broj']);
	}
	
	function sastavi_asocijaciju()
	{
		$asocijacija=array(array(),array(),array(array(),array(),array(),array()));//resenje, kolone i polja
		$query=mysql_query("SELECT COUNT(id) AS broj_resenja FROM asocijacija_veze WHERE tip_veze='gr-rk'");
		$niz=mysql_fetch_assoc($query);
		$id=rand(0,intval($niz['broj_resenja'])-1);
		$query=mysql_query("SELECT objekat FROM asocijacija_veze WHERE tip_veze='gr-rk' LIMIT $id,1");
		unset($niz);
		$niz=mysql_fetch_assoc($query);
		$id=intval($niz['objekat']);
		array_push($asocijacija[0],$id);
		$uslov="";
		for($i=0;$i<4;$i++)
		{
			$id=rand(0,broj_objekata('gr-rk',$uslov." AND objekat='".$asocijacija[0][0]."' AND sinonim='0'")-1);
			$query=mysql_query("SELECT odrediste FROM asocijacija_veze WHERE objekat='".$asocijacija[0][0]."' AND tip_veze='gr-rk' AND sinonim='0' $uslov LIMIT $id,1");
			$niz=mysql_fetch_assoc($query);
			$id=intval($niz['odrediste']);
			$uslov.="AND odrediste!='$id' ";
			array_push($asocijacija[1],$id);
		}
		$uslov="";
		for($x=0;$x<4;$x++)
		{	
			$uslov="";
			for($i=0;$i<4;$i++)
			{
				$id=rand(0,broj_objekata('rk-po',$uslov." AND objekat='".$asocijacija[1][$x]."'")-1);
				$query=mysql_query("SELECT odrediste FROM asocijacija_veze WHERE objekat='".$asocijacija[1][$x]."' AND tip_veze='rk-po' $uslov LIMIT $id,1");
				$niz=mysql_fetch_assoc($query);
				$id=intval($niz['odrediste']);
				$uslov.="AND odrediste!='$id' ";
				array_push($asocijacija[2][$x],$id);
			}
		}
		return $asocijacija;
	}
	
?>