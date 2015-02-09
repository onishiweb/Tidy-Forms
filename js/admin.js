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
				$('.arc-fields').on('click', '[arc-action-finished-editing]', closeField);
			}

		},

		sortableFields = function() {
			$('.arc-fields-sortable').sortable({
				placeholder:'arc-sortable-placeholder',
				forcePlaceholderSize:true,
				update:reorderFieldNumbers,
				cancel:'.editing,input,textarea,button,select,option'
			});

			$('.arc-fields-sortable').disableSelection();
		},

		addField = function(e) {
			e.preventDefault();

			var $field = $('.arc-field-placeholder').clone(true),
				field_count = $('.arc-fields .arc-field').length + 1;


			$field.removeClass('arc-field-placeholder');
			// Replace number placeholder with actual number (slightly hacky)
			$field.html( $field.html().replace(/{#}/g, field_count) );

			// Append field
			$('.arc-fields').append($field);
			// Hide extra options
			$field.find('.arc-field-type-options').slideUp(0);
			$field.find('.arc-field-advanced-options').slideUp(0);
			// Reveal options
			$field.slideUp(0).slideDown('fast');
		},

		closeField = function(e) {
			e.preventDefault();
			var $field = $(this).parents('.arc-field'),
				$settings = $field.find('.arc-field-settings');

			$field.removeClass('editing');
			$settings.slideUp('fast');

		},

		editField = function(e) {
			e.preventDefault();

			var $field = $(this).parents('.arc-field'),
				$settings = $field.find('.arc-field-settings');

			$field.addClass('editing');
			$settings.slideDown('fast');
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

			for( var i=0; i<fields.length; i++ ) {
				$(fields[i]).find('th.row-title').text(i+1);
			}
		};

	return {
		go : init
	};

})(jQuery);

ARCHITECT_FORMS.go();
