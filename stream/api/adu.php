<?php
//
// This file is for handling creating/editing stream link.
//
session_start();
if (isset($_SESSION['userwtf']) && $_GET['upc'] != null) {
    // Endpoint URL
    header('Content-Type: application/json; charset=utf-8');
    require '../../assets/variables/sql.php';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://api.found.ee/auth/login');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "{ \"email\": \"nhocpeacock@gmail.com\", \"password\": \"#Cong040307\" }");
    $headers = array();
    $headers[] = 'Content-Type: application/json';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }
    curl_close($ch);
    //nghĩ tên để đặt vcl
    $ok = json_decode($response);
    //($ok);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://api.found.ee/link/api/page');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "{\"upc\": \"" . $_GET["upc"] . "\"}");
    $headers = array();
    $headers[] = 'Authorization: Bearer ' . $ok->token;
    $headers[] = 'Content-Type: application/json';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }
    curl_close($ch);
    //echo ($result);
    $decoded = json_decode($result)->data;
    $kcj = smallRelease($_SESSION['userwtf'], $_GET['upc']);
    $tmp1 = query("select * from albums_stream where albumID=" . $kcj->id . ";");
    while ($row = $tmp1->fetch_assoc()) {
        echo json_encode(['status' => 0, 'message' => 'Duplicated record.']);
        exit(0);
    }
    if (true) {
        $resp = query("insert into albums_stream (associated,album_name,release_date, album_image, artist,albumID)
    values ('" . $decoded->shortCode . "','" . $decoded->desc . "','" . $kcj->relDate . "','" . $decoded->content->image->imageUrl . "', '" . $decoded->header . "','" . $kcj->id . "');");
        $tmp2 = query("select * from albums_stream where associated='" . $decoded->shortCode . "';");
        $streamid = 0;
        while ($row = $tmp2->fetch_assoc()) {
            $streamid = $row["id"];
        }
        foreach ($dspObj as $decoded->retailersInfo) {
            $resp2 = query("insert into dsp_urls (album_id,name,url,order)
    values (" . $streamid . ",'" . $dspObj->retailerName . "','" . $dspObj->dest->destinationUrl . "'," . $dspObj->order . ");");
            //echo json_encode(['status' => 1, 'message' => 'Successfully created stream link.', 'url' => 'https://fuchsia.viiic.net/stream/' . $decoded->shortCode]);
        }
        echo json_encode(['status' => 1, 'message' => 'Successfully created stream link.', 'url' => 'https://fuchsia.viiic.net/stream/' . $decoded->shortCode]);
    }
} else
    echo '<h1>con cac</h1>';
?>