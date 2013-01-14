<?php
/*
Plugin Name: Prevent Multiple Logins
Plugin URI: http://code.google.com/p/prevent-multiple-logins/
Description: Prevents multiple logins to the same user account.
Version: 1.0
Author: Upeksha Wisidagama
Author URI: http://code.google.com/p/prevent-multiple-logins/people/list
License: GPL2 or later.
*/

/*  Copyright 2013  Upeksha Wisidagama  (email : upeksha@php-sri-lanka.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston,
    MA  02110-1301  USA
*/

// Don't allow this file to be called directly.
if( !defined( 'ABSPATH' ) ){
    header('HTTP/1.0 403 Forbidden');
    die('No Direct Access Allowed!');
}

if ( ! class_exists( 'UWPML_Prevent_Multiple_Logins' ) ){
    
/**
 * Prevent Multiple Logins
 * 
 * This class is instantiated and 
 * plugin_setup() method is attached to
 * the 'plugins_loaded' action hook.
 * 
 * @package uwpml 
 * @since 1.0
 */    
class UWPML_Prevent_Multiple_Logins
{
	/**
	 * Plugin instance.
	 *
	 * @var UWPML_Prevent_Multiple_Logins Plugin Instance.
	 */
	protected static $instance = NULL;
        
        protected $manage_options;

        /**
	 * URL to this plugin's directory.
	 *
	 * @var string
	 */
	public $plugin_url = '';

	/**
	 * Path to this plugin's directory.
	 *
	 * @type string
	 */
	public $plugin_path = '';
        
	/**
	 * Basename this plugin's directory.
	 *
	 * @type string
	 */
	public $plugin_basename = '';        

	/**
	 * Access this plugin’s working instance
	 *
	 * @return  object of this class
	 */
	public static function get_instance(){
		NULL === self::$instance and self::$instance = new self;

		return self::$instance;
	}

	/**
	 * Plugin Setup.
	 *
	 * @return  void
	 */
	public function plugin_setup(){
            $this->plugin_url      = plugin_dir_url( __FILE__ );
            $this->plugin_path     = plugin_dir_path( __FILE__ );
            $this->plugin_basename = dirname( plugin_basename( __FILE__ ) );
            $this->load_language( 'uwpml' );

            include 'inc/class-uwpml-manage-options.php';
            $this->manage_options = new UWPML_Manage_Options();

            add_action('after_head', array( $this, 'after_head' ) );
	}
        
        /**
         * Outputs contents with 'after_head' action hook.
         * 
         * @return void
         */
        public function after_head(){
            echo $this->plugin_basename . '/languages';
            echo __( 'Hello', 'uwpml');
        }

        /**
	 * Constructor. Intentionally left empty and public.
	 *
         * empty-constructor-approach
         * https://github.com/toscho
         * 
         * @return void 
	 */
	public function __construct() {}

	/**
	 * Loads translation file.
	 *
	 * @param   string $domain
	 * @return  void
	 */
	public function load_language( $domain ){
		load_plugin_textdomain(
			$domain,
			null,
			$this->plugin_basename . '/languages'
		);
	}
}
}

/**
 * Initialize plugin class and attach 'plugin_setup' method
 * to 'plugins_loaded' action hook.
 */
add_action(
    'plugins_loaded',
    array ( UWPML_Prevent_Multiple_Logins::get_instance(), 'plugin_setup' )
);
?>