<?php
define('DB_HOST', 'localhost');
define('DB_NAME', 'rootCapCre');
define('DB_USERNAME', 'a90amc1ZaLpcF');
define('DB_PASSWORD', '4rG7Frdq&qhx77sBb');

@$odb = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USERNAME, DB_PASSWORD);
putenv("TZ=America/Phoenix");

// @$odbTest = new PDO('mysql:host=' . DB_HOST . ';dbname=' . 'rootCapMailer', 'mailer', '2$M1qa25SpgignPok');
// putenv("TZ=America/Phoenix");

// $SQLGetTeam = $odbTest -> query("SELECT * FROM `test` ORDER BY id ASC")->fetchAll();

// print_r($SQLGetTeam);
?>