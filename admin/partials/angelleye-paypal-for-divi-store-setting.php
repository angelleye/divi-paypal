<?php

/**
 * This class defines all code necessary to General Setting from admin side
 * @class       AngellEYE_PayPal_WP_Button_Manager_General_Setting
 * @version	1.0.0
 * @package		paypal-wp-button-manager/includes
 * @category	Class
 * @author      Angell EYE <service@angelleye.com>
 */
class AngellEYE_PayPal_For_Divi_Store_Setting_Class {
  /**
     * Hook in methods
     * @since    0.1.0
     * @access   static
     */
    public static function init() {
        add_action('angelleye_paypal_for_divi_store_setting', array(__CLASS__, 'angelleye_paypal_for_divi_store_setting'));       
    }
    
    /**
     * angelleye_paypal_for_divi_store_setting function used for submit form of settings
     * @since 1.0.0
     * @access public
     */
    public static function angelleye_paypal_for_divi_store_setting() {
        
        $angelleye_bm_install=false;
        $angelleye_bm_plugin_activate=false;
        $plugins = array_keys(get_plugins());
        if(in_array('paypal-wp-button-manager/paypal-wp-button-manager.php',$plugins)){
                $angelleye_bm_install=true;
                if (is_plugin_active('paypal-wp-button-manager/paypal-wp-button-manager.php')) {
                   $angelleye_bm_plugin_activate=true;
                }
        }
        $message='';
        $button='';
        if($angelleye_bm_install==false && $angelleye_bm_plugin_activate==false){
            $message= "Install the PayPal WP Button Manager plugin for a more advanced PayPal button manager that is fully compatible with PayPal for Divi.";
            $button=  sprintf('<a target="_blank" href="%2$s" class="button">%1$s</a>',esc_html('More Info','angelleye_paypal_divi'),esc_url('https://www.angelleye.com/product/wordpress-paypal-button-manager/?utm_source=pbm&utm_medium=store_tab&utm_campaign=pfd'));
        }
        else if($angelleye_bm_install==true && $angelleye_bm_plugin_activate==false){
            $message= "Activate the PayPal WP Button Manager plugin if you would like to use it to manage PayPal buttons in WordPress.";
            $button=  sprintf('<a href="%2$s" class="button" aria-label="Activate PayPal WP Button Manager">%1$s</a>',  esc_html('Activate','angelleye_paypal_divi'),AngellEYE_PayPal_For_Divi_Store_Setting_Class::na_action_link('paypal-wp-button-manager/paypal-wp-button-manager.php'));
        }
        else if($angelleye_bm_install==true && $angelleye_bm_plugin_activate==true){
            $message='';
            $button=  sprintf('<a href="#" class="button">%1$s</a>',  esc_html('Installed','angelleye_paypal_divi'));
        }
    ?>       
        <div class="div_store_settings">
            <div class="wrap">                                
                <div class="plugin-card plugin-card-paypal-wp-button-manager">                    
			<div class="plugin-card-top">                            
                            <div class="name column-name">
                                <h3>
                                    <a href="https://www.angelleye.com/product/wordpress-paypal-button-manager/" class="thickbox" target="_blank">PayPal WP Button Manager
                                        <img src="//ps.w.org/paypal-wp-button-manager/assets/icon-256x256.jpg?rev=1153193" class="plugin-icon" alt="">
                                    </a>
                                </h3>
                            </div>
                            
                            <div class="action-links">
                                <ul class="plugin-action-buttons"><li><?php echo $button; ?></li></ul>
                            </div>
                            
                            <div class="desc column-description">
                                <p> <?php _e('Create more advanced PayPal buttons with PayPal WP Button Manager.  Fully compatible with PayPal for Divi so that you can easily drop PayPal buttons you have created into Divi modules.','angelleye_paypal_divi'); ?> </p>
                                <p class="authors"> <cite>By <a href="http://www.angelleye.com/">Angell EYE</a></cite></p>
                            </div>
			</div>                                            
                        <div class="plugin-card-bottom">         
                          <?php 
                            if($message!=''):
                          ?>  
                            <div id="message" class="notice notice-info" style="padding: 10px;">       
                              <?php echo $message; ?>
                          </div>
                          <?php endif; ?>  
                        </div>
                                        
        </div>
       </div>
      </div>
    <?php
    }
    
    public static function na_action_link( $plugin, $action = 'activate' ) {
	if ( strpos( $plugin, '/' ) ) {
		$plugin = str_replace( '\/', '%2F', $plugin );
	}
	$url = sprintf( admin_url( 'plugins.php?action=' . $action . '&plugin=%s&plugin_status=all&paged=1&s' ), $plugin );
	$_REQUEST['plugin'] = $plugin;
	$url = wp_nonce_url( $url, $action . '-plugin_' . $plugin );
	return $url;
    }       
}

AngellEYE_PayPal_For_Divi_Store_Setting_Class::init();
