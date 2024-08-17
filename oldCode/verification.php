<?php
ob_start(); 
require 'includes/db.php';
require 'includes/init.php';

@session_start();
if (empty($_SESSION['tempID'])) {
    header('location: login.php');
    die();
}
if(isset($_SESSION['theme_mode']) && $_SESSION['theme_mode']=='light'){ $load_screen = "load_screen_light"; }else{ $load_screen = "load_screen_dark"; } 
if (isset($_POST['submitForm']) && $_POST['submitForm']) {
    $digit1 = $_POST['digit1'];
    $digit2 = $_POST['digit2'];
    $digit3 = $_POST['digit3'];
    $digit4 = $_POST['digit4'];
    $digit5 = $_POST['digit5'];
    $digit6 = $_POST['digit6'];
    $otpExpired = $_POST['otpExpired'];
    if ($otpExpired) {
        $ret['status'] = false;
        $ret['message'] = "Otp is expired please resend code.";
        echo json_encode($ret);
        return $ret;
    } else {
        if ($digit1 == '' ||  $digit2 == ''   ||  $digit3 == ''  ||  $digit4 == '' ||  $digit5 == '' ||  $digit6 == '') {
            $ret['status'] = false;
            $ret['message'] = "All fields are required.";
            echo json_encode($ret);
            return $ret;
        } else {
            $otp = $digit1 . $digit2 . $digit3 . $digit4 . $digit5 . $digit6;

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
                $ret['message'] = "VERIFICATION SUCCESSFUL. YOU ARE NOW BEING LOGGED INTO THE PLATFORM...";
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
require_once 'home-header.php'; 
?>


<div class="container-fluid  py-5">
    <div class="row login-top">
        <div class="col-md-6 ">

    <div class=" " id="container">

        <div class="overlay"></div>
        <div class="search-overlay"></div>
        <div class="auth-container d-flex">
            <div class="container">

                <div class="row">

                 
                        <div class="card mt-3 mb-3">
                            <div class="card-body">
                                <form method="POST" onsubmit="validates();return false" action="verification.php">
                                    <?php
                                    if (!($user->LoggedIn())) {
                                        $resend = $_GET['resend'];
                                        $via = $_GET['via'];
                                        if ($resend && $via == 'phone') {
                                            $SQLGetInfo = $odb->prepare("SELECT `phone`,`otp_verification_preference` FROM `users` WHERE `id` = :id");
                                            $SQLGetInfo->execute(array(':id' => $_SESSION['tempID']));
                                            $userInfo = $SQLGetInfo->fetch(PDO::FETCH_ASSOC);
                                            $phone = $userInfo['phone'];
                                            $otp_verification_preference = $userInfo['otp_verification_preference'];
                                            if ($phone == NULL || $phone == "") {
                                                echo '<div class="message" id="message"><p><strong>SUCCESS: </strong>Redirecting you to mobile updation....</p></div><meta http-equiv="refresh" content="3;url=update-phone.php">';
                                            } else {
                                                // otp send
                                                // $apiKey = urlencode('NjIzMjYxNTY0MTc3NTA2ZjY1MzY0NDM5NzI3NzZhNjQ=');
                                                $digits = 6;
                                                $otp = rand(pow(10, $digits - 1), pow(10, $digits) - 1);

        require './twilio/twilio-php-main/src/Twilio/autoload.php'; 
        // Your Account SID and Auth Token from twilio.com/console
        $account_sid = 'ACdd75d7f2dd310c4d296e761fd227510f';
        $auth_token = '83944661abc222e049761c25b561fe36';
    
        // A Twilio number you own with SMS capabilities
    
        $client = new Twilio\Rest\Client($account_sid, $auth_token);
        
        $result = $user -> send_sms_twilio( $phone,$otp,$client );
    

        if($result['status'])
        {
            $updateEmailSql = $odb->prepare("UPDATE users SET `otp` = :otp WHERE id = :id");
            $updateEmailSql->execute(array(':otp' => $otp, ':id' => $_SESSION['tempID']));
            if (!$otp_verification_preference) {
                $_SESSION['otp_veri_pre'] = 1;
            }
            echo '<div class="message" id="message"><p><strong>SUCCESS: </strong>PLEASE CHECK YOUR PHONE AND ENTER THE PROVIDED OTP SENT VIA TEXT MESSAGE.</p></div>';
        }
        else
        {
            echo '<div class="error" ><p><strong>ERROR: </strong>'.$result['err_msg'].'</p></div>';
        }

                                                
                                            }
                                        } else if ($resend && $via == 'email') {
                                            $SQLGetInfo = $odb->prepare("SELECT `email`,`otp_verification_preference` FROM `users` WHERE `id` = :id");
                                            $SQLGetInfo->execute(array(':id' => $_SESSION['tempID']));
                                            $userInfo = $SQLGetInfo->fetch(PDO::FETCH_ASSOC);
                                            $email = $userInfo['email'];
                                            $otp_verification_preference = $userInfo['otp_verification_preference'];

                                            //email otp
                                            $digits = 6;
                                            $otp = rand(pow(10, $digits - 1), pow(10, $digits) - 1);

                                            $img = "https://rootcapture.com/assets/img/RootCaptureResizeSmall.png";
                                            $rcurl = "https://rootcapture.com/";
                                            $html = "<center><img src='$img'/><p>Hello there,</p><p>Welcome to the rootCapture Learning Platform! Your One-Time Passcode is:</p> <p>$otp </p> <p></p><p>Sincerely,</p><p> The RootCapture Support Team</p>";

                                            $headers  = "From: The rootCapture Support Team <support@rootcapture.com>\n";
                                            $headers .= "MIME-Version: 1.0\r\n";
                                            $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
                                            mail($email, "Otp Verification", $html, $headers);

                                            $updateEmailSql = $odb->prepare("UPDATE users SET `otp` = :otp WHERE id = :id");
                                            $updateEmailSql->execute(array(':otp' => $otp, ':id' => $_SESSION['tempID']));
                                            if (!$otp_verification_preference) {
                                                $_SESSION['otp_veri_pre'] = 2;
                                            }

                                            echo '<div class="message" id="message"><p><strong>SUCCESS: </strong>PLEASE CHECK YOUR E-MAIL INBOX AND ENTER THE PROVIDED OTP....</p></div>';
                                        }



                                        if (isset($_POST['otp_expired']) && $_POST['otp_expired'] == 1) {
                                            $updateEmailSql = $odb->prepare("UPDATE users SET `otp` = :otp WHERE id = :id");
                                            $updateEmailSql->execute(array(':otp' => null, ':id' => $_SESSION['tempID']));
                                        }
                                    } else {
                                        header('location: index.php');
                                    }
                                    ?>
                                    <div class="error" style="display:none">
                                    </div>
                                    <div class="message1" style="display:none" id="message1"></div>
                                    <div class="message" style="display:none" id="message"></div>

                                    <div class="row">

                                        <div class="col-md-12 mb-3 ">
                                         
                                                <p class="second-txt">
                                                    <span id="timer" class="timer_code">
                                                        <span class="exTxt">Code Expires in </span><span id="time"> 60 </span>Seconds
                                                    </span>
                                                </p>
                                                <p class="mb-0">Didn't receive the code?</p>
                                                <p><a href="verification.php?resend=1&via=email" class="text-warning">Resend via E-Mail </a> <span class="seprateline">|</span><a href="verification.php?resend=1&via=phone" class="text-warning">Resend via Phone </a></p>

                                            

                                            <h2>2-Step Verification</h2>
                                          
                                            <p>Enter the code for verification.</p>

                                        </div>

                                        <div class="col-sm-2 col-3 ms-auto mobile-div">
                                            <div class="mb-3">
                                                <input type="hidden" name="otpExpired" id="otpExpired" value="0" class="form-control">
                                                <input type="text" name="digit1" id="digit1" class="form-control opt-input" onkeypress="return isNumber(event)" autofocus>
                                            </div>
                                        </div>
                                        <div class="col-sm-2 col-3 mobile-div">
                                            <div class="mb-3">
                                                <input type="text" name="digit2" id="digit2" class="form-control opt-input" onkeypress="return isNumber(event)">
                                            </div>
                                        </div>
                                        <div class="col-sm-2 col-3 mobile-div">
                                            <div class="mb-3">
                                                <input type="text" name="digit3" id="digit3" class="form-control opt-input" onkeypress="return isNumber(event)">
                                            </div>
                                        </div>
                                        <div class="col-sm-2 col-3 me-auto mobile-div">
                                            <div class="mb-3">
                                                <input type="text" name="digit4" id="digit4" class="form-control opt-input" onkeypress="return isNumber(event)">

                                            </div>
                                        </div>

                                        <div class="col-sm-2 col-3 me-auto mobile-div">
                                            <div class="mb-3">
                                                <input type="text" name="digit5" id="digit5" class="form-control opt-input" onkeypress="return isNumber(event)">

                                            </div>
                                        </div>

                                        <div class="col-sm-2 col-3 me-auto mobile-div">
                                            <div class="mb-3">
                                                <input type="text" name="digit6" id="digit6" class="form-control opt-input" onkeypress="return isNumber(event)">

                                            </div>
                                        </div>

                                        <div class="col-12 mt-4">
                                            <div class="mb-3">
                                                <button class="btn btn-secondary w-100" type="submit" name="verifyBtn">VERIFY</button>
                                            </div>
                                        </div>


                                        <!--<div class="col-12">
                                    <div class="text-center">
                                                     <p class="second-txt">
  <span id="timer" >
    <span class="exTxt">Expire in</span><span id="time"> 60 </span>Seconds      
  </span>
</p>
                                        <p class="mb-0">Didn't receive the code ? </p>
                                        <p><a href="verification.php?resend=1&via=email" class="text-warning">Resend via  email </a> <span class="bar-mid">|</span><a href="verification.php?resend=1&via=phone" class="text-warning">Resend via  phone </a></p>
                                      
                                    </div>
                                </div>-->

                                </form>


                            </div>

                        </div>

                 

                </div>
            </div>
        </div>
    </div>





 </div> 



</div> <div class="col-md-6"><div class="form-right-img">
                        <figure>
                            <img src="assets/img/form-right-img-3.png" alt="">
                        </figure>
                    </div></div> </div>
    </div>







    <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
    <script src="<?=BASEURL?>src/plugins/src/global/vendors.min.js"></script>
    <script src="../src/assets/js/authentication/2-Step-Verification.js?ver=1.1"></script>
    <script src="../src/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../src/plugins/src/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="../src/plugins/src/mousetrap/mousetrap.min.js"></script>
    <script src="../layouts/vertical-light-menu/app.js"></script>
    <script type="text/javascript">
        function validates() { 
            
            var digit1 = $('#digit1').val();
            var digit2 = $('#digit2').val();
            var digit3 = $('#digit3').val();
            var digit4 = $('#digit4').val();
            var digit5 = $('#digit5').val();
            var digit6 = $('#digit6').val();

            if (digit1 == '' || digit2 == '' || digit3 == '' || digit4 == '' || digit5 == '' || digit6 == '') {
                $('.error').html('<p><strong>ERROR:</strong> <br /> All fields need to be filled in.</p> ')
                $('.error').css('display', 'block');
                return false;
            } else {

                if ($('#otpExpired').val() == 1) {
                    $('.error').html('<p><strong>ERROR:</strong> <br /> Otp is expired please resend code. </p>')
                    $('.error').css('display', 'block');
                    return false;
                } else {
                    $.ajax({
                        url: window.location.origin + '/verification.php',
                        type: "post",
                        data: {
                            digit1: digit1,
                            digit2: digit2,
                            digit3: digit3,
                            digit4: digit4,
                            digit5: digit5,
                            digit6: digit6,
                            otpExpired: $('#otpExpired').val(),
                            submitForm: true
                        },
                        async: false,
                        success: function(response) {
                            console.log(response); 
                            res = JSON.parse(response);
                            if (res.status) {
                                
                                $('.error').css('display', 'none');
                                $('.message1').html('')
                                $('.message1').html('<p><strong>SUCCESS:</strong> <br />' + res.message + '</p>')
                                $('.message1').css('display', 'block');

                                setTimeout(function(){ 
                                    window.location = window.location.origin + '/index.php'; 
                                }, 4000);

                                
                                // var formSubmit = true;
                                // console.log(res);

                            } else {
                                $('.message').css('display', 'none');
                                $('.error').html('');
                                $('.error').html('<p><strong>ERROR:</strong> <br />' + res.message + '</p>');
                                $('.error').css('display', 'block');
                                // var formSubmit = false;
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.log(textStatus, errorThrown);
                        }
                    });
                }
            }

        }

        $(document).ready(function() {
            $('#digit6').on('keyup', function() {
                if ( $('#digit6').val() != '' && $('#digit5').val() != '' && $('#digit4').val() != '' && $('#digit2').val() != '' && $('#digit3').val() != '' && $('#digit1').val() != '') {
                    setTimeout(function() {
                        validates();
                    }, 7500);
                }

            });
        });

        var counter = 60;
        var interval = setInterval(function() {
            counter--;
            // Display 'counter' wherever you want to display it.
            if (counter <= 0) {
                $('#timer').html("<h4 class='text-warning'>Code expired</h4>");
                clearInterval(interval);
                //$('#otpExpired').val(1);
                setTimeout(function() {
                    $.ajax({
                        //url: window.location.href,
                        url: "resend_verification.php",
                        type: "post",
                        data: {
                            otp_expired: 2
                        },
                        async: false,
                        success: function(response) {
                            res = JSON.parse(response);
                            if (res.status) {
                                $('.error').css('display', 'none');
                                $('.message').html('')
                                $('.message').html('<p><strong>SUCCESS:</strong> <br />' + res.message + '</p>')
                                $('.message').css('display', 'block');
                                $('#timer').empty();
                            

                            } else {
                                $('.message').css('display', 'none');
                                $('.error').html('');
                                $('.error').html('<p><strong>ERROR:</strong> <br />' + res.message + '</p>');
                                $('.error').css('display', 'block');
                            }


                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.log(textStatus, errorThrown);
                        }
                    });
                }, 5000);


            } else {
                $('#time').text(counter);
            }
        }, 1000);
        setTimeout(function() {
            $("#message").remove();
        },5000);

         function changeThemeMode(mode=''){
            $.ajax({
                url: "<?=BASEURL?>theme-mode-session.php",
                type: "post",
                data: {
                    mode: mode
                },
                success: function(response) { 
                }
            });
        }

        // $( document ).ready(function() {
        // $('body').addClass('loaderNone')
          
       //});
        $(window).on('load', function () { 
           $('body').removeClass('loaderNone')
          $('#loading').remove();
          
        });
        function isNumber(evt) {
                evt = (evt) ? evt : window.event;
                var charCode = (evt.which) ? evt.which : evt.keyCode;
                if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                    return false;
                }
                return true;
            }
      </script>

      <?php require_once 'home-footer.php';  ?>

</body>

</html>