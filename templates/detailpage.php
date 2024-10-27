<?php require 'header.tpl'; ?>
<div class="pageinfo bg-body-secondary">
<div class="container">
<p class="fs-2 fw-semibold"><?php echo $title; ?></p>
<span class="glyphicon glyphicon-heart item-favi <?php echo (empty($favi)? '':' item-like'); ?>" item-id="<?php echo $id; ?>" aria-hidden="true" style="font-size:2em;" onclick="itemLike(this);">
</span>
<p class="fs-6">
<?php
echo str_replace(PHP_EOL, "<br/>", $content);
echo '<br/>' . $base.' · '. $category . (empty($subcategory)?'':' · ' . $subcategory . '')  . ' · '. $name . '';
?>
</p>
<div class="fs-5">
<?php
$categoryUrl = queryString('', '', $subcategory, $category, 0, 0, 0, 999, 0, 0);
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
<a id="add-plus" class="badge text-primary-emphasis bg-primary-subtle border border-primary-subtle rounded-pill" href="javascript:void(0);">
<div id="add-loading" style="display: none">
<span class="spinner-border spinner-border-sm" role="status">
</span>
<?php echo L('updating'); ?>
</div>
<div id="add-icon">
<span class="glyphicon glyphicon-cloud-upload" aria-hidden="true">
</span>
<?php echo L('update'); ?>
</div>
</a>
<a id="open-floder" class="badge text-warning-emphasis bg-warning-subtle border border-warning-subtle rounded-pill" href="javascript:void(0);"><?php echo L('open'); ?></a>
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
	echo ($i == 0 ? '<br>':'') . '<div class="text-center"><img class="img-fluid" src="resource?base='. $base.'&cata='. $category .'&subcata='.$subcategory.'&name='. $name.'&filename='. $all_images[$i] .'"/></div>';
}
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
			const DIR_SEP = DIRECTORY_SEPARATOR;
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
<?php require 'backtop.tpl';
require 'footer.tpl'; ?>