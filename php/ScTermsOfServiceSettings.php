<?php

function kv_initialize_terms_of_service_options()
{
    if (false == get_option('kvoucher_plugin_terms_of_service_textfields')) {
        add_option('kvoucher_plugin_terms_of_service_textfields');
    } // end if
    add_settings_section('terms_of_service_settings_section', // ID used to identify this section and with which to register options
        __('Terms of Service Options','kvoucherpro'), // Title to be displayed on the administration page
        'kv_sandbox_terms_of_service_callback', // Callback used to render the description of the section
        'kvoucher_plugin_terms_of_service_textfields' // Page on which to add this section of options
        );
    
    add_settings_field('terms_of_service', __('Terms of Service','kvoucherpro'), 'kv_textfield_terms_of_service_callback', 'kvoucher_plugin_terms_of_service_textfields', 'terms_of_service_settings_section');
    
    register_setting('kvoucher_plugin_terms_of_service_textfields', 'kvoucher_plugin_terms_of_service_textfields', 'kv_plugin_sanitize_terms_of_service_options');
} // end kv_initialize_terms_of_service_options
add_action('admin_init', 'kv_initialize_terms_of_service_options');

// callback section
function kv_sandbox_terms_of_service_callback()
{
    echo '<p>'.__('Provide your Terms of Service','kvoucherpro').'</p>';
}

function kv_textfield_terms_of_service_callback()
{
    if (empty(get_option('kvoucher_plugin_terms_of_service_textfields')['terms_of_service'])) {
        $terms_of_service = '';
        $msg = ' <small style="color:red">'.__( 'required', 'kvoucherpro').'!<small/>';
    } else {
        $terms_of_service = get_option('kvoucher_plugin_terms_of_service_textfields')['terms_of_service'];
        $msg = '';
    }
    ;
    
    // Render the output
    echo '<textarea cols="90" rows="20" id="terms_of_service" name="kvoucher_plugin_terms_of_service_textfields[terms_of_service]" placeholder="'.__('your terms of service here','kvoucherpro').'">'.esc_html ($terms_of_service).'</textarea>';
}

function kv_plugin_sanitize_terms_of_service_options($input)
{
    if( isset( $input['terms_of_service'] ) )
        $new_input['terms_of_service'] = htmlentities ($input['terms_of_service']);
        
        return $new_input;
} 