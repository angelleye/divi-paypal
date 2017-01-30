<?php

function ex_divi_child_theme_setup1() {

    if (class_exists('ET_Builder_Module')) {

       class ET_Builder_Module_Paypal_Button extends ET_Builder_Module {
	function init() {
		$this->name = esc_html__( 'Paypal_Button', 'et_builder' );
		$this->slug = 'et_pb_paypal_button';

		$this->whitelisted_fields = array(
			'button_text',
			'pp_item_name',
			'pp_amount',
			'admin_label',
			'module_id',
			'module_class',
                        'text_orientation'
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
			'button_text' => array(
				'label'           => esc_html__( 'Button Text', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Input your desired button text.', 'et_builder' ),
			),
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
		$button_text       = $this->shortcode_atts['button_text'];	
                $pp_item_name      = $this->shortcode_atts['pp_item_name'];
                $pp_amount         = $this->shortcode_atts['pp_amount'];
		$custom_icon       = $this->shortcode_atts['button_icon'];
		$button_custom     = $this->shortcode_atts['custom_button'];
		$button_alignment  = $this->shortcode_atts['button_alignment'];

		// Nothing to output if neither Button Text defined
		if ( '' === $button_text) {
			return;
		}

		$module_class = ET_Builder_Element::add_module_order_class( $module_class, $function_name );

		$module_class .= " et_pb_module et_pb_bg_layout_{$background_layout}";

		$output = sprintf(
			'<div class="et_pb_button_module_wrapper et_pb_module%6$s">
                            <form target="paypal" action="https://www.paypal.com/cgi-bin/webscr" method="post">
                               <input type="hidden" name="business" value="tejasm-merchant@itpathsolutions.co.in">
                               <input type="hidden" name="cmd" value="_xclick">
                               <input type="hidden" name="item_name" value="%7$s">
                               <input type="hidden" name="amount" value="%8$s">
                               <input type="hidden" name="currency_code" value="USD">
                               <button type="submit" class="et_pb_button%3$s%5$s" %3$s%4$s>%1$s</button>       
                            </form>
			</div>',
			'' !== $button_text ? esc_html( $button_text ) : '<input type="image" name="submit" border="0" src="https://www.paypalobjects.com/webstatic/en_US/i/btn/png/btn_buynow_107x26.png" alt="Buy Now"/>', 
                        
			'' !== $custom_icon && 'on' === $button_custom ? sprintf(
				' data-icon="%1$s"',
				esc_attr( et_pb_process_font_icon( $custom_icon ) )
			) : '', 
                        
			'' !== $custom_icon && 'on' === $button_custom ? ' et_pb_custom_button_icon' : '', 
                        
			( '' !== $module_id ? sprintf( ' id="%1$s"', esc_attr( $module_id ) ) : '' ), 
                        
			( '' !== $module_class ? sprintf( ' %1$s', esc_attr( $module_class ) ) : '' ), 
                        
			'right' === $button_alignment || 'center' === $button_alignment ? sprintf( ' et_pb_button_alignment_%1$s', esc_attr( $button_alignment ) )  : '', 
                        
                        $pp_item_name, 
                        $pp_amount 
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
 * [et_pb_paypal_button admin_label=”Paypal_Button” url_new_window=”off” button_text=”Paypal Button” button_alignment=”left” background_layout=”light” custom_button=”on” button_letter_spacing=”0″ button_use_icon=”on” button_icon_placement=”right” button_on_hover=”on” button_letter_spacing_hover=”0″ item_name=”Item” pp_amount=”10.00″ text_orientation=”left” button_icon=”%%92%%” button_text_color=”#0c71c3″ button_bg_color=”#ffffff” button_border_color=”#0c71c3″ button_icon_color=”#0c71c3″] [/et_pb_paypal_button]

 * 
 */
