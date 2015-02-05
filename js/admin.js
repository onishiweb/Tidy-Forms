// Modular JS file
TIDY_FORMS = (function ($) {

	// Breakpoints
	var	init = function () {
			// runs like jQuery normally would when everything is ready
			// run all functions for the site inside the domReady function
			$(domReady); // same as $(document).ready (function () {...});
		},

		// this runs only when we know the whole DOM is ready
		domReady = function () {

			if( $('#tidy-form-fields').length ) {
				$('#tidy-form-fields').on('click', '[tidy-action-add-field]', addField);
			}

			if( $('.tidy-fields-sortable').length ) {
				sortableFields();
			}

			if( $('.tidy-field').length ) {
				$('.tidy-fields').on('click', '[tidy-action-edit]', editField);
				$('.tidy-fields').on('click', '[tidy-action-delete]', deleteField);
			}

		},

		sortableFields = function() {
			$('.tidy-fields-sortable').sortable({
				placeholder:'tidy-sortable-placeholder'
			});

			$('.tidy-fields-sortable').disableSelection();
		},

		addField = function(e) {
			e.preventDefault();

			var $field = $('.tidy-field-placeholder').clone(true),
				$field_title = $field.find('th.row-title'),
				field_count = $('.tidy-fields .tidy-field').length;


			$field.removeClass('tidy-field-placeholder');
			$field_title.text(field_count);

			$('.tidy-fields').append($field);
			$field.slideUp(0).slideDown('fast');
		},

		editField = function(e) {
			e.preventDefault();
		},

		deleteField = function(e) {
			e.preventDefault();

			var $this = $(this),
				$field = $this.parents('.tidy-field');

			$field.slideUp('fast', function () {
				$field.remove();
			});
		};

	return {
		go : init
	};

})(jQuery);

TIDY_FORMS.go();

