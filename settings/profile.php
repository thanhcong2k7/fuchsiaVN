<?php
	session_start();
	if (isset($_SESSION["userwtf"])){
		require '../assets/variables/sql.php';
		require '../assets/variables/func.php';
		if(isset($_POST["fulname"])){
			query("update user set name='".$_POST["fulname"]."' where userID=".$_SESSION["userwtf"].";");
		}
		if(isset($_POST["displayname"])){
			query("update user set display='".$_POST["displayname"]."' where userID=".$_SESSION["userwtf"].";");
		}
		if(isset($_POST["email"])){
			query("update user set display='".$_POST["email"]."' where userID=".$_SESSION["userwtf"].";");
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
		if(isset($_FILES["coverimg"]["tmp_name"])){
			//
			// UPLOAD IMAGE
			//
			$apikey = "9527a1c516dd2fca6551240ba89343ca";
			$url = "https://api.imgbb.com/1/upload";
			$ch = curl_init();
			$localFile = $_FILES["coverimg"]['tmp_name'];
			echo $localFile."<br>";
			//START PROCESSING IMAGE
				$filename2=date("d-m-Y hms").'.png';
			/*
			$im = imagecreatefrompng($localFile);
			$size = min(imagesx($im)/8, imagesy($im)/5);
			$im2 = imagecrop($im, ['x' => (imagesx($im)>$size*8?imagesx($im)/2-$size*4:0), 'y' => (imagesy($im)>$size*5?imagesy($im)/2-$size*5/2:0), 'width' => $size*8, 'height' => $size*5]);
			echo $size."<br>";
			if ($im2 !== FALSE) {
				echo $filename2."<br>";
				ob_start();
				imagepng($im2);
				$data = ob_get_contents();
				ob_end_clean();
				file_put_contents($filename2,$data);
				imagedestroy($im2);
			}
			imagedestroy($im);
			*/
			$imgcrop = new ImageCrop();
			if ($imgcrop->openImage($localFile)){
				$imgcrop->crop(800,500);
				$imgcrop->save(date("d-m-Y hms").'.png');
			}
			//END PROCESSING IMAGE
			curl_setopt_array($ch, array(
				CURLOPT_RETURNTRANSFER => 1,
				CURLOPT_URL => $url,
				CURLOPT_USERAGENT => 'fuchsia TestSys',
				CURLOPT_POST => 1,
				CURLOPT_SSL_VERIFYPEER => false,
				CURLOPT_POSTFIELDS => http_build_query(array(
							'key' => $apikey,
							'image' => base64_encode(file_get_contents($filename2)),
						))
			));
			$res = curl_exec($ch);
			echo $res;
			$dec = json_decode($res, false);
			curl_close($ch);
			echo $res."<br>";
			//query("update user set imgavatar='".$dec->data->url."' where userID=".$_SESSION["userwtf"].";");
			if($dec->data->url) echo update("coverimg",$dec->data->url,"user","userID=".$_SESSION["userwtf"]);
		}
		//header("Location: ./");
	}
?>