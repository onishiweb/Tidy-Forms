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

			if( $('#arc-form-settings').length ) {
				initSettings();
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

			if( $('body.post-type-arc_form_entry').length ) {
				revealFormsSubmenu();
			}

		},

		initField = function(field) {

			var field_type = $(field).find('.arc-field-type-select option:selected').val();

			if( field_type === 'text' || field_type === 'textarea' || field_type === 'title' ) {
				$(field).find('.arc-field-options').slideUp(0);
			} else {
				$(field).find('.arc-field-options').addClass('active');
			}

			if( field_type !== 'text' ) {
				$(field).find('.arc-field-text-validation').slideUp(0);
			} else {
				$(field).find('.arc-field-text-validation').addClass('active');
			}

			$(field).find('.arc-field-advanced-options').slideUp(0);
			$(field).find('.arc-field-settings').slideUp(0);

			$(field).on('change', '.arc-field-type-select', fieldOptions);

		},

		initSettings = function() {
			var $emailField = $('#arc-send-via-email'),
				$toEmail = $('#arc-form-settings').find('.arc-form-to-email');

			if( ! $emailField.is(':checked') ) {
				$toEmail.slideUp(0);
			}

			$('#arc-form-settings').on('change', '#arc-send-via-email', showHideToEmail);

		},

		showHideToEmail = function() {
			var $emailField = $('#arc-send-via-email'),
				$toEmail = $('#arc-form-settings').find('.arc-form-to-email');

			if( $emailField.is(':checked') ) {
				$toEmail.slideDown();
			} else {
				$toEmail.slideUp('fast');
			}
		},

		revealFormsSubmenu = function() {
			$('#menu-posts-arc_form').addClass('wp-has-current-submenu').removeClass('wp-not-current-submenu');

			// Find the entries sub-menu (no classes on sub-menu items)
			var $menu_item = $('#menu-posts-arc_form').find('a:contains(Entries)');

			$menu_item.parent().addClass('current');
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

		closeModal = function() {
			$('.arc-lightbox-modal').fadeOut(200, function() {
				$(this).remove();

				$('.arc-lightbox-mask').fadeOut(300, function() {
					$(this).remove();
				});
			});

			$('body').trigger('arcCloseModal');
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
				// register close handler
				$('body').on('arcCloseModal', function() {

					$('body').off('click', '.arc-lightbox-modal .arc-field-type-choice', insertField);
					$('body').off('arcCloseModal');

				});
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
			$field.find('.arc-field-type-select option').attr('selected', '');
			$field.find('.arc-field-type-select option[value=' + choice + ']').attr('selected', 'selected');

			$field.on('change', '.arc-field-type-select', fieldOptions);

			updateFieldInfo($field);

			// Hide extra options
			if( choice === 'text' || choice === 'textarea' || choice === 'title' ) {
				$field.find('.arc-field-options').slideUp(0);
			} else {
				$field.find('.arc-field-options').addClass('active');
			}

			if( choice !== 'text' ) {
				$field.find('.arc-field-text-validation').slideUp(0);
			} else {
				$field.find('.arc-field-text-validation').addClass('active');
			}

			$field.find('.arc-field-advanced-options').slideUp(0);

			// Remove modal
			closeModal();

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

		fieldOptions = function(e) {
			var $this = $(this),
				$field = $this.parents('.arc-field'),
				$options = $field.find('.arc-field-options'),
				$validation = $field.find('.arc-field-text-validation'),
				option = $this.find(':selected').val();

			switch(option) {
				case 'select':
				case 'radio':
				case 'checkbox':
					if( $validation.hasClass('active') ) {
						$validation.removeClass('active').slideUp(400, function() {
							$options.slideDown('fast').addClass('active');
						});
					} else if( ! $options.hasClass('active') ) {
						$options.slideDown('fast').addClass('active');
					}

					break;
				case 'text':
					if( $options.hasClass('active') ) {
						$options.removeClass('active').slideUp(400, function () {
							$validation.slideDown('fast').addClass('active');
							$options.find('textarea').val('');
						});
					} else if( ! $validation.hasClass('active') ) {
						$validation.slideDown('fast').addClass('active');
					}

					break;
				default:
					if( $options.hasClass('active') ) {
						$options.slideUp('fast').removeClass('active');
						$options.find('textarea').val('');
					}

					if( $validation.hasClass('active') ) {
						$validation.slideUp('fast').removeClass('active');
					}

					break;
			}
		},

		deleteField = function(e) {
			e.preventDefault();

			var $this = $(this),
				$field = $this.parents('.arc-field');

			$field.off('change', '.arc-field-type-select', advancedFields);

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
