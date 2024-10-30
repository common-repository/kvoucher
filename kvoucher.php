<?php

/*
 * Plugin Name: KVoucher
 * Plugin URI: http://www.koboldsoft.com
 * Description: Voucher plugin for Wordpress Websites
 * Version: 1.1.1
 * Author: KoboldSoft
 * Text Domain: kvoucherpro
 * Domain Path: /languages
 * 
 *  */

if ( !function_exists( 'kvo_fs' ) ) {
    // Create a helper function for easy SDK access.
    function kvo_fs()
    {
        global  $kvo_fs ;
        
        if ( !isset( $kvo_fs ) ) {
            // Include Freemius SDK.
            require_once dirname( __FILE__ ) . '/freemius/start.php';
            $kvo_fs = fs_dynamic_init( array(
                'id'             => '8433',
                'slug'           => 'kvoucher',
                'type'           => 'plugin',
                'public_key'     => 'pk_7e454d0c83f91e766649447851394',
                'is_premium'     => false,
                'premium_suffix' => 'Premium',
                'has_addons'     => false,
                'has_paid_plans' => true,
                'menu'           => array(
                'slug' => 'kvoucher_options',
            ),
                'is_live'        => true,
            ) );
        }
        
        return $kvo_fs;
    }
    
    // Init Freemius.
    kvo_fs();
    // Signal that SDK was initiated.
    do_action( 'kvo_fs_loaded' );
}

defined( 'ABSPATH' ) or die( 'Are you ok?' );
// load FrontendStuff class
define( 'PLUGIN_ROOT_DIR', plugin_dir_path( __FILE__ ) );
include PLUGIN_ROOT_DIR . 'class/KV_FrontStuff.php';
// load KoboldcouponCustomers class
include PLUGIN_ROOT_DIR . 'class/KV_Customers.php';
// load KVoucherSaveUsrData class
include PLUGIN_ROOT_DIR . 'class/KV_SaveUsrData.php';
// load KVoucherSendData class
include PLUGIN_ROOT_DIR . 'class/KV_SendData.php';
// ####################################################################################################################
use  KVoucherFrontendStuff\KV_Form ;
use  KVoucherSaveUsrData\KV_SaveData ;
use  KVoucherSendUsrData\KV_SendData ;
register_activation_hook( __FILE__, 'kv_install' );
function kv_install()
{
    global  $wpdb ;
    $table_name_usr = $wpdb->prefix . "usr_kvoucher";
    $charset_collate = $wpdb->get_charset_collate();
    // create table for template
    // create table usr data
    $sql_usr_kvoucher = "CREATE TABLE {$table_name_usr} (\n        id int(9) NOT NULL AUTO_INCREMENT,\n        price int(20) NOT NULL,\n        shipping varchar(6) NULL,\n        shipping_costs varchar(6) NULL,\n        kind_of_adress varchar(6) NOT NULL,\n        occasion varchar(100) NULL,\n        title varchar(4) NOT NULL,\n        fname varchar(30) NOT NULL,\n        nname varchar(30) NOT NULL,\n        company varchar(40) NULL,\n        for_title varchar(4) NOT NULL,\n        for_fname varchar(30) NOT NULL,\n        for_nname varchar(30) NOT NULL,\n        streetname varchar(50) NOT NULL,\n        plz varchar(8) NOT NULL,\n        city varchar(50) NOT NULL,\n        country varchar(50) NOT NULL,\n        phone varchar(20) NOT NULL,\n        email varchar(50) NOT NULL,\n        dif_title varchar(4) NULL,\n        dif_fname varchar(30) NULL,\n        dif_nname varchar(30) NULL,\n        dif_streetname varchar(50) NULL,\n        dif_plz varchar(8) NULL,\n        dif_city varchar(50) NULL,\n        dif_country varchar(50) NULL,\n        dif_email varchar(50) NULL,\n        key_kvoucher char(32) NOT NULL,\n        date datetime NOT NULL,\n        validity int(1) DEFAULT 3,\n        vat decimal(2, 1) DEFAULT 0,\n        currency varchar(20) DEFAULT 'euro',\n        action int(1) DEFAULT 0,\n        del int(1) DEFAULT 0,\n        PRIMARY KEY  (id)\n        ) {$charset_collate};";
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta( $sql_usr_kvoucher );
    if ( false == get_option( 'kvoucher_coupon_id' ) ) {
        add_option( 'kvoucher_coupon_id', '0' );
    }
}

function kv_oucherpro_load_plugin_textdomain()
{
    load_plugin_textdomain( 'kvoucherpro', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
}

add_action( 'plugins_loaded', 'kv_oucherpro_load_plugin_textdomain' );
function kv_encryptData( $dataToEncrypt )
{
    $cipher = 'aes-256-ofb';
    
    if ( in_array( $cipher, openssl_get_cipher_methods() ) ) {
        $ivlen = openssl_cipher_iv_length( $cipher );
        $iv = openssl_random_pseudo_bytes( $ivlen );
        $data['data'] = openssl_encrypt(
            $dataToEncrypt,
            'aes-256-ofb',
            '~B-$mAf5~jm<Fz!p',
            $options = 0,
            $iv
        );
        $data['iv'] = $iv;
        return $data;
    }

}

function kv_encrytURLVarables( $array )
{
    $data = kv_encryptData( http_build_query( $array ) );
    return $data;
}

function kv_encryptURL( $url )
{
    $data = kv_encryptData( $url );
    return $data;
}

// functions company settings here ##############################################################
function kv_insertSettings()
{
    require_once 'php/ScCompanySettings.php';
    require_once 'php/ScPayPalSettings.php';
    require_once 'php/ScStyleSettings.php';
    require_once 'php/ScTermsOfServiceSettings.php';
}

kv_insertSettings();
add_action( 'wp_head', 'kv_header_scripts' );
function kv_GetCurrencyforPaypalApi()
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

function kv_header_scripts()
{
    echo  sprintf( '<script src="https://www.paypal.com/sdk/js?client-id=%s&currency=' . kv_GetCurrencyforPaypalApi() . '"></script>', sanitize_text_field( get_option( 'kvoucher_plugin_paypal_textfiels' )['paypal_client_id'] ) ) ;
}

// load scripts #######################################
function kv_load_frontend_scripts()
{
    wp_enqueue_media();
    // load js admin-script
    wp_register_script( 'frontend_js_script', plugins_url( '/js/frontend-script.js', __FILE__ ), array( 'jquery' ) );
    wp_enqueue_script( 'frontend_js_script' );
    wp_localize_script( 'frontend_js_script', 'usr_data_obj', array(
        'ajaxurl' => admin_url( 'admin-ajax.php' ),
        'nonce'   => wp_create_nonce( 'ajax-nonce' ),
    ) );
    // load css admin-script
    wp_register_style( 'frontend_css_script', plugins_url( '/css/frontend-style.css', __FILE__ ) );
    wp_enqueue_style( 'frontend_css_script' );
}

function kv_usr_data_request()
{
    $nonce = $_POST['nonce'];
    if ( !wp_verify_nonce( $nonce, 'ajax-nonce' ) ) {
        die( 'Nonce value cannot be verified.' );
    }
    
    if ( isset( $_POST ) ) {
        $coupon_data = KV_SaveData::kv_saveData( $_POST['data'] );
        $send = new KV_SendData( $coupon_data );
        $send->kv_sendDataCurl();
        // send data
    }
    
    die;
}

add_action( 'wp_ajax_kv_usr_data_request', 'kv_usr_data_request' );
add_action( 'wp_ajax_nopriv_kv_usr_data_request', 'kv_usr_data_request' );
// create a session
function kv_load_backend_scripts()
{
    wp_enqueue_media();
    // load js admin-script
    wp_register_script( 'backend_js_script', plugins_url( '/js/backend-script.js', __FILE__ ) );
    wp_enqueue_script( 'backend_js_script' );
}

add_action( 'admin_enqueue_scripts', 'kv_load_backend_scripts' );
add_action( 'wp_enqueue_scripts', 'kv_load_frontend_scripts' );
// add fronpage site #############################################
function kv_oucher_add_frontpage()
{
    add_shortcode( 'kvoucher', 'KVoucherFrontendStuff\\KV_Form::kv_BillingAdress' );
}

add_action( 'init', 'kv_oucher_add_frontpage' );
// ################################################################
add_action( 'admin_menu', 'kv_settings_menu' );
function kv_settings_menu()
{
    add_menu_page(
        'KVoucher',
        // The title to be displayed in the browser window for this page.
        'KVoucher',
        // The text to be displayed for this menu item
        'administrator',
        // Which type of users can see this menu item
        'kvoucher_options',
        // The unique ID - that is, the slug - for this menu item
        'kv_plugin_display',
        // The name of the function to call when rendering this menu's page
        plugin_dir_url( __FILE__ ) . 'img/kvoucherpro.png'
    );
}

function kv_checkCompanyData()
{
    $company_data = array();
    $company_data = get_option( 'kvoucher_plugin_company_textfiels' );
    $required_data_company = array(
        __( 'Company name', 'kvoucherpro' )   => 'company',
        __( 'First Name', 'kvoucherpro' )     => 'first_name',
        __( 'Last Name', 'kvoucherpro' )      => 'last_name',
        __( 'Streetname', 'kvoucherpro' )     => 'street_name',
        __( 'Postal-Code', 'kvoucherpro' )    => 'postal_code',
        __( 'City', 'kvoucherpro' )           => 'city',
        __( 'Country', 'kvoucherpro' )        => 'country',
        __( 'Phonenumber', 'kvoucherpro' )    => 'phone_number',
        __( 'Company Domain', 'kvoucherpro' ) => 'company_url',
        __( 'Company E-mail', 'kvoucherpro' ) => 'company_email',
    );
    // output all errors
    foreach ( $required_data_company as $required => $value ) {
        if ( empty($company_data[$value]) || $company_data[$value] == null || $company_data[$value] == '' ) {
            echo  '<i style="color:red">' . esc_html( $required ) . ' ' . esc_html( __( 'is required!', 'kvoucherpro' ) ) . '</i><br>' ;
        }
    }
}

function kv_checkTermsOfServiceData()
{
    $terms_of_service_data = array();
    $terms_of_service_data = get_option( 'kvoucher_plugin_terms_of_service_textfields' );
    $required_data_terms_of_service = array(
        'AGB`s' => 'terms_of_service',
    );
    // output all errors
    foreach ( $required_data_terms_of_service as $required => $value ) {
        if ( empty($terms_of_service_data[$value]) || $terms_of_service_data[$value] == null || $terms_of_service_data[$value] == '' ) {
            echo  '<i style="color:orange">' . __( 'The creation of the general terms and conditions is recommended!', 'kvoucherpro' ) . '</i><br>' ;
        }
    }
}

function kv_check_paypal_data()
{
    $paypal_data = array();
    $paypal_data = get_option( 'kvoucher_plugin_paypal_textfiels' );
    $required_data = array(
        'Paypal Client-ID' => 'paypal_client_id',
    );
    // output all errors
    foreach ( $required_data as $required => $value ) {
        if ( empty($paypal_data[$value]) || $paypal_data[$value] == null || $paypal_data[$value] == '' ) {
            echo  '<i style="color:red">' . esc_html( $required ) . ' ' . __( 'is required!', 'kvoucherpro' ) . '</i><br>' ;
        }
    }
}

add_filter( 'safe_style_css', function ( $styles ) {
    $styles[] = 'display';
    return $styles;
} );
function kv_plugin_display()
{
    ?>
<!-- Create a header in the default WordPress 'wrap' container -->
<div class="wrap">

	<div id="icon-themes" class="icon32"></div>

	<h2><?php 
    _e( 'KVoucher Options', 'kvoucherpro' );
    ?></h2>
	
		<?php 
    settings_errors();
    ?>
        
        <?php 
    kv_checkCompanyData();
    ?>
        
        <?php 
    kv_check_paypal_data();
    ?>
        
        <?php 
    kv_checkTermsOfServiceData();
    ?>
        
        <?php 
    $active_tab = ( isset( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ) : 'company_options' );
    ?>
         
    <h2 class="nav-tab-wrapper">
		<a href="?page=kvoucher_options&tab=company_options"
			class="nav-tab <?php 
    echo  ( $active_tab == 'company_options' ? 'nav-tab-active' : '' ) ;
    ?>"><?php 
    _e( 'Company Settings', 'kvoucherpro' );
    ?></a>

		<a href="?page=kvoucher_options&tab=paypal_options"
			class="nav-tab <?php 
    echo  ( $active_tab == 'paypal_options' ? 'nav-tab-active' : '' ) ;
    ?>"><?php 
    _e( 'PayPal Settings', 'kvoucherpro' );
    ?></a>

		<a href="?page=kvoucher_options&tab=terms_of_service_options"
			class="nav-tab <?php 
    echo  ( $active_tab == 'terms_of_service_options' ? 'nav-tab-active' : '' ) ;
    ?>"><?php 
    _e( 'Terms of Service', 'kvoucherpro' );
    ?></a>

		<a href="?page=kvoucher_options&tab=style_options"
			class="nav-tab <?php 
    echo  ( $active_tab == 'style_options' ? 'nav-tab-active' : '' ) ;
    ?>"><?php 
    _e( 'Style Settings', 'kvoucherpro' );
    ?></a>

		<a href="https://koboldsoft.com/kvoucher/" target="_blank"><img
			alt="<?php 
    _e( 'Handbook', 'kvoucherpro' );
    ?>"
			title="<?php 
    _e( 'Handbook', 'kvoucherpro' );
    ?>"
			src="<?php 
    echo  esc_url( plugins_url( 'img/help_kcoupon.png', __FILE__ ) ) ;
    ?>"
			width="35" height="35"></a>
	</h2>

	<form method="post" action="options.php">
 
            <?php 
    switch ( $active_tab ) {
        case "company_options":
            settings_fields( 'kvoucher_plugin_company_textfiels' );
            do_settings_sections( 'kvoucher_plugin_company_textfiels' );
            break;
        case "paypal_options":
            settings_fields( 'kvoucher_plugin_paypal_textfiels' );
            do_settings_sections( 'kvoucher_plugin_paypal_textfiels' );
            break;
        case "style_options":
            settings_fields( 'kvoucher_plugin_style_textfiels' );
            do_settings_sections( 'kvoucher_plugin_style_textfiels' );
            break;
        case "terms_of_service_options":
            settings_fields( 'kvoucher_plugin_terms_of_service_textfields' );
            do_settings_sections( 'kvoucher_plugin_terms_of_service_textfields' );
            break;
    }
    submit_button();
    ?>
             
        </form>

</div>
<!-- /.wrap -->
<?php 
}

//Edit_coupons