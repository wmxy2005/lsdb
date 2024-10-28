<link rel="stylesheet" href="/core/photoswipe/photoswipe.css">
<script type="module">
import PhotoSwipeLightbox from '/core/photoswipe/photoswipe-lightbox.esm.js';
import PhotoSwipe from '/core/photoswipe/photoswipe.esm.js';

const lightbox = new PhotoSwipeLightbox({
  gallery: '#gallery-images',
  children: 'a',
  pswpModule: PhotoSwipe
});

lightbox.init();
</script>