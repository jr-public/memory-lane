<?php
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