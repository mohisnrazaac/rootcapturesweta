<?php
ob_start();
require_once 'includes/db.php';
require_once 'includes/init.php';
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
@session_start();
    $loggedUserId    = $_SESSION['ID'];
    $userInfo = $user->userInfo($odb,$loggedUserId);
    if($userInfo['restrict_chat']==1){
        header('location: /index.php');
        die();
    }

	if (isset($_POST['submitUser']))
	{
		$username = $_POST['username'];
		$email = $_POST['email'];
		$password = $_POST['password'];
		$repassword = $_POST['repassword'];
		$role = $_POST['role'];
		$phone = $_POST['phone'];
        

		$errors = array();
		if (empty($username) || empty($email) || empty($password) || empty($repassword) || empty($role) || empty($phone))
		{
			$errors[] = 'Please verify all fields'; 
		} 
        else
        {
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
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

                $SQLinsert = $odb -> prepare("INSERT INTO `users` VALUES(NULL, :username, :password, :email, :rank, :phone, 0, 0, 0, NULL, 0, Null)");
                $SQLinsert -> execute(array(':username' => $username, ':password' => SHA1($password), ':email' => $email, ':rank' => $role, ':phone' => $phone));
            // }
            // catch(Exception $e) {
            //     echo 'Exception -> ';
            //     var_dump($e->getMessage());
            // }
                
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
    
    $getuserList     = $odb -> query("SELECT * FROM `users` WHERE ID !=  $loggedUserId")->fetchAll();
    $getGroups     = $odb -> query("SELECT * FROM chat_group WHERE FIND_IN_SET($loggedUserId, `group_members`) OR `created_by`=$loggedUserId")->fetchAll();
  
    

    if (($user -> LoggedIn())){
        $SQLupdate = $odb -> prepare("UPDATE loginip SET `status` = :status WHERE userID = :id");
        $SQLupdate -> execute(array(':status' => '1', ':id' => $loggedUserId));

        $getLoggedinuserList = $odb -> query("SELECT * FROM `loginip` WHERE userID = $loggedUserId")->fetchAll();
        $chatThread = $odb -> query("select distinct least(sender_userid, reciever_userid) as partner1 , greatest(reciever_userid, sender_userid) as partner2 from chat")->fetchAll();

    }

    if( isset( $_POST['action'] ) ){
        if($_POST['action'] == 'show_chat') {
            $to_user_id = $_POST['to_user_id'];

            $msgmute = $odb -> prepare("SELECT * FROM `chat_mute` WHERE `receiver_id` = :reciever_userid AND `sender_id` = :sender_userid");
            $msgmute -> execute(array(':sender_userid' => $to_user_id, 'reciever_userid' => $loggedUserId));
            $mutes = $msgmute -> fetchAll(); 
            
            if(empty($mutes)){
                $mute = 0;
            }else{
                $mute = 1;
            }
            // get user conversation
            $conversation = getUserChat($loggedUserId, $to_user_id);	
            // update chat user read status		
            $SQLupdate = $odb -> prepare("UPDATE chat SET `status` = :status WHERE sender_userid = :sender_userid AND reciever_userid = :reciever_userid AND status = '1'");
            $SQLupdate -> execute(array(':status' => '0', ':sender_userid' => $to_user_id, 'reciever_userid' => $loggedUserId));

            $data = array(
                "conversation" => $conversation,
                "mute" => $mute 			
            );
            
            echo json_encode($data, JSON_UNESCAPED_SLASHES);exit();
        }

        if($_POST['action'] == 'show_chat_group'){
            $user_id = $loggedUserId;
            $group = $_POST['group'];
            $conversation = getGroupChat($group,$user_id);  
            $data = array(
                "conversation" => $conversation         
            );
            echo json_encode($data, JSON_UNESCAPED_SLASHES);exit();   
            die;
        }

        if($_POST['action'] == 'show_chat_thread') {
            $partner1 = $_POST['partner1'];
            $partner2 = $_POST['partner2'];
            // get user conversation
            $conversation = getThreadChat($partner1, $partner2);  
            // update chat user read status     

            $data = array(
                "conversation" => $conversation         
            );
            
            echo json_encode($data, JSON_UNESCAPED_SLASHES);exit();
        }


        if($_POST['action'] == 'delete_chat_temp'){

            $to_chat_id = $_POST['to_chat_id'];
            $delete_status = 1;
            $reciever_userid = $_POST['partner2'];
            $user_id         = $_POST['partner1'];
            $SQL = $odb -> prepare("UPDATE `chat` SET `delete_status` = :delete_status WHERE chatid = :to_chat_id");
            $SQL -> execute(array(':delete_status' => $delete_status, ':to_chat_id' => $to_chat_id));
            $conversation = getThreadChat($user_id, $reciever_userid);
            $data = array(
                "conversation" => $conversation         
            );
            echo json_encode($data, JSON_UNESCAPED_SLASHES);exit(); 
        }

        if($_POST['action'] == 'restore_chat'){

            $to_chat_id = $_POST['to_chat_id'];
            $delete_status = 0;
            $reciever_userid = $_POST['partner2'];
            $user_id         = $_POST['partner1'];
            $SQL = $odb -> prepare("UPDATE `chat` SET `delete_status` = :delete_status WHERE chatid = :to_chat_id");
            $SQL -> execute(array(':delete_status' => $delete_status, ':to_chat_id' => $to_chat_id));
            $conversation = getThreadChat($user_id, $reciever_userid);
            $data = array(
                "conversation" => $conversation         
            );

            echo json_encode($data, JSON_UNESCAPED_SLASHES);exit(); 
        }

        if($_POST['action'] == 'insert_chat') {
            $reciever_userid = $_POST['to_user_id'];
            $user_id         = $loggedUserId;
            $chat_message    = $_POST['chat_message'];

            $insertChats = $odb -> prepare("INSERT INTO `chat` (`sender_userid`, `reciever_userid`, `message`,`timestamp`, `status`) VALUES(:sender_userid, :reciever_userid, :chat_message, UNIX_TIMESTAMP(), 1)");
	        $insertChats -> execute(array(':sender_userid' => $user_id, ':reciever_userid' => $reciever_userid, ':chat_message' => $chat_message));
            $conversation = getUserChat($user_id, $reciever_userid);
            $data = array(
                "conversation" => $conversation			
            );
            echo json_encode($data, JSON_UNESCAPED_SLASHES);exit();	
        }

        if($_POST['action'] == 'insert_group_chat') {
            $group_id = $_POST['group_id'];
            $user_id         = $loggedUserId;
            $chat_message    = $_POST['chat_message'];

            $insertChats = $odb -> prepare("INSERT INTO `chat_group_msg` (`group_id`, `sender_id`, `msg`) VALUES(:group_id, :sender_id, :chat_message)");
            $insertChats -> execute(array(':group_id' => $group_id, ':sender_id' => $user_id, ':chat_message' => $chat_message));
            $conversation = getGroupChat($group_id, $user_id);
            $data = array(
                "conversation" => $conversation         
            );
            echo json_encode($data, JSON_UNESCAPED_SLASHES);exit(); 
        }

        if($_POST['action'] == 'edit_chat') {
            $reciever_userid = $_POST['to_user_id'];
            $user_id         = $loggedUserId;
            $edit_id         = $_POST['edit_id'];
            $chat_message    = $_POST['chat_message'];

            $insertChats = $odb -> prepare("UPDATE `chat` SET `message` = :chat_message WHERE chatid = :edit_id");
            $insertChats -> execute(array(':edit_id' => $edit_id, ':chat_message' => $chat_message));
            $conversation = getUserChat($user_id, $reciever_userid);
            $data = array(
                "conversation" => $conversation         
            );
            echo json_encode($data, JSON_UNESCAPED_SLASHES);exit(); 
        }

        if($_POST['action'] == 'edit_group_chat') {
            $group_id = $_POST['group_id'];
            $user_id         = $loggedUserId;
            $edit_id         = $_POST['edit_id'];
            $chat_message    = $_POST['chat_message'];

            $insertChats = $odb -> prepare("UPDATE `chat_group_msg` SET `msg` = :chat_message WHERE msg_id = :edit_id");
            $insertChats -> execute(array(':edit_id' => $edit_id, ':chat_message' => $chat_message));
            $conversation = getGroupChat($group_id,$user_id);
            $data = array(
                "conversation" => $conversation         
            );
            echo json_encode($data, JSON_UNESCAPED_SLASHES);exit(); 
        }

        if($_POST['action'] == 'update_user_chat'){

            $update_chat = getUserChat($loggedUserId, $_POST['to_user_id']);
            $data = array(
                "conversation" => $update_chat			
            );
            echo json_encode($data, JSON_UNESCAPED_SLASHES);exit();
        }
        
        if($_POST['action'] == 'update_unread_message'){
            $senderUserid = $_POST['to_user_id'];
            $count = getUnreadMessageCount($senderUserid, $loggedUserId);
            $data = array(
                "count" => $count			
            );
            echo json_encode($data);exit();
        }
        if($_POST['action'] == 'delete_chat'){
            $to_chat_id = $_POST['to_chat_id'];
            $SQL = $odb -> prepare("DELETE FROM `chat` WHERE `chatid` = :to_chat_id");
            $SQL -> execute(array(':to_chat_id' => $to_chat_id));
            $conversation = getUserChat($user_id, $reciever_userid);
            $data = array(
                "conversation" => $conversation			
            );
            echo json_encode($data, JSON_UNESCAPED_SLASHES);exit();	
        }

        if($_POST['action'] == 'available_status'){
            $user_id = $loggedUserId;
            $available_status = $_POST['available_status'];
            $insert = $odb -> prepare("UPDATE `users` SET `available_status` = :available_status WHERE ID = :user_id");
            $insert -> execute(array(':user_id' => $user_id, ':available_status' => $available_status));
            die;
        }

        if($_POST['action'] == 'mute_chat'){
            $user_id = $loggedUserId;
            $sender_id = $_POST['id'];
            $title = $_POST['title'];
            if($title=="mute"){
                $insertmute = $odb -> prepare("INSERT INTO `chat_mute` (`receiver_id`, `sender_id`) VALUES(:user_id, :sender_id)");
                $insertmute -> execute(array(':user_id' => $user_id, ':sender_id' => $sender_id));
            }else{
                $SQL = $odb -> prepare("DELETE FROM `chat_mute` WHERE `receiver_id` = :user_id AND `sender_id`=:sender_id");
                $SQL -> execute(array(':user_id' => $user_id, ':sender_id' => $sender_id));
            }
            die;
        }

    }

    function getUnreadMessageCount($senderUserid, $loggedUserId) {
        global $odb;
        $get_unread_count = $odb->prepare("SELECT * FROM `chat` WHERE `sender_userid` = :sender_userid AND `reciever_userid` = :reciever_userid AND `status` = 1");
        $get_unread_count -> execute(array( ':sender_userid' => $senderUserid, ':reciever_userid' => $loggedUserId));
        $numRows = $get_unread_count->rowCount();
        $output = '';
        if($numRows > 0){
            $output = $numRows;
        }
        return $output;
    }

    function getUserChat($loggedUserId, $to_user_id) {
        global $odb;
        $chatQuery = "SELECT * FROM `chat` WHERE (sender_userid = $loggedUserId AND reciever_userid = '".$to_user_id."') OR (sender_userid = '".$to_user_id."' AND reciever_userid = '".$loggedUserId."') ORDER BY timestamp ASC";
        $getuserChat = $odb->query($chatQuery)->fetchAll(PDO::FETCH_ASSOC);
        if ( !empty( $getuserChat ) ){
            $conversation = '<div class="bubble_three_dot">';
            foreach($getuserChat as $chat){
                $user_name = '';
                if($chat['delete_status']==0){
                    if($chat['sender_userid'] == $loggedUserId) {
                        $conversation .= '<div class="bubble me"><span class="edit" id="'.$chat['chatid'].'"  message="'.$chat['message'].'"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                        <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                        <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                      </svg></span>';
                      //$conversation .= '<span class="delete" id="'.$chat['chatid'].'"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16"><path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/><path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/></svg></span>';
                    } else {
                        $conversation .= '<div class="bubble you">';
                    }	
                	
                    $conversation .= '<p>'.$chat['message'].'</p>';			
                    $conversation .= '</div>';
                }   
            }		
            $conversation .= '</div>';
            return $conversation;
        }
    }

    function getGroupChat($group,$user_id){
        global $odb,$user;
        $groupmsg = $odb -> prepare("SELECT * FROM `chat_group_msg` WHERE `group_id` = :group_id");
        $groupmsg -> execute(array(':group_id' => $group));
        $groupchat = $groupmsg -> fetchAll(PDO::FETCH_ASSOC); 
        if ( !empty( $groupchat ) ){
            $conversation = '<div class="bubble_three_dot">';
            foreach($groupchat as $chat){
                $user_name = '';
                $userInfo = $user->userInfo($odb,$chat['sender_id']);
                $user_name = $userInfo['username'];
                if($chat['sender_id'] == $user_id) {
                    $conversation .= '<div class="bubble me"><span class="edit groupmsg" id="'.$chat['msg_id'].'"  message="'.$chat['msg'].'"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                  </svg></span>';
                  //$conversation .= '<span class="delete" id="'.$chat['chatid'].'"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16"><path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/><path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/></svg></span>';
                } else {
                    $conversation .= '<div class="bubble you"><p class="username">'.$user_name.'</p>';
                }   
                
                $conversation .= '<p>'.$chat['msg'].'</p>';         
                $conversation .= '</div>';
            }       
            $conversation .= '</div>';
            return $conversation;
        }
    }

    function getThreadChat($loggedUserId, $to_user_id) {
        global $odb;
        $chatQuery = "SELECT * FROM `chat` WHERE (sender_userid = $loggedUserId AND reciever_userid = '".$to_user_id."') OR (sender_userid = '".$to_user_id."' AND reciever_userid = '".$loggedUserId."') ORDER BY timestamp ASC";
        $getuserChat = $odb->query($chatQuery)->fetchAll(PDO::FETCH_ASSOC);
        if ( !empty( $getuserChat ) ){
            $conversation = '<div class="bubble_three_dot">';
            foreach($getuserChat as $chat){
                $user_name = '';
                $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                      <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                      <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                    </svg>';
                $cl = 'delete_temp';
                if($chat['delete_status']==1){
                    $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24"><path d="M13.5 2c-5.621 0-10.211 4.443-10.475 10h-3.025l5 6.625 5-6.625h-2.975c.257-3.351 3.06-6 6.475-6 3.584 0 6.5 2.916 6.5 6.5s-2.916 6.5-6.5 6.5c-1.863 0-3.542-.793-4.728-2.053l-2.427 3.216c1.877 1.754 4.389 2.837 7.155 2.837 5.79 0 10.5-4.71 10.5-10.5s-4.71-10.5-10.5-10.5z"/></svg>';
                    $cl = 'restore';
                }
                if($chat['sender_userid'] == $to_user_id) {
                    $conversation .= '<div class="bubble me"><span class="'.$cl.'" id="'.$chat['chatid'].'">'.$svg.'</span>';
                } else {
                    $conversation .= '<div class="bubble you"><span class="'.$cl.'" id="'.$chat['chatid'].'">'.$svg.'</span>';
                }           
                $conversation .= '<p>'.$chat['message'].'</p>';         
                $conversation .= '</div>';
            }       
            $conversation .= '</div>';
            return $conversation;
        }
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>rootCapture - Chat Room </title>
    <link rel="icon" type="image/x-icon" href="../src/assets/img/favicon.ico"/>
    <link href="../layouts/vertical-dark-menu/css/light/loader.css" rel="stylesheet" type="text/css" />
    <link href="../layouts/vertical-dark-menu/css/dark/loader.css" rel="stylesheet" type="text/css" />
    <script src="../layouts/vertical-dark-menu/loader.js"></script>
	<link href="../css/alter.css" rel="stylesheet" type="text/css" />
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
    <link href="../src/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="../layouts/vertical-dark-menu/css/light/plugins.css" rel="stylesheet" type="text/css" />
    <link href="../layouts/vertical-dark-menu/css/dark/plugins.css" rel="stylesheet" type="text/css" />
    <link href="../css/chat.css" rel="stylesheet" type="text/css" />

    <!-- END GLOBAL MANDATORY STYLES -->

    <!-- BEGIN PAGE LEVEL STYLES -->
    <link rel="stylesheet" type="text/css" href="../src/plugins/src/table/datatable/datatables.css">
     <link rel="stylesheet" type="text/css" href="../src/plugins/src/vanillaSelectBox/vanillaSelectBox.css">
    <link rel="stylesheet" type="text/css" href="../src/plugins/css/dark/vanillaSelectBox/custom-vanillaSelectBox.css">

    <link href="../src/assets/css/light/apps/chat.css" rel="stylesheet" type="text/css" />
    <link href="../src/assets/css/dark/apps/chat.css" rel="stylesheet" type="text/css" />
    
    <link rel="stylesheet" type="text/css" href="../src/plugins/css/light/table/datatable/dt-global_style.css">
    <link rel="stylesheet" type="text/css" href="../src/plugins/css/dark/table/datatable/dt-global_style.css">
    <!-- END PAGE LEVEL STYLES -->

</head>
<body class="layout-boxed">
    <!-- BEGIN LOADER -->
    <div id="load_screen"> <div class="loader"> <div class="loader-content">
        <div class="spinner-grow align-self-center"></div>
    </div></div></div>
    <!--  END LOADER -->

    

    <!--  BEGIN MAIN CONTAINER  -->
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
                    <div class="chat_restrict_msg layout-top-spacing <?php echo $displaymsgBlock?>">
                        <div class="row">
                            <div class="col-xl-12 col-lg-12 col-md-12">
                                <?php echo '<div class="success" id="message"><p>'.$bannedSucMsg.'</p></div>';?>
                            </div>
                        </div>
                    </div>
                    <div class="chat-section layout-top-spacing <?php echo $hidechatBlock?> ">
                        <div class="row mb-1">
                            <div class="col-xl-4 col-lg-12 col-md-12">
                                <div class="search">
                                    <input type="text" class="form-control" placeholder="Search User" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            
                            <div class="col-xl-12 col-lg-12 col-md-12">
                                <nav>
                                    <div class="nav nav-tabs mb-3" id="nav-tab" role="tablist">
                                        <button class="nav-link active" id="nav-personal-tab" data-bs-toggle="tab" data-bs-target="#nav-personal" type="button" role="tab" aria-controls="nav-personal" aria-selected="true">Personal</button>
                                        <button class="nav-link" id="nav-group-tab" data-bs-toggle="tab" data-bs-target="#nav-group" type="button" role="tab" aria-controls="nav-group" aria-selected="false">Group</button>
                                        <?php if($userInfo['rank']==1 || $userInfo['rank']==2){ ?>
                                            <button class="nav-link" id="nav-thread-tab" data-bs-toggle="tab" data-bs-target="#nav-thread" type="button" role="tab" aria-controls="nav-thread" aria-selected="false">Thread</button>
                                        <?php } ?>
                                    </div>
                                </nav>
                                <div class="tab-content" id="nav-tabContent">
                                    <div class="tab-pane fade active show" id="nav-personal" role="tabpanel" aria-labelledby="nav-personal-tab">
                                        <div class="chat-system">
                                            <div class="hamburger"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-menu mail-menu d-lg-none"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg></div>
                                            <div class="user-list-box">
                                                <div class="people">
                                                    <?php foreach ($getuserList as $userList) { 
                                                        if($userList['restrict_chat']!=1){
                                                            $getuserLoginip  = $odb -> query("SELECT * FROM `loginip` WHERE userID = '".$userList['ID']."'")->fetchAll();
                                                            $loggedStatus = 'loggedout';
                                                            $logintime = time()-$userList['last_login'];
                                                            if($userList['is_logout']!='yes' && $userList['available_status']!=1 && $logintime<3600){
                                                                $loggedStatus = 'loggedin';
                                                            }
                                                        ?>
                                                            <div class="person <?php echo 'personchat'.$userList['ID'];?>" data-chat="<?php echo 'person'.$userList['ID'];?>" data-touserid="<?php echo $userList['ID'];?>" data-tousername="<?php echo $userList['username'];?>">
                                                                <div class="user-info">
                                                                    <div class="f-body">
                                                                        <div class="meta-info">
                                                                            <span class="user-name" data-name="<?php echo $userList['ID'];?>"><?php echo $userList['username'];?></span>
                                                                            <span class="<?php echo $loggedStatus;?>">●</span>
                                                                            <span id="unread_<?php echo $userList['ID'];?>" class="unread">
                                                                                <?php
                                                                                if( !empty(getUnreadMessageCount($userList['ID'], $loggedUserId))){
                                                                                    echo '<span class="unread_count">'. getUnreadMessageCount($userList['ID'], $loggedUserId).'</span>';
                                                                                }?>
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                    <?php } } ?>                                       
                                                </div>
                                            </div>
                                            <div class="chat-box">
            
                                                <div class="chat-box-inner">
                                                    <div class="chat-meta-user" id="userSection">
                                                        <div class="current-chat-user-name">
                                                            <span class="personalname"></span>
                                                            <?php 
                                                                $mute = "display:none;";
                                                                $unmute = "display:none;";
                                                                $mmute = $user->getUserMute($odb,$loggedUserId, $_GET['user_id']);
                                                                if(!empty($mmute)){
                                                                    $unmute = "";
                                                                }else{
                                                                    $mute = "";
                                                                }
                                                            ?>
                                                            <span class="mute_notification mute_icon" title="mute" style="<?php echo $mute; ?>">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bell"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
                                                            </span>
                                                            <span class="mute_notification unmute_icon" title="unmute" style="<?php echo $unmute; ?>">

                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 640 512" fill="#bfc9" stroke="currentColor"  stroke-width="2"><path d="M38.8 5.1C28.4-3.1 13.3-1.2 5.1 9.2S-1.2 34.7 9.2 42.9l592 464c10.4 8.2 25.5 6.3 33.7-4.1s6.3-25.5-4.1-33.7l-87.5-68.6c.5-1.7 .7-3.5 .7-5.4c0-27.6-11-54.1-30.5-73.7L512 320c-20.5-20.5-32-48.3-32-77.3V208c0-77.4-55-142-128-156.8V32c0-17.7-14.3-32-32-32s-32 14.3-32 32V51.2c-42.6 8.6-79 34.2-102 69.3L38.8 5.1zM160 242.7c0 29-11.5 56.8-32 77.3l-1.5 1.5C107 341 96 367.5 96 395.2c0 11.5 9.3 20.8 20.8 20.8H406.2L160 222.1v20.7zM384 448H320 256c0 17 6.7 33.3 18.7 45.3s28.3 18.7 45.3 18.7s33.3-6.7 45.3-18.7s18.7-28.3 18.7-45.3z"/></svg>
                                                            </span>
                                                        </div>
                                                        
                                                    </div>
                                                    <div class="chat-conversation-box">
                                                        <div id="chat-conversation-box-scroll" class="chat-conversation-box-scroll">
                                                            <div class="active-chat" id="person">
                                                                <div class="conversation-start">
                                                                    <!-- <span>Today, 6:48 AM</span> -->
                                                                </div>
                                                                <div class="bubble-chats" id="conversation">
                                                                    
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="chat-footer" style="margin-top:20px;">
                                                        <div class="chat-input">
                                                            <form class="chat-form" action="javascript:void(0);">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-message-square"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                                                                <input type="text" class="mail-write-box form-control" placeholder="Message" id="chatMessage<?php echo $_GET['user_id']; ?>" />
                                                                
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="nav-group" role="tabpanel" aria-labelledby="nav-group-tab">
                                        <div class="chat-system">
                                            <div class="hamburger"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-menu mail-menu d-lg-none"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg></div>
                                            <div class="user-list-box">
                                                <div class="people">
                                                    <?php foreach ($getGroups as $group) { 
                                                        ?>
                                                            <div class="person group <?php echo 'group'.$group['group_id'];?>" data-group="<?php echo $group['group_id'];?>" data-chat="<?php echo 'group'.$group['group_id'];?>" data-touserid="<?php echo $group['group_id'];?>" data-tousername="<?php echo $group['group_name'];?>">
                                                                <div class="user-info">
                                                                    <div class="f-body">
                                                                        <div class="meta-info">
                                                                            <span class="user-name" data-name="<?php echo $userList['group_id'];?>"><?php echo $group['group_name'];?></span>
                                                                            <span class="loggedin">●</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                    <?php } ?>                                       
                                                </div>
                                            </div>
                                            <div class="chat-box">
            
                                                <div class="chat-box-inner">
                                                    <div class="chat-meta-user" id="userSectionGroup">
                                                        <div class="current-chat-user-name">
                                                            <span class="groupname"></span>
                                                        </div>
                                                        
                                                    </div>
                                                    <div class="chat-conversation-box">
                                                        <div id="chat-conversation-box-scroll" class="chat-conversation-box-scroll">
                                                            <div class="active-chat" id="person">
                                                                <div class="conversation-start">
                                                                    <!-- <span>Today, 6:48 AM</span> -->
                                                                </div>
                                                                <div class="bubble-chats" id="conversation_group">
                                                                    
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="chat-footer" style="margin-top:20px">
                                                        <div class="chat-input">
                                                            <form class="chat-form" action="javascript:void(0);">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-message-square"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                                                                <input type="text" class="mail-write-box form-control ingroup" placeholder="Message here" />
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php if($userInfo['rank']==1 || $userInfo['rank']==2){ ?>
                                        <div class="tab-pane fade" id="nav-thread" role="tabpanel" aria-labelledby="nav-thread-tab">
                                            <div class="chat-system">
                                                <div class="hamburger"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-menu mail-menu d-lg-none"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg></div>
                                                <div class="user-list-box">
                                                    <div class="people">
                                                        <?php 
                                                            foreach ($chatThread as $userList) { 
                                                                $partner1 = $user->userInfo($odb,$userList['partner1']);
                                                                $partner2 = $user->userInfo($odb,$userList['partner2']);
                                                                if(!empty($partner1) && !empty($partner2)){ 
                                                            ?>
                                                                    <div class="person thread" data-partner1="<?php echo $userList['partner1'];?>" data-partner2="<?php echo $userList['partner2'];?>" data-tousername="<?php echo $partner1['username'];?> To <?php echo $partner2['username'];?>">
                                                                        <div class="user-info">
                                                                            <div class="f-body">
                                                                                <div class="meta-info">
                                                                                    <span class="user-name" data-name="<?php echo $partner1['username'];?> To <?php echo $partner2['username'];?>"> <?php echo $partner1['username'];?> To <?php echo $partner2['username'];?>
                                                                                    </span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                        <?php } } ?>                                       
                                                    </div>
                                                </div>
                                                <div class="chat-box">
                
                                    
                                                    <div class="chat-box-inner">
                                                        <div class="chat-meta-user" id="userSection">
                                                            <div class="current-chat-user-name">
                                                                <span class="threadname"></span>
                                                            </div>
                                                            
                                                        </div>
                                                        <div class="chat-conversation-box">
                                                            <div id="chat-conversation-box-scroll" class="chat-conversation-box-scroll">
                                                                <div class="active-chat" id="person">
                                                                    <div class="conversation-start">
                                                                        <!-- <span>Today, 6:48 AM</span> -->
                                                                    </div>
                                                                    <div class="bubble-chats" id="conversation_thread">
                                                                        
                                                                    </div>
                                                                    <input type="hidden" name="partner1" class="ppartner1" value="<?php echo $_GET['partner1']; ?>">
                                                                    <input type="hidden" name="partner2" class="ppartner2" value="<?php echo $_GET['partner2']; ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                
            </div>

            <!--  BEGIN FOOTER  -->
            <div class="footer-wrapper  mt-0">
                <div class="footer-section f-section-1">
                    <p class="">Copyright © <span class="dynamic-year">2022</span> <a target="_blank" href="https://designreset.com/cork-admin/">DesignReset</a>, All rights reserved.</p>
                </div>
               
            </div>
            <!--  END FOOTER  -->
        </div>
        <!--  END CONTENT AREA  -->
    </div>
    <!-- END MAIN CONTAINER -->
    
    <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
    <script src="../src/plugins/src/global/vendors.min.js"></script>
    <script src="../src/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../src/plugins/src/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="../src/plugins/src/mousetrap/mousetrap.min.js"></script>
    <script src="../layouts/vertical-dark-menu/app.js"></script>
    <script src="../src/plugins/src/vanillaSelectBox/vanillaSelectBox.js"></script>
    <script src="../src/plugins/src/vanillaSelectBox/custom-vanillaSelectBox.js"></script>
    <script src="../src/assets/js/custom.js"></script>
    <script src="../assets/js/chat.js"></script>
     <script src="../src/assets/js/apps/chat.js"></script>
    <!-- END GLOBAL MANDATORY SCRIPTS -->
    <?php if(isset($_GET['user_id']) && $_GET['user_id']!=''){ ?>
        <script>
            var userid = "<?php echo $_GET['user_id']; ?>";
            $(".personchat"+userid).trigger("click");
        </script>
    <?php } ?>
</body>
</html>