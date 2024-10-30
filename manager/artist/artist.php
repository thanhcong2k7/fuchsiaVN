what?
<?php
    session_start();
    if(isset($_SESSION["userwtf"])){
        require '../../assets/variables/sql.php';
        if(isset($_GET["delete"])){
            $ar = fetchArtist($_SESSION["userwtf"]);
            foreach($ar as &$a){
                if($a->id == $_GET["id"]){
                    if(count($a->isRestrict) == 0)
                        query("delete from author where authorID=".$_GET["id"].";");
                    else $_SESSION["restricted"] = $a->isRestrict_;
                }
            }
            resetinc("author");
        } else if(isset($_POST["alias"])){
            if($_POST['artist-id']==""){
                resetinc("author");
                query("insert into author (authorName,spotifyID,amID,email,userID) values ('".$_POST["alias"]."','".$_POST["spotifyID"]."','".$_POST["amID"]."','".$_POST["email"]."',".$_SESSION["userwtf"].");");
            } else {
                query("update author set authorName='".$_POST["alias"]."',spotifyID='".$_POST["spotifyID"]."',amID='".$_POST["amID"]."' where userID=".$_SESSION["userwtf"]." and authorID=".$_POST["artist-id"].";");
            }
        }
        header("Location: ./");
    }
?>