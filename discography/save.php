<?php
session_start();
require '../assets/variables/sql.php';
require '../assets/variables/obf.php';
if (isset($_SESSION['userwtf'])) {
    if (isset($_POST['upc'])) {
        update("UPCNum", $_POST["upc"], "album", "albumID=" . $_POST["albumid"]);
    }
    if (isset($_POST['albumtitle'])) {
        update("albumName", $_POST["albumtitle"], "album", "albumID=" . $_POST["albumid"]);
    }
    if (isset($_POST['albumversion'])) {
        update("versionLine", $_POST["albumversion"], "album", "albumID=" . $_POST["albumid"]);
    }
    if (isset($_POST['reldate'])) {
        $dt = date_format(date_create($_POST["reldate"]), "Y-m-d");
        update("relDate", $dt, "album", "albumID=" . $_POST["albumid"]);
    }
    if (isset($_POST['orgreldate'])) {
        $dt2 = date_format(date_create($_POST["orgreldate"]), "Y-m-d");
        update("orgReldate", $dt2, "album", "albumID=" . $_POST["albumid"]);
    }
    if (isset($_POST['cyear'])) {
        update("cyear", $_POST["cyear"], "album", "albumID=" . $_POST["albumid"]);
    }
    if (isset($_POST['pyear'])) {
        update("pyear", $_POST["pyear"], "album", "albumID=" . $_POST["albumid"]);
    }
    if (isset($_POST['cline'])) {
        update("compLine", $_POST["cline"], "album", "albumID=" . $_POST["albumid"]);
    }
    if (isset($_POST['pline'])) {
        update("publishLine", $_POST["pline"], "album", "albumID=" . $_POST["albumid"]);
    }
    if (isset($_POST['ytcid'])) {
        update("ytcid", 1, "album", "albumID=" . $_POST["albumid"]);
    }
    if (isset($_POST['scloud'])) {
        update("scloud", 1, "album", "albumID=" . $_POST["albumid"]);
    }
    if (isset($_POST['soundx'])) {
        update("soundx", 1, "album", "albumID=" . $_POST["albumid"]);
    }
    if (isset($_POST['jdl'])) {
        update("juno", 1, "album", "albumID=" . $_POST["albumid"]);
    }
    if (isset($_POST['trl'])) {
        update("tracklib", 1, "album", "albumID=" . $_POST["albumid"]);
    }
    if (isset($_POST['bport'])) {
        update("beatport", ($_POST["bporturl"] ? $_POST["bporturl"] : "1"), "album", "albumID=" . $_POST["albumid"]);
    }
    if ($_POST["submit"] == 'Distribute now') {
        update("status", 3, "album", "albumID=" . $_POST["albumid"]);
    }
    if (isset($_FILES["artworkup"]['tmp_name']) && $_FILES["artworkup"]['tmp_name'] != NULL) {
        //
        // UPLOAD IMAGE
        //
        $ch = curl_init();
        //curl_setopt($ch, CURLOPT_URL,$url);
        //curl_setopt($ch, CURL,1);
        $localFile = $_FILES["artworkup"]['tmp_name'];
        echo "an cut r <br>";
        //$fp = fopen($localFile, 'r');
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
        foreach ($_POST as $key => $value) {
            echo "Field " . htmlspecialchars($key) . " is " . htmlspecialchars($value) . "<br>";
        }
        foreach ($_FILES as $file) {
            $name = basename($file['name']);
            $tmp_name = $file['tmp_name'];
            $size = $file['size'];
            echo $tmp_name . "<br>";
            echo ($dec->data->url ? $dec->data->url : "wtf null?");
            echo update("artID", $dec->data->url, "album", "albumID=" . $_POST["albumid"]);
            echo update("artPrev", $dec->data->thumb->url, "album", "albumID=" . $_POST["albumid"]);
        }
        //
        // END UPLOAD IMAGE
        //
    }
    header("Location: ./");
}
?>