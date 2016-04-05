<?php

add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );





/**
 * Proper way to enqueue scripts and styles
 *	Include wistia js-api
 */
add_action( 'wp_enqueue_scripts', 'codeart_enqueue_wistia_js_api' );
function codeart_enqueue_wistia_js_api()
{
	global $member;
	if( $member->can_watch_video() )
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
		global $member;
		var_dump( $member->get_cookie() );
		?>

		<div class="entry-content">
			<h2>Interview Title</h2>
			<span class="interview-category">Category</span>

			<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Maiores, eveniet repudiandae dolorem aliquam neque facere laboriosam placeat accusantium ipsam ratione eligendi animi unde minus rerum. Sit perspiciatis quasi facilis minus.</p>			

			<?php if( get_field('interview_video') ): ?>
				<?php if( $member->can_watch_video() ): ?>
				<div class="video wistia-video" data-id="<?php echo esc_attr(get_field('interview_video')); ?>">
	                <div class="iframe-wrap">
	                	<iframe id="mediator-iframe" src="https://fast.wistia.net/embed/iframe/<?php echo esc_attr(get_field('interview_video')); ?>?videoFoam=true" allowtransparency="true" frameborder="0" scrolling="no" class="wistia_embed" name="wistia_embed" allowfullscreen mozallowfullscreen webkitallowfullscreen oallowfullscreen msallowfullscreen width="640" height="360"></iframe>
	                </div>
	            </div>
	        	<?php else: ?>
	        		<p><a href="<?php bloginfo('url'); ?>/register/">Register</a> to watch the video</p>
	        	<?php endif; ?>
            <?php endif; ?>

			<div class="transcript">
				<h3>Transcript Title</h3>

				<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Consectetur iusto fugiat placeat nisi magnam, laudantium maiores tenetur provident non tempora magni totam nostrum possimus consequuntur delectus officia nam omnis sapiente!</p>
				<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Necessitatibus fugit excepturi nulla accusamus, eum, cum sed. Nihil cumque alias, voluptatibus non ab mollitia blanditiis iste, soluta, laboriosam quibusdam voluptate suscipit.</p>

				<a href="#" class="read-full-transcript">Read Full Transcript</a>
			</div> <!-- .transcript -->
		</div> <!-- .entry-content -->

		<div class="entry-sidebar">
			<?php
			$interview_chapters = get_field('interview_chapters');

			if($interview_chapters):
				printf( '<div class="interview-chapters">' );
				foreach($interview_chapters as $chapter):
					printf(
						'<div class="chapter">
							<a href="%s"><h3>%s</h3></a>
						</div>',
						get_permalink($chapter['chapter']->ID),
						$chapter['chapter']->post_title
					);
				endforeach;
				printf( '</div>' );
			endif; ?>
		</div> <!-- .entry-sidebar -->

	</div>
	<?php
}









/**
 *  Wistia API, activity log events on single post
 **/
add_action('genesis_after', 'codeart_single_course_footer', 999);
function codeart_single_course_footer()
{
	?>
    <script type="text/javascript">
    jQuery(document).ready(function($) {

    	if( $('body').find("#mediator-iframe").length )
    	{
    		wVideo_event = $('body').find("#mediator-iframe")[0].wistiaApi;

	    	<?php
	    	global $post;
	    	$video_id = get_field('interview_video');
	    	?>

	    	wVideo_event.bind("play", function() {
	    		// alert('Please register ...');
	        }); // End Video Event

	        wVideo_event.bind("end", function() {

	        	var pid 		= '<?php the_ID(); ?>';
	        	var type 		= 'finished';
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
    	}

    });
    </script>
    <?php
}




genesis();

?>