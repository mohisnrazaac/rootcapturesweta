<?php

//Database_connection.php

class Database_connection
{
	function connect()
	{
		$connect = new PDO("mysql:host=localhost; dbname=rootCapCre", "a90amc1ZaLpcF", "4rG7Frdq&qhx77sBb");

		return $connect;
	}
}

?>