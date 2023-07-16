<?php
namespace App\Http\Helpers;

class RSA 
{
    static function rsa_decode($data){
        $private_key = env('RSA_PRIVATE_KEY');
        openssl_private_decrypt(
            base64_decode($data),
            $decode_result,
            $private_key
        ); 
        return $decode_result;
    }
}