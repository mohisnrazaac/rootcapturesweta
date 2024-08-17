<?php
ob_start();
require 'includes/db.php';
require 'includes/init.php';

// get id 
$id = $_POST["id"];
$token = $_POST["token"];
$password = $_POST["password"];

// check if id exist
$stmp = $odb -> prepare("SELECT id, `key`, used FROM `users` WHERE `id` = :id");
$stmp -> execute(array(':id' => $id));
$row = $stmp -> fetch(PDO::FETCH_ASSOC);

if( !$row  )
{
	$ret['code'] = 404;
	$ret['message'] = "The URL is invalid! Please try again.";
		
	echo json_encode($ret);
	return;	
}
	
				  
$titleShow = $show['title'];
$detailShow = $show['detail'];
$rowID = $show['ID'];
					
$id = $row['id'];
$key = $row['key'];
$used = $row['used'];

$ret = array();

if( $id == false )
{
	$ret['code'] = 404;
	$ret['message'] = "The URL is invalid! Please try again.";
		
	echo json_encode($ret);
	return;	
}

if( $key != $token || empty($token)  )
{
	$ret['code'] = 404;
	$ret['message'] = "Your password reset token is invalid! Please reset your password again.";
	$ret['token'] = $token;
	$ret['key'] = $key;
		
	echo json_encode($ret);
	return;	
}

if( $used != 0  )
{
	$ret['code'] = 405;
	$ret['message'] = "Your password reset token has already been used! Please reset your password again.";
	echo json_encode($ret);
	return;	
}

// update password
$stmp = $odb -> prepare("UPDATE users SET `password` = :password, used = 1 WHERE id = :id");
$stmp -> execute(array(':password' => SHA1($password), ':id' => $id));

$ret["code"] = 200;
$ret["message"] = "Your password has been successfully changed! You are now being redirected to the rootCapture Login Page!";
$ret['id'] = $id;

echo json_encode($ret);
?>