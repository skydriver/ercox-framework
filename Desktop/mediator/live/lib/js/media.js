jQuery(document).ready(function($){
	/*
	// Media Uplad
	var profile_avatar 			= $('body').find('input[name=avatar]');
	var profile_avatar_img 		= $('body').find('img.profile-avatar');
	var profile_avatar_img_new 	= $('body').find('div.avatar').find('img').first();

	var image = wp.media({
		title  : 'Add image',
		multiple : false
	});

	$('body').find('a.mediator-member-avatar').on('click', function(e) {
		e.preventDefault();
		
		image.open();
	});

	image.on( 'select', function(){
		var images = image.state().get( 'selection' ).toJSON();
		profile_avatar.val(images[0].id);
		return;
	});

	$('body').find('a.mediator-member-avatar-remove').on('click', function(e) {
		e.preventDefault();
		
		profile_avatar.val(0);

		if( default_image )
		{
			profile_avatar_img_new.attr('src', default_image.thumbnail);
		}
		// profile_avatar_img_new.attr('src', 'https://scontent-vie1-1.xx.fbcdn.net/hphotos-xtp1/t31.0-8/12418855_966149306804634_5922422014117009937_o.jpg');
		// profile_avatar_img.remove();
	});
	// Media Uplad End
	*/

});





jQuery(document).ready(function($) {

	var profile_avatar 			= $('body').find('input[name=avatar]');
	var profile_avatar_img 		= $('body').find('img.profile-avatar');
	var profile_avatar_img_new 	= $('body').find('div.avatar').find('img').first();

	$('body').find('a.mediator-member-avatar').on('click', function(e) {
		e.preventDefault();

		if ( $('body').hasClass('wp-admin') ) {
			return false;
		}

		var options = {
			beforeSubmit: 	showRequest,
			success: 		showResponse,
			url: 			ajaxurl
		};

		$('#thumbnail_upload').find('input[type="file"]').click();
		$('#thumbnail_upload').find('input[type="file"]').on('change', function(e) {
			$(this).closest('form').submit();
		});

		$('#thumbnail_upload').ajaxForm(options);
	});


	$('body').find('a.mediator-member-avatar-remove').on('click', function(e) {
		e.preventDefault();
		
		profile_avatar.val(0);

		if( default_image )
		{
			profile_avatar_img_new.removeAttr('srcset');
			profile_avatar_img_new.attr('src', default_image.thumbnail);
		}
	});



	$('body').on('click touchend', 'div.avatar-galleries', function(e) {
		e.preventDefault();
        var target = $(e.target);

        var attach_id = $(this).data('id');
        var parent_this = $(this);

        if( target.hasClass('remove-avatar-from-gallery' )) {

        	var data = {
				'action': 'remove_from_gallery',
				'attach_id': attach_id
			};

			jQuery.post(ajaxurl, data, function(response) {
				if (!response) {
					console.log('no response');
					return false;
				}

				var obj = jQuery.parseJSON(response);
				if (obj.status == true) {
					parent_this.addClass('delete');

					$('body').find('.limit-over').removeClass('limit-over');

					if ( $('body').find('.avatar-galleries:not(.delete)').length < 1 ) {
						$('body').find('.add_button.add-avatar-button').addClass('have-not-images');
						$('body').find('.avatar-popup .ca_custom_popup_close').trigger('click');

						$('body').find('#profile-edit .avatar img').removeAttr('srcset');
						$('body').find('#profile-edit .avatar img').attr('src', default_image.medium);

						console.log($('body').find('#profile-edit .avatar img'));
					}
				} else {
					console.log('no response');
					return false;
				}
			});

            return false;
        }

		if ($(this).hasClass('selected')) {
			return false;
		}

		$('body').find('div.avatar-galleries').removeClass('selected');
		$(this).addClass('selected');
	});


	$('body').on('click', 'a.set-avatar-grom-gallery-btn', function(e) {
		e.preventDefault();
		var selected = $('body').find('div.avatar-galleries.selected');
		if (selected.length != 1 ) {
			alert('Please select an avatar!');
			return false;
		}

		var attach_id = selected.data('id');

		var avatar_input = $('body').find('input[type="hidden"].avatar');
		avatar_input.val(attach_id);

		profile_avatar.val(attach_id);
		profile_avatar_img_new.removeAttr('srcset');
		var bg_url = selected.css('background-image');
		bg_url = /^url\((['"]?)(.*)\1\)$/.exec(bg_url);
		bg_url = bg_url ? bg_url[2] : "";
		profile_avatar_img_new.attr('src', bg_url);



		console.log( $(this).closest('.avatar-popup').find('.close-popup') );

		$(this).closest('.avatar-popup').find('.close-popup').trigger('click');

		$('body').find('div.bottom-buttons input[type=submit]').trigger('click');

		// body.codeart-profile-edit div.bottom-buttons input[type=submit]
	});

});



function showRequest(formData, jqForm, options) {
	jQuery('body').find('div.avatar').addClass('uploading');
	jQuery('#submit-avatar-ajax').attr("disabled", "disabled");
}

function showResponse(responseText, statusText, xhr, $form)  {
	var profile_avatar 			= jQuery('body').find('input[name=avatar]');
	var profile_avatar_img 		= jQuery('body').find('img.profile-avatar');
	var profile_avatar_img_new 	= jQuery('body').find('div.avatar').find('img').first();

	var obj = jQuery.parseJSON(responseText);

	var profile_form = jQuery('body').find('form#profile-edit');

	if( obj.status == true )
	{
		profile_form.find('input[name="avatar"]').val(obj.attach_id);
		profile_avatar_img_new.removeAttr('srcset');
		profile_avatar_img_new.attr('src', obj.attach_url);

		jQuery('body').find('.avatar a.have-not-images').removeClass('have-not-images');
		var new_avatar = '<div class="avatar-galleries" data-id="' + obj.attach_id + '" style="background-image: url(' + obj.attach_url + ');">';
		new_avatar += '<a href="#" class="remove-avatar-from-gallery"></a>';
		new_avatar += '</div>';
		jQuery('body').find('.avatar-popup .all-images-wrap').append(new_avatar);

		if( jQuery('body').find('.avatar-popup .avatar-galleries:not(.delete)').length == 9 ) {
			jQuery('body').find('a.mediator-member-avatar').addClass('limit-over');
		}

		profile_avatar_img_new.closest('form').trigger('submit');
		setTimeout(function() {
			location.reload();
		}, 280);
	} else {
		var alert_box_new = jQuery('#profile-edit-content div.alert_changes');
		var update_profile_form_new = jQuery('body').find('form#profile-edit');
    	alert_box_new.find('h3').text(obj.message); // .fadeIn();
    	alert_box_new.fadeIn();

    	jQuery('html, body').animate({
    		scrollTop: jQuery(update_profile_form_new).offset().top - (jQuery('body').find('#header').height() + 15)
    	}, 800);
	}

	jQuery('body').find('div.avatar').removeClass('uploading');
	jQuery('#submit-avatar-ajax').attr("disabled", false);
}