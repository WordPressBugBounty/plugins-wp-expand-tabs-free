; (function($) {

	'use strict';

	// Tab override toggle.
	$('.sp-tab-toggle').on('change', function () {
		var container = $(this).closest('.sp-tab-item');
		var content = container.find('.sp-tab-content');
		if ($(this).is(':checked')) {
			content.slideDown(200);
		} else {
			content.slideUp(200);
		}
	});

}(jQuery));
