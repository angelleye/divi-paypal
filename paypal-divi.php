<?php
/**
 *
 * @wordpress-plugin
 * Plugin Name:       PayPal for Divi
 * Plugin URI:        http://www.angelleye.com/product/paypal-for-divi-wordpress-plugin/
 * Description:       Adds a PayPal Buy Now / Donate button module to the Divi theme by Elegant Themes.  Quickly and easily create PayPal Buy Now and Donate buttons.
 * Version:           1.0.0
 * Author:            Angell EYE
 * Author URI:        http://www.angelleye.com/
 * License:           GNU General Public License v3.0
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       angelleye_paypal_divi
 * Domain Path:       /languages
 */

function angelleye_setup_For_paypaldivi()
{
    /**
     * Build PayPal module for Divi theme by ElegantThemes.
     */
   include_once( plugin_dir_path( __FILE__ ) . 'custom-pb-paypal-module.php' );
}
add_action( 'init', 'angelleye_setup_For_paypaldivi' );
 
function angelleye_setup_For_paypaldivi_install()
{
    // trigger our function that registers the custom post type
    angelleye_setup_For_paypaldivi();
}
register_activation_hook( __FILE__, 'angelleye_setup_For_paypaldivi_install' );

function angelleye_setup_For_paypaldivi_deactivate()
{
    flush_rewrite_rules();
    
}
register_deactivation_hook( __FILE__, 'angelleye_setup_For_paypaldivi_deactivate' );