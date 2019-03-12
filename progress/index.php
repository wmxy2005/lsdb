<?php 
session_start(); 
$zs=21;//设置任务总数 
if(!isset($_SESSION['num'])) 
$_SESSION['num']=$zs;//赋予session变量值 
$jd=$_SESSION['num']--;//进度值 
echo $jd;//显示进度值 
echo "<br />"; 
echo 100-round($jd/$zs*100)."%";//显示完成进度百分比 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head> 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
<title>进度条demo--www.jbxue.com</title> 
<script type="text/javascript" language="javascript" src="/js/jquery-1.9.1.min.js"></script> 
<script language="javascript" type="text/javascript"> 
////判断窗体是否最大化 
//if (window.screen) {//判断浏览器是否支持window.screen判断浏览器是否支持screen 
//var myw = screen.availWidth;   //定义一个myw，接受到当前全屏的宽 
//var myh = screen.availHeight;  //定义一个myw，接受到当前全屏的高 
//window.moveTo(0, 0);   //把window放在左上脚 
//window.resizeTo(myw, myh); //把当前窗体的长宽跳转为myw和myh 
//} 
//弹出隐藏层 
function ShowDiv(show_div, bg_div) { 
document.getElementById(show_div).style.display = 'block'; 
document.getElementById(bg_div).style.display = 'block'; 
var bgdiv = document.getElementById(bg_div); 
bgdiv.style.width = document.body.scrollWidth; 
$("#" + bg_div).height($(document).height()); 
}; 
//关闭弹出层 
function CloseDiv(show_div, bg_div) { 
document.getElementById(show_div).style.display = 'none'; 
document.getElementById(bg_div).style.display = 'none'; 
$("#country").val($("#name").val()); 
}; 
//窗口大小改变时 
$(window).resize(function() { 
if (!$('#MyDiv').is(':hidden')) popup(); 
}); 
 
//重新设置遮罩层的高和宽 
function popup() { 
var maskHeight = $(document).height(); 
var maskWidth = $(window).width(); 
$('#fade').css({ height: maskHeight, width: maskWidth }).show(); 
} 
</script> 
<style type="text/css"> 
.black_overlay { display: block; position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-color: black; z-index: 1001; -moz-opacity: 0.5; opacity: 0.5; filter: alpha(opacity=50); -khtml-opacity: 0.5; } 
.white_content { display: block; position: absolute; top: 45%; left: 40%; width: 18%; height: 6%; z-index: 1002; overflow: visible; color: #FFF; } 
<!-- 
.white_content_small { display: block; position: fixed; top: 20%; left: 30%; width: 40%; height: 50%; border: 16px solid lightblue; background-color: white; z-index: 1002; overflow: auto; } 
--> 
#MyDiv .text { text-align: center; } 
#jdtbj { background-color: #ACACAC; height: 13px; width: 208px; } 
#bfb { height:13px; display:block; text-align:center; font-size:11px; color:#333; width:208px; position: absolute; top: 17px; } 
#jdt { height:13px; display:block; text-align:center; font-size:11px; color:#333; background-image: url(loadings.gif); width:<?php echo 100-round($jd/$zs*100)."%";?>; } 
</style> 
</head> 
<body> 
<h1>欢迎访问脚本学堂:<a href="http://www.jbxue.com" target="_blank">http://www.jbxue.com</a></h1> 
<div id="fade" class="black_overlay"></div> 
<div id="MyDiv" class="white_content"> 
<div class="text">正在载入中......</div> 
  <div id="jdtbj"> 
<div id="bfb"><?php echo 100-round($jd/$zs*100)."%";?></div> 
<div id="jdt"></div> 
<h1>欢迎访问脚本学堂:<a href="http://www.jbxue.com" target="_blank">http://www.jbxue.com</a></h1> 
  </div> 
</div> 
</body> 
</html>