<?php
    $f = fopen("stores.txt","r") or die("unable to open file!");
    $f2 = fopen("stores.csv","w") or die("unable to create file!");
    fwrite($f2,"albumid,");
    $i=1;
    while($i<486){
        fwrite($f2,$i.",");
        $i++;
    }
    fwrite($f2,"486");
    fclose($f2);
    while(!feof($f)){
        echo fgets($f)."<br>";
    }
    fclose($f);
?>