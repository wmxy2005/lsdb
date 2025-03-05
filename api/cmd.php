<?php
include 'init.php';
header('Access-Control-Allow-Origin: ' . Allow_Origin);
header('Access-Control-Allow-Credentials: ' . 'true');
header('Access-Control-Allow-Headers: ' . 'Content-Type');
header('Content-Type: ' . 'application/json;charset=utf-8');

$shutdown = false;
$restart = false;
$path = '';
$queries = array();
parse_str($_SERVER['QUERY_STRING'], $queries);
if(array_key_exists('shutdown', $queries)) {
	$shutdown = 'true' == $queries['shutdown'];
}
if(array_key_exists('restart', $queries)) {
	$restart = 'true' == $queries['restart'];
}
if(array_key_exists('path', $queries)) {
	$path = rawurldecode($queries['path']);
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$cmd = '';
	if($shutdown) {
		$cmd = 'shutdown -s -f -t 0';
	}else if($restart){
		$cmd = 'shutdown -r -f -t 0';
	}else{
		$conf = Config::$config;
		$base_dir = $conf['folder'];
		$folderPath = $base_dir . DIR_SEP . $path;
		$cmd = 'explorer ' . $folderPath;
	}
	if(!empty($cmd)){
		echo $cmd;
		exec($cmd, $out);
		$updateInfo = [
			'success' => true
		];
		echo json_encode($updateInfo);
		http_response_code(200);
		return;
	}
}
$warnInfo = [
	'errorMessage' => 'Method not allowed'
];
echo json_encode($warnInfo);
http_response_code(405);
?>