<?php
require_once 'core/config.test.php';
require_once 'core/mess.php';
$conf = Config::$config;
$lang = $conf['lang'];
$dbname = './' . $conf['dbname'];
$GLOBALS['lang'] = new Mess($lang);
$base_dir = $conf['folder'];
const DIR_SEP = DIRECTORY_SEPARATOR;
const TOKEN_EXPIRED = 3600*24*7;
const Allow_Origin = 'http://localhost:8000';
function getImagePath($base_dir, $base, $cate, $subcate, $name, $filename) {
	$filepath = $base_dir. DIR_SEP. $base . DIR_SEP . (empty($cate) ? "" : $cate . DIR_SEP) . (empty($subcate) ? "" : $subcate . DIR_SEP) . (empty($name) ? "" : $name . DIR_SEP) . $filename;
	return $filepath;
}
function getImageUrl($base, $cate, $subcate, $name, $filename) {
	$image_url = '/api/resource?' . 'base=' . $base . (empty($cate) ? "" : '&cata=' . $cate) . (empty($subcate) ? "" : '&subcata=' . $subcate) . (empty($name) ? "" : '&name=' . $name) . '&filename=' . $filename;
	return $image_url;
}
function resolveAvatarUrl($base_dir, $base, $category) {
	$avatarSrc = '';
    $cate = '';
    $subcategory = '';
    $name = '';
    $filename = '';
    if(file_exists(getImagePath($base_dir, $base, $category, '', '', 'logo.png'))) {
      $cate = $category;
      $filename = 'logo.png';
    }
    if(empty($filename) && file_exists(getImagePath($base_dir, $base, $category, '', '', 'logo.jpg'))) {
      $cate = $category;
      $filename = 'logo.jpg';
    }
    if(empty($filename) && file_exists(getImagePath($base_dir, $base, $category, '', '', 'logo.svg'))) {
      $cate = $category;
      $filename = 'logo.svg';
    }
    if(empty($filename) && file_exists(getImagePath($base_dir, $base, '', '', '', 'logo.png'))) {
      $filename = 'logo.png';
    }
    if(empty($filename) && file_exists(getImagePath($base_dir, $base, '', '', '', 'logo.jpg'))) {
      $filename = 'logo.jpg';
    }
    if(empty($filename) && file_exists(getImagePath($base_dir, $base, '', '', '', 'logo.svg'))) {
      $filename = 'logo.svg';
    }
    if(!empty($filename)) {
      $avatarSrc = getImageUrl($base, $cate, '', '', $filename);
    }
    return $avatarSrc;
}
function processDataItem($base_dir, $row) {
	$tagList = array();
	if (!empty($row['base'])) {
		$tagItem = array();
		$tagItem['type'] = 'base';
		$tagItem['tagIndex'] = 0;
		$tagItem['value'] = $row['base'];
		array_push($tagList, $tagItem);

		$row['avatar'] = strtoupper(substr($row['base'], 0, 1)) . strtoupper(substr($row['base'], 1, 2));
		$row['avatarSrc'] = resolveAvatarUrl($base_dir, $row['base'], $row['category']);
	}
	if(!empty($row['category'])){
		$tagItem = array();
		$tagItem['type'] = 'category';
		$tagItem['tagIndex'] = 0;
		$tagItem['value'] = $row['category'];
		array_push($tagList, $tagItem);
	}
	if(!empty($row['subcategory'])){
		$tagItem = array();
		$tagItem['type'] = 'subcategory';
		$tagItem['tagIndex'] = 0;
		$tagItem['value'] = $row['subcategory'];
		array_push($tagList, $tagItem);
	}
	$tagValues = explode(";", $row['tag']);
	for($i=0; $i < count($tagValues); $i++){
		$tagText = trim($tagValues[$i]);
		if(!empty($tagText)){
			$tagItem = array();
			$tagItem['type'] = 'tag';
			$tagItem['tagIndex'] = $i;
			$tagItem['value'] = $tagText;
			array_push($tagList, $tagItem);
		}
	}
	$tagValues = explode(";", $row['tag2']);
	for($i=0; $i < count($tagValues); $i++){
		$tagText = trim($tagValues[$i]);
		if(!empty($tagText)){
			$tagItem = array();
			$tagItem['type'] = 'tag2';
			$tagItem['tagIndex'] = $i;
			$tagItem['value'] = $tagText;
			array_push($tagList, $tagItem);
		}
	}
	$tagValues = explode(";", $row['tag3']);
	for($i=0; $i < count($tagValues); $i++){
		$tagText = trim($tagValues[$i]);
		if(!empty($tagText)){
			$tagItem = array();
			$tagItem['type'] = 'tag3';
			$tagItem['tagIndex'] = $i;
			$tagItem['value'] = $tagText;
			array_push($tagList, $tagItem);
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
	
	$filepath = getImagePath($base_dir, $row['base'], $row['category'], $row['subcategory'], $row['name'], $row['roll']);
	if(!file_exists($filepath)) {
		$row['roll'] = '';
	}
	return $row;
}
function str_starts_with($haystack, $needle) {
	if ( '' === $needle ) {
		return true;
	}
	return 0 === strpos($haystack, $needle);
}
function str_ends_with($haystack, $needle) {
	if ( '' === $haystack && '' !== $needle ) {
		return false;
	}
	$len = strlen($needle);
	return 0 === substr_compare($haystack, $needle, -$len, $len);
}
$GLOBALS['styleTypes'] = array("primary", "secondary", "success", "danger", "warning", "info", "light", "dark");
function get_style_type($index) {
	$i = ($index) % sizeof($GLOBALS['styleTypes']);
	return $GLOBALS['styleTypes'][$i];
}
?>