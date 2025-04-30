<?php
require_once 'init.php';
require_once 'jwt.php';

$created = time();
$token = "";
if(array_key_exists('token', $_COOKIE)) {
	$token = $_COOKIE['token'];
}
if(Allow_Origin_Enable){
	header('Access-Control-Allow-Origin: ' . Allow_Origin);
	header('Access-Control-Allow-Credentials: ' . 'true');
	header('Access-Control-Allow-Headers: ' . 'Content-Type');
	header('Content-Type: ' . 'application/json;charset=utf-8');
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$logout = '';
	if(array_key_exists('logout', $_REQUEST)) {
		$logout = $_REQUEST['logout'];
	}
	if('true' == $logout){
		$userInfo = [
			'userId' => 0
		];
		echo json_encode($userInfo);
		setcookie('token', '');
		http_response_code(200);
		return;
	}else{
		$userId = 1;
		$passwordHash = password_hash('acd', PASSWORD_DEFAULT);
		$input = file_get_contents('php://input');
		$decoded_input = json_decode($input, true);
		$user = $decoded_input['username'];
		$password = $decoded_input['password'];
		if ('admin' != $user || !password_verify($password, $passwordHash)) {
			$result['message'] = 'Invalid email or password';
			echo json_encode($result);
			http_response_code(401);
			return;
		}
		$payload = [
			'userId' => $userId,
			'name' => $user,
			'created' => $created
		];
		$jwt = JWT::generateJWT($payload);
		$userInfo = [
			'user' => $user
		];
		setcookie('token', $jwt);
		echo json_encode($payload);
		http_response_code(200);
		return;
	}
	
} else if ($_SERVER['REQUEST_METHOD'] == 'GET') {
	if(!empty($token)){
		$decodedPayload = JWT::verifyJWT($token);
		if($decodedPayload){
			$storeUserId = 0;
			$storeCreated = 0;
			if(array_key_exists('userId', $decodedPayload)) {
				$storeUserId = $decodedPayload['userId'];
			}
			if(array_key_exists('created', $decodedPayload)) {
				$storeCreated = $decodedPayload['created'];
			}
			if($storeUserId > 0) {
				if ($created - $storeCreated > TOKEN_EXPIRED) {
					$userInfo = [
						'userId' => $storeUserId,
						'name' => 'expired'
					];
					echo json_encode($userInfo);
					http_response_code(401);
					return;
				}else{
					$userInfo = [
						'userId' => $storeUserId,
						'name' => 'admin'
					];
					echo json_encode($userInfo);
					http_response_code(200);
					return;
				}
			} else {
				$userInfo = [
					'userId' => 0,
					'name' => 'noUser'
				];
				echo json_encode($userInfo);
				http_response_code(401);
				return;
			}
		}
	}
} else if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
	header('Access-Control-Request-Method: ' . 'GET,POST');
	http_response_code(200);
	return;
}
$warnInfo = [
	'errorMessage' => 'Method not allowed'
];
echo json_encode($warnInfo);
http_response_code(405);
?>