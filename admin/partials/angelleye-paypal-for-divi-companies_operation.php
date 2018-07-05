<?php

/**
 * This class defines all code necessary to add edit delete company
 * @class       AngellEYE_PayPal_For_Divi_Company_Operations
 * @version	1.0.0
 * @package		Angelleye_Paypal_For_Divi/admin/partials
 * @category	Class
 * @author      Angell EYE <service@angelleye.com>
 */
class AngellEYE_PayPal_For_Divi_Company_Operations {

    /**
     * Hook in methods
     * @since    0.1.0
     * @access   static
     */
    function __construct() {
        
    }

    public function paypal_for_divi_add_company() {
        global $wpdb;
        $table_name = $wpdb->prefix . "angelleye_paypal_for_divi_companies";
        if(!isset($_POST['paypal_for_divi_account_id']) || sanitize_key($_POST['paypal_for_divi_account_id'])==''){
            return false;
        }
        $title =  isset($_POST['company_title']) ? sanitize_text_field($_POST['company_title']) : '';
        $account_id = isset($_POST['paypal_for_divi_account_id']) ? sanitize_text_field($_POST['paypal_for_divi_account_id']) : '';
        $paypal_mode = isset($_POST['paypal_mode']) ? sanitize_text_field($_POST['paypal_mode']) : '';
        
        $add_result = $wpdb->insert($table_name, array('title' => $title,
                                                       'account_id' => $account_id,
                                                       'paypal_mode' => $paypal_mode
                                                ));        
        return $add_result;
    }

    public function paypal_for_divi_edit_company() {
        global $wpdb;
        $table_name = $wpdb->prefix . "angelleye_paypal_for_divi_companies";
        $id = sanitize_key($_GET['cmp_id']);
        $title =  isset($_POST['company_title']) ? sanitize_text_field($_POST['company_title']) : '';
        $account_id = isset($_POST['paypal_for_divi_account_id']) ? sanitize_text_field($_POST['paypal_for_divi_account_id']) : '';
        $paypal_mode = isset($_POST['paypal_mode']) ? sanitize_text_field($_POST['paypal_mode']) : '';
        $edit_result = $wpdb->update($table_name, array('title' => $title,'account_id' => $account_id,'paypal_mode' => $paypal_mode) , array('ID' => $id), array('%s'), array('%d'));
        
        return $edit_result;
    }

    public function paypal_for_divi_delete_company() {
        global $wpdb;
        $table_name = $wpdb->prefix . "angelleye_paypal_for_divi_companies";
        $nonce = $_REQUEST['_wpnonce'];
        $ID = isset($_GET['cmp_id']) ? sanitize_key($_GET['cmp_id']) : 0;
        if (wp_verify_nonce($nonce, 'delete_company' . $ID)) {
            $delete_item = $wpdb->delete($table_name, array('ID' => $ID));
        }

        return $delete_item;
    }

}