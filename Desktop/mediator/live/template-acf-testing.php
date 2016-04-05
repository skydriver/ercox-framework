<?php

/**
 *	Template Name: ACF Testing
 **/

acf_form_head();

remove_action('genesis_loop', 'genesis_do_loop');
add_action('genesis_loop', 'codeart_acf_testing');
function codeart_acf_testing()
{
	the_post();

	acf_form(['post_id' => get_the_ID()]);
}

genesis();

?>