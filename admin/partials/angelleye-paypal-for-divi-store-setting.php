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
                
        if (is_plugin_active('paypal-wp-button-manager/paypal-wp-button-manager.php')) {
           $plugin_activate=true;
        }
        else{
           $plugin_activate=false;
        }
    ?>       
        <div class="div_store_settings">
            <div class="wrap">                                
                <div class="plugin-card plugin-card-paypal-wp-button-manager">                    
			<div class="plugin-card-top">                            
                            <div class="name column-name">
                                <h3>
                                    <a href="https://wordpress.org/plugins/paypal-wp-button-manager/" class="thickbox" target="_blank">PayPal WP Button Manager
                                        <img src="//ps.w.org/paypal-wp-button-manager/assets/icon-256x256.jpg?rev=1153193" class="plugin-icon" alt="">
                                    </a>
                                </h3>
                            </div>
                            
                            <div class="action-links">
                                <ul class="plugin-action-buttons"><li><?php if(!$plugin_activate) { ?> <a href="https://wordpress.org/plugins/paypal-wp-button-manager/" class="button" aria-label="Activate PayPal WP Button Manager"><?php _e('Get It Now','angelleye_paypal_divi'); ?></a><?php } else { _e( "<strong>Installed</strong>",'angelleye_paypal_divi'); } ?></li></ul>
                            </div>
                            
                            <div class="desc column-description">
                                <p> <?php _e('Developed by an Ace Certified PayPal Developer, official PayPal Partner, PayPal Ambassador, and 3-time PayPal Star Developer Award Winner.','angelleye_paypal_divi'); ?> </p>
                                <p class="authors"> <cite>By <a href="http://www.angelleye.com/">Angell EYE</a></cite></p>
                            </div>
			</div>
                        <?php if(!$plugin_activate) : ?>
                        <div class="plugin-card-bottom">                    
                          <div id="message" class="notice notice-info">
                              <?php _e('<p><strong>PayPal Button Manager Plugin</strong> is Compatible with <strong>PayPal For Divi Plugin</strong>.<strong>PayPal Button Manager Plugin</strong> is not installed/activated.</p>','angelleye_paypal_divi'); ?>
                          </div>
                        </div>
                        <?php endif; ?>                
        </div>
       </div>
      </div>
    <?php
    }
}

AngellEYE_PayPal_For_Divi_Store_Setting_Class::init();
