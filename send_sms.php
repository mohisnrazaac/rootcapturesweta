<?php
 
    // require __DIR__ . './twilio/twilio-php-main/src/Twilio/autoload.php';                 
    require './twilio/twilio-php-main/src/Twilio/autoload.php';                 
    echo 'tryyu'; exit;
    // Your Account SID and Auth Token from twilio.com/console
    $account_sid = 'ACdd75d7f2dd310c4d296e761fd227510f';
    $auth_token = '83944661abc222e049761c25b561fe36';
    // In production, these should be environment variables. E.g.:
    // $auth_token = $_ENV["TWILIO_ACCOUNT_SID"]
    // A Twilio number you own with SMS capabilities
    $twilio_number = "+16027868971";
    $client = new Twilio\Rest\Client($account_sid, $auth_token);
    
    try
    { 
       
       $final = $client->messages->create(
           // Where to send a text message (your cell phone?)
           '9616666919',
           array(
               'from' => $twilio_number,
               'body' => 'Your otp is'.$otp
           )
       );

       if( !$otp_verification_preference ) {
          $_SESSION['otp_veri_pre'] = 1;
      }
      $sucMsg = 'LOGIN SUCCESSFUL. REDIRECTING....';
      
      $updateEmailSql = $odb -> prepare("UPDATE users SET `otp` = :otp WHERE id = :id");
      $updateEmailSql -> execute(array(':otp' => $otp, ':id' => $userInfo['ID']));
      $showline++;
      echo '<div class="message" id="message"><p><strong>SUCCESS: </strong>'.$sucMsg.'</p></div><meta http-equiv="refresh" content="3;url=verification.php">';
       
    }                                                      
    catch(Exception $e)
    {    echo 'ohh tum yaha ho';
       $showline++;
       echo '<div class="error" id="message"><p><strong>ERROR: </strong>'.$e->getMessage().'</p></div>';
    }
?>