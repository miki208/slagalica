<?php
	include "connect.php";
	if(isset($_POST['id']))
	{
		$id=mysql_escape_string($_POST['id']);
		mysql_query("UPDATE baneri SET posete=posete+'1' WHERE lnk='$id'");
	}
?>