jQuery(document).on('ready', function ($) {

	jQuery('.tidy-fields-sortable').sortable({
		placeholder:'ui-state-highlight'
	});

	jQuery('.tidy-fields-sortable').disableSelection();

});
