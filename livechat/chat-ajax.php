<?php
    ob_start();
    require_once '../includes/db.php';
    require_once '../includes/init.php';

    /*ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);*/

    @session_start();
    $loggedUserId    = $_SESSION['ID'];
    $userInfo = $user->userInfo($odb,$loggedUserId);
    $user->updateLastSeen($odb,$loggedUserId);
	if(isset($_POST) && $_POST['action']=='create_group'){
        
		if(isset($_POST['group_member']) && !empty($_POST['group_member'])){
            // try
            // {
                // $odb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                // $odb->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
                $channel_name = $_POST['group_name'].'-'.date('YmdHis');
                if(isset($_POST['group_id']) && $_POST['group_id']>0){
                    
                    $updategroup = $odb -> prepare("UPDATE `chat_group` SET `group_name` = :group_name, `group_members` = :group_members WHERE group_id = :group_id");
                    $updategroup -> execute(array(':group_name' => $_POST['group_name'], ':group_members' => implode(",", $_POST['group_member']), ':group_id' => $_POST['group_id']));
                }else{
                    
                    $insertgroup = $odb -> prepare("INSERT INTO `chat_group` (`group_name`, `group_members`,`channel_name`, `created_by`) VALUES(:group_name, :group_members,:channel_name, :created_by)");
                    $insertgroup -> execute(array(':group_name' => $_POST['group_name'], ':group_members' => implode(",", $_POST['group_member']),':channel_name' => $channel_name, ':created_by' => $loggedUserId));
                }
            // }catch(\Exception $e){
            //     echo $e->getMessage(); exit;
            // }
		}
		$usergrouplist = $user->getUserGroups($odb,$loggedUserId);
		$html = '';
		if(!empty($usergrouplist)){
            foreach($usergrouplist as $key=>$value){
                $html .= '<li class="group_list" group="'.$value['group_name'].'">';
                $html .= '<a href="javascript:void(0)">';
                $html .= '<div class="d-flex align-items-center">';
                $html .= '<div class="chat-user-img me-3 ms-0">';
                $html .= '<div class="avatar-xs">';
                $html .= '<span class="avatar-title rounded-circle bg-soft-primary text-primary">';
                $html .= strtoupper($value['group_name'][0]);;
                $html .= '</span>';
                $html .= '</div>';
                $html .= '</div>';
                $html .= '<div class="flex-grow-1 overflow-hidden">';
                $html .= '<h5 class="text-truncate font-size-14 mb-0">#'.strtoupper($value['group_name']).'</h5>';
                $html .= '</div>';
                $html .= '</div>';
                $html .= '</a>';
                $html .= '</li>';
            }
            echo $html; 
        } 
	}elseif(isset($_POST) && $_POST['action']=='add_contacts'){
		if(isset($_POST['add_contacts']) && !empty($_POST['add_contacts'])){
			$contacts = $_POST['add_contacts'];
			foreach($contacts as $key=>$value){
				$insertgroup = $odb -> prepare("INSERT INTO `chat_contacts` (`sender_id`, `receiver_id`) VALUES(:sender_id, :receiver_id)");
		    	$insertgroup -> execute(array(':sender_id' => $loggedUserId, ':receiver_id' =>$value));
			}
			$contactlist = $user->getContactList($odb,$loggedUserId);
	        $userorderlist = array();
	        $data = '';
	        if(!empty($contactlist)){
	            foreach($contactlist as $key=>$value){
	                $contactInfo = $user->userInfo($odb,$value['receiver_id']);
	                $existContact[] = $value['receiver_id'];
	                $userorderlist[$contactInfo['username'][0]][$contactInfo['ID']] = $contactInfo['username'];
	            }
	            
	            ob_start();

                require_once('chat-contacts.php');

                $data = ob_get_contents();

                ob_end_clean();

                echo $data;

	        }
		}
	}elseif(isset($_POST) && $_POST['action']=='remove_contact'){

		$receiver_id = $_POST['action_id'];
		$SQL = $odb -> prepare("DELETE FROM `chat_contacts` WHERE `receiver_id` = :receiver_id AND `sender_id` = :sender_id");
        $SQL -> execute(array(':receiver_id' => $receiver_id,':sender_id' => $loggedUserId));
        $contactlist = $user->getContactList($odb,$loggedUserId);

        $userorderlist = array();
        $data = '';
        if(!empty($contactlist)){
            foreach($contactlist as $key=>$value){
                $contactInfo = $user->userInfo($odb,$value['receiver_id']);
                $existContact[] = $value['receiver_id'];
                $userorderlist[$contactInfo['username'][0]][$contactInfo['ID']] = $contactInfo['username'];
            }
            
            ob_start();

            require_once('chat-contacts.php');

            $data = ob_get_contents();

            ob_end_clean();

            echo $data;
        }
    
	}elseif(isset($_POST) && $_POST['action']=='block_contact'){

		$receiver_id = $_POST['action_id'];

        $SQL = $odb -> prepare("UPDATE `chat_contacts` SET `status` = :status WHERE receiver_id = :receiver_id AND sender_id = :sender_id");
        $SQL -> execute(array(':status' => 1, ':sender_id' => $loggedUserId, ':receiver_id' => $receiver_id));

        $contactlist = $user->getContactList($odb,$loggedUserId);

        $userorderlist = array();
        $data = '';
        if(!empty($contactlist)){
            foreach($contactlist as $key=>$value){
                $contactInfo = $user->userInfo($odb,$value['receiver_id']);
                $existContact[] = $value['receiver_id'];
                $userorderlist[$contactInfo['username'][0]][$contactInfo['ID']] = $contactInfo['username'];
            }
            
            ob_start();

            require_once('chat-contacts.php');

            $data = ob_get_contents();

            ob_end_clean();

            echo $data;
        }
        
	}elseif(isset($_POST) && $_POST['action']=='contact_chat'){

		ob_start();

        $channelName = $user->createChannel($odb,$loggedUserId,$_POST['contact_id']);
		require_once('chat-data.php');
		$data = ob_get_contents();
	   	ob_end_clean(); 
	   	echo $channelName.'|'.$data;

	}elseif(isset($_POST) && $_POST['action']=='group_chat'){

        ob_start();

        require_once('group-chat-data.php');

        $data = ob_get_contents();

        ob_end_clean();

        // echo $data;
        echo $group['channel_name'].'|'.$data;

    }elseif(isset($_POST) && $_POST['action']=='share_contacts'){

		$message = 'I am sharing with you the user that is named '.$_POST['shared_user'];
		$share_contacts = $_POST['share_contacts'];
		foreach ($share_contacts as $key => $value) {
			$insertChats = $odb -> prepare("INSERT INTO `chat` (`sender_userid`, `reciever_userid`, `message`,`timestamp`, `status`) VALUES(:sender_userid, :reciever_userid, :chat_message, UNIX_TIMESTAMP(), 1)");
	    	$insertChats -> execute(array(':sender_userid' => $loggedUserId, ':reciever_userid' => $value, ':chat_message' => $message));
		}

	}elseif(isset($_POST) && $_POST['action']=='archive_chat'){
        /*$user_id = $_POST['contact_id'];
        $SQL = $odb -> prepare("UPDATE `chat` SET `archive` = :status WHERE sender_userid = :sender_userid AND reciever_userid = :reciever_userid");
        $SQL -> execute(array(':status' => 1, ':sender_userid' => $loggedUserId, ':reciever_userid' => $user_id));

        ob_start();

        require_once('livechat-data.php');

        $data = ob_get_contents();

        ob_end_clean();

        echo $data;*/
    }elseif(isset($_POST) && $_POST['action']=='mute_chat'){

        $user_id = $loggedUserId;
        $sender_id = $_POST['contact_id'];
        $status = $_POST['status'];
        if($status=="mute"){
            $insertmute = $odb -> prepare("INSERT INTO `chat_mute` (`receiver_id`, `sender_id`) VALUES(:user_id, :sender_id)");
            $insertmute -> execute(array(':user_id' => $user_id, ':sender_id' => $sender_id));
        }else{
            $SQL = $odb -> prepare("DELETE FROM `chat_mute` WHERE `receiver_id` = :user_id AND `sender_id`=:sender_id");
            $SQL -> execute(array(':user_id' => $user_id, ':sender_id' => $sender_id));
        }

        ob_start();

        require_once('chat-data.php');

        $data = ob_get_contents();

        ob_end_clean();

        echo $data;
        die;
    }elseif(isset($_POST) && $_POST['action']=='delete_chat'){

        $user_id = $_POST['contact_id'];
        $insert = $odb -> prepare("INSERT INTO `chat_delete` (`login_id`, `receiver_id`, `delete_on`) VALUES(:login_id, :receiver_id, :delete_on)");
        $insert -> execute(array(':login_id' => $loggedUserId, ':receiver_id' => $user_id, ':delete_on' => time()));

        ob_start();

        require_once('chat-data.php');

        $data = ob_get_contents();

        ob_end_clean();

        echo $data;
    }elseif(isset($_POST) && $_POST['action']=='forward_message'){

        $message = $_POST['message'];
        $share_contacts = $_POST['contacts'];
        foreach ($share_contacts as $key => $value) {
            $insertChats = $odb -> prepare("INSERT INTO `chat` (`sender_userid`, `reciever_userid`, `message`,`timestamp`, `status`, `forward`) VALUES(:sender_userid, :reciever_userid, :chat_message, UNIX_TIMESTAMP(), 1,1)");
            $insertChats -> execute(array(':sender_userid' => $loggedUserId, ':reciever_userid' => $value, ':chat_message' => $message));
        }
    }elseif(isset($_POST) && $_POST['action']=='delete_message'){

        $chatid = $_POST['chatid'];
        $contact_id = $_POST['contact_id'];
        $SQL = $odb -> prepare("DELETE FROM `chat` WHERE `chatid` = :chatid");
        $SQL -> execute(array(':chatid' => $chatid));
        
        ob_start();

        require_once('chat-data.php');

        $data = ob_get_contents();

        ob_end_clean();

        echo $data;
    }elseif(isset($_POST) && $_POST['action']=='lastseen_switch'){

        $user_id = $loggedUserId;
        $status = $_POST['status'];
        $SQL = $odb -> prepare("SELECT * FROM `users_meta` WHERE `user_id` = :user_id AND `meta_key` = :lastseen");
        $SQL -> execute(array(':user_id' => $user_id,':lastseen' => 'lastseen'));
        $data = $SQL -> fetch(); 
        
        if(empty($data)){
            $insert = $odb -> prepare("INSERT INTO `users_meta` (`user_id`, `meta_key`, `meta_value`) VALUES(:user_id, :meta_key, :meta_value)");
            $insert -> execute(array(':user_id' => $user_id, ':meta_key' => 'lastseen', ':meta_value' => $status));
        }else{
            $update = $odb -> prepare("UPDATE `users_meta` SET `meta_value`=:meta_value WHERE user_id = :user_id AND meta_key = :meta_key");
            $update -> execute(array(':meta_value' => $status,':user_id' => $user_id, ':meta_key' => 'lastseen'));

        }
        
    }elseif(isset($_POST) && $_POST['action']=='readreceipt_switch'){

        $user_id = $loggedUserId;
        $status = $_POST['status'];
        $SQL = $odb -> prepare("SELECT * FROM `users_meta` WHERE `user_id` = :user_id AND `meta_key` = :readreceipt");
        $SQL -> execute(array(':user_id' => $user_id,':readreceipt' => 'readreceipt'));
        $data = $SQL -> fetch(); 
        
        if(empty($data)){
            $insert = $odb -> prepare("INSERT INTO `users_meta` (`user_id`, `meta_key`, `meta_value`) VALUES(:user_id, :meta_key, :meta_value)");
            $insert -> execute(array(':user_id' => $user_id, ':meta_key' => 'readreceipt', ':meta_value' => $status));
        }else{
            $update = $odb -> prepare("UPDATE `users_meta` SET `meta_value`=:meta_value WHERE user_id = :user_id AND meta_key = :meta_key");
            $update -> execute(array(':meta_value' => $status,':user_id' => $user_id, ':meta_key' => 'readreceipt'));

        }
        
    }elseif(isset($_POST) && $_POST['action']=='statusseen_switch'){
        
        $user_id = $loggedUserId;
        $seen = $_POST['seen'];
        $SQL = $odb -> prepare("SELECT * FROM `users_meta` WHERE `user_id` = :user_id AND `meta_key` = :statusseen");
        $SQL -> execute(array(':user_id' => $user_id,':statusseen' => 'statusseen'));
        $data = $SQL -> fetch(); 
        $contacts = '';
        if(isset($_POST['contacts']) && !empty($_POST['contacts'])){
            $contacts = implode(",", $_POST['contacts']);
        }
        if(empty($data)){
            $insert = $odb -> prepare("INSERT INTO `users_meta` (`user_id`, `meta_key`, `meta_value`) VALUES(:user_id, :meta_key, :meta_value)");
            $insert -> execute(array(':user_id' => $user_id, ':meta_key' => 'statusseen', ':meta_value' => $seen));

            $insert2 = $odb -> prepare("INSERT INTO `users_meta` (`user_id`, `meta_key`, `meta_value`) VALUES(:user_id, :meta_key, :meta_value)");
            $insert2 -> execute(array(':user_id' => $user_id, ':meta_key' => 'statusseen_contacts', ':meta_value' => $contacts));
        }else{
            $update = $odb -> prepare("UPDATE `users_meta` SET `meta_value`=:meta_value WHERE user_id = :user_id AND meta_key = :meta_key");
            $update -> execute(array(':meta_value' => $seen,':user_id' => $user_id, ':meta_key' => 'statusseen'));

            $update2 = $odb -> prepare("UPDATE `users_meta` SET `meta_value`=:meta_value WHERE user_id = :user_id AND meta_key = :meta_key");
            $update2 -> execute(array(':meta_value' => $contacts,':user_id' => $user_id, ':meta_key' => 'statusseen_contacts'));

        }
    }elseif(isset($_POST) && $_POST['action']=='useraval_status'){
        
        $user_id = $loggedUserId;
        $status = $_POST['status'];
        $SQL = $odb -> prepare("SELECT * FROM `users_meta` WHERE `user_id` = :user_id AND `meta_key` = :statusaval");
        $SQL -> execute(array(':user_id' => $user_id,':statusaval' => 'statusaval'));
        $data = $SQL -> fetch(); 
        
        if(empty($data)){
            $insert = $odb -> prepare("INSERT INTO `users_meta` (`user_id`, `meta_key`, `meta_value`) VALUES(:user_id, :meta_key, :meta_value)");
            $insert -> execute(array(':user_id' => $user_id, ':meta_key' => 'statusaval', ':meta_value' => $status));

            $insert2 = $odb -> prepare("INSERT INTO `users_meta` (`user_id`, `meta_key`, `meta_value`) VALUES(:user_id, :meta_key, :meta_value)");
            $insert2 -> execute(array(':user_id' => $user_id, ':meta_key' => 'statusaval_time', ':meta_value' => time()));
        }else{
            $update = $odb -> prepare("UPDATE `users_meta` SET `meta_value`=:meta_value WHERE user_id = :user_id AND meta_key = :meta_key");
            $update -> execute(array(':meta_value' => $status,':user_id' => $user_id, ':meta_key' => 'statusaval'));

            $update2 = $odb -> prepare("UPDATE `users_meta` SET `meta_value`=:meta_value WHERE user_id = :user_id AND meta_key = :meta_key");
            $update2 -> execute(array(':meta_value' => time(),':user_id' => $user_id, ':meta_key' => 'statusaval_time'));

        }
        
    }elseif(isset($_POST) && $_POST['action']=='allowadd_group'){
        
        $user_id = $loggedUserId;
        $allow = $_POST['allow'];
        $SQL = $odb -> prepare("SELECT * FROM `users_meta` WHERE `user_id` = :user_id AND `meta_key` = :allowaddgroup");
        $SQL -> execute(array(':user_id' => $user_id,':allowaddgroup' => 'allowaddgroup'));
        $data = $SQL -> fetch(); 

        $contacts = '';
        if(isset($_POST['contacts']) && !empty($_POST['contacts'])){
            $contacts = implode(",", $_POST['contacts']);
        }
        
        if(empty($data)){
            $insert = $odb -> prepare("INSERT INTO `users_meta` (`user_id`, `meta_key`, `meta_value`) VALUES(:user_id, :meta_key, :meta_value)");
            $insert -> execute(array(':user_id' => $user_id, ':meta_key' => 'allowaddgroup', ':meta_value' => $allow));

            $insert2 = $odb -> prepare("INSERT INTO `users_meta` (`user_id`, `meta_key`, `meta_value`) VALUES(:user_id, :meta_key, :meta_value)");
            $insert2 -> execute(array(':user_id' => $user_id, ':meta_key' => 'allowaddgroup_contacts', ':meta_value' => $contacts));
        }else{
            $update = $odb -> prepare("UPDATE `users_meta` SET `meta_value`=:meta_value WHERE user_id = :user_id AND meta_key = :meta_key");
            $update -> execute(array(':meta_value' => $allow,':user_id' => $user_id, ':meta_key' => 'allowaddgroup'));

            $update2 = $odb -> prepare("UPDATE `users_meta` SET `meta_value`=:meta_value WHERE user_id = :user_id AND meta_key = :meta_key");
            $update2 -> execute(array(':meta_value' => $contacts,':user_id' => $user_id, ':meta_key' => 'allowaddgroup_contacts'));

        }
    }elseif(isset($_POST) && $_POST['action']=='read_msg'){
        $user_id = $loggedUserId;
        $sid = $_POST['sid'];
        $user->readUnreadMessage($odb,$sid,$loggedUserId);
    }
    elseif(isset($_POST) && $_POST['action']=='send_message'){
        try
        {
            require __DIR__ . '/vendor/autoload.php';
            $odb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $odb->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            
            $message = $_POST['msg'];
            $reciever_userid = $_POST['ri'];
            $rec_name = $_POST['rec_name'];
            $chat_group_id = $_POST['chat_group_id'];
            
            $channel_name = $_POST['channel_name'];

            $options = array(
                'cluster' => 'ap2',
                'useTLS' => true
            );
            $pusher = new Pusher\Pusher(
            'afc846898302db567944',
            'ade7a55c67e46e56239b',
            '1625867',
            $options
            );
            
            $data['message'] = $message;        
            $data['rec_name'] = $rec_name;        
            $data['rec_id'] = $reciever_userid;        
            $data['loggedUserId'] = $loggedUserId;        
            $pusher->trigger($channel_name, ($chat_group_id == 0)?'send_message':'group_chat', $data);            
        
            if($chat_group_id == 0)
            {
                $insertChats = $odb -> prepare("INSERT INTO `chat` (`sender_userid`, `reciever_userid`, `message`,`timestamp`, `status`, `forward`) VALUES(:sender_userid, :reciever_userid, :chat_message, UNIX_TIMESTAMP(), 1,1)");
                $insertChats -> execute(array(':sender_userid' => $loggedUserId, ':reciever_userid' => $reciever_userid, ':chat_message' => $message));
            }
            else
            {
                $insertChats = $odb -> prepare("INSERT INTO `chat_group_msg` (`group_id`, `sender_id`, `msg`) VALUES(:group_id, :sender_id, :chat_message)"); 
                $insertChats -> execute(array(':group_id' => $chat_group_id, ':sender_id' => $loggedUserId, ':chat_message' => $message));
            }
            

            $ret['status'] = true;
            $ret['message'] = 'Message Send';
            echo json_encode($ret);
        }
        catch(Trowable $e)
        {
            $ret['status'] = false;
            $ret['message'] = $e->getMessage();
            echo json_encode($ret);
        }
        
    }
?>