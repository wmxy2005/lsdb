<?php
include 'init.php';
include 'jwt.php';
header('Access-Control-Allow-Methods: ' . 'GET, POST, OPTIONS, PUT, DELETE');
header('Access-Control-Allow-Origin: ' . Allow_Origin);
header('Access-Control-Allow-Credentials: ' . 'true');
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

$base = '';
$cate = '';
$subcate = '';
$name = '';
$filename = '';
$queries = array();
parse_str($_SERVER['QUERY_STRING'], $queries);
if(array_key_exists('base', $queries))
    $base = $queries['base'];
if(array_key_exists('category', $queries))
	$cate = $queries['category'];
if(array_key_exists('subcategory', $queries))
    $subcate = $queries['subcategory'];
if(array_key_exists('name', $queries))
	$name = $queries['name'];
if(array_key_exists('filename', $queries))
	$filename = $queries['filename'];
if (!empty($filename)) {
	if ($_SERVER['REQUEST_METHOD'] == 'GET') {
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
			if(str_ends_with($file_name_lower, '.svg')){
				$mime_type = 'image/svg+xml';
			}
			header('Content-Type: ' . $mime_type);
			header('Content-Disposition: inline; filename="' . $file_name . '"');
			$file_size = filesize($file_path);
			
			if (str_ends_with($file_name_lower, '.png') || str_ends_with($file_name_lower, '.jpg') || str_ends_with($file_name_lower, '.jpeg') || str_ends_with($file_name_lower, '.svg') || str_ends_with($file_name_lower, '.webm')){
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
			include IMAGE_NOT_FOUND;
		}
	} else if ($_SERVER['REQUEST_METHOD'] == 'POST' || $_SERVER['REQUEST_METHOD'] == 'DELETE') {
		header('Access-Control-Allow-Headers: ' . 'Content-Type');
		header('Content-Type: ' . 'application/json;charset=utf-8');
		$created = time();
		$auth = false;
		$token = "";
		if(array_key_exists('token', $_COOKIE)) {
			$token = $_COOKIE['token'];
		}
		if(!empty($token)){
			$decodedPayload = JWT::verifyJWT($token);
			if($decodedPayload){
				$storeUserId = 0;
				$storeCreated = 0;
				if(array_key_exists('userId', $decodedPayload)) {
					$storeUserId = $decodedPayload['userId'];
				}
				if(array_key_exists('created', $decodedPayload)) {
					$storeCreated = $decodedPayload['created'];
				}
				if($storeUserId > 0) {
					if ($created - $storeCreated > TOKEN_EXPIRED) {
						$userInfo = [
							'errorMessage' => 'Expired'
						];
						echo json_encode($userInfo);
						http_response_code(200);
						return;
					}else{
						$auth = true;
					}
				}
			}
		}
		if(!$auth){
			$userInfo = [
				'errorMessage' => 'Unauthorized'
			];
			echo json_encode($userInfo);
			http_response_code(200);
			return;
		}

		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			$destination = getImagePath($base_dir, $base, $cate, $subcate, $name, $filename);
			$uploadedFile = $_FILES['file'] ?? null;
			if ($uploadedFile && $uploadedFile['error'] === UPLOAD_ERR_OK) {
				$success = false;
				$warn = false;
				$message = '';
				if(!file_exists($destination)) {
					move_uploaded_file($uploadedFile['tmp_name'], $destination);
					$success = true;
					$message = 'Success';
				}else{
					$warn = true;
					$message = 'File exists: ' . htmlspecialchars($filename);
				}
				$successInfo = [
					'success' => $success,
					'warn' => $warn,
					'message' => $message
				];
				echo json_encode($successInfo);
				http_response_code(200);
			} else {
				$successInfo = [
					'errorMessage' => $uploadedFile['error']
				];
				echo json_encode($successInfo);
				http_response_code(405);
			}
		}else{
			$filepath = getImagePath($base_dir, $base, $cate, $subcate, $name, $filename);
			if (file_exists($filepath)) {
				$success = false;
				$warn = false;
				$message = '';
				
				if(unlink($filepath)){
					$success = true;
					$message = 'Delete failed';
				}
				
				$successInfo = [
					'success' => $success,
					'warn' => $warn,
					'message' => $message
				];
				echo json_encode($successInfo);
				http_response_code(200);
				return;
			}
			$warnInfo = [
				'errorMessage' => $uploadedFile['error']
			];
			echo json_encode($warnInfo);
			http_response_code(405);
		}
	}
}
?>