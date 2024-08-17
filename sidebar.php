<?php
if (!defined('DIRECT'))
{
	die('Direct access not allowed.');
}
$loggedUserId    = $_SESSION['ID'];
$loginInfo = $user->userInfo($odb,$loggedUserId); 
$loggedUserTeam = $loginInfo['team_name'];
$currUrl = "";
$getUserDetailIdWise = $user->getUserDetailIdWise($odb); 
$college_id = $getUserDetailIdWise['college_id']; 

$redTeamSystem = '';
$SQLredTeamSystem = $odb -> query("SELECT asset_group.name FROM `asset_group` INNER JOIN `teams` ON teams.id = asset_group.team INNER JOIN team_status ON team_status.team_id = teams.id WHERE `teams`.`name` LIKE 'Red Team' AND team_status.college_id = $college_id AND team_status.status = 1"); 
 while ($getInfo = $SQLredTeamSystem -> fetch(PDO::FETCH_ASSOC)){ 
    $redTeamSystem = '<li><a href="'.BASEURL.'asset_system.php?group='.$getInfo['name'].'">'.$getInfo['name'].'</a></li>';	
 } 

 $getUserData = $odb -> query("SELECT * from users where id=$loggedUserId");
 $userData = [];
 while ($getUserInfo = $getUserData -> fetch(PDO::FETCH_ASSOC)){ 
    $userData['first_name'] = $getUserInfo['username'];
    $userData['password'] = $getUserInfo['username'];
    if(isset($getUserInfo['email'])){
        $userEmail = $getUserInfo['email'];
    }else{
        $userEmail = $getUserInfo['username'];
    }
    $userData['email_address'] = $userEmail;
    $userData['last_name'] = ".";
 }
$jsonData = json_encode($userData);
$encryptionKey = "rootCapture"; 
$iv = "kb321";
$encryptedData = openssl_encrypt($jsonData, 'aes-256-cbc', $encryptionKey, 0, $iv);



 if($redTeamSystem == '')
 {
    $redTeamSystem = '<li><a href="javascript:void(0)">No Systems Have Been Added Yet</a></li>';
 }

$blueTeamSystem = '';
$SQLblueTeamSystem = $odb -> query("SELECT asset_group.name FROM `asset_group` INNER JOIN `teams` ON teams.id = asset_group.team INNER JOIN team_status ON team_status.team_id = teams.id WHERE `teams`.`name` LIKE 'Blue Team' AND team_status.college_id = $college_id AND team_status.status = 1"); 
 while ($getInfo = $SQLblueTeamSystem -> fetch(PDO::FETCH_ASSOC)){ 
    $blueTeamSystem = '<li><a href="'.BASEURL.'asset_system.php?group='.$getInfo['name'].'">'.$getInfo['name'].'</a></li>';	
 } 

 if($blueTeamSystem == '')
 {
    $blueTeamSystem = '<li><a href="javascript:void(0)">No Systems Have Been Added Yet</a></li>';
 }

 $purpleTeamSystem = '';
$SQLpurpleTeamSystem = $odb -> query("SELECT asset_group.name FROM `asset_group` INNER JOIN `teams` ON teams.id = asset_group.team INNER JOIN team_status ON team_status.team_id = teams.id WHERE `teams`.`name` LIKE 'Purple Team' AND team_status.college_id = $college_id AND team_status.status = 1"); 
 while ($getInfo = $SQLpurpleTeamSystem -> fetch(PDO::FETCH_ASSOC)){ 
    $purpleTeamSystem = '<li><a href="'.BASEURL.'asset_system.php?group='.$getInfo['name'].'">'.$getInfo['name'].'</a></li>';	
 } 

 if($purpleTeamSystem == '')
 {
    $purpleTeamSystem = '<li><a href="javascript:void(0)">No Systems Have Been Added Yet</a></li>';
 }

$sqlDynamicTeamLIst = $odb -> query("SELECT teams.* FROM `teams` INNER JOIN team_status ON teams.id = team_status.team_id WHERE teams.name != 'Admin' AND teams.name != 'Administrative Assistant' AND team_status.college_id = $college_id AND team_status.status = 1 ORDER BY id ASC")->fetchAll();

$getUserMeta = $odb->query("SELECT * FROM `users_meta` where `user_id` = $loggedUserId AND meta_key = 'assistant_permissions'")->fetchAll();
$getUserMetaData = null;
if(isset($getUserMeta[0]) && count($getUserMeta) > 0){
    $getUserMetaData = explode(",",$getUserMeta[0]['meta_value']);
    
}

if($getUserMetaData != null && in_array("enterprise_support",$getUserMetaData)) {
$urlData = "https://kb.rootcapture.com/api/postData?data=".urlencode($encryptedData);
}else{
$urlData = "https://kb.rootcapture.com";
}


?>

  <link href="../css/alter.css" rel="stylesheet" type="text/css" />
    <!--  BEGIN NAVBAR  -->
    <div class="header-container headerTop container-xxl">
        <header class="header navbar navbar-expand-sm expand-header">

            <a href="javascript:void(0);" class="sidebarCollapse">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-menu"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
            </a>

            <div class="search-animated">
            </div>

            <ul class="navbar-item flex-row ms-lg-auto ms-0">

                <li class="nav-item theme-toggle-item">
                    <a href="javascript:void(0);" class="nav-link theme-toggle">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-moon dark-mode" onclick="changeThemeMode('light')"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path ></svg>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-sun light-mode" onclick="changeThemeMode('dark')"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>
                    </a>
                </li>
                <?php if($loginInfo['restrict_chat']!=1){ ?>
                    <li class="nav-item dropdown notification-dropdown">
                        <a href="javascript:void(0);" class="nav-link dropdown-toggle" id="notificationDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bell"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg><span class="badge badge-success"></span>
                        </a>

                        <div class="dropdown-menu position-absolute" aria-labelledby="notificationDropdown">
                            <div class="drodpown-title">
                                <h6 class="d-flex justify-content-between">
                                    <?php 
                                       
                                        $count = 0;
                                        $usermute = $odb -> prepare("SELECT * FROM `chat_mute` WHERE `receiver_id` = :userid");
                                        $usermute -> execute(array(":userid" => $_SESSION['ID']));
                                        $mutedata = $usermute -> fetchAll(); 

                                        $usersmute = array();
                                        if(!empty($mutedata)){
                                            foreach($mutedata as $kk=>$vv){
                                                $usersmute[] = $vv['sender_id'];
                                            }
                                        }
                                        
                                        if(!empty($usersmute)){
                                            $mutest = implode(",", $usersmute);
                                            //$msgsql = $odb -> prepare("SELECT DISTINCT `sender_userid` FROM `chat` WHERE `reciever_userid` = :userid AND `status`=1 AND `sender_userid` NOT IN (".$mutest.")");
                                            $msgsql = $odb -> prepare("SELECT DISTINCT `sender_userid` FROM `chat` WHERE `reciever_userid` = :userid AND `status`=1");
                                        }else{
                                            $msgsql = $odb -> prepare("SELECT DISTINCT `sender_userid` FROM `chat` WHERE `reciever_userid` = :userid AND `status`=1");
                                        }
                                        
                                        $msgsql -> execute(array(":userid" => $_SESSION['ID']));
                                        $msg = $msgsql -> fetchAll(); 
                                        if(!empty($msg)){
                                            foreach ($msg as $key => $value) {
                                                if(!in_array($value['sender_userid'], $usersmute)){
                                                    $count = $count+$user->getUnreadMessageCount($odb,$value['sender_userid'], $_SESSION['ID']);
                                                }
                                            }
                                        }
                                    ?>
                                    <span class="align-self-center">Messages</span> 
                                    <span class="badge badge-primary"><?php echo $count; ?> Unread</span>
                                </h6>
                            </div>
                            <div class="notification-scroll">
                                <?php
                                    if(!empty($msg)){
                                        foreach ($msg as $key => $value) {
                                            if(!in_array($value['sender_userid'], $usersmute)){
                                ?>  
                                                <div class="dropdown-item">
                                                    <div class="media server-log">
                                                        <!-- <img src="../src/assets/img/profile-16.jpeg" class="img-fluid me-2" alt="avatar"> -->
                                                        <div class="media-body">
                                                            <div class="data-info">
                                                                <?php 
                                                                    $userInfo = $user->userInfo($odb,$value['sender_userid']); 
                                                                    $message = $user->getLastUnreadMessage($odb,$value['sender_userid'], $_SESSION['ID']);
                                                                    $ts1 = $message['timestamp'];
                                                                    $ts2 = time();     
                                                                    $seconds_diff = $ts2 - $ts1;
                                                                    $time = '';
                                                                    if($seconds_diff>86400){
                                                                        $time = intval($seconds_diff/86400)." days";
                                                                    }elseif($seconds_diff>3600){
                                                                        $time = intval($seconds_diff/3600)." hr";
                                                                    }elseif($seconds_diff>60){
                                                                        $time = intval($seconds_diff/60)." min";
                                                                    }else{
                                                                        $time = $seconds_diff." sec";
                                                                    }
                                                                ?>
                                                                <a href="/chat.php?user_id=<?php echo $value['sender_userid']; ?>">
                                                                    <h6 class=""><?php echo $userInfo['username']; ?></h6>
                                                                    <p class=""><?php echo $time; ?> ago</p>
                                                                    <p class=""><?php echo $message['message']; ?></p>
                                                                </a>
                                                            </div>                                        
                                                        </div>
                                                    </div>
                                                </div>
                                <?php
                                            }
                                        }
                                    }
                                ?>
                                <div class="drodpown-title notification mt-2">
                                    <h6 class="d-flex justify-content-between"><span class="align-self-center">Notifications</span> 
                                        <a href="livechat/chat.php"> <span class="badge badge-secondary">View Chat</span></a>
                                    </h6>
                                </div>
                            </div>
                        </div>
                    </li>
                <?php } ?>
                <li class="nav-item dropdown user-profile-dropdown  order-lg-0 order-1">
                    <a href="javascript:void(0);" class="nav-link dropdown-toggle user" id="userProfileDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<p>My Account</p></a>

                    <div class="dropdown-menu position-absolute" aria-labelledby="userProfileDropdown">
                        <div class="user-profile-section">
                            <div class="media mx-auto">
                                <div class="emoji me-2">
                                    &#x1F44B;
                                </div>
                                <div class="media-body">
                                    <h5><?php 
									$usersql = $odb -> prepare("SELECT `username` FROM `users` WHERE `username` = :username");
									$usersql -> execute(array(":username" => $_SESSION['username']));
									$row = $usersql -> fetch(); 
									echo $_SESSION['username'];
									?></h5>
                                    <p><font color=<?=$loginInfo['color_code']?>><?=$loggedUserTeam?></font></p>
								   <?php


									// $ranksql = $odb -> prepare("SELECT `rank` FROM `users` WHERE `ID` = :id");
									// $ranksql -> execute(array(":id" => $_SESSION['ID']));
									// $row = $ranksql -> fetch(); 
									// if ($row['rank'] == "1")
									// {
									// 	echo "<p><font color=Orange>Administrative Member</font></p>";
									// }
									// elseif ($row['rank'] == "2")
									// {
									// 	echo "<p><font color=Yellow>Administrative Assistant Member</font></p>";
									// }
									// elseif ($row['rank'] == "3")
									// {
									// 	echo "<p><font color=Red>Red Team Member</font></p>";
									// }
									// elseif ($row['rank'] == "4")
									// {
									// 	echo "<p><font color=Teal>Blue Team Member</font></p>";
									// }
									// elseif ($row['rank'] == "5")
									// {
									// 	echo "<p><font color=Purple>Purple Team Member</font></p>";
									// }
									// else {
									// 	echo "<p><font color=White>No Team Assigned</font></p>";
									// }
									?>
                                </div>
                            </div>
                        </div>
                        <div class="dropdown-item">
                            <a href="../user-account-settings.php">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg> <span>Profile</span>
                            </a>
                        </div>
                        <?php if($loginInfo['restrict_chat']!=1){ ?>
                            <div class="dropdown-item">
                                <a href="group-list.php">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg> <span>Groups</span>
                                </a>
                            </div>
                        <?php } ?>
						<div class="dropdown-item">
                            <a href="../lock.php?lock=1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-lock"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg> <span>Lock Account</span>
                            </a>
                        </div>
                        <div class="dropdown-item">
                            <a href="../logout.php">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-log-out"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg> <span>Log Out</span>
                            </a>
                        </div>
                    </div>
                    
                </li>
           


            </ul>
        </header>
    </div>
    <!--  END NAVBAR  -->
        <div class="sidebar-wrapper sidebar-theme">

            <nav id="sidebar">

            <div class="navbar-nav theme-brand flex-row  text-center">
                    <div class="nav-logo">
                        <div class="nav-item theme-logo">
                            <a href="<?=BASEURL?>index.php">
                              <img src="../src/assets/img/RootCapturelogo.png" class="navbar-logo" alt="logo"> 
                            </a>
                        </div>
                         <div class="nav-item theme-text">
                            <a href="<?=BASEURL?>index.php">
                             <img src="https://rootcapture.com/frontend-assets/img/site-logo.svg" class="navbar-logo" alt="logo">
                         </a>
                        </div>
                      
                    </div>
                    <div class="nav-item sidebar-toggle">
                        <div class="btn-toggle sidebarCollapse">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevrons-left"><polyline points="11 17 6 12 11 7"></polyline><polyline points="18 17 13 12 18 7"></polyline></svg>
                        </div>
                    </div>
                </div>
                
	<?php
	if ($user -> isBlueTeam($odb))
	{
	?>
                <ul class="list-unstyled menu-categories" id="accordionExample">
                    <li class="menu active">
                        <a href="../index.php#dashboard" data-bs-toggle="collapse" aria-expanded="true" class="dropdown-toggle">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                                <span>My Dashboard</span>
                            </div>
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                            </div>
                        </a>
                        <ul class="collapse submenu list-unstyled show" id="dashboard" data-bs-parent="#accordionExample">
                            <li class="active">
                                <a href="<?=BASEURL?>index.php"> Home  </a>
                            </li>
                            <li class="active">
                                <a href="../assetip.php"> Asset List</a>
                            </li>
                        </ul>
                        <ul class="collapse submenu list-unstyled show" id="dashboard" data-bs-parent="#accordionExample">
                            <li class="active">
                            <a href="<?=BASEURL?>gradedrubric.php"> Graded Rubric  </a>
                            </li>
                        </ul>
                    </li>

                    <li class="menu menu-heading">
                        <div class="heading"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-minus"><line x1="5" y1="12" x2="19" y2="12"></line></svg><span>BLUE TEAM SYSTEMS</span></div>
                    </li>

                   
					
                    <li class="menu">
                        <a href="#vuln" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-box"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>                                <span>Vulnerable Assets</span>
                            </div>
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                            </div>
                        </a>
                        <ul class="collapse submenu list-unstyled" id="vuln" data-bs-parent="#accordionExample">
                            <?=$blueTeamSystem?>					
                        </ul>
                    </li>

                    <li class="menu">
                        <a href="#userQuiz" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                <span>Quiz</span>
                            </div>
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                            </div>
                        </a>
                        <ul class="collapse submenu list-unstyled" id="userQuiz" data-bs-parent="#accordionExample">
                            <li><a href="<?=BASEURL?>quiz_list.php">Quiz List</a></li>
                        </ul>
                    </li>

                    <li class="menu menu-heading">
                        <div class="heading"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-minus"><line x1="5" y1="12" x2="19" y2="12"></line></svg><span>SUPPORT</span></div>
                    </li>					
                    <li class="menu">
                        <a href="../tickets.php" aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                                <span>Support Tickets</span>
                            </div>
                        </a>
                    </li>
                    <!-- <li class="menu">
                        <a href="../kb/index.php" aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                <span> Enterprise Support</span>
                            </div>
                        </a>
                    </li> -->
                </ul>
    <?php
	}
	?>
	<?php
	if ($user -> isRedTeam($odb))
	{
	?>
                <ul class="list-unstyled menu-categories" id="accordionExample">
                    <li class="menu active">
                        <a href="../index.php#dashboard" data-bs-toggle="collapse" aria-expanded="true" class="dropdown-toggle">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                                <span>Dashboard</span>
                            </div>
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                            </div>
                        </a>
                        <ul class="collapse submenu list-unstyled show" id="dashboard" data-bs-parent="#accordionExample">
                            <li class="active">
                                <a href="<?=BASEURL?>index.php"> Home  </a>
                            </li>
                            <li class="active">
                                <a href="../assetip.php"> Asset List </a>
                            </li>
                        </ul>
						<ul class="collapse submenu list-unstyled show" id="dashboard" data-bs-parent="#accordionExample">
                            <li class="active">
                                <a href="<?=BASEURL?>gradedrubric.php"> Graded Rubric </a>
                            </li>
                        </ul>
                    </li>

                    <li class="menu menu-heading">
                        <div class="heading"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-minus"><line x1="5" y1="12" x2="19" y2="12"></line></svg><span>RED TEAM SYSTEMS</span></div>
                    </li>
					
                    <li class="menu">
                        <a href="#red" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-box"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                                <span>The Arsenal</span>
                            </div>
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                            </div>
                        </a>
                        <ul class="collapse submenu list-unstyled" id="red" data-bs-parent="#accordionExample">
                            <?=$redTeamSystem?>
                        </ul>
                    </li>

                    <li class="menu">
                <a href="#userQuiz" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                        <span>Quiz</span>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </div>
                </a>
                <ul class="collapse submenu list-unstyled" id="userQuiz" data-bs-parent="#accordionExample">
                    <li><a href="<?=BASEURL?>quiz_list.php">Quiz List</a></li>
                </ul>
            </li>

                    <li class="menu menu-heading">
                        <div class="heading"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-minus"><line x1="5" y1="12" x2="19" y2="12"></line></svg><span>SUPPORT</span></div>
                    </li>
                    <li class="menu">
                        <a href="../tickets.php" aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                                <span>Support Tickets</span>
                            </div>
                        </a>
                    </li>
                    <!-- <li class="menu">
                        <a href="../kb/index.php" aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                <span> Enterprise Support</span>
                            </div>
                        </a>
                    </li> -->
                </ul>
	<?php
	}	
	else if ($user -> isPurpleTeam($odb))
	{
	?>
        <ul class="list-unstyled menu-categories" id="accordionExample">
            <li class="menu active">
                <a href="../index.php#dashboard" data-bs-toggle="collapse" aria-expanded="true" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                        <span>Dashboard</span>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </div>
                </a>
                <ul class="collapse submenu list-unstyled show" id="dashboard" data-bs-parent="#accordionExample">
                    <li class="active">
                        <a href="<?=BASEURL?>index.php"> Home  </a>
                    </li>
                    <li class="active">
                        <a href="../assetip.php"> Asset List </a>
                    </li>
                </ul>
                <ul class="collapse submenu list-unstyled show" id="dashboard" data-bs-parent="#accordionExample">
                    <li class="active">
                        <a href="<?=BASEURL?>gradedrubric.php"> Graded Rubric </a>
                    </li>
                </ul>
            </li>

            <li class="menu menu-heading">
                <div class="heading"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-minus"><line x1="5" y1="12" x2="19" y2="12"></line></svg><span>PURPLE TEAM SYSTEMS</span></div>
            </li>
            
            <li class="menu">
                <a href="#inv" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-airplay"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                        <span>Forensics Systems</span>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </div>
                </a>
                <ul class="collapse submenu list-unstyled" id="inv" data-bs-parent="#accordionExample">
                    <?=$purpleTeamSystem?>						
                </ul>
            </li>

            <li class="menu">
                <a href="#userQuiz" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                        <span>Quiz</span>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </div>
                </a>
                <ul class="collapse submenu list-unstyled" id="userQuiz" data-bs-parent="#accordionExample">
                    <li><a href="<?=BASEURL?>quiz_list.php">Quiz List</a></li>
                </ul>
            </li>

            <li class="menu menu-heading">
                <div class="heading"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-minus"><line x1="5" y1="12" x2="19" y2="12"></line></svg><span>SUPPORT</span></div>
            </li>	
            <li class="menu">
                <a href="../tickets.php" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                        <span>Support Tickets</span>
                    </div>
                </a>
            </li>
            <!-- <li class="menu">
                <a href="../kb/index.php" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                        <span> Enterprise Support</span>
                    </div>
                </a>
            </li> -->
        </ul>
	<?php
	}
	else if ($user -> isAdmin($odb))
	{ 
        ?>
                    <ul class="list-unstyled menu-categories" id="accordionExample">
                        <li class="menu active">
                           
                                <li class="menu active">
                                    <a href="<?=BASEURL?>index.php" aria-expanded="false" class="dropdown-toggle">
                                        <div class="">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                                            <span> Home  </span>
                                        </div>
                                    </a>
                                </li>
                                <li class="menu active">
                                    <a href="../assetip.php" aria-expanded="false" class="dropdown-toggle">
                                        <div class="">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-clipboard"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path><rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect></svg>
                                            <span> Asset List  </span>
                                        </div>
                                    </a>
                                        </li>
                                        <li class="menu active">
                                            <a href="<?=BASEURL?>gradedrubric.php" aria-expanded="false" class="dropdown-toggle">
                                        <div class="">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-book"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg><span>  Grading Rubric </span>
                                        </div>
                                    </a>
                                </li>
                        
                            
                        </li>
                        <li class="menu menu-heading">
                            <div class="heading"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-minus"><line x1="5" y1="12" x2="19" y2="12"></line></svg><span>ADMINISTRATIVE FUNCTIONS</span></div>
                        </li>

                        <li class="menu <?php if(strpos($currUrl, "create-range-administration.php")){echo 'active';} ?>">
                            <a href="<?=BASEURL?>admin/create-range-administration.php" aria-expanded="false" class="dropdown-toggle">
                                <div class="">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                                    <span>Admin Dashboard</span>
                                </div>
                            </a>
                        </li>

                        <!-- <li class="menu <?php //if(strpos($currUrl, "raised_ticket.php")){echo 'active';} ?>">
                            <a href="<?=BASEURL?>admin/raised_ticket.php" aria-expanded="false" class="dropdown-toggle">
                                <div class="">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                                    <span>Raised Ticket</span>
                                </div>
                            </a>
                        </li> -->

                        <li class="menu">
                            <a href="#announce_admin" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                            <div class="">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-map"><polygon points="1 6 1 22 8 18 16 22 23 18 23 2 16 6 8 2 1 6"></polygon><line x1="8" y1="2" x2="8" y2="18"></line><line x1="16" y1="6" x2="16" y2="22"></line></svg>
                                    <span title="Announcements Administration">Announcements </span>
                                </div>
                                <div>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                                </div>
                            </a>
                            <ul class="collapse submenu list-unstyled" id="announce_admin" data-bs-parent="#accordionExample">
                                <li>
                                    <a href="<?=BASEURL?>dashboard/news.php"> Manage Announcements </a>
                                </li>
                                <li>
                                    <a href="<?=BASEURL?>admin/addnews.php"> Create An Announcement </a>
                                </li>                           						
                            </ul>
                        </li>

                        <li class="menu">
                            <a href="#manage_team" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                                    <span title="User Management">Team Management</span>
                                </div>
                                <div>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                                </div>
                            </a>
                            <ul class="collapse submenu list-unstyled" id="manage_team" data-bs-parent="#accordionExample">
                                <li>
                                    <a href="<?=BASEURL?>dashboard/manage-team.php"> Manage Teams </a>
                                </li>
                                <li>
                                    <a href="<?=BASEURL?>admin/create_team.php"> Create Team </a>
                                </li>                           						
                            </ul>
                        </li>	

                        <li class="menu">
                            <a href="#system_group_mngmnt" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-pie-chart"><path d="M21.21 15.89A10 10 0 1 1 8 2.83"></path><path d="M22 12A10 10 0 0 0 12 2v10z"></path></svg>
                                    <span title="Announcements Administration">System Group Management </span>
                                </div>
                                <div>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                                </div>
                            </a>
                            <ul class="collapse submenu list-unstyled" id="system_group_mngmnt" data-bs-parent="#accordionExample">
                                <li class=""><a href="<?=BASEURL?>dashboard/asset_group.php">Manage System Groups</a></li>                      						
                                <li class=""><a href="<?=BASEURL?>admin/create_group.php">Create System Groups</a></li>                      						
                            </ul>
                        </li>

                        <li class="menu">
                            <a href="#system_mngmnt" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                                    <span title="Announcements Administration">System Management </span>
                                </div>
                                <div>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                                </div>
                            </a>
                            <ul class="collapse submenu list-unstyled" id="system_mngmnt" data-bs-parent="#accordionExample">
                                <li><a href="<?=BASEURL?>admin/manage_assets.php">Manage Systems</a></li>
                                <li><a href="<?=BASEURL?>admin/create_asset.php">Create System</a></li>                         						
                            </ul>
                        </li>

                        <li class="menu">
                            <a href="#user_admin" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-layers"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>
                                    <span title="User Management">User Management</span>
                                </div>
                                <div>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                                </div>
                            </a>
                            <ul class="collapse submenu list-unstyled" id="user_admin" data-bs-parent="#accordionExample">
                                <li>
                                    <a href="<?=BASEURL?>dashboard/users.php"> Manage Users </a>
                                </li>
                                <li>
                                    <a href="<?=BASEURL?>admin/addusers.php"> Create A New User </a>
                                </li>                           						
                            </ul>
                        </li>

                        <li class="menu">
                            <a href="#manage_api" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-airplay"><path d="M5 17H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-1"></path><polygon points="12 15 17 21 7 21 12 15"></polygon></svg>
                                    <span title="User Management">API Management</span>
                                </div>
                                <div>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                                </div>
                            </a>
                            <ul class="collapse submenu list-unstyled" id="manage_api" data-bs-parent="#accordionExample">
                                <li>
                                    <a href="<?=BASEURL?>admin/manage_apis.php"> Manage APIs </a>
                                </li>
                                <li>
                                    <a href="<?=BASEURL?>admin/create_apis.php"> Create APIs </a>
                                </li>                                                   
                            </ul>
                        </li>

                        <li class="menu">
                            <a href="#adm" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-plus"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="12" y1="18" x2="12" y2="12"></line><line x1="9" y1="15" x2="15" y2="15"></line></svg>
                                    <span title="Rubric Management">Rubric Management</span>
                                </div>
                                <div>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                                </div>
                            </a>
                            <ul class="collapse submenu list-unstyled" id="adm" data-bs-parent="#accordionExample">
                            
                                <li>
                                    <a href="<?=BASEURL?>admin/manage-grading-rubric.php"> Manage Grades</a>
                                </li>
                                <li>
                                    <a href="<?=BASEURL?>admin/add-grading-rubric.php"> Create New Criterion</a>
                                </li>		
                            </ul>
                        </li>



                        <li class="menu">
                            <a href="#quize" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-plus"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="12" y1="18" x2="12" y2="12"></line><line x1="9" y1="15" x2="15" y2="15"></line></svg>
                                    <span title="Quize Management">Quiz Management</span>
                                </div>
                                <div>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                                </div>
                            </a>
                            <ul class="collapse submenu list-unstyled" id="quize" data-bs-parent="#accordionExample">
                            
                                <li>
                                <a href="<?=BASEURL?>admin/manage_quizes.php"> Manage Quiz</a>
                                </li>
                                <li>
                                    <a href="<?=BASEURL?>admin/create_quize.php"> Create New</a>
                                </li>		
                            </ul>
                        </li>

                        <li class="menu">
                            <a href="#knowledgebase" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-plus"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="12" y1="18" x2="12" y2="12"></line><line x1="9" y1="15" x2="15" y2="15"></line></svg>
                                    <span title="Knowledgebase">Knowledgebase</span>
                                </div>
                                <div>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                                </div>
                            </a>
                            <ul class="collapse submenu list-unstyled" id="knowledgebase" data-bs-parent="#accordionExample">
                            
                                <li>
                                <a href="<?=BASEURL?>admin/category.php"> Category</a>
                                </li>
                                <li>
                                <a href="<?=BASEURL?>admin/sub_category.php"> Sub Category</a>
                                </li>
                                <li>
                                <a href="<?=BASEURL?>admin/knowledgebase.php"> Knowledgebase</a>
                                </li>
                               	
                            </ul>
                        </li>

                        <li class="menu">
                            <a href="#course_system" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-plus"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="12" y1="18" x2="12" y2="12"></line><line x1="9" y1="15" x2="15" y2="15"></line></svg>
                                    <span title="course_system">Course System</span>
                                </div>
                                <div>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                                </div>
                            </a>
                            <ul class="collapse submenu list-unstyled" id="course_system" data-bs-parent="#accordionExample">
                            
                                <li>
                                <a href="<?=BASEURL?>admin/course_category.php"> Category</a>
                                </li>
                               
                                <li>
                                <a href="<?=BASEURL?>admin/course_system.php"> Course</a>
                                </li>
                               	
                            </ul>
                        </li>
                       
<!-- start new code 25 April -->
<?php

if( count($sqlDynamicTeamLIst) > 0 )
{
?>
<li class="menu menu-heading">
    <div class="heading"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-minus"><line x1="5" y1="12" x2="19" y2="12"></line></svg><span>SYSTEM LIST</span></div>
</li>
<?php
} 
foreach($sqlDynamicTeamLIst as $sqlDynamicTeamLIstV)
{
    
    $SQLassetList = $odb -> query("SELECT name FROM `asset_group` WHERE `team` = ".$sqlDynamicTeamLIstV['id']." AND college_id = $college_id")->fetchAll();                
    if(count($SQLassetList)>0){
    foreach($SQLassetList as $assetListV){ 
        $teamAsset = '<li><a href="'.BASEURL.'asset_system.php?group='.$assetListV['name'].'">'.$assetListV['name'].'</a></li>';	
    }
    }
    else
    {
        $teamAsset = '<li><a href="javascript:void(0)">No Systems Have Been Added Yet</a></li>';	
    }
    
   if($sqlDynamicTeamLIstV['name'] == 'Purple Team')
   {
?>
     <li class="menu menu-heading">
        <div class="heading"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-minus"><line x1="5" y1="12" x2="19" y2="12"></line></svg><span>PURPLE TEAM</span></div>
    </li>
    <li class="menu">
        <a href="#inv" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
            <div class="">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-box"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                <span>Purple Systems</span>
            </div>
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </div>
        </a>
        <ul class="collapse submenu list-unstyled" id="inv" data-bs-parent="#accordionExample">             
             <?=$teamAsset?>
        </ul>
    </li>
<?php
   }
   else if($sqlDynamicTeamLIstV['name'] == 'Red Team')
   {
?>
    <li class="menu menu-heading">
                            <div class="heading"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-minus"><line x1="5" y1="12" x2="19" y2="12"></line></svg><span>RED TEAM</span></div>
    </li>
    <li class="menu">
        <a href="#red" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
            <div class="">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-zap"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
                <span>Red Systems</span>
            </div>
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </div>
        </a>
        <ul class="collapse submenu list-unstyled" id="red" data-bs-parent="#accordionExample">
            <?=$teamAsset?>	
        </ul>
    </li>    
<?php
   }
   else if($sqlDynamicTeamLIstV['name'] == 'Blue Team')
   {
?>
    <li class="menu menu-heading">
        <div class="heading"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-minus"><line x1="5" y1="12" x2="19" y2="12"></line></svg><span>BLUE TEAM</span></div>
    </li>
    <li class="menu">
        <a href="#vuln" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
        <div class="">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-terminal"><polyline points="4 17 10 11 4 5"></polyline><line x1="12" y1="19" x2="20" y2="19"></line></svg>
                <span>Blue Systems</span>
            </div>
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </div>
        </a>
        <ul class="collapse submenu list-unstyled" id="vuln" data-bs-parent="#accordionExample">                           	
            <?=$teamAsset?>				
        </ul>
    </li>
      

<?php 
   }
   else
   {
    $teamname = $sqlDynamicTeamLIstV['name'];
?>
    <li class="menu menu-heading">
        <div class="heading"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-minus"><line x1="5" y1="12" x2="19" y2="12"></line></svg><span><?=strtoupper($teamname)?></span></div>
    </li>
    <li class="menu">
        <a href="#<?=preg_replace('/\s+/', '_', $teamname)?>" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
        <div class="">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-zap"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
                <span><?=strstr($teamname, ' ', true)?> Systems</span>
            </div>
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </div>
        </a>
        <ul class="collapse submenu list-unstyled" id="<?=preg_replace('/\s+/', '_', $teamname)?>" data-bs-parent="#accordionExample">
            <?=$teamAsset?>
        </ul>
    </li>
<?php
   }
?>
<?php }
?>
<!-- old code 25 april -->
                       
                       
                        <li class="menu menu-heading">
                            <div class="heading"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-minus"><line x1="5" y1="12" x2="19" y2="12"></line></svg><span>ADMINISTRATIVE SUPPORT</span></div>
                        </li>					
                        <li class="menu">
                            <a href="/admin/manageTickets.php" aria-expanded="false" class="dropdown-toggle">
                                <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-pen-tool"><path d="M12 19l7-7 3 3-7 7-3-3z"></path><path d="M18 13l-1.5-7.5L2 2l3.5 14.5L13 18l5-5z"></path><path d="M2 2l7.586 7.586"></path><circle cx="11" cy="11" r="2"></circle></svg>
                                    <span>Manage Tickets</span>
                                </div>
                            </a>
                        </li>
                        <li class="menu">
                            <a href="<?php echo $urlData ?>" aria-expanded="false" class="dropdown-toggle">
                                <div class="">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                    <span> Enterprise Supports</span>
                                </div>
                            </a>
                        </li>
                    </ul>
        <?php
	}
    else if ($user -> isAssist($odb))
	{
       
	?>
        <ul class="list-unstyled menu-categories" id="accordionExample">
            <li class="menu active">
                <a href="../index.php#dashboard" data-bs-toggle="collapse" aria-expanded="true" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                        <span>Dashboard</span>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </div>
                </a>
                <ul class="collapse submenu list-unstyled show" id="dashboard" data-bs-parent="#accordionExample">
                    <li class="active">
                        <a href="<?=BASEURL?>index.php"> Home  </a>
                    </li>
                    <li class="active">
                        <a href="../assetip.php"> Asset List </a>
                    </li>
                </ul>
                <ul class="collapse submenu list-unstyled show" id="dashboard" data-bs-parent="#accordionExample">
                    <li class="active">
                        <a href="<?=BASEURL?>gradedrubric.php"> Graded Rubric </a>
                    </li>
                </ul>
            </li>
            <li class="menu menu-heading">
                <div class="heading"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-minus"><line x1="5" y1="12" x2="19" y2="12"></line></svg><span>ADMINISTRATIVE FUNCTIONS</span></div>
            </li>

            <!-- CheckUsers -->
                    <?php  if($getUserMetaData != null && in_array("dashboard",$getUserMetaData)) { ?>
                    <li class="menu <?php if(strpos($currUrl, "create-range-administration.php")){echo 'active';} ?>">
                        <a href="<?=BASEURL?>admin/create-range-administration.php" aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                                <span>Admin Dashboard</span>
                            </div>
                        </a>
                    </li>
                    <?php } ?>


                    <?php  if($getUserMetaData != null && in_array("announcement",$getUserMetaData)) { ?>
                    <li class="menu">
                        <a href="#announce_admin" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
						<div class="">
                                                     <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-map"><polygon points="1 6 1 22 8 18 16 22 23 18 23 2 16 6 8 2 1 6"></polygon><line x1="8" y1="2" x2="8" y2="18"></line><line x1="16" y1="6" x2="16" y2="22"></line></svg>
                                <span title="Announcements Administration">Announcements </span>
                            </div>
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                            </div>
                        </a>
                        <ul class="collapse submenu list-unstyled" id="announce_admin" data-bs-parent="#accordionExample">
						    <li>
                                <a href="<?=BASEURL?>dashboard/news.php"> Manage Announcements </a>
                            </li>
                            <li>
                                <a href="<?=BASEURL?>admin/addnews.php"> Create An Announcement </a>
                            </li>                           						
                        </ul>
                    </li>
                    <?php } ?>

                    <?php  if($getUserMetaData != null && in_array("team",$getUserMetaData)) { ?>
                    <li class="menu">
                        <a href="#manage_team" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
						<div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                                <span title="User Management">Team Management</span>
                            </div>
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                            </div>
                        </a>
                        <ul class="collapse submenu list-unstyled" id="manage_team" data-bs-parent="#accordionExample">
						    <li>
                                <a href="<?=BASEURL?>dashboard/manage-team.php"> Manage Teams </a>
                            </li>
                            <li>
                                <a href="<?=BASEURL?>admin/create_team.php"> Create Team </a>
                            </li>                           						
                        </ul>
                    </li>	
                    <?php } ?>

                    <?php  if($getUserMetaData != null && in_array("system_group",$getUserMetaData)) { ?>
                    <li class="menu">
                        <a href="#system_group_mngmnt" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
						<div class="">
                               <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-pie-chart"><path d="M21.21 15.89A10 10 0 1 1 8 2.83"></path><path d="M22 12A10 10 0 0 0 12 2v10z"></path></svg>
                                <span title="Announcements Administration">System Group Management </span>
                            </div>
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                            </div>
                        </a>
                        <ul class="collapse submenu list-unstyled" id="system_group_mngmnt" data-bs-parent="#accordionExample">
                             <li class=""><a href="<?=BASEURL?>admin/asset_group.php">Manage System Groups</a></li>                      						
                            <li class=""><a href="<?=BASEURL?>admin/create_group.php">Create System Groups</a></li>                        						
                        </ul>
                    </li>
                    <?php } ?>

                    <?php  if($getUserMetaData != null && in_array("system",$getUserMetaData)) { ?>
                    <li class="menu">
                        <a href="#system_mngmnt" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
						<div class="">
                               <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                                <span title="Announcements Administration">System Management </span>
                            </div>
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                            </div>
                        </a>
                        <ul class="collapse submenu list-unstyled" id="system_mngmnt" data-bs-parent="#accordionExample">
                            <li><a href="<?=BASEURL?>admin/manage_assets.php">Manage Systems</a></li>
                            <li><a href="<?=BASEURL?>admin/create_asset.php">Create System</a></li>                         						
                        </ul>
                    </li>
                    <?php } ?>
                    
                    <?php  if($getUserMetaData != null && in_array("user",$getUserMetaData)) { ?>
                    <li class="menu">
                        <a href="#user_admin" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
						<div class="">
                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-layers"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>
                                <span title="User Management">User Management</span>
                            </div>
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                            </div>
                        </a>
                        <ul class="collapse submenu list-unstyled" id="user_admin" data-bs-parent="#accordionExample">
						    <li>
                                <a href="<?=BASEURL?>dashboard/users.php"> Manage Users </a>
                            </li>
                            <li>
                                <a href="<?=BASEURL?>admin/addusers.php"> Create A New User </a>
                            </li>                           						
                        </ul>
                    </li>
                    <?php } ?>
                    
                    <?php  if($getUserMetaData != null && in_array("api",$getUserMetaData)) { ?>
                    <li class="menu">
                        <a href="#manage_api" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        <div class="">
                               <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-airplay"><path d="M5 17H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-1"></path><polygon points="12 15 17 21 7 21 12 15"></polygon></svg>
                                <span title="User Management">API Management</span>
                            </div>
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                            </div>
                        </a>
                        <ul class="collapse submenu list-unstyled" id="manage_api" data-bs-parent="#accordionExample">
                            <li>
                                <a href="<?=BASEURL?>admin/manage_apis.php"> Manage APIs </a>
                            </li>
                            <li>
                                <a href="<?=BASEURL?>admin/create_apis.php"> Create APIs </a>
                            </li>                                                   
                        </ul>
                    </li>
                    <?php } ?>
                    
                    <?php  if($getUserMetaData != null && in_array("rubric",$getUserMetaData)) { ?>
                    <li class="menu">
                        <a href="#adm" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
						<div class="">
                               <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-plus"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="12" y1="18" x2="12" y2="12"></line><line x1="9" y1="15" x2="15" y2="15"></line></svg>
                                <span title="Rubric Management">Rubric Management</span>
                            </div>
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                            </div>
                        </a>
                        <ul class="collapse submenu list-unstyled" id="adm" data-bs-parent="#accordionExample">
						  
							<li>
                                <a href="<?=BASEURL?>admin/manage-grading-rubric.php"> Manage Grades</a>
                            </li>
                            <li>
                                <a href="<?=BASEURL?>admin/add-grading-rubric.php"> Create New Criterion</a>
                            </li>		
                        </ul>
                    </li>
                    <?php } ?>

                    <?php  if($getUserMetaData != null && in_array("quiz",$getUserMetaData)) { ?>
                    <li class="menu">
                            <a href="#quize" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-plus"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="12" y1="18" x2="12" y2="12"></line><line x1="9" y1="15" x2="15" y2="15"></line></svg>
                                    <span title="Quize Management">Quiz Management</span>
                                </div>
                                <div>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                                </div>
                            </a>
                            <ul class="collapse submenu list-unstyled" id="quize" data-bs-parent="#accordionExample">
                            
                                <li>
                                <a href="<?=BASEURL?>admin/manage_quizes.php"> Manage Quiz</a>
                                </li>
                                <li>
                                    <a href="<?=BASEURL?>admin/create_quize.php"> Create New</a>
                                </li>		
                            </ul>
                    </li>
                    <?php } ?>

                    <?php  if($getUserMetaData != null && in_array("knowledgebase",$getUserMetaData)) { ?>
                    <li class="menu">
                        <a href="#knowledgebase" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        <div class="">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-plus"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="12" y1="18" x2="12" y2="12"></line><line x1="9" y1="15" x2="15" y2="15"></line></svg>
                                <span title="Knowledgebase">Knowledgebase</span>
                            </div>
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                            </div>
                        </a>
                        <ul class="collapse submenu list-unstyled" id="knowledgebase" data-bs-parent="#accordionExample">
                        
                            <li>
                            <a href="<?=BASEURL?>admin/category.php"> Category</a>
                            </li>
                            <li>
                            <a href="<?=BASEURL?>admin/sub_category.php"> Sub Category</a>
                            </li>
                            <li>
                            <a href="<?=BASEURL?>admin/knowledgebase.php"> Knowledgebase</a>
                            </li>
                            
                        </ul>
                    </li>
                    <?php } ?>

                    <?php  if($getUserMetaData != null && in_array("course_system",$getUserMetaData)) { ?>
                    <li class="menu">
                        <a href="#course_system" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        <div class="">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-plus"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="12" y1="18" x2="12" y2="12"></line><line x1="9" y1="15" x2="15" y2="15"></line></svg>
                                <span title="course_system">Course System</span>
                            </div>
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                            </div>
                        </a>
                        <ul class="collapse submenu list-unstyled" id="course_system" data-bs-parent="#accordionExample">
                        
                            <li>
                            <a href="<?=BASEURL?>admin/course_category.php"> Category</a>
                            </li>
                            
                            <li>
                            <a href="<?=BASEURL?>admin/course_system.php"> Course</a>
                            </li>
                            
                        </ul>
                    </li>
                    <?php } ?>
<!-- dynamic system list for assistant -->

<?php

if( count($sqlDynamicTeamLIst) > 0 )
{
?>
<li class="menu menu-heading">
    <div class="heading"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-minus"><line x1="5" y1="12" x2="19" y2="12"></line></svg><span>SYSTEM LIST</span></div>
</li>
<?php
} 
foreach($sqlDynamicTeamLIst as $sqlDynamicTeamLIstV)
{
    
    $SQLassetList = $odb -> query("SELECT name FROM `asset_group` WHERE `team` = ".$sqlDynamicTeamLIstV['id']." AND college_id = $college_id")->fetchAll();                
    if(count($SQLassetList)>0){
    foreach($SQLassetList as $assetListV){ 
        $teamAsset = '<li><a href="'.BASEURL.'asset_system.php?group='.$assetListV['name'].'">'.$assetListV['name'].'</a></li>';	
    }
    }
    else
    {
        $teamAsset = '<li><a href="javascript:void(0)">No Systems Have Been Added Yet</a></li>';	
    }
    
   if($sqlDynamicTeamLIstV['name'] == 'Purple Team')
   {
?>
     <li class="menu menu-heading">
        <div class="heading"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-minus"><line x1="5" y1="12" x2="19" y2="12"></line></svg><span>PURPLE TEAM</span></div>
    </li>
    <li class="menu">
        <a href="#inv" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
            <div class="">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-box"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                <span>Purple Systems</span>
            </div>
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </div>
        </a>
        <ul class="collapse submenu list-unstyled" id="inv" data-bs-parent="#accordionExample">             
             <?=$teamAsset?>
        </ul>
    </li>
<?php
   }
   else if($sqlDynamicTeamLIstV['name'] == 'Red Team')
   {
?>
    <li class="menu menu-heading">
                            <div class="heading"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-minus"><line x1="5" y1="12" x2="19" y2="12"></line></svg><span>RED TEAM</span></div>
    </li>
    <li class="menu">
        <a href="#red" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
            <div class="">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-zap"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
                <span>Red Systems</span>
            </div>
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </div>
        </a>
        <ul class="collapse submenu list-unstyled" id="red" data-bs-parent="#accordionExample">
            <?=$teamAsset?>	
        </ul>
    </li>    
<?php
   }
   else if($sqlDynamicTeamLIstV['name'] == 'Blue Team')
   {
?>
    <li class="menu menu-heading">
        <div class="heading"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-minus"><line x1="5" y1="12" x2="19" y2="12"></line></svg><span>BLUE TEAM</span></div>
    </li>
    <li class="menu">
        <a href="#vuln" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
        <div class="">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-terminal"><polyline points="4 17 10 11 4 5"></polyline><line x1="12" y1="19" x2="20" y2="19"></line></svg>
                <span>Blue Systems</span>
            </div>
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </div>
        </a>
        <ul class="collapse submenu list-unstyled" id="vuln" data-bs-parent="#accordionExample">                           	
            <?=$teamAsset?>				
        </ul>
    </li>
      

<?php 
   }
   else
   {
    $teamname = $sqlDynamicTeamLIstV['name'];
?>
    <li class="menu menu-heading">
        <div class="heading"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-minus"><line x1="5" y1="12" x2="19" y2="12"></line></svg><span><?=strtoupper($teamname)?></span></div>
    </li>
    <li class="menu">
        <a href="#<?=preg_replace('/\s+/', '_', $teamname)?>" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
        <div class="">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-zap"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
                <span><?=strstr($teamname, ' ', true)?> Systems</span>
            </div>
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </div>
        </a>
        <ul class="collapse submenu list-unstyled" id="<?=preg_replace('/\s+/', '_', $teamname)?>" data-bs-parent="#accordionExample">
            <?=$teamAsset?>
        </ul>
    </li>
<?php
   }
?>
<?php }
?>

<!-- end dynmic system list for assistant -->



            <li class="menu menu-heading">
                <div class="heading"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-minus"><line x1="5" y1="12" x2="19" y2="12"></line></svg><span>ADMINISTRATIVE SUPPORT</span></div>
            </li>
            
            <?php  if($getUserMetaData != null && in_array("ticket",$getUserMetaData)) { ?>
            <li class="menu">
                <a href="/admin/manageTickets.php" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-pen-tool"><path d="M12 19l7-7 3 3-7 7-3-3z"></path><path d="M18 13l-1.5-7.5L2 2l3.5 14.5L13 18l5-5z"></path><path d="M2 2l7.586 7.586"></path><circle cx="11" cy="11" r="2"></circle></svg>
                        <span>Manage Tickets</span>
                    </div>
                </a>
            </li>
            <?php } ?>

            <li class="menu">
                <a href="<?php echo $urlData ?>" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                        <span> Enterprise Support.</span>
                    </div>
                </a>
            </li>
        </ul>
	<?php 
    } 
    else
    { ?>
 <ul class="list-unstyled menu-categories" id="accordionExample">
                    <li class="menu active">
                        <a href="../index.php#dashboard" data-bs-toggle="collapse" aria-expanded="true" class="dropdown-toggle">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                                <span>Dashboard</span>
                            </div>
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                            </div>
                        </a>
                        <ul class="collapse submenu list-unstyled show" id="dashboard" data-bs-parent="#accordionExample">
                            <li class="active">
                                <a href="<?=BASEURL?>index.php"> Home  </a>
                            </li>
                            <li class="active">
                                <a href="../assetip.php"> Asset List </a>
                            </li>
                        </ul>
						<ul class="collapse submenu list-unstyled show" id="dashboard" data-bs-parent="#accordionExample">
                            <li class="active">
                                <a href="<?=BASEURL?>gradedrubric.php"> Graded Rubric </a>
                            </li>
                        </ul>
                    </li>

                    

                    <li class="menu menu-heading">
                        <div class="heading"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-minus"><line x1="5" y1="12" x2="19" y2="12"></line></svg><span><?=strtoupper($loginInfo['team_name'])?> SYSTEMS</span></div>
                    </li>
					
                    <li class="menu">
                        <a href="#inv" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-airplay"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                                <span>Forensics Systems</span>
                            </div>
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                            </div>
                        </a>
                        <ul class="collapse submenu list-unstyled" id="inv" data-bs-parent="#accordionExample">
                        <?php
                            $SQLTeamSystem = $odb -> query("SELECT asset_group.name FROM `asset_group` INNER JOIN `teams` ON teams.id = asset_group.team INNER JOIN team_status ON team_status.team_id = teams.id WHERE `teams`.`name` LIKE '$loggedUserTeam' AND team_status.college_id = $college_id AND team_status.status = 1"); 
                            while ($getInfo = $SQLTeamSystem -> fetch(PDO::FETCH_ASSOC)){ 
                               echo  '<li><a href="'.BASEURL.'asset_system.php?group='.$getInfo['name'].'">'.$getInfo['name'].'</a></li>';	
                            } 
                            ?> 
                        </ul>
                    </li>

                    <li class="menu">
                        <a href="#userQuiz" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                <span>Quiz</span>
                            </div>
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                            </div>
                        </a>
                        <ul class="collapse submenu list-unstyled" id="userQuiz" data-bs-parent="#accordionExample">
                            <li><a href="<?=BASEURL?>quiz_list.php">Quiz List</a></li>
                        </ul>
                    </li>

                    <li class="menu menu-heading">
                        <div class="heading"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-minus"><line x1="5" y1="12" x2="19" y2="12"></line></svg><span>SUPPORT</span></div>
                    </li>					
                    <li class="menu">
                        <a href="../tickets.php" aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                                <span>Support Tickets</span>
                            </div>
                        </a>
                    </li>
                    <!-- <li class="menu">
                        <a href="../kb/index.php" aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                <span> Enterprise Support..</span>
                            </div>
                        </a>
                    </li> -->
                </ul>
   <?php }
	?>
            </nav>
        </div>
        <!--  END SIDEBAR  -->
