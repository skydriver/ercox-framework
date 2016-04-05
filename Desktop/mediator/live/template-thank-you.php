<?php

/**
 *	Template Name: Thank You
 **/

// require 'lib/mediator-member-loader.php';

add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

add_filter('body_class', 'codeart_thankyou_body_classes');
function codeart_thankyou_body_classes( $classes )
{
	$classes[] = 'codeart-thankyou ca-thank-you-custom';
	return $classes;
}


remove_action('genesis_loop', 'genesis_do_loop');

add_action('genesis_loop', 'codeart_thankyou_loop1', 5);
function codeart_thankyou_loop1()
{
    ?>
    <div class="entry">
        <div class="entry-content">
            <h2>Get up to 12 months FREE Premium Membership by inviting your friends to Mediator Academy</h2>
            <h3>For every friend you invite that becomes a Premium Member we’ll give you 1 month of free Premium Membership (up to 12 months).</h3>
            <div class="custom-image">
                <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/ppl1.png" alt="" class="left">
                <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/ppl2.png" alt="" class="center">
                <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/ppl3.png" alt="" class="right">
            </div>
            <div class="ca-send-invitation">
                <div class="left-holder">
                    <div id="send-invitation-magic"></div>
                    <div class="ca-invitation-selected"></div>
                </div>
                <div class="ca-invite-send-trigger">Send</div>
            </div>
            <div class="ca-send-invitation-errors">
                <p class="error-msg">An error occured. You can add emails only and you need to add at least one email. Please correct the error and try again.</p>
            </div>
        </div>
    </div>  
    
    <script>
        jQuery(document).ready(function($){
            
            var data_to_sent;
            var validation_check = false;
            
            var ca_invitation_container_box = $('div.ca-send-invitation div#send-invitation-magic').magicSuggest({
                allowFreeEntries: true,
                expandOnFocus: false,
                useTabKey: true,
                vtype: 'email',
                toggleOnClick: false,
                placeholder: 'Add emails (max 10)'
            });

            

            function validateEmail(email) {
                var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                return re.test(email);
            }

            var ca_ref_btn_send = $('div.ca-send-invitation div.ca-invite-send-trigger');
            var ca_ref_err_msg  = $('div.ca-send-invitation-errors p.error-msg');
            
            ca_ref_btn_send.on('click', function(e){
                e.preventDefault();

                validation_check = true;

                $('body').trigger('click');

                data_to_sent = ca_invitation_container_box.getValue();

                if (data_to_sent.length < 1) {
                    ca_ref_err_msg.slideDown();
                    return;
                }

                $.each(data_to_sent, function(index, value) {
                    if (validateEmail(value) == false) {
                        ca_ref_err_msg.slideDown();
                        validation_check = false;
                        return false;
                    }
                });

                if( validation_check === false ){
                    return;
                }
                
                var data = {
                    'action': 'ajax_ref_invite',
                    'emails': data_to_sent
                };

                $('body').addClass('sending');

                jQuery.post(ajaxurl, data, function(response) {
                    if( response )
                    {
                        console.log( response );
                        var obj = $.parseJSON(response);
                        if (obj.status == true)
                        {
                            ca_ref_err_msg.text('Your invitations has been sent.').addClass('green-color').slideDown();
                        } else {
                            ca_ref_err_msg.text('Your invitations has not been sent. ' + obj.message).addClass('error-msg').slideDown();
                        }
                    }
                    $('body').removeClass('sending');
                });
            });
            
        });
        
    </script>
    <?php
}

genesis();

?>