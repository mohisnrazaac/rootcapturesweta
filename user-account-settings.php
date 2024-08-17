<?php
ob_start();
require_once 'includes/db.php';
require_once 'includes/init.php';
@session_start();
if (!($user -> LoggedIn()))
{
	if(isset($_GET['r'])){
		$_SESSION['refer'] = preg_replace("/[^A-Za-z0-9-]/","", $_GET['r']);
		header('Location: login.php');
		die();
	}
	header('location: login.php');
	die();
}
if (!($user -> notBanned($odb)))
{
	header('location: login.php');
	die();
}

$SQLGetInfo = $odb -> prepare("SELECT *  FROM `users` WHERE `ID` = :ID");
$SQLGetInfo -> execute(array(':ID' => $_SESSION['ID']));               
$userInfo = $SQLGetInfo -> fetch(PDO::FETCH_ASSOC);
$editphone = $userInfo['phone'];
$editemail = $userInfo['email'];
$username = $userInfo['username'];
$otp_verification_preference = $userInfo['otp_verification_preference'];

$pageTitle = 'My Account';
require_once 'header.php';

$accountSet = 'active';
$FASet = '';
$resetSet = '';
if(isset($_POST['update2FA'])){
    $FASet = 'active';
    $accountSet = '';
    $resetSet = '';
}
if(isset($_POST['updatePassword'])){
    $FASet = '';
    $accountSet = '';
    $resetSet = 'active';
}


?>
    <!--  BEGIN MAIN CONTAINER  -->
    <style>
	
    .name_user{
       display: block;
       height: 100%;
       line-height: 2.2;
       padding: 10px;
       border:1px solid #eee;
   }
   .min-width-8rem	{min-width:8rem;}
   
   ul.status_user{padding:0 15px;}
   
   ul.status_user.m-0 li {
       list-style-type: none;
       padding:4px 0;
   } 
   .avatar-online:before {
       background-color: #00ab55 !important;
   }
   .avatar-online.offlineme:before {
       background-color: #ff0000 !important;
   }
   body.layout-boxed.dark .color-swatcher{ background:#1b2e4b;}
   body.layout-boxed .color-swatcher{ background:#fff;}
   body.layout-boxed .color-swatcher ul li a{ color:#515365;}
   body.layout-boxed.dark .color-swatcher ul li a{ color:#fff;}
   body.layout-boxed .name_user{border:1px solid #bfc9d4;}
   
   @media(max-width:767px){
       
   body.layout-boxed .color-swatcher {
     transform: translate3d(150px, 444px, 0px) !important;
   }
       
       
   }
   
   
   @media(min-width:767px) and (max-width:991px){
       
   body.layout-boxed .color-swatcher {
     transform: translate3d(87px, 444px, 0px) !important;
   }
       
       
   }
   
   
       </style>
    <div class="main-container" id="container">

        <div class="overlay"></div>
        <div class="search-overlay"></div>

        <!--  BEGIN SIDEBAR  --> 
<?php include 'sidebar.php'; ?>
        <!--  END SIDEBAR  -->

        <!--  BEGIN CONTENT AREA  -->
        <div id="content" class="main-content">
            <div class="layout-px-spacing">

                <div class="middle-content container-xxl p-0">

                    <!-- BREADCRUMB -->
                    <div class="page-meta">
                        <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">My Account</li>
                                <li class="breadcrumb-item active" aria-current="page">Account Settings</li>
                            </ol>
                        </nav>
                    </div>
                    <!-- /BREADCRUMB -->
                        
                    <div class="account-settings-container layout-top-spacing">
    
                        <div class="account-content">
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <h2>Settings</h2>
        
                                    <div class="animated-underline-content">
                                        <ul class="nav nav-tabs" id="animateLine" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link <?=$accountSet?>" id="animated-underline-home-tab" data-bs-toggle="tab" href="#animated-underline-home" role="tab" aria-controls="animated-underline-home" aria-selected="true"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="$_SESSION['username']round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg> Account Settings</a>
                                            </li>

                                            <li class="nav-item">
                                                <a class="nav-link <?=$FASet?>" id="animated-underline-2FA-tab" data-bs-toggle="tab" href="#animated-underline-2FA" role="tab" aria-controls="animated-underline-2FA" aria-selected="false"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="$_SESSION['username']round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg> 2FA Change</a>
                                            </li>

                                            <li class="nav-item">
                                                <a class="nav-link" id="animated-underline-password-tab" data-bs-toggle="tab" href="#animated-underline-password" role="tab" aria-controls="animated-underline-password" aria-selected="false">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-lock"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>    
                                                Reset Password</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
 
                            <div class="tab-content" id="animateLineContent-4">
                                <div class="tab-pane fade show <?=$accountSet?>" id="animated-underline-home" role="tabpanel" aria-labelledby="animated-underline-home-tab">
                                    <div class="row">
                                        <div class="col-xl-12 col-lg-12 col-md-12 layout-spacing">
                                            <form method="POST" class="section general-info">
                                                <div class="info">
                                                    <div align="Center"><h6 class="">Your Account Information</h6>
                                                        <?php 
                                                            if(isset($_POST['updatePassBtn'])){
                                                                $FASet = '';
                                                                $accountSet = 'active';
                                                                $resetSet = '';
                                                                $cpassword = $_POST['cpassword'];
                                                                $npassword = $_POST['npassword'];
                                                                $rpassword = $_POST['rpassword'];
                                                                $email = $_POST['email'];
                                                                $phone = $_POST['phone'];
                                                                // $fa_preference = $_POST['2fa_preference'];
                                                                // if( isset($fa_preference) &&  $fa_preference == 'on')
                                                                // {
                                                                //     $fa_preference = 1;
                                                                // }
                                                                // else
                                                                // {
                                                                //     $fa_preference = 2;
                                                                // } 

                                                                if(!empty($cpassword) && !empty($email) && !empty($phone)){

                                                                    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                                                                        echo '<div class="error" id="message"><p><strong>ERROR: </strong>Invalid Email!</p></div>';
                                                                    }
                                                                    else
                                                                    {
                                                                        
                                                                            $SQLCheckCurrent = $odb->prepare("SELECT COUNT(*) FROM `users` WHERE `username` = :username AND `password` = :password");
                                                                            $SQLCheckCurrent->execute(array(':username' => $_SESSION['username'], ':password' => SHA1($cpassword)));
                                                                            $countCurrent = $SQLCheckCurrent -> fetchColumn(0);
                                                                            if($countCurrent == 1){
                                                                                $SQLUpdate = $odb->prepare("UPDATE `users` SET  `email` = :email, `phone` = :phone WHERE `username` = :username AND `ID` = :id");
                                                                                $SQLUpdate->execute(array(':email' => $email,':phone' => $phone,':username' => $_SESSION['username'], ':id' => $_SESSION['ID']));

                                                                                // $SQLUpdate->execute(array(':password' => SHA1($npassword),':email' => $email,':phone' => $phone,':otp_verification_preference' => $fa_preference,':username' => $_SESSION['username'], ':id' => $_SESSION['ID']));

                                                                                echo '<div class="message" id="message"><p><strong>SUCCESS: </strong>Your password has been updated! You are now being redirected back to the platform!</p></div><meta http-equiv="refresh" content="1;url=user-account-settings.php">';
                                                                            } else {
                                                                                echo '<div class="error" id="message"><p><strong>ERROR: </strong>Your current password is incorrect!</p></div>';
                                                                            }
                                                                       
                                                                    }
                                                                    
                                                                } else {
                                                                    echo '<div class="error" id="message"><p><strong>ERROR: </strong>Please fill in all of the fields!</p></div>';
                                                                }
                                                            }

                                                            
                                                        ?>
                                                    <div class="row">
                                                        <div class="col-lg-11 mx-auto">
                                                            <div class="row">
													     <div class="col-xl-2 col-lg-4 col-md-4 mt-3">
														
                                                        <div class="user-profile-dropdown  order-lg-0 order-1">
														<a href="javascript:void(0);" class="nav-link dropdown-toggle user" id="userProfileDropdowns" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
															<div class="avatar-container">
															<div class="avatar avatar-lg avatar-indicators avatar-online bg-warning rounded-circle <?php if(isset($userInfo['available_status']) && $userInfo['available_status']==1){ echo "offlineme"; } ?>">
															<span class=" name_user rounded-circle"><?php echo strtoupper(substr($_SESSION['username'], 0, 2)); ?></span>
															
															
															  </div>
															 </div>
														    </a>
                                                            <p><?php echo $_SESSION['username']; ?></p>
														<div class="dropdown-menu position-absolute color-swatcher min-width-8rem" aria-labelledby="userProfileDropdowns" style="">
															<div class="user-profile-section">
																<div class="media mx-auto">
																	
																	<div class="media-body ">
																	<ul class="avaliblity_option status_user m-0">
																		<li status="0"><a href="javascript:void(0)"><svg xmlns="http://www.w3.org/2000/svg" fill="#00ab55" width="20" height="20" viewBox="0 0 512 512"><path d="M504 256c0 136.967-111.033 248-248 248S8 392.967 8 256 119.033 8 256 8s248 111.033 248 248zM227.314 387.314l184-184c6.248-6.248 6.248-16.379 0-22.627l-22.627-22.627c-6.248-6.249-16.379-6.249-22.628 0L216 308.118l-70.059-70.059c-6.248-6.248-16.379-6.248-22.628 0l-22.627 22.627c-6.248 6.248-6.248 16.379 0 22.627l104 104c6.249 6.249 16.379 6.249 22.628.001z"/></svg> Online</a></li>
																		<li status="1"><a href="javascript:void(0)"><svg xmlns="http://www.w3.org/2000/svg" fill="#ff0000" width="20" height="20" viewBox="0 0 512 512"><path d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zM124 296c-6.6 0-12-5.4-12-12v-56c0-6.6 5.4-12 12-12h264c6.6 0 12 5.4 12 12v56c0 6.6-5.4 12-12 12H124z"/></svg> Offline</a></li>
																		</ul>
																	</div>
																</div>
															</div>
														
																
															</div>
															</div> 
														</div>
														
													
																								
														 <div class="col-xl-10 col-lg-8 col-md-8 mt-md-0 mt-4">
                                                                    <div class="form">
                                                                        <div class="row">
                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label for="tAssigned">Team Assigned</label>
                                                                                    <input type="text" class="form-control mb-3" id="tAssigned" value="<?php if ($row['rank'] == "1")
									{
										echo "Administrative Member";
									}
									elseif ($row['rank'] == "2")
									{
										echo "Staff Member";
									}
									elseif ($row['rank'] == "3")
									{
										echo "Red Team Member";
									}
									elseif ($row['rank'] == "4")
									{
										echo "Blue Team Member";
									}
									elseif ($row['rank'] == "5")
									{
										echo "Purple Team Member";
									}
									else {
										echo "No Team Assigned";
									}?>" readonly>
                                                                                </div>
                                                                            </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="username">Username</label>
                                        <input type="text" class="form-control mb-3" id="username" value="<?php echo $_SESSION['username']; ?>" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="text" class="form-control mb-3" value="<?=$editemail?>" name="email" id="email">
                                    </div>
                                </div>  
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phone">Phone</label>
                                        <input type="text" onkeypress="return isNumberKey(event)" class="form-control mb-3" value="<?=$editphone?>" name="phone" id="phone">
                                    </div>
                                </div>  
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phone">Enter Your Current Password</label>
                                        <input type="password" class="form-control mb-3" name="cpassword" id="cpassword" placeholder="*********"  value="">
                                    </div>
                                </div>  
                                <!-- <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phone">Enter Your New Desired Password</label>
                                        <input type="password" class="form-control mb-3" name="npassword" id="npassword" value="************">
                                    </div>
                                </div>		
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phone">Confirm Your New Desired Password</label>
                                        <input type="password" class="form-control mb-3" name="rpassword" id="rpassword" value="**********">
                                    </div>
                                </div> -->


                       

                                <div class="col-md-12 mt-1">
                                    <div class="form-group text-end">
                                        <button type="submit" name="updatePassBtn" class="btn btn-outline-success btn-lrg">Update My Account Settings</button>
                                    </div>
                                </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
															</div>
                                                        </div>
                                                    </div>
                                                
                                            </form>
                                        </div>
                                    </div>
								</div> 
                                
                            </div>

                        <!-- second -->
                        <div class="tab-pane fade show <?=$FASet?>" id="animated-underline-2FA" role="tabpanel" aria-labelledby="animated-underline-2FA-tab">
                                <div class="row">
                                    <div class="col-xl-12 col-lg-12 col-md-12 layout-spacing">
                                        <form method="POST" class="section general-info">
                                                <div class="info">
                                                    <div align="Center"><h6 class="">2FA Preferences Change</h6></div>
                                                    <?php
                                                        if(isset($_POST['update2FA'])){
                                                            $FASet = 'active';
                                                            $accountSet = '';
                                                            $resetSet = '';
                                                            $FAPassword = $_POST['password'];
                                                            $fa_preference = $_POST['2fa_preference'];
                                                            if(!empty($FAPassword)){
                                                                $SQLCheckLogin = $odb -> prepare("SELECT COUNT(*) FROM `users` WHERE BINARY  `username` = :username AND `password` = :password");
                                                                $SQLCheckLogin -> execute(array(':username' => $username, ':password' => SHA1($FAPassword)));
                                                                $countLogin = $SQLCheckLogin -> fetchColumn(0);
                                                                if ($countLogin == 1)
                                                                { 
                                                                    if( isset($fa_preference) &&  $fa_preference == 'on')
                                                                    {
                                                                        $otp_verification_preference = 1;
                                                                        
                                                                    }
                                                                    else
                                                                    {
                                                                        $otp_verification_preference = 2;
                                                                    }
                                                                    
                                                                    $SQLUpdate = $odb->prepare("UPDATE `users` SET `otp_verification_preference` = :otp_verification_preference WHERE `ID` = :id");

                                                                    $SQLUpdate->execute(array(':otp_verification_preference' => $otp_verification_preference, ':id' => $_SESSION['ID']));
                                                                    echo '<div class="message" id="message"><p><strong>SUCCESS: </strong>Updated!</p></div>';

                                                                }else{
                                                                    echo '<div class="error" id="message"><p><strong>ERROR: </strong>Your current password is incorrect!</p></div>';
                                                                }
                                                            }else{
                                                                echo '<div class="error" id="message"><p><strong>ERROR: </strong>Please Enter current password</p></div>';
                                                            }
                                                        
                                                        }
                                                    ?>
                                                    <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="phone">Enter Current Password</label>
                                                            <input type="password" class="form-control mb-3" name="password" id="password" placeholder="********">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="headingForRadio">2FA Preferences</div>
                                                            <div class="form-check form-check-primary form-check-inline mt-3">
                                                                    <input class="form-check-input" <?php if($otp_verification_preference == 1){echo 'checked';} ?> type="radio" name="2fa_preference" id="form-check-radio-default">
                                                                    <label class="form-check-label" value="1" for="form-check-radio-default">
                                                                    Text
                                                                    </label>
                                                                </div>

                                                                <div class="form-check form-check-primary form-check-inline mt-3">
                                                                    <input class="form-check-input" type="radio" name="2fa_preference" id="form-check-radio-default-checked" value="2" <?php if($otp_verification_preference == 2){echo 'checked';} ?>>
                                                                    <label class="form-check-label" for="form-check-radio-default-checked">
                                                                        E-Mail
                                                                    </label>
                                                                </div>
                                                    </div>
                                                    </div>
                                        

                                                    <div class="col-md-12 mt-1">
                                                        <div class="form-group text-end">
                                                            <button type="submit" name="update2FA" class="btn btn-outline-success btn-lrg">Update 2FA</button>
                                                        </div>
                                                    </div>
                                                </div>
                                        </form>

                                    </div>
                            </div>
                        </div>

                        <!-- end second -->

                           <!-- third -->
                        <div class="tab-pane fade show <?=$resetSet?>" id="animated-underline-password" role="tabpanel" aria-labelledby="animated-underline-password-tab">
                                <div class="row">
                                    <div class="col-xl-12 col-lg-12 col-md-12 layout-spacing">
                                        <form method="POST" class="section general-info">
                                                <div class="info">
                                                    <div align="Center"><h6 class="">Reset Password</h6></div>
                                                    <?php
                                                        if(isset($_POST['updatePassword'])){
                                                            $FASet = '';
                                                            $accountSet = '';
                                                            $resetSet = 'active';
                                                            $cpassword = $_POST['cpassword'];
                                                            $npassword = $_POST['npassword'];
                                                            $rpassword = $_POST['rpassword'];
                                                            if(!empty($cpassword) && !empty($npassword) && !empty($rpassword)){
                                                                if($npassword == $rpassword){
                                                                    $SQLCheckLogin = $odb -> prepare("SELECT COUNT(*) FROM `users` WHERE BINARY  `username` = :username AND `password` = :password");
                                                                    $SQLCheckLogin -> execute(array(':username' => $username, ':password' => SHA1($cpassword)));
                                                                    $countLogin = $SQLCheckLogin -> fetchColumn(0);
                                                                    if ($countLogin == 1)
                                                                    { 
                                                                       
                                                                        
                                                                        $SQLUpdate = $odb->prepare("UPDATE `users` SET `password` = :password WHERE `ID` = :id");
    
                                                                        $SQLUpdate->execute(array(':password' => SHA1($npassword), ':id' => $_SESSION['ID']));

                                                                        echo '<div class="message" id="message"><p><strong>SUCCESS: </strong>Updated!</p></div>';
    
                                                                    }else{
                                                                        echo '<div class="error" id="message"><p><strong>ERROR: </strong>Your current password is incorrect!</p></div>';
                                                                    }
                                                                }else{
                                                                    echo '<div class="error" id="message"><p><strong>ERROR: </strong>New Password or confirm password not matched!</p></div>';
                                                                }
                                                               
                                                            }else{
                                                                echo '<div class="error" id="message"><p><strong>ERROR: </strong>Please Fill all fields</p></div>';
                                                            }
                                                        
                                                        }
                                                    ?>
                                                    <div class="row">
                                                   
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="phone">Enter Your Current Password</label>
                                                            <input type="password" class="form-control mb-3" name="cpassword" id="cpassword" placeholder="*********"  value="">
                                                        </div>
                                                    </div>  
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="phone">Enter Your New Desired Password</label>
                                                            <input type="password" class="form-control mb-3" name="npassword" id="npassword" placeholder="************">
                                                        </div>
                                                    </div>		
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="phone">Confirm Your New Desired Password</label>
                                                            <input type="password" class="form-control mb-3" name="rpassword" id="rpassword" placeholder="**********">
                                                        </div>
                                                    </div>
                                                    </div>
                                        

                                                    <div class="col-md-12 mt-1">
                                                        <div class="form-group text-end">
                                                            <button type="submit" name="updatePassword" class="btn btn-outline-success btn-lrg">Update Password</button>
                                                        </div>
                                                    </div>
                                                </div>
                                        </form>

                                    </div>
                            </div>
                        </div>

                        <!-- end third -->


                            
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
            <!--  BEGIN FOOTER  -->  <?php require_once 'includes/footer-section.php'; ?>            <!--  END FOOTER  -->
            
        </div>
        <!--  END CONTENT AREA  -->
    </div>
    <!-- END MAIN CONTAINER -->
    <?php  require_once 'footer.php'; ?>

    <script>
         function isNumberKey(evt){
                var charCode = (evt.which) ? evt.which : evt.keyCode
                if (charCode > 31 && (charCode < 48 || charCode > 57))
                    return false;
                return true;
        }

        $(document).on("click",".avaliblity_option li",function(){
            var status = $(this).attr("status");
            if(status==0){
                $(".avatar.avatar-lg.avatar-indicators").removeClass("offlineme");
            }else{
                $(".avatar.avatar-lg.avatar-indicators").addClass("offlineme");
            }
            $.ajax({
            url:"chat.php",
                method:"POST",
                data:{action:'available_status',available_status:status},
                dataType: "json",
                success:function(response){
                    
                }
            });
        });
     
    </script>
</body>
</html>