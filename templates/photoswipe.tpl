<link rel="stylesheet" href="./assets/photoswipe/photoswipe.css">
<script type="module">
import PhotoSwipeLightbox from './assets/photoswipe/photoswipe-lightbox.esm.js';
import PhotoSwipe from './assets/photoswipe/photoswipe.esm.js';

const lightbox = new PhotoSwipeLightbox({
  gallery: '#gallery-images',
  children: 'a',
  pswpModule: PhotoSwipe,
  secondaryZoomLevel: 1,
  maxZoomLevel: 2,
  bgOpacity: 1
});

lightbox.init();
</script>