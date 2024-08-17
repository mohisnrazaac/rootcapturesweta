<?php
Class EncryptDecrypt
{
    private $api_secret_key;
    private $api_salt_iv;
    private $api_encryption_block_mode;
    private $options = 0;

    public function __construct($api_secret_key, $api_salt_iv, $api_encryption_block_mode)
    {
        $this->api_secret_key = $api_secret_key;
        $this->api_salt_iv = $api_salt_iv;
        $this->api_encryption_block_mode = $api_encryption_block_mode;
    }

    //it must be the same when you encrypt and decrypt
    public function getIV() {
        return $this->api_salt_iv;
        // return mcrypt_create_iv(mcrypt_get_iv_size($this->cipher, $this->mode), MCRYPT_RAND);
        // return trim(openssl_random_pseudo_bytes(openssl_cipher_iv_length($this->method)));
    }

    public function encrypt($data_str=null) {
        if(!empty($data_str)){
            return trim(openssl_encrypt($data_str, $this->api_encryption_block_mode, $this->api_secret_key, $this->options,$this->getIV()));
        }
        return null;
    }

    public function decrypt($data_str=null) {
        if(!empty($data_str)){
            $ret=openssl_decrypt($data_str, $this->api_encryption_block_mode, $this->api_secret_key, $this->options,$this->getIV());
          
           return   trim($ret); 
        }
        return null;
    }
}

?>