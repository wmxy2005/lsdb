<!-- <div class="pageinfo jumbotron">
	<div class="container">
	<div class="row" style="display:flex; flex-wrap: wrap;">
	<div style="padding-left:5px;padding-right:5px;">
	<p class="font-weight-bold">
	<?php echo $favi > 0 ? '<span>【' . L('myfavi') .'】</span>' : ''; ?>
	<?php echo '<span class="badge text-secondary-emphasis bg-secondary-subtle border border-secondary-subtle rounded-pill">'. (!empty($tag)?$tag:$category). '</span> ';?>
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
	<?php echo '<span class="badge text-secondary-emphasis bg-secondary-subtle border border-secondary-subtle rounded-pill">'. (!empty($tag)?$tag:$category). '</span> ';?>
	<?php echo $total_mess . (empty($keyword) ? '' : ', '.L('search_for').'『'. $keyword . '』') . ', ' . L('time_cost') . ' ' . $timeCost; ?>
	</h6>
  </div>
</div> -->
<div id="sort-bar" class="bg-light">
<div id="sort-main" class="container" style="padding: 0px 5px">
<?php
for ($row=0; $row < count($consor_list); $row++) {
$item = $consor_list[$row];
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
  <div class="collapse navbar-collapse" id="navbarNavDropdown0">
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
	<div class="fs-6">
	<?php echo $favi > 0 ? '<span>【' . L('myfavi') .'】</span>' : ''; ?>
	<?php echo '<span class="badge text-secondary-emphasis bg-secondary-subtle border border-secondary-subtle rounded-pill">'. (!empty($tag)?$tag:$category). '</span> ';?>
	<?php echo $total_mess . (empty($keyword) ? '' : ', '.L('search_for').'『'. $keyword . '』') . ', ' . L('time_cost') . ' ' . $timeCost; ?>
	</div>
	<div class="fs-5">
	<?php
	for ($row=0; $row < count($resRole); $row++) {
	$role = $resRole[$row];
	$roleNames= preg_split('[;]', $role['name'], 0, PREG_SPLIT_NO_EMPTY);
	$roleImges= preg_split('[;]', $role['images'], 0, PREG_SPLIT_NO_EMPTY);
	$roleImgeName = '';
	$stypeType = get_style_type($row);
	echo '<a href="' . 'role?id=' . $role['id'] .'" target="_blank" style="margin-top: 5px;" class="badge text-'. $stypeType .'-emphasis bg-'. $stypeType .'-subtle border border-'. $stypeType .'-subtle rounded-pill">';
	if(sizeof($roleImges) > 0) {
		$roleImge = $roleImges[0];
		$roleImgeValues= preg_split('[@]', $roleImge);
		$roleImgeName = $roleImgeValues[0];
		$roleImgeSrc = "";
		if(sizeof($roleImgeValues) >= 2) {
			$roleImgeSrc = $roleImgeValues[sizeof($roleImgeValues)-1];
		}
		
		$searchWord = '';
		if(!empty($tag)) {
			$searchWord = $tag;
		}
		if(!empty($keyword)) {
			$searchWord = $keyword;
		}
		if(!empty($searchWord)) {
			for ($i=1; $i < sizeof($roleImges); $i++) {
				$roleImge2 = $roleImges[$i];
				$roleImgeValues2= preg_split('[@]', $roleImge2);
				$roleImgeName2 = $roleImgeValues2[0];
				$roleImgeSrc2 = "";
				if(sizeof($roleImgeValues2) >= 2) {
					$roleImgeSrc2 = $roleImgeValues[sizeof($roleImgeValues2)-1];
				}
				if(strpos($roleImgeName2, $searchWord) !== false) {
					$roleImgeName = $roleImgeName2;
					$roleImgeSrc = $roleImgeSrc2;
				}
			}
		}
		$imgUrl = 'resource?base=etigoya955&name=e'. $role['id'].'&filename='. $roleImgeSrc;
		echo '<img class="rounded-circle me-1" src="' . $imgUrl . '" alt="" width="24" height="24">';
	}
	if(!empty($searchWord)) {
		for($i = 0; $i < sizeof($roleNames); $i=$i+1) {
			$roleName = $roleNames[$i];
			if(strpos($roleName, $searchWord) !== false) {
				$roleImgeName = $roleName;
			}
		}
	}
	echo $roleImgeName . '</a> ';
	?>
	<?php
	}
	?>
	</div>
	</div>
</div>
<div class="container">
<div class="row" style="display:flex; flex-wrap: wrap;">
<?php
for ($row=0; $row < count($res); $row++) {
$item = $res[$row];
?>
<div class="col-sm-6 col-md-4 col-lg-3 position-relative" style="padding-left:5px;padding-right:5px;padding-bottom: 10px;">
    <div class="card h-100 d-flex flex-column justify-content-between no-gutters rounded overflow-hidden shadow-sm">
      	<a class="text-center card-img-top" href="detail?id=<?php echo $item['id']; ?>">
			<img class="img-fluid cover" src="<?php echo 'resource?base='. $item['base'].'&cata='. $item['category'] .'&subcata='.$item['subcategory'].'&name='. $item['name'].'&filename='. $item['thumbnail']; ?>">
		</a>
		<div class="card-body" style="padding: 0.2em 0.2em">
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
			$categoryUrl = queryString('', '', $item['subcategory'], $item['category'], 0, 0, 0, 2, $sorts, $display);
			$categoryName = empty($item['subcategory']) ? $item['category'] : $item['subcategory'];
			if(empty($base)){
				if(strlen($item['base']) > 5)
					echo '<a><span class="badge text-danger-emphasis bg-danger-subtle border border-danger-subtle rounded-pill">'. $item['base'] .'</span></a> ';
				else
					echo '<a><span class="badge text-secondary-emphasis bg-secondary-subtle border border-secondary-subtle rounded-pill">'. $item['base'] .'</span></a> ';
			}
			echo (empty($item['subcategory']) ? '<a class="badge text-success-emphasis bg-success-subtle border border-success-subtle rounded-pill" href="'.$categoryUrl.'" target="_blank">'. $item['category'] .'</a>'
						: '<a class="badge text-warning-emphasis bg-warning-subtle border border-warning-subtle rounded-pill" href="'. $categoryUrl.'" target="_blank">'. $item['subcategory'] .'</a>');
			$tags = explode(";", $item['tag']);

			for ($i=0,$count=0; $i < count($tags) && $count < 1; $i++) {
				$tagValue = trim($tags[$i]);
				if($tagValue != '' && strpos($categoryName,trim($tagValue)) === false) {
					echo ' <a class="badge text-info-emphasis bg-info-subtle border border-info-subtle rounded-pill" href="search?tag='.$tagValue.'" target="_blank">'. $tagValue .'</a>';
					$count++;
				}
			}
			?>	
			</div>
		</div>
		<div class="card-footer">
			<div class="d-flex justify-content-between align-items-center">
				<span class="glyphicon glyphicon-heart item-favi <?php echo (empty($item['favi'])? '':' item-like'); ?>" item-id="<?php echo $item['id']; ?>" aria-hidden="true" onclick="itemLike(this);"></span>
				
				<div>
					<span class="glyphicon glyphicon-calendar" aria-hidden="true"></span>
					<span><?php echo $item['date']; ?></span>
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
		<a class="page-link" href="<?php $q = queryString($base, $keyword, $tag, $category, $start, 1, $favi, $censor, $sorts, $display); echo $q; ?>">
			<span aria-hidden="true">&laquo;</span>
		</a>
	</li>
	<?php if($page > 1) {
		$q = queryString($base, $keyword, $tag, $category, $start, $page-1, $favi, $censor, $sorts, $display);
		echo '<li class="page-item"><a class="page-link" href="'.$q.'" aria-label=""><span aria-hidden="true" style="white-space: nowrap;">'.L('prev').'</span></a></li>';
	} else {
		echo '<li class="page-item disabled"><a class="page-link" aria-label=""><span aria-hidden="true">'.L('prev').'</span></a></li>';
	}
	for($i = $page - 2; $i <= $toalPage+1 && $i <= $page+2; $i = $i+1) {
		if($i > 0) {
			if($i == $page)
				echo '<li class="page-item active"><span class="page-link">' . ($i) .'</span></li>';
			else {
				$q = queryString($base, $keyword, $tag, $category, $start, $i, $favi, $censor, $sorts, $display);
				echo '<li class="page-item"><a class="page-link" href="'.$q.'">'. ($i) .'</a></li>';
			}
		}
	}
	if($page <= $toalPage) {
		$q = queryString($base, $keyword, $tag, $category, $start, $page+1, $favi, $censor, $sorts, $display);
		echo '<li class="page-item"><a class="page-link" href="'. $q .'" aria-label=""><span aria-hidden="true" style="white-space: nowrap;">'.L('next').'</span></a></li>';
	} else {
		echo '<li class="page-item disabled"><a class="page-link" aria-label=""><span aria-hidden="true">'.L('next').'</span></a></li>';
	}
	$q = queryString($base, $keyword, $tag, $category, $start, $toalPage+1, $favi, $censor, $sorts, $display);
	?>
	<li class="page-item <?php echo ($page==$toalPage+1?'disabled':''); ?>">
		<a class="page-link" href="<?php echo $q; ?>" aria-label="">
			<span aria-hidden="true">»</span>
		</a>
	</li>
</ul>
</nav>
<div>