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
$censor = 2;
$sorts = 0;
$display = 0;
$base = '';
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
if(array_key_exists('favi', $queries)) {
	$favi = $queries['favi'];
}
if(array_key_exists('censor', $queries)) {
	$censor = $queries['censor'];
}
if(array_key_exists('sorts', $queries)) {
	$sorts = $queries['sorts'];
}
if(array_key_exists('display', $queries)) {
	$display = $queries['display'];
}
if(array_key_exists('base', $queries)) {
	$base = $queries['base'];
}

$conf = Config::$config;
$dbname = $conf['dbname'];
$pdo = new \PDO('sqlite:'.$dbname);
$pagesize = 20;

$cond = " WHERE a.id > ". $start;
$cond2 = "";
if(!empty($base)) {
	$cond = $cond . " and a.base = '" . $base ."'";
}
if(!empty($keyword)) {
	$words= preg_split('/ /s', $keyword);
	for($i = 0; $i < sizeof($words); $i=$i+1) {
		$word = $words[$i];
		if(!empty($word)){
			$cond = $cond . " and (a.title like '%" . $word . "%' or a.name like '%" . $word . "%' or a.tag like '%" . $word . "%' or a.content like '%" . $word . "%')";
			
			$cond2 = $cond2 . (empty($cond2) ? "" : " or ") . "a.name like ';%" . $word . "%;'";
		}
	}
}

if(!empty($tag)) {
	$cond = $cond . " and a.tag like '%" . $tag ."%'";
	
	$cond2 = $cond2 . (empty($cond2) ? "" : " or ") . "a.name like '%" . $tag . "%'";
}
if(!empty($category))
	$cond = $cond . " and a.category like '%" . $category ."%'";
if($censor == 0) {
	$cond = $cond . " and (a.censored = 0 or a.censored isnull)";
}else if($censor == 1){
	$cond = $cond . " and a.censored = 1";
}

$sort_cond = "";
if($favi > 0) {
	$sort_cond = "b.datetime desc, a.date desc, a.id desc";
	if($sorts == 1){
		$sort_cond = "a.date asc, a.id asc";
	}
	$cond = $cond . " and b.id not null";
}else{
	$sort_cond = "a.date desc, a.id desc";
	if($sorts == 1){
		$sort_cond = "a.date asc, a.id asc";
	}
}
$sql = "SELECT count(1) FROM items as a left join itemfavi as b on a.id = b.itemId ". $cond;
$result = $pdo->query($sql);
$count = $result->fetchColumn();
$toalPage = ceil($count / $pagesize) - 1;
$begin = $start + $pagesize*($page-1);

$sql = "SELECT a.*,b.id as favi FROM items as a left join itemfavi as b on a.id = b.itemId ". $cond ." order by ".$sort_cond." limit ". $begin . ", ".$pagesize;
$result = $pdo->query($sql);
$total_mess = L('total_mess');
$arr1 = array('%1%','%2%');
$arr2 = array($count.'',($toalPage+1).'');
$total_mess = str_replace($arr1,$arr2,$total_mess);

$res = array();
while ($row = $result->fetch(\PDO::FETCH_ASSOC)){
	array_push($res, $row);
}


$sql2 = "SELECT a.* FROM role as a WHERE ". (empty($cond2) ? "1 = 2" : $cond2) ." ORDER BY a.id desc;";
$result2 = $pdo->query($sql2);
$resRole = array();
while ($row = $result2->fetch(\PDO::FETCH_ASSOC)){
	array_push($resRole, $row);
}

$display_template = 'searchpage.php';
if($display > 0){
	$display_template = 'searchpage_one.php';
}

$template = new Template('templates/' . $display_template);
$template->res = $res;
$template->resRole = $resRole;
$template->sql2 = $sql2;
$template->start = $start;
$template->page = $page;
$template->toalPage = $toalPage;
$template->favi = $favi;
$template->censor = $censor;
$template->total_mess = $total_mess;
$template->category = $category;
$template->tag = $tag;
$template->keyword = $keyword;

$time_cost = round(microtime(true) - $time_start, 3);
$template->timeCost = $time_cost . "s";

$consor_list = array();
$consor0 = array();
$consor0['mess'] = L('all');
if($censor > 1){
	$consor0['active'] = 1;
}
$consor0['href'] = queryString($base, $keyword, $tag, $category, $start, 1, $favi, 2, $sorts, $display);
array_push($consor_list, $consor0);
$consor1 = array();
$consor1['mess'] = L('uncensored');
if($censor == 0){
	$consor1['active'] = 1;
}
$consor1['href'] = queryString($base, $keyword, $tag, $category, $start, 1, $favi, 0, $sorts, $display);
array_push($consor_list, $consor1);
$consor2 = array();
$consor2['mess'] = L('censored');
if($censor == 1){
	$consor2['active'] = 1;
}
$consor2['href'] = queryString($base, $keyword, $tag, $category, $start, 1, $favi, 1, $sorts, $display);
array_push($consor_list, $consor2);
$template->consor_list = $consor_list;

$base_list = array();
$base0 = array();
$base0['mess'] = L('all');
$base0['href'] = queryString('', $keyword, $tag, $category, $start, 1, $favi, $censor, 0, $display);
if($base == ''){
$base0['active'] = 1;
$template->base_name = L('all');
}
array_push($base_list, $base0);
$base1 = array();
$base1['mess'] = 'wallpaper';
$base1['href'] = queryString('wallpaper', $keyword, $tag, $category, $start, 1, $favi, $censor, 0, $display);
if($base == 'wallpaper'){
$base1['active'] = 1;
$template->base_name = 'wallpaper';
}
array_push($base_list, $base1);

$template->base = $base;
$template->base_list = $base_list;

$sort_list = array();
$sort_item0 = array();
$sort_item0['mess'] = L('default');
$sort_item0['href'] = queryString($base, $keyword, $tag, $category, $start, 1, $favi, $censor, 0, $display);
if($sorts == 0){
$sort_item0['active'] = 1;
$template->sort_name = L('default');
}
array_push($sort_list, $sort_item0);
$sort_item1 = array();
$sort_item1['mess'] = L('create_date_asc');
$sort_item1['href'] = queryString($base, $keyword, $tag, $category, $start, 1, $favi, $censor, 1, $display);
if($sorts == 1){
$sort_item1['active'] = 1;
$template->sort_name = L('create_date_asc');
}
array_push($sort_list, $sort_item1);
$template->sort_list = $sort_list;
$template->sorts = $sorts;

$display_list = array();
$display_item0 = array();
$display_item0['mess'] = L('multi_col');
$display_item0['href'] = queryString($base, $keyword, $tag, $category, $start, $page, $favi, $censor, $sorts, 0);
if($display == 0){
$display_item0['active'] = 1;
$template->display_name = L('multi_col');
}
array_push($display_list, $display_item0);
$display_item1 = array();
$display_item1['mess'] = L('single_col');
$display_item1['href'] = queryString($base, $keyword, $tag, $category, $start, $page, $favi, $censor, $sorts, 1);
if($display == 1){
$display_item1['active'] = 1;
$template->display_name = L('single_col');
}
array_push($display_list, $display_item1);
$template->display_list = $display_list;
$template->display = $display;

echo $template;
$pdo = null; 
?>
