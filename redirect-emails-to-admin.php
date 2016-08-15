<?php
/**
 * Plugin Name: Redirect All Emails to Admin
 * Plugin URI: http://wordpress.org/plugins/redirect-emails-to-admin/
 * Description: Redirects all emails to site admin. This is useful in making sure that a development or staging site doesn't send out confusing emails to your users.
 * Version: 1.0
 * Author: Jon Brown
 * Author URI: http://9seeds.com/
 * License: GPL2
 */


/* Props:
 * This plugin was very heavily based on Jeremy Pry's WPE Rdirect Emails on Staging plugin which
 * was built for WP Engine Staging only and hasn't been update in quite a while. However,
 * it is still available from https://github.com/PrysPlugins/WPE-redirect-emails-on-staging
 * and https://wordpress.org/plugins/redirect-emails-on-staging/ if you want to check it out
 */


// Prevent direct access to this file
if ( ! defined( 'ABSPATH' ) ) {
	die( "You can't do anything by accessing this file directly." );
}

// Activation check
register_activation_hook( plugin_basename( __FILE__ ), 's9_rea_activation_check' );

/**
 * Check to ensure the plugin is able to run
 *
 * We're specifically looking to make sure there is a PHP version of 5.3.2 or greater
 *
 * @since 1.0
 */
if ( ! function_exists( 's9_rea_activation_check' ) ) {
	function s9_rea_activation_check() {
		$min_php_version = '5.3.2';
		$installed_php_version = phpversion();

		if ( version_compare( $min_php_version, $installed_php_version, '>' ) ) {
			deactivate_plugins( plugin_basename( __FILE__ ) );
			wp_die( sprintf( 'This plugin requires a minimum PHP version of %s. You only have version %s installed. Please upgrade PHP to use this plugin.', $min_php_version, $installed_php_version ) );
		}
	}
}

// Some constants
define( 'RES_FILE', __FILE__ );
define( 'RES_DIR', dirname( RES_FILE ) );

// Pull in the class files
require_once( RES_DIR . '/classes/class-s9-rea-singleton.php' );
require_once( RES_DIR . '/classes/class-s9-redirect-email-to-admin.php' );

// Instantiate the class
S9_Redirect_Email_to_Admin::get_instance();
