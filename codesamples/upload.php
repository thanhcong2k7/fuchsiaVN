<?php
	if(isset($_POST["submit"])){
		$target_dir = "userRes/";
		$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
		$uploadOk = 1;
		$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
		// Check if image file is a actual image or fake image
		if(isset($_POST["submit"])) {
			$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
			if($check !== false) {
				echo "File is an image - " . $check["mime"] . ".";
				$uploadOk = 1;
			} else {
				echo "File is not an image.";
				$uploadOk = 0;
			}
		}
		// Check if file already exists
		if (file_exists($target_file)) {
			echo "Sorry, file already exists.";
			$uploadOk = 0;
		}

		// Allow certain file formats
		if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
			echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
			$uploadOk = 0;
		}
		//echo '<script>window.location.href="./update_storage.php'..'"</script>';
		move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
		echo "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.";
		//
	} else {
		$conn = mysqli_connect("localhost", "lunaris_real", "1993Ak1993@", "lunaris_real");
		$res = $conn->query("INSERT INTO storage (gID, fType, userID) (".$_GET["gID"].",".strval($uploadOk).",".$_SESSION["userwtf"].");");
	}
?>