<?php
$host 		= getenv("POSTGRES_SERVICE");
$dbname 	= getenv("POSTGRES_DB");
$username 	= getenv("POSTGRES_USER");
$password 	= getenv("POSTGRES_PASSWORD");
try {
	$db = new PDO( "pgsql:host=$host;dbname=$dbname", $username, $password, [
		PDO::ATTR_ERRMODE 				=> PDO::ERRMODE_EXCEPTION,
		PDO::ATTR_DEFAULT_FETCH_MODE 	=> PDO::FETCH_ASSOC,
		PDO::ATTR_EMULATE_PREPARES 		=> false
	]);
} catch(PDOException $e) {
	die("Connection failed: " . $e->getMessage());
}
define("DB",$db);