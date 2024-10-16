<?php
    if(isset($_SESSION["userwtf"])){
        require '../assets/variables/sql.php';
        $re = getRelease($_SESSION["userwtf"],0,$_GET["albumid"]);
        $tmparr = array();
        foreach($re->file as &$tr){
            if($tr != $_GET["trackid"]){
                $tmparr[] = $tr;
            }
        }
        $merge = "";
        foreach($tmparr as $tr){
            $merge .= ($tmparr==""?$tr:", ".$tr);
        }
        query("update album set trackID=\"[".$merge."]\" where albumID=".$_GET["albumid"]);
        echo "is it ok?";
    } else echo "nah, stupid. wanna try to do it for 1 more time?";
?>