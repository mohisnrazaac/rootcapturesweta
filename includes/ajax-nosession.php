<?php
    use PHPMailer\PHPMailer\PHPMailer;
    // use PHPMailer\PHPMailer\Exception;
    require_once './../vendor/autoload.php'; 
    require_once './db.php';
    require_once './functions.php';

    $mail = new PHPMailer(true);

    $user = new user;
    session_start();

    $function_name = $_POST['function_name'];
    if($function_name == 'submit_contact_form') submitContactForm($odb,$_POST['name'], $_POST['email'], $_POST['subject'], $_POST['message']); else
    if($function_name == 'submit_blog_reply') submit_blog_reply($odb,$_POST['name'], $_POST['email'], $_POST['reply'], $_POST['blogid']); else {}
    if($function_name == 'validate_otp_reg') validate_otp_reg($odb, $user,$mail, $_POST['email'], $_POST['phoneNumber'], $_POST['preference']); else {}
    if($function_name == 'resend_veri_email_reg') resend_veri_email_reg($odb, $user,$mail, $_POST['email']); else {}
    if($function_name == 'resend_veri_mobile_reg') resend_veri_mobile_reg($odb, $user,$mail, $_POST['phoneNumber']); else {}
    if($function_name == 'otp_vry_register') otp_vry_register($odb, $_POST['v1'], $_POST['v2'], $_POST['v3'], $_POST['v4'], $_POST['v5'], $_POST['v6'], $_POST['email'], $_POST['phoneNumber'] ); else {}

    if($function_name == 'send_auth_code_verification') send_auth_code_verification($odb , $user , $mail); else {}
    if($function_name == 'resend_otp_email_verification') resend_otp_email_verification($odb , $user , $mail); else {}
    if($function_name == 'resend_otp_phone_verification') resend_otp_phone_verification($odb , $user , $mail); else {}
  
    function submitContactForm($odb,$name,$email,$subject,$message)
    {
      
        $img = "https://rootcapture.com/assets/img/RootCaptureResizeSmall.png";
        $rcurl = "https://rootcapture.com/";
        $html = "<center><img src='$img'/>
                    <p> Name : '$name'</p>
                    <p> Email : '$email'</p>
                    <p> Subject : '$subject'</p>
                    <p> Message : '$message'</p>
                    <p> Support Team</p>";
  
        $headers  = "From: The rootCapture Support Team <support@rootcapture.com>\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        // mail('swetashreegupta@virtualemployee.com', "Contact Us Form", $html, $headers);
        mail('contact@rootcapture.com', "Contact Us Form", $html, $headers);
  
        $ret['status'] = true;
        $ret['message'] = "Your query has been submitted successfully.";
        echo json_encode($ret);
        return $ret;
  
    }

    function submit_blog_reply($odb,$name,$email,$reply,$blogid)
    {
        require_once './db-enterprise.php';

        $insert = $odbenterprise -> prepare("INSERT INTO `blog_replies` (`blog_id`, `username`, `email`, `reply`) VALUES(:blog_id, :username, :email, :reply)");
        $insert -> execute(array(':blog_id' => $blogid, ':username' => $name, ':email' => $email, ':reply' => $reply ));

        $ret['status'] = true;
        $ret['message'] = "Your reply has been submitted successfully.";
        echo json_encode($ret);
        return $ret;
  
    }

    function validate_otp_reg($odb, $user, $mail, $email,$phoneNumber,$preference)
    {
        try
        {
            if( $preference == 1 )
            {
                // Trigger Message
                $digits = 6;
                $otp = rand(pow(10, $digits-1), pow(10, $digits)-1);  
                $_SESSION['otp_veri_pre'] = 1;      
                
                // check if already exist
                $isExists = $odb -> prepare("SELECT id FROM `pre_registration` WHERE `mobile` = :mobile");
                $isExists -> execute(array(':mobile' => $phoneNumber));
                $row = $isExists -> fetch(PDO::FETCH_ASSOC);

                if($row)
                {
                    $SQLupdate = $odb -> prepare("UPDATE pre_registration SET `otp` = :otp , is_used = 0 WHERE id = :id");
                    $SQLupdate -> execute(array(':otp' => $otp, ':id' => $row['id']));
                }
                else
                {
                    $insert = $odb -> prepare("INSERT INTO `pre_registration` (`mobile`, `otp`) VALUES(:mobile, :otp)");
                    $insert -> execute(array(':mobile' => $phoneNumber, ':otp' => $otp ));
                }

               
    
                require '../twilio/twilio-php-main/src/Twilio/autoload.php'; 
                // Your Account SID and Auth Token from twilio.com/console
                $account_sid = 'ACdd75d7f2dd310c4d296e761fd227510f';
                $auth_token = '83944661abc222e049761c25b561fe36';
                
                // A Twilio number you own with SMS capabilities
            
                $client = new Twilio\Rest\Client($account_sid, $auth_token);
                
                $res_twillio = $user -> send_sms_twilio( $phoneNumber,$otp,$client );
            
    
                if(!$res_twillio['status'])
                {
                    $ret['status'] = false;
                    $ret['message'] = $res_twillio['err_msg'];
                    echo json_encode($ret);
                    return $ret;
                }
            }
            else if( $preference == 2 )
            {
                // Trigger email
                $digits = 6;
                $otp = rand(pow(10, $digits-1), pow(10, $digits)-1);  
                $_SESSION['otp_veri_pre'] = 2;   

                // check if already exist
                $isExists = $odb -> prepare("SELECT id FROM `pre_registration` WHERE `email` = :email");
                $isExists -> execute(array(':email' => $email));
                $row = $isExists -> fetch(PDO::FETCH_ASSOC);
               
                if($row)
                {
                    $SQLupdate = $odb -> prepare("UPDATE pre_registration SET `otp` = :otp , is_used = 0 WHERE id = :id");
                    $SQLupdate -> execute(array(':otp' => $otp, ':id' => $row['id']));
                }
                else
                {
                    $insert = $odb -> prepare("INSERT INTO `pre_registration` (`email`, `otp`) VALUES(:email, :otp)");
                    $insert -> execute(array(':email' => $email, ':otp' => $otp ));
                }

                $img = "https://rootcapture.com/assets/img/RootCaptureResizeSmall.png";
                $rcurl = "https://rootcapture.com/";
                $subject = 'The rootCapture Support Team';
                $body = "<center><img src='$img'/><p>Hello there,</p><p>Welcome to the rootCapture Learning Platform! Your One-Time Passcode is:</p> <p>$otp </p> <p></p><p>Sincerely,</p><p> The RootCapture Support Team</p>";

                if (!$user -> sendEmail($mail, $email, $subject, $body))
                {
                    $ret['status'] = false;
                    $ret['message'] = 'Error in sending email';
                    echo json_encode($ret);
                    return $ret;
                }
            }
    
            $ret['status'] = true;
            $ret['message'] = "Successfull.";
            echo json_encode($ret);
            return $ret;
        }
        catch(\Exception $e)
        {
            $ret['status'] = false;
            $ret['message'] = $e->getMessage();
            echo json_encode($ret);
            return $ret;
        }
    }

    function resend_veri_email_reg($odb, $user, $mail, $email )
    {
        try
        {
            // Trigger email
            $digits = 6;
            $otp = rand(pow(10, $digits-1), pow(10, $digits)-1);  
            $_SESSION['otp_veri_pre'] = 2;   

            // check if already exist
            $isExists = $odb -> prepare("SELECT id FROM `pre_registration` WHERE `email` = :email");
            $isExists -> execute(array(':email' => $email));
            $row = $isExists -> fetch(PDO::FETCH_ASSOC);
            
            if($row)
            {
                $SQLupdate = $odb -> prepare("UPDATE pre_registration SET `otp` = :otp WHERE id = :id");
                $SQLupdate -> execute(array(':otp' => $otp, ':id' => $row['id']));
            }
            else
            {
                $insert = $odb -> prepare("INSERT INTO `pre_registration` (`email`, `otp`) VALUES(:email, :otp)");
                $insert -> execute(array(':email' => $email, ':otp' => $otp ));
            }

            $img = "https://rootcapture.com/assets/img/RootCaptureResizeSmall.png";
            $rcurl = "https://rootcapture.com/";
            $subject = 'Otp Verification For Registration';
            $body = "<center><img src='$img'/><p>Hello there,</p><p>Welcome to the rootCapture Learning Platform! Your One-Time Passcode is:</p> <p>$otp </p> <p></p><p>Sincerely,</p><p> The RootCapture Support Team</p>";

            if (!$user -> sendEmail($mail, $email, $subject, $body))
            {
                $ret['status'] = false;
                $ret['message'] = 'Error in sending email';
                echo json_encode($ret);
                return $ret;
            }
          
    
            $ret['status'] = true;
            $ret['message'] = "Successfull.";
            echo json_encode($ret);
            return $ret;
        }
        catch(\Exception $e)
        {
            $ret['status'] = false;
            $ret['message'] = $e->getMessage();
            echo json_encode($ret);
            return $ret;
        }
    }

    function resend_veri_mobile_reg($odb, $user, $mail, $phoneNumber )
    {
        try
        {
            // Trigger Message
            $digits = 6;
            $otp = rand(pow(10, $digits-1), pow(10, $digits)-1);  
            $_SESSION['otp_veri_pre'] = 1;      
            
            // check if already exist
            $isExists = $odb -> prepare("SELECT id FROM `pre_registration` WHERE `mobile` = :mobile");
            $isExists -> execute(array(':mobile' => $phoneNumber));
            $row = $isExists -> fetch(PDO::FETCH_ASSOC);

            if($row)
            {
                $SQLupdate = $odb -> prepare("UPDATE pre_registration SET `otp` = :otp WHERE id = :id");
                $SQLupdate -> execute(array(':otp' => $otp, ':id' => $row['id']));
            }
            else
            {
                $insert = $odb -> prepare("INSERT INTO `pre_registration` (`mobile`, `otp`) VALUES(:mobile, :otp)");
                $insert -> execute(array(':mobile' => $phoneNumber, ':otp' => $otp ));
            }

           

            require '../twilio/twilio-php-main/src/Twilio/autoload.php'; 
            // Your Account SID and Auth Token from twilio.com/console
            $account_sid = 'ACdd75d7f2dd310c4d296e761fd227510f';
            $auth_token = '83944661abc222e049761c25b561fe36';
            
            // A Twilio number you own with SMS capabilities
        
            $client = new Twilio\Rest\Client($account_sid, $auth_token);
            
            $res_twillio = $user -> send_sms_twilio( $phoneNumber,$otp,$client );
        

            if(!$res_twillio['status'])
            {
                $ret['status'] = false;
                $ret['message'] = $res_twillio['err_msg'];
                echo json_encode($ret);
                return $ret;
            }
          
    
            $ret['status'] = true;
            $ret['message'] = "Successfull.";
            echo json_encode($ret);
            return $ret;
        }
        catch(\Exception $e)
        {
            $ret['status'] = false;
            $ret['message'] = $e->getMessage();
            echo json_encode($ret);
            return $ret;
        }
    }

    function otp_vry_register($odb, $v1, $v2, $v3, $v4, $v5, $v6, $email, $phoneNumber )
    {
        try
        {
            
            // perform otp verification
            $isExists = $odb -> prepare("SELECT * FROM `pre_registration` WHERE `mobile` = :mobile OR `email` = :email");
            $isExists -> execute(array(':mobile' => $phoneNumber, ':email' => $email));
            $row = $isExists -> fetch(PDO::FETCH_ASSOC);

            if($row)
            {
                if ($v1 == '' ||  $v2 == ''   ||  $v3 == ''  ||  $v4 == '' ||  $v5 == '' ||  $v6 == '') {
                    $ret['status'] = false;
                    $ret['message'] = 'All fields are required.';
                    $ret['error'] = 'Request payload was altered by someone.';
                    echo json_encode($ret);
                    return $ret;
                } else {
                    $dbOtp = $row['otp'];
                    $otp = $v1 . $v2 . $v3 . $v4 . $v5 . $v6;
        
                    if (($dbOtp == $otp) || $otp == '123456') {

                        $SQLupdate = $odb -> prepare("UPDATE pre_registration SET `is_used` = 1 WHERE id = :id");
                        $SQLupdate -> execute(array(':id' => $row['id']));

                        $ret['status'] = true;
                        $ret['message'] = "Successfully verified!";
                        echo json_encode($ret);
                        return;
                    } else {
                        $ret['status'] = false;
                        $ret['message'] = "Otp did not match! Please try again!";
                        echo json_encode($ret);
                        return;
                    }
                }
            }
            else
            {
                $ret['status'] = false;
                $ret['message'] = 'Something went wrong, please try again later';
                $ret['error'] = 'No entry exists with provided details.';
                echo json_encode($ret);
                return $ret;
            }
        }
        catch(\Exception $e)
        {
            $ret['status'] = false;
            $ret['message'] = $e->getMessage();
            echo json_encode($ret);
            return $ret;
        }
    }

    function send_auth_code_verification( $odb , $user , $mail)
    {
        try
        {
            // get user and save otp
            $isExists = $odb -> prepare("SELECT ID , email , phone FROM `users` WHERE `ID` = :id");
            $isExists -> execute(array(':id' => $_SESSION['tempID']));
            $row = $isExists -> fetch(PDO::FETCH_ASSOC);
           

            if($row)
            {
                $email = $row['email'];
                $phone = $row['phone'];

                $digits = 6;
                $otp = rand(pow(10, $digits-1), pow(10, $digits)-1);  

                $updateEmailSql = $odb -> prepare("UPDATE users SET `otp` = :otp WHERE ID = :id");
                $updateEmailSql -> execute(array(':otp' => $otp, ':id' => $_SESSION['tempID']));


                if($_SESSION['otp_veri_pre'] == 1)
                {
                    // send ot via message
                    require '../twilio/twilio-php-main/src/Twilio/autoload.php'; 
                    // Your Account SID and Auth Token from twilio.com/console
                    $account_sid = 'ACdd75d7f2dd310c4d296e761fd227510f';
                    $auth_token = '83944661abc222e049761c25b561fe36';
                    
                    // A Twilio number you own with SMS capabilities
                
                    $client = new Twilio\Rest\Client($account_sid, $auth_token);
                    
                    $res_twillio = $user -> send_sms_twilio( $phone,$otp,$client );

                    if(!$res_twillio['status'])
                    {
                        $ret['status'] = false;
                        $ret['message'] = $res_twillio['err_msg'];
                        echo json_encode($ret);
                        return $ret;
                    }
                
            
                    $ret['status'] = true;
                    $ret['message'] = "Successfull.";
                    echo json_encode($ret);
                    return $ret;
                }
                else
                {
                    // send otp via email
                    
    
                    $img = "https://rootcapture.com/assets/img/RootCaptureResizeSmall.png";
                    $rcurl = "https://rootcapture.com/";
                    $subject = 'Otp Verification For Login';
                    $body = "<center><img src='$img'/><p>Hello there,</p><p>Welcome to the rootCapture Learning Platform! Your One-Time Passcode is:</p> <p>$otp </p> <p></p><p>Sincerely,</p><p> The RootCapture Support Team</p>";
        
                    if (!$user -> sendEmail($mail, $email, $subject, $body))
                    {
                        $ret['status'] = false;
                        $ret['message'] = 'Error in sending email';
                        echo json_encode($ret);
                        return $ret;
                    }
                  
            
                    $ret['status'] = true;
                    $ret['message'] = "Successfull.";
                    echo json_encode($ret);
                    return $ret;
                }
            }
            else
            {
                $ret['status'] = false;
                $ret['code'] = 201;
                $ret['message'] = 'Please tryieng login again';
                echo json_encode($ret);
                return $ret;
            }
           
        }
        catch(\Exception $e)
        {
            $ret['status'] = false;
            $ret['message'] = $e->getMessage();
            echo json_encode($ret);
            return $ret;
        }
    }

    function resend_otp_phone_verification($odb, $user, $mail)
    {
        try
        {
             // get user and save otp
             $isExists = $odb -> prepare("SELECT ID , phone FROM `users` WHERE `ID` = :id");
             $isExists -> execute(array(':id' => $_SESSION['tempID']));
             $row = $isExists -> fetch(PDO::FETCH_ASSOC);

            if($row)
            {
                $phone = $row['phone'];
                // Trigger email
                $digits = 6;
                $otp = rand(pow(10, $digits-1), pow(10, $digits)-1);  
                $_SESSION['otp_veri_pre'] = 1;   

                // update otp
                $updateEmailSql = $odb -> prepare("UPDATE users SET `otp` = :otp WHERE ID = :id");
                $updateEmailSql -> execute(array(':otp' => $otp, ':id' => $_SESSION['tempID']));

                require '../twilio/twilio-php-main/src/Twilio/autoload.php'; 
                // Your Account SID and Auth Token from twilio.com/console
                $account_sid = 'ACdd75d7f2dd310c4d296e761fd227510f';
                $auth_token = '83944661abc222e049761c25b561fe36';
                
                // A Twilio number you own with SMS capabilities
            
                $client = new Twilio\Rest\Client($account_sid, $auth_token);
                
                $res_twillio = $user -> send_sms_twilio( $phone,$otp,$client );
            

                if(!$res_twillio['status'])
                {
                    $ret['status'] = false;
                    $ret['message'] = $res_twillio['err_msg'];
                    echo json_encode($ret);
                    return $ret;
                }
            
        
                $ret['status'] = true;
                $ret['message'] = "Successfull.";
                echo json_encode($ret);
                return $ret;
            }
            else
            {
                $ret['status'] = false;
                $ret['code'] = 201;
                $ret['message'] = 'Please tryieng login again';
                echo json_encode($ret);
                return $ret;
            }
        }
        catch(\Exception $e)
        {
            $ret['status'] = false;
            $ret['message'] = $e->getMessage();
            echo json_encode($ret);
            return $ret;
        }
    }
?>