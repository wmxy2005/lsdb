<link rel="stylesheet" href="./assets/xgplayer/index.min.css">
<script src="./assets/xgplayer/index.min.js" type="text/javascript"></script>
<script>
var video_url = document.getElementById('video_url');
var poster_url = document.getElementById('poster_url');
if (video_url) {
let player = new Player({
  id: 'xplayer',
  fluid: true,
  volume: 0.2,
  url: video_url.src,
  poster: poster_url.src
});
}
</script>