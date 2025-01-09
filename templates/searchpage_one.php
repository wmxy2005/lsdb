<!-- <div class="pageinfo jumbotron">
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
</div> -->
<!-- <div class="headerinfo container">
  <div class="d-flex align-items-center p-3 my-3 rounded shadow-sm" style="background-color:#e9ecef">
	<h6 class="mb-0 lh-100">
	<?php echo $favi > 0 ? '<span>【' . L('myfavi') .'】</span>' : ''; ?>
	<?php echo '<span class="badge badge-pill badge-secondary">'. (!empty($tag)?$tag:$category). '</span> ';?>
	<?php echo $total_mess . (empty($keyword) ? '' : ', '.L('search_for').'『'. $keyword . '』') . ', ' . L('time_cost') . ' ' . $timeCost; ?>
	</h6>
  </div>
</div> -->
<div id="sort-bar" class="bg-light">
<div id="sort-main" class="container" style="padding: 0px 5px">
<?php
for ($row=0; $row < count($type_list); $row++) {
$item = $type_list[$row];
?>
<a class="toolbar <?php echo !empty($item['active'])?'active':'';?>" href="<?php echo $item['href']; ?>"><?php echo $item['mess']; ?></a>
<?php
}
?>
<nav style="float: right;padding: 0px" class="navbar navbar-expand navbar-light">
  <div class="collapse navbar-collapse" id="navbarNavDropdownBase">
    <ul class="navbar-nav">
      <li class="nav-item dropdown">
		<button class="btn dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
          <?php echo $base_name; ?>
        </button>
        <ul class="dropdown-menu">
		<?php
		for ($row=0; $row < count($base_list); $row++) {
		$item = $base_list[$row];
		?>
		<li><a class="dropdown-item <?php echo !empty($item['active'])?'active':''; ?>" href="<?php echo $item['href']; ?>"><?php echo $item['mess']; ?></a></li>
		<?php
		}
		?>
        </ul>
      </li>
    </ul>
  </div>
  <div class="collapse navbar-collapse" id="navbarNavDropdownStyle">
    <ul class="navbar-nav">
      <li class="nav-item dropdown">
        <button class="btn dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
          <?php echo $display_name; ?>
        </button>
        <ul class="dropdown-menu">
		<?php
		for ($row=0; $row < count($display_list); $row++) {
		$item = $display_list[$row];
		?>
		<li><a class="dropdown-item <?php echo !empty($item['active'])?'active':''; ?>" href="<?php echo $item['href']; ?>"><?php echo $item['mess']; ?></a></li>
		<?php
		}
		?>
        </ul>
      </li>
    </ul>
  </div>
  <div class="collapse navbar-collapse" id="navbarNavDropdown">
    <ul class="navbar-nav">
      <li class="nav-item dropdown">
        <button class="btn dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
          <?php echo $sort_name; ?>
        </button>
        <ul class="dropdown-menu">
		<?php
		for ($row=0; $row < count($sort_list); $row++) {
		$item = $sort_list[$row];
		?>
		<li><a class="dropdown-item <?php echo !empty($item['active'])?'active':''; ?>" href="<?php echo $item['href']; ?>"><?php echo $item['mess']; ?></a></li>
		<?php
		}
		?>
        </ul>
      </li>
    </ul>
  </div>
</nav>
</div>
</div>
<div class="headerinfo container">
	<div class="bd-note">
	<h6 class="mb-0 lh-100">
	<?php echo $favi > 0 ? '<span>【' . L('myfavi') .'】</span>' : ''; ?>
	<?php echo '<span class="badge text-secondary-emphasis bg-secondary-subtle border border-secondary-subtle rounded-pill">'. (!empty($tag)?$tag:$category). '</span> ';?>
	<?php echo $total_mess . (empty($keyword) ? '' : ', '.L('search_for').'『'. $keyword . '』') . ', ' . L('time_cost') . ' ' . $timeCost; ?>
	</h6>
	</div>
</div>
<div class="container">

<?php
for ($row=0; $row < count($res); $row++) {
$item = $res[$row];
?>
<div class="col-sm-12 col-md-12 col-lg-12" style="padding-left:5px;padding-right:5px;">
<div class="row no-gutters rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative thumbnail">
     
      	<div class="col-md-8" style="padding: 0;">
		<a class="text-center" href="detail?id=<?php echo $item['id']; ?>">
			<img class="img-fluid onecover" src="<?php echo 'resource?base='. $item['base'].'&cata='. $item['category'] .'&subcata='.$item['subcategory'].'&name='. $item['name'].'&filename='. $item['thumbnail']; ?>">
		</a>
		</div>
		<div class="col-md-4">
		<div class="h-100 d-flex flex-column justify-content-between no-gutters rounded overflow-hidden">
			<div class="caption" style="card-body">
				<div class="">
					<a href="detail?id=<?php echo $item['id']; ?>" data-toggle="tooltip1" title="<?php echo $item['title']; ?>">
					<p style="display: -webkit-box;-webkit-line-clamp: 2;-webkit-box-orient: vertical;overflow: hidden;margin-top: .5rem">
					  <?php echo $item['title']; ?>
					</p>
					</a>
					<p class="mycontent" style="display: -webkit-box;-webkit-line-clamp: 10;-webkit-box-orient: vertical;overflow: hidden;">
					<?php
					echo str_replace(PHP_EOL, "", $item['content']);
					echo '<br/>' . $item['base'].' · '. $item['category'] . (empty($item['subcategory'])?'':' · ' . $item['subcategory'] . '')  . ' · '. $item['name'] . '';
					?>
					</p>
				</div>
			</div>
			<div style="card-footer">
			<?php
			$categoryUrl = queryString('', '', $item['subcategory'], '', '', $item['category'], 0, 0, 0, 999, $sorts, $display);
			$categoryName = empty($item['subcategory']) ? $item['category'] : $item['subcategory'];
			echo (empty($item['subcategory']) ? '<a class="badge text-success-emphasis bg-success-subtle border border-success-subtle rounded-pill" href="'.$categoryUrl.'" target="_blank">'. $item['category'] .'</a>'
						: '<a class="badge text-warning-emphasis bg-warning-subtle border border-warning-subtle rounded-pill" href="'. $categoryUrl.'" target="_blank">'. $item['subcategory'] .'</a>');
			$tags = explode(";", $item['tag']);

			for ($i=0,$count=0; $i < count($tags) && $count<10; $i++) {
				$tagValue = trim($tags[$i]);
				if($tagValue != '' && strpos($categoryName,trim($tagValue)) === false) {
					echo ' <a class="badge text-info-emphasis bg-info-subtle border border-info-subtle rounded-pill" href="'.queryString('', '', $tagValue, '', '', '', 0, 1, 0, 999, $sorts, $display).'" target="_blank">'. $tagValue .'</a>';
					$count++;
				}
			}
			?>
			<div style="margin-bottom: 5px;">
			<span class="glyphicon glyphicon-heart item-favi <?php echo (empty($item['favi'])? '':' item-like'); ?>" item-id="<?php echo $item['id']; ?>" aria-hidden="true" onclick="itemLike(this);"></span>
			<div style="float:right; vertical-align: top;">
				<span class="glyphicon glyphicon-calendar" aria-hidden="true"></span>
				<span ><?php echo $item['date']; ?></span>
			</div>
			</div>
			</div>
			
		</div>
		</div>
	
</div>
</div>
<?php
}
?>

<nav aria-label="..." class="bottom-page">
<ul class="pagination justify-content-left pagination">
	<li class="page-item <?php echo ($page==1?'disabled':'');?>">
		<a class="page-link" href="<?php $q = queryString($base, $keyword, $tag, $tag2, $tag3, $category, $start, 1, $favi, $type, $sorts, $display); echo $q; ?>">
			<span aria-hidden="true">&laquo;</span>
		</a>
	</li>
	<?php if($page > 1) {
		$q = queryString($base, $keyword, $tag, $tag2, $tag3, $category, $start, $page-1, $favi, $type, $sorts, $display);
		echo '<li class="page-item"><a class="page-link" href="'.$q.'" aria-label=""><span aria-hidden="true" style="white-space: nowrap;">'.L('prev').'</span></a></li>';
	} else {
		echo '<li class="page-item disabled"><a class="page-link" aria-label=""><span aria-hidden="true">'.L('prev').'</span></a></li>';
	}
	for($i = $page - 2; $i <= $toalPage+1 && $i <= $page+2; $i = $i+1) {
		if($i > 0) {
			if($i == $page)
				echo '<li class="page-item active"><span class="page-link">' . ($i) .'</span></li>';
			else {
				$q = queryString($base, $keyword, $tag, $tag2, $tag3, $category, $start, $i, $favi, $type, $sorts, $display);
				echo '<li class="page-item"><a class="page-link" href="'.$q.'">'. ($i) .'</a></li>';
			}
		}
	}
	if($page <= $toalPage) {
		$q = queryString($base, $keyword, $tag, $tag2, $tag3, $category, $start, $page+1, $favi, $type, $sorts, $display);
		echo '<li class="page-item"><a class="page-link" href="'. $q .'" aria-label=""><span aria-hidden="true" style="white-space: nowrap;">'.L('next').'</span></a></li>';
	} else {
		echo '<li class="page-item disabled"><a class="page-link" aria-label=""><span aria-hidden="true">'.L('next').'</span></a></li>';
	}
	$q = queryString($base, $keyword, $tag, $tag2, $tag3, $category, $start, $toalPage+1, $favi, $type, $sorts, $display);
	?>
	<li class="page-item <?php echo ($page==$toalPage+1?'disabled':''); ?>">
		<a class="page-link" href="<?php echo $q; ?>" aria-label="">
			<span aria-hidden="true">»</span>
		</a>
	</li>
</ul>
</nav>
<div>
<?php require 'backtop.tpl';?>