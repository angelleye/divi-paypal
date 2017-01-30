<?php

function ex_divi_child_theme_setup1() {

    if (class_exists('ET_Builder_Module')) {

       class ET_Builder_Module_Paypal_Button extends ET_Builder_Module {
	function init() {
		$this->name = esc_html__( 'Paypal_Button', 'et_builder' );
		$this->slug = 'et_pb_paypal_button';

		$this->whitelisted_fields = array(
                        'pp_select_button',
			//'button_text',
			'pp_item_name',
			'pp_amount',
			'admin_label',
			'module_id',
			'module_class'
		);
                
		$this->main_css_element = '%%order_class%%';
		$this->advanced_options = array(
			'button' => array(
				'button' => array(
					'label' => esc_html__( 'Button', 'et_builder' ),
					'css' => array(
						'main' => $this->main_css_element,
					),
				),
			),
		);
                $this->custom_css_options = array();
	}

	function get_fields() {
		$fields = array(
                        'pp_select_button' => array(
				'label'           => esc_html__( 'Select Button', 'et_builder' ),
				'type'            => 'select',
				'option_category' => 'basic_option',
				'options'         => array(
					'buynow'  => esc_html__( 'Buy now', 'et_builder' ),
					'donate'  => esc_html__( 'Donate', 'et_builder' ),
				),
				'description' => esc_html__( 'Here you can choose whether to use PayPal *Buy Now* Button or *Donate* Button ', 'et_builder' ),
			),
//			'button_text' => array(
//				'label'           => esc_html__( 'Button Text', 'et_builder' ),
//				'type'            => 'text',
//				'option_category' => 'basic_option',
//				'description'     => esc_html__( 'Input your desired button text.', 'et_builder' ),
//			),
                        'pp_item_name' => array(
                         	'label'           => esc_html__( 'Paypal Item name', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Input your Paypal Item name here.', 'et_builder' ),                            
                        ),
                        'pp_amount' => array(
                         	'label'           => esc_html__( 'Paypal Amount', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Input your amount here.', 'et_builder' ),                            
                        ),
			'admin_label' => array(
				'label'       => esc_html__( 'Admin Label', 'et_builder' ),
				'type'        => 'text',
				'description' => esc_html__( 'This will change the label of the module in the builder for easy identification.', 'et_builder' ),
			),
			'module_id' => array(
				'label'           => esc_html__( 'CSS ID', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'tab_slug'        => 'custom_css',
				'option_class'    => 'et_pb_custom_css_regular',
			),
			'module_class' => array(
				'label'           => esc_html__( 'CSS Class', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'tab_slug'        => 'custom_css',
				'option_class'    => 'et_pb_custom_css_regular',
			),
		);
		return $fields;
	}

	function shortcode_callback( $atts, $content = null, $function_name ) {
		$module_id         = $this->shortcode_atts['module_id'];
		$module_class      = $this->shortcode_atts['module_class'];		
		//$button_text       = $this->shortcode_atts['button_text'];	
                $pp_item_name      = $this->shortcode_atts['pp_item_name'];
                $pp_amount         = $this->shortcode_atts['pp_amount'];
		$custom_icon       = $this->shortcode_atts['button_icon'];
		$button_custom     = $this->shortcode_atts['custom_button'];
		$button_alignment  = $this->shortcode_atts['button_alignment'];
                $pp_select_button  = $this->shortcode_atts['pp_select_button'];

		// Nothing to output if neither Button Text defined
		//if ( '' === $button_text) {
		//	return;
		//}
                if($pp_select_button =='buynow'){
                    $cmd    = '_xclick';
                    $pp_img = 'https://www.paypalobjects.com/webstatic/en_US/i/btn/png/btn_buynow_107x26.png'; 
                    $pp_alt = 'Buy Now';
                }
                elseif($pp_select_button =='donate'){
                    $cmd    = '_donations';
                    $pp_img = 'https://www.paypalobjects.com/webstatic/en_US/i/btn/png/btn_donate_92x26.png';
                    $pp_alt = 'Donate';
                }
                    
		$module_class = ET_Builder_Element::add_module_order_class( $module_class, $function_name );

		$module_class .= " et_pb_module et_pb_bg_layout_{$background_layout}";
                //<button type="submit" class="et_pb_button%3$s%5$s" %3$s%4$s>%1$s</button>       
		$output = sprintf(
			'<div class="et_pb_button_module_wrapper et_pb_module%6$s">
                            <form target="paypal" action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post">                                
                               <input type="hidden" name="business" value="tejasm-merchant@itpathsolutions.co.in">
                               <input type="hidden" name="cmd" value="%1$s">
                               <input type="hidden" name="item_name" value="%7$s">
                               <input type="hidden" name="amount" value="%8$s">
                               <input type="hidden" name="currency_code" value="USD">
                               <input type="image" name="submit" border="0" src="%9$s" alt="%10$s"/>
                               <img alt="" border="0" width="1" height="1" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" >                               
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
                        $pp_alt
		);

		return $output;
	}
}
        $et_builder_module_paypal_button = new ET_Builder_Module_Paypal_Button();
        add_shortcode('et_pb_paypal_button', array($et_builder_module_paypal_button, '_shortcode_callback'));
    }
}

add_action('et_builder_ready', 'ex_divi_child_theme_setup1');

/*
 * Shortcode
 * 

 * 
 */
