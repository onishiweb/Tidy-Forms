// Modular JS file
Tidy_Forms = (function ($) {

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

				showTableHeader();
			}

			if( $('.tidy-fields-sortable').length ) {
				sortableFields();
			}

			if( $('#tidy-form-settings').length ) {
				initSettings();
			}

			if( $('.tidy-field').length ) {
				var fields = $('.tidy-fields .tidy-field');

				for(var i=0; i<fields.length; i++) {
					initField(fields[i]);
				}

				$('.tidy-fields').on('click', '[tidy-action-edit]', editField);
				$('.tidy-fields').on('click', '[tidy-action-delete]', deleteField);
				$('.tidy-fields').on('click', '[tidy-action-finished-editing]', editField);
				$('.tidy-fields').on('click', '[tidy-action-advanced-fields]', advancedFields);
			}

			if( $('body.post-type-tidy_form_entry').length ) {
				revealFormsSubmenu();
			}

		},

		initField = function(field) {

			var field_type = $(field).find('.tidy-field-type-select option:selected').val();

			if( field_type === 'text' || field_type === 'textarea' || field_type === 'title' ) {
				$(field).find('.tidy-field-options').slideUp(0);
			} else {
				$(field).find('.tidy-field-options').addClass('active');
			}

			if( field_type !== 'text' ) {
				$(field).find('.tidy-field-text-validation').slideUp(0);
			} else {
				$(field).find('.tidy-field-text-validation').addClass('active');
			}

			$(field).find('.tidy-field-advanced-options').slideUp(0);
			$(field).find('.tidy-field-settings').slideUp(0);

			$(field).on('change', '.tidy-field-type-select', fieldOptions);

		},

		initSettings = function() {
			var $emailField = $('#tidy-send-via-email'),
				$toEmail = $('#tidy-form-settings').find('.tidy-form-to-email');

			if( ! $emailField.is(':checked') ) {
				$toEmail.slideUp(0);
			}

			$('#tidy-form-settings').on('change', '#tidy-send-via-email', showHideToEmail);

		},

		showTableHeader = function() {
			if( $('#tidy-fields-count').val() > 0 ) {
				$('#tidy-form-fields .tidy-fields-header').addClass('has-fields');
			} else {
				$('#tidy-form-fields .tidy-fields-header').removeClass('has-fields');
			}
		},

		showHideToEmail = function() {
			var $emailField = $('#tidy-send-via-email'),
				$toEmail = $('#tidy-form-settings').find('.tidy-form-to-email');

			if( $emailField.is(':checked') ) {
				$toEmail.slideDown();
			} else {
				$toEmail.slideUp('fast');
			}
		},

		revealFormsSubmenu = function() {
			$('#menu-posts-tidy_form').addClass('wp-has-current-submenu').removeClass('wp-not-current-submenu');

			// Find the entries sub-menu (no classes on sub-menu items)
			var $menu_item = $('#menu-posts-tidy_form').find('a:contains(Entries)');

			$menu_item.parent().addClass('current');
		},

		openModal = function( content, callback ) {
			var mask = $('<div />', { 'class': 'tidy-lightbox-mask' });
			var modal = $('<div />', { 'class': 'tidy-lightbox-modal' });
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
			$('body').on('click', '.tidy-lightbox-close, .tidy-lightbox-mask', closeModal);

			$(modal).fadeIn(400, callback);
		},

		closeModal = function() {
			$('.tidy-lightbox-modal').fadeOut(200, function() {
				$(this).remove();

				$('.tidy-lightbox-mask').fadeOut(300, function() {
					$(this).remove();
				});
			});

			$('body').trigger('tidyCloseModal');
		},

		sortableFields = function() {
			$('.tidy-fields-sortable').sortable({
				placeholder:'tidy-sortable-placeholder',
				forcePlaceholderSize:true,
				update:reorderFieldNumbers,
				cancel:'.editing,input,textarea,button,select,option'
			});

			$('.tidy-fields-sortable').disableSelection();
		},

		addField = function(e) {
			e.preventDefault();

			// Open field type selector
			openModal( '#tidy-field-type-selector', function() {
				// register click handler
				$('body').on('click', '.tidy-lightbox-modal .tidy-field-type-choice', insertField);
				// register close handler
				$('body').on('tidyCloseModal', function() {

					$('body').off('click', '.tidy-lightbox-modal .tidy-field-type-choice', insertField);
					$('body').off('tidyCloseModal');

				});
			});

		},

		insertField = function( e ) {
			var $this = $(this),
				choice = $this.val();

			// Insert field
			var $field = $('.tidy-field-placeholder').clone(true),
				field_count = $('.tidy-fields .tidy-field').length + 1;

			$field.removeClass('tidy-field-placeholder');
			// Replace number placeholder with actual number (slightly hacky)
			$field.html( $field.html().replace(/{#}/g, field_count) );
			$('#tidy-fields-count').val( field_count );

			// Append field
			$('.tidy-fields').append($field);

			// Set field type
			$field.find('.tidy-field-type-select option').attr('selected', '');
			$field.find('.tidy-field-type-select option[value=' + choice + ']').attr('selected', 'selected');

			$field.on('change', '.tidy-field-type-select', fieldOptions);

			updateFieldInfo($field);

			// Hide extra options
			if( choice === 'text' || choice === 'textarea' || choice === 'title' ) {
				$field.find('.tidy-field-options').slideUp(0);
			} else {
				$field.find('.tidy-field-options').addClass('active');
			}

			if( choice !== 'text' ) {
				$field.find('.tidy-field-text-validation').slideUp(0);
			} else {
				$field.find('.tidy-field-text-validation').addClass('active');
			}

			$field.find('.tidy-field-advanced-options').slideUp(0);

			// Remove modal
			closeModal();

			// Reveal options & show table header if first field
			$field.slideUp(0).slideDown('fast');
			showTableHeader();
		},

		editField = function(e) {
			e.preventDefault();

			var $field = $(this).parents('.tidy-field'),
				$settings = $field.find('.tidy-field-settings');

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

			var $field = $(this).parents('.tidy-field'),
				$settings = $field.find('.tidy-field-advanced-options');

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
				$field = $this.parents('.tidy-field'),
				$options = $field.find('.tidy-field-options'),
				$validation = $field.find('.tidy-field-text-validation'),
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
				$field = $this.parents('.tidy-field'),
				count = $('#tidy-fields-count').val() - 1;

			$field.off('change', '.tidy-field-type-select', advancedFields);

			$field.slideUp('fast', function () {
				$field.remove();
				$('#tidy-fields-count').val( count );
				showTableHeader();
				reorderFieldNumbers();
			});
		},

		updateFieldInfo = function($field) {
			var label = $field.find('[id^=tidy-field-label]').val(),
				$type_select = $field.find('[id^=tidy-field-type]'),
				type = $type_select.find(':selected').val(),
				type_text = $type_select.find(':selected').text();

			if( label !== '' ) {
				// Update info
				$field.find('.tidy-field-info-label').text(label);
				// Update name field if not already set
				var $name = $field.find('[id^=tidy-field-name]'),
					name_value = label.replace(/[ -]/g,'_').toLowerCase();

				if( $name.val() === '' ) {
					$name.val(name_value);
				}
			}

			if( type !== '' ) {
				$field.find('.tidy-field-info-type').text(type_text);
			}
		},

		reorderFieldNumbers = function () {
			var fields = $('.tidy-fields .tidy-field');

			for( var i=0; i<fields.length; i++ ) {
				$(fields[i]).find('th.row-title').text(i+1);
				$(fields[i]).find('input.tidy-field-order').val(i+1);
			}
		};

	return {
		go : init
	};

})(jQuery);

Tidy_Forms.go();
