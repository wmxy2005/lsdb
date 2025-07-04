<?php
$time_start = microtime(true);
include 'init.php';
if(Allow_Origin_Enable){
	header('Access-Control-Allow-Origin: ' . Allow_Origin);
	header('Access-Control-Allow-Credentials: ' . 'true');
	header('Access-Control-Allow-Headers: ' . 'Content-Type');
}
header('Content-Type: ' . 'application/json;charset=utf-8');

$created = date("i:s");

$cmd = 'wmic cpu get loadpercentage';
exec($cmd, $out);

$result = array();
$result['success'] = true;
$result['time'] = $created;
$result['cpu'] = intval($out[1]);
$result['errorCode'] = 0;
echo json_encode($result);
http_response_code(200);
?>