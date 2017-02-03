<?php

function angelleye_paypal_button_module() {

    if (class_exists('ET_Builder_Module')) {
        ob_start(); 
       class ET_Builder_Module_Paypal_Button extends ET_Builder_Module {
        /**
         * Function init() callled while initialization of all Divi modules 
         * Name, slug, and some other settings for the module are initialize here.       
        */   
	function init() {
		$this->name = esc_html__( 'PayPal Button', 'angelleye_paypal_divi' );
		$this->slug = 'et_pb_paypal_button';

		$this->whitelisted_fields = array(
                        'test_mode',
                        'pp_business_name',
                        'pp_select_button',
			'pp_item_name',
			'pp_amount',
                        'pp_shipping',
                        'pp_tax',
                        'pp_handling',
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
                    'test_mode'         => array( 'on' ),
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
		$fields = array(
                        'test_mode' => array(
				'label'           => esc_html__( 'Test Mode', 'angelleye_paypal_divi' ),
				'type'            => 'yes_no_button',
				'option_category' => 'basic_option',
				'options'         => array(
                                            'on'  => esc_html__( 'Yes', 'angelleye_paypal_divi' ),
					    'off' => esc_html__( 'No', 'angelleye_paypal_divi' ),					    
				),
				'description'     => esc_html__( 'Here you can choose weather to use "sandbox" mode or "live" mode.', 'angelleye_paypal_divi' ),
			),
                        'pp_business_name' => array(
                            'label'           => esc_html__( 'Business Name', 'angelleye_paypal_divi' ),
                            'type'            => 'text',
                            'option_category' => 'basic_option',
                            'description'     => esc_html__( 'Identify your business so that you can collect the payments. PayPal account email/ID.', 'angelleye_paypal_divi' ),
                        ),
                        'pp_select_button' => array(
				'label'           => esc_html__( 'Select Button', 'angelleye_paypal_divi' ),
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
				'description'     => esc_html__( 'Here you can choose whether to use PayPal *Buy Now* Button or *Donate* Button.', 'angelleye_paypal_divi' ),                                 
			),			
                        'pp_item_name' => array(
                         	'label'           => esc_html__( 'Item Name', 'angelleye_paypal_divi' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Input your item name here.', 'angelleye_paypal_divi' ),
                        ),
                        'pp_amount' => array(
                         	'label'           => esc_html__( 'Item Amount', 'angelleye_paypal_divi' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Amount to be charged for the item.', 'angelleye_paypal_divi' ),
                        ),
                        'pp_shipping' => array(
                                'label'           => esc_html__( 'Shipping Amount', 'angelleye_paypal_divi' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'The cost of shipping for the item.', 'angelleye_paypal_divi' ),
                        ),
                        'pp_tax'      => array(
                                'label'           => esc_html__( 'Tax Amount', 'angelleye_paypal_divi' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Set this value to a flat tax amount to apply to the payment regardless of the buyer\'s location.', 'angelleye_paypal_divi' ),
                        ),
                        'pp_handling' => array(
                                'label'           => esc_html__( 'Handling Amount', 'angelleye_paypal_divi' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'The same handling cost applies, regardless of the number of items on the order.', 'angelleye_paypal_divi' ),
                        ), 
                        'use_custom' => array(
                                'label'           => esc_html__( 'Use Custom Button', 'angelleye_paypal_divi' ),
				'type'            => 'yes_no_button',
				'option_category' => 'basic_option',
				'options'         => array(
                                            'off' => esc_html__( 'No', 'angelleye_paypal_divi' ),
					    'on'  => esc_html__( 'Yes', 'angelleye_paypal_divi' ),					    
				),
                                'affects'         => array(
					'#et_pb_button_text',
                                        '#et_pb_button_alignment',
                                        '#et_pb_background_layout',
                                        '#et_pb_src',
				),
				'description'     => esc_html__( 'Enable this to use Custom Button.', 'angelleye_paypal_divi' ),
                        ),
                        'button_text' => array(
				'label'           => esc_html__( 'Button Text', 'angelleye_paypal_divi' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Input your desired button text.', 'angelleye_paypal_divi' ),
			),
                        'src' => array(
				'label'              => esc_html__( 'Image URL', 'angelleye_paypal_divi' ),
				'type'               => 'upload',
				'option_category'    => 'basic_option',
				'upload_button_text' => esc_attr__( 'Upload an image', 'angelleye_paypal_divi' ),
				'choose_text'        => esc_attr__( 'Choose an Image', 'angelleye_paypal_divi' ),
				'update_text'        => esc_attr__( 'Set As Image', 'angelleye_paypal_divi' ),
				'description'        => esc_html__( 'Upload your desired image, or type in the URL to the image you would like to display.', 'angelleye_paypal_divi' ),
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
				'description'     => esc_html__( 'Here you can define the alignemnt of Button.', 'angelleye_paypal_divi' ),
			),
			'background_layout' => array(
				'label'           => esc_html__( 'Text Color', 'angelleye_paypal_divi' ),
				'type'            => 'select',
				'option_category' => 'color_option',
				'options'         => array(
					'light'   => esc_html__( 'Dark', 'angelleye_paypal_divi' ),
					'dark'    => esc_html__( 'Light', 'angelleye_paypal_divi' ),
				),
				'description'     => esc_html__( 'Here you can choose whether your text should be light or dark. If you are working with a dark background, then your text should be light. If your background is light, then your text should be set to dark.', 'angelleye_paypal_divi' ),
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
                $test_mode         = $this->shortcode_atts['test_mode'];
                $pp_business_name  = $this->shortcode_atts['pp_business_name'];
                $pp_shipping       = $this->shortcode_atts['pp_shipping'];
                $pp_tax            = $this->shortcode_atts['pp_tax'];
                $pp_handling       = $this->shortcode_atts['pp_handling'];
                $use_custom        = $this->shortcode_atts['use_custom'];
                
                $pp_option_shipping ='';
                $pp_option_tax      ='';
                $pp_option_handling ='';
                
		// Nothing to output if $pp_business_name is blank
		if ( '' === $pp_business_name) {
			return;
		}
                if ( 'off' !== $test_mode ) {
                    $mode = 'sandbox.';
                }
                else{
                    $mode = '';
                }
                if($pp_select_button =='on'){
                    $cmd    = '_xclick';
                    $pp_img = 'https://www.paypalobjects.com/webstatic/en_US/i/btn/png/btn_buynow_107x26.png';
                    $pp_alt = 'Buy Now';
                    $pp_option_shipping = '<input type="hidden" name="shipping" value="'.$pp_shipping.'">';
                    $pp_option_tax = '<input type="hidden" name="tax" value="'.$pp_tax.'">';
                    $pp_option_handling = '<input type="hidden" name="handling" value="'.$pp_handling.'">';
                }
                elseif($pp_select_button =='off'){
                    $cmd    = '_donations';
                    $pp_img = 'https://www.paypalobjects.com/webstatic/en_US/i/btn/png/btn_donate_92x26.png';
                    $pp_alt = 'Donate';
                }
                    
		$module_class = ET_Builder_Element::add_module_order_class( $module_class, $function_name );

		$module_class .= " et_pb_module et_pb_bg_layout_{$background_layout}";       
		$output = sprintf(
			'<div class="et_pb_button_module_wrapper et_pb_module%6$s">                            
                            <form target="paypal" action="https://www.%11$spaypal.com/cgi-bin/webscr" method="post"> 
                               <input type="hidden" name="bn" value="AngellEYE_PHPClass" />
                               <input type="hidden" name="business" value="%12$s">
                               <input type="hidden" name="cmd" value="%1$s">
                               <input type="hidden" name="item_name" value="%7$s">
                               <input type="hidden" name="amount" value="%8$s">
                               <input type="hidden" name="currency_code" value="USD">
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
		);

		return $output;
	}
}
        $et_builder_module_paypal_button = new ET_Builder_Module_Paypal_Button();
        add_shortcode('et_pb_paypal_button', array($et_builder_module_paypal_button, '_shortcode_callback'));
        ob_end_flush();
    }
}
add_action('et_builder_ready', 'angelleye_paypal_button_module');