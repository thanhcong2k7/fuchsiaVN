<?php
	session_start();
	function generateRandomString($n = 0) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < ($n!=0?$n:random_int(5,25)); $i++) {
			$randomString .= $characters[random_int(0, $charactersLength - 1)];
		}
		return $randomString;
	}
	if(isset($_POST["resetpwd"]) && isset($_SESSION["resetpwd"])){
		unset($_SESSION["resetpwd"]);
		require "assets/variables/sql.php";
		query("delete from resetpwd where code=".$_SESSION["resetpwd"].";");
		$new_md5pwd = md5($_POST["resetpwd"]);
		update("pwd",$new_md5pwd,"user","email='".$_SESSION["resetemail"]."'");
		//auth
		$key=generateRandomString();
		$iv=generateRandomString(17);
		setcookie("saveses",openssl_encrypt($key,"AES-128-CTR",$new_md5pwd,0,$iv), time()+(86400*30), "/");
		query("delete from sessions where ip='".$_SERVER["REMOTE_ADDR"]."';");
		query("insert into sessions (secret,userID,ip,timeAdded,iv) values ('".$key."',".getID($_SESSION["resetemail"]).",'".$_SERVER['REMOTE_ADDR']."',NOW(),'".$iv."');");
		header("Location: index.php");
	} else if (isset($_GET["logout"]) && isset($_SESSION["userwtf"])){
		unset($_SESSION["userwtf"]);
		setcookie("saveses","", time()-3600, "/");
		header("Location: index.php");
	} else{
		//$_SESSION["userkey"]=0;
        //header("Location: ../");
	    //echo "<script>alert('ok');</script>";
	    //$_SESSION["userwtf"]=5;
		$conn = mysqli_connect("127.0.0.1", "wtjmdnac_fuchsia", "nguyenthanhcong", "wtjmdnac_fuchsia");
		if (!$conn){
			$_SESSION["saipass"]="Connection failed! " . mysqli_connect_error();
			header("Location: ./");
		}
	    $usr=$_POST["userslot"];
	    $pwd=md5($_POST["pwdslot"]);
	    $cnt=0;
	    $res=$conn->query("SELECT userID,pwd FROM user WHERE username='".$usr."' OR email='".$usr."';");
	    while($row=$res->fetch_assoc()){
			if($pwd==$row["pwd"]){
				$cnt++;
				$id=$row["userID"];
				break;
			}
	    }
	    if ($cnt!=0){
			$_SESSION["userwtf"]=$id;
			if(isset($_POST["remember"])){
				$key=generateRandomString();
				$iv=generateRandomString(17);
				setcookie("saveses",openssl_encrypt($key,"AES-128-CTR",$pwd,0,$iv), time()+(86400*30), "/");
				$conn->query("delete from sessions where ip='".$_SERVER["REMOTE_ADDR"]."';");
				$conn->query("insert into sessions (secret,userID,ip,timeAdded,iv) values ('".$key."',".$id.",'".$_SERVER['REMOTE_ADDR']."',NOW(),'".$iv."');");
			}
			header("Location: index.php");
	    } else {
			$_SESSION["saipass"]="Wrong password/username! Please try again.";
			header("Location: index.php");
	    }
	}
?>
