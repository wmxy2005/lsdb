<?php
$time_start = microtime(true);
include 'init.php';
include 'jwt.php';
if(Allow_Origin_Enable){
	header('Access-Control-Allow-Origin: ' . Allow_Origin);
	header('Access-Control-Allow-Credentials: ' . 'true');
	header('Access-Control-Allow-Headers: ' . 'Content-Type');
	header('Content-Type: ' . 'application/json;charset=utf-8');
}

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
	$itemId = intval($decoded_input['itemId']);
	$expired = $decoded_input['expired'];
	if($itemId > 0) {
		$pdo = new \PDO('sqlite:'. $dbname);
		
		$sql = "insert into itemfavi(itemId) select :itemId where not exists(select id from itemfavi where uId = 0 and itemId = :itemId)";
		$stmt = $pdo->prepare($sql);
		$stmt->bindValue(':itemId', $itemId, PDO::PARAM_INT);
		$stmt->execute();
		
		$sql2 = "update itemfavi set expired= :expired, datetime=(datetime(CURRENT_TIMESTAMP,'localtime')) where uId = 0 and itemId = :itemId";
		$stmt2 = $pdo->prepare($sql2);
		$stmt2->bindValue(':expired', $expired, PDO::PARAM_BOOL);
		$stmt2->bindValue(':itemId', $itemId, PDO::PARAM_INT);
		$stmt2->execute();
		
		$updateInfo = [
			'success' => true
		];
		echo json_encode($updateInfo);
		http_response_code(200);
		return;
	}
}

$warnInfo = [
	'errorMessage' => 'Method not allowed'
];
echo json_encode($warnInfo);
http_response_code(405);
?>