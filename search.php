<?php require_once 'core/init.php';
$start = 1;
$page = 1;
$keyword = '';
$tag = '';
$category = '';
$myfavi = 0;
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
if(array_key_exists('myfavi', $queries)) {
	$myfavi = $queries['myfavi'];
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
  <embed id="svg-loading" src="core/img/ajax-loader-black.svg"/>
  </div>
</div>
<div id="content">
  <div class="pageinfo jumbotron">
    <div class="container">
	<p class="font-weight-bold"><?php echo L('searching'); ?></p>
    </div>
  </div>
</div>

<link href="core/css/search.css" rel="stylesheet">
<script>
  function search(start, page) {
    var keyword = $.trim($('#search-input').val());
    searchAll(keyword, "", "", start, page, 0);
  }
  function searchCategory(category, start, page) {
    searchAll("", category, "", start, page, 0);
  }
  function searchTag(tag, start, page) {
    searchAll("", "", tag, start, page, 0);
  }
  function searchAll(keyword, category, tag, start, page, myfavi) {
    $("#mask").show();
    
    var url="query.php";
    $.get(url, {"keyword" : keyword, "category" : category, "tag" : tag, "start" : start, "page" : page, "myfavi" : myfavi}, callback);
  }
  function callback(data) {
	$("#mask").remove();
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
      <?php echo '$(\'#search-input\').val(\''.$keyword.'\');'; echo "searchAll('".$keyword."','".$category."','".$tag."',0,".$page.",".$myfavi.");";?>
    }
  });
</script>
<?php require 'templates/footer.tpl'; ?>