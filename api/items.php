<?php
const DIR_SEP = DIRECTORY_SEPARATOR;
function getImagePath($base_dir, $base, $cate, $subcate, $name, $filename) {
	$filepath = $base_dir. DIR_SEP. $base . DIR_SEP . (empty($cate) ? "" : $cate . DIR_SEP) . (empty($subcate) ? "" : $subcate . DIR_SEP) . (empty($name) ? "" : $name . DIR_SEP) . $filename;
	return $filepath;
}
function getImageUrl($base, $cate, $subcate, $name, $filename) {
	$image_url = 'resource?' . 'base=' . $base . (empty($cate) ? "" : '&cata=' . $cate) . (empty($subcate) ? "" : '&subcata=' . $subcate) . (empty($name) ? "" : '&name=' . $name) . '&filename=' . $filename;
	return $image_url;
}

$time_start = microtime(true);
require_once 'core/config.test.php';
require_once 'core/mess.php';
$conf = Config::$config;
$lang = $conf['lang'];
$GLOBALS['lang'] = new Mess($lang);
$base_dir = $conf['folder'];

$dbname = $conf['dbname'];
$pdo = new \PDO('sqlite:'.$dbname);

$sql = "SELECT a.*, ifnull(b.id, 0) as favi FROM items as a left join itemfavi as b on a.id = b.itemId and b.expired=0";
$query = $pdo->query($sql);
$result = array();
while($row = $query->fetch(\PDO::FETCH_ASSOC)){
	
	$tagList = array();
	$tagValues = explode(";", $row['tag']);
	for($i=0; $i < count($tagValues); $i++){
		$tagText = trim($tagValues[$i]);
		if(!empty($tagText)){
			$tag = array();
			$tag['value'] = $tagText;
			array_push($tagList, $tag);
		}
	}
	$row['tagList'] = $tagList;
	
	$row['isFavi'] = $row['favi'] > 0;
	$filepath = getImagePath($base_dir, $row['base'], $row['category'], $row['subcategory'], $row['name'], $row['thumbnail']);
	if(file_exists($filepath)) {
		$fileExist = true;
	}else{
		$filepath = 'core/img/image-not-found.jpg';
	}
	list($width, $height) = getimagesize($filepath);
	$row['thumbnailW'] = $width;
	$row['thumbnailH'] = $height;
	array_push($result, $row);
}

$json = json_encode($result);
header('Content-Type: ' . 'application/json;charset=utf-8');
echo $json;
?>