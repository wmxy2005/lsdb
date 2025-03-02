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

$conf = Config::$config;
$lang = $conf['lang'];
$GLOBALS['lang'] = new Mess($lang);
$base_dir = $conf['folder'];

$id = 0;
$queries = array();
parse_str($_SERVER['QUERY_STRING'], $queries);
if(array_key_exists('id', $queries)) {
	$id = $queries['id'];
}

if ($id > 0) {
	$dbname = '../' . $conf['dbname'];
	$pdo = new \PDO('sqlite:'.$dbname);
	$sql = "SELECT a.* FROM role as a where a.id = ". $id;
	$result = $pdo->query($sql);
	if($row = $result->fetch(\PDO::FETCH_ASSOC)) {
		$nameList = array();
		$nameValues = explode(";", $row['name']);
		for($index=0; $index < count($nameValues); $index++){
			if(strlen($nameValues[$index]) > 0){
				$nameItem = array();
				$nameItem['nameIndex'] = $index;
				$nameItem['name'] = $nameValues[$index];
				array_push($nameList, $nameItem);
			}
		}
		
		$imageList = array();
		$imageValues = explode(";", $row['images']);
		for($index=0; $index < count($imageValues); $index++){
			$trimImage = trim($imageValues[$index]);
			if(strlen($trimImage) > 0){
				$roleValues = explode("@", $trimImage);
				if(count($roleValues) == 2){
					$trimRoleName = $roleValues[0];
					$trimRoleSrc = $roleValues[1];
					
					$imageItem = array();
					$imageItem['nameIndex'] = $index;
					$imageItem['name'] = $trimRoleName;
					$imageItem['image'] = $trimRoleSrc;
					$imageItem['imageSrc'] = getImageUrl($row['base'], '', '', 'e' . $row['id'], $trimRoleSrc);
					array_push($imageList, $imageItem);
				}
			}
		}
		$row['nameList'] = $nameList;
		$row['imageList'] = $imageList;
		$result = array();
		$result['success'] = true;
		$result['data'] = $row;
		$result['errorCode'] = 0;
		echo json_encode($result);
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