<?php

function angelleye_paypal_button_module() {

    if (class_exists('ET_Builder_Module')) {    
       class ET_Builder_Module_Paypal_Button extends ET_Builder_Module {
        /**
         * Function init() callled while initialization of all Divi modules 
         * Name, slug, and some other settings for the module are initialize here.       
        */   
	function init() {            
                wp_enqueue_script( 'local-storage-clear', plugins_url('../admin/js/angelleye-paypal-for-divi-admin.js',__FILE__), array(), '1.0.0', true );
		$this->name = esc_html__( 'PayPal Button', 'angelleye_paypal_divi' );
		$this->slug = 'et_pb_paypal_button';

		$this->whitelisted_fields = array(
                        'use_pbm',
                        'pbm_list',
                        'pp_business_name',
                        'pp_select_button',
			'pp_item_name',
                        'pp_currency_code',
			'pp_amount',
                        'pp_shipping',
                        'pp_tax',
                        'pp_handling',
                        'pp_return',
                        'pp_cancel_return',
                        'use_custom',
                        'button_text',
                        'src',
                        'background_layout',
                        'button_alignment',
			'admin_label',
			'module_id',
			'module_class'                        
		);
                $this->fields_defaults = array(
                    'use_pbm'           => array( 'on' ),
                    'background_color'  => array( et_builder_accent_color(), 'add_default_setting' ),
                    'background_layout' => array( 'light' ),
                );
		$this->main_css_element = '%%order_class%%';
		$this->advanced_options = array(
			'button' => array(
				'button' => array(
					'label' => esc_html__( 'Button', 'angelleye_paypal_divi' ),
					'css'   => array(
						'main' => $this->main_css_element,
					),
				),
			),
		);
                $this->custom_css_options = array();
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
                                  'post_type'        => 'paypal_buttons',  
                                  'post_status'      => 'publish',
                                  'posts_per_page' => -1, 
                              );
                          $button_manager_posts_array = get_posts( $button_manager_args );
                          $paypal_button_manager_option_arrray=array();                
                          foreach ($button_manager_posts_array as $value) {
                              $paypal_button_manager_option_arrray[$value->ID]=$value->post_title;
                          }
                    /* end */ 
                    $fields = array(
                         'pbm_list' => array(
                            'label'           => esc_html__( 'PayPal Button', 'angelleye_paypal_divi' ),
                            'type'            => 'select',
                            'option_category' => 'layout',
                            'options'         => $paypal_button_manager_option_arrray,
                            'description'     => esc_html__( 'Choose the button you would like to use in this module.  This list comes from the buttons created in the PayPal WP Button Manager plugin.', 'angelleye_paypal_divi' ),
                        ),
                        'use_custom' => array(
                                'label'           => esc_html__( 'Custom Button Display', 'angelleye_paypal_divi' ),
				'type'            => 'yes_no_button',
				'option_category' => 'basic_option',
				'options'         => array(
                                            'off' => esc_html__( 'No', 'angelleye_paypal_divi' ),
					    'on'  => esc_html__( 'Yes', 'angelleye_paypal_divi' ),					    
				),
                                'affects'         => array(
					'#et_pb_button_text',
                                        '#et_pb_background_layout',
                                        '#et_pb_src',
				),
				'description'     => esc_html__( 'Enable this option to use a text only or custom graphic button in place of the default Buy Now / Donate button.', 'angelleye_paypal_divi' ),
                        ),
                         'button_text' => array(
				'label'           => esc_html__( 'Button Text', 'angelleye_paypal_divi' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Enter a value here to be displayed in a text only button. (If an Image URL is set this text will not be displayed.)', 'angelleye_paypal_divi' ),
			),
                        'background_layout' => array(
                            'label'           => esc_html__( 'Text Color', 'angelleye_paypal_divi' ),
                            'type'            => 'select',
                            'option_category' => 'color_option',
                            'options'         => array(
                                'light'   => esc_html__( 'Dark', 'angelleye_paypal_divi' ),
                                'dark'    => esc_html__( 'Light', 'angelleye_paypal_divi' ),
                            ),
                            'description'     => esc_html__( 'Adjust whether your text only button uses light or dark text. If you are working with a dark background, then your text should be light. If your background is light, then your text should be set to dark.', 'angelleye_paypal_divi' ),
                        ),                        
                        'button_alignment' => array(
				'label'           => esc_html__( 'Button Alignment', 'angelleye_paypal_divi' ),
				'type'            => 'select',
				'option_category' => 'configuration',
				'options'         => array(
					'left'    => esc_html__( 'Left', 'angelleye_paypal_divi' ),
					'center'  => esc_html__( 'Center', 'angelleye_paypal_divi' ),
					'right'   => esc_html__( 'Right', 'angelleye_paypal_divi' ),
				),
				'description'     => esc_html__( 'Adjust the alignment of your button.', 'angelleye_paypal_divi' ),
			),
			'admin_label' => array(
				'label'       => esc_html__( 'Admin Label', 'angelleye_paypal_divi' ),
				'type'        => 'text',
				'description' => esc_html__( 'This will change the label of the module in the builder for easy identification.', 'angelleye_paypal_divi' ),
			),
			'module_id' => array(
				'label'           => esc_html__( 'CSS ID', 'angelleye_paypal_divi' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'tab_slug'        => 'custom_css',
				'option_class'    => 'et_pb_custom_css_regular',
			),
			'module_class' => array(
				'label'           => esc_html__( 'CSS Class', 'angelleye_paypal_divi' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'tab_slug'        => 'custom_css',
				'option_class'    => 'et_pb_custom_css_regular',
			),
                    );
                    return $fields;
             }
             else{                 
                   /*
                    * Below code is to get all the pages of the website to put into the return url and cancle url             
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
                   $all_page = array();
                   foreach ($pages as $p) {
                       $all_page[$p->ID] = $p->post_title;
                   }      
                   /* end */

                   /* Below code get all the companies from database. */
                   global $wpdb;
                   $companies = $wpdb->prefix . 'angelleye_paypal_for_divi_companies'; // do not forget about tables prefix
                   $result_records = $wpdb->get_results("SELECT * FROM `{$companies}` WHERE account_id !=''", ARRAY_A);
                   $all_accounts=array();
                    foreach ($result_records as $result_records_value) {
                        $all_accounts[$result_records_value['account_id']] = $result_records_value['title'].' ('.$result_records_value['account_id'].')';
                    }
                    if(empty($all_accounts)){
                        $all_accounts['noAccount']='Please Add Paypal Account';
                    }
                    /* end */   
                    
                     /*
                     * Currency Array
                     */      
                    
                    $currency_codes = array(
                         'AUD' => 'Austrailian Dollar',
                         'BRL' => 'Brazilian Real',
                         'CAD' => 'Canadian Dollar',
                         'CZK' => 'Czeck Koruna',
                         'DKK' => 'Danish Krone',
                         'EUR' => 'Euro',
                         'HKD' => 'Hong Kong Dollar',
                         'HUF' => 'Hungarian Forint',
                         'ILS' => 'Israeli New Sheqel',
                         'JPY' => 'Japanese Yen',
                         'MYR' => 'Malaysian Ringgit',
                         'MXN' => 'Mexican Peso',
                         'NOK' => 'Norwegian Krone',
                         'NZD' => 'New Zealand Dollar',
                         'PHP' => 'Philippine Peso',
                         'PLN' => 'Polish Zloty',
                         'GBP' => 'Pound Sterling',
                         'SGD' => 'Singapore Dollar',
                         'SEK' => 'Swedish Krona',
                         'CHF' => 'Swiss Franc',
                         'TWD' => 'Taiwan New Dollar',
                         'THB' => 'Thai Baht',
                         'USD' => 'U.S. Dollar',
                         'TRY' => 'Turkish Lira',
                     ); 
                    /* currency end */
                    
                $fields = array(                              
                        'pp_business_name' => array(
                            'label'           => esc_html__( 'PayPal Account ID', 'angelleye_paypal_divi' ),
                            'type'            => 'select',
                            'option_category' => 'layout',
                            'options'         => $all_accounts,
                            'description'     => esc_html__( 'Enter your PayPal account ID or email address to specify where the payment should be sent.', 'angelleye_paypal_divi' ),
                        ),
                        'pp_select_button' => array(
				'label'           => esc_html__( 'Button Type', 'angelleye_paypal_divi' ),
				'type'            => 'select',
				'option_category' => 'layout',
				'options'         => array(
					   'on'   => esc_html__( 'Buy Now', 'angelleye_paypal_divi' ),
					   'off'  => esc_html__( 'Donate', 'angelleye_paypal_divi' ),
				),
                                'affects'         => array(
					'#et_pb_pp_shipping',
                                        '#et_pb_pp_tax',
                                        '#et_pb_pp_handling',
				),
				'description'     => esc_html__( 'Choose between a PayPal *Buy Now* button or *Donate* button.', 'angelleye_paypal_divi' ),
			),			
                        'pp_item_name' => array(
                         	'label'           => esc_html__( 'Item Name', 'angelleye_paypal_divi' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Add a name / description for the item or service being sold.', 'angelleye_paypal_divi' ),
                        ),
                        'pp_amount' => array(
                         	'label'           => esc_html__( 'Item Price', 'angelleye_paypal_divi' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Enter the price for the item / service being sold.', 'angelleye_paypal_divi' ),
                        ),
                       'pp_currency_code'=> array(
                            'label'           => esc_html__( 'Currency', 'angelleye_paypal_divi' ),
                            'type'            => 'select',
                            'option_category' => 'layout',
                            'options'         => $currency_codes,
                            'description'     => esc_html__( 'Select your currency in which payment will be made.', 'angelleye_paypal_divi' ),
                        ),
                        'pp_shipping' => array(
                                'label'           => esc_html__( 'Shipping Amount', 'angelleye_paypal_divi' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Enter the cost of shipping for the item if you would like to override the shipping rules in your PayPal account profile.', 'angelleye_paypal_divi' ),
                        ),
                        'pp_tax'      => array(
                                'label'           => esc_html__( 'Tax Amount', 'angelleye_paypal_divi' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Enter a sales tax amount to be charged if you would like to override the tax rules in your PayPal account profile.', 'angelleye_paypal_divi' ),
                        ),
                        'pp_handling' => array(
                                'label'           => esc_html__( 'Handling Amount', 'angelleye_paypal_divi' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Enter a handling fee if you would like to include one with this item / service.', 'angelleye_paypal_divi' ),
                        ), 
                        'pp_return' => array(
				'label'           => esc_html__( ' Return Url', 'et_builder' ),
				'type'            => 'select',
				'option_category' => 'basic_option',
                                'options' => $all_page,
				'description'     => esc_html__( 'The URL to which PayPal redirects buyers\' browser after they complete their payments.', 'angelleye_paypal_divi' ),
			),
                        'pp_cancel_return' => array(
                                    'label'           => esc_html__( ' Cancel Url', 'et_builder' ),
                                    'type'            => 'select',
                                    'option_category' => 'basic_option',
                                    'options' => $all_page,
                                    'description'     => esc_html__( 'A URL to which PayPal redirects the buyers\' browsers if they cancel checkout before completing their payments.', 'angelleye_paypal_divi' ),
                        ),
                        'use_custom' => array(
                                'label'           => esc_html__( 'Custom Button Display', 'angelleye_paypal_divi' ),
				'type'            => 'yes_no_button',
				'option_category' => 'basic_option',
				'options'         => array(
                                            'off' => esc_html__( 'No', 'angelleye_paypal_divi' ),
					    'on'  => esc_html__( 'Yes', 'angelleye_paypal_divi' ),					    
				),
                                'affects'         => array(
					'#et_pb_button_text',
                                        '#et_pb_background_layout',
                                        '#et_pb_src',
				),
				'description'     => esc_html__( 'Enable this option to use a text only or custom graphic button in place of the default Buy Now / Donate button.', 'angelleye_paypal_divi' ),
                        ),
                        'button_text' => array(
				'label'           => esc_html__( 'Button Text', 'angelleye_paypal_divi' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Enter a value here to be displayed in a text only button. (If an Image URL is set this text will not be displayed.)', 'angelleye_paypal_divi' ),
			),
                        'background_layout' => array(
                            'label'           => esc_html__( 'Text Color', 'angelleye_paypal_divi' ),
                            'type'            => 'select',
                            'option_category' => 'color_option',
                            'options'         => array(
                                'light'   => esc_html__( 'Dark', 'angelleye_paypal_divi' ),
                                'dark'    => esc_html__( 'Light', 'angelleye_paypal_divi' ),
                            ),
                            'description'     => esc_html__( 'Adjust whether your text only button uses light or dark text. If you are working with a dark background, then your text should be light. If your background is light, then your text should be set to dark.', 'angelleye_paypal_divi' ),
                        ),
                        'src' => array(
				'label'              => esc_html__( 'Image URL', 'angelleye_paypal_divi' ),
				'type'               => 'upload',
				'option_category'    => 'basic_option',
				'upload_button_text' => esc_attr__( 'Upload an image', 'angelleye_paypal_divi' ),
				'choose_text'        => esc_attr__( 'Choose an Image', 'angelleye_paypal_divi' ),
				'update_text'        => esc_attr__( 'Set As Image', 'angelleye_paypal_divi' ),
				'description'        => esc_html__( 'Upload your desired image or type in the URL to the image you would like to display.', 'angelleye_paypal_divi' ),
			),
                       'button_alignment' => array(
				'label'           => esc_html__( 'Button Alignment', 'angelleye_paypal_divi' ),
				'type'            => 'select',
				'option_category' => 'configuration',
				'options'         => array(
					'left'    => esc_html__( 'Left', 'angelleye_paypal_divi' ),
					'center'  => esc_html__( 'Center', 'angelleye_paypal_divi' ),
					'right'   => esc_html__( 'Right', 'angelleye_paypal_divi' ),
				),
				'description'     => esc_html__( 'Adjust the alignment of your button.', 'angelleye_paypal_divi' ),
			),
			'admin_label' => array(
				'label'       => esc_html__( 'Admin Label', 'angelleye_paypal_divi' ),
				'type'        => 'text',
				'description' => esc_html__( 'This will change the label of the module in the builder for easy identification.', 'angelleye_paypal_divi' ),
			),
			'module_id' => array(
				'label'           => esc_html__( 'CSS ID', 'angelleye_paypal_divi' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'tab_slug'        => 'custom_css',
				'option_class'    => 'et_pb_custom_css_regular',
			),
			'module_class' => array(
				'label'           => esc_html__( 'CSS Class', 'angelleye_paypal_divi' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'tab_slug'        => 'custom_css',
				'option_class'    => 'et_pb_custom_css_regular',
			),
		);
		return $fields;
             }
	}
        /*
         *  Function shortcode_callback. This method returns the content the module will display
         */
	function shortcode_callback( $atts, $content = null, $function_name ) {
		$module_id         = $this->shortcode_atts['module_id'];
		$module_class      = $this->shortcode_atts['module_class'];		
		$button_text       = $this->shortcode_atts['button_text'];
                $src               = $this->shortcode_atts['src'];
                $pp_item_name      = $this->shortcode_atts['pp_item_name'];
                $pp_amount         = $this->shortcode_atts['pp_amount'];
		$custom_icon       = $this->shortcode_atts['button_icon'];
		$button_custom     = $this->shortcode_atts['custom_button'];
		$button_alignment  = $this->shortcode_atts['button_alignment'];
                $background_layout = $this->shortcode_atts['background_layout'];
                $pp_select_button  = $this->shortcode_atts['pp_select_button'];
                $test_mode         = '';
                $pp_business_name  = $this->shortcode_atts['pp_business_name'];
                $pp_shipping       = $this->shortcode_atts['pp_shipping'];
                $pp_tax            = $this->shortcode_atts['pp_tax'];
                $pp_handling       = $this->shortcode_atts['pp_handling'];
                
                $pp_return         = $this->shortcode_atts['pp_return'];
                $pp_cancel_return  = $this->shortcode_atts['pp_cancel_return'];
                
                $use_custom        = $this->shortcode_atts['use_custom'];
                
                $use_pbm           = $this->shortcode_atts['use_pbm'];
                $pbm_list          = $this->shortcode_atts['pbm_list'];
                
                $pp_currency_code  = $this->shortcode_atts['pp_currency_code'];
                
                $pp_option_shipping ='';
                $pp_option_tax      ='';
                $pp_option_handling ='';
                             
                    
		$module_class = ET_Builder_Element::add_module_order_class( $module_class, $function_name );

		$module_class .= " et_pb_module et_pb_bg_layout_{$background_layout}";  
                
                include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
                if (is_plugin_active('paypal-wp-button-manager/paypal-wp-button-manager.php')) {
                    
                    $paypal_button_manager_post_meta=get_post_meta($pbm_list);
                    $_pbm_form             = $paypal_button_manager_post_meta['paypal_button_response'][0];
                    $_pbm_hosted_button_id = $paypal_button_manager_post_meta['paypal_wp_button_manager_button_id'][0];
                    $_pbm_email_link       = $paypal_button_manager_post_meta['paypal_wp_button_manager_email_link'][0];
                    $output = sprintf(
                            '<div class="et_pb_button_module_wrapper et_pb_module%1$s">                            
                                %2$s
                            </div>',                       
                            'right' === $button_alignment || 'center' === $button_alignment ? sprintf( ' et_pb_button_alignment_%1$s', esc_attr( $button_alignment ) )  : '',
                            '' !== $use_custom && 'on' === $use_custom
                                                   ? sprintf('<a href="%6$s" type="submit" class="et_pb_button%2$s%3$s" %5$s%4$s>%1$s</a>',
                                                     $button_text,
                                                    '' !== $custom_icon && 'on' === $button_custom ? ' et_pb_custom_button_icon' : '',
                                                    ( '' !== $module_class ? sprintf( ' %1$s', esc_attr( $module_class ) ) : '' ),
                                                    ( '' !== $module_id ? sprintf( ' id="%1$s"', esc_attr( $module_id ) ) : '' ),
                                                    '' !== $custom_icon && 'on' === $button_custom ? sprintf(' data-icon="%1$s"',esc_attr( et_pb_process_font_icon( $custom_icon ) )) : '',$_pbm_email_link)
                                                   : $_pbm_form
                    );
                    return $output;
                }
                else{
                        // Nothing to output if Account is not setup
                   if ( 'noAccount' === $pp_business_name) {
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
                       $pp_alt = 'Buy Now With Credit Cards';                    
                       $pp_option_shipping = '' !== trim($pp_shipping) ? '<input type="hidden" name="shipping" value="'.$pp_shipping.'">' : '';
                       $pp_option_tax = '' !== trim($pp_tax) ? '<input type="hidden" name="tax" value="'.$pp_tax.'">' : '';                    
                       $pp_option_handling = '' !== trim($pp_handling) ? '<input type="hidden" name="handling" value="'.$pp_handling.'">' : '';
                   }
                   elseif($pp_select_button =='off'){
                       $cmd    = '_donations';
                       $pp_img = 'https://www.paypalobjects.com/webstatic/en_US/i/btn/png/btn_donate_cc_147x47.png';
                       $pp_alt = 'Donate';
                   }
                
                        $output = sprintf(
                            '<div class="et_pb_button_module_wrapper et_pb_module%6$s">                            
                                <form target="paypal" action="https://www.%11$spaypal.com/cgi-bin/webscr" method="post"> 
                                   <input type="hidden" name="bn" value="AngellEYE_SP_Divi" />
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
                                                   ? sprintf('<button type="submit" class="et_pb_button%2$s%3$s" %5$s%4$s>%1$s</button>',
                                                    $button_text,
                                                    '' !== $custom_icon && 'on' === $button_custom ? ' et_pb_custom_button_icon' : '',
                                                    ( '' !== $module_class ? sprintf( ' %1$s', esc_attr( $module_class ) ) : '' ),
                                                    ( '' !== $module_id ? sprintf( ' id="%1$s"', esc_attr( $module_id ) ) : '' ),
                                                    '' !== $custom_icon && 'on' === $button_custom ? sprintf(
                                                     ' data-icon="%1$s"',
                                                     esc_attr( et_pb_process_font_icon( $custom_icon ) )
                                                     ) : '')
                                                   : sprintf('<input type="image" name="submit" border="0" src="%1$s" alt="%2$s"/><img alt="" border="0" width="1" height="1" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" >',
                                                           '' !==  $src ? $src : $pp_img,$pp_alt
                                                           )                        
                            ,
                            '' !== $pp_return ? sprintf('<input type="hidden" name="return" value="%1$s">',get_permalink($pp_return)) : '',
                            '' !== $pp_cancel_return ? sprintf('<input type="hidden" name="cancel_return" value="%1$s">',get_permalink($pp_cancel_return)) : '',
                            $pp_currency_code
                    );
                    return $output;
                }		
	}
}
        $et_builder_module_paypal_button = new ET_Builder_Module_Paypal_Button();
        add_shortcode('et_pb_paypal_button', array($et_builder_module_paypal_button, '_shortcode_callback'));      
    }
}
add_action('et_builder_ready', 'angelleye_paypal_button_module');