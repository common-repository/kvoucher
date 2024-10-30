<?php

namespace KVoucherSaveUsrData;

if ( !class_exists( 'SaveUsrData' ) ) {
    class KV_SaveData
    {
        function kv_decodeData( $data )
        {
            
            if ( base64_encode( base64_decode( $data, true ) ) === $data ) {
                $data = base64_decode( $data );
            } else {
                exit( 'data is NOT valid' );
            }
            
            parse_str( $data, $arr );
            return $arr;
        }
        
        function kv_saveData( $data )
        {
            $arr = self::kv_decodeData( $data );
            global  $wpdb ;
            
            if ( $arr['action'] == 'save3' ) {
                // emailadress wp-admin
                $email_admin = get_option( 'admin_email' );
                
                if ( kvo_fs()->is_not_paying() ) {
                    $last_id = get_option( 'kvoucher_coupon_id' );
                    $id = $last_id + 1;
                    update_option( 'kvoucher_coupon_id', $id );
                }
                
                if ( isset( $arr['price'] ) ) {
                    $price = sanitize_text_field( $arr['price'] );
                }
                if ( isset( $arr['shipping'] ) ) {
                    $shipping = sanitize_text_field( $arr['shipping'] );
                }
                if ( isset( $arr['shipping_costs'] ) ) {
                    $shipping_costs = sanitize_text_field( $arr['shipping_costs'] );
                }
                if ( isset( $arr['kind_of_adress'] ) ) {
                    $kind_of_adress = sanitize_text_field( $arr['kind_of_adress'] );
                }
                if ( isset( $arr['occasion'] ) ) {
                    $occasion = sanitize_text_field( $arr['occasion'] );
                }
                if ( isset( $arr['for_title'] ) ) {
                    $for_title = sanitize_text_field( $arr['for_title'] );
                }
                if ( isset( $arr['for_fname'] ) ) {
                    $for_fname = sanitize_text_field( $arr['for_fname'] );
                }
                if ( isset( $arr['for_nname'] ) ) {
                    $for_nname = sanitize_text_field( $arr['for_nname'] );
                }
                if ( isset( $arr['title'] ) ) {
                    $title = sanitize_text_field( $arr['title'] );
                }
                if ( isset( $arr['fname'] ) ) {
                    $fname = sanitize_text_field( $arr['fname'] );
                }
                if ( isset( $arr['nname'] ) ) {
                    $nname = sanitize_text_field( $arr['nname'] );
                }
                if ( isset( $arr['company'] ) ) {
                    $company = sanitize_text_field( $arr['company'] );
                }
                if ( isset( $arr['streetname'] ) ) {
                    $streetname = sanitize_text_field( $arr['streetname'] );
                }
                if ( isset( $arr['plz'] ) ) {
                    $plz = sanitize_text_field( $arr['plz'] );
                }
                if ( isset( $arr['city'] ) ) {
                    $city = sanitize_text_field( $arr['city'] );
                }
                if ( isset( $arr['country'] ) ) {
                    $country = sanitize_text_field( $arr['country'] );
                }
                if ( isset( $arr['phone'] ) ) {
                    $phone = sanitize_text_field( $arr['phone'] );
                }
                if ( isset( $arr['email'] ) ) {
                    $email = sanitize_text_field( $arr['email'] );
                }
                if ( isset( $arr['checkbox_del_shipping_adress'] ) ) {
                    $checkbox_del_shipping_adress = sanitize_text_field( $arr['checkbox_del_shipping_adress'] );
                }
                if ( isset( $arr['dif_title'] ) ) {
                    $dif_title = sanitize_text_field( $arr['dif_title'] );
                }
                if ( isset( $arr['dif_fname'] ) ) {
                    $dif_fname = sanitize_text_field( $arr['dif_fname'] );
                }
                if ( isset( $arr['dif_nname'] ) ) {
                    $dif_nname = sanitize_text_field( $arr['dif_nname'] );
                }
                if ( isset( $arr['dif_streetname'] ) ) {
                    $dif_streetname = sanitize_text_field( $arr['dif_streetname'] );
                }
                if ( isset( $arr['dif_plz'] ) ) {
                    $dif_plz = sanitize_text_field( $arr['dif_plz'] );
                }
                if ( isset( $arr['dif_city'] ) ) {
                    $dif_city = sanitize_text_field( $arr['dif_city'] );
                }
                if ( isset( $arr['dif_country'] ) ) {
                    $dif_country = sanitize_text_field( $arr['dif_country'] );
                }
                if ( isset( $arr['dif_email'] ) ) {
                    $dif_email = sanitize_text_field( $arr['dif_email'] );
                }
                $key_kvoucher = md5( $id + $arr['fname'] + $arr['nname'] + date( "His" ) );
                $date = date( "Y-m-d H:i:s" );
                $validity = get_option( 'kvoucher_plugin_company_textfiels' )['validity'];
                $currency = get_option( 'kvoucher_plugin_company_textfiels' )['currency'];
                $vat = get_option( 'kvoucher_plugin_company_textfiels' )['value_added_tax'];
                $futuredate = date( 'd-m-Y', strtotime( '+' . $validity . ' year' ) );
                // ##################################################
                $date = date( 'd-m-Y' );
                $coupon_data = array();
                $coupon_data['coupon_data']['id'] = $id;
                $coupon_data['coupon_data']['url'] = home_url();
                $coupon_data['coupon_data']['lang'] = get_locale();
                $coupon_data['company_data'] = get_option( 'kvoucher_plugin_company_textfiels' );
                $coupon_data['company_data']['email_admin'] = $email_admin;
                $coupon_data['style_data'] = get_option( 'kvoucher_plugin_style_textfiels' );
                
                if ( kvo_fs()->is_not_paying() ) {
                    $coupon_data['style_data']['background_color'] = '#ffffff';
                    $coupon_data['style_data']['font_color'] = '#000000';
                }
                
                $coupon_data['buyer_data']['title'] = $title;
                $coupon_data['buyer_data']['fname'] = $fname;
                $coupon_data['buyer_data']['nname'] = $nname;
                $coupon_data['buyer_data']['company'] = $company;
                $coupon_data['buyer_data']['for_title'] = $for_title;
                $coupon_data['buyer_data']['for_fname'] = $for_fname;
                $coupon_data['buyer_data']['for_nname'] = $for_nname;
                $coupon_data['buyer_data']['price'] = $price;
                $coupon_data['buyer_data']['shipping'] = $shipping;
                $coupon_data['buyer_data']['shipping_costs'] = $shipping_costs;
                $coupon_data['buyer_data']['kind_of_adress'] = $kind_of_adress;
                $coupon_data['buyer_data']['occasion'] = $occasion;
                $coupon_data['buyer_data']['streetname'] = $streetname;
                $coupon_data['buyer_data']['plz'] = $plz;
                $coupon_data['buyer_data']['city'] = $city;
                $coupon_data['buyer_data']['country'] = $country;
                $coupon_data['buyer_data']['phone'] = $phone;
                $coupon_data['buyer_data']['email'] = $email;
                $coupon_data['buyer_data']['checkbox_del_shipping_adress'] = $checkbox_del_shipping_adress;
                $coupon_data['buyer_data']['dif_title'] = $dif_title;
                $coupon_data['buyer_data']['dif_fname'] = $dif_fname;
                $coupon_data['buyer_data']['dif_nname'] = $dif_nname;
                $coupon_data['buyer_data']['dif_streetname'] = $dif_streetname;
                $coupon_data['buyer_data']['dif_plz'] = $dif_plz;
                $coupon_data['buyer_data']['dif_city'] = $dif_city;
                $coupon_data['buyer_data']['dif_country'] = $dif_country;
                $coupon_data['buyer_data']['dif_email'] = $dif_email;
                $coupon_data['buyer_data']['date'] = $date;
                $coupon_data['buyer_data']['futuredate'] = $futuredate;
                return $coupon_data;
            }
        
        }
    
    }
}