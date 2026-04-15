<?php

namespace App\Services;

use Exception;

class AES
{
    private const FALLBACK_AES_HEX = '30555C24496F7F124026365948694E0F';

    private function GenerateIV(int $length = 32): string
    {
        $randomBytes = random_bytes((int)($length / 2));
        return strtoupper(bin2hex($randomBytes));
    }

    private function resolveAesHexKey(): string
    {
        $candidates = [
            (string) env('AES', ''),
            (string) env('AES_KEY', ''),
            (string) env('ARDUINO_AES_KEY', ''),
            self::FALLBACK_AES_HEX,
        ];

        foreach ($candidates as $candidate) {
            $candidate = strtoupper(trim($candidate));
            if (strlen($candidate) === 32 && ctype_xdigit($candidate)) {
                return $candidate;
            }
        }

        throw new Exception('AES key is not configured. Set AES in .env as HEX(32).');
    }

    private function resolveBinaryKey(): string
    {
        $key = hex2bin($this->resolveAesHexKey());
        if ($key === false) {
            throw new Exception('Invalid AES key format in environment.');
        }

        return $key;
    }

    public function Encrypt(array $data)
    {
        $aesKey = $this->resolveBinaryKey();
        $iv = $this->GenerateIV();
        $aesIv = hex2bin($iv);

        if ($aesIv === false) {
            throw new Exception('Failed to generate AES IV.');
        }

        foreach ($data as $key => $value) {
            $value .= "\0";
            $ciphertext = openssl_encrypt(
                $value,
                'aes-128-cbc',
                $aesKey,
                OPENSSL_RAW_DATA,
                $aesIv
            );
            if ($ciphertext === false) {
                throw new Exception('Encryption failed: ' . (string)openssl_error_string());
            }

            $data[$key] = base64_encode($ciphertext);

        }
        $data["IV"] = $iv;

        return $data;

    }
    public function Decrypt(string $message,string $IV)
    {
        $aesKey = $this->resolveBinaryKey();
        $aesIv = hex2bin($IV);
        if ($aesIv === false) {
            throw new Exception('Invalid response IV format.');
        }

        $encryptedData = base64_decode($message);
        if ($encryptedData === false) {
            throw new Exception('Invalid encrypted payload format.');
        }

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
