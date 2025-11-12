/**
 * FatLab Schema Wizard - Admin JavaScript
 *
 * @package FatLab_Schema_Wizard
 */

(function($) {
	'use strict';

	/**
	 * Initialize on document ready.
	 */
	$(document).ready(function() {
		FLS_Admin.init();
	});

	/**
	 * Main admin object.
	 */
	var FLS_Admin = {
		/**
		 * Current post ID.
		 */
		postId: null,

		/**
		 * Initialize.
		 */
		init: function() {
			this.postId = $('#post_ID').val();
			this.mediaUpload();
			this.wizardSteps();
			this.dismissNotices();
			this.schemaActions();
			this.schemaPreview();
		},

		/**
		 * Media upload functionality.
		 */
		mediaUpload: function() {
			var mediaUploader;

			$(document).on('click', '.fls-media-upload-button', function(e) {
				e.preventDefault();

				var button = $(this);
				var urlField = button.siblings('.fls-media-url');
				var preview = button.siblings('.fls-media-preview');

				// If the uploader object has already been created, reopen the dialog
				if (mediaUploader) {
					mediaUploader.open();
					return;
				}

				// Extend the wp.media object
				mediaUploader = wp.media({
					title: fatlabschemaAdmin.strings.upload_logo || 'Choose Image',
					button: {
						text: fatlabschemaAdmin.strings.use_image || 'Use this image'
					},
					multiple: false
				});

				// When a file is selected, grab the URL and set it as the text field's value
				mediaUploader.on('select', function() {
					var attachment = mediaUploader.state().get('selection').first().toJSON();
					urlField.val(attachment.url);

					// Update preview
					preview.html('<img src="' + attachment.url + '" style="max-width: 200px; margin-top: 10px;" />');
				});

				// Open the uploader dialog
				mediaUploader.open();
			});
		},

		/**
		 * Wizard step navigation.
		 */
		wizardSteps: function() {
			var self = this;

			// Continue from step 1
			$(document).on('click', '.fls-wizard-continue', function() {
				var selectedType = $('input[name="fatlabschema_type_selection"]:checked').val();

				if (!selectedType) {
					alert(fatlabschemaAdmin.strings.select_type || 'Please select a content type.');
					return;
				}

				self.showRecommendation(selectedType);
			});

			// Content type selection change
			$(document).on('change', '.fls-schema-type-option input[type="radio"]', function() {
				$('.fls-schema-type-option').removeClass('selected');
				$(this).closest('.fls-schema-type-option').addClass('selected');
			});

			// Back to step 1
			$(document).on('click', '.fls-wizard-back', function() {
				$('.fls-step-2').fadeOut(300, function() {
					$('.fls-step-1').fadeIn(300);
				});
			});

			// Skip schema
			$(document).on('click', '.fls-skip-schema', function() {
				// Save "none" as schema type
				$('input[name="fatlabschema_type"]').val('none');
				$('input[name="fatlabschema_enabled"]').val('0');
				$('input[name="fatlabschema_wizard_completed"]').val('1');

				// Show success message
				self.showNotice('success', fatlabschemaAdmin.strings.no_schema_saved || 'No schema will be added to this page.');

				// Close wizard
				$('.fls-wizard-container').html('<p>' + (fatlabschemaAdmin.strings.no_schema_message || 'No schema configured for this page.') + '</p>');
			});

			// Add schema button
			$(document).on('click', '.fls-add-schema', function() {
				var schemaType = $(this).data('schema-type');
				self.loadSchemaForm(schemaType);
			});
		},

		/**
		 * Show recommendation for selected type.
		 */
		showRecommendation: function(type) {
			var self = this;
			var step1 = $('.fls-step-1');
			var step2 = $('.fls-step-2');

			// Show loading
			step2.html('<p><span class="fls-loading"></span> ' + (fatlabschemaAdmin.strings.loading || 'Loading...') + '</p>');
			step1.fadeOut(300, function() {
				step2.fadeIn(300);
			});

			// Load recommendation via AJAX
			$.ajax({
				url: fatlabschemaAdmin.ajax_url,
				type: 'POST',
				data: {
					action: 'fls_get_recommendation',
					nonce: fatlabschemaAdmin.nonce,
					schema_type: type,
					post_id: self.postId
				},
				success: function(response) {
					if (response.success) {
						step2.html(response.data.html);
					} else {
						step2.html('<div class="notice notice-error"><p>' + (response.data.message || 'Error loading recommendation.') + '</p></div>');
					}
				},
				error: function() {
					step2.html('<div class="notice notice-error"><p>' + (fatlabschemaAdmin.strings.error || 'An error occurred.') + '</p></div>');
				}
			});
		},

		/**
		 * Load schema form for a specific type.
		 */
		loadSchemaForm: function(schemaType) {
			var self = this;

			// Show loading in step 2
			$('.fls-step-2').html('<p><span class="fls-loading"></span> ' + (fatlabschemaAdmin.strings.loading || 'Loading form...') + '</p>');

			// Load form via AJAX
			$.ajax({
				url: fatlabschemaAdmin.ajax_url,
				type: 'POST',
				data: {
					action: 'fls_get_schema_form',
					nonce: fatlabschemaAdmin.nonce,
					schema_type: schemaType,
					post_id: self.postId
				},
				success: function(response) {
					if (response.success) {
						$('.fls-step-2').html(response.data.html);
						self.initializeFormFields(schemaType);
					} else {
						self.showNotice('error', response.data.message || 'Error loading form.');
					}
				},
				error: function() {
					self.showNotice('error', fatlabschemaAdmin.strings.error || 'An error occurred.');
				}
			});
		},

		/**
		 * Initialize form-specific fields and interactions.
		 */
		initializeFormFields: function(schemaType) {
			var self = this;

			// Date/time pickers
			if ($('.fls-datepicker').length) {
				$('.fls-datepicker').each(function() {
					$(this).attr('type', 'date');
				});
			}

			if ($('.fls-timepicker').length) {
				$('.fls-timepicker').each(function() {
					$(this).attr('type', 'time');
				});
			}

			// Dynamic repeater fields (for FAQ, HowTo)
			self.initializeRepeaterFields();

			// Location type toggle (for Events)
			$(document).on('change', 'input[name="fatlabschema_data[location_type]"]', function() {
				var locationType = $(this).val();
				if (locationType === 'virtual') {
					$('.fls-physical-location').hide();
					$('.fls-virtual-location').show();
				} else {
					$('.fls-physical-location').show();
					$('.fls-virtual-location').hide();
				}
			});

			// Save schema button
			$(document).on('click', '.fls-save-schema-button', function(e) {
				e.preventDefault();
				self.saveSchema(schemaType);
			});

			// Cancel button
			$(document).on('click', '.fls-cancel-schema-button', function(e) {
				e.preventDefault();
				$('.fls-step-2').fadeOut(300, function() {
					$('.fls-step-1').fadeIn(300);
				});
			});
		},

		/**
		 * Initialize repeater fields (for FAQ, HowTo).
		 */
		initializeRepeaterFields: function() {
			// Add new repeater item
			$(document).on('click', '.fls-add-repeater-item', function() {
				var container = $(this).closest('.fls-repeater-container');
				var template = container.find('.fls-repeater-template').html();
				var items = container.find('.fls-repeater-items');
				var index = items.find('.fls-repeater-item').length;

				// Replace placeholder index
				template = template.replace(/\[INDEX\]/g, index);

				items.append(template);
			});

			// Remove repeater item
			$(document).on('click', '.fls-remove-repeater-item', function() {
				$(this).closest('.fls-repeater-item').remove();
			});
		},

		/**
		 * Save schema data.
		 */
		saveSchema: function(schemaType) {
			var self = this;
			var formData = $('.fls-schema-form-wrapper :input').serialize();

			// Show saving indicator
			$('.fls-save-schema-button').prop('disabled', true).text(fatlabschemaAdmin.strings.saving || 'Saving...');

			$.ajax({
				url: fatlabschemaAdmin.ajax_url,
				type: 'POST',
				data: formData + '&action=fls_save_schema&nonce=' + fatlabschemaAdmin.nonce + '&post_id=' + self.postId + '&schema_type=' + schemaType,
				success: function(response) {
					if (response.success) {
						// Update hidden fields
						$('input[name="fatlabschema_type"]').val(schemaType);
						$('input[name="fatlabschema_enabled"]').val('1');
						$('input[name="fatlabschema_wizard_completed"]').val('1');

						// Show success message
						self.showNotice('success', response.data.message || fatlabschemaAdmin.strings.saved || 'Schema saved successfully!');

						// Show warnings if any
						if (response.data.warnings && response.data.warnings.length > 0) {
							var warningHtml = '<ul>';
							response.data.warnings.forEach(function(warning) {
								warningHtml += '<li>' + warning + '</li>';
							});
							warningHtml += '</ul>';
							self.showNotice('warning', warningHtml);
						}

						// Reload to show summary view
						setTimeout(function() {
							window.location.reload();
						}, 1500);
					} else {
						var errorMessage = response.data.message || fatlabschemaAdmin.strings.error || 'Error saving schema.';

						if (response.data.errors && response.data.errors.length > 0) {
							errorMessage += '<ul>';
							response.data.errors.forEach(function(error) {
								errorMessage += '<li>' + error + '</li>';
							});
							errorMessage += '</ul>';
						}

						self.showNotice('error', errorMessage);
						$('.fls-save-schema-button').prop('disabled', false).text(fatlabschemaAdmin.strings.save_schema || 'Save Schema');
					}
				},
				error: function() {
					self.showNotice('error', fatlabschemaAdmin.strings.error || 'An error occurred.');
					$('.fls-save-schema-button').prop('disabled', false).text(fatlabschemaAdmin.strings.save_schema || 'Save Schema');
				}
			});
		},

		/**
		 * Schema preview functionality.
		 */
		schemaPreview: function() {
			var self = this;

			$(document).on('click', '.fls-preview-schema-button', function(e) {
				e.preventDefault();

				var schemaType = $('input[name="fatlabschema_type"]').val();
				var formData = $('.fls-schema-form-wrapper :input').serialize();

				// Show loading
				var modalContent = '<div class="fls-preview-modal"><div class="fls-preview-content"><h3>' + (fatlabschemaAdmin.strings.preview || 'Schema Preview') + '</h3><p><span class="fls-loading"></span> ' + (fatlabschemaAdmin.strings.loading || 'Loading...') + '</p></div></div>';
				$('body').append(modalContent);

				$.ajax({
					url: fatlabschemaAdmin.ajax_url,
					type: 'POST',
					data: formData + '&action=fls_preview_schema&nonce=' + fatlabschemaAdmin.nonce + '&post_id=' + self.postId + '&schema_type=' + schemaType,
					success: function(response) {
						if (response.success) {
							var html = '<h3>' + (fatlabschemaAdmin.strings.preview || 'Schema Preview') + '</h3>';
							html += '<pre>' + response.data.json + '</pre>';

							if (response.data.test_url) {
								html += '<p><a href="' + response.data.test_url + '" target="_blank" class="button button-primary">' + (fatlabschemaAdmin.strings.test_google || 'Test with Google Rich Results') + '</a></p>';
							}

							html += '<p><button type="button" class="button fls-close-preview">' + (fatlabschemaAdmin.strings.close || 'Close') + '</button></p>';

							$('.fls-preview-content').html(html);
						} else {
							$('.fls-preview-content').html('<p>' + (response.data.message || 'Error generating preview.') + '</p><p><button type="button" class="button fls-close-preview">' + (fatlabschemaAdmin.strings.close || 'Close') + '</button></p>');
						}
					},
					error: function() {
						$('.fls-preview-content').html('<p>' + (fatlabschemaAdmin.strings.error || 'An error occurred.') + '</p><p><button type="button" class="button fls-close-preview">' + (fatlabschemaAdmin.strings.close || 'Close') + '</button></p>');
					}
				});
			});

			// Close preview modal
			$(document).on('click', '.fls-close-preview, .fls-preview-modal', function(e) {
				if (e.target === this) {
					$('.fls-preview-modal').remove();
				}
			});
		},

		/**
		 * Dismiss admin notices.
		 */
		dismissNotices: function() {
			$(document).on('click', '.notice.is-dismissible[data-notice-id]', function() {
				var noticeId = $(this).data('notice-id');

				if (noticeId) {
					$.post(fatlabschemaAdmin.ajax_url, {
						action: 'fatlabschema_dismiss_notice',
						nonce: fatlabschemaAdmin.nonce,
						notice_id: noticeId
					});
				}
			});
		},

		/**
		 * Schema actions (edit, remove, etc.).
		 */
		schemaActions: function() {
			// Edit schema
			$('.fls-edit-schema').on('click', function() {
				var schemaType = $('input[name="fatlabschema_type"]').val();
				FLS_Admin.loadSchemaForm(schemaType);
			});

			// Cancel edit
			$('.fls-cancel-edit').on('click', function() {
				$('.fls-schema-form').slideUp();
				$('.fls-wizard-summary').slideDown();
			});

			// Remove schema
			$('.fls-remove-schema').on('click', function() {
				if (confirm(fatlabschemaAdmin.strings.confirm_delete || 'Are you sure you want to remove this schema?')) {
					$('input[name="fatlabschema_type"]').val('none');
					$('input[name="fatlabschema_enabled"]').val('0');
					$('input[name="fatlabschema_wizard_completed"]').val('0');

					// Reload page to show wizard
					window.location.reload();
				}
			});

			// Run wizard again
			$('.fls-run-wizard-again').on('click', function() {
				$('input[name="fatlabschema_wizard_completed"]').val('0');
				window.location.reload();
			});
		},

		/**
		 * Show admin notice.
		 */
		showNotice: function(type, message) {
			var noticeClass = 'notice-' + type;
			var notice = $('<div class="notice ' + noticeClass + ' is-dismissible"><p>' + message + '</p></div>');

			// Insert after h1 or at top of content
			if ($('h1').length) {
				notice.insertAfter('h1').first();
			} else {
				$('.wrap').prepend(notice);
			}

			// Scroll to notice
			$('html, body').animate({
				scrollTop: notice.offset().top - 100
			}, 500);

			// Auto-dismiss success notices after 5 seconds
			if (type === 'success') {
				setTimeout(function() {
					notice.fadeOut(function() {
						$(this).remove();
					});
				}, 5000);
			}
		}
	};

})(jQuery);
