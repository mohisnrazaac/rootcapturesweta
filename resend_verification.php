<?php
ob_start();
require_once 'includes/db.php';
require_once 'includes/init.php';
@session_start();
$ret = [];
if (isset($_POST['otp_expired']) && $_POST['otp_expired'] == 1) {
    $digits = 6;
    $otp = rand(pow(10, $digits - 1), pow(10, $digits) - 1);    

    $SQLGetInfo = $odb->prepare("SELECT * FROM `users` WHERE `id` = :id");
    $SQLGetInfo->execute(array(':id' => $_SESSION['tempID']));
    $userInfo = $SQLGetInfo->fetch(PDO::FETCH_ASSOC);
    $email = $userInfo['email'];
    $phone = $userInfo['phone'];
    $otp_verification_preference = $userInfo['otp_verification_preference'];

    //email otp
    if($userInfo['otp_verification_preference']==2 ){   
        $img = "https://rootcapture.com/assets/img/RootCaptureResizeSmall.png";
        $rcurl = "https://rootcapture.com/";
        //$html = "<center><img src='$img'/><p>Hello there,</p><p>Your one time otp is</p> $otp </p> <p> Support Team</p>";
        //$html = "<center><img src='$img'/><p>Hello there,</p><p>Your one time otp is</p> $otp </p> <p> Support Team</p>";
        $html = "<center><img src='$img'/><p>Uh-Oh, your code expired! Your new code is</p> <p>$otp </p> <p></p><p>Sincerely,</p><p> The RootCapture Support Team</p>";

        $headers  = "From: The rootCapture Support Team <support@rootcapture.com>\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        mail($email, "Otp Verification", $html, $headers);

        if( !$otp_verification_preference ) {
            $_SESSION['otp_veri_pre'] = 2;
        }
    }else{
        require './twilio/twilio-php-main/src/Twilio/autoload.php'; 
        // Your Account SID and Auth Token from twilio.com/console
        $account_sid = 'ACdd75d7f2dd310c4d296e761fd227510f';
        $auth_token = '83944661abc222e049761c25b561fe36';
    
        // A Twilio number you own with SMS capabilities
    
        $client = new Twilio\Rest\Client($account_sid, $auth_token);
        
        $result = $user -> send_sms_twilio( $phone,$otp,$client );
    

        if($result['status'])
        {
            if( !$otp_verification_preference ) {
                $_SESSION['otp_veri_pre'] = 1;
            }
        }
        else
        {
            $ret['status'] = false;
            $ret['message'] = $result['err_msg'];
            echo json_encode($ret);
            return;
        }

        
    }
    
    $updateEmailSql = $odb->prepare("UPDATE users SET `otp` = :otp WHERE id = :id");
    $updateEmailSql->execute(array(':otp' => $otp, ':id' => $_SESSION['tempID']));
    
    $ret['status'] = true;
    $ret['message'] = 'Please check your inbox and enter the provided otp....';
    //$ret['message'] = 'Code expired, your new code is being resent...';
    echo json_encode($ret);
    return $ret;
}elseif(isset($_POST['otp_expired']) && $_POST['otp_expired'] == 2){
    $otp = NULL;
    $updateEmailSql = $odb->prepare("UPDATE users SET `otp` = :otp WHERE id = :id");
    $updateEmailSql->execute(array(':otp' => $otp, ':id' => $_SESSION['tempID']));
    
    $ret['status'] = true;
    $ret['message'] = 'OTP has been expired Please resend it.';
    //$ret['message'] = 'Code expired, your new code is being resent...';
    echo json_encode($ret);
    return $ret;
}else {
    $ret['status'] = false;
    $ret['message'] = "Something wrong";
    echo json_encode($ret);
    return;
}
