<?php require 'header.tpl'; ?>
<div class="pageinfo jumbotron">
<div class="container">
<h2><?php echo $title; ?></h2>
<span class="glyphicon glyphicon-heart item-favi <?php echo (empty($item['favi'])? '':' item-like'); ?>" item-id="<?php echo $item['id']; ?>" aria-hidden="true" style="font-size:2em;" onclick="itemLike(this);">
</span>
<p>
<?php
echo str_replace(PHP_EOL, "<br/>", $content);
echo '<br/>' . $base.' · '. $category . (empty($subcategory)?'':' · ' . $subcategory . '')  . ' · '. $name . '';
?>
</p>
<div class="bs-docs-section">
<h5>
<?php
$categoryUrl = queryString('', $item['subcategory'], $item['category'], 0, 0, 0);
$categoryName = empty($item['subcategory']) ? $item['category'] : $item['subcategory'];
echo (empty($item['subcategory']) ? '<a href="'.$categoryUrl.'"><span class="badge badge-success">'. $item['category'] .'</span></a> '
			: '<a href="'. $categoryUrl.'"><span class="badge badge-warning">'. $item['subcategory'] .'</span></a> ');
$tags = explode(";", $tag);
for ($i=0; $i < count($tags); $i++) {
	$tagValue = trim($tags[$i]);
	if($tagValue != '')
		echo '<a href="search?tag='.$tagValue.'"><span class="badge badge-info">'. $tagValue .'</span></a> ';
}
?>
</h5>
<br/>
</div>
</div>
</div>
<div class="container">
<div class="row">
<div class="col-md-12">
<?php
$all_images = explode(";", $images);
for ($i=0; $i < count($all_images); $i++) { 
	echo ($i > 0 ? '<br>':'') . '<div class="text-center"><img class="img-fluid" src="resource?base='. $base.'&cata='. $category .'&subcata='.$subcategory.'&name='. $name.'&filename='. $all_images[$i] .'"/></div>';
}
?>
</div>
</div>
</div>
<?php require 'backtop.tpl';
require 'footer.tpl'; ?>