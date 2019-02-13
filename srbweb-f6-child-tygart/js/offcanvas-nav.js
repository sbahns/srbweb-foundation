
jQuery(document).ready(function() {

	// off-canvas menu accordion
	jQuery('ul.my-off-canvas-menu li.is-accordion-submenu-parent > a').on('click', function(event) {
		event.preventDefault();
	});

	jQuery('ul.my-off-canvas-menu li.is-accordion-submenu-parent a').on('click', function(event) {
		event.stopPropagation();

		var x = jQuery(this).closest('li');

		if (x.children('ul').hasClass('open')) {
			x.children('ul').removeClass('open');
			x.removeClass('minus');
			x.children('ul').slideUp('slow');
		} else {
			jQuery('li.is-accordion-submenu-parent').children('ul').removeClass('open');
			jQuery('li.is-accordion-submenu-parent').removeClass('minus');

			x.parents('ul').addClass('open');
			x.children('ul').addClass('open');

			x.parents('li').addClass('minus');
			x.addClass('minus');

		}
	});

});
