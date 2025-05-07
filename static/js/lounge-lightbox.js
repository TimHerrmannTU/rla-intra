import PhotoSwipeLightbox from '/intranet_rla/wp_intra24/wp-content/themes/rlaintra24/static/js/photoswipe-lightbox.esm.js';
const options = {
  gallery:'#gallery',
  children:'a.item',

  initialZoomLevel: 'fit',
  secondaryZoomLevel: 1.5,
  maxZoomLevel: 1,
  
  pswpModule: () => import('/intranet_rla/wp_intra24/wp-content/themes/rlaintra24/static/js/photoswipe.esm.js')
};
const lightbox = new PhotoSwipeLightbox(options);
lightbox.on('uiRegister', function() {
  	// download button
	lightbox.pswp.ui.registerElement({
		name: 'download-button',
		order: 8,
		isButton: true,
		tagName: 'a',

		// SVG with outline
		html: {
		isCustomSVG: true,
		inner: '<path d="M20.5 14.3 17.1 18V10h-2.2v7.9l-3.4-3.6L10 16l6 6.1 6-6.1ZM23 23H9v2h14Z" id="pswp__icn-download"/>',
		outlineID: 'pswp__icn-download'
		},

		onInit: (el, pswp) => {
			el.setAttribute('download', '');
			el.setAttribute('target', '_blank');
			el.setAttribute('rel', 'noopener');

			pswp.on('change', () => {
				const new_download = pswp.currSlide.data.element.dataset.downloadLink;
				el.href = new_download;
				if (new_download == "") $(el).hide()
				else $(el).show()
			});
		}
	});
	// caption
	lightbox.pswp.ui.registerElement({
		name: 'custom-caption',
		order: 9,
		isButton: false,
		appendTo: 'root',
		html: 'Caption',
		onInit: (el, pswp) => {
		  lightbox.pswp.on('change', () => {
			const ele_data = lightbox.pswp.currSlide.data.element.dataset;
			var cap = "<h3><a href='"+ele_data.wbLink+"'>"+ele_data.caption+"</a> <span class='dashicons dashicons-external'></span></h3>";
			el.innerHTML = cap || '';
		  });
		}
	});
	// info
	lightbox.pswp.ui.registerElement({
		name: 'custom-info',
		order: 9,
		isButton: false,
		appendTo: 'root',
		html: 'Info',
		onInit: (el, pswp) => {
			lightbox.pswp.on('change', () => {
				const ele_data = lightbox.pswp.currSlide.data.element.dataset;
				var infoText = "<span>"+ele_data.date+"</span>"
				if (ele_data.place != "keine Auszeichnung") infoText += "<span>"+ele_data.place+"</span>"
				el.innerHTML = infoText || '';
			});
		}
	});
});
lightbox.init();