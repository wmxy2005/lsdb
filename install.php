<?php require 'header.tpl'; ?>
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
  #progress{
    background-color:#de5833;
  }
  #log{
    width: 100%;
    min-height: 30em;
    margin-top: 1em;
  }
</style>
	<div class="container">
    <div class="jumbotron">
        <h3>Processing files</h3>
        <div class="progress">
          <div id="progress" class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
          </div>
        </div>
        <div id="status"></div>
        <div class="checkbox">
          <label>
            <input id="appendMode" type="checkbox" value="append-mode"> Append mode
          </label>
        </div>
        <a id="startBtn" class="btn btn-md btn-block btn-outline-primary" href="javascript:start();" role="button">Start</a>
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
  $("#startBtn").attr("disabled","true");
  $("#startBtn").attr("disabled",true);
  $("#startBtn").attr("disabled","disabled");
  log(100, "开始...", "");
  $.ajax({
    type: "POST",
    url: "initfiles.php",
    xhrFields: {
        onprogress: function (e) {
          var str = e.currentTarget.responseText;
          handleMessage(str);
        }
    },
    success: function (response) {
      $("#startBtn").removeAttr("disabled");
      $("#startBtn").attr("disabled",false);
      isProcessing = false;
    },
    error: function (response) {
      $("#startBtn").removeAttr("disabled");
      $("#startBtn").attr("disabled","false");
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
<?php require 'footer.tpl'; ?>