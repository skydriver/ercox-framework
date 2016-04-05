<?php
/**
 *	Template Name: Upgrade
 **/

require_once 'lib/mediator-member-loader.php';

// Full Width
add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );


add_filter('body_class', 'codeart_upgrade_body_classes');
function codeart_upgrade_body_classes( $classes )
{
	$classes[] = 'codeart-upgrade';
	return $classes;
}


global $member;
$member = new Mediator_Member;



remove_action('genesis_loop', 'genesis_do_loop');
add_action('genesis_loop', 'codeart_upgrade_loop');
function codeart_upgrade_loop()
{
	global $member;
	
	$member_card = $member->member_data->card_id;

	if( $member_card )
		$member_card = maybe_unserialize( $member_card ); ?>

    <div class="payment-heading">
        <h2>Simple Pricing, No Surprises</h2>
        <h3>UPGRADE to Premium Membership.</h3>
        <h3>Free trial, cancel anytime</h3>
    </div>

	<form action="" method="POST" id="payment-form">
	    <div class="notice" style="text-align: center;">
            <p>The form is in test mode but you can "Upgrade" your account to test the premium features. Just click Complete Payment button below.</p>
        </div>
        
		<span class="payment-errors"></span>

		<?php $stripe_plans = get_field('stripe_plans', 'option'); ?>

		<?php if( $stripe_plans ): ?>
		<div class="form-row">
			<label>
				<span class="payment_heading">Select Package</span>

				<?php
				$selected_plan_index = 0;
				$selected_plan_counter = 0;

				if (isset($_GET['type'])) {
					foreach($stripe_plans as $plan) {
						if (strtolower($plan['name']) == $_GET['type']) {
							$selected_plan_index = $selected_plan_counter;
							break;
						}
						$selected_plan_counter++;
					}
				}
				?>

				<?php $counter = 0; ?>

				<?php $stripe_prices = ['ma-yearly' => 30, 'mediatoracademyplan' => 37]; ?>

				<?php foreach($stripe_plans as $splan): ?>
				<div class="radio-holder">
				    <input type="radio" id="<?php echo $splan['plan']; ?>" name="membership_level" value="<?php echo $splan['plan']; ?>" <?php echo $counter++ == $selected_plan_index ? 'checked' : ''; ?>>
                    <label for="<?php echo $splan['plan']; ?>"><?php echo $splan['name']; ?> <span class="updade_price"><span class="text_up">$</span><?php echo $stripe_prices[$splan['plan']]; ?><span class="text_up"></span></span></label>
                </div>
				<?php endforeach; ?>
                
			</label>
		</div>
		<?php endif; ?>
		
		<div class="country-heading">
		    <span class="payment_heading">Select Your Country Of Residence</span>
		</div>

		<div class="country-details">
		    <select name="country" id="country"><?php
$countries = array
(
	'AF' => 'Afghanistan',
	'AX' => 'Aland Islands',
	'AL' => 'Albania',
	'DZ' => 'Algeria',
	'AS' => 'American Samoa',
	'AD' => 'Andorra',
	'AO' => 'Angola',
	'AI' => 'Anguilla',
	'AQ' => 'Antarctica',
	'AG' => 'Antigua And Barbuda',
	'AR' => 'Argentina',
	'AM' => 'Armenia',
	'AW' => 'Aruba',
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
	'BA' => 'Bosnia And Herzegovina',
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
	'CD' => 'Congo, Democratic Republic',
	'CK' => 'Cook Islands',
	'CR' => 'Costa Rica',
	'CI' => 'Cote D\'Ivoire',
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
	'HM' => 'Heard Island & Mcdonald Islands',
	'VA' => 'Holy See (Vatican City State)',
	'HN' => 'Honduras',
	'HK' => 'Hong Kong',
	'HU' => 'Hungary',
	'IS' => 'Iceland',
	'IN' => 'India',
	'ID' => 'Indonesia',
	'IR' => 'Iran, Islamic Republic Of',
	'IQ' => 'Iraq',
	'IE' => 'Ireland',
	'IM' => 'Isle Of Man',
	'IL' => 'Israel',
	'IT' => 'Italy',
	'JM' => 'Jamaica',
	'JP' => 'Japan',
	'JE' => 'Jersey',
	'JO' => 'Jordan',
	'KZ' => 'Kazakhstan',
	'KE' => 'Kenya',
	'KI' => 'Kiribati',
	'KR' => 'Korea',
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
	'FM' => 'Micronesia, Federated States Of',
	'MD' => 'Moldova',
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
	'RE' => 'Reunion',
	'RO' => 'Romania',
	'RU' => 'Russian Federation',
	'RW' => 'Rwanda',
	'BL' => 'Saint Barthelemy',
	'SH' => 'Saint Helena',
	'KN' => 'Saint Kitts And Nevis',
	'LC' => 'Saint Lucia',
	'MF' => 'Saint Martin',
	'PM' => 'Saint Pierre And Miquelon',
	'VC' => 'Saint Vincent And Grenadines',
	'WS' => 'Samoa',
	'SM' => 'San Marino',
	'ST' => 'Sao Tome And Principe',
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
	'GS' => 'South Georgia And Sandwich Isl.',
	'ES' => 'Spain',
	'LK' => 'Sri Lanka',
	'SD' => 'Sudan',
	'SR' => 'Suriname',
	'SJ' => 'Svalbard And Jan Mayen',
	'SZ' => 'Swaziland',
	'SE' => 'Sweden',
	'CH' => 'Switzerland',
	'SY' => 'Syrian Arab Republic',
	'TW' => 'Taiwan',
	'TJ' => 'Tajikistan',
	'TZ' => 'Tanzania',
	'TH' => 'Thailand',
	'TL' => 'Timor-Leste',
	'TG' => 'Togo',
	'TK' => 'Tokelau',
	'TO' => 'Tonga',
	'TT' => 'Trinidad And Tobago',
	'TN' => 'Tunisia',
	'TR' => 'Turkey',
	'TM' => 'Turkmenistan',
	'TC' => 'Turks And Caicos Islands',
	'TV' => 'Tuvalu',
	'UG' => 'Uganda',
	'UA' => 'Ukraine',
	'AE' => 'United Arab Emirates',
	'GB' => 'United Kingdom',
	'US' => 'United States',
	'UM' => 'United States Outlying Islands',
	'UY' => 'Uruguay',
	'UZ' => 'Uzbekistan',
	'VU' => 'Vanuatu',
	'VE' => 'Venezuela',
	'VN' => 'Viet Nam',
	'VG' => 'Virgin Islands, British',
	'VI' => 'Virgin Islands, U.S.',
	'WF' => 'Wallis And Futuna',
	'EH' => 'Western Sahara',
	'YE' => 'Yemen',
	'ZM' => 'Zambia',
	'ZW' => 'Zimbabwe',
);

				$http_accept_lang = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
				$http_accept_lang = explode('-', $http_accept_lang);
				$http_accept_lang = substr($http_accept_lang[1], 0, 2);
				var_dump($http_accept_lang);
				$selected = false;
				foreach ($countries as $code => $name) {
					$selected = false;
					if ($code == $http_accept_lang) {
						$selected = true;
					}
					printf('<option value="%s"%s>%s</option>', $code, ($selected ? ' selected' : ''), $name);
				}
?>
		    </select>
		</div>

		<div class="card-details">
		    <span class="payment_heading">Card Details</span>
		</div>
		
		<div class="form-row stripe-input card_number">
			<label>
				<span class="payment_title">Card Number</span>
				<input type="text" size="20" data-stripe="number" maxlength="16" value="4242424242424242" readonly />
			</label>
		</div>
		<div class="form-row stripe-input card_cvc">
			<label>
				<span class="payment_title">CVC</span>
				<input type="text" size="4" data-stripe="cvc" value="3423" readonly/>
			</label>
		</div>
		<div class="form-row stripe-input card_expire">
			<label>
				<span class="payment_title">Expiration (MM/YYYY)</span>
<!--				<input type="text" size="2" data-stripe="exp-month" value="10"/>-->
            
            <select data-stripe="exp-month" onfocus="this.defaultIndex=this.selectedIndex;" onchange="this.selectedIndex=this.defaultIndex;">
                <option value="1">January</option>
                <option value="2">February</option>
                <option value="3">March</option>
                <option value="4">April</option>
                <option value="5">May</option>
                <option value="6">June</option>
                <option value="7">July</option>
                <option value="8">August</option>
                <option value="9">September</option>
                <option value="10">October</option>
                <option value="11">November</option>
                <option value="12" selected="selected">December</option>
            </select>
				
			</label>
			<span class="slash"> / </span>
<!--			<input type="text" size="4" data-stripe="exp-year" value="2020"/>-->
			<select class="year_input" data-stripe="exp-year">
                <?php for($i=date('Y'); $i<date('Y')+10; $i++): ?>
                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
            	<?php endfor; ?>
			</select>
		</div>
		<div class="purchase">
		    <button type="submit">Complete Payment</button>
		</div>
		<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/ssl-secure.png" width="65" height="35" class="ssl_secure" alt="">
	</form>
	
	<?php
}





add_action('wp_footer', 'codeart_stripe_js');
function codeart_stripe_js()
{
	?>
	<script type="text/javascript">
	<?php $mediatorStripe = new MediatorStripe; ?>
	Stripe.setPublishableKey('<?php echo $mediatorStripe->get_publishable_key(); ?>');
	jQuery(function($) {
        
		function stripeResponseHandler(status, response) {
			var $form = $('#payment-form');

			if (response.error) {
				$form.find('.payment-errors').text(response.error.message);
                $('body').addClass('codeart-upgrade-error');
                
                $('html, body').animate({
                scrollTop: $('body.codeart-upgrade form#payment-form').offset().top
                }, 800);
                $form.find('button').prop('disabled', false);
			} else {
                
                $('body').find('div.ca_custom_loader').fadeIn().addClass('go');
                
                var timer = 2200;
                
                setInterval(function(){
                    $('body').find('div.ca_custom_loader').removeClass('go');
                    setTimeout(function(){
                        $('body').find('div.ca_custom_loader').addClass('go');
                    },timer)
                },timer);
                
				var token = response.id;
				$form.append($('<input type="hidden" name="stripeToken" />').val(token));

				var data = {
					'action': 'upgrade_profile',
					'token': token,
					'stripe_plan': $form.find('input[name="membership_level"]:checked').val()
				};
				
				jQuery.post(ajaxurl, data, function(response) {
					console.log(response);
					var obj = jQuery.parseJSON(response);
					if(obj.status == true)
					{
						window.location = "<?php the_field('thank_you_upgrade', 'option'); ?>";
					}
					else
					{
						$('body').find('.payment-errors').text(obj.message);
						$('body').find('div.ca_custom_loader').fadeOut().removeClass('go');
					}

					$form.find('button').prop('disabled', false);
					
				});
			}
		};

		$('body').find('#payment-form').on('submit', function(e) {
			var $form = $(this);
			$('body').find('.payment-errors').text('');

			$form.find('button').prop('disabled', true);
			Stripe.card.createToken($form, stripeResponseHandler);
			return false;
		});

		// window.location = "http://www.google.com";
	});
	</script>
	<?php
}




add_action( 'wp_enqueue_scripts', 'codeart_add_stripe_js_sdk' );
function codeart_add_stripe_js_sdk()
{
	wp_enqueue_script(
		'stripe-sdk',
		'https://js.stripe.com/v2/',
		array('jquery'),
		'1.0.0',
		false
	);
}


genesis();

?>