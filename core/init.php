<?php require_once 'core/config.php';
require_once 'core/mess.php';
$conf = Config::$config;
$lang = $conf['lang'];
$GLOBALS['lang'] = new Mess($lang);
function L($messCode) {
	return $GLOBALS['lang']->mess[$messCode];
}

$isAuth = false;
if (empty($_SERVER['PHP_AUTH_USER']) || empty($_SERVER['PHP_AUTH_PW'])) {
	if (!empty($_COOKIE['user']) && !empty($_COOKIE['password'])) {
		$_SERVER["PHP_AUTH_USER"]=$_COOKIE['user'];
		$_SERVER["PHP_AUTH_PW"]=$_COOKIE['password'];
	}else{
		header ( "WWW-Authenticate: Basic realm=\"LSDB\"");
		$isAuth = true;
	}
}
$reAuth = false;
if (empty($_SERVER['PHP_AUTH_USER']) || empty($_SERVER['PHP_AUTH_PW'])) {
	$reAuth = true;
}else if($_SERVER['PHP_AUTH_USER'] != $conf['user'] || $_SERVER['PHP_AUTH_PW'] != $conf['pwd']){
	$reAuth = true;
}
if(!$isAuth && $reAuth){
	header ( "WWW-Authenticate: Basic realm=\"LSDB\"");
}
if (empty($_SERVER["PHP_AUTH_USER"]) || empty($_SERVER["PHP_AUTH_PW"])) {
	header ("HTTP/1.0 401 Unauthorized");
	echo L('unauth_user');
	exit;
}
if($_SERVER['PHP_AUTH_USER'] == $conf['user'] && $_SERVER['PHP_AUTH_PW'] == $conf['pwd']){
	if(!empty($conf['cookie']) || empty($conf['user']) || empty($conf['password'])){
		setcookie('user',$_SERVER['PHP_AUTH_USER'],time()+3600);
		setcookie('password',$_SERVER['PHP_AUTH_PW'],time()+3600);
	}
    $authorization = true;
}else{
	header ("HTTP/1.0 401 Unauthorized");
	setcookie("user", '');
	setcookie("password", '');
	echo L('unauth_user');
	exit;
}
function queryString($keyword, $tag, $category, $start, $page, $myfavi) {
	$qString = 'search';
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
	if(!empty($myfavi) && $myfavi > 0)
		$data["myfavi"] = $myfavi;
	if(sizeof($data) > 0)
		return $qString . '?' . http_build_query($data);
	else
		return $qString;
}
?>