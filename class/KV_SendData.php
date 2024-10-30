<?php
namespace KVoucherSendUsrData;

class KV_SendData{
        
        private $data;
        
        public function __construct( $data )
        {
            $this->data = $data;
            
           
        }
        
        function kv_encryptData($dataToEncrypt){
            
            $cipher = 'aes-256-ofb';
            
            if (in_array( $cipher , openssl_get_cipher_methods()))
            {
                $ivlen = openssl_cipher_iv_length($cipher);
                
                $iv = openssl_random_pseudo_bytes($ivlen);
                
                $data['data'] = openssl_encrypt($dataToEncrypt, 'aes-256-ofb', '~B-$mAf5~jm<Fz!p', $options=0, $iv);
                
                $data['iv'] = $iv;
                
                return $data;
                
            }
            
        }
        
        function kv_encrytURLVarables( $array ){
            
            $data = kv_encryptData( http_build_query( $array ) );
            
            return  http_build_query( $data );
            
        }
        
        public function kv_sendDataCurl(){
            
            $data = self::kv_encrytURLVarables($this->data);
            
            $args = array(
                'method'      => 'POST',
                'body'        => $data,
                'timeout'     => '5',
                'redirection' => '5',
                'httpversion' => '1.0',
                'blocking'    => true,
                'headers'     => array(),
                'cookies'     => array(),
            );
            
            $response = wp_remote_post( 'https://couponsystem.koboldsoft.com/createpdf.php', $args );
            
            
        }
        
}      