<?php

class Encryption {
    private string $cipherEn;
    private string $key;

    public function __construct($cipherEn, $key) {
        $this->cipherEn = $cipherEn;
        $this->key = $key;
    }

    public function encrypt($data): array {
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($this->cipherEn));
        $encrypted = openssl_encrypt($data, $this->cipherEn, $this->key, 0, $iv);
        return ["value" => base64_encode($encrypted), "iv"=>$iv];
    }

    public function decrypt($data, $iv): string {
        $dataDecoded = base64_decode($data);
        return openssl_decrypt($dataDecoded, $this->cipherEn, $this->key, 0, $iv);
    }
}