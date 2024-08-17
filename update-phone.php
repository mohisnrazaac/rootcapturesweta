<?php
    ob_start();
    require 'includes/db.php';
    require 'includes/init.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>rootCapture - Sign In</title>
    <link rel="icon" type="image/x-icon" href="../src/assets/img/favicon.ico"/>
    <link href="../layouts/vertical-light-menu/css/light/loader.css" rel="stylesheet" type="text/css" />
    <link href="../layouts/vertical-light-menu/css/dark/loader.css" rel="stylesheet" type="text/css" />
    <script src="../layouts/vertical-light-menu/loader.js"></script>
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
    <link href="../src/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="../css/alter.css" rel="stylesheet" type="text/css" />
    <link href="../layouts/vertical-light-menu/css/light/plugins.css" rel="stylesheet" type="text/css" />
    <link href="../src/assets/css/light/authentication/auth-boxed.css" rel="stylesheet" type="text/css" />
    <link href="../layouts/vertical-light-menu/css/dark/plugins.css" rel="stylesheet" type="text/css" />
    <link href="../src/assets/css/dark/authentication/auth-boxed.css" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->
    
</head>
<body class="form">

    <!-- BEGIN LOADER -->
    <div id="load_screen"> <div class="loader"> <div class="loader-content">
        <div class="spinner-grow align-self-center"></div>
    </div></div></div>
    <!--  END LOADER -->

       <!--  BEGIN NAVBAR  -->
    <div class="outerPagesHeader">
        <div class="header navbar navbar-expand-sm expand-header">

      
         

            <ul class="navbar-item flex-row ms-lg-auto ms-0">

           

                <li class="nav-item theme-toggle-item">
                    <a href="javascript:void(0);" class="nav-link theme-toggle">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-moon dark-mode"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-sun light-mode"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>
                    </a>
                </li>

            
            </ul>
        </div>
    </div>
    <!--  END NAVBAR  -->
    <div class=" " id="container">
        
        <div class="overlay"></div>
        <div class="search-overlay"></div>

    <div class="auth-container d-flex">
        <div class="container mx-auto align-self-center">
    
            <div class="row">
    
                <div class="col-xxl-4 col-xl-5 col-lg-5 col-md-8 col-12 d-flex flex-column align-self-center mx-auto">
                    <div class="card mt-3 mb-3">
                        <div class="card-body">
    
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <div align="Center"><h2>Welcome to rootCapture!</h2></div>
									<div class="logo" align="center"><img src="/assets/img/RootCapture0.png"></div>
                                    
                                    
                                </div>

								<form action='update-phone.php' method='POST'>
<?php
if (!($user -> LoggedIn()))
{  
    $resend = $_GET['resend'];
    $via = $_GET['via'];
    if( $resend && $via == 'phone' )
    {
        $SQLGetInfo = $odb -> prepare("SELECT `phone`,`otp_verification_preference` FROM `users` WHERE `id` = :id");
        $SQLGetInfo -> execute(array(':id' => $_SESSION['tempID']));        
        $userInfo = $SQLGetInfo -> fetch(PDO::FETCH_ASSOC);
        $phone = $userInfo['phone'];
        $otp_verification_preference = $userInfo['otp_verification_preference'];
        if( $phone == NULL || $phone == "" )
        {
           //
        }
        else
        {
             // otp send
            $digits = 4;
            $otp = rand(pow(10, $digits-1), pow(10, $digits)-1);
            require './twilio/twilio-php-main/src/Twilio/autoload.php'; 
            // Your Account SID and Auth Token from twilio.com/console
            $account_sid = 'ACdd75d7f2dd310c4d296e761fd227510f';
            $auth_token = '83944661abc222e049761c25b561fe36';
        
            // A Twilio number you own with SMS capabilities
        
            $client = new Twilio\Rest\Client($account_sid, $auth_token);
            
            $result = $user -> send_sms_twilio( $phone,$otp,$client );
        
    
            if($result['status'])
            {
                $updateEmailSql = $odb -> prepare("UPDATE users SET `otp` = :otp WHERE id = :id");
                $updateEmailSql -> execute(array(':otp' => $otp, ':id' => $_SESSION['tempID']));
                if( !$otp_verification_preference )
                {
                    $_SESSION['otp_veri_pre'] = 1;
                }

                echo '<div class="message" id="message"><p><strong>SUCCESS: </strong>Successful.  Redirecting....</p></div><meta http-equiv="refresh" content="3;url=verification.php">';
                }
            else
            {
                echo '<div class="error" ><p><strong>ERROR: </strong>'.$result['err_msg'].'</p></div>';
            }
        }
    }
    else if( $resend && $via == 'email' )
    {
        $SQLGetInfo = $odb -> prepare("SELECT `email`,`otp_verification_preference` FROM `users` WHERE `id` = :id");
        $SQLGetInfo -> execute(array(':id' => $_SESSION['tempID']));        
        $userInfo = $SQLGetInfo -> fetch(PDO::FETCH_ASSOC);
        $email = $userInfo['email'];
        $otp_verification_preference = $userInfo['otp_verification_preference'];

        //email otp
        $digits = 4;
        $otp = rand(pow(10, $digits-1), pow(10, $digits)-1);

        $img = "https://rootcapture.com/assets/img/RootCaptureResizeSmall.png";
        $rcurl = "https://rootcapture.com/";
        $html = "<center><img src='$img'/><p>Hello there,</p><p>Welcome to the rootCapture Learning Platform! Your One-Time Passcode is:</p> $otp </p> <p> Support Team</p>";

        $headers  = "From: The rootCapture Support Team <support@rootcapture.com>\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        mail($email, "Otp Verification", $html, $headers);

        $updateEmailSql = $odb -> prepare("UPDATE users SET `otp` = :otp WHERE id = :id");
        $updateEmailSql -> execute(array(':otp' => $otp, ':id' => $_SESSION['tempID']));  

        if( !$otp_verification_preference )
        {
            $_SESSION['otp_veri_pre'] = 2;
        }

        echo '<div class="message" id="message"><p><strong>SUCCESS: </strong>Please check your inbox and enter the provided otp....</p></div><meta http-equiv="refresh" content="3;url=verification.php">';
    }

	if (isset($_POST['phoneUpdate']))
	{    
		$phone = $_POST['phone'];
		$errors = array();
		
		if (empty($phone))
		{
			$errors[] = 'Please enter mobile number.';
		}
		
		if (empty($errors))
		{
            if( !empty($phone) )
            {
                $SQLGetInfo = $odb -> prepare("SELECT `otp_verification_preference` FROM `users` WHERE `id` = :id");
                $SQLGetInfo -> execute(array(':id' => $_SESSION['tempID']));        
                $userInfo = $SQLGetInfo -> fetch(PDO::FETCH_ASSOC);
                $otp_verification_preference = $userInfo['otp_verification_preference'];

                $updatePhoneSql = $odb -> prepare("UPDATE users SET `phone` = :phone WHERE id = :id");
                $updatePhoneSql -> execute(array(':phone' => $phone,':id' => $_SESSION['tempID']));

                $digits = 4;
                $otp = rand(pow(10, $digits-1), pow(10, $digits)-1);
                // otp send
                require './twilio/twilio-php-main/src/Twilio/autoload.php'; 
                // Your Account SID and Auth Token from twilio.com/console
                $account_sid = 'ACdd75d7f2dd310c4d296e761fd227510f';
                $auth_token = '83944661abc222e049761c25b561fe36';
            
                // A Twilio number you own with SMS capabilities
            
                $client = new Twilio\Rest\Client($account_sid, $auth_token);
                
                $result = $user -> send_sms_twilio( $phone,$otp,$client );

                if($result['status'])
                {
                    $updatePhoneSql = $odb -> prepare("UPDATE users SET `otp` = :otp WHERE id = :id");
                    $updatePhoneSql -> execute(array(':otp' => $otp, ':id' => $_SESSION['tempID'])); 

                    if( !$otp_verification_preference )
                    {
                        $_SESSION['otp_veri_pre'] = 1;
                    }
    
                    echo '<div class="message" id="message"><p><strong>SUCCESS: </strong>Successful.  Redirecting you to verify otp....</p></div><meta http-equiv="refresh" content="3;url=verification.php">';
                }
                else
                {
                    echo '<div class="error" ><p><strong>ERROR: </strong>'.$result['err_msg'].'</p></div>';
                }

               
            }
         
            

		
		}
		else
		{
			echo '<div class="error" id="message"><p><strong>ERROR:</strong><br />';
			foreach($errors as $error)
			{
				echo ''.$error.'<br />';
			}
			echo '</div>';
		}
	}
  
}
else
{
	header('location: index.php');
}
?>
                       <div class="col-md-12">                       
                            <div align="Center"><p>Enter your phone number for 2-factor authentication</p></div>
                                <div class="mb-3">
                                    <label class="form-label">Phone</label>
                                    <input type="text" id='phone' onkeypress="return isNumberKey(event)" name="phone" class="form-control">
                                </div>                                  
                            </div>
                           
                            <div class="col-12">
                                <div class="mb-4">
                                    <button class="btn btn-secondary w-100" input type="submit" value="Update" name="phoneUpdate"> Update</button>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="text-center">
                                    <p class="mb-0">Didn't receive the code ? </p>
                                    <p><a href="update-phone.php?resend=1&via=email" class="text-warning">Resend via  email </a><span class="bar-mid">|</span><a href="update-phone.php?resend=1&via=phone" class="text-warning">Resend via  phone </a> </p>
                                   
                                </div>
                            </div>

                            </div>                       
                        </div>
                    </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    
    <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
     <script src="../src/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../src/plugins/src/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="../src/plugins/src/mousetrap/mousetrap.min.js"></script>
    <script src="../layouts/vertical-light-menu/app.js"></script>
    <!-- END GLOBAL MANDATORY SCRIPTS -->
    <script>
        function isNumberKey(evt){
                var charCode = (evt.which) ? evt.which : evt.keyCode
                if (charCode > 31 && (charCode < 48 || charCode > 57))
                    return false;
                return true;
        }

        $(document).ready(function() {
            $('#phone').blur( function(){
                var phone = $('#phone').val(); 
                if (!(phone.length >= 10 && phone.length <= 14) ) { 
                    $('#phone').val('');
                    $('#phone').attr('placeholder','Phone length cannot be less than 10 or greater than 14 digit.');
                }
            } );

        })

    </script>

</body>
</html>