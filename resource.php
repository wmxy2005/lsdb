<?php
const DIR_SEP = DIRECTORY_SEPARATOR;
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

$base_dir = "D:\\Doc\\fWeb";
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
if (!empty($cate) && !empty($name)) {
	$filepath = $base_dir. DIR_SEP. $base . DIR_SEP . $cate. DIR_SEP . (empty($subcate) ? "" : $subcate . DIR_SEP) . $name . DIR_SEP . $filename;
	$filer = file_get_contents($filepath);
	header('Content-type: image/jpeg');
	echo $filer;
}
?>