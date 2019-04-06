<?php require 'header.tpl'; ?>
<?php
$id = 0;
$queries = array();
parse_str($_SERVER['QUERY_STRING'], $queries);
if(array_key_exists('id', $queries)) {
	$id = $queries['id'];
}
if ($id > 0) {
	$pdo = new \PDO('sqlite:'.'mydb.db');
	$sql = "SELECT * FROM items where id = ". $id;
	$result = $pdo->query($sql);
	if($row = $result->fetch(\PDO::FETCH_ASSOC)) {
		echo '<div class="jumbotron"><div class="container">
	        <h2>'. $row['title'] .'</h2>
	        <p>'. str_replace(PHP_EOL, '<br/>', $row['content']) .'</p><div class="bs-docs-section">';
	     
	    $tags = explode(";", $row['tag']);
	    for ($i=0; $i < count($tags); $i++) {
	    	$tagValue = $tags[$i];
	    	if($tagValue != '')
	     		echo '<a href="search?tag='.$tagValue.'"><span class="badge badge-secondary">'. $tagValue .'</span></a> ';
	    }
	    echo '</div><div class="row"><div class="col-md-12">';
	    $images = explode(";", $row['images']);
	    for ($i=0; $i < count($images); $i++) { 
	    	echo '<br><div class="media"><img class="img-fluid" src="resource.php?base='. $row['base'].'&cata='. $row['category'] .'&subcata='.$row['subcategory'].'&name='. $row['name'].'&filename='. $images[$i] .'"/></div>';
	    }
	    echo '</div></div></div></div>';
	    
	}
} else {
	echo "Not found";
}
?>
<style type="text/css">
  body > .jumbotron  {
    padding: 100px 15px 50px;
  }
</style>
<?php require 'footer.tpl'; ?>