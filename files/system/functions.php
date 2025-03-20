<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function generate_token(array $data): string {
	$issued_at = time();
	$expiration = $issued_at + 3600; // Token valid for 1 hour

	$payload = [
		'iat' => $issued_at,           // Issued at
		'exp' => $expiration,          // Expiration time
		'sub' => $data['id'], // Subject (client ID)
		'data' => $data
	];

	return JWT::encode($payload, getenv("JWT_SECRET"), 'HS256');
}
function verify_token(string $token): array {
	try {
		$decoded = JWT::decode($token, new Key(getenv("JWT_SECRET"), 'HS256'));
		return response(true,$decoded);
	} catch (\Exception $e) {
		return response(false,[$e],$e->getMessage());
	}
}
function set_cookie( $jwt, $expiration = null) {
	setcookie('auth_token', $jwt, [
		'expires' => $expiration ?? time() + 3600,
		'path' => '/',
		'domain' => $_SERVER['SERVER_NAME'],
		// 'secure' => true,     // Only send over HTTPS
		'httponly' => true,   // Not accessible via JavaScript
		'samesite' => 'Lax'   // Protects against CSRF
	]);
}
function get_cookie() {
	return $_COOKIE["auth_token"];
}

function response( bool $success = true, $data = null, ?string $message = null, ?int $code = null ): array {
    $response = [
		"success" 	=> $success,
		"data"		=> $data,
		"message" 	=> $message,
		"code"		=> $code
	];
	return $response;
}
?>