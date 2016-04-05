<?php

/**
 * Template Name: Login and Register Template
 **/

add_filter('body_class', 'codeart_login_and_register_new_classes');
function codeart_login_and_register_new_classes($classes)
{
	$classes[] = 'codeart-login-and-register-new';
	return $classes;
}

remove_action('genesis_loop', 'genesis_do_loop');
add_action('genesis_loop', 'codeart_login_and_register_new_loop');
function codeart_login_and_register_new_loop()
{
	
}

genesis();

?>