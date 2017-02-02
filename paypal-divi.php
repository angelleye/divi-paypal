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

function angelleye_setup_for_paypal_divi()
{
    /**
     * Build PayPal module for Divi theme by ElegantThemes.
     */
   include_once( plugin_dir_path( __FILE__ ) . 'custom-pb-paypal-module.php' );
}
add_action( 'init', 'angelleye_setup_for_paypal_divi' );

 /**
 * The code that runs during plugin activation.
  * 
  */
function angelleye_setup_For_paypal_divi_install()
{    
        // trigger our function that registers PayPal for Divi plugin.     
        angelleye_setup_for_paypal_divi();                       
}
register_activation_hook( __FILE__, 'angelleye_setup_For_paypal_divi_install' );

 /**
 * The code that runs during plugin deactivation.
  * 
  */
function angelleye_setup_for_paypal_divi_deactivate()
{
     // trigger our function that deactivate PayPal for Divi plugin.
    flush_rewrite_rules();    
}
register_deactivation_hook( __FILE__, 'angelleye_setup_for_paypal_divi_deactivate' );


/* Display a notice */
function check_divi_available(){
         ?>
    <div class="notice notice-info is-dismissible">        
        <p>
        <?php _e('<b>PayPal for Divi</b> is designed for the <a href="https://www.elegantthemes.com/gallery/divi/" target="_blank">Divi theme by Elegant Themes.</a> Please install and activate the Divi theme prior to activating <b>PayPal for Divi</b>.'); ?>
        </p>
    </div>
    <?php
}


$theme_data    = wp_get_theme();
$is_child      = is_child( $theme_data );

if ( $is_child ) {
    $parent_name = $theme_data->parent()->Name;    
    if ($parent_name != 'Divi'){
        global $pagenow;
        if ( $pagenow == 'plugins.php' ){
           add_action('admin_notices', 'check_divi_available');
        }
    }
}
else{
    if ($theme_data->Name != 'Divi') {
        /* code when divi theme is not activated. */
        global $pagenow;
         if ( $pagenow == 'plugins.php' ){
            add_action('admin_notices', 'check_divi_available');
         }
    }
}
function is_child( $theme_data ) {
    // For limitation of empty() write in var
    $parent = $theme_data->parent();
    if ( ! empty( $parent ) ) {
        return TRUE;
    }
    return FALSE;
}      