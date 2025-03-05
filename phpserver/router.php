<?php
$base = ltrim(parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH), '/');
$base_url = ltrim($_SERVER["REQUEST_URI"], '/');
$route = parse_url(substr($_SERVER["REQUEST_URI"], 1))["path"];
$pathinfo = pathinfo($route);
$ext = isset($pathinfo['extension']) ? $pathinfo['extension'] : '';

if($base == '') {
	include 'search.php';
} else if($ext == 'php') {
	include $base;
} else {
	if (file_exists($base_url)) {
		$mimeTypes = [
			'css' => 'text/css',
			'js'  => 'application/javascript',
			'jpg' => 'image/jpg',
			'png' => 'image/png',
			'gif' => 'image/gif',
			'svg' => 'image/svg+xml',
			'map' => 'application/json',
			'woff' => 'application/x-woff',
			'woff2' => 'application/font-woff2',
			'html' => 'text/html',
		];

		if (isset($mimeTypes[$ext])) {
			header("Content-Type: $mimeTypes[$ext]");
		}
		readfile($base_url);
	} else if (file_exists($base . '.php')) {
		include $base . '.php';
	}
}