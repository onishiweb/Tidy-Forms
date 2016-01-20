// Modular JS file
Tidy_Forms_TINYMCE = (function ($) {

	// Breakpoints
	var	init = function () {
			// runs like jQuery normally would when everything is ready
			// run all functions for the site inside the domReady function
			$(domReady); // same as $(document).ready (function () {...});
		},

		// this runs only when we know the whole DOM is ready
		domReady = function () {

			tinymce.PluginManager.add('Tidy_Forms', function( editor, url ) {
				editor.addButton('Tidy_Forms', {
					text: '',
					tooltip: 'Tidy Forms',
					icon: 'tidy-form-mce-button',
					onclick: function() {

						// todo: Add loading spinner

						var request = {'action': 'tidy_get_form_values'};

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
											name: 'tidyFormsBox',
											label: 'Select form',
											values: response
										}
									],
									onsubmit: function( e ) {
										editor.insertContent( '[tidy-form id="' + e.data.tidyFormsBox + '"]');
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

Tidy_Forms_TINYMCE.go();
