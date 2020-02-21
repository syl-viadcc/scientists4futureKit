<?php

namespace Stb\Letter\Classes;

class StbCrypto
{
    const METHOD = 'aes-256-ctr';

    public static function getIV( ) {
        $enc_iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length(self::METHOD));
        return bin2hex($enc_iv);
    }


    /**
     * Encrypt and decrypt
     *
     * @param string $string string to be encrypted/decrypted
     * @param string $action what to do with this? e for encrypt, d for decrypt
     * @param string $iv A single-use unique Random Initialization Vector
     * @return bool|string
     * @author STB based on Nazmul Ahsan <n.mukto@gmail.com>
     * @link http://nazmulahsan.me/simple-two-way-function-encrypt-decrypt-string/
     *
     */
    public static function crypt( $string, $action = 'e',  $iv ) {
        // you may change these values to your own
        $secret_key = 'Kp4u45JMDjtnknoujmD7UZc57RFgjjzyHu9EpeWJcf7UFwzjS5JVp2McsqDWzF6u';

        $output = false;
        $key = hash( 'sha256', $secret_key );
        $iv = substr( hash( 'sha256', $iv ), 0, 16 );

        if( $action == 'e' ) {
            $output = base64_encode( openssl_encrypt( $string, self::METHOD, $key, 0, $iv ) );
        }
        else if( $action == 'd' ){
            $output = openssl_decrypt( base64_decode( $string ), self::METHOD, $key, 0, $iv );
        }

        return $output;
    }
}