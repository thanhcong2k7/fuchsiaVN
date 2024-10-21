<?php
    require 'sql.php';
    if(isset($_SESSION["userwtf"])){
        if($_GET["req"]==1){
            query("update album set artID=".$_GET["fileID"]." where albumID=".$_GET["id"].";");
            query("insert into storage (gID,userID,fName) values (".$_GET["fileID"].",".$_SESSION["userwtf"].",".$_GET["name"].");");
        }
    }
?>