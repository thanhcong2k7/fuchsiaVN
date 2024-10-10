<?php
	//include 'tp/db.php
  echo "<script>alert('test done');</script>";
  $conn = mysqli_connect("localhost","lunaris_real","1993Ak1993@","lunaris_real");
	session_start();
	if(isset($_GET["id"])){
		$res=$conn->query("DELETE FROM author WHERE authorID=".$_GET["id"].";");
		echo "<script>alert('ok1');</script>";
	} else if (isset($_POST["btnSubmit"])){
		$res=$conn->query("SELECT authorID FROM author WHERE userID='".$_SESSION["userwtf"]."';");
		while($row=$res->fetch_assoc()) $curID=$row["authorID"];
		$res=$conn->query("INSERT INTO author (authorID,authorName,spotifyID,amID,email,userID) VALUES (".strval($curID+1).",'".$_POST["name"]."',".$_POST["sID"].",".$_POST["amID"].",".$_POST["email"].",".strval($_SESSION["userwtf"]).")");
		echo "<script>alert('ok');</script>";
	}
    header("Location: ./artist.php");
?>