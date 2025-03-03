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

$id = 0;
$queries = array();
if(array_key_exists('QUERY_STRING', $_SERVER)){
	parse_str($_SERVER['QUERY_STRING'], $queries);
}
if(array_key_exists('id', $queries)) {
	$id = $queries['id'];
}
if($id > 0) {
	$pdo = new \PDO('sqlite:'.$dbname);
	if ($_SERVER['REQUEST_METHOD'] == 'GET') {
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
	} else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$input = file_get_contents('php://input');
		$decoded_input = json_decode($input, true);
		$title = $decoded_input['title'];
		$date = $decoded_input['date'];
		$content = $decoded_input['content'];
		$thumbnail = $decoded_input['thumbnail'];
		$roll = $decoded_input['roll'];
		$tagsArray = $decoded_input['tags'];
		$tags2Array = $decoded_input['tags2'];
		$tags3Array = $decoded_input['tags3'];
		$imagesArray = $decoded_input['images'];
		
		$tag = ";";
		$tag2 = ";";
		$tag3 = ";";
		for($i=0; $i < count($tagsArray); $i++){
			$tag = $tag . $tagsArray[$i] .";";
		}
		for($i=0; $i < count($tags2Array); $i++){
			$tag2 = $tag2 . $tags2Array[$i] .";";
		}
		for($i=0; $i < count($tags3Array); $i++){
			$tag3 = $tag3 . $tags3Array[$i] .";";
		}
		$images = "";
		for($i=0; $i < count($imagesArray); $i++){
			$images = $images . (strlen($images) > 0 ? ';' : '') . $imagesArray[$i]['value'];
		}
		try {
			$sql = "UPDATE items set title = :title, date = :date, content = :content, thumbnail = :thumbnail, roll = :roll, tag = :tag, tag2 = :tag2, tag3 = :tag3, images = :images where id = ". $id;
			$stmt = $pdo->prepare($sql);
			$stmt->execute([
				':title' => $title,
				':date' => $date,
				':content' => $content,
				':thumbnail' => $thumbnail,
				':roll' => $roll,
				':tag' => $tag,
				':tag2' => $tag2,
				':tag3' => $tag3,
				':images' => $images
			]);
			$updateInfo = [
				'success' => true
			];
			echo json_encode($updateInfo);
			http_response_code(200);
			return;
		} catch (PDOException $e) {
		}
	}
}
$warnInfo = [
	'error' => 'Method not allowed'
];
echo json_encode($warnInfo);
http_response_code(405);
?>