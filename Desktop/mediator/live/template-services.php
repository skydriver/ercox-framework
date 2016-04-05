<?php

/**
 *	Template Name: Services
 **/

add_filter('body_class', 'codeart_services_body_classes');
function codeart_services_body_classes( $classes )
{
	$classes[] = 'codeart-services';
	return $classes;
}

remove_action('genesis_loop', 'genesis_do_loop');
add_action('genesis_loop', 'codeart_services_loop');
function codeart_services_loop()
{
	$service_args = array(
		'posts_per_page' => -1,
		'post_type' => 'service'
	);

	$services = new WP_Query( $service_args );

	if( $services->have_posts() )
	{
		printf( '<div class="services-wrap">' );
		while( $services->have_posts() ): $services->the_post();
			global $post;
			printf(
				'<div class="service-item">
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
		printf('<h4 class="no-mediators">No services</h4>');
	}
}

genesis();

?>