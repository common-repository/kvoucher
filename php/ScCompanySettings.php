<?php

function kv_initialize_company_options()
{
    if (false == get_option('kvoucher_plugin_company_textfiels')) {
        add_option('kvoucher_plugin_company_textfiels');
    } // end if

    // First, we register a section. This is necessary since all future options must belong to a
    add_settings_section('company_settings_section', __( 'Company Options', 'kvoucherpro'),'kv_general_company_callback','kvoucher_plugin_company_textfiels');

    add_settings_field('company', __( 'Company', 'kvoucherpro').'*', 'kv_textfield_company_callback', 'kvoucher_plugin_company_textfiels', 'company_settings_section');

    add_settings_field('first_name',__( 'First Name', 'kvoucherpro').'*','kv_textfield_first_name_callback','kvoucher_plugin_company_textfiels','company_settings_section');

    add_settings_field('last_name',__( 'Last Name', 'kvoucherpro').'*', 'kv_textfield_last_name_callback', 'kvoucher_plugin_company_textfiels', 'company_settings_section');

    add_settings_field('street_name',__( 'No. /Streetname', 'kvoucherpro').'*', 'kv_textfield_street_name_callback', 'kvoucher_plugin_company_textfiels', 'company_settings_section');

    add_settings_field('postal_code',__( 'Postal-Code', 'kvoucherpro').'*', 'kv_textfield_postal_code_callback', 'kvoucher_plugin_company_textfiels', 'company_settings_section');

    add_settings_field('city', __( 'City', 'kvoucherpro').'*', 'kv_textfield_city_callback', 'kvoucher_plugin_company_textfiels', 'company_settings_section');
    
    add_settings_field('country', __( 'Country', 'kvoucherpro').'*', 'kv_textfield_country_callback', 'kvoucher_plugin_company_textfiels', 'company_settings_section');

    add_settings_field('tax_number', __( 'Tax number', 'kvoucherpro'), 'kv_textfield_tax_number_callback', 'kvoucher_plugin_company_textfiels', 'company_settings_section');

    add_settings_field('phone_number', __( 'Phonenumber', 'kvoucherpro').'*', 'kv_textfield_phone_number_callback', 'kvoucher_plugin_company_textfiels', 'company_settings_section');

    add_settings_field('company_url', __( 'Company Domain', 'kvoucherpro').'*', 'kv_textfield_company_url_callback', 'kvoucher_plugin_company_textfiels', 'company_settings_section');

    add_settings_field('company_email',__( 'Company E-mail', 'kvoucherpro').'*', 'kv_textfield_company_email_callback', 'kvoucher_plugin_company_textfiels', 'company_settings_section');
    
    add_settings_field('validity', __( 'Validity', 'kvoucherpro'), 'kv_textfield_validity_callback', 'kvoucher_plugin_company_textfiels', 'company_settings_section');
    
    add_settings_field('currency', __( 'Currency', 'kvoucherpro'), 'kv_textfield_currency_callback', 'kvoucher_plugin_company_textfiels', 'company_settings_section');
    
    add_settings_field('shipping', __( 'Shipping', 'kvoucherpro').'*', 'kv_textfield_company_shipping_callback', 'kvoucher_plugin_company_textfiels', 'company_settings_section');
    
    add_settings_field('shipping_costs',__( 'Shipping costs by post', 'kvoucherpro'), 'kv_textfield_shipping_costs_callback', 'kvoucher_plugin_company_textfiels', 'company_settings_section');

    add_settings_field('value_added_tax',__( 'Value added tax in percent', 'kvoucherpro').'*', 'kv_textfield_value_added_tax_callback', 'kvoucher_plugin_company_textfiels', 'company_settings_section');

    // Finally, we register the fields with WordPress

    register_setting('kvoucher_plugin_company_textfiels', 'kvoucher_plugin_company_textfiels', 'kv_plugin_sanitize_company_options');
} // end kv_initialize_company_options
add_action('admin_init', 'kv_initialize_company_options');

/*
 * ------------------------------------------------------------------------ *
 * Section Callbacks
 * ------------------------------------------------------------------------
 */
function kv_general_company_callback()
{
    _e( 'Register your company data.', 'kvoucherpro');
    
}

// end kv_general_company_callback

/*
 * ------------------------------------------------------------------------ *
 * Field Callbacks
 * ------------------------------------------------------------------------
 */
function kv_textfield_company_callback()
{
    
    if (empty(get_option('kvoucher_plugin_company_textfiels')['company'])) {
        $company = '';
        $msg = sanitize_text_field( __( 'required !', 'kvoucherpro') );
        
    } else {
        $company = sanitize_text_field( get_option('kvoucher_plugin_company_textfiels')['company'] );
        $msg = '';
    }
    ;

    echo '<input type="text" id="show_company" name="kvoucher_plugin_company_textfiels[company]" value="' . esc_attr($company) . '" required/><small style="color:red">'.esc_html( $msg ).'<small/>';
}

function kv_textfield_shipping_costs_callback()
{
    
    if (empty(get_option('kvoucher_plugin_company_textfiels')['shipping_costs'])) {
        
        $shipping_costs = '';
        
    } else {
        
        $shipping_costs = sanitize_text_field( get_option('kvoucher_plugin_company_textfiels')['shipping_costs'] );
        
    }
    
    echo '<input type="text" id="shipping_costs" maxlength="8" size="8" name="kvoucher_plugin_company_textfiels[shipping_costs]" value="' . esc_attr( $shipping_costs ) . '"/> <b id="curr_shipping">'.esc_attr( kv_getCurrency() ).'</b>';
    

}

// end kv_textfield_company_callback
function kv_textfield_first_name_callback($args)
{
    if (empty(get_option('kvoucher_plugin_company_textfiels')['first_name'])) {
        $first_name = '';
        $msg = sanitize_text_field( __( 'required !', 'kvoucherpro') );
    } else {
        $first_name = sanitize_text_field( get_option('kvoucher_plugin_company_textfiels')['first_name'] );
    }
    ;

    echo '<input type="text" id="show_firstname" name="kvoucher_plugin_company_textfiels[first_name]" value="' . esc_attr($first_name) . '" required/><small style="color:red">'.esc_html( $msg ).'<small/>';


}

// end kv_textfield_first_name_callback
function kv_textfield_last_name_callback()
{
    if (empty(get_option('kvoucher_plugin_company_textfiels')['last_name'])) {
        $last_name = '';
        $msg = sanitize_text_field( __( 'required !', 'kvoucherpro') );
    } else {
        $last_name = sanitize_text_field( get_option('kvoucher_plugin_company_textfiels')['last_name'] );
        $msg = '';
    }
    ;

    echo '<input type="text" id="last_name" name="kvoucher_plugin_company_textfiels[last_name]" value="' . esc_attr($last_name) . '" required/><small style="color:red">'.esc_html( $msg ).'<small/>';

}

// end kv_textfield_first_name_callback
function kv_textfield_street_name_callback()
{
    if (empty(get_option('kvoucher_plugin_company_textfiels')['street_name'])) {
        $street_name = '';
        $msg = sanitize_text_field( __( 'required !', 'kvoucherpro') );
    } else {
        $street_name = sanitize_text_field( get_option('kvoucher_plugin_company_textfiels')['street_name'] );
        $msg = '';
    }
    ;

    echo '<input type="text" id="street_name" name="kvoucher_plugin_company_textfiels[street_name]" value="' . esc_attr($street_name) . '" required/><small style="color:red">'.esc_html( $msg ).'<small/>';

}

// end kv_textfield_street_name_callback

//
function kv_textfield_postal_code_callback()
{
    if (empty(get_option('kvoucher_plugin_company_textfiels')['postal_code'])) {
        $postal_code = '';
        $msg = sanitize_text_field( __( 'required !', 'kvoucherpro') );
    } else {
        $postal_code = sanitize_text_field( get_option('kvoucher_plugin_company_textfiels')['postal_code'] );
        $msg = '';
    }
    ;

    echo '<input type="text" id="postal_code" name="kvoucher_plugin_company_textfiels[postal_code]" value="' . esc_attr($postal_code) . '" required/><small style="color:red">'.esc_html( $msg ).'<small/>';


}

// end kvoucher_textfield_post_code_callback
function kv_textfield_city_callback()
{
    if (empty(get_option('kvoucher_plugin_company_textfiels')['city'])) {
        $city = '';
        $msg = sanitize_text_field( __( 'required !', 'kvoucherpro') );
    } else {
        $city = sanitize_text_field( get_option('kvoucher_plugin_company_textfiels')['city'] );
        $msg = '';
    }
    ;

    echo '<input type="text" id="postal_code" name="kvoucher_plugin_company_textfiels[city]" value="' . esc_attr( $city ) . '" required/><small style="color:red">'.esc_html( $msg ).'<small/>';


}

function kv_textfield_country_callback()
{
    if (empty(get_option('kvoucher_plugin_company_textfiels')['country'])) {
        $country = '';
        $msg = sanitize_text_field( __( 'required !', 'kvoucherpro') );
    } else {
        $country =  sanitize_text_field( get_option('kvoucher_plugin_company_textfiels')['country'] );
        $msg = '';
    }
    ;
    
    echo '<input type="text" id="country" placeholder="'.__( 'your country', 'kvoucherpro').'" name="kvoucher_plugin_company_textfiels[country]" value="' . esc_attr( $country ) . '" required/><small style="color:red">'.esc_html( $msg ).'<small/>';
;
}

// end kv_textfield_city_callback
function kv_textfield_tax_number_callback($args)
{
    if (empty(get_option('kvoucher_plugin_company_textfiels')['tax_number'])) {
        $tax_number = '';
        
    } else {
        $tax_number = sanitize_text_field( get_option('kvoucher_plugin_company_textfiels')['tax_number'] );
        
    }

    echo'<input type="text" id="postal_code" name="kvoucher_plugin_company_textfiels[tax_number]" value="' . esc_attr( $tax_number ) . '"/>';

}

// end kv_textfield_tax_number_callback
function kv_textfield_phone_number_callback()
{
    if (empty(get_option('kvoucher_plugin_company_textfiels')['phone_number'])) {
        $phone_number = '';
        $msg = sanitize_text_field( __( 'required !', 'kvoucherpro') );
    } else {
        $phone_number = sanitize_text_field( get_option('kvoucher_plugin_company_textfiels')['phone_number'] );
        $msg = '';
    }
    ;

    echo '<input type="text" id="postal_code" name="kvoucher_plugin_company_textfiels[phone_number]" value="' . esc_attr( $phone_number ) . '" required/><small style="color:red">'.esc_html( $msg ).'<small/>';

}

// end kv_textfield_phone_number_callback
function kv_textfield_company_url_callback()
{
    if (empty(get_option('kvoucher_plugin_company_textfiels')['company_url'])) {
        $company_url = '';
        $msg = sanitize_text_field( __( 'required !', 'kvoucherpro') );
    } else {
        $company_url = sanitize_text_field( get_option('kvoucher_plugin_company_textfiels')['company_url'] );
        $msg = '';
    }
    ;

    echo '<input type="text" id="company_url" placeholder="example.com" name="kvoucher_plugin_company_textfiels[company_url]" value="' . esc_attr($company_url) . '" required/><small style="color:red">'.esc_html( $msg ).'<small/>';

}

// end kvoucher_textfield_companx_url_callback
function kv_textfield_company_email_callback()
{
    if (empty(get_option('kvoucher_plugin_company_textfiels')['company_email'])) {
        $company_email = '';
        $msg = sanitize_text_field( __( 'required !', 'kvoucherpro') );
    } else {
        $company_email = sanitize_text_field( get_option('kvoucher_plugin_company_textfiels')['company_email'] );
        $msg = '';
    }
    ;

    echo '<input type="email" id="company_email" placeholder="example@example.com" name="kvoucher_plugin_company_textfiels[company_email]" value="' . esc_attr($company_email) . '" required/><small style="color:red">'.esc_html( $msg ).'<small/>';

}

// end kv_textfield_company_email_callback

function kv_textfield_company_shipping_callback(){
    
    if (empty(get_option('kvoucher_plugin_company_textfiels')['shipping'])) {
        
        $kind_of_shipping = 'post+email';
        
    } else {
        
        $kind_of_shipping = get_option('kvoucher_plugin_company_textfiels')['shipping'];
        
    }

    
    echo '<p><input type="radio" id="post+email" name="kvoucher_plugin_company_textfiels[shipping]" value="post+email" '.($kind_of_shipping == 'post+email' ? "checked" : "").'><label for="post+email">Post + E-mail</label></p>
             <p><input type="radio" id="post" name="kvoucher_plugin_company_textfiels[shipping]" value="post" '.($kind_of_shipping == 'post' ? "checked" : "").'><label for="male">Post</label></p>
             <p><input type="radio" id="email" name="kvoucher_plugin_company_textfiels[shipping]" value="email" '.($kind_of_shipping == 'email' ? "checked" : "").'><label for="email">E-mail</label></p>';
    

    
}

function kv_textfield_currency_callback(){
    
    if (empty(get_option('kvoucher_plugin_company_textfiels')['currency'])) {
        
        $currency = 'euro';
        
    } else {
        
        $currency = get_option('kvoucher_plugin_company_textfiels')['currency'];
        
    }
    
    
    echo '<select name="kvoucher_plugin_company_textfiels[currency]" id="currency" onchange="setCurrencyShippingCost()">
                <option value="euro" '.($currency == 'euro' ? "selected" : "").'>€</option>
                <option value="dollar" '.($currency == 'dollar' ? "selected" : "").'>$</option>
                <option value="british_pound" '.($currency == 'british_pound' ? "selected" : "").'>£</option>
            </select>';

    
}

function kv_textfield_validity_callback(){
    
    if (empty(get_option('kvoucher_plugin_company_textfiels')['validity'])) {
        
        $validity = '3';
        
    } else {
        
        $validity = get_option('kvoucher_plugin_company_textfiels')['validity'];
        
    }
    
    
    echo '<select name="kvoucher_plugin_company_textfiels[validity]" id="validity">
                <option value="1" '.($validity == '1' ? "selected" : "").'>1</option>
                <option value="2" '.($validity == '2' ? "selected" : "").'>2</option>
                <option value="3" '.($validity == '3' ? "selected" : "").'>3</option>
                <option value="4" '.($validity == '4' ? "selected" : "").'>4</option>
                <option value="5" '.($validity == '5' ? "selected" : "").'>5</option>
            </select> '.__('Year(s)','kvoucherpro');

    
}


function kv_textfield_value_added_tax_callback()
{
    if (empty(get_option('kvoucher_plugin_company_textfiels')['value_added_tax'])) {
        
        $value_added_tax = '0';
        
    } else {
        
        $value_added_tax = get_option('kvoucher_plugin_company_textfiels')['value_added_tax'];
    }

    echo '<input type="number" step=0.1 id="value_added_tax" min="0" max="100" maxlength="2" size="2" name="kvoucher_plugin_company_textfiels[value_added_tax]" value="' . esc_attr($value_added_tax) . '" required/>%';

    
}

function kv_plugin_sanitize_company_options($input)
{
    $new_input = array();
    if (isset($input['company']))
        $new_input['company'] = sanitize_text_field($input['company']);

    if (isset($input['first_name']))
        $new_input['first_name'] = sanitize_text_field($input['first_name']);

    if (isset($input['last_name']))
        $new_input['last_name'] = sanitize_text_field($input['last_name']);

    if (isset($input['street_name']))
        $new_input['street_name'] = sanitize_text_field($input['street_name']);

    if (isset($input['postal_code']))
        $new_input['postal_code'] = sanitize_text_field($input['postal_code']);

    if (isset($input['city']))
        $new_input['city'] = sanitize_text_field($input['city']);
    
    if (isset($input['country']))
        $new_input['country'] = sanitize_text_field($input['country']);
    
    if (isset($input['tax_number']))
        $new_input['tax_number'] = sanitize_text_field($input['tax_number']);

    if (isset($input['phone_number']))
        $new_input['phone_number'] = sanitize_text_field($input['phone_number']);

    if (isset($input['company_url']))
        $new_input['company_url'] = strip_tags(stripslashes($input['company_url']));

    if (isset($input['company_email']))
        $new_input['company_email'] = sanitize_email(strip_tags(stripslashes($input['company_email'])));

    if (isset($input['value_added_tax']))
        
        $new_input['value_added_tax'] =  kv_PregMatchValueaddedTax(sanitize_text_field($input['value_added_tax']));
    
    if (isset($input['shipping']))
            
        $new_input['shipping'] =  sanitize_text_field($input['shipping']);
    
    if (isset($input['currency']))
            
        $new_input['currency'] =  sanitize_text_field($input['currency']);
    
        if (isset($input['validity']))
            
            $new_input['validity'] =  sanitize_text_field($input['validity']);
    
    if (isset($input['shipping_costs']))
            
        $new_input['shipping_costs'] =  kv_PregMatchShippingCosts($input['shipping_costs']);

    return $new_input;
}

function kv_PregMatchShippingCosts($shipping_costs){
    
    $shipping_costs = str_replace('.', ',', $shipping_costs);
    
    if(preg_match('/^[0-9]*\,[0-9]{0,2}$/', $shipping_costs)){
        
        return $shipping_costs;
        
    }
    
    if(preg_match('/^[0-9]*$/', $shipping_costs)){
        
        return $shipping_costs;
        
    }
    
}

function kv_PregMatchValueaddedTax($valueaddedtax){
    
    $valueaddedtax = str_replace('.', ',', $valueaddedtax);
    
    if(preg_match('/^[0-9]*\,[0-9]{0,2}$/', $valueaddedtax)){
        
        return $valueaddedtax;
        
    }
    
    if(preg_match('/^[0-9]*$/', $valueaddedtax)){
        
        return $valueaddedtax;
        
    }
    
}

function kv_getCurrency(){
    
    @$currency = sanitize_text_field( get_option('kvoucher_plugin_company_textfiels')['currency'] );
    
    if( empty( $currency ) ){ $output = "€"; };
    
    if( strcmp( $currency, 'euro' ) == 0 ){ $output = " €"; };
    
    if( strcmp( $currency, 'dollar' ) == 0 ){ $output = " $"; };
    
    if( strcmp( $currency, 'british_pound' ) == 0 ){ $output = " £"; };
    
    return $output;
    
}
    

