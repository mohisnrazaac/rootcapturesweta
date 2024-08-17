<?php
    
    ob_start();
    // use PHPMailer\PHPMailer\PHPMailer;
    // use PHPMailer\PHPMailer\Exception;
    require './vendor/autoload.php'; 

    require 'includes/db.php';
    require 'includes/init.php';
    // $mail = new PHPMailer(true);
    
        /* AJAX check  */
        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

        if(isset($_POST))
        {
            $username = $_POST['username'];
            $password = $_POST['password'];

            $message = "";
            $errors = array();
            if (empty($username) || empty($password))
            {
                $ret['status'] = false;
                $ret['message'] = "All fields need to be filled";
                echo json_encode($ret);
                return;
            }

            if (empty($errors))
            { 
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
                                $_SESSION['otp_veri_pre'] = 2;
                                $ret['status'] = true;
                                $ret['message'] = $sucMsg;
                                echo json_encode($ret);
                                return;

                         } 
                         else
                         { 
                            $_SESSION['otp_veri_pre'] = 1; 
                            $ret['status'] = true;
                            $ret['message'] = $sucMsg;
                            echo json_encode($ret);
                            return;
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
    <div class="video-background" id="login_screen-1" style="display: none; background-color: black; color: white;">
        <div class="terms_holder d-flex flex-column justify-content-center align-items-center">
          <div class="terms_video_holder d-flex flex-column justify-content-center align-items-center">
            <video autoplay muted  preload="auto" class="terms_vid" id="terms_anim_no_text">
              <source src="./animated/assets/vids/terms_anim_no_text.mp4" type="video/mp4">
              Your browser does not support the video tag.
            </video> 
            <div class="d-flex flex-row justify-content-center align-items-center terms_h_holder">
			
		  <h4 class="terms_h" id="twtos">
		  <!--By logging into the platform you agree to the rootCapture <a href="#" class="terms_color">ToS</a> and <a  href="#"  class="terms_color">Privacy Policy</a>.-->
		  </h4>
		  
		   
            </div>
          </div>
          <div class="d-flex flex-md-row flex-column" style="margin-top: -80px;">
            <button class="pass_reset_btn hero_btns" id="hero_btns" onclick="acceptCookieConsent()"><div class="hero_btn_text">Accept</div></button>
            <a href="hero.php" class="mt-md-0 mt-5">
			<button class="pass_reset_btn hero_btns" id="hero_btns1"><div class="hero_btn_text">Reject</div></button></a>
          </div>
        </div>
    </div>
 

    <div class="video-background" id="login_screen-3" style="background-color: black;">
        <video autoplay muted id="myVideo2" preload="auto">
            <source src="./animated/assets/vids/Description 07_me Comp 1.mp4" type="video/mp4" >
            Your browser does not support the video tag.
        </video>
    </div>

    <div class="video-background" id="login_screen-5" style="background-color: black;">
        <video autoplay muted id="myVideo4" preload="auto"style="object-fit: contain; width: 100%; height: auto; top: 50%; left: 50%; transform: translate(-50%,-50%);">
            <source src="./animated/assets/vids/login success.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>
    </div>

    <div class="video-background" id="login_screen-4" style="background-color: black;">
	
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
	
 
		
    <div class="video-background" id="login_screen-2">
        <video autoplay muted id="myVideo" preload="auto">
            <source id="videoSource" src="./animated/assets/vids/Login page.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>
	<div id="left_alignment_html_content" style=" margin-left: 20vw;" >
                    <div class="cr_ac" style="margin-top: 0px !important">
                        <form action="" class="signup-form" id="signup-form">
                            <div class="d-flex flex-column  pb-0 signup-top-div">
                                <h3 class="signup-h1 pt-1">Login Details</h3>
                                <div class="d-flex flex-row" style="gap: 5px;"><div class="input-box"></div><input type="text" class="signup-input p-2" id="username" placeholder="USERNAME OR EMAIL"></div> 
								
                                <div class="d-flex flex-row" style="gap: 5px;">
								<div class="input-box"></div>
								<input type="text"  id="password-input" class="signup-input p-2" oninput="maskPasswordLogin(this)" placeholder="PASSWORD">
								<input type="hidden"  id="actualPassword" class="signup-input p-2"/>
								</div>
                                
								<div class="d-flex flex-column  p-4 pt-1 pb-1 signup-bottom-div" >
									<div class="mt-2 d-flex flex-row justify-content-center align-items-center">
										<button class="pass_reset_btn signin_btn" onclick="Login()" type="button"></button>
									</div>
									<a href="forpass.php"><p class="mt-2 login_forgot_p">Forgot Password?</p></a>
								</div>
                            <div class="d-flex flex-row justify-content-center align-items-center p-2 login_register_holder" style="border-top: 4px solid #fc4653;">
                                <a href="./register.php"><span class="login_register_holder_button">Register Your Account</span></a>
                                <a href="./register-college.php"><span class="login_register_holder_button">Register Your Institution</span></a>
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
	</div> 
	          <a class="gear_icon" onclick="showPop()"><i class="fa-solid fa-gear fa-2x"></i></a>


    <script>

        function maskPasswordLogin(input) {
            var value = input.value;
            var maskedValue = 'X'.repeat(value.length);
            var actualPasswordInput = document.getElementById("actualPassword");
            var previousValue = actualPasswordInput.value; // Store previous value
            actualPasswordInput.value = previousValue.substring(0, value.length) + value.substring(previousValue.length); // Update with new value
            input.value = maskedValue; // Display masked password in the visible input
        }
	

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
			videoSource.src = "./animated/assets/vids/BG_Login_page_Mobile.mp4";
			// Reload the video to apply the new source
			video.load();

			// Apply styling to the div
			leftAlignmentHtmlContent.classList.add("mobile_login_page");
		}
	});
	
 function showScreen2() {
    document.getElementById('login_screen-1').style.display = 'none';
    setupPage(); // Call the setup function on DOMContentLoaded as a fallback
    var htmlContent = document.getElementById('left_alignment_html_content');
    htmlContent.style.display = 'none';


    var myDiv = document.getElementById("signup-form");

    // Delay the appearance of the div by 2.2 seconds
    setTimeout(function() {
        myDiv.style.opacity = 1; // Make the div visible
    }, 2200);

    document.getElementById('login_screen-2').style.display = 'block';
    // Animation Effects
    setTimeout(function() {
        myDiv.style.opacity = 0.7;
    }, 2800); // 2.2s + 0.2s
    setTimeout(function() {
        myDiv.style.opacity = 0.4;
    }, 3000); // 2.2s + 0.4s
    setTimeout(function() {
        myDiv.style.opacity = 0;
    }, 3200); // 2.2s + 0.6s
    setTimeout(function() {
        myDiv.style.opacity = 0.5;
    }, 3300); // 2.2s + 0.7s
    setTimeout(function() {
        myDiv.style.opacity = 0;
    }, 3400); // 2.2s + 0.8s
    setTimeout(function() {
        myDiv.style.opacity = 0.5;
    }, 3500); // 2.2s + 0.9s
    setTimeout(function() {
        myDiv.style.opacity = 0;
    }, 3600); // 2.2s + 1.0s
    setTimeout(function() {
        myDiv.style.opacity = 0.5;
    }, 3700); // 2.2s + 1.1s
    setTimeout(function() {
        myDiv.style.opacity = 0;
    }, 3780); // 2.2s + 1.18s
    setTimeout(function() {
        myDiv.style.opacity = 1;
    }, 4400); // 2.2s + 1.8s

    const video = document.getElementById('myVideo');
    video.autoplay = true;
    video.muted = true;

    video.addEventListener("ended", handleVideoEnded);

    function handleVideoEnded() {
        video.pause();
        video.currentTime = 8.8;
        video.play();
    }
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
                        video.currentTime = 4;
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
		console.log(window.innerWidth+'x'+window.innerHeight);
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
         			
  if ( (window.devicePixelRatio==1.5) && (window.innerWidth < 1300 && window.innerHeight > 500 && window.innerHeight < 700)) { /*1272*572*1.5*/htmlContent.style.marginTop = '33vh';}
  else if (window.devicePixelRatio == 1.75 && window.innerWidth < 1300 && window.innerHeight < 500) { /*1090*470*1.75*/ htmlContent.style.marginTop = '15vh'; }	
  else if (window.devicePixelRatio == 1.5 && window.innerWidth < 1300 && window.innerHeight > 650 && window.innerHeight < 700 ) { /*1280*665*1.5*/
  htmlContent.style.marginTop = '30vh';}	  
  else if (window.innerWidth < 500 ) { /*Mobile*/  htmlContent.style.marginTop = '50vh';}		  
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
  if ( (window.devicePixelRatio==1.5) && (window.innerWidth < 1300 && window.innerHeight > 500 && window.innerHeight < 700)) { /*1272*572*1.5*/htmlContent.style.marginTop = '33vh';}
  else if (window.devicePixelRatio == 1.75 && window.innerWidth < 1300 && window.innerHeight < 500) { /*1090*470*1.75*/ htmlContent.style.marginTop = '15vh'; }	
  else if (window.devicePixelRatio == 1.5 && window.innerWidth < 1300 && window.innerHeight > 650 && window.innerHeight < 700 ) { /*1280*665*1.5*/
  htmlContent.style.marginTop = '30vh';}	
  else if (window.innerWidth < 500 ) { /*Mobile*/  htmlContent.style.marginTop = '50vh';}		  
  
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
  


 

var i = 0;
var j = 0;
// var txt = 'Password Did Not Match Security Requirements<br>REASONS:<br>Case Sensative<br>8 Characters Needed'; 
var txttos = 'By logging into the platform you agree to the rootCapture <a href="#" class="terms_color" id="tosLink">ToS</a> and <a href="#" class="terms_color" id="privacyLink">Privacy Policy</a>';
var speed = 25;
var typeWriterTOSExecuted = false;
 
function typeWriterTOS() {
  if (typeWriterTOSExecuted) {
    return;
  }

  typeWriterTOSExecuted = true;

  var twtos = document.getElementById("twtos");

  function typeCharacter() {
    if (i < txttos.length) {
      var char = txttos.charAt(i);
      if (char === '<') {
        var tagEndIndexTOS = txttos.indexOf('>', i);
        twtos.innerHTML += txttos.substring(i, tagEndIndexTOS + 1);
        i = tagEndIndexTOS + 1;
      } else {
        twtos.appendChild(document.createTextNode(char));
        i++;
      }

      setTimeout(typeCharacter, speed);
    } else {
      // Create ToS link
      var tosLink = document.createElement("a");
      tosLink.href = "#"; // Replace '#' with the actual URL of your Terms of Service page
      tosLink.className = "terms_color";
      tosLink.textContent = "ToS";

      // Create Privacy Policy link
      var privacyLink = document.createElement("a");
      privacyLink.href = "#"; // Replace '#' with the actual URL of your Privacy Policy page
      privacyLink.className = "terms_color";
      privacyLink.textContent = "Privacy Policy";

      // Append links to twtos
      twtos.innerHTML = ""; // Clear existing content
      twtos.appendChild(document.createTextNode("By logging into the platform you agree to the rootCapture "));
      twtos.appendChild(tosLink);
      twtos.appendChild(document.createTextNode(" and "));
      twtos.appendChild(privacyLink);
    }
  }

  typeCharacter();
}
		
function typeWriterOld(message) {
    var txt = message;
  if (j < txt.length) {
    console.log('J='+j);
    // Check if the current character is '<', then add the following characters until '>'
    if (txt.charAt(j) === '<') {
      var tagEndIndex = txt.indexOf('>', j);
      document.getElementById("demo").innerHTML += txt.substring(j, tagEndIndex + 1);
      j = tagEndIndex + 1;
    } else {
      document.getElementById("demo").innerHTML += txt.charAt(j);
      j++;
    }
    setTimeout(typeWriter(txt), speed); // Call the function recursively after a delay
  }
}		
		
// function typeWriter() {
    
    
//   if (j < txt.length) {
//     console.log('J='+j);
//     // Check if the current character is '<', then add the following characters until '>'
//     if (txt.charAt(j) === '<') {
//       var tagEndIndex = txt.indexOf('>', j);
//       document.getElementById("demo").innerHTML += txt.substring(j, tagEndIndex + 1);
//       j = tagEndIndex + 1;
//     } else {
//       document.getElementById("demo").innerHTML += txt.charAt(j);
// 		j++;
//     }
//     setTimeout(typeWriter(txt), 1000);
//   }
// }



document.addEventListener("DOMContentLoaded", function () {
		setTimeout(function () { typeWriterTOS() }, 2000); 
        const videoterms_anim_no_text = document.getElementById('terms_anim_no_text');
        terms_anim_no_text.autoplay = true;
        terms_anim_no_text.muted = true;
    
        terms_anim_no_text.addEventListener("ended", handleVideoEndedterms_anim_no_text);
    
        function handleVideoEndedterms_anim_no_text() {
            terms_anim_no_text.pause();
            terms_anim_no_text.currentTime = 2.8;
            terms_anim_no_text.play();
        }
		 
        var myDiv = document.getElementById("hero_btns");
        var myDiv2 = document.getElementById("hero_btns1");
        setTimeout(function() {
          myDiv.style.height = '80px';
          myDiv2.style.height = '80px';    
        }, 1000);
        setTimeout(function() {
          myDiv.style.width = '265px';
          myDiv2.style.width = '265px';
          document.querySelector('.terms_h').style.display = 'block'
        }, 1000);
      });

      function LoginOld()
      {
        let login_success = false;
        // document.getElementById('login_screen-2').style.display = 'none';
        document.getElementById('login_screen-1').style.display = 'none';
        document.getElementById('login_screen-3').style.display = 'block';
        setTimeout(function() {
          if(login_success){
            document.getElementById('login_screen-3').style.display = 'none';
            document.getElementById('login_screen-5').style.display = 'block';
          }else{
          document.getElementById('login_screen-3').style.display = 'none';
          document.getElementById('login_screen-4').style.display = 'block';
		  
	    setTimeout(function () { typeWriter() }, 2000); 
	   
		const video = document.getElementById('myVideo3');
        video.autoplay = true;
        video.muted = true;
    
        video.addEventListener("ended", handleVideoEnded);
    
        function handleVideoEnded() {
            video.pause();
            video.currentTime = 5;
            video.play();
        }
          document.querySelector('.login_error_h').style.display = 'block'
        }
         }, 5100);
      }

     


    </script>
   <?php require_once('animated/common/footer.php') ?>