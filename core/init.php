<?php
class TimeInit
{
   public $logs = array();
   public $init;
   public function __construct(){
	   $this->init = microtime(true);
   }
}
$time_init = new TimeInit();
require_once 'core/config.my.php';
require_once 'core/mess.php';
const DIR_SEP = DIRECTORY_SEPARATOR;
$conf = Config::$config;
$lang = $conf['lang'];
$GLOBALS['lang'] = new Mess($lang);
function L($messCode) {
	return $GLOBALS['lang']->mess[$messCode];
}
$checkAuth = true;
if(!empty($skipAuth)){
	$checkAuth = false;
}
if($checkAuth){
	$reAuth = false;
	$isAuth = false;
	$existCache = false;
	if (!empty($_SERVER['PHP_AUTH_USER']) && !empty($_SERVER['PHP_AUTH_PW']) && $conf['user'] == $_SERVER['PHP_AUTH_USER'] && $conf['pwd'] == sha1($_SERVER['PHP_AUTH_PW'])) {
		$isAuth = true;
	}else{
		if (!empty($_COOKIE['user']) && !empty($_COOKIE['password'])) {
			$existCache = true;
			if($conf['user'] == $_COOKIE['user'] && $conf['pwd'] == sha1($_COOKIE['password'])) {
				$_SERVER["PHP_AUTH_USER"]=$_COOKIE['user'];
				$_SERVER["PHP_AUTH_PW"]=$_COOKIE['password'];
				$isAuth = true;
			}else{
				$reAuth = true;
			}
		}else{
			$reAuth = true;
		}
	}
	if($reAuth){
		header ( "WWW-Authenticate: Basic realm=\"LSDB\"");
		if(!empty($_SERVER['PHP_AUTH_USER']) && !empty($_SERVER['PHP_AUTH_PW']) && $conf['user'] == $_SERVER['PHP_AUTH_USER'] && $conf['pwd'] == sha1($_SERVER['PHP_AUTH_PW'])){
			setcookie('user',$_SERVER['PHP_AUTH_USER'],time()+3600);
			setcookie('password',$_SERVER['PHP_AUTH_PW'],time()+3600);
			$isAuth = true;
		}
	}
	if (!$isAuth) {
		header ("HTTP/1.0 401 Unauthorized");
		if($existCache){
		setcookie("user", '', time()-3600);
		setcookie("password", '', time()-3600);
		}
		echo L('unauth_user');
		exit;
	}
}
$main_menu = array();
$menu0 = array();
$menu0['mess'] = L('favi');
$menu0['href'] = 'search?favi=1';
array_push($main_menu, $menu0);
$menu1 = array();
$menu1['mess'] = L('install');
$menu1['href'] = 'install';
array_push($main_menu, $menu1);
$menu2 = array();
$menu2['mess'] = L('speedtest');
$menu2['href'] = 'speedtest';
array_push($main_menu, $menu2);
$menu3 = array();
$menu3['mess'] = L('tools');
$menu3['href'] = 'tools';
array_push($main_menu, $menu3);
$menu4 = array();
$menu4['mess'] = L('history');
$menu4['href'] = 'history';
// array_push($main_menu, $menu4);

function queryString($base, $keyword, $tag, $tag2, $tag3, $category, $start, $page, $favi, $type, $sorts, $display) {
	$qString = 'search';
	$data = array();
	if(!is_null($type)){
		if($type <= 99)
			$data["type"] = $type;
	}
	if(!empty($sorts))
		$data["sorts"] = $sorts;
	if(!empty($display)){
		$data["display"] = $display;
	}
	if(!empty($favi) && $favi > 0)
		$data["favi"] = $favi;
	if(!empty($base))
		$data["base"] = $base;
	if(!empty($keyword))
		$data["q"] = $keyword;
	if(!empty($tag))
		$data["tag"] = $tag;
	if(!empty($tag2))
		$data["tag2"] = $tag2;
	if(!empty($tag3))
		$data["tag3"] = $tag3;
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
function getImagePath($base_dir, $base, $cate, $subcate, $name, $filename) {
	$filepath = $base_dir. DIR_SEP. $base . DIR_SEP . (empty($cate) ? "" : $cate . DIR_SEP) . (empty($subcate) ? "" : $subcate . DIR_SEP) . (empty($name) ? "" : $name . DIR_SEP) . $filename;
	return $filepath;
}
function getImageUrl($base, $cate, $subcate, $name, $filename) {
	$image_url = 'resource?' . 'base=' . $base . (empty($cate) ? "" : '&cata=' . $cate) . (empty($subcate) ? "" : '&subcata=' . $subcate) . (empty($name) ? "" : '&name=' . $name) . '&filename=' . $filename;
	return $image_url;
}
function str_starts_with($haystack, $needle) {
	if ( '' === $needle ) {
		return true;
	}
	return 0 === strpos($haystack, $needle);
}
function str_ends_with($haystack, $needle) {
	if ( '' === $haystack && '' !== $needle ) {
		return false;
	}
	$len = strlen($needle);
	return 0 === substr_compare($haystack, $needle, -$len, $len);
}
$GLOBALS['styleTypes'] = array("primary", "secondary", "success", "danger", "warning", "info", "light", "dark");
function get_style_type($index) {
	$i = ($index) % sizeof($GLOBALS['styleTypes']);
	return $GLOBALS['styleTypes'][$i];
}
?>