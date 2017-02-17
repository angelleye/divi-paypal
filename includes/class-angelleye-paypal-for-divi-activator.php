<?php

/**
 * Fired during plugin activation
 *
 * @link       http://www.angelleye.com/
 * @since      1.0.0
 *
 * @package    Angelleye_Paypal_For_Divi
 * @subpackage Angelleye_Paypal_For_Divi/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Angelleye_Paypal_For_Divi
 * @subpackage Angelleye_Paypal_For_Divi/includes
 * @author     Angell EYE <service@angelleye.com>
 */
class Angelleye_Paypal_For_Divi_Activator {

    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    1.0.0
     */
    public static function activate() {
        global $wpdb;

        // Log activation in Angell EYE database via web service.
        // @todo need to add option for people to enable this.
        //$log_url = $_SERVER['HTTP_HOST'];
        //$log_plugin_id = 9;
        //$log_activation_status = 1;
        //wp_remote_request('http://www.angelleye.com/web-services/wordpress/update-plugin-status.php?url='.$log_url.'&plugin_id='.$log_plugin_id.'&activation_status='.$log_activation_status);


        $table_name = $wpdb->prefix . "angelleye_paypal_for_divi_companies";
        $charset_collate = $wpdb->get_charset_collate();

        if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
            $sql = "CREATE TABLE " . $table_name . " (
		`ID` mediumint(9) NOT NULL AUTO_INCREMENT,
		`title` mediumtext  NULL,
                `account_id` mediumtext  NULL,
                `paypal_mode` tinytext  NULL,
		UNIQUE KEY ID (ID)
		) $charset_collate;";

            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
        } else if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name) {
            $row_title = $wpdb->get_var("SHOW COLUMNS FROM `$table_name` LIKE 'title'");            
            if (!$row_title) {
                $wpdb->query("ALTER TABLE $table_name ADD title mediumtext NULL");
            }
            $row_account_id = $wpdb->get_var("SHOW COLUMNS FROM `$table_name` LIKE 'account_id'");            
            if (!$row_account_id) {
                $wpdb->query("ALTER TABLE $table_name ADD account_id mediumtext NULL");
            }
            $row_paypal_mode = $wpdb->get_var("SHOW COLUMNS FROM `$table_name` LIKE 'paypal_mode'");            
            if (!$row_paypal_mode) {
                $wpdb->query("ALTER TABLE $table_name ADD paypal_mode tinytext NULL");
            }            
        }
    }

}
