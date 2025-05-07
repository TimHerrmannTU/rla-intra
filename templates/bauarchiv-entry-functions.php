<!-- Lightbox code -->
<link rel="stylesheet" href="/intranet_rla/wp_intra24/wp-content/themes/rlaintra24/static/css/photoswipe.css">
<script type="module">
import PhotoSwipeLightbox from '/intranet_rla/wp_intra24/wp-content/themes/rlaintra24/static/js/photoswipe-lightbox.esm.js';
export function photoSwipeInit() {
	const options = {
		gallery:'.bauteil-grid',
		children:'a.gallery-item',

		initialZoomLevel: 'fit',
		secondaryZoomLevel: 1.5,
		maxZoomLevel: 1,

		pswpModule: () => import('/intranet_rla/wp_intra24/wp-content/themes/rlaintra24/static/js/photoswipe.esm.js')
	};
	const lightbox = new PhotoSwipeLightbox(options);
	lightbox.init();
}
photoSwipeInit()
</script>

<?php
?>