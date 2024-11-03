<?php
if(array_key_exists("id",$_POST) && array_key_exists("favi",$_POST)) {
$itemId = $_POST['id'];
$isItemFavi = ("true" == $_POST['favi'] ? true : false);
if($itemId > 0) {
	require_once 'core/init.php';
	$conf = Config::$config;
	$dbname = $conf['dbname'];
	$pdo = new \PDO('sqlite:'. $dbname);
	if($isItemFavi) {
		$res = $pdo->exec("insert into itemfavi(itemId) select " . $itemId ." where not exists(select id from itemfavi where uId = 0 and itemId = " . $itemId .")");
		$result = $pdo->exec("update itemfavi set expired=0, datetime=(datetime(CURRENT_TIMESTAMP,'localtime')) where uId = 0 and itemId = " . $itemId );
	} else {
		$result = $pdo->exec("update itemfavi set expired=1 where uId = 0 and itemId = " . $itemId );
	}
	$pdo = null;
	return;
}
}
http_response_code(404);
?>