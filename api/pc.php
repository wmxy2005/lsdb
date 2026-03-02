<?php
function getSystemCpuUsageWindows() {
    $wmi = new COM('winmgmts://./root/cimv2');
    $cpus = $wmi->ExecQuery('SELECT * FROM Win32_PerfFormattedData_PerfOS_Processor WHERE Name="_Total"');
    foreach ($cpus as $cpu) {
        return $cpu->PercentProcessorTime; // 当前 CPU 使用率
    }
    return 0;
}

$time_start = microtime(true);
include 'init.php';
if(Allow_Origin_Enable){
	header('Access-Control-Allow-Origin: ' . Allow_Origin);
	header('Access-Control-Allow-Credentials: ' . 'true');
	header('Access-Control-Allow-Headers: ' . 'Content-Type');
}
header('Content-Type: ' . 'application/json;charset=utf-8');

date_default_timezone_set('Asia/Shanghai');
$created = date("H:i:s");

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