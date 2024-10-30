<?php

function kv_initialize_paypal_options()
{
    
    // If the social options don't exist, create them.
    if (false == get_option('kvoucher_plugin_paypal_textfiels')) {
        add_option('kvoucher_plugin_paypal_textfiels');
    } // end if
    add_settings_section('paypal_settings_section', // ID used to identify this section and with which to register options
        __( 'PayPal Options','kvoucherpro'), // Title to be displayed on the administration page
        'kv_sandbox_paypal_options_callback', // Callback used to render the description of the section
        'kvoucher_plugin_paypal_textfiels' // Page on which to add this section of options
        );
    
    add_settings_field('paypal_client_id', __( 'PayPal Client-ID','kvoucherpro'), 'kv_textfield_paypal_client_id_callback', 'kvoucher_plugin_paypal_textfiels', 'paypal_settings_section');
    
    register_setting('kvoucher_plugin_paypal_textfiels', 'kvoucher_plugin_paypal_textfiels', 'kv_plugin_sanitize_paypal_options');
} // end kv_initialize_paypal_options
add_action('admin_init', 'kv_initialize_paypal_options');

// callback section
function kv_sandbox_paypal_options_callback()
{
    echo '<p>'.__( 'Provide your PayPal Client-ID', 'kvoucherpro').'.</p>';
}

function kv_textfield_paypal_client_id_callback()
{
    if(empty(get_option('kvoucher_plugin_paypal_textfiels')['paypal_client_id'])){
        $paypal_client_id ='';
        $msg = sanitize_text_field( __( 'required !', 'kvoucherpro') );
    }else{
        $paypal_client_id = sanitize_text_field( get_option('kvoucher_plugin_paypal_textfiels')['paypal_client_id'] );
        $msg = '';
    };
    
    // Render the output
    echo '<input type="text" size="80" id="paypal_client_id" name="kvoucher_plugin_paypal_textfiels[paypal_client_id]" placeholder="' . esc_attr( $paypal_client_id ) . '" required /><small style="color:red">'.esc_html( $msg ).'<small/>';
}

function kv_plugin_sanitize_paypal_options($input)
{
    if( isset( $input['paypal_client_id'] ) )
        $new_input['paypal_client_id'] = sanitize_text_field($input['paypal_client_id']);
        
        return $new_input;
} 