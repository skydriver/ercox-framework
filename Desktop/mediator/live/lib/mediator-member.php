<?php


/**
 *	Class Mediator_Member
 *
 *	Single member functions.
 *
 **/
class Mediator_Member extends Mediator_Member_Config
{

	public $member_data = array();

	private $hash_salt = 'mediator_academy';

	private $cookie_key = 'watched_count';

	private $watched_videos = 0;





	/**
	 *	Object Constructor
	 **/
	public function __construct( $user_id = null )
	{
		parent::__construct();

		// setcookie($this->cookie_key, '4', time() + (86400 * 30), "/");

		$uid = $user_id ? $user_id : get_current_user_id();
		if (isset($_GET['member_id'])) {
			$uid = $_GET['member_id'];
		}


/*
		if( is_author() )
		{
			global $author;
			$uid = $author;
		}
		// var_dump($uid);*/
		
		$this->member_data = self::get_member_data( $uid );

		$this->watched_videos();
	} // End of public function __construct();






	public function codeart_get_invited_by_hash()
	{
		if (isset($_GET['ref'])) {
			return $_GET['ref'];
		}

		if (isset($_COOKIE['invited_by'])) {
			return $_COOKIE['invited_by'];
		}

		return false;
	}






	public function watched_videos()
	{
		if( is_user_logged_in() )
		{
			// SELECT COUNT(DISTINCT(pid)) FROM mwdpb_mediator_members_logs WHERE uid = 184 AND type = 'started'
			global $wpdb;
			$this->watched_videos = $wpdb->get_var(
				sprintf(
					"SELECT COUNT(DISTINCT(pid)) FROM %s WHERE uid = %d AND type = '%s'",
					$wpdb->prefix . 'mediator_members_logs',
					get_current_user_id(),
					'started'
				)
			);
		}
		else
		{
			$this->watched_videos = count($this->get_cookie());
		}
	}








	/**
	 * Get watched video ids (used for can_watch_video)
	 **/
	public function get_started_videos()
	{
		$user_id = get_current_user_id();
		$user_id = intval($user_id);

		if( empty($user_id) )
			return false;

		global $wpdb;
		$sql = sprintf(
			"SELECT vid FROM %s WHERE uid = %d AND type = '%s' LIMIT 0, 20",
			$wpdb->prefix . 'mediator_members_logs',
			get_current_user_id(),
			'started'
		);

		
		$videos = $wpdb->get_results($sql);

		return $videos;
	} // End of function get_started_videos;







	/**
	 * Get watched video count
	 **/
	public function get_watched_video_count()
	{
		return $this->watched_videos;
	} // End of get_watched_video_count();







	/**
	 *	Method to handle the post request
	 **/
	public function save( $post_fields, $is_admin = false )
	{
		// mediator-profile-edit-submit
		/*
		if( isset($_POST['mediator-profile-edit-submit']) )
		{
			$first_name 	= isset($_POST['first_name']) 	? $_POST['first_name'] 		: '';
			$last_name 		= isset($_POST['last_name']) 	? $_POST['last_name'] 		: '';
			$email 			= isset($_POST['email']) 		? $_POST['email'] 			: '';
			$display_name 	= isset($_POST['display_name']) ? $_POST['display_name'] 	: '';

			$user_id = get_current_user_id();

			$updated_user = wp_update_user(
				array(
					'ID' 			=> $user_id,
					'first_name' 	=> $first_name,
					'last_name' 	=> $last_name,
					'user_email' 	=> $email,
					'display_name' 	=> $display_name
				)
			);

			if( is_wp_error( $updated_user ) )
				return false;

			return $this->update($user_id, $_POST);
		}
		*/

		$first_name 	= isset($post_fields['first_name']) 	? $post_fields['first_name'] 		: '';
		$last_name 		= isset($post_fields['last_name']) 		? $post_fields['last_name'] 		: '';
		$email 			= isset($post_fields['email']) 			? $post_fields['email'] 			: '';
		$display_name 	= isset($post_fields['display_name']) 	? $post_fields['display_name'] 		: '';
		$description 	= isset($post_fields['description']) 	? $post_fields['description'] 		: '';

		$user_id 		= isset($post_fields['member_id']) ? intval($post_fields['member_id']) :  get_current_user_id();
		if( empty($user_id) )
			return false;

		$updated_user = wp_update_user(
			array(
				'ID' 			=> $user_id,
				'first_name' 	=> $first_name,
				'last_name' 	=> $last_name,
				'user_email' 	=> $email,
				'display_name' 	=> $display_name,
				'description' 	=> $description
			)
		);

		if( is_wp_error( $updated_user ) )
			return false;

		return $this->update($user_id, $post_fields, $is_admin);
	} // End of public function save();






	public function get_hidden_profile_ids()
	{
		global $wpdb;

		$sql = sprintf(
			"SELECT user_id FROM %s WHERE hidden_profile = 1",
			$wpdb->prefix . 'mediator_members_data'
		);

		$hidden_profile_ids = $wpdb->get_results($sql);
		
		return $hidden_profile_ids;
	}








	public function codeart_aweber_request($email, $name, $message, $password)
	{
		require_once('../aweber_api/aweber_api.php');

		$consumerKey    	= 'Ak3dYij0fvwWIeb2D90Oyawj';
		$consumerSecret 	= 'rkwFt1ySTT71JZwqU4Oo6JdBjquSljTHUSEvDFAY';
		$accessTokenKey 	= 'AgIno8gMZG8HfFBfBL08hp3u';
		$accessTokenSecret 	= 'r7IkBnt6jnYPVVJw7yOkQicYNQtoc7lwP04tEOSM';
		$account_id 		= '544917';
		$list_id 			= '4200540';

		$aweber = new AWeberAPI($consumerKey, $consumerSecret);

		try {
			$account = $aweber->getAccount($accessTokenKey, $accessTokenSecret);
			$list = $account->loadFromUrl("/accounts/{$account_id}/lists/{$list_id}");
			$params = array(
				'email' 		=> $email,
				'ip_address' 	=> $_SERVER['REMOTE_ADDR'],
				'ad_tracking' 	=> 'client_lib_example',
				'last_followup_message_number_sent' => 1,
				'misc_notes' 	=> $message,
				'name' 			=> $name,
				'custom_fields' => array(
					'WP Pass' => $password
				),
			);

			$subscribers = $list->subscribers;
			$new_subscriber = $subscribers->create($params);
			return true;
		} catch(AWeberAPIException $exc) {
			return false;
		}
		return false;
	} // End of function codeart_aweber_request();









	/**
	 *	Method to register the user
	 **/
	public function register($first_name, $last_name, $user_email, $invited_by = 0, $post_ids = [])
	{
		$userdata = array();

		$password = wp_generate_password(10);

		$userlogin = $first_name . '-' . $last_name;
		$counter = 2;

		while(username_exists($userlogin))
		{
			$userlogin = explode('-', $userlogin);
			$num = count($userlogin) === 3 ? $num = end($userlogin) : $num = $counter;
			$num = intval($num);
			$num++;
			$userlogin = $userlogin[0] . '-' . $userlogin[1] . '-' . $num;

			$counter++;
		}

		$userdata['user_login'] = $userlogin;
		$userdata['user_pass'] 	= $password;
		$userdata['user_email'] = $user_email;

		$userdata['first_name'] = $first_name;
		$userdata['last_name'] 	= $last_name;

		$new_user_id = wp_insert_user( $userdata );
		
		if( is_wp_error($new_user_id) )
			return false;

		$this->add_to_aweber($user_email, $first_name . ' ' . $last_name, $password);

		$userdata['user_id'] 	= $new_user_id;

		$this->add($userdata);
		$this->transfer_watched_videos_new($new_user_id, $post_ids);

		$signon = wp_signon(
			[
				'user_login' => $userdata['user_login'],
				'user_password' => $userdata['user_pass']
			],
			true
		);

		/*if( !wp_mail( $user_email, 'Success Register', 'Password: ' . $password ) )
			return false;*/

		if( is_wp_error($signon) )
			return false;
		
		return true;
	} // End of public function register();








	/**
	 * Function to get member data value
	 * 
	 * @param string $property Value key
	 * @param boolean $echo Echo or return
	 * 
	 * @return mixed
	 **/
	public function get($property = '', $echo = true)
	{
		if(!empty($property) && is_object($this->member_data) && property_exists($this->member_data, $property))
		{
			/*
			$fields = Mediator_Member_Fields::get_fields();
			$field = isset($fields[$property]) ? $fields[$property] : false;
			$sanitize = $field['sanitize'];
			*/

			$value = $this->member_data->{$property};

			if( is_serialized($value) )
				$value = maybe_unserialize($value);

			if($echo)
			{
				echo $value;
			}
			else
			{
				return $value;
			}
		}

		return false;
	} // End of function get();







	/**
	 * Function to get member data value
	 * 
	 * @param string $property Value key
	 * @param boolean $echo Echo or return
	 * 
	 * @return mixed
	 **/
	public function get_member($property = '', $echo = true)
	{
		global $current_user;
		get_currentuserinfo();

		if(!empty($property) && is_object($current_user))
		{
			$value = $current_user->{$property};
			if($echo)
				echo $value;
			else
				return $value;
		}

		return false;
	} // End of function get_member()









	public function download_and_upload_avatar_from_linkedin( $image_url, $title )
	{
		// Add Featured Image to Post
		// $image_url  = 'https://media.licdn.com/mpr/mprx/0_0vA3T6wAYRy2c-HsscKfXbb1xEmuclesUz7ubbn0-xffrKVqJ5KDLTNbLj1'; // Define the image URL here
		 $upload_dir = wp_upload_dir(); // Set upload folder
		 $image_data = file_get_contents($image_url); // Get image data
		 $filename   = basename($image_url); // Create image file name

		 $get 	= wp_remote_get( $image );
		 $type 	= wp_remote_retrieve_header( $get, 'content-type' );

		 if( strpos($type, 'jpg') >= 0 || strpos($type, 'jpeg') >= 0 )
		 {
		 	$type_tmp = 'jpg';
		 }
		 else
		 {
		 	if( strpos($type, 'png') === false )
		 	{
		 		return 0;
		 	}

		 	$type_tmp = 'png';
		 }

		 // Check folder permission and define file location
		 if( wp_mkdir_p( $upload_dir['path'] ) ) {
		     $file = $upload_dir['path'] . '/' . sanitize_title($title) . '-' . rand(10, 1000) . '.' . $type_tmp;
		 } else {
		     $file = $upload_dir['basedir'] . '/' . sanitize_title($title) . '-' . rand(10, 1000) . '.' . $type_tmp;
		 }

		 // Create the image  file on the server
		 file_put_contents( $file, $image_data );

		 // Check image file type
		 $wp_filetype = wp_check_filetype( $file, null );

		 // Set attachment data
		 $attachment = array(
		     'post_mime_type' => $wp_filetype['type'],
		     'post_title'     => sanitize_file_name( $filename ),
		     'post_content'   => '',
		     'post_status'    => 'inherit'
		 );

		 // Create the attachment
		 $attach_id = wp_insert_attachment( $attachment, $file );

		 // Include image.php
		 require_once(ABSPATH . 'wp-admin/includes/image.php');

		 // Define attachment metadata
		 $attach_data = wp_generate_attachment_metadata( $attach_id, $file );

		 // Assign metadata to attachment
		 wp_update_attachment_metadata( $attach_id, $attach_data );

		 return $attach_id;

		// // $image_url = 'https://media.licdn.com/mpr/mprx/0_0vA3T6wAYRy2c-HsscKfXbb1xEmuclesUz7ubbn0-xffrKVqJ5KDLTNbLj1';
		// $media_dir = wp_upload_dir();

		$get 	= wp_remote_get( $image );
		$type 	= wp_remote_retrieve_header( $get, 'content-type' );

		if( strpos($type, 'jpg') >= 0 || strpos($type, 'jpeg') >= 0 )
		{
			$type_tmp = 'jpg';
		}
		else
		{
			if( strpos($type, 'png') === false )
			{
				return 0;
			}

			$type_tmp = 'png';
		}
	}












	public function register_with_linkedin($user_details, $post_ids = [])
	{
		$userdata = array();

		$password 	= wp_generate_password(12);

		$email 		= $user_details->emailAddress;
		$picture 	= $user_details->pictureUrl;
		$bio 		= $user_details->summary;
		$first_name = $user_details->firstName;
		$last_name 	= $user_details->lastName;
		$title 		= $user_details->headline;
		$location 	= $user_details->location->country->code;

		$avatar_url = $user_details->pictureUrls->values[0];

		$userlogin = $first_name . '-' . $last_name;
		$counter = 2;

		while(username_exists($userlogin))
		{
			$userlogin = explode('-', $userlogin);
			$num = count($userlogin) === 3 ? $num = end($userlogin) : $num = $counter;
			$num = intval($num);
			$num++;
			$userlogin = $userlogin[0] . '-' . $userlogin[1] . '-' . $num;

			$counter++;
		}

		$userdata['user_login'] = $userlogin;

		// $userdata['user_login'] = $first_name . $last_name . substr(md5(rand()), 0, 8);
		$userdata['user_pass'] 	= $password;
		$userdata['user_email'] = $email;
		$userdata['first_name'] = $first_name;
		$userdata['last_name'] 	= $last_name;

		$userdata['description'] = $bio;
		$userdata['title'] 		= $title;
		$userdata['location'] 	= $location;

		$new_user_id = wp_insert_user( $userdata );
		
		if( is_wp_error($new_user_id) )
			return false;

		$this->add_to_aweber($email, $first_name . ' ' . $last_name, $password);

		$avatar_id = $this->download_and_upload_avatar_from_linkedin($avatar_url, $first_name . '-' . $last_name);
		$userdata['avatar'] = $avatar_id;

		$company = $user_details->positions->values[0];

		if( $company )
		{
			$job_title 			= $company->title;
			$job_company 		= $company->company->name;
			$job_start 			= '0.' . $company->startDate->month . '.' . $company->startDate->year;
			$job_description 	= $company->summary;

			$userdata['experience'] = array(
				array(
					'experience_position' 		=> $job_title,
					'experience_company_name' 	=> $job_company,
					'experience_period_from' 	=> $job_start,
					'experience_period_to' 		=> date('d.m.Y'),
					'experience_description' 	=> $job_description
				)
			);
		}

		$userdata['user_id'] 	= $new_user_id;

		$this->add($userdata);
		// $this->transfer_watched_videos($new_user_id);
		$this->transfer_watched_videos_new($new_user_id, $post_ids);

		$signon = wp_signon(
			[
				'user_login' => $userdata['user_login'],
				'user_password' => $userdata['user_pass']
			],
			true
		);

		$subject = '[Mediator Academy] Registration Successful!';
		$message = 'Password: ' . $password;
		if(wp_mail( $email, $subject, $message ))
			return true;

		return false;
	} // function register_with_linkedin()






	/**
	 * Methot to add the new user to aweber list
	 **/
	public function add_to_aweber($email, $name, $password)
	{
		$aweber_request = get_stylesheet_directory_uri() . '/aweber-request.php?';
		$aweber_request .= 'email=' . $email . '&';
		$aweber_request .= 'name=' . urlencode($name) . '&';
		$aweber_request .= 'pwd=' . urlencode($password);

		file_get_contents( $aweber_request );
	}








	/**
	 *	Method to add new member.
	 *
	 *	@access public
	 *	@since 1.0.0
	 *
	 *	@param array $member_data
	 *
	 *	@see https://codex.wordpress.org/Class_Reference/wpdb#INSERT_row
	 *
	 *	@return mixed Member ID or false.
	 **/
	public function add( $member_data )
	{
		// var_dump( $member_data['avatar'] );
		// Validate user_id and member_data, can't be empty.

		if( empty($member_data) )
			return false;

		$defaults = array(
			'user_id' 			=> '',
			'avatar' 			=> '',
			'customer_id' 		=> '',
			'invited_by' 		=> '',
			'phone' 			=> '',
			'location' 			=> '',
			'title' 			=> '',
			'services_provided' => array(),
			'experience' 		=> array(),
			'education' 		=> array(),
			'specialisms' 		=> array(),
			'interests' 		=> array(),
			'cases_mediated' 	=> array(),
		);

		$member_data = wp_parse_args( $member_data, $defaults );

		$member_data['invited_by'] = $this->codeart_get_invited_by_hash();

		$new_member = self::$database->insert(
			self::$table,
			array(
				'user_id' 			=> $member_data['user_id'],
				'avatar' 			=> $member_data['avatar'],
				'customer_id' 		=> $member_data['customer_id'],
				'invited_by' 		=> $member_data['invited_by'],
				'uuid' 				=> UUID::v4(),
				'phone' 			=> $member_data['phone'],
				'location' 			=> $member_data['location'],
				'title' 			=> $member_data['title'],
				'services_provided' => serialize($member_data['services_provided']),
				'experience' 		=> serialize($member_data['experience']),
				'education' 		=> serialize($member_data['education']),
				'specialisms' 		=> serialize($member_data['specialisms']),
				'interests' 		=> serialize($member_data['interests']),
				'cases_mediated' 	=> serialize($member_data['cases_mediated'])
			),
			array(
				'%d',
				'%d',
				'%s',
				'%d',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s'
			)
		);

		if( isset($_COOKIE[$this->cookie_key]) )
			unset($_COOKIE[$this->cookie_key]);

		return $new_member;
	} // End of public function add( $member_data );





	public function transfer_watched_videos($user_id)
	{
		global $wpdb;
		$cookies = $this->get_cookie();
		
		if (is_array($cookies))
		{
			foreach ($cookies as $cookie)
			{
				$new_log = $wpdb->insert( 
					$wpdb->prefix . 'mediator_members_logs', 
					array( 
						'uid' => $user_id, 
						'pid' => $cookie['pid'],
						'vid' => $cookie['vid'],
						'type' => 'finished',
						'duration' => $cookie['duration']
					), 
					array(
						'%d',
						'%d',
						'%s',
						'%s',
						'%d'
					) 
				);
			}
		}
	}








	public function transfer_watched_videos_new($user_id = 0, $post_ids = [])
	{
		global $wpdb;
		if (empty($user_id) || !is_array($post_ids) || empty($post_ids)) {
			return false;
		}

		foreach($post_ids as $pid)
		{
			$vid 		= get_field('interview_video', $pid);
			$duration 	= get_field('topic_video_length', $pid);

			$new_log = $wpdb->insert( 
				$wpdb->prefix . 'mediator_members_logs', 
				array( 
					'uid' 		=> $user_id, 
					'pid' 		=> $pid,
					'vid' 		=> $vid,
					'type' 		=> 'started',
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
		}
	} // End of function transfer_watched_videos_new()







	public function is_hidden_member( $user_id )
	{
		if( empty($user_id) || !is_numeric($user_id) )
			return false;

		global $wpdb;

		$sql = sprintf(
			"SELECT count(member_id) FROM mwdpb_mediator_members_data WHERE user_id = %d AND hidden_profile = 1",
			$user_id
		);

		return intval($wpdb->get_var($sql));
	}










	public function is_member_exists( $user_id )
	{
		if( empty($user_id) )
			return true;

		$sql = sprintf(
			"SELECT COUNT(member_id) FROM mwdpb_mediator_members_data WHERE user_id = %d",
			intval($user_id)
		);

		global $wpdb;
		$user_count = $wpdb->get_var($sql);

		return intval($user_count) > 0 ? true : false;
	}







	public function print_avatar( $user_id, $size = 'thumbnail', $og = false )
	{
		if( empty($user_id) || !is_numeric($user_id) )
			return;

		global $wpdb;

		$sql = sprintf(
			"SELECT avatar FROM mwdpb_mediator_members_data WHERE user_id = %d",
			intval($user_id)
		);

		$avatar_id = intval($wpdb->get_var($sql));
		
		$avatar = wp_get_attachment_image( $avatar_id, $size );

		if(empty($avatar) && class_exists('Acf'))
		{
			$def_post_image_obj = get_field( 'option_default_thumbnail', 'option' );
			$avatar = wp_get_attachment_image( $def_post_image_obj['ID'], $size );
		}

		if ($og) {
			if (!$avatar) { return ''; }

			$doc = new DOMDocument();
			$doc->loadHTML($avatar);
			$imageTags = $doc->getElementsByTagName('img');
			$imageAvatar = '';
			foreach($imageTags as $tag) {
				$imageAvatar = $tag->getAttribute('src');
				break;
			}
			return $imageAvatar;
		}

		echo $avatar;
	} // End of function print_avatar();







	public function get_popular_mediators( $ids )
	{
		if( empty($ids) || !is_array($ids) )
			return false;

		global $wpdb;

		$sql = sprintf(
			"SELECT member_id, user_id, avatar, title FROM mwdpb_mediator_members_data WHERE user_id in (%s)",
			implode(',', $ids)
		);

		return $wpdb->get_results($sql);
	}










	public function get_popular_mediator_avatars( $ids )
	{
		if( empty($ids) || !is_array($ids) )
			return false;

		global $wpdb;
		$sql = sprintf(
			"SELECT guid FROM %s WHERE ID in (%s)",
			$wpdb->posts,
			implode(',', $ids)
		);

		return $wpdb->get_results($sql);
	}









	/**
	 * Method to encrypt user id (registration/invitation)
	 * 
	 * @since 1.0.0
	 * @access public
	 * 
	 * @param int $user_id User ID
	 * 
	 * @return string Decrypted hash
	 */
	public function encrypt_ID($user_id)
	{
		return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $this->hash_salt, $user_id, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))));
	} // End of function encrypt_ID();



	/**
	 * Method to decrypt user id (registration/invitation)
	 * 
	 * @since 1.0.0
	 * @access public
	 * 
	 * @param int $user_id User ID
	 * 
	 * @return string Decrypted hash
	 */

	public function decrypt_ID($user_id)

	{

		return intval(trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $this->hash_salt, base64_decode($user_id), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))));

	} // End of function decrypt_ID();





	
	/**
	 *	Method to update member data.
	 *
	 *	@access public
	 *	@since 1.0.0
	 *
	 *	@param int $user_id
	 *	@param array $member_data
	 *
	 *	@see https://codex.wordpress.org/Class_Reference/wpdb#UPDATE_rows
	 *
	 *	@return bool True or Falce.
	 **/
	public function update( $user_id, $member_data, $is_admin = false )
	{
		// Validate user_id and member_data, can't be empty.
		if( empty($user_id) || empty($member_data) )
			return false;

		$defaults = array(
			'avatar' 			=> '',
			'phone' 			=> '',
			'title' 			=> '',
			'hidden_profile'	=> '',
			'location' 			=> '',
			'twitter_url' 		=> '',
			'facebook_url' 		=> '',
			'google_plus_url' 	=> '',
			'services_provided' => array(),
			'experience' 		=> array(),
			'education' 		=> array(),
			'specialisms' 		=> array(),
			'interests' 		=> array(),
			'organizations' 	=> array(),
			'cases_mediated' 	=> array()
		);

		$member_data = wp_parse_args( $member_data, $defaults );

		$organizations = serialize($member_data['organizations']);

		$custom_member_data = self::get_member_data($user_id);
		if (!$is_admin) {
			$organizations = empty($custom_member_data->organizations) ? [] : $custom_member_data->organizations;
		}

		$updated_member = self::$database->update(
			self::$table, 
			array(
				'avatar' 			=> $member_data['avatar'],
				'phone' 			=> $member_data['phone'],
				'title' 			=> $member_data['title'],
				'hidden_profile'	=> intval($member_data['hidden_profile']),
				'location' 			=> $member_data['location'],
				'twitter_url' 		=> $member_data['twitter_url'],
				'facebook_url' 		=> $member_data['facebook_url'],
				'google_plus_url' 	=> $member_data['google_plus_url'],
				'services_provided' => serialize($member_data['services_provided']),
				'experience' 		=> serialize($member_data['experience']),
				'education' 		=> serialize($member_data['education']),
				'specialisms' 		=> serialize($member_data['specialisms']),
				'interests' 		=> serialize($member_data['interests']),
				'organizations' 	=> $organizations,
				'cases_mediated' 	=> serialize($member_data['cases_mediated'])
			),
			array(
				'user_id' => $user_id
			), 
			array(
				'%d',
				'%s',
				'%s',
				'%d',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s'
			), 
			array( '%d' )
		);

		return $updated_member;
	} // End of public function update( $user_id, $member_data );






	/**
	 * Method to add the customer id retreived from stripe
	 * 
	 * @param string $customer_id Customer ID
	 * @param int $user_id User ID
	 * 
	 * @return boolean True on success
	 */
	public static function add_customer_id( $customer_id,  $user_id )
	{
		$sql = sprintf(
			"UPDATE %s SET customer_id = '%s' WHERE user_id = %s",
			self::$table,
			$customer_id,
			$user_id
		);

		return self::$database->query($sql);
	} // End of function add_customer_id();






	/**
	 * Method to add the customer card id retreived from stripe
	 * 
	 * @param array $card_id Customer Card
	 * @param int $user_id User ID
	 * 
	 * @return boolean True on success
	 */
	public static function add_card_id( $card_id,  $user_id )
	{
		$sql = sprintf(
			"UPDATE %s SET card_id = '%s' WHERE user_id = %s",
			self::$table,
			maybe_serialize($card_id),
			$user_id
		);

		return self::$database->query($sql);
	} // End of function add_card_id();







	/**
	 * Function to determine if current user is premium member
	 * 
	 * @return bool
	 **/
	public function is_premium_member( $user_id = 0 )
	{
		// Allow admins
		/*
		if(is_super_admin())
			return true;
			*/

		/*
		if(is_author())
		{

		}
		$uid = get_current_user_id();
		var_dump($uid);

		if(empty($uid))
			return false;
			*/

		$user_id = empty($user_id) ? get_current_user_id() : intval($user_id);
		if (is_author()) {
			global $author;
			$user_id = $author;
		}
		
		if (empty($user_id)) {
			return false;
		}

		$check_user = self::get_member_data($user_id);

		if ($check_user->customer_id) {
			return true;
		}

		$user = new WP_User( $user_id );
		if ( !empty( $user->roles ) && is_array( $user->roles ) ) {
			$user_role = array_shift($user->roles);
			if ($user_role == 'vip_member' || $user_role == 'premium_member' || $user_role == 'administrator') {
				return true;
			}
		}

		return $this->member_data->customer_id ? true : false;
	} // End of function is_premium_member();







	/**
	 * Method to add the access flag
	 * 
	 * @param int $customer_id Access Flag
	 * @param int $user_id User ID
	 * 
	 * @return boolean True on success
	 */
	public static function add_access_flag( $access_flag, $user_id )
	{
		$sql = sprintf(
			"UPDATE %s SET access_flag = '%s' WHERE user_id = %s",
			self::$table,
			$access_flag,
			$user_id
		);

		return self::$database->query($sql);
	} // End of function add_access_flag();

	






	/**
	 *	Method to remove the member.
	 *
	 *	@access public
	 *	@since 1.0.0
	 *
	 *	@param int $user_id
	 *
	 *	@see https://codex.wordpress.org/Class_Reference/wpdb#DELETE_Rows
	 *
	 *	@return bool True or False.
	 **/
	public function remove( $user_id )
	{
		// Validate user_id, can't be empty.
		if( empty($user_id) )
			return false;

		// Sanitize
		$user_id = intval($user_id);

		return self::$database->delete( self::$table, array( 'user_id' => $user_id ) );

	} // End of public function remove( $user_id );





	/**
	 *	Method to get the member data (for one member only).
	 *
	 *	@access public
	 *	@since 1.0.0
	 *
	 *	@param int $user_id
	 *
	 *	@see https://codex.wordpress.org/Class_Reference/wpdb#SELECT_a_Row
	 *
	 *	@return mixed Array Member dataID or false.
	 **/
	public static function get_member_data( $user_id )
	{
		if( is_author() )
		{
			global $author;
			$user_id = $author;
			// _debug();
		}

		// Validate user_id, can't be empty.
		if( empty($user_id) )
			return false;

		// Sanitize
		$user_id = intval($user_id);

		// Make the query
		$sql = sprintf(
			'SELECT * FROM %s WHERE user_id = %d',
			self::$table,
			$user_id
		);

		/*
		$sql = sprintf(
			"select 
				mwdpb_mediator_members_data.*, group_concat(mwdpb_mediator_members_logs.vid separator ',') 
			from 
				mwdpb_mediator_members_data 
			left join 
				mwdpb_mediator_members_logs 
			on 
			mwdpb_mediator_members_data.user_id = mwdpb_mediator_members_logs.uid 
				where 
			mwdpb_mediator_members_data.user_id = %d",
			$user_id
		);
		*/
		
		$member_data = self::$database->get_row( $sql );

		return $member_data;
	} // End of public function get_member_data( $user_id );








	public static function get_videos_left()
	{
		if(!is_user_logged_in())
			return false;

		// Sanitize
		$user_id = get_current_user_id();
		$user_id = intval($user_id);

		// Make the query
		$sql = sprintf(
			"SELECT count(id) as left_videos FROM mwdpb_mediator_members_logs WHERE uid = %d AND type = 'started' LIMIT 0, 15",
			$user_id
		);

		$video_count = self::$database->get_row( $sql );

		return $video_count ? (int)$video_count->left_videos : false;
	} // End of public function get_member_data( $user_id );









	/**
	 * Method to deremine if the visitor can watch video
	 */
	public function can_watch_video( $video_id = '' )
	{
		if( is_user_logged_in() )
		{
			if($this->is_premium_member())
				return true;

			$all_cookies = $this->get_started_videos();
			if( count($all_cookies) >= $this->allowed_videos() )
			{
				foreach ($all_cookies as $cookie)
				{
					if($cookie->vid == $video_id)
					{
						return true;
					}
				}

				return false;
			}
		}
		else
		{
			$all_cookies = $this->get_cookie();
			// var_dump();
			if( count($all_cookies) >= $this->allowed_videos() )
			{
				foreach ($all_cookies as $cookie)
				{
					if($cookie['vid'] == $video_id)
					{
						return true;
					}
				}

				return false;
			}
		}

		return true;
	} // End of can_view_video();







	/**
	 * Allowed videos for registered guests
	 * 
	 * @return int Count of allowed videos to watch
	 **/
	public function allowed_videos()
	{
		$key = is_user_logged_in() ? 'allowed_videos_for_registered_members' : 'allowed_videos_for_non_members';
		return get_field($key, 'option') ? get_field($key, 'option') : 0;
	} // End of function allowed_videos_for_visitors();






	public function set_cookie($data)
	{
		$cookie = $this->get_cookie();
		if( empty($cookie) )
			$cookie = array();

		foreach($cookie as $c)
			if($c['vid'] == $data['vid'])
				return;

		array_push($cookie, $data);
		$cookie = base64_encode(serialize($cookie));
		setcookie($this->cookie_key, $cookie, time() + (86400 * 30), "/");
	}


	public function get_cookie()
	{
		$cookie = isset($_COOKIE[$this->cookie_key]) ? $_COOKIE[$this->cookie_key] : '';
		return empty($cookie) ? array() : unserialize(base64_decode($cookie));
	}









	/**
	 * 
	 **/
	public function get_field( $key )
	{
		if( !isset($this->member_data->{$key}) )
			return false;

		$return_value = $this->member_data->{$key};

		if( is_serialized($return_value) )
			$return_value = unserialize($return_value);

		return $return_value;
	} // public function get_field( $key );




	public function the_field( $key )
	{
		printf( $this->get_field($key) );
	}

} // End of class Mediator_Member;

?>