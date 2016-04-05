<?php

add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );



add_filter('body_class', 'codeart_add_body_class_to_single_interview');
function codeart_add_body_class_to_single_interview($classes)
{
	$classes[] = 'codeart-content-sidebar-leyout';
	return $classes;
}





/**
 * Proper way to enqueue scripts and styles
 *	Include wistia js-api
 */
add_action( 'wp_enqueue_scripts', 'codeart_enqueue_wistia_js_api' );
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
add_action('genesis_loop', 'codeart_single_interview_loop');
function codeart_single_interview_loop()
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
				$catterms = get_the_terms( get_the_ID(), 'interview_cat' );
				$all_cat_term_names = array();
				foreach ($catterms as $catterm):
					$all_cat_term_names[] = $catterm->name;
				endforeach;
				printf('<span class="interview-category">%s</span>', implode(', ', $all_cat_term_names)); ?>

				<?php the_content(); ?>

				<?php if( $video_id ): ?>
					<?php if( $member->can_watch_video($video_id) ): ?>
					<div class="video wistia-video" data-id="<?php echo esc_attr($video_id); ?>">
		                <div class="iframe-wrap">
		                	<iframe id="mediator-iframe" src="https://fast.wistia.net/embed/iframe/<?php echo esc_attr($video_id); ?>?videoFoam=true" allowtransparency="true" frameborder="0" scrolling="no" class="wistia_embed" name="wistia_embed" allowfullscreen mozallowfullscreen webkitallowfullscreen oallowfullscreen msallowfullscreen width="640" height="360"></iframe>
		                </div>
		            </div>
		        	<?php else: ?>
		        		<p>
		        			<a href="#" class="register-button limit-image">
		        				<?php
		        				printf(
		        					'<img src="%s" />',
		        					codeart_get_video_thumbnail_from_vistia( $video_id, [500, 500] )
		        				);
		        				?>
		        			</a>
		        		</p>
		        	<?php endif; ?>
	            <?php endif; ?>

	            <?php if( get_field('interview_mediator') ): ?>
	            <div class="about-mediator">
	            	<h4>About the mediator</h4>

	            	<?php
	            	$mediator_profile = get_field('interview_mediator');
	            	printf(
	            		'<img src="%s" class="alignleft" />%s',
	            		'http://ma.codeart.rocks/wp-content/uploads/2015/11/10562521_10203462103055715_4662996974450321019_o.jpg',
	            		apply_filters('the_content', $mediator_profile['user_description'])
	            	);
	            	?>
	            </div>
	            <?php endif; ?>

	            <?php if( get_field('interview_transcript') && $member->can_watch_video($video_id)): ?>
				<div class="transcript">
					<a href="#" class="read-full-transcript">Read Full Transcript</a>
					<div class="full-transcript"><?php the_field('interview_transcript'); ?></div>
				</div> <!-- .transcript -->
				<?php endif; ?>
			</div> <!-- .left -->

			<div class="right">
			    
                <?php codeart_print_video_raintgs(); ?>

                <?php codeart_share_box('share-interview'); ?>

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

	$current_id = $post->ID;
	$post_parent = $post->post_parent ? $post->post_parent : $post->ID;

	$chapter_args = array(
		'post_type' 		=> 'interviews',
		'posts_per_page' 	=> -1,
		'post_parent'		=> $post_parent
	);

	$chapters = new WP_Query($chapter_args);

	if( $chapters->have_posts() ):
		printf('<div class="right-box-items interview-chapters">');
			printf('<h4 class="chapters-title">Interview Chapters</h4>');
			while( $chapters->have_posts() ): $chapters->the_post();
				printf(
					'<div class="box-item chapter">
						%s
						<a href="%s" class="%s"><h4>%s</h4></a>
					</div>',
					get_the_post_thumbnail( get_the_ID(), 'thumbnail', ['class' => 'alignleft'] ),
					get_permalink(get_the_ID()),
					$current_id == get_the_ID() ? 'active' : '',
					get_the_title()
				);
			endwhile;
		printf('</div>');
	endif;

	wp_reset_postdata();
} // End of function codeart_print_video_chapters();









function codeart_print_video_raintgs()
{
	global $post;
	$interview_video = get_field('interview_video', $post->ID);
	$is_rated = is_user_raetd_video( $interview_video ); ?>

	<div class="box rating-box interview-rating <?php echo $is_rated ? 'rated' : 'not-rated'; ?>">
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
    		$('body').find('.full-transcript').stop().slideToggle();
    		$(this).toggleClass('close');
    	});

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

	        wVideo_event.bind("end", function() {

	        }); // End Video Event
    	}

    });
    </script>
    <?php
}




genesis();

?>