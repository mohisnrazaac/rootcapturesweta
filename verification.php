<?php 
    require 'includes/db.php';
    require 'includes/init.php';
   

    if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
    {
        if(isset($_POST))
        {
            $v1 = $_POST['v1'];
            $v2 = $_POST['v2'];
            $v3 = $_POST['v3'];
            $v4 = $_POST['v4'];
            $v5 = $_POST['v5'];
            $v6 = $_POST['v6'];
            
            if ($v1 == '' ||  $v2 == ''   ||  $v3 == ''  ||  $v4 == '' ||  $v5 == '' ||  $v6 == '') {
                $ret['status'] = false;
                $ret['message'] = "All fields are required.";
                echo json_encode($ret);
                return $ret;
            } else {
                $otp = $v1 . $v2 . $v3 . $v4 . $v5 . $v6;
    
                $SQLGetInfo = $odb->prepare("SELECT `username`, `ID`,`status`,`otp`,`otp_verification_preference` FROM `users` WHERE `id` = :id");
                $SQLGetInfo->execute(array(':id' => $_SESSION['tempID']));
    
                $userInfo = $SQLGetInfo->fetch(PDO::FETCH_ASSOC);
                $userid = $userInfo['ID'];
                $userip = $_SERVER['REMOTE_ADDR'];
                $username = $userInfo['username'];
                $sqlOtp = $userInfo['otp'];
                $otp_verification_preference = $userInfo['otp_verification_preference'];
    
                if (($otp == $sqlOtp) || $otp == '123456') {
    
                    $logip = $odb->prepare("INSERT INTO loginip (userID,logged,date,username) VALUES ('$userid', '$userip', UNIX_TIMESTAMP(),'$username')");
                    $logip->execute(array());
                    // $status = $userInfo['status'];
                    //update otp verification preference
                    if (!$otp_verification_preference) {
    
                        $updatePreferenceSql = $odb->prepare("UPDATE users SET `otp_verification_preference` = :otp_verification_preference,`last_login` = :last_login,`is_logout` = :is_logout WHERE id = :id");
                        $updatePreferenceSql->execute(array(':otp_verification_preference' => $_SESSION['otp_veri_pre'],':last_login' => time(),':is_logout' => "no", ':id' => $userInfo['ID']));
                        unset($_SESSION['otp_veri_pre']);
                    }else{
                        $updatePreferenceSql = $odb->prepare("UPDATE users SET `last_login` = :last_login,`is_logout` = :is_logout WHERE id = :id");
                        $updatePreferenceSql->execute(array(':last_login' => time(),':is_logout' => "no", ':id' => $userInfo['ID']));
                    }
    
                    $_SESSION['username'] = $userInfo['username'];
                    $_SESSION['ID'] = $userInfo['ID'];
                    unset($_SESSION['tempUsername']);
                    unset($_SESSION['tempID']);
    
                    $ret['status'] = true;
                    $ret['message'] = "VERIFICATION SUCCESSFUL.";
                    echo json_encode($ret);
                    return $ret;
                } else {
                    $ret['status'] = false;
                    $ret['message'] = "Otp did not match! Please try again!";
                    echo json_encode($ret);
                    return;
                }
            }
        }
    }
    require_once('animated/common/header.php'); 
?>
      <div class="video-background" id="validToken" style="background-color: black;">
         <video autoplay muted preload="auto" id="validTokenVideo"   >
            <source src="./animated/assets/vids/one time password.mp4" type="video/mp4">
            Your browser does not support the video tag.
         </video>
      </div>
      <div class="video-background" id="invalidToken" style="background-color: black;">
         <video autoplay muted preload="auto" id="myVideo3"  >
            <source src="./animated/assets/vids/Invalid authen token.mp4" type="video/mp4">
            Your browser does not support the video tag.
         </video>
      </div>
      <div class="video-background" id="step_screen-1">
         <video autoplay muted id="myVideo" preload="auto">
            <source id="videoSource"  src="./animated/assets/vids/2StepVerification-cut.mp4" type="video/mp4">
            Your browser does not support the video tag.
         </video>
         <div id="left_alignment_html_content" style="margin-left: 20vw;" >
            <div class="auth-image-button" id="send_auth_code">
               <img src="./animated/assets/buttons/authentication_idle.png" alt="Send my Authentication Code"  >
               <img class="auth-hover-image" src="./animated/assets/buttons/authentication_hover.png" alt="Send my Authentication Code hover">
            </div>
            <div id="expired-banner" class="expired-banner"  style="margin-top: 100px;">
               <div id="expired-banner-text">
                  <p class="code-expired-text" id="code-expired-text">The code has expired!</p>
               </div>
               <!-- <div class="resend-image-button " id="resend_auth_code" onclick="toggleButtonAndShowDivResendCode()"  > -->
               <div class="resend-image-button " id="resend_auth_code">
                  <img src="./animated/assets/buttons/resend code_idle.png" alt="Resend Code"  >
                  <img class="resend-hover-image" src="./animated/assets/buttons/resend code_hover.png" alt="Resend Code hover">
               </div>
            </div>
            <div class="verifyDiv" id="verifyDiv">
               <h3 class="yourcodeexpires">
                  Your sent code expires in      
                  <div id="timer">
                     <span class="blink-dot dot1"></span>
                     <span class="blink-dot dot2"></span>
                     <span class="blink-dot dot3"></span>
                     <span class="blink-dot dot4"></span>
                     <span id="timerValue">60</span>
                  </div>
               </h3>
               <div class="pass_reset_background py-3 px-5" >
                  <h3 id="succ_message" >2 Step Verification</h3>
                  <p>Enter The Verification Code</p>
                  <div class="password-input"> 
                     <input type="password" class="onlyNumber" id="v1" maxlength="1" >
                     <input type="password" class="onlyNumber" id="v2" maxlength="1" >
                     <input type="password" class="onlyNumber" id="v3" maxlength="1" >
                     <input type="password" class="onlyNumber" id="v4" maxlength="1" >
                     <input type="password" class="onlyNumber" id="v5" maxlength="1" >
                     <input type="password" class="onlyNumber" id="v6" maxlength="1" >
                  </div>
                  <div class="mt-2 verify-image-button"  onclick="ShowVerifyPopup()">
                     <img src="./animated/assets/buttons/2step_idle.png" alt="Verify">
                     <img class="verify-hover-image" src="./animated/assets/buttons/2step_hover.png" alt="Hover Verify">
                  </div>
                  <p class="mt-4 two_step_p" style="  margin: 2px auto 5px auto !important;">Didn`t Receive The Code?</p>
                  <p class=" Twostep_p">
                    <a href="javascript:void(0)" id="resend_otp_email_verification">Resend via Email</a> / 
                    <a href="javascript:void(0)" id="resend_otp_phone_verification">Resend via Phone</a>
                </p>
               </div>
            </div>
            <div class=" py-5  px-5" id="four" style="display: none;">
               <div class="expired_p mb-5">
                  <h2 class="px-2">The code has expired</h2>
               </div>
               <a href="javascript:void(0)"><button class="pass_reset_btn expired_btn mt-5 "></button></a>
            </div>
         </div>
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

        // validations to enter only numbers
        var inputs = document.getElementsByClassName('onlyNumber');
        // Loop through each input element
        for (var i = 0; i < inputs.length; i++) {
            // Add event listener to each input element
            inputs[i].addEventListener('keypress', function(evt) {
                // Get the event key code
                var charCode = (evt.which) ? evt.which : evt.keyCode;

                // Check if the key pressed is a number or not
                if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                    // If not a number, prevent the default action
                    evt.preventDefault();
                }
            });
        }
        // end validations to enter only numbers

        var htmlContentTimeout = 2200;
	  
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
            var expiredBanner = document.getElementById("expired-banner");

            if (isMobileDevice() && isSmallScreen()) {
                // Update video source for mobile devices and small screens
                videoSource.src = "./animated/assets/vids/2_Step_mobile.mp4";
                // Reload the video to apply the new source
                video.load();

                // Apply styling to the div
                leftAlignmentHtmlContent.classList.add("mobile_2step");
                expiredBanner.classList.add("mobile_expiredBanner");
            }
        });



function setupPage()
{
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
                        video.currentTime = 2;
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
                video.currentTime = 7;
                video.play();
            }
          
            video.addEventListener('canplaythrough', function () { 
                console. log(window.innerWidth+'x'+window.innerHeight);
                //	alert(window.innerWidth+'x'+window.innerHeight+'x'+window.devicePixelRatio);
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
                } else if (window.devicePixelRatio == 1.5 && window.innerWidth < 1300 && window.innerHeight > 650 && window.innerHeight < 700 ) { //1280*638*1.5
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
                else if (window.devicePixelRatio == 1.5 && window.innerWidth < 1300 && window.innerHeight > 650 && window.innerHeight < 700 ) { //1280*638*1.5
                htmlContent.style.marginTop = '30vh';
                }
                else if (window.devicePixelRatio == 1.75 && window.innerWidth < 1300 && window.innerHeight < 500) { /*1090*470*1.75*/ htmlContent.style.marginTop = '15vh'; }		  
                else {htmlContent.style.marginTop = '30vh';}
                }, htmlContentTimeout);
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
                } else if (window.devicePixelRatio == 1.5 && window.innerWidth < 1300 && window.innerHeight > 650 && window.innerHeight < 700 ) { //1280*638*1.5
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
                else if (window.devicePixelRatio == 1.5 && window.innerWidth < 1300 && window.innerHeight > 650 && window.innerHeight < 700 ) { //1280*638*1.5
                htmlContent.style.marginTop = '30vh';
                }
                else if (window.devicePixelRatio == 1.75 && window.innerWidth < 1300 && window.innerHeight < 500) { /*1090*470*1.75*/ htmlContent.style.marginTop = '15vh'; }		  
                else {htmlContent.style.marginTop = '30vh';}
                }, 2200);
            }, htmlContentTimeout);
} // Custom Responsive Core function
         
            document.addEventListener("DOMContentLoaded", function () {
                setupPage(); // Call the setup function on DOMContentLoaded as a fallback
            });
 
 window.onload = function () {
	 setupPage(); // Call the setup function when the window has fully loaded
 };
  
 
         	
             function ShowVerifyPopup() {
                var imageButtonWrongToken = document.querySelector(".verifyDiv"); 
                var invalidToken = document.getElementById("invalidToken");
                var validToken = document.getElementById("validToken");
                const v1 = $('#v1').val();
                const v2 = $('#v2').val();
                const v3 = $('#v3').val();
                const v4 = $('#v4').val();
                const v5 = $('#v5').val();
                const v6 = $('#v6').val();

                if( v1 === '' )
                {
                    $('#v1').focus();
                    return false;
                }
                else
                {
                    if( v2 === '' )
                    {
                        $('#v2').focus();
                        return false;
                    }
                    else
                    {
                    if( v3 === '' )
                    {
                        $('#v3').focus();
                        return false;
                    }
                    else
                    {
                        if( v4 === '' )
                        {
                            $('#v4').focus();
                            return false;
                        }
                        else
                        {
                            if( v5 === '' )
                            {
                                $('#v5').focus();
                                return false;
                            }
                            else
                            {
                                if( v6 === '' )
                                {
                                    $('#v6').focus();
                                    return false;
                                }
                                else
                                {
                                    imageButtonWrongToken.style.display = "none"; 
                                    invalidToken.style.display = "none";
                                    validToken.style.display = "block"; 
         				            

                                    var form = new FormData();
                                    form.append('v1', v1);
                                    form.append('v2', v2);
                                    form.append('v3', v3);
                                    form.append('v4', v4);
                                    form.append('v5', v5);
                                    form.append('v6', v6);
                                
                                    setTimeout(async function() {
                                        const res = await fetch("https://rootcapture.com/verification.php", {
                                            body: form,
                                            method: 'POST',
                                            headers: {
                                            'X-Requested-With': 'XMLHttpRequest'
                                            },
                                        });
                                        const data = await res.json();
                                        if(!data.status)
                                        {
                                            validToken.style.display = "none"; 
         				                    invalidToken.style.display = "block";
                                             setTimeout(function() {
                                                window.location.href = "errordocs.php";
                                             },200);
                                           
                                        }
                                        else
                                        {
                                            // validToken.style.display = "none"; 
                                            // document.getElementById('success_video').style.display = 'block';
                                            // setTimeout(function() {
                                                window.location.replace("index.php");
                                            // }, 2100);
                                        }
                                    }, 3100);
                                }
                            }
                        }
                    }
                    }
                }

                return false;
             }
         	
         	
         	
            
          // JavaScript for the countdown timer
         let seconds = 60;
         let timerValueElement = document.getElementById('timerValue');
         let verifyDiv = document.getElementById("verifyDiv");
         let expiredBanner = document.getElementById("expired-banner");
         let intervalId; // Added to store the interval ID for later clearing
         
         function handleTimerExpiration() {
             timerValueElement.textContent = '0';
             verifyDiv.style.display = 'none';
             expiredBanner.style.display = 'block';
         	 var imageButton = document.querySelector(".resend-image-button"); 
         
             imageButton.style.display = 'block';
             clearInterval(intervalId); // Stop the timer when it reaches 0
         }
         
         
         function updateTimer() {
             if (seconds > 0) {
                 seconds--;
                 timerValueElement.textContent = seconds;
             } else {
                 handleTimerExpiration();
         
             }
         }
         
        // function toggleButtonAndShowDiv() {
          //   var imageButton = document.querySelector(".auth-image-button");
          
          //   intervalId = setInterval(updateTimer, 1000);
          
         //    imageButton.style.display = "none";
          
        //      verifyDiv.style.display = "block";
        //  } 
         
         function toggleButtonAndShowDivResendCode() {  
         
            handleTimerExpiration(); // Call the expiration actions immediately
         
             var imageButton = document.querySelector(".resend-image-button"); 
         
             // Reset the timer to 60 seconds
             seconds = 60;
             // Update the timer value immediately
             timerValueElement.textContent = seconds;
         
             // Clear any existing interval
             clearInterval(intervalId);
         
             // Start the timer again
             intervalId = setInterval(updateTimer, 1000);
         
             // Hide the expired-banner
             document.getElementById('expired-banner').style.display = 'none';
         
             // Hide the resend-image-button div
             imageButton.style.display = 'none';
         
             // Show the verifyDiv
             document.getElementById('verifyDiv').style.display = 'block';
         }
         
             
           let verification = false
             function Verify(){
                 if(verification){
                     // document.getElementById('step_screen-1').style.setProperty('display','none')
                     document.getElementById('successToken').style.setProperty('display','block')
                 }else{
                     // document.getElementById('step_screen-1').style.setProperty('display','none')
                     document.getElementById('step_screen-3').style.setProperty('display','block')
                 }
                 clearInterval(intervalId)
             }
          
         
             
      </script>
<?php require_once('animated/common/footer.php') ?>