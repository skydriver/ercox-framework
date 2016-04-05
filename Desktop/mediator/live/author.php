<?php

add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );



add_filter('body_class', 'codeart_mediator_profile_body_class');
function codeart_mediator_profile_body_class( $classes )
{
	$classes[] = 'codeart-mediator-profile';
	$classes[] = 'codeart-content-sidebar-leyout';
	return $classes;
}



remove_action('genesis_loop', 'genesis_do_loop');
add_action('genesis_loop', 'codeart_single_author_loop');
function codeart_single_author_loop()
{
	global $author, $member, $author_member;

	$current_author = get_user_by('id', $author); ?>

	<?php
		$location 	= $author_member->get('location', false);
		$location 	= Mediator_Member_Fields::get_full_location($location);
		
		$phone 		= $author_member->get('phone', false);
		$experience = $author_member->get('experience', false);
		$education 	= $author_member->get('education', false);
		$experience = maybe_unserialize($experience);
		$education 	= maybe_unserialize($education);

		$member_title 	= $author_member->get('title', false);

		$specialisms = $author_member->get('specialisms', false);
		$specialism = $specialisms ? get_terms('specialism', array('include' => $specialisms, 'hide_empty' => false)) : false;

		$services_provided = $author_member->get('services_provided', false);
		$services_provided = $services_provided ? get_terms('service', array('include' => $services_provided, 'hide_empty' => false)) : false;

		$special_service_has_count = 0;
		if ($specialism) { $special_service_has_count++; }
		if ($services_provided) { $special_service_has_count++; }


		$special_service_has_everything = false;
		if (empty($specialism) && empty($services_provided) && empty($current_author->description)) {
			$special_service_has_everything = true;
		}

		global $member;
		$organizations_data = isset($member->member_data->organizations) ? $member->member_data->organizations : [];
		$organizations_data = maybe_unserialize($organizations_data);

		$org_args = array(
			'post_type' 		=> 'organization',
			// 'posts_per_page' 	=> 2,
			'post__in'			=> $organizations_data
		);
		$organizations = get_posts($org_args);
		if (empty($organizations_data)) {
			$organizations = null;
		}
		
		$asso_topics = get_posts([
			'posts_per_page' 	=> 2,
			'post_type' 		=> 'topics',
			'post_status' 		=> 'publish',
			'meta_query' => array(
				array(
					'key'     => 'interview_mediator',
					'value'   => $author,
					'compare' => '=',
				),
			)
		]);


		$has_asso_org_and_topics = 0;
		if (!$organizations || !$asso_topics) {
			$has_asso_org_and_topics = 1;
		}
	?>

	<div class="entry org-topic-count-<?php echo intval($has_asso_org_and_topics); ?> spec-serv-<?php echo intval($special_service_has_count); ?> <?php echo $special_service_has_everything ? 'ca-empty-everything' : ''; ?>">
		<div class="entry-content">

			<div class="author-top">
				<div class="left">
					<div class="a-box">
					
					<?php 
                        if( $author_member->is_premium_member() ):
                            printf('<span class="author-star"></span>');
                        endif;
                    ?>
				        <div class="avatar-holder">
				        	<?php $author_member->print_avatar($author, 'popular-mediator-avatar'); ?>
                        </div>
						<h2><?php echo $current_author->first_name . ' ' . $current_author->last_name; ?></h2>
						<?php if($member_title): ?>
						<span class="desc"><?php echo $member_title; ?></span>
						<?php endif; ?>

						<div class="mediator-info">
							<?php if($location): ?><span class="location"><?php echo $location; ?></span><?php endif; ?>
						</div>

						<div class="share-author">
							<span>Share on: </span>
							<?php
								global $wp;
								$current_url = home_url(add_query_arg(array(),$wp->request));

								$share_title = urlencode($current_author->first_name . ' ' . $current_author->last_name);
							?>

							<a onclick="javascript:window.open('http://twitter.com/home?status=<?php echo $share_title; ?> - <?php echo $current_url; ?>', '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" href="http://twitter.com/intent/tweet?status=<?php echo $share_title; ?>+<?php echo $current_url; ?>" class="s-icon tw">Twitter</a>
							<a onclick="javascript:window.open('http://www.facebook.com/sharer.php?u=<?php echo urlencode($current_url); ?>&t=<?php echo urlencode($share_title); ?>', '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" href="http://www.facebook.com/share.php?u=<?php echo $current_url; ?>&title=<?php echo $share_title; ?>" class="s-icon fb">Facebook</a>
							<a onclick="javascript:window.open('https://plus.google.com/share?url=<?php echo $current_url; ?>', '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" href="https://plus.google.com/share?url=<?php echo $current_url; ?>" class="s-icon gp">Google+</a>
							
						</div>
					</div> <!-- a.box -->
				</div> <!-- .left -->


				<?php if($author_member->is_premium_member() ): ?>
				<div class="right">
				    <a href="#" class="contact-mediator contact-mediator-big ajax-contact-button ca_custom_main_popup_trigger" data-name="<?php echo $current_author->first_name; ?>" data-id="<?php echo intval($author); ?>" data-popup="div.ajax-contact-popup">Contact <?php echo $current_author->first_name; ?></a>
					<?php
					
					if($specialism): ?>
					<div class="a-box box-collapsable">
					    <div class="box-heading">
				            <h4>Specialisms</h4>
						    <a href="#" class="show-more">Show more</a>
                        </div>
						<div class="entry-items">
							<?php $ncounter = 1; ?>
							<?php foreach($specialism as $spec): ?>
							<a href="<?php echo bloginfo('url'); ?>/search/?specialism=<?php echo $spec->slug; ?>">
								<span class="counter"><?php $ncounter++; ?></span>
								<?php echo $spec->name; ?>
							</a>
							<?php endforeach; ?>
						</div>
					</div> <!-- a.box -->
					<?php endif; ?>


					<?php
					if($services_provided): ?>
					<div class="a-box box-collapsable">
					    <div class="box-heading">
				            <h4>Services Provided</h4>
						    <a href="#" class="show-more">Show more</a>
                        </div>
						<div class="entry-items">
							<?php $ncounter = 1; ?>
							<?php foreach($services_provided as $sp): ?>
							<a href="<?php echo bloginfo('url'); ?>/search/mediators/?services=<?php echo $sp->slug; ?>">
								<span class="counter"><?php $ncounter++; ?></span>
								<?php echo $sp->name; ?>
							</a>
							<?php endforeach; ?>
						</div>
					</div> <!-- a.box -->
					<?php endif; ?>

				</div> <!-- .right -->
				<?php endif; ?>
			</div> <!-- .author-top -->


			<?php if($current_author->description): ?>

			<?php if($author_member->is_premium_member()): ?>
			<div class="for-bio-contact-btn">
				<a href="#" class="contact-mediator contact-mediator-big ajax-contact-button ca_custom_main_popup_trigger" data-name="<?php echo $current_author->first_name; ?>" data-id="<?php echo intval($author); ?>" data-popup="div.ajax-contact-popup">Contact <?php echo $current_author->first_name; ?></a>
			</div>
			<?php endif; ?>
			<div class="author-bio">
				<div class="a-box box-collapsable">
				    <div class="box-heading">
					    <h4>Bio</h4>
                        <a href="#" class="show-more">Show more</a>
                    </div>
					<div class="entry-items">
						<?php echo apply_filters('the_content', $current_author->description); ?>
					</div>
				</div> <!-- .a-box -->
			</div> <!-- .author-middle -->
			<?php endif; ?>


			<?php
			$education 	= $author_member->get('education', false);
			$expirience = $author_member->get('experience', false);

			$has_education 	= codeart_filter_array($education);
			$has_expirience = codeart_filter_array($expirience);

			$full_width_class = '';
			if( $has_expirience || $has_education )
			{
				$full_width_class = ($has_education && $has_expirience) ? 'e-not-full-width' : 'e-full-width';
			}
			?>


			<div class="author-edu-exp <?php echo $full_width_class; ?>">


				<?php if($has_expirience): ?>
				<div class="a-box left box-collapsable">
				    <div class="box-heading">
					    <h4>Experience</h4>
                        <a href="#" class="show-more">Show more</a>
                    </div>
					<ul>
						<?php
						foreach($expirience as $exp):

							$period_from = '';
							$period_to = '';
							$period_from_to = '';

							if( isset($exp['experience_period_from']) )
								$period_from = date('M, Y', strtotime($exp['experience_period_from']));

							if( isset($exp['experience_period_to']) )
								$period_to = date('M, Y', strtotime($exp['experience_period_to']));

							if($period_from && $period_to)
								$period_from_to = sprintf('From %s to %s', $period_from, $period_to);

							printf(
								'<li><h4>%s at %s</h4><p>%s</p><p>%s</p></li>',
								apply_filters('the_title', $exp['experience_position']),
								apply_filters('the_title', $exp['experience_company_name']),
								$period_from_to,
								apply_filters('the_title', $exp['experience_description'])
							);
						endforeach; ?>
					</ul>
				</div> <!-- .a-box.right -->
				<?php endif; ?>


				<?php if($has_education): ?>
				<div class="a-box right box-collapsable">
				    <div class="box-heading">
					    <h4>Education</h4>
                        <a href="#" class="show-more">Show more</a>
                    </div>
					<ul>
						<?php
						foreach($education as $edu):

							$period_from = '';
							$period_to = '';
							$period_from_to = '';
							
							if( isset($edu['education_period_from']) )
								$period_from = date('M, Y', strtotime($edu['education_period_from']));

							if( isset($edu['education_period_to']) )
								$period_to = date('M, Y', strtotime($edu['education_period_to']));

							if($period_from && $period_to)
								$period_from_to = sprintf('From %s to %s', $period_from, $period_to);

							$act_and_soc = '';
							if( isset($edu['education_activities_and_societies']) )
							{
								$act_and_soc = apply_filters('the_title', $edu['education_activities_and_societies']);
								$act_and_soc = '<h4>Activities And Societies</h4><p>' . $act_and_soc . '</p>';
							}

							printf(
								'<li><h4>%s (%s)</h4><p>%s</p><p>%s</p>%s</li>',
								apply_filters('the_title', $edu['education_school_name']),
								apply_filters('the_title', $edu['education_degree']),
								$period_from_to,
								apply_filters('the_title', $edu['education_description']),
								$act_and_soc
							);
						endforeach; ?>
					</ul>
					
				</div> <!-- .a-box.left -->
				<?php endif; ?>

				

			</div> <!-- .author-edu-exp -->


			
            <div class="author-edu-exp e-not-full-width">
            
			<?php if( $organizations): ?>
			
			    <div class="asso-org half left">
				    <div class="a-box left box-collapsable">
                        <div class="box-heading">
					        <h4>Organisational Affiliations</h4>
                            <a href="#" class="show-more">Show more</a>
                        </div>
                        
					    <div class="entry-items">
						<?php $org_counter = 0; ?>
						<?php foreach($organizations as $org): ?>
						    <div class="item <?php codeart_add_item_classes($org_counter++) ?>">
							<?php
							$org_thumbnail = get_the_post_thumbnail( $org, 'popular-mediator-avatar' );
							echo $org_thumbnail;
							?>
							<!--
							<img src="http://ma.codeart.rocks/wp-content/uploads/2015/11/10562521_10203462103055715_4662996974450321019_o.jpg" alt="">
							-->
							    <h4 class="ttl"><?php echo apply_filters('the_title', $org->post_title); ?></h4>
						    </div>
						<?php endforeach; ?>
					    </div>
				    </div> <!-- .a-box -->
			    </div> <!-- .asso-org -->

			<?php endif; ?>

			
			
			<?php if($asso_topics): ?>
			<div class="asso-org half right">
				<div class="a-box right box-collapsable">
                    <div class="box-heading">
					    <h4>Topics</h4>
                        <a href="#" class="show-more">Show more</a>
                    </div>
					<div class="entry-items">
						<?php $org_counter = 0; ?>
						<?php foreach($asso_topics as $top): ?>
						<div class="item <?php codeart_add_item_classes($org_counter++) ?>">

							<a href="<?php echo get_permalink($top->ID); ?>" class="stia">
								<?php
								$org_thumbnail = get_the_post_thumbnail( $top, 'popular-mediator-avatar' );
								echo $org_thumbnail;
								?>
							</a>
							
							<a href="<?php echo get_permalink($top->ID); ?>" class="stta">
							<h4 class="ttl"><?php echo apply_filters('the_title', $top->post_title); ?></h4>
							</a>
							<?php
								$catterms = get_the_terms( $top, 'topic_cat' );
								if($catterms):
									printf('<div class="topic-categories">');
									foreach($catterms as $ct):
										printf(
											'<a href="%s/search/topics/?category=%s" class="topic-cat-item">%s</a>',
											get_bloginfo('url'),
											$ct->slug,
											$ct->name
										);
									endforeach;
									printf('</div>');
								endif; ?>
							<?php if( get_field('topic_video_length', $top->ID) ): ?>
							<p class="info-by"><?php echo codeart_mins_to_time( intval(get_field('topic_video_length', $top->ID)) ); ?></p>
						<?php endif; ?>
							<?php codeart_print_grid_rating($top->ID, true); ?>
						</div>
						<?php endforeach; ?>
					</div>
				</div> <!-- .a-box -->
			</div> <!-- .asso-org -->

			<?php endif; ?>
			
			</div>



			
			<!-- .author-tabs -->

		</div> <!-- .entry-content -->
	</div> <!-- .entry -->


	<style type="text/css">
	.author-tabs {}
	
	.author-tabs .tabs li {}
	.author-tabs .tabs li a {}

	.author-tabs .panels {}
        
	.author-tabs .panels li {
		display: none;
        opacity: 0;
        transition:
            opacity 0.25s ease-in-out 0s;
            -webkit-transition:
        		opacity 0.25s ease-in-out 0s;
        	-moz-transition:
        		opacity 0.25s ease-in-out 0s;
        	-o-transition:
        		opacity 0.25s ease-in-out 0s;
	}
        .author-tabs .panels li.visible{
            opacity: 1;
        }
	.author-tabs .panels li.active {
		display: block;
	}
	</style>


	<script type="text/javascript">
	jQuery(document).ready(function($) {
        var tabs   = $('body.codeart-mediator-profile div.author-tabs ul.tabs li a');
        var panels = $('body.codeart-mediator-profile div.author-tabs ul.panels li');
        
        tabs.on('click', function(e){
            e.preventDefault();
            
            if($(this).hasClass('active')){
                return;
            }
            
            var target = $(this).attr('data-tab');
            
            panels.removeClass('active visible');
            tabs.removeClass('active');
            
            
            $(this).addClass('active');
            
            panels.parent().find('.' + target).addClass('active').delay('100').queue(function(i){
                $(this).addClass('visible');
                i();
            });
        });
        
        var show_more_button = $('body.codeart-mediator-profile a.show-more');
        
        show_more_button.on('click', function(e){
            e.preventDefault();
            var parent = $(this).closest('div.box-collapsable');
            
            if($(this).hasClass('active')){
                $(this).text('Show More').removeClass('active');
                parent.removeClass('box-open');
                return;
            }
            
            parent.addClass('box-open');
            
            $(this).addClass('active');
            $(this).text('Hide More');
        });
        
        
	});
	</script>
	<?php
}




add_action('wp_head', 'codeart_add_person_schema_markup');
function codeart_add_person_schema_markup()
{
	global $author, $member, $author_member;
	$current_author = get_user_by('id', $author);

	// var_dump($current_author->user_email);

	$location 	= $author_member->get('location', false);
	$phone 		= $author_member->get('phone', false);
	$experience = $author_member->get('experience', false);
	$education 	= $author_member->get('education', false);
	$experience = maybe_unserialize($experience);
	$education 	= maybe_unserialize($education);

	$member_title = $author_member->get('title', false); ?>

	<script type="application/ld+json">
	{
		"@context": "http://schema.org",
		"@type": "Person",
		"address": {
			"@type": "PostalAddress",
			"addressLocality": "Seattle",
			"addressRegion": "WA",
			"postalCode": "98052",
			"streetAddress": "20341 Whitworth Institute 405 N. Whitworth"
		},
		"email": "mailto:<?php echo $current_author->user_email; ?>",
		"image": "janedoe.jpg",
		<?php if($member_title): ?>
		"jobTitle": "<?php echo $member_title; ?>",
		<?php endif; ?>
		"name": "<?php echo $current_author->first_name . ' ' . $current_author->last_name; ?>",
		"telephone": "<?php echo $phone; ?>",
		"url": "<?php echo $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>"
	}
	</script>
	<?php
}

genesis();

?>