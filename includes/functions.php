<?php
error_reporting(0);


class user
{
	function isAdmin($odb)
	{
		$SQL = $odb -> prepare("SELECT teams.name as team_name FROM `users` INNER JOIN `teams` ON teams.id = users.rank WHERE `users`.`ID` = :id");
		$SQL -> execute(array(':id' => $_SESSION['ID']));
		$team_name = $SQL -> fetchColumn(0);
		if ($team_name == 'Admin')
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	function isAssist($odb,$access='')
	{
		$SQL = $odb -> prepare("SELECT teams.name as team_name FROM `users` INNER JOIN `teams` ON teams.id = users.rank WHERE `users`.`ID` = :id");
		$SQL -> execute(array(':id' => $_SESSION['ID']));
		$team_name = $SQL -> fetchColumn(0);
		if ($team_name == 'Administrative Assistant')
		{
			if($access==''){
				return true;
			}else{
				$permission = $odb -> query("SELECT * FROM `users_meta` WHERE  user_id='".$_SESSION['ID']."' AND meta_key='assistant_permissions' AND  FIND_IN_SET ('".$access."',`meta_value`)")->fetch();

				if(empty($permission)){
					return false;
				}else{
					return true;
				}
				return true;
			}			
		}
		else
		{
			return false;
		}
	}
	function isRedTeam($odb)
	{
		$SQL = $odb -> prepare("SELECT teams.name as team_name FROM `users` INNER JOIN `teams` ON teams.id = users.rank WHERE `users`.`ID` = :id");
		$SQL -> execute(array(':id' => $_SESSION['ID']));
		$team_name = $SQL -> fetchColumn(0);
		if ($team_name == 'Red Team')
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	function isBlueTeam($odb)
	{
		$SQL = $odb -> prepare("SELECT teams.name as team_name FROM `users` INNER JOIN `teams` ON teams.id = users.rank WHERE `users`.`ID` = :id");
		$SQL -> execute(array(':id' => $_SESSION['ID']));
		$team_name = $SQL -> fetchColumn(0);
		if ($team_name == 'Blue Team')
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	function isPurpleTeam($odb)
	{
		$SQL = $odb -> prepare("SELECT teams.name as team_name FROM `users` INNER JOIN `teams` ON teams.id = users.rank WHERE `users`.`ID` = :id");
		$SQL -> execute(array(':id' => $_SESSION['ID']));
		$team_name = $SQL -> fetchColumn(0);
		if ($team_name == 'Purple Team')
		{
			return true;
		}
		else
		{
			return false;
		}
	}


	function getUserDetailIdWise($odb)
	{
		$SQL = $odb -> prepare("SELECT `users`.`college_id`,`users`.`rank`,teams.name as team_name FROM `users` INNER JOIN `teams` ON teams.id = users.rank WHERE `users`.`ID` = :id");
		$SQL -> execute(array(':id' => $_SESSION['ID']));
		$data = $SQL -> fetchAll();				
		 return $arr = array(
			'rank' => $data[0]['rank'],
			'team_name' => $data[0]['team_name'],
			'college_id' => $data[0]['college_id']
		);
	}

	function LoggedIn()
	{
		@session_start();
		if (isset($_SESSION['username'], $_SESSION['ID']))
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	function LoggedAval($odb = array())
	{
		@session_start();
		
		//print_r($odb);
		$row = array();
		
		if(isset($_SESSION['username'])){
			$usersql = $odb -> prepare("SELECT `username` FROM `users` WHERE `username` = :username");
			$usersql -> execute(array(":username" => $_SESSION['username']));
			$row = $usersql -> fetch();
		}
		

		if (isset($row) && !empty($row))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	function isLocked()
	{
		@session_start();
		if (isset($_SESSION['locked']))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	function notBanned($odb)
	{
		$SQL = $odb -> prepare("SELECT `status` FROM `users` WHERE `ID` = :id");
		$SQL -> execute(array(':id' => $_SESSION['ID']));
		$result = $SQL -> fetchColumn(0);
		if ($result == 0)
		{
			return true;
		}
		else
		{
			session_destroy();
			return false;
		}
	}

	function gradeList($odb)
	{
		return $SQLGetGrade = $odb -> query("SELECT * FROM `grade`")->fetchAll();
		// return $SQLGetGrade->fetch();
	}

	function getTeamList($odb,$college_id)
	{
		return $SQLGetTeam = $odb -> query("SELECT teams.* FROM `teams` INNER JOIN team_status ON teams.id = team_status.team_id WHERE college_id = $college_id && status = 1  ORDER BY id ASC")->fetchAll();
		// return $SQLGetGrade->fetch();
	}

	function loggedUserDetail()
	{
		$loggedUserDetail = [];
		$loggedUserDetail['id'] = $_SESSION['ID'];
		return $loggedUserDetail;
	}

	function userInfo($odb,$user_id='')
	{

		if($user_id!=''){
			$SQL = $odb -> prepare("SELECT *,teams.name as team_name,teams.color_code FROM `users` INNER JOIN `teams` ON `teams`.`id` = `users`.`rank` WHERE `users`.`ID` = :id");
			$SQL -> execute(array(':id' => $user_id));
			$user = $SQL -> fetch();
			return $user;
		}else{
			return array();
		}		
	}

	function getTeamMembers($odb,$user_id='',$rank){
		if($user_id!=''){
			$SQL = $odb -> prepare("SELECT username FROM `users` WHERE `rank` = :rank AND `ID` != :user_id");
			$SQL -> execute(array(':user_id' => $user_id, ':rank'=> $rank));
			$users = $SQL -> fetchAll();
			return $users;
		}else{
			return array();
		}		
	}

	function getUserList($odb,$user_id='',$college_id=0){
		if($user_id!=''){
			$users     = $odb -> query("SELECT * FROM `users` WHERE ID !=  $user_id AND college_id = $college_id ORDER BY username ASC")->fetchAll();
			return $users;
		}else{
			return array();
		}		
	}

	function getUserGroups($odb,$user_id=''){
		if($user_id!=''){
			$usersgroups     = $odb -> query("SELECT * FROM chat_group WHERE FIND_IN_SET($user_id, `group_members`) OR `created_by`=$user_id")->fetchAll();
			return $usersgroups;
		}else{
			return array();
		}		
	}

	function getContactList($odb,$user_id=''){
		if($user_id!=''){
			$contacts     = $odb -> query("SELECT * FROM chat_contacts cc INNER JOIN users us ON cc.receiver_id=us.ID WHERE cc.sender_id=$user_id AND cc.status=0 ORDER BY us.username ASC")->fetchAll();
			return $contacts;
		}else{
			return array();
		}		
	}

	function getChatUserList($odb,$user_id=''){
		if($user_id!=''){
			$chatuser = $odb -> query("select distinct least(sender_userid, reciever_userid) as partner1 , greatest(reciever_userid, sender_userid) as partner2 from chat WHERE sender_userid=$user_id OR reciever_userid=$user_id")->fetchAll();
			return $chatuser;
		}else{
			return array();
		}		
	}

	function getUserMeta($odb,$user_id='',$key){
		if($user_id!=''){
			$usermeta = $odb -> query("select * from users_meta WHERE user_id=$user_id AND meta_key='".$key."'")->fetch();
			return $usermeta;
		}else{
			return array();
		}		
	}

	function updateLastSeen($odb,$user_id=''){

        $SQL = $odb -> prepare("SELECT * FROM `users_meta` WHERE `user_id` = :user_id AND `meta_key` = :lastseen");
        $SQL -> execute(array(':user_id' => $user_id,':lastseen' => 'lastseen_time'));
        $data = $SQL -> fetch(); 
        
        if(empty($data)){
            $insert = $odb -> prepare("INSERT INTO `users_meta` (`user_id`, `meta_key`, `meta_value`) VALUES(:user_id, :meta_key, :meta_value)");
            $insert -> execute(array(':user_id' => $user_id, ':meta_key' => 'lastseen_time', ':meta_value' => time()));
        }else{
            $update = $odb -> prepare("UPDATE `users_meta` SET `meta_value`=:meta_value WHERE user_id = :user_id AND meta_key = :meta_key");
            $update -> execute(array(':meta_value' => time(),':user_id' => $user_id, ':meta_key' => 'lastseen_time'));

        }		
	}

	 // chat chaanges
	 function createChannel($odb,$user1=0,$user2=0){

        $SQL = $odb -> prepare("SELECT * FROM `chat_channel` WHERE (`user_one` = :user1 AND `user_two` = :user2) OR (`user_one` = :user2 AND `user_two` = :user1) AND status = 1");
        $SQL -> execute(array(':user1' => $user1,':user2' => $user2));
		$data = $SQL -> fetchAll();				
		         
        if(empty($data)){
			$channelName = 'private-chat'.$user1.$user2;
            $insert = $odb -> prepare("INSERT INTO `chat_channel` (`user_one`, `user_two`, `channel_name`) VALUES(:user_one, :user_two, :channel_name)");
            $insert -> execute(array(':user_one' => $user1, ':user_two' => $user2, ':channel_name' => $channelName));
        }else{
            $channelName = $data[0]['channel_name'];

        }		
		return $channelName;
	}

	function readUnreadMessage($odb,$sender_id='',$receiver_id=''){
		$mystatusaval = $this->getUserMeta($odb,$receiver_id,"statusaval");
		$mystatus = 'Online';
		if(!empty($mystatusaval)){
		    $mystatus = $mystatusaval['meta_value'];
		}
		$queryadd = '';
		if($mystatus!="Offline"){
			$update = $odb -> prepare("UPDATE `chat` SET `status`=:status WHERE sender_userid = :sender_id AND reciever_userid = :receiver_id");
        	$update -> execute(array(':status' => 0,':sender_id' => $sender_id, ':receiver_id' => $receiver_id));	
		}	
	}

	function getLastMessage($odb,$partner1='',$partner2=''){

		if($partner1!='' && $partner1!=''){
			$mystatusaval = $this->getUserMeta($odb,$partner2,"statusaval");
			$mystatus = 'Online';
			if(!empty($mystatusaval)){
			    $mystatus = $mystatusaval['meta_value'];
			}
			$queryadd = '';
			if($mystatus=="Offline"){
				$statusaval_time = $this->getUserMeta($odb,$partner2,"statusaval_time");
			    $aval_time = $statusaval_time['meta_value'];
			    $queryadd = "AND (timestamp<".$aval_time." OR sender_userid=".$partner2.")";
			}
			$chatQuery = "SELECT * FROM `chat` WHERE (sender_userid = $partner1 AND reciever_userid = '".$partner2."') OR (sender_userid = '".$partner2."' AND reciever_userid = '".$partner1."') ".$queryadd." ORDER BY timestamp DESC";
			$lastchat = $odb->query($chatQuery)->fetch(PDO::FETCH_ASSOC);
			return $lastchat;
		}else{
			return array();
		}		
	}

	function getUnreadMessageCount($odb,$senderUserid='', $loggedUserId='') {
		$mystatusaval = $this->getUserMeta($odb,$loggedUserId,"statusaval");
		$mystatus = 'Online';
		if(!empty($mystatusaval)){
		    $mystatus = $mystatusaval['meta_value'];
		}
		$queryadd = '';
		if($mystatus=="Offline"){
			$statusaval_time = $this->getUserMeta($odb,$loggedUserId,"statusaval_time");
		    $aval_time = $statusaval_time['meta_value'];
		    $queryadd = "AND (timestamp<".$aval_time." OR sender_userid=".$loggedUserId.")";
		}
        $get_unread_count = $odb->prepare("SELECT * FROM `chat` WHERE `sender_userid` = :sender_userid AND `reciever_userid` = :reciever_userid AND `status` = 1"." ".$queryadd);
        $get_unread_count -> execute(array( ':sender_userid' => $senderUserid, ':reciever_userid' => $loggedUserId));
        $numRows = $get_unread_count->rowCount();
        $output = '';
        if($numRows > 0){
            $output = $numRows;
        }
        return $output;
    }

    function getATeamMembers($odb,$rank='',$college_id=null) {
        if($rank!=''){
			$SQL = $odb -> prepare("SELECT * FROM `users` WHERE `rank` = :rank AND `college_id` = :college_id");
			$SQL -> execute(array(':rank'=> $rank,':college_id'=>$college_id));
			$users = $SQL -> fetchAll();
			return $users;
		}else{
			return array();
		}		
    }

    function getAllUserList($odb){
		$users     = $odb -> query("SELECT * FROM `users` ORDER BY username ASC")->fetchAll();
		return $users;
	}


    function getLastUnreadMessage($odb,$senderUserid='', $loggedUserId='') {
        $get_unread = $odb->prepare("SELECT * FROM `chat` WHERE `sender_userid` = :sender_userid AND `reciever_userid` = :reciever_userid AND `status` = 1 ORDER BY chatid DESC");
        $get_unread -> execute(array( ':sender_userid' => $senderUserid, ':reciever_userid' => $loggedUserId));
        $get_msg = $get_unread->fetch();        
        return $get_msg;
    }

    function getUserMute($odb,$loggedUserId='',$senderUserid='' ) {
        $get_mute = $odb->prepare("SELECT * FROM `chat_mute` WHERE `receiver_id` = :receiver_id AND `sender_id` = :sender_id");
        $get_mute -> execute(array( ':receiver_id' => $loggedUserId, ':sender_id' => $senderUserid));
        $mute = $get_mute->fetch();        
        return $mute;
    }

	function isMaintanenceMode($odb,$college_id) {
        $sqlMaintenenceMode = $odb -> query("SELECT status FROM `settings` WHERE `label` LIKE 'is_maintanence_mode' AND college_id = $college_id");
        $maintenanceMode = $sqlMaintenenceMode->fetchColumn();    
        return $maintenanceMode;
    }

	function getCollegeIdByAccesstoken($odb,$token) {
        $sqlCollegeIdToken = $odb -> query("SELECT college_id FROM `team_status` WHERE `team_code` LIKE '$token'");
        $sqlCollegeIdToken = $sqlCollegeIdToken->fetchColumn();    
        return $sqlCollegeIdToken;
    }

	function isRegistrationMode($odb,$college_id) {
        $sqlRegistrationMode = $odb -> query("SELECT status FROM `settings` WHERE `label` LIKE 'is_registration_mode' AND college_id = $college_id");
        $registrationMode = $sqlRegistrationMode->fetchColumn();    
        return $registrationMode;
    }

	function isRedTeamCTFactive($odb,$college_id) {
        $sqlRedTeamCTFActive = $odb -> query("SELECT status FROM `settings` WHERE `label` LIKE 'is_red_team_ctf_active' AND college_id = $college_id");
        $redTeamCTF = $sqlRedTeamCTFActive->fetchColumn();    
        return $redTeamCTF;
    }

	function addRecentActivities($odb,$label,$activities) {
		try
		{  
			$odb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$odb->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
			$getUserDetailIdWise = $this->getUserDetailIdWise($odb);
			$college_id = $getUserDetailIdWise['college_id']; 
			$SQLinsert = $odb -> prepare("INSERT INTO `recent_activities`(`id`,`college_id`, `user_id`,`label`, `activities`, `datetime`)  VALUES(NULL,$college_id ,:user_id,:label, :activities,:datetime)");

			

			$SQLinsert -> execute(array(':user_id' => $_SESSION['ID'], ':label' => $label, ':activities' => $activities,':datetime' => DATETIME));  
			return true;

		 }
		catch(Exception $e) {
			echo 'Exception -> ';
			var_dump($e->getMessage());
		}
                    
    }

	function getRecentActivities($odb)
	{
		return $SQLrecentActivity = $odb -> query("SELECT * FROM `recent_activities` ORDER BY `recent_activities`.`datetime` DESC LIMIT 20")->fetchAll();
	}

	function getActiveInactiveSession($odb,$college_id)
	{
		$time = strtotime(DATETIME);
		$time = $time - (15 * 60);
		$time = date("Y-m-d H:i:s", $time);
		$SQLActiveSession = $odb -> query("SELECT COUNT(id) FROM `user_session` WHERE `active_session` > '$time' AND college_id = $college_id");
		$activeSession = $SQLActiveSession->fetchColumn();
		$totaluser = $odb -> query("SELECT COUNT(ID) FROM `users` WHERE college_id = $college_id")->fetchColumn();
		$inactiveSession = $totaluser - $activeSession;
		$SQLactiveUser = $odb -> query("SELECT * FROM `user_session` WHERE `active_session` > '$time' AND college_id = $college_id ")->fetchAll();
		return $arr = array(
			'active_session' => $activeSession,
			'inactive_session' => $inactiveSession,
			'get_all_active_user' => $SQLactiveUser
		);
	}

	function getUsernameByid($odb,$id)
	{
		return $username = $odb -> query("SELECT username FROM `users` WHERE ID = $id")->fetchColumn();
	}

	function random_strings($length_of_string) 
	{    
		$str_result = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz'; 
		return substr(str_shuffle($str_result), 0, $length_of_string); 
	} 

	function getTeamByUserID($odb,$id)
	{

	}

	function darken_color($rgb, $darker=2) {

		$hash = (strpos($rgb, '#') !== false) ? '#' : '';
			$rgb = (strlen($rgb) == 7) ? str_replace('#', '', $rgb) : ((strlen($rgb) == 6) ? $rgb : false);
			if(strlen($rgb) != 6) return $hash.'000000';
			$darker = ($darker > 1) ? $darker : 1;
	
			list($R16,$G16,$B16) = str_split($rgb,2);
	
			$R = sprintf("%02X", floor(hexdec($R16)/$darker));
			$G = sprintf("%02X", floor(hexdec($G16)/$darker));
			$B = sprintf("%02X", floor(hexdec($B16)/$darker));
	
			return $hash.$R.$G.$B;
		}

		function callAPI($method, $url, $data){
            $curl = curl_init();
            switch ($method){
               case "POST":
                  curl_setopt($curl, CURLOPT_POST, 1);
                  if ($data)
                     curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                  break;
               case "PUT":
                  curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                  if ($data)
                     curl_setopt($curl, CURLOPT_POSTFIELDS, $data);			 					
                  break;
               default:
                  if ($data)
                     $url = sprintf("%s?%s", $url, http_build_query($data));
            }
            // OPTIONS:
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
               'APIKEY: 111111111111111111111',
               'Content-Type: application/json',
            ));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            // EXECUTE:
            $result = curl_exec($curl);
            if(!$result){die("Connection Failure");}
            curl_close($curl);
            return $result;
         }

		 function send_sms_twilio( $phone,$otp,$client )
		 {  
			try
			{ 
			   
				$twilio_number = "+16027868971";
			   $final = $client->messages->create(
			       // Where to send a text message (your cell phone?)
			       $phone,
			       array(
			           'from' => $twilio_number,
			           'body' => 'rootCapture: '.$otp.' is your security code, do not share this code with anyone.'
			       )
			   );

			  return [
				'status' => true
			  ];			  
			   
			}                                                      
			catch(Exception $e)
			{    			  
			   return [
				'status' => false,	  
				'err_msg' => $e->getMessage()	  
			   ];
			} 
		}	

		function sendEmail($mail = null, $to, $subject, $body) {
			// Create a new PHPMailer instance
			
		
			try {
				// Server settings
				$mail->isSMTP();                                      // Set mailer to use SMTP
				$mail->Host = 'smtp.ionos.com';                     // Specify main and backup SMTP servers
				$mail->SMTPAuth = true;                               // Enable SMTP authentication
				$mail->Username   = 'auth@rootcapture.com';                     // SMTP username
				$mail->Password   = 'YT%wX@V826#z';                    // SMTP password
				$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
				$mail->Port = 587;                                    // TCP port to connect to
		
				// Recipients
				$mail->setFrom('support@rootcapture.com', 'RootCapture');
				$mail->addAddress($to);                               // Add a recipient
		
				// Content
				$mail->isHTML(true);                                  // Set email format to HTML
				$mail->Subject = $subject;
				$mail->Body = $body;
				// $mail->SMTPDebug = 2; 
				// Send email
				$mail->send();
				return true;
			} catch (Exception $e) {
				// echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
				return false;
			}
		}




    
}
class stats
{
	function totalUsers($odb)
	{
		$SQL = $odb -> query("SELECT COUNT(*) FROM `users`");
		return $SQL->fetchColumn(0);
	}
	function totalRed($odb)
	{
		$SQL = $odb -> query("SELECT COUNT(*) FROM 'redservers'");
		return $SQL->fetchColumn(0);
	}
		function totalBlue($odb)
	{
		$SQL = $odb -> query("SELECT COUNT(*) FROM 'blueservers'");
		return $SQL->fetchColumn(0);
	}
		function totalPurple($odb)
	{
		$SQL = $odb -> query("SELECT COUNT(*) FROM 'purpleservers'");

		return $SQL->fetchColumn(0);
	}
}
?>