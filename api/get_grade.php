<?php
ob_start();
require_once '../includes/db.php';
require_once '../includes/init.php';


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
	$sql -> execute(array(":api_function" => "Get The Grade(s)"));
	$row = $sql -> fetch(PDO::FETCH_ASSOC);
	$user = $_GET['user_id'];
	$grade_id = $_GET['grade_id'];
	$response = array();
	if($row){
		if($row['api_key']!=$token){
			$response['type'] = "error";
			$response['msg'] = "Please provide a valid api key.";
		}elseif($user==''){
			$response['type'] = "error";
			$response['msg'] = "Please provide user id.";
		}else{
			$api_members = explode(",", $row['api_members']);

			if(in_array($user, $api_members)){
				if($grade_id!=''){
					$SQL = $odb -> prepare("SELECT * FROM `grade` WHERE `id` = :id");
					$SQL -> execute(array(':id' => $grade_id));
					$grade = $SQL -> fetch(PDO::FETCH_ASSOC);	
					if($grade){
						$response['type'] = "success";
						$response['msg'] = "Grade available.";
						$response['data'] = $grade;
					}else{
						$response['type'] = "error";
						$response['msg'] = "Grade not available.";
					}
				}else{
					$response['type'] = "error";
					$response['msg'] = "Grade id not empty.";
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