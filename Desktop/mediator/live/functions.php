<?php

// Include Genesis Framework Engine.
if( file_exists( get_template_directory() . '/lib/init.php' ) )
	@require_once( get_template_directory() . '/lib/init.php' );
else
	wp_die( 'Genesis Framework Missed!' );

define( 'CHILD_THEME_VERSION', '1.0.0' );
add_filter( 'widget_text', 'do_shortcode' );
update_option( 'image_default_link_type', 'none' );


define('LINKEDIN_CLIENT_ID', '771i7nupune81p');
define('LINKEDIN_CLIENT_SECRET', 'KPRV8LxTjhUoB6J9');



// Mediator avatar
// add_image_size( 'mediator-avatar', 310, 270, true );



require_once('uuid.php');



add_action('genesis_before_header', 'codeart_add_internet_explorer_bar');
function codeart_add_internet_explorer_bar()
{
	printf(
		'<div class="old-ie-bar"><p>%s</p></div>',
		'Please upgrade your browser.'
	);
}





// Popular Mediator avatar
add_image_size( 'popular-mediator-avatar', 300, 300, true );
add_image_size( 'topics-thumbnail', 600, 400, true );



require_once('lib/mediator-member-loader.php');
global $member;
$member = new Mediator_Member();





add_action('wp', 'codeart_init_author_member');
function codeart_init_author_member()
{
	global $author_member;
	$author_member = new Mediator_Member();
}





/**
 * Method to debug
 * 
 * @param mixed $str Var for debug
 * @param bool $tf True for hide from front end
 * */
function _debug( $str, $tf = false )
{
	if( $tf ) echo '<!--';
	echo '<pre>' . print_r( $str, true ) . '</pre>';
	if( $tf ) echo '-->';
}





/***************************************************
*** SET DEFAULT IMAGE IF POST HAS NO FEATURED IMAGE
****************************************************/
add_filter( 'post_thumbnail_html', 'ca_replace_no_featured_image', 99, 5 );
function ca_replace_no_featured_image( $html, $post_id, $post_thumbnail_id, $size, $attr )
{
	if ( ! class_exists( 'Acf' ) )
		return $html;

	if( ! has_post_thumbnail( $post_id ) )
	{
		$def_post_image_obj = get_field( 'option_default_thumbnail', 'option' );
		$html = wp_get_attachment_image( $def_post_image_obj['ID'], $size, false, $attr );
	}
	return $html;
}
/******************************************************
*** END SET DEFAULT IMAGE IF POST HAS NO FEATURED IMAGE
*******************************************************/




/***********************
* Register widget areas
************************/
genesis_register_sidebar( array(
	'id'            => 'footer-top',
	'name'          => __( 'Home top', 'genesis' ),
	'description'   => __( 'This is a footer top widget area', 'genesis' ),
) );

genesis_register_sidebar( array(
	'id'            => 'footer',
	'name'          => __( 'Footer', 'genesis' ),
	'description'   => __( 'This is a footer widget area', 'genesis' ),
) );
/****************************
* END Register widget areas
*****************************/







/***********************
*** Replace Footer
***********************/
remove_action( 'genesis_footer', 'genesis_do_footer' );
add_action( 'genesis_footer', 'ca_footer');
function ca_footer()
{
	genesis_widget_area( 'footer' );
}
/***********************
*** END Replace Footer
***********************/






/***********************
*** Register scripts
***********************/
if( function_exists('acf_add_options_page') )
{
	acf_add_options_page();	
}



/**
 * Method to display the footer top widget
 * */
// add_action('genesis_before_footer', 'codeart_add_footer_top_area');
function codeart_add_footer_top_area()
{
	genesis_widget_area(
		'footer-top',
		array(
			'before' => '<div class="footer-top-area widget-area footer-sidebar"><div class="wrap">',
			'after' => '</div></div>'
		)
	);
} // End of codeart_add_footer_top_area();







/**
 * Add before footer section
 * */
add_action('genesis_before_footer', 'codeart_add_before_footer_section', 10);
function codeart_add_before_footer_section()
{
	?>
	<div class="before-footer">
		<div class="wrap">
			<a href="<?php bloginfo('url'); ?>" class="footer-logo"></a>
			<div class="social-icons">
				<h4>Find us online:</h4>
				<a href="#" class="fb">
				    <?php echo file_get_contents(get_stylesheet_directory_uri() . "/images/icon-facebook.svg"); ?>
				</a>
				<a href="#" class="tw">
				    <?php echo file_get_contents(get_stylesheet_directory_uri() . "/images/icon-twitter.svg"); ?>
				</a>
				<a href="#" class="gp">
				    <?php echo file_get_contents(get_stylesheet_directory_uri() . "/images/icon-gplus.svg"); ?>
				</a>
				<a href="#" class="li">
				    <?php echo file_get_contents(get_stylesheet_directory_uri() . "/images/icon-linkedin.svg"); ?>
				</a>
				<a href="#" class="yb">
				    <?php echo file_get_contents(get_stylesheet_directory_uri() . "/images/icon-youtube-small.svg"); ?>
				</a>
				<a href="#" class="ct">
				    <?php echo file_get_contents(get_stylesheet_directory_uri() . "/images/icon-email.svg"); ?>
				</a>
			</div>
		</div>
	</div>
	<?php
}








add_action('init', 'codeart_mediator_base');
function codeart_mediator_base()
{
    global $wp_rewrite;
    // _debug($wp_rewrite);
    $author_slug = 'network/mediators';
    $wp_rewrite->author_base = $author_slug;
    flush_rewrite_rules();
}









function codeart_get_filtered_content_for_meta($description = '')
{
	$description = strip_tags($description);
	$description = strip_shortcodes($description);
	$description = $description ? substr($description, 0, 200) . '...' : get_bloginfo('name');
	$description = apply_filters('the_title', $description);
	return $description;
}

add_action('wp_head', 'codeart_add_og_and_twitter_meta_tags');
function codeart_add_og_and_twitter_meta_tags()
{
	$title 			= '';
	$description 	= '';
	$type 			= 'website';
	$url 			= '';
	$image 			= '';
	$site_name 		= get_bloginfo('name');
	$brand 			= get_bloginfo('name');

	$title = is_singular() ? get_the_title() : get_bloginfo('title');
	$title = apply_filters('the_title', $title);

	global $post;

	$description = (is_singular() && $post) ? $post->post_content : get_bloginfo('description');
	$description = codeart_get_filtered_content_for_meta($description);

	$image = get_stylesheet_directory_uri() . '/images/fb2.jpg';

	if (is_singular('topics')) {
		$type = 'video.other';
	}

	if (is_author()) {
		$type = 'profile';
		global $author, $member, $wpdb;
		$image = $member->print_avatar($author, 'large', true);
		$current_profile = get_userdata($author);
		$title = $current_profile->display_name;
		$description = get_user_meta( $author, 'description', true );
		$description = codeart_get_filtered_content_for_meta($description);
		// var_dump($current_profile);
	}

	if (is_singular()) {
		$thumbnail_id = get_post_thumbnail_id($post->ID);
		$image = wp_get_attachment_image_src($thumbnail_id, 'large');
		$image = $image[0];
	}

	$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

	?>
	
	<meta xmlns:og="http://opengraphprotocol.org/schema/" property="og:title" content="<?php echo $title; ?>" />
	<meta xmlns:og="http://opengraphprotocol.org/schema/" property="og:description" content="<?php echo $description; ?>" />
	<meta xmlns:og="http://opengraphprotocol.org/schema/" property="og:type" content="<?php echo $type; ?>" />
	<meta xmlns:og="http://opengraphprotocol.org/schema/" property="og:url" content="<?php echo $url; ?>" />
	<meta xmlns:og="http://opengraphprotocol.org/schema/" property="og:image" content="<?php echo $image; ?>" />
	<meta xmlns:og="http://opengraphprotocol.org/schema/" property="og:site_name" content="<?php echo $site_name; ?>" />
	<meta xmlns:og="http://opengraphprotocol.org/schema/" property="og:brand" content="<?php echo $brand; ?>" />

	<meta name="twitter:card" content="summary_large_image" />
	<meta name="twitter:site" content="@mediatoracademy" />
	<meta name="twitter:creator" content="@mediatoracademy" />
	<meta name="twitter:title" content="<?php echo $title; ?>" />
	<meta name="twitter:description" content="<?php echo $description; ?>" />
	<meta name="twitter:image" content="<?php echo $image; ?>" />
	<?php
}












/**
 * Method to display the popular videos
 **/
function popular_videos()
{
	printf('<div class="video-items-wrap">');
	for($i=0; $i<8; $i++):
		$cls = [];
		$cls[] = $i % 2 == 0 ? 'even' : 'odd';
		$cls[] = $i % 3 == 0 ? 'three' : 'non-three';
		$cls[] = $i % 4 == 0 ? 'four' : 'non-four';
		?>
		<div class="video-item <?php echo implode(' ', $cls); ?>">
			<a href="#">
				<img src="http://ma.codeart.rocks/wp-content/uploads/2015/10/mediation.jpg" alt="">
			</a>
		</div>
	<?php
	endfor;
	printf('</div>');
} // End of function popular_videos();






/**
 * Method to include ajaxurl on front-end
 **/
add_action('wp_head','codeart_ajaxurl');
function codeart_ajaxurl()
{
	?>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<meta name="author" content="codeart.mk" />

	<script type="text/javascript">
		var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
		var is_home = <?php echo (is_home() || is_front_page()) ? 'true' : 'false'; ?>;
	</script>
	<?php
} // End of function codeart_ajaxurl();






/**
 * Functions to upgrade the profile (Ajax, Stripe)
 */
add_action( 'wp_ajax_upgrade_profile', 'codeart_upgrade_profile' );
add_action( 'wp_ajax_nopriv_upgrade_profile', 'codeart_upgrade_profile' );
function codeart_upgrade_profile()
{
	global $wpdb;

	$mediatorStripe = new MediatorStripe;

	$token = isset($_POST['token']) ? $_POST['token'] : false;
	$stripe_plan_id = isset($_POST['stripe_plan']) ? $_POST['stripe_plan'] : false;

	$json_response = array();

	if(empty($stripe_plan_id))
	{
		$json_response['status'] = false;
		$json_response['message'] = 'Empty plan id';
		echo json_encode($json_response);
		wp_die();
	}

	$member = new Mediator_Member;
	
	if( !$token )
	{
		$json_response['status'] = false;
		$json_response['message'] = 'Empty token';
		echo json_encode($json_response);
		wp_die();
	}
	else
	{
		$current_user = wp_get_current_user();
		$response = $mediatorStripe->create_customer($token, $current_user->user_email);
		if( is_wp_error($response) )
		{
			$json_response['status'] = false;
			$json_response['message'] = 'Stripe response';
			echo json_encode($json_response);
			wp_die();
		}
		else
		{
			$user_id = get_current_user_id();
			$user_data = $member::get_member_data($user_id);
			$customer_id = $user_data->customer_id;
			if( empty($customer_id) )
			{
				if($member::add_customer_id($response->id, $user_id))
				{
					$customer_id = $response->id;
				}
				else
				{
					$json_response['status'] = false;
					$json_response['message'] = 'Database error -> ' . $response->id . ' -> ' . $user_id;
					echo json_encode($json_response);
					wp_die();
				}
			}

			if( $stripe_subscription = $mediatorStripe->add_stripe_subscription( $stripe_plan_id, $customer_id ) )
			{
				if( !$member::add_access_flag($stripe_plan_id, $user_id) )
				{
					$json_response['status'] = false;
					$json_response['message'] = 'Database error (changing flag) ';

					echo json_encode($json_response);
					wp_die();
				}
				
				$upgraded_user = get_user_by('id', $user_id);
				$display_name = urlencode($upgraded_user->display_name);
				file_get_contents(get_stylesheet_directory_uri() . '/aweber-upgrade-to-premium.php?name=' . $display_name . '&email=' . $upgraded_user->user_email);

				wp_update_user( ['ID' => $user_id, 'role' => 'premium_member'] );

				$json_response['status'] = true;
				$json_response['message'] = 'Success';

				echo json_encode($json_response);

				wp_die();
			}
			else
			{
				$json_response['status'] = false;
				$json_response['message'] = 'Subscription error';
				echo json_encode($json_response);
				wp_die();
			}

		}
	}

	wp_die();
} // End of function codeart_upgrade_profile();











/**
 * Method to display the error message
 * 
 * @param string $message Error message
 **/
function codeart_error_message( $message )
{
	printf(
		'<div class="error-message">
			<p>%s</p>
		</div>',
		$message
	);
} // End of function codeart_error_message();










add_action('template_redirect', 'codeart_restrict_template_access');
function codeart_restrict_template_access()
{
	global $member;
	if(is_page_template('template-upgrade.php') && $member->is_premium_member())
	{
		$payment_details = get_field('payment_details_url', 'option');
		wp_redirect( $payment_details, 301 );
		exit;
	}

	if(
		(	is_page_template('template-profile-edit.php') || 
			is_page_template('template-payment-details.php') ||
			is_page_template('template-upgrade.php')
		) && 
		!is_user_logged_in()
	)
	{
		$homepage = get_bloginfo('url');
		wp_redirect( $homepage, 301 );
		exit;
	}
}






add_action('template_redirect', 'codeart_send_email_invitations_for_register');
function codeart_send_email_invitations_for_register()
{
	if( isset($_POST['send-infitations']) )
	{
		$emails = $_POST['emails'];

		// $m = new Mediator_Member;
		// $hash = $m->encrypt_ID(get_current_user_id());
		global $member;
		$hash = $member->get('uuid', false);

		global $current_user, $member;
		get_currentuserinfo();

		$uname = $current_user->user_firstname . ' ' . $current_user->user_lastname;
		$uemail = $current_user->user_email;

		$message = sprintf(
			'Invite by %s. Please register to %s by clicking the following link: %s',
			$uname,
			get_bloginfo('name'),
			get_bloginfo('url') . '/?ref=' . $hash
		);

		$headers .= 'From: ' . $uname . ' <' . $uemail . '>' . "\r\n";
		$headers .= 'BCC: '. implode(",", $emails) . "\r\n";

		$subject = sprintf(
			'Invitation by %s',
			$uname
		);

		if( wp_mail( null, $subject, $message, $headers ) )
		{
			wp_redirect( get_bloginfo('home') . '/profile/edit/', 301 );
			exit;
		}
	}
} // End of codeart_send_email_invitations_for_register();








add_action( 'wp_ajax_ajax_ref_invite', 'codeart_ajax_ref_invite_callback' );
add_action( 'wp_ajax_nopriv_ajax_ref_invite', 'codeart_ajax_ref_invite_callback' );

function codeart_ajax_ref_invite_callback()
{
	$emails = isset($_POST['emails']) ? $_POST['emails'] : false;
	if (empty($emails)) {
		echo json_encode(['status' => false, 'message' => 'Empty emails']);
		wp_die();
	}


	try
	{
		global $member;
		$hash = $member->get('uuid', false);

		global $current_user, $member;
		get_currentuserinfo();

		$uname = $current_user->user_firstname . ' ' . $current_user->user_lastname;
		$uemail = $current_user->user_email;

		$message = sprintf(
			'Invite by %s. Please register to %s by clicking the following link: %s',
			$uname,
			get_bloginfo('name'),
			get_bloginfo('url') . '/?ref=' . $hash
		);

		$invited_emails = codeart_get_invited_emails( get_current_user_id() );
		$emails_to_invite = empty($invited_emails) ? $emails : [];

		$already_invited_emails = [];

		foreach ($emails as $mail) {
			$ok = true;

			if ($invited_emails) {
				foreach ($invited_emails as $inemail) {
					if ($mail == $inemail->email) {
						$already_invited_emails[] = $mail;
						$ok = false;
						break;
					}
				}
			}
			
			if ($ok) {
				$emails_to_invite[] = $mail;
			}
		}

		if ($emails_to_invite) {
			codeart_add_invited_emails( get_current_user_id(), $emails_to_invite );

			$headers .= 'From: ' . $uname . ' <' . $uemail . '>' . "\r\n";
			$headers .= 'BCC: '. implode(",", $emails) . "\r\n";

			$subject = sprintf(
				'Invitation by %s',
				$uname
			);
/*
Hi there,

First Last wants you to try Mediator Academy.

On Mediator Academy you can learn from experienced mediators and thought leaders from around the world. With over 1500 videos, you can watch some of the best in the field in your own time and at your own pace.

Plus, you’ll be able to watch up to 10 videos for free if you get started through this email.

<ACCEPT INVITE>

Thanks

The Mediator Academy Team
*/

			/*
			if( !wp_mail( null, $subject, $message, $headers ) ) {
				echo json_encode(['status' => false, 'message' => 'fail']);
				wp_die();
			}
			*/
		}

		echo json_encode(['status' => true, 'message' => 'Success']);
		wp_die();
	}
	catch(Exception $ex)
	{
		echo json_encode(['status' => false, 'message' => $ex->getMessage()]);
		wp_die();
	}


	wp_die();
}






function codeart_get_invited_emails( $uid )
{
	global $wpdb;

	if (empty($uid)) {
		return [];
	}

	$sql = "SELECT email FROM mwdpb_mediator_members_invited_emails WHERE uid = " . intval($uid);
	return $wpdb->get_results($sql);
}



/**
 * Add invited emails
 * @param int $uid User ID
 * @param array $emails Already invited emails
 * @return bool
 **/
function codeart_add_invited_emails( $uid, $emails )
{
	if (!is_array($emails) || empty($emails)) {
		return false;
	}

	// $sql = 'INSERT INTO `mwdpb_mediator_members_invited_emails` (uid, email) VALUES ';
	$sql = '';
	$values = [];

	foreach ($emails as $email) {
		$values[] = sprintf(
			" (%d, '%s')",
			$uid,
			$email
		);
	}

	$sql .= implode(', ', $values);

	global $wpdb;
	return $wpdb->query($sql);
} // End of function codeart_add_invited_emails()







add_action( 'wp_ajax_interveiw_log', 'codeart_interveiw_log_callback' );
add_action( 'wp_ajax_nopriv_interveiw_log', 'codeart_interveiw_log_callback' );
function codeart_interveiw_log_callback()
{
	global $wpdb;

	$user_id = get_current_user_id();

	$pid 		= isset($_POST['pid']) ? intval($_POST['pid']) : 0;
	$type 		= isset($_POST['type']) ? $_POST['type'] : '';
	$vid 		= isset($_POST['vid']) ? $_POST['vid'] : '';
	$duration 	= isset($_POST['duration']) ? intval($_POST['duration']) : 0;

	if( empty($pid) || empty($type) || empty($vid) || empty($duration) )
		wp_die();

	if( empty($user_id) )
	{
		global $member;
		$member->set_cookie([
			'pid' 		=> $pid,
			'type' 		=> $type,
			'vid' 		=> $vid,
			'duration' 	=> $duration
		]);
		wp_die();
	}

	$new_log = $wpdb->insert( 
		$wpdb->prefix . 'mediator_members_logs', 
		array( 
			'uid' 		=> $user_id, 
			'pid' 		=> $pid,
			'vid' 		=> $vid,
			'type' 		=> $type,
			'duration' 	=> $duration
		), 
		array( 
			'%d',
			'%d',
			'%s',
			'%s',
			'%d'
		) 
	);

	wp_die();
} // End of codeart_interveiw_log_callback();







/**
 * Proper way to enqueue scripts and styles
 */
add_action( 'wp_enqueue_scripts', 'codeart_add_jquery' );
function codeart_add_jquery()
{
	wp_enqueue_script( 'jquery' );
} // End of codeart_add_jquery();








add_action( 'genesis_header', 'codeart_magic_line');
function codeart_magic_line()
{
    printf('<div class="ca_magic_line"></div>');
} // End of codeart_magic_line();







add_action( 'genesis_header', 'codeart_custom_logo', 5 );
function codeart_custom_logo()
{
    ?>
        <div class="custom_header_logo">
            	<a href="<?php bloginfo('url'); ?>"><?php echo file_get_contents(get_stylesheet_directory_uri() . "/images/logo.svg"); ?></a>
        </div>
    <?php
} // End of codeart_custom_logo();






add_action( 'wp_enqueue_scripts', 'ca_main_js_file' );
function ca_main_js_file()
{
    wp_enqueue_script(
		'main_js',
		get_stylesheet_directory_uri() . '/js/main.js',
		array('jquery'),
		CHILD_THEME_VERSION,
		true
	);
    
    wp_enqueue_script(
		'ca_custom_scrollbar',
		get_stylesheet_directory_uri() . '/scripts/custom-scroll/jquery.mCustomScrollbar.min.js',
		array('jquery'),
		CHILD_THEME_VERSION,
		true
	);
    
    wp_register_style( 'custom_scroll_css', get_stylesheet_directory_uri() . '/scripts/custom-scroll/jquery.mCustomScrollbar.css' );
    wp_enqueue_style( 'custom_scroll_css' );
    
} // End of function ca_main_js_file();








/**
 * Function to login with emails
 **/
add_action( 'wp_authenticate', 'codeart_login_with_email_address' );
function codeart_login_with_email_address( &$username )
{
	$user = get_user_by( 'email', $username );

	if ( !empty( $user->user_login ) )
		$username = $user->user_login;
	
	return $username;
} // End of function codeart_login_with_email_address();











class CodeArtLinkedIn
{
	protected $apiUrl = 'https://www.linkedin.com/uas/oauth2/authorization';

	protected $clientId;
	protected $secretId;

	public function __construct($clientId, $secretId)
	{
		$this->clientId = $clientId;
		$this->secretId = $secretId;
	} // End of constructor;

	protected function getState()
	{
		return substr(md5(rand()), 0, 10);
	} // End of getState;

	protected function getScope()
	{
		return 'r_basicprofile';
	} // End of function getScope;

	public function redirectUri()
	{
		return urlencode('http://ma.codeart.rocks/wp-content/themes/mediator-academy/linkedin-sample.php');
	}

	protected function getResponseType()
	{
		return 'code';
	} // end of getResponseType;

	public function auth()
	{
		$restUrl = sprintf(
			'%s?response_type=%s&client_id=%s&redirect_uri=%s&state=%s&scope=%s',
			$this->apiUrl,
			$this->getResponseType(),
			$this->clientId,
			$this->redirectUri(),
			$this->getState(),
			$this->getScope()
		);

		return $restUrl;
	}



	public function auth_second($code)
	{
		$params = array(
			'grant_type' => 'authorization_code',
			'client_id' => $this->clientId,
			'client_secret' => $this->secretId,
			'code' => $code,
			'redirect_uri' => $this->redirectUri(),
		);

		// Access Token request
		$url = 'https://www.linkedin.com/uas/oauth2/accessToken?' . http_build_query($params);

		// Tell streams to make a POST request
		$context = stream_context_create(
			array('http' => 
				array('method' => 'POST')
			)
		);

		// Retrieve access token information
		$response = file_get_contents($url, false, $context);

		return $response;
	} // auth_second

} // End of class CodeArtLinkedIn;









remove_action( 'genesis_site_title', 'genesis_seo_site_title' );
remove_action( 'genesis_site_description', 'genesis_seo_site_description' );






add_action('genesis_before', 'codeart_video_notification');
function codeart_video_notification()
{
	global $member;

	if( $member->is_premium_member() )
		return;

	$watched_videos = $member->get_watched_video_count();

	if ($watched_videos < 1 )
		return ?>
	<div class="videos-left">
		<div class="wrap">
			<?php
			if( (int)$watched_videos < $member->allowed_videos() )
			{
				if( is_user_logged_in() ): ?>
                <p>You have <span class="count"><?php echo $member->allowed_videos() - $watched_videos ?> videos</span> left to watch! <a href="<?php bloginfo('url'); ?>/plans/" class="">Upgrade</a> to Watch more</p>
                <?php else: ?>
                <p>You have <span class="count"><?php echo $member->allowed_videos() - $watched_videos ?> videos</span> left to watch! <a href="#" class="register-button">Sign up</a> to Watch more</p>
                <?php endif;
			}
			else
			{
				if( is_user_logged_in() )
				{
					printf(
						'<p>Please <a href="%s" class="register-button upgrade-watch">upgrade</a> to watch more videos...</p>',
						get_field('upgrade_page', 'option')
					);
				}
				else
				{
					printf(
						'<p>Please <a href="#" class="register-button">register</a> to watch more videos...</p>'
					);
				}
				
			}
			?>
		</div>
	</div>
	<div class="videos-left-holder"></div>
	<?php
} // End of function codeart_video_notification();








/**
 * Method to display the register popup
 */
add_action('genesis_after', 'codeart_add_register_popup');
function codeart_add_register_popup()
{
	if( is_user_logged_in() )
		return; ?>
	
	<div class="register-popup popup-login-register-main">
		<div class="close-popup"></div>
		<div class="overlay"></div>

		<div class="popup-wrap">
            <div class="popup_logo"></div>
            <div class="mini_nav">
                <a href="#" class="login_form" data-element="login">Log In</a>
                <a href="#" class="register_form" data-element="register">Sign Up</a>
            </div>
			<div class="entry">
				<div class="left login">
					<form id="login" action="">
						<p class="status"></p>
						<!--<a href="<?php echo get_stylesheet_directory_uri(); ?>/linkedin-login.php" class="linkedin-sync">Login with LinkedIn</a>-->
						<a href="<?php bloginfo('url'); ?>/linkedin/signin.php" class="linkedin-sync">Login with LinkedIn</a>
						<div class="text-div"></div>
						<input type="text" id="username" placeholder="Enter your email">
						<input type="password" id="password" placeholder="Enter your password">
						<input type="submit" value="Log in">
	                    <div class="checkbox-holder">
	                        <input type="checkbox" id="remember_login" name="remember_login">
	                        <label for="remember_login">Remember me</label>
	                    </div>
	                    <?php wp_nonce_field( 'ajax-login-nonce', 'security' ); ?>
	                    <a href="#" class="forgot_password">Forgot Password?</a>
                    </form>
				</div> <!-- .left -->
				
				<div class="left forgot_password">
					<form id="reset-password" action="">
						<p>Enter your email address below. We'll look for your account and send you a password reset email.</p>
						<input type="email" placeholder="Enter your email">
						<input type="submit" value="Reset Password">
					</form>
				</div>

				<div class="right">

					<p class="register-error">Error placeholder</p>

					<form id="mediator-profile-register" action="" method="post">
				        <!--<a href="<?php echo get_stylesheet_directory_uri(); ?>/linkedin-sample.php" class="linkedin-sync">Sign Up with LinkedIn</a>-->
				        <a href="<?php bloginfo('url'); ?>/linkedin/signup.php" class="linkedin-sync">Sign Up with LinkedIn</a>
				        <div class="text-div"></div>
						<input type="text" name="first_name" placeholder="First Name" value="" />
						<input type="text" name="last_name" placeholder="Last Name" value="" />
						<input type="email" name="user_email" placeholder="Email Address" value="" />

						<?php
						/*
						$invited_by = '';
						if( isset($_GET['ref']) )
							$invited_by = $_GET['ref'];

						if( $_COOKIE['invited_by'] )
							$invited_by = $_COOKIE['invited_by'];
							*/

						codeart_get_watched_videos();

						global $member;
						$invited_by = $member->codeart_get_invited_by_hash();

						/*
						$invited_by = get_query_var('invited_by');
						$invited_by = empty($invited_by) ? 0 : esc_attr($invited_by);
						*/
						if($invited_by): ?>
						<input type="hidden" name="invited_by" value="<?php echo esc_attr($invited_by); ?>" />
						<?php endif; ?>
						
						<div id="g-recaptcha-1"></div>

						<input type="submit" name="mediator-profile-register-submit" value="Sign Up" />
						<div class="bottom">
                            <p>By signing up to create an account I accept our <a href="#">Terms of Use</a> and <a href="#">Privacy Policy</a>.</p>
						</div>
					</form>
				</div> <!-- .right -->
			</div> <!-- .entry -->

		</div> <!-- .popup-wrap -->

	</div> <!-- .register-popup-->
	<?php
}







add_action('wp', 'codeart_set_register_ref_cookie');
function codeart_set_register_ref_cookie()
{
	// var_dump( empty($_COOKIE['invited_by']) );
	if( empty($_COOKIE['invited_by']) && isset($_GET['ref']) && !is_user_logged_in() )
	{
		// setcookie('invited_by', '1546058f-5a25-4334-85ae-e68f2a44bbaf', time() + (86400 * 30), "/");
		// var_dump( setcookie('invited_by', $_GET['invited_by'], time()+7200, '/') );
	}
}





// add_action('genesis_after_header', 'codeart_dsadsadas');
function codeart_dsadsadas()
{
	global $wp;
	$current_url = add_query_arg( $wp->query_string, '', home_url( $wp->request ) );
	$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	var_dump($actual_link);
}




/********************************************************************************
	Functions to get video thumbnail from wistia
	@param $video_ID String The video ID
	@param $size Array(Width x Height)
	@return string Video thumbnail

	@see - http://wistia.com/doc/working-with-images
***********************************************************************************/
function codeart_get_video_thumbnail_from_vistia( $video_ID, $size = array() )
{
	if( !$video_ID )
		return;

	$base_url = 'https://fast.wistia.net/oembed?url=http%3A//home.wistia.com/medias/' . esc_attr($video_ID);
	$video_json = @file_get_contents($base_url);
	$video_data = json_decode($video_json);

	$thumbnail = parse_url( $video_data->thumbnail_url );
	$thumbnail = $thumbnail['scheme'] . '://' . $thumbnail['host'] . $thumbnail['path'];

	if( $size )
		$thumbnail .= '?image_crop_resized=' . $size[0] . 'x' . $size[1];

	return $thumbnail;
} // End of codeart_get_video_thumbnail_from_vistia();





add_action('wp_head', 'codeart_register_scripts');
function codeart_register_scripts()
{
	?>
	<script type="text/javascript">

	function setCookie(cvalue) {
		var d = new Date();
		d.setTime(d.getTime() + (30*1000*20));
		var expires = "expires="+d.toUTCString();
		document.cookie = "ma_ref=" + cvalue + "; " + expires + "; path=/";
	}

	jQuery(document).ready(function($) {

		var register_form    = $('body').find('form#mediator-profile-register');
        var custom_loader    = $('body').find('.ca_custom_loader .dot');
        var body             = $('body');

        register_form.find('input').on('click', function() {
        	$(this).removeClass('validation-error')
        });

		register_form.on('submit', function(e) {
            setCookie(location.href);

            $('body').addClass('register-animation');
            
            var timer = 2*1000;
            var custom_loader = $('div.ca_custom_loader');

            function animateTree(){
                custom_loader.addClass('go').delay(timer).queue(function(i){
                    $(this).removeClass('go');
                    i();
                });
            }
			
			var first_name 	= register_form.find('[name="first_name"]');
			var last_name 	= register_form.find('[name="last_name"]');
			var user_email 	= register_form.find('[name="user_email"]');
			var recaptcha 	= register_form.find('[name="g-recaptcha-response"]');
			var watched_ids = register_form.find('[name="transfer-watched-videos"]');

			var invited_by 	= register_form.find('[name="invited_by"]');
			var invited_by_val = '';
			if(invited_by && invited_by != null && invited_by != 'undefined' && invited_by.length > 0)
				invited_by_val = invited_by.val();

			var error = false;

			if( first_name.val().length < 2 )
			{
				first_name.addClass('validation-error');
				error = true;
			}

			if( last_name.val().length < 2 )
			{
				last_name.addClass('validation-error');
				error = true;
			}

			if( !validateEmail(user_email.val()) )
			{
				user_email.addClass('validation-error');
				error = true;
			}

			if( error ){
                $('body').removeClass('register-animation');
                return false;
            }
            
            custom_loader.fadeIn();

            animateTree();
            
            setInterval(function(){
                animateTree();
            }, timer * 2.2);
                
			var data = {
				'action': 'ajax_register',
				'first_name': first_name.val(),
				'last_name': last_name.val(),
				'user_email': user_email.val(),
				'invited_by': invited_by_val,
				'recaptcha': recaptcha.val(),
				'watched_ids': watched_ids.val()
			};

			jQuery.post(ajaxurl, data, function(response) {
				var obj = jQuery.parseJSON(response);
				console.log(obj);

				if( obj.status == true )
				{
					// window.location.href = '<?php the_field("thank_you_page", "option"); ?>';
                    
                    //DELETE ME 
                    
                    // location.reload();
                    location.href = "<?php the_field('thank_you_page', 'option'); ?>";
				}
				else
				{
					$('body').find('.register-error').text(obj.message);
                    $('body').find('div.register-popup').addClass('form_error');
					custom_loader.fadeOut();
                    
                    if( body.hasClass('linkedin-error') === true ){
                        $('body').find('.register-error').slideDown();
                    }
				}

                $('body').removeClass('register-animation');
			});

			return false;
		});

	});
	</script>
	<?php
}








/**
 * Function to register new members (mediators)
 **/
add_action( 'wp_ajax_ajax_register', 'codeart_ajax_register_callback' );
add_action( 'wp_ajax_nopriv_ajax_register', 'codeart_ajax_register_callback' );
function codeart_ajax_register_callback()
{
	global $wpdb, $member;

	if (codeart_verify_recaptcha( $_POST['recaptcha'] ) === false) {
		echo json_encode(['status' => false, 'message' => 'No spam']);
		wp_die();
	}

	$first_name 	= isset($_POST['first_name']) 	? $_POST['first_name'] 	: '';
	$last_name 		= isset($_POST['last_name']) 	? $_POST['last_name'] 	: '';
	$user_email 	= isset($_POST['user_email']) 	? $_POST['user_email'] 	: '';
	$invited_by 	= isset($_POST['invited_by']) 	? $_POST['invited_by'] 	: '';
	$watched_ids 	= isset($_POST['watched_ids']) 	? $_POST['watched_ids'] : '';

	if( email_exists($user_email) )
	{
		echo json_encode(['status' => false, 'message' => 'Email exists']);
		wp_die();
	}

	$emails = !empty($watched_ids) ? explode(',', $watched_ids) : [];
	
	if ($member->register($first_name, $last_name, $user_email, $invited_by, $emails)) {
		echo json_encode(['status' => true, 'message' => 'Success']);
	} else {
		echo json_encode(['status' => false, 'message' => 'false']);
	}

	wp_die();
} // End of codeart_ajax_register_callback();









add_action('init', 'codeart_register_post_type_topics' );
function codeart_register_post_type_topics()
{
	register_post_type(
		'topics', 
		array(
			'label' 				=> __('Topics'),
			'description' 			=> 'Used to display topics',
			'capability_type' 		=> 'post',
			'show_ui' 				=> true,
			'public' 				=> true,
			'publicly_queryable' 	=> true,
			'query_var'          	=> true,
			'hierarchical'			=> true,
			'rewrite' 				=> array(
											'slug' => 'topics/%topic_cat%',
											'with_front' => false
										),
			'supports'           	=> array( 'title', 'editor', 'thumbnail', 'page-attributes', 'custom-fields', 'revisions', 'comments', 'post-formats' ),
			'taxonomies' 			=> array('topic_cat')
		)
	);

	register_taxonomy(
		'topic_cat',
		array(
			0 => 'topics',
		),
		array(
			'hierarchical' 	=> true,
			'query_var'		=> true,
			'show_admin_column' => true,
			'label' 		=> __('Topic Categories'),
			'rewrite' 		=> array(
				'hierarchical' 	=> true,
				'slug' 			=> 'topics_cat'
			),
		)
	);





	register_taxonomy(
		'service',
		array(
			0 => 'topics',
		),
		array(
			'label' 	=> __('Services'),
			'rewrite' 	=> array(
				'slug' 	=> 'service'
			),
		)
	);

	register_taxonomy(
		'specialism',
		array(
			0 => 'topics',
		),
		array(
			'hierarchical' 		=> true,
			'show_admin_column' => true,
			'label' 			=> __('Specialisms'),
			'rewrite' 			=> array(
				'slug' 			=> 'specialism'
			),
		)
	);


}






add_filter('post_type_link', 'codeart_topic_create_hierarchy_permalink', 1, 3);
function codeart_topic_create_hierarchy_permalink( $post_link, $id = 0, $leavename = false )
{
	global $wp_query;
	// var_dump($wp_query);
	if ( strpos($post_link, '%topic_cat%') === false )
	{
		return $post_link;
	}

	// if this link is also a course continue
	if ( strpos($post_link, 'topics'))
	{
		$post = get_post($id);

		// make sure that the current post is actually a course
		if ( !is_object($post) || $post->post_type != 'topics' )
		{
			return $post_link;
		}

		// get all the locations that this course belongs to.
		// It could belong to Scotland AND St Andrews
		$terms = wp_get_object_terms($post->ID, 'topic_cat', ['parent' => 0]);
		// $terms = wp_get_post_terms($post->ID, 'topic_cat');
		if ( !$terms )
		{
			return str_replace('topics/%topic_cat%/', '', $post_link);
		}

		// loop through each topic_cat adding it to the url
		$locations = "";
		foreach ($terms as $term)
		{
			$locations .= $term->slug . "/";
			break;
		}

		$locations = trim($locations, "/");

		return str_replace('%topic_cat%', $locations, $post_link);
	}
}








add_action('init', 'codeart_register_post_type_courses' );
function codeart_register_post_type_courses()
{
	register_post_type(
		'courses', 
		array(
			'label' 				=> __('Courses'),
			'description' 			=> 'Used to display courses',
			'capability_type' 		=> 'post',
			'show_ui' 				=> true,
			'public' 				=> true,
			'publicly_queryable' 	=> true,
			'hierarchical'			=> true,
			'rewrite' 				=> array(
											'slug' => 'courses/%course_cat%',
											'with_front' => false
										),
			'supports'           	=> array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
			'taxonomies' 			=> array('course_cat')
		)
	);

	register_taxonomy(
		'course_cat',
		array(
			0 => 'courses',
		),
		array(
			'hierarchical' => true,
			'label' => __('Course Categories'),
			'rewrite' => array(
				'hierarchical' => true,
				'slug' => 'course_cat'
			),
		)
	);
}


add_filter('post_type_link', 'codeart_course_create_hierarchy_permalink', 1, 3);
function codeart_course_create_hierarchy_permalink( $post_link, $id = 0, $leavename = false )
{
	if ( strpos($post_link, '%course_cat%') === false )
	{
		return $post_link;
	}
	// if this link is also a course continue
	if ( strpos($post_link, 'courses'))
	{
		$post = get_post($id);

		// make sure that the current post is actually a course
		if ( !is_object($post) || $post->post_type != 'courses' )
		{
			return $post_link;
		}

		// get all the locations that this course belongs to.
		// It could belong to Scotland AND St Andrews
		$terms = wp_get_object_terms($post->ID, 'course_cat');
		if ( !$terms )
		{
			return str_replace('courses/%course_cat%/', '', $post_link);
		}

		// loop through each course_cat adding it to the url
		$locations = "";
		foreach ($terms as $term)
		{
			$locations .= $term->slug . "/";
		}

		$locations = trim($locations, "/");

		return str_replace('%course_cat%', $locations, $post_link);
	}
}









/**
 *	Function to determine if video is already rated from the current user
 **/
function is_user_raetd_video( $vid = '' )
{
	global $wpdb, $post;
	$table_name = $wpdb->prefix . 'mediator_members_rating';

	$uid = get_current_user_id();

	if( !$uid || !$vid )
		return true;

	$exists = $wpdb->get_row(
		$wpdb->prepare(
			'SELECT id FROM ' . $table_name . ' WHERE uid = %d AND vid = %s',
			$uid,
			$vid
		)
	);
	
	return $exists;
} // End of is_user_raetd_video();




/**
 *	Function to get video rating sum for given video id
 *	This is used for AJAX interaction
 *
 *	@return array(rating_sum, rating_count)
 **/
function codeart_get_video_rating_sum( $video_id = '' )
{
	global $wpdb;

	if( !$video_id )
		return 0;

	$table_name = $wpdb->prefix . 'mediator_members_rating';
	$rating_rows = $wpdb->get_results( "SELECT rating FROM " . $table_name . " WHERE vid = '" . esc_attr($video_id) . "'" );

	if( !$rating_rows )
		return 0;

	$rating_count = 0;
	$rating = 0;

	foreach( $rating_rows as $r ):
		$rating += $r->rating;
		$rating_count++;
	endforeach;

	return array($rating, $rating_count);
} // End of codeart_get_video_rating_sum();







function codeart_get_video_rating_avg( $pid = '' )
{
	global $wpdb;

	if( !$pid )
		return 0;

	$table_name = $wpdb->prefix . 'mediator_members_rating';
	return $wpdb->get_var( "SELECT AVG(rating) FROM " . $table_name . " WHERE pid = '" . intval($pid) . "'" );
} // End of codeart_get_video_rating_sum();





/**
 *	Function to get video rating
 *
 *	@return calculated video rating (with one decimal)
 **/
function codeart_get_video_rating( $uid = NULL, $video_id = '' )
{
	global $wpdb;

	if( !$video_id )
		return 0;

	if( !$uid )
		$uid = get_current_user_id();

	$table_name = $wpdb->prefix . 'mediator_members_rating';
	$rating_rows = $wpdb->get_results( "SELECT rating FROM " . $table_name . " WHERE vid = '" . esc_attr($video_id) . "'" );

	if( !$rating_rows )
		return 0;

	$rating_count = 0;
	$rating = 0;

	foreach( $rating_rows as $r ):
		$rating += $r->rating;
		$rating_count++;
	endforeach;

	return round(($rating/$rating_count), 1);
} // End of codeart_get_video_rating();




/**
 *	Function to insert rating to the database
 **/
function codeart_insert_activity_log_rating( $uid = 0, $pid = 0, $video_id = '', $rating = 0 )
{
	global $wpdb;
	$table_name = $wpdb->prefix . 'mediator_members_rating';

	$insert = $wpdb->insert(
		$table_name,
		array(
			'uid' 		=> $uid,
			'pid'		=> $pid,
			'rating' 	=> $rating,
			'vid' 		=> $video_id,
			'time' 		=> current_time('mysql')
		)
	);

	if( !$insert )
		return false;

	$title = get_the_title($pid);
	$perma = get_permalink($pid);

	// $log = codeart_insert_activity_log( $title, $pid, $type = 'rated', $perma, $video_id, '', $rating );
	// return $log ? true : false;

	return $insert ? true : false;
} // End of function codeart_insert_activity_log_rating();







/**
 *	Function to rate video
 **/
add_action( 'wp_ajax_rate_video', 'codeart_rate_video_log_callback' );
add_action( 'wp_ajax_nopriv_rate_video', 'codeart_rate_video_log_callback' );
function codeart_rate_video_log_callback()
{
    global $wpdb;

    $uid 		= get_current_user_id();
    $pid 		= isset($_POST['pid']) 		? intval($_POST['pid']) : 0;
    $vid 		= isset($_POST['vid']) 		? esc_attr($_POST['vid']) : '';
    $rating 	= isset($_POST['rating']) 	? intval($_POST['rating']) : 0;

    if( !$uid || !$pid || !$rating || !$vid )
    	return;

    $result = codeart_insert_activity_log_rating($uid, $pid, $vid, $rating);

    echo json_encode( array('status' => ($result == false ? false : true), 'result' => $result ) );

    die();
}










/**
 * Rewrite rule for topics template
 **/
// add_action('init', 'codeart_topics_rewrite_rule', 10, 0);
function codeart_topics_rewrite_rule()
{
	add_rewrite_rule(
		'^topics/([^/]*)/?',
		'index.php?pagename=topics&topic_category=$matches[1]',
		'top'
	);

	add_rewrite_tag('%topic_category%', '([^&]+)');
}












/**
 * Rewrite rule for search template
 **/
add_action('init', 'codeart_search_rewrite_rule', 10, 0);
function codeart_search_rewrite_rule()
{
	/*
	add_rewrite_rule(
		'^search/([^/]*)/([^/]*)/([^/]*)/([^/]*)/?',
		'index.php?pagename=search&searchtype=$matches[1]&searchcategory=$matches[2]&query=$matches[3]&item-page=$matches[4]',
		'top'
	);
	*/

	add_rewrite_rule(
		'^search/([^/]*)/([^/]*)/([^/]*)/?',
		'index.php?pagename=search&searchtype=$matches[1]&searchcategory=$matches[2]&query=$matches[3]',
		'top'
	);

	add_rewrite_rule(
		'^search/([^/]*)/([^/]*)/?',
		'index.php?pagename=search&searchtype=$matches[1]&searchcategory=$matches[2]',
		'top'
	);

	add_rewrite_rule(
		'^search/([^/]*)/?',
		'index.php?pagename=search&searchtype=$matches[1]',
		'top'
	);

	add_rewrite_tag('%searchtype%', '([^&]+)');
	add_rewrite_tag('%searchcategory%', '([^&]+)');
	add_rewrite_tag('%query%', '([^&]+)');
}









/**
 * Method to get the mediators url base
 * 
 * @param string $mediator_name The nicename of the mediator
 * @return string Base mediators URL or URL to the mediator.
 * */
function codeart_get_mediators_url( $mediator_name = '' )
{
	return sprintf( get_bloginfo('url') . '/network/mediators/' . $mediator_name);
}











add_action('wp_logout','codeart_logout_url');
function codeart_logout_url()
{
	wp_redirect( home_url() );
	exit();
}




/**
 * Function to print loop item classes
 * 
 * @param int $counter Current counter
 * @return void
 **/
function codeart_add_item_classes($counter)
{
    $classes = array();
    
    $classes[] = $counter == 0   	? ' first'  : '';
    $classes[] = $counter % 2 == 0  ? ' even'  : ' odd';
    $classes[] = $counter % 3 == 0  ? ' three'  : '';
    $classes[] = $counter % 4 == 0  ? ' fourth'  : '';
    $classes[] = $counter % 5 == 0  ? ' five'  : '';
    $classes[] = $counter % 6 == 0  ? ' six'  : '';

    echo trim(ltrim(rtrim(implode('', $classes))));
} // End of codeart_add_item_classes();






/**
 * Function to include SVG images
 * 
 * @param string $svg name
 * @param bool $return Print or return
 **/
function ca_get_svg( $svg = '', $return = false )
{
	$path = __DIR__ . '/images/' . $svg;

	if( ! file_exists( $path ) ) return;

	if( $return )
	{
		return file_get_contents( $path );
	}
	else
	{
		echo file_get_contents( $path );
	}
} // End of ca_get_svg();







function codeart_loop_grid( $query_args = array(), $title, $classes )
{
	$topics = new WP_Query($query_args); ?>
	<div class="videos-holder <?php echo $classes; ?>">
        <h4 class="ca_category"><span class="icon"><?php echo $title; ?></span></h4>
		<div class="wrap-videos">
			<?php
			$counter = 0;
			if( $topics->have_posts() ):
				while( $topics->have_posts() ): $topics->the_post(); global $post; ?>
				<div class="item <?php codeart_add_item_classes($counter++); ?>">
					<a href="<?php the_permalink(); ?>" class="image-holder">
						<?php $topic_id = $post->post_parent ? $post->post_parent : $post->ID; ?>
						<?php echo get_the_post_thumbnail($topic_id, 'topics-thumbnail'); ?>
						<?php codeart_print_video_overlay( $post ); ?>
					</a>
					<div class="box-content">
	                    <div class="box-heading">
		                    <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>

		                    <?php
		                    $catterms = get_the_terms( get_the_ID(), 'topic_cat' );
		                    $all_cat_term_names = array();
		                    if($catterms):
		                    foreach ($catterms as $catterm):
		                    	$all_cat_term_names[] = $catterm->name;
		                    endforeach;
		                    endif;
		                    printf('<span class="topic-category">%s</span></div>', implode(', ', $all_cat_term_names)); ?>
		                    <div class="entry-desc">
		                    	<?php
		                    	global $post;
		                    	$excerpt = $post->post_content;
		                    	$excerpt = strip_shortcodes($excerpt);
		                    	$excerpt = strip_tags($excerpt);
		                    	$excerpt = substr($excerpt, 0, 200);
		                    	$excerpt = $excerpt . '... ' . sprintf('<a href="%s" class="more-link">read more</a>', get_permalink($post->ID));
		                    	echo wpautop($excerpt);
		                    	?>
		                    </div>
                    	</div>
                	</div>
				<?php endwhile;
			endif;

			wp_reset_postdata(); ?>
		</div> <!-- .wrap-videos -->
		<a href="#" class="ajax-load-more-button">Load More</a>
	</div> <!-- .most-popular -->
	<?php
}









add_action('wp_head', 'codeart_topics_load_more_ajax_head');
function codeart_topics_load_more_ajax_head()
{
	?>
	<script type="text/javascript" >
	jQuery(document).ready(function($) {
		var topics_paged = 2;
		var all_topics_count = <?php echo wp_count_posts('topics')->publish; ?>;

		var topics_more_button = $('body.codeart-topics').find('.all-videos a.ajax-load-more-button');
		topics_more_button.on('click', function(e) {
			e.preventDefault();

			var ajax_btn = $(this);

			if( ajax_btn.hasClass('active') )
				return false;

			ajax_btn.addClass('active');

			var data = {
				'action': 'topics_load_more',
				'paged': topics_paged++,
				'topics_template': true
			};

			jQuery.post(ajaxurl, data, function(html) {

				html = $(html);
				html.find('a.ajax-load-more-button').remove();
				html.find('a.ajax-load-more-button-new').remove();
				html.find('h4.ca_category').remove();

				if ( $('body.codeart-topics').find('.wrap-videos .item').length >= all_topics_count ) {
					$('body').find('a.ajax-load-more-button').before('<p class="info-no-more-topics">No more topics to show</p>');
					$('body').find('a.ajax-load-more-button').remove();
				}

				html.css('display', 'none');
				topics_more_button.before( html );
				html.fadeIn(800);

				ajax_btn.removeClass('active');
			});
		});

	});
	</script>
	<?php
}



add_action( 'wp_ajax_topics_load_more', 'codeart_topics_load_more_ajax_head_callback' );
add_action( 'wp_ajax_nopriv_topics_load_more', 'codeart_topics_load_more_ajax_head_callback' );
function codeart_topics_load_more_ajax_head_callback()
{
	$paged = isset($_POST['paged']) ? intval($_POST['paged']) : 1;

	$topic_args = array(
		'post_type' 		=> 'topics',
		'posts_per_page' 	=> 9,
		'paged'				=> $paged,
		'orderby' 			=> 'post_parent title',
		'order'				=> 'ASC',
		'post_status' 		=> 'publish'
	);

	global $scporder;
	remove_action( 'pre_get_posts', array( $scporder, 'scporder_pre_get_posts' ) );
	remove_action( 'wp_ajax_update-menu-order', array( $scporder, 'update_menu_order' ) );
	remove_action( 'wp_ajax_update-menu-order-tags', array( $scporder, 'update_menu_order_tags' ) );

	codeart_loop_grid($topic_args, 'All Videos', 'all-videos', 'videos');
	
	wp_die();
}






/**
 * Share icons
 **/
function codeart_share_box( $classes = '' )
{
	?>
	<div class="box">
	    <div class="share-right-wrap <?php echo $classes; ?>">
		<h4>Share On</h4>
			<div class="share-right-box">

				<?php
					global $wp, $post;
					$current_url = home_url(add_query_arg(array(),$wp->request));
					$share_title = urlencode($post->post_title);
				?>

				<a onclick="javascript:window.open('http://twitter.com/home?status=<?php echo $share_title; ?> - <?php echo $current_url; ?>', '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" href="http://twitter.com/intent/tweet?status=<?php echo $share_title; ?>+<?php echo $current_url; ?>" class="s-icon tw"><?php echo file_get_contents(get_stylesheet_directory_uri() . "/images/icon-twitter.svg"); ?></a>
				<a onclick="javascript:window.open('http://www.facebook.com/sharer.php?u=<?php echo urlencode($current_url); ?>&t=<?php echo urlencode($share_title); ?>', '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" href="http://www.facebook.com/share.php?u=<?php echo $current_url; ?>&title=<?php echo $share_title; ?>" class="s-icon fb"><?php echo file_get_contents(get_stylesheet_directory_uri() . "/images/icon-facebook.svg"); ?></a>
				<a onclick="javascript:window.open('https://plus.google.com/share?url=<?php echo $current_url; ?>', '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" href="https://plus.google.com/share?url=<?php echo $current_url; ?>" class="s-icon gp"><?php echo file_get_contents(get_stylesheet_directory_uri() . "/images/icon-gplus.svg"); ?></a>
					
					<!--
				<a href="#" class="tw"><?php echo file_get_contents(get_stylesheet_directory_uri() . "/images/icon-twitter.svg"); ?></a>
				<a href="#" class="gp"><?php echo file_get_contents(get_stylesheet_directory_uri() . "/images/icon-gplus.svg"); ?></a>
				<a href="#" class="fb"><?php echo file_get_contents(get_stylesheet_directory_uri() . "/images/icon-facebook.svg"); ?></a>
			-->
			</div>
		</div>
    </div>
	<?php
}







/**
 * Function to create search url
 * 
 * @param string $type Type to search
 * 
 * @return string New search url
 **/
function codeart_get_search_url( $type = '' )
{
	$search_url = get_bloginfo('url') . '/search/';

	$query = get_query_var('query');
	$query = esc_attr($query);

	$slash = empty($type) ? '' : '/';

	$search_url = $search_url . $type . $slash;
	return $query ? add_query_arg('query', $query, $search_url) : $search_url;
} // codeart_get_search_url()







add_action('genesis_header', 'codeart_add_ca_mobile_menu');
function codeart_add_ca_mobile_menu()
{
	?>
	<div class="ca_mobile_menu"></div>
	<div class="ca_mobile_menu_close"></div>
	<div class="ca_mobile_animation"></div>
	<?php
}







add_shortcode( 'codeart_member_links', 'codeart_member_links_func' );
function codeart_member_links_func( $atts )
{
	ob_start();
	if( is_user_logged_in() ): ?>
	<div class="login-signup">
		<?php
		global $current_user;
		get_currentuserinfo(); ?>

		<?php
		$upgrade_url = is_page_template('template-pricing-temporary.php') ? '/upgrade/' : '/plans/';
		$upgrade_url = get_bloginfo('url') . $upgrade_url;
		?>

		<a href="<?php echo $upgrade_url; ?>" class="login-button orange_background premium_upgrade">Upgrade</a>
		<a href="<?php bloginfo('url'); ?>/edit/" class="login-button">Edit Profile</a>
		<a href="<?php echo wp_logout_url( get_bloginfo('url') ); ?> " class="login-button">Logout</a>
	</div>
	<?php else: ?>
	<div class="login-signup">
		<a href="#" class="login-button register-button">Log In</a>
		<a href="#" class="register-button">Sign Up</a>
	</div>
	<?php endif;
	?><?php
	return ob_get_clean();
} // codeart_member_links_func();








add_action('wp', 'codeart_add_member_stripe_account_functions');
function codeart_add_member_stripe_account_functions()
{
	if(!is_page_template('template-payment-details.php'))
		return;

	require_once( get_stylesheet_directory() . '/my-account-func.php' );
}

add_action( 'genesis_footer', 'codeart_custom_loader' );
function codeart_custom_loader(){
    ?>
        <div class="ca_custom_loader">
            <div class="ca-align-center">
                <?php ca_get_svg('logo_loader.svg'); ?>
            </div>
        </div>
    <?php
}












add_action('template_redirect', 'codeart_first_time_login_redirect');
function codeart_first_time_login_redirect()
{
	$uid = get_current_user_id();

	if (!is_page_template('ervo-thank-you.php') || !$uid) {
		return;
	}

	$user_meta_key = '_first_time_login';
	$first_time = get_user_meta($uid, $user_meta_key, true);

	

	if (!$first_time) {
		update_user_meta( $uid, $user_meta_key, 1 );
		return;
	}

	wp_redirect(get_bloginfo('url').'/edit/', 301);
	exit();
}





function ajax_login_init()
{
	wp_register_script('ajax-login-script', get_stylesheet_directory_uri() . '/js/ajax-login-script.js', array('jquery') ); 
	wp_enqueue_script('ajax-login-script');

	wp_localize_script( 'ajax-login-script', 'ajax_login_object', array( 
		'ajaxurl' => admin_url( 'admin-ajax.php' ),
		'redirecturl' => get_field('thank_you_page', 'option'),
		'loadingmessage' => __('Sending user info, please wait...')
	));

	// Enable the user with no privileges to run ajax_login() in AJAX
	add_action( 'wp_ajax_nopriv_ajaxlogin', 'ajax_login' );
}

// Execute the action only if the user isn't logged in
if (!is_user_logged_in()) {
    add_action('init', 'ajax_login_init');
}







function ajax_login()
{
	// First check the nonce, if it fails the function will break
	check_ajax_referer( 'ajax-login-nonce', 'security' );

	// Nonce is checked, get the POST data and sign user on
	$info = array();
	$info['user_login'] = $_POST['username'];
	$info['user_password'] = $_POST['password'];
	$info['remember'] = true;

	$user_signon = wp_signon( $info, false );
	if ( is_wp_error($user_signon) )
	{
		echo json_encode(array('loggedin'=>false, 'message'=>__('Wrong username or password.')));
	} else {
		echo json_encode(array('loggedin'=>true, 'message'=>__('Login successful, redirecting...')));
	}

	die();
}





add_action('wp', 'codeart_remove_admin_bar_for_non_admins');
function codeart_remove_admin_bar_for_non_admins()
{
	if (!is_super_admin()) {
		show_admin_bar( false );
	}
}






// add_action('genesis_before_footer', 'codeart_add_elements_before_footer', 5);
function codeart_add_elements_before_footer()
{
	if( is_user_logged_in() )
		return; ?>

	<div class="footer-top-area">
		<div class="wrap">
			<p>Sharpen Your Mind</p>
			<p>Improve Your Effectiveness</p>
			<p>Build Your Mediation Business</p>
			<h3>Become A Better Mediator</h3>
			<div class="login-signup">
			    <a href="#" class="button register-button">Sign Up</a>
            </div>
		</div>
	</div>
	<?php
}





add_action('genesis_after_header', 'codeart_add_full_width_main_title', 10);
function codeart_add_full_width_main_title()
{
	if( is_home() || is_front_page() )
		return;

	$title = '';
	if( is_singular() ):
		global $post;
		if( get_permalink($post->ID) == get_field('thank_you_page', 'option') )
			return;
		$title = $post->post_title;
	endif;

	if( is_author() ):
		$author = get_query_var('author');
		$current_author = get_user_by('id', $author);
		$title = $current_author->first_name . ' ' . $current_author->last_name;
	endif;
	
	printf(
		'<h1 class="entry-title">%s</h1>',
		apply_filters('the_title', $title)
	);
}






function codeart_member_navigation_buttons()
{
	if( !is_user_logged_in() )
		return;

	global $current_user, $member;
	get_currentuserinfo(); ?>
	<div class="top-buttons">
		<a href="<?php echo codeart_get_mediators_url($current_user->user_nicename); ?>" class="view-profile">View Profile</a>

		<?php if( get_field('payment_details_url', 'option') ): ?>
		<a href="<?php the_field('payment_details_url', 'option'); ?>" class="payment-details">Payment Details</a>
		<?php endif; ?>
		
		<?php if( get_field('cpd_dashboard_page', 'option') ): ?>
<!--		<a href="<?php the_field('cpd_dashboard_page', 'option'); ?>" class="cpd-btn">CPD Dashboard</a>-->
		<?php endif; ?>

		<?php if( get_field('upgrade_page', 'option') && !$member->is_premium_member() ): ?>
		<a href="<?php bloginfo('url'); ?>/plans/" class="upgrade-premium orange_background">Upgrade to Premium</a>
		<?php endif; ?>	

		<?php if( $member->is_premium_member() ): ?>
		<a href="<?php bloginfo('url'); ?>/invite-friends/" class="upgrade-premium invite-friends orange_background">Invite Friends</a>
		<?php endif; ?>	
	</div>
	<?php
}

















add_action( 'wp_ajax_delete_card', 'delete_card_function' );
add_action( 'wp_ajax_nopriv_delete_card', 'delete_card_function' );
function delete_card_function()
{
	$sID = get_user_stripe_id( get_current_user_id() );
	$cID = $_POST[ 'cardid' ];

	$response = stripe_request(
		'',
		'customers/' . $sID . '/sources/' . $cID,
		'DELETE'
	);

	if( ! is_wp_error( $response ) )
		wp_send_json_success();
	else
		wp_send_json_error();
}

add_action( 'wp_ajax_save_card', 'save_card_function' );
add_action( 'wp_ajax_nopriv_save_card', 'save_card_function' );
function save_card_function()
{
	$cID 			= $_POST[ 'cardid' ];
	$card_number	= $_POST[ 'card_number' ];
	$card_cvc		= $_POST[ 'card_cvc' ];
	$card_exp_mon	= $_POST[ 'card_exp_mon' ];
	$card_exp_year	= $_POST[ 'card_exp_year' ];

	if( empty( $cID ) )
		create_card( $card_number, $card_cvc, $card_exp_mon, $card_exp_year );
	else
		update_card( $cID, $card_exp_mon, $card_exp_year );
}



function create_card( $card_number, $card_cvc, $card_exp_mon, $card_exp_year )
{
	$sID = get_user_stripe_id( get_current_user_id() );

	$args	= array(
		'card'	 => array(
			'number'	=> $card_number,
			'cvc'		=> $card_cvc,
			'exp_month'	=> $card_exp_mon,
			'exp_year'	=> $card_exp_year
		)			
	);

	$response = stripe_request(
		$args,
		'customers/' . $sID . '/sources',
		'POST'
	);

	if( ! is_wp_error( $response ) )
	{
		$data = array(
			'id'		=> $response->id,
			'last4'		=> $response->last4,
			'exp_month'	=> $response->exp_month,
			'exp_year'	=> $response->exp_year,
		);

		wp_send_json_success( $data );
	}else{
		wp_send_json_error( $data );
	}
}

function update_card( $cID, $card_exp_mon, $card_exp_year )
{
	$sID = get_user_stripe_id( get_current_user_id() );

	$args = array(
		'exp_month'	=> $card_exp_mon,
		'exp_year'	=> $card_exp_year
	);

	$response = stripe_request(
		$args,
		'customers/' . $sID . '/sources/' . $cID,
		'POST'
	);

	if( ! is_wp_error( $response ) )
	{
		$data = array(
			'id'		=> $response->id,
			'last4'		=> $response->last4,
			'exp_month'	=> $response->exp_month,
			'exp_year'	=> $response->exp_year,
		);

		wp_send_json_success( $data );
	}else{
		wp_send_json_error( $data );
	}
}







function stripe_request( $request, $api = 'charges', $method = 'POST' )
{
	$stripe_class = new MediatorStripe;

	$api_endpoint	= 'https://api.stripe.com/';
	$secret_key		= $stripe_class->get_secret_key();

	$response = wp_safe_remote_post(
		$api_endpoint . 'v1/' . $api,

		array(
			'method'        => $method,
			'headers'       => array(
				'Authorization'  => 'Basic ' . base64_encode( $secret_key . ':' ),
				'Stripe-Version' => '2015-04-07'
			),
			'body'       => $request,
			'timeout'    => 70
		)
	);

	if ( is_wp_error( $response ) )
	{
		return new WP_Error( 'stripe_error', __( 'There was a problem connecting to the payment gateway.', 'woocommerce-gateway-stripe' ) );
	}

	if ( empty( $response['body'] ) )
	{
		return new WP_Error( 'stripe_error', __( 'Empty response.', 'woocommerce-gateway-stripe' ) );
	}

	$parsed_response = json_decode( $response[ 'body' ] );

	// Handle response
	if ( ! empty( $parsed_response->error ) )
	{
		return new WP_Error( isset( $parsed_response->error->param ) ? $parsed_response->error->param : 'stripe_error', $parsed_response->error->message );
	}else{
		return $parsed_response;
	}
}

function get_user_stripe_id( $uID = 0 )
{
	global $member;
	return $member->member_data->customer_id;
}

function get_cards( $cID = '' )
{
	if( empty( $cID ) ) $cID = get_user_stripe_id( get_current_user_id() );
	if( empty( $cID ) ) return;

	$response = stripe_request(
		array(
			'limit'	=> 100
		),
		'customers/' . $cID . '/sources',
		'GET'
	);

	return $response;
}

function get_payments( $cID = '' )
{
	if( empty( $cID ) ) $cID = get_user_stripe_id( get_current_user_id() );
	if( empty( $cID ) ) return;

	$response = stripe_request(
		array(
			'limit'		=> 100,
			'customer'	=> $cID
		),
		'charges',
		'GET'
	);

	return $response;
}

function get_invoice( $invoice_ID )
{
	$response = stripe_request(
		'',
		'invoices/' . $invoice_ID,
		'GET'
	);

	return $response;
}


















add_action('wp_head', 'codeart_ajax_reset_password_head');
function codeart_ajax_reset_password_head()
{
	?>
	<script type="text/javascript">
	jQuery(document).ready(function($) {
		var reset_form = $('body').find('#reset-password');
		reset_form.on('submit', function(e) {

			var cls = 'reset-password-loading';
			$('body').addClass(cls);

			var email = reset_form.find('input[type="email"]');

			if( email && !validateEmail(email.val()) )
			{
				email.addClass('validation-error');
				return false;
			}

			var data = {
				'action': 'reset_password',
				'email': email.val()
			};

			jQuery.post(ajaxurl, data, function(response) {
				obj         = jQuery.parseJSON(response);
                var message = obj.message;
                
                reset_form.find('p').text(message).addClass('error');
                
				if(message === 'New password was sent to email you entered!'){
                    reset_form.addClass('done');
                    reset_form.find('p').text(message).removeClass('error');
                }
			});

			$('body').removeClass(cls);

			return false;
		});
	});
	</script>
	<?php
}


add_action( 'wp_ajax_reset_password', 'reset_password_callback' );
add_action( 'wp_ajax_nopriv_reset_password', 'reset_password_callback' );
function reset_password_callback()
{
	$email = isset($_POST['email']) ? $_POST['email'] : '';

	$response = [];

	$user_id = email_exists($email);

	if( !is_email($email) || !$user_id )
	{
		$response['status'] = false;
		$response['message'] = 'Email you entered does not exists!';
		echo json_encode($response);
		wp_die();
	}

	$new_password = wp_generate_password( 14, true );
	wp_set_password( $new_password, $user_id );

	$subject = get_bloginfo('name') . ' - Reset Password';
	$message = 'Your new password is: ' . $new_password . ' Go to ' . get_bloginfo('url') . ' and try to login with your new password.';

	if( !wp_mail($email, $subject, $message) )
	{
		$response['status'] = false;
		$response['message'] = 'Could not sent email!';
		echo json_encode($response);
		wp_die();
	}

	$response['status'] = false;
	$response['message'] = 'New password was sent to email you entered!';
	echo json_encode($response);
	wp_die();
}

















add_action( 'wp_ajax_update_profile', 'update_profile_callback' );
add_action( 'wp_ajax_nopriv_update_profile', 'update_profile_callback' );
function update_profile_callback()
{
	$fields 		= $_POST['fields'];
	$specialisms 	= $_POST['specialisms'];
	$services 		= $_POST['services'];
	$organizations 	= $_POST['organizations'];
	$is_admin 		= $_POST['is_admin'];

	$fields = wp_parse_args( $fields );

	$fields['specialisms'] 			= $specialisms;
	$fields['organizations'] 		= $organizations;
	$fields['services_provided'] 	= $services;

	// echo json_encode($fields);
	// wp_die();

	global $member;
    $res = $member->save($fields, $is_admin);
    if($res === 0){
        echo json_encode(['status' => 'nochanges', 'message' => 'nochanges']);
        wp_die();
    }
    
    if($res === 1){
        echo json_encode(['status' => 'success', 'message' => 'success']);
        wp_die();
    }
    
    echo json_encode(['status' => 'error', 'message' => 'error']);

	wp_die();
}





function codeart_member_avatar($attachment_id = 0, $size = 'popular-mediator-avatar')
{
	$avatar = wp_get_attachment_image( $attachment_id, $size );

	if(empty($avatar) && class_exists('Acf'))
	{
		$def_post_image_obj = get_field( 'option_default_thumbnail', 'option' );
		$avatar = wp_get_attachment_image( $def_post_image_obj['ID'], $size );
	}

	echo $avatar;
}




function codeart_get_member_avatar_id( $data, $user_id = 0 )
{
	foreach($data as $d)
		if($d->user_id == $user_id)
			return $d->avatar;
	return 0;
}




function codeart_get_member_title( $data, $user_id = 0 )
{
	foreach($data as $d)
	{
		if($d->user_id == $user_id)
		{
			return $d->title;
		}
	}

	return '';
}








/*
function numediaweb_custom_user_profile_fields($user) {
?>
<table class="form-table">
<tr>
	<th>
		<label for="tc_location"><?php _e('Location'); ?></label>
	</th>
	<td>
		<input type="text" name="tc_location" id="tc_location" value="<?php echo esc_attr( get_the_author_meta( 'tc_location', $user->ID ) ); ?>" class="regular-text" />
		<br><span class="description"><?php _e('Your location.', 'travelcat'); ?></span>
	</td>
</tr>
<tr>
	<th>
		<label for="tc_favorites"><?php _e('Favorites', 'travelcat'); ?></label>
	</th>
	<td>
		<input type="text" name="tc_favorites" id="tc_favorites" value="<?php echo esc_attr( get_the_author_meta( 'tc_favorites', $user->ID ) ); ?>" class="regular-text" />
		<br><span class="description"><?php _e('Can you share a few of your favorite places to be or to stay?', 'travelcat'); ?></span>
		<br><span class="description"><?php _e('Separate by commas.', 'travelcat'); ?></span>
	</td>
</tr>
<tr>
	<th>
		<label for="tc_travel_map"><?php _e('Travel map', 'travelcat'); ?></label>
	</th>
	<td>
		<input type="text" name="tc_travel_map" id="tc_travel_map" value="<?php echo esc_attr( get_the_author_meta( 'tc_travel_map', $user->ID ) ); ?>" class="regular-text" />
		<br><span class="description"><?php _e('Been there / Going there within a year / Wish list.', 'travelcat'); ?></span>
		<br><span class="description"><?php _e('Separate by commas.', 'travelcat'); ?></span>
	</td>
</tr>
</table>
<?php
}
add_action('show_user_profile', 'numediaweb_custom_user_profile_fields');
add_action('edit_user_profile', 'numediaweb_custom_user_profile_fields');
*/







Mediator_Member_JavaScripts::repeater_scripts();
Mediator_Member_JavaScripts::media_scripts();

/*
add_action('template_redirect', 'codeart_include_media_and_repeater_sripts_for_edit_profiles');
add_action('admin_enqueue_scripts', 'codeart_include_media_and_repeater_sripts_for_edit_profiles');
function codeart_include_media_and_repeater_sripts_for_edit_profiles()
{
	global $pagenow;
	if( is_page_template('template-profile-edit.php') || ($pagenow == 'users.php' && $_GET['page'] == 'edit-member') )
	{
		Mediator_Member_JavaScripts::repeater_scripts();
		Mediator_Member_JavaScripts::media_scripts();
	}
}*/






add_action( 'wp_enqueue_scripts', 'codeart_interviews_magicsuggest_scripts' );
add_action( 'admin_enqueue_scripts', 'codeart_interviews_magicsuggest_scripts' );
function codeart_interviews_magicsuggest_scripts()
{
	global $pagenow;
	if( is_page_template('template-thank-you.php') || is_page_template('template-profile-edit.php') || ($pagenow == 'users.php' && isset($_GET['page']) && $_GET['page'] == 'edit-member') )
	{
	    wp_enqueue_script(
			'magicsuggest',
			get_stylesheet_directory_uri() . '/js/magicsuggest-min.js',
			array('jquery'),
			CHILD_THEME_VERSION,
			true
		);
        
        wp_enqueue_script(
			'ca_edit_profile',
			get_stylesheet_directory_uri() . '/js/ca_edit_profile.js',
			array('jquery'),
			CHILD_THEME_VERSION,
			true
		);
        
        wp_enqueue_script('jquery-ui-datepicker');
	    
	    wp_enqueue_style( 'style-name', get_stylesheet_directory_uri() . '/css/magicsuggest-min.css' );

	    // wp_enqueue_style( 'profile-edit', get_stylesheet_directory_uri() . '/profile-edit-style.css' );
	}



	if( is_admin() || ($pagenow == 'users.php' && isset($_GET['page']) && $_GET['page'] == 'edit-member') )
	{   
	    wp_enqueue_style( 'admin-user-edit', get_stylesheet_directory_uri() . '/admin-user-edit.css' );
	    // wp_enqueue_style( 'profile-edit', get_stylesheet_directory_uri() . '/profile-edit-style.css' );
	}
}







add_action('wp_head', 'codeart_add_ajax_scripts_for_profile_edit');
add_action('admin_head', 'codeart_add_ajax_scripts_for_profile_edit');
function codeart_add_ajax_scripts_for_profile_edit()
{
	global $pagenow, $member;
	if( is_page_template('template-profile-edit.php') || ($pagenow == 'users.php' && isset($_GET['page']) && $_GET['page'] == 'edit-member') ): ?>
	<script type="text/javascript">
	jQuery(document).ready(function($) {

		<?php
		$suggestions = [];
		$service_suggest_terms = get_terms( 'service', ['hide_empty' => false] );
		if($service_suggest_terms):
			foreach($service_suggest_terms as $service_suggest_term):
				$suggestions[] = "{id: " . $service_suggest_term->term_id . ", name:'" . $service_suggest_term->name . "'}";
			endforeach;
		endif;

		$suggestions_spec = [];
		$service_suggest_terms_spec = get_terms( 'specialism', ['hide_empty' => false] );
		if($service_suggest_terms):
			foreach($service_suggest_terms_spec as $service_suggest_term_spec):
				$suggestions_spec[] = "{id: " . $service_suggest_term_spec->term_id . ", name:'" . $service_suggest_term_spec->name . "'}";
			endforeach;
		endif;

		$suggestions_organizations = [];
		$suggestions_organizations_tmp = get_posts(['posts_per_page' => -1, 'post_type' => 'organization']);
		if($suggestions_organizations_tmp):
			foreach($suggestions_organizations_tmp as $sot):
				$suggestions_organizations[] = "{id: " . $sot->ID . ", name:'" . addslashes($sot->post_title) . "'}";
			endforeach;
		endif; ?>
        
		var ms_specialisms = $('#ca_magicsuggest_specialisms').magicSuggest({
            data: [<?php echo implode(', ', $suggestions_spec); ?>],
            typeDelay: 0,
            columns: 2,
            allowFreeEntries: false,
            placeholder: 'Add Specialisms...',
            maxSelection: null,
            noSuggestionText:''
        });

        var ms_organizations = $('body').find('#ca_magicsuggest_organizations').magicSuggest({
        	data: [<?php echo implode(', ', $suggestions_organizations); ?>, {}],
            typeDelay: 0,
            columns: 2,
            allowFreeEntries: false,
            placeholder: 'Add Organizations...',
            maxSelection: null,
            noSuggestionText:''
        });

        <?php
        $suggestions_organizations_selected = empty($member->member_data->organizations) ? array() : $member->member_data->organizations;
        $suggestions_organizations_selected = maybe_unserialize( $suggestions_organizations_selected );

        if(!empty($suggestions_organizations_selected) && is_admin()): ?>
        ms_organizations.setValue([<?php echo implode(',', $suggestions_organizations_selected); ?>]);
        <?php endif; ?>


        <?php
        $specialisms = empty($member->member_data->specialisms) ? array() : $member->member_data->specialisms;
        $specialisms = maybe_unserialize( $specialisms );

        if(!empty($specialisms)): ?>
        ms_specialisms.setValue([<?php echo implode(',', $specialisms); ?>]);
        <?php endif; ?>

        var ms = $('#ca_magicsuggest').magicSuggest({
            data: [<?php echo implode(', ', $suggestions); ?>],
            typeDelay: 0,
            columns: 1,
            allowFreeEntries: false,
            placeholder: 'Add Services...',
            maxSelection: null,
            noSuggestionText:''
        });

        <?php

        $suggestions = empty($member->member_data->services_provided) ? array() : $member->member_data->services_provided;
        $suggestions = maybe_unserialize( $suggestions );

        if(!empty($suggestions)): ?>
        ms.setValue([<?php echo implode(',', $suggestions); ?>]);
        <?php endif; ?>


		var save_button         = $('body.codeart-profile-edit div.bottom-buttons a.save');
        var alert_box           = $('#profile-edit-content div.alert_changes');
		var update_profile_form = $('body').find('form#profile-edit');
        var wp_input_fields     = $('input.wpvalidate, textarea.wpvalidate');

        update_profile_form.on('submit', function(e){
            
            var error = true;
            var error_repeaters = false;

            // member-group-child
            var repeaters = $('body').find('div.member-group-child');
            repeaters.find('input, textarea, select').removeClass('error');
            repeaters.find('input, textarea, select').on('click', function(e) {
            	$(this).removeClass('error');
            });

            if (repeaters.length > 2) {
            	$.each(repeaters, function(index, value) {
	            	var parent_div = $(this);

	            	var inputs = parent_div.find('input, textarea, select');
	            	$.each(inputs, function(index, value) {
	            		var input_value = $(this).val();
	            		if( input_value == '' )
	            		{
	            			$(this).addClass('error');
	            			error_repeaters = true;
	            		}
	            	});
	            });
            };
            
            if(error_repeaters === true)
            {
            	generateError('Empty fields are not allowed.');
            	return false;
            }

            $(this).addClass('loading');
            
            wp_input_fields.each(function(){
                var value = $(this).val();
                var original = $(this).data('original');
                
                if(value != original){
                    error = false;
                }
                
                $(this).data('original', $(this).val());
            });

			var specialisms 	= ms_specialisms.getValue();
			var services 		= ms.getValue();
			var organizations 	= '';
			if(ms_organizations.length != 0)
				organizations = ms_organizations.getValue();

            save_button.addClass('saving');
            
            update_profile_form.find('div.bottom-buttons div.submit_holder').addClass('saving');

            var data = {
            	'action': 'update_profile',
            	'fields': $(this).serialize(),
            	'specialisms': specialisms,
            	'services': services,
            	'organizations': organizations,
            	'is_admin': <?php echo is_admin() ? '1' : '0'; ?>
            };

            jQuery.post(ajaxurl, data, function(response) {
                var status = jQuery.parseJSON(response);
                
                /*if(status.status === 'nochanges' && error === true){*/
                if(status.status === 'nochanges' && error === true){
                    generateError();
                    update_profile_form.find('div.bottom-buttons div.submit_holder').removeClass('saving');
                    return false;
                } else {
                    alert_box.fadeOut();
                    successLoader();
                }

                if(status.status === 'success'){
                    alert_box.fadeOut();
                    successLoader();
                }
                
                if(status.status === 'error'){
                    alert_box.fadeOut();
                    failedLoader();
                    update_profile_form.find('div.bottom-buttons div.submit_holder').removeClass('saving');
                    return false;
                }
                
                
                <?php if( is_admin() ): ?>
                location.reload();
                <?php else: ?>
                update_profile_form.find('div.bottom-buttons div.submit_holder').removeClass('saving');
                <?php endif; ?>
            });

            return false;
            
        });
        
        function generateError(error_message){
        	var default_text = 'Please change something before saving new changes';
        	error_message = error_message ? error_message : default_text;

        	update_profile_form.removeClass('loading');
        	alert_box.find('h3').text(error_message);
        	alert_box.fadeIn();

        	$('html, body').animate({
        		scrollTop: $(update_profile_form).offset().top - ($('body').find('#header').height() + 15)
        	}, 800);
        }

        function successLoader(){
            update_profile_form.removeClass('loading');
            $('body.codeart-profile-edit div.animation-holder.done').show();
            $('body').delay('100').queue(function(i){
               $('body').addClass('done no_scroll');
                i();
            });
            
            setTimeout(function(){
                $('body').delay('100').queue(function(i){
                    $('body').removeClass('done no_scroll');
                i();
            });
                
                $('body.codeart-profile-edit div.animation-holder').delay('500').fadeOut();
                
            },2000);
        }
        
        function failedLoader(){
            update_profile_form.removeClass('loading');
            $('body.codeart-profile-edit div.animation-holder.error').show();
            
            $('body').delay('100').queue(function(i){
                $('body').addClass('done no_scroll');
                i();
            });
        }
	});
	</script>
	<?php
	endif;
}









add_action('admin_menu', 'codeart_add_edit_members_to_admin');
function codeart_add_edit_members_to_admin()
{
	add_submenu_page(
		'users.php',
		'Edit Member',
		'Edit Member',
		'manage_options',
		'edit-member',
		'codeart_admin_edit_member'
	);
}

function codeart_admin_edit_member()
{
	?>
	<div class="wrap">
		<h1>Edit Member Details</h1>
		<div id="edit-member-magicsuggest"></div>
	</div>

	<?php
	$all_users = get_users();
	$user_names = array();
	if($all_users)
		foreach($all_users as $usr)
			$user_names[] = "{id: " . $usr->ID . ", name:'" . $usr->data->display_name . "'}";
	?>

	<script type="text/javascript">
	jQuery(document).ready(function($) {

		var member_select = $('#edit-member-magicsuggest').magicSuggest({
			data: [<?php echo implode(', ', $user_names); ?>],
            typeDelay: 0,
            columns: 1,
            allowFreeEntries: false,
            placeholder: 'Browse Members...',
            maxSelection: null,
            maxDropHeight: 200,
            noSuggestionText:''
		});

		$(member_select).on('selectionchange', function(e,m){
			var member_obj = this.getValue();
			if( Array.isArray(member_obj) == false || member_obj.length < 1)
				return;

			var member_id = member_obj.pop();
			location.href = location.origin + location.pathname + '?page=edit-member&member_id=' + member_id;
		});
	});
	</script>
	<?php
	codeart_profile_edit_form();
}



add_filter('query_vars', 'codeart_add_member_id_param_to_users');
function codeart_add_member_id_param_to_users($public_query_vars)
{
	$public_query_vars[] = 'member_id';
	return $public_query_vars;
}




function codeart_profile_edit_form()
{
	global $member;

    $form_fields = new Mediator_Member_Fields(); ?>

    <div id="profile-edit-content">
		<div class="entry profile-edit-wrap">

			<form id="thumbnail_upload" method="post" action="#" enctype="multipart/form-data">
				<input type="file" name="thumbnail" id="thumbnail">
				<input type="hidden" name="action" id="action" value="avatar_upload_action">
				<input id="submit-avatar-ajax" name="submit-avatar-ajax" type="submit" value="Upload">
			</form>

			<div class="entry-content">

				<?php
				if(!is_admin())
					codeart_member_navigation_buttons();
				?>

				<form id="profile-edit" action="" methot="post" enctype="multipart/form-data">
                    
                    <?php if(is_admin()): ?>
                    <div class="loader-home-search">
				        <div class="showbox">
				        	<div class="loader">
				        		<svg class="circular" viewBox="25 25 50 50">
				        			<circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"></circle>
				        		</svg>
				        	</div>
				        </div>
				    </div>
					<?php endif; ?>

                    <div class="alert_changes">
                        <h3>Please change something before saving new changes.</h3>
                    </div>

					<?php if(is_admin() && isset($_GET['member_id'])): ?>
					<input type="hidden" name="member_id" value="<?php echo intval($_GET['member_id']); ?>">
					<?php endif; ?>

					<div class="fullblock">

						<div class="box">
						    <div class="box-heading">
							    <h4 class="about_icon icon">About you</h4>
		                    </div>
							<div class="left">
								<div class="avatar">
									<div class="loader-home-search">
								        <div class="showbox">
								        	<div class="loader">
								        		<svg class="circular" viewBox="25 25 50 50">
								        			<circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"></circle>
								        		</svg>
								        	</div>
								        </div>
								    </div>
									<?php
									$avatar_id = isset($member->member_data->avatar) ? $member->member_data->avatar : 0;
									codeart_member_avatar($avatar_id);
									?>

									<?php
									$images_in_gallery = 0;
									global $wpdb;
									$sql = sprintf(
										"SELECT COUNT(ID) FROM $wpdb->posts WHERE post_type = 'attachment' AND post_author = %d",
										get_current_user_id()
									);
									$images_in_gallery = $wpdb->get_var($sql);
									?>

									<h4>Profile Picture</h4>
									<a href="#" class="mediator-member-avatar <?php echo $images_in_gallery >= 9 ? 'limit-over' : ''; ?>">Upload Image</a>

									<a href="#" class="add_button add-avatar-button ca_custom_main_popup_trigger <?php echo $images_in_gallery ? 'have-images' : 'have-not-images'; ?>" data-popup="div.avatar-popup">From Gallery</a>
									<a href="#" class="mediator-member-avatar-remove"></a>
									<input type="hidden" name="avatar" value="<?php echo $avatar_id; ?>" />
									<p class="hint">Maximum 500Kb or 500px x 500px in size.</p>
								</div>

								<p class="field firstname">
									<label for="">First Name</label>
									<?php $form_fields::wp_edit_to_html('first_name'); ?>
								</p>

								<p class="field lastname">
									<label for="">Last Name</label>
									<?php $form_fields::wp_edit_to_html('last_name'); ?>
								</p>

								<p class="field title icon">
									<label for="">Title</label>
                                    <?php $form_fields::edit_to_html('title'); ?>
								</p>

								<p class="field hidden_profile icon">
								    <?php $form_fields::edit_to_html('hidden_profile'); ?>
									<label for="hidden_profile">Hide Profile?</label>
								</p>

							</div>



							<div class="right">
								<p class="field textarea bio">
									<label for="">Bio</label>
									<?php $form_fields::wp_edit_to_html('description'); ?>
								</p>

								<p class="field email icon">
									<label for="">Email</label>
									<?php $form_fields::wp_edit_to_html('email'); ?>
								</p>

								<p class="field location icon">
									<label for="">Location</label>
									<?php $form_fields::edit_to_html('location'); ?>
								</p>
							</div>
						</div>
					</div>

					<div class="fullblock education half">
						<div class="left">
							<div class="box">
							    <div class="box-heading">
								    <h4 class="exp_icon icon">Experience</h4>
<!--		                            <a href="#" class="add-repeater">Add</a>-->
		                        </div>

		                        <div style="display: block;">
		                        <?php $form_fields::edit_to_html('experience'); ?>
		                        </div>
							</div>
						</div>

						<div class="right">
							<div class="box">
		                        <div class="box-heading">
		                            <h4 class="edu_icon icon">Education</h4>
		                        </div>

		                        <?php $form_fields::edit_to_html('education'); ?>

							</div>
						</div>
					</div>

					<?php $spec_asso_serv = $member->is_premium_member( get_current_user_id() ); ?>
					<div class="fullblock box spec-asso-serv half">
						<div class="box-item spec left">
						    <div class="box-heading">
							    <h4 class="spec_icon icon">Specialisms</h4>
							    <?php if(!$spec_asso_serv): ?>
							    <a href="#" data-popup="div.ca-upgrade-profile-popup" class="ca_custom_main_popup_trigger upgrade-spec-serv-anchor">Upgrade to Unlock</a>
								<?php endif; ?>
		                    </div>
		                        <?php if( !$spec_asso_serv ): ?>
		                            <a href="#" data-popup="div.ca-upgrade-profile-popup" class="ca_custom_main_popup_trigger">
		                        <?php endif; ?>
							<div class="items">
		                        <div id="ca_magicsuggest_specialisms"></div>
							</div>
                                <?php if( !$spec_asso_serv ): ?>
                                    </a>
                                <?php endif; ?>
						</div>

                            
						<div class="box-item serv right">
						    <div class="box-heading">
		                        <h4 class="service_icon icon">Services Provided</h4>
		                        <?php if(!$spec_asso_serv): ?>
							    <a href="#" data-popup="div.ca-upgrade-profile-popup" class="ca_custom_main_popup_trigger upgrade-spec-serv-anchor">Upgrade to Unlock</a>
								<?php endif; ?>
		                    </div>
		                    <?php if( !$spec_asso_serv ): ?>
                                <a href="#" data-popup="div.ca-upgrade-profile-popup" class="ca_custom_main_popup_trigger">
                            <?php endif; ?>
							<div class="items">
		                        <div id="ca_magicsuggest"></div>
							</div>
							<?php if( !$spec_asso_serv ): ?>
                                </a>
                            <?php endif; ?>
						</div>
					</div>
					
					
					<div class="fullblock">
                        <div class="box-heading">
                        	<h4 class="icon organization_icon">Organization</h4>
                        </div>
                        
                        <?php
                        global $pagenow;
                        if($pagenow == 'users.php'): ?>
                        	<div class="items">
		                        <div id="ca_magicsuggest_organizations"></div>
							</div>
                        	<?php
                        	$organizations_data = isset($member->member_data->organizations) ? $member->member_data->organizations : [];
                        	$organizations_data = maybe_unserialize($organizations_data);

                        	$org_args = array(
                        		'post_type' 		=> 'organization',
                        		'post__in'			=> $organizations_data
                        	);
                        	$organizations = get_posts($org_args);

                        	if( $organizations && !empty($organizations_data)): ?>
			
							    <div class="asso-org half left">
								    <div class="a-box left box-collapsable">
				                        
									    <div class="entry-items">
									    	<?php $org_counter = 0; ?>
									    	<?php foreach($organizations as $org): ?>
									    	<div class="item <?php codeart_add_item_classes($org_counter++) ?>">
									    		<?php
									    		$org_thumbnail = get_the_post_thumbnail( $org, 'popular-mediator-avatar' );
									    		echo $org_thumbnail; ?>
									    		<h4 class="ttl"><?php echo apply_filters('the_title', $org->post_title); ?></h4>
									    	</div>
									    	<?php endforeach; ?>
									    </div>
								    </div> <!-- .a-box -->
							    </div> <!-- .asso-org -->

							<?php endif; ?>

                    	<?php else: ?>
                        	<a href="#" class="add_button add-organization-button ca_custom_main_popup_trigger" data-popup="div.organization-popup">Add Organization</a>
                    	<?php endif; ?>

					</div>

					<div class="bottom-buttons">
					    <?php 
                            global $current_user, $member;
                            get_currentuserinfo();

                            if( is_admin() && isset($_GET['member_id']) )
                            {
                            	$current_member = get_user_by( 'id', intval($_GET['member_id']) );
                            	$member_url = $current_member->data->user_nicename;
                            }
                            else
                            {
                            	$member_url = codeart_get_mediators_url($current_user->user_nicename);
                            }
                        ?>
						<a href="<?php echo $member_url; ?>" class="view-profile">View Profile</a>
				        <div class="submit_holder">
						    <input type="submit" value="Save Changes">
                        </div>
					</div>
				</form>








				<div class="register-popup register organization-popup">
					<div class="overlay"></div>
					<div class="ca_custom_popup_overlay"></div>
					<div class="ca_custom_popup_close close-popup"></div>
					<div class="popup-wrap">
						<div class="popup_logo"></div>
						<div class="entry">
							<div class="right">

								<p>Please contact <a href="mailto: <?php echo antispambot(get_field('support_email_address', 'option')); ?>">Support@MediatorAcademy.com</a> for more information.</p>

							</div> <!-- .right -->
						</div> <!-- .entry -->
					</div> <!-- .popup-wrap -->
				</div>










				<!--<div class="organization-popup">
					<div class="organization-popup-wrap">
						<div class="org-wrap">
							<p>Please contact <a href="mailto: <?php echo antispambot(get_field('support_email_address', 'option')); ?>">Support@MediatorAcademy.com</a> for more information.</p>
						</div>
					</div>
					
					<div class="ca_custom_popup_overlay"></div>
				</div>-->

				<script type="text/javascript">
				jQuery(document).ready(function($) {
					var org_button = $('body').find('a.add-organization-button');

					var org_popup = $('body').find('.organization-popup');
					var org_overlay = org_popup.find('.overlay');
					var orf_wrap = org_popup.find('.org-wrap');

					org_button.on('click', function(e) {
						
					});
				});
				</script>

			</div>
		</div>
	</div>
	<?php
}






add_action('wp_head', 'codeart_add_no_follow_for_hidden_profiles');
function codeart_add_no_follow_for_hidden_profiles()
{
	if( !is_author() )
		return;

	global $member, $author;
	if( is_numeric($author) && $member->is_hidden_member($author))
		printf('<meta name="robots" content="noindex">');
}








add_filter('user_row_actions', 'codeart_user_action_links', 10, 2);
function codeart_user_action_links($actions, $user_object)
{
	$actions['member_edit'] = "<a class='member_edit' href='" . admin_url( "users.php?page=edit-member&amp;member_id=$user_object->ID") . "'>" . 'Advanced Edit' . "</a>";
	return $actions;
}

add_filter('body_class', 'codeart_profile_edit_body_classes_global');
function codeart_profile_edit_body_classes_global( $classes )
{
	global $member;
	$classes[] = $member->is_premium_member() ? 'premium-member' : 'codeart-not-premium';
	$classes[] = wp_is_mobile() ? 'codeart-mobile' : 'codeart-not-mobile';

	// var_dump($member->get_watched_video_count());

	if( !$member->is_premium_member() )
	{
		if ($member->get_watched_video_count() > 0 && $member->get_watched_video_count() < 10) {
			$classes[] = 'codeart-bar-visible';
		}
	}

	return $classes;
}









//add_action ('user_register', 'codeart_add_data_after_user_register_via_admin', 10, 1);
function codeart_add_data_after_user_register_via_admin($user_id)
{
	if( $user_id && is_admin() )
	{
		global $member;

		try
		{
			$userdata = array();
			$userdata['user_id'] = $user_id;
			$results = $member->add($userdata);
		}
		catch(Exception $ex)
		{
			// $results = $ex->getMessage();
		}
	}
}











add_action('wp', 'codeart_check_if_member_has_data');
function codeart_check_if_member_has_data()
{
	if( !is_user_logged_in() )
		return;

	global $member;
	$user_id = get_current_user_id();
	if( !$member->is_member_exists($user_id) )
	{
		$member->add(array('user_id' => $user_id));
	}
}





// add_action( 'genesis_before_header', 'codeart_update_avatars' );
function codeart_update_avatars()
{
	global $wpdb;

	$sql = sprintf(
		"UPDATE mwdpb_mediator_members_data SET avatar = %d WHERE user_id = %d",
		310, 253
	);
	$q1 = $wpdb->query($sql);

	$sql = sprintf(
		"UPDATE mwdpb_mediator_members_data SET avatar = %d WHERE user_id = %d",
		311, 254
	);
	$q2 = $wpdb->query($sql);

	var_dump($q1);
	var_dump($q2);
}







function codeart_filter_array( $arr )
{
	if( !is_array($arr) )
		return false;

	if( count($arr) < 1 )
		return false;

	$arr = $arr[0];
	foreach($arr as $key => $val)
		if( !empty($val) )
			return true;

	return false;
}








/* Display custom column */
add_action( 'manage_topics_posts_custom_column' , 'codeart_add_mediators_to_table_list', 10, 2 );
function codeart_add_mediators_to_table_list( $column, $post_id )
{
    if ($column == 'mediator')
    {
    	$mediator 	= get_field('interview_mediator', $post_id);
    	$audio 		= get_field('download_audio', $post_id);
    	$video 		= get_field('download_video', $post_id);
    	$pdf 		= get_field('download_pdf', $post_id);

    	// _debug($mediator);

        echo sprintf(
        	'<p>Mediator: %s</p>
        	<p>Audio: %s</p>
        	<p>Video: %s</p>
        	<p>PDF: %s</p>',
        	isset($mediator['display_name']) ? $mediator['display_name'] : '',
        	$audio ? '+' : '',
        	$video ? '+' : '',
        	$pdf ? '+' : ''
        );
    }
}


/* Add custom column to post list */
add_filter( 'manage_topics_posts_columns' , 'codeart_add_mediators_to_table_list_heading' );
function codeart_add_mediators_to_table_list_heading( $columns )
{
    return array_merge(
    	$columns,
    	array(
    		'mediator' => 'Mediator'
    	)
    );
}













/* Display custom column */
add_action( 'manage_topics_posts_custom_column' , 'codeart_add_thumbnail_to_topics_list', 10, 2 );
function codeart_add_thumbnail_to_topics_list( $column, $post_id )
{
    if ($column == 'thumbnail' && has_post_thumbnail( $post_id ))
    {
    	echo get_the_post_thumbnail( $post_id, 'thumbnail' );
    }
}


/* Add custom column to post list */
add_filter( 'manage_topics_posts_columns' , 'codeart_add_thumbnail_to_topics_list_heading' );
function codeart_add_thumbnail_to_topics_list_heading( $columns )
{
    return array_merge(
    	$columns,
    	array(
    		'thumbnail' => 'Thumbnail'
    	)
    );
}












//define('ALLOW_UNFILTERED_UPLOADS', true);



add_action('pre_get_posts','codeart_users_own_attachments');
function codeart_users_own_attachments( $wp_query_obj )
{
	global $current_user, $pagenow;

	if( !is_a( $current_user, 'WP_User') )
		return;

	if( !in_array( $pagenow, array( 'upload.php', 'admin-ajax.php' ) ) )
		return;

	// if( !current_user_can('delete_pages') )
 //        $wp_query_obj->set('author', $current_user->ID );

    return;
}









add_action('wp_head', 'codeart_add_default_image_as_variable', 50);
add_action('admin_head', 'codeart_add_default_image_as_variable', 50);
function codeart_add_default_image_as_variable()
{
	$default_image = get_field('option_default_thumbnail', 'option');
	if($default_image): ?>
	<script type="text/javascript">
	var default_image = '<?php echo json_encode($default_image["sizes"]); ?>';
	default_image = jQuery.parseJSON(default_image);
	</script>
	<?php endif;
	// _debug($default_image);
}





add_action('wp', 'codeart_set_invited_by_cookie');
function codeart_set_invited_by_cookie()
{
	if (isset($_GET['ref']) && !isset($_COOKIE['invited_by'])) {
		setcookie('invited_by', $_GET['ref'], time() + (86400 * 30), "/");
	}
}


















add_action( 'wp_head', 'ajax_search_javascript' );
function ajax_search_javascript()
{
	$all_types 			= codeart_get_search_types();
	$query_arguments 	= codeart_get_search_args();
	$search_paramethers = codeart_get_search_paramethers();
	extract($search_paramethers); ?>

	<script type="text/javascript" >
	var all_types 	= <?php echo json_encode($all_types); ?>;
	var search_args = <?php echo json_encode($query_arguments); ?>;
	var search_type = '<?php echo $search_type; ?>';

	var timer;




	function codeart_get_url_params()
	{
		var url_data = window.location;
		var search = url_data.search;
		search = search.substring(1);

		var splited_params = search.split('&');
		var params = {};
		for(i=0; i<splited_params.length; i++)
		{
			var param = splited_params[i].split('=');
			params[param[0]] = param[1];
		}

		return params;
	}

	function codeart_set_url_params(category, specialism, services, query)
	{
		if (is_home) { return; }

		var new_params = [];
		if (category) { new_params.push('category='+category); };
		if (specialism) { new_params.push('specialism='+specialism); };
		if (services) { new_params.push('services='+services); };
		if (query) { new_params.push('query='+query); };

		window.history.pushState("", "", '?' + new_params.join('&'));
	}



	jQuery(document).ready(function($) {

		var button_search = $('body').find('input#search-button');

		function codeart_check_search_taxonomies(cb_wrap) {

			var box_wrap 	= cb_wrap.closest('div.box');
			var checkboxes 	= box_wrap.find("input[type='checkbox']");

			var cb 		= cb_wrap.closest("div.checkbox").find('input');
			var checked = cb.is(':checked');

			if( cb.hasClass('all') && cb.is(':checked') )
				return;

			var group_wrap = cb_wrap.closest('div.cb-item');
			var group_wrap_child = group_wrap.find('div.child-term-items');
			if (group_wrap_child.length > 0 && cb_wrap.closest('div.child-term-items').css('display') != 'block') {
				group_wrap_child.slideToggle();
			}

			if ( typeof cb.data('id') !== "undefined" ) {
				checkboxes.first().prop('checked', false);
			} else {
				checkboxes.prop('checked', false);
				checkboxes.first().prop('checked', true);
			}

			cb.prop('checked', !checked);

			var checked_count = 0;
			$.each(checkboxes, function(index, value) {
				var is_checked = $(value).is(':checked');
				if (is_checked) {
					checked_count++;
				};
			});

			if (!checked_count) {
				checkboxes.first().prop('checked', true);
			};

			button_search.trigger('click');
		}

		var tax_cat_checkboxes = $('body').find('.box-ajax-search .checkbox a');
		var all_tax_item = $('body').find('input.tax-cb');

		tax_cat_checkboxes.on('click', function(e) {
			e.preventDefault();

			if (search_args.hasOwnProperty('number'))
			{
				search_args.offset = 0;
			}
			else
			{
				search_args.paged = 1;
			}

			codeart_check_search_taxonomies( $(this) );
		});


		var search_form = $('body').find('form#main-search-form');
		var search_input = search_form.find('input#search');

		var target_div = $('body').find('div.results');
		var loadingCls = 'loading';

		// Home search
		var home_search_input = $('body').find('input.trigger-live-search');
        
        var typingTimer;
        var doneTypingInterval = 700;

		home_search_input.on('keyup', function(e) {

			if (e.which != 8 && (e.which < 48 || e.which > 90)) { return; }
            
            var val = this.value;
            var sbutton = $('body').find('a.view-mediators-and-topics');
            var sbutton_data = sbutton.data('url');
            if (val) {
            	sbutton.attr('href', sbutton_data + '?query=' + val);
            } else {
            	sbutton.attr('href', sbutton_data);
            }

			clearTimeout(typingTimer);
            
            typingTimer = setTimeout(function(){
                home_search_input.closest('.form-live-search').addClass(loadingCls);
                search(val);
            }, doneTypingInterval);
            
		}); /* End Home Search */
        
        home_search_input.on('keydown', function(){
            clearTimeout(typingTimer);
        });

		// Main search
		search_form.on('submit', function(e) {
			search();
			return false;
		}); /* End Main search */




		$('body').on('click', 'a.ajax-more-pagination', function(e) {
			e.preventDefault();
			
			if (search_args.hasOwnProperty('number'))
			{
				search_args.offset = (parseInt(search_args.offset) + 1) + 10;
			}
			else
			{
				search_args.paged = parseInt(search_args.paged) + 1;
			}

			search(true, $(this));
		});



		function search(load_more, more_button)
		{
			target_div.closest('div.search-wrap').addClass(loadingCls);
			
			var tmp = $('body').find('input#search');
			search_val = tmp.val();

			if(search_args.meta_query && search_args.meta_query !== 'undefined')
			{
				$.each(search_args.meta_query, function(index, value) {
					if( 'undefined' != value['value'] )
						value['value'] = search_val;
				});
			}

			// Category
			// box box-ajax-search search-categories-box
			var tax_cat_checkboxes_tmp = $('body').find('.box-ajax-search.box-slug-topic_cat a');
			var tax1 = Array();

			$.each(tax_cat_checkboxes_tmp, function(index, value) {
				var cb = $(this).closest('div.checkbox').find("input[type='checkbox']");
				var term_slug = cb.data('id');
				if (cb.is(':checked') && (term_slug && term_slug != null && term_slug != 'undefined')) {
					tax1.push(term_slug);
				};
			});


			// specialism
			// box box-ajax-search search-categories-box
			var tax2 = Array();
			var tax_cat_checkboxes_tmp = $('body').find('.box-ajax-search.box-slug-specialism a');
			
			$.each(tax_cat_checkboxes_tmp, function(index, value) {
				var cb = $(this).closest('div.checkbox').find("input[type='checkbox']");
				var term_slug = cb.data('id');
				if (cb.is(':checked') && (term_slug && term_slug != null && term_slug != 'undefined')) {
					tax2.push(term_slug);
				};
			});




			// services
			// box box-ajax-search search-categories-box
			var tax3 = Array();
			var tax_cat_checkboxes_tmp = $('body').find('.box-ajax-search.box-slug-service a');
			
			$.each(tax_cat_checkboxes_tmp, function(index, value) {
				var cb = $(this).closest('div.checkbox').find("input[type='checkbox']");
				var term_slug = cb.data('id');
				if (cb.is(':checked') && (term_slug && term_slug != null && term_slug != 'undefined')) {
					tax3.push(term_slug);
				};
			});

			search_args.s = search_val;

			var cat_url 	= '';
			var spec_url 	= '';

			codeart_set_url_params(tax1.join(','), tax2.join(','), tax3.join(','), search_val);

            var static_links = $('body').find('.main-filter a');
            // console.log(static_links);
            $.each(static_links, function(index, value) {
            	var static_links_data = $(this).data('url');
            	if (search_val) {
            		$(this).attr('href', static_links_data + '?query=' + search_val);
            	} else {
            		$(this).attr('href', static_links_data);
            	}
            });

            <?php
            $is_all = 0;
            $stype = get_query_var('searchtype');
            if (is_page_template('template-search.php') && empty($stype)):
            	$is_all = 1;
            endif; ?>

			var data = {
				'action': 'ajax_search',
				'tax1': tax1, // categories
				'tax2': tax2, // specialisms
				'tax3': tax3, // services
				's': search_val,
				'all_types': all_types,
				'search_args': search_args,
				'search_type': search_type,
				'is_home': <?php echo (is_home() || is_front_page()) ? 1 : 0; ?>,
				'is_all': <?php echo $is_all; ?>
			};

			console.log('testing1');

			jQuery.post(ajaxurl, data, function(response) {

				if (!search_type) {
					var response_output = $('<div>').append($.parseHTML(response));

					var mediator_div 	= $('body').find('.results.results-mediators');
					var topics_div 		= $('body').find('.results.results-topics');

					mediator_div.html('');
					topics_div.html('');

					mediator_div.html( response_output.find('.results.results-mediators').html() );
					topics_div.html( response_output.find('.results.results-topics').html() );
                
                    home_search_input.closest('.form-live-search').removeClass(loadingCls);
				} else {
					if (load_more)
					{
						console.log('testing');
						var source = $(response);
						source.find('h4.main--results-heading').remove();
						source.find('a.ajax-load-more-button-new').remove();
						more_button.before( source.html() );
					}
					else
					{
						target_div.html( $(response).html() );
					}
				}

				target_div.closest('div.search-wrap').removeClass(loadingCls);
				codeart_check_paginate_button();
			});
		}

		codeart_check_paginate_button();

		function codeart_check_paginate_button()
		{
			if( $('body.codeart-search').find('.result-item').length < 10 ) {
				$('body').addClass('no-search-results');
			} else {
				$('body').removeClass('no-search-results');
			}
		}

	});
	</script> <?php
}









function codeart_get_search_type_param()
{
	$search_type = get_query_var('searchtype');
	// $search_type = (is_home() || is_front_page()) ? 'mediators' : $search_type;
	return $search_type;
}



function codeart_get_search_paramethers($hidden_profiles = true)
{
	global $member;

	$search_type 		= codeart_get_search_type_param();
	$search_category 	= get_query_var('searchcategory');
	$search_text 		= get_query_var('query');

	$url_type 		= isset($_GET['type']) 			? $_GET['type'] 		: '';
	$url_category 	= isset($_GET['category']) 		? $_GET['category'] 	: '';
	$url_specialism = isset($_GET['specialism']) 	? $_GET['specialism'] 	: '';
	$url_service 	= isset($_GET['services']) 		? $_GET['services'] 	: '';

	$current_page		= isset($_GET['item-page']) ? intval($_GET['item-page']) : 1;
	$posts_per_page 	= 10;

	$hidden_profile_ids = array();

	if ($hidden_profiles) {
		$hidden_profiles = $member->get_hidden_profile_ids();
		if( is_array($hidden_profiles) ):
			foreach( $hidden_profiles as $hidden_profile_id ):
				$hidden_profile_ids[] = $hidden_profile_id->user_id;
			endforeach;
		endif;
	}

	return [
		'search_type' 			=> $search_type,
		'search_category' 		=> $search_category,
		'search_text' 			=> $search_text,
		'current_page' 			=> $current_page,
		'posts_per_page' 		=> $posts_per_page,
		'hidden_profile_ids' 	=> $hidden_profile_ids,
		'url_type' 				=> $url_type,
		'url_category' 			=> $url_category,
		'url_specialism' 		=> $url_specialism,
		'url_service' 			=> $url_service,
	];
}






function codeart_get_search_types( $json = false )
{
	$search_params = codeart_get_search_paramethers();
	extract($search_params);

	$search_type_check = get_query_var('searchtype');
	$posts_per_page = is_page_template('template-search.php') && empty($search_type_check) ? 5 : $posts_per_page;

	$all_types = array(
		'topics' => array(
			'post_type' 		=> 'topics',
			'posts_per_page' 	=> $posts_per_page,
			's' 				=> $search_text,
			'orderby' 			=> 'post_parent title',
			'order'				=> 'ASC',
			'paged' 			=> $current_page,
		),
		'mediators' => array(
			'number' => $posts_per_page,
			'offset' => (($current_page - 1) * $posts_per_page),
			'exclude' => $hidden_profile_ids,
			'meta_query' => array(
				'relation' => 'OR',
				array(
					'key'     => 'first_name',
					'value'   => $search_text,
					'compare' => 'LIKE'
				),
				array(
					'key'     => 'last_name',
					'value'   => $search_text,
					'compare' => 'LIKE'
				)
			)
		),
		
	);

	if (!empty($url_category) || !empty($url_specialism)) {
		$all_types['topics']['tax_query'] = array();
	}

	if (!empty($url_category) && !empty($url_specialism)) {
		$all_types['topics']['tax_query']['relation'] = 'OR';
	}

	if (!empty($url_category)) {
		$all_types['topics']['tax_query'][] = array(
			'taxonomy' => 'topic_cat',
			'field'    => 'slug',
			'terms'    => empty($url_category) ? [] : explode(',', $url_category),
			'operator' => 'IN',
		);
	}

	if (!empty($url_specialism)) {
		$all_types['topics']['tax_query'][] = array(
			'taxonomy' => 'specialism',
			'field'    => 'slug',
			'terms'    => empty($url_specialism) ? [] : explode(',', $url_specialism),
			'operator' => 'IN',
		);
	}

	return $json ? json_encode($all_types) : $all_types;
}







function codeart_get_search_args( $json = false )
{
	$search_type 	= codeart_get_search_type_param();
	$all_types 		= codeart_get_search_types();

	$return = isset($all_types[$search_type]) ? $all_types[$search_type] : array();
	return $json ? json_encode($return) : $return;
}










add_action( 'wp_ajax_ajax_search', 'ajax_search_callback' );
add_action( 'wp_ajax_nopriv_ajax_search', 'ajax_search_callback' );
function ajax_search_callback()
{
	$all_types 		= isset($_POST['all_types']) 	? $_POST['all_types'] 		: [];
	$search_args 	= isset($_POST['search_args']) 	? $_POST['search_args'] 	: [];
	$search_type 	= isset($_POST['search_type']) 	? $_POST['search_type'] 	: '';
	$is_home 		= isset($_POST['is_home']) 		? $_POST['is_home'] 		: 0;

	$tax1 = isset($_POST['tax1']) ? $_POST['tax1'] : [];
	$tax2 = isset($_POST['tax2']) ? $_POST['tax2'] : [];
	$tax3 = isset($_POST['tax3']) ? $_POST['tax3'] : [];

	$s = isset($_POST['s']) ? $_POST['s'] : [];

	$is_all = isset($_POST['is_all']) ? intval($_POST['is_all']) : 0;

	codeart_ajax_search_func($all_types, $search_args, $search_type, $is_home, true, $tax1, $tax2, $tax3, $s, $is_all);

	wp_die();
}









function codeart_get_user_id_by_specialisms( $search = '', $specialisms_ids = [], $services_ids = [])
{
	global $wpdb;
	
	$userdata_table = $wpdb->prefix . 'mediator_members_data';

	$specs_sql = [];
	if (is_array($specialisms_ids)) {
		foreach($specialisms_ids as $si) {
			$specs_sql[] = "(UT.specialisms REGEXP '.*;s:[0-9]+:\"$si\".*')";
		}
	}

	if (is_array($services_ids)) {
		foreach($services_ids as $sp) {
			$specs_sql[] = "(UT.services_provided REGEXP '.*;s:[0-9]+:\"$sp\".*')";
		}
	}

	$specs_sql = implode(' OR ', $specs_sql);

	$sql_tmp = "
		SELECT U.ID 
		FROM mwdpb_users AS U 
		INNER JOIN mwdpb_usermeta AS UM ON U.ID = UM.user_id 
		INNER JOIN mwdpb_mediator_members_data AS UT ON U.ID = UT.user_id 
		WHERE (
			(UM.meta_key = 'first_name' AND UM.meta_value LIKE '%$search%') 
			OR 
			(UM.meta_key = 'last_name' AND UM.meta_value LIKE '%$search%')
		) 
	";

	if ($specs_sql) {
		$sql_tmp .= " AND ($specs_sql)";
	}

	// mwdpb_usermeta | Table
	// mwdpb_capabilities | Key
	$sql_tmp .= " AND (UT.hidden_profile = 0)";

	// $sql_tmp .= " ORDER BY FIELD(UM.meta_value, 'vip_member', 'premium_member', 'free_member', 'subscriber') DESC";
	// $sql_tmp = " ORDER BY U.ID DESC";

	// var_dump($sql_tmp);

	$results = $wpdb->get_results($sql_tmp, ARRAY_N);

	$return = [];
	if ($results) {
		foreach($results as $key => $val)
			$return[] = $val[0];
	}

	return $return;
}





function codeart_get_specialisms_ids_by_slug( $specialisms_slugs = [], $services_ids = [] )
{
	if (empty($specialisms_slugs)) {
		return false;
	}

	$ids = [];

	$specs = get_terms('specialism', ['hide_empty' => false]);
	foreach($specs as $sp) {
		if (in_array($sp->slug, $specialisms_slugs)) {
			$ids[] = $sp->term_id;
		}
	}

	return $ids;
}






function codeart_get_services_ids_by_slug( $services_ids = [] )
{
	if (empty($services_ids)) {
		return false;
	}

	$ids = [];

	$serv = get_terms('service', ['hide_empty' => false]);
	foreach($serv as $sp) {
		if (in_array($sp->slug, $services_ids)) {
			$ids[] = $sp->term_id;
		}
	}

	return $ids;
}










function codeart_ajax_search_func($all_types = [], $query_arguments = [], $search_type = '', $is_home = false, $is_ajax = false, $tax1 = [], $tax2 = [], $tax3 = [], $s = '', $is_all = 0)
{
	$title = ucfirst($search_type);

	if ($is_ajax && $search_type != 'mediators') {
		unset($query_arguments['tax_query']);

		if (!empty($tax1) || !empty($tax2)) {
			$query_arguments['tax_query'] = array();
		}

		if (!empty($tax1) && !empty($tax2)) {
			$query_arguments['tax_query']['relation'] = 'OR';
		}

		if (!empty($tax1)) {
			$query_arguments['tax_query'][] = array(
				'taxonomy' => 'topic_cat',
				'field'    => 'slug',
				'terms'    => $tax1,
				'operator' => 'IN',
			);
		}

		if (!empty($tax2)) {
			$query_arguments['tax_query'][] = array(
				'taxonomy' => 'specialism',
				'field'    => 'slug',
				'terms'    => $tax2,
				'operator' => 'IN',
			);
		}
	}

	if (!empty($search_type)) {
		if ($search_type == 'mediators') {
			unset($query_arguments['meta_query']);

			$specs 		= codeart_get_specialisms_ids_by_slug($tax2);
			$services 	= codeart_get_services_ids_by_slug($tax3);

			$query_arguments['include'] = codeart_get_user_id_by_specialisms($s, $specs, $services);

			$offset = isset($query_arguments['offset']) ? $query_arguments['offset'] : 0;
			codeart_search_grid_for_mediators_custom($title, $s, $specs, $services, $is_home, $offset, $is_all);
		} else {
			codeart_search_grid($title, $query_arguments, $is_home, $is_all);
		}
	} else {
		foreach ($all_types as $type => $search_args) {
			if (empty($search_args))
				continue;

			$title = ucfirst($type);

			if ($type == 'mediators') {
				unset($search_args['meta_query']);

				$specs = codeart_get_specialisms_ids_by_slug($tax2);
				$services 	= codeart_get_services_ids_by_slug($tax3);
				
				$search_args['include'] = codeart_get_user_id_by_specialisms($s, $specs, $services);
				
				//codeart_search_grid_for_mediators($title, $search_args);
				$offset = isset($query_arguments['offset']) ? $query_arguments['offset'] : 0;
				codeart_search_grid_for_mediators_custom($title, $s, $specs, $services, $is_home, $offset, $is_all);
			} else {

				if ($is_ajax) {
					$search_args['s'] = $s;

					unset($search_args['tax_query']);

					if (!empty($tax1) || !empty($tax2)) {
						$search_args['tax_query'] = array();
					}

					if (!empty($tax1) && !empty($tax2)) {
						$search_args['tax_query']['relation'] = 'OR';
					}

					if (!empty($tax1)) {
						$search_args['tax_query'][] = array(
							'taxonomy' => 'topic_cat',
							'field'    => 'slug',
							'terms'    => $tax1,
							'operator' => 'IN',
						);
					}

					if (!empty($tax2)) {
						$search_args['tax_query'][] = array(
							'taxonomy' => 'specialism',
							'field'    => 'slug',
							'terms'    => $tax2,
							'operator' => 'IN',
						);
					}
				}

				codeart_search_grid($title, $search_args, $is_home, $is_all);
			}
		}
	}

}






/**
 * Function to print the search grid
 * 
 * @param string $title Heading title
 * @param array $args WP_Query args 
 */
function codeart_search_grid( $title, $search_args = [], $is_home = false, $is_all = 0 )
{
	if( strtolower($title) == 'courses')
		return;

	if (isset($search_args['tax_query']) && 
		count($search_args['tax_query']) == 1 && 
		isset($search_args['tax_query'][0]['terms']) && 
		empty($search_args['tax_query'][0]['terms'])) {
		unset($search_args['tax_query']);
	}

	if ($is_home) {
		$search_args['posts_per_page'] = 5;
	}

	$results = new WP_Query($search_args);

	printf('<div class="results results-topics">');
	printf('<h4 class="main--results-heading">%s</h4>', $title);
	if( $results->have_posts() ):
		while( $results->have_posts() ): $results->the_post(); global $post; ?>
		<div class="result-item">
			<a href="<?php the_permalink(); ?>">
				<?php
                    $thumbnail_size = ($is_home) ? 'topics-thumbnail' : 'topics-thumbnail';
					if ($post->post_parent) {
						printf('<div class="cam-icon-wrap">');
							printf('<div class="camera-icon"></div>');
							echo get_the_post_thumbnail( $post->post_parent, $thumbnail_size );
						printf('</div>');
					} else {
						printf('<div class="cam-icon-wrap">');
							printf('<div class="camera-icon mic-icon"></div>');
							the_post_thumbnail( $thumbnail_size );
						printf('</div>');
					}
				?>

				<?php
				global $post;
				if( is_page_template('template-interviews.php')):
					codeart_print_video_overlay( $post );
				endif; ?>
			</a>
			
			<a href="<?php the_permalink(); ?>">
				<h4 class="title"><?php the_title(); ?></h4>
			</a>

			<?php codeart_print_grid_rating($post->ID); ?>
			
			<?php if( !$is_home ): ?>
			<div class="entry-topic-content">
				<div class="aditional-info">
					<?php
					if (get_field('interview_mediator', $post->ID)) {
						$mediator_author = get_field('interview_mediator', $post->ID);
						printf('<p class="info-by">By: %s</p>', $mediator_author['display_name']);
					}
					?>
					<?php if($post->post_parent == 0): ?>
						<?php $video_count = codeart_get_child_topic_count($post->ID); ?>
						<?php if($video_count): ?>
						<p class="info-by">Videos: <?php echo $video_count; ?></p>
						<?php endif; ?>
					<?php endif; ?>

					<?php if( get_field('topic_video_length') ): ?>
					<p class="info-by">Length: <?php echo codeart_mins_to_time( intval(get_field('topic_video_length'))); ?></p>
					<?php endif; ?>
				</div>

				<?php
					$content = get_the_content();
					$content = strip_tags($content);
					$content = strip_shortcodes( $content );

					$perma = sprintf('<a href="%s" class="view-mediator">Watch Video</a>', get_permalink(get_the_ID()));

					echo apply_filters('the_title', wpautop( substr($content, 0, 200) . '... '));
					echo $perma;
				?>
			</div>
			<?php endif; ?>
		</div>
		<?php endwhile;

		if (!$is_home):
			$cls = '';
			$url = get_bloginfo('url') . '/search/topics/';
			if ($is_all == 0) {
				$cls = 'ajax-more-pagination';
				$url = '#';
			}

			printf('<a href="%s" class="ajax-more-topics %s default-green-btn ajax-load-more-button-new" data-page="2">More Topics</a>', $url, $cls);
		endif;
	else:
		printf('<div class="search-no-found"><p>No results found.</p></div>');
	endif;

	wp_reset_query();

	printf('</div> <!-- .results -->');
} // End of function codeart_search_grid();








function codeart_get_child_topic_count($parent_post_id)
{
	global $wpdb;
	$sql = "select count(ID) from $wpdb->posts where post_parent = $parent_post_id and post_type = 'topics' and post_status = 'publish'";
	return intval($wpdb->get_var($sql));
}




function codeart_print_grid_rating( $post_id, $is_home = false )
{
	$avg_rating = codeart_get_video_rating_avg($post_id);
	$avg_rating = number_format ($avg_rating, 1);

	
	if ($is_home): ?>
	<div class="rating-stars search-rating-stars rated">
		<?php for($i=1; $i<=5; $i++): ?>
			<div class="star <?php echo $avg_rating >= $i ? 'hovered' : ''; ?>">
				<div class="off icon"><?php ca_get_svg('rating_off.svg'); ?></div>
				<div class="on icon"><?php ca_get_svg('rating_on.svg'); ?></div>
			</div>
		<?php endfor; ?>
	</div>
	<?php else: ?>
	<?php $percetange = (floatval($avg_rating) / 5) * 100; ?>
	<div class="one-star-rating">
		<div class="icon-holder">
			<span><?php echo $avg_rating; ?></span>
			<div class="off icon"><?php ca_get_svg('rating_off.svg'); ?></div>
			<div class="on icon"  style="width: <?php echo $percetange; ?>%;"><?php ca_get_svg('rating_on.svg'); ?></div>
		</div>
	</div>
	<?php endif;
}





/**
 * Add support for the "display_name" search column in WP_User_Query
 * 
 * @see http://wordpress.stackexchange.com/a/166369/26350
 */
add_filter( 'user_search_columns', function( $search_columns ) {
    $search_columns[] = 'display_name';
    return $search_columns;
} );


add_action( 'pre_user_query', 'codeart_display_name_find' );
function codeart_display_name_find( $query )
{
	global $wpdb;

	/* you don't say where the name comes from - this assumes a $_POST field */
	$display_name = isset($_GET['query']) ? $_GET['query'] : false;
    if (empty($display_name)) {
    	return;
    }

    $query->query_where .= $wpdb->prepare( " OR $wpdb->users.display_name LIKE %s", '%' . like_escape( $display_name ) . '%' );
}







/**
 * Function to print the search grid
 * 
 * @param string $title Heading title
 * @param array $args WP_Query args 
 */
function codeart_search_grid_for_mediators( $title, $args = [] )
{
	global $member;

	$mediators_query = new WP_User_Query( $args );

	$mediators = $mediators_query->get_results();

	printf('<div class="results results-mediators">');
	printf('<h4 class="main-results-heading">%s</h4>', $title);
	if( $mediators ):
		foreach( $mediators as $mediator ): ?>
		<div class="result-item">
			<a href="<?php echo codeart_get_mediators_url($mediator->user_nicename); ?>">
				<?php $member->print_avatar($mediator->data->ID, 'thumbnail'); ?>
			</a>
			<a href="<?php echo codeart_get_mediators_url($mediator->user_nicename); ?>">
				<h4 class="title"><?php echo $mediator->data->display_name; ?> (User ID: <?php echo $mediator->ID; ?>)</h4>
			</a>
			
			<a href="<?php echo codeart_get_mediators_url($mediator->user_nicename); ?>" class="view-mediator">
				View Mediator
			</a>
		</div>
		<?php endforeach;
	else:
		printf('<div class="search-no-found"><p>No mediators found.</p></div>');
	endif;

	printf('</div> <!-- .results -->');
} // End of function codeart_search_grid_for_mediators();








/**
 * Function to print the search grid
 * 
 * @param string $title Heading title
 * @param array $args WP_Query args 
 */
function codeart_search_grid_for_mediators_custom( $title, $search, $specialisms_ids = [], $services_ids = [], $is_home = false, $offset = 0, $is_all = 0 )
{
	global $member, $wpdb;

	$userdata_table = $wpdb->prefix . 'mediator_members_data';

	$specs_sql = [];
	if (is_array($specialisms_ids)) {
		foreach($specialisms_ids as $si) {
			$specs_sql[] = "(UT.specialisms REGEXP '.*;s:[0-9]+:\"$si\".*')";
		}
	}

	if (is_array($services_ids)) {
		foreach($services_ids as $sp) {
			$specs_sql[] = "(UT.services_provided REGEXP '.*;s:[0-9]+:\"$sp\".*')";
		}
	}

	$specs_sql = implode(' OR ', $specs_sql);

	if ($specs_sql) {
		$specs_sql = " ($specs_sql) AND ";
	}

	$posts_per_page = ($is_home) ? 4 : 10;
	$posts_per_page = is_page_template('template-search.php') && $is_all ? 5 : $posts_per_page;

	$first_name_search = explode(' ', $search);
	$first_name_search = $first_name_search[0];
/*
	$sql_tmp = "
SELECT U.ID, U.user_nicename, UT.customer_id, U.display_name, UM.meta_value, UM2.meta_value, 
	CASE 
		WHEN UM3.meta_value LIKE '%administrator%' 	THEN 'administrator' 
		WHEN UM3.meta_value LIKE '%vip_member%' 	THEN 'vip_member' 
		WHEN UM3.meta_value LIKE '%premium_member%' THEN 'premium_member' 
		WHEN UM3.meta_value LIKE '%free_member%' 	THEN 'free_member' 
		WHEN UM3.meta_value LIKE '%subscriber%' 	THEN 'subscriber' 
	END AS capabilities, 
	UT.title, UM4.meta_value AS description 
FROM mwdpb_users AS U
LEFT JOIN mwdpb_usermeta AS UM 
	ON (U.ID = UM.user_id AND UM.meta_key = 'first_name' AND UM.meta_value LIKE '%$first_name_search%') 
LEFT JOIN mwdpb_usermeta AS UM2 
	ON (U.ID = UM2.user_id AND UM2.meta_key = 'last_name' AND UM2.meta_value LIKE '%$search%') 
LEFT JOIN mwdpb_usermeta AS UM3 
	ON (U.ID = UM3.user_id AND UM3.meta_key = 'mwdpb_capabilities') 
LEFT JOIN mwdpb_usermeta AS UM4 
	ON (U.ID = UM4.user_id AND UM4.meta_key = 'description') 
LEFT JOIN mwdpb_mediator_members_data AS UT 
	ON U.ID = UT.user_id 
WHERE 
	UT.hidden_profile < 1 AND $specs_sql (UM.meta_value is not null OR UM2.meta_value is not null) 
ORDER BY (CASE 
	WHEN UM3.meta_value LIKE '%administrator%' 	THEN 10 
	WHEN UM3.meta_value LIKE '%vip_member%' 	THEN 2 
	WHEN UM3.meta_value LIKE '%premium_member%' THEN 3 
	WHEN UM3.meta_value LIKE '%free_member%' 	THEN 4 
	WHEN UM3.meta_value LIKE '%subscriber%' 	THEN 5 
	ELSE 100 END) ASC, RAND() 
LIMIT $offset, $posts_per_page
	";

*/

$first_name_search 	= [];
$last_name_search 	= [];
$display_name_search = '';

$search_prepared = explode(' ', $search);

foreach ($search_prepared as $sp) {
	$first_name_search[] = "UM.meta_key = 'first_name' AND UM.meta_value LIKE '%$sp%'";
}
$first_name_search = implode(' OR ', $first_name_search);

foreach ($search_prepared as $sp) {
	$last_name_search[] = "UM2.meta_key = 'last_name' AND UM2.meta_value LIKE '%$sp%'";
}
$last_name_search = implode(' OR ', $last_name_search);

/*
if (count($search_prepared) > 1) {
	$search_text = $search_prepared[0];
	$first_name_search = "UM.meta_key = 'first_name' AND UM.meta_value LIKE '%$search_text%'";
	$last_name_search = "UM2.meta_key = 'last_name' AND UM2.meta_value LIKE '%$search_text%'";
} else {
	$first_text = array_pop($search_prepared);
	$last_text = implode('%', $search_prepared);

	$last_name_search = "UM.meta_key = 'first_name' AND UM.meta_value LIKE '%andrew%' OR UM.meta_key = 'first_name' AND UM.meta_value LIKE '%andrew%'";
	$display_name_search = "UM2.meta_key = 'last_name' AND UM2.meta_value LIKE '%ac%' OR UM2.meta_key = 'last_name' AND UM2.meta_value LIKE '%ac%'";
}
*/

// $search = str_replace(' ', '%', $search);

$sql_tmp = "
SELECT U.ID, U.user_nicename, UT.customer_id, U.display_name, UM.meta_value, UM2.meta_value, 
	CASE 
		WHEN UM3.meta_value LIKE '%administrator%' 	THEN 'administrator' 
		WHEN UM3.meta_value LIKE '%vip_member%' 	THEN 'vip_member' 
		WHEN UM3.meta_value LIKE '%premium_member%' THEN 'premium_member' 
		WHEN UM3.meta_value LIKE '%free_member%' 	THEN 'free_member' 
		WHEN UM3.meta_value LIKE '%subscriber%' 	THEN 'subscriber' 
	END AS capabilities, 
	UT.title, UM4.meta_value AS description 
FROM mwdpb_users AS U 
LEFT JOIN mwdpb_usermeta AS UM 
	ON (U.ID = UM.user_id) AND ($first_name_search) 
LEFT JOIN mwdpb_usermeta AS UM2 
	ON (U.ID = UM2.user_id) AND ($last_name_search) 
LEFT JOIN mwdpb_usermeta AS UM3 
	ON (U.ID = UM3.user_id AND UM3.meta_key = 'mwdpb_capabilities') 
LEFT JOIN mwdpb_usermeta AS UM4 
	ON (U.ID = UM4.user_id AND UM4.meta_key = 'description') 
LEFT JOIN mwdpb_mediator_members_data AS UT 
	ON U.ID = UT.user_id 
WHERE 
	UT.hidden_profile < 1 AND $specs_sql (UM.meta_value is not null OR UM2.meta_value is not null) 
ORDER BY (CASE 
	WHEN UM3.meta_value LIKE '%administrator%' 	THEN 10 
	WHEN UM3.meta_value LIKE '%vip_member%' 	THEN 2 
	WHEN UM3.meta_value LIKE '%premium_member%' THEN 3 
	WHEN UM3.meta_value LIKE '%free_member%' 	THEN 4 
	WHEN UM3.meta_value LIKE '%subscriber%' 	THEN 5 
	ELSE 100 END) ASC, RAND() 
LIMIT $offset, $posts_per_page
";



$sql_tmp = "
SELECT U.ID, U.user_nicename, UT.customer_id, U.display_name, UM.meta_value, UM2.meta_value, 
	CASE 
		WHEN UM3.meta_value LIKE '%administrator%' 	THEN 'administrator' 
		WHEN UM3.meta_value LIKE '%vip_member%' 	THEN 'vip_member' 
		WHEN UM3.meta_value LIKE '%premium_member%' THEN 'premium_member' 
		WHEN UM3.meta_value LIKE '%free_member%' 	THEN 'free_member' 
		WHEN UM3.meta_value LIKE '%subscriber%' 	THEN 'subscriber' 
	END AS capabilities, 
	UT.title, UM4.meta_value AS description 
FROM mwdpb_users AS U 
LEFT JOIN mwdpb_usermeta AS UM 
	ON (U.ID = UM.user_id) AND ($first_name_search) 
LEFT JOIN mwdpb_usermeta AS UM2 
	ON (U.ID = UM2.user_id) AND ($last_name_search) 
LEFT JOIN mwdpb_usermeta AS UM3 
	ON (U.ID = UM3.user_id AND UM3.meta_key = 'mwdpb_capabilities') 
LEFT JOIN mwdpb_usermeta AS UM4 
	ON (U.ID = UM4.user_id AND UM4.meta_key = 'description') 
LEFT JOIN mwdpb_mediator_members_data AS UT 
	ON U.ID = UT.user_id 
WHERE 
	UT.hidden_profile < 1 AND $specs_sql (UM.meta_value is not null OR UM2.meta_value is not null) 
ORDER BY (CASE 
	WHEN UM3.meta_value LIKE '%administrator%' 	THEN 10 
	WHEN UM3.meta_value LIKE '%vip_member%' 	THEN 2 
	WHEN UM3.meta_value LIKE '%premium_member%' THEN 3 
	WHEN UM3.meta_value LIKE '%free_member%' 	THEN 4 
	WHEN UM3.meta_value LIKE '%subscriber%' 	THEN 5 
	ELSE 100 END) ASC, RAND() 
LIMIT $offset, $posts_per_page
";
	// echo '<pre>' . print_r($sql_tmp, true) . '</pre>';

	$results = $wpdb->get_results($sql_tmp);

	printf('<div class="results results-mediators">');
	printf('<h4 class="main--results-heading">%s</h4>', $title);
	if( $results ):
		foreach( $results as $mediator ): ?>
		<?php
		$cap_class = $mediator->capabilities;
		if ($mediator->customer_id) {
			$cap_class = 'premium_member';
		}
		?>
		<div class="result-item cap-<?php echo $cap_class; ?>">
			<a href="<?php echo codeart_get_mediators_url($mediator->user_nicename); ?>" class="mediator-avatar">
			    <div class="avatar-holder">
				    <?php $thumbnail_size = ($is_home) ? 'thumbnail' : 'popular-mediator-avatar'; ?>
				    <?php $member->print_avatar($mediator->ID, $thumbnail_size); ?>
                </div>
			</a>

			<div class="name-and-title">
				<a href="<?php echo codeart_get_mediators_url($mediator->user_nicename); ?>">
					<h4 class="title"><?php echo $mediator->display_name; ?></h4>
				</a>

				<span class="mediator-title"><?php echo apply_filters('the_title', $mediator->title); ?></span>
			</div>
			<?php
			$mediator_descritpion = $mediator->description;
			$mediator_descritpion = strip_tags($mediator_descritpion);
			$mediator_descritpion = strip_shortcodes($mediator_descritpion);
			$mediator_descritpion = substr($mediator_descritpion, 0, 300);
			?>

			<?php if(!$is_home): ?>
				<div class="mediator-description"><?php echo apply_filters('the_content', $mediator_descritpion . '...'); ?></div>

				<a href="<?php echo codeart_get_mediators_url($mediator->user_nicename); ?>" class="view-mediator">View Mediator</a>
				
				<?php if( $member->is_premium_member($mediator->ID) || $mediator->capabilities == 'vip_member' ): ?>
				<a href="#" class="contact-mediator ajax-contact-button ca_custom_main_popup_trigger" data-name="<?php echo $mediator->display_name; ?>" data-id="<?php echo intval($mediator->ID); ?>" data-popup="div.ajax-contact-popup">Contact Mediator</a>
				<?php endif; ?>
			<?php endif; ?>
		</div>
		<?php endforeach;

		/*
		global $wp;
		$current_url = add_query_arg( $wp->query_string, '', home_url( $wp->request ) );
		$cls = '';
		$url = get_bloginfo('url') . '/search/mediators/';
		if (strpos($current_url, 'mediators')) {
			$cls = 'ajax-more-pagination';
			$url = '#';
		}
		*/

		if (!$is_home):
			$cls = '';
			$url = get_bloginfo('url') . '/search/mediators/';
			if ($is_all == 0) {
				$cls = 'ajax-more-pagination';
				$url = '#';
			}

			printf(
				'<a href="%s" class="ajax-more-mediators %s default-green-btn ajax-load-more-button-new" data-page="2">More Mediators</a>',
				$url,
				$cls
			);
		endif;
		
	else:
		printf('<div class="search-no-found"><p>No mediators found.</p></div>');
	endif;

	printf('</div> <!-- .results -->');
} // End of function codeart_search_grid_for_mediators_custom();




function codeart_get_user_role($uid)
{
	global $wpdb;
	$sql = "SELECT meta_value FROM {$wpdb->usermeta} WHERE meta_key = '{$wpdb->prefix}capabilities' AND user_id = {$uid}";
	// var_dump($sql);
	$role = $wpdb->get_var($sql);
	if(!$role) return 'non-user';
	$rarr = unserialize($role);
	$roles = is_array($rarr) ? array_keys($rarr) : array('non-user');
	return $roles[0];
}



















// add_action('wp_head', 'codeart_ajax_search_paramethers', 99);
function codeart_ajax_search_paramethers()
{
	$search_type 		= get_query_var('searchtype');
	$search_category 	= get_query_var('searchcategory');
	$search_text 		= get_query_var('query');
	$current_page		= isset($_GET['item-page']) ? intval($_GET['item-page']) : 1;

	global $member;

	$hidden_profile_ids = array();
	$hidden_profiles = $member->get_hidden_profile_ids();
	if( is_array($hidden_profiles) ):
		foreach( $hidden_profiles as $hidden_profile_id ):
			$hidden_profile_ids[] = $hidden_profile_id->user_id;
		endforeach;
	endif;

	$posts_per_page = 10;

	$all_types = array(
		'mediators' => array(
			'number' => $posts_per_page,
			'offset' => (($current_page - 1) * $posts_per_page),
			'exclude' => $hidden_profile_ids,
			'meta_query' => array(
				'relation' => 'OR',
				array(
					'key'     => 'first_name',
					'value'   => $search_text,
					'compare' => 'LIKE'
				),
				array(
					'key'     => 'last_name',
					'value'   => $search_text,
					'compare' => 'LIKE'
				)
			)
		),

		'organizations' => array(
		),

		'services' => array(
		),

		'courses' => array(
			'post_type' 		=> 'courses',
			'posts_per_page' 	=> $posts_per_page,
			's' 				=> $search_text,
			'topics_tax' 		=> 'topic_cat',
			'paged' 			=> $current_page
		),

		'topics' => array(
			'post_type' 		=> 'topics',
			'posts_per_page' 	=> $posts_per_page,
			's' 				=> $search_text,
			'paged' 			=> $current_page,
			'tax_query' => array(
				array(
					'taxonomy' => 'topic_cat',
					'field'    => 'slug',
					'terms'    => $search_category
				),
			),
		),
	);

	$title = ucfirst($search_type);
	$query_arguments = isset($all_types[$search_type]) ? $all_types[$search_type] : array();
}



















wp_enqueue_script( 'jquery-form' );

add_action('wp_ajax_avatar_upload_action', 'avatar_upload_action_callback');
add_action('wp_ajax_nopriv_avatar_upload_action', 'avatar_upload_action_callback');
function avatar_upload_action_callback()
{
	require_once(ABSPATH . "wp-admin" . '/includes/image.php');
	require_once(ABSPATH . "wp-admin" . '/includes/file.php');
	require_once(ABSPATH . "wp-admin" . '/includes/media.php');

	$tmp = false;

	global $wpdb;
	$sql = "SELECT COUNT(ID) FROM $wpdb->posts WHERE post_type = 'attachment' AND post_author = " . get_current_user_id();
	$count_gallery_images = $wpdb->get_var($sql);
	if (intval($count_gallery_images) >= 9) {
		echo json_encode(['status' => false, 'message' => 'You can upload maximum 9 images.']);
		wp_die();
	}

	if ($_FILES) {
		foreach ($_FILES as $file => $array) {
			if ($_FILES[$file]['error'] !== UPLOAD_ERR_OK) {
				echo json_encode(['status' => false, 'message' => 'Upload error.']);
				wp_die();
			}

			$filesize = $_FILES[$file]['size'];
			$filesize = (intval($filesize) / 1024) / 1024;
			if ($filesize > 4) {
				echo json_encode(['status' => false, 'message' => 'Max upload size. ']);
				wp_die();
			}

			$tmp_filename_dimensions = $_FILES[$file]['tmp_name'];
			list($iw, $ih) = getimagesize($tmp_filename_dimensions);
			if ($iw > 2000 || $ih > 2000) {
				echo json_encode(['status' => false, 'message' => 'Max upload dimension.']);
				wp_die();
			}

			$tmp = $_FILES[$file];

			$attach_id 	= media_handle_upload( $file, 0 );
			$attach_url = wp_get_attachment_image_src($attach_id, 'popular-mediator-avatar');

			break;
		}
	}

	echo json_encode(['tmp' => $tmp, 'status' => true, 'message' => 'Success', 'attach_id' => $attach_id, 'attach_url' => $attach_url[0]]);
	wp_die();
} 








add_action( 'genesis_after_header', 'ca_helper_stuff', 5);
function ca_helper_stuff(){
    ?>
        <div class="ca-menu-height-holder"></div>
    <?php
}










function codeart_get_specialisms_count( $specialism_id = 0 )
{
	if (empty($specialism_id)) {
		return false;
	}

	global $wpdb;

	$sql = sprintf(
		"SELECT COUNT(member_id) FROM mwdpb_mediator_members_data WHERE specialisms REGEXP '.*;s:[0-9]+:\"%s\".*'",
		intval($specialism_id)
	);

	return $wpdb->get_var($sql);
}





function codeart_get_services_count( $service_id = 0 )
{
	if (empty($service_id)) {
		return false;
	}

	global $wpdb;

	$sql = sprintf(
		"SELECT COUNT(member_id) FROM mwdpb_mediator_members_data WHERE services_provided REGEXP '.*;s:[0-9]+:\"%s\".*'",
		intval($service_id)
	);

	return $wpdb->get_var($sql);
}








add_action('genesis_before', 'codeart_preload_resources');
function codeart_preload_resources()
{
	?>
	<div class="preload-resources">
		<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/spiner-button-loader.svg" alt="">
	</div>
	<?php
}






add_action('deleted_user', 'codeart_delete_member_detail_row_on_delete_user', 10, 2);
function codeart_delete_member_detail_row_on_delete_user($user_id, $reassign)
{
	global $wpdb;

	$user_id = intval($user_id);
	if (empty($user_id))
		return;

	$sql = "delete from mwdpb_mediator_members_data where user_id = $user_id";

	$wpdb->query($sql);
}





function codeart_mins_to_time($mins)
{
	$time = '';

	$hours = floor($mins / 60);
    $minutes = ($mins % 60);

    if ($hours) {
    	$time .= $hours . ($hours > 1 ? ' hours and ' : ' hour and ');
    }

    $time .= $minutes . ($minutes > 1 ? ' minutes ' : ' minute ');

	return $time;
}






function codeart_get_user_title($user_id)
{
	$user_id = intval($user_id);
	if (!$user_id) {
		return false;
	}

	global $wpdb;
	$sql = "select title from mwdpb_mediator_members_data where user_id = $user_id";
	return $wpdb->get_var($sql);
}





function codeart_print_video_overlay( $topic )
{
	?>
	<div class="topic-overlay-info-wrap">
		<div class="overlay"></div>
		<div class="topic-info-home">
			<?php
			global $member;
			$mediator_author = get_field('interview_mediator', $topic->ID);
			$mediator_author = isset($mediator_author['ID']) ? $mediator_author['ID'] : 0;
			$member->print_avatar($mediator_author, 'thumbnail'); ?>
			<div class="align-center"><?php
			if($topic->post_parent == 0): ?>
				<?php $video_count = codeart_get_child_topic_count($topic->ID); ?>
				<?php if($video_count): ?>
			    <p class="info-by">Videos: <?php echo intval($video_count); ?></p>
				<?php endif; ?>
			<?php endif; ?>

			<?php if( get_field('topic_video_length', $topic->ID) ): ?>
			    <p class="info-by">Length: <?php echo codeart_mins_to_time( intval(get_field('topic_video_length', $topic->ID)) ); ?></p>
			<?php endif; ?>
	        </div>
		</div>
	</div>
	<?php
}





add_filter('body_class', 'codeart_add_mediator_capabilities_onh_viwe_profile');
function codeart_add_mediator_capabilities_onh_viwe_profile( $classes )
{
	if (!is_author()) {
		return $classes;
	}

	global $author, $wpdb;
	$ud = get_userdata($author);
	$roles = $ud->roles;
	$classes[] = 'cap-' . array_shift($roles);

	$sql = "select specialisms, services_provided from mwdpb_mediator_members_data where user_id = $author";
	$results = $wpdb->get_results($sql);

	if (is_array($results) && !empty($results)) {
		$results = array_shift($results);

		$spec = maybe_unserialize($results->specialisms );
		$serv = maybe_unserialize($results->services_provided );

		if (empty($spec) && empty($serv)) {
			$classes[] = 'ca-empty-spec-service';
		}
	}

	return $classes;
}







add_action('wp_head', 'codeart_ajax_contact_javascript');
function codeart_ajax_contact_javascript()
{
	?>
	<script type="text/javascript" >
	jQuery(document).ready(function($) {
		var contact_popup = $('body').find('.ajax-contact-popup');
		var contact_overlay = contact_popup.find('.overlay');


		$('body').on('click', 'a.ajax-contact-button', function(e) {
			e.preventDefault();

			var uid 	= $(this).data('id');
			var uname 	= $(this).data('name');

			$('body').find('form#ajax-contact-form input[name="uid"]').val(uid);
			$('body').find('.ajax-contact-popup .contact-mediator-name').text(uname);
		});

		var ajax_contact_form = $('body').find('form#ajax-contact-form');
		ajax_contact_form.on('submit', function(e) {
            
            ajax_contact_form.addClass('loader-init');
            
			var data = {
				'action': 'ajax_contact',
				'data': $(this).serialize()
			};

			jQuery.post(ajaxurl, data, function(response) {
                
                var responseServer = $.parseJSON(response);
                var status         = responseServer.status;
                var error_msg      = $('div.popup-wrap div.contact-heading p.error-msg');
                
                if( status == false ){
                    error_msg.addClass('visible');
                    ajax_contact_form.removeClass('loader-init');
                }
                
                else{
                    ajax_contact_form.removeClass('loader-init').addClass('form-done-init').delay('500').queue(function(i){
                        ajax_contact_form.addClass('form-done-ready');
                        i();
                    });
                    
                    error_msg.removeClass('visible');
                }
			});

			return false;
		});

	});
	</script>
	<script src="https://www.google.com/recaptcha/api.js?onload=CaptchaCallback&render=explicit" async defer></script>

	<script type="text/javascript">
    var CaptchaCallback = function(){
    	<?php $captcha_site_key = get_field('google_re_captcha_site_key', 'option'); ?>
    	if (jQuery('body').find('#g-recaptcha-1').length) {
    		grecaptcha.render('g-recaptcha-1', {'sitekey' : '<?php echo $captcha_site_key; ?>'});
    	};
    	if (jQuery('body').find('#g-recaptcha-2').length) {
    		grecaptcha.render('g-recaptcha-2', {'sitekey' : '<?php echo $captcha_site_key; ?>'});
    	};
    };
    </script>
	<?php
}



add_action( 'wp_ajax_ajax_contact', 'codeart_ajax_contact_callback' );
add_action( 'wp_ajax_nopriv_ajax_contact', 'codeart_ajax_contact_callback' );
function codeart_ajax_contact_callback()
{
	$data = $_POST['data'];
	parse_str($data, $data_final);

	if (empty($data_final)) {
		codeart_print_ajax_contact_status('No data');
	}

	if (codeart_verify_recaptcha($data_final['g-recaptcha-response']) === false) {
		codeart_print_ajax_contact_status('No spam');
		wp_die();
	}

	$full_name 	= isset($data_final['full_name']) 	? esc_attr($data_final['full_name']) 		: '';
	$from 		= isset($data_final['user_email']) 	? sanitize_email($data_final['user_email']) : '';
	$subject 	= isset($data_final['subject']) 	? esc_attr($data_final['subject']) 			: '';
	$message 	= isset($data_final['message']) 	? esc_attr($data_final['message']) 			: '';
	$uid 		= isset($data_final['uid']) 		? intval($data_final['uid']) 				: '';

	if (empty($full_name) || empty($from) || empty($uid) || empty($subject) || empty($message)) {
		codeart_print_ajax_contact_status('All fields are required!');
	}

	$user_data 	= get_userdata( $uid );
	if (!$user_data) {
		codeart_print_ajax_contact_status('User data error');
	}

	$to_email 	= $user_data->user_email;
	$to_name 	= $user_data->display_name;

	$headers[] = "From: $full_name <$from>";
	$headers[] = "Cc: $to_name <$to_email>";

	$is_ok = wp_mail( $to, $subject, $message, $headers );
	$message = 'Error';

	if ($is_ok) {
		$message = 'Success';

		$contact_cound = get_user_meta( $uid, 'contact_count', true );
		$contact_cound = intval($contact_cound);
		$contact_cound++;
		update_user_meta($uid, 'contact_count', $contact_cound);
	}

	codeart_print_ajax_contact_status('', $is_ok);

	wp_die();
}


function codeart_print_ajax_contact_status($message = '', $status = false)
{
	echo json_encode( ['status' => $status, 'message' => $message] );
	wp_die();
}


add_action('wp_footer', 'codeart_ajax_contact_popup');
function codeart_ajax_contact_popup()
{
	?>
	<div class="ajax-contact-popup register-popup">
		<div class="overlay"></div>
		<div class="ca_custom_popup_overlay"></div>
		<div class="ca_custom_popup_close close-popup"></div>
		<div class="popup-wrap">
			<div class="popup_logo"></div>
			<div class="contact-heading">
				<h4>Contact <span class="contact-mediator-name">Mediator</span></h4>
				<p class="error-msg">All fields are required.</p>
			</div>
			<div class="entry">

				<form id="ajax-contact-form" action="" method="post">
					<input type="text" name="full_name" placeholder="Full Name" value="">
					<input type="email" name="user_email" placeholder="Email Address" value="">
					<input type="text"  name="subject" placeholder="Subject" value="">
					<input type="hidden" name="uid" value="">
					<textarea name="message"  placeholder="Message"></textarea>

					<div id="g-recaptcha-2"></div>

					<input type="submit" name="ajax-contact-submit" value="Send">
					<span class="thanks-msg">Your message has been sent.</span>
				</form>
                <div class="loader-home-search">
                    <div class="showbox">
                        <div class="loader">
                            <svg class="circular" viewBox="25 25 50 50">
                                <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"></circle>
                            </svg>
                        </div>
                    </div>
                </div>
			</div> <!-- .entry -->
		</div> <!-- .popup-wrap -->
	</div>
	<?php
}











add_filter( 'user_contactmethods', 'codeart_new_contact_methods', 10, 1 );
function codeart_new_contact_methods( $contactmethods )
{
	$contactmethods['contact_count'] = 'Contact Count';
	return $contactmethods;
}



add_filter( 'manage_users_columns', 'codeart_new_modify_user_table' );
function codeart_new_modify_user_table( $column )
{
	$column['contact_count'] = 'Contact Count';
	return $column;
}



add_filter( 'manage_users_custom_column', 'codeart_new_modify_user_table_row', 10, 3 );
function codeart_new_modify_user_table_row( $val, $column_name, $user_id )
{
	if ($column_name == 'contact_count') {
		$contact_count = get_user_meta($user_id, 'contact_count', true);
		return intval($contact_count);
	}
	
	return $val;
}















add_filter( 'user_contactmethods', 'codeart_new_contact_methods_ureg_col', 10, 1 );
function codeart_new_contact_methods_ureg_col( $contactmethods )
{
	$contactmethods['reg_date_ca'] = 'Reg. Date';
	return $contactmethods;
}



add_filter( 'manage_users_columns', 'codeart_new_modify_user_table_ureg_col' );
function codeart_new_modify_user_table_ureg_col( $column )
{
	$column['reg_date_ca'] = 'Reg. Date';
	return $column;
}



add_filter( 'manage_users_custom_column', 'codeart_new_modify_user_table_row_ureg_col', 10, 3 );
function codeart_new_modify_user_table_row_ureg_col( $val, $column_name, $user_id )
{
	if ($column_name == 'reg_date_ca') {
		$udata = get_userdata( $user_id );
		// $reg_date_ca = get_user_meta($user_id, 'reg_date_ca', true);
		return $udata->user_registered;
	}
	
	return $val;
}









add_action( 'init', 'codeart_restrict_wp_admin_access' );
function codeart_restrict_wp_admin_access()
{
	if ( is_admin() && !current_user_can( 'administrator' ) && !(defined( 'DOING_AJAX' ) && DOING_AJAX) ) {
		wp_redirect( home_url() );
		exit;
	}
}













function codeart_linkedin_print_error($message = '', $is_register = false)
{
    ?>
    <html>
    <head>
    	<title><?php bloginfo('title'); ?></title>
    	<?php wp_head(); ?>
    	<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/style.css" type="text/css" media="all">
    	<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/css/linkedin-error.css" type="text/css" media="all">
    	<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/js/linkedin.js"></script>
    </head>
    <body class="linkedin-error">
    	<div class="wrap">
    	    <div class="box">
    		    <div class="entry">
    			    <div class="register-popup popup-login-register-main register">

						<div class="popup-wrap">
							<div class="popup_logo"></div>
							<div class="mini_nav">
								<a href="#" class="login_form" data-element="login">Log In</a>
								<a href="#" class="register_form" data-element="register">Sign Up</a>
							</div>

							<div class="entry">
								<div class="left login">
									<form id="login" action="">
										<p class="status"></p>
										<a href="http://mediator.codeart.mk/linkedin/signin.php" class="linkedin-sync">Login with LinkedIn</a>
										<div class="text-div"></div>
										<input type="text" id="username" placeholder="Enter your email">
										<input type="password" id="password" placeholder="Enter your password">
										<input type="submit" value="Log in">
										<div class="checkbox-holder">
											<input type="checkbox" id="remember_login" name="remember_login">
											<label for="remember_login">Remember me</label>
										</div>

										<input type="hidden" id="security" name="security" value="41d40d8088"><input type="hidden" name="_wp_http_referer" value="/">
										<a href="#" class="forgot_password">Forgot Password?</a>
									</form>
								</div> <!-- .left -->

								<div class="left forgot_password">
									<form id="reset-password" action="">
										<p>Enter your email address below. We'll look for your account and send you a password reset email.</p>
										<input type="email" placeholder="Enter your email">
										<input type="submit" value="Reset Password">
									</form>
								</div>

								<div class="right">

									<p class="register-error">Error placeholder</p>

									<form id="mediator-profile-register" action="" method="post">
										<a href="http://mediator.codeart.mk/linkedin/signup.php" class="linkedin-sync">Sign Up with LinkedIn</a>
										<div class="text-div"></div>

										<input type="text" name="first_name" placeholder="First Name" value="">
										<input type="text" name="last_name" placeholder="Last Name" value="">
										<input type="email" name="user_email" placeholder="Email Address" value="">

										<div id="g-recaptcha-1"></div>
										<input type="submit" name="mediator-profile-register-submit" value="Sign Up">
										<div class="bottom">
											<p>By signing up to create an account I accept our <a href="#">Terms of Use</a> and <a href="#">Privacy Policy</a>.</p>
										</div>
									</form>
								</div> <!-- .right -->
							</div> <!-- .entry -->

							<div class="loader-home-search">
						        <div class="showbox">
						        	<div class="loader">
						        		<svg class="circular" viewBox="25 25 50 50">
						        			<circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"></circle>
						        		</svg>
						        	</div>
						        </div>
						    </div> <!-- .loader-home-search -->

						</div> <!-- .popup-wrap -->
					</div>

    		    </div>
            </div>
    	</div>
    	<?php wp_footer(); ?>
    </body>
    </html>
    <?php
}










function codeart_verify_recaptcha( $key = '' )
{
	if (empty($key)) {
		return false;
	}

	$captcha_secret_key = get_field('google_re_captcha_sicret_key', 'option');
	$url = 'https://www.google.com/recaptcha/api/siteverify';

	$response = file_get_contents($url.'?secret='.$captcha_secret_key.'&response='.$key.'&remoteip='.$_SERVER['REMOTE_ADDR']);
	$data = json_decode($response);

	return (isset($data->success) && $data->success == true) ? true : false;
}







function codeart_get_watched_videos( $only_ids = false )
{
	$watched_cookies = isset($_COOKIE['watched_count']) ? $_COOKIE['watched_count'] : false;
	if (!$watched_cookies) {
		return;
	}

	$watched_cookies = base64_decode($watched_cookies);
	$watched_cookies = unserialize($watched_cookies);

	if (empty($watched_cookies) || !is_array($watched_cookies)) {
		return;
	}

	$ids = [];

	foreach($watched_cookies as $wc) {
		$ids[] = $wc['pid'];
	}

	if ($only_ids) {
		return $ids;
	}

	if (!empty($ids)) {
		printf(
			'<input type="hidden" name="transfer-watched-videos" value="%s" />',
			implode(',', $ids)
		);
	}
}








add_action('genesis_after_footer', 'codeart_add_note_form');
function codeart_add_note_form()
{
	?>
	<div class="note-container">

		<div class="note-loader">
			<div class="note-loader-wrap">
				<div class="spinner">
					<div class="double-bounce1"></div>
					<div class="double-bounce2"></div>
				</div>
			</div>
		</div>

		<div class="note-wrap">
			<a href="#" class="open-note-btn button">
				<span class="close-note">X</span>
				<span class="feedback">Leave Feedback</span>
				<span class="bug">Report a Bug</span>
			</a>
			<form action="" method="post" id="form-note">
				<h4>I want to</h4>
				<div class="radio-wrap">
					<p class="idea">
						<input type="radio" checked name="report" value="idea" id="report-idea">
						<label for="report-idea">Suggest an Idea</label>
					</p>
					<p class="bug">
						<input type="radio" name="report" value="bug" id="report-bug">
						<label for="report-bug">Report a Bug</label>
					</p>
				</div>

				<?php $email = codeart_populate_feedback_email(); ?>

				<div class="idea-fields">
					<p class="txt">Enter your email:</p>
					<input type="email" name="email" placeholder="email@address.com" value="<?php echo $email; ?>" />
					<p class="txt">Please describe the idea:</p>
					<textarea placeholder="Enter your text here..." name="idea-ta-1"></textarea>
				</div>

				<div class="bug-fields">
					<p class="txt">Enter your email:</p>
					<input type="email" name="email" placeholder="email@address.com" value="<?php echo $email; ?>" />
					<p class="txt">Did you notice this bug in any other place beside the current page?</p>
					<input type="text" placeholder="Enter your text here..." name="bug-ta-1"/>

					<p class="txt">Please describe the problem:</p>
					<textarea placeholder="Enter your text here..." name="bug-ta-2"></textarea>
				</div>

				<input type="hidden" name="hp" value="<?php echo rand(0, 9999); ?>">

				<input type="submit" name="note-btn" class="button" value="Submit">
			</form>
		</div>
	</div>
	<?php
}



add_action( 'wp_head', 'codeart_note_js' );
function codeart_note_js()
{
	?>
	<script type="text/javascript" >
	jQuery(document).ready(function($) {
        
        var body = $('body');

		var note_cls = 'loading-add-note';
		var add_note = $('body').find('.note-container form');
		var note_cb = add_note.find("input[type=radio]");

		note_cb.on('click', function(e) {
			var v = $(this).val();

			var bugf = $('body').find('.bug-fields');
			var ideaf = $('body').find('.idea-fields');

			if (v == 'bug') {
				if (bugf.css('display') == 'none') {
					ideaf.fadeOut(0, function() {
						bugf.fadeIn(190);
					});
				}
			} else {
				if (ideaf.css('display') == 'none') {
					bugf.fadeOut(0, function() {
						ideaf.fadeIn(190);
					});
				}
			}

		});



		function codeartSetCookie(cname, cvalue, exdays) {
			var d = new Date();
			d.setTime(d.getTime() + (exdays*24*60*60*1000));
			var expires = "expires="+d.toUTCString();
			document.cookie = cname + "=" + cvalue + "; " + expires + ";path=/";
		}


		var open_note = $('body').find('a.open-note-btn');
		open_note.on('click', function(e) {
			e.preventDefault();

			var note_container = $('body').find('.note-container');
			var note_container_offset = note_container.offset();

			if ($('body').hasClass('open-note')) {
                body.addClass('note-closing').delay('200').queue(function(i){
                    note_container.removeAttr('style'); 
                    $('body').removeClass('open-note note-closing');
                    i();
                });
			} else {
				var top_val = note_container_offset.top + 'px';
				note_container.css('position', 'absolute');
				note_container.css('top', top_val);
			}

			$('body').addClass('open-note');
		});


		

		add_note.on('submit', function(e) {
			var error = false;

			var note_form = $('this').closest('form');
			note_form.find(':input').attr("disabled", true);

			$('body').find('.note-container .error-val').removeClass('error-val');
			$('body').find('.note-container p.success-note').remove();

			var fidea 	= add_note.find("[name=idea-ta-1]");
			var femail 	= add_note.find("[name=email]");
			var fbug1 	= add_note.find("[name=bug-ta-1]");
			var fbug2 	= add_note.find("[name=bug-ta-2]");
			var ftype 	= add_note.find('input[name=report]:checked').val();

			if (ftype == 'idea') {
				if (fidea.val().length < 1) {
					error = true;
					fidea.addClass('error-val');
				}
			} else {
				if (fbug1.val().length < 1) {
					error = true;
					fbug1.addClass('error-val');
				}

				if (fbug2.val().length < 1) {
					error = true;
					fbug2.addClass('error-val');
				}
			}

			femail = ftype == 'bug' ? femail.last() : femail.first();

			if (femail.val().length < 1) {
				error = true;
				femail.addClass('error-val');
			}

			if (error) {
				return false;
			}

			$('body').addClass(note_cls);

			var data = {
				'action': 'ajax_add_note',
				'idea': fidea.val(),
				'email': femail.val(),
				'bug1': fbug1.val(),
				'bug2': fbug2.val(),
				'type': ftype
			};

			jQuery.post(ajaxurl, data, function(response) {
				if( response ) {
					obj = $.parseJSON(response);
					if( obj.status == true ) {
						$('body').find('.radio-wrap').after('<p class="success-note">Success sent!</p>');

						fidea.val('');
						fbug1.val('');
						fbug2.val('');

						codeartSetCookie('feedback_email', femail.val());
					} else {
						console.log('error add note');
						note_form.find(':input').attr("disabled", false);
					}
				}

				$('body').removeClass(note_cls);
			});

			return false;
		});

	});
	</script> <?php
}

add_action( 'wp_ajax_ajax_add_note', 'codeart_note_callback' );
add_action( 'wp_ajax_nopriv_ajax_add_note', 'codeart_note_callback' );
function codeart_note_callback()
{
	if (isset($_POST['hp'])) {
		wp_die();
	}

	$idea = esc_attr( $_POST['idea'] );
	$email = esc_attr( $_POST['email'] );

	$bug1 = esc_attr( $_POST['bug1'] );
	$bug2 = esc_attr( $_POST['bug2'] );

	$type = esc_attr( $_POST['type'] );

	$to = [get_option('admin_email'), 'aled@mediatoracademy.com'];
	$subject = $type == 'bug' ? 'Report a Bug' : 'Suggest an Idea';

	$msg = ($type == 'bug') ? $msg = $bug1 . '<br><br>' . $bug2 : $idea;

	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

	$msg = !empty($email) ? 'Email: ' . $email . '</br>' . $msg : $msg;
	$meil_status = wp_mail( $to, $subject, stripslashes($msg), $headers );

	echo json_encode(['status' => $meil_status, 'message' => '']);

	wp_die();
}






add_action("admin_enqueue_scripts", "codeart_advanced_edit_enqueue_media_uploader");
function codeart_advanced_edit_enqueue_media_uploader()
{
	global $pagenow;
	if ($pagenow != 'users.php' || $_GET['page'] != 'edit-member') {
		return;
	}

    wp_enqueue_media();
}

add_action('admin_head', 'codeart_advanced_edit_media');
function codeart_advanced_edit_media()
{
	global $pagenow;
	if ($pagenow != 'users.php' || $_GET['page'] != 'edit-member') {
		return;
	} ?>

	<script type="text/javascript">
	var media_uploader = null;

	function open_media_uploader_image()
	{
		media_uploader = wp.media({
			frame:    "post",
			state:    "insert",
			multiple: false
		});

		media_uploader.on("insert", function(){
			var json = media_uploader.state().get("selection").first().toJSON();
			
			var image_id = json.id;
			var image_url = json.sizes['popular-mediator-avatar'].url;

			jQuery('body').find('#profile-edit .avatar img').removeAttr('srcset');
			jQuery('body').find('#profile-edit .avatar img').attr('src', image_url);
			
			jQuery('body').find('#profile-edit input[name=avatar]').val(image_id);
		});

		media_uploader.open();
	}

	jQuery(document).ready(function($) {
		var avatar_button = $('body').find('a.mediator-member-avatar');
		avatar_button.on('click', function(e) {
			e.preventDefault();

			open_media_uploader_image();
		});
	});
	</script>
	<?php
}






add_action('wp_handle_upload_prefilter', 'codeart_handle_image_upload_request');
function codeart_handle_image_upload_request($file)
{
	$size = $file['size'];
	$size = $size / 1024;
	$type = $file['type'];
	$is_image = strpos($type, 'image');
	$limit = 2000;

	if ( !current_user_can('manage_options') && $is_image === false ) {
		$file['error'] = 'Only images are allowed.';
		return $file;
	}

	if ( ( $size > $limit ) && ($is_image !== false) ) {
		$file['error'] = 'Max upload image error';
		if (WPISL_DEBUG) {
			$file['error'] .= ' [ filesize = '.$size.', limit ='.$limit.' ]';
		}
	}

	return $file;
}













add_action('init', 'codeart_allow_contributor_uploads');
function codeart_allow_contributor_uploads() {
    if ( is_user_logged_in() && !current_user_can('upload_files') )
    {
    	$roles = [ 'vip_member', 'premium_member', 'free_member', 'subscriber' ];

    	foreach ($roles as $r)
    	{
    		$cap = get_role( $r );
    		$cap->add_cap('upload_files');
    	}
        //$contributor = get_role('contributor');
        // $contributor->add_cap('upload_files');
    }
}












add_filter( 'ajax_query_attachments_args', 'codeart_show_users_own_attachments', 1, 1 );
function codeart_show_users_own_attachments( $query ) 
{
	$id = get_current_user_id();
	if( !current_user_can('manage_options') )
		$query['author'] = $id;

	return $query;
}



// add_action('wp_head', 'codeart_profile_edit_media');
function codeart_profile_edit_media()
{
	if (!is_page_template('template-profile-edit.php')) {
		return;
	} ?>

	<script type="text/javascript">
	var media_uploader = null;

	function open_media_uploader_image()
	{
		media_uploader = wp.media({
			frame:    "post",
			state:    "insert",
			multiple: false
		});

		media_uploader.on("insert", function(){
			var json = media_uploader.state().get("selection").first().toJSON();
			
			/*
			var image_id = json.id;
			var image_url = json.sizes['popular-mediator-avatar'].url;

			jQuery('body').find('#profile-edit .avatar img').removeAttr('srcset');
			jQuery('body').find('#profile-edit .avatar img').attr('src', image_url);
			
			jQuery('body').find('#profile-edit input[name=avatar]').val(image_id);
			*/
			console.log(json);
		});

		media_uploader.open();
	}

	jQuery(document).ready(function($) {
		var avatar_button = $('body').find('a.mediator-member-avatar');
		avatar_button.on('click', function(e) {
			e.preventDefault();

			open_media_uploader_image();
		});
	});
	</script>
	<?php
}






add_action('wp_head', 'codeart_add_lte8_css');
function codeart_add_lte8_css()
{
	?>
	<!--[if lt IE 9]>
		<style type="text/css">
		.old-ie-bar { display: block !important; }
		</style>
	<![endif]-->
	<?php
}








function codeart_populate_feedback_email()
{
	$email = isset($_COOKIE['feedback_email']) ? sanitize_email($_COOKIE['feedback_email']) : '';
	if (is_user_logged_in()) {
		$current_user = wp_get_current_user();
		return $current_user->user_email;
	}

	return $email;
}









add_action( 'wp_ajax_remove_from_gallery', 'codeart_remove_avatar_from_gallery' );
add_action( 'wp_ajax_nopriv_remove_from_gallery', 'codeart_remove_avatar_from_gallery' );
function codeart_remove_avatar_from_gallery()
{
	$attach_id = intval($_POST['attach_id']);
	if (empty($attach_id)) {
		echo json_encode(['status' => false, 'message' => 'No image selected.']);
	}

	if (false === wp_delete_attachment($attach_id)) {
		$message = 'Fail remove';
		echo json_encode(['status' => false, 'message' => 'Fail remove']);
	} else {
		echo json_encode(['status' => true, 'message' => 'Success remove']);
	}
	wp_die();
}

?>