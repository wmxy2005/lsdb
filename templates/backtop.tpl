<div id="scroll_to_top">
	<a id="scroll_img" href="javascript:void(0);" title="返回顶部"></a>
</div>
<style type="text/css">
#scroll_to_top {
position: fixed;
_position: absolute;
bottom: 80px;
right: 10px;
width: 70px;
height: 70px;
display: none;
}
#scroll_img {
width: 70px;
height: 70px;
display: inline-block;
background: url(core/img/scroll_top.png) no-repeat;
outline: none;
}
#scroll_img:hover {
filter:brightness(70%);
}
</style>
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