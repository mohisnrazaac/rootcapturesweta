<?php
$number = $_POST['number'];
$msg = $_POST['msg'];

// Include the bundled autoload from the Twilio PHP Helper Library
require __DIR__ . '/twilio-php-main/src/Twilio/autoload.php';
use Twilio\Rest\Client;
// Your Account SID and Auth Token from twilio.com/console
$account_sid = 'ACdd75d7f2dd310c4d296e761fd227510f';
$auth_token = '83944661abc222e049761c25b561fe36';
// In production, these should be environment variables. E.g.:
// $auth_token = $_ENV["TWILIO_ACCOUNT_SID"]
// A Twilio number you own with SMS capabilities
$twilio_number = "+16027868971";
$client = new Twilio\Rest\Client($account_sid, $auth_token);
/*echo "<pre>";
print_r($client);
die;*/
try {
  
$final = $client->messages->create(
    // Where to send a text message (your cell phone?)
    $number,
    array(
        'from' => $twilio_number,
        'body' => $msg
    )
);
  echo 'Message Send';
}

//catch exception
catch(Exception $e) {
  echo 'Message: ' .$e->getMessage();
}