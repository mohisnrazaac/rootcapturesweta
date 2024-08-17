<?php
   ob_start();
   require 'includes/db.php';
   require 'includes/init.php';
   @session_start();
   if(isset($_SESSION['theme_mode']) && $_SESSION['theme_mode']=='light'){ $load_screen = "load_screen_light"; }else{ $load_screen = "load_screen_dark"; }
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

if(isset($_GET['lock'])) {
	$_SESSION['locked'] = true;
	header('location: lock.php');
	die();
	}

if (!($user -> isLocked()))
{
	header('location: index.php');
	die();
}

$username = '';
if(isset($_SESSION['username'])) {
	$username = $_SESSION['username'];
}
   ?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
      <title>rootCapture - Sign In</title>
      <link rel="icon" type="image/x-icon" href="../src/assets/img/favicon.ico"/>
      <link href="../layouts/vertical-light-menu/css/light/loader.css" rel="stylesheet" type="text/css" />
      <link href="../layouts/vertical-light-menu/css/dark/loader.css" rel="stylesheet" type="text/css" />
      <script src="../layouts/vertical-dark-menu/loader.js"></script>
      <!-- BEGIN GLOBAL MANDATORY STYLES -->
      <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
      <link href="../src/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
      <link href="../css/alter.css" rel="stylesheet" type="text/css" />
      <link href="../layouts/vertical-light-menu/css/light/plugins.css" rel="stylesheet" type="text/css" />
      <link href="../src/assets/css/light/authentication/auth-boxed.css" rel="stylesheet" type="text/css" />
      <link href="../layouts/vertical-light-menu/css/dark/plugins.css" rel="stylesheet" type="text/css" />
      <link href="../src/assets/css/dark/authentication/auth-boxed.css" rel="stylesheet" type="text/css" />
      <link href="css/custom-style.css" rel="stylesheet" type="text/css" />
      <!-- END GLOBAL MANDATORY STYLES -->
   </head>
   <body class="layout-boxed">
      <!-- BEGIN LOADER -->
      <div id="load_screen" class="<?php echo $load_screen; ?>">
         <div class="loader">
            <div class="loader-content">
                  <div class="spinner-grow-new align-self-center">
                     <img src="<?=BASEURL?>assets/img/Loading-Animation-dark.gif" class="dark_preloader <?php if($load_screen=='load_screen_light'){ echo 'hide_preloader'; }?>">
                     <img src="<?=BASEURL?>assets/img/Loading-Animation-light.gif" class="light_preloader <?php if($load_screen!='load_screen_light'){ echo 'hide_preloader'; } ?>">
                  </div>
            </div>
         </div>
      </div>
      <!--  END LOADER -->
      <!--  BEGIN NAVBAR  -->
      <div class="outerPagesHeader">
         <div class="header navbar navbar-expand-sm expand-header">
            <div class="navbar-nav theme-brand flex-row  text-center">
               <!-- <div class="nav-logo">
                  <div class="nav-item theme-logo">
                      <a href="../index.html">
                          <img src="../src/assets/img/logo.svg" class="navbar-logo" alt="logo">
                      </a>
                  </div>
                  <div class="nav-item theme-text">
                      <a href="../index.php" class="nav-link"> rootCapture </a>
                  </div>
                  </div>-->
               <div class="nav-item sidebar-toggle">
                  <div class="btn-toggle sidebarCollapse">
                     <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevrons-left">
                        <polyline points="11 17 6 12 11 7"></polyline>
                        <polyline points="18 17 13 12 18 7"></polyline>
                     </svg>
                  </div>
               </div>
            </div>
            <ul class="navbar-item flex-row ms-lg-auto ms-0">
               <li class="nav-item theme-toggle-item">
                  <a href="javascript:void(0);" class="nav-link theme-toggle">
                     <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-moon dark-mode"  onclick="changeThemeMode('light')">
                        <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
                     </svg>
                     <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-sun light-mode" onclick="changeThemeMode('dark')">
                        <circle cx="12" cy="12" r="5"></circle>
                        <line x1="12" y1="1" x2="12" y2="3"></line>
                        <line x1="12" y1="21" x2="12" y2="23"></line>
                        <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
                        <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line>
                        <line x1="1" y1="12" x2="3" y2="12"></line>
                        <line x1="21" y1="12" x2="23" y2="12"></line>
                        <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line>
                        <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
                     </svg>
                  </a>
               </li>
            </ul>
         </div>
      </div>
      <!--  END NAVBAR  -->
      <div class=" " id="container">
         <div class="overlay"></div>
         <div class="search-overlay"></div>
         <div class="auth-container d-flex">
            <div class="container mx-auto align-self-center">
               <div class="row">
    
                <div class="col-xxl-4 col-xl-5 col-lg-5 col-md-8 col-12 d-flex flex-column align-self-center mx-auto">
                    <div class="card mt-3 mb-3">
                        <div class="card-body">
    
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    
                                    <div class="media mb-4">
                                        

                                        <div class="media-body align-self-center">

											<h3 class="mb-0"><center>Hello, <?php echo $username; ?></center></h3>
											<br>
											<div class="logo logo-section" align="center">
			                                    <img src="/assets/img/RootCapture0.png" class="dark-logo">
			                                    <img src="/assets/img/rootcapture-whitelogo.png" class="light-logo">
			                                 </div>
											<br>
                                            <p class="mb-0"><center><font color="Red">You have locked your cyber range access, please enter your password to unlock your account's session.</font></center></p>

                                        </div>
                                        
                                    </div>
                                    
                                </div>
								<form action='' method='POST'>
<?php

if (isset($_POST['loginBtn']))
	{
		$username = $_POST['username'];
		$password = $_POST['password'];
		$errors = array();
		
		if (empty($username) || empty($password))
		{
			$errors[] = 'All fields need to be filled in.';
		}
		
		if (empty($errors))
		{
			$SQLCheckLogin = $odb -> prepare("SELECT COUNT(*) FROM `users` WHERE `username` = :username AND `password` = :password");
			$SQLCheckLogin -> execute(array(':username' => $username, ':password' => SHA1($password)));
			$countLogin = $SQLCheckLogin -> fetchColumn(0);
			if ($countLogin == 1)
			{
				$SQLGetInfo = $odb -> prepare("SELECT `username`, `ID`,`status` FROM `users` WHERE `username` = :username AND `password` = :password");
				$SQLGetInfo -> execute(array(':username' => $username, ':password' => SHA1($password)));
				$userInfo = $SQLGetInfo -> fetch(PDO::FETCH_ASSOC);
				$status = $userInfo['status'];
				$userid = $userInfo['ID'];
				$userip = $_SERVER['REMOTE_ADDR'];
				if ($status == 1)
				{
					echo '<div class="error" id="message"><p><strong>ERROR: </strong>Your account has been banned. Please contact a staff or administrative member to resolve this.</p></div>';
				}
				elseif ($status == 0)
				{
				$username = $userInfo['username'];
				$logip = $odb -> prepare("INSERT INTO loginip (userID,logged,date,username) VALUES ('$userid', '$userip', UNIX_TIMESTAMP(),'$username')");
				$logip -> execute(array());
				$status = $userInfo['status'];
					$_SESSION['username'] = $userInfo['username'];
					$_SESSION['ID'] = $userInfo['ID'];
					unset($_SESSION['locked']); // unlock user
					echo '<div class="message" id="message"><p><strong>SUCCESS: </strong>YOUR CYBER RANGE ACCESS HAS BEEN UNLOCKED! YOU ARE NOW BEING REDIRECTED BACK TO THE CYBER RANGE....</p></div><meta http-equiv="refresh" content="3;url=index.php">';
				}
				
			}
			else
			{
				echo '<div class="error" id="message"><p><strong>ERROR: </strong>Login Failed! Please check your credentials and try again!</p></div>';
			}
		}
		else
		{
			echo '<div class="error" id="message"><p><strong>ERROR:</strong><br />';
			foreach($errors as $error)
			{
				echo ''.$error.'<br />';
			}
			echo '</div>';
		}
	}

?>
                                <div class="col-12">
                                    <div class="mb-4">
                                        <label class="form-label">Password</label>
                                        <input id="username" name="username" type="hidden" value="<?php echo $username;?>">
										<input id="password" name="password" type="password" class="form-control">
                                    </div>
                                </div>
                                
                                <div class="col-12">
                                    <div class="mb-4">
                                        <button class="btn btn-secondary w-100" type="submit" value="Login" name="loginBtn">UNLOCK</button>
                                    </div>
                                </div>
								
								</form>
                                
                            </div>
                            
                        </div>
                    </div>
                </div>
                
            </div>
            </div>

         </div>

      </div>
      <div class="footer_login_system">
         <?php require_once 'includes/footer-login-system.php'; ?>
      </div>
      <!-- BEGIN GLOBAL MANDATORY STYLES -->
      <script src="../src/bootstrap/js/bootstrap.bundle.min.js"></script>
      <script src="../src/plugins/src/perfect-scrollbar/perfect-scrollbar.min.js"></script>
      <script src="../src/plugins/src/mousetrap/mousetrap.min.js"></script>
      <script src="../layouts/vertical-light-menu/app.js"></script>
      <script>
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
      </script>
   </body>
</html>