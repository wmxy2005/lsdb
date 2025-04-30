<?php
$time_start = microtime(true);
require_once 'init.php';
require_once 'jwt.php';
if(Allow_Origin_Enable){
	header('Access-Control-Allow-Origin: ' . Allow_Origin);
	header('Access-Control-Allow-Credentials: ' . 'true');
	header('Access-Control-Allow-Headers: ' . 'Content-Type');
	header('Content-Type: ' . 'application/json;charset=utf-8');
}
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

$time_start = microtime(true);
$conf = Config::$config;
$lang = $conf['lang'];
$GLOBALS['lang'] = new Mess($lang);
$base_dir = $conf['folder'];

$uId = 0;
$start = 0;
$pageSize = 20;
$current = 1;
$keyword = array();
$base = '';
$category = array();
$subcategory = '';
$tag = array();
$dateFrom = '';
$dateTo = '';
$matchMode = '';
$favi = false;
$type = '';
$sort = '';
$display = 0;

$params = array();
$paramsRole = array();
$queries = array();
if(array_key_exists('QUERY_STRING', $_SERVER)){
	parse_str($_SERVER['QUERY_STRING'], $queries);
}
if(array_key_exists('start', $queries)) {
	$start = intval($queries['start']);
}
if(array_key_exists('page', $queries)) {
	$current = intval($queries['page']);
}
if(array_key_exists('keyword', $queries)) {
	$keywordStr = trim(rawurldecode($queries['keyword']));
	$splitStr = explode(";", $keywordStr);
	for($i=0; $i < count($splitStr); $i++){
		if(strlen($splitStr[$i])>0){
			array_push($keyword, $splitStr[$i]);
		}
	}
}
if(array_key_exists('base', $queries)) {
	$base = $queries['base'];
}
if(array_key_exists('category', $queries)) {
	$categoryStr = trim(rawurldecode($queries['category']));
	$splitStr = explode(";", $categoryStr);
	for($i=0; $i < count($splitStr); $i++){
		if(strlen($splitStr[$i])>0){
			array_push($category, $splitStr[$i]);
		}
	}
}
if(array_key_exists('tag', $queries)) {
	$tagStr = trim(rawurldecode($queries['tag']));
	$splitStr = explode(";", $tagStr);
	for($i=0; $i < count($splitStr); $i++){
		if(strlen($splitStr[$i])>0){
			array_push($tag, $splitStr[$i]);
		}
	}
}
if(array_key_exists('dateFrom', $queries)) {
	$dateFrom = trim(rawurldecode($queries['dateFrom']));
}
if(array_key_exists('dateTo', $queries)) {
	$dateTo = trim(rawurldecode($queries['dateTo']));
}
if(array_key_exists('matchMode', $queries)) {
	$matchMode = $queries['matchMode'];
}
if(array_key_exists('favi', $queries)) {
	$favi = $queries['favi'];
}
if(array_key_exists('type', $queries)) {
	$type = $queries['type'];
}
if(array_key_exists('sort', $queries)) {
	$sort = $queries['sort'];
}
if(array_key_exists('favi', $queries)) {
	$favi = 'true' == $queries['favi'];
}

$cond = " WHERE a.id > ". $start;
$role_cond = "";
if(!empty($base)) {
	$params[':base'] = $base;
	$cond = $cond . " AND a.base = :base";
}
if(!empty($keyword)) {
	$arrayConds = '';
	for($i=0; $i < count($keyword); $i++){
		if(strlen($keyword[$i])>0){
			$params[':keyword'.$i] = '%' . $splitStr[$i] . '%';
			$paramsRole[':keyword'.$i] = '%' . $splitStr[$i] . '%';
			$arrayConds = $arrayConds . (strlen($arrayConds) > 0 ? ('or' == $matchMode ? " OR " : " AND "): "") . "(a.name LIKE :keyword" . $i . " OR a.title like :keyword" . $i . " OR a.tag like :keyword" . $i . " OR a.tag2 like :keyword" . $i . " OR a.tag3 like :keyword" . $i . " )";
			$role_cond = $role_cond . (strlen($role_cond) > 0 ? ' OR ': '') . "a.name LIKE :keyword" . $i ."";
		}
	}
	$cond = $cond . " AND (" . $arrayConds .")";
}
if(!empty($category)) {
	$arrayConds = '';
	for($i=0; $i < count($category); $i++){
		if(strlen($category[$i])>0){
			$params[':category'] = $category[$i];
			$arrayConds = $arrayConds . (strlen($arrayConds) > 0 ? ('or' == $matchMode ? " OR " : " AND "): "") . "a.category = :category";
		}
	}
	$cond = $cond . " AND (" . $arrayConds .")";
}
if(!empty($tag)) {
	$arrayConds = '';
	for($i=0; $i < count($tag); $i++){
		if(strlen($tag[$i])>0){
			$params[':tag'.$i] = '%;' . $tag[$i] . ';%';
			$paramsRole[':tag'.$i] = '%;' . $tag[$i] . ';%';
			$arrayConds = $arrayConds . (strlen($arrayConds) > 0 ? ('or' == $matchMode ? " OR " : " AND "): "") . "(a.tag like :tag" . $i . " OR a.tag2 like :tag" . $i . " OR a.tag3 like :tag" . $i . " )";
			$role_cond = $role_cond . (strlen($role_cond) > 0 ? ' OR ': '') . "a.name LIKE :tag" . $i ."";
		}
	}
	$cond = $cond . " AND (" . $arrayConds .")";
}
if(!empty($dateFrom)) {
	$params[':dateFrom'] = $dateFrom;
	$cond = $cond . " AND a.date >= :dateFrom";
}
if(!empty($dateTo)) {
	$params[':dateTo'] = $dateTo;
	$cond = $cond . " AND a.date <= :dateTo";
}

if(strlen($type) > 0) {
	$cond = $cond . " AND a.type = " . intval($type) ."";
}
if($favi > 0){
	$cond = $cond . " AND b.id IS NOT NULL " ."";
}

$sort_cond = "";
$begin = $start + $pageSize*($current-1);
if($favi > 0) {
	$sort_cond = "b.datetime DESC, a.date DESC, a.id DESC";
	if($sort == 'createAt'){
		$sort_cond = "b.id DESC, a.date DESC, a.id DESC";
	}
	$cond = $cond . " AND b.id NOT NULL";
}else{
	$sort_cond = "a.date DESC, a.id DESC";
	if($sort == 'createAt'){
		$sort_cond = "a.id DESC";
	}
}
 
$pdo = new \PDO('sqlite:'.$dbname);

$sql1 = "SELECT COUNT(1) FROM items AS a LEFT JOIN itemfavi AS b ON a.id = b.itemId AND b.expired=0 ". $cond;
$stmt1 = $pdo->prepare($sql1);
$stmt1->execute($params);
$total = $stmt1->fetchColumn();
$pages = ceil($total / $pageSize);

$sql2 = "SELECT a.*, b.id AS favi FROM items as a LEFT JOIN itemfavi AS b ON a.id = b.itemId and b.expired=0 ". $cond ." ORDER BY ".$sort_cond." LIMIT ". $begin . ", ".$pageSize;
$stmt2 = $pdo->prepare($sql2);
$stmt2->execute($params);
$list = array();
while($row = $stmt2->fetch(\PDO::FETCH_ASSOC)){
	array_push($list, processDataItem($base_dir, $row));
}

$sql3 = "SELECT a.* FROM role AS a WHERE ". (empty($role_cond) ? "1 = 2" : $role_cond) ." ORDER BY a.id DESC;";
$stmt3 = $pdo->prepare($sql3);
$stmt3->execute($paramsRole);
$roleList = array();
while ($row = $stmt3->fetch(\PDO::FETCH_ASSOC)){
	$nameValues = explode(";", $row['name']);
	$roleName = '';
	for($index=0; $index < count($nameValues); $index++){
		$nameText = trim($nameValues[$index]);
		for($i=0; $i < count($keyword); $i++){
			if($roleName == ''){
				$roleName = $nameText;
			}
			if(strlen($keyword[$i])>0){
				if(stripos($nameText, $keyword[$i])) {
					$roleName = $nameText;
				}
			}
		}
		for($i=0; $i < count($tag); $i++){
			if($roleName == ''){
				$roleName = $nameText;
			}
			if(strlen($tag[$i])>0){
				if($nameText == $tag[$i]) {
					$roleName = $nameText;
				}
			}
		}
	}
	$roleSrc = '';
	$imageValues = explode(";", $row['images']);
	for($i=0; $i < count($imageValues); $i++){
		$trimImage = trim($imageValues[$i]);
		if(strlen($trimImage) > 0){
			$roleValues = explode("@", $trimImage);
			if(count($roleValues) == 2){
				$trimRoleName = $roleValues[0];
                $trimRoleSrc = $roleValues[1];
				if($roleSrc == ''){
					$roleSrc = $trimRoleSrc;
				}
				if($roleName == $trimRoleName){
					$roleSrc = $trimRoleSrc;
				}
			}
		}
	}
	
	$row['tagIndex'] = $row['id'];
	$row['name'] = $roleName;
	$row['image'] = $roleSrc;
	$row['imageSrc'] = getImageUrl($row['base'], '', '', 'e' . $row['id'], $roleSrc);
	
	array_push($roleList, $row);
}

$time_cost = round(microtime(true) - $time_start, 3);

$data = array();
$data['params'] = $params;
$data['sql1'] = $sql1;
$data['sql2'] = $sql2;
$data['sql3'] = $sql3;
$data['base'] = $base;
$data['category'] = $category;
$data['subcategory'] = $subcategory;
$data['keyword'] = $keyword;
$data['tag'] = $tag;
$data['dateFrom'] = $dateFrom;
$data['dateTo'] = $dateTo;
$data['matchMode'] = $matchMode;
$data['favi'] = $favi;
$data['type'] = $type;
$data['sort'] = $sort;
$data['current'] = $current;
$data['pageSize'] = $pageSize;
$data['pages'] = $pages;
$data['total'] = $total;
$data['costTime'] = $time_cost;
$data['roleList'] = $roleList;
$data['title'] = 'all';
$data['message'] = $total . ' record(s), ' . $pages . ' page(s)';
$data['list'] = $list;
$result['roleList'] = $roleList;

$result = array();
$result['success'] = true;
$result['data'] = $data;
$result['errorCode'] = 0;
$json = json_encode($result);

echo $json;
?>