<?php

/**
 * Template Name: Custom Thank You Page
 */



// Full Width
add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );



// Body class
add_filter('body_class', 'codeart_filter_body_class_thank_you');
function codeart_filter_body_class_thank_you( $classes )
{
	$classes[] = 'codeart-thank-you ca-big-heading';
	return $classes;
}



// Main loop/content
remove_action('genesis_loop', 'genesis_do_loop');
add_action('genesis_loop', 'codeart_loop_thank_you');
function codeart_loop_thank_you()
{
	?>
	<div class="background">
	    <div class="wrap">
            <h1>Thank You</h1>
            <!--<h2>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco.</h2>-->

            <?php
            $go_back 		= isset($_COOKIE['ma_ref']) ? $_COOKIE['ma_ref'] : '';
            $my_profile 	= get_field('edit_profile_page', 'option');
            $browse_videos 	= get_bloginfo('url') . '/search/';
            $upgrade 		= get_field('upgrade_page', 'option');
            ?>

            <a href="<?php echo $go_back; ?>" class="go-back">Go Back</a>
            <a href="<?php echo $my_profile; ?>" class="my-profile">My Profile</a>
            <a href="<?php echo $browse_videos; ?>" class="browse-videos">Browse Videos</a>
            <a href="<?php bloginfo('url'); ?>/plans/" class="upgrade-premium">Upgrade to Premium</a>
        </div>
	</div>

	<div class="ty-table-wrap">
        <div class="wrap">
            <h4>Simple Pricing, No Surprises</h4>
            <h5>Sign up for <strong>Mediator Academy Premium Membership</strong></h5>
            <p class="small-p">Free trial, cancel anytime</p>

            <p>Learn from Internationally respected Mediators and Thought Leaders at your own pace, <br> Display your profile, show off your expertise and get hired.</p>

            <div class="ty-table">
                <div class="row green no-border">
                    <div class="col col-1"><span>Feature</span></div>
                    <div class="col col-2"><span>Free</span></div>
                    <div class="col col-3"><span>Mediator Premium $37<br>(20% discount Annual billing)</span></div>
                </div>
                
                <div class="for-responsive">
                    <div class="row row-full"><span>Access Learning Resources</span></div>
                    <div class="row">
                        <div class="col col-1"><span>Access to Videos (No.)</span></div>
                        <div class="col col-2"><span>10</span></div>
                        <div class="col col-3"><span>Unlimited</span></div>
                    </div>
                    <div class="row">
                        <div class="col col-1"><span>Download Audio</span></div>
                        <div class="col col-2"><span class="x-icon"></span></div>
                        <div class="col col-3"><span class="y-icon"></span></div>
                    </div>
                    <div class="row">
                        <div class="col col-1"><span>Download Transcripts</span></div>
                        <div class="col col-2"><span class="x-icon"></span></div>
                        <div class="col col-3"><span class="y-icon"></span></div>
                    </div>
                </div>

                <div class="for-responsive">
                    <div class="row row-full"><span>Mediator Profile</span></div>
                    <div class="row">
                        <div class="col col-1"><span>Display Basic Profile</span></div>
                        <div class="col col-2"><span class="y-icon"></span></div>
                        <div class="col col-3"><span class="y-icon"></span></div>
                    </div>
                    <div class="row">
                        <div class="col col-1"><span>Advertise Services</span></div>
                        <div class="col col-2"><span class="x-icon"></span></div>
                        <div class="col col-3"><span class="y-icon"></span></div>
                    </div>
                    <div class="row">
                        <div class="col col-1"><span>Advertise Specialisms</span></div>
                        <div class="col col-2"><span class="x-icon"></span></div>
                        <div class="col col-3"><span class="y-icon"></span></div>
                    </div>
                    <div class="row">
                        <div class="col col-1"><span>Enable Get Hired Feature</span></div>
                        <div class="col col-2"><span class="x-icon"></span></div>
                        <div class="col col-3"><span class="y-icon"></span></div>
                    </div>
                </div>
                <div class="for-responsive">
                    <div class="row row-full"><span>CPD</span></div>
                    <div class="row">
                        <div class="col col-1"><span>Access CPD Dashboard</span></div>
                        <div class="col col-2"><span class="x-icon"></span></div>
                        <div class="col col-3"><span class="y-icon"></span></div>
                    </div>
                    <div class="row">
                        <div class="col col-1"><span><strong>Premium Support</strong></span></div>
                        <div class="col col-2"><span class="x-icon"></span></div>
                        <div class="col col-3"><span class="y-icon"></span></div>
                    </div>
                </div>
                <div class="row row-button">
                    <a href="<?php bloginfo('url'); ?>/plans/" class="go-to-upgrade">Upgrade to Premium</a>
                </div>
            </div> <!-- .ty-table -->
        </div>
	</div> <!-- .ty-table-wrap -->
	<?php
}



genesis();

?>