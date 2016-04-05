<?php

add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );



add_filter('body_class', 'codeart_add_body_class_to_single_organization');
function codeart_add_body_class_to_single_organization($classes)
{
	$classes[] = 'codeart-organization codeart-content-sidebar-leyout';
	return $classes;
}






remove_action('genesis_loop', 'genesis_do_loop');
add_action('genesis_loop', 'codeart_single_organization_loop');
function codeart_single_organization_loop()
{
	the_post(); ?>

	<div class="entry">

		<?php
		global $member, $post, $wpdb;
		$video_id = get_field('organization_video'); ?>

		<div class="entry-content">
			
			<div class="left">
				<h2><?php the_title(); ?></h2>

				<?php the_content(); ?>

				<?php if( $video_id ): ?>
					<div class="video wistia-video" data-id="<?php echo esc_attr($video_id); ?>">
		                <div class="iframe-wrap">
		                	<iframe id="mediator-iframe" src="https://fast.wistia.net/embed/iframe/<?php echo esc_attr($video_id); ?>?videoFoam=true" allowtransparency="true" frameborder="0" scrolling="no" class="wistia_embed" name="wistia_embed" allowfullscreen mozallowfullscreen webkitallowfullscreen oallowfullscreen msallowfullscreen width="640" height="360"></iframe>
		                </div>
		            </div>
	            <?php endif; ?>

	            <?php
	            // SELECT * FROM table WHERE your_field_here REGEXP '.*;s:[0-9]+:"your_value_here".*'
	            $sql = "
	            SELECT u.ID, u.display_name, u.user_nicename, md.avatar 
	            	FROM $wpdb->users AS u 
	            LEFT JOIN 
	            	mwdpb_mediator_members_data AS md ON (u.ID = md.user_id) 
	            WHERE organizations REGEXP '.*;s:[0-9]+:" . '"' . $post->ID . ".*'
	            ";
	            $asso_users = $wpdb->get_results($sql);
	            
	            if($asso_users):
	            	$asso_counter = 1;
	            	printf('<div class="asso-mediators">');
		            	printf('<h3>Associate Meditators</h3>');
		            	foreach($asso_users as $au): ?>
		            		<div class="item <?php codeart_add_item_classes($asso_counter) ?>">
                                <a href="<?php echo codeart_get_mediators_url($au->user_nicename); ?>">
                                    <div class="image-holder">
                                    	<?php codeart_member_avatar($au->avatar); ?>
                                        <!--<img src="http://ma.codeart.rocks/wp-content/uploads/2015/11/10562521_10203462103055715_4662996974450321019_o.jpg" alt="">-->
                                    </div>
                                    <h4><?php echo apply_filters('the_title', $au->display_name); ?></h4>
                                </a>
                            </div>
		            		<?php
		            	endforeach;
	            	printf('</div>');
	            endif; ?>


	            <?php if( get_field('organization_transcript') && $member->can_watch_video($video_id)): ?>
				<div class="transcript">
					<a href="#" class="read-full-transcript">Read Full Transcript</a>
					<div class="full-transcript"><?php the_field('organization_transcript'); ?></div>
				</div> <!-- .transcript -->
				<?php endif; ?>
			</div> <!-- .left -->

			<div class="right">

				<div class="box org-details">
					<?php
						// $profile_picture 			= get_field('organization_image');
						$organization_name 			= get_field('organization_name');
						$organization_established 	= get_field('organization_established');
						$organization_hq_location 	= get_field('organization_hq_location');

						/*
						if( $profile_picture ):
							printf('<img src="%s" />', $profile_picture['url']);
						endif;
						*/
						if (has_post_thumbnail()) {
							the_post_thumbnail( 'topics-thumbnail' );
						}

						if( $organization_name ):
							printf(
								'<p class="detail-item"><strong>Name: </strong><span>%s</span></p>',
								apply_filters('the_title', $organization_name)
							);
						endif;
						if( $organization_established ):
							printf(
								'<p class="detail-item"><strong>Established: </strong><span>%s</span></p>',
								apply_filters('the_title', $organization_established)
							);
						endif;
						if( $organization_hq_location ):
							printf(
								'<p class="detail-item"><strong>HQ Location: </strong><span>%s</span></p>',
								apply_filters('the_title', $organization_hq_location)
							);
						endif; ?>
				</div>

						

                <?php codeart_share_box('share-interview'); ?>

			</div> <!-- .right -->

		</div> <!-- .entry -->


	</div>
	<?php
}








/**
 *  Wistia API, activity log events on single post
 **/
add_action('genesis_after', 'codeart_single_organization_footer', 999);
function codeart_single_organization_footer()
{
	global $post; ?>
    <script type="text/javascript">
    jQuery(document).ready(function($) {

    	$('body').find('a.read-full-transcript').on('click', function(e) {
    		e.preventDefault();
    		$('body').find('.full-transcript').stop().slideToggle();
    		$(this).toggleClass('close');
    	});

    });
    </script>
    <?php
}









add_action('wp_head', 'codeart_organization_seo_markup');
function codeart_organization_seo_markup()
{
	global $post;
	$name = get_field('organization_name', $post->ID);

	?>
	<script type="application/ld+json">
	{
		"@context": "http://schema.org",
		"@type": "EducationalOrganization",
		"name": "<?php echo $name; ?>"
	}
	</script>
	<?php
}





genesis();

?>