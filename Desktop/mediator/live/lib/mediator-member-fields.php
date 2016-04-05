<?php

class Mediator_Member_Fields
{
	public static $fields = array();

	private static $member_data = array();





	/**
	 *	Object Constructor
	 **/
	public function __construct()
	{
		// Get the member data from the database
		$user_id = (is_admin() && isset($_GET['member_id'])) ? intval($_GET['member_id']) : get_current_user_id();
		self::$member_data = Mediator_Member::get_member_data( $user_id );

		self::$fields = self::get_fields();



	} // End of public function __construct();







	public static function get_fields()
	{
		// phone field
		self::$fields['phone'] = array(
			'type' 			=> 'text',
			'default' 		=> '',
			'sanitize' 		=> 'esc_attr',
			'placeholder' 	=> 'Phone',
			'value'			=> '',
			'role'			=> 'read',
			'premium_member_field' => 1
		); // End phone field



		// title field
		self::$fields['title'] = array(
			'type' 			=> 'text',
			'default' 		=> '',
			'sanitize' 		=> 'esc_attr',
			'placeholder' 	=> 'Title',
			'value'			=> '',
			'role'			=> 'read'
		); // End title field



		// twitter_url field
		self::$fields['twitter_url'] = array(
			'type' 			=> 'text',
			'default' 		=> '',
			'sanitize' 		=> 'esc_url',
			'placeholder' 	=> 'Twitter',
			'value'			=> '',
			'role'			=> 'read',
			'premium_member_field' => 1
		); // End twitter_url field

		// facebook_url field
		self::$fields['facebook_url'] = array(
			'type' 			=> 'text',
			'default' 		=> '',
			'sanitize' 		=> 'esc_url',
			'placeholder' 	=> 'Facebook',
			'value'			=> '',
			'role'			=> 'read',
			'premium_member_field' => 1
		); // End facebook_url field

		// google_plus_url field
		self::$fields['google_plus_url'] = array(
			'type' 			=> 'text',
			'default' 		=> '',
			'sanitize' 		=> 'esc_url',
			'placeholder' 	=> 'Google+',
			'value'			=> '',
			'role'			=> 'read',
			'premium_member_field' => 1
		); // End google_plus_url field



		// hidden_profile field
		self::$fields['hidden_profile'] = array(
			'type' 			=> 'checkbox',
			'default' 		=> '',
			'id'			=> 'hidden_profile',
			'sanitize' 		=> 'intval',
			'placeholder' 	=> 'Hidden Profile',
			'value'			=> '',
			'role'			=> 'read'
		); // End hidden_profile field




		// location field
		self::$fields['location'] = array(
			'type' 			=> 'select',
			'default' 		=> '',
			'sanitize' 		=> 'esc_attr',
			'placeholder' 	=> 'Location',
			'value'			=> '',
			'role'			=> 'read',
			'options'		=> array(
									"" => "-- Select Country --",
									'AF' => 'Afghanistan',
									  'AX' => 'Åland Islands',
									  'AL' => 'Albania',
									  'DZ' => 'Algeria',
									  'AS' => 'American Samoa',
									  'AD' => 'Andorra',
									  'AO' => 'Angola',
									  'AI' => 'Anguilla',
									  'AQ' => 'Antarctica',
									  'AG' => 'Antigua and Barbuda',
									  'AR' => 'Argentina',
									  'AU' => 'Australia',
									  'AT' => 'Austria',
									  'AZ' => 'Azerbaijan',
									  'BS' => 'Bahamas',
									  'BH' => 'Bahrain',
									  'BD' => 'Bangladesh',
									  'BB' => 'Barbados',
									  'BY' => 'Belarus',
									  'BE' => 'Belgium',
									  'BZ' => 'Belize',
									  'BJ' => 'Benin',
									  'BM' => 'Bermuda',
									  'BT' => 'Bhutan',
									  'BO' => 'Bolivia',
									  'BA' => 'Bosnia and Herzegovina',
									  'BW' => 'Botswana',
									  'BV' => 'Bouvet Island',
									  'BR' => 'Brazil',
									  'IO' => 'British Indian Ocean Territory',
									  'BN' => 'Brunei Darussalam',
									  'BG' => 'Bulgaria',
									  'BF' => 'Burkina Faso',
									  'BI' => 'Burundi',
									  'KH' => 'Cambodia',
									  'CM' => 'Cameroon',
									  'CA' => 'Canada',
									  'CV' => 'Cape Verde',
									  'KY' => 'Cayman Islands',
									  'CF' => 'Central African Republic',
									  'TD' => 'Chad',
									  'CL' => 'Chile',
									  'CN' => 'China',
									  'CX' => 'Christmas Island',
									  'CC' => 'Cocos (Keeling) Islands',
									  'CO' => 'Colombia',
									  'KM' => 'Comoros',
									  'CG' => 'Congo',
									  'CD' => 'Zaire',
									  'CK' => 'Cook Islands',
									  'CR' => 'Costa Rica',
									  'CI' => 'Côte D\'Ivoire',
									  'HR' => 'Croatia',
									  'CU' => 'Cuba',
									  'CY' => 'Cyprus',
									  'CZ' => 'Czech Republic',
									  'DK' => 'Denmark',
									  'DJ' => 'Djibouti',
									  'DM' => 'Dominica',
									  'DO' => 'Dominican Republic',
									  'EC' => 'Ecuador',
									  'EG' => 'Egypt',
									  'SV' => 'El Salvador',
									  'GQ' => 'Equatorial Guinea',
									  'ER' => 'Eritrea',
									  'EE' => 'Estonia',
									  'ET' => 'Ethiopia',
									  'FK' => 'Falkland Islands (Malvinas)',
									  'FO' => 'Faroe Islands',
									  'FJ' => 'Fiji',
									  'FI' => 'Finland',
									  'FR' => 'France',
									  'GF' => 'French Guiana',
									  'PF' => 'French Polynesia',
									  'TF' => 'French Southern Territories',
									  'GA' => 'Gabon',
									  'GM' => 'Gambia',
									  'GE' => 'Georgia',
									  'DE' => 'Germany',
									  'GH' => 'Ghana',
									  'GI' => 'Gibraltar',
									  'GR' => 'Greece',
									  'GL' => 'Greenland',
									  'GD' => 'Grenada',
									  'GP' => 'Guadeloupe',
									  'GU' => 'Guam',
									  'GT' => 'Guatemala',
									  'GG' => 'Guernsey',
									  'GN' => 'Guinea',
									  'GW' => 'Guinea-Bissau',
									  'GY' => 'Guyana',
									  'HT' => 'Haiti',
									  'HM' => 'Heard Island and Mcdonald Islands',
									  'VA' => 'Vatican City State',
									  'HN' => 'Honduras',
									  'HK' => 'Hong Kong',
									  'HU' => 'Hungary',
									  'IS' => 'Iceland',
									  'IN' => 'India',
									  'ID' => 'Indonesia',
									  'IR' => 'Iran, Islamic Republic of',
									  'IQ' => 'Iraq',
									  'IE' => 'Ireland',
									  'IM' => 'Isle of Man',
									  'IL' => 'Israel',
									  'IT' => 'Italy',
									  'JM' => 'Jamaica',
									  'JP' => 'Japan',
									  'JE' => 'Jersey',
									  'JO' => 'Jordan',
									  'KZ' => 'Kazakhstan',
									  'KE' => 'KENYA',
									  'KI' => 'Kiribati',
									  'KP' => 'Korea, Democratic People\'s Republic of',
									  'KR' => 'Korea, Republic of',
									  'KW' => 'Kuwait',
									  'KG' => 'Kyrgyzstan',
									  'LA' => 'Lao People\'s Democratic Republic',
									  'LV' => 'Latvia',
									  'LB' => 'Lebanon',
									  'LS' => 'Lesotho',
									  'LR' => 'Liberia',
									  'LY' => 'Libyan Arab Jamahiriya',
									  'LI' => 'Liechtenstein',
									  'LT' => 'Lithuania',
									  'LU' => 'Luxembourg',
									  'MO' => 'Macao',
									  'MK' => 'Macedonia',
									  'MG' => 'Madagascar',
									  'MW' => 'Malawi',
									  'MY' => 'Malaysia',
									  'MV' => 'Maldives',
									  'ML' => 'Mali',
									  'MT' => 'Malta',
									  'MH' => 'Marshall Islands',
									  'MQ' => 'Martinique',
									  'MR' => 'Mauritania',
									  'MU' => 'Mauritius',
									  'YT' => 'Mayotte',
									  'MX' => 'Mexico',
									  'FM' => 'Micronesia, Federated States of',
									  'MD' => 'Moldova, Republic of',
									  'MC' => 'Monaco',
									  'MN' => 'Mongolia',
									  'ME' => 'Montenegro',
									  'MS' => 'Montserrat',
									  'MA' => 'Morocco',
									  'MZ' => 'Mozambique',
									  'MM' => 'Myanmar',
									  'NA' => 'Namibia',
									  'NR' => 'Nauru',
									  'NP' => 'Nepal',
									  'NL' => 'Netherlands',
									  'AN' => 'Netherlands Antilles',
									  'NC' => 'New Caledonia',
									  'NZ' => 'New Zealand',
									  'NI' => 'Nicaragua',
									  'NE' => 'Niger',
									  'NG' => 'Nigeria',
									  'NU' => 'Niue',
									  'NF' => 'Norfolk Island',
									  'MP' => 'Northern Mariana Islands',
									  'NO' => 'Norway',
									  'OM' => 'Oman',
									  'PK' => 'Pakistan',
									  'PW' => 'Palau',
									  'PS' => 'Palestinian Territory, Occupied',
									  'PA' => 'Panama',
									  'PG' => 'Papua New Guinea',
									  'PY' => 'Paraguay',
									  'PE' => 'Peru',
									  'PH' => 'Philippines',
									  'PN' => 'Pitcairn',
									  'PL' => 'Poland',
									  'PT' => 'Portugal',
									  'PR' => 'Puerto Rico',
									  'QA' => 'Qatar',
									  'RE' => 'Réunion',
									  'RO' => 'Romania',
									  'RU' => 'Russian Federation',
									  'RW' => 'Rwanda',
									  'SH' => 'Saint Helena',
									  'KN' => 'Saint Kitts and Nevis',
									  'LC' => 'Saint Lucia',
									  'PM' => 'Saint Pierre and Miquelon',
									  'VC' => 'Saint Vincent and the Grenadines',
									  'WS' => 'Samoa',
									  'SM' => 'San Marino',
									  'ST' => 'Sao Tome and Principe',
									  'SA' => 'Saudi Arabia',
									  'SN' => 'Senegal',
									  'RS' => 'Serbia',
									  'SC' => 'Seychelles',
									  'SL' => 'Sierra Leone',
									  'SG' => 'Singapore',
									  'SK' => 'Slovakia',
									  'SI' => 'Slovenia',
									  'SB' => 'Solomon Islands',
									  'SO' => 'Somalia',
									  'ZA' => 'South Africa',
									  'GS' => 'South Georgia and the South Sandwich Islands',
									  'ES' => 'Spain',
									  'LK' => 'Sri Lanka',
									  'SD' => 'Sudan',
									  'SR' => 'Suriname',
									  'SJ' => 'Svalbard and Jan Mayen',
									  'SZ' => 'Swaziland',
									  'SE' => 'Sweden',
									  'CH' => 'Switzerland',
									  'SY' => 'Syrian Arab Republic',
									  'TW' => 'Taiwan, Province of China',
									  'TJ' => 'Tajikistan',
									  'TZ' => 'Tanzania, United Republic of',
									  'TH' => 'Thailand',
									  'TL' => 'Timor-Leste',
									  'TG' => 'Togo',
									  'TK' => 'Tokelau',
									  'TO' => 'Tonga',
									  'TT' => 'Trinidad and Tobago',
									  'TN' => 'Tunisia',
									  'TR' => 'Turkey',
									  'TM' => 'Turkmenistan',
									  'TC' => 'Turks and Caicos Islands',
									  'TV' => 'Tuvalu',
									  'UG' => 'Uganda',
									  'UA' => 'Ukraine',
									  'AE' => 'United Arab Emirates',
									  'GB' => 'United Kingdom',
									  'US' => 'United States',
									  'UM' => 'United States Minor Outlying Islands',
									  'UY' => 'Uruguay',
									  'UZ' => 'Uzbekistan',
									  'VU' => 'Vanuatu',
									  'VE' => 'Venezuela',
									  'VN' => 'Viet Nam',
									  'VG' => 'Virgin Islands, British',
									  'VI' => 'Virgin Islands, U.S.',
									  'WF' => 'Wallis and Futuna',
									  'EH' => 'Western Sahara',
									  'YE' => 'Yemen',
									  'ZM' => 'Zambia',
									  'ZW' => 'Zimbabwe',
								)
		); // End location field

		// experience field
		self::$fields['experience'] = array(
			'type' 			=> 'repeater',
			'default' 		=> '',
			'sanitize' 		=> '',
			'placeholder' 	=> '',
			'button_text'	=> 'Experience',
			'role'			=> 'edit_posts',
			'value'			=> '',
			'options'		=> array(
                                    'experience_position' => array(
										'type' 			=> 'text',
                                        'tag'           => 'h4',
										'default' 		=> '',
                                        'class'         => '',
										'sanitize' 		=> 'esc_attr',
										'placeholder' 	=> 'Title/Position',
										'role'			=> 'read',
										'value'			=> ''
									),
									'experience_company_name' => array(
										'type' 			=> 'text',
                                        'tag'           => 'span',
                                        'class'         => 'company',
										'default' 		=> '',
										'sanitize' 		=> 'esc_attr',
										'placeholder' 	=> 'Company Name',
										'role'			=> 'read',
										'value'			=> ''
									),
									'experience_period_from' => array(
										'type' 			=> 'text',
										'default' 		=> '',
                                        'tag'           => 'p',
                                        'class'         => 'datetime',
										'sanitize' 		=> 'esc_attr',
										'placeholder' 	=> 'Time/Period From',
										'role'			=> 'read',
										'value'			=> ''
									),
									'experience_period_to' => array(
										'type' 			=> 'text',
										'default' 		=> '',
                                        'tag'           => 'p',
                                        'class'         => 'datetime',
										'sanitize' 		=> 'esc_attr',
										'placeholder' 	=> 'Time/Period To',
										'role'			=> 'read',
										'value'			=> ''
									),
									'experience_description' => array(
										'type' 			=> 'textarea',
										'default' 		=> '',
                                        'tag'           => 'p',
                                        'class'         => 'textarea',
										'sanitize' 		=> 'esc_attr',
										'placeholder' 	=> 'Description',
										'role'			=> 'read',
										'value'			=> ''
									),
								)
		); // End experience field





		// education field
		self::$fields['education'] = array(
			'type' 			=> 'repeater',
			'default' 		=> '',
			'sanitize' 		=> '',
			'placeholder' 	=> '',
			'button_text'	=> 'Education',
			'role'			=> 'edit_posts',
			'value'			=> '',
			'options'		=> array(
									'education_school_name' => array(
										'type' 			=> 'text',
										'default' 		=> '',
										'tag'           => 'h4',
                                        'class'         => '',
										'sanitize' 		=> 'esc_attr',
										'placeholder' 	=> 'School',
										'role'			=> 'read',
										'value'			=> ''
									),
									'education_degree' => array(
										'type' 			=> 'text',
										'default' 		=> '',
										'tag'           => 'p',
                                        'class'         => 'bigtext',
										'sanitize' 		=> 'esc_attr',
										'placeholder' 	=> 'Degree',
										'role'			=> 'read',
										'value'			=> ''
									),
									'education_period_from' => array(
										'type' 			=> 'text',
										'default' 		=> '',
										'tag'           => 'p',
                                        'class'         => 'datetime',
										'sanitize' 		=> 'esc_attr',
										'placeholder' 	=> 'Time/Period From',
										'role'			=> 'read',
										'value'			=> ''
									),
									'education_period_to' => array(
										'type' 			=> 'text',
										'default' 		=> '',
										'tag'           => 'p',
                                        'class'         => 'datetime',
										'sanitize' 		=> 'esc_attr',
										'placeholder' 	=> 'Time/Period To',
										'role'			=> 'read',
										'value'			=> ''
									),
									'education_activities_and_societies' => array(
										'type' 			=> 'textarea',
										'default' 		=> '',
										'tag'           => 'p',
                                        'class'         => 'textarea no-validate',
										'sanitize' 		=> 'esc_attr',
										'placeholder' 	=> 'Activities And Societies',
										'role'			=> 'read',
										'value'			=> ''
									),
									'education_description' => array(
										'type' 			=> 'textarea',
										'default' 		=> '',
										'tag'           => 'p',
                                        'class'         => 'textarea',
										'sanitize' 		=> 'esc_attr',
										'placeholder' 	=> 'Description',
										'role'			=> 'read',
										'value'			=> ''
									)
								)
		); // End education field
		
		return self::$fields;
	}






	public static function get_full_location($code = '')
	{
		if (empty($code)) {
			return '';
		}

		$fields = self::get_fields();

		$field = $fields['location'];

		$field = $field['options'];
		// _debug($field);
		// var_dump($field);

		// var_dump($field);
		return $field[strtoupper($code)];
	}





	/**
	 *	Method to print the field in html format
	 *
	 *	@access public
	 *	@since 1.0.0
	 *
	 *	@param string $field_name Field Key (database column name)
	 *
	 *	@return void.
	 **/
	public static function edit_to_html( $field_name, $input_args = array(), $index = 0, $repeater_value = '' )
	{
		if( empty($field_name) )
			return false;

		if( empty($input_args) )
		{
			if( !isset(self::$fields[$field_name]) )
				return false;

			$field = self::$fields[$field_name];
		}
		else
		{
			$field = $input_args;
		}

		// var_dump(self::$member_data);

		/*
		if( !current_user_can($field['role']) )
			return false;
			*/

		$parent_field_name = self::get_parent_key($field_name, self::$fields);
		$field_name = ($parent_field_name == false) ? $field_name : $parent_field_name . '[' . $index . '][' . $field_name. ']';

		$field_name_tmp = empty(self::$member_data->{$field_name}) ? '' : self::$member_data->{$field_name};
		$value = empty($repeater_value) ? $field_name_tmp : $repeater_value;

		switch($field['type'])
		{
			case 'text':
				global $member;
				$premium_member_field_tmp = isset($field['premium_member_field']) ? $field['premium_member_field'] : 0;

				printf(
					'<input type="text" name="%s" placeholder="%s" value="%s" %s />',
					$field_name,
					$field['placeholder'],
					$value,
					(empty($member->member_data->customer_id) && $premium_member_field_tmp) ? 'disabled' : ''
				);
				break;

			case 'checkbox':
				printf(
					'<input type="checkbox" id="%s" name="%s" value="1" %s />',
					$field['id'],
					$field_name,
					$value == 1 ? 'checked' : ''
				);
				break;

			case 'textarea':
				printf(
					'<textarea name="%s" class="%s" placeholder="%s">%s</textarea>',
					$field_name,
                    $field['class'],
					$field['placeholder'],
					$value
				);
				break;

			case 'select':
				printf( '<select name="%s">', $field_name );
				foreach( $field['options'] as $key => $value )
				{
					$key = $field_name == 'location' ? strtolower($key) : $key;
					printf(
						'<option value="%s" %s>%s</option>',
						$key,
						self::$member_data->{$field_name} == $key ? 'selected' : '',
						$value
					);
				}
				printf( '</select>' );
				break;

			case 'repeater':
				$input_fields = $field['options'];

				printf( '<div class="member-repeater-%s">', $field_name );

				$repeater_data = empty(self::$member_data->{$field_name}) ? '' : self::$member_data->{$field_name};

				if( is_serialized( $repeater_data ) )
					$repeater_data = unserialize($repeater_data);

				$check_empty_repeater = false;
				if( is_array($repeater_data) && count($repeater_data) > 0)
				{
					$repeater_data_tmp 			= $repeater_data[0];
					$repeater_data_tmp_values 	= array_values($repeater_data_tmp);
					foreach($repeater_data_tmp_values as $v)
					{
						if(!empty($v))
						{
							$check_empty_repeater = true;
							break;
						}
					}
				}

				if( $check_empty_repeater )
				{
					$repeater_index = 0;

					foreach( $repeater_data as $repeater_data_key => $repeater_data_value )
					{
						printf( '<div class="member-group-child box_can_edit">' );
						printf('<a href="#" class="mobile-edit-button">Edit</a>');
						foreach( $input_fields as $input_name => $input_args )
						{
							printf('<div class="field field-%s">', $input_name);
								printf('<div class="field-item item-%s" data-key="%s" style="display: none;">', $input_name, $input_name);
									printf('<label for="">%s</label>', $input_args['placeholder']);
									self::edit_to_html($input_name, $input_args, $repeater_index, $repeater_data_value[$input_name]);
								printf('</div>');

								if($input_args['tag']):
									printf('<%s data-key="%s" class="%s text_item">%s</%s>',
	                                       $input_args['tag'],
                                           $input_name,
	                                       $input_args['class'],
	                                       $repeater_data_value[$input_name], 
	                                       $input_args['tag']
	                                );
								endif;
							printf('</div> <!-- .field -->');
						}

                        printf('
                        	<a href="#" class="remove-repeater-row field-item">Remove %s</a>
                        	<a href="#" class="update_fields field-item">Save</a>',
                        	$field['button_text']
                        );
						printf( '</div>' );

						$repeater_index++;
					}
				}
				else
				{

					printf( '<div class="member-group-child box_can_edit editing initial-repeater">' );
					printf('<a href="#" class="mobile-edit-button">Edit</a>');
					foreach( $input_fields as $input_name => $input_args )
					{
						printf('<div class="field field-%s">', $input_name);
							printf('<div class="field-item item-%s" data-key="%s">', $input_name, $input_name);
								printf('<label for="">%s</label>', $input_args['placeholder']);
								self::edit_to_html($input_name, $input_args);
							printf('</div>');

							if($input_args['tag']):
								printf('<%s data-key="%s" class="%s text_item">%s</%s>',
	                                   $input_args['tag'],
	                                   $input_name,
	                                   $input_args['class'],
	                                   $input_args['value'],
	                                   $input_args['tag']
	                            );
							endif;

						printf('</div> <!-- .field -->');

					}
                    printf('
                    	<a href="#" class="remove-repeater-row field-item">Remove %s</a>
                    	<a href="#" class="update_fields field-item">Save</a>',
                    	$field['button_text']
                    );

					printf( '</div>' );
				}

				printf( '<a href="#" class="member-repeater-button add_new_row">Add %s</a>', $field['button_text'] );
				printf( '</div>' );
				break;
			
			default:
				break;
		}
	} // End of public static function edit_to_html( $field_name );





	/**
	 *	Method to get the parent key (for repeaters).
	 *
	 *	@access private
	 *	@since 1.0.0
	 *
	 *	@param string $search The key to search for parent array
	 *	@param array $array Current repeater array/field
	 *
	 *	@return mixed Repeater parent key/name or false.
	 **/
	private static function get_parent_key($search, $array)
	{
		foreach( self::$fields as $field_key => $field_value_array )
			if( $field_value_array['type'] == 'repeater' )
				foreach( $field_value_array['options'] as $stack_key => $stack_value )
					if( $stack_key == $search )
						return $field_key;

	    return false;
	} // End of private static function get_parent_key($search, $array);





	/**
	 *	Method to print the field in html format
	 *
	 *	@access public
	 *	@since 1.0.0
	 *
	 *	@param string $field_name Field Key (database column name)
	 *
	 *	@return void.
	 **/
	public static function wp_edit_to_html( $field_name )
	{
		if( empty($field_name) )
			return false;

		$the_user = null;
		global $pagenow;

		if( isset($_GET['member_id']) && $pagenow == 'users.php' && current_user_can('manage_options') )
		{
			$user_id = intval($_GET['member_id']);
			if( !$user_id )
				return;

			$the_user = get_userdata( $user_id );
		}
		else
		{
			global $current_user;
			get_currentuserinfo();
			$the_user = $current_user;
		}
		

		switch( $field_name )
		{
			case 'first_name':
				printf( '<input type="text" class="wpvalidate" name="first_name" value="%s" placeholder="First Name" />', esc_attr($the_user->user_firstname) );
				break;

			case 'last_name':
				printf( '<input type="text" class="wpvalidate" name="last_name" value="%s" placeholder="Last Name" />', esc_attr($the_user->user_lastname) );
				break;

			case 'email':
				printf( '<input type="email" class="wpvalidate" name="email" value="%s" placeholder="Email Address" />', sanitize_email($the_user->user_email) );
				break;

			case 'display_name':
				printf( '<input type="text" class="wpvalidate" name="display_name" value="%s" placeholder="Display Name" />', esc_attr($the_user->display_name) );
				break;

			case 'description':
				printf( '<textarea name="description" class="wpvalidate" id="description" cols="30" rows="10">%s</textarea>', esc_attr($the_user->description) );
				break;
			
			default:
				break;
		}
	} // End of public static function edit_to_html( $field_name );





	/**
	 *	Method to print the field.
	 *
	 *	@access public
	 *
	 *	@param string $field_name The field key/name
	 *
	 *	@return void.
	 **/
	public static function wp_to_html( $field_name, $field_value = '' )
	{
		if( empty($field_name) )
			return;

		global $current_user;

		switch( $field_name )
		{
			case 'first_name':
				printf(
					'<p class="%s">
						<span class="label">%s</span>
						<span class="value">%s</span>
					</p>',
					'text-field',
					'First Name',
					esc_attr($current_user->user_firstname)
				);
				break;

			case 'last_name':
				printf(
					'<p class="%s">
						<span class="label">%s</span>
						<span class="value">%s</span>
					</p>',
					'text-field',
					'Last Name',
					esc_attr($current_user->user_lastname)
				);
				break;

			case 'email':
				printf(
					'<p class="%s">
						<span class="label">%s</span>
						<span class="value">%s</span>
					</p>',
					'text-field',
					'Email Address',
					esc_attr($current_user->user_email)
				);
				break;

			case 'display_name':
				printf(
					'<p class="%s">
						<span class="label">%s</span>
						<span class="value">%s</span>
					</p>',
					'text-field',
					'Display Name',
					esc_attr($current_user->display_name)
				);
				break;
			
			default:
				break;
		}
	} // public function wp_to_html( $field_name );


} // End of class Mediator_Member_Fields;

?>