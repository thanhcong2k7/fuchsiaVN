<?php
    $file = $_FILES["filee"]["tmp_name"];
    $url = "https://script.google.com/macros/s/AKfycbyo4CppGYxPqVOZgIjamIWKDcNMu999V5zRAJRfR-326pkQ-M6oULGy-QR4e1IDmPLE/exec";
    $ch = curl_init();
    //curl_setopt($ch, CURLOPT_URL,$url);
    //curl_setopt($ch, CURL,1);
    //$localFile = $_FILES["artworkup"]['tmp_name'];
    //$fp = fopen($localFile, 'r');
    curl_setopt_array($ch, array(
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => $url,
        CURLOPT_USERAGENT => 'fuchsia TestSys',
        CURLOPT_POST => 1,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_POSTFIELDS => array(
            'content' => base64_encode(file_get_contents($file))
        )
    ));
    $res = curl_exec($ch);
    curl_close($ch);
    echo $res;
?>