<?php

namespace KVoucherFrontendStuff;


if ( !class_exists( 'KV_Form' ) ) {
    class KV_Form
    {
        private static function kv_PostToHiddenField()
        {
            foreach ( $_POST as $content => $value ) {
                if ( $content != 'action' ) {
                    echo  ' <input type="hidden" name="' . esc_html( $content ) . '" value="' . esc_html( $value ) . '">' ;
                }
            }
        }
        
        private function kv_Button( $value, $label )
        {
            echo  '<button style="margin:5px;" type="submit" value="' . esc_html( $value ) . '" name="action">' . esc_html( $label ) . '</button>' ;
        }
        
        // end kv_Button($value, $label)
        private function kv_SubStr( $string, $length )
        {
            return substr( $string, 0, $length );
        }
        
        // end substr($string, 0, $length);
        private static function kv_CheckBillingAdressDeliveryShipping()
        {
            if ( kvo_fs()->is_not_paying() ) {
                echo  '<input type="hidden" name="checkbox_del_shipping_adress" value="0" />' ;
            }
        }
        
        private static function kv_CheckTermsOfServiceData()
        {
            $output = true;
            $terms_of_service_data = get_option( 'kvoucher_plugin_terms_of_service_textfields' );
            if ( !is_array( $terms_of_service_data ) && !isset( $terms_of_service_data['terms_of_service'] ) ) {
                $output = false;
            }
            return $output;
        }
        
        // end kv_CheckBillingAdressDeliveryShipping()
        private static function kv_CheckAllCompanyData()
        {
            $output = true;
            $company_data = get_option( 'kvoucher_plugin_company_textfiels' );
            $paypal_data = get_option( 'kvoucher_plugin_paypal_textfiels' );
            $required_data_company = array(
                'company',
                'first_name',
                'last_name',
                'street_name',
                'postal_code',
                'city',
                'phone_number',
                'company_url',
                'value_added_tax',
                'company_email'
            );
            $required_data_paypal = array( 'paypal_client_id' );
            foreach ( $required_data_company as $required ) {
                if ( @$company_data[$required] == '' || @$company_data[$required] == null ) {
                    $output = false;
                }
            }
            foreach ( $required_data_paypal as $required ) {
                if ( @$paypal_data[$required] == '' || @$paypal_data[$required] == null ) {
                    $output = false;
                }
            }
            return $output;
        }
        
        private static function kv_GetCurrencyPaypalHTMLoutput()
        {
            $currency = get_option( 'kvoucher_plugin_company_textfiels' )['currency'];
            switch ( $currency ) {
                case empty($currency) || $currency == null || $currency == '':
                    return "€";
                    break;
                case 'euro':
                    return "€";
                    break;
                case 'dollar':
                    return "\$";
                    break;
                case 'british_pound':
                    return "£";
                    break;
            }
        }
        
        // end kv_CheckAllCompanyData()
        private static function kv_BillingAdressPrice()
        {
            $currency = self::kv_GetCurrencyPaypalHTMLoutput();
            echo  '<style>

                        .radio-toolbar input[type="radio"] { opacity: 0;position: fixed;width: 0; }
                        
                        .radio-toolbar label {display: inline-block; background-color: #d3d7cf; width: fit-content;  width: -moz-fit-content; padding: 2px 5px; margin: 2px; 100px; border-radius: 4px; border: 1px solid #fff; color: #000; }

                        .radio-toolbar label:hover  {background-color: #babdb6; border: 1px solid #fff; }

                        .radio-toolbar input[type="radio"]:checked + label { background-color: #2e3436; padding: 3px; border: 1px solid #fff; color: #fff; }

                        input[type=text], select, textarea{ width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; resize: vertical; }
                       
                      </style>' ;
            // end if ( kvo_fs()->can_use_premium_code__premium_only() )
            if ( kvo_fs()->is_not_paying() ) {
                echo  '<fieldset class="radio-toolbar">
                    
                       <legend>' . __( 'Value', 'kvoucherpro' ) . '</legend>
                           
                       <input class="price" type="radio" id="twenty" name="price" value="20" ' . (( empty($_POST['price']) || $_POST['price'] == '20' ? 'checked' : '' )) . '>
                           
    	               <label for="twenty">20,00 ' . esc_html( $currency ) . '</label>
    	                   
    	               <input class="price" type="radio" id="fifty" name="price" value="50" ' . (( $_POST['price'] == '50' ? 'checked' : '' )) . '>
    	                   
    	               <label for="fifty">50,00 ' . esc_html( $currency ) . '</label>
    	                   
    	               <input class="price" type="radio" id="onehundred" name="price" value="100" ' . (( $_POST['price'] == '100' ? 'checked' : '' )) . '>
    	                   
    	               <label for="onehundred">100,00 ' . esc_html( $currency ) . '</label>
    	                   
    	               <input type="hidden" id="post" name="shipping" value="E-mail">
    	                   
	                   </fieldset>' ;
            }
        }
        
        // end kv_BillingAdressPrice()
        private static function kv_BillingAdressAdress()
        {
            echo  '<fieldset>
                
                        <legend>' . __( 'Billing address', 'kvoucherpro' ) . '</legend>
                
                        <label for="privat">' . __( 'Privat', 'kvoucherpro' ) . ':</label><input type="radio" onchange="kv_toggleDisableDelCompany(this)" id="radiobox_company_dis" id="privat" name="kind_of_adress" value="Privat" ' . (( empty($_POST['kind_of_adress']) || $_POST['kind_of_adress'] == 'Privat' ? 'checked' : '' )) . '>
                
                        <label for="firma">' . __( 'Company', 'kvoucherpro' ) . ':</label><input class="kind_of_adress" type="radio" onchange="kv_toggleEnableDelCompany(this)" id="radiobox_company_en" name="kind_of_adress" value="Firma" ' . (( $_POST['kind_of_adress'] == 'Firma' ? 'checked' : '' )) . '><br>
                
		                <label for="herr">' . __( 'Mr', 'kvoucherpro' ) . ':</label><input type="radio" id="herr" name="title" value="Herr" ' . (( empty($_POST['title']) || $_POST['title'] == 'Herr' ? 'checked' : '' )) . '>
                
                        <label for="frau">' . __( 'Mrs', 'kvoucherpro' ) . ':</label><input type="radio" id="frau" name="title" value="Frau" ' . (( $_POST['title'] == 'Frau' ? 'checked' : '' )) . '>

                        <p style=' . (( $_POST['kind_of_adress'] == 'Firma' ? '"display:block"' : '"display:none"' )) . ' id="company_input_field"><label style="width: 140px;" for="company">' . __( 'Company', 'kvoucherpro' ) . ': </label><input type="text" maxlength="40" name="company" id="company" value="' . (( !empty($_POST['company']) ? esc_html( $_POST['company'] ) : '' )) . '"  placeholder="' . __( 'Company', 'kvoucherpro' ) . '" style="max-width: 300px;"></p>
                
                        <p><label style="width: 140px;" for="fname">' . __( 'First Name', 'kvoucherpro' ) . '*: </label><input type="text" maxlength="30" name="fname" id="fname" required="required" value="' . (( !empty($_POST['fname']) ? esc_html( $_POST['fname'] ) : '' )) . '"  placeholder="' . __( 'First Name', 'kvoucherpro' ) . '" style="max-width: 300px;"></p>
                
                        <p><label style="width: 140px;" for="nname">' . __( 'Last Name', 'kvoucherpro' ) . '*: </label><input type="text" maxlength="30" name="nname" id="nname" required="required" value="' . (( !empty($_POST['nname']) ? esc_html( $_POST['nname'] ) : '' )) . '" placeholder="' . __( 'Last Name', 'kvoucherpro' ) . '" style="max-width: 300px;"></p>
                
                        <p><label style="width: 140px;" for="streetname">' . __( 'Street + No.', 'kvoucherpro' ) . '*:</label><input type="text" maxlength="50"  name="streetname" id="streetname" value="' . (( !empty($_POST['streetname']) ? esc_html( $_POST['streetname'] ) : '' )) . '" required="required" placeholder="' . __( 'Street + No.', 'kvoucherpro' ) . '" style="max-width: 300px;"></p>
                
                        <p><label style="width: 140px;" for="plz">' . __( 'Postal-Code', 'kvoucherpro' ) . '*:</label><input type="text" name="plz" id="plz" value="' . (( !empty($_POST['plz']) ? esc_html( $_POST['plz'] ) : '' )) . '" required="required" maxlength="6" placeholder="' . __( 'Postal-Code', 'kvoucherpro' ) . '" style="max-width: 300px;"></p>
                
                        <p><label style="width: 140px;" for="city">' . __( 'City', 'kvoucherpro' ) . '*:</label><input type="text" maxlength="50" name="city"  id="city" value="' . (( !empty($_POST['city']) ? esc_html( $_POST['city'] ) : '' )) . '" required="required" placeholder="' . __( 'City', 'kvoucherpro' ) . '" style="max-width: 300px;"></p>
                
                        <p><label style="width: 140px;" for="country">' . __( 'Country', 'kvoucherpro' ) . '*:</label><input type="text" maxlength="50" name="country" id="country" value="' . (( !empty($_POST['country']) ? esc_html( $_POST['country'] ) : 'Deutschland' )) . '" required="required" value="Deutschland" placeholder="' . __( 'Country', 'kvoucherpro' ) . '" style="max-width: 300px;"></p>
                
                        <p><label style="width: 140px;" for="phone">' . __( 'Phone', 'kvoucherpro' ) . ':</label><input type="tel" maxlength="20" name="phone" id="phone" value="' . (( !empty($_POST['phone']) ? esc_html( $_POST['phone'] ) : '' )) . '" placeholder="' . __( 'Phone', 'kvoucherpro' ) . '" style="max-width: 300px;"></p>
                
                        <p><label style="width: 140px;" for="email">' . __( 'E-mail', 'kvoucherpro' ) . '*:</label><input type="email" maxlength="50" name="email" id="email" value="' . (( !empty($_POST['email']) ? esc_html( $_POST['email'] ) : '' )) . '" required="required" placeholder="' . __( 'E-mail', 'kvoucherpro' ) . '" style="max-width: 300px;"></p>

                        </fieldset>' ;
        }
        
        // end kv_BillingAdressAdress()
        private static function kv_BillingAdressDeliveryShipping()
        {
            echo  '<fieldset id="fieldset_del_shipping_adress"' . (( !empty($_POST['checkbox_del_shipping_adress']) || $_POST['checkbox_del_shipping_adress'] == '1' ? 'style="display:block' : 'disabled="disabled" style="display:none' )) . '">

                      <legend>' . __( 'Differing Shipping Address', 'kvoucherpro' ) . '</legend>

                      <label for="dif_herr">' . __( 'Mr', 'kvoucherpro' ) . ':</label><input type="radio" id="dif_herr" name="dif_title" value="Herr" ' . (( empty($_POST['dif_title']) || $_POST['dif_title'] == 'Herr' ? 'checked' : '' )) . '>
                
                      <label for="dif_frau">' . __( 'Mrs', 'kvoucherpro' ) . ':</label><input type="radio" id="dif_frau" name="dif_title" value="Frau" ' . (( $_POST['dif_title'] == 'Frau' ? 'checked' : '' )) . '>

                      <p><label style="width: 140px;" for="dif_fname">' . __( 'First Name', 'kvoucherpro' ) . '*: </label><input type="text" name="dif_fname" id="dif_fname" value ="' . (( !empty($_POST['dif_fname']) ? esc_html( $_POST['dif_fname'] ) : '' )) . '" required="required"  placeholder="' . __( 'First Name', 'kvoucherpro' ) . '" style="max-width: 300px;"></p>

                      <p><label style="width: 140px;" for="dif_nname">' . __( 'Last Name', 'kvoucherpro' ) . '*: </label><input type="text" name="dif_nname" id="dif_nname" value ="' . (( !empty($_POST['dif_nname']) ? esc_html( $_POST['dif_nname'] ) : '' )) . '" required="required"  placeholder="' . __( 'Last Name', 'kvoucherpro' ) . '" style="max-width: 300px;"></p>

                      <p><label style="width: 140px;" for="dif_streetname">' . __( 'Street + No.', 'kvoucherpro' ) . '*:</label><input type="text"  name="dif_streetname" id="dif_streetname" value ="' . (( !empty($_POST['dif_nname']) ? esc_html( $_POST['dif_nname'] ) : '' )) . '" placeholder="' . __( 'Street + No.', 'kvoucherpro' ) . '" style="max-width: 300px;"></p>

                      <p><label style="width: 140px;" for="dif_plz">' . __( 'Postal-Code', 'kvoucherpro' ) . '*:</label><input type="text" name="dif_plz" id="dif_plz" value ="' . (( !empty($_POST['dif_plz']) ? esc_html( $_POST['dif_plz'] ) : '' )) . '" required="required" maxlength="6" placeholder="' . __( 'Postal-Code', 'kvoucherpro' ) . '" style="max-width: 300px;"></p>

                      <p><label style="width: 140px;" for="dif_city">' . __( 'City', 'kvoucherpro' ) . '*:</label><input type="text" name="dif_city"  id="dif_city" value ="' . (( !empty($_POST['dif_city']) ? esc_html( $_POST['dif_city'] ) : '' )) . '" required="required" placeholder="' . __( 'City', 'kvoucherpro' ) . '" style="max-width: 300px;"></p>

                      <p><label style="width: 140px;" for="dif_country">' . __( 'Country', 'kvoucherpro' ) . '*:</label><input type="text" name="dif_country" id="dif_country" value ="' . (( !empty($_POST['dif_country']) ? esc_html( $_POST['dif_country'] ) : 'Deutschland' )) . '" required="required" value="Deutschland" placeholder="' . __( 'Country', 'kvoucherpro' ) . '" style="max-width: 300px;"></p>

                      <p><label style="width: 140px;" for="dif_email">' . __( 'E-mail', 'kvoucherpro' ) . '*:</label><input type="email" name="dif_email" id="dif_email" value ="' . (( !empty($_POST['dif_email']) ? esc_html( $_POST['dif_email'] ) : '' )) . '" required="required" placeholder="' . __( 'E-mail', 'kvoucherpro' ) . '" style="max-width: 300px;"></p>

                      </fieldset>' ;
        }
        
        // end kv_BillingAdressDeliveryShipping()
        private static function kv_PayCoupon()
        {
            $currency = self::kv_GetCurrencyPaypalHTMLoutput();
            $wert = floatval( esc_html( $_POST['price'] ) );
            if ( $_POST['shipping'] == 'Post' ) {
                $shipping_costs = floatval( str_replace( ',', '.', sanitize_text_field( $_POST['shipping_costs'] ) ) );
            }
            if ( $_POST['shipping'] == 'E-mail' ) {
                $_POST['shipping_costs'] = 0.0;
            }
            if ( $_POST['kind_of_adress'] == 'Privat' ) {
                unset( $_POST['company'] );
            }
            $summe = $wert + $shipping_costs;
            echo  '<fieldset>

                        <legend>' . __( 'Voucher for', 'kvoucherpro' ) . '</legend>

                       <p><i>' . __( 'Dates appear on the voucher', 'kvoucherpro' ) . '</i></p>

                        ' . __( 'First Name', 'kvoucherpro' ) . ': ' . esc_html( $_POST['for_fname'] ) . '<br>

                        ' . __( 'Last Name', 'kvoucherpro' ) . ': ' . esc_html( $_POST['for_nname'] ) . '<br>
            
                        ' . __( 'Occasion', 'kvoucherpro' ) . ': ' . esc_html( $_POST['occasion'] ) . '<br>
            
                        ' . __( 'Value', 'kvoucherpro' ) . ': ' . number_format( esc_html(
                $_POST['price'],
                2,
                ',',
                '.'
            ) ) . ' ' . esc_html( $currency ) . '</fieldset>' ;
            echo  '<fieldset>

                     <legend>' . __( 'Billing Adress', 'kvoucherpro' ) . '</legend>

                        ' . (( !empty($_POST['company']) ? __( 'Company', 'kvoucherpro' ) . ': ' . esc_html( $_POST['company'] ) . '<br>' : '' )) . '

                        ' . __( 'First Name', 'kvoucherpro' ) . ': ' . esc_html( $_POST['fname'] ) . '<br>

                        ' . __( 'Last Name', 'kvoucherpro' ) . ': ' . esc_html( $_POST['nname'] ) . '<br>

                        ' . __( 'Street', 'kvoucherpro' ) . ': ' . esc_html( $_POST['streetname'] ) . '<br>

                        ' . __( 'City', 'kvoucherpro' ) . ': ' . esc_html( $_POST['plz'] ) . ' ' . esc_html( $_POST['city'] ) . '<br>

                        ' . __( 'Country', 'kvoucherpro' ) . ': ' . esc_html( $_POST['country'] ) . '<br>' . (( !empty($_POST['phone']) ? __( 'Phone', 'kvoucherpro' ) . ': ' . esc_html( $_POST['phone'] ) . '<br>' : '' )) . '

                        ' . __( 'E-mail', 'kvoucherpro' ) . ': ' . esc_html( $_POST['email'] ) . '<br>
            
                        ' . __( 'Shipping costs', 'kvoucherpro' ) . ': ' . number_format(
                esc_html( $_POST['shipping_costs'] ),
                2,
                ',',
                '.'
            ) . ' ' . esc_html( $currency ) . '<br>
            
                        ' . __( 'Total', 'kvoucherpro' ) . ': ' . number_format(
                esc_html( $summe ),
                2,
                ',',
                '.'
            ) . ' ' . esc_html( $currency ) . '<br>
            
                        ' . __( 'Shipping', 'kvoucherpro' ) . ': ' . esc_html( $_POST['shipping'] ) . '<br></fieldset>' ;
            echo  '<fieldset>
                        
                        <legend>' . __( 'Shipping Adress', 'kvoucherpro' ) . '</legend>' . (( $_POST['checkbox_del_shipping_adress'] == '1' ? __( 'First name', 'kvoucherpro' ) . ': ' . esc_html( $_POST['dif_fname'] ) . '<br>' : __( 'First name', 'kvoucherpro' ) . ': ' . esc_html( $_POST['fname'] ) . '<br>' )) . (( $_POST['checkbox_del_shipping_adress'] == '1' ? __( 'Last name', 'kvoucherpro' ) . ': ' . esc_html( $_POST['dif_nname'] ) . '<br>' : __( 'Last name', 'kvoucherpro' ) . ': ' . esc_html( $_POST['nname'] ) . '<br>' )) . (( $_POST['checkbox_del_shipping_adress'] == '1' ? __( 'Street', 'kvoucherpro' ) . ': ' . esc_html( $_POST['dif_streetname'] ) . '<br>' : __( 'Street', 'kvoucherpro' ) . ': ' . esc_html( $_POST['streetname'] ) . '<br>' )) . (( $_POST['checkbox_del_shipping_adress'] == '1' ? __( 'City', 'kvoucherpro' ) . ': ' . esc_html( $_POST['dif_plz'] ) . ' ' . esc_html( $_POST['dif_city'] ) . '<br>' : __( 'City', 'kvoucherpro' ) . ': ' . esc_html( $_POST['plz'] ) . ' ' . esc_html( $_POST['city'] ) . '<br>' )) . (( $_POST['checkbox_del_shipping_adress'] == '1' ? __( 'Country', 'kvoucherpro' ) . ': ' . esc_html( $_POST['dif_country'] ) . '<br>' : __( 'Country', 'kvoucherpro' ) . ': ' . esc_html( $_POST['country'] ) . '<br>' )) . (( $_POST['checkbox_del_shipping_adress'] == '1' ? __( 'E-mail', 'kvoucherpro' ) . ': ' . esc_html( $_POST['dif_email'] ) . '<br>' : __( 'E-mail', 'kvoucherpro' ) . ': ' . esc_html( $_POST['email'] ) . '<br>' )) . __( 'Shipping', 'kvoucherpro' ) . ': ' . esc_html( $_POST['shipping'] ) . '</fieldset>' ;
        }
        
        // end kv_PayCoupon()
        private static function kv_TermsOfService()
        {
            $terms_of_service_data = get_option( 'kvoucher_plugin_terms_of_service_textfields' );
            
            if ( is_array( $terms_of_service_data ) && isset( $terms_of_service_data['terms_of_service'] ) ) {
                $terms = get_option( 'kvoucher_plugin_terms_of_service_textfields' )['terms_of_service'];
            } else {
                $terms = '';
            }
            
            echo  '<p><label for="checkbox_terms_of_sevice">' . __( 'Please confirm our', 'kvoucherpro' ) . ' ' . (( self::kv_CheckTermsOfServiceData() == true ? '<a onclick="kv_openToc();">' . __( 'terms and conditions', 'kvoucherpro' ) . '</a>' : 'AGB´s' )) . '</label>
                       <input type="checkbox" required="required" name="checkbox_terms_of_sevice" value="1" ' . (( !empty($_POST['checkbox_terms_of_sevice']) || $_POST['checkbox_terms_of_sevice'] == '1' ? 'checked' : '' )) . ' id="checkbox_terms_of_sevice"></p>' ;
            echo  '<div id="toc">

                            <div style="background-color: #ffffff; color: #000000;" id="toc_content">' . esc_html( $terms ) . '</div>

                            <div style="background-color: #ffffff" id="toc_close"><a style="color: DodgerBlue;" onclick="kv_closeToc();">' . __( 'close', 'kvoucherpro' ) . '</a></div>
            
                        </div>' ;
        }
        
        private static function kv_ThxForBuy()
        {
            echo  '<div id="thanks_message">

                               <p style="text-align: center; background-color: #ffffff; color: #000000;">' . __( 'Thank you for shopping at', 'kvoucherpro' ) . ' ' . esc_html( get_bloginfo( 'name' ) ) . '</p>

	                           <p style="text-align: center">

		                      <a style="color: DodgerBlue;" href="' . esc_html( $_SERVER['REQUEST_URI'] ) . '">' . __( 'Back to selection', 'kvoucherpro' ) . '</a>

                      </div>' ;
        }
        
        // end kv_TermsOfService()
        private function kv_CouponFor()
        {
            echo  '<fieldset>
                        
                       <legend>' . __( 'Voucher for', 'kvoucherpro' ) . '</legend>

                       <p><i>' . __( 'Dates appear on the voucher', 'kvoucherpro' ) . '</i></p>

                       <label for="for_herr">' . __( 'Mr', 'kvoucherpro' ) . ':</label><input type="radio" id="for_herr" name="for_title" value="Herr" ' . (( empty($_POST['for_title']) || $_POST['for_title'] == 'Herr' ? 'checked' : '' )) . '>
                
                        <label for="for_frau">' . __( 'Mrs', 'kvoucherpro' ) . ':</label><input type="radio" id="for_frau" name="for_title" value="Frau" ' . (( $_POST['for_title'] == 'Frau' ? 'checked' : '' )) . '>
                
                        <p><label style="width: 140px;" for="for_fname">' . __( 'First Name', 'kvoucherpro' ) . '*: </label><input type="text" maxlength="30" name="for_fname" id="for_fname" required="required" value="' . (( !empty($_POST['for_fname']) ? $_POST['for_fname'] : '' )) . '"  placeholder="' . __( 'First Name', 'kvoucherpro' ) . '" style="max-width: 300px;"></p>
                
                        <p><label style="width: 140px;" for="for_nname">' . __( 'Last Name', 'kvoucherpro' ) . '*: </label><input type="text" maxlength="30" name="for_nname" id="for_nname" required="required" value="' . (( !empty($_POST['for_nname']) ? $_POST['for_nname'] : '' )) . '" placeholder="' . __( 'Last Name', 'kvoucherpro' ) . '" style="max-width: 300px;"></p>

                        <p><label style="width: 140px;" for="occasion">' . __( 'Occasion', 'kvoucherpro' ) . ': </label><input type="text" maxlength="30" name="occasion" id="occasion" value="' . (( !empty($_POST['occasion']) ? $_POST['occasion'] : '' )) . '" placeholder="' . __( 'Occasion', 'kvoucherpro' ) . '" style="max-width: 300px;"></p>
                
                        </fieldset>' ;
        }
        
        private static function kv_kindOfShipping()
        {
            $currency = self::kv_GetCurrencyPaypalHTMLoutput();
            // load currency
            $show_shipping_costs = get_option( 'kvoucher_plugin_company_textfiels' )['shipping_costs'];
            ( $show_shipping_costs == null || empty($show_shipping_costs) ? $show_shipping_costs = '0,00' : ($show_shipping_costs = $show_shipping_costs) );
            // if shipping selectected in the admin area post+email
            if ( get_option( 'kvoucher_plugin_company_textfiels' )['shipping'] == 'post+email' ) {
                echo  '<fieldset>
                
                       <legend>' . __( 'Shipping', 'kvoucherpro' ) . '</legend>
                
                            <input type="radio" id="post" name="shipping" value="Post" ' . (( empty($_POST['shipping']) || $_POST['shipping'] == 'Post' ? 'checked' : '' )) . '>
                           
    	                    <label for="post">' . __( 'via Post', 'kvoucherpro' ) . ' <i>( + ' . esc_html( $show_shipping_costs ) . ' ' . esc_html( $currency ) . ' ' . __( 'Shipping', 'kvoucherpro' ) . ')</i></label>
                           
    	                    <input type="radio" id="email" name="shipping" value="E-mail" ' . (( $_POST['shipping'] == 'E-mail' ? 'checked' : '' )) . '>
    	                   
    	                    <label for="email">' . __( 'via E-mail (PDF)', 'kvoucherpro' ) . '</label>
            
                       </fieldset>' ;
            }
            // if shipping selectected in the admin area email
            
            if ( get_option( 'kvoucher_plugin_company_textfiels' )['shipping'] == 'email' ) {
                echo  '<fieldset>
                    
                       <legend>' . __( 'Shipping', 'kvoucherpro' ) . '</legend>
                           
                           <p>' . __( 'The dispatch takes place via E-mail (PDF)', 'kvoucherpro' ) . '</p>
                               
                       </fieldset>' ;
                echo  '<input type="hidden" id="email" name ="shipping" value="E-mail">' ;
                echo  '<input type="hidden" id="shipping_costs" name ="shipping_costs" value="0.00">' ;
            }
            
            // if shipping selectected in the admin area post
            
            if ( get_option( 'kvoucher_plugin_company_textfiels' )['shipping'] == 'post' ) {
                echo  '<fieldset>
                    
                       <legend>' . __( 'Shipping', 'kvoucherpro' ) . '</legend>
                           
                           <p>' . __( 'The dispatch takes place by Post', 'kvoucherpro' ) . '</p>
                               
                       </fieldset>' ;
                echo  '<input type="hidden" id="post" name ="shipping" value="Post">' ;
                echo  '<input type="hidden" id="shipping_costs" name ="shipping_costs" value="' . esc_html( get_option( 'kvoucher_plugin_company_textfiels' )['shipping_costs'] ) . '">' ;
            }
        
        }
        
        private static function kv_GetCurrencyforPaypalApi()
        {
            $currency = get_option( 'kvoucher_plugin_company_textfiels' )['currency'];
            switch ( $currency ) {
                case empty($currency) || $currency == null || $currency == '':
                    return "EUR";
                    break;
                case 'euro':
                    return "EUR";
                    break;
                case 'dollar':
                    return "USD";
                    break;
                case 'british_pound':
                    return "GBP";
                    break;
            }
        }
        
        private static function kv_getPostData()
        {
            $data = array(
                'price'                        => sanitize_text_field( $_POST['price'] ),
                'shipping'                     => sanitize_text_field( $_POST['shipping'] ),
                'shipping_costs'               => str_replace( ',', '.', sanitize_text_field( $_POST['shipping_costs'] ) ),
                'kind_of_adress'               => sanitize_text_field( $_POST['kind_of_adress'] ),
                'for_title'                    => sanitize_text_field( $_POST['for_title'] ),
                'for_fname'                    => self::kv_SubStr( sanitize_text_field( $_POST['for_fname'] ), 30 ),
                'for_nname'                    => self::kv_SubStr( sanitize_text_field( $_POST['for_nname'] ), 30 ),
                'occasion'                     => self::kv_SubStr( sanitize_text_field( $_POST['occasion'] ), 30 ),
                'title'                        => sanitize_text_field( $_POST['title'] ),
                'fname'                        => self::kv_SubStr( sanitize_text_field( $_POST['fname'] ), 30 ),
                'nname'                        => self::kv_SubStr( sanitize_text_field( $_POST['nname'] ), 30 ),
                'company'                      => self::kv_SubStr( sanitize_text_field( $_POST['company'] ), 40 ),
                'streetname'                   => self::kv_SubStr( sanitize_text_field( $_POST['streetname'] ), 50 ),
                'plz'                          => self::kv_SubStr( sanitize_text_field( $_POST['plz'] ), 8 ),
                'city'                         => self::kv_SubStr( sanitize_text_field( $_POST['city'] ), 50 ),
                'country'                      => self::kv_SubStr( sanitize_text_field( $_POST['country'] ), 50 ),
                'phone'                        => self::kv_SubStr( sanitize_text_field( $_POST['phone'] ), 20 ),
                'email'                        => self::kv_SubStr( sanitize_email( $_POST['email'] ), 50 ),
                'dif_title'                    => esc_attr( $_POST['dif_title'] ),
                'dif_fname'                    => self::kv_SubStr( sanitize_text_field( $_POST['dif_fname'] ), 30 ),
                'dif_nname'                    => self::kv_SubStr( sanitize_text_field( $_POST['dif_nname'] ), 30 ),
                'dif_streetname'               => self::kv_SubStr( sanitize_text_field( $_POST['dif_streetname'] ), 50 ),
                'dif_plz'                      => self::kv_SubStr( sanitize_text_field( $_POST['dif_plz'] ), 8 ),
                'dif_city'                     => self::kv_SubStr( sanitize_text_field( $_POST['dif_city'] ), 50 ),
                'dif_country'                  => self::kv_SubStr( sanitize_text_field( $_POST['dif_country'] ), 50 ),
                'dif_email'                    => self::kv_SubStr( sanitize_email( $_POST['dif_email'] ), 50 ),
                'checkbox_del_shipping_adress' => sanitize_text_field( $_POST['checkbox_del_shipping_adress'] ),
                'action'                       => sanitize_text_field( $_POST['action'] ),
            );
            return base64_encode( http_build_query( $data ) );
        }
        
        // end kv_kindOfShipping()
        private static function kv_paypalButtons()
        {
            $data = self::kv_getPostData();
            $shipping_costs = floatval( str_replace( ',', '.', esc_html( $_POST['shipping_costs'] ) ) );
            $value = floatval( esc_html( $_POST['price'] ) );
            $summe = $shipping_costs + $value;
            echo  '<fieldset id="paypal-button-container"></fieldset>' ;
            echo  '<script>
                     paypal.Buttons({
                         createOrder: function(data, actions) {
                             // This function sets up the details of the transaction, including the amount and line item details.
                             return actions.order.create({
                                 purchase_units: [{
                                     amount: {
                                         value: "' . esc_js( $summe ) . '"
                                     }
                                 }]
                             });
                         },
                         onApprove: function(data, actions) {
                             // This function captures the funds from the transaction.
                             return actions.order.capture().then(function(details) {
                                 // This function shows a transaction success message to your buyer.
                                             
                                 var data = "' . esc_js( $data ) . '";
                                     
                                 kv_saveUserData(data);

                                 kv_openThxMsg();
                                     
                             });
                         }
                     }).render("#paypal-button-container");
                     //This function displays Smart Payment Buttons on your web page.
                     </script>' ;
        }
        
        // end kv_paypalButtons()
        public static function kv_BillingAdress()
        {
            echo  '<div id="kvoucherBillingAdress">' ;
            echo  '<noscript style="color:red;">' . __( 'Please enable javascript in your browser. Otherwise it is not possible to buy a voucher.', 'kvoucherpro' ) . '</noscript>' ;
            $check_required_data = self::kv_CheckAllCompanyData();
            // check all required company data
            
            if ( $check_required_data == true ) {
                
                if ( $_POST['action'] == 'save1' || $_POST['action'] == 'back1' || empty($_POST['action']) || !isset( $_POST['action'] ) ) {
                    echo  '<form action="" method="post">' ;
                    self::kv_PostToHiddenField();
                    self::kv_BillingAdressPrice();
                    self::kv_kindOfShipping();
                    self::kv_CouponFor();
                    echo  '<fieldset>' ;
                    self::kv_Button( 'save2', __( 'Next', 'kvoucherpro' ) );
                    echo  '</fieldset>' ;
                    echo  '</form>' ;
                }
                
                
                if ( $_POST['action'] == 'save2' || $_POST['action'] == 'back2' ) {
                    
                    if ( $_POST['action'] == 'save2' ) {
                        
                        if ( get_option( 'kvoucher_plugin_company_textfiels' )['shipping'] == 'post' || $_POST['shipping'] == 'Post' ) {
                            $shipping_costs = get_option( 'kvoucher_plugin_company_textfiels' )['shipping_costs'];
                            ( $shipping_costs == null || empty($shipping_costs) ? $_POST['shipping_costs'] = '0.00' : ($_POST['shipping_costs'] = $shipping_costs) );
                        }
                        
                        if ( get_option( 'kvoucher_plugin_company_textfiels' )['shipping'] == 'email' ) {
                            $_POST['shipping_costs'] = '0.00';
                        }
                    }
                    
                    echo  '<form action="" method="post">' ;
                    self::kv_PostToHiddenField();
                    self::kv_BillingAdressAdress();
                    self::kv_CheckBillingAdressDeliveryShipping();
                    self::kv_TermsOfService();
                    echo  '<fieldset>' ;
                    echo  '<button style="margin:5px" onclick="kv_submitForms(1)">' . __( 'Back', 'kvoucherpro' ) . '</button>' ;
                    self::kv_Button( 'save3', __( 'Next', 'kvoucherpro' ) );
                    echo  '</fieldset>' ;
                    echo  '</form>' ;
                    echo  '<form action="" method="post" id="1">' ;
                    self::kv_PostToHiddenField();
                    echo  '<input type="hidden" name="action" value="back1">' ;
                    echo  '</form>' ;
                }
                
                if ( $_POST['action'] == 'save3' || $_POST['action'] == 'back3' ) {
                    
                    if ( $_POST['action'] == 'save3' ) {
                        self::kv_PayCoupon();
                        // show all date
                        echo  '<form action="" method="post">' ;
                        self::kv_PostToHiddenField();
                        echo  '<fieldset>' ;
                        self::kv_Button( 'back2', __( 'Back', 'kvoucherpro' ) );
                        // show button back
                        echo  '</fieldset>' ;
                        echo  '</form>' ;
                        self::kv_ThxForBuy();
                        self::kv_paypalButtons();
                        // show paypal buttons
                    }
                
                }
            } else {
                echo  '<p>' . __( 'Unfortunately, our online voucher service is currently not available. Please accept our apologies', 'kvoucherpro' ) . '.</p>' ;
            }
            
            echo  '</div>' ;
            // return $output;
        }
    
    }
    // end class KV_Form
}

// end if class_exists('KV_Form')