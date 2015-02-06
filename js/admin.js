// Modular JS file
ARCHITECT_FORMS = (function ($) {

	// Breakpoints
	var	init = function () {
			// runs like jQuery normally would when everything is ready
			// run all functions for the site inside the domReady function
			$(domReady); // same as $(document).ready (function () {...});
		},

		// this runs only when we know the whole DOM is ready
		domReady = function () {

			if( $('#arc-form-fields').length ) {
				$('#arc-form-fields').on('click', '[arc-action-add-field]', addField);
			}

			if( $('.arc-fields-sortable').length ) {
				sortableFields();
			}

			if( $('.arc-field').length ) {
				$('.arc-fields').on('click', '[arc-action-edit]', editField);
				$('.arc-fields').on('click', '[arc-action-delete]', deleteField);
			}

		},

		sortableFields = function() {
			$('.arc-fields-sortable').sortable({
				placeholder:'arc-sortable-placeholder',
				forcePlaceholderSize:true,
				update:reorderFieldNumbers
			});

			$('.arc-fields-sortable').disableSelection();
		},

		addField = function(e) {
			e.preventDefault();

			var $field = $('.arc-field-placeholder').clone(true),
				$field_title = $field.find('th.row-title'),
				field_count = $('.arc-fields .arc-field').length;


			$field.removeClass('arc-field-placeholder');
			$field_title.text(field_count);

			$('.arc-fields').append($field);
			$field.slideUp(0).slideDown('fast');
		},

		editField = function(e) {
			e.preventDefault();
		},

		deleteField = function(e) {
			e.preventDefault();

			var $this = $(this),
				$field = $this.parents('.arc-field');

			$field.slideUp('fast', function () {
				$field.remove();
				reorderFieldNumbers();
			});
		},

		reorderFieldNumbers = function () {
			var fields = $('.arc-fields .arc-field');

			for( var i=1; i<fields.length; i++ ) {
				$(fields[i]).find('th.row-title').text(i);
			}
		};

	return {
		go : init
	};

})(jQuery);

ARCHITECT_FORMS.go();

