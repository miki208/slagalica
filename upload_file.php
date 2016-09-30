<?php
require_once('connect.php');
checkUser();
if(!checkPerm($_SESSION['userName'],"baner_add"))
	header("Location: index.php");
else
{
	if ((($_FILES["file"]["type"] == "image/gif")
	|| ($_FILES["file"]["type"] == "image/jpeg")
	|| ($_FILES["file"]["type"] == "image/pjpeg")
	|| ($_FILES["file"]["type"] == "image/png"))
	&& ($_FILES["file"]["size"] < 2000000))
	{
	  if ($_FILES["file"]["error"] > 0)
      {
		header("Location: dodaj_baner.php");
      }
      else
      {
		$ext=split('/',$_FILES["file"]["type"]);
		$id=mysql_query("SELECT id FROM baneri ORDER BY id DESC LIMIT 1");
		$id1=mysql_fetch_assoc($id);
		if(mysql_num_rows($id)==0)
		$id1['id']="0";
		$id1['id']=strval(intval($id1['id']+1));
		$naziv=mysql_escape_string($id1['id'].".".$ext[1]);
		$title=mysql_escape_string($_POST['title']);
		$adresa=mysql_escape_string($_POST['adresa']);
		mysql_query("INSERT INTO baneri(lnk, adresa, title, status) VALUES('$naziv','$adresa','$title','aktivan')");
    	if (file_exists("baneri/" . $_FILES["file"]["name"]))
      	{
        	unlink("baneri/" . $id1['id'].".".$ext[1]);
			move_uploaded_file($_FILES["file"]["tmp_name"],"baneri/" . $id1['id'].".".$ext[1]);
	  		header("Location: dodaj_baner.php");
      	}
    	else
        {
        	move_uploaded_file($_FILES["file"]["tmp_name"],"baneri/" . $id1['id'].".".$ext[1]);
	  		header("Location: dodaj_baner.php");
      	}
      }
  	}
	else
  	{
	  header("Location: dodaj_baner.php");
  	}
	header("Location: dodaj_baner.php");
}

?>