<?php
	session_start();
	echo "<script>alert('ok');</script>";
	//$_SESSION["userwtf"]=5;
	$conn = mysqli_connect("localhost", "lunaris_real", "1993Ak1993@", "lunaris_real");
	$usr=$_POST["userslot"];
	$pwd=md5($_POST["pwdslot"]);
	$cnt=0;
	$res=$conn->query("SELECT userID,pwd FROM user WHERE username='".$usr."' OR email='".$usr."';");
	while($row=$res->fetch_assoc()){
	    if($pwd==$row["pwd"]){
	        $cnt++;
	        $id=$row["userID"];
	    } else $tmppwd=md5($row["pwd"]);
	}
	if ($cnt!=0){
	    $_SESSION["userwtf"]=$id;
	    header("Location: ./");
	}
	else {
	    $_SESSION["saipass"]=$pwd." hehe ".$tmppwd;
	    header("Location: ./login/");
	}
?>