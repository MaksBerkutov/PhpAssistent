<?php

namespace App\Services;

use App\Services\AES as AESService;
use Exception;
use Illuminate\Support\Facades\Log;

class DevicesReqest
{
    private static function normalizeUrl(string $url): string
    {
        $url = trim($url);
        if ($url === '') {
            throw new Exception('Device URL is empty.');
        }

        if (!preg_match('#^https?://#i', $url)) {
            $url = "http://{$url}";
        }

        return rtrim($url, '/');
    }

    public static function sendReqest(string $url, ?string $message, ?string $arg = '')
    {

        $message = trim((string) $message);
        if ($message === '') {
            throw new Exception('Device command is empty. Check environment command variables.');
        }

        $AES = new AESService();
        $url = self::normalizeUrl($url) . '/command';
        if (is_null($arg)) {
            $arg = '';
        }

        $jsonData = json_encode($AES->Encrypt(['command' => $message, 'arg' => $arg]));
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($jsonData)
        ));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);

        $response = curl_exec($ch);
        
        if ($response === false) {
            $error = curl_error($ch);
            Log::error($error);
            curl_close($ch);
            throw  new Exception($error);
        } else {
            curl_close($ch);
            $responseObject = json_decode((string)$response);

            if (!is_object($responseObject)) {
                throw new Exception('Invalid device response: ' . (string)$response);
            }

            if (isset($responseObject->ok) && $responseObject->ok === false) {
                $error = isset($responseObject->error) ? (string)$responseObject->error : 'Device returned error.';
                throw new Exception($error);
            }

            if (!isset($responseObject->message, $responseObject->IV)) {
                throw new Exception('Invalid encrypted response from device.');
            }

            return  $AES->Decrypt((string)$responseObject->message, (string)$responseObject->IV);
        }
    }
    public static function isAvalibe(string $url): bool
    {
        $url = self::normalizeUrl($url) . '/command';
        $curlInit = curl_init($url);
        curl_setopt($curlInit, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($curlInit, CURLOPT_HEADER, true);
        curl_setopt($curlInit, CURLOPT_NOBODY, true);
        curl_setopt($curlInit, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($curlInit);

        curl_close($curlInit);
        return (bool) $response;
    }
}
