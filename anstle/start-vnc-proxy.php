<?php
include "head.php";

$uuid['antlet-uuid'] = $_GET['uuid'];
$data = json_encode($uuid);
echo $data;

$curl = curl_init();


curl_setopt_array($curl, array(
    CURLOPT_URL => 'http://66.172.2.18:8080/api/antlets/start-vnc-proxy',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS =>$data,
    CURLOPT_HTTPHEADER => array(
      'Accept: application/json',
      'Authorization: Token eyJhbGciOiJIUzUxMiJ9.eyJ1c2VyIjoicm9vdCJ9.e9e6RYtx2AaTHnowWRkYB-KlbJzSGEaGv_oF_F1dEOgVk9PQp9m5-y_cAJ6XelVbtrOmwQxZnC58-4A7klz5Ug',
      'Content-Type: application/json',
    ),
  ));

$response = curl_exec($curl);
$httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
curl_close($curl);
 

$responseData = json_decode($response,true);
// Array ( [success] => 1 [message] => VNC proxy opened [proxy-port] => 6912 [vncdisplay-num] => 12 )
// http://10.1.4.12:6906/vnc.html?host=10.1.4.12&port=6906&autoconnect=true


if($httpcode ==200 && isset($responseData['proxy-port'])){
    $redirectUrl =  "http://10.1.4.12:".$responseData['proxy-port']."/vnc.html?host=10.1.4.12&port=".$responseData['proxy-port']."&autoconnect=true";
    header("Location: $redirectUrl");
    die();
}


?>


