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
	
	$sql = $odb -> prepare("SELECT * FROM `api_management` WHERE `api_function` = :api_function");
	$sql -> execute(array(":api_function" => "Get Grading Rubric Criterion"));
	$row = $sql -> fetch(PDO::FETCH_ASSOC);
	$user_id = $_GET['user_id'];
	$rubric_id = $_GET['rubric_id'];
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
				if($rubric_id!=''){
					$SQL = $odb -> prepare("SELECT * FROM `grading_rubric_criteria` WHERE `id` = :id");
					$SQL -> execute(array(':id' => $rubric_id));
					$rubric = $SQL -> fetch(PDO::FETCH_ASSOC);	

					$SQL1 = $odb -> prepare("SELECT * FROM `grading_rubric_criteria_user` WHERE `grading_rubric_criteria_id` = :id");
					$SQL1 -> execute(array(':id' => $rubric_id));
					$rubric_user = $SQL1 -> fetchAll(PDO::FETCH_ASSOC);
					$assign_users = array();	
					if(!empty($rubric_user)){
						foreach ($rubric_user as $key => $value) {
							$userInfo = $user->userInfo($odb,$value['user_id']);
							$assign_users[$key]['user_id'] = $value['user_id'];
							$assign_users[$key]['username'] = $userInfo['username'];
							$assign_users[$key]['grade'] = $value['grade'];
						}
					}
					if($rubric){
						$rubric['assigned_user'] = $assign_users;
						$response['type'] = "success";
						$response['msg'] = "Grading Rubric Criterion available.";
						$response['data'] = $rubric;
					}else{
						$response['type'] = "error";
						$response['msg'] = "Grading Rubric Criterion not available.";
					}
				}else{
					$response['type'] = "error";
					$response['msg'] = "Grading Rubric Criterion id not empty.";
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