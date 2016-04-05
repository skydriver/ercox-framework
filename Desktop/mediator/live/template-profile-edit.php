<?php

/**
 *	Template Name: Profile Edit 
 **/

require 'lib/mediator-member-loader.php';


add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );



add_filter('body_class', 'codeart_profile_edit_body_classes');
function codeart_profile_edit_body_classes( $classes )
{
	$classes[] = 'codeart-profile-edit';

	global $member;
	$classes[] = $member->is_premium_member() ? 'premium-member' : 'codeart-not-premium';
	
	return $classes;
}



remove_action('genesis_loop', 'genesis_do_loop');
add_action('genesis_loop', 'codeart_profile_edit_loop');
function codeart_profile_edit_loop()
{
	global $member;

	codeart_profile_edit_form(); ?>

	<div class="animation-holder done">
	    <div class="success-change"></div>
	    <div class="success-icon">
	        <p>Everything's updated!</p>
	    </div>
    </div>
    <div class="animation-holder error">
        <div class="success-change"></div>
        <div class="success-icon">
	        <p>Something's Wrong. Please reload.</p>
	    </div>
    </div>
	<?php
}







add_action( 'wp_head', 'ca_select_script_init' );
function ca_select_script_init()
{
	$suggestions = [];
	$service_suggest_terms = get_terms( 'service', ['hide_empty' => false] );
	if($service_suggest_terms):
		foreach($service_suggest_terms as $service_suggest_term):
			$suggestions[] = "'" . $service_suggest_term->name . "'";
		endforeach;
	endif;

	$suggestions_spec = [];
	$service_suggest_terms_spec = get_terms( 'specialism', ['hide_empty' => false] );
	if($service_suggest_terms):
		foreach($service_suggest_terms_spec as $service_suggest_term_spec):
			$suggestions_spec[] = "{id: " . $service_suggest_term_spec->term_id . ", name:'" . $service_suggest_term_spec->name . "'}";
		endforeach;
	endif; ?>
	<script type="text/javascript">
		jQuery(document).ready(function($){
            
            var ms_specialisms = $('#ca_magicsuggest_specialisms').magicSuggest({
                data: [<?php echo implode(', ', $suggestions_spec); ?>],
                typeDelay: 0,
                columns: 1,
                allowFreeEntries: false,
                placeholder: 'Add Specialisms...',
                maxSelection: null,
                maxDropHeight: 65,
                expandOnFocus: true,
                noSuggestionText:''
            });

            var ms = $('#ca_magicsuggest').magicSuggest({
                data: [<?php echo implode(', ', $suggestions); ?>],
                typeDelay: 0,
                columns: 1,
                allowFreeEntries: false,
                placeholder: 'Add Services...',
                maxSelection: null,
                maxDropHeight: 65,
                expandOnFocus: true,
                noSuggestionText:''
            });
            
        });
        
    </script>   
    <?php
}

add_action( 'wp_footer', 'ca_upgrade_user_popup' );
function ca_upgrade_user_popup(){
    ?>
        <div class="register-popup register ca-upgrade-profile-popup">
		    <div class="overlay"></div>
		    <div class="ca_custom_popup_overlay" data-active-popup="div.organization-popup" style="display: block;"></div>
		    <div class="ca_custom_popup_close close-popup"></div>
		    <div class="popup-wrap">
			    <div class="entry">
                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/upgrade-popup.png" alt="">
                    <h3>You need to <strong>upgrade to premium</strong> in order to access these features</h3>
                    <a href="<?php bloginfo('url');  ?>/plans/">Upgrade Now</a>
			    </div> <!-- .entry -->
		    </div> <!-- .popup-wrap -->
        </div>
    <?php
}






add_action('genesis_after_header', 'codeart_popup_user_uploded_images');
function codeart_popup_user_uploded_images() {
	if (!is_user_logged_in()) {
		return;
	}

	$uid = get_current_user_id();

	global $wpdb;
	$sql = "SELECT ID FROM $wpdb->posts WHERE post_type = 'attachment' AND post_author = $uid";
	$image_ids = $wpdb->get_results($sql); ?>

	<div class="register-popup register avatar-popup">
		<div class="overlay"></div>
		<div class="ca_custom_popup_overlay"></div>
		<div class="ca_custom_popup_close close-popup"></div>
		<div class="popup-wrap">
			<div class="entry">
				<div class="all-images-wrap">

					<?php
					$img_counter = 0;
					if ($image_ids):
						foreach ($image_ids as $id):
							if ($img_counter++ == 10) {
								break;
							}

							$id = $id->ID;
							if (!$id) { continue; }
							$image_url = wp_get_attachment_image_src( $id, 'popular-mediator-avatar' );
							if (is_array($image_url)):
								printf(
									'<div class="avatar-galleries" data-id="%d" style="background-image: url(%s);">
										<a href="#" class="remove-avatar-from-gallery"></a>
									</div>',
									$id,
									$image_url[0]
								);
							endif;
						endforeach;
					endif;

					printf('<a href="#" class="set-avatar-grom-gallery-btn">Set Selected Avatar</a>'); ?>

				</div> <!-- .all-images-wrap -->
			</div> <!-- .entry -->
		</div> <!-- .popup-wrap -->
	</div>

	<?php
}




genesis();

?>