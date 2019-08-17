<?php
/*
Plugin Name:  SamplePlugin
Description:  Example of adding a custom post with meta box and integrating the REST API.
Plugin URI:   https://WEBSITE
Author:       Matthew Boswell
Version:      1.0
Text Domain:  sampleplugin
Domain Path:  /languages
License:      GPL v2 or later
License URI:  https://www.gnu.org/licenses/gpl-2.0.txt
*/



// disable direct file access
if ( ! defined( 'ABSPATH' ) ) {

	exit;

}



// Internationalization: load text domain
function sampleplugin_load_textdomain() {

	load_plugin_textdomain( 'sampleplugin', false, plugin_dir_path( __FILE__ ) . 'languages/' );

}
add_action( 'plugins_loaded', 'sampleplugin_load_textdomain' );


// include plugin dependencies
require_once plugin_dir_path( __FILE__ ) . 'includes/core-functions.php';


