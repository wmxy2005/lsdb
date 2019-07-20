<div id="scroll_to_top" class="d-fixed">
	<a class="scroll_img" href="javascript:void(0);" title="返回顶部"></a>
</div>
<script>
// 返回顶部
var jscroll_to_top = $('#scroll_to_top');
$(window).scroll(function() {
	if ($(window).scrollTop() >= 500) {
	   jscroll_to_top.fadeIn(300);
	} else {
		jscroll_to_top.fadeOut(300);
	}
});

jscroll_to_top.on('click', function() {
	$('html,body').animate({scrollTop: '0px' }, 100);
});
</script>
<style type="text/css">
@-webkit-keyframes rocket {
0% {
    background-position: 0 0;
}
25% {
    background-position: -150px 0;
}
50% {
    background-position: -300px 0;
}
75% {
    background-position: -450px 0;
}
100% {
    background-position: -600px 0;
}
}
@keyframes rocket {
0% {
    background-position: 0 0;
}
25% {
    background-position: -150px 0;
}
50% {
    background-position: -300px 0;
}
75% {
    background-position: -450px 0;
}
100% {
    background-position: -600px 0;
}
}
#scroll_to_top{
position: fixed;
_position: absolute;
bottom: 80px;
right: 10px;
width: 150px;
height: 174px;
display: none;
background-image: url(core/img/lsdb_top.png);
}
#scroll_to_top:hover{
	background-image: url(core/img/lsdb_frame.png);
	-webkit-animation: rocket steps(1, start) 0.5s infinite;
	animation: rocket steps(1, start) 0.5s infinite;
}
</style>