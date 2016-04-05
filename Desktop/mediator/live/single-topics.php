<?php

add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );



add_filter('body_class', 'codeart_add_body_class_to_single_topic');
function codeart_add_body_class_to_single_topic($classes)
{
	$classes[] = 'codeart-content-sidebar-leyout';
	return $classes;
}





/**
 * Proper way to enqueue scripts and styles
 *	Include wistia js-api
 */
// add_action( 'wp_enqueue_scripts', 'codeart_enqueue_wistia_js_api' );
function codeart_enqueue_wistia_js_api()
{
	global $member;
	$video_id = get_field('interview_video');

	if( $member->can_watch_video($video_id) )
	{
		wp_enqueue_script(
			'wistia-js',
			'https://fast.wistia.net/assets/external/iframe-api-v1.js',
			array('jquery'),
			'1.0.0',
			true
		);
	}
}










remove_action('genesis_loop', 'genesis_do_loop');
add_action('genesis_loop', 'codeart_single_topic_loop');
function codeart_single_topic_loop()
{
	the_post(); ?>

	<div class="entry">

		<?php
		global $member, $post;
		$video_id = get_field('interview_video');
		$is_rated = is_user_raetd_video($video_id); ?>

		<div class="entry-content">

			<div class="left">
				<h2><?php the_title(); ?></h2>

				<?php
				$catterms = get_the_terms( get_the_ID(), 'topic_cat' );

				$clicable_categories = [];

				if($catterms):
					foreach ($catterms as $catterm):
						$clicable_categories[] = sprintf('<a href="%s">%s</a>', get_bloginfo('url') . '/search/topics/?category=' . $catterm->slug, $catterm->name);
					endforeach;
				endif; ?>

				<div class="cat-date-wrap">
					<?php
					if ($clicable_categories) {
						printf('<div class="topic-category">%s</div>', implode(', ', $clicable_categories));
					} ?>

					<div class="pub-date">
						<span><?php echo get_the_date('m.d.Y'); ?></span>
					</div>
				</div>

				<?php
				// the_content();

				if ( trim(strip_tags($post->post_content))) {
					printf('<div class="topic-content-wrap">');
					$topic_content = wpautop($post->post_content);
					$topic_content_extracted = explode('</p>', $topic_content);
					$topic_content_first = array_shift($topic_content_extracted);

					$other_content = !empty($topic_content_extracted) ? trim($topic_content_extracted[0]) : '';

					echo '<div class="first-p ' . (($other_content) ? 'fhas-content' : 'fhas-no-content') . '">' . apply_filters('the_content', $topic_content_first) . '</div>';

					if( $topic_content_extracted ):
						printf('<div class="inner-topic-content">');
						foreach($topic_content_extracted as $tcontent):
							if (empty($tcontent)) {
								continue;
							}

							echo wpautop( $tcontent );
						endforeach;
						printf('</div>');
					endif;

	                if ($other_content) {
						printf('<a href="#" class="topic-content-button">See More</a>');
					}
	    
					printf('</div>');
				}

				?>
				

				<?php if( $video_id ): ?>
					<?php if( $member->can_watch_video($video_id) ): ?>

		            <div class="wistia-video wistia_responsive_padding" data-id="<?php echo esc_attr($video_id); ?>" style="padding:56.25% 0 28px 0;position:relative;">
		            	<div class="wistia_responsive_wrapper" style="height:100%;left:0;position:absolute;top:0;width:100%;">
		            		<div class="wistia_embed wistia_async_<?php echo $video_id; ?> videoFoam=true" style="height:100%;width:100%">
		            			&nbsp;
		            		</div>
		            	</div>
		            </div>
		            <script charset="ISO-8859-1" src="//fast.wistia.com/assets/external/E-v1.js" async></script>

		        	<?php else: ?>
		        		<div class="must-upgrade-wrap" style="background-image:url('<?php echo get_stylesheet_directory_uri(); ?>/images/must-upgrade.jpg');">
		        			<?php if(is_user_logged_in()): ?>
	        				<div class="overlay">
		        				<h4><strong>You have reached your limit as free member..<br>Upgrade NOW</strong> to <strong>get</strong> access to more videos and a lot more features.</h4>
		        				<a href="<?php bloginfo('url'); ?>/plans/">Upgrade NOW!</a>
	        				</div>
	        				<?php else: ?>
	        				<div class="overlay">
		        				<h4><strong>You have reached your limit.<br>Sign up</strong> for <strong>FREE</strong> to access more videos</h4>
		        				<a href="#" class="register-button">Sign Up NOW!</a>
	        				</div>
	        				<?php endif; ?>
	        			</div>
		        	<?php endif; ?>
	            <?php endif; ?>

	            <?php if( get_field('interview_transcript') && $member->can_watch_video($video_id)): ?>
				<div class="transcript">

					<?php
					$transcript = get_field('interview_transcript');
					$transcript = explode('</p>', $transcript);

					$transcript_rest = $transcript;
					array_shift($transcript_rest);

					$transcript = $transcript[0];
					$transcript = strip_tags($transcript);
					?>

					<div class="transcript-head">
						<div class="transcript-title">
							<h4>Transcript</h4>

							<a href="#" class="read-full-transcript">Full Transcript</a>

							<div class="download-links">
								<?php if(get_field('download_pdf') || get_field('download_audio') || get_field('download_video')): ?>
								<span>Download as:</span>
								<?php endif; ?>
								
								<?php if(get_field('download_pdf')): ?>
								<a href="<?php the_field('download_pdf'); ?>" target="_blank" class="d-pdf">PDF</a>
								<?php endif; ?>

								<?php if(get_field('download_audio')): ?>
								<a href="<?php the_field('download_audio'); ?>" target="_blank" class="d-audio">Audio</a>
								<?php endif; ?>
							</div>
						</div>

						<div class="transcript-wrap">
							<?php echo wpautop($transcript); ?>
							<div class="full-transcript">
								<?php
								echo implode('', $transcript_rest);
								// the_field('interview_transcript');
								?>
							</div>
						</div>
					</div>
					<!--<a href="#" class="read-full-transcript">Read Full Transcript</a>-->
				</div> <!-- .transcript -->
				<?php endif; ?>

				<?php if( get_field('interview_mediator') ): ?>
	            <div class="about-mediator">
	            	<h4>About the mediator</h4>

	            	<div class="about-mediator-wrap">
		            	<?php
		            	$mediator_profile = get_field('interview_mediator');

		            	global $member;

		            	$member->print_avatar( $mediator_profile['ID'] );
		            	$mediator_description = $mediator_profile['user_description'];
		            	$mediator_description = strip_shortcodes($mediator_description);
		            	$mediator_description = strip_tags($mediator_description);
		            	$mediator_description = substr($mediator_description, 0, 550) . '... ';
		            	$mediator_description .= '<a href="' . codeart_get_mediators_url($mediator_profile['user_nicename']) . '">View Mediator</a>';

		            	printf('%s', $mediator_description); ?>
	            	</div>
	            </div>
	            <?php endif; ?>


	            <!--<div class="asso-org-topic">
	            	<h4>Associated Organisations</h4>

	            	<div class="entry-items">
	            		<div class="item first even three fourth five six">
	            			<img width="200" height="200" src="http://mediator.codeart.mk/wp-content/uploads/2015/12/h.jpg" class="attachment-popular-mediator-avatar size-popular-mediator-avatar wp-post-image" alt="h" srcset="http://mediator.codeart.mk/wp-content/uploads/2015/12/h-150x150.jpg 150w, http://mediator.codeart.mk/wp-content/uploads/2015/12/h.jpg 200w" sizes="(max-width: 200px) 100vw, 200px">
	            			<h4 class="ttl">Harvard</h4>
	            		</div>

		            	<div class="item odd">
		            		<img width="200" height="200" src="http://mediator.codeart.mk/wp-content/uploads/2015/12/o.jpg" class="attachment-popular-mediator-avatar size-popular-mediator-avatar wp-post-image" alt="o" srcset="http://mediator.codeart.mk/wp-content/uploads/2015/12/o-150x150.jpg 150w, http://mediator.codeart.mk/wp-content/uploads/2015/12/o.jpg 200w" sizes="(max-width: 200px) 100vw, 200px">
		            		<h4 class="ttl">Oxford</h4>
		            	</div>

		            	<div class="item even">
	            			<img width="200" height="200" src="http://mediator.codeart.mk/wp-content/uploads/2015/12/h.jpg" class="attachment-popular-mediator-avatar size-popular-mediator-avatar wp-post-image" alt="h" srcset="http://mediator.codeart.mk/wp-content/uploads/2015/12/h-150x150.jpg 150w, http://mediator.codeart.mk/wp-content/uploads/2015/12/h.jpg 200w" sizes="(max-width: 200px) 100vw, 200px">
	            			<h4 class="ttl">Harvard</h4>
	            		</div>

		            	<div class="item odd">
		            		<img width="200" height="200" src="http://mediator.codeart.mk/wp-content/uploads/2015/12/o.jpg" class="attachment-popular-mediator-avatar size-popular-mediator-avatar wp-post-image" alt="o" srcset="http://mediator.codeart.mk/wp-content/uploads/2015/12/o-150x150.jpg 150w, http://mediator.codeart.mk/wp-content/uploads/2015/12/o.jpg 200w" sizes="(max-width: 200px) 100vw, 200px">
		            		<h4 class="ttl">Oxford</h4>
		            	</div>
	            	</div>

	            </div> -->
			</div> <!-- .left -->

			<div class="right">
			    
                <?php codeart_print_video_raintgs(); ?>

                <?php codeart_share_box('share-topic'); ?>

                <div class="box">
                	<?php codeart_print_video_chapters(); ?>
            	</div>

			</div> <!-- .right -->

		</div> <!-- .entry -->


	</div>
	<?php
}








/**
 * Print child video chapters
 **/
function codeart_print_video_chapters()
{
	global $post;

	$current_topic_id = $post->ID;

	$current_id = $post->ID;
	$post_parent = $post->post_parent ? $post->post_parent : $post->ID;

	$chapter_args = array(
		'post_type' 		=> 'topics',
		'posts_per_page' 	=> -1,
		'post_parent'		=> $post_parent
	);

	$chapters = new WP_Query($chapter_args);

	$chapter_counter = 1;

	if( $chapters->have_posts() ):
		printf('<h4 class="chapters-title">Topic Chapters</h4>');
		printf('<div class="right-box-items topic-chapters topic-chapters-height">'); ?>
			<?php if($chapters->found_posts > 5): ?>
			<div class="chapter-height-spinner">
				<div class="spinner">
					<div class="double-bounce1"></div>
					<div class="double-bounce2"></div>
				</div>
			</div>
			<?php endif; ?>
			<?php
			while( $chapters->have_posts() ): $chapters->the_post();
				printf(
					'<div class="box-item chapter %s">
                        <div class="chapter-content">
						<a href="%s" class="camera-anchor"><span class="chapetr-counter">%s</span><div class="camera">%s</div></a>
						<a href="%s" class="%s"><h4>%s</h4></a>
                        </div>
					</div>',
					$current_topic_id == get_the_ID() ? 'active' : '',
					get_permalink(get_the_ID()),
					$chapter_counter++ . '/' . $chapters->found_posts,
                    file_get_contents(get_stylesheet_directory_uri() . "/images/icon-videocamera.svg"),
					get_permalink(get_the_ID()),
					$current_id == get_the_ID() ? 'active' : '',
					get_the_title()
				);
			endwhile;
		printf('</div>');
	endif;

	wp_reset_postdata();
	
	if ($post_parent != $post->ID) {
		printf(
			'<div class="box box-bottom"><div class="parent-full-interview"><a href="%s" class="thumb">%s</a><a href="%s" class="ttl">Watch Full Interview</a></div></div>',
			get_permalink($post_parent),
			get_the_post_thumbnail( $post_parent, 'topics-thumbnail' ),
			get_permalink($post_parent)
		);
	}
	?>

	<div class="box box-bottom-comments">
		<div id="comments">
			<?php genesis_get_comments_template(); ?>
		</div>
	</div>
	<?php

	wp_reset_postdata();
} // End of function codeart_print_video_chapters();









function codeart_print_video_raintgs()
{
	global $post;
	$interview_video = get_field('interview_video', $post->ID);
	$is_rated = is_user_raetd_video( $interview_video ); ?>

	<div class="box rating-box topic-rating <?php echo $is_rated ? 'rated' : 'not-rated'; ?>">
    	<h4>Rate Video</h4>

    	<?php
    		$video_rating 		= codeart_get_video_rating_sum($interview_video);
    		$video_rating_avg 	= $video_rating[1] > 0 ? round( ($video_rating[0]/$video_rating[1]), 1 ) : 0;

    		$all_rating_sum = isset($video_rating[0]) ? $video_rating[0] : 0;
    		$count_rating_sum = isset($video_rating[1]) ? $video_rating[1] : 0;
    	?>

    	<div class="rating-stars" data-allrating="<?php echo $all_rating_sum; ?>" data-countrating="<?php echo $count_rating_sum; ?>">
    		<?php $avg_rating = round($video_rating_avg); ?>
    		<?php for($i=1; $i<=5; $i++): ?>
    		<?php $cls = ($i <= $avg_rating && !empty($is_rated)) ? 'hovered' : ''; ?>
    		<div class="star <?php echo $cls; ?>" data-id="<?php echo $i; ?>">
                <div class="off icon"><?php ca_get_svg('rating_off.svg'); ?></div>
                <div class="on icon"><?php ca_get_svg('rating_on.svg'); ?></div>
    		</div>
    		<?php endfor; ?>
        </div>

        <div class="rating-count">
        	<?php ca_get_svg('rating_on.svg'); ?>
        	<span class="count-number"><?php echo $video_rating_avg; ?></span>
        </div>
    </div>
	<?php
} // End of function codeart_print_video_ratings();







/**
 *  Wistia API, activity log events on single post
 **/
add_action('genesis_after', 'codeart_single_course_footer', 999);
function codeart_single_course_footer()
{
	global $post; ?>
    <script type="text/javascript">
    jQuery(document).ready(function($) {

    	var rate = $('body').find('.rating-stars div.star');
    	rate.on('click', function() {
    		var rate_holder = $(this).closest('.rating-box');

    		var div_stars = $(this).closest('.rating-stars');
    		var all_rating 		= div_stars.data('allrating');
    		var count_rating 	= div_stars.data('countrating');

    		var current_rating = $(this).data('id');

    		div_stars.addClass('is-rating');
    		rate_holder.addClass('animation-start');

    		var data = {
				'action': 'rate_video',
				'pid': <?php echo $post->ID; ?>,
				'vid': '<?php the_field("interview_video", $post->ID); ?>',
				'rating': $(this).data('id')
			};

			jQuery.post(ajaxurl, data, function(response) {
				var obj = jQuery.parseJSON( response );

				if( obj.status == true )
				{
					rate_holder.addClass('rated finish-rated');

					all_rating += parseInt(current_rating);
					count_rating++;

					var rating_result = Math.floor(all_rating/count_rating);
					$('body').find('span.count-number').text(rating_result);
				}
				else
				{

				}
			});
    	});





    	$('body').find('a.read-full-transcript').on('click', function(e) {
    		e.preventDefault();
            $('body').find('div.transcript-wrap .full-transcript').slideToggle();
    		$(this).toggleClass('close');
    	});

    	/*
    	if( $('body').find("#mediator-iframe").length )
    	{
    		wVideo_event = $('body').find("#mediator-iframe")[0].wistiaApi;

	    	<?php
		    	global $post;
		    	$video_id = get_field('interview_video');
	    	?>

	    	wVideo_event.bind("play", function() {

	    		var pid 		= '<?php the_ID(); ?>';
	        	var type 		= 'started';
	        	var vid 		= $('body').find('.wistia-video').data('id');
	        	var duration 	= wVideo_event.duration();

	            var data = {
	                'action': 'interveiw_log',
	                'pid': pid,
	                'vid' : vid,
	                'type': type,
	                'duration' : duration
	            };

	            $.post(ajaxurl, data, function(response) {
	            	console.log( response );
	            });
	        }); // End Video Event

    	} */

    	<?php
    	global $post;
    	$video_id = get_field('interview_video');
    	?>
    	
    	<?php if($video_id): ?>
    	window._wq = window._wq || [];

    	_wq.push({ '<?php echo substr($video_id, 0, 3); ?>': function(video) {
    		video.bind('play', function() {
    			
    			var pid 		= '<?php the_ID(); ?>';
	        	var type 		= 'started';
	        	var vid 		= $('body').find('.wistia-video').data('id');
	        	var duration 	= video.duration();

	            var data = {
	                'action': 'interveiw_log',
	                'pid': pid,
	                'vid' : vid,
	                'type': type,
	                'duration' : duration
	            };

	            $.post(ajaxurl, data, function(response) {
	            	console.log( response );
	            });

    			return video.unbind;
    		});
    	}});
    	<?php endif; ?>

    });
    </script>
    <?php
}




genesis();

?>