<div class="pageinfo jumbotron">
	<div class="container">
	<div class="row" style="display:flex; flex-wrap: wrap;">
	<div style="padding-left:5px;padding-right:5px;">
	<p class="font-weight-bold">
	<?php echo $favi > 0 ? '<span>【' . L('myfavi') .'】</span>' : ''; ?>
	<?php echo '<span class="badge badge-pill badge-secondary">'. (!empty($tag)?$tag:$category). '</span> ';?>
	<?php echo $total_mess . (empty($keyword) ? '' : ', '.L('search_for').'『'. $keyword . '』') . ', ' . L('time_cost') . ' ' . $timeCost; ?>
	</p>
	</div>
	</div>
	</div>
</div>
<div class="container">
<div class="row" style="display:flex; flex-wrap: wrap;">
<?php
for ($row=0; $row < count($res); $row++) {
$item = $res[$row];
?>
<div class="col-sm-6 col-md-4 col-lg-3" style="padding-left:5px;padding-right:5px;">
<div class="row no-gutters rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative thumbnail">
     <div class="d-flex flex-column justify-content-between" style="height:100%;width:100%;">
      	<a class="text-center" href="detail?id=<?php echo $item['id']; ?>">
			<img class="img-fluid" src="<?php echo 'resource?base='. $item['base'].'&cata='. $item['category'] .'&subcata='.$item['subcategory'].'&name='. $item['name'].'&filename='. $item['thumbnail']; ?>">
		</a>
		<div style="margin-left:.3em;margin-right:.3em;margin-bottom:.2em">
			<div class="caption">
				<div class="mytitle">
					<a href="detail?id=<?php echo $item['id']; ?>" data-toggle="tooltip1" title="<?php echo $item['title']; ?>">
					<p style="display: -webkit-box;-webkit-line-clamp: 2;-webkit-box-orient: vertical;overflow: hidden;">
					  <?php echo $item['title']; ?>
					</p></a>
				
				</div>
			</div>
			<div style="margin-bottom: 5px;">
			<?php
			$categoryUrl = queryString('', $item['subcategory'], $item['category'], 0, 0, 0);
			$categoryName = empty($item['subcategory']) ? $item['category'] : $item['subcategory'];
			if(strlen($item['base']) > 5)
				echo '<a><span class="badge badge-danger">'. $item['base'] .'</span></a> ';
			else
				echo '<a><span class="badge badge-secondary">'. $item['base'] .'</span></a> ';
			echo (empty($item['subcategory']) ? '<a href="'.$categoryUrl.'" target="_blank"><span class="badge badge-success">'. $item['category'] .'</span></a>'
						: '<a href="'. $categoryUrl.'" target="_blank"><span class="badge badge-warning">'. $item['subcategory'] .'</span></a>');
			$tags = explode(";", $item['tag']);

			for ($i=0,$count=0; $i < count($tags) && $count < 1; $i++) {
				$tagValue = trim($tags[$i]);
				if($tagValue != '' && strpos($categoryName,trim($tagValue)) === false) {
					echo ' <a href="search?tag='.$tagValue.'" target="_blank"><span class="badge badge-info">'. $tagValue .'</span></a>';
					$count++;
				}
			}
			?>	
			</div>
			<span class="glyphicon glyphicon-heart item-favi <?php echo (empty($item['favi'])? '':' item-like'); ?>" item-id="<?php echo $item['id']; ?>" aria-hidden="true" onclick="itemLike(this);"></span>
			<div style="float:right; vertical-align: top;">
				<span class="glyphicon glyphicon-calendar" aria-hidden="true"></span>
				<span ><?php echo $item['date']; ?></span>
			</div>
		</div>
	</div>
</div>
</div>
<?php
}
?>
</div>
<nav aria-label="..." class="bottom-page">
<ul class="pagination justify-content-left pagination">
	<li class="page-item <?php echo ($page==1?'disabled':'');?>">
		<a class="page-link" href="<?php $q = queryString($keyword, $tag, $category, $start, 1, $favi); echo $q; ?>">
			<span aria-hidden="true">&laquo;</span>
		</a>
	</li>
	<?php if($page > 1) {
		$q = queryString($keyword, $tag, $category, $start, $page-1, $favi);
		echo '<li class="page-item"><a class="page-link" href="'.$q.'" aria-label=""><span aria-hidden="true" style="white-space: nowrap;">'.L('prev').'</span></a></li>';
	} else {
		echo '<li class="page-item disabled"><a class="page-link" aria-label=""><span aria-hidden="true">'.L('prev').'</span></a></li>';
	}
	for($i = $page - 2; $i <= $toalPage+1 && $i <= $page+2; $i = $i+1) {
		if($i > 0) {
			if($i == $page)
				echo '<li class="page-item active"><span class="page-link">' . ($i) .'</span></li>';
			else {
				$q = queryString($keyword, $tag, $category, $start, $i, $favi);
				echo '<li class="page-item"><a class="page-link" href="'.$q.'">'. ($i) .'</a></li>';
			}
		}
	}
	if($page <= $toalPage) {
		$q = queryString($keyword, $tag, $category, $start, $page+1, $favi);
		echo '<li class="page-item"><a class="page-link" href="'. $q .'" aria-label=""><span aria-hidden="true" style="white-space: nowrap;">'.L('next').'</span></a></li>';
	} else {
		echo '<li class="page-item disabled"><a class="page-link" aria-label=""><span aria-hidden="true">'.L('next').'</span></a></li>';
	}
	$q = queryString($keyword, $tag, $category, $start, $toalPage+1, $favi);
	?>
	<li class="page-item <?php echo ($page==$toalPage+1?'disabled':''); ?>">
		<a class="page-link" href="<?php echo $q; ?>" aria-label="">
			<span aria-hidden="true">»</span>
		</a>
	</li>
</ul>
</nav>
<div>