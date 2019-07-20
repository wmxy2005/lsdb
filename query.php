<?php require_once 'core/init.php';
require_once 'core/config.php';
require_once 'core/template.php';
$time_start = microtime(true);
$start = 0;
$page = 1;
$keyword = '';
$tag = '';
$category = '';
$favi = 0;
$queries = array();
parse_str($_SERVER['QUERY_STRING'], $queries);
if(array_key_exists('start', $queries)) {
	$start = $queries['start'];
}
if(array_key_exists('page', $queries)) {
	$page = $queries['page'];
}
if(array_key_exists('keyword', $queries)) {
	$keyword = trim(rawurldecode($queries['keyword']));
}
if(array_key_exists('tag', $queries)) {
	$tag = trim(rawurldecode($queries['tag']));
}
if(array_key_exists('category', $queries)) {
	$category = trim(rawurldecode($queries['category']));
}
if(array_key_exists('myfavi', $queries)) {
	$favi = $queries['myfavi'];
}

$conf = Config::$config;
$dbname = $conf['dbname'];
$pdo = new \PDO('sqlite:'.$dbname);
$pagesize = 20;

$sort = "a.date desc, a.id desc";
$cond = " where a.id > ". $start;
if(!empty($keyword)) {
	$words= preg_split('/ /s', $keyword);
	for($i = 0; $i < sizeof($words); $i=$i+1) {
		$word = $words[$i];
		if(!empty($word))
			$cond = $cond . " and (a.title like '%" . $word . "%' or a.name like '%" . $word . "%' or a.tag like '%" . $word . "%' or a.content like '%" . $word . "%')";
	}
}
if(!empty($tag))
	$cond = $cond . " and a.tag like '%" . $tag ."%'";
if(!empty($category))
	$cond = $cond . " and a.category like '%" . $category ."%'";
if($favi > 0) {
	$sort = "b.datetime desc , a.date desc, a.id desc";
	$cond = $cond . " and b.id not null";
}
$sql = "SELECT count(1) FROM items as a left join itemfavi as b on a.id = b.itemId ". $cond;
$result = $pdo->query($sql);
$count = $result->fetchColumn();
$toalPage = ceil($count / $pagesize) - 1;
$begin = $start + $pagesize*($page-1);

$sql = "SELECT a.*,b.id as favi FROM items as a left join itemfavi as b on a.id = b.itemId ". $cond ." order by ".$sort." limit ". $begin . ", ".$pagesize;
$result = $pdo->query($sql);
$total_mess = L('total_mess');
$arr1 = array('%1%','%2%');
$arr2 = array($count.'',($toalPage+1).'');
$total_mess = str_replace($arr1,$arr2,$total_mess);

$res = array();
while ($row = $result->fetch(\PDO::FETCH_ASSOC)){
	array_push($res, $row);
}
$template = new Template('templates/searchpage.php');
$template->res = $res;
$template->start = $start;
$template->page = $page;
$template->toalPage = $toalPage;
$template->favi = $favi;
$template->total_mess = $total_mess;
$template->category = $category;
$template->tag = $tag;
$template->keyword = $keyword;

$time_cost = round(microtime(true) - $time_start, 3);
$template->timeCost = $time_cost . "s";

echo $template;
$pdo = null; 
?>
