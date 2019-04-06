<?php
$proc_total = 0;
$proc = 0;
$item_check = true;
ini_set('max_execution_time','3000');
$time_start = microtime(true);
$time_current = $time_start;
ob_start();
const DIR_SEP = DIRECTORY_SEPARATOR;
function formatDate($dateStr) {

}
function getDir($pdo, $dir, $base, $category, $subcategory) {
    if (false != ($handle = opendir ( $dir ))) {
        while ( false !== ($file = readdir ( $handle )) ) {
            if ($file != "." && $file != ".." && !strpos($file,".")) {
                if (false != ($dirHandle = opendir ( $dir . DIR_SEP . $file))) {
                    processDetail($pdo, $base, $dir, $category, $subcategory, $file);
                }
            }
        }
        closedir ( $handle );
    }
}

function processDetail($pdo, $base, $dir, $category, $subcategory, $file) {
    $currentDir = $file;
    global $proc_total;
    $proc_total++;

    global $time_start;
    global $time_current;
    $time_current = microtime(true);
    if(round($time_current - $time_start, 1) >= 0.1) {
        $time_start = $time_current;
        $result = array(
            'msg'=> "已处理 " . $proc_total. ", 正在处理 " . $currentDir . "...."
        );
        echo "\n" . json_encode($result);
        ob_flush();
        flush();
    }
    global $proc;
    $proc++;

    $item_exist = false;
    $sql = "SELECT * FROM items where base = '". $base ."' and category = '". $category . "' and subcategory = '". $subcategory ."' and name = '". $file ."'";
    $result = $pdo->query($sql);
    if ($row = $result->fetch(\PDO::FETCH_ASSOC)) {
        $item_exist = true;
    }
    if($item_check && $item_exist)
        return;

    $mainTxt = $file . ".txt";
    $name = $file;
    $title = "";
    $date = "";
    $thumbnail = "";
    $tag = "";
    $content = "";
    $images = "";

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
    $pdo->exec("delete from items where base = '". $base ."' and category = '". $category . "' and subcategory = '". $subcategory ."' and name = '". $file ."'");
    $res = $pdo->exec("insert into items(base, category, subcategory, name, title, date, thumbnail, tag, content, images) values('". $base ."','". $category . "','". $subcategory ."','". $name ."','". $title."', '". $date."', '". $thumbnail ."','". $tag ."', '". $content . "','". $images ."')");
    
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

$base = "wallpaper";
$baseDir = "sample\\" . $base;
$pdo = new \PDO('sqlite:'.'mydb.db');
$appendFile = $baseDir . DIR_SEP . 'fWeb_new.log';
$appendMode = false;

if($appendMode) {
    $append_attr = file($appendFile);
    for($line = 0; $line < count($append_attr); $line++) {
        $str = $append_attr[$line];
        if($str != "") {
            $result = preg_split('/:/s', $str,3);
            if(sizeof($result) == 3) {
                $category = $result[0];
                $subCategory = $result[1];
                $name = $result[2];

                $dir = $baseDir . DIR_SEP . $category . (empty($subCategory) ? '' : DIR_SEP . $subCategory);
                processDetail($pdo, $base, $dir, $category, $subCategory, trim($name));
            }
        }
    }
} else if (false != ($handle = opendir ( $baseDir ))) {
	$pdo->exec("delete from items");
    while( false !== ($file = readdir ( $handle )) ) {
        if ($file != "." && $file != ".." && !strpos($file,".")) {
            $category = $file;
            if($category != "tag")
                getDir($pdo, $baseDir . DIR_SEP . $category, $base, $category, '');
            else {
                 if (false != ($tagHandle = opendir ($baseDir . DIR_SEP . $category ))) {
                    while ( false !== ($tagDir = readdir ( $tagHandle )) ) {
                        if ($tagDir != "." && $tagDir != ".." && !strpos($tagDir,".")) {
                            //Tag category
                            getDir($pdo, $baseDir . DIR_SEP . $category . DIR_SEP . $tagDir, $base, 'tag', $tagDir);
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
    'msg'=> "已处理" . $proc_total . ", 操作完成！"
);
echo "\n" . json_encode($result);
?>