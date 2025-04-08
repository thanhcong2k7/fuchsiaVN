<?php
session_start();
if (isset($_SESSION['userwtf'])) {
    if (isset($_POST['gID'])) {
        require "../../assets/variables/sql.php";
        try {
            resetinc('storage');
            query('insert into storage (gID, userID, fName) values ("' . $_POST['gID'] . '",' . $_SESSION['userwtf'] . ',"' . $_POST['fName'] . '");');
            $tmp = query('select * from storage where gID="' . $_POST['gID'] . '";');
            $id = NULL;
            while ($tmp2 = $tmp->fetch_assoc()) {
                $id = $tmp2['fileID'];
            }
            resetinc('track');
            query('insert into track (fID, userID, name) values ("' . $id . '",' . $_SESSION['userwtf'] . ',"' . $_POST['fName'] . '");');
            echo '{"status":1, "message":"All tasks succeeded! File ID: '.$id.', gID: '.$_POST["gID"].'"}';
        } catch (Exception $e) {
            echo '{"status":0, "message":"' . $e->getMessage() . '"}';
        }
    } else {
        echo '{"status":0, "message":"POST data detected, but why it is not valid???"}';
    }
} else {
    echo '{"status":0, "message":"User ID not detected... Who are you?"}';
}
?>