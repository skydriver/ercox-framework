<?php

/**
 *	Mediator_Members_Config Class Interface
 **/
interface Mediator_Members_Config_Interface
{
	public static function base_url();
} // End of interface Mediator_Members_Config_Interface;



/**
 *	Mediator_Member Class Interface
 **/
interface Mediator_Member_Interface
{
	public function __construct();
	public function add( $member_data );
	public function update( $user_id, $new_member_data );
	public function remove( $user_id );
	public static function get_member_data( $user_id );
} // End of interface Mediator_Member_Interface;



/**
 *	Mediator_Member_Fields Class Interface
 **/
interface Mediator_Member_Fields_Interface
{
	
} // End of interface Mediator_Member_Fields_Interface;



/**
 *	Mediator_Member_Fields Class Interface
 **/
interface Mediator_Member_JavaScripts_Interface
{
	public static function repeater_scripts();
	public static function media_scripts();
} // End of interface Mediator_Member_JavaScripts_Interface;

?>