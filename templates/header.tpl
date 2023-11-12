<!DOCTYPE html>
<html lang="<?php echo L('lang'); ?>">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="core/img/favicon.ico">
    <title><?php echo (empty($title)?L('sitename'): $title); ?></title>

    <!-- Bootstrap core CSS -->
    <link href="dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom styles for this template -->
	<link href="core/css/glyphicons.css" rel="stylesheet">
    <link href="core/css/header.css" rel="stylesheet">
    <link href="core/css/signin.css" rel="stylesheet">
	
	<script>window.jQuery || document.write('<script src="assets/js/vendor/jquery.min.js"><\/script>')</script>
	<script>
		$(document).ready(function(){
			$("#fixed-top").removeClass("top-loading");
		});
	</script>
  </head>
<body>
<div id="fixed-top" class="site-wrapper-border fixed-top top-loading"></div>

<nav class="navbar navbar-expand-md bg-body-tertiary fixed-top header" role="navigation">
  <div class="container">
    <a href="search"><img style="max-height: 50px" src="core/img/logo.svg" alt="LSDB LOGO" class="logo"></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto mb-2 mb-sm-0">
		<?php
		for ($row=0; $row < count($main_menu); $row++) {
		$item = $main_menu[$row];
		?>
		<li class="nav-item"><a class="nav-link <?php echo !empty($item['active'])?'active':''; ?>" href="<?php echo $item['href']; ?>"><span><?php echo $item['mess']; ?></span></a></li>
		<?php
		}
		?>
      </ul> 

      <div class="search-area">
        <form id="search-form" action="search" class="my-search navbar-right search-form" onsubmit="">
		<?php if (isset($favi) && $favi > 0) {
		?>
		<input id="extra-param" name="favi" value="1" hidden="true"/>
		<?php
		}
		?>
		<input id="search-input" class="search-input" aria-label="search" title="LSDB Search" placeholder="<?php echo L('search'); ?>" name="q" maxlength="2048" aria-haspopup="false" role="combobox" aria-autocomplete="both" spellcheck="false" autocomplete="off" type="search">
        <button class="search-btn" onclick="">
          <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
        </button>
        </form>
      </div>    
    </div>
  </div>
</nav>