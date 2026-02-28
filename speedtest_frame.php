<?php
$skipAuth = true;
require_once 'core/init.php';
$title = L('speed_test');
$main_menu[2]['active'] = 1;
 ?>
<html lang="<?php echo L('lang'); ?>">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="core/img/favicon.ico">
    <title><?php echo (empty($title)?L('sitename'): $title); ?></title>

    <!-- Bootstrap core CSS -->
    <link href="dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom styles for this template -->
	<link href="core/css/glyphicons.css" rel="stylesheet">
    <link href="core/css/signin.css" rel="stylesheet">
	
	<script>window.jQuery || document.write('<script src="assets/js/vendor/jquery.min.js"><\/script>')</script>
	<script language="JavaScript">
		$(document).ready(function(){
			$("#fixed-top").removeClass("top-loading");
		});
		function updateTop(progress){
			$('#fixed-top').animate({width: progress + '%' });
		};
	</script>
  </head>
<body>
<style type="text/css">
	#main{
		text-align:center;
		font-family:"Roboto",sans-serif;
	}
	h1{
		color:#404040;
	}
	#startStopBtn{
		width: 10em;
		color:#DE5834;
		border-color:#DE5834;
	}
	#startStopBtn:hover{
		color:#FFF;
		background: #DE5834;
	}
	#startStopBtn.running{
		background-color:#FF3030;
		border-color:#FF6060;
		color:#FFFFFF;
	}
	#startStopBtn:before{
		content:"<?php echo L('start'); ?>";
	}
	#startStopBtn.running:before{
		content:"<?php echo L('abort'); ?>";
	}
	#test{
		margin-top:2em;
		margin-bottom:2em;
	}
	div.testArea{
		display:inline-block;
		width:14em;
		height:9em;
		position:relative;
		box-sizing:border-box;
	}
	div.testName{
		position:absolute;
		top:0.1em; left:0;
		width:100%;
		font-size:1.4em;
		z-index:9;
	}
	div.meterText{
		position:absolute;
		bottom:1.5em; left:0;
		width:100%;
		font-size:2.5em;
		z-index:9;
	}
	#dlText{
		color:#6060AA;
	}
	#ulText{
		color:#309030;
	}
	#pingText,#jitText{
		color:#AA6060;
	}
	div.meterText:empty:before{
		color:#505050 !important;
		content:"0.00";
	}
	div.unit{
		position:absolute;
		bottom:2em; left:0;
		width:100%;
		z-index:9;
	}
	div.testGroup{
		display:inline-block;
	}
	#progressBar{
		opacity: 0;
	}
	#progress{
		background-color:#de5833;
	}


</style>
<script type="text/javascript">
function I(id){return document.getElementById(id);}

var w=null; //speedtest worker
function startStop(){
	if(w!=null){
		//speedtest is running, abort
		w.postMessage('abort');
		w=null;
		$("#startStopBtn").toggleClass("running");
		initUI();
	}else{
		//test is not running, begin
		w=new Worker('speedtest_worker.min.js');
		w.postMessage('start'); //Add optional parameters as a JSON object to this command
		$("#startStopBtn").toggleClass("running");
		$("#progressBar").css("opacity","1");
		w.onmessage=function(e){
			var data=JSON.parse(e.data);
			var status=data.testState;
			if(status>=4){
				//test completed
				$("#startStopBtn").toggleClass("running");
				$("#progressBar").css("opacity","0");
				w=null;
			}
			I("ip").textContent=data.clientIp;
			I("dlText").textContent=(status==1&&data.dlStatus==0)?"...":data.dlStatus;
			I("ulText").textContent=(status==3&&data.ulStatus==0)?"...":data.ulStatus;
			I("pingText").textContent=data.pingStatus;
			I("jitText").textContent=data.jitterStatus;
			var prog=(Number(data.dlProgress)*2+Number(data.ulProgress)*2+Number(data.pingProgress))/5;
			var progVar = parseInt(100*prog);
			I("progress").style.width=progVar+"%";
			I("progress").textContent=progVar+"%";
		};
	}
}
//poll the status from the worker every 200ms (this will also update the UI)
setInterval(function(){
	if(w) w.postMessage('status');
},200);
//function to (re)initialize UI
function initUI(){
	I("dlText").textContent="";
	I("ulText").textContent="";
	I("pingText").textContent="";
	I("jitText").textContent="";
	I("ip").textContent="";
	I("progress").style.width="";
	$("#progressBar").css("opacity","0");
}
</script>
<div>
<div id="main" class="p-4 rounded text-body-emphasis">
	<p><a id="startStopBtn" class="btn btn-lg" href="javascript:void();" onclick="startStop()" role="button"></a></p>
	<br>
	<div id="progressBar" class="progress" style="opacity: 0">
      <div id="progress" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
      </div>
      <br>
    </div>
	<div id="test">
		<!-- <div id="progressBar"><div id="progress"></div></div> -->
		<div class="testGroup">
			<div class="testArea">
				<div class="testName">Download</div>
				<div id="dlText" class="meterText"></div>
				<div class="unit">Mbps</div>
			</div>
			<div class="testArea">
				<div class="testName">Upload</div>
				<div id="ulText" class="meterText"></div>
				<div class="unit">Mbps</div>
			</div>
		</div>
		<div class="testGroup">
			<div class="testArea">
				<div class="testName">Ping</div>
				<div id="pingText" class="meterText"></div>
				<div class="unit">ms</div>
			</div>
			<div class="testArea">
				<div class="testName">Jitter</div>
				<div id="jitText" class="meterText"></div>
				<div class="unit">ms</div>
			</div>
		</div>
		<div id="serverArea">
			Server: <?php echo gethostbyname(''); ?>
		</div>
		<div id="ipArea">
			IP Address: <span id="ip"></span>
		</div>
	</div>
</div>
</div>
<script type="text/javascript">initUI();</script>
<script src="dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>