<?php

/**
 *	Template Name: Organizations
 **/

add_filter('body_class', 'codeart_organizations_body_classes');
function codeart_organizations_body_classes( $classes )
{
	$classes[] = 'codeart-organizations';
	return $classes;
}

remove_action('genesis_loop', 'genesis_do_loop');
add_action('genesis_loop', 'codeart_organizations_loop');
function codeart_organizations_loop()
{
	$organization_args = array(
		'posts_per_page' => -1,
		'post_type' => 'organization'
	);

	$organizations = new WP_Query( $organization_args );
	
	if( $organizations->have_posts() )
	{
		printf( '<div class="organizations-wrap">' );
		while( $organizations->have_posts() ): $organizations->the_post();
			global $post;
			printf(
				'<div class="organization-item">
					<a href="%s">
						<h2>%s</h2>
					</a>
				</div>',
				get_permalink($post->ID),
				get_the_title()
			);
		endwhile;
		printf( '</div>' );
	}
	else
	{
		printf('<h4 class="no-mediators">No organizations</h4>');
	}
}

genesis();

?>