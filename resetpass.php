<?php 
	ob_start();
	require 'includes/db.php';
	require 'includes/init.php';

	/* AJAX check  */
	if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
	{

		if(isset($_POST))
		{
			$password = $_POST['password_input'];
			$cnfpassword = $_POST['password_input2'];
			$token = $_GET['token'];

			$message = "";
			$errors = array();
			if ( empty($password) || empty($cnfpassword) || empty($token) )
			{
				$ret['status'] = false;
				$ret['message'] = "Please fill all required field.";
				echo json_encode($ret);
				return;
			}

			if ( $password !== $cnfpassword )
			{
				$ret['status'] = false;
				$ret['message'] = "Password and confirm password<br/> does not match.";
				echo json_encode($ret);
				return;
			}
			else
			{
				// check if id exist
				$stmp = $odb -> prepare("SELECT ID, `key`, used FROM `users` WHERE `key` = :token");
				$stmp -> execute(array(':token' => $token));
				$row = $stmp -> fetch(PDO::FETCH_ASSOC);
				if( !$row  )
				{
					$ret['status'] = false;
					$ret['message'] = "The URL is invalid! Please try again.";
					echo json_encode($ret);
					return;
				}
				else
				{
					$used = $row['used'];
					$id = $row['ID'];
					if( $used != 0  )
					{
						$ret['status'] = false;
						$ret['message'] = "Your password reset token has already been used!</br> Please reset your password again.";
						echo json_encode($ret);
						return;	
					}
					else
					{
						// update password
						$stmp = $odb -> prepare("UPDATE users SET `password` = :password, used = 1 WHERE ID = :id");
						$stmp -> execute(array(':password' => SHA1($password), ':id' => $id));

						$ret['status'] = true;
						$ret["message"] = "Your password has been successfully changed! You are now being redirected to the rootCapture Login Page!";
						echo json_encode($ret);
						return;
						
					}
				}
			}
			
		}

	}

	require_once('animated/common/header.php'); ?>
	
    <div class="video-background" id="resetpassword"> 

        <video autoplay muted     id="myVideo" preload="auto">
            <source src="./animated/assets/vids/Set New Password.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video> 
		<div id="left_alignment_html_content">
			
			<div class=" pass_reset_background py-3" id="pass_reset_2_background">
				<h3>Set New Password</h3>
				<!-- <p>Must be atleast 8 characters</p> -->
				<h6>New Password</h6>
				<input  id="password-input"  type="password"  class="mt-2 email_input" placeholder="Enter New Password">
					
				<input  id="password-input2" type="password" class="mt-2 email_input" placeholder="Confirm New Password">
				<button class="mt-2   set_new_pass_btn" id="set_new_pass_btn"> </button>
			</div>
				
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

    <div class="video-background" id="success_video" style="display: none; background-color: black;">
        <video autoplay muted id="myVideo4" preload="auto"style="object-fit: contain; width: 100%; height: auto; top: 50%; left: 50%; transform: translate(-50%,-50%);">
            <source src="./animated/assets/vids/login success.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>
    </div>

		<a class="gear_icon" onclick="showPop()"><i class="fa-solid fa-gear fa-2x"></i></a>
    
    <script>
    document.addEventListener("DOMContentLoaded", function () {

	  var htmlContent = document.getElementById('left_alignment_html_content');

      const video = document.getElementById("myVideo");
	  video.addEventListener('loadedmetadata', function () {
      var startingPixelX = video.getBoundingClientRect().left;
      var videoHeight = video.getBoundingClientRect().height;

      setTimeout(function () {
        htmlContent.style.display = 'block';
        htmlContent.style.marginLeft = 'calc(15.5vw + ' + startingPixelX + 'px)';
        htmlContent.style.marginTop = videoHeight / 25 + 'vh';
      }, 2200); 
    });
	/*
      video.autoplay = true;
      video.muted = true;
    
    video.addEventListener("ended", handleVideoEnded);

    function handleVideoEnded() {
        video.pause();
        video.currentTime = 5;
        video.play();
      }
*/



 function setupPage() {
             var htmlContent = document.getElementById('left_alignment_html_content');
             const video = document.getElementById("myVideo"); 
             const toggleText = document.getElementById("toggleText1");
            const slider = document.querySelector(".slider1");

            video.autoplay = true;
            video.muted = true;    
            if( getCookie("user_cookie_animation") == 0 || getCookie("user_cookie_animation") == '' )
            {
                video.currentTime = 7;
                video.pause();

                // video.addEventListener('play', function() {
                //     setTimeout(() => {
                //         video.currentTime = 7;
                //         video.pause();
                //     }, 1);
                        
                // });

                htmlContentTimeout = 500;
                toggleText.textContent = "OFF";
                slider.style.transform = "translateX(0)";
                toggleText.style.transform = "translate(0,-50%)";

               
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
		  //alert('2');
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
         			
 if (window.devicePixelRatio==1.5 && window.innerWidth < 1300 && window.innerHeight > 500 && window.innerHeight < 650) { /*1272*572*1.5*/ htmlContent.style.marginTop = '27vh'; }
 else if(window.devicePixelRatio==1.5 && window.innerWidth<1300 && window.innerHeight>650 && window.innerHeight<700){ /*1272*665*1.5*/ htmlContent.style.marginTop = '30.5vh';}
 else if(window.devicePixelRatio == 1.75 && window.innerWidth < 1300 && window.innerHeight < 500) { /*1090*470*1.75*/ htmlContent.style.marginTop = '15vh'; }		  
 else if(window.devicePixelRatio==1.75 && window.innerWidth<1100 && window.innerHeight>500 && window.innerHeight<650){/*1098*551*1.75*/htmlContent.style.marginTop = '30vh';} 
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
 if (window.devicePixelRatio==1.5 && window.innerWidth < 1300 && window.innerHeight > 500 && window.innerHeight < 650) { /*1272*572*1.5*/ htmlContent.style.marginTop = '27vh'; }
 else if(window.devicePixelRatio==1.5 && window.innerWidth<1300 && window.innerHeight>650 && window.innerHeight<700){ /*1272*665*1.5*/ htmlContent.style.marginTop = '30.5vh';}
 else if(window.devicePixelRatio == 1.75 && window.innerWidth < 1300 && window.innerHeight < 500) { /*1090*470*1.75*/ htmlContent.style.marginTop = '15vh'; }		  
 else if(window.devicePixelRatio==1.75 && window.innerWidth<1100 && window.innerHeight>500 && window.innerHeight<650){/*1098*551*1.75*/htmlContent.style.marginTop = '30vh';} 
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
  



        var myDiv = document.getElementById("pass_reset_2_background");
        setTimeout(function() {
          myDiv.style.opacity = 0.0;
        }, 2200);
        setTimeout(function() {
          myDiv.style.opacity = 0.4;
        }, 2300);
        setTimeout(function() {
          myDiv.style.opacity = 0;
        }, 2400);
        setTimeout(function() {
          myDiv.style.opacity = 0.5;
        }, 2600);
        setTimeout(function() {
          myDiv.style.opacity = 0;
        }, 2700);
        setTimeout(function() {
          myDiv.style.opacity = 0.5;
        }, 2900);
        setTimeout(function() {
          myDiv.style.opacity = 0;
        }, 3200);
        setTimeout(function() {
          myDiv.style.opacity = 0.5;
        }, 3400);
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