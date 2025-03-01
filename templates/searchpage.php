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
	<div class="fs-6">
	<?php echo $favi > 0 ? '<span>【' . L('myfavi') .'】</span>' : ''; ?>
	<?php echo '<span class="badge text-secondary-emphasis bg-secondary-subtle border border-secondary-subtle rounded-pill">'. (!empty($tag)?$tag:$category). '</span> ';?>
	<?php echo '<span class="badge text-secondary-emphasis bg-secondary-subtle border border-secondary-subtle rounded-pill">'. (!empty($tag2)?$tag2:''). '</span> ';?>
	<?php echo '<span class="badge text-secondary-emphasis bg-secondary-subtle border border-secondary-subtle rounded-pill">'. (!empty($tag3)?$tag3:''). '</span> ';?>
	<?php echo $total_mess . (empty($keyword) ? '' : ', '.L('search_for').'『'. $keyword . '』') . ', ' . L('time_cost') . ' ' . $timeCost; ?>
	</div>
	<div class="fs-5">
	<?php
    $conf = Config::$config;
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
		$imgUrl = 'resource?base=' . $conf['role'] . '&name=e'. $role['id'].'&filename='. $roleImgeSrc;
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
      	<a class="text-center card-img-top thumbnail__link" href="detail?id=<?php echo $item['id']; ?>">
			<!-- <div class="resolutions" data-v-28702a91="" data-v-01a8294c="" style="
				align-items: center;
				background: #fff;
				border: 1.5px solid #fff;
				border-radius: 6px;
				color: #fff;
				cursor: pointer;
				display: flex;
				height: 100%;
				justify-content: center;
				max-height: 20px;
				max-width: 60px;
				position: absolute;
				right: 8px;
				top: 8px;
				width: 100%;
				z-index: 1;
			">			
			<img class="resolution-value" src="resource?base=fpvr&filename=icon.svg" style="width: 59px; height: 17px;"></img>
			</div> --> 
			<picture>
			<img class="img-fluid cover" src="<?php echo 'resource?base='. $item['base'].'&cata='. $item['category'] .'&subcata='.$item['subcategory'].'&name='. $item['name'].'&filename='. $item['thumbnail']; ?>">
			</picture>
			<?php
			if (!empty($item['roll'])) {
			?>
			<div class="thumbnail__video">
			<video autoplay="" loop="" muted="" playsinline="" src="<?php echo 'resource?base='. $item['base'].'&cata='. $item['category'] .'&subcata='.$item['subcategory'].'&name='. $item['name'].'&filename='. $item['roll']; ?>"></video>
			</div>
			<?php
			}
			?>
		</a>
		<div class="card-body" style="padding: 0.2em 0.2em;display: flex; flex-direction: column; justify-content: flex-end;">
			
			<div class="caption" style="display: flex;">
				
				<div class="" style="padding: 5px;align-content: center;justify-content: center;">
				<a href="<?php echo 'search?base='.$item['base']; ?>" class="" style="text-decoration: none;">
					<?php
					$conf = Config::$config;
					$base_dir = $conf['folder'];					
					$icon_category = '';
					$icon_name = '';
					if (file_exists(getImagePath($base_dir, $item['base'], $item['category'], '', '', 'logo.png'))) {
						$icon_name = 'logo.png';
						$icon_category = $item['category'];
					} else if (file_exists(getImagePath($base_dir, $item['base'], $item['category'], '', '', 'logo.jpg'))) {
						$icon_name = 'logo.png';
						$icon_category = $item['category'];
					} else if (file_exists(getImagePath($base_dir, $item['base'], $item['category'], '', '', 'logo.svg'))) {
						$icon_name = 'logo.svg';
						$icon_category = $item['category'];
					} else if (file_exists(getImagePath($base_dir, $item['base'], '', '', '', 'logo.png'))) {
						$icon_name = 'logo.png';
					} else if (file_exists(getImagePath($base_dir, $item['base'], '', '', '', 'logo.jpg'))) {
						$icon_name = 'logo.jpg';
					} else if (file_exists(getImagePath($base_dir, $item['base'], '', '', '', 'logo.svg'))) {
						$icon_name = 'logo.svg';
					}
					
					if (!empty($icon_name)) {
						$icon_url = getImageUrl($item['base'], $icon_category, '', '', $icon_name);
					?>
					<div style="
						display: flex;
						justify-content: center;
						align-items: center;
						width: 35px;
						height: 35px;
						border-radius: 50%;
						font-size: 12px;
						color: white;
						background-color: #212529;
						font-weight: bold;
					">
					<img class="" src="<?php echo $icon_url; ?>" style="width: 35px;height: 35px;border-radius: 50%;">
					</div>
					<?php
					} else {
					?>
					<div style="
						display: flex;
						justify-content: center;
						align-items: center;
						width: 35px;
						height: 35px;
						border-radius: 50%;
						font-size: 12px;
						color: white;
						background-color: #007bff;
						font-weight: bold;
					"><?php echo strtoupper(substr($item['base'], 0, 3)); ?></div>
					<?php
					}
					?>
				</a>
				</div>
				
				<div class="mytitle" style="padding-left: 0.2rem;">
					<a href="detail?id=<?php echo $item['id']; ?>" data-toggle="tooltip1" title="<?php echo $item['title']; ?>" style="color: black;">
					<p style="display: -webkit-box;-webkit-line-clamp: 2;-webkit-box-orient: vertical;overflow: hidden;">
					  <?php echo $item['title']; ?>
					</p></a>
				
				</div>
			</div>
			<div style="margin-bottom: 5px;">
			<?php
			$categoryUrl = queryString('', '', $item['subcategory'], '', '', $item['category'], 0, 0, 0, 999, $sorts, $display);
			$categoryName = empty($item['subcategory']) ? $item['category'] : $item['subcategory'];
			if(empty($base) && 1 == 2){
				if(strlen($item['base']) > 5)
					echo '<a href="search?base='.$item['base'].'" target="_blank"><span class="badge text-danger-emphasis bg-danger-subtle border border-danger-subtle rounded-pill">'. $item['base'] .'</span></a> ';
				else
					echo '<a href="search?base='.$item['base'].'" target="_blank"><span class="badge text-secondary-emphasis bg-secondary-subtle border border-secondary-subtle rounded-pill">'. $item['base'] .'</span></a> ';
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