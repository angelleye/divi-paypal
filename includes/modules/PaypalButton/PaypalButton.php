<?php

class ET_Builder_Module_Paypal_Button extends ET_Builder_Module {

    /**
     * Function init() callled while initialization of all Divi modules
     * Name, slug, and some other settings for the module are initialize here.
     */
    
    public $slug       = 'et_pb_paypal_button';
    public $vb_support = 'on';
        
    protected $module_credits = array(
            'module_uri' => 'http://www.angelleye.com/product/divi-paypal-module-plugin/',
            'author'     => 'Angell EYE',
            'author_uri' => 'https://www.angelleye.com/',
    );
    
    function init() {
        $theme_version = et_get_theme_version();
        if (version_compare($theme_version, '3.0', '<')) {
            wp_enqueue_script('local-storage-clear', plugins_url('../admin/js/angelleye-paypal-for-divi-admin.js', __FILE__), array(), '1.0.0', true);
        }

        $this->name = esc_html__('PayPal Button', 'angelleye_paypal_divi');        
        $this->main_css_element = '%%order_class%%';

        $this->custom_css_fields = array(
            'main_element' => array(
                'label' => esc_html__('Main Element', 'angelleye_paypal_divi'),
                'no_space_before_selector' => true,
            ),
        );

        $this->settings_modal_toggles = array(
            'general' => array(
                'toggles' => array(
                    'main_content' => esc_html__('Text', 'angelleye_paypal_divi'),
                    'link' => esc_html__('Link', 'angelleye_paypal_divi'),
                ),
            ),
            'advanced' => array(
                'toggles' => array(
                    'alignment' => esc_html__('Alignment', 'angelleye_paypal_divi'),
                    'text' => array(
                        'title' => esc_html__('Text', 'angelleye_paypal_divi'),
                        'priority' => 49,
                    ),
                ),
            ),
        );

        $this->fields_defaults = array(
            'use_pbm' => array('on'),
            'background_color' => array(et_builder_accent_color(), 'add_default_setting'),
            'background_layout' => array('light')
        );
    }

    /*
     *  Function get_fields. This method returns an array of fields that the module will
     *  display as the module settings
     */

    function get_fields() {

        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        if (is_plugin_active('paypal-wp-button-manager/paypal-wp-button-manager.php')) {
            /* Below code is getting all the Buttons created by PayPal Button Manager Plugin */
            $button_manager_args = array(
                'post_type' => 'paypal_buttons',
                'post_status' => 'publish',
                'posts_per_page' => -1,
            );
            $button_manager_posts_array = get_posts($button_manager_args);
            $paypal_button_manager_option_arrray = array();
            foreach ($button_manager_posts_array as $value) {
                $paypal_button_manager_option_arrray[$value->ID] = $value->post_title;
            }
            /* end */
            $fields = array(
                'pbm_list' => array(
                    'label' => esc_html__('PayPal Button', 'angelleye_paypal_divi'),
                    'type' => 'select',
                    'option_category' => 'layout',
                    'options' => $paypal_button_manager_option_arrray,
                    'description' => esc_html__('Choose the button you would like to use in this module.  This list comes from the buttons created in the PayPal WP Button Manager plugin.', 'angelleye_paypal_divi'),
                ),
                'button_alignment' => array(
                    'label' => esc_html__('Button Alignment', 'angelleye_paypal_divi'),
                    'type' => 'select',
                    'option_category' => 'configuration',
                    'options' => array(
                        'left' => esc_html__('Left', 'angelleye_paypal_divi'),
                        'center' => esc_html__('Center', 'angelleye_paypal_divi'),
                        'right' => esc_html__('Right', 'angelleye_paypal_divi'),
                    ),
                    'description' => esc_html__('Adjust the alignment of your button.', 'angelleye_paypal_divi'),
                ),
                'admin_label' => array(
                    'label' => esc_html__('Admin Label', 'angelleye_paypal_divi'),
                    'type' => 'text',
                    'description' => esc_html__('This will change the label of the module in the builder for easy identification.', 'angelleye_paypal_divi'),
                ),
                'module_id' => array(
                    'label' => esc_html__('CSS ID', 'angelleye_paypal_divi'),
                    'type' => 'text',
                    'option_category' => 'configuration',
                    'tab_slug' => 'custom_css',
                    'option_class' => 'et_pb_custom_css_regular',
                ),
                'module_class' => array(
                    'label' => esc_html__('CSS Class', 'angelleye_paypal_divi'),
                    'type' => 'text',
                    'option_category' => 'configuration',
                    'tab_slug' => 'custom_css',
                    'option_class' => 'et_pb_custom_css_regular',
                ),
            );
            return $fields;
        } else {

            $all_page = array();
            /**
             * Adding compatibility to WPML plugin.
             * It loads all pages created for the active languages.
             * It checks for the WPML plugin is activated or installed.
             */
            if (is_plugin_active('sitepress-multilingual-cms/sitepress.php')) {
                $languages = apply_filters( 'wpml_active_languages', NULL, array( 'skip_missing' => 0));
                foreach( (array) $languages as $lang ) {

                    /* change language */
                    do_action( 'wpml_switch_language', $lang['code'] );

                    /* building query */
                    $posts = new WP_Query( array(
                        'sort_order' => 'ASC',
                        'sort_column' => 'post_title',
                        'post_type' => 'page',
                        'posts_per_page' => -1,
                        'post_status' => 'publish',
                    ) );
                    $posts = $posts->posts;
                    foreach( (array) $posts as $post ) {
                        $all_page[$post->ID] = $post->post_title;
                    }
                }
            }
            else{
                /*
                 * Below code is to get all the pages of the website to put into the return url and cancel url
                 */

                $args = array(
                    'sort_order' => 'ASC',
                    'sort_column' => 'post_title',
                    'hierarchical' => 1,
                    'exclude' => '',
                    'include' => '',
                    'meta_key' => '',
                    'meta_value' => '',
                    'authors' => '',
                    'child_of' => 0,
                    'parent' => -1,
                    'exclude_tree' => '',
                    'number' => '',
                    'offset' => 0,
                    'post_type' => 'page',
                    'post_status' => 'publish'
                );
                $pages = get_pages($args);
                foreach ($pages as $p) {
                    $all_page[$p->ID] = $p->post_title;
                }
            }
            /* end */

            /* Below code get all the companies from database. */
            global $wpdb;
            $companies = $wpdb->prefix . 'angelleye_paypal_for_divi_companies'; // do not forget about tables prefix
            $result_records = $wpdb->get_results("SELECT * FROM `{$companies}` WHERE account_id !=''", ARRAY_A);
            $all_accounts = array();
            foreach ($result_records as $result_records_value) {
                $all_accounts[$result_records_value['account_id']] = $result_records_value['title'] . ' (' . $result_records_value['account_id'] . ')';
            }
            if (empty($all_accounts)) {
                $all_accounts['noAccount'] = __('Please Add Paypal Account', 'angelleye_paypal_divi');
            }
            /* end */

            /*
             * Currency Array
             */

            $currency_codes = apply_filters('ae_paypal_divi_currency_codes', array(
                'USD' => 'U.S. Dollar (USD)',
                'CAD' => 'Canadian Dollar (CAD)',
                'AUD' => 'Australian Dollar (AUD)',
                'BRL' => 'Brazilian Real (BRL)',
                'CZK' => 'Czech koruna (CZK)',
                'DKK' => 'Danish Krone (DKK)',
                'EUR' => 'Euro (EUR)',
                'HKD' => 'Hong Kong Dollar (HKD)',
                'HUF' => 'Hungarian Forint (HUF)',
                'INR' => 'Indian rupee (INR)',
                'ILS' => 'Israeli New Sheqel (ILS)',
                'JPY' => 'Japanese Yen (JPY)',
                'MYR' => 'Malaysian Ringgit (MYR)',
                'MXN' => 'Mexican Peso (MXN)',
                'TWD' => 'New Taiwan dollar',
                'NZD' => 'New Zealand dollar',
                'NOK' => 'Norwegian Krone (NOK)',
                'PHP' => 'Philippine Peso (PHP)',
                'PLN' => 'Polish Zloty (PLN)',
                'GBP' => 'Pound Sterling (GBP)',
                'RUB' => 'Russian ruble',
                'SGD' => 'Singapore Dollar (SGD)',
                'SEK' => 'Swedish Krona (SEK)',
                'CHF' => 'Swiss Franc (CHF)',
                'THB' => 'Thai Baht (THB)',
                'TRY' => 'Turkish Lira (TRY)',
            ));
            /* currency end */

            $fields = array(
                'pp_business_name' => array(
                    'label' => esc_html__('PayPal Account ID', 'angelleye_paypal_divi'),
                    'type' => 'select',
                    'option_category' => 'layout',
                    'options' => $all_accounts,
                    'default' => key($all_accounts),
                    'description' => esc_html__('Enter your PayPal account ID or email address to specify where the payment should be sent.', 'angelleye_paypal_divi'),
                ),
                'pp_select_button' => array(
                    'label' => esc_html__('Button Type', 'angelleye_paypal_divi'),
                    'type' => 'select',
                    'option_category' => 'layout',
                    'options' => array(
                        'on' => esc_html__('Buy Now', 'angelleye_paypal_divi'),
                        'off' => esc_html__('Donate', 'angelleye_paypal_divi'),
                    ),
                    'default' => 'on',
                    'affects' => array(
                        'pp_shipping',
                        'pp_tax',
                        'pp_handling',
                    ),
                    'description' => esc_html__('Choose between a PayPal *Buy Now* button or *Donate* button.', 'angelleye_paypal_divi'),
                ),
                'pp_item_name' => array(
                    'label' => esc_html__('Item Name', 'angelleye_paypal_divi'),
                    'type' => 'text',
                    'option_category' => 'basic_option',
                    'description' => esc_html__('Add a name / description for the item or service being sold.', 'angelleye_paypal_divi'),
                ),
                'pp_amount' => array(
                    'label' => esc_html__('Item Price', 'angelleye_paypal_divi'),
                    'type' => 'text',
                    'option_category' => 'basic_option',
                    'description' => esc_html__('Enter the price for the item / service being sold. Leave blank to allow the user to enter their own amount during checkout.', 'angelleye_paypal_divi'),
                ),
                'pp_currency_code' => array(
                    'label' => esc_html__('Currency', 'angelleye_paypal_divi'),
                    'type' => 'select',
                    'option_category' => 'layout',
                    'options' => $currency_codes,
                    'default' => key($currency_codes),
                    'description' => esc_html__('Select your currency in which payment will be made.', 'angelleye_paypal_divi'),
                ),
                'pp_shipping' => array(
                    'label' => esc_html__('Shipping Amount', 'angelleye_paypal_divi'),
                    'type' => 'text',
                    'option_category' => 'basic_option',
                    'description' => esc_html__('Enter the cost of shipping for the item if you would like to override the shipping rules in your PayPal account profile.', 'angelleye_paypal_divi'),
                ),
                'pp_tax' => array(
                    'label' => esc_html__('Tax Amount', 'angelleye_paypal_divi'),
                    'type' => 'text',
                    'option_category' => 'basic_option',
                    'description' => esc_html__('Enter a sales tax amount to be charged if you would like to override the tax rules in your PayPal account profile.', 'angelleye_paypal_divi'),
                ),
                'pp_handling' => array(
                    'label' => esc_html__('Handling Amount', 'angelleye_paypal_divi'),
                    'type' => 'text',
                    'option_category' => 'basic_option',
                    'description' => esc_html__('Enter a handling fee if you would like to include one with this item / service.', 'angelleye_paypal_divi'),
                ),
                'pp_return' => array(
                    'label' => esc_html__('Return Url', 'angelleye_paypal_divi'),
                    'type' => 'select',
                    'option_category' => 'layout',
                    'options' => $all_page,
                    'default' => key($all_page),
                    'description' => esc_html__('The URL to which PayPal redirects buyers\' browser after they complete their payments.', 'angelleye_paypal_divi'),
                ),
                'pp_cancel_return' => array(
                    'label' => esc_html__('Cancel Url', 'angelleye_paypal_divi'),
                    'type' => 'select',
                    'option_category' => 'layout',
                    'options' => $all_page,
                    'default' => key($all_page),
                    'description' => esc_html__('A URL to which PayPal redirects the buyers\' browsers if they cancel checkout before completing their payments.', 'angelleye_paypal_divi'),
                ),
                'open_in_new_tab' => array(
                    'label' => esc_html__('Open in New Tab', 'angelleye_paypal_divi'),
                    'type' => 'yes_no_button',
                    'option_category' => 'basic_option',
                    'options' => array(
                        'off' => esc_html__('No', 'angelleye_paypal_divi'),
                        'on' => esc_html__('Yes', 'angelleye_paypal_divi'),
                    ),
                    'description' => esc_html__('Enable this option to open PayPal button URL in a new tab.', 'angelleye_paypal_divi'),
                ),
                'use_custom' => array(
                    'label' => esc_html__('Custom Button Display', 'angelleye_paypal_divi'),
                    'type' => 'yes_no_button',
                    'option_category' => 'basic_option',
                    'options' => array(
                        'off' => esc_html__('No', 'angelleye_paypal_divi'),
                        'on' => esc_html__('Yes', 'angelleye_paypal_divi'),
                    ),
                    'affects' => array(
                        'button_text',                        
                        'src',
                    ),
                    'description' => esc_html__('Enable this option to use a text only or custom graphic button in place of the default Buy Now / Donate button.', 'angelleye_paypal_divi'),
                ),
                'button_text' => array(
                    'label' => esc_html__('Button Text', 'angelleye_paypal_divi'),
                    'type' => 'text',
                    'option_category' => 'basic_option',
                    'description' => esc_html__('Enter a value here to be displayed in a text only button. (If an Image URL is set this text will not be displayed.)', 'angelleye_paypal_divi'),
                ),                
                'src' => array(
                    'label' => esc_html__('Image URL', 'angelleye_paypal_divi'),
                    'type' => 'upload',
                    'option_category' => 'basic_option',
                    'upload_button_text' => esc_attr__('Upload an image', 'angelleye_paypal_divi'),
                    'choose_text' => esc_attr__('Choose an Image', 'angelleye_paypal_divi'),
                    'update_text' => esc_attr__('Set As Image', 'angelleye_paypal_divi'),
                    'description' => esc_html__('Upload your desired image or type in the URL to the image you would like to display.', 'angelleye_paypal_divi'),
                ),
                'button_alignment' => array(
                    'label' => esc_html__('Button Alignment', 'angelleye_paypal_divi'),
                    'type' => 'select',
                    'option_category' => 'configuration',
                    'options' => array(
                        'left' => esc_html__('Left', 'angelleye_paypal_divi'),
                        'center' => esc_html__('Center', 'angelleye_paypal_divi'),
                        'right' => esc_html__('Right', 'angelleye_paypal_divi'),
                    ),
                    'default' =>'left',
                    'description' => esc_html__('Adjust the alignment of your button.', 'angelleye_paypal_divi'),
                ),
                'admin_label' => array(
                    'label' => esc_html__('Admin Label', 'angelleye_paypal_divi'),
                    'type' => 'text',
                    'description' => esc_html__('This will change the label of the module in the builder for easy identification.', 'angelleye_paypal_divi'),
                ),
                'module_id' => array(
                    'label' => esc_html__('CSS ID', 'angelleye_paypal_divi'),
                    'type' => 'text',
                    'option_category' => 'configuration',
                    'tab_slug' => 'custom_css',
                    'option_class' => 'et_pb_custom_css_regular',
                ),
                'module_class' => array(
                    'label' => esc_html__('CSS Class', 'angelleye_paypal_divi'),
                    'type' => 'text',
                    'option_category' => 'configuration',
                    'tab_slug' => 'custom_css',
                    'option_class' => 'et_pb_custom_css_regular',
                ),
            );
            return $fields;
        }
    }

    
    function get_advanced_fields_config() {
        return array(
            'borders' => array(
                'default' => false,
            ),
            'button' => array(
                'button' => array(
                    'label' => esc_html__('Button', 'et_builder'),
                    'css' => array(
                        'alignment' => "%%order_class%% .et_pb_button_wrapper",
                    ),
                    'box_shadow' => [
                        'css' => [
                            'main' => "{$this->main_css_element}.et_pb_module .et_pb_button ",
                        ],
                    ],
                ),
            ),
            'margin_padding' => array(
                'css' => array(
                    // 'main' => "{$this->main_css_element}.et_pb_module .dss_post_button_button, .et_pb_module {$this->main_css_element}.et_pb_module:hover",
                    'important' => 'all',
                ),
            ),
            'text' => array(
                'use_text_orientation' => false,
                'use_background_layout' => false,
                'css' => array(
                    'text_orientation' => "{$this->main_css_element}",
                ),
                'text_orientation' => array(
                    'exclude_options' => array(
                        'justified'
                    ),
                ),
            ),
            'text_shadow' => array(
                'default' => false,
            ),
            'fonts' => false,
        );
    }

    /**
     * Till v2.0.0 of this plugin, we stored directly url in database but to add compatibility
     * with WPML plugin, we stored page id in database.
     * So Below function will give page id of the url that stored in db and works with old created buttons.
     * @return int
     */
    function check_cancel_return_url($value){
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return url_to_postid($value);
        }
        else{
            return $value;
        }
    }

    /*
     *  Function render. This method returns the content the module will display
     */

    function render( $atts, $content = null, $function_name ) {
		$module_id         = isset($this->props['module_id']) ? $this->props['module_id'] : '';
		$module_class      = isset($this->props['module_class']) ? $this->props['module_class'] : '';
		$button_text       = isset($this->props['button_text']) ? $this->props['button_text'] : '';
                $src               = isset($this->props['src']) ? $this->props['src'] : '';
                $pp_item_name      = isset($this->props['pp_item_name']) ? $this->props['pp_item_name'] : '';
                $pp_amount         = isset($this->props['pp_amount']) ? $this->props['pp_amount'] : '';
		$custom_icon       = isset($this->props['button_icon']) ? $this->props['button_icon'] : '';
		$button_custom     = isset($this->props['custom_button']) ? $this->props['custom_button'] : '';
		$button_alignment  = isset($this->props['button_alignment']) ? $this->props['button_alignment'] : '';                
                $pp_select_button  = isset($this->props['pp_select_button']) ? $this->props['pp_select_button'] : '';
                $test_mode         = '';
                $pp_business_name  = isset($this->props['pp_business_name']) ? $this->props['pp_business_name'] : '';
                $pp_shipping       = isset($this->props['pp_shipping']) ? $this->props['pp_shipping'] : '';
                $pp_tax            = isset($this->props['pp_tax']) ? $this->props['pp_tax'] : '';
                $pp_handling       = isset($this->props['pp_handling']) ? $this->props['pp_handling'] : '';

                $pp_return         = isset($this->props['pp_return']) ? $this->check_cancel_return_url($this->props['pp_return']) : '';
                $pp_cancel_return  = isset($this->props['pp_cancel_return']) ? $this->check_cancel_return_url($this->props['pp_cancel_return']) : '';

                $open_in_new_tab   = isset($this->props['open_in_new_tab']) ? $this->props['open_in_new_tab'] : '';
                $use_custom        = isset($this->props['use_custom']) ? $this->props['use_custom'] : '';

                $use_pbm           = isset($this->props['use_pbm']) ? $this->props['use_pbm'] : '';
                $pbm_list          = isset($this->props['pbm_list']) ? $this->props['pbm_list'] : '';

                $pp_currency_code  = isset($this->props['pp_currency_code']) ? $this->props['pp_currency_code'] : '';

                $pp_option_shipping ='';
                $pp_option_tax      ='';
                $pp_option_handling ='';

                /* people enter the amount for the button with the $ included sometimes so we are going to stripe
                 * all characters from the paypal amount variable.
                 */
                $pp_amount = !empty($pp_amount) ? filter_var($pp_amount, FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION | FILTER_FLAG_ALLOW_THOUSAND) : '';
                $pp_amount = !empty($pp_amount) ? number_format($pp_amount, 2, '.', '') : '';
		$module_class = ET_Builder_Element::add_module_order_class( $module_class, $function_name );

		$module_class .= " et_pb_module et_pb_bg_layout_light";

                include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
                if (is_plugin_active('paypal-wp-button-manager/paypal-wp-button-manager.php')) {

                    $paypal_button_manager_post_meta=get_post_meta($pbm_list);
                    $_pbm_form             = $paypal_button_manager_post_meta['paypal_button_response'][0];
                    $_pbm_hosted_button_id = isset($paypal_button_manager_post_meta['paypal_wp_button_manager_button_id'][0]) ? $paypal_button_manager_post_meta['paypal_wp_button_manager_button_id'][0] : '';
                    $_pbm_email_link       = isset($paypal_button_manager_post_meta['paypal_wp_button_manager_email_link'][0]) ? $paypal_button_manager_post_meta['paypal_wp_button_manager_email_link'][0] : '';
                    
                    $output = sprintf(
                            '<div class="et_pb_button_module_wrapper et_pb_module%1$s">
                                %2$s
                            </div>',
                            'right' === $button_alignment || 'center' === $button_alignment ? sprintf( ' et_pb_button_alignment_%1$s', esc_attr( $button_alignment ) )  : '',
                            $_pbm_form
                    );
                    return $output;
                }
                else{
                        // Nothing to output if Account is not setup
                   if ( 'noAccount' === $pp_business_name) {
                           return;
                   }
                   
                   if(empty($pp_business_name)){
                       return;
                   }

                   global $wpdb;
                   $tablecompanies = $wpdb->prefix . 'angelleye_paypal_for_divi_companies'; // do not forget about tables prefix
                   $result_mode = $wpdb->get_results("SELECT paypal_mode FROM `{$tablecompanies}` WHERE account_id ='{$pp_business_name}'", ARRAY_A);
                   $test_mode=$result_mode[0]['paypal_mode'];
                   if ( 'Sandbox' === $test_mode ) {
                       $mode = 'sandbox.';
                   }
                   else{
                       $mode = '';
                   }
                   if($pp_select_button =='on'){
                       $cmd    = '_xclick';
                       $pp_img = 'https://www.paypalobjects.com/webstatic/en_US/i/btn/png/btn_buynow_cc_171x47.png';
                       $pp_alt = __('Buy Now With Credit Cards','angelleye_paypal_divi');
                       $pp_option_shipping = '' !== trim($pp_shipping) ? '<input type="hidden" name="shipping" value="'.esc_attr($pp_shipping).'">' : '';
                       $pp_option_tax = '' !== trim($pp_tax) ? '<input type="hidden" name="tax" value="'.esc_attr($pp_tax).'">' : '';
                       $pp_option_handling = '' !== trim($pp_handling) ? '<input type="hidden" name="handling" value="'.esc_attr($pp_handling).'">' : '';
                   }
                   elseif($pp_select_button =='off'){
                       $cmd    = '_donations';
                       $pp_img = 'https://www.paypalobjects.com/webstatic/en_US/i/btn/png/btn_donate_cc_147x47.png';
                       $pp_alt = __('Donate','angelleye_paypal_divi');
                   }

                   if('' !== $open_in_new_tab && 'on' === $open_in_new_tab){
                       $target = 'target="paypal"';
                   }
                   else{
                       $target = 'target="_self"';
                   }

                        $output = sprintf(
                            '<div class="et_pb_button_module_wrapper et_pb_module%6$s">
                                <form %21$s action="https://www.%11$spaypal.com/cgi-bin/webscr" method="post">
                                   <input type="hidden" name="business" value="%12$s">
                                   <input type="hidden" name="cmd" value="%1$s">
                                   <input type="hidden" name="item_name" value="%7$s">
                                   <input type="hidden" name="amount" value="%8$s">
                                   <input type="hidden" name="currency_code" value="%20$s">
                                   %18$s
                                   %19$s
                                   %13$s
                                   %14$s
                                   %15$s
                                   %17$s
                                </form>
                            </div>',
                            $cmd,

                            '' !== $custom_icon && 'on' === $button_custom ? sprintf(
                                    ' data-icon="%1$s"',
                                    esc_attr( et_pb_process_font_icon( $custom_icon ) )
                            ) : '',

                            '' !== $custom_icon && 'on' === $button_custom ? ' et_pb_custom_button_icon' : '',

                            ( '' !== $module_id ? sprintf( ' id="%1$s"', esc_attr( $module_id ) ) : '' ),

                            ( '' !== $module_class ? sprintf( ' %1$s', esc_attr( $module_class ) ) : '' ),

                            'right' === $button_alignment || 'center' === $button_alignment ? sprintf( ' et_pb_button_alignment_%1$s', esc_attr( $button_alignment ) )  : '',

                            $pp_item_name,
                            $pp_amount,
                            $pp_img,
                            $pp_alt,
                            $mode,
                            $pp_business_name,
                            $pp_option_shipping,
                            $pp_option_tax,
                            $pp_option_handling,
                            $button_text,
                            '' !== $use_custom && 'on' === $use_custom && '' === $src
                                                   ? sprintf('<button style="cursor: pointer;" type="submit" class="et_pb_button%2$s%3$s" %5$s%4$s>%1$s</button>',
                                                    $button_text,
                                                    '' !== $custom_icon && 'on' === $button_custom ? ' et_pb_custom_button_icon' : '',
                                                    ( '' !== $module_class ? sprintf( ' %1$s', esc_attr( $module_class ) ) : '' ),
                                                    ( '' !== $module_id ? sprintf( ' id="%1$s"', esc_attr( $module_id ) ) : '' ),
                                                    '' !== $custom_icon && 'on' === $button_custom ? sprintf(
                                                     ' data-icon="%1$s"',
                                                     esc_attr( et_pb_process_font_icon( $custom_icon ) )
                                                     ) : '')
                                                   : sprintf('<input style="cursor: pointer;" type="image" name="submit" border="0" src="%1$s" alt="%2$s"/><img alt="" border="0" width="1" height="1" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" >',
                                                           '' !==  $src ? $src : $pp_img,$pp_alt
                                                           )
                            ,
                            '' !== $pp_return ? sprintf('<input type="hidden" name="return" value="%1$s">',  get_page_link($pp_return)) : '',
                            '' !== $pp_cancel_return ? sprintf('<input type="hidden" name="cancel_return" value="%1$s">',get_page_link($pp_cancel_return)) : '',
                            $pp_currency_code,
                            $target

                    );
                    return $output;
                }
	}

}

new ET_Builder_Module_Paypal_Button;
