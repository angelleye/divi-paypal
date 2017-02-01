<?php
/*
Plugin Name: Paypal For Divi
Plugin URI: https://www.angelleye.com/
Version: 1.0
Author: Andrew Angell
Description: Use Paypal Button in divi module.
*/

function angelleye_setup_For_paypaldivi()
{
    // register the "book" custom post type
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