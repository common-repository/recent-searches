<?php
/*
    Plugin Name: Recent Searches
    Plugin URI: http://www.ground6.com/wordpress-plugins/recent-searches/
    Description: Track you visitor recent searches
    Version: 0.0.1
    Author: zourbuth
    Author URI: http://zourbuth.com
    License: GPL2

	Copyright 2014 | zourbuth.com | zourbuth@gmail.com

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


/**
 * Exit if accessed directly
 * @since 0.0.1
 */
if ( ! defined( 'ABSPATH' ) )
	exit;

// Set constant variable
define( 'RECENTSEARCHES', true );
define( 'RECENTSEARCHES_VERSION', '0.0.1' );
define( 'RECENTSEARCHES_NAME', 'Recent Searches' );
define( 'RECENTSEARCHES_SLUG', 'recent-searches' );
define( 'RECENTSEARCHES_DIR', plugin_dir_path( __FILE__ ) );
define( 'RECENTSEARCHES_URL', plugin_dir_url( __FILE__ ) );


/**
 * Check if plugin activated
 * Anonymous functions are only supported on 5.3+
 * @since 0.0.1
 */
register_activation_hook( __FILE__, 'recentsearches_activation_hook' );

function recentsearches_activation_hook() { 
	add_option( 'recent_searches_version', RECENTSEARCHES_VERSION );
}


/**
 * Load the plugin
 * @since 0.0.1
 */
add_action( 'plugins_loaded', 'recentsearches_plugin_loaded' );
				
				
/**
 * Initializes the plugin and it's features with the 'plugins_loaded' action
 * Creating custom constan variable and load necessary file for this plugin
 * Attach the widget on plugin load
 * @since 0.0.1
 */
function recentsearches_plugin_loaded() {
	// Load the plugin text domain
	load_plugin_textdomain( 'recentsearches', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );	

	// Load the plugin class files
	require_once( RECENTSEARCHES_DIR . 'main.php' );
	
	// Do addon plugin action
	do_action( 'recentsearches_addons' );
	
	// Prepare widgets
	add_action( 'widgets_init', 'recentsearches_widgets_init' );
}


/**
 * Load widget files and register
 * @since 0.0.1
 */
function recentsearches_widgets_init() {
	require_once( RECENTSEARCHES_DIR . 'widget.php' );
	register_widget( 'Recent_Searches_Widget' );
}
?>