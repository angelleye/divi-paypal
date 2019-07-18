<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://www.angelleye.com/
 * @since      1.0.0
 *
 * @package    Angelleye_Paypal_For_Divi
 * @subpackage Angelleye_Paypal_For_Divi/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Angelleye_Paypal_For_Divi
 * @subpackage Angelleye_Paypal_For_Divi/public
 * @author     Angell EYE <service@angelleye.com>
 */
class Angelleye_Paypal_For_Divi_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Angelleye_Paypal_For_Divi_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Angelleye_Paypal_For_Divi_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/angelleye-paypal-for-divi-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Angelleye_Paypal_For_Divi_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Angelleye_Paypal_For_Divi_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/angelleye-paypal-for-divi-public.js', array( 'jquery' ), $this->version, false );

	}

        public function pfd_get_environment(){
            global $wpdb;     
            $pp_business_name  = sanitize_text_field($_POST['pp_business_name']);
            if(isset($_POST['nonce']) && wp_verify_nonce( $_POST['nonce'], 'et_admin_load_nonce')){
                $tablecompanies = $wpdb->prefix . 'angelleye_paypal_for_divi_companies'; // do not forget about tables prefix
                $result_mode = $wpdb->get_results("SELECT paypal_mode FROM `{$tablecompanies}` WHERE account_id ='{$pp_business_name}'", ARRAY_A);
                $test_mode=$result_mode[0]['paypal_mode'];
                if ( 'Sandbox' === $test_mode ) {
                    echo json_encode(array('success'=>'true' ,'mode' =>'sandbox'));                   
                }
                else{
                    echo json_encode(array('success'=>'true' ,'mode' =>'live'));                    
                }
            }
            else{                
                echo json_encode(array('success'=>'true' ,'mode' =>'live'));
            }
            wp_die();
        }
        
        public function check_pbm_active(){
            $pbm_list = sanitize_text_field($_POST['pbm_list']);
            if(empty($pbm_list)){
                $button_manager_args = array(
                    'post_type' => 'paypal_buttons',
                    'post_status' => 'publish',
                    'posts_per_page' => -1,
                    'order'   => 'ASC'
                );
                $button_manager_posts_array = get_posts($button_manager_args);
                $pbm_list = isset($button_manager_posts_array[0]->ID) ? $button_manager_posts_array[0]->ID : '';                
            }
            $paypal_button_manager_post_meta=get_post_meta($pbm_list);
            $_pbm_form = isset($paypal_button_manager_post_meta['paypal_button_response'][0]) ? $paypal_button_manager_post_meta['paypal_button_response'][0] : '';
            echo json_encode(array('success'=>'true','pbm_form' => $_pbm_form));
            wp_die();
		}
		
		public function ae_get_page_link(){     
			if(wp_verify_nonce($_POST['nonce'],'et_admin_load_nonce')){
                if (filter_var($_POST['page_id'], FILTER_VALIDATE_URL)) {
                    echo json_encode(array('success'=>'true','page_url' => $_POST['page_id']));
                }
                else{
                    $page_id = sanitize_text_field($_POST['page_id']);
                    echo json_encode(array('success'=>'true','page_url' => get_page_link($page_id)));
                }
			}
			else{
				echo json_encode(array('success'=>'false','page_url' => ''));
			}			
			wp_die();
		}
}
