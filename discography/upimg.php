<?php
    session_start();
    //if (isset($_SESSION["userwtf"])){
        $apikey = "9527a1c516dd2fca6551240ba89343ca";
        $url = "https://api.imgbb.com/1/upload";
        $ch = curl_init();
        //curl_setopt($ch, CURLOPT_URL,$url);
        //curl_setopt($ch, CURL,1);
        $localFile = $_FILES["artworkup"]['tmp_name'];
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
    //}
?>