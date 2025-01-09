<?php require_once 'core/init.php';
require_once('lib/simple_html_dom.php');

$proc_total = 0;
$proc = 0;
$item_check = false;
ini_set('max_execution_time','36000');
$time_start = microtime(true);
$time_current = $time_start;
ob_start();

$appendMode = ("true" == $_POST['appendmode'] ? true : false);

$conf = Config::$config;
$folder = $conf['folder'];
$base = $conf['name'];
$dbname = $conf['dbname'];
$appendfilename = $conf['appendfilename'];

$baseDir = $folder . DIR_SEP . $base;
$pdo = new \PDO('sqlite:'. $dbname);
$appendFile = $baseDir . DIR_SEP . $appendfilename;
	
function getDir($pdo, $dir, $base, $category, $subcategory, $update) {
    if (false != ($handle = opendir ( $dir ))) {
        while ( false !== ($file = readdir ( $handle )) ) {
            if ($file != "." && $file != ".." && !strpos($file,".")) {
                if (false != ($dirHandle = opendir ( $dir . DIR_SEP . $file))) {
                    processDetail($pdo, $base, $dir, $category, $subcategory, $file, $update, 100);
                }
            }
        }
        closedir ( $handle );
    }
}

function processDetail($pdo, $base, $dir, $category, $subcategory, $file, $update, $progress) {
    $currentDir = $file;
    global $proc_total;
	global $item_check;
    $proc_total++;

    global $time_start;
    global $time_current;
    $time_current = microtime(true);
    if(round($time_current - $time_start, 1) >= 0.1) {
        $time_start = $time_current;
        $result = array(
            'msg'=> "已处理 " . $proc_total. ", 正在处理 " . $currentDir,
            'progress'=> $progress
        );
        echo "\n" . json_encode($result);
        ob_flush();
        flush();
    }
    global $proc;
    $proc++;

    $item_id = 0;
    $sql = "SELECT id FROM items where base = '". $base ."' and category = '". $category . "' and subcategory = '". $subcategory ."' and name = '". $file ."'";
    $result = $pdo->query($sql);
    if ($row = $result->fetch(\PDO::FETCH_ASSOC)) {
        $item_id = $row['id'];
    }
    if(!$update && $item_check && $item_id > 0)
        return;

    $mainTxt = $file . ".txt";
    $name = $file;
    $title = "";
    $date = "";
    $thumbnail = "";
    $tag = "";
    $content = "";
    $images = "";
	$type = "0";

    $dirHandle = opendir ( $dir . DIR_SEP . $file);
    if($dirHandle) {
    while ( false !== ($dirFile = readdir ( $dirHandle )) ) {
        if ($dirFile != "." && $dirFile != ".." && strpos($dirFile,".")) {
            if ($mainTxt == $dirFile) {
                $file_arr = file($dir . DIR_SEP . $file . DIR_SEP. $mainTxt);
                for($line = 0; $line < count($file_arr); $line++){
                    if($line == 0) {
                        $title = $file_arr[$line];
                    } else if ($line == 1) {
                        $tag = $file_arr[$line];
                    } else if ($line == 3) {
                        $date = $file_arr[$line];
                        $date = date('Y-m-d',strtotime($date));
					} else if ($line == 4) {
						if(!empty($file_arr[$line])) {
							$type = "1";
							$flag = trim($file_arr[$line]);
							if($flag == "fc2") {
								$type = "2";
							}
						}
                    }
                }
            } else if (strpos($dirFile,"_src.txt")) {
                $file_arr = file($dir . DIR_SEP . $file . DIR_SEP. $dirFile);
                for($line = 0; $line < count($file_arr); $line++){
                    $str = $file_arr[$line];
                    if($str != "") {
                        $index = strpos($str,":");
                        if($index) {
                            $image = substr($str, 0, $index);
                            if($line == 0) {
                                $thumbnail = $image;
                            }
                            
                            if($image != "") {
                                $images = $images . ($images == "" ? "" : ";") . $image;
                            }
                        }
                    }
                }
            } else if ($dirFile == "content.txt") {
                $file_arr = file($dir . DIR_SEP . $file . DIR_SEP. $dirFile);
                for($line = 0; $line < count($file_arr); $line++) {
                    $str = $file_arr[$line];
                    if($str != "") {
                        $content = $content . ($content == "" ? "" : "\n") . $str;
                    }
                }
            }
        }
    }
    }
    if($item_id > 0) {
        $pdo->exec("update items set name='".$name."',title='". $title."',date='".$date."',thumbnail='".$thumbnail."',tag='".$tag."',content='".$content."',images='".$images."' where id = ". $item_id);
    } else {
        $pdo->exec("delete from items where base = '". $base ."' and category = '". $category . "' and subcategory = '". $subcategory ."' and name = '". $file ."'");
        $res = $pdo->exec("insert into items(base, category, subcategory, name, title, date, thumbnail, tag, content, images, type) values('". $base ."','". $category . "','". $subcategory ."','". $name ."','". $title."', '". $date."', '". $thumbnail ."','". $tag ."', '". $content . "','". $images . "', " . $type . ")");
    }
    closedir ( $dirHandle );
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

if(array_key_exists("id",$_POST)) {
	$itemId = $_POST['id'];
	if($itemId > 0) {
		$sql = "SELECT * FROM items where id = ". $itemId;
		$result = $pdo->query($sql);
		if ($row = $result->fetch(\PDO::FETCH_ASSOC)) {
			$category = $row['category'];
			$subCategory = $row['subcategory'];
			$name = $row['name'];

			$dir = $baseDir . DIR_SEP . $category . (empty($subCategory) ? '' : DIR_SEP . $subCategory);
			
			$html_name = $dir . DIR_SEP . $name . DIR_SEP . $name . '.html';
			$html_string = file_get_contents($html_name);
			$html = str_get_html($html_string);

			$src_name = $dir . DIR_SEP . $name . DIR_SEP . 'img_src' . '.txt';
			$src_string = '';
			if(file_exists($src_name)) {
				$src_string = file_get_contents($src_name);
			}
			if($src_string == null || $src_string == '') {
				$src_file = fopen($src_name, "w");
				$src_element = 'img';
				foreach ($html->find($src_element) as $element) {
					$src = $element->src;
					if ($src != '') {
						$lastIndex = strrpos($src, '/');
						$img_name = substr($src, $lastIndex+1);
						fwrite($src_file, $img_name . ':' . $src . PHP_EOL);
					}
				}
				fclose($src_file);
			}
			
			if (1 == 2) {
				$src_arr = file($src_name);
				for($line = 0; $line < count($src_arr); $line++){
					$str = $src_arr[$line];
					if($str != "") {
						$index = strpos($str,":");
						if($index) {
							$image = substr($str, 0, $index);
							$url = substr($str, $index + 1);
							$img_file = $dir . DIR_SEP . $name . DIR_SEP . $image;
							if(!file_exists($img_file)) {
								echo $url;
								$url = str_replace("https","http", $url);
								$downSuccess = false;
								$downMsg = '';
								/*
								try {
									$arrContextOptions = array(
										"ssl"=>array(
											"verify_peer"=>false,
											"verify_peer_name"=>false,
										),
									);
									$content = file_get_contents($url, false, stream_context_create($arrContextOptions));
									file_put_contents($image, $content);
								}
								catch(Exception $e) {
									echo 'Failed to download ' . $url;
								}
								*/
								$ch=curl_init();
								curl_setopt($ch, CURLOPT_URL, trim($url));
								curl_setopt($ch, CURLOPT_TIMEOUT, 10);
								curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/106.0.0.0 Safari/537.36');
								curl_setopt($ch, CURLOPT_REFERER, 'http://javfree.me');
								curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
								curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
								curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
								curl_setopt($curl, CURLOPT_SSL_VERIFYSTATUS, false);
								$result=curl_exec($ch);
								if($result === FALSE){
									$downMsg = curl_error($ch);
								} else {
									file_put_contents($img_file, $result);
									$downSuccess = true;
								}
								curl_close($ch);
								if(!$downSuccess) {
									$result = array(
										'msg'=> $downMsg,
										'progress'=> $progress
									);
									echo "\n" . json_encode($result);
									ob_flush();
									flush();
									return;
								}
							}
						}
					}
				}
			}
			$cmd_str = 'python ' . $conf['pyfolder'] . ' ' . $category . ' ' . $subCategory . ' ' . trim($name);
			echo "\n" . $cmd_str;
			ob_flush();
			flush();
			exec($cmd_str);
			processDetail($pdo, $base, $dir, $category, $subCategory, trim($name), true, 0);
		}
	}
} else if($appendMode) {
    $append_attr = file($appendFile);
    $progress = 0;
    $count = count($append_attr);
    for($line = 0; $line < $count; $line++) {
        $str = $append_attr[$line];
        if($str != "") {
            $result = preg_split('/,/s', $str,3);
            if(sizeof($result) == 3) {
                $category = $result[0];
                $subCategory = $result[1];
                $name = $result[2];

                $dir = $baseDir . DIR_SEP . $category . (empty($subCategory) ? '' : DIR_SEP . $subCategory);
                $progress = round($line*100/$count, 0);
                processDetail($pdo, $base, $dir, $category, $subCategory, trim($name), true, $progress);
            }
        }
    }
} else if (false != ($handle = opendir ( $baseDir ))) {
	//$pdo->exec("delete from items");
    while( false !== ($file = readdir ( $handle )) ) {
        if ($file != "." && $file != ".." && !strpos($file,".")) {
            $category = $file;
            if($category != "tag")
                getDir($pdo, $baseDir . DIR_SEP . $category, $base, $category, '', false);
            else {
                 if (false != ($tagHandle = opendir ($baseDir . DIR_SEP . $category ))) {
                    while ( false !== ($tagDir = readdir ( $tagHandle )) ) {
                        if ($tagDir != "." && $tagDir != ".." && !strpos($tagDir,".")) {
                            //Tag category
                            getDir($pdo, $baseDir . DIR_SEP . $category . DIR_SEP . $tagDir, $base, 'tag', $tagDir, false);
                        }
                    }
                    closedir ( $tagHandle );
                }
            }
        }
    }
    closedir ( $handle );
}
$pdo = null;
$result = array(
    'msg'=> "已处理" . $proc_total . ", 操作完成！",
    'progress'=> 100
);
echo "\n" . json_encode($result);
?>