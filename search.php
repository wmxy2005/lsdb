<?php require 'header.tpl'; ?>
<!-- Main jumbotron for a primary marketing message or call to action -->
<div class="mask" style="width: 100%; height: 100%; opacity: 0.7">
  <div class="loading" style="opacity: 1"></div>
</div>
<div id="content" class="container">
  <div class="container searchword alert alert-success">
    <h4>Searching for result...</h4>
  </div>
</div>

<link href="search.css" rel="stylesheet">
<script>
  var loaded = false;
  function search(start, page) {
    var keyword = $.trim($('#search-input').val());
    searchAll(keyword, "", "", start, page);
  }

  function searchCategory(category, start, page) {
    searchAll("", category, "", start, page);
  }

  function searchTag(tag, start, page) {
    searchAll("", "", tag, start, page);
  }

  function searchAll(keyword, category, tag, start, page) {
    $(".mask").show();
    
    var url="query.php"; 
    $.get(url, {"keyword" : keyword, "category" : category, "tag" : tag, "start" : start, "page" : page}, callback);
  }
  function callback(data) {
    $('#content').html(data);
    $(".mask").hide();
    $(document).scrollTop(0);
    $('[data-toggle="tooltip"]').tooltip();
  }
  $(document).ready(function(){
    if(!loaded) {
      loaded = true;
      var keyword = $.trim(getQueryString("q"));
      $('#search-input').val(decodeURI(keyword).replace("+", " "));
      var category = getQueryString("category");
      var tag = getQueryString("tag");
      var page = getQueryString("page");
      if(page == '')
        page = 0;
      searchAll(keyword, category, tag, 0, page);
    }
  });
  function getQueryString(name) { 
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i"); 
    var r = window.location.search.substr(1).match(reg); 
    if (r != null)
      return r[2];
    return ""; 
  }
</script>
<?php require 'footer.tpl'; ?>