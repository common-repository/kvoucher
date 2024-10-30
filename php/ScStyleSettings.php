<?php

function kv_initialize_style_options()
{
    // If the social options don't exist, create them.
    if ( false == get_option( 'kvoucher_plugin_style_textfiels' ) ) {
        add_option( 'kvoucher_plugin_style_textfiels' );
    }
    // end if
    add_settings_section(
        'style_settings_section',
        // ID used to identify this section and with which to register options
        __( 'Style Options', 'kvoucherpro' ),
        // Title to be displayed on the administration page
        'kv_sandbox_style_options_callback',
        // Callback used to render the description of the section
        'kvoucher_plugin_style_textfiels'
    );
    add_settings_section(
        'style_preview_button_section',
        __( 'Previews', 'kvoucherpro' ),
        'kv_sandbox_style_button_preview_callback',
        'kvoucher_plugin_style_textfiels'
    );
    add_settings_field(
        'font_color',
        __( 'Font-Color', 'kvoucherpro' ),
        'kv_textfield_style_font_color_callback',
        'kvoucher_plugin_style_textfiels',
        'style_settings_section'
    );
    add_settings_field(
        'background_color',
        __( 'Background-Color', 'kvoucherpro' ),
        'kv_textfield_style_background_color_callback',
        'kvoucher_plugin_style_textfiels',
        'style_settings_section'
    );
    add_settings_field(
        'logo',
        'Logo',
        'kv_textfield_style_logo_callback',
        'kvoucher_plugin_style_textfiels',
        'style_settings_section'
    );
    register_setting( 'kvoucher_plugin_style_textfiels', 'kvoucher_plugin_style_textfiels', 'kv_plugin_sanitize_style_options' );
}

// end kvoucher_initialize_style_options
add_action( 'admin_init', 'kv_initialize_style_options' );
// callback section
function kv_sandbox_style_options_callback()
{
    echo  '<p>' . __( 'Provide your Custom-Style', 'kvoucherpro' ) . '</p>' ;
}

function kv_sandbox_style_button_preview_callback()
{
    $data['coupon_data']['lang'] = get_locale();
    $data['company_data']['company'] = @get_option( 'kvoucher_plugin_company_textfiels' )['company'];
    $data['company_data']['currency'] = @get_option( 'kvoucher_plugin_company_textfiels' )['currency'];
    $data['company_data']['street_name'] = @get_option( 'kvoucher_plugin_company_textfiels' )['street_name'];
    $data['company_data']['postal_code'] = @get_option( 'kvoucher_plugin_company_textfiels' )['postal_code'];
    $data['company_data']['city'] = @get_option( 'kvoucher_plugin_company_textfiels' )['city'];
    $data['company_data']['phone_number'] = @get_option( 'kvoucher_plugin_company_textfiels' )['phone_number'];
    $data['company_data']['company_url'] = @get_option( 'kvoucher_plugin_company_textfiels' )['company_url'];
    $data['company_data']['company_email'] = @get_option( 'kvoucher_plugin_company_textfiels' )['company_email'];
    $data['company_data']['tax_number'] = @get_option( 'kvoucher_plugin_company_textfiels' )['tax_number'];
    $data['company_data']['value_added_tax'] = @get_option( 'kvoucher_plugin_company_textfiels' )['value_added_tax'];
    $data['style_data'] = get_option( 'kvoucher_plugin_style_textfiels' );
    $arrquery = @kv_encrytURLVarables( $data );
    echo  '<a style="margin: 0px 5px 0px 0px;" href="https://couponsystem.koboldsoft.com/preview.php?preview=coupon&' . http_build_query( $arrquery ) . '" target="_blank" title="' . __( 'Preview Coupon', 'kvoucherpro' ) . '">' . __( 'Preview Coupon', 'kvoucherpro' ) . '</a>' ;
    echo  '<a style="margin: 0px 5px 0px 5px;" href="https://couponsystem.koboldsoft.com/preview.php?preview=bill&' . http_build_query( $arrquery ) . '" target="_blank" title="' . __( 'Preview Bill', 'kvoucherpro' ) . '">' . __( 'Preview Bill', 'kvoucherpro' ) . '</a>' ;
}

function kv_textfield_style_background_color_callback()
{
    
    if ( empty(get_option( 'kvoucher_plugin_style_textfiels' )['background_color']) ) {
        $background_color = '#ffffff';
    } else {
        $background_color = sanitize_text_field( get_option( 'kvoucher_plugin_style_textfiels' )['background_color'] );
    }
    
    if ( kvo_fs()->is_not_paying() ) {
        // Render the output
        echo  '<input type="color" value="#ffffff" id="style_background_color" disabled/><i style="color:grey;">' . __( '(available in KVoucher Premium)', 'kvoucherpro' ) . '</i><a href="' . kvo_fs()->get_upgrade_url() . '">' . __( ' Upgrade Now!', 'kvoucherpro' ) . '</a>' ;
    }
}

function kv_textfield_style_font_color_callback()
{
    
    if ( empty(get_option( 'kvoucher_plugin_style_textfiels' )['font_color']) ) {
        $font_color = '#000000';
    } else {
        $font_color = get_option( 'kvoucher_plugin_style_textfiels' )['font_color'];
    }
    
    if ( kvo_fs()->is_not_paying() ) {
        echo  '<input type="color" id="style_font_color" disabled/><i style="color:grey;">' . __( '(available in KVoucher Premium)', 'kvoucherpro' ) . '</i><a href="' . kvo_fs()->get_upgrade_url() . '">' . __( ' Upgrade Now!', 'kvoucherpro' ) . '</a>' ;
    }
}

function kv_textfield_style_logo_callback()
{
    
    if ( empty(get_option( 'kvoucher_plugin_style_textfiels' )['logo']) ) {
        $logo = '';
    } else {
        $logo = get_option( 'kvoucher_plugin_style_textfiels' )['logo'];
    }
    
    echo  '<input id="style_logo" type="text" size="36" name="kvoucher_plugin_style_textfiels[logo]" value="' . esc_attr( $logo ) . '" />' ;
    echo  '<input id="upload_image_button" class="button button-primary" class="button" type="button" value="' . __( 'Upload Image', 'kvoucherpro' ) . '" />' ;
}

function kv_plugin_sanitize_style_options( $input )
{
    if ( isset( $input['background_color'] ) ) {
        $new_input['background_color'] = sanitize_text_field( $input['background_color'] );
    }
    if ( isset( $input['font_color'] ) ) {
        $new_input['font_color'] = sanitize_text_field( $input['font_color'] );
    }
    if ( isset( $input['logo'] ) ) {
        $new_input['logo'] = esc_url_raw( strip_tags( stripslashes( $input['logo'] ) ) );
    }
    return $new_input;
}
