<?php

/**
 *	Template Name: Member Register
 **/

require_once 'lib/mediator-member-loader.php';


$member = new Mediator_Member;
// $register_response = $member->register();



add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );




add_filter('body_class', 'codeart_register_body_classes');
function codeart_register_body_classes( $classes )
{
	$classes[] = 'codeart-register';
	return $classes;
}




remove_action('genesis_loop', 'genesis_do_loop');
add_action('genesis_loop', 'codeart_register_member_loop');
function codeart_register_member_loop()
{
	?>
	<div class="entry">

		<div class="entry-content">

			<?php
				$invited_by = get_query_var('invited_by');
				$invited_by = empty($invited_by) ? 0 : esc_attr($invited_by);
			?>

			<div class="mediator-profile-register-error">
				<span>Error placeholder</span>
			</div>

			<!--
			<form id="mediator-profile-register" action="" method="post">
				<input type="text" name="user_login" placeholder="Username" value="" />
				<input type="email" name="user_email" placeholder="Email Address" value="" />
				<input type="password" name="user_pass" placeholder="Password" value="" />

				<input type="hidden" name="invited_by" value="<?php echo $invited_by; ?>">

				<input type="text" name="first_name" placeholder="First Name" value="" />
				<input type="text" name="last_name" placeholder="Last Name" value="" />

				<input type="submit" name="mediator-profile-register-submit" value="Save" />
			</form>
		-->
		</div>
	</div>
	<?php
}


genesis();

?>