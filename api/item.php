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
if($id > 0) {
	$dbname = '../' . $conf['dbname'];
	$pdo = new \PDO('sqlite:'.$dbname);
	
	$sql = "SELECT a.*, b.id as favi FROM items as a left join itemfavi as b on a.id = b.itemId and b.expired=0 where a.id = ". $id;
	$result = $pdo->query($sql);
	if($row = $result->fetch(\PDO::FETCH_ASSOC)) {
		$data = processDataItem($base_dir, $row);
		$imgList = array();
		$imageValues = explode(";", $data['images']);
		for($i=0; $i < count($imageValues); $i++){
			$imageValue = trim($imageValues[$i]);
			if(!empty($imageValue)){
				$imageItem = array();
				$filepath = getImagePath($base_dir, $row['base'], $row['category'], $row['subcategory'], $row['name'], $imageValue);
				if(file_exists($filepath)) {
					$fileExist = true;
				}else{
					$filepath = 'core/img/image-not-found.jpg';
				}
				list($width, $height) = getimagesize($filepath);
				$imageItem['imgIndex'] = $i;
				$imageItem['value'] = $imageValue;
				$imageItem['width'] = $width;
				$imageItem['height'] = $height;
				array_push($imgList, $imageItem);
			}
		}
		$data['imgList'] = $imgList;
		$result = array();
		$result['success'] = true;
		$result['data'] = $data;
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