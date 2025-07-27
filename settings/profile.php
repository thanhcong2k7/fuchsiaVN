<?php
	session_start();
	if (isset($_SESSION["userwtf"])){
		require '../assets/variables/sql.php';
		if(isset($_POST["fulname"])){
			query("update user set name='".$_POST["fullname"]."' where userID=".$_SESSION["userwtf"].";");
		}
		if(isset($_POST["displayname"])){
			query("update user set display='".$_POST["displayname"]."' where userID=".$_SESSION["userwtf"].";");
		}
		if(isset($_POST["email"])){
			query("update user set email='".$_POST["email"]."' where userID=".$_SESSION["userwtf"].";");
		}
		if(isset($_POST["paypal"])){
			query("update user set paypal='".$_POST["paypal"]."' where userID=".$_SESSION["userwtf"].";");
		}
		if(isset($_POST["pwd"])){
			update("pwd",md5($_POST["pwd"]),"user","userID=".$_SESSION["userwtf"]);
		}
		if(isset($_FILES["avt"]["tmp_name"])){
			//
			// UPLOAD IMAGE
			//
			$apikey = "9527a1c516dd2fca6551240ba89343ca";
			$url = "https://api.imgbb.com/1/upload";
			$ch = curl_init();
			$localFile = $_FILES["avt"]['tmp_name'];
			curl_setopt_array($ch, array(
				CURLOPT_RETURNTRANSFER => 1,
				CURLOPT_URL => $url,
				CURLOPT_USERAGENT => 'fuchsia TestSys',
				CURLOPT_POST => 1,
				CURLOPT_SSL_VERIFYPEER => false,
				CURLOPT_POSTFIELDS => http_build_query(array(
							'key' => $apikey,
							'image' => base64_encode(file_get_contents($localFile)),
						))
			));
			$res = curl_exec($ch);
			echo $res;
			$dec = json_decode($res, false);
			curl_close($ch);
			echo $res."<br>";
			//query("update user set imgavatar='".$dec->data->url."' where userID=".$_SESSION["userwtf"].";");
			if($dec->data->url) echo update("imgavt",$dec->data->url,"user","userID=".$_SESSION["userwtf"]);
		}
		//header("Location: ./");
	}
?>