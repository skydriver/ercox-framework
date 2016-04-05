<?php

/**
 *	Template Name: Courses
 **/

add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );



add_filter('body_class', 'codeart_courses_body_classes');
function codeart_courses_body_classes( $classes )
{
	$classes[] = 'codeart-courses codeart-course-topic';
	return $classes;
}



remove_action('genesis_loop', 'genesis_do_loop');
add_action('genesis_loop', 'codeart_courses_loop');
function codeart_courses_loop()
{
	?>
	<div class="entry">
		<div class="entry-content">

			<div class="form-box">
				<form action="<?php echo get_bloginfo('url'); ?>/search/courses/" method="post">
					<input type="text" name="query" id="search" placeholder="Search Courses">
					<input type="submit" id="search-button" value="Search">
				</form>
			</div> <!-- .form-box -->

			<?php
			$interview_args = array(
				'post_type' 		=> 'courses',
				'posts_per_page' 	=> get_option('posts_per_page')
			);
			codeart_loop_grid($interview_args, 'Most Popular', 'most-popular');


			$interview_args = array(
				'post_type' 		=> 'courses',
				'posts_per_page' 	=> get_option('posts_per_page')
			);
			codeart_loop_grid($interview_args, 'All Videos', 'all-videos');
			?>

		</div> <!-- .entry-content -->
	</div> <!-- .entry -->
	<?php
}

genesis();

?>