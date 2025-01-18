<?php
    $file = $_FILES["filee"];
    $url = "https://script.google.com/macros/s/AKfycbxLfqhemfnxXTmMAIEh14N3qq0ap09Dqi-Q7Dn2BquJfvmigmqto0R-E2NAUu5bN8KE/exec";
    $ch = curl_init();
    //curl_setopt($ch, CURLOPT_URL,$url);
    //curl_setopt($ch, CURL,1);
    //$localFile = $_FILES["artworkup"]['tmp_name'];
    //$fp = fopen($localFile, 'r');
    curl_setopt_array($ch, array(
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => $url,
        CURLOPT_POST => 1,
        CURLOPT_POSTFIELDS => http_build_query(array(
            'data' => base64_encode(file_get_contents($file["tmp_name"])),
            'type' => $file["type"],
            'name' => $file["name"]
        ))
    ));
    $res = curl_exec($ch);
    curl_close($ch);
    //echo $res;
    $result = json_decode($res, true);
    if ($result['status'] === 'success') {
        echo json_encode([
            'status' => 'success',
            'url' => $result['url']
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => $result['message']
        ]);
    }
?>