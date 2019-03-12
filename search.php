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

    <!-- Custom styles for this template -->
    <link href="search.css" rel="stylesheet">
    <link href="header.css" rel="stylesheet">

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
    <!-- <div class="site-wrapper-border navbar-fixed-top"></div>
    <div class="header navbar-fixed-top">
      <div class="container-fluid">
      <div class="header-content navbar-header">
        <a href="/myweb/search.php"><img src="logo.svg" alt="BTDB LOGO" class="logo"></a>
        <div class="search-area">
        <form name="f" role="search" accept-charset="utf-8" class="search-form" id="search-form" onsubmit="return false;">
          <input id="search-input" aria-label="Search" title="LDBS Search" name="q" maxlength="2048" class="search-input" aria-haspopup="false" role="combobox" aria-autocomplete="both" spellcheck="false" autocomplete="off" type="text">
          <button class="search-btn" onclick="search(0, 0);">
            <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
          </button>
        </form>
        </div>
      </div>
      
      </div>
    </div> -->
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
            <li><a href="webtask.php">Progress</a></li>
            <li><a href="setup.php">Setup</a></li>
          </ul>
          <div class="search-area">
          <form id="search-form" class="navbar-right search-form" onsubmit="">
            <input id="search-input" aria-label="Search" title="LDBS Search" name="q" maxlength="2048" class="search-input" aria-haspopup="false" role="combobox" aria-autocomplete="both" spellcheck="false" autocomplete="off" type="text">
            <button class="search-btn" onclick="">
              <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
            </button>
          </form>
          </div>
        </div>
      </div>
    </nav> 

    <!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="mask" style="width: 100%; height: 100%; opacity: 0.7">
      <div class="loading" style="opacity: 1"></div>
    </div>
    <div id="content" class="container">
      <div class="container searchword alert alert-success">
        <h4>Searching for result...</h4>
      </div>
      
      <!-- <div class="row display-flex">
        <div class="col-sm-6 col-md-3 placeholder">
            <div class="thumbnail">
                <div class="myimage">
                <a href="/myweb/detail?category=sky-high-ent&amp;name=sky-254">
                <img src="/myweb/resource.php?base=javfree&amp;cata=sky-high-ent&amp;subcata=&amp;name=sky-254&amp;filename=SKY-254.jpg">
                </a>
                </div>
                <div class="caption">
                    
                    <div class="mytitle dot-ellipsis dot-resize-update dot-height-50">
                    <h4>
                        <a href="/">[HD][SKY-254][SKYHD-093] ゴールドエンジェル OOOOOOOOOOOO Vol.24 : 北条麻妃</a>
                    </h4>
                    </div>
                    
                </div>
                <div>
                    <span class="label label-primary">sky-high-ent</span>
                    <a href="#" class="btn btn-default btn-xs" role="button">Info</a>
                    </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3 placeholder">
            <div class="thumbnail">
                <div class="myimage">
                <a href="/myweb/detail?category=1pondo&amp;name=1pondo-100418_751">
                <img src="/myweb/resource.php?base=javfree&amp;cata=1pondo&amp;subcata=&amp;name=1pondo-100418_751&amp;filename=100418_751-1pon.jpg">
                </a>
                </div>
                <div class="caption">
                    
                    <div class="mytitle dot-ellipsis dot-resize-update dot-height-50">
                    <h4><a href="/">More of the same text sapien massa, aliquam in cursus ut, ullamcorper in tortor. 
                    Aliquam codeply mauris arcu..OOOOO</a></h4>
                    </div>
                    
                </div>
                <div>
                    <span class="label label-primary">sky-high-ent</span>
                    <span class="label label-primary">2018-09-28</span>
                    </div>
            </div>
        </div>
    </div> -->
      <!-- <nav aria-label="...">
        <ul class="pagination pagination-lg">
          <li class="disabled">
            <span>
              <span aria-hidden="true">&laquo;</span>
            </span>
          </li>
          <li>
            <a href="/">1</a>
          </li>
          <li>
            <a href="javascript:search(2);">2</a>
          </li>
          <li>
            <a href="/">3</a>
          </li>
          <li>
            <a href="/">4</a>
          </li>
          <li>
            <a href="/">5</a>
          </li>
          <li class="disabled">
            <span>...<span class="sr-only"></span></span>
          </li>
          <li>
            <a href="#" aria-label="Next">
              <span aria-hidden="true">»</span>
            </a>
          </li>
        </ul>
      </nav> -->
    </div><!--/.container-->
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
          $('#search-input').val(decodeURI(keyword));
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
  </body>
</html>
