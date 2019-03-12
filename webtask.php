<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="./favicon.ico">

    <title>Dashboard Template for Bootstrap</title>

    <!-- Bootstrap core CSS -->
    <link href="./dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="./assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <link rel="stylesheet" href="header.css">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="./assets/js/ie-emulation-modes-warning.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>
    <div class="site-wrapper-border navbar-fixed-top"></div>
    <nav class="navbar navbar-default navbar-fixed-top header" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar">
            <span class="sr-only">切换导航</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          </button>
          <a href="search.php"><img style="max-height: 50px" src="logo.svg" alt="BTDB LOGO" class="logo"></a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
            <li><a href="#">Setting</a></li>
          </ul>
          <div class="search-area">
          <form action="search.php" method="get" id="search-form" class="navbar-right search-form">
            <input id="search-input" aria-label="Search" title="LDBS Search" name="q" maxlength="2048" class="search-input" aria-haspopup="false" role="combobox" aria-autocomplete="both" spellcheck="false" autocomplete="off" type="text">
            <button class="search-btn">
              <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
            </button>
          </form>
          </div>
        </div>
      </div>
    </nav>
	
	<div class="container">
        <div class="jumbotron">
            <h3>Processing files</h3>
            <div class="progress">
              <div id="progress" class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
                0%
              </div>
            </div>
            <div id="status"></div>
            <p><a class="btn btn-primary btn-md" href="javascript:start();" role="button"> Start Task</a></p>
            <textarea id="log" style="width: 100%; height: 500px;"></textarea>
        </div>
    </div>
	
    <footer class="footer">
      <div class="container">
        <p class="text-muted">Place sticky footer content here.</p>
      </div>
    </footer>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="./assets/js/vendor/jquery.min.js"><\/script>')</script>
    <script src="./dist/js/bootstrap.min.js"></script>
    <!-- Just to make our placeholder images work. Don't actually copy the next line! -->
    <script src="./assets/js/vendor/holder.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="./assets/js/ie10-viewport-bug-workaround.js"></script>
<style type="text/css">
body > .container  {
  padding: 110px 15px 50px;
}
</style>
<script language="JavaScript">
function updateProgress(sMsg, progressValue)
{
  document.getElementById("status").innerHTML = sMsg;
  document.getElementById("progress").innerHTML = (progressValue < 100 ? progressValue+"%" : "Completed");
  document.getElementById("progress").style.width = progressValue + "%";
}
var socket;
function start() {
  link();
}
function link(){
  var url='ws://localhost:8000';
  socket=new WebSocket(url);
  socket.onopen=function(){log(0, 'Start Progress');socket.send('start');}
  socket.onmessage=message;
  socket.onclose=function(){log(100, 'Completed')}
}
function dis(){
  socket.close();
  socket=null;
}
function message(msg){
  var data = $.parseJSON(msg.data);
  log(data.progress, data.msg);
}
function log(progress, msg){
  updateProgress('', progress);
  $('#log').append(msg+"\r\n");
  var scrollTop = $("#log")[0].scrollHeight;
  $("#log").scrollTop(scrollTop);
}
</script>
</body>
</html>