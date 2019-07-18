// External Dependencies
import React, { Component } from 'react';
import $ from 'jquery';

// Internal Dependencies
import './style.css';

let mode = '';
let pbm_form = '';
let page_link = '';

class PaypalButton extends Component {

  static slug = 'et_pb_paypal_button';

  _pfd_get_environment_mode(pp_business_name){
    $.ajax({
          url: window.et_fb_options.ajaxurl,
          type: 'POST',
          data: {
            'action' : 'pfd_get_environment',
            'nonce' : window.et_fb_options.et_admin_load_nonce,
            'pp_business_name' : pp_business_name
          },
          async: false,
          dataType : "json",
          success: function (response) {
              mode = response.mode;
          }
      });
      return mode;
  }

  _check_pbm_active(pbm_list){
     $.ajax({
          url: window.et_fb_options.ajaxurl,
          type: 'POST',
          data: {
            'action' : 'check_pbm_active',
            'nonce' : window.et_fb_options.et_admin_load_nonce,
            'pbm_list' : pbm_list
          },
          async: false,
          dataType : "json",
          success: function (response) {
            pbm_form = response.pbm_form;
          }
      });
      return pbm_form;
  }

  _get_page_permalink_by_page_id(page_id) {
    $.ajax({
      url: window.et_fb_options.ajaxurl,
      type: 'POST',
      data: {
        'action': 'ae_get_page_link',
        'nonce': window.et_fb_options.et_admin_load_nonce,
        'page_id': page_id
      },
      async: false,
      dataType: "json",
      success: function (response) {
        page_link = response.page_url;
      }
    });    
    return page_link;
  }

  render() {
        const pp_button = this.props;
        if ( 'noAccount' === pp_button.pp_business_name) {
            return;
        }
        let button_align ='';
        let pbm_active = "";
        button_align = 'et_pb_button_module_wrapper et_pb_module et_pb_button_alignment_'+pp_button.button_alignment;
        if (window.Angelleye_paypal_diviBuilderData.pbm_plugin_active === 'true') {
          pbm_active = this._check_pbm_active(pp_button.pbm_list);
          return (
            <div className={button_align} dangerouslySetInnerHTML={{__html: pbm_active}}>
            </div>
          );
        }
        if(pp_button.pp_business_name === '' || pp_button.pp_business_name === undefined){
          return ( <div class="pfd_alert">
                      <b>PayPal Button:</b> Please select a PayPal Account ID (even if it looks like it is already set.)
                  </div>
                );
        }
        const env_mode = this._pfd_get_environment_mode(pp_button.pp_business_name);
        const utils = window.ET_Builder.API.Utils;
        let cmd,returnElement,cancelElement,pp_option_shipping,pp_option_tax,pp_option_handling,buttonElement = "";
        let customButtonIcon,customModuleClass,customButtonClass,customButtonModuleId,customButtonIconClass ='';
        let pp_img,pp_alt,form_action_url= '';

        if (env_mode === 'sandbox') {
          form_action_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";
        } else {
          form_action_url = "https://www.paypal.com/cgi-bin/webscr";
        }
        if(pp_button.pp_select_button === 'on'){
            cmd = '_xclick';
            pp_option_shipping = ((pp_button.pp_shipping !== undefined && '' !== pp_button.pp_shipping.trim()) ? <input type="hidden" name="shipping" value={pp_button.pp_shipping} /> : '');
            pp_option_tax = (( pp_button.pp_tax !== undefined && '' !== pp_button.pp_tax.trim()) ? <input type="hidden" name="tax" value={pp_button.pp_tax} /> : '');
            pp_option_handling = ((pp_button.pp_handling !== undefined && '' !== pp_button.pp_handling.trim()) ? <input type="hidden" name="handling" value={pp_button.pp_handling} /> : '');
            pp_img = 'https://www.paypalobjects.com/webstatic/en_US/i/btn/png/btn_buynow_cc_171x47.png';
            pp_alt = 'Buy Now With Credit Cards';
        }
        else if (pp_button.pp_select_button === 'off') {
            cmd = '_donations';
            pp_img = 'https://www.paypalobjects.com/webstatic/en_US/i/btn/png/btn_donate_cc_147x47.png';
            pp_alt = 'Donate';
        }
        else{
          return ( <div class="pfd_alert">
                      <b>PayPal Button:</b> Please select a Button Type (even if it looks like it is already set.)
                  </div>
                );
        }
        returnElement = ((pp_button.pp_return !== '') ? <input type="hidden" name="return" value={this._get_page_permalink_by_page_id(pp_button.pp_return)} /> : "");
        cancelElement = (pp_button.pp_cancel_return !== '' ? <input type="hidden" name="cancel_return" value={this._get_page_permalink_by_page_id(pp_button.pp_cancel_return)} /> : "");
        if('' !== pp_button.use_custom && 'on' === pp_button.use_custom && ('' ===  pp_button.src || undefined ===  pp_button.src)){
            customButtonIconClass = ( ('' !== pp_button.button_icon && pp_button.button_icon !== undefined)? ' et_pb_custom_button_icon' : '');
            customButtonIcon = ( ('' !== pp_button.button_icon && pp_button.button_icon !== undefined)? utils.processFontIcon(pp_button.button_icon) : '');
            customModuleClass =( ('' !== pp_button.module_class && pp_button.module_class !== undefined) ? ' '+pp_button.module_class : ' et_pb_module et_pb_bg_layout_light');
            customButtonClass = 'et_pb_button '+customButtonIconClass+customModuleClass;
            customButtonModuleId = ( ('' !== pp_button.module_id && pp_button.module_id !== undefined) ? pp_button.module_id : '' );
            buttonElement = (
                                <button style={{cursor: "pointer"}}
                                    type="submit"
                                    className={customButtonClass}
                                    id={customButtonModuleId}
                                    data-icon={customButtonIcon}>
                                    {pp_button.button_text}
                                </button>
                            );
        }
        else{
            buttonElement = (
                <div>
                <input  style={{cursor: "pointer"}}
                        type="image"
                        name="submit"
                        border="0"
                        src={('' !==  pp_button.src && undefined !==  pp_button.src) ? pp_button.src : pp_img}
                        alt={pp_alt}/>
                <img alt="paypal object" border="0" width="1" height="1" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif"/>
                </div>
            );
        }

    return (
      <div className={button_align}>
        <form target={this.props.open_in_new_tab === 'on' ? "paypal" : "_self"} action={form_action_url} method="post">
            <input type="hidden" name="business" value={pp_button.pp_business_name}/>
            <input type="hidden" name="cmd" value={cmd} />
            <input type="hidden" name="item_name" value={pp_button.pp_item_name} />
            <input type="hidden" name="amount" value={pp_button.pp_amount} />
            <input type="hidden" name="currency_code" value={pp_button.pp_currency_code} />
            {returnElement}
            {cancelElement}
            {pp_option_shipping}
            {pp_option_tax}
            {pp_option_handling}
            {buttonElement}
        </form>
      </div>
    );
  }
}

export default PaypalButton;
