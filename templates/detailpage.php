<?php require 'header.tpl'; ?>
<div class="pageinfo bg-body-secondary">
<div class="container">
<div style="display: flex;">
<span class="glyphicon glyphicon-heart item-favi <?php echo (empty($favi)? '':' item-like'); ?>" item-id="<?php echo $id; ?>" aria-hidden="true" style="font-size:3em;padding: 0 10px 0 0px" onclick="itemLike(this);">
</span>
<p class="fs-2 fw-semibold"><?php echo $title; ?></p>
</div>
<p class="fs-6">
<?php
echo str_replace(PHP_EOL, "<br/>", $content);
echo '<br/>' . $base . (empty($category)?'':' · ' . $category . '') . (empty($subcategory)?'':' · ' . $subcategory . '')  . ' · '. $name . '';
?>
</p>
<?php
if(!empty($extra))
	echo '<span>' . $extra . '</span>';
?>
<div class="fs-5">
<?php
$categoryUrl = queryString('', '', $subcategory, '', '', $category, 0, 0, 0, 999, 0, 0);
$categoryName = empty($subcategory) ? $category : $subcategory;
echo (empty($subcategory) ? '<a class="badge text-success-emphasis bg-success-subtle border border-success-subtle rounded-pill" href="'.$categoryUrl.'">'. $category .'</a> '
			: '<a class="badge text-warning-emphasis bg-warning-subtle border border-warning-subtle rounded-pill" href="'. $categoryUrl.'">'. $subcategory .'</a> ');
$tags = explode(";", $tag);
for ($i=0; $i < count($tags); $i++) {
	$tagValue = trim($tags[$i]);
	if($tagValue != '')
		echo '<a class="badge text-info-emphasis bg-info-subtle border border-info-subtle rounded-pill" href="search?tag='.$tagValue.'">'. $tagValue .'</span></a> ';
}
?>
<?php
if ($base == 'javfree') {
?>
<a id="add-plus" class="badge text-primary-emphasis bg-primary-subtle border border-primary-subtle rounded-pill" href="javascript:void(0);">
<div id="add-loading" style="display: none">
<span class="spinner-border spinner-border-sm" role="status" style="max-width: 1em;max-height: 1em;">
</span>
<?php echo L('updating'); ?>
</div>
<div id="add-icon">
<span class="glyphicon glyphicon-cloud-upload" aria-hidden="true" style="font-size: .8rem;">
</span>
<?php echo L('update'); ?>
</div>
</a>
<?php
}
?>
<a id="open-floder" class="badge text-warning-emphasis bg-warning-subtle border border-warning-subtle rounded-pill" href="javascript:void(0);"><?php echo L('open'); ?></a>
<br/>
<?php
$tags2 = explode(";", $tag2);
for ($i=0; $i < count($tags2); $i++) {
	$tagValue = trim($tags2[$i]);
	if($tagValue != '')
		echo '<a class="badge text-success-emphasis bg-success-subtle border border-success-subtle rounded-pill" href="search?tag2='.$tagValue.'">'. $tagValue .'</span></a> ';
}
$tags3 = explode(";", $tag3);
for ($i=0; $i < count($tags3); $i++) {
	$tagValue = trim($tags3[$i]);
	if($tagValue != '')
		echo '<a class="badge text-dark-emphasis bg-dark-subtle border border-dark-subtle rounded-pill" href="search?tag3='.$tagValue.'">'. $tagValue .'</span></a> ';
}
?>
</div>
</div>
</div>
<div class="container text-center">
<div class="row" style="justify-content: center;padding: 12px;">
<?php
if (!empty($trailer)) {
?>
<source id="video_url" style="display: none;" src="<?php echo 'resource?base='. $base.'&cata='. $category .'&subcata='.$subcategory.'&name='. $name.'&filename='. $trailer; ?>"></source>
<source id="poster_url" style="display: none;" src="<?php echo 'resource?base='. $base.'&cata='. $category .'&subcata='.$subcategory.'&name='. $name.'&filename='. $thumbnail; ?>"></source>
<div id="xplayer"></div>
<?php
}
?>
</div>
<div class="row">
<div class="col-md-12" id="gallery-images">
<?php
echo "\n<!--COST:" . round(microtime(true) - $time_init->init, 6) . "-->";
?>
<?php
if (!empty($trailer) && 1 == 2) {
?>
<video class="embed-responsive" width="100%" controls >
<source src="<?php echo 'resource?base='. $base.'&cata='. $category .'&subcata='.$subcategory.'&name='. $name.'&filename='. $trailer; ?>" type="video/mp4"></source>
</video>
<?php
}
?>
<?php
$all_images = explode(";", $images);
for ($i=0; $i < count($all_images); $i++) {
	$imageUrl = 'resource?base='. $base.'&cata='. $category .'&subcata='.$subcategory.'&name='. $name.'&filename='. $all_images[$i];
	$conf = Config::$config;
	$base_dir = $conf['folder'];
	$filepath = getImagePath($base_dir, $base, $category, $subcategory, $name, $all_images[$i]);
	if (file_exists($filepath)) {
		list($width, $height) = getimagesize($filepath);
		echo ($i == 0 ? '':'') . '<a data-pswp-src="'. $imageUrl .'" data-pswp-width="' . $width .'" data-pswp-height="' . $height .'"><img class="img-fluid" src="'. $imageUrl .'"/></a>';
	} else {
		echo ($i == 0 ? '':'') . '<img class="img-fluid" src="'. $imageUrl .'"/>';
	}
}
?>
<?php
echo "\n<!--COST:" . round(microtime(true) - $time_init->init, 6) . "-->";
?>
</div>
</div>
</div>
<script>
$('#add-plus').on('click', function() {
	$('#add-plus').addClass("disabled");
	$('#add-icon').hide();
	$('#add-loading').show();
	$.ajax({
		type: "POST",
		url: "initfiles.php",
		data : {
			"appendmode":true,
			"id": <?php echo $id; ?>
		},
		xhrFields: {
			onprogress: function (e) {
				var msg = e.currentTarget.responseText;
				console.log(msg);
			}
		},
		success: function (response) {
			$('#add-plus').removeClass("disabled");
			$('#add-loading').hide();
			$('#add-icon').show();
			var message = response.substring(response.lastIndexOf("\n")+1);
			var status = $.parseJSON(message);
			alert(status.msg);
			$('#add-plus').removeClass("disabled");
		},
		error: function (response) {
			('#add-plus').removeClass("disabled");
			$('#add-loading').hide();
			$('#add-icon').show();
			alert('failed');
		}
	});
});
$('#open-floder').on('click', function() {
	$('#open-floder').addClass("disabled");
	$.ajax({
		type: "POST",
		url: "exec.php",
		data : {
			"id": <?php echo $id; ?>,
			"cmd": <?php
			$filepath = $folder. DIR_SEP. $base . DIR_SEP . (empty($category) ? "" : $category . DIR_SEP) . (empty($subcategory) ? "" : $subcategory . DIR_SEP) . $name;
			echo '"explorer ' . urlencode($filepath) . '"';
			?>
		},
		success: function (response) {
			console.log(response);
			$('#open-floder').removeClass("disabled");
		},
		error: function (response) {
			('#open-floder').removeClass("disabled");
			alert('failed');
		}
	});
});
</script>
<?php require 'vpalyer.tpl';
require 'backtop.tpl';
require 'backtop.tpl';
require 'photoswipe.tpl';
require 'footer.tpl'; ?>