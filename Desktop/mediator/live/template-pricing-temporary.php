<?php

/**
 *	Template Name: Pricing New Temporary
 **/

remove_action('genesis_loop', 'genesis_do_loop');
add_action('genesis_loop', 'codeart_new_pricing_temporary');
function codeart_new_pricing_temporary()
{
	?>
	<div class="new-pricing-temporary">
        <div class="custom-wrap">
            <h2 class="green">Simple Pricing, No Surprises</h2>
            <h4>Sign up for <strong>Mediator Academy Premium Membership</strong></h4>
            <p class="small">Free trial, cancel anytime</p>
            
            <div class="ca-custom-switch">
                <label>Monthly Billing</label>
                <div class="button checked"></div>
                <label>Annual billing <span class="green-text">(Save 20%)</span></label>
            </div>

            <div class="comparation-divs">
                <div class="comp-box free-box">
                    <h4>Free</h4>
                    <div class="entry-box">
                        <p class="price"><span>$0</span>/ month</p>
                        <p>For explorers and curious observers</p>
                        <div class="separator"></div>
                        <ul>
                            <li>For explorers and curious observers</li>
                            <li>Create your basic Mediator Profile</li>
                        </ul>
                        <p class="dark-button">Your current plan</p>
                    </div>
                </div> <!-- .free-box -->

                <div class="comp-box premium-box">
                    <div class="green-top-box">
                        <h4>Premium</h4>
                    </div>
                    <div class="entry-box">
                        <p class="price ca-price-can-change"><span data-promo-price="$30" data-original-price="$37">$30</span>/ month</p>
                        <p>For aspiring and professional mediators</p>
                        <div class="separator"></div>
                        <ul>
                            <li>Unlimited access to video library</li>
                            <li>Download audio and transcripts</li>
                            <li>Enable Pro Mediator Profile & get hired</li>
                            <li>CPD dashboard to log and track learning</li>
                            <li>Premium Support</li>
                        </ul>
                        <a href="<?php bloginfo('url'); ?>/upgrade/" data-url="<?php bloginfo('url'); ?>/upgrade/" class="ca-upgrade-premium">Upgrade to Premium</a>
                    </div>
                </div> <!-- .premium-box -->
                <p class="see-the">See the <a href="<?php bloginfo('url'); ?>/plans-detailed/">detailed comparison of the plans</a></p>
                <ul class="more-margin-bottom font_18">
                    <li>Learn from Internationally respected Mediators and Thought Leaders at your own pace</li>
                    <li>Display your profile, show off your skills and talents to the world and get appointed</li>
                </ul>
            </div> <!-- .comparation-divs -->
        </div>
        
        <img src="http://mediator.codeart.mk/wp-content/uploads/2016/02/30dayguarantee-1.png" alt="" class="aligned-center ca-top-bottom-margin-20">
        
        <?php
        if (have_rows('testimonials_plans')) {
            printf('<div class="mediator-people">');
            while (have_rows('testimonials_plans')) {
                the_row(); ?>
                <div class="box">
                    <div class="image">
                        <?php
                        // popular-mediator-avatar
                        $pavatar = get_sub_field('pimage');
                        if($pavatar) {
                            printf(
                                '<img src="%s" />',
                                $pavatar['sizes']['popular-mediator-avatar']
                            );
                        }
                        ?>
                    </div>
                    <h3><?php the_sub_field('pname'); ?></h3>
                    <h4><?php the_sub_field('ptitle'); ?></h4>
                    <?php the_sub_field('ptestimonial_content'); ?>
                </div>
                <?php
            }
            printf(
                '<a href="%s" class="ca-upgrade-premium default-big-btn">Upgrade to Premium</a>',
                get_bloginfo('url') . '/upgrade/'
            );
            printf('</div>');
        }
        ?>

	</div>
	<?php
}

genesis();

?>