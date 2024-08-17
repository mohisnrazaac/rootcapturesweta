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
	$data = json_decode(file_get_contents('php://input'), true);

	$sql = $odb -> prepare("SELECT * FROM `api_management` WHERE `api_function` = :api_function");
	$sql -> execute(array(":api_function" => "Add The Grade(s)"));
	$row = $sql -> fetch(PDO::FETCH_ASSOC);
	$user = $data['user_id'];
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
				if($data['grade']=='' || $data['grade_value']==''){
					$response['type'] = "error";
					$response['msg'] = "Please enter all required fileds.";
				}else{
					$SQL = $odb -> prepare("SELECT * FROM `grade` WHERE `grade_value` = :grade_value");
					$SQL -> execute(array(':grade_value' => $data['grade_value']));
					$grade = $SQL -> fetch(PDO::FETCH_ASSOC);	
					if($grade){
						$response['type'] = "error";
						$response['msg'] = "Grade value already exist in grade list.";
					}else{
						$SQLinsert = $odb -> prepare("INSERT INTO `grade` (grade, grade_value)  VALUES(:grade, :grade_value)");
	        			$SQLinsert -> execute(array(':grade' => $data['grade'], ':grade_value' => $data['grade_value']));
	        			$response['type'] = "success";
						$response['msg'] = "Grade added successfully";
						$response['grade_id'] = $odb->lastInsertId();
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