<?php require_once 'core/init.php';
$useLoading = true;
$start = 1;
$page = 1;
$keyword = '';
$tag = '';
$category = '';
$favi = 0;
$type = 999;
$sorts = 0;
$display = 0;
$base = '';
$queries = array();
if(array_key_exists('QUERY_STRING', $_SERVER)) {
parse_str($_SERVER['QUERY_STRING'], $queries);
if(array_key_exists('start', $queries)) {
	$start = $queries['start'];
}
if(array_key_exists('page', $queries)) {
	$page = $queries['page'];
}
if(array_key_exists('q', $queries)) {
	$keyword = trim(rawurldecode($queries['q']));
}
if(array_key_exists('tag', $queries)) {
	$tag = trim(rawurldecode($queries['tag']));
}
if(array_key_exists('category', $queries)) {
	$category = trim(rawurldecode($queries['category']));
}
if(array_key_exists('favi', $queries)) {
	$favi = $queries['favi'];
}
if(array_key_exists('type', $queries)) {
	$type = $queries['type'];
}
if(array_key_exists('sorts', $queries)) {
	$sorts = $queries['sorts'];
}
if(array_key_exists('display', $queries)) {
	$display = $queries['display'];
}
if(array_key_exists('base', $queries)) {
	$base = $queries['base'];
}
}
$pagetag = L('sitename');
if(!empty($keyword)) {
	$pagetag = $keyword;
} else if (!empty($tag)) {
	$pagetag = $tag;
} else if (!empty($category)) {
	$pagetag = $category;
}
$title = (empty($myfavi)?$pagetag:L('myfavi')). ' - '.str_replace('%1%',$page,L('page_format'));
if($favi > 0){
	$main_menu[0]['active'] = 1;
}
require 'templates/header.tpl'; ?>
<!-- Main jumbotron for a primary marketing message or call to action -->
<div id="mask" style="position: absolute;background: #f5f5f5;width: 100%; height: 100%; opacity: 0.7;z-index:1000;">
  <!-- <div class="loading" style="opacity: 1"></div> -->
  <!-- <svg id="svg-loading" class="icon">
  <use xlink:href="#icon-loading">
	<symbol id="icon-loading" viewBox="0 0 70 70">
      <circle cx="35" cy="35" r="30" fill="none" stroke="#333" stroke-width="8" stroke-dasharray="20 7"></circle>
    </symbol>
  </use>
  </svg> -->
  <!-- <img id="svg-loading" rel="loading_image" src="core/img/ajax-loader-black.svg"> -->
  <embed id="svg-loading" src="core/img/loader-black.svg"/>
  </div>
</div>
<div id="content">
	<div class="headerinfo container">
		<div class="bd-note">
		<h6 class="mb-0 lh-100">
			<?php echo L('searching'); ?>
		</h6>
		</div>
	</div>
</div>
<link href="core/css/search.css" rel="stylesheet">
<script>
  function searchAll(base, keyword, category, tag, start, page, favi, type, sorts, display) {
    $("#mask").show();
    
    var url="query.php";
	var reqdata = {"base" : base, "keyword" : keyword, "category" : category, "tag" : tag, "start" : start, "page" : page, "favi" : favi, "type" : type, "sorts" : sorts, "display" : display}
    <?php // $.get(url, reqdata, callback); ?>
	$.ajax({
		type: "GET",
		url: "query.php",
		data : reqdata,
		xhrFields: {
			onprogress: function (e) {
			  var str = e.currentTarget.responseText;
			  handleMessage(str);
			}
		},
		success: function (response) {
		  callback(response);
		  updateTop(100);
		},
		error: function (response) {
		  
		}
	  });
  }
  function handleMessage(str){
	if(str.lastIndexOf("\n") < 0){
		return;
	}
	var message = str.substring(str.lastIndexOf("\n")+1);
	if(message && message.startsWith("<!--MSG")) {
	  var reg = new RegExp("<!--MSG:[\\s\\S]*?--\\>");
	  var r = message.match(reg);
	  if (r != null) {
		var msg = r[0].substring(8, r[0].length-3);
		console.log(msg);
		var status = $.parseJSON(msg);
		updateTop(status.progress);
	  }
	}
  }
  function callback(data) {
	$("#mask").hide();
    $('#content').html(data);
    $(document).scrollTop(0);
    $('[data-toggle="tooltip"]').tooltip();
  }
  function getQueryString(name) { 
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i"); 
    var r = window.location.search.substr(1).match(reg); 
    if (r != null)
      return r[2];
    return ""; 
  }
  $(document).ready(function(){
    if(!window.loaded) {
      window.loaded = true;
      /*var keyword = $.trim(getQueryString("q"));
      $('#search-input').val(decodeURI(keyword).replace("+", " "));
      var category = getQueryString("category");
      var tag = getQueryString("tag");
      var page = getQueryString("page");
	    var myfavi = getQueryString("myfavi");
      if(page == '')
        page = 1;*/
      <?php echo '$(\'#search-input\').val(\''.$keyword.'\');'; echo "searchAll('".$base."','".$keyword."','".$category."','".$tag."',0,".$page.",".$favi.",".$type.",".$sorts.",".$display.");";?>
    }
  });
</script>
<?php require 'templates/footer.tpl';
require_once 'core/initd.php';
?>