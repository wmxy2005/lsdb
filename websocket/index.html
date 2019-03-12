<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>websocket_TEST</title>
</head>
<body>
<textarea class="log" style="width: 100%; height: 500px;">
=======websocket======
</textarea>
<input type="button" value="连接" onClick="link()"> 
<input type="button" value="断开" onClick="dis()">
<input type="text" id="text">
<input type="button" value="发送" onClick="send()">
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>
<script>
function link(){
  var url='ws://127.0.0.1:8000';
  socket=new WebSocket(url);
  socket.onopen=function(){log('连接成功')}
  socket.onmessage=function(msg){log('获得消息:'+msg.data);console.log(msg);}
  socket.onclose=function(){log('断开连接')}
}
function dis(){
  socket.close();
  socket=null;
}
function log(var1){
  $('.log').append(var1+"\r\n");
}
function send(){
  socket.send($('#text').attr('value'));
}
function send2(){
  var json = JSON.stringify({'type':'php','msg':$('#text2').attr('value')})
  socket.send(json);
}
</script>
</body>
</html>