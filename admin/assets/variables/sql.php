<?php
include 'obf.php';
//echo "FUCK YOU! YOU DON'T HAVE ANY PERMISSIONS TO RUN THIS SHIT!";
error_reporting(E_ERROR | E_PARSE);
function query($cmd)
{
	try {
		return $GLOBALS["conn"]->query($cmd);
	} catch (Exception $e) {
		echo '<script>console.log("' . $e->getMessage() . '");</script>';
		echo '<script>console.log("Wrong SQL command: ' . $cmd . '");</script>';
		return false;
	}
}
function resetinc($table)
{
	$tmp = query("SELECT `AUTO_INCREMENT`
					FROM  INFORMATION_SCHEMA.TABLES
					WHERE TABLE_SCHEMA = 'wtjmdnac_fuchsia'
					AND   TABLE_NAME   = '".$table."';");
	$lastInc = 0;
	while ($row = $tmp->fetch_assoc())
		$lastInc = $row["AUTO_INCREMENT"];
	query("ALTER TABLE " . $table . " AUTO_INCREMENT = ".($lastInc===NULL?"0":$lastInc).";");
}
function update($key, $val, $table, $condition)
{
	query("update " . $table . " set " . $key . "='" . $val . "' where " . $condition . ";");
	return "update " . $table . " set " . $key . "='" . $val . "' where " . $condition . ";";
}
function getID($email)
{
	$tmp2 = query("select userID from user where email='" . $email . "';");
	while ($tmp1 = $tmp2->fetch_assoc())
		return $tmp1["userID"];
}
class store
{
	public $id;
	public $name;
}
function getStore()
{
	$tmp = query("select * from stores;");
	$tmp2 = array();
	while ($row = mysqli_fetch_array($tmp)) {
		$sus = new store();
		$sus->id = $row["storeID"];
		$sus->name = $row["storeName"];
		$tmp2[] = $sus;
	}
	return $tmp2;
}
class artistType
{
	public $id;
	public $name;
	public $role;
}
class albumType
{
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
	public $staffID; // ID of the staff member who claimed this release
	public $submitterName; // Name of the user who submitted the release
}
function getRelease($uid, $num = 0, $id = 0)
{
	//find all release based on user id
	$tmp1 = query("select * from album where userID=" . $uid . ";");
	$releases = array();
	while ($row = $tmp1->fetch_assoc()) {
		$tmp2 = new albumType();
		$tmp2->id = $row["albumID"];
		$tmp2->upc = $row["UPCNum"];
		$tmp2->name = $row["albumName"];
		$tmp2->status = $row["status"];
		$tmp2->art = $row["artID"];
		$tmp2->store = json_decode($row["storeID"]);
		$tmp2->file = getTrackListOfAlbum($id);
		$tmp2->c = $row["compLine"];
		$tmp2->p = $row["publishLine"];
		$tmp2->orgReldate = $row["orgReldate"];
		//$rrole = json_decode($row["artistRole"]); //role
		$tmp2->createdDate = $row["createdDate"];
		$tmp2->relDate = $row["relDate"];
		$tmp2->version = $row["versionLine"];
		$tmp2->ytcid = $row["ytcid"];
		$tmp2->jdl = $row["juno"];
		$tmp2->sx = $row["soundx"];
		$tmp2->sc = $row["scloud"];
		$tmp2->bp = $row["beatport"];
		$tmp2->artp = $row["artPrev"];
		if ($id != 0 && $row["albumID"] == $id) {
			return $tmp2;
		} else
			$releases[] = $tmp2;
	}
	$r = array();
	if ($num != 0) {
		for ($i = 0; $i < $num && $i < count($releases); $i++) {
			$r[] = $releases[$i];
		}
		return $r;
	}
	return $releases;
}
function getAllReleases($statusFilter = null) // Optional status filter (e.g., 'pending', 'approved')
{
    $sql = "SELECT album.*, user.labelName AS submitterName FROM album JOIN user ON album.userID = user.userID";
    if ($statusFilter !== null) {
        // Assuming status 0=draft, 1=accepted, 2=error, 3=checking/pending
        // Adjust the condition based on how you want to filter (e.g., for pending: status = 3)
        $sql .= " WHERE album.status = '" . $GLOBALS["conn"]->real_escape_string($statusFilter) . "'";
    }
    $sql .= " ORDER BY album.createdDate DESC;";

    $tmp1 = query($sql);
    $releases = array();
    if ($tmp1) {
        while ($row = $tmp1->fetch_assoc()) {
            $tmp2 = new albumType();
            $tmp2->id = $row["albumID"];
            $tmp2->upc = $row["UPCNum"];
            $tmp2->name = $row["albumName"];
            $tmp2->status = $row["status"];
            $tmp2->art = $row["artID"];
            $tmp2->store = json_decode($row["storeID"]);
            // $tmp2->file = getTrackListOfAlbum($row["albumID"]); // Potentially heavy, consider loading on demand
            $tmp2->c = $row["compLine"];
            $tmp2->p = $row["publishLine"];
            $tmp2->orgReldate = $row["orgReldate"];
            $tmp2->createdDate = $row["createdDate"];
            $tmp2->relDate = $row["relDate"];
            $tmp2->version = $row["versionLine"];
            $tmp2->ytcid = $row["ytcid"];
            $tmp2->jdl = $row["juno"];
            $tmp2->sx = $row["soundx"];
            $tmp2->sc = $row["scloud"];
            $tmp2->bp = $row["beatport"];
            $tmp2->artp = $row["artPrev"];
            $tmp2->submitterName = $row["submitterName"]; // Added submitter name
            $tmp2->staffID = $row["staffID"]; // Include staffID for claim functionality

            // Fetch artists for the album - this might need a dedicated function or optimized query
            // For simplicity, let's assume a function getArtistsForAlbum($albumId) exists or is added
            // $tmp2->author = getArtistsForAlbum($row["albumID"]); // Placeholder

            $releases[] = $tmp2;
        }
    }
    return $releases;
}

function getReleasesForReview($staffId = null)
{
    $sql = "SELECT album.*, user.labelName AS submitterName FROM album JOIN user ON album.userID = user.userID";
    if ($staffId !== null) {
        // Get releases claimed by a specific staff member
        $sql .= " WHERE album.staffID = " . intval($staffId);
    } else {
        // Get unclaimed releases (staffID is NULL or 0)
        $sql .= " WHERE album.staffID IS NULL OR album.staffID = 0";
    }
    $sql .= " ORDER BY album.createdDate DESC;";

    $tmp1 = query($sql);
    $releases = array();
    if ($tmp1) {
        while ($row = $tmp1->fetch_assoc()) {
            $tmp2 = new albumType();
            $tmp2->id = $row["albumID"];
            $tmp2->upc = $row["UPCNum"];
            $tmp2->name = $row["albumName"];
            $tmp2->status = $row["status"];
            $tmp2->art = $row["artID"];
            $tmp2->store = json_decode($row["storeID"]);
            $tmp2->c = $row["compLine"];
            $tmp2->p = $row["publishLine"];
            $tmp2->orgReldate = $row["orgReldate"];
            $tmp2->createdDate = $row["createdDate"];
            $tmp2->relDate = $row["relDate"];
            $tmp2->version = $row["versionLine"];
            $tmp2->ytcid = $row["ytcid"];
            $tmp2->jdl = $row["juno"];
            $tmp2->sx = $row["soundx"];
            $tmp2->sc = $row["scloud"];
            $tmp2->bp = $row["beatport"];
            $tmp2->artp = $row["artPrev"];
            $tmp2->submitterName = $row["submitterName"];
            $tmp2->staffID = $row["staffID"]; // Include staffID

            $releases[] = $tmp2;
        }
    }
    return $releases;
}

function getAllUsers()
{
    $tmp1 = query("SELECT * FROM user ORDER BY regdate DESC;");
    $users = array();
    if ($tmp1) {
        while ($row = $tmp1->fetch_assoc()) {
            $tmp2 = new userType();
            $tmp2->id = $row["userID"]; // Assuming userID is the primary key
            $tmp2->name = $row["name"];
            $tmp2->display = $row["labelName"];
            $tmp2->register = $row["regdate"];
            $tmp2->avatar = $row["imgavt"] ? $row["imgavt"] : "/assets/images/gallery/ava_sample.png";
            $tmp2->email = $row["email"];
            $tmp2->type = $row["type"];
            $tmp2->handle = $row["username"];
            $users[] = $tmp2;
        }
    }
    return $users;
}
class userType
{
public $id;
	public $name;
	public $email;
	public $display; //Display name
	public $register; //register date
	public $avatar; //Google Drive file ID
	public $type; //account type: 0 admin; 1 sub-acc
	public $handle; //actually username
}
function getUser($uid)
{
    if ($GLOBALS["conn"] === null) {
        // Handle null connection if necessary, though it should be set by var.php
        error_log("Database connection is not initialized in getUser.");
        return null;
    }

    $stmt = $GLOBALS["conn"]->prepare("SELECT userID, name, labelName, regdate, imgavt, email, type, username FROM user WHERE userID = ?");
    if (!$stmt) {
        error_log("Prepare failed: (" . $GLOBALS["conn"]->errno . ") " . $GLOBALS["conn"]->error);
        return null;
    }

    $stmt->bind_param("i", $uid);
    if (!$stmt->execute()) {
        error_log("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
        $stmt->close();
        return null;
    }

    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $user = new userType();
        $user->id = $row["userID"];
        $user->name = $row["name"];
        $user->display = $row["labelName"];
        $user->register = $row["regdate"];
        $user->avatar = $row["imgavt"] ? $row["imgavt"] : "/assets/images/gallery/ava_sample.png";
        $user->email = $row["email"];
        $user->type = $row["type"];
        $user->handle = $row["username"];
        $stmt->close();
        return $user;
    }

    $stmt->close();
    return null; // User not found
}
class file
{
	public $name;
	public $id;
	public $data;
	public $gid;
}
function getFile($uid)
{
	$tmp1 = query("select * from storage where userID=" . $uid . ";");
	$data = array();
	while ($row = $tmp1->fetch_assoc()) {
		$tmp2 = new file();
		$tmp2->name = $row["fName"];
		$tmp2->gid = $row["gID"];
		$tmp2->id = $row["fileID"];
		$data[] = $tmp2;
	}
	return $data;
}
class trackType
{
	public $id;
	public $name;
	public $version;
	public $role;
	public $artist;
	public $artistname;
	public $file;
	public $isrc;
	public $duration; // in seconds
}
function getTrack($track)
{
	$tmp1 = query("select * from track where id=" . $track . ";");
	//echo "<script> console.log('select * from track where id=".$track.";');</script>";
	//$track = array();
	$typ = new trackType();
	while ($row = $tmp1->fetch_assoc()) {
		$typ->id = $row["id"];
		$typ->name = $row["name"];
		$typ->version = $row["ver"];
		$typ->artist = json_decode($row["artist"]);
		$artist = getArtist($typ->id);
		$mergedArtistnames = "";
		foreach ($artist as &$adu) {
			$mergedArtistnames .= ($mergedArtistnames != "" ? ", " : "") . $adu->name;
		}
		$typ->artistname = $mergedArtistnames;
		$typ->role = json_decode($row["role"]);
		$typ->file = $row["fID"];
		$typ->isrc = $row["isrc"];
		$typ->duration = $row["duration"];
	}
	return $typ;
}
function getArtist($trackID)
{
	$tmp1 = query("select * from track where id=" . strval($trackID) . ";");
	$arr = array();
	while ($row = $tmp1->fetch_assoc()) {
		$jsjsjsjs = json_decode($row["artist"]);
		$sus = json_decode($row["role"]);
		$i = 0;
		foreach ($jsjsjsjs as &$tmp2) {
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
function getArtistname($artistID)
{
	$tmp1 = query("select authorName from author where authorID=" . $artistID . ";");
	$ok = "";
	while ($row = $tmp1->fetch_assoc()) {
		$ok = $row["authorName"];
	}
	return $ok;
}
function creNew($uid)
{
	$tmp1 = query("select albumID from album where userID=" . $uid . ";");
	$tmp2 = 0;
	while ($row = $tmp1->fetch_assoc()) {
		$tmp2 = $row["albumID"];
	}
	return $tmp2;
}
class fetchArtist
{
	public $id;
	public $name;
	public $spot;
	public $applemusic;
	public $email;
	public $isRestrict; // restrict user from deleting
	//this thing shows what tracks it is involving as array
	public $isRestrict_; //raw
}
function fetchArtist($uid)
{
	$tmp1 = query("select * from author where userID=" . $uid . ";");
	$tmp3 = array();
	while ($row = $tmp1->fetch_assoc()) {
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
function getTrackname($tid)
{
	$tmp1 = query("select name from track where id=" . $tid . ";");
	while ($row = $tmp1->fetch_assoc()) {
		return $row["name"];
	}
}
function getTrackList($uID)
{
	$tmp1 = query("select * from track where userID=" . $uID . ";");
	$tmp2 = array();
	while ($row = $tmp1->fetch_assoc())
		$tmp2[] = getTrack($row["id"]);
	return $tmp2;
}
function getTrackListOfAlbum($albID)
{
	$tmp1 = query("select * from track where albumID=" . $albID . ";");
	$tmp2 = array();
	while ($row = $tmp1->fetch_assoc())
		$tmp2[] = getTrack($row["id"]);
	return $tmp2;
}
?>