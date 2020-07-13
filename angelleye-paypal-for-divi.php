<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://www.angelleye.com/
 * @since             1.0.0
 * @package           Angelleye_Paypal_For_Divi
 *
 * @wordpress-plugin
 * Plugin Name:       PayPal for Divi
 * Plugin URI:        http://www.angelleye.com/product/divi-paypal-module-plugin/
 * Description:       Adds a PayPal Buy Now / Donate button module to the Divi theme by Elegant Themes.  Quickly and easily create PayPal Buy Now and Donate buttons.
 * Version:           2.0.2
 * Author:            Angell EYE
 * Author URI:        http://www.angelleye.com/
 * License:           GNU General Public License v3.0
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       angelleye_paypal_divi
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! defined( 'ANGELLEYE_PAYPAL_DIVI' ) ) {
	define( 'ANGELLEYE_PAYPAL_DIVI', plugin_basename( __FILE__ ) );
}
if ( ! defined( 'ANGELLEYE_PAYPAL_DIVI_BASE_PATH' ) ) {
	define( 'ANGELLEYE_PAYPAL_DIVI_BASE_PATH', plugin_dir_path( __FILE__ ));
}

if (!defined('AEU_ZIP_URL')) {
    define('AEU_ZIP_URL', 'https://updates.angelleye.com/ae-updater/angelleye-updater/angelleye-updater.zip');
}

if (!defined('PAYPAL_FOR_WOOCOMMERCE_PUSH_NOTIFICATION_WEB_URL')) {
    define('PAYPAL_FOR_WOOCOMMERCE_PUSH_NOTIFICATION_WEB_URL', 'https://www.angelleye.com/');
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-angelleye-paypal-for-divi-activator.php
 */
function activate_angelleye_paypal_for_divi() {
        add_option( 'my_plugin_activation','just-activated' );
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-angelleye-paypal-for-divi-activator.php';
	Angelleye_Paypal_For_Divi_Activator::activate();              
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-angelleye-paypal-for-divi-deactivator.php
 */
function deactivate_angelleye_paypal_for_divi() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-angelleye-paypal-for-divi-deactivator.php';
	Angelleye_Paypal_For_Divi_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_angelleye_paypal_for_divi' );
register_deactivation_hook( __FILE__, 'deactivate_angelleye_paypal_for_divi' );

if ( ! function_exists( 'ae_paypal_divi_initialize_extension' ) ):
/**
 * Creates the extension's main class instance.
 *
 * @since 1.0.0
 */
function ae_paypal_divi_initialize_extension() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/MyExtension.php';
}
add_action( 'divi_extensions_init', 'ae_paypal_divi_initialize_extension' );
endif;

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-angelleye-paypal-for-divi.php';

/**
 * Required functions
 */
if (!function_exists('angelleye_queue_update')) {
    require_once( 'includes/angelleye-functions.php' );
}
/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_angelleye_paypal_for_divi() {

	$plugin = new Angelleye_Paypal_For_Divi();
	$plugin->run();

}
run_angelleye_paypal_for_divi();