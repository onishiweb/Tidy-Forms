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
				var fields = $('.arc-fields .arc-field');

				for(var i=0; i<fields.length; i++) {
					initField(fields[i]);
				}

				$('.arc-fields').on('click', '[arc-action-edit]', editField);
				$('.arc-fields').on('click', '[arc-action-delete]', deleteField);
				$('.arc-fields').on('click', '[arc-action-finished-editing]', editField);
				$('.arc-fields').on('click', '[arc-action-advanced-fields]', advancedFields);
			}

		},

		initField = function(field) {

			$(field).find('.arc-field-type-options').slideUp(0);
			$(field).find('.arc-field-advanced-options').slideUp(0);
			$(field).find('.arc-field-settings').slideUp(0);

		},

		openModal = function( content, callback ) {
			var mask = $('<div />', { 'class': 'arc-lightbox-mask' });
			var modal = $('<div />', { 'class': 'arc-lightbox-modal' });
			var $content = $( content ).clone(true);

			$('body').append(mask);
			$(mask).fadeOut(0).fadeIn(200);

			$('body').append(modal);
			$(modal).append( $content );

			var contentX = ($content.width() / 2) * -1,
				contentY = ($content.height() / 2) * -1;

			$(modal).fadeOut(0)
					.css({
						'margin-top':contentY,
						'margin-left':contentX
					});

			// Register click handler to close modal window
			$('body').on('click', '.arc-lightbox-close, .arc-lightbox-mask', closeModal);

			$(modal).fadeIn(400, callback);
		},

		closeModal = function(callback) {
			$('body').off('click', '.arc-lightbox-close, .arc-lightbox-mask', closeModal);

			$('.arc-lightbox-modal').fadeOut(200, function() {
				$(this).remove();

				$('.arc-lightbox-mask').fadeOut(300, function() {
					$(this).remove();
					callback();
				});
			});
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

			// Open field type selector
			openModal( '#arc-field-type-selector', function() {
				// register click handler
				$('body').on('click', '.arc-lightbox-modal .arc-field-type-choice', insertField);
			});

		},

		insertField = function( e ) {
			var $this = $(this),
				choice = $this.val();

			// Insert field
			var $field = $('.arc-field-placeholder').clone(true),
				field_count = $('.arc-fields .arc-field').length + 1;

			$field.removeClass('arc-field-placeholder');
			// Replace number placeholder with actual number (slightly hacky)
			$field.html( $field.html().replace(/{#}/g, field_count) );
			$('#arc-fields-count').val( field_count );

			// Append field
			$('.arc-fields').append($field);

			// Set field type
			$field.find('.arc-field-select option').attr('selected', '');
			$field.find('.arc-field-select option[value=' + choice + ']').attr('selected', 'selected');

			updateFieldInfo($field);

			// Hide extra options
			$field.find('.arc-field-type-options').slideUp(0);
			$field.find('.arc-field-advanced-options').slideUp(0);

			// Remove modal
			closeModal( function() {
				// register click handler
				$('body').off('click', '.arc-lightbox-modal .arc-field-type-choice', insertField);
			});

			// Reveal options
			$field.slideUp(0).slideDown('fast');
		},

		editField = function(e) {
			e.preventDefault();

			var $field = $(this).parents('.arc-field'),
				$settings = $field.find('.arc-field-settings');

			if( $field.hasClass('editing') ) {
				$settings.slideUp('fast');
				updateFieldInfo( $field );
			} else {
				$settings.slideDown('fast');
			}

			$field.toggleClass('editing');
		},

		advancedFields = function(e) {
			e.preventDefault();

			var $field = $(this).parents('.arc-field'),
				$settings = $field.find('.arc-field-advanced-options');

			if( $settings.hasClass('active') ) {
				$settings.slideUp('fast');
				// @todo: translate string
				$(this).text('Advanced field options');
			} else {
				$settings.slideDown('fast');
				// @todo: translate string
				$(this).text('Close advanced options');
			}

			$settings.toggleClass('active');

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

		updateFieldInfo = function($field) {
			var label = $field.find('[id^=arc-field-label]').val(),
				$type_select = $field.find('[id^=arc-field-type]'),
				type = $type_select.find(':selected').val(),
				type_text = $type_select.find(':selected').text();

			if( label !== '' ) {
				// Update info
				$field.find('.arc-field-info-label').text(label);
				// Update name field if not already set
				var $name = $field.find('[id^=arc-field-name]'),
					name_value = label.replace(/[ -]/g,'_').toLowerCase();

				if( $name.val() === '' ) {
					$name.val(name_value);
				}
			}

			if( type !== '' ) {
				$field.find('.arc-field-info-type').text(type_text);
			}
		},

		reorderFieldNumbers = function () {
			var fields = $('.arc-fields .arc-field');

			for( var i=0; i<fields.length; i++ ) {
				$(fields[i]).find('th.row-title').text(i+1);
				$(fields[i]).find('input.arc-field-order').val(i+1);
			}
		};

	return {
		go : init
	};

})(jQuery);

ARCHITECT_FORMS.go();
