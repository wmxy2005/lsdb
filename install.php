<?php require_once 'core/init.php';
$title = L('process_file');
require 'templates/header.tpl';
?>
<style type="text/css">
  #startBtn{
    width: 10em;
    color:#DE5834;
	border-color:#DE5834;
  }
  #startBtn:hover{
	color:#FFF;
    background: #DE5834;
  }
  #startBtn.running{
	background-color:#FF3030;
	border-color:#FF6060;
	color:#FFFFFF;
  }
  #progress{
    background-color:#de5833;
  }
  #log{
    width: 100%;
    min-height: 30em;
    margin-top: 1em;
  }
</style>
<div class="pageinfo jumbotron">
	<div class="container">
	<h4><?php echo L('process_file'); ?></h4>
	</div>
</div>
<div class="container">
    <div class="jumbotron">
        <div id="progressBar" class="progress" style="opacity: 0">
          <div id="progress" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
          </div>
        </div>
		<br>
        <div id="status"></div>
		<br>
        <div class="checkbox">
          <label>
            <input id="appendmode" type="checkbox" checked="checkbox"><?php echo ' '.L('append_mode'); ?>
          </label>
        </div>
        <a id="startBtn" class="btn btn-md btn-block" href="javascript:start();" role="button"><?php echo L('start'); ?></a>
        <textarea id="log"></textarea>
    </div>
</div>
<script language="JavaScript">
function updateProgress(progressValue, sMsg)
{
  document.getElementById("status").innerHTML = sMsg;
  document.getElementById("progress").style.width = progressValue + "%";
}
var isProcessing = false;
function start(){
  if(isProcessing)
    return;
  isProcessing = true;
  $('#log').empty();
  $("#startBtn").text("Running");
  $("#startBtn").toggleClass("disabled");
  $("#startBtn").toggleClass("running");
  $("#progressBar").css("opacity","1");
  log(100, "开始...", "");
  $.ajax({
    type: "POST",
    url: "initfiles.php",
	data : { 
      "appendmode" : $("#appendmode").prop("checked")
     },
    xhrFields: {
        onprogress: function (e) {
          var str = e.currentTarget.responseText;
          handleMessage(str);
        }
    },
    success: function (response) {
      $("#startBtn").text("Start");
	  $("#startBtn").toggleClass("disabled");
      $("#startBtn").toggleClass("running");
	  $("#progressBar").css("opacity","0");
	  isProcessing = false;
    },
    error: function (response) {
      $("#startBtn").text("<?php echo L('start'); ?>");
	  $("#startBtn").toggleClass("disabled");
	  $("#startBtn").toggleClass("<?php echo L('running'); ?>");
	  $("#progressBar").css("opacity","0");
      isProcessing = false;
    }
  });
}
function handleMessage(msg){
  var message = msg.substring(msg.lastIndexOf("\n")+1);
  var status = $.parseJSON(message);
  log(100, status.msg, status.msg);
}
function log(progress, msg, detailed){
  updateProgress(progress, msg);
  if(detailed != "") {
    $('#log').append(detailed+"\r\n");
    var scrollTop = $("#log")[0].scrollHeight;
    $("#log").scrollTop(scrollTop);
  }
}
$(document).ready(function(){
  //start();
});
</script>
<?php require 'templates/footer.tpl'; ?>