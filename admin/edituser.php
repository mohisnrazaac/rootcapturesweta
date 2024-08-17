<?php
ob_start();
require_once '../includes/db.php';
require_once '../includes/init.php';
if (!($user -> LoggedIn()))
{
	header('location: ../login.php');
	die();
}
if (!($user -> notBanned($odb)))
{
	header('location: ../login.php');
	die();
}

if ( !($user -> isAssist($odb) || $user -> isAdmin($odb))) {
    header('location: ../index.php');
	die();
}
            
        $editId =  $_GET['editId'];
        $danzerActive =  false;
        $bannedSucMsg = '';
        $getUserDetailIdWise = $user->getUserDetailIdWise($odb); 
        $college_id = $getUserDetailIdWise['college_id']; 
        $teamList  = $odb -> query("SELECT teams.* FROM `teams` INNer JOIN team_status ON teams.id = team_status.team_id  WHERE teams.name != 'Admin' AND college_id = $college_id AND status = 1   ORDER BY id ASC")->fetchAll();
		if (isset($_POST['updateUser']))
		{
			$username = $_POST['username'];
			$email = $_POST['email'];
			$password = $_POST['password'];
			$repassword = $_POST['repassword'];
			$role = $_POST['role'];
			$phone = $_POST['phone'];
            $fa_preference = $_POST['2fa_preference'];
            
            $restrictchat = $_POST['restrict_chat'];
            $assistant_permissions = $_POST['assistant_permissions'];
            if($restrictchat==''){
                $restrict = 0;
            }else{
                $restrict = 1;
            }       
            
			$errors = array();
			if (empty($username) || empty($role) || empty($fa_preference))
			{
				$errors[] = 'Please verify all fields'; 
			} 
            else if( empty($email) && empty($phone) )
            {
                $errors[] = 'Email or phone is required.'; 
            }elseif(!empty($fa_preference) && $fa_preference==1 && $phone==''){
                $errors[] = 'Please enter phone for text 2fa perference.';
            }
            else
            {
                if( !empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                 $errors[] = 'Invalid email format';
                }

                if( ( $password != '' && $repassword != '' ) && ($password != $repassword) ) {
                    // error matching passwords
                    $errors[] = 'Your passwords and confirm password do not match. Please type carefully.';                
                }               
            } 

			if (empty($errors))
			{ 
                //  try
                // {
                //     $odb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                //     $odb->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

                //Check for dublicate username
                   
                $checkUsernameExists = $odb -> prepare("SELECT * FROM `users` WHERE `username` = :username AND `ID` != :id");
                $checkUsernameExists -> execute(array(':username' => $username,':id' => $editId));

                $checkPhoneExists = $odb -> prepare("SELECT * FROM `users` WHERE `phone` = :phone AND `ID` != :id");
                $checkPhoneExists -> execute(array(':phone' => $phone,':id' => $editId));

                $checkEmailExists = $odb -> prepare("SELECT * FROM `users` WHERE `email` = :email AND `ID` != :id");
                $checkEmailExists -> execute(array(':email' => $email,':id' => $editId));

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
                else
                {
                    if( !empty($email) && !empty($phone))
                    {
                        $SQLupdate = $odb -> prepare("UPDATE users SET `username` = :username, `email` = :email, `rank` = :rank, `phone` = :phone,`otp_verification_preference` = :otp_verification_preference,`restrict_chat` = :restrict_chat  WHERE id = :id");
                        $SQLupdate -> execute(array(':username' => $username, ':email' => $email, ':rank' => $role, ':phone' => $phone,':otp_verification_preference' => $fa_preference,':restrict_chat' => $restrict, ':id' => $editId));
                    }else if( !empty($email) && empty($phone) )
                    { 
                        $SQLupdate = $odb -> prepare("UPDATE users SET `username` = :username, `email` = :email, `rank` = :rank,`otp_verification_preference` = :otp_verification_preference, `phone` = Null,`restrict_chat` = :restrict_chat  WHERE id = :id");
                        $SQLupdate -> execute(array(':username' => $username, ':email' => $email, ':rank' => $role,':otp_verification_preference' => $fa_preference,':restrict_chat' => $restrict, ':id' => $editId));
                    }else if( empty($email) && !empty($phone) )
                    {
                        $SQLupdate = $odb -> prepare("UPDATE users SET `username` = :username, `rank` = :rank, `phone` = :phone,`otp_verification_preference` = :otp_verification_preference , `email` = Null,`restrict_chat` = :restrict_chat   WHERE id = :id");
                        $SQLupdate -> execute(array(':username' => $username, ':rank' => $role, ':phone' => $phone,':otp_verification_preference' => $fa_preference,':restrict_chat' => $restrict, ':id' => $editId));
                    }
                    $permission = 'none';
                    if($role==2 && !empty($assistant_permissions)){
                        $permission = implode(",", $assistant_permissions);
                    }

                    $SQLCSelect = $odb -> query("SELECT * FROM `users_meta` where user_id = $editId AND meta_key ='assistant_permissions'");
                    $SQLCSelect -> execute();
                    if($SQLCSelect->rowCount() >0){
                        $update = $odb -> prepare("UPDATE `users_meta` SET `meta_value`=:meta_value WHERE user_id = :user_id AND meta_key = :meta_key");
                        $update -> execute(array(':meta_value' => $permission,':user_id' => $editId, ':meta_key' => 'assistant_permissions'));
    
                    }else{
                        $insert = $odb -> prepare("INSERT INTO `users_meta` (`user_id`, `meta_key`, `meta_value`) VALUES(:user_id, :meta_key, :meta_value)");
                        $insert -> execute(array(':user_id' => $editId, ':meta_key' => 'assistant_permissions', ':meta_value' => $permission));

                    }

                    
                    $user->addRecentActivities($odb,'edit_user',' Modified the user '.$username.' on the platform');
                }
               
               

                // }
                // catch(Exception $e) {
                //     echo 'Exception -> ';
                //     var_dump($e->getMessage());
                // }

                if( ( $password != '' && $repassword != '' ) && ($password == $repassword) ) {
                    // error matching passwords
                    $SQLupdate = $odb -> prepare("UPDATE users SET `password` = :password WHERE id = :id");
                    $SQLupdate -> execute(array(':password' => SHA1($password), ':id' => $editId));               
                }
			}
			else
			{
				foreach($errors as $error)
				{
					echo '-'.$error.'<br />';
				}
				echo '</div>';
			}
		}
        
        if(isset($_POST['formSubmit']))
        {
          $userstatus = $_POST['user_act_dea']; 
          $bannedmsg = $_POST['bannedmsg']; 

          if( isset($userstatus) && $userstatus == 'on' )
          {
              $userstatus = 1;
          }
          else
          {
              $userstatus = 0;
          }

          $SQLupdate = $odb -> prepare("UPDATE users SET  `status` = :status , `banned_msg` = :banned_msg WHERE id = :id");
          $SQLupdate -> execute(array(':status' => $userstatus,':banned_msg' => $bannedmsg, ':id' => $editId));
          $danzerActive =  true;
          if($bannedmsg != '')
          {
            $bannedSucMsg = "<div class='message' id='message'><p><strong>SUCCESS: The user's custom ban message has been set.</strong></div>";
          }
          else
          {
            $bannedSucMsg = "<div class='message' id='message'><p><strong>SUCCESS: The user's custom ban message is cleared, if the user is ever banned, the default ban message will appear.</strong></div>";
          }
          

        }

        $pageTitle = 'Edit An User';
        require_once '../header.php';
        
?>
   

    <!--  BEGIN MAIN CONTAINER  -->
    <div class="main-container" id="container">

        <div class="overlay"></div>
        <div class="search-overlay"></div>

        <!--  BEGIN SIDEBAR  --> 
<?php include '../sidebar.php'; ?>
        <!--  END SIDEBAR  -->

        <!--  BEGIN CONTENT AREA  -->
        <div id="content" class="main-content">
            <div class="layout-px-spacing">

                <div class="middle-content container-xxl p-0">

                    <!-- BREADCRUMB -->
                    <div class="page-meta">
                        <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">Cyber Range</li>
                                <li class="breadcrumb-item active" aria-current="page">Edit A User</li>
                            </ol>
                        </nav>
                    </div>
					<br>
                    <!-- /BREADCRUMB -->
                       <div class="col-lg-12 col-12 layout-spacing">
                        <div class="row mb-3">
                                <div class="col-md-12">
                                    <h2>Edit User</h2>
        
                                    <div class="animated-underline-content">
                                        <ul class="nav nav-tabs" id="animateLine" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link <?php if(!$danzerActive){ echo 'active'; } ?>" id="animated-underline-home-tab" data-bs-toggle="tab" href="#animated-underline-home" role="tab" aria-controls="animated-underline-home" aria-selected="true"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg> Edit A User</a>
                                            </li>
                                              <li class="nav-item">
                                                <a class="nav-link <?php if($danzerActive){ echo 'active'; }?>" id="animated-underline-contact-tab" data-bs-toggle="tab" href="#animated-underline-contact" role="tab" aria-controls="animated-underline-contact" aria-selected="false"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>User Administration</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-content" id="animateLineContent-4">
                                <div class="tab-pane fade show active" id="animated-underline-home" role="tabpanel" aria-labelledby="animated-underline-home-tab">
		<?php 
		if (isset($_POST['updateUser']))
		{
			if(empty($errors)) {
				echo '<div class="message" id="message"><p><strong>SUCCESS: The user has been added! You are now being redirected to the User Management Platform.</strong></div><meta http-equiv="refresh" content="4;url=../admin/adminu.php">';
				
				$username = '';
				$email = '';
				$password = '';
				$repassword = '';
				$role = '';
			} else {
				echo '<div class="error" id="message"><p><strong>ERROR: </strong>';
				foreach($errors as $error) {
					echo ''.$error.'<br />';
				}
				echo '</div>';
			}
			
		}
        
        // Get User Data id wise
            
        //  try
        //         {
        //             $odb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //             $odb->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $SQLgetUser = $odb -> prepare("SELECT * FROM `users` WHERE `ID` = :editId");
        $SQLgetUser -> execute(array(':editId' => $editId));
        $userInfo = $SQLgetUser -> fetch(PDO::FETCH_ASSOC); 
        $assistant_data = $user->getUserMeta($odb,$editId,"assistant_permissions");
        $assistant_permissions = array();
        if(!empty($assistant_data) && $assistant_data['meta_value']!=''){
            $assistant_permissions = explode(",", $assistant_data['meta_value']);
        }
        
        //  }
        //         catch(Exception $e) {
        //             echo 'Exception -> ';
        //             var_dump($e->getMessage());
        //         }
       
        $editUserName = '';
        $editEmail = '';
        $editPhone = '';
        $editRole = '';
        $otp_verification_preference = 0;
       

        if(!empty($userInfo))
        {    
            $editUserName = $userInfo['username'];
            $editEmail = $userInfo['email'];
            $editPhone = $userInfo['phone'];
            $editRole = $userInfo['rank'];
            $editstatus = $userInfo['status'];
            $banned_msg = $userInfo['banned_msg'];
            $otp_verification_preference = $userInfo['otp_verification_preference'];
            $restrict_chat = $userInfo['restrict_chat'];
        }
        else
        {
            echo '<div class="error" id="message"><p><strong>ERROR: </strong>Something went wrong</p></div>';
        }
		
		?>
              
<div class="row">
  <div class="col-xl-12 col-lg-12 col-md-12 layout-spacing">
<form  method="POST" class="section general-info">
 <div class="info">
     <div align="Center">
<h6 class="">Edit A User</h6>
<div class="row">
    <div class="col-lg-11 mx-auto">
        <div class="row">
<div class="col-xl-10 col-lg-12 col-md-8 mt-md-0 mt-4">
 <div class="row">

    <div class="col-md-6">
        <div class="form-group">
            <label for="titleAdd">Username</label>
            <input type="text" class="form-control mb-3" value="<?=$editUserName?>" name="username">
        </div>
    </div>
        <div class="col-md-6">
        <div class="form-group">
            <label for="titleAdd">Email-Address</label>
                                            <input type="email" class="form-control mb-3" value="<?=$editEmail?>" name="email">
        </div>
    </div>
    
     
        <div class="col-md-6">
            <div class="form-group">
            <label for="titleAdd">Password</label>
                                            <input type="password" class="form-control mb-3" name="password">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="titleAdd">Confirm Password</label>
                                            <input type="password" class="form-control mb-3" name="repassword">
        </div>
    </div>
   
     
        <div class="col-md-6">
        <div class="form-group">
            <label for="titleAdd">Phone No.<span class="toolContain"><a class="dropdown-toggle warning bs-tooltip" href="#" role="button" title="Phone number cannot be less than 10 or greater than 14 digits.">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-alert-octagon"><polygon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"></polygon><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12" y2="16"></line></svg>
                                                            </a></span></label>
                                            <input type="text" id="phone" value="<?=$editPhone?>" onkeypress="return isNumberKey(event)" class="form-control mb-3" name="phone">
        </div>
    </div>
   
   

    <div class="col-md-6 customBluBg mb-3">
            <div class="headingForRadio">2FA Preferences</div>
            <div class="form-check form-check-primary form-check-inline mt-3">
                <input class="form-check-input" type="radio" name="2fa_preference" <?php if($otp_verification_preference == 1){echo 'checked';} ?> value="1" id="form-check-radio-default">
                <label class="form-check-label" for="form-check-radio-default">
                Text
                </label>
            </div>

            <div class="form-check form-check-primary form-check-inline mt-3">
                <input class="form-check-input" type="radio" name="2fa_preference" <?php if($otp_verification_preference == 2){echo 'checked';} ?> value="2" id="form-check-radio-default-checked">
                <label class="form-check-label" for="form-check-radio-default-checked">
                E-Mail
                </label>
            </div>
        </div>
         <div class="col-md-6">
            <div class="form-group">
                <label for="titleAdd" class="custOption">Role</label>
                    <select class="form-control mb-3" name="role" id="selectCust">
                        <option value="" onclick="setPermission(0)"> Please Select </option>
                        <?php foreach ($teamList as $teamListV) { 
                            if($editRole == $teamListV['id']){ $select = 'selected';}else{ $select = ''; }  
                            echo '<option onclick="setPermission('.$teamListV['id'].')"  value="'.$teamListV['id'].'" '.$select.' > '.$teamListV['name'].' </option>'; 
                        } ?>
                    </select>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="titleAdd" class="custOption">Restrict Chat</label>
                <div class="form-check form-check-primary form-check-inline mt-3">
                    <input class="form-check-input" type="checkbox" name="restrict_chat" <?php if($restrict_chat==1){ echo "checked"; } ?> value="yes">
                    <label class="form-check-label">Yes</label>
                </div>
            </div>
        </div>
       
   </div>
    <div class="permission_options row" style="display: <?php if($editRole==2){ echo "inline-flex"; }else{ echo "none"; } ?>">
        <div class="col-md-4">
            <div class="form-group mt-4">
                <div class="switch form-switch-custom form-switch-success mt-1">
                    <label>Dashboard</label>
                    <input class="switch-input" name="assistant_permissions[]" type="checkbox" role="switch" value="dashboard" <?php if(!empty($assistant_permissions) && in_array("dashboard", $assistant_permissions)){ echo "checked"; } ?>>
                </div>                                                      
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group mt-4">
                <div class="switch form-switch-custom form-switch-success mt-1">
                    <label>Announcements</label>
                    <input class="switch-input" name="assistant_permissions[]" type="checkbox" role="switch" value="announcement" <?php if(!empty($assistant_permissions) && in_array("announcement", $assistant_permissions)){ echo "checked"; } ?>>
                </div>                                                      
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group mt-4">
                <div class="switch form-switch-custom form-switch-success mt-1">
                    <label>Team Management</label>
                    <input class="switch-input" name="assistant_permissions[]" type="checkbox" role="switch" value="team" <?php if(!empty($assistant_permissions) && in_array("team", $assistant_permissions)){ echo "checked"; } ?>>
                </div>                                                      
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group mt-4">
                <div class="switch form-switch-custom form-switch-success mt-1">
                    <label>System Group  </label>
                    <input class="switch-input" name="assistant_permissions[]" type="checkbox" role="switch" value="system_group" <?php if(!empty($assistant_permissions) && in_array("system_group", $assistant_permissions)){ echo "checked"; } ?>>
                </div>                                                      
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group mt-4">
                <div class="switch form-switch-custom form-switch-success mt-1">
                    <label>System Management </label>
                    <input class="switch-input" name="assistant_permissions[]" type="checkbox" role="switch" value="system" <?php if(!empty($assistant_permissions) && in_array("system", $assistant_permissions)){ echo "checked"; } ?>>
                </div>                                                      
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group mt-4">
                <div class="switch form-switch-custom form-switch-success mt-1">
                    <label>User Management</label>
                    <input class="switch-input" name="assistant_permissions[]" type="checkbox" role="switch" value="user" <?php if(!empty($assistant_permissions) && in_array("user", $assistant_permissions)){ echo "checked"; } ?>>
                </div>                                                      
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group mt-4">
                <div class="switch form-switch-custom form-switch-success mt-1">
                    <label>API Management</label>
                    <input class="switch-input" name="assistant_permissions[]" type="checkbox" role="switch" value="api" <?php if(!empty($assistant_permissions) && in_array("api", $assistant_permissions)){ echo "checked"; } ?>>
                </div>                                                      
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group mt-4">
                <div class="switch form-switch-custom form-switch-success mt-1">
                    <label>Rubric Management</label>
                    <input class="switch-input" name="assistant_permissions[]" type="checkbox" role="switch" value="rubric" <?php if(!empty($assistant_permissions) && in_array("rubric", $assistant_permissions)){ echo "checked"; } ?>>
                </div>                                                      
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group mt-4">
                <div class="switch form-switch-custom form-switch-success mt-1">
                    <label>Manage Tickets</label>
                    <input class="switch-input" name="assistant_permissions[]" type="checkbox" role="switch" value="ticket" <?php if(!empty($assistant_permissions) && in_array("ticket", $assistant_permissions)){ echo "checked"; } ?>>
                </div>                                                      
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group mt-4">
                <div class="switch form-switch-custom form-switch-success mt-1">
                    <label>Quiz Management</label>
                    <input class="switch-input" name="assistant_permissions[]" type="checkbox" role="switch" value="quiz" <?php if(!empty($assistant_permissions) && in_array("quiz", $assistant_permissions)){ echo "checked"; } ?>>
                </div>                                                      
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group mt-4">
                <div class="switch form-switch-custom form-switch-success mt-1">
                    <label>Knowledgebase</label>
                    <input class="switch-input" name="assistant_permissions[]" type="checkbox" role="switch" value="knowledgebase" <?php if(!empty($assistant_permissions) && in_array("knowledgebase", $assistant_permissions)){ echo "checked"; } ?>>
                </div>                                                      
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group mt-4">
                <div class="switch form-switch-custom form-switch-success mt-1">
                    <label>Course System</label>
                    <input class="switch-input" name="assistant_permissions[]" type="checkbox" role="switch" value="course_system" <?php if(!empty($assistant_permissions) && in_array("course_system", $assistant_permissions)){ echo "checked"; } ?>>
                </div>                                                      
            </div>
        </div>

    </div>
    <div class="row mb-3"> 
        <div class="col-md-12 mt-1">
            <div class="form-group text-end">
                <input type="submit" name="updateUser" class="btn btn-outline-success btn-lrg">    
            </div>
        </div>
    </div>
    
  

</div></div></div></div>
</div></div>
</form>
</div>
</div>






                                    
                                </div>
                                

                                <div class="tab-pane fade" id="animated-underline-contact" role="tabpanel" aria-labelledby="animated-underline-contact-tab">
                                    <!--<div class="alert alert-arrow-right alert-icon-right alert-light-warning alert-dismissible fade show mb-4" role="alert">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-alert-circle"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12" y2="16"></line></svg>
                                        <strong>Warning!</strong> Please proceed with caution. For any assistance - <a href="javascript:void(0);">Contact Us</a>                                        
                                    </div>-->
                                    <div class="message" style="display:none" id="user_act_succ_message"><p><strong>SUCCESS: The user has been activated successfully!</strong></div>

                                    <div class="error" style="display:none" id="user_act_err_message"><p><strong>ERROR: </strong>The user has been deactivated!</p></div>
                                    <div class="row">                                    
                                    
                                    <form method="POST" id="userbannedfrom">
                                        <div class="col-xl-4 col-lg-12 col-md-12 layout-spacing">
                                            <div class="section general-info">
                                                <div class="info">
                                                    <h6 class="mb-3">Ban Account<span class="toolContain"><a class="dropdown-toggle warning bs-tooltip" href="#" role="button" title="Banning the Account will remove access to the range, you may toggle the function to the right to ban the account, or to the left to unban the account. You may also write a custom ban message.">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-alert-octagon"><polygon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"></polygon><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12" y2="16"></line></svg>
                                                            </a></span></h6>
                                                    <?php if($bannedSucMsg != ''){
                                                        echo $bannedSucMsg;
                                                     } ?>
                                                    <!--<p>Banning the Account will remove access to the range, toggle to the right to continue.</p>-->
                                                    <div class="form-group">
                                                        <label for="bannedmsg">Custom Banned Message</label>
                                                        <textarea class="form-control mb-3" name="bannedmsg" rows="2"><?php echo $banned_msg?></textarea>
                                                    </div>                                                    
                                                    <div class="form-group mt-4">
                                                        <div class="switch form-switch-custom switch-inline form-switch-success mt-1">
                                                            <label for="bannedmsg">Banned</label>
                                                            <input class="switch-input" <?php if($editstatus == 1){echo 'checked'; } ?> name="user_act_dea" id="user_act_dea" type="checkbox" role="switch" id="socialformprofile-custom-switch-success">
                                                        </div>
                                                      
                                                    </div>
                                                    <div class="form-group text-end">
                                                         <input type="submit" name="formSubmit" class="btn btn-outline-success btn-lrg">    
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

            <!--  BEGIN FOOTER  -->
            <?php require_once '../includes/footer-section.php'; ?>
            <!--  END FOOTER  -->
            <!--  END CONTENT AREA  -->
        </div>
        <!--  END CONTENT AREA  -->
    </div>
    <!-- END MAIN CONTAINER -->
    
    <?php  require_once '../footer.php'; ?>

    <script>
       
        function isNumberKey(evt){
                var charCode = (evt.which) ? evt.which : evt.keyCode
                if (charCode > 31 && (charCode < 48 || charCode > 57))
                    return false;
                return true;
        }

        function setPermission($role) {
            if($role==2){
                $(".permission_options").show();
            }else{
                $(".permission_options").hide();
            }
        }

       
        selectBox = new vanillaSelectBox("#selectCust", {
            "keepInlineStyles":true,
            "maxHeight": 200,
            "minWidth":481,
            "search": true,
            "placeHolder": "Choose..." 
        });

        // function to activate and deactivate user
        $(document).ready( function() {
            <?php if($danzerActive){?>            
                $('#animated-underline-home').removeClass("active").removeClass("show");
                $('#animated-underline-contact').addClass("active").addClass("show");
            <?php }?>        
        
            //  $('#user_act_dea').bind('change', function () {
            
            //     $('#userbannedfrom').submit();
                
            // });
        });
    </script>


      
    
</body>
</html>