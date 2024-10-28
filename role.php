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
	$sql = "SELECT a.* FROM role as a where a.id = ". $id;
	$result = $pdo->query($sql);
	if($row = $result->fetch(\PDO::FETCH_ASSOC)) {
		$template = new Template('templates/rolepage.php');
		$template->role = $row;
		$template->id = $row['id'];
		$template->datetime = $row['date'];
		$template->title = $row['title'];
		$template->roleNames = trim($row['name']);
		$template->roleImges = $row['images'];
		$template->content = $row['remark'];
		
		$template->base = $conf['role'];
		$template->main_menu = $main_menu;
		echo $template;
	} else {
		header('Location: error');
	}
} else {
	header('Location: error');
}