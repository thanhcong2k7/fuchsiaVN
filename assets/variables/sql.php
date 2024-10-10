<?php
	$conn = mysqli_connect("127.0.0.1", "root", "rootroot", "fuchsia_dev");
	function query($cmd){
		return $GLOBALS["conn"]->query($cmd);
	}
	class artistType {
		public $id;
		public $role;
	}
	class albumType{
		public $id;
		public $upc;
		public $name;
		public $status; //0=draft, 1=accepted, 2=error, 3=checking
		public $art; //link to artwork google drive
		public $store = array(); //JSON array of chose stores
		public $author = array(); //JSON array of artists
		public $createdDate; //datetime fvck brbrbr
		public $relDate; //unset=ASAP; set=ok
		public $role = array(); //1 primary, 2 ft, 3 remixer ??? artist roles btw
		public $c; //copyright line
		public $p; //publishing line
		public $file = array(); //JSON array of tracks
	}
	function getRelease($uid){
		//find all release based on user id
		$tmp1 = query("select * from album where userID=".$uid.";");
		$releases = array();
		while($row=$tmp1->fetch_assoc()){
			$tmp2 = new albumType();
			$tmp2->id = $row["albumID"];
			$tmp2->upc = $row["UPCNum"];
			$tmp2->name = $row["albumName"];
			$tmp2->status = $row["status"];
			$tmp2->art = $row["artID"];
			$tmp2->store = json_decode($row["storeID"]);
			$tmp2->file = json_decode($row["fileID"]);
			$tmp2->c = $row["compLine"];
			$tmp2->p = $row["publishLine"];
			$rrole = json_decode($row["artistRole"]); //role
			$tmp2->createdDate = $row["createdDate"];
			$tmp2->relDate = $row["relDate"];
			$aauthor = json_decode($row["authorID"]); //author
			//$something[1]->relDate
			$i=0;
			$tmpatype = new artistType();
			if ($aauthor==null)
				$tmp2->author = null;
			else foreach ($aauthor as &$sus){
				//Process author
				$tmpatype->id = $aauthor[$i];
				$tmpatype->role = $rrole[$i];
				$tmp2->author = $tmpatype;
				$i++;
				//$something[1]->author->id
			}
			$releases[] = $tmp2;
		}
		return $releases;
	}
	class userType {
		public $name;
		public $email;
		public $display; //Display name
		public $register; //register date
		public $avatar; //Google Drive file ID
	}
	function getUser($uid){
		$tmp1 = query("select name,email,labelName,regdate,imgavt from user where userID=".$uid.";");
		$tmp2 = new userType();
		while ($row=$tmp1->fetch_assoc()){
			$tmp2->name = $row["name"];
			$tmp2->display = $row["labelName"];
			$tmp2->register = $row["regdate"];
			$tmp2->avatar = $row["imgavt"];
			$tmp2->email = $row["email"];
		}
		return $tmp2;
	}
?>
