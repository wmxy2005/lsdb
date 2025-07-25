<?php
$time_start = microtime(true);
include 'init.php';
include 'jwt.php';
header('Access-Control-Allow-Methods: ' . 'GET, POST, OPTIONS, PUT, DELETE');
if(Allow_Origin_Enable){
	header('Access-Control-Allow-Origin: ' . Allow_Origin);
	header('Access-Control-Allow-Credentials: ' . 'true');
	header('Access-Control-Allow-Headers: ' . 'Content-Type');
}
header('Content-Type: ' . 'application/json;charset=utf-8');

function updateItems($pdo, $id, $decoded_input) {
    $title = '';
	$date = '';
	$content = '';
	$thumbnail = '';
	$roll = '';
	if(array_key_exists('title', $decoded_input)) {
		$title = $decoded_input['title'];
	}
	if(array_key_exists('date', $decoded_input)) {
		$date = $decoded_input['date'];
	}
	if(array_key_exists('content', $decoded_input)) {
		$content = $decoded_input['content'];
	}
	if(array_key_exists('thumbnail', $decoded_input)) {
		$thumbnail = $decoded_input['thumbnail'];
	}
	if(array_key_exists('roll', $decoded_input)) {
		$roll = $decoded_input['roll'];
	}
	
	$tagsArray = null;
	$tags2Array = null;
	$tags3Array = null;
	$imagesArray = null;
	if(array_key_exists('tags', $decoded_input)) {
		$tagsArray = $decoded_input['tags'];
	}
	if(array_key_exists('tags2', $decoded_input)) {
		$tags2Array = $decoded_input['tags2'];
	}
	if(array_key_exists('tags3', $decoded_input)) {
		$tags3Array = $decoded_input['tags3'];
	}
	if(array_key_exists('images', $decoded_input)) {
		$imagesArray = $decoded_input['images'];
	}
	
	$tag = ";";
	$tag2 = ";";
	$tag3 = ";";
	if(is_array($tagsArray)) {
		for($i=0; $i < count($tagsArray); $i++){
			$tag = $tag . $tagsArray[$i] .";";
		}
	}
	if(is_array($tags2Array)) {
		for($i=0; $i < count($tags2Array); $i++){
			$tag2 = $tag2 . $tags2Array[$i] .";";
		}
	}
	if(is_array($tags3Array)) {
		for($i=0; $i < count($tags3Array); $i++){
			$tag3 = $tag3 . $tags3Array[$i] .";";
		}
	}
	$images = "";
	if(is_array($imagesArray)) {
		for($i=0; $i < count($imagesArray); $i++){
			$images = $images . (strlen($images) > 0 ? ';' : '') . $imagesArray[$i]['value'];
		}
	}
	$success = false;
	$error = '';
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
		$success = true;
	} catch (PDOException $e) {
		$error = 'sql exception';
	}
	return [$success, $error];
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
		list($success, $errorMessage) = updateItems($pdo, $id, $decoded_input);
		if($success) {
			$updateInfo = [
				'success' => true,
				'id' => $id
			];
			echo json_encode($updateInfo);
			http_response_code(200);
			return;
		}
	}
} else {
	if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
		$pdo = new \PDO('sqlite:'.$dbname);
		$input = file_get_contents('php://input');
		$decoded_input = json_decode($input, true);
		$base = '';
		$category = '';
		$subcategory = '';
		$name = '';
		if(array_key_exists('base', $decoded_input)) {
			$base = $decoded_input['base'];
		}
		if(array_key_exists('category', $decoded_input)) {
			$category = $decoded_input['category'];
		}
		if(array_key_exists('subcategory', $decoded_input)) {
			$subcategory = $decoded_input['subcategory'];
		}
		if(array_key_exists('name', $decoded_input)) {
			$name = $decoded_input['name'];
		}
		
		$checkFolder = true;
		$errorMessage = '';
		if(empty($base) || empty($category) || empty($name)){
			$checkFolder = false;
		}
		if($checkFolder) {
			$folderPath = getImagePath($base_dir, $base, $category, $subcategory, $name, '');
			if(!file_exists($folderPath)) {
				mkdir($folderPath, 0777, true);
			}
			if(file_exists($folderPath)){
				try {
					$sql = "INSERT INTO items(base, category, subcategory, name) SELECT :base, :category, :subcategory, :name WHERE NOT EXISTS(SELECT id FROM items WHERE base = :base AND category = :category AND subcategory = :subcategory AND name = :name)";
					$stmt = $pdo->prepare($sql);
					$stmt->execute([
						':base' => $base,
						':category' => $category,
						':subcategory' => $subcategory,
						':name' => $name
					]);
					
					$sql = "SELECT id FROM items WHERE base = :base AND category = :category AND subcategory = :subcategory AND name = :name";
					$stmt = $pdo->prepare($sql);
					$stmt->execute([
						':base' => $base,
						':category' => $category,
						':subcategory' => $subcategory,
						':name' => $name
					]);
					if($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
						$itemId = $row['id'];
						list($success, $errorMessage) = updateItems($pdo, $itemId, $decoded_input);
						if($success) {
							$updateInfo = [
								'success' => true,
								'id' => $itemId
							];
							echo json_encode($updateInfo);
							http_response_code(200);
							return;
						}
					}
				} catch (PDOException $e) {
				}
			} else {
				$errorMessage = 'Failed to create folder';
			}
		} else {
			$errorMessage = 'Empty Base or Category';
		}
		$putInfo = [
			'success' => false,
			'errorMessage' => $errorMessage
		];
		echo json_encode($putInfo);
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