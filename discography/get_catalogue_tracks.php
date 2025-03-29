<?php
    session_start();
    if(isset($_SESSION["userwtf"])){
        require '../assets/variables/sql.php';
        $trackList = getTrackList($_SESSION["userwtf"]);
        echo json_encode($trackList);
    }
?>