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
	$sql = "SELECT a.*, b.id as favi FROM items as a left join itemfavi as b on a.id = b.itemId where a.id = ". $id;
	$result = $pdo->query($sql);
	if($row = $result->fetch(\PDO::FETCH_ASSOC)) {
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
require_once 'core/initd.php';