<?php
define('DIRECT', TRUE); 

function getRealIpAddr()
{
	if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
	{
		$ip=$_SERVER['HTTP_CLIENT_IP'];
	}
	elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
	{
		$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
	}
	else
	{
		$ip=$_SERVER['REMOTE_ADDR'];
	}
	return $ip;
}

$_SERVER['REMOTE_ADDR'] = getRealIpAddr();
require 'functions.php'; 
require 'constant.php';
$user = new user;
$stats = new stats;
$title_prefix = "rootCapture - ";

/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/



if (basename($_SERVER['PHP_SELF']) != 'lock.php' && $user -> isLocked()){
	header('location: lock.php');
	die();
}

$currentPage= $_SERVER['SCRIPT_NAME'];


$permission_array = array("/admin/create-range-administration.php"=>'dashboard',"/admin/news.php"=>'announcement',"/admin/addnews.php"=>'announcement',"/admin/manage_team.php"=>'team','/admin/manage_team.php'=>'team','/admin/asset_group.php'=>'system_group','/admin/create_group.php'=>'system_group','/admin/manage_assets.php'=>'system','/admin/create_asset.php'=>'system','/admin/adminu.php'=>'user','/admin/addusers.php'=>'user','/admin/manage_apis.php'=>'api','/admin/create_apis.php'=>'api','/admin/manage-grading-rubric.php'=>'rubric','/admin/add-grading-rubric'=>'rubric');

if (!($user -> LoggedAval($odb)) && strpos($_SERVER['REQUEST_URI'], "login.php") === false && strpos($_SERVER['REQUEST_URI'], "verification.php") === false && strpos($_SERVER['REQUEST_URI'], "resetpass.php") === false && strpos($_SERVER['REQUEST_URI'], "resend_verification.php") === false  && strpos($_SERVER['REQUEST_URI'], "forpass.php") === false && strpos($_SERVER['REQUEST_URI'], "forgot_password_proc.php") === false && strpos($_SERVER['REQUEST_URI'], "register.php") === false && strpos($_SERVER['REQUEST_URI'], "register-college.php") === false && strpos($_SERVER['REQUEST_URI'], "/api/") === false && strpos($_SERVER['REQUEST_URI'], "update-phone.php") === false && strpos($_SERVER['REQUEST_URI'], "register-college-old.php") === false &&  strpos($_SERVER['REQUEST_URI'], "forgot-password-verify.php") === false){  
	if(isset($_SESSION['username'])){
		unset($_SESSION['username']);
	}

	if(isset($_SESSION['ID'])){
		unset($_SESSION['ID']);
	}
	
    header('location: /login.php');
    die();
} 
	
if (($user -> LoggedAval($odb)) && ($user -> LoggedIn())){ 
	$loggedUserId    = $_SESSION['ID'];
    $userInfo = $user->userInfo($odb,$loggedUserId);
    if(strpos($_SERVER['REQUEST_URI'], "admin") !== false && $userInfo['rank']!=1  && $userInfo['rank']!=2){
    	header('location: /index.php');
    	die();
    }elseif(strpos($_SERVER['REQUEST_URI'], "admin") !== false && $userInfo['rank']==2){
    	if(isset($permission_array[$currentPage])){
    		$access = $permission_array[$currentPage];
    		if (!$user -> isAssist($odb,$access)) {
    			/*header('location: /index.php');
    			die();*/
    		}
    	}
    }
} 

?>