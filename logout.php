<?php
require_once 'includes/db.php';
session_start();

$updatePreferenceSql = $odb->prepare("UPDATE users SET `is_logout` = :is_logout WHERE id = :id");
$updatePreferenceSql->execute(array(':is_logout' => "yes", ':id' => $_SESSION['ID']));

unset($_SESSION['username']);
unset($_SESSION['ID']);
session_destroy();
header('location: login.php');
?>