<?php
define('DBENTERPRISE_HOST', 'localhost');
define('DBENTERPRISE_NAME', 'rootCapMailer');
define('DBENTERPRISE_USERNAME', 'mailer');
define('DBENTERPRISE_PASSWORD', '2$M1qa25SpgignPok');

@$odbenterprise = new PDO('mysql:host=' . DBENTERPRISE_HOST . ';dbname=' . DBENTERPRISE_NAME, DBENTERPRISE_USERNAME, DBENTERPRISE_PASSWORD);
putenv("TZ=America/Phoenix");

// @$odbTest = new PDO('mysql:host=' . DB_HOST . ';dbname=' . 'rootCapMailer', 'mailer', '2$M1qa25SpgignPok');
// putenv("TZ=America/Phoenix");

// $SQLGetTeam = $odbTest -> query("SELECT * FROM `test` ORDER BY id ASC")->fetchAll();

// print_r($SQLGetTeam);
?>