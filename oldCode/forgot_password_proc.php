<?php
ob_start();
require 'includes/db.php';
require 'includes/init.php';

// get email 
$email = $_GET["email"];

// check if email exist
$stmp = $odb -> prepare("SELECT id FROM `users` WHERE `email` = :email");
$stmp -> execute(array(':email' => $email));
$id = $stmp -> fetchColumn(0);

$ret = array();

if( $id == false )
{
	$ret['code'] = 404;
	$ret['message'] = "Email does not exist";
		
	echo json_encode($ret);
	return;	
}

// if  email exist, genereate random token for reset password
$token = bin2hex(random_bytes(20));


// insert this token to users
$stmp = $odb -> prepare("UPDATE users SET `key` = :key, used = 0 WHERE id = :id");
$stmp -> execute(array(':key' => $token, ':id' => $id));

// generate reset password html page
$url = "https://rootcapture.com/resetpass.php?id=$id&token=$token";
$img = "https://rootcapture.com/assets/img/RootCaptureResizeSmall.png";
$rcurl = "https://rootcapture.com/";
$html = "<center><img src='$img'/><p>Hello there,</p><p>We have received a request to reset your rootCapture Password, please click <a href=\"$url\">here</a> in order to reset your rootCapture Password.</p><p>Sincerely,</p><p>The <a href=\"$rcurl\">rootCapture</a> Support Team</p>";

$headers  = "From: The rootCapture Support Team <support@rootcapture.com>\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
mail($email, "Resetting Your rootCapture Password", $html, $headers);


$ret["code"] = 200;
$ret["message"] = "Please check your email inbox to reset your password!";
$ret["email"] = $email;
$ret['id'] = $id;
$ret['token'] = $token;
$ret['html'] = $html;
$ret['url'] = $url;

echo json_encode($ret);
?>