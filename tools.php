<?php
$skipAuth = true;
require_once 'core/init.php';
$title = L('tools');
$main_menu[3]['active'] = 1;
require 'templates/header.tpl';
?>
<style type="text/css">
.startBtn{
	width: 10em;
	color:#DE5834;
	border-color:#DE5834;
}
.startBtn:hover{
	color:#FFF;
	background: #DE5834;
}
.startBtn.running{
	background-color:#FF3030;
	border-color:#FF6060;
	color:#FFFFFF;
}
</style>
<div id="content" class="container">
<div class="headerinfo container">
	<div class="bd-note">
	<h4 class="mb-0 lh-100">
		<span class="badge badge-pill badge-secondary"></span><?php echo L('tools'); ?></h4>
	</div>
</div>
<div class="p-4 p-md-5 mb-4 rounded text-body-emphasis bg-body-secondary">
	<a id="shutDownBtn" class="shutdownBtn startBtn btn btn-md btn-block" href="javascript:shutdown();" role="button"><?php echo L('shutdown'); ?></a>
	<br>
	<br>
	<a id="restartBtn" class="restartBtn startBtn btn btn-md btn-block" href="javascript:restart();" role="button"><?php echo L('restart'); ?></a>
</div>
</div>
<script language="JavaScript">
function shutdown(){
	$(".shutdownBtn").toggleClass("disabled");
	$(".shutdownBtn").toggleClass("running");
	exec("shutdown -s -f -t 0");
	//exec("shutdown -l");
};
function restart(){
	$(".restartBtn").toggleClass("disabled");
	$(".restartBtn").toggleClass("running");
	exec("shutdown -r -f -t 0");
};
function exec(msg){
	$.ajax({
		type: "POST",
		url: "exec.php",
		data : {
			"cmd":msg
		},
		success: function (response) {
			console.log(response);
		},
		error: function (response) {
			alert('failed');
		}
	});
}
</script>
<?php require 'templates/footer.tpl'; ?>