<?php require 'header.tpl'; ?>
<div class="pageinfo bg-body-secondary">
<div class="container">
<p class="fs-2 fw-semibold"><?php echo $title; ?></p>

<p class="fs-6">
<?php
$roleImgesList= preg_split('[;]', $roleImges);
for ($i=0; $i < count($roleImgesList); $i++) {
	$roleImge = $roleImgesList[$i];
	$roleImgeValues= preg_split('[@]', $roleImge);
	$roleImgeName = $roleImgeValues[0];
	$roleImgeSrc = "";
	if(sizeof($roleImgeValues) >= 2) {
		$roleImgeSrc = $roleImgeValues[sizeof($roleImgeValues)-1];
	}
	$imgUrl = 'resource?base=' . $base . '&name=e'. $role['id'].'&filename='. $roleImgeSrc;
	echo '<img alt="' . $roleImgeName .'" src="' . $imgUrl .'" width="140" height="165" border="0">';
}
?>
</p>
<p class="fs-5">
<?php
$roleNameList= preg_split('[;]', $roleNames, 0, PREG_SPLIT_NO_EMPTY);
for($i = 0; $i < sizeof($roleNameList); $i=$i+1) {
	$roleName = $roleNameList[$i];
	$stypeType = get_style_type($i);
	echo '<a href="search?q=' . $roleName . '" target="_blank" class="badge text-' . $stypeType .'-emphasis bg-' . $stypeType .'-subtle border border-' . $stypeType .'-subtle rounded-pill">'. $roleName . '</a> ';
}
?>
</p>
<div class="fs-6">
<span class="glyphicon glyphicon-calendar" aria-hidden="true"></span>
<span>
<?php
echo substr($datetime, 0, 10);
?>
</span>
</div>

</div>
</div>
<div class="container">
</br>
<?php
echo $content;
?>
</div>
<?php require 'backtop.tpl';
require 'footer.tpl'; ?>