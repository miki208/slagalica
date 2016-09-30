<?php
	include "connect.php";
	if(isset($_POST['nick']))
	{
		$nick=$_POST['nick'];
		$data=mysql_query("SELECT * FROM slagalica_sesije WHERE nick='$nick' AND igra='skocko'");
		$res=mysql_fetch_assoc($data);
		if(mysql_num_rows($data)==1)
		echo $res['rec'];
		mysql_query("DELETE FROM slagalica_sesije WHERE id='".$res['id']."' AND igra='skocko'");
	}
?>