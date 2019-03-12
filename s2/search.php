<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Local Data Search</title>
	<meta name="description" content="Search Local Data"/>
	<link rel="stylesheet" href="./css/style.css">
	<link rel="stylesheet" href="./css/font-awesome.min.css" media="all">
</head>
<body>
<div class="container">
	<div class="site-wrapper-border"></div>
		<div class="header">
			
			<div class="header-content">
				<a href="https://ko.btdb.to/"><img src="./img/logo.png" alt="BTDB LOGO" class="logo"></a>
				<div class="search-area">
				<form name="f" role="search" accept-charset="utf-8" class="search-form" id="search-form" onsubmit="return false;">
					<input id="search-input" aria-label="Search" title="LDBS Search" name="q" maxlength="2048" class="search-input" aria-haspopup="false" role="combobox" aria-autocomplete="both" spellcheck="false" autocomplete="off" type="text">
					<button class="search-btn" onclick="search()"><i class="fa fa-search"></i></button>
				</form>
				</div>
			</div>
		</div>
	<div class="mask">
		<div class="loading"></div>
	</div>
	
	<div id="content" class="content clearfix">
		<div class="left-content">
		<div class="about">
			<h1>About Local Data Search</h1>
			<a href="">LDBS</a> is the local file search engine.<br><br>
			LDBS's database is formed by users.<br>
			Users can add tags to files, and search files by name, tag, etc<br><br>
			<p id="remove"><b>Takedown Instructions</b></p>
			<ol>
				<li>Remember that this is just a search tool, your content is not hosted here. Please be polite. There is no point in threats.</li>
				<li>Please only provide URLs containing ID values. (Ex: https://btdb.to/torrent/<b>579qo3wbbYfQq36b8xwBFDdQbv4l1mf8on</b>.html)</li>
				<li>Takedowns that do not provide URLs with uniquely identifying ID values will take considerably more time to process!</li>
				<li>Avoid sending search query URLs. List specific results you want removed.</li>
				<li>Use your company/business email. Free mailboxes (AOL, Yahoo, Hotmail, Gmail, etc) will take more time to process and verify.</li>
				<li>Google <a href="http://www.google.com/search?q=dmca+notice">DMCA Notice</a> if you never done this before.</li>
				<li>Send your takedowns in plain text to <a href="mailto:bittorrent.db+remove@gmail.com">bittorrent.db+remove@gmail.com</a> only. Takedowns sent to ISPs or other addresses might not get filed properly.</li>
			</ol>
		</div>
		<div class="friends_links">
			<h3>Friends Links</h3>
			<a rel="external nofollow" target="_blank" href="https://torrents.me/">https://torrents.me</a>
		</div>
		<div>
			<br><br>
			<h3>Query Page</h3>
			<a rel="external nofollow" href="/myweb/query.php">query page</a>
		</div>
		</div>
	</div>
	<div class="footer">
		<div class="footer-content">
			<div class="pull-left">
			© 2018 Local Search
			</div>
		</div>
	</div>
</div>
<style type="text/css">
	.header {
            width:100%;
            height:4rem;
            position:sticky;
            z-index:5;
            top:0;
            text-align:center;
    }

	.mask {       
		position: absolute;
		top: 0px;
		bottom: : 0px;
		filter: alpha(opacity=60);
		background-color: #fff;     
		z-index: 9999; 
		left: 0px;
		right: 0px   
		opacity:0.5;
		-moz-opacity:0.5;
	}
    .loading{
        /*固定loading*/
        position: fixed;
        top: 50%;
        left: 50%;
        /*垂直水平居中*/
        margin: -20px 0 0 -20px;
        width: 3em;
        height: 3em;
        border: .2em solid;
        border-color: #333 #333 transparent;
        border-radius: 50%;
        box-sizing: border-box;
        animation: loading 1s linear infinite;
        opacity: 0
    }
    @keyframes loading{
        0%{
            transform: rotate(0deg);
        }
        100%{
            transform: rotate(360deg);
        }
    }
</style>
<script>
	function search() {
		$(".mask").css("height", $(document).height());     
		$(".mask").css("width", $(document).width()); 
		$(".mask").css("opacity", .7);
		$(".loading").css("opacity", 1); 
		$(".mask").show();

		var keyword = $('#search-input').val();
		//$('#content').html('<div class="loading"></div>');
		var url="/myweb/query.php"; 
		$.get(url, {"keyword" : keyword}, callback);
	}
	function callback(data) {
		$('#content').html(data);
		$(".mask").hide();
	}
</script>
<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
</body>
</html>