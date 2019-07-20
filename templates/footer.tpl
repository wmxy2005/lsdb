<footer class="footer">
  <div class="container">
	<p class="text-muted">Copyright © 2019. wmxy2005 All Rights Reserved. </p>
  </div>
</footer>
<script>
var isFaviProcessing = false;
function itemLike(element) {
if(isFaviProcessing)
	return;
isFaviProcessing = true;
var itemId = element.getAttribute('item-id');
let elementClass = element.classList;
var isItemFavi = !elementClass.contains("item-like");
elementClass.add("item-addingfavi");
$.ajax({
	type: "POST",
	url: "addfavi.php",
	data : {
		"id":itemId,
		"favi":isItemFavi
	},
	success: function (response) {
		setTimeout(function(){
			elementClass.remove("item-addingfavi");
			if(isItemFavi) {
				elementClass.add("item-like");
			} else {
				elementClass.remove("item-like");
			}
			isFaviProcessing = false;
		}, 150);
	},
	error: function (response) {
		setTimeout(function(){
			elementClass.remove("item-addingfavi");
			isFaviProcessing = false;
		}, 150);
	}
});
}
</script>
<script src="dist/js/bootstrap.min.js"></script>
</body>
</html>