
<?php
    ob_start();
    require 'includes/db.php';
    require 'includes/init.php';  
    require_once 'home-header.php';  
    if (!($user -> LoggedIn()) || !($user -> LoggedAval($odb)))
    { }
    else
    {
        header('location: index.php');
    }  
    if(isset($_SESSION['theme_mode']) && $_SESSION['theme_mode']=='light'){ $load_screen = "load_screen_light"; }else{ $load_screen = "load_screen_dark"; }
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
    
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <!--<a href="login.php">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevrons-left"><polyline points="11 17 6 12 11 7"></polyline><polyline points="18 17 13 12 18 7"></polyline></svg>
                                        <span>Back to Login</span>
                                    </a>-->
                                    <h2>Welcome to rootCapture!</h2>
                                    
                                </div>
                                <form action='' method='POST'>

                                <?php
if (!($user -> LoggedIn()) || !($user -> LoggedAval($odb)))
{  
    if (isset($_POST['registerCollegeBtn']))
    {
        $college_name = $_POST['college_name'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $repassword = $_POST['repassword'];
        $phonenumber = $_POST['phonenumber'];
        $accesscode = $_POST['accesscode'];
        $fa_preference = $_POST['2fa_preference'];

        $errors = array();
        if ( empty($college_name) || empty($username) || empty($password) || empty($repassword) || empty($accesscode) || empty($fa_preference))
        {
            $errors[] = 'ALL FIELDS NEED TO BE FILLED IN.'; 
        } 
        else if( empty($email) && empty($phonenumber) )
        {
            $errors[] = 'Email or phone is required.'; 
        }
        elseif($fa_preference==1 && empty($phonenumber)){
            $errors[] = 'Please enter phone for text 2fa perference.';
        }
        elseif($fa_preference==2 && empty($email)){  
            $errors[] = 'Please enter email for 2fa perference.';
        }
        else
        {
            if( !empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Invalid email format';
            }
            if($password != $repassword) {
                // error matching passwords
                $errors[] = 'YOUR PASSWORDS DO NOT MATCH, PLEASE RE-ENTER YOUR PASSWORDS.';                
            }
        }

        if (empty($errors))
        {    
            // try
            // {
                // $odb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                // $odb->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

                //Check for dublicate username                   
                $checkUsernameExists = $odb -> prepare("SELECT * FROM `users` WHERE `username` = :username");
                $checkUsernameExists -> execute(array(':username' => $username));

                $checkPhoneExists = $odb -> prepare("SELECT * FROM `users` WHERE `phone` = :phone");
                $checkPhoneExists -> execute(array(':phone' => $phonenumber));

                $checkEmailExists = $odb -> prepare("SELECT * FROM `users` WHERE `email` = :email");
                $checkEmailExists -> execute(array(':email' => $email));

                $isAccessTokenUsed = $odb -> prepare("SELECT id FROM `college` WHERE `access_token` = :accesscode");
                $isAccessTokenUsed -> execute(array(':accesscode' => $accesscode));
               
                if($checkUsernameExists -> rowCount() >= 1) {
                    
                    $errors[] = 'The username is already in use, please use a different username.';  
                }
                else if($checkEmailExists -> rowCount() >= 1)
                {
                    
                    $errors[] = 'The email is already in use, please use a different email.';  
                }
                else if($checkPhoneExists -> rowCount() >= 1)
                {
                    
                    $errors[] = 'The phone number is already in use, please use a different phone number.';  
                }  
                else if($isAccessTokenUsed -> rowCount() >= 1)
                {
                    
                    $errors[] = 'With this token already a college is registered, Kindly contact with admin to get new one.';  
                }                    
                else
                {     
                   
                    // Check access token for college
                        $curl = curl_init();
                        curl_setopt_array($curl, array(
                        CURLOPT_URL => 'https://rootcapture.com/adminenterprise/api/test-api',
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'POST',
                        CURLOPT_POSTFIELDS =>'{
                            "access_token" : "'.$accesscode.'"
                        }',
                        CURLOPT_HTTPHEADER => array(
                            'x-api-key: rootcap-2345-jugby-ou89',
                            'Content-Type: application/json'
                        ),
                        ));

                        $response = curl_exec($curl);
                        $res = json_decode($response,true);
                        curl_close($curl);
                        if($res['status'])
                        {
                            try {

                                $statement1 = $odb -> prepare("INSERT INTO `college`(`id`, `name`, `access_token`, `created_at`, `updated_at`) VALUES(NULL, :name, :access_token, :created_at, :updated_at)");

                                $odb->beginTransaction();
                            
                                $statement1 -> execute(array(':name' => $college_name, ':access_token' => $accesscode, ':created_at' => DATETIME, ':updated_at' => DATETIME));
                                $last_insert_id = $odb->lastInsertId();
                                $role = 1;
                            
                                if( !empty($email) && !empty($phonenumber))
                                {
                                    $SQLinsert = $odb -> prepare("INSERT INTO `users`(`ID`, `username`, `password`, `email`, `rank`,`college_id`, `phone`, `membership`, `expire`, `status`, `key`, `used`, `otp`, `otp_verification_preference`, `banned_msg`, `grading_criteria`,`datetime`) VALUES(NULL, :username, :password, :email, :rank,:college_id, :phone, 0, 0, 0, NULL, 0, Null,:otp_verification_preference,Null,0,:datetime)");
                                    $SQLinsert -> execute(array(':username' => $username, ':password' => SHA1($password), ':email' => $email, ':rank' => $role, ':college_id' => $last_insert_id, ':phone' => $phonenumber, ':otp_verification_preference' => $fa_preference,':datetime' => DATETIME));
                                }else if( !empty($email) && empty($phonenumber) )
                                { 
                                    $SQLinsert = $odb -> prepare("INSERT INTO `users`(`ID`, `username`, `password`, `email`, `rank`, `college_id`,`phone`, `membership`, `expire`, `status`, `key`, `used`, `otp`, `otp_verification_preference`, `banned_msg`, `grading_criteria`, `datetime`) VALUES(NULL, :username, :password, :email, :rank,:college_id, Null, 0, 0, 0, NULL, 0, Null,2,Null,0,:datetime)");

                                    $SQLinsert -> execute(array(':username' => $username, ':password' => SHA1($password), ':email' => $email, ':rank' => $role,':college_id' => $last_insert_id, ':datetime' => DATETIME));
                                }else if( empty($email) && !empty($phonenumber) )
                                {
                                    $SQLinsert = $odb -> prepare("INSERT INTO `users`(`ID`, `username`, `password`, `email`, `rank`, `college_id`,`phone`, `membership`, `expire`, `status`, `key`, `used`, `otp`, `otp_verification_preference`, `banned_msg`, `grading_criteria`, `datetime`) VALUES(NULL, :username, :password, Null, :rank,:college_id, :phone, 0, 0, 0, NULL, 0, Null,1,Null,0,:datetime)");
                                    $SQLinsert -> execute(array(':username' => $username, ':password' => SHA1($password), ':rank' => $role, ':college_id' => $last_insert_id, ':phone' => $phonenumber,':datetime' => DATETIME));
                                }

                                // maintain status of default teams college wise
                                $sqlDefaultTeam = $odb -> query("SELECT id FROM `teams` WHERE (`name` LIKE 'Admin' OR `name` LIKE 'Administrative Assistant' OR `name` LIKE 'Red Team' OR `name` LIKE 'Blue Team' OR `name` LIKE 'Purple Team')")->fetchAll();

                                foreach ($sqlDefaultTeam as $defaultTeamV) { 
                                    $SQLinsert = $odb -> prepare("INSERT INTO `team_status`(`id`, `team_id`, `college_id`, `status`) VALUES(NULL, :team_id, :college_id, 1)");
                                    
                                    $SQLinsert -> execute(array(':team_id' => $defaultTeamV['id'], ':college_id' => $last_insert_id));
                                }

                                $user->addRecentActivities($odb,'college_register','A new college ('.$college_name.') successfully registered on the platform.');
                                
                                // echo '<div class="message" id="message"><p><strong>SUCCESS: </strong> YOU HAVE SUCCESSFULLY JOINED THE PLATFORM!</p></div><meta http-equiv="refresh" content="3;url='.BASEURL.'login.php">';

                                //OTP AND REDIRECT TO OTP PAGE

                               

                                $SQLGetInfo = $odb -> prepare("SELECT `username`, `ID`,`status`, `phone`,`email`,`otp_verification_preference`,`banned_msg`  FROM `users` WHERE `username` = :username");
                                $SQLGetInfo -> execute(array(':username' => $username));
                                
                                $userInfo = $SQLGetInfo -> fetch(PDO::FETCH_ASSOC);
                                $last_id = $userInfo['ID']; 
                                $_SESSION['tempUsername'] = $username;
                                $_SESSION['tempID'] = $userInfo['ID'];
                                // if phone number not available
                                
                                if($fa_preference==2 ){  
                                    //email otp
                                    $digits = 6;
                                    $otp = rand(pow(10, $digits-1), pow(10, $digits)-1);
                            
                                    $img = "https://rootcapture.com/assets/img/RootCaptureResizeSmall.png";
                                    $rcurl = "https://rootcapture.com/";
                                    $html = "<center><img src='$img'/><p>Hello there,</p><p>Welcome to the rootCapture Learning Platform! Your One-Time Passcode is:</p> <p>$otp </p> <p></p><p>Sincerely,</p><p> The RootCapture Support Team</p>";
            
                                    $headers  = "From: The rootCapture Support Team <support@rootcapture.com>\n";
                                    $headers .= "MIME-Version: 1.0\r\n";
                                    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
                                    mail($email, "Otp Verification", $html, $headers);
            
                                    if( !$fa_preference )
                                    {
                                        $_SESSION['otp_veri_pre'] = 2;
                                    }
            
                                    $sucMsg = 'LOGIN SUCCESSFUL. REDIRECTING....';

                                    $updateEmailSql = $odb -> prepare("UPDATE users SET `otp` = :otp WHERE ID = :id");
                                    $updateEmailSql -> execute(array(':otp' => $otp, ':id' => $last_id));
                                    
                                    $showline++;
                                    echo '<div class="message" id="message"><p><strong>SUCCESS: </strong>'.$sucMsg.'</p></div><meta http-equiv="refresh" content="3;url=verification.php">';
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
                                    
                                    $ret = $user -> send_sms_twilio( $phone,$otp,$client );
                                

                                    if($ret['status'])
                                    {
                                    if( !$fa_preference ) {
                                        $_SESSION['otp_veri_pre'] = 1;
                                    }
                                    $sucMsg = 'LOGIN SUCCESSFUL. REDIRECTING....';            
                                    $updateEmailSql = $odb -> prepare("UPDATE users SET `otp` = :otp WHERE ID = :id");
                                    $updateEmailSql -> execute(array(':otp' => $otp, ':id' => $last_id));
                                    $showline++;
                                    echo '<div class="message" id="message"><p><strong>SUCCESS: </strong>'.$sucMsg.'</p></div><meta http-equiv="refresh" content="3;url=verification.php">';
                                    }
                                    else
                                    {
                                    $showline++;
                                    echo '<div class="error" id="message"><p><strong>ERROR: </strong>'.$ret['err_msg'].'</p></div>';
                                    }
                                }

                            //END OTP
                               
                            
                                $odb->commit();
                            } 
                            catch (\Exception $e) {
                                if ($odb->inTransaction()) {
                                    $odb->rollback();
                                    // If we got here our two data updates are not in the database
                                } 
                                $errors[] = $e;
                            }
                            // end 
                            
                        }
                        else
                        {   
                            $errors[] = $res['message'];  
                        }
                   
                    

                }
            // }
            // catch(Exception $e)
            // {
            //     print_r($e);
            // }

        }  else  {
           
        }

    }
  
}
else
{
	header('location: index.php');
}

                         
                            if($errors){
                             echo '<div class="error" id="message"><p><strong>ERROR:</strong> ';
                            
                                foreach($errors as $error)
                                {
                                    echo $error.'<br />';
                                }
                            
                             echo '</div>';
                            }
                            
                        ?>

                        <div class="col-md-12">
                       
                            <p>Create your college account !</p>
                            <div class="col-md-12"> 
                                <div class="mb-3">
                                    <label class="form-label">College Name</label>
                                    <input type="text" id="college_name" name="college_name" class="form-control">
                                </div>
                            </div>

                            <div class="col-md-12"> 
                                <div class="mb-3">
                                    <label class="form-label">Username</label>
                                    <input type="text" id='username' name="username" class="form-control">
                                </div>
                            </div>

                            <div class="col-md-12">   
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="text" id='email' name="email" class="form-control">
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="mb-4">
                                    <label class="form-label">Password</label>
                                    <input type="password" id='password' name="password" class="form-control">
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="mb-4">
                                    <label class="form-label">Confirm Password                                        
                                    </label>
                                    <input type="password" id='repassword' name="repassword" class="form-control">
                                </div>
                            </div>


                              <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label">Phone number
                                        <span class="toolContain"><a class="dropdown-toggle warning bs-tooltip" href="#" role="button" title="Phone number cannot be less than 10 or greater than 14 digits.">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-alert-octagon"><polygon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"></polygon><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12" y2="16"></line></svg>
                                        </a></span>
                                    </label>
                                    <input type="text" id='phonenumber' onkeypress="return isNumberKey(event)" name="phonenumber" class="form-control">
                                </div>
                            </div>

                            <div class="col-md-6">
                            <div class="mb-3">
                                <div class="form-label">2FA Preferences</div>
                                <div class="form-check form-check-primary form-check-inline mt-3">
                                    <input class="form-check-input" type="radio" name="2fa_preference" value="1" id="form-check-radio-default">
                                    <label class="form-check-label" for="form-check-radio-default">
                                    Text
                                    </label>
                                </div>

                                <div class="form-check form-check-primary form-check-inline mt-3">
                                    <input class="form-check-input" type="radio" name="2fa_preference" value="2" id="form-check-radio-default-checked">
                                    <label class="form-check-label" for="form-check-radio-default-checked">
                                    E-Mail
                                    </label>
                                </div>
                            </div>
                            </div>

                            <div class="col-md-12">
                       
                           
                                <div class="mb-3">
                                    <label class="form-label">Access Code</label>
                                    <input type="text" id='accesscode' name="accesscode" class="form-control">
                                </div>
                            </div>


                            <div class="col-12">
                                <div class="mb-4">
                                    <button class="btn btn-secondary w-100" input type="submit" value="Register" name="registerCollegeBtn"> Register</button>
                                </div>
                            </div>
                            
                            </div>
                       
                        </div>
                    </div>
                </div>
           
        </div>
    </div>
</div>
     </div>
    </div>

<div class="col-md-6"><div class="form-right-img">
                        <figure>
                            <img src="assets/img/form-right-img-1.png" alt="">
                        </figure>
                    </div></div>

</div>
</div>
    
<!-- BEGIN GLOBAL MANDATORY STYLES -->
    <script src="<?=BASEURL?>src/plugins/src/global/vendors.min.js"></script>
    <script src="../src/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../src/plugins/src/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="../src/plugins/src/mousetrap/mousetrap.min.js"></script>
    <script src="../layouts/vertical-light-menu/app.js"></script>

    <script>
         function isNumberKey(evt){
                var charCode = (evt.which) ? evt.which : evt.keyCode
                if (charCode > 31 && (charCode < 48 || charCode > 57))
                    return false;
                return true;
        }

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
      </script>
<?php require_once 'home-footer.php';  ?>
</body>
</html>