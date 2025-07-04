<?php include 'core/init.php';
function getDir($dir) {
    $dirArray[]=NULL;
    if (false != ($handle = opendir ( $dir ))) {
        $i=0;
        while ( false !== ($file = readdir ( $handle )) ) {
            if ($file != "." && $file != ".." && !strpos($file,".")) {
                $dirArray[$i] = $file;
                $i++;
            }
        }
        closedir ( $handle );
    }
    return $dirArray;
}

function getFile($dir) {
    $fileArray[]=NULL;
    if (false != ($handle = opendir ( $dir ))) {
        $i=0;
        while ( false !== ($file = readdir ( $handle )) ) {
            if ($file != "." && $file != ".." && strpos($file,".")) {
                $fileArray[$i]="./imageroot/current/".$file;
                echo $fileArray[$i];
                if($i==100){
                    break;
                }
                $i++;
            }
        }
        closedir ( $handle );
    }
    return $fileArray;
}

$conf = Config::$config;
$folder = $conf['folder'];

$base_dir = $folder;
$base = '';
$cate = '';
$subcate = '';
$name = '';
$filename = '';
$queries = array();
parse_str($_SERVER['QUERY_STRING'], $queries);
if(array_key_exists('base', $queries))
    $base = $queries['base'];
if(array_key_exists('cata', $queries))
	$cate = $queries['cata'];
if(array_key_exists('subcata', $queries))
    $subcate = $queries['subcata'];
if(array_key_exists('name', $queries))
	$name = $queries['name'];
if(array_key_exists('filename', $queries))
	$filename = $queries['filename'];
if (!empty($filename)) {
	$fileExist = false;
	$filepath = getImagePath($base_dir, $base, $cate, $subcate, $name, $filename);
	if (file_exists($filepath)) {
		$fileExist = true;
	} else {
		$filepath2 = getImagePath($base_dir, $base, $cate, $subcate, $name, rawurlencode($filename));
		if (file_exists($filepath2)) {
			$filename = rawurlencode($filename);
			$filepath = $filepath2;
			$fileExist = true;
		}
	}
	if ($fileExist) {
		$file_path = $filepath;
		$file_name = $filename;
		$file_name_lower = strtolower($filepath);
		$mime_type = mime_content_type($file_path);
		if(my_str_ends_with($file_name_lower, '.svg')){
			$mime_type = 'image/svg+xml';
		}
		header('Content-Type: ' . $mime_type);
		header('Content-Disposition: inline; filename="' . $file_name . '"');
		$file_size = filesize($file_path);
		
		if (my_str_ends_with($file_name_lower, '.png') || my_str_ends_with($file_name_lower, '.jpg') || my_str_ends_with($file_name_lower, '.jpeg') || my_str_ends_with($file_name_lower, '.svg') || my_str_ends_with($file_name_lower, '.webm')){
			header('Content-Length: ' . $file_size);
			readfile($file_path);
			// $filer = file_get_contents($file_path);
			// echo $filer;
			// include $filepath;
			// include $file_path;
		} else {
			// 最大执行时间 s
			ini_set('max_execution_time', '300');
			// 内存限制
			ini_set('memory_limit', '512M');
			// 添加适当的缓存控制头信息，以确保文件不会被缓存，特别是在内容频繁更新的情况下。
			// header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
			// header('Pragma: no-cache');
			// header('Expires: Thu, 19 Nov 1981 08:52:00 GMT');
			// 清理缓冲区
			while (ob_get_level() > 0) {
				ob_end_flush();
			}
			// 断点续传
			if (isset($_SERVER['HTTP_RANGE'])) {
				$fp = fopen($file_path, 'rb');
				$range = $_SERVER['HTTP_RANGE'];
				list(, $range) = explode('=', $range, 2);
				list($start, $end) = explode('-', $range);
				$start = intval($start);
				$end = ($end === '') ? ($file_size - 1) : intval($end);
				$length = $end - $start + 1;
				header('HTTP/1.1 206 Partial Content');
				header("Content-Range: bytes {$start}-{$end}/{$file_size}");
				header('Content-Length: ' . $length);
				fseek($fp, $start);
				echo fread($fp, $length);
				fclose($fp);
			} else {
				header('Content-Length: ' . $file_size);
				readfile($file_path);
			}
		}
    } else {
		include 'core/img/image-not-found.jpg';
    }
}
?>