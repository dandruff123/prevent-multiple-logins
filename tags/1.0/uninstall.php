<?php
/**
 * @package PML
 *
 * Code used when the plugin is removed (not just deactivated but actively 
 * deleted through the WordPress Admin).
 */

if( !defined( 'ABSPATH') && !defined('WP_UNINSTALL_PLUGIN') )
    exit();

if( !get_option('uwpml_options') )
delete_option( 'uwpml_options' );

