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

$queries = array();
if(array_key_exists('QUERY_STRING', $_SERVER)){
	parse_str($_SERVER['QUERY_STRING'], $queries);
}
if(array_key_exists('start', $queries)) {
	$start = $queries['start'];
}
if(array_key_exists('page', $queries)) {
	$current = $queries['page'];
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
	$cond = $cond . " and a.base = '" . $base ."'";
}
if(!empty($keyword)) {
	$arrayConds = '';
	for($i=0; $i < count($keyword); $i++){
		if(strlen($keyword[$i])>0){
			$arrayConds = $arrayConds . (strlen($arrayConds) > 0 ? ('or' == $matchMode ? " or " : " and "): "") . "(a.name like '%" . $keyword[$i] . "%' or a.title like '%" . $keyword[$i] . "%' or a.tag like '%" . $keyword[$i] . "%' or a.tag2 like '%" . $keyword[$i] . "%' or a.tag3 like '%" . $keyword[$i] . "%' )";
			$role_cond = $role_cond . (strlen($role_cond) > 0 ? ' or ': '') . "a.name like '%" . $keyword[$i] ."%'";
		}
	}
	$cond = $cond . " and (" . $arrayConds .")";
}
if(!empty($category)) {
	$arrayConds = '';
	for($i=0; $i < count($category); $i++){
		if(strlen($category[$i])>0){
			$arrayConds = $arrayConds . (strlen($arrayConds) > 0 ? ('or' == $matchMode ? " or " : " and "): "") . "a.category = '" . $category[$i] . "'";
		}
	}
	$cond = $cond . " and (" . $arrayConds .")";
}
if(!empty($tag)) {
	$arrayConds = '';
	for($i=0; $i < count($tag); $i++){
		if(strlen($tag[$i])>0){
			$arrayConds = $arrayConds . (strlen($arrayConds) > 0 ? ('or' == $matchMode ? " or " : " and "): "") . "(a.tag like '%;" . $tag[$i] . ";%' or a.tag2 like '%;" . $tag[$i] . ";%' or a.tag3 like '%;" . $tag[$i] . ";%' )";
			$role_cond = $role_cond . (strlen($role_cond) > 0 ? ' or ': '') . "a.name like '%;" . $tag[$i] .";%'";
		}
	}
	$cond = $cond . " and (" . $arrayConds .")";
}
if(!empty($dateFrom)) {
	$cond = $cond . " and a.date >= '" . $dateFrom ."'";
}
if(!empty($dateTo)) {
	$cond = $cond . " and a.date <= '" . $dateTo ."'";
}

if(strlen($type) > 0) {
	$cond = $cond . " and a.type = " . $type ."";
}
if($favi){
	$cond = $cond . " and b.id is not null " ."";
}

$sort_cond = "";
$begin = $start + $pageSize*($current-1);
if($favi > 0) {
	$sort_cond = "b.datetime desc, a.date desc, a.id desc";
	if($sort == 'createAt'){
		$sort_cond = "b.id desc, a.date desc, a.id desc";
	}
	$cond = $cond . " and b.id not null";
}else{
	$sort_cond = "a.date desc, a.id desc";
	if($sort == 'createAt'){
		$sort_cond = "a.id desc";
	}
}

$pdo = new \PDO('sqlite:'.$dbname);

$sql = "SELECT count(1) FROM items as a left join itemfavi as b on a.id = b.itemId and b.expired=0 ". $cond;
$queryCount = $pdo->query($sql);
$total = $queryCount->fetchColumn();
$pages = ceil($total / $pageSize);

$sql = "SELECT a.*, b.id as favi FROM items as a left join itemfavi as b on a.id = b.itemId and b.expired=0 ". $cond ." order by ".$sort_cond." limit ". $begin . ", ".$pageSize;
$query = $pdo->query($sql);
$list = array();
while($row = $query->fetch(\PDO::FETCH_ASSOC)){
	array_push($list, processDataItem($base_dir, $row));
}

$sql2 = "SELECT a.* FROM role as a WHERE ". (empty($role_cond) ? "1 = 2" : $role_cond) ." ORDER BY a.id desc;";

$queryRole = $pdo->query($sql2);
$roleList = array();
while ($row = $queryRole->fetch(\PDO::FETCH_ASSOC)){
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