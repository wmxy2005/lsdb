<?php
$status = false;
$queries = array();
parse_str($_SERVER['QUERY_STRING'], $queries);
echo $status;
if(array_key_exists('status', $queries)) {
	$status = true;
}

if(!$status) {
	session_start();
	if(true != $_SESSION['inProgress']) {
		$_SESSION['inProgress'] = true;
		$_SESSION['progress'] = 0;
		session_write_close();

		$total = 20;
		$p = 0;
		for ($i=1; $i <= $total; $i++) { 
			sleep(1);
			$np = round(100*$i/$total);
			if($np >= $p+5) {
				session_start();
				$_SESSION['progress'] = $np;
				session_write_close();
				$p = $np;
			}
		}

		session_start();
		$_SESSION['inProgress'] = false;

		$result = array();
		$result['code'] = 200;
		$result['progress'] = $_SESSION['progress'];
		echo json_encode($result);
	} else {
		$result = array();
		$result['code'] = 400;
		echo json_encode($result);
	}

	
} else {
	session_start();
	$p = 0;
	if(isset($_SESSION['progress'])) 
		$p = $_SESSION['progress'];
	$result = array();
	$result['code'] = 200;
	$result['progress'] = $p;
	echo json_encode($result);
}
?>