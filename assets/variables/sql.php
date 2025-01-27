<?php
	include 'obf.php';
 //echo "FUCK YOU! YOU DON'T HAVE ANY PERMISSIONS TO RUN THIS SHIT!";
	function query($cmd){
		return $GLOBALS["conn"]->query($cmd);
	}
	function resetinc($table){
		query("ALTER TABLE ".$table." AUTO_INCREMENT = 1;");
	}
	function update($key, $val, $table, $condition){
		query("update ".$table." set ".$key."='".$val."' where ".$condition.";");
		return "update ".$table." set ".$key."='".$val."' where ".$condition.";";
	}
	class store{
		public $id;
		public $name;
	}
	function getStore(){
		$tmp = query("select * from stores;");
		$tmp2 = array();
		while($row = mysqli_fetch_array($tmp)){
			$sus = new store();
			$sus->id = $row["storeID"];
			$sus->name = $row["storeName"];
			$tmp2[] = $sus;
		}
		return $tmp2;
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
		public $orgReldate; //original release date
		public $role = array(); //1 primary, 2 ft, 3 remixer ??? artist roles btw
		public $c; //copyright line
		public $p; //publishing line
		public $file = array(); //JSON array of tracks
		public $version; //Album version line: remix,...
		public $ytcid;
		public $sc;
		public $jdl;
		public $trl;
		public $sx;
		public $bp;
		public $artp;
	}
	function getRelease($uid, $num = 0, $id = 0){
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
			$tmp2->file = json_decode($row["trackID"]);
			$tmp2->c = $row["compLine"];
			$tmp2->p = $row["publishLine"];
			$tmp2->orgReldate = $row["orgReldate"];
			$rrole = json_decode($row["artistRole"]); //role
			$tmp2->createdDate = $row["createdDate"];
			$tmp2->relDate = $row["relDate"];
			$tmp2->version = $row["versionLine"];
			$tmp2->ytcid = $row["ytcid"];
			$tmp2->jdl=$row["juno"];
			$tmp2->sx = $row["soundx"];
			$tmp2->sc = $row["scloud"];
			$tmp2->bp = $row["beatport"];
			$tmp2->artp = $row["artPrev"];
			if($id!=0 && $row["albumID"] == $id){
				return $tmp2;
			} else $releases[] = $tmp2;
		}
		$r = array();
		if ($num!=0){
			for($i=0; $i<$num && $i<count($releases); $i++){
				$r[] = $releases[$i];
			}
			return $r;
		}
		return $releases;
	}
	class userType {
		public $name;
		public $email;
		public $display; //Display name
		public $register; //register date
		public $avatar; //Google Drive file ID
		public $type; //account type: 0 admin; 1 sub-acc
		public $handle; //actually username
	}
	function getUser($uid){
		$tmp1 = query("select name,email,labelName,regdate,imgavt from user where userID=".$uid.";");
		$tmp2 = new userType();
		while ($row=$tmp1->fetch_assoc()){
			$tmp2->name = $row["name"];
			$tmp2->display = $row["labelName"];
			$tmp2->register = $row["regdate"];
			$tmp2->avatar = $row["imgavt"]?$row["imgavt"]:"https://dashboard.fuchsia.viiic.net/assets/images/gallery/ava_sample.png";
			$tmp2->email = $row["email"];
			$tmp2->type = $row["type"];
			$tmp2->handle = $row["username"];
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
		$data = array();
		while ($row=$tmp1->fetch_assoc()){
			$tmp2 = new file();
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
		public $artistname;
		public $file;
	}
	function getTrack($track){
		$tmp1 = query("select * from track where id=".$track.";");
		//$track = array();
		$typ = new trackType();
		while ($row=$tmp1->fetch_assoc()){
			$typ->id = $row["id"];
			$typ->name = $row["name"];
			$typ->artist = json_decode($row["artist"]);
			$artist = getArtist($typ->id);
			$mergedArtistnames = "";
            foreach ($artist as &$adu) {
                $mergedArtistnames .= ($mergedArtistnames!=""?", ":"").$adu->name;
            }
			$typ->artistname = $mergedArtistnames;
			$typ->role = json_decode($row["role"]);
			$typ->file = $row["fID"];
		}
		return $typ;
	}
	function getArtist($trackID){
		$tmp1 = query("select * from track where id=".$trackID.";");
		$arr = array();
		while($row=$tmp1->fetch_assoc()){
			$jsjsjsjs = json_decode($row["artist"]);
			$sus = json_decode($row["role"]);
			$i = 0;
			foreach($jsjsjsjs as &$tmp2){
				$artists = new artistType();
				$artists->id = $tmp2;
				$artists->name = getArtistname($tmp2);
				$artists->role = $sus[$i];
				$i++;
				$arr[] = $artists;
			}
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
	function creNew($uid){
		$tmp1 = query("select albumID from album where userID=".$uid.";");
		$tmp2 = 0;
		while ($row=$tmp1->fetch_assoc()){
			$tmp2 = $row["albumID"];
		}
		return $tmp2;
	}
	class fetchArtist{
		public $id;
		public $name;
		public $spot;
		public $applemusic;
		public $email;
		public $isRestrict; // restrict user from deleting
		//this thing shows what tracks it is involving as array
		public $isRestrict_; //raw
	}
	function fetchArtist($uid){
		$tmp1 = query("select * from author where userID=".$uid.";");
		$tmp3 = array();
		while( $row=$tmp1->fetch_assoc()){
			$tmp2 = new fetchArtist();
			$tmp2->id = $row["authorID"];
			$tmp2->name = $row["authorName"];
			$tmp2->spot = $row["spotifyID"];
			$tmp2->applemusic = $row["amID"];
			$tmp2->email = $row["email"];
			$tmp2->isRestrict = json_decode($row["isRestricted"]);
			$tmp2->isRestrict_ = $row["isRestricted"];
			$tmp3[] = $tmp2;
		}
		return $tmp3;
	}
	function getTrackname($tid){
		$tmp1 = query("select name from track where id=".$tid.";");
		while ( $row=$tmp1->fetch_assoc()){
			return $row["name"];
		}
	}
	function getTrackList($uID){
		$tmp1 = query("select * from track where userID=".$uID.";");
		$tmp2 = array();
		while($row = $tmp1->fetch_assoc())
			$tmp2[] = getTrack($row["id"]);
		return $tmp2;
	}
?>
