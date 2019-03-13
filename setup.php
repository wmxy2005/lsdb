<?php
$proc_total = 0;
$proc = 0;
ini_set('max_execution_time','3000');
$time_start = microtime(true);
$time_current = $time_start;
?>
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

    <link rel="stylesheet" href="header.css">

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
            <li><a href="#">Setting</a></li>
          </ul>
          <div class="search-area">
          <form action="search.php" method="get" id="search-form" class="navbar-right search-form">
            <input id="search-input" aria-label="Search" title="LDBS Search" name="q" maxlength="2048" class="search-input" aria-haspopup="false" role="combobox" aria-autocomplete="both" spellcheck="false" autocomplete="off" type="text">
            <button class="search-btn">
              <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
            </button>
          </form>
          </div>
        </div>
      </div>
    </nav>
    <div class="container">
        <div class="jumbotron">
            <h3>Processing files</h3>
            <div class="progress">
              <div id="progress" class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
                0%
              </div>
            </div>
            <div id="status"> </div>
        </div>
    </div>


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
    <style type="text/css">
    body > .container  {
      padding: 100px 15px 50px;
    }
    </style>
<?php
const DIR_SEP = DIRECTORY_SEPARATOR;
function formatDate($dateStr) {

}
function getDir($pdo, $dir, $base, $category, $subcategory) {
    if (false != ($handle = opendir ( $dir ))) {
        while ( false !== ($file = readdir ( $handle )) ) {
            if ($file != "." && $file != ".." && !strpos($file,".")) {
                
                if (false != ($dirHandle = opendir ( $dir . DIR_SEP . $file))) {
                    $currentDir = $file;
                    global $proc_total;
                    $proc_total++;

                    global $time_start;
                    global $time_current;
                    $time_current = microtime(true);
                    if(round($time_current - $time_start, 1) >= 0.1) {
                        $time_start = $time_current;
                    ?>
                    
                    <script language="JavaScript">updateProgress("已处理<?php global $proc_total; echo $proc_total; ?>, 正在处理 <?php echo $currentDir; ?> ....", 100);</script>
                    <?php flush();?>

                    <?php
                    }
                    global $proc;
                    $proc++;

                    $item_exist = false;
                    $sql = "SELECT * FROM items where base = '". $base ."' and category = '". $category . "' and subcategory = '". $subcategory ."' and name = '". $file ."'";
                    $result = $pdo->query($sql);
                    if ($row = $result->fetch(\PDO::FETCH_ASSOC)) {
                        $item_exist = true;
                    }
                    if($item_exist)
                        continue;

                    $mainTxt = $file . ".txt";
                    $name = $file;
                    $title = "";
                    $date = "";
                    $thumbnail = "";
                    $tag = "";
                    $content = "";
                    $images = "";
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
                    $res = $pdo->exec("insert into items(base, category, subcategory, name, title, date, thumbnail, tag, content, images) values('". $base ."','". $category . "','". $subcategory ."','". $name ."','". $title."', '". $date."', '". $thumbnail ."','". $tag ."', '". $content . "','". $images ."')");
                    
                    closedir ( $dirHandle );
                }

            }
        }
        closedir ( $handle );
    }
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
?>
<script language="JavaScript">
  function updateProgress(sMsg, progressValue)
  {
    document.getElementById("status").innerHTML = sMsg;
    document.getElementById("progress").innerHTML = "";
    document.getElementById("progress").style.width = progressValue + "%";
   }
</script>
<?php
flush();
$base = "wallpaper";
$baseDir = "sample\\" . $base;
$pdo = new \PDO('sqlite:'.'mydb.db');
//$pdo->exec("delete from items");

if (false != ($handle = opendir ( $baseDir ))) {
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
?>
<script language="JavaScript">
  updateProgress("已处理<?php global $proc_total; echo $proc_total; ?>, 操作完成！", 100);
</script>
<?php
flush();
?>
</body>
</html>