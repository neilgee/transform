jQuery(function( $ ){

	// Local Scroll Speed
	$.localScroll({
		duration: 750
	});

	function home_hero_height() {

		// WordPress admin bar height
		var barHeight = $('#wpadminbar').outerHeight();

		// Viewport height
		var windowHeight = window.innerHeight;

		// Viewport height minus WordPress admin bar height
		var newHeight = windowHeight - barHeight;

		$( '.image-section, .image-section-hero' ).css({'height': newHeight + 'px'});
	}

	// http://stackoverflow.com/a/1974797/778809
	// Bind to the resize event of the window object
	$(window).on("resize", function () {
		home_hero_height();
		// Invoke the resize event immediately
	}).resize();

});
