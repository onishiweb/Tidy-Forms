// Modular JS file
ARCHITECT_FORMS_TINYMCE = (function ($) {

	// Breakpoints
	var	init = function () {
			// runs like jQuery normally would when everything is ready
			// run all functions for the site inside the domReady function
			$(domReady); // same as $(document).ready (function () {...});
		},

		// this runs only when we know the whole DOM is ready
		domReady = function () {

			tinymce.PluginManager.add('architect_forms', function( editor, url ) {
				editor.addButton('architect_forms', {
					text: '',
					tooltip: 'WP Architect Form',
					icon: 'arc-form-mce-button',
					onclick: function() {

						// todo: Add loading spinner

						var request = {'action': 'arc_get_form_values'};

					    $.ajax({
							type: 'POST',
							dataType: 'json',
							url: ajaxurl,
							data: request,
							success: function(response) {
								editor.windowManager.open( {
									title: 'Insert form',
									body: [
										{
											type: 'listbox',
											name: 'architectFormsBox',
											label: 'Select form',
											values: response
										}
									],
									onsubmit: function( e ) {
										editor.insertContent( '[architect-form id="' + e.data.architectFormsBox + '"]');
									}
								});
							}
						});
					}
				});
			});
		};

	return {
		go : init
	};

})(jQuery);

ARCHITECT_FORMS_TINYMCE.go();
