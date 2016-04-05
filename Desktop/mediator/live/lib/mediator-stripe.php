<?php
class MediatorStripe
{
	/**
	 * Get stripe secret key from ACF
	 */
	public function get_secret_key()
	{
		$sendbox = get_field('stripe_sendbox_mode', 'option');
		$key = $sendbox ? 'stripe_test_secret_key' : 'stripe_live_secret_key';
		return get_field($key, 'option');
	}




	/**
	 * Get stripe publishable key from ACF
	 */
	public function get_publishable_key()
	{
		$sendbox = get_field('stripe_sendbox_mode', 'option');
		$key = $sendbox ? 'stripe_test_publishable_key' : 'stripe_live_publishable_key';
		return get_field($key, 'option');
	}




	/**
	 * Stripe request
	 */
	private function stripe_request( $request, $api = 'charges', $method = 'POST' )
	{
		$api_endpoint	= 'https://api.stripe.com/';
		$secret_key		= $this->get_secret_key();
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
			$msg 	= $parsed_response->error->message;
			$param 	= ! empty( $parsed_response->error->param ) ? $parsed_response->error->param : 'stripe_error';
			return new WP_Error( $param, $msg );
		}else{
			return $parsed_response;
		}
	}





	/**
	 * Method to create new customer
	 * 
	 * @param string @source Data retreived from stripe request
	 * @param string $mail Customer email address
	 * 
	 * @return mixed Array (Stripe response) on succes or WP_Error|Exception
	 **/
	public function create_customer( $source, $mail )
	{
		$data = array();
		try
		{
			$data[ 'source' ] 	= $source;
			$data[ 'email' ] 	= $mail;
			$data[ 'description' ] = sprintf('Customer for %s', $mail);
			$response = $this->stripe_request( $data, 'customers' );
			if ( is_wp_error( $response ) )
			{
				throw new Exception( $response->get_error_message() );
			}
		}
		catch( Exception $e )
		{
			return new WP_Error( 'error', $e->getMessage() );
		}

		return $response;
	}




	public function charge_product_to_stripe( $amount, $customer, $tax )
	{
		$data = array();
		try
		{
			if( $tax != 0 )
			{
				$tax = floatval( $tax );
				$amount = floatval( $amount );
				$percent = ( $amount * $tax ) / 100;
				$amount = number_format( $amount + $percent, 2 );
			}
			$data[ 'amount' ]		= $amount * 100;
			$data[ 'customer' ] 	= $customer;
			$data[ 'currency' ]		= 'usd';
			$response = $this->stripe_request( $data );
			if ( is_wp_error( $response ) )
			{
				throw new Exception( $response->get_error_message() );
			}
		}
		catch( Exception $e )
		{
			return new WP_Error( 'error', $e->getMessage() );
		}
		return true;
	}





	/**
	 * Method to add add stripe subscription
	 * 
	 * @param string $stripe_plan_id Stripe Plan ID
	 * @param string $customer Customer ID (retreived from stripe customers)
	 * 
	 * @return mixed True on success or WP_Error instance|Exception
	 */
	public function add_stripe_subscription( $stripe_plan_id, $customer )
	{
		$data = array();
		try
		{
			$data[ 'plan' ] = $stripe_plan_id;
			$response = $this->stripe_request( $data, 'customers/' . $customer . '/subscriptions' );
			if( is_wp_error( $response ) )
			{
				throw new Exception( $response->get_error_message() );
			}
		}
		catch( Exception $e )
		{
			return new WP_Error( 'error', $e->getMessage() );
		}

		return $response;
	}



	

	/**
	 * Method to remove stripe subscription
	 * 
	 * @param string $subscription Stripe Plan ID
	 * @param string $customer Customer ID (retreived from stripe customers)
	 * 
	 * @return mixed True on success or WP_Error instance|Exception
	 */
	public function remove_stripe_subscription( $subscription, $customer )
	{
		$data = array();
		try
		{
			$response = $this->stripe_request( $data, 'customers/' . $customer . '/subscriptions/' . $subscription, 'DELETE' );
			if( is_wp_error( $response ) )
			{
				throw new Exception( $response->get_error_message() );
			}
		}
		catch( Exception $e )
		{
			return new WP_Error( 'error', $e->getMessage() );
		}

		return true;
	} // End of function remove_stripe_subscription();

} // Class FunnelStripe
?>