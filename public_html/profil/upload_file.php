<?php
require_once('../connect.php');
include("../resize-class.php");

checkUser();
if(!isset($_SESSION['userName']))
	header("Location: ../login.php");
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
		header("Location: index.php");
      }
      else
      {
		$ext=split('/',$_FILES["file"]["type"]);
		$nick=$_SESSION['userName'];
		$lista_data=mysql_query("SELECT ID FROM lista WHERE username='$nick'");
		$lista_niz=mysql_fetch_assoc($lista_data);
		$id=$lista_niz['ID'];
		mysql_query("UPDATE lista SET profilna='".$id.".".$ext[1]."' WHERE ID='$id'");
    	if (file_exists("../profilne/" . $_FILES["file"]["name"]))
      	{
        	unlink("../profilne/" . $id.".".$ext[1]);
			move_uploaded_file($_FILES["file"]["tmp_name"],"../profilne/" . $id.".".$ext[1]);
			$resizeObj = new resize('../profilne/'.$id.".".$ext[1]);
			$resizeObj -> resizeImage(150, 150,'exact');
			unlink("../profilne/" . $id.".".$ext[1]);
			$resizeObj -> saveImage('../profilne/'.$id.".".$ext[1], 100);
	  		header("Location: index.php");
      	}
    	else
        {
        	move_uploaded_file($_FILES["file"]["tmp_name"],"../profilne/" . $id.".".$ext[1]);
        	$resizeObj = new resize('../profilne/'.$id.".".$ext[1]);
		$resizeObj -> resizeImage(150, 150,'exact');
		unlink("../profilne/" . $id.".".$ext[1]);
		$resizeObj -> saveImage('../profilne/'.$id.".".$ext[1], 100);
	  	header("Location: index.php");
      	}
      }
  	}
	else
  	{
	  header("Location: index.php");
  	}
	header("Location: index.php");
}

?>