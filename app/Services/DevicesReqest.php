<?php

namespace App\Services;
use App\Services\AES as AESService;
use Exception;
use Illuminate\Support\Facades\Log;

class DevicesReqest
{
    public static function sendReqest(string $url,string $message){
        $AES = new AESService();
        $url = "http://$url/command";
        $jsonData = json_encode($AES->Encrypt(['command'=>$message]));
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
            throw  new Exception($error);
        } else {
            $responseObject = json_decode($response);

            return  $AES->Decrypt($responseObject->message, $responseObject->IV);
        }
    }
    public static function isAvalibe(string $url): bool{
        $url = "http://$url/command";
        $curlInit = curl_init($url);
        curl_setopt($curlInit,CURLOPT_CONNECTTIMEOUT,10);
        curl_setopt($curlInit,CURLOPT_HEADER,true);
        curl_setopt($curlInit,CURLOPT_NOBODY,true);
        curl_setopt($curlInit,CURLOPT_RETURNTRANSFER,true);

        $response = curl_exec($curlInit);

        curl_close($curlInit);
        if ($response) return true;
        return false;

    }
}
