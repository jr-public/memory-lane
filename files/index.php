<?php
// 1. Initial Authentication
// - Generate JWT with current context
// - Store context markers in token

// 2. Subsequent Requests
// - Validate current context against token
// - If context differs:
//   * Reject request
//   * Prompt client to re-authenticate
//   * Generate new JWT with updated context
function reserved_db() {
	$host 		= getenv("POSTGRES_SERVICE");
	$dbname 	= getenv("POSTGRES_DB");
	$username 	= getenv("POSTGRES_USER");
	$password 	= getenv("POSTGRES_PASSWORD");
	try {
		$db = new PDO(
			"pgsql:host=$host;dbname=$dbname",
			$username,
			$password,
			[
				PDO::ATTR_ERRMODE 				=> PDO::ERRMODE_EXCEPTION,
				PDO::ATTR_DEFAULT_FETCH_MODE 	=> PDO::FETCH_ASSOC,
				PDO::ATTR_EMULATE_PREPARES 		=> false
			]
		);
	} catch(PDOException $e) {
		die("Connection failed: " . $e->getMessage());
	}
	return $db;
}
define("DB",reserved_db());

try {
	$sql	= "SELECT * FROM users";
	$stmt	= DB->prepare($sql);
	$stmt->execute();
	$users	= $stmt->fetchAll();
	
	echo "<br /><br />";
	foreach ( $users AS $user ) {
		echo json_encode($user);
		echo "<br /><br />";
	}
} catch (\Throwable $th) {
    echo "<b>An error occurred:</b> " . $th->getMessage();
}


echo "<br/>";
echo "and now for something completely different :";
echo "<br/>";

require 'vendor/autoload.php';

use MemoryLane\Auth;
$auth = new Auth();
$jwt = $auth->authenticate("jr-client","12341234");
$response = $auth->verifyToken($jwt);
echo json_encode($response);

