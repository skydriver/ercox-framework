jQuery(document).ready(function($){

	// Repeater
	function clear_div_elements( el )
	{
		el.find(':input').each(function() {
			switch(this.type) {
				case 'password':
				case 'text':
				case 'textarea':
				case 'file':
				case 'select-one':
					jQuery(this).val('');
					break;
				case 'checkbox':
				case 'radio':
					this.checked = false;
			}

			this.name = this.name.replace(/\[(\d+)\]/, function($0, $1) {
				return '[' + (+$1 + 1) + ']';
			});
		});
	} // End of function clear_div_elements( el );

	$('body').on('click', 'a.remove-repeater-row', function(e) {
		e.preventDefault();

		var parent_div = $(this).closest('.box_can_edit');
		if(parent_div.hasClass('initial-repeater'))
		{
			parent_div.hide();
			return false;
		}

		$(this).parent('div').remove();
	});

	$('body').on('click', 'a.member-repeater-button', function(e) {
		e.preventDefault();

		/*
		var repeater_wrap = $(this).closest('div.box');

		console.log(repeater_wrap);

		var new_repeater = repeater_wrap.clone();
		clear_div_elements(new_repeater.find('.box-heading').remove());

		// box-heading
		*/

		var repeater_wrap = $(this).parent('div').find('.member-group-child');

		if(repeater_wrap.hasClass('initial-repeater'))
		{
			repeater_wrap.show();
			return false;
		}

		var new_repeater = repeater_wrap.last().clone();
		clear_div_elements(new_repeater);

		new_repeater.find('.text_item').text('');
		new_repeater.find('.text_item').css('display', 'none');
		new_repeater.find('.field-item').css('display', 'block');
        new_repeater.find('.field-item input').removeAttr('id').removeClass('hasDatepicker');
		new_repeater.addClass('editing error');

		$(this).before(new_repeater);
	});
	// Repeater

});