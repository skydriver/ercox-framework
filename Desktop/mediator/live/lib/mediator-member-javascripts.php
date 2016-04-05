<?php

abstract class Mediator_Member_JavaScripts extends Mediator_Member_Config
{

	/**
	 *	Method to print the repeater scripts (required for fields/edit profile)
	 *
	 *	@access public
	 *
	 *	@return void.
	 **/
	public static function repeater_scripts()
	{
		wp_enqueue_script(
			'mediator-member-repeater',
			self::base_url() . '/js/repeater.js',
			array('jquery'),
			self::version,
			true
		);
	} // End of public static function repeater_scripts();





	/**
	 *	Method to print the media scripts (required for fields/edit profile/profile avatar)
	 *
	 *	@access public
	 *
	 *	@return void.
	 **/
	public static function media_scripts()
	{
		add_action('wp_enqueue_scripts', array(__CLASS__, 'members_add_media_upload_scripts'));
		add_action('admin_enqueue_scripts', array(__CLASS__, 'members_add_media_upload_scripts'));

		wp_enqueue_script(
			'mediator-member-media',
			self::base_url() . '/js/media.js',
			array('jquery'),
			self::version,
			true
		);
	} // End of public static function media_scripts();




	
	/**
	 *	Method to include core media scripts (required for profile avatar/edit profile)
	 *
	 *	@access public
	 *
	 *	@return void.
	 **/
	public static function members_add_media_upload_scripts()
	{
		wp_enqueue_media();
	} // End of public static function members_add_media_upload_scripts();



} // End of class Mediator_Member_JavaScripts

?>