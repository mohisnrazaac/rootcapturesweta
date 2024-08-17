<?php
ob_start();
require  $_SERVER["DOCUMENT_ROOT"].'/includes/functions.php';


// use $odb;
Class ApiController
{
    private $host = "localhost";
    private $username = "a90amc1ZaLpcF";
    private $password = "4rG7Frdq&qhx77sBb";
    private $database = "rootCapCre";
    private $connection;
    private $user;

    public function __construct()
    {
        $this->user = new user;

    }
    

    public function getAllActiveUser($college_token)
    {
        try
        {  
            
            $dsn = "mysql:host=$this->host;dbname=$this->database";
            $this->connection = new PDO($dsn, $this->username, $this->password);
            // Set PDO to throw exceptions on error
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
            $sql = $this->connection->prepare("select id from college WHERE access_token = :access_token");
            $sql -> execute(array(":access_token" => $college_token));
            
             $result = $sql->fetchAll(PDO::FETCH_ASSOC);
            
            if( !empty($result) && $result[0] && $result[0]['id'])
            {
                $college_id = $result[0]['id'];
                $rawQuery = "SELECT * FROM `user_session` WHERE  college_id = $college_id";

                $statement = $this->connection->prepare($rawQuery);
                $statement->execute();
                // Close the connection
                $this->connection = null;
                return [
                    'status' => true,	  
                    'message' => 'Data fetched',	  
                    'data' => $statement->fetchAll(PDO::FETCH_ASSOC)
                   ];
            }
            else
            {
                return [
                    'status' => false,	  
                    'err_msg' => 'Access Token Invalid' 
                   ];
            }
            
        } catch (PDOException $e) {
            return [
				'status' => false,	  
				'err_msg' => $e->getMessage()	  
			   ];
        }
    }

    public function getAllCollegeWiseQuiz($college_token)
    {
        try
        {  
            
            $dsn = "mysql:host=$this->host;dbname=$this->database";
            $this->connection = new PDO($dsn, $this->username, $this->password);
            // Set PDO to throw exceptions on error
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
            $sql = $this->connection->prepare("select id from college WHERE access_token = :access_token");
            $sql -> execute(array(":access_token" => $college_token));
            
             $result = $sql->fetchAll(PDO::FETCH_ASSOC);
            
            if( !empty($result) && $result[0] && $result[0]['id'])
            {
                $college_id = $result[0]['id'];
                $rawQuery = "SELECT quize.* from quize WHERE college_id = $college_id  ORDER BY id ASC";

                $statement = $this->connection->prepare($rawQuery);
                $statement->execute();
                // Close the connection
                $this->connection = null;
                return [
                    'status' => true,	  
                    'message' => 'Data fetched',	  
                    'data' => $statement->fetchAll(PDO::FETCH_ASSOC)
                   ];
            }
            else
            {
                return [
                    'status' => false,	  
                    'err_msg' => 'Access Token Invalid' 
                   ];
            }
            
        } catch (PDOException $e) {
            return [
				'status' => false,	  
				'err_msg' => $e->getMessage()	  
			   ];
        }
    }

    public function getQuizDetail($college_token,$id)
    {
        try
        {  
            
            $dsn = "mysql:host=$this->host;dbname=$this->database";
            $this->connection = new PDO($dsn, $this->username, $this->password);
            // Set PDO to throw exceptions on error
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
            $sql = $this->connection->prepare("select id from college WHERE access_token = :access_token");
            $sql -> execute(array(":access_token" => $college_token));
            
             $result = $sql->fetchAll(PDO::FETCH_ASSOC);
            
            if( !empty($result) && $result[0] && $result[0]['id'])
            {
                $college_id = $result[0]['id'];
                $rawQuery = "SELECT quize.*,quize_question.* FROM `quize` INNER JOIN `quize_question` ON `quize`.id = `quize_question`.quize_id WHERE quize.college_id = $college_id  AND quize.id=$id";

                $statement = $this->connection->prepare($rawQuery);
                $statement->execute();
                // Close the connection
                $this->connection = null;
                return [
                    'status' => true,	  
                    'message' => 'Data fetched',	  
                    'data' => $statement->fetchAll(PDO::FETCH_ASSOC)
                   ];
            }
            else
            {
                return [
                    'status' => false,	  
                    'err_msg' => 'Access Token Invalid' 
                   ];
            }
            
        } catch (PDOException $e) {
            return [
				'status' => false,	  
				'err_msg' => $e->getMessage()	  
			   ];
        }
    }

    public function getAllUserCollegeWise($college_token)
    {
        try
        {  
            
            $dsn = "mysql:host=$this->host;dbname=$this->database";
            $this->connection = new PDO($dsn, $this->username, $this->password);
            // Set PDO to throw exceptions on error
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
            $sql = $this->connection->prepare("select id from college WHERE access_token = :access_token");
            $sql -> execute(array(":access_token" => $college_token));
            
             $result = $sql->fetchAll(PDO::FETCH_ASSOC);
            
            if( !empty($result) && $result[0] && $result[0]['id'])
            {
                $college_id = $result[0]['id'];
                $rawQuery = "SELECT users.*,teams.name as team_name FROM `users` LEFT JOIN   `teams` ON users.rank = teams.id WHERE users.college_id = $college_id  AND users.status != 2 ORDER BY `ID` DESC";

                $statement = $this->connection->prepare($rawQuery);
                $statement->execute();
                // Close the connection
                $this->connection = null;
                return [
                    'status' => true,	  
                    'message' => 'Data fetched',	  
                    'data' => $statement->fetchAll(PDO::FETCH_ASSOC)
                   ];
            }
            else
            {
                return [
                    'status' => false,	  
                    'err_msg' => 'Access Token Invalid' 
                   ];
            }
            
        } catch (PDOException $e) {
            return [
				'status' => false,	  
				'err_msg' => $e->getMessage()	  
			   ];
        }
    }

    public function getAllTeamsCollegeWise($college_token, $team_id)
    {
        try
        {  
            
            $dsn = "mysql:host=$this->host;dbname=$this->database";
            $this->connection = new PDO($dsn, $this->username, $this->password);
            // Set PDO to throw exceptions on error
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
            $sql = $this->connection->prepare("select id from college WHERE access_token = :access_token");
            $sql -> execute(array(":access_token" => $college_token));
            
             $result = $sql->fetchAll(PDO::FETCH_ASSOC);
            
            if( !empty($result) && $result[0] && $result[0]['id'])
            {
                $college_id = $result[0]['id'];

                // chnage status if required
                if($team_id != 0)
                {
                    $updateSql = $this->connection->prepare("UPDATE team_status SET status = (1-status) where team_id = :team_id AND college_id=:college_id");
                    $updateSql -> execute(array(":team_id" => $team_id, ":college_id" => $college_id));
                }

                $rawQuery = "SELECT teams.*,team_status.team_code,team_status.status as teamStatus,team_status.id as teamStatusId FROM `teams` INNER JOIN team_status ON teams.id = team_status.team_id WHERE teams.name != 'Admin' AND teams.name != 'Administrative Assistant' AND  team_status.college_id = $college_id ORDER BY id ASC";

                $statement = $this->connection->prepare($rawQuery);
                $statement->execute();

                // Close the connection
                $this->connection = null;
                return [
                    'status' => true,	  
                    'message' => 'Data fetched',	  
                    'data' => $statement->fetchAll(PDO::FETCH_ASSOC)
                   ];
            }
            else
            {
                return [
                    'status' => false,	  
                    'err_msg' => 'Access Token Invalid' 
                   ];
            }
            
        } catch (PDOException $e) {
            return [
				'status' => false,	  
				'err_msg' => $e->getMessage()	  
			   ];
        }
    }

    public function getAllAssetGroupCollegeWise($college_token)
    {
        try
        {  
            
            $dsn = "mysql:host=$this->host;dbname=$this->database";
            $this->connection = new PDO($dsn, $this->username, $this->password);
            // Set PDO to throw exceptions on error
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
            $sql = $this->connection->prepare("select id from college WHERE access_token = :access_token");
            $sql -> execute(array(":access_token" => $college_token));
            
             $result = $sql->fetchAll(PDO::FETCH_ASSOC);
            
            if( !empty($result) && $result[0] && $result[0]['id'])
            {
                $college_id = $result[0]['id'];
                $rawQuery = "SELECT * FROM `asset_group` WHERE asset_group.college_id = $college_id ORDER BY `ID` DESC";

                $statement = $this->connection->prepare($rawQuery);
                $statement->execute();
                // Close the connection
                $this->connection = null;
                return [
                    'status' => true,	  
                    'message' => 'Data fetched',	  
                    'data' => $statement->fetchAll(PDO::FETCH_ASSOC)
                   ];
            }
            else
            {
                return [
                    'status' => false,	  
                    'err_msg' => 'Access Token Invalid' 
                   ];
            }
            
        } catch (PDOException $e) {
            return [
				'status' => false,	  
				'err_msg' => $e->getMessage()	  
			   ];
        }
    }

    public function getTeamName($team_id)
    {
        try
        {  
            
            $dsn = "mysql:host=$this->host;dbname=$this->database";
            $this->connection = new PDO($dsn, $this->username, $this->password);
            // Set PDO to throw exceptions on error
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
            $rawQuery = "SELECT name,color_code FROM `teams` WHERE `id` = $team_id";

            $statement = $this->connection->prepare($rawQuery);
            $statement->execute();
            // Close the connection
            $this->connection = null;
            return [
                'status' => true,	  
                'message' => 'Data fetched',	  
                'data' => $statement->fetchAll(PDO::FETCH_ASSOC)
                ];
           
            
        } catch (PDOException $e) {
            return [
				'status' => false,	  
				'err_msg' => $e->getMessage()	  
			   ];
        }
    }

    public function getGradingRubricCollegeWise($college_token)
    {
        try
        {  
            
            $dsn = "mysql:host=$this->host;dbname=$this->database";
            $this->connection = new PDO($dsn, $this->username, $this->password);
            // Set PDO to throw exceptions on error
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
            $sql = $this->connection->prepare("select id from college WHERE access_token = :access_token");
            $sql -> execute(array(":access_token" => $college_token));
            
             $result = $sql->fetchAll(PDO::FETCH_ASSOC);
            
            if( !empty($result) && $result[0] && $result[0]['id'])
            {
                $college_id = $result[0]['id'];
                $rawQuery = "SELECT * FROM `grading_rubric_criteria` WHERE college_id = $college_id ORDER BY `ID` DESC";

                $statement = $this->connection->prepare($rawQuery);
                $statement->execute();
                // Close the connection
                $this->connection = null;
                return [
                    'status' => true,	  
                    'message' => 'Data fetched',	  
                    'data' => $statement->fetchAll(PDO::FETCH_ASSOC)
                   ];
            }
            else
            {
                return [
                    'status' => false,	  
                    'err_msg' => 'Access Token Invalid' 
                   ];
            }
            
        } catch (PDOException $e) {
            return [
				'status' => false,	  
				'err_msg' => $e->getMessage()	  
			   ];
        }
    }

    public function getAllSupportCollegeWise($college_token)
    {
        try
        {  
            
            $dsn = "mysql:host=$this->host;dbname=$this->database";
            $this->connection = new PDO($dsn, $this->username, $this->password);
            // Set PDO to throw exceptions on error
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
            $sql = $this->connection->prepare("select id from college WHERE access_token = :access_token");
            $sql -> execute(array(":access_token" => $college_token));
            
             $result = $sql->fetchAll(PDO::FETCH_ASSOC);
            
            if( !empty($result) && $result[0] && $result[0]['id'])
            {
                $college_id = $result[0]['id'];
                $rawQuery = "SELECT users.username, 
                tickets.ticketID, 
                tickets.ticketTitle, 
                tickets.ticketStatus, 
                (SELECT ticketResponses.time FROM `ticketResponses` WHERE ticketResponses.ticketID = tickets.ticketID ORDER BY ticketResponses.time DESC LIMIT 1) AS lastResponseTime
            FROM `tickets`
            INNER JOIN `users` ON tickets.userID = users.ID AND users.college_id = $college_id";

                $statement = $this->connection->prepare($rawQuery);
                $statement->execute();
                // Close the connection
                $this->connection = null;
                return [
                    'status' => true,	  
                    'message' => 'Data fetched',	  
                    'data' => $statement->fetchAll(PDO::FETCH_ASSOC)
                   ];
            }
            else
            {
                return [
                    'status' => false,	  
                    'err_msg' => 'Access Token Invalid' 
                   ];
            }
            
        } catch (PDOException $e) {
            return [
				'status' => false,	  
				'err_msg' => $e->getMessage()	  
			   ];
        }
    }

    public function getAllteamListUserWise($college_token)
    {
        try
        {  
            
            $dsn = "mysql:host=$this->host;dbname=$this->database";
            $this->connection = new PDO($dsn, $this->username, $this->password);
            // Set PDO to throw exceptions on error
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
            $sql = $this->connection->prepare("select id from college WHERE access_token = :access_token");
            $sql -> execute(array(":access_token" => $college_token));
            
             $result = $sql->fetchAll(PDO::FETCH_ASSOC);
            
            if( !empty($result) && $result[0] && $result[0]['id'])
            {
                $college_id = $result[0]['id'];
                $rawQuery = "SELECT teams.* FROM `teams` INNER JOIN team_status ON teams.id = team_status.team_id  WHERE teams.name != 'Admin' AND college_id = $college_id AND status = 1   ORDER BY id ASC";

                $statement = $this->connection->prepare($rawQuery);
                $statement->execute();
                // Close the connection
                $this->connection = null;
                return [
                    'status' => true,	  
                    'message' => 'Data fetched',	  
                    'data' => $statement->fetchAll(PDO::FETCH_ASSOC)
                   ];
            }
            else
            {
                return [
                    'status' => false,	  
                    'err_msg' => 'Access Token Invalid' 
                   ];
            }
            
        } catch (PDOException $e) {
            return [
				'status' => false,	  
				'err_msg' => $e->getMessage()	  
			   ];
        }
    }

    public function createUserCollegeWise($college_token,$username,$phone,$email,$password,$role,$fa_preference,$restrict,$assistant_permissions)
    {
        try
        {  
            
            $dsn = "mysql:host=$this->host;dbname=$this->database";
            $this->connection = new PDO($dsn, $this->username, $this->password);
            // Set PDO to throw exceptions on error
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
            $sql = $this->connection->prepare("select id from college WHERE access_token = :access_token");
            $sql -> execute(array(":access_token" => $college_token));
            
             $result = $sql->fetchAll(PDO::FETCH_ASSOC);
            
            if( !empty($result) && $result[0] && $result[0]['id'])
            {
                $college_id = $result[0]['id'];

                $checkUsernameExists = $this->connection->prepare("SELECT ID FROM `users` WHERE `username` = :username");
                $checkUsernameExists -> execute(array(":username" => $username));
                $checkUsernameExists = $checkUsernameExists->fetchAll(PDO::FETCH_ASSOC);

                if( !empty($checkUsernameExists) && $checkUsernameExists[0] && $checkUsernameExists[0]['ID'])
                {
                    // Close the connection
                    $this->connection = null;
                    return [
                        'status' => false,	  
                        'message' => 'The username is already in use, please use a different username.',
                        'err_msg' => 'The username is already in use, please use a different username.',
                    ];
                }
                else
                {
                    $checkPhoneExists = $this->connection->prepare("SELECT * FROM `users` WHERE `phone` = :phone");
                    $checkPhoneExists -> execute(array(":phone" => $phone));
                    $checkPhoneExists = $checkPhoneExists->fetchAll(PDO::FETCH_ASSOC);

                    if( !empty($checkPhoneExists) && $checkPhoneExists[0] && $checkPhoneExists[0]['ID'])
                    {
                        // Close the connection
                        $this->connection = null;
                        return [
                            'status' => false,	  
                            'message' => 'The email is already in use, please use a different email.',
                            'err_msg' => 'The email is already in use, please use a different email.',
                        ];
                    }
                    else
                    {
                        $shaPass = SHA1($password);
                        if( !empty($email) && !empty($phone))
                        {
                            $SQLinsert = $this->connection->prepare("INSERT INTO `users`(`ID`, `username`, `password`, `email`, `rank`, `phone`,`created_by`,`college_id`, `membership`, `expire`, `status`, `key`, `used`, `otp`, `otp_verification_preference`, `banned_msg`, `grading_criteria`, `restrict_chat`,`datetime`) VALUES(NULL, '$username', '$shaPass', '$email', $role, '$phone',0,$college_id, 0, 0, 0, NULL, 0, Null,$fa_preference,Null,0,$restrict,now())");    
                            $SQLinsert -> execute();              

                        }else if( !empty($email) && empty($phone) )
                        { 
                            $SQLinsert = $this->connection->prepare("INSERT INTO `users`(`ID`, `username`, `password`, `email`, `rank`, `phone`,`created_by`,`college_id`, `membership`, `expire`, `status`, `key`, `used`, `otp`, `otp_verification_preference`, `banned_msg`, `grading_criteria`, `restrict_chat`, `datetime`) VALUES(NULL, '$username', '$shaPass', '$email', $role, Null,0,$college_id, 0, 0, 0, NULL, 0, Null,$fa_preference,Null,0,$restrict,now())");
                            $SQLinsert -> execute(); 

                        }else if( empty($email) && !empty($phone) )
                        {
                            $SQLinsert = $this->connection->prepare("INSERT INTO `users`(`ID`, `username`, `password`, `email`, `rank`, `phone`,`created_by`,`college_id`, `membership`, `expire`, `status`, `key`, `used`, `otp`, `otp_verification_preference`, `banned_msg`, `grading_criteria`, `restrict_chat`, `datetime`) VALUES(NULL, '$username', '$shaPass', Null, $role, '$phone',0,$college_id, 0, 0, 0, NULL, 0, Null,$fa_preference,Null,0,$restrict,now())"); 
                            $SQLinsert -> execute();                   
                        }

                        $lastID = $this->connection->lastInsertID();
                        $permission = 'none';
                        if($role==2 && !empty($assistant_permissions)){
                            $permission = implode(",", $assistant_permissions);
                        }
        
                        $insert = $this->connection->prepare("INSERT INTO `users_meta` (`user_id`, `meta_key`, `meta_value`) VALUES($lastID, 'assistant_permissions', '$permission')"); 
                        $insert -> execute(); 

                        $this->connection = null;
                        return [
                            'status' => true,	  
                            'message' => 'Data Inserted',	  
                            'data' => []
                        ];
                    }
                }
                
            }
            else
            {
                return [
                    'status' => false,	  
                    'err_msg' => 'Access Token Invalid' 
                   ];
            }
            
        } catch (PDOException $e) {
            return [
				'status' => false,	  
				'err_msg' => $e->getMessage()	  
			   ];
        }
    }

    public function createTeamCollegeWise($college_token,$name,$color)
    {
        try
        {  
            
            $dsn = "mysql:host=$this->host;dbname=$this->database";
            $this->connection = new PDO($dsn, $this->username, $this->password);
            // Set PDO to throw exceptions on error
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
            $sql = $this->connection->prepare("select id from college WHERE access_token = :access_token");
            $sql -> execute(array(":access_token" => $college_token));
            
             $result = $sql->fetchAll(PDO::FETCH_ASSOC);
            
            if( !empty($result) && $result[0] && $result[0]['id'])
            {
                $college_id = $result[0]['id'];
                try
                {
                    $team_code = $this->user->random_strings(20); 
                    
                    $sqlTeamCodeExists = $this->connection -> query("SELECT COUNT(id) FROM `teams` WHERE `team_code` LIKE '$team_code'");
                
                    if($sqlTeamCodeExists->fetchColumn())
                    {   
                        $team_code = $this->user->random_strings(20);
                    }

                    $this->connection->beginTransaction();

                    $SQLinsert = $this->connection -> prepare("INSERT INTO `teams` (`id`,`team_type`,`name`,`team_code`,`color_code`, `created_at`, `updated_at`) VALUES(NULL,:team_type,:team_name, :team_code,:color_code, :created_at,:updated_at)");

                    $SQLinsert -> execute(array(':team_type'=>3,':team_name' => $name, ':team_code' => $team_code,':color_code' => $color,'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')));

                    $statement2 = $this->connection -> prepare("INSERT INTO `team_status` (`id`,`team_id`,`team_code`,`college_id`,`status`) VALUES(NULL,:team_id,:team_code ,$college_id,1)");
                   

                   

                    $last_insert_id = $this->connection->lastInsertId();
                    

                    $statement2 -> execute(array(':team_id'=>$last_insert_id,':team_code'=>$team_code));


                    $this->connection->commit();

                    $this->connection = null;
                    return [
                        'status' => true,	  
                        'message' => 'Data Inserted',	  
                        'data' => []
                    ];
                }
                catch (\Exception $e) {                   
                    if ($this->connection->inTransaction()) {
                        $this->connection->rollback();
                        // If we got here our two data updates are not in the database
                    } 
                    return [
                        'status' => false,	  
                        'err_msg' => $e->getMessage()	  
                       ];
                }
               
                
            }
            else
            {
                return [
                    'status' => false,	  
                    'err_msg' => 'Access Token Invalid' 
                   ];
            }
            
        } catch (PDOException $e) {
            return [
				'status' => false,	  
				'err_msg' => $e->getMessage()	  
			   ];
        }
    }

    public function getAllSystemAsset($college_token)
    {
        try
        {  
            
            $dsn = "mysql:host=$this->host;dbname=$this->database";
            $this->connection = new PDO($dsn, $this->username, $this->password);
            // Set PDO to throw exceptions on error
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
            $sql = $this->connection->prepare("select id from college WHERE access_token = :access_token");
            $sql -> execute(array(":access_token" => $college_token));
            
             $result = $sql->fetchAll(PDO::FETCH_ASSOC);
            
            if( !empty($result) && $result[0] && $result[0]['id'])
            {
                $college_id = $result[0]['id'];
                $rawQuery = "SELECT asset.*,asset_group.name as assetgroup FROM `asset` INNER JOIN `asset_group` ON `asset_group`.id = `asset`.asset_group WHERE asset_group.college_id = $college_id  ORDER BY `asset`.`id` DESC";

                $statement = $this->connection->prepare($rawQuery);
                $statement->execute();
                // Close the connection
                $this->connection = null;
                return [
                    'status' => true,	  
                    'message' => 'Data fetched',	  
                    'data' => $statement->fetchAll(PDO::FETCH_ASSOC)
                   ];
            }
            else
            {
                return [
                    'status' => false,	  
                    'err_msg' => 'Access Token Invalid' 
                   ];
            }
            
        } catch (PDOException $e) {
            return [
				'status' => false,	  
				'err_msg' => $e->getMessage()	  
			   ];
        }
    }

    public function getAllGradingRubricCollegeWise($college_token)
    {
        try
        {  
            
            $dsn = "mysql:host=$this->host;dbname=$this->database";
            $this->connection = new PDO($dsn, $this->username, $this->password);
            // Set PDO to throw exceptions on error
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
            $sql = $this->connection->prepare("select id from college WHERE access_token = :access_token");
            $sql -> execute(array(":access_token" => $college_token));
            
             $result = $sql->fetchAll(PDO::FETCH_ASSOC);
            
            if( !empty($result) && $result[0] && $result[0]['id'])
            {
                $college_id = $result[0]['id'];
                $rawQuery = "SELECT grading_rubric_criteria.*,college.name FROM `grading_rubric_criteria` 
                // JOIN  college ON  college.id=grading_rubric_criteria.college_id WHERE grading_rubric_criteria.college_id =$college_id  ORDER BY `ID` DESC";

                $statement = $this->connection->prepare($rawQuery);
                $statement->execute();
                // Close the connection
                $this->connection = null;
                return [
                    'status' => true,	  
                    'message' => 'Data fetched',	  
                    'data' => $statement->fetchAll(PDO::FETCH_ASSOC)
                   ];
            }
            else
            {
                return [
                    'status' => false,	  
                    'err_msg' => 'Access Token Invalid' 
                   ];
            }
            
        } catch (PDOException $e) {
            return [
				'status' => false,	  
				'err_msg' => $e->getMessage()	  
			   ];
        }
    }

    public function getAllCollegeList()
    {
        try
        {  
            $dsn = "mysql:host=$this->host;dbname=$this->database";
            $this->connection = new PDO($dsn, $this->username, $this->password);
            // Set PDO to throw exceptions on error
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
            $rawQuery = "select id,name from college";

            $statement = $this->connection->prepare($rawQuery);
            $statement->execute();
            // Close the connection
            $this->connection = null;
            return [
                'status' => true,	  
                'message' => 'Data fetched',	  
                'data' => $statement->fetchAll(PDO::FETCH_ASSOC)
                ];
           
        } catch (PDOException $e) {
            return [
				'status' => false,	  
				'err_msg' => $e->getMessage()	  
			   ];
        }
    }

    public function getticketDetail($ticketId)
    {
        try
        {  
            
            $dsn = "mysql:host=$this->host;dbname=$this->database";
            $this->connection = new PDO($dsn, $this->username, $this->password);
            // Set PDO to throw exceptions on error
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
            $sql = $this->connection->prepare("select * from tickets where ticketID = :ticketId");
            $sql -> execute(array(":ticketId" => $ticketId));
            
             $result = $sql->fetchAll(PDO::FETCH_ASSOC);
            
            if( !empty($result) && $result[0])
            {
                $rawQuery = "SELECT (SELECT `username` FROM `users` WHERE `ID` = `userID`) AS username, `response`, `time` FROM `ticketResponses` WHERE `ticketID` = $ticketId ORDER BY `time` ASC";

                $statement = $this->connection->prepare($rawQuery);
                $statement->execute();
                // Close the connection
                $this->connection = null;
                return [
                    'status' => true,	  
                    'message' => 'Data fetched',	  
                    'data' => $statement->fetchAll(PDO::FETCH_ASSOC),
                    'ticketInfo' => $result
                   ];
            }
            else
            {
                return [
                    'status' => false,	  
                    'err_msg' => 'Ticket id Invalid' 
                   ];
            }
            
        } catch (PDOException $e) {
            return [
				'status' => false,	  
				'err_msg' => $e->getMessage()	  
			   ];
        }
    }

    public function getQuizPlayedList($quizeId)
    {
        try
        {  
            
            $dsn = "mysql:host=$this->host;dbname=$this->database";
            $this->connection = new PDO($dsn, $this->username, $this->password);
            // Set PDO to throw exceptions on error
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
             $sql = $this->connection->prepare("SELECT sum(is_correct) as correct, avg(is_correct) as avgcorrect,username,rank,previous_rank,teams.name as teamname,quiz_submission.created_at FROM quiz_submission JOIN users ON users.id=quiz_submission.created_by 
             JOIN teams ON teams.id=users.rank   where quize_id = :quize_id group by quiz_submission.created_by,username,rank,previous_rank,teams.name,quiz_submission.created_at");
             $sql -> execute(array(":quize_id" => $quizeId));
            
             $result = $sql->fetchAll(PDO::FETCH_ASSOC);
            
            if( !empty($result) && $result[0])
            {
                $this->connection = null;
                return [
                    'status' => true,	  
                    'message' => 'Data fetched',	  
                    'data' => $result
                   ];
            }
            else
            {
                return [
                    'status' => false,	  
                    'err_msg' => 'Quiz id is Invalid' 
                   ];
            }
            
        } catch (PDOException $e) {
            return [
				'status' => false,	  
				'err_msg' => $e->getMessage()	  
			   ];
        }
    }

    public function updateQuizStatus( $college_token, $quiz_id)
    {
        try
        {  
            
            $dsn = "mysql:host=$this->host;dbname=$this->database";
            $this->connection = new PDO($dsn, $this->username, $this->password);
            // Set PDO to throw exceptions on error
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $collegeSql = $this->connection->prepare("select id from college WHERE access_token = :access_token");
            $collegeSql -> execute(array(":access_token" => $college_token));
            $collegeResult = $collegeSql->fetchAll(PDO::FETCH_ASSOC);
            

             $sql = $this->connection->prepare("select * from quize where id=:id");
             $sql -> execute(array(":id" => $quiz_id));
            
             $result = $sql->fetchAll(PDO::FETCH_ASSOC);
            
            if( !empty($result) && $result[0] && !empty($collegeResult) && $collegeResult[0] )
            {
                $college_id = $collegeResult[0]['id'];
                
                $updateSql = $this->connection->prepare("UPDATE quize SET status = (1-status) where id = :quiz_id AND college_id=:college_id");
                $updateSql -> execute(array(":quiz_id" => $quiz_id, ":college_id" => $college_id));

                $this->connection = null;
                return [
                    'status' => true,	  
                    'message' => 'Data Updated Successfully',	  
                    'data' => $result
                   ];
            }
            else
            {
                return [
                    'status' => false,	  
                    'err_msg' => 'Quiz id is Invalid' 
                   ];
            }
            
        } catch (PDOException $e) {
            return [
				'status' => false,	  
				'err_msg' => $e->getMessage()	  
			   ];
        }
    }

    public function openTicket( $ticketId )
    {
        try
        {  
            
            $dsn = "mysql:host=$this->host;dbname=$this->database";
            $this->connection = new PDO($dsn, $this->username, $this->password);
            // Set PDO to throw exceptions on error
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $updateSql = $this->connection->prepare("UPDATE tickets SET ticketStatus = 1 where ticketID = :ticketID ");
            $updateSql -> execute(array(":ticketID" => $ticketId));

            $this->connection = null;
            return [
                'status' => true,	  
                'message' => 'Data Updated Successfully'
                ];
           
        } catch (PDOException $e) {
            return [
				'status' => false,	  
				'err_msg' => $e->getMessage()	  
			   ];
        }
    }

    public function closeTicket( $ticketId )
    {
        try
        {  
            
            $dsn = "mysql:host=$this->host;dbname=$this->database";
            $this->connection = new PDO($dsn, $this->username, $this->password);
            // Set PDO to throw exceptions on error
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $updateSql = $this->connection->prepare("UPDATE tickets SET ticketStatus = 0 where ticketID = :ticketID ");
            $updateSql -> execute(array(":ticketID" => $ticketId));

            $this->connection = null;
            return [
                'status' => true,	  
                'message' => 'Data Updated Successfully'
                ];
           
        } catch (PDOException $e) {
            return [
				'status' => false,	  
				'err_msg' => $e->getMessage()	  
			   ];
        }
    }

    public function ticketResponse( $college_token, $ticketID, $userID, $response, $time )
    {
        try
        {  
            
            $dsn = "mysql:host=$this->host;dbname=$this->database";
            $this->connection = new PDO($dsn, $this->username, $this->password);
            // Set PDO to throw exceptions on error
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
            $sql = $this->connection->prepare("select id from college WHERE access_token = :access_token");
            $sql -> execute(array(":access_token" => $college_token));
            
             $result = $sql->fetchAll(PDO::FETCH_ASSOC);
            
            if( !empty($result) && $result[0] && $result[0]['id'])
            {
                $college_id = $result[0]['id'];
                try
                {
                    $SQLinsert = $this->connection -> prepare("INSERT INTO `ticketResponses` (`college_id`,`ticketID`,`userID`,`response`,`time`) VALUES(:college_id,:ticketID, :userID,:response, :ttime)");

                    $SQLinsert -> execute(array(
                        ':college_id'=>$college_id,
                        ':ticketID'=>$ticketID,
                        ':userID'=>$userID,
                        ':response'=>$response,
                        ':ttime'=>$time,
                    ));

                    $this->connection = null;
                    return [
                        'status' => true,	  
                        'message' => 'Data Inserted',	  
                        'data' => []
                    ];
                }
                catch (\Exception $e) {   
                    return [
                        'status' => false,	  
                        'err_msg' => $e->getMessage()	  
                       ];
                }
               
                
            }
            else
            {
                return [
                    'status' => false,	  
                    'err_msg' => 'Access Token Invalid' 
                   ];
            }
            
        } catch (PDOException $e) {
            return [
				'status' => false,	  
				'err_msg' => $e->getMessage()	  
			   ];
        }
    }

    public function editUser( $college_token, $edit )
    {
        try
        {  
            
            $dsn = "mysql:host=$this->host;dbname=$this->database";
            $this->connection = new PDO($dsn, $this->username, $this->password);
            // Set PDO to throw exceptions on error
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
            $sql = $this->connection->prepare("select id from college WHERE access_token = :access_token");
            $sql -> execute(array(":access_token" => $college_token));
            
             $result = $sql->fetchAll(PDO::FETCH_ASSOC);
            
            if( !empty($result) && $result[0] && $result[0]['id'])
            {
                $college_id = $result[0]['id'];
                try
                {
                    // get editable user data
                    $sql = $this->connection->prepare("SELECT * FROM `users` WHERE `ID` = :id");
                    $sql -> execute(array(":id" => $edit));
                    $data = $sql->fetchAll(PDO::FETCH_ASSOC);
                    $user_id = ($data[0]['ID'])?$data[0]['ID']:0;
                    // get teamList
                    $teamList = $this->connection->prepare("SELECT teams.* FROM `teams` INNER JOIN team_status ON teams.id = team_status.team_id  WHERE teams.name != 'Admin' AND college_id = $college_id AND status = 1   ORDER BY id ASC");

                    $teamList -> execute();
                    $teamList = $teamList->fetchAll(PDO::FETCH_ASSOC);

                    $assistant_data = $this->connection->prepare("select * from users_meta WHERE user_id= ".$user_id." AND meta_key='assistant_permissions'");

                    $assistant_data -> execute();
                    $assistant_data = $assistant_data->fetchAll(PDO::FETCH_ASSOC);

                    $this->connection = null;
                    return [
                        'status' => true,	  
                        'message' => 'Data Inserted',	  
                        'data' => $data,
                        'teamList' => $teamList,
                        'assistant_data' => $assistant_data,
                        'user_id' => $user_id
                    ];
                }
                catch (\Exception $e) {   
                    return [
                        'status' => false,	  
                        'err_msg' => $e->getMessage()	  
                       ];
                }
               
                
            }
            else
            {
                return [
                    'status' => false,	  
                    'err_msg' => 'Access Token Invalid' 
                   ];
            }
            
        } catch (PDOException $e) {
            return [
				'status' => false,	  
				'err_msg' => $e->getMessage()	  
			   ];
        }
    }

    public function editRubric( $college_token, $edit )
    {
        try
        {  
            
            $dsn = "mysql:host=$this->host;dbname=$this->database";
            $this->connection = new PDO($dsn, $this->username, $this->password);
            // Set PDO to throw exceptions on error
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
            $sql = $this->connection->prepare("select id from college WHERE access_token = :access_token");
            $sql -> execute(array(":access_token" => $college_token));
            
             $result = $sql->fetchAll(PDO::FETCH_ASSOC);
            
            if( !empty($result) && $result[0] && $result[0]['id'])
            {
                $college_id = $result[0]['id'];
                try
                {
                    // get college list
                    $collegeList = $this->connection->prepare("select id,name from college");
                    $collegeList -> execute();
                    $collegeList = $collegeList->fetchAll(PDO::FETCH_ASSOC);

                    // get grading rubric
                    $data = $this->connection->prepare("SELECT * FROM `grading_rubric_criteria` WHERE `id` = :id");

                    $data -> execute(array(':id'=>$edit));
                    $data = $data->fetchAll(PDO::FETCH_ASSOC);

                    $this->connection = null;
                    return [
                        'status' => true,	  
                        'message' => 'Data Inserted',	  
                        'data' => $data,
                        'collegeList' => $collegeList
                    ];
                }
                catch (\Exception $e) {   
                    return [
                        'status' => false,	  
                        'err_msg' => $e->getMessage()	  
                       ];
                }
               
                
            }
            else
            {
                return [
                    'status' => false,	  
                    'err_msg' => 'Access Token Invalid' 
                   ];
            }
            
        } catch (PDOException $e) {
            return [
				'status' => false,	  
				'err_msg' => $e->getMessage()	  
			   ];
        }
    }

    public function updateRubric( $college_token, $update, $title, $detail, $purpleteam_grade, $redteam_grade, $blueteam_grade, $assigned_user )
    {
        try
        {  
            
            $dsn = "mysql:host=$this->host;dbname=$this->database";
            $this->connection = new PDO($dsn, $this->username, $this->password);
            // Set PDO to throw exceptions on error
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $collegeSql = $this->connection->prepare("select id from college WHERE access_token = :access_token");
            $collegeSql -> execute(array(":access_token" => $college_token));
            $collegeResult = $collegeSql->fetchAll(PDO::FETCH_ASSOC);
            

            
            if( !empty($collegeResult) && $collegeResult[0] )
            {
                $college_id = $collegeResult[0]['id'];
                
                $updateSql = $this->connection->prepare("UPDATE grading_rubric_criteria SET 
                college_id = :college_id,
                title = :title,
                detail = :detail,
                purpleteam_grade = :purpleteam_grade,
                redteam_grade = :redteam_grade,
                blueteam_grade = :blueteam_grade,
                assigned_user = :assigned_user
                where id = :updateid");

                $updateSql -> execute(array(
                    ":college_id" => $college_id, 
                    ":title" => $title,
                    ":detail" => $detail,
                    ":purpleteam_grade" => $purpleteam_grade,
                    ":redteam_grade" => $redteam_grade,
                    ":blueteam_grade" => $blueteam_grade,
                    ":assigned_user" => $assigned_user,
                    ":updateid" => $update
                ));

                $this->connection = null;
                return [
                    'status' => true,	  
                    'message' => 'Data Updated Successfully',	  
                    'data' => []
                   ];
            }
            else
            {
                return [
                    'status' => false,	  
                    'err_msg' => 'Quiz id is Invalid' 
                   ];
            }
            
        } catch (PDOException $e) {
            return [
				'status' => false,	  
				'err_msg' => $e->getMessage()	  
			   ];
        
        }
    }

    public function createRubric($college_token,$title,$detail,$purpleteam_grade,$redteam_grade,$blueteam_grade,$assigned_user)
    {
        try
        {  
            
            $dsn = "mysql:host=$this->host;dbname=$this->database";
            $this->connection = new PDO($dsn, $this->username, $this->password);
            // Set PDO to throw exceptions on error
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
            $sql = $this->connection->prepare("select id from college WHERE access_token = :access_token");
            $sql -> execute(array(":access_token" => $college_token));
            
             $result = $sql->fetchAll(PDO::FETCH_ASSOC);
            
            if( !empty($result) && $result[0] && $result[0]['id'])
            {
                $college_id = $result[0]['id'];
              
                $SQLinsert = $this->connection->prepare("INSERT INTO `grading_rubric_criteria`(`college_id`, `title`, `detail`, `purpleteam_grade`, `redteam_grade`,`blueteam_grade`,`assigned_user`) VALUES(:college_id, :title, :detail, :purpleteam_grade, :redteam_grade, :blueteam_grade, :assigned_user )"); 

                $SQLinsert -> execute( array(
                    "college_id" => $college_id,
                    "title" => $title,
                    "detail" => $detail,
                    "purpleteam_grade" => $purpleteam_grade,
                    "redteam_grade" => $redteam_grade,
                    "blueteam_grade" => $blueteam_grade,
                    "assigned_user" => $assigned_user
                ) );   

                $this->connection = null;
                return [
                    'status' => true,	  
                    'message' => 'Data Inserted',	  
                    'data' => []
                ];
            }
            else
            {
                return [
                    'status' => false,	  
                    'err_msg' => 'Access Token Invalid' 
                   ];
            }
            
        } catch (PDOException $e) {
            return [
				'status' => false,	  
				'err_msg' => $e->getMessage()	  
			   ];
        }
    }

    public function updateUser($college_token,$username,$phone,$email,$password,$role,$fa_preference,$restrict,$assistant_permissions, $update_id)
    {
        try
        {  
            
            $dsn = "mysql:host=$this->host;dbname=$this->database";
            $this->connection = new PDO($dsn, $this->username, $this->password);
            // Set PDO to throw exceptions on error
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
            $sql = $this->connection->prepare("select id from college WHERE access_token = :access_token");
            $sql -> execute(array(":access_token" => $college_token));
            
             $result = $sql->fetchAll(PDO::FETCH_ASSOC);
            
            if( !empty($result) && $result[0] && $result[0]['id'])
            {
                $college_id = $result[0]['id'];

                $checkUsernameExists = $this->connection->prepare("SELECT ID FROM `users` WHERE `username` = :username AND ID != :update_id");
                $checkUsernameExists -> execute(array(":username" => $username , ":update_id" => $update_id));
                $checkUsernameExists = $checkUsernameExists->fetchAll(PDO::FETCH_ASSOC);

                if( !empty($checkUsernameExists) && $checkUsernameExists[0] && $checkUsernameExists[0]['ID'])
                {
                    // Close the connection
                    $this->connection = null;
                    return [
                        'status' => false,	  
                        'message' => 'The username is already in use, please use a different username.',
                        'err_msg' => 'The username is already in use, please use a different username.',
                    ];
                }
                else
                {
                    $checkPhoneExists = $this->connection->prepare("SELECT * FROM `users` WHERE `phone` = :phone AND ID != :update_id");
                    $checkPhoneExists -> execute(array(":phone" => $phone , ":update_id" => $update_id));
                    $checkPhoneExists = $checkPhoneExists->fetchAll(PDO::FETCH_ASSOC);

                    if( !empty($checkPhoneExists) && $checkPhoneExists[0] && $checkPhoneExists[0]['ID'])
                    {
                        // Close the connection
                        $this->connection = null;
                        return [
                            'status' => false,	  
                            'message' => 'The phone is already in use, please use a different phone.',
                            'err_msg' => 'The phone is already in use, please use a different phone.',
                        ];
                    }
                    else
                    {
                        $checkEmailExists = $this->connection->prepare("SELECT * FROM `users` WHERE `email` = :email AND ID != :update_id");
                        $checkEmailExists -> execute(array(":email" => $email , ":update_id" => $update_id));
                        $checkEmailExists = $checkEmailExists->fetchAll(PDO::FETCH_ASSOC);

                        if( !empty($checkEmailExists) && $checkEmailExists[0] && $checkEmailExists[0]['ID'] )
                        {
                            $this->connection = null;
                            return [
                                'status' => false,	  
                                'message' => 'The email is already in use, please use a different email.',
                                'err_msg' => 'The email is already in use, please use a different email.',
                            ];
                        }
                        else
                        {
                            $shaPass = SHA1($password);
                            if( !empty($email) && !empty($phone))
                            {
                                $SQLupdate = $this->connection->prepare("UPDATE users SET `username` = '$username',`email` ='$email',`rank`=$role,`phone`='$phone',`otp_verification_preference` = '$fa_preference',`restrict_chat`=$restrict  WHERE ID = $update_id");   

                                $SQLupdate -> execute();              

                            }else if( !empty($email) && empty($phone) )
                            { 
                                $SQLupdate = $this->connection->prepare("UPDATE users SET `username` = '$username',`email` ='$email',`rank`=$role,`otp_verification_preference` = '$fa_preference',`restrict_chat`=$restrict  WHERE ID = $update_id");

                                $SQLupdate -> execute(); 

                            }else if( empty($email) && !empty($phone) )
                            {
                                $SQLinsert = $this->connection->prepare("UPDATE users SET `username` = '$username',`rank`=$role,`phone`='$phone',`otp_verification_preference` = '$fa_preference',`restrict_chat`=$restrict  WHERE ID = $update_id"); 
                                $SQLinsert -> execute();                   
                            }

                            $permission = 'none';
                            if($role==2 && !empty($assistant_permissions)){
                                $permission = implode(",", $assistant_permissions);
                            }

                            $lastID = $this->connection->lastInsertID();
                           
                            if($role==2 && !empty($assistant_permissions)){
                                $permission = implode(",", $assistant_permissions);
                            }

                            $checkMeta = $this->connection->prepare("SELECT * FROM `users_meta` where `user_id` = $update_id AND meta_key ='assistant_permissions'"); 
                            $checkMeta -> execute(); 

                            if( !empty($checkMeta) )
                            {
                                $insert = $this->connection->prepare("UPDATE `users_meta` set  `meta_value`='$permission' where `user_id` = $update_id AND meta_key ='assistant_permissions'"); 
                                $insert -> execute(); 

                            }else{
                                $insert = $this->connection->prepare("INSERT INTO `users_meta` (`user_id`, `meta_key`, `meta_value`) VALUES($update_id, 'assistant_permissions', '$permission')"); 
                                $insert -> execute(); 

                            }

                            $this->connection = null;
                            return [
                                'status' => true,	  
                                'message' => 'Data Inserted',	  
                                'data' => []
                            ];
                        }
                    }
                }
                
            }
            else
            {
                return [
                    'status' => false,	  
                    'err_msg' => 'Access Token Invalid' 
                   ];
            }
            
        } catch (PDOException $e) {
            return [
				'status' => false,	  
				'err_msg' => $e->getMessage()	  
			   ];
        }
    }

    public function updateTeam($college_token,$name,$color, $update_id)
    {
        try
        {  
            
            $dsn = "mysql:host=$this->host;dbname=$this->database";
            $this->connection = new PDO($dsn, $this->username, $this->password);
            // Set PDO to throw exceptions on error
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $updateSql = $this->connection->prepare("UPDATE teams SET 
                name = :name,
                color_code = :color_code
                where id = :update_id");

                $updateSql -> execute(array(
                    ":name" => $name, 
                    ":color_code" => $color,
                    ":update_id" => $update_id
                ));

                $this->connection = null;
                return [
                    'status' => true,	  
                    'message' => 'Data Updated Successfully',	  
                    'data' => []
                   ];
            
        } catch (PDOException $e) {
            return [
				'status' => false,	  
				'err_msg' => $e->getMessage()	  
			   ];
        
        }
    }

    public function userDeleteById( $user_id )
    {
        try
        {  
            $dsn = "mysql:host=$this->host;dbname=$this->database";
            $this->connection = new PDO($dsn, $this->username, $this->password);
            // Set PDO to throw exceptions on error
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
           
            $sql = $this->connection->prepare("SELECT `username` FROM `users` WHERE `ID` = :id");
            $sql -> execute(array(":id" => $user_id));

             $result = $sql->fetchAll(PDO::FETCH_ASSOC);
            
            if( !empty($result) && $result[0] && $result[0]['username'])
            {
                $getUsername = $result[0]['username'];

                $this->connection->beginTransaction();

                $updateSql = $this->connection->prepare("UPDATE news SET 
                created_by = :created_by
                where userID = :userID");

                $updateSql -> execute(array(
                    ":created_by" => $getUsername, 
                    ":userID" => $user_id
                ));

                $updateSql1 = $this->connection->prepare("UPDATE users SET 
                status = 2
                where ID = :ID");

                $updateSql1 -> execute(array(
                    ":ID" => $user_id
                ));

                $this->connection->commit();

                $this->connection = null;

                return [
                    'status' => true,	  
                    'message' => 'Data Deleted Successfully',	  
                    'data' => []
                   ];
            }
            else
            {
                return [
                    'status' => false,	  
                    'err_msg' => 'Something went wrong' 
                   ];
            }
            
        } catch (PDOException $e) {
            if ($this->connection->inTransaction()) {
                $this->connection->rollback();
                // If we got here our two data updates are not in the database
            } 
            return [
                'status' => false,	  
                'err_msg' => $e->getMessage()	  
               ];
        
        }
    }

    public function ticketDeleteById( $ticketID )
    {
        try
        {  
            $dsn = "mysql:host=$this->host;dbname=$this->database";
            $this->connection = new PDO($dsn, $this->username, $this->password);
            // Set PDO to throw exceptions on error
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
           
            $this->connection->beginTransaction();

            $deleteSql = $this->connection->prepare("DELETE FROM tickets
            where ticketID = :ticketID");

            $deleteSql -> execute(array(
                ":ticketID" => $ticketID
            ));

            $deleteSql1 = $this->connection->prepare("DELETE FROM  ticketResponses
            where ticketID = :ticketID");

            $deleteSql1 -> execute(array(
                ":ticketID" => $ticketID
            ));

            $this->connection->commit();

            $this->connection = null;

            return [
                'status' => true,	  
                'message' => 'Data Deleted Successfully',	  
                'data' => []
                ];
           
            
        } catch (PDOException $e) {
            if ($this->connection->inTransaction()) {
                $this->connection->rollback();
                // If we got here our two data updates are not in the database
            } 
            return [
                'status' => false,	  
                'err_msg' => $e->getMessage()	  
               ];
        
        }
    }

    public function deleteTenant( $token )
    {
        try
        {  
            $dsn = "mysql:host=$this->host;dbname=$this->database";
            $this->connection = new PDO($dsn, $this->username, $this->password);
            // Set PDO to throw exceptions on error
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
           
            $this->connection->beginTransaction();

            $collegeIdSql = $this->connection->prepare("SELECT id from college WHERE `access_token` = :token");
            $collegeIdSql -> execute(array(":token" => $token));

             $result = $collegeIdSql->fetchAll(PDO::FETCH_ASSOC);
            
            if( !empty($result) && $result[0] && $result[0]['id'])
            {
                $college_id = $result[0]['id'];

                $statement2 = $this->connection->prepare("UPDATE college SET status = 2
                where access_token = :access_token");
                $statement2 -> execute(array(
                    ":access_token" => $token
                ));

                $userStatus =  DB::connection('mysql2')->table('users')->where('college_id',$college_id)->update(['status'=>2]);  

                $userStatus = $this->connection->prepare("UPDATE users SET status = 2
                where college_id = $college_id");
                
                $this->connection->commit();
    
                $this->connection = null;
    
                return [
                    'status' => true,	  
                    'message' => 'Data Deleted Successfully',	  
                    'data' => []
                    ];
            }
            else
            {
                return [
                    'status' => false,	  
                    'err_msg' => 'Access Token Invalid' 
                   ];
            }
            
        } catch (PDOException $e) {
            if ($this->connection->inTransaction()) {
                $this->connection->rollback();
                // If we got here our two data updates are not in the database
            } 
            return [
                'status' => false,	  
                'err_msg' => $e->getMessage()	  
               ];
        
        }
    }
}