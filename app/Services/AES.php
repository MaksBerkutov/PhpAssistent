<?php

namespace App\Services;

class AES
{
    private function GenerateIV($length = 32): string {
        $randomBytes = openssl_random_pseudo_bytes($length / 2);

        return strtoupper(bin2hex($randomBytes));
    }
    public function Encrypt(array $data)
    {
        $aesKey = hex2bin(env("AES"));
        $iv = $this->GenerateIV();
        $aesIv = hex2bin($iv);
        foreach ($data as $key => $value) {
            $value .= "\0";
            $ciphertext = openssl_encrypt(
                $value,
                'aes-128-cbc',
                $aesKey,
                OPENSSL_RAW_DATA,
                $aesIv
            );
            $data[$key] = base64_encode($ciphertext);

        }
        $data["IV"] = $iv;

        return $data;

    }
    public function Decrypt(string $message,string $IV)
    {
        $aesKey = hex2bin(env("AES"));
        $aesIv = hex2bin($IV);
        $encryptedData = base64_decode($message);

        $decryptedData = openssl_decrypt(
            $encryptedData,
            'aes-128-cbc',
            $aesKey,
            OPENSSL_RAW_DATA,
            $aesIv
        );

        if ($decryptedData === false) {
            throw new \Exception('Decryption failed: ' . openssl_error_string());
        }

        return rtrim($decryptedData, "\0");
    }
}
