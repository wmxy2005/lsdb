<?php require_once 'core/init.php';
$pagetag = L('sitename');
if(!empty($keyword)) {
	$pagetag = $keyword;
} else if (!empty($tag)) {
	$pagetag = $tag;
} else if (!empty($category)) {
	$pagetag = $category;
}
$title = 'Test';
require 'templates/header.tpl'; ?>
<div id="content">
	<div class="headerinfo container">
		<div class="bd-note">
		<h6 class="mb-0 lh-100">
			<?php echo L('searching'); ?>
		</h6>
		</div>
	</div>
	<div class="container">
    <div class="row" style="display:flex; flex-wrap: wrap;">
        <div class="col-sm-6 col-md-4 col-lg-12" style="padding-left:5px;padding-right:5px;">
            <div class="row no-gutters rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative thumbnail">
                <div class="d-flex flex-column justify-content-between" style="height:100%;width:100%;">
                    <a class="text-center" href="detail?id=7577">
                        <img class="img-fluid" src="resource?base=javfree&cata=heyzo&subcata=&name=heyzo-1407&filename=heyzo_hd_1407_full.jpg">
                    </a>
                    <div style="margin-left:.3em;margin-right:.3em;margin-bottom:.2em">
                        <div class="caption">
                            <div class="mytitle">
                                <a href="detail?id=7577" data-toggle="tooltip1" title="Heyzo 1407 加藤えま【かとうえま】 続々生中～乱れまくりの清純系～">
                                    <p style="display: -webkit-box;-webkit-line-clamp: 2;-webkit-box-orient: vertical;overflow: hidden;">Heyzo 1407 加藤えま【かとうえま】 続々生中～乱れまくりの清純系～</p>
                                </a>
                            </div>
                        </div>
                        <div style="margin-bottom: 5px;">	<a><span class="badge badge-danger">javfree</span></a>  <a class="badge badge-success" href="search?category=heyzo" target="_blank">heyzo</a>  <a class="badge badge-info" href="search?tag=加藤えま" target="_blank">加藤えま</a>	
                        </div>	<span class="glyphicon glyphicon-heart item-favi  item-like" item-id="7577" aria-hidden="true" onclick="itemLike(this);"></span>

                        <div style="float:right; vertical-align: top;">	<span class="glyphicon glyphicon-calendar" aria-hidden="true"></span>
							<span>2017-03-29</span>
						</div>
                    </div>
                </div>
            </div>
        </div>
	</div>
</div>
<link href="core/css/search.css" rel="stylesheet">
<script>
</script>
<?php require 'templates/footer.tpl'; ?>