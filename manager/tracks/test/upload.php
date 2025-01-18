<?php
    $file = $_FILES["filee"];
    $url = "https://script.google.com/macros/s/AKfycbwq-sqxAmNipIElVPMf2iR2xxN-4c6kQJ97cXig4Jldn2TqlY-2TKJTtyLY3fJESQ/exec";
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
        CURLOPT_POSTFIELDS => http_build_query(array(
            'data' => base64_encode(file_get_contents($file["tmp_name"])),
            'type' => $file["type"],
            'name' => $file["name"]
        ))
    ));
    $res = curl_exec($ch);
    curl_close($ch);
    echo $res;
?>