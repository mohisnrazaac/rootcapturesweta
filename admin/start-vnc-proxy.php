<?php
if(isset($_GET['server_name'])){


  $curl = curl_init();
  curl_setopt_array($curl, array(
  CURLOPT_URL => 'http://66.172.2.18:8080/api/login',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'{
      "username": "root",
      "password": "antsle"
  }',
  CURLOPT_HTTPHEADER => array(
      'Content-Type: application/json'
  ),
  ));
  $response = curl_exec($curl);
  if(curl_errno($curl))
      echo 'Curl error: '.curl_error($curl);
  $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
  // echo $httpcode;
  curl_close($curl);

  if($httpcode == 200 ){
      $getToken = json_decode($response,true);

      $curlSend = curl_init();

      curl_setopt_array($curlSend, array(
        CURLOPT_URL => 'http://66.172.2.18:8080/api/antlets/'.$_GET['server_name'],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
          'Accept: application/json',
          'Authorization: Token '.$getToken['token'],
          'Content-Type: application/json',
        ),
      ));

    $respinseSend = curl_exec($curlSend);
    echo $httpcodeSend = curl_getinfo($curlSend, CURLINFO_HTTP_CODE);
    curl_close($curlSend);

    if($httpcodeSend == 200 ){
      $getUuid = json_decode($respinseSend,true);
     
      $uuid['antlet-uuid'] = $getUuid['uuid'];
      $data = json_encode($uuid);
      // echo $data;

      $curlConsole = curl_init();


      curl_setopt_array($curlConsole, array(
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
            'Authorization: Token '.$getToken['token'],
            'Content-Type: application/json',
          ),
        ));

      $responseConsole = curl_exec($curlConsole);
      $httpcodeConsole = curl_getinfo($curlConsole, CURLINFO_HTTP_CODE);
      curl_close($curlConsole);
      
      $responseData = json_decode($responseConsole,true);
      // echo $responseConsole; exit;


      if($httpcodeConsole ==200 && isset($responseData['proxy-port'])){
          $redirectUrl =  "http://66.172.2.18:".$responseData['proxy-port']."/vnc_lite.html";
          // $redirectUrl =  "http://10.1.4.12:".$responseData['proxy-port']."/vnc.html?host=10.1.4.12&port=".$responseData['proxy-port']."&autoconnect=true";
          header("Location: $redirectUrl");
          die();
      }
      // echo $respinseSend;


    }else{
      header("Location: https://rootcapture.com/admin/server_list.php");
    }
    

  }else{
    header("Location: https://rootcapture.com/admin/server_list.php");
  }

  





}

header("Location: https://rootcapture.com/admin/server_list.php");



?>


