 <?php require_once('animated/common/header.php'); ?>
    <div class="video-background">
	        <video autoplay muted id="myVideo" preload="auto"  style="object-fit: fill;">
            <source id="videoSource" src="./animated/assets/vids/HERO PAGE.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>
	
		<div class="container hero_holder">
            <div class="hero_holder2 d-flex flex-row justify-content-center align-items-end">
                <div class="d-flex flex-md-row flex-column">
                    <a href="login.php">
                        <button class="pass_reset_btn hero_btns_left" id="hero_btns">
                            <div class="hero_btn_text">Login</div>
                        </button>
                    </a>
                    <a href="./CreateAccount.html" id="registerLink"  class="mt-md-0 mt-5">
                        <button class="pass_reset_btn hero_btns_right" id="hero_btns1">
                            <div class="hero_btn_text">Register</div>
                        </button>
                    </a>
                </div>
            </div>
        </div> 
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

            if (isMobileDevice() && isSmallScreen()) {
                // Update video source for mobile devices and small screens
                videoSource.src = "./assets/vids/BG_HERO_mobile.mp4";
                // Reload the video to apply the new source
                video.load();

                // Replace the class when mobile is detected
                var registerLink = document.getElementById("registerLink");
                if (registerLink) {
                    registerLink.classList.remove("mt-md-0", "mt-5");
                    registerLink.classList.add("mobile-hero"); // Add your new class here
                }
            }
        });
		 
		 
		 
    document.addEventListener("DOMContentLoaded", function () {
	// alert(window.innerWidth+'x'+window.innerHeight+'x'+window.devicePixelRatio);

	  const video = document.getElementById("myVideo");
      video.autoplay = true;
      video.muted = true;
    
    video.addEventListener("ended", handleVideoEnded);

    function handleVideoEnded() {
        video.pause();
        video.currentTime = 7;
        video.play();
      }



        var myDiv = document.getElementById("hero_btns");
        var myDiv2 = document.getElementById("hero_btns1");
        setTimeout(function() {
		
		if (window.devicePixelRatio < 1.70 && window.innerWidth > 1440 && window.innerHeight > 920) {
          myDiv.style.height = '150px';
          myDiv2.style.height = '150px';
          myDiv.style.width = '360px';
          myDiv2.style.width = '360px';}	  
		else if (window.devicePixelRatio > 1.70 && window.innerWidth > 1000 && window.innerHeight > 510) {
		  myDiv.style.height = '100px';
          myDiv2.style.height = '100px';
          myDiv.style.width = '240px';
          myDiv2.style.width = '240px';}
		  
		  
		else if (window.devicePixelRatio > 1.70 && window.innerWidth > 1000 && window.innerHeight > 410) {
		  myDiv.style.height = '80px';
          myDiv2.style.height = '80px';
          myDiv.style.width = '192px';
          myDiv2.style.width = '192px';}	
		  
		else if (window.devicePixelRatio > 1.25 && window.devicePixelRatio < 1.7 && window.innerWidth > 1400 && window.innerHeight > 800) {
		  myDiv.style.height = '100px';
          myDiv2.style.height = '100px';
          myDiv.style.width = '240px';
          myDiv2.style.width = '240px';}	
		  
		else if (window.devicePixelRatio > 1.25 && window.innerWidth > 1000 && window.innerHeight > 510) {
		  myDiv.style.height = '100px';
          myDiv2.style.height = '100px';
          myDiv.style.width = '240px';
          myDiv2.style.width = '240px';}	

		else if (window.devicePixelRatio > 1 && window.innerWidth > 1440 && window.innerHeight > 510) {
          myDiv.style.height = '125px';
          myDiv2.style.height = '125px';
          myDiv.style.width = '300px';
          myDiv2.style.width = '300px'; }
		
		  else { 
          myDiv.style.height = '125px';
          myDiv2.style.height = '125px';
          myDiv.style.width = '300px';
          myDiv2.style.width = '300px';
		  }
		  
        }, 900);
      });
    </script>
<?php require_once('animated/common/footer.php') ?>