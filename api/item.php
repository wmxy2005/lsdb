<?php
$time_start = microtime(true);
include 'init.php';
include 'jwt.php';
if(Allow_Origin_Enable){
	header('Access-Control-Allow-Origin: ' . Allow_Origin);
	header('Access-Control-Allow-Credentials: ' . 'true');
	header('Access-Control-Allow-Headers: ' . 'Content-Type');
}
header('Content-Type: ' . 'application/json;charset=utf-8');

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
if($id > 0) {
	$pdo = new \PDO('sqlite:'.$dbname);
	if ($_SERVER['REQUEST_METHOD'] == 'GET') {
		$sql = "SELECT a.*, b.id as favi FROM items as a left join itemfavi as b on a.id = b.itemId and b.expired=0 where a.id = :itemId";
		$stmt = $pdo->prepare($sql);
		$stmt->bindValue(':itemId', $id, PDO::PARAM_INT);
		$stmt->execute();
		if($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
			$data = processDataItem($base_dir, $row);
			$imgList = array();
			$imgSet = array();
			$fileList = array();
			$fileItem = array();
			$fileItem['type'] = 'thumbnail';
			$fileItem['value'] = $data['thumbnail'];
			array_push($fileList, $fileItem);
			$imgSet[$data['thumbnail']] = true;
			
			$imageValues = explode(";", $data['images']);
			for($i=0; $i < count($imageValues); $i++){
				$imageValue = trim($imageValues[$i]);
				if(!empty($imageValue)){
					$imageItem = array();
					$filepath = getImagePath($base_dir, $row['base'], $row['category'], $row['subcategory'], $row['name'], $imageValue);
					if(file_exists($filepath)) {
						$fileExist = true;
					}else{
						$filepath = IMAGE_NOT_FOUND;
					}
					list($width, $height) = @getimagesize($filepath);
					$imageItem['imgIndex'] = $i;
					$imageItem['value'] = $imageValue;
					list($width, $height) = @getimagesize($filepath);
					if($width) {
						$imageItem['width'] = $width;
						$imageItem['height'] = $height;
					}else{
						$filepath = IMAGE_NOT_FOUND;
						list($width, $height) = getimagesize($filepath);
						$imageItem['width'] = $width;
						$imageItem['height'] = $height;
					}
					array_push($imgList, $imageItem);
					if(!array_key_exists($imageValue, $imgSet)) {
						$fileItem = array();
						$fileItem['type'] = 'image';
						$fileItem['value'] = $imageValue;
						array_push($fileList, $fileItem);
						$imgSet[$imageValue] = true;
					}
				}
			}
			$data['imgList'] = $imgList;
			
			$dir = getImagePath($base_dir, $row['base'], $row['category'], $row['subcategory'], $row['name'], '');
			if (false != ($handle = opendir ( $dir ))) {
				while ( false !== ($file = readdir ( $handle )) ) {
					if ($file != "." && $file != ".." && strpos($file,".") && !strpos($file,".txt") && !strpos($file,".html")) {
						if (file_exists( $dir . DIR_SEP . $file)) {
							if(!array_key_exists($file, $imgSet)) {
								$fileItem = array();
								$fileItem['type'] = 'file';
								if(strpos($file,".mp4") || strpos($file,".wbm")){
									$fileItem['thumbUrl'] = '/video.svg';
								}
								$fileItem['value'] = $file;
								array_push($fileList, $fileItem);
							}
						}
					}
				}
				closedir ( $handle );
			}
			$data['fileList'] = $fileList;
			
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
	'errorMessage' => 'Method not allowed'
];
echo json_encode($warnInfo);
http_response_code(405);
?>