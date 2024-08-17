<?php
    ob_start();
    require './vendor/autoload.php'; 
    require 'includes/db.php';
    require 'includes/init.php';

    if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
    {
        if(isset($_POST))
        {
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $repassword = $_POST['cnfPassword'];
            $phonenumber = $_POST['phoneNumber'];
            $accesscode = $_POST['code'];
            $fa_preference = $_POST['fa_preference'];


            if (empty($username) || empty($password) || empty($repassword) || empty($accesscode) || empty($fa_preference))
            {
                $ret['status'] = false;
                $ret['message'] = "All fields need to be filled";
                echo json_encode($ret);
                return;
            }
            else if( empty($email) && empty($phonenumber) )
            {
                $ret['status'] = false;
                $ret['message'] = "Email or phone is required.";
                echo json_encode($ret);
                return;
            }
            elseif($fa_preference==2 && empty($email)){  
                $ret['status'] = false;
                $ret['message'] = "Please enter email for 2fa perference.";
                echo json_encode($ret);
                return;
            }
            elseif($fa_preference==1 && empty($phonenumber)){
                $ret['status'] = false;
                $ret['message'] = "Please enter email for 2fa perference.";
                echo json_encode($ret);
                return;
            }
            else
            {
                if( !empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL))
                {
                    $ret['status'] = false;
                    $ret['message'] = "Invalid email format!";
                    echo json_encode($ret);
                    return;
                }
                if($password != $repassword) {
                    $ret['status'] = false;
                    $ret['message'] = "YOUR PASSWORDS DO NOT MATCH,<br/>RE-ENTER YOUR PASSWORDS.";
                    echo json_encode($ret);
                    return;           
                }
                else
                {
                    //Check for dublicate username                   
                    $checkUsernameExists = $odb -> prepare("SELECT * FROM `users` WHERE `username` = :username");
                    $checkUsernameExists -> execute(array(':username' => $username));

                    $checkPhoneExists = $odb -> prepare("SELECT * FROM `users` WHERE `phone` = :phone");
                    $checkPhoneExists -> execute(array(':phone' => $phonenumber));

                    $checkEmailExists = $odb -> prepare("SELECT * FROM `users` WHERE `email` = :email");
                    $checkEmailExists -> execute(array(':email' => $email));
                    if($checkUsernameExists -> rowCount() >= 1) {
                        $ret['status'] = false;
                        $ret['message'] = "The username is already in use,<br/> please use a different username.";
                        echo json_encode($ret);
                        return;    
                    }
                    else if($checkEmailExists -> rowCount() >= 1)
                    {
                        $ret['status'] = false;
                        $ret['message'] = "The email is already in use,<br/> please use a different email.";
                        echo json_encode($ret);
                        return;  
                    }
                    else if($checkPhoneExists -> rowCount() >= 1)
                    {
                        $ret['status'] = false;
                        $ret['message'] = "The phone number is already in use,<br/> please use a different phone number.";
                        echo json_encode($ret);
                        return;  
                    }
                    else
                    {

                        $getCollegeIdByAccesstoken = $user->getCollegeIdByAccesstoken($odb,$accesscode);  
                        
                        if($getCollegeIdByAccesstoken != '')
                        {
                            if( $user->isRegistrationMode($odb,$getCollegeIdByAccesstoken) )
                            {   
                                $role = 0;

                                $SQLGetTeam = $odb->prepare("SELECT teams.id,team_status.team_code, teams.name FROM `teams` INNER JOIN `team_status` ON teams.id = team_status.team_id WHERE `teams`.`name` NOT LIKE 'Admin' AND `teams`.`name` NOT LIKE 'Administrative Assistant' AND team_status.team_code LIKE ?" );
                                $SQLGetTeam->execute([$accesscode]);
                                $SQLGetTeam = $SQLGetTeam->fetch();

                                $role = $SQLGetTeam['id'];
                                $team_name = $SQLGetTeam['name']; 

                                if( !empty($email) && !empty($phonenumber))
                                {
                                     // check if user is authenticated
                                    $isCheckUserAuth = $odb->prepare("SELECT * FROM `pre_registration` where email = ? AND mobile = ?");
                                    $isCheckUserAuth->execute([$email, $phonenumber]);
                                    $isCheckUserAuth = $isCheckUserAuth->fetch();
                                    $isCheckUserAuth = ($isCheckUserAuth['is_used'])?$isCheckUserAuth['is_used']:'';

                                    if($isCheckUserAuth == 1)
                                    {
                                        $SQLinsert = $odb -> prepare("INSERT INTO `users`(`ID`, `username`, `password`, `email`, `rank`,`college_id`, `phone`, `membership`, `expire`, `status`, `key`, `used`, `otp`, `otp_verification_preference`, `banned_msg`, `grading_criteria`,`datetime`) VALUES(NULL, :username, :password, :email, :rank,$getCollegeIdByAccesstoken, :phone, 0, 0, 0, NULL, 0, Null,:otp_verification_preference,Null,0,:datetime)");
                                        
                                        $SQLinsert -> execute(array(':username' => $username, ':password' => SHA1($password), ':email' => $email, ':rank' => $role, ':phone' => $phonenumber, ':otp_verification_preference' => $fa_preference, ':datetime' => DATETIME));
                                    }
                                    else
                                    {
                                        $ret['status'] = false;
                                        $ret['message'] = "Something went wrong,<br/>Please try again later";
                                        echo json_encode($ret);
                                        return;  
                                    }
                                    
                                }else if( !empty($email) && empty($phonenumber) )
                                { 
                                    // check if user is authenticated
                                    $isCheckUserAuth = $odb->prepare("SELECT * FROM `pre_registration` where email = ?");
                                    $isCheckUserAuth->execute([$email]);

                                    $isCheckUserAuth = $isCheckUserAuth->fetch();
                                    $isCheckUserAuth = ($isCheckUserAuth['is_used'])?$isCheckUserAuth['is_used']:'';

                                    if($isCheckUserAuth == 1)
                                    {

                                        $SQLinsert = $odb -> prepare("INSERT INTO `users`(`ID`, `username`, `password`, `email`, `rank`,`college_id`, `phone`, `membership`, `expire`, `status`, `key`, `used`, `otp`, `otp_verification_preference`, `banned_msg`, `grading_criteria`, `datetime`) VALUES(NULL, :username, :password, :email, :rank,$getCollegeIdByAccesstoken, Null, 0, 0, 0, NULL, 0, Null,2,Null,0,:datetime)");
            
                                        $SQLinsert -> execute(array(':username' => $username, ':password' => SHA1($password), ':email' => $email, ':rank' => $role, ':datetime' => DATETIME));
                                    }
                                    else
                                    {
                                        $ret['status'] = false;
                                        $ret['message'] = "Something went wrong,<br/>Please try again later";
                                        echo json_encode($ret);
                                        return;  
                                    }

                                }else if( empty($email) && !empty($phonenumber) )
                                {
                                    // check if user is authenticated
                                    $isCheckUserAuth = $odb->prepare("SELECT * FROM `pre_registration` where mobile = ?");
                                    $isCheckUserAuth->execute([$phonenumber]);
                                    $isCheckUserAuth = $isCheckUserAuth->fetch();
                                    $isCheckUserAuth = ($isCheckUserAuth['is_used'])?$isCheckUserAuth['is_used']:'';

                                    if($isCheckUserAuth == 1)
                                    {

                                        $SQLinsert = $odb -> prepare("INSERT INTO `users`(`ID`, `username`, `password`, `email`, `rank`,`college_id`, `phone`, `membership`, `expire`, `status`, `key`, `used`, `otp`, `otp_verification_preference`, `banned_msg`, `grading_criteria`, `datetime`) VALUES(NULL, :username, :password, Null, :rank,$getCollegeIdByAccesstoken, :phone, 0, 0, 0, NULL, 0, Null,1,Null,0,:datetime)");

                                        $SQLinsert -> execute(array(':username' => $username, ':password' => SHA1($password), ':rank' => $role, ':phone' => $phonenumber,':datetime' => DATETIME));
                                    }
                                    else
                                    {
                                        $ret['status'] = false;
                                        $ret['message'] = "Something went wrong,<br/>Please try again later";
                                        echo json_encode($ret);
                                        return;  
                                    }
                                }
    
                                // $user->addRecentActivities($odb,'user_register','A new user ('.$username.') successfully registered on the platform and on '.$team_name);
                               
                                   
    
                                    $SQLGetUserInfo = $odb -> prepare("SELECT `username`, `ID`,`status`, `phone`,`email`,`otp_verification_preference`,`banned_msg`  FROM `users` WHERE `username` = :username");
                                    $SQLGetUserInfo -> execute(array(':username' => $username));
                                    
                                    $userInfo = $SQLGetUserInfo -> fetch(PDO::FETCH_ASSOC);
                                    $last_id = $userInfo['ID']; 
                                    $_SESSION['tempUsername'] = $username;
                                    $_SESSION['tempID'] = $userInfo['ID'];
                                    // if phone number not available
                                    
                                    if($fa_preference==2 )
                                    {  
                                        $_SESSION['otp_veri_pre'] = 2; 
                                    } 
                                    else
                                    { 
                                        $_SESSION['otp_veri_pre'] = 1;
                                    }

                                    $ret['status'] = true;
                                    $ret['message'] = "Successfully Registered.";
                                    echo json_encode($ret);
                                    return;
                            }
                            else
                            {
                                $ret['status'] = false;
                                $ret['message'] = "Registration feature is disabled,<br/>ask your administartion to enable it.";
                                echo json_encode($ret);
                                return;  
                            }
                        }
                        else
                        {
                            $ret['status'] = false;
                            $ret['message'] = "Invalid access token.";
                            echo json_encode($ret);
                            return;  
                        }
                    }

                }
            }
        }
    }

    require_once('animated/common/header.php'); ?>
	<div class="Textpopup">
		<div class="verifyDiv" id="verifyDiv" style="display: block !important">
			 
		
					  <h3 class="yourcodeexpires" >
                        <span id="yourcodeexpires">Your Sent code expires in </span>      
					  <div id="timer">
							<span class="blink-dot dot1"></span>
							<span class="blink-dot dot2"></span>
							<span class="blink-dot dot3"></span>
							<span class="blink-dot dot4"></span>
							<span id="timerValue">60</span>
						</div>

						</h3>
                        <div class="pass_reset_background py-3 px-5" >
                            <h3>2 Step Verification</h3>
                            <p id="otpverimsg">Enter The Verification Code</p>
                            <div class="password-input"> 
                                <input type="password" class="onlyNumber" id="v1" maxlength="1" />
                                <input type="password" class="onlyNumber" id="v2" maxlength="1" />
                                <input type="password" class="onlyNumber" id="v3" maxlength="1" />
                                <input type="password" class="onlyNumber" id="v4" maxlength="1" />
                                <input type="password" class="onlyNumber" id="v5" maxlength="1" />
                                <input type="password" class="onlyNumber" id="v6" maxlength="1" />
                            </div>
 
					  <div class="mt-2 verify-image-button">
						<img src="./animated/assets/buttons/2step_idle.png" alt="Verify">
						<img class="verify-hover-image" src="./animated/assets/buttons/2step_hover.png" id="reg_verification" alt="Hover Verify">
					  </div>

 
                            <p class="mt-4 two_step_p" style="  margin: 2px auto 5px auto !important;">Didn`t Receive The Code?</p>
                            <p class=" Twostep_p">
                                <a href="javascript:void(0)" id="resend_otp_email" >Resend via Email</a> / 
                                <a href="javascript:void(0)" id="resend_otp_phone">Resend via Phone</a></p>
                        </div>
					  
					  
					  
					  </div>

    </div>
	
	

<div class="video-background" id="register_student_v">
        <video autoplay muted id="myVideo" preload="auto">
            <source id="videoSource" src="./animated/assets/vids/Create Your Account_bg.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>
<div id="left_alignment_html_content" style="margin-left: 20vw;">
		<!-- <div class="container"> -->
			<!-- <div class="row"> -->
				<!-- <div class="col-xl-1 col-md-0 col-1"></div> -->
				<!-- <div class="col-xl-4 col-md-6 col-10"> -->
				<div class="cr_ac">
					<form action="" class="signup-form" id="signup-form">
						<div class="d-flex flex-column   pb-0 signup-top-div">
							<h3 class="signup-h1 py-1">Create Your Account</h3>

							<div class="d-flex flex-row" style="gap: 5px;">
                                <div class="input-box"></div>
                                <input type="text" class="signup-input p-2" id="username" placeholder="Enter Your User Name">
                            </div>

							<div class="d-flex flex-row" style="gap: 5px;">
                                <div class="input-box"></div>
                                <input type="text" id="email" class="signup-input p-2" placeholder="Enter Your Email">
                            </div>

							<div class="d-flex flex-row" style="gap: 5px;">
                                <div class="input-box"></div>
                                <input type="password" id="password" class="signup-input p-2" placeholder="Enter Your Password">
                            </div>

							<div class="d-flex flex-row" style="gap: 5px;">
                                <div class="input-box"></div>
                                <input type="password" class="signup-input p-2" id="cnfPassword" placeholder="Confirm Your Password">
                            </div>

							<div class="d-flex flex-row" style="gap: 5px;">
                                <div class="input-box"></div>
							    <input type="text" class="signup-input p-2" placeholder="Phone Number" id="phoneNumber" data-toggle="tooltip" data-placement="top" title="Only numbers are allowed"/>
							</div>
						</div>
	
						<div class="d-flex flex-column mt-2   pt-0 signup-bottom-div">
							<div class="signup-pref-holder">
								<p class="signup-pref-p">2FA Preferences <span style="color: #04fefe;" id="2faValidation"></span> </p>
								<div class="d-flex flex-row mt-1" style="gap: 35px;">
									<label class="custom-checkbox" style="margin-right: 1rem">
										<input type="radio" name="2faOption" class="checkbox-input" value="1"  onclick="show2FaPop()">
										<span class="checkmark"></span>
										Text
									</label>
									<label class="custom-checkbox">
										<input type="radio" name="2faOption" class="checkbox-input" value="2"  onclick="show2FaPop()">
										<span class="checkmark"></span>
										Email
									</label>
								</div>
							</div>
							<div class="d-flex flex-row" style="border-bottom: 6px solid #0000006b;box-shadow: 0px 5px 0px 0px #fc4653; gap: 5px;">
								<div class="input-box"></div>
								<input type="text" id="code" class="signup-input p-2" placeholder="Enter Code">
							</div>
							
							<div class="mt-2 d-flex flex-row justify-content-center align-items-center">		 
							  <div class="resend-image-button rim-margin-1">
								<img src="./animated/assets/buttons/register institution_idle.png"  alt="Register Button"/>
								<img class="resend-hover-image" id="register_student" src="./animated/assets/buttons/register institution_hover.png" alt="Register hover"/>
							  </div>		  
							</div>
						</div>
					</form>
				</div>
				<!-- <div class="col-xl-7 col-md-6 col-1"></div> 
			</div>
		</div> -->
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

   
	 
     var otp_verified = false;
	   

	
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
                videoSource.src = "./animated/assets/vids/Create_Your_Account_bg_mobile.mp4";
                // Reload the video to apply the new source
                video.load();

                // Apply styling to the div
                leftAlignmentHtmlContent.classList.add("mobile");
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
        video.currentTime = 5;
        video.play();
    }
            
    
    video.addEventListener('canplaythrough', function () { 
        console. log(window.innerWidth+'x'+window.innerHeight+'x'+window.devicePixelRatio);
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
                
            if ( (window.devicePixelRatio==1.5) && (window.innerWidth < 1300 && window.innerHeight > 510 && window.innerHeight < 650)) { /*1272*572*1.5*/ htmlContent.style.marginTop = '20vh'; }
            else if ( (window.devicePixelRatio==1.5) && (window.innerWidth < 1300 && window.innerHeight > 650 && window.innerHeight < 700)) { /*1272*572*1.5*/ htmlContent.style.marginTop = '17.5vh'; }
            else if (window.devicePixelRatio > 1.5 && window.innerWidth < 1300 && window.innerHeight < 510) { /*1090*470*1.75*/ htmlContent.style.marginTop = '13vh'; }	
            else if (window.devicePixelRatio > 1.70 && window.innerWidth < 1300 && window.innerHeight < 510) { /*1090*470*1.75*/ htmlContent.style.marginTop = '13vh'; }		
            else if (window.devicePixelRatio > 1.70 && window.innerWidth < 1300 && window.innerHeight > 510 && window.innerHeight < 550) {/*1090*470*1.75*/htmlContent.style.marginTop = '16vh';}		  
            else if (window.devicePixelRatio>1.70 && window.innerWidth<1300 && window.innerHeight>510 && window.innerHeight < 560){/*1098*551*1.75*/htmlContent.style.marginTop = '17vh';}

            else {htmlContent.style.marginTop = '22.5vh';}
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
            if ( (window.devicePixelRatio==1.5) && (window.innerWidth < 1300 && window.innerHeight > 510 && window.innerHeight < 650)) { /*1272*572*1.5*/ htmlContent.style.marginTop = '20vh'; }
            else if ( (window.devicePixelRatio==1.5) && (window.innerWidth < 1300 && window.innerHeight > 650 && window.innerHeight < 700)) { /*1272*572*1.5*/ htmlContent.style.marginTop = '17.5vh'; }
            else if (window.devicePixelRatio > 1.5 && window.innerWidth < 1300 && window.innerHeight < 510) { /*1090*470*1.75*/ htmlContent.style.marginTop = '13vh'; }	
            else if (window.devicePixelRatio > 1.70 && window.innerWidth < 1300 && window.innerHeight < 510) { /*1090*470*1.75*/ htmlContent.style.marginTop = '13vh'; }	
            else if (window.devicePixelRatio > 1.70 && window.innerWidth < 1300 && window.innerHeight > 510 && window.innerHeight < 550) {/*1090*470*1.75*/htmlContent.style.marginTop = '16vh';}	  else if (window.devicePixelRatio>1.70 && window.innerWidth<1300 && window.innerHeight>510 && window.innerHeight < 560){/*1098*551*1.75*/htmlContent.style.marginTop = '17vh';}

            else {htmlContent.style.marginTop = '22.5vh';}
        }, 2200);
        }, 2200);
}
		 
		 
    document.addEventListener("DOMContentLoaded", function () {
        setupPage(); // Call the setup function on DOMContentLoaded as a fallback
    });
 
    window.onload = function () {
        setupPage(); // Call the setup function when the window has fully loaded
    };
  



    document.addEventListener("DOMContentLoaded", function () {
        setupPage(); // Call the setup function on DOMContentLoaded as a fallback

        var myDiv = document.getElementById("signup-form");
            setTimeout(function() {
            myDiv.style.opacity = 0.7;
            }, 600);
            setTimeout(function() {
            myDiv.style.opacity = 0.4;
            }, 800);
            setTimeout(function() {
            myDiv.style.opacity = 0;
            }, 1000);
            setTimeout(function() {
            myDiv.style.opacity = 0.5;
            }, 1100);
            setTimeout(function() {
            myDiv.style.opacity = 0;
            }, 1200);
            setTimeout(function() {
            myDiv.style.opacity = 0.5;
            }, 1300);
            setTimeout(function() {
            myDiv.style.opacity = 0;
            }, 1400);
            setTimeout(function() {
            myDiv.style.opacity = 0.85;
            }, 1500);
            setTimeout(function() {
            myDiv.style.opacity = 1;
            }, 3500);
            setTimeout(function() {
            myDiv.style.opacity = 0.5;
            }, 3600);
            setTimeout(function() {
            myDiv.style.opacity = 0;
            }, 3700);
            setTimeout(function() {
            myDiv.style.opacity = 0.8;
            }, 3800);
            setTimeout(function() {
            myDiv.style.opacity = 0;
            }, 3900);
            setTimeout(function() {
            myDiv.style.opacity = 0.8;
            }, 4000);
            setTimeout(function() {
            myDiv.style.opacity = 1;
            }, 4100);
    });

    window.onload = function () {
        setupPage(); // Call the setup function when the window has fully loaded
    };







    let Textpopup = false;
    async function show2FaPop()
    {
            let email = $('#email').val();
            let phoneNumber = $('#phoneNumber').val();
            var twoFaOption = document.getElementsByName('2faOption');
            var twoFaValid = false;
            var preference = 0;

            for (var i = 0; i < twoFaOption.length; i++)
            {
                    if (twoFaOption[i].checked) {
                    if(twoFaOption[i].value == 1)
                    {
                        if( phoneNumber === '' )
                        {
                        $('#phoneNumber').focus();
                        $('#phoneNumber').attr('placeholder','Please enter phone number.');
                        twoFaOption[i].checked = false;
                        return false;
                        }
                        else
                        {
                            preference = 1;
                        }
                    }
                    else if (twoFaOption[i].value == 2)
                    {
                        if( email === '' )
                        {
                        $('#email').focus();
                        $('#email').attr('placeholder','Please enter email address');
                        twoFaOption[i].checked = false;
                        return false;
                        }
                        else if(!email.toLowerCase().match(
                        /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|.(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
                        ))
                        {
                        $('#email').val('');
                        $('#email').focus();
                        $('#email').attr('placeholder','Please enter valid email address.');
                        twoFaOption[i].checked = false;
                        return false;
                        }

                        preference = 2;
                    }
                    else
                    {
                        alert("Inavalid Request.");
                    }

                    twoFaValid = true;
                    break; // Exit loop if a radio button is checked
                    }
            }

            if(twoFaValid)
            {
                var form = new FormData();
                form.append('function_name', 'validate_otp_reg');
                form.append('email', email);
                form.append('phoneNumber', phoneNumber);
                form.append('preference', preference);
                
                const res = await fetch("https://rootcapture.com/includes/ajax-nosession.php", {
                    body: form,
                    method: 'POST'
                });
                
                

                const data = await res.json();
                if(data.status)
                {
                    // Start the timer
                    var intervalId = setInterval(updateTimer, 1000);
                    if(!Textpopup)
                    {
                        document.querySelector('.Textpopup').style.display = 'flex'; 
                        Textpopup = true;

                        // var imageButtonWrongToken = document.querySelector(".verifyDiv"); 
                        // var invalidToken = document.getElementById("invalidToken");
                        // var validToken = document.getElementById("validToken");
                        // var inputValue = document.getElementById("textInput").value;

                    }
                    else {
                        document.querySelector('.Textpopup').style.display = 'none'; 
                        // Textpopup = false;
                    }
                }
                else
                {
                    document.getElementsByName('2faOption')[0].checked = false;
                    document.getElementsByName('2faOption')[1].checked = false;
                    console.log("Something went wrong"); return false;
                }
                // end sent code to user
            }
            else
            {
            alert("Invalid Request");
            }

        
	  
			// var imageButton = document.querySelector(".auth-image-button");
			// Start the timer
			// intervalId = setInterval(updateTimer, 1000);
			// Show the additional div
			// verifyDiv.style.display = "block";
			// if(!Textpopup){
			// 	document.querySelector('.Textpopup').style.display = 'flex'; 
			// 	Textpopup = true;

			// 	// var imageButtonWrongToken = document.querySelector(".verifyDiv"); 
			// 	// var invalidToken = document.getElementById("invalidToken");
			// 	// var validToken = document.getElementById("validToken");
			// 	// var inputValue = document.getElementById("textInput").value;

			// }
			// else {
			// 	document.querySelector('.Textpopup').style.display = 'none'; 
			// 	// Textpopup = false;
			// }
    }

    
	  
        function closeTextPop()
        {
            var textPopup = document.querySelector('.Textpopup');
                textPopup.style.display = 'none';
                Textpopup = false;

                // Stop and reset the countdown timer
                clearInterval(intervalId); // Stop the timer
                seconds = 60; // Reset the timer to its initial state
                timerValueElement.textContent = seconds; // Update the displayed value
        }

  
        // JavaScript for the countdown timer
        let seconds = 60;
        let timerValueElement = document.getElementById('timerValue');
        let verifyDiv = document.getElementById("verifyDiv");
        // let expiredBanner = document.getElementById("expired-banner");
        let intervalId; // Added to store the interval ID for later clearing

        function updateTimer() {
            if (seconds > 0) {
                seconds--;
                timerValueElement.textContent = seconds;
            } else {
                timerValueElement.textContent = '0';
                document.getElementsByName('2faOption')[0].checked = false;
                document.getElementsByName('2faOption')[1].checked = false;
                closeTextPop();
                clearInterval(intervalId); // Stop the timer when it reaches 0
            }
        }
    </script>
<?php require_once('animated/common/footer.php') ?>