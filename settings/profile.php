<?php
	session_start();
	if (isset($_SESSION["userwtf"])){
		require '../assets/variables/sql.php';
		if(isset($_POST["fulname"])){
			query("update user set name='".$_POST["fulname"]."' where userID=".$_SESSION["userwtf"].";");
		}
		if(isset($_POST["displayname"])){
			query("update user set display='".$_POST["displayname"]."' where userID=".$_SESSION["userwtf"].";");
		}
		if(isset($_POST["email"])){
			query("update user set display='".$_POST["email"]."' where userID=".$_SESSION["userwtf"].";");
		}
		if(isset($_FILES["avt"]["tmp_name"])){
			//query("update user set display='".$_POST["displayname"]."' where userID=".$_SESSION["userwtf"].";");
		}
		header("Location: ./");
	}
?>