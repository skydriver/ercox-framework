<?php

/**
 *	Class Mediator_Config
 *
 *	Single member functions.
 *
 **/
class Mediator_Member_Config
{

	public static $table 	= NULL;
	public static $database = NULL;
	public static $base_url = NULL;

	const version 	= '1.0.0';



	/**
	 *	Object Constructor
	 **/
	public function __construct()
	{
		global $wpdb;

		self::$database 	= $wpdb;
		self::$table 		= $wpdb->prefix . 'mediator_members_data';
		self::$base_url 	= self::base_url();
	} // End of public function __construct();





	/**
	 *	Methot to get the plugin base url
	 *
	 *	@access public
	 *
	 *	@return string The base plugin URL
	 **/
	public static function base_url()
	{
		return get_stylesheet_directory_uri() . '/lib/';
	} // End of public static function base_url();

} // End of class Mediator_Config;

?>