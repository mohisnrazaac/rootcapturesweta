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

        $getUserDetailIdWise = $user->getUserDetailIdWise($odb); 
        $college_id = $getUserDetailIdWise['college_id']; 

        // echo "SELECT teams.* FROM `teams` LEFT JOIN team_status ON teams.id = team_status.team_id AND college_id = $college_id AND status = 1 WHERE teams.name != 'Admin'   ORDER BY id ASC"; exit;
        
        $teamList  = $odb -> query("SELECT teams.* FROM `teams` INNer JOIN team_status ON teams.id = team_status.team_id  WHERE teams.name != 'Admin' AND college_id = $college_id AND status = 1   ORDER BY id ASC")->fetchAll();
        $submitdata = array();
		if (isset($_POST['submitUser']))
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
            $loggedUserId = $_SESSION['ID'];

            if($restrictchat==''){
                $restrict = 0;
            }else{
                $restrict = 1;
            }

            $submitdata = $_POST;

			$errors = array();
			if (empty($username) || empty($password) || empty($repassword) || empty($role) || empty($fa_preference)){
				$errors[] = 'Please verify all fields'; 
			}  else if( empty($email) && empty($phone) ) {
                $errors[] = 'Email or phone is required.'; 
            }elseif(!empty($fa_preference) && $fa_preference==1 && $phone==''){
                $errors[] = 'Please enter phone for text 2fa perference.';
            }  else {
                if( !empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                 $errors[] = 'Invalid email format';
                }
                if($password != $repassword) {
                    // error matching passwords
                    $errors[] = 'Your passwords and confirm password do not match. Please type carefully.';                
                }
            }
            



			if (empty($errors))
			{  
                // try
                // {
                //     $odb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                //     $odb->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

                    //Check for dublicate username
                   
                    $checkUsernameExists = $odb -> prepare("SELECT * FROM `users` WHERE `username` = :username");
	                $checkUsernameExists -> execute(array(':username' => $username));

                    $checkPhoneExists = $odb -> prepare("SELECT * FROM `users` WHERE `phone` = :phone");
	                $checkPhoneExists -> execute(array(':phone' => $phone));

                    $checkEmailExists = $odb -> prepare("SELECT * FROM `users` WHERE `email` = :email");
	                $checkEmailExists -> execute(array(':email' => $email));


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
                            $SQLinsert = $odb -> prepare("INSERT INTO `users`(`ID`, `username`, `password`, `email`, `rank`, `phone`,`created_by`,`college_id`, `membership`, `expire`, `status`, `key`, `used`, `otp`, `otp_verification_preference`, `banned_msg`, `grading_criteria`, `restrict_chat`,`datetime`) VALUES(NULL, :username, :password, :email, :rank, :phone,:created_by,:college_id, 0, 0, 0, NULL, 0, Null,:otp_verification_preference,Null,0,:restrict_chat,:datetime)");
                            $SQLinsert -> execute(array(':username' => $username, ':password' => SHA1($password), ':email' => $email, ':rank' => $role, ':phone' => $phone, 'created_by'=>$loggedUserId,'college_id'=>$college_id,':otp_verification_preference' => $fa_preference, ':restrict_chat' => $restrict, ':datetime' => DATETIME));

                        }else if( !empty($email) && empty($phone) )
                        { 
                            $SQLinsert = $odb -> prepare("INSERT INTO `users`(`ID`, `username`, `password`, `email`, `rank`, `phone`,`created_by`,`college_id`, `membership`, `expire`, `status`, `key`, `used`, `otp`, `otp_verification_preference`, `banned_msg`, `grading_criteria`, `restrict_chat`, `datetime`) VALUES(NULL, :username, :password, :email, :rank, Null,$loggedUserId,$college_id, 0, 0, 0, NULL, 0, Null,:otp_verification_preference,Null,0,:restrict_chat,:datetime)");
                            $SQLinsert -> execute(array(':username' => $username, ':password' => SHA1($password), ':email' => $email, ':rank' => $role, ':otp_verification_preference' => $fa_preference, ':restrict_chat' => $restrict,':datetime' => DATETIME));
                        }else if( empty($email) && !empty($phone) )
                        {
                            $SQLinsert = $odb -> prepare("INSERT INTO `users`(`ID`, `username`, `password`, `email`, `rank`, `phone`,`created_by`,`college_id`, `membership`, `expire`, `status`, `key`, `used`, `otp`, `otp_verification_preference`, `banned_msg`, `grading_criteria`, `restrict_chat`, `datetime`) VALUES(NULL, :username, :password, Null, :rank, :phone,$loggedUserId,$college_id, 0, 0, 0, NULL, 0, Null,:otp_verification_preference,Null,0,:restrict_chat,:datetime)");
                            $SQLinsert -> execute(array(':username' => $username, ':password' => SHA1($password), ':rank' => $role, ':phone' => $phone, ':otp_verification_preference' => $fa_preference, ':restrict_chat' => $restrict,':datetime' => DATETIME));
                        }
                        $lastID = $odb -> lastInsertID();
                        $permission = 'none';
                        if($role==2 && !empty($assistant_permissions)){
                            $permission = implode(",", $assistant_permissions);
                        }

                        $insert = $odb -> prepare("INSERT INTO `users_meta` (`user_id`, `meta_key`, `meta_value`) VALUES(:user_id, :meta_key, :meta_value)");
                        $insert -> execute(array(':user_id' => $lastID, ':meta_key' => 'assistant_permissions', ':meta_value' => $permission));

                        $user->addRecentActivities($odb,'add_user',' Created a New User on the Platform');
                        
                    }

                    

                // }
                // catch(Exception $e) {
                //     echo 'Exception -> ';
                //     var_dump($e->getMessage());
                // }
                    
			}
		}

        $pageTitle = 'Add An User';
        require_once '../header.php'
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
                                <li class="breadcrumb-item active" aria-current="page">Add A User</li>
                            </ol>
                        </nav>
                    </div>
					<br>
                    <!-- /BREADCRUMB -->
                       <div class="col-lg-12 col-12 layout-spacing">

                        <div class="row mb-3">
                                <div class="col-md-12">
                                    <h2>Add A User</h2>
        
                                    <div class="animated-underline-content">
                                        <ul class="nav nav-tabs" id="animateLine" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" id="animated-underline-home-tab" data-bs-toggle="tab" href="#animated-underline-home" role="tab" aria-controls="animated-underline-home" aria-selected="true"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg> Add A User</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-content" id="animateLineContent-4">
                                <div class="tab-pane fade show active" id="animated-underline-home" role="tabpanel" aria-labelledby="animated-underline-home-tab">
		<?php 
		if (isset($_POST['submitUser']))
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
		
		?>

<div class="row">
<div class="col-xl-12 col-lg-12 col-md-12 layout-spacing">
<form class=" section general-info" method="POST">
<div class="info"> 
    <div align="Center">
        <h6 class="">Add A User</h6>
<div class="row">
 <div class="col-lg-11 mx-auto">
    <div class="row">
<div class="col-xl-10 col-lg-12 col-md-8 mt-md-0 mt-4">

<div class="form">
<div class="row">



   
        <div class="col-md-6">
            <div class="form-group">
            <label for="titleAdd">Username</label>
            <input type="text" class="form-control mb-3" name="username" value="<?php if(isset($submitdata['username'])){ echo $submitdata['username']; } ?>">
        </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
             <label for="titleAdd">Email-Address</label>
            <input type="email" class="form-control mb-3" name="email" value="<?php if(isset($submitdata['email'])){ echo $submitdata['email']; } ?>">
        </div>
        </div>
    
     
        <div class="col-md-6">
            <div class="form-group">
            <label for="titleAdd">Password</label>
            <input type="password" class="form-control mb-3" name="password" value="<?php if(isset($submitdata['password'])){ echo $submitdata['password']; } ?>">
        </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="titleAdd">Confirm Password</label>
                <input type="password" class="form-control mb-3" name="repassword" value="<?php if(isset($submitdata['repassword'])){ echo $submitdata['repassword']; } ?>">
            </div>
        </div>
   
     
        <div class="col-md-6">
            <div class="form-group">
                <label for="titleAdd">Phone No.
                    <span class="toolContain">
                        <a class="dropdown-toggle warning bs-tooltip" href="#" role="button" title="Phone number cannot be less than 10 or greater than 14 digits.">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-alert-octagon"><polygon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"></polygon><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12" y2="16"></line></svg>
                        </a>
                    </span>
                </label>
                <input type="text" id="phone" onkeypress="return isNumberKey(event)" class="form-control mb-3" name="phone" value="<?php if(isset($submitdata['phone'])){ echo $submitdata['phone']; } ?>">
            </div>
        </div>
        
       
        <div class="col-md-6 customBluBg mb-3">
            <div class="headingForRadio">2FA Preferences</div>
            <div class="form-check form-check-primary form-check-inline mt-3">
                <input class="form-check-input" type="radio" name="2fa_preference" value="1" id="form-check-radio-default" <?php if(isset($submitdata['2fa_preference']) && $submitdata['2fa_preference']==1){ echo "checked"; } ?>>
                <label class="form-check-label" for="form-check-radio-default">
                Text
                </label>
            </div>

            <div class="form-check form-check-primary form-check-inline mt-3">
                <input class="form-check-input" type="radio" name="2fa_preference" value="2" id="form-check-radio-default-checked" <?php if(isset($submitdata['2fa_preference']) && $submitdata['2fa_preference']==2){ echo "checked"; } ?>>
                <label class="form-check-label" for="form-check-radio-default-checked">
                E-Mail
                </label>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="titleAdd" class="custOption">Role</label>
                <select class="form-control mb-3" name="role" id="selectCust" >
                    <option value="" onclick="setPermission(0)"> Please Select </option>
                    <?php foreach ($teamList as $teamListV) { 
                        $tsel = '';
                        if(isset($submitdata['role']) && $submitdata['role']==$teamListV['id']){ 
                            $tsel = 'selected';
                        }
                        echo '<option onclick="setPermission('.$teamListV['id'].')"  value="'.$teamListV['id'].'" '.$tsel.'> '.$teamListV['name'].' </option>'; 
                    } ?>
                </select>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="titleAdd" class="custOption">Restrict Chat</label>
                <div class="form-check form-check-primary form-check-inline mt-3">
                    <input class="form-check-input" type="checkbox" name="restrict_chat" value="yes" <?php if(isset($submitdata['restrict_chat']) && $submitdata['restrict_chat']=='yes'){ echo "checked"; } ?>>
                    <label class="form-check-label">Yes</label>
                </div>
            </div>
        </div>
    </div>
    <div class="permission_options row" style="display: <?php if(!empty($submitdata['assistant_permissions'])){ echo "inline-flex"; }else{ echo "none"; } ?>">
        <div class="col-md-4">
            <div class="form-group mt-4">
                <div class="switch form-switch-custom form-switch-success mt-1">
                    <label>Dashboard</label>
                    <input class="switch-input" name="assistant_permissions[]" type="checkbox" role="switch" value="dashboard" <?php if(!empty($submitdata['assistant_permissions']) && in_array("dashboard", $submitdata['assistant_permissions'])){ echo "checked"; } ?>>
                </div>                                                      
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group mt-4">
                <div class="switch form-switch-custom form-switch-success mt-1">
                    <label>Announcements</label>
                    <input class="switch-input" name="assistant_permissions[]" type="checkbox" role="switch" value="announcement" <?php if(!empty($submitdata['assistant_permissions']) && in_array("announcement", $submitdata['assistant_permissions'])){ echo "checked"; } ?>>
                </div>                                                      
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group mt-4">
                <div class="switch form-switch-custom form-switch-success mt-1">
                    <label>Team Management</label>
                    <input class="switch-input" name="assistant_permissions[]" type="checkbox" role="switch" value="team" <?php if(!empty($submitdata['assistant_permissions']) && in_array("team", $submitdata['assistant_permissions'])){ echo "checked"; } ?>>
                </div>                                                      
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group mt-4">
                <div class="switch form-switch-custom form-switch-success mt-1">
                    <label>System Group  </label>
                    <input class="switch-input" name="assistant_permissions[]" type="checkbox" role="switch" value="system_group" <?php if(!empty($submitdata['assistant_permissions']) && in_array("system_group", $submitdata['assistant_permissions'])){ echo "checked"; } ?>>
                </div>                                                      
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group mt-4">
                <div class="switch form-switch-custom form-switch-success mt-1">
                    <label>System Management </label>
                    <input class="switch-input" name="assistant_permissions[]" type="checkbox" role="switch" value="system" <?php if(!empty($submitdata['assistant_permissions']) && in_array("system", $submitdata['assistant_permissions'])){ echo "checked"; } ?>>
                </div>                                                      
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group mt-4">
                <div class="switch form-switch-custom form-switch-success mt-1">
                    <label>User Management</label>
                    <input class="switch-input" name="assistant_permissions[]" type="checkbox" role="switch" value="user" <?php if(!empty($submitdata['assistant_permissions']) && in_array("user", $submitdata['assistant_permissions'])){ echo "checked"; } ?>>
                </div>                                                      
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group mt-4">
                <div class="switch form-switch-custom form-switch-success mt-1">
                    <label>API Management</label>
                    <input class="switch-input" name="assistant_permissions[]" type="checkbox" role="switch" value="api" <?php if(!empty($submitdata['assistant_permissions']) && in_array("api", $submitdata['assistant_permissions'])){ echo "checked"; } ?>>
                </div>                                                      
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group mt-4">
                <div class="switch form-switch-custom form-switch-success mt-1">
                    <label>Rubric Management</label>
                    <input class="switch-input" name="assistant_permissions[]" type="checkbox" role="switch" value="rubric" <?php if(!empty($submitdata['assistant_permissions']) && in_array("rubric", $submitdata['assistant_permissions'])){ echo "checked"; } ?>>
                </div>                                                      
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group mt-4">
                <div class="switch form-switch-custom form-switch-success mt-1">
                    <label>Manage Tickets</label>
                    <input class="switch-input" name="assistant_permissions[]" type="checkbox" role="switch" value="ticket" <?php if(!empty($submitdata['assistant_permissions']) && in_array("ticket", $submitdata['assistant_permissions'])){ echo "checked"; } ?>>
                </div>                                                      
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group mt-4">
                <div class="switch form-switch-custom form-switch-success mt-1">
                    <label>Quiz Management</label>
                    <input class="switch-input" name="assistant_permissions[]" type="checkbox" role="switch" value="quiz" <?php if(!empty($submitdata['assistant_permissions']) && in_array("quiz", $submitdata['assistant_permissions'])){ echo "checked"; } ?>>
                </div>                                                      
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group mt-4">
                <div class="switch form-switch-custom form-switch-success mt-1">
                    <label>Knowledgebase</label>
                    <input class="switch-input" name="assistant_permissions[]" type="checkbox" role="switch" value="knowledgebase" <?php if(!empty($submitdata['assistant_permissions']) && in_array("knowledgebase", $submitdata['assistant_permissions'])){ echo "checked"; } ?>>
                </div>                                                      
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group mt-4">
                <div class="switch form-switch-custom form-switch-success mt-1">
                    <label>Course System</label>
                    <input class="switch-input" name="assistant_permissions[]" type="checkbox" role="switch" value="course_system" <?php if(!empty($submitdata['assistant_permissions']) && in_array("course_system", $submitdata['assistant_permissions'])){ echo "checked"; } ?>>
                </div>                                                      
            </div>
        </div>

    </div>
   <div class="row">
        <div class="col-md-12 mt-4">
            <div class="form-group text-end">
                <input type="submit" name="submitUser" class="btn btn-outline-success btn-lrg">
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
</form>
</div></div>




                               
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
    </script>
   
</body>
</html>