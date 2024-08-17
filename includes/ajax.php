<?php
  ob_start(); 

  require_once './db.php';
  require_once './init.php';
 
  $function_name = $_POST['function_name'];
  $ip_address = $_POST['ip_address'];
  $code = ($_POST['code'])?$_POST['code']:'';

  if($function_name == 'maintainence_mode_change') maintainence_mode_change($odb,$user); else 
  if($function_name == 'registration_mode_change') registration_mode_change($odb,$user); else 
  if($function_name == 'redTeamCTFactive') redTeamCTFactive($odb,$user); else
  if($function_name == 'maintain_active_inactive_session') maintainActiveInactiveSession($odb,$ip_address); else 
  if($function_name == 'regenerate_access_teamkey') regenerate_access_teamkey($odb,$user,$code); else
  if($function_name == 'get_live_recent_activities') getLiveRecentActivities($odb,$user); 
  

    function maintainence_mode_change($odb,$user)
    { 
        
         $status = ($_POST['status'])?1:0; 
         $college_id =  $_POST['college_id']; 

        $pairExists = $odb -> query("SELECT COUNT(id) FROM `settings` WHERE `college_id` = $college_id AND label LIKE 'is_maintanence_mode'");
        if($pairExists->fetchColumn())
        {   
            $updateMaintainenceStatusSql = $odb->prepare("UPDATE settings SET `status` = :status WHERE `label` LIKE 'is_maintanence_mode' AND `college_id` = $college_id");
            $updateMaintainenceStatusSql->execute(array(':status' =>$status));
        }else{
            $statement1 = $odb -> prepare("INSERT INTO `settings` (`college_id`, `label`, `status`) VALUES
            ($college_id, 'is_maintanence_mode', :status)");
            $statement1->execute(array(':status' =>$status));
        }
        
        if($status)
        {
            $user->addRecentActivities($odb,'maintainence_mode',' enabled the Maintenance Mode.');
        }
        else
        {
            $user->addRecentActivities($odb,'maintainence_mode',' disabled the Maintenance Mode.');
        }

        $ret['status'] = true;
        $ret['message'] = "Maintanence mode status has been updated.";
        echo json_encode($ret);
        return;
       
    }

    function registration_mode_change($odb,$user)
    { 
        
        $status = ($_POST['status'])?1:0; 
        $college_id =  $_POST['college_id']; 
        
        $pairExists = $odb -> query("SELECT COUNT(id) FROM `settings` WHERE `college_id` = $college_id AND label LIKE 'is_registration_mode'");
        if($pairExists->fetchColumn())
        {   
            $updateRegistrationStatusSql = $odb->prepare("UPDATE settings SET `status` = :status WHERE `label` LIKE 'is_registration_mode' AND `college_id` = $college_id");
            $updateRegistrationStatusSql->execute(array(':status' =>$status));
        }
        else
        {
            $statement1 = $odb -> prepare("INSERT INTO `settings` (`college_id`, `label`, `status`) VALUES
            ($college_id, 'is_registration_mode', :status)");
            $statement1->execute(array(':status' =>$status));
        }

       
        //change access code of each team
       
        if($status)
        {
            $SQLGetTeams =   $odb -> query("SELECT teams.*,team_status.team_code,team_status.id as teamStatusId FROM `teams` INNER JOIN team_status ON teams.id = team_status.team_id WHERE teams.name != 'Admin' AND teams.name != 'Administrative Assistant' AND  team_status.college_id = $college_id AND team_status.status = 1 ORDER BY id ASC");

            while ($getInfo = $SQLGetTeams -> fetch(PDO::FETCH_ASSOC))
            {
                if(!$getInfo['team_code'])
                {
                    $team_code = $user->random_strings(20); 
                    $sqlTeamCodeExists = $odb -> query("SELECT COUNT(id) FROM `team_status` WHERE `team_code` LIKE '$team_code' AND college_id = $college_id");
                    if($sqlTeamCodeExists->fetchColumn())
                    {   
                        $team_code = $user->random_strings(20);
                    }
    
                    $SQLupdate = $odb -> prepare("UPDATE `team_status` SET  `team_code` = :team_code WHERE  `id` = :id");
                    $SQLupdate -> execute(array(':team_code' => $team_code,':id' => $getInfo['teamStatusId']));
                }
            }
            
            $user->addRecentActivities($odb,'registration_mode',' enabled Platform Registration.');
        }
        else
        {
            // $sqlTeamCodeDelete = $odb -> query("UPDATE `teams` SET  `team_code` = NULL WHERE college_id = $college_id");
            $user->addRecentActivities($odb,'registration_mode',' disabled Platform Registration.');
        }

        $ret['status'] = true;
        $ret['message'] = "Registration mode status has been updated.";
        $ret['team_code'] =$odb -> query("SELECT teams.*,team_status.team_code FROM `teams` INNER JOIN team_status ON teams.id = team_status.team_id WHERE teams.name != 'Admin' AND teams.name != 'Administrative Assistant' AND team_status.college_id = $college_id AND team_status.status = 1 ORDER BY id ASC")->fetchAll();
        echo json_encode($ret);
        return;
       
    }

    function regenerate_access_teamkey($odb,$user,$code)
    {   
        $college_id =  $_POST['college_id']; 
        $team_code = $user->random_strings(20); 
        $sqlTeamCodeExists = $odb -> query("SELECT COUNT(id) FROM `team_status` WHERE `team_code` LIKE '$team_code' AND college_id = $college_id");
        if($sqlTeamCodeExists->fetchColumn())
        {   
            $team_code = $user->random_strings(20);
        }
       
        $SQLupdate = $odb -> prepare("UPDATE `team_status` SET  `team_code` = :team_code WHERE  `team_code` LIKE :code AND college_id = $college_id");
        $SQLupdate -> execute(array(':team_code' => $team_code,':code' => $code));

        $ret['status'] = true;
        $ret['message'] = "New access code has been generated.";
        $ret['team_code'] = $team_code;
        echo json_encode($ret);
        return;
    }

    function redTeamCTFactive($odb,$user)
    { 
        
         $status = ($_POST['status'])?1:0; 
         $college_id =  $_POST['college_id']; 

         $pairExists = $odb -> query("SELECT COUNT(id) FROM `settings` WHERE `college_id` = $college_id AND label LIKE 'is_red_team_ctf_active'");

        if($pairExists->fetchColumn())
        {   
            $updateRedTeamCtfStatusSql = $odb->prepare("UPDATE settings SET `status` = :status WHERE `label` LIKE 'is_red_team_ctf_active' AND `college_id` = $college_id");
            $updateRedTeamCtfStatusSql->execute(array(':status' =>$status));
        }
        else
        {
            $statement1 = $odb -> prepare("INSERT INTO `settings` (`college_id`, `label`, `status`) VALUES
            ($college_id, 'is_red_team_ctf_active', :status)");
            $statement1->execute(array(':status' =>$status));
        }

        
        $getRedTeamId =  $odb -> query("SELECT id FROM `teams` WHERE `name` LIKE 'Red Team'");
		$getRedTeamId = $getRedTeamId->fetchColumn();
        if($status)
        {
            $user->addRecentActivities($odb,'red_team_ctf_mode',' enabled the Red Team Capture The Flag Competition.');
            $SQLGetUser = $odb -> query("SELECT users.ID,users.rank FROM `users` INNER JOIN `teams` ON teams.id = users.rank WHERE teams.name != 'Admin' AND teams.name != 'Administrative Assistant' AND users.college_id = $college_id")->fetchAll();
            foreach ($SQLGetUser as $usr) {
                $SQLupdate = $odb -> prepare("UPDATE users SET `rank` = $getRedTeamId, `previous_rank` = :previous_rank WHERE ID = :id");
                $SQLupdate -> execute(array(':previous_rank' => $usr['rank'], ':id' => $usr['ID']));
            }
        }
        else
        {
            $user->addRecentActivities($odb,'red_team_ctf_mode',' disabled the Red Team Capture The Flag Competition.');
            $SQLGetUser = $odb -> query("SELECT users.ID,users.rank,users.previous_rank FROM `users` INNER JOIN `teams` ON teams.id = users.rank WHERE teams.name != 'Admin' AND teams.name != 'Administrative Assistant' AND users.college_id = $college_id")->fetchAll();
            foreach ($SQLGetUser as $usr) {
                $SQLupdate = $odb -> prepare("UPDATE users SET `rank` = :rank, `previous_rank` = 0 WHERE ID = :id");
                $SQLupdate -> execute(array(':rank' => $usr['previous_rank'], ':id' => $usr['ID']));
            }
        }

        $ret['status'] = true;
        $ret['message'] = "Ctf red team status has been updated.";
        echo json_encode($ret);
        return;
       
    }

    function maintainActiveInactiveSession($odb,$ip_address)
    {
        try
        {
            $odb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $odb->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

            $user_id = $_SESSION['ID'];
            $college_id = $_POST['college_id'];

            $sqlIsExist = $odb -> query("SELECT count(id) as is_exists FROM `user_session` WHERE `user_id` = $user_id AND college_id = $college_id");
            $sqlIsExist = $sqlIsExist->fetchColumn();

            if($sqlIsExist)
            {
                $SQLupdate = $odb -> prepare("UPDATE user_session SET `active_session` = :active_session, `ip_address` = :ip_address WHERE user_id = :id AND college_id = $college_id");
                $SQLupdate -> execute(array(':active_session' => DATETIME,':ip_address' => $ip_address, ':id' => $_SESSION['ID']));                
            }
            else
            {
                $sqlInsert = $odb -> prepare("INSERT INTO `user_session` (`user_id`,`college_id`, `active_session`, `ip_address`) VALUES
                (:user_id,$college_id, :active_session, :ip_address)");
                $sqlInsert -> execute(array(':user_id' => $_SESSION['ID'], ':active_session' => DATETIME, ':ip_address' => $ip_address));
            }
            
            $ret['status'] = true;
            $ret['message'] = "Success.";
            echo json_encode($ret);

        }
        catch(Exception $e) {
            echo 'Exception -> ';
            var_dump($e->getMessage());
        }
    }

    function getLiveRecentActivities($odb,$user)
    {
        $getRecentActivities = $user -> getRecentActivities($odb);
        $content = '';
         foreach ($getRecentActivities as $recentAct) { 
            if(isset($recentAct['user_id']) && $recentAct['user_id'] != Null)
            {
                $content .= '<div class="item-timeline timeline-primary">
                <div class="t-dot" data-original-title="" title="">
                </div>
                <div class="t-text">
                    <p>
                        <span>'.$user->getUsernameByid($odb,$recentAct['user_id']).'</span>'.$recentAct['activities'].'</p>
                    <p class="t-time">'.$recentAct['datetime'].'</p>
                </div>
                </div>';
            }
            else
            {
                $content .= '<div class="item-timeline timeline-primary">
                <div class="t-dot" data-original-title="" title="">
                </div>
                <div class="t-text">
                    <p>'.$recentAct['activities'].'</p>
                    <p class="t-time">'.$recentAct['datetime'].'</p>
                </div>
                </div>';  
            }
                      
         } 
         $ret['status'] = true;
         $ret['message'] = "Success.";
         $ret['content'] = $content;
         
         echo json_encode($ret);
         return $ret;
    }

   
?>