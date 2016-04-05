<?php



/**
 *	Template Name: Mediators
 **/



add_filter('body_class', 'codeart_mediators_body_classes');
function codeart_mediators_body_classes( $classes )
{
	$classes[] = 'codeart-mediators';
	return $classes;
}


remove_action('genesis_loop', 'genesis_do_loop');
add_action('genesis_loop', 'codeart_mediators_loop');
function codeart_mediators_loop()
{
	$mediator_args = array(
		'number' => 10,
		'offset' => 0
	);

	$mediators_query = new WP_User_Query( $mediator_args );

	if( !$mediators_query )
		return;

	$mediators = $mediators_query->get_results();
	
	if( $mediators )
	{
		printf( '<div class="mediators-wrap">' );
		foreach( $mediators as $mediator )
		{
			printf(
				'<div class="mediator-item">
					<h4><a href="%s">%s</a></h4>
					<p>%s</p>
				</div>',
				codeart_get_mediators_url($mediator->user_nicename),
				$mediator->data->display_name,
				$mediator->data->user_email
			);
		}
		printf( '</div>' );
	}
	else
	{
		printf('<h4 class="no-mediators">No mediators</h4>');
	}
}


genesis();

?>