<?php
	$conn = mysqli_connect("127.0.0.1", "wtjmdnac_fuchsia", "nguyenthanhcong", "wtjmdnac_fuchsia");
	function query($cmd){
		return $GLOBALS["conn"]->query($cmd);
	}
	class artistType {
		public $id;
		public $name;
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
	function getRelease($uid, $num = 0){
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
		$r = array();
		if ($num!=0){
			for($i=0; $i<$num && $i<count($releases); $i++){
				$r[] = $releases[$i];
			}
			return $r;
		} else return $releases;
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
	class file{
		public $name;
		public $id;
		public $data;
		public $gid;
	}
	function getFile($uid){
		$tmp1 = query("select * from storage where userID=".$uid.";");
		$tmp2 = new file();
		$data = array();
		while ($row=$tmp1->fetch_assoc()){
			$tmp2->name = $row["fName"];
			$tmp2->gid = $row["gID"];
			$tmp2->id = $row["fileID"];
			$data[] = $tmp2;
		}
		return $data;
	}
	class trackType{
		public $id;
		public $name;
		public $role;
		public $artist;
	}
	function getTrack($album){
		$tmp1 = query("select trackID from album where albumID=".$album.";");
		//$track = array();
		while ($row=$tmp1->fetch_assoc()){
			$track = json_decode($row["trackID"]);
			foreach ($track as &$ok) {
				$typ = new trackType();
				$typ->id = $ok;
				$typ->name = getTrackname($ok);
			}
		}
	}
	function getTrackname($trackID){
		$tmp1 = query("select name from track where id=".$trackID.";");
		while ($row=$tmp1->fetch_assoc()){
			return $row["name"];
		}
	}
	function getArtist($trackID){
		$tmp1 = query("select * from track where id=".$trackID.";");
		$arr = array();
		while($row=$tmp1->fetch_assoc()){
			$artists = new artistType();
			$jsjsjsjs = json_decode($row["artist"]);
			$sus = json_decode($row["role"]);
			$i = 0;
			foreach($jsjsjsjs as &$tmp2){
				$artists->id = $tmp2;
				$artists->name = getArtistname($tmp2);
				$artists->role = $sus[$i];
				$i++;
			}
			$arr[] = $artists;
		}
		return $arr;
	}
	function getArtistname($artistID){
		$tmp1 = query("select authorName from author where authorID=".$artistID.";");
		$ok = "";
		while ($row=$tmp1->fetch_assoc()){
			$ok = $row["authorName"];
		}
		return $ok;
	}
?>
