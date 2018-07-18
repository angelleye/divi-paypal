<?php

class MYEX_MyExtension extends DiviExtension {

	/**
	 * The gettext domain for the extension's translations.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $gettext_domain = 'angelleye_paypal_divi';

	/**
	 * The extension's WP Plugin name.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $name = 'angelleye_paypal_divi';

	/**
	 * The extension's version
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $version = '1.0.0';

	/**
	 * AE_PaypalButton constructor.
	 *
	 * @param string $name
	 * @param array  $args
	 */
	public function __construct( $name = 'angelleye_paypal_divi', $args = array() ) {
		$this->plugin_dir     = plugin_dir_path( __FILE__ );
		$this->plugin_dir_url = plugin_dir_url( $this->plugin_dir );                
                
                include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
                if (is_plugin_active('paypal-wp-button-manager/paypal-wp-button-manager.php')) {
                    $pbm = "true";
                }
                else{
                    $pbm = "false";
                }
                
                $this->_builder_js_data = array(
                    'pbm_plugin_active' => $pbm
                );
		parent::__construct( $name, $args );
	}
        
}

new MYEX_MyExtension;
