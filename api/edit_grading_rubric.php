<?php
ob_start();
require_once '../includes/db.php';
require_once '../includes/init.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$headers = null;
$token = '';
if (isset($_SERVER['Authorization'])) {
    $headers = trim($_SERVER["Authorization"]);
}
else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
    $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
} elseif (function_exists('apache_request_headers')) {
    $requestHeaders = apache_request_headers();
    // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
    $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
    //print_r($requestHeaders);
    if (isset($requestHeaders['Authorization'])) {
        $headers = trim($requestHeaders['Authorization']);
    }
}
if (!empty($headers)) {
    if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
        $token = $matches[1];
    }
}

if($token==''){
	$type = "error";
	$msg = "Please enter a api key.";
}else{
	$data = json_decode(file_get_contents('php://input'), true);

	$sql = $odb -> prepare("SELECT * FROM `api_management` WHERE `api_function` = :api_function");
	$sql -> execute(array(":api_function" => "Add Grading Rubric Criterion"));
	$row = $sql -> fetch(PDO::FETCH_ASSOC);
	$user_id = $data['user_id'];
	$response = array();
	if($row){
		if($row['api_key']!=$token){
			$response['type'] = "error";
			$response['msg'] = "Please provide a valid api key.";
		}elseif($user_id==''){
			$response['type'] = "error";
			$response['msg'] = "Please provide user id.";
		}else{
			$api_members = explode(",", $row['api_members']);
			if(in_array($user_id, $api_members)){
				$title = $data['title'];
		        $detail = $data['description'];
		        $redTeam = $data['red_team'];
		        $purpleTeam = $data['purple_team'];
		        $blueTeam = $data['blue_team'];
		        $assign_user = $data['assign_user']; 
		        $grade = false;
		        if( $redTeam != '' ){ $grade = true;} 
		        else if( $purpleTeam != '' ) { $grade = true; }
		        else if( $blueTeam != '' ) { $grade = true; }

		        if(!empty($assign_user)){  $grade = true; }
		        

				if(!$grade || $title=='' || $detail=='' ){
					$response['type'] = "error";
					$response['msg'] = "Please enter all required fileds.";
				}else{

					$SQL = $odb -> prepare("SELECT * FROM `grading_rubric_criteria` WHERE `id` = :id");
					$SQL -> execute(array(':id' => $data['rubric_id']));
					$rubric = $SQL -> fetch(PDO::FETCH_ASSOC);
					if($rubric){
						$SQLupdate = $odb -> prepare("UPDATE grading_rubric_criteria SET `title` = :title, `detail` = :detail, `redteam_grade` = :redteam_grade, `blueteam_grade` = :blueteam_grade, `purpleteam_grade` = :purpleteam_grade WHERE id = :id");
			            $SQLupdate -> execute(array(':title' => $title, ':detail' => $detail, ':redteam_grade' => $redTeam, ':blueteam_grade' => $blueTeam, ':purpleteam_grade' => $purpleTeam, ':id' => $data['rubric_id']));

			            $SQL = $odb -> prepare("DELETE FROM `grading_rubric_criteria_user` WHERE `grading_rubric_criteria_id` = :id");
			            $SQL -> execute(array(':id' => $data['rubric_id']));

			            $assign_ids = array();
			            foreach($assign_user as $key => $assignGradeUserV){
			            	$assign_ids[] = $assignGradeUserV['user_id'];
			                $SQLinsert = $odb -> prepare("INSERT INTO `grading_rubric_criteria_user` (grading_rubric_criteria_id, user_id, grade)  VALUES(:last_id, :user_id, :grade)");
			                $SQLinsert -> execute(array(':last_id' => $data['rubric_id'], ':user_id' => $assignGradeUserV['user_id'], ':grade' => $assignGradeUserV['grade']));
			            }
			            if(!empty($assign_ids)){
			            	$SQLupdate1 = $odb -> prepare("UPDATE grading_rubric_criteria SET `assigned_user` = :assigned_user WHERE id = :id");
	            			$SQLupdate1 -> execute(array(':assigned_user' => implode(',',$assign_ids), ':id' => $data['rubric_id']));
			            }
			            $response['type'] = "success";
						$response['msg'] = "Grading Rubic update successfully";
						$response['rubic_id'] = $data['rubric_id'];
					}else{
						$response['type'] = "error";
						$response['msg'] = "Grade rubric id not available.";
					}				
					
				}
			}else{
				$response['type'] = "error";
				$response['msg'] = "User don't have permission to access.";
			}
		}
		
	}else{
		$response['type'] = "error";
		$response['msg'] = "Api function is not available";
	}
}

echo json_encode($response); die;