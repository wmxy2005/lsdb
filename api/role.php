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
$userInfo = checkToken($token);
if(!$userInfo['auth']){
	echo json_encode($userInfo);
	http_response_code(200);
	return;
}

$id = 0;
$queries = array();
if(array_key_exists('QUERY_STRING', $_SERVER)){
	parse_str($_SERVER['QUERY_STRING'], $queries);
}
if(array_key_exists('id', $queries)) {
	$id = intval($queries['id']);
}

if ($id > 0) {
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
	'errorMessage' => 'Method not allowed'
];
echo json_encode($warnInfo);
http_response_code(405);
?>