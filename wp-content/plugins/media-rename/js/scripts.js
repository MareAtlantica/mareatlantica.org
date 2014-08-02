(function($) {


	var $form, $fields, type, is_media_single, fields_count, current_field = 0;

	$.fn.do_rename = function() {
		var $field = this;

		$.post(
			ajaxurl, {
				action: 'media_rename',
				type: type,
				_wpnonce: $('input[name=_mr_wp_nonce]', $form).val(),
				new_filename: $('input', $field).val(),
				post_id: $('input', $field).data('post-id')
			}, function (response) {
				$('.loader', $field).hide();

				if (response != '1') {
					$('.error', $field).text(response).css('display', 'inline-block');
				} else {
					$('input[type=text]', $field).attr('title', $('input[type=text]', $field).val());
					$('.success', $field).css('display', 'inline-block');
				}

				if (++current_field == fields_count) {
					current_field = 0;

					if (!$form.find('.error:visible').length) {
						$form.submit();
					}
				} else {
					$fields.eq(current_field).do_rename();
				}
			}
		);
	}


	$(document).ready(function() {

		$form = $('.wrap form');
		is_media_single = !$('.wp-list-table').length;

		if (!is_media_single) {
			$('.tablenav select[name^=action]').each(function() {
				for (label in MRSettings.labels) {
					$('option:last', this).before( $('<option>').attr('value', label).text( decodeURIComponent(MRSettings.labels[label].replace(/\+/g, '%20')) ) );
				}
			});
		}

		$('#post').submit(process_form_submit);
		$('.tablenav .button.action').click(process_form_submit);

	});


	var process_form_submit = function() {
		type = $(this).siblings('select').length ? $(this).siblings('select').val() : 'rename';

		if ( !is_media_single && (type != 'rename' && type != 'rename_retitle') ) return;

		$fields = (is_media_single) ? $('.media-rename', $form) : $('#the-list input:checked', $form).closest('tr').find('.media-rename');

		$fields = $fields.filter(function() {
			return $('input[type=text]', this).val() != $('input[type=text]', this).attr('title');
		});

		if (fields_count = $fields.length) {
			$fields.find('.loader, .error, .success').hide();
			$fields.find('.loader').css('display', 'inline-block');

			$fields.eq(current_field).do_rename();

			return false;
		}
	};


})(jQuery);