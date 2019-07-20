<?php require_once 'core/init.php';
$title = L('not_found');
require_once 'templates/header.tpl'; ?>
<div class="pageinfo">
  <div class="container searchword alert alert-danger">
    <h4><?php echo L('not_found'); ?></h4>
  </div>
</div>

<link href="search.css" rel="stylesheet">
<div class="row" style="display:flex; flex-wrap: wrap;">
<?php require_once 'templates/footer.tpl'; ?>