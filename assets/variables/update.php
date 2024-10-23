<?php
    require 'sql.php';
    header('Content-Type: application/json');
    if(isset($_SESSION["userwtf"])){
        if($_GET["req"]==1){
            query("update album set artID=".$_GET["fileID"]." where albumID=".$_GET["id"].";");
            query("insert into storage (gID,userID,fName) values (".$_GET["fileID"].",".$_SESSION["userwtf"].",".$_GET["name"].");");
            echo "{status:1}";
        }
    }
?>