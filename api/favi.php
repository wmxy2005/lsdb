<?php
$time_start = microtime(true);
include 'init.php';
include 'jwt.php';
header('Access-Control-Allow-Origin: ' . Allow_Origin);
header('Access-Control-Allow-Credentials: ' . 'true');
header('Access-Control-Allow-Headers: ' . 'Content-Type');
header('Content-Type: ' . 'application/json;charset=utf-8');

$created = time();
$auth = false;
$token = "";
if(array_key_exists('token', $_COOKIE)) {
	$token = $_COOKIE['token'];
}
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
					'errorMessage' => 'Expired'
				];
				echo json_encode($userInfo);
				http_response_code(200);
				return;
			}else{
				$auth = true;
			}
		}
	}
}
if(!$auth){
	$userInfo = [
		'errorMessage' => 'Unauthorized'
	];
	echo json_encode($userInfo);
	http_response_code(200);
	return;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$input = file_get_contents('php://input');
	$decoded_input = json_decode($input, true);
	$itemId = $decoded_input['itemId'];
	$expired = $decoded_input['expired'];
	if($itemId > 0) {
		$dbname = '../' . $conf['dbname'];
		$pdo = new \PDO('sqlite:'. $dbname);
		$res = $pdo->exec("insert into itemfavi(itemId) select " . $itemId ." where not exists(select id from itemfavi where uId = 0 and itemId = " . $itemId .")");
		$pdo->exec("update itemfavi set expired=" . $expired . ", datetime=(datetime(CURRENT_TIMESTAMP,'localtime')) where uId = 0 and itemId = " . $itemId );
		$updateInfo = [
			'success' => true
		];
		echo json_encode($updateInfo);
		http_response_code(200);
		return;
	}
}

$warnInfo = [
	'error' => 'Method not allowed'
];
echo json_encode($warnInfo);
http_response_code(405);
?>