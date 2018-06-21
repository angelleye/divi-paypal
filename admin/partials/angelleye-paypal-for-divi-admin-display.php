<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://www.angelleye.com/
 * @since      1.0.0
 *
 * @package    Angelleye_Paypal_For_Divi
 * @subpackage Angelleye_Paypal_For_Divi/admin/partials
 */

class AngellEYE_PayPal_For_Divi_Admin_Display {
        /**
     * Hook in methods
     * @since    1.0.0
     * @access   static
     */
    public static function init() {
        add_action('admin_menu', array(__CLASS__, 'add_settings_menu'));
    }
        
    /**
     * add_settings_menu helper function used for add menu for pluging setting
     * @since    0.1.0
     * @access   public
     */
    public static function add_settings_menu() {
        add_options_page('PayPal for Divi Setting', 'PayPal for Divi', 'manage_options', 'angelleye-paypal-divi-option', array(__CLASS__, 'angelleye_paypal_divi_options'));
    }
    
        /**
     * angelleye_paypal_divi_options helper will trigger hook and handle all the settings section 
     * @since    0.1.0
     * @access   public
     */
    public static function angelleye_paypal_divi_options() {
        $setting_tabs = apply_filters('angelleye_paypal_divi_setting_tab', array('company' => __('General','angelleye_paypal_divi'),'store' => __('Store','angelleye_paypal_divi')));
        $current_tab = (isset($_GET['tab'])) ? sanitize_key($_GET['tab']) : 'company';
        ?>
        <h2 class="nav-tab-wrapper">
            <?php
            foreach ($setting_tabs as $name => $label){
                echo '<a href="' . esc_url(admin_url('admin.php?page=angelleye-paypal-divi-option&tab=' . $name)) . '" class="nav-tab ' . ( $current_tab == $name ? esc_attr('nav-tab-active') : '' ) . '">' . esc_html($label) . '</a>';
            }
            ?>
        </h2>
        <?php
        foreach ($setting_tabs as $setting_tabkey => $setting_tabvalue) {
            switch ($setting_tabkey) {
                case $current_tab:
                    do_action('angelleye_paypal_for_divi_' . $setting_tabkey . '_setting_save_field');
                    do_action('angelleye_paypal_for_divi_' . $setting_tabkey . '_setting');
                    do_action('angelleye_paypal_for_divi_' . $setting_tabkey . '_create_setting');
                    break;
            }
        }
    }
}
AngellEYE_PayPal_For_Divi_Admin_Display::init();
?>