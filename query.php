<?php
function queryString($keyword, $tag, $category, $start, $page) {
	$qString = 'search.php';
	$data = array();
	if(!empty($keyword))
		$data["q"] = $keyword;
	if(!empty($tag))
		$data["tag"] = $tag;
	if(!empty($category))
		$data["category"] = $category;
	if(!empty($start))
		$data["start"] = $start;
	if(!empty($page))
		$data["page"] = $page;
	if(sizeof($data) > 0)
		return $qString . '?' . http_build_query($data);
	else
		return $qString;
}
$start = 0;
$page = 0;
$keyword = '';
$tag = '';
$category = '';
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
$pdo = new \PDO('sqlite:'.'mydb.db');
$pagesize = 20;

$cond = " where id > ". $start . (empty($keyword) ? "" : " and (title like '%" . $keyword . "%' or name like '%" . $keyword . "%' or tag like '%" . $keyword . "%')");
if(!empty($tag))
	$cond = $cond . " and tag like '%" . $tag ."%'";
if(!empty($category))
	$cond = $cond . " and category like '%" . $category ."%'";

$sql = "SELECT count(1) FROM items". $cond;
$result = $pdo->query($sql);
$count = $result->fetchColumn();
$toalPage = ceil($count / $pagesize) - 1;
$begin = $start + 20*$page;

$sql = "SELECT * FROM items". $cond ." order by date desc limit ". $begin . ", 20";
$result = $pdo->query($sql);
echo '<div class="searchword alert alert-info">
		<span class="label label-primary">'.(!empty($tag)?$tag:$category).'</span>
		<strong> Total '.$count. ' record(s), '. ($toalPage+1) .' page(s)'. (empty($keyword) ? '' : ', searching for '. $keyword) .'</strong></div>
      <div class="row" style="display:flex; flex-wrap: wrap;">'; 
while ($row = $result->fetch(\PDO::FETCH_ASSOC)){
	echo 
	'<div class="col-sm-6 col-md-4 col-lg-3">
     	<div class="thumbnail">
     	<div class="myimage">
      	<a href="detail.php?id='.$row['id'].'">
        <img class="img-responsive center-block" src="resource.php?base='. $row['base'].'&cata='. $row['category'] .'&subcata='.$row['subcategory'].'&name='. $row['name'].'&filename='. $row['thumbnail'] .'">
        </a>
        </div>
        <div class="caption">
	        <div class="mytitle">
	        <h5>
	            <a href="detail.php?id='.$row['id'].'" data-toggle="tooltip1" title="'.$row['title'].'"><p style="display: -webkit-box;-webkit-line-clamp: 2;-webkit-box-orient: vertical;overflow: hidden;">'
	              . $row['title'] .
	            '</p></a>
	        </h5>
	        </div>
    	</div>
    	<div style="margin-bottom: 5px;">'
        	.(empty($row['subcategory']) ? '<a href="'.queryString('', '', $row['category'], 0, 0).'"><span class="label label-primary">'. $row['category'] .'</span></a>'
        	: '<a href="'.queryString('', $row['subcategory'], 'tag', 0, 0).'"><span class="label label-primary">'. $row['subcategory'] .'</span></a>')
	        .'<div style="float:right; vertical-align: top;">
	          <span class="glyphicon glyphicon-calendar" aria-hidden="true"></span>
	          <span >'. $row['date'] .'</span>
	        </div>
        </div>
    	</div>
    </div>';
}

// Pages
$q = queryString($keyword, $tag, $category, $start, 0);
echo '</div><nav aria-label="...">
	        <ul class="pagination pagination-lg">
	          <li><a href="'. $q .'"
	            <span>
	              <span aria-hidden="true">&laquo;</span>
	            </span>
	          </li>';
if($page > 0) {
	$q = queryString($keyword, $tag, $category, $start, $page-1);
	echo '<li><a href="'. $q .'" aria-label="">
              <span aria-hidden="true">Prev</span>
            </a>
          </li>';
} else {
	echo '<li class="disabled"><a aria-label="">
              <span aria-hidden="true">Prev</span>
            </a>
          </li>';
}

for($i = $page - 5; $i <= $toalPage && $i <= $page+5; $i = $i+1) {
	if($i >= 0) {
		if($i == $page)
			echo '<li class="active"><span>' . ($i+1) .'</span></li>';
		else {
			$q = queryString($keyword, $tag, $category, $start, $i);
			echo '<li><a href="'.$q.'">'. ($i+1) .'</a></li>';
		}
	}
}
if($page < $toalPage) {
	$q = queryString($keyword, $tag, $category, $start, $page+1);
	echo '<li><a href="'. $q .'" aria-label="">
	              <span aria-hidden="true">Next</span>
	            </a>
	          </li>';
} else {
	echo '<li class="disabled"><a aria-label="">
              <span aria-hidden="true">Next</span>
            </a>
          </li>';
}
$q = queryString($keyword, $tag, $category, $start, $toalPage);
echo '<li><a href="'. $q .'" aria-label="">
	              <span aria-hidden="true">»</span>
	            </a>
	          </li>';
echo '</ul></nav>';
$pdo = null; 
?>
