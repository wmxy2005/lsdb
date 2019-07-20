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
    <link href="core/css/header.css" rel="stylesheet">
    <link href="core/css/signin.css" rel="stylesheet">
	
	<script>window.jQuery || document.write('<script src="assets/js/vendor/jquery.min.js"><\/script>')</script>
  </head>
<body>
<div class="site-wrapper-border fixed-top"></div>

<nav class="navbar navbar-expand-md navbar-light bg-light fixed-top header" role="navigation">
  <div class="container">
    <a href="search"><img style="max-height: 50px" src="core/img/logo.svg" alt="LSDB LOGO" class="logo"></a>
    <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarNav"
    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav mr-auto">
		<a class="p-2" href="search?myfavi=1"><?php echo L('favi'); ?></a>
        <a class="p-2" href="install"><?php echo L('install'); ?></a>
        <a class="p-2" href="speedtest"><?php echo L('speedtest'); ?></a>
        <!-- <a class="p-2"  href="javascript:void(0);" onclick="login()">Login</a> -->
      </ul> 

      <div class="search-area">
        <form id="search-form" action="search" class="my-search navbar-right search-form" onsubmit="">
        <input id="search-input" class="search-input" aria-label="search" title="LSDB Search" placeholder="<?php echo L('search'); ?>" name="q" maxlength="2048" aria-haspopup="false" role="combobox" aria-autocomplete="both" spellcheck="false" autocomplete="off" type="search">
        <button class="search-btn" onclick="">
          <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
        </button>
        </form>
      </div>    
    </div>
  </div>
</nav>