<footer class="footer">
  <div class="container">
	<p class="text-muted">
	<a class="gh-btn" id="gh-btn" href="https://github.com/wmxy2005/lsdb" target="_blank" aria-label="Star on GitHub"><img style="max-height: 25px"  src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB2ZXJzaW9uPSIxLjEiIGlkPSJMYXllcl8xIiB4PSIwcHgiIHk9IjBweCIgd2lkdGg9IjQwcHgiIGhlaWdodD0iNDBweCIgdmlld0JveD0iMTIgMTIgNDAgNDAiIGVuYWJsZS1iYWNrZ3JvdW5kPSJuZXcgMTIgMTIgNDAgNDAiIHhtbDpzcGFjZT0icHJlc2VydmUiPjxwYXRoIGZpbGw9IiMzMzMzMzMiIGQ9Ik0zMiAxMy40Yy0xMC41IDAtMTkgOC41LTE5IDE5YzAgOC40IDUuNSAxNS41IDEzIDE4YzEgMC4yIDEuMy0wLjQgMS4zLTAuOWMwLTAuNSAwLTEuNyAwLTMuMiBjLTUuMyAxLjEtNi40LTIuNi02LjQtMi42QzIwIDQxLjYgMTguOCA0MSAxOC44IDQxYy0xLjctMS4yIDAuMS0xLjEgMC4xLTEuMWMxLjkgMC4xIDIuOSAyIDIuOSAyYzEuNyAyLjkgNC41IDIuMSA1LjUgMS42IGMwLjItMS4yIDAuNy0yLjEgMS4yLTIuNmMtNC4yLTAuNS04LjctMi4xLTguNy05LjRjMC0yLjEgMC43LTMuNyAyLTUuMWMtMC4yLTAuNS0wLjgtMi40IDAuMi01YzAgMCAxLjYtMC41IDUuMiAyIGMxLjUtMC40IDMuMS0wLjcgNC44LTAuN2MxLjYgMCAzLjMgMC4yIDQuNyAwLjdjMy42LTIuNCA1LjItMiA1LjItMmMxIDIuNiAwLjQgNC42IDAuMiA1YzEuMiAxLjMgMiAzIDIgNS4xYzAgNy4zLTQuNSA4LjktOC43IDkuNCBjMC43IDAuNiAxLjMgMS43IDEuMyAzLjVjMCAyLjYgMCA0LjYgMCA1LjJjMCAwLjUgMC40IDEuMSAxLjMgMC45YzcuNS0yLjYgMTMtOS43IDEzLTE4LjFDNTEgMjEuOSA0Mi41IDEzLjQgMzIgMTMuNHoiLz48L3N2Zz4="></a>
	Copyright © 2019. wmxy2005 All Rights Reserved. 
	</p>
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