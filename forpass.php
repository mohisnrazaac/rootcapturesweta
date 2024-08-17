<?php
    ob_start();
    require 'includes/db.php';
    require 'includes/init.php';
    use PHPMailer\PHPMailer\PHPMailer;
    // use PHPMailer\PHPMailer\Exception;
    require './vendor/autoload.php'; 
    $mail = new PHPMailer(true);

    
        /* AJAX check  */
        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
        {

            if(isset($_POST))
            {
                $email = $_POST['email'];

                $message = "";
                $errors = array();
                if (empty($email))
                {
                    $ret['status'] = false;
                    $ret['message'] = "Please enter email";
                    echo json_encode($ret);
                    return;
                }

                if (!filter_var($email, FILTER_VALIDATE_EMAIL))
                {
                    $ret['status'] = false;
                    $ret['message'] = "Invalid Email Address";
                    echo json_encode($ret);
                    return;
                }
                
                if (empty($errors))
                { 
                    // check if email exist
                    $stmp = $odb -> prepare("SELECT id FROM `users` WHERE `email` = :email");
                    $stmp -> execute(array(':email' => $email));
                    $id = $stmp -> fetchColumn(0);

                    if( $id == false )
                    {
                        $ret['status'] = false;
                        $ret['message'] = "Email-id does not exists.";
                        echo json_encode($ret);
                        return;
                    }
                    else
                    {
                        // if  email exist, genereate 6 digit otp for reset password
                        $digits = 6;
                        $otp = rand(pow(10, $digits-1), pow(10, $digits)-1);  

                        try
                        {
                            // insert this otp to users
                            $stmp = $odb -> prepare("UPDATE users SET `forgot_otp` = :forgot_otp where id = :id");
                            $stmp -> execute(array(':forgot_otp' => $otp, ':id' => $id));

                            // send otp for password
                            $img = "https://rootcapture.com/assets/img/RootCaptureResizeSmall.png";
                            $rcurl = "https://rootcapture.com/";
                            $subject = 'Otp to reset password';
                            $body = "<center><img src='$img'/><p>Hello there,</p><p>Welcome to the rootCapture Learning Platform! Your One-Time Passcode is:</p> <p>$otp </p> <p></p><p>Sincerely,</p><p> The RootCapture Support Team</p>";
                            if ($user -> sendEmail($mail, $email, $subject, $body))
                            {
                                $ret['status'] = true;
                                $ret['message'] = 'Please check your email inbox to reset your password!';
                                $ret['email'] = $email;
                                echo json_encode($ret);
                                return;
                            } else
                            {
                                $ret['status'] = false;
                                $ret['message'] = "Error in email process.Please contact a staff or <br/> administrative member to resolve this.";
                                echo json_encode($ret);
                                return;
                            }
                        }
                        catch(Exception $e)
                        {
                            $ret['status'] = false;
                            $ret['message'] = "Something went wrong";
                            $ret['error'] = $e->getMessage();
                            echo json_encode($ret);
                            return;
                        }
                    }
                   


                    $SQLCheckLogin = $odb -> prepare("SELECT COUNT(*) FROM `users` WHERE BINARY  `username` = :username AND `password` = :password");
                    $SQLCheckLogin -> execute(array(':username' => $username, ':password' => SHA1($password)));
                    $countLogin = $SQLCheckLogin -> fetchColumn(0);
                    if ($countLogin == 1)
                    {  
                        $SQLGetInfo = $odb -> prepare("SELECT `username`, `ID`,`status`, `phone`,`email`,`otp_verification_preference`,`banned_msg`  FROM `users` WHERE `username` = :username AND `password` = :password");
                        $SQLGetInfo -> execute(array(':username' => $username, ':password' => SHA1($password)));
                        
                        $userInfo = $SQLGetInfo -> fetch(PDO::FETCH_ASSOC);
                        $status = $userInfo['status'];
                        $phone = $userInfo['phone'];
                        $email = $userInfo['email'];
                        $otp_verification_preference = $userInfo['otp_verification_preference'];
                        $banned_msg = $userInfo['banned_msg'];
                        // $userid = $userInfo['ID'];
                        // $userip = $_SERVER['REMOTE_ADDR'];
                        if ($status == 1)
                        {
                            if(!$banned_msg)
                            {
                                $banned_msg = 'Your account has been banned.<br/> Please contact a staff or <br/>administrative member to resolve this.';
                            }
        
                            $ret['status'] = false;
                            $ret['message'] = $banned_msg;
                            echo json_encode($ret);
                            return;
                        } 
                        elseif ($status == 0)
                        { 
                            $_SESSION['tempUsername'] = $userInfo['username'];
                            $_SESSION['tempID'] = $userInfo['ID'];
                            // if phone number not available
                            if($userInfo['otp_verification_preference']==2 ){  
                                //email otp
                                $digits = 6;
                                $otp = rand(pow(10, $digits-1), pow(10, $digits)-1);
                        
                                $img = "https://rootcapture.com/assets/img/RootCaptureResizeSmall.png";
                                $rcurl = "https://rootcapture.com/";
                                $subject = 'The rootCapture Support Team';
                                $body = "<center><img src='$img'/><p>Hello there,</p><p>Welcome to the rootCapture Learning Platform! Your One-Time Passcode is:</p> <p>$otp </p> <p></p><p>Sincerely,</p><p> The RootCapture Support Team</p>";
        
                                //  $headers  = "From:  <support@rootcapture.com>\n";
                                //  $headers .= "MIME-Version: 1.0\r\n";
                                //  $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
                                //  mail($email, "Otp Verification", $html, $headers);
                                if ($user -> sendEmail($mail, $email, $subject, $body))
                                {
                                    if( !$otp_verification_preference )
                                    {
                                        $_SESSION['otp_veri_pre'] = 2;
                                    }
            
                                    $sucMsg = 'LOGIN SUCCESSFUL. REDIRECTING....';

                                    $updateEmailSql = $odb -> prepare("UPDATE users SET `otp` = :otp WHERE ID = :id");
                                    $updateEmailSql -> execute(array(':otp' => $otp, ':id' => $userInfo['ID']));

                                    $ret['status'] = true;
                                    $ret['message'] = $sucMsg;
                                    echo json_encode($ret);
                                    return;
                                } else
                                {
                                    $ret['status'] = false;
                                    $ret['message'] = "Error in email process.Please contact a staff or <br/> administrative member to resolve this.";
                                    echo json_encode($ret);
                                    return;
                                }
        
                                

                            }  else {  
                            
                                // otp send
                                $digits = 6;
                                $otp = rand(pow(10, $digits-1), pow(10, $digits)-1);  

                                require './twilio/twilio-php-main/src/Twilio/autoload.php'; 
                                // Your Account SID and Auth Token from twilio.com/console
                                $account_sid = 'ACdd75d7f2dd310c4d296e761fd227510f';
                                $auth_token = '83944661abc222e049761c25b561fe36';
                                
                                // A Twilio number you own with SMS capabilities
                                
                                $client = new Twilio\Rest\Client($account_sid, $auth_token);
                                
                                $return = $user -> send_sms_twilio( $phone,$otp,$client );
                                

                                if($return['status'])
                                {
                                    if( !$otp_verification_preference ) {
                                    $_SESSION['otp_veri_pre'] = 1;
                                    }
                                    $sucMsg = 'LOGIN SUCCESSFUL. REDIRECTING....';            
                                    $updateEmailSql = $odb -> prepare("UPDATE users SET `otp` = :otp WHERE ID = :id");
                                    $updateEmailSql -> execute(array(':otp' => $otp, ':id' => $userInfo['ID']));

                                    $ret['status'] = true;
                                    $ret['message'] = $sucMsg;
                                    echo json_encode($ret);
                                    return;
                                }
                                else
                                {
                                    $ret['status'] = false;
                                    $ret['message'] = $return['err_msg'];
                                    echo json_encode($ret);
                                    return;
                                }
                            }
                            
                            
                            
                        }  
                        elseif ($status == 2)
                        {
                            $ret['status'] = false;
                            $ret['message'] = "Your account has been deleted.<br/> Please contact a staff or <br/> administrative member to resolve this.";
                            echo json_encode($ret);
                            return;
                        }    
                                
                    }
                    else
                    {
                        $ret['status'] = false;
                        $ret['message'] = "Login Failed! <br/> Please check your credentials <br/> and try again!";
                        echo json_encode($ret);
                        return;
                    }
                }
               
            }

        }
    require_once('animated/common/header.php'); 
 ?>
    <div class="video-background" id="forgot_password1">
	
		<video autoplay muted id="myVideo" preload="auto">
            <source id="videoSource" src="./animated/assets/vids/Password Reset 2.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>
		
 
        <div id="left_alignment_html_content" >

	
	
	
            <!-- <div class="container"> -->
                <!-- <div class="row d-flex flex-row justify-content-center align-items-center"> -->
                    <!-- <div class="col-xxl-2 col-xl-1 col-md-0 col-2 col-sm-1"></div> -->
                    <!-- <div class="col-xxl-4 col-xl-4 col-md-5 col-8 col-sm-7 pass_reset_2_background py-5 px-5" id="pass_reset_2_background"> -->
                    <div class=" pass_reset_background py-4 px-3" id="pass_reset_2_background">
                        <h3>Password Reset</h3>
                        <p id="succMsg">Enter Your Email To Recover Your Account</p>
                        <h6 class="mt-2">Email</h6>
                        <input type="text" id="email" class="email_input">
                        <button class="mt-2 continue_btn pass_reset_2_btn" id="forgot_password" >
                        </button>
                    </div>
                    <!-- <div class="col-xxl-6 col-xl-7 col-md-6 col-2 col-sm-1"></div> -->
                <!-- </div> -->
            <!-- </div> -->
        </div>
    </div>

    <div class="video-background" id="authenticate_video" style="display: none; background-color: black;">
        <video autoplay muted id="myVideo2" preload="auto">
            <source src="./animated/assets/vids/Description 07_me Comp 1.mp4" type="video/mp4" >
            Your browser does not support the video tag.
        </video>
    </div>

    <div class="video-background" id="error_video" style="display: none; background-color: black;">
	
        <div class="login_error_text d-flex flex-column justify-content-center align-items-center">
        <video autoplay muted id="myVideo3" preload="auto" >
            <source src="./animated/assets/vids/login error.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>
	 
		<div class="d-flex flex-row justify-content-center align-items-center login_error_h_holder">
		  <h4 class="login_error_h" id="demo">
		  <!--Password Did Not Match Security Requirements<br><span style="color: #00e4ff">REASONS:</span><br>Case Sensative<br>8 Characters Needed-->
		  </h4>
		</div>
		
        </div>

    </div>

    <div class="video-background" id="success_video" style=" display: none; background-color: black;">
        <video autoplay muted id="myVideo4" preload="auto"style="object-fit: contain; width: 100%; height: auto; top: 50%; left: 50%; transform: translate(-50%,-50%);">
            <source src="./animated/assets/vids/login success.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>
    </div>

	<a class="gear_icon" onclick="showPop()"><i class="fa-solid fa-gear fa-2x"></i></a>

    <script> 
	
		function isMobileDevice() {
            return /iPhone|iPad|iPod|Android|webOS|BlackBerry|Windows Phone/i.test(navigator.userAgent);
        }

        function isSmallScreen() {
            return window.innerWidth < 768; // You can adjust this threshold as needed
        }

        document.addEventListener("DOMContentLoaded", function () {
            var video = document.getElementById("myVideo");
            var videoSource = document.getElementById("videoSource");
            var leftAlignmentHtmlContent = document.getElementById("left_alignment_html_content");

            if (isMobileDevice() && isSmallScreen()) {
                // Update video source for mobile devices and small screens
                videoSource.src = "./assets/vids/BG_Password_Reset_2_Mobile.mp4";
                // Reload the video to apply the new source
                video.load();

                // Apply styling to the div
                leftAlignmentHtmlContent.classList.add("mobile_pass1_page");
            }
        });



     document.addEventListener("DOMContentLoaded", function () {
        
	  var htmlContent = document.getElementById('left_alignment_html_content');
		

      const video = document.getElementById("myVideo");
      video.autoplay = true;
      video.muted = true;
    
    video.addEventListener("ended", handleVideoEnded);

    function handleVideoEnded() {
        video.pause();
        video.currentTime = 6;
        video.play();
      }
 


  
 function setupPage() {
             var htmlContent = document.getElementById('left_alignment_html_content');
             const video = document.getElementById("myVideo"); 
             const toggleText = document.getElementById("toggleText1");
             const slider = document.querySelector(".slider1");

             video.autoplay = true;
             video.muted = true;    
            if( getCookie("user_cookie_animation") == 0 || getCookie("user_cookie_animation") == '' )
            {
                htmlContentTimeout = 500;
                toggleText.textContent = "OFF";
                slider.style.transform = "translateX(0)";
                toggleText.style.transform = "translate(0,-50%)";

                video.addEventListener('play', function() {
                    setTimeout(() => {
                        video.currentTime = 7;
                        video.pause();
                    }, 500);
                        
                });
            }
            else
            {
                toggleText.textContent = "ON";
                slider.style.transform = "translateX(100%)";
                toggleText.style.transform = "translate(-50%,-50%)";
                video.addEventListener("ended", handleVideoEnded);
            }
         
             function handleVideoEnded() {
                 video.pause();
                 video.currentTime = 6;
                 video.play();
             }
         	 
          
		video.addEventListener('canplaythrough', function () { 
		console. log(window.innerWidth+'x'+window.innerHeight);
		var startingPixelX = video.getBoundingClientRect().left; 
		var vwValue;  
         
         if (window.devicePixelRatio == 1 && window.innerWidth < 1280) {
             vwValue = 17; // For x-small screens DONE  
         } else if (window.devicePixelRatio == 1.75 && window.innerWidth < 1300 && window.innerHeight < 500) { //1090*470*1.75
             vwValue = 12.8; // For 1920x 175%   
         } else if (window.devicePixelRatio == 1.5 && window.innerWidth < 1300 && window.innerHeight > 500 && window.innerHeight < 600) { //1272*572*1.5
             vwValue = 13.4; // For 1920x 150%   
			 
         } else if (window.devicePixelRatio == 1.5 && window.innerWidth < 1300 && window.innerHeight > 600 && window.innerHeight < 650 ) { //1280*638*1.5
             vwValue = 14.8; // For 1920x 150%   SRM
         } else if (window.devicePixelRatio == 1.5 && window.innerWidth < 1300 && window.innerHeight > 650 && window.innerHeight < 700 ) { //1280*665*1.5
             vwValue = 15.4; // For 1920x 150%  Brandon
		 // alert('2');
         } else if (window.devicePixelRatio == 1.5 && window.innerWidth < 1300 && window.innerHeight > 700 && window.innerHeight < 730 ) { //1280*638*1.5
             vwValue = 16.8; // For 1920x 150%    SRM
     
		 
		 } else if ((window.devicePixelRatio == 1 || window.devicePixelRatio == 1.25) && window.innerWidth < 1366 && window.innerHeight < 720) {
             vwValue = 15; // For 1280x800  
         } else if (window.innerWidth < 1366 && window.innerHeight > 720) {
             vwValue = 18.5; // For 1280x960  
         } else if (window.devicePixelRatio !== 1.75 && window.innerWidth < 1440) {
             vwValue = 13.25; // For 1366x768 DONE  
         } else if (window.devicePixelRatio !== 1.75 && window.innerWidth < 1530 && window.innerHeight < 720) { //1528*716*1.25
             vwValue = 14; // For 1920x 125%  
         } else if (window.devicePixelRatio == 1.25 && window.innerWidth > 1530 && window.innerHeight > 720 && window.innerHeight < 740) { //1536*738*1.25
             vwValue = 14.2; // For 1920x 125%  SRM 
         } else if (window.devicePixelRatio == 1.25 && window.innerWidth > 1530 && window.innerHeight > 740 && window.innerHeight < 800) { //1536*785*1.25
             vwValue = 15.1; // For 1920x 125%   17" 
         } else if (window.devicePixelRatio == 1.25 && window.innerWidth > 1530 && window.innerHeight > 800 && window.innerHeight < 860) { //1536*826*1.25
             vwValue = 15.9; // For 1920x 125%   17"   
         } else if (window.devicePixelRatio == 1.25 && window.innerWidth > 1530 && window.innerHeight > 860) { //1536*864*1.25
             vwValue = 16.7; // For 1920x 125%   17"   
         } else if (window.devicePixelRatio == 1.25 && window.innerWidth > 1530 && window.innerHeight < 720) { //1536x703*1.25 with bookmark
             vwValue = 13.7; // For 1920x 125%    
         } else if (window.devicePixelRatio !== 1.75 && window.innerWidth < 1536) {
             vwValue = 15.3; // For 1440x900 DONE  
         } else if (window.devicePixelRatio !== 1.75 && window.innerWidth < 1600) {
             vwValue = 16; // For 1536x864 DONE   
         } else if (window.devicePixelRatio !== 1.75 && window.innerWidth < 1920) {
             vwValue = 14.5; // For 1600x900 DONE    
         } else if (window.devicePixelRatio == 1 && window.innerWidth < 1921 && window.innerHeight < 920) { //1920*920*1.0 w/o bookmark
             vwValue = 14.1; // For 1920x 100%  SRM  
         } else if (window.devicePixelRatio == 1 && window.innerWidth < 1921 && window.innerHeight > 920) { //1920*920*1.0 w/o bookmark
             vwValue = 16.0; // For 1920x 100%  SRM  
         } else if (window.devicePixelRatio == 1.25 && window.innerWidth < 1921 && window.innerHeight > 920) { //1920*1065*1.25 
             vwValue = 16.7; // For 1920x1065 17" monitor 125%   
         } else {
             vwValue = 14.7; // For 1920x1080 and larger screens DONE    
         }
         
         		
                 setTimeout(function () {
                     htmlContent.style.display = 'block';
                     htmlContent.style.marginLeft = 'calc(' + vwValue + 'vw + ' + startingPixelX + 'px)';
                     //htmlContent.style.marginTop = videoHeight / ( vhMargin) + 'vh
         			
          if ( (window.devicePixelRatio==1.5) && (window.innerWidth < 1300 && window.innerHeight > 500 && window.innerHeight < 650)) { /*1272*572*1.5*/ htmlContent.style.marginTop = '27vh'; }
		  else if (window.devicePixelRatio == 1.5 && window.innerWidth < 1300 && window.innerHeight > 650 && window.innerHeight < 700 ) { //1280*665*1.5
			htmlContent.style.marginTop = '30.5vh';}
		  else if (window.devicePixelRatio == 1.75 && window.innerWidth > 1100 && window.innerWidth < 1300 && window.innerHeight < 500) { /*1090*470*1.75*/ htmlContent.style.marginTop = '15vh'; }
		  else if (window.devicePixelRatio == 1.75 && window.innerWidth < 1100 && window.innerHeight > 550) { /*1098*551*1.75*/ htmlContent.style.marginTop = '30vh'; }
		  else {htmlContent.style.marginTop = '33vh';}
		  }, 2200);
             });
         
             // Fallback in case the 'canplaythrough' event is not supported
             setTimeout(function () {
                 var startingPixelX = video.getBoundingClientRect().left;
              //   var videoHeight = video.getBoundingClientRect().height;
                 var vwValue;
         
         if (window.devicePixelRatio == 1 && window.innerWidth < 1280) {
             vwValue = 17; // For x-small screens DONE  
         } else if (window.devicePixelRatio == 1.75 && window.innerWidth < 1300 && window.innerHeight < 500) { //1090*470*1.75
             vwValue = 12.8; // For 1920x 175%   
         } else if (window.devicePixelRatio == 1.5 && window.innerWidth < 1300 && window.innerHeight > 500 && window.innerHeight < 600) { //1272*572*1.5
             vwValue = 13.4; // For 1920x 150%   
         } else if (window.devicePixelRatio == 1.5 && window.innerWidth < 1300 && window.innerHeight > 600 && window.innerHeight < 650 ) { //1280*638*1.5
             vwValue = 14.8; // For 1920x 150%   SRM
         } else if (window.devicePixelRatio == 1.5 && window.innerWidth < 1300 && window.innerHeight > 650 && window.innerHeight < 700 ) { //1280*665*1.5
             vwValue = 15.4; // For 1920x 150%  Brandon
         } else if (window.devicePixelRatio == 1.5 && window.innerWidth < 1300 && window.innerHeight > 700 && window.innerHeight < 730 ) { //1280*638*1.5
             vwValue = 16.8; // For 1920x 150%    SRM
		 } else if ((window.devicePixelRatio == 1 || window.devicePixelRatio == 1.25) && window.innerWidth < 1366 && window.innerHeight < 720) {
             vwValue = 15; // For 1280x800  
         } else if (window.innerWidth < 1366 && window.innerHeight > 720) {
             vwValue = 18.5; // For 1280x960  
         } else if (window.devicePixelRatio !== 1.75 && window.innerWidth < 1440) {
             vwValue = 13.25; // For 1366x768 DONE  
         } else if (window.devicePixelRatio !== 1.75 && window.innerWidth < 1530 && window.innerHeight < 720) { //1528*716*1.25
             vwValue = 14; // For 1920x 125%  
         } else if (window.devicePixelRatio == 1.25 && window.innerWidth > 1530 && window.innerHeight > 720 && window.innerHeight < 740) { //1536*738*1.25
             vwValue = 14.2; // For 1920x 125%  SRM 
         } else if (window.devicePixelRatio == 1.25 && window.innerWidth > 1530 && window.innerHeight > 740 && window.innerHeight < 800) { //1536*785*1.25
             vwValue = 15.1; // For 1920x 125%   17" 
         } else if (window.devicePixelRatio == 1.25 && window.innerWidth > 1530 && window.innerHeight > 800 && window.innerHeight < 860) { //1536*826*1.25
             vwValue = 15.9; // For 1920x 125%   17"   
         } else if (window.devicePixelRatio == 1.25 && window.innerWidth > 1530 && window.innerHeight > 860) { //1536*864*1.25
             vwValue = 16.7; // For 1920x 125%   17"   
         } else if (window.devicePixelRatio == 1.25 && window.innerWidth > 1530 && window.innerHeight < 720) { //1536x703*1.25 with bookmark
             vwValue = 13.7; // For 1920x 125%    
         } else if (window.devicePixelRatio !== 1.75 && window.innerWidth < 1536) {
             vwValue = 15.3; // For 1440x900 DONE  
         } else if (window.devicePixelRatio !== 1.75 && window.innerWidth < 1600) {
             vwValue = 16; // For 1536x864 DONE   
         } else if (window.devicePixelRatio !== 1.75 && window.innerWidth < 1920) {
             vwValue = 14.5; // For 1600x900 DONE    
         } else if (window.devicePixelRatio == 1 && window.innerWidth < 1921 && window.innerHeight < 920) { //1920*920*1.0 w/o bookmark
             vwValue = 14.1; // For 1920x 125%  SRM  
         } else if (window.devicePixelRatio == 1 && window.innerWidth < 1921 && window.innerHeight > 920) { //1920*920*1.0 w/o bookmark
             vwValue = 16.0; // For 1920x 105%  SRM  
         } else if (window.devicePixelRatio == 1.25 && window.innerWidth < 1921 && window.innerHeight > 920) { //1920*1065*1.25 
             vwValue = 16.7; // For 1920x1065 17" monitor 125%   
         } else {
             vwValue = 14.7; // For 1920x1080 and larger screens DONE    
         }
         
         
		 setTimeout(function () {
			 htmlContent.style.display = 'block';
			 htmlContent.style.marginLeft = 'calc(' + vwValue + 'vw + ' + startingPixelX + 'px)';
			 //htmlContent.style.marginTop = videoHeight / ( vhMargin) + 'vh
          if ( (window.devicePixelRatio==1.5) && (window.innerWidth < 1300 && window.innerHeight > 500 && window.innerHeight < 650)) { /*1272*572*1.5*/ htmlContent.style.marginTop = '27vh'; }
		  else if (window.devicePixelRatio == 1.5 && window.innerWidth < 1300 && window.innerHeight > 650 && window.innerHeight < 700 ) { //1280*665*1.5
			htmlContent.style.marginTop = '30.5vh';}
		  else if (window.devicePixelRatio == 1.75 && window.innerWidth > 1100 && window.innerWidth < 1300 && window.innerHeight < 500) { /*1090*470*1.75*/ htmlContent.style.marginTop = '15vh'; }
		  else if (window.devicePixelRatio == 1.75 && window.innerWidth < 1100 && window.innerHeight > 550) { /*1098*551*1.75*/ htmlContent.style.marginTop = '30vh'; }
		  else {htmlContent.style.marginTop = '33vh';}
		  }, 2200);
             }, 2200);
} // Custom Responsive Core function
         
 document.addEventListener("DOMContentLoaded", function () {
	 setupPage(); // Call the setup function on DOMContentLoaded as a fallback
 });
 
 window.onload = function () {
	 setupPage(); // Call the setup function when the window has fully loaded
 };
  

	  
	 /* 

    video.addEventListener('loadedmetadata', function () {
      var startingPixelX = video.getBoundingClientRect().left;
      var videoHeight = video.getBoundingClientRect().height;

      setTimeout(function () {
        htmlContent.style.display = 'block';
        htmlContent.style.marginLeft = 'calc(15.5vw + ' + startingPixelX + 'px)';
        htmlContent.style.marginTop = videoHeight / 25 + 'vh';
      }, 2000); 
    }); */
	
            var myDiv = document.getElementById("pass_reset_2_background");
        setTimeout(function() {
          myDiv.style.opacity = 0.7;
        }, 1600);
        setTimeout(function() {
          myDiv.style.opacity = 0.4;
        }, 1800);
        setTimeout(function() {
          myDiv.style.opacity = 0;
        }, 2000);
        setTimeout(function() {
          myDiv.style.opacity = 0.5;
        }, 2100);
        setTimeout(function() {
          myDiv.style.opacity = 0;
        }, 2200);
        setTimeout(function() {
          myDiv.style.opacity = 0.5;
        }, 2300);
        setTimeout(function() {
          myDiv.style.opacity = 0;
        }, 2400);
        setTimeout(function() {
          myDiv.style.opacity = 0.5;
        }, 2500);
        setTimeout(function() {
          myDiv.style.opacity = 0.85;
        }, 3500);
        setTimeout(function() {
          myDiv.style.opacity = 0.5;
        }, 3600);
        setTimeout(function() {
          myDiv.style.opacity = 0.8;
        }, 3700);
        setTimeout(function() {
          myDiv.style.opacity = 1;
        }, 3800);

    });
    </script>
    <?php require_once('animated/common/footer.php') ?>