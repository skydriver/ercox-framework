<?php

/**
 *	Template Name: Topics
 **/

add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );



add_filter('body_class', 'codeart_topics_body_classes');
function codeart_topics_body_classes( $classes )
{
	$classes[] = 'codeart-topics codeart-course-topic';
	return $classes;
}




add_action('wp_head', 'codeart_add_topics_seo_link_rel');
function codeart_add_topics_seo_link_rel()
{
	$item_page = isset($_GET['item-page']) ? intval($_GET['item-page']) : 1;
	
	if($item_page < 1)
		return;
	
	if($item_page > 1):
		printf(
			'<link rel="prev" href="%s/?item-page=%d">',
			get_bloginfo('url') . '/topics',
			$item_page - 1
		);
	endif;

	printf(
		'<link rel="next" href="%s/?item-page=%d">',
		get_bloginfo('url') . '/topics',
		$item_page + 1
	);
}




remove_action('genesis_loop', 'genesis_do_loop');
add_action('genesis_loop', 'codeart_topics_loop');
function codeart_topics_loop()
{
	the_post();

	$item_page = isset($_GET['item-page']) ? intval($_GET['item-page']) : 1; ?>

	<div class="entry">
		<div class="entry-content">

			<div class="form-box">
				<form action="<?php echo get_bloginfo('url'); ?>/search/topics/" method="get">
					<input type="text" name="query" id="search" placeholder="Search topics">
					<input type="submit" id="search-button" value="Search">
				</form>
			</div> <!-- .form-box -->
			
			<?php
			// topic_most_popular_topics
			$popular_topics = get_field('topic_most_popular_topics');
			$most_popular_ids = [];
			if ($popular_topics) {
				foreach ($popular_topics as $topic) {
					$most_popular_ids[] = $topic['topic_topic'];
				}
			}

			$topic_args = array(
				'post_type' 		=> 'topics',
				'posts_per_page' 	=> 6,
				'post__in'			=> $most_popular_ids
			);
			codeart_loop_grid($topic_args, 'Most Popular', 'most-popular hide-view-more');

			$topic_args = array(
				'post_type' 		=> 'topics',
				'posts_per_page' 	=> 9,
				'paged'				=> $item_page,
				'orderby' 			=> 'post_parent title menu_order',
				'order'				=> 'ASC',
			);
			codeart_loop_grid($topic_args, 'All Videos', 'all-videos', 'videos');
			?>

		</div> <!-- .entry-content -->
	</div> <!-- .entry -->
	<?php
}

genesis();

?>