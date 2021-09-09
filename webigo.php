<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://webigo.com.br
 * @since             1.0.0
 * @package           Webigo
 *
 * @wordpress-plugin
 * Plugin Name:       Webigo
 * Plugin URI:        https://webigo.com.br
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Lanzoni Nicola
 * Author URI:        https://webigo.com.br
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       webigo
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	return;
}

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

define( 'PLUGIN_NAME', 'webigo' );

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'WEBIGO_VERSION', '1.0.0' );


/**
 * 	The path of plugin
 * 
 * 	...\wp-content\plugins\webigo
 */
define( 'WEBIGO_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );


define( 'WEBIGO_SIMPLE_PRODUCT_CLASS_NAME' , 'WC_Product_Simple' );

define( 'WEBIGO_BUNDLE_PRODUCT_CLASS_NAME' , 'WC_Product_Yith_Bundle' );


/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-webigo-activator.php
 */
function activate_webigo() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-webigo-activator.php';
	Webigo_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-webigo-deactivator.php
 */
function deactivate_webigo() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-webigo-deactivator.php';
	Webigo_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_webigo' );
register_deactivation_hook( __FILE__, 'deactivate_webigo' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/class-webigo.php';

/**
 * Run in init hook
 * https://codex.wordpress.org/Plugin_API/Action_Reference
 *
 *  Init action: Typically used by plugins to initialize. The current user is already authenticated by this time
 *
 * @since 1.0.0
 */
add_action('init', 'run_webigo');

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_webigo() {

	$plugin = new Webigo();
	$plugin->run();

}
// run_webigo();
