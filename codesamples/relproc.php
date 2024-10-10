<?php
    session_start();
    echo "ok, ".(isset($_SESSION["userwtf"])?"ok":"deo on r");
    if (isset($_SESSION["userwtf"])){
    	$conn = mysqli_connect("localhost", "lunaris_real", "1993Ak1993@", "lunaris_real");
        echo $_POST["relTitle"]." hehe ".$_POST["btnSubmit"]." ".$_POST["btnSave"]." ".strval($_POST["albumID"]);
        $res=$conn->query("SELECT * FROM artist WHERE userID=".$_SESSION["userwtf"].";");
        while($row=$res->fetch_assoc())
        {
            $artist[]=$row["authorName"];
            $artistid[]=$row["authorID"];
        }
        for($i=0; isset($_POST["aname-".strval($i)]); $i++)
        {
            $arole[]=$_POST["arole-".strval($i)];
            $aid[]=$_POST["aname-".strval($i)];
        }
        foreach($aid as $a)
            echo $a." sus ";
        //if (isset($_POST["btnSubmit"]))
            //$res=$conn->query("UPDATE album SET albumName=".$_POST["relTitle"].", WHERE albumID=".$_POST["albumID"].";")
    }
?>