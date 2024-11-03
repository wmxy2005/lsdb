<?php require_once 'core/init.php';
require_once 'core/template.php';
$conf = Config::$config;
$dbname = $conf['dbname'];
$pdo = new \PDO('sqlite:'.$dbname);

$id = 0;
$queries = array();
parse_str($_SERVER['QUERY_STRING'], $queries);
if(array_key_exists('id', $queries)) {
	$id = $queries['id'];
}
if ($id > 0) {
	$sql = "SELECT a.*, b.id as favi FROM items as a left join itemfavi as b on a.id = b.itemId and b.expired=0 where a.id = ". $id;
	$result = $pdo->query($sql);
	if($row = $result->fetch(\PDO::FETCH_ASSOC)) {
		if($conf['queryLog']) {
			update_visit($pdo, $row['id']);
		}
		$template = new Template('templates/detailpage.php');
		$template->time_init = $time_init;
		$template->item = $row;
		$template->title = $row['title'];
		$template->favi = $row['favi'];
		$template->id = $row['id'];
		$template->content = $row['content'];
		$template->tag = $row['tag'];
		$template->base = $row['base'];
		$template->category = $row['category'];
		$template->subcategory = $row['subcategory'];
		$template->name = $row['name'];
		$template->images = $row['images'];
		$template->folder = $conf['folder'];
		
		$template->main_menu = $main_menu;
		echo $template;
	} else {
		header('Location: error');
	}
} else {
	header('Location: error');
}

function update_visit($pdo, $itemId) {
	$res = $pdo->exec("insert into itemvisit(itemId, count) select " . $itemId .", 1 where not exists(select id from itemvisit where itemId = " . $itemId ." and uId = 0);");
	$res = $pdo->exec("update itemvisit set count=count+1, datetime=(datetime(CURRENT_TIMESTAMP,'localtime')) where itemId = " . $itemId ." and uId = 0 and datetime < date(CURRENT_DATE,'localtime');");
	$res = $pdo->exec("insert into itemvisitdetail(itemvisitId) select id from itemvisit where itemId = " . $itemId ." and uId = 0;");
}

require_once 'core/initd.php';