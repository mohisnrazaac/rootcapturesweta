<?php
include "head.php";

//  http://10.1.4.12:6906/vnc.html?host=10.1.4.12&port=6906&autoconnect=true

// $redirectUrl =  "http://66.172.2.18:6906/vnc.html?host=66.172.2.18&port=6906&autoconnect=true";
// header("Location: $redirectUrl");
// die();

//Login

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
  
  $curlS = curl_init();
  curl_setopt_array($curlS, array(
    CURLOPT_URL => 'http://66.172.2.18:8080/api/antlets',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_HTTPHEADER => array(
      'Accept: application/json',
      'Authorization: Token '.$getToken['token']
    ),
  ));

  $responses = curl_exec($curlS);

  curl_close($curlS);
  

  $responseData = json_decode($responses,true);

  // print_r($responses);


?>

<body>

<div class="container" >
  <div class="row">
  <div class="col-md-12">
  <div class="card">
    <div class="card-header"><h1>Anstel Data</h1></div>
    <div class="card-body">
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Name</th>
          <th>Type</th>
          <th>Ram</th>
          <th>Storage</th>
          <th>IP</th>
          <!-- <th>Action</th> -->
        </tr>
      </thead>
      <tbody>
        <?php
          foreach($responseData as $key=>$value){
        ?>
        <tr>
          
          <td><?php echo $value['dname']?></td>
          <td><?php echo $value['dtype']?></td>
          <td><?php echo $value['ram-display']?></td>
          <td><?php echo $value['storage-display']?></td>
          <td><?php echo $value['ip']?></td>
          <!-- <td><a class="btn btn-primary" target="_blank" href="start-vnc-proxy.php?uuid=<?=$value['uuid']?>">Enter System</a></td> -->
        </tr>
        <?php 
      }
       ?>
      </tbody>
    </table>
    </div> 

</div>
</div>

  </div>
</div>

</body>

<?php
}
?>