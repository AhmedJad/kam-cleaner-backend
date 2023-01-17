<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class MyFatoorahController extends Controller
{
    public $apiURL;
    public $apiKey;

    public function __construct()
    {
        $this->apiURL = "https://apitest.myfatoorah.com";
        $this->apiKey = "rLtt6JWvbUHDDhsZnfpAhpYk4dxYDQkbcPTyGaKp2TYqQgG7FGZ5Th_WD53Oq8Ebz6A53njUoo1w3pjU1D4vs_ZMqFiz_j0urb_BH9Oq9VZoKFoJEDAbRZepGcQanImyYrry7Kt6MnMdgfG5jn4HngWoRdKduNNyP4kzcp3mRv7x00ahkm9LAK7ZRieg7k1PDAnBIOG3EyVSJ5kK4WLMvYr7sCwHbHcu4A5WwelxYK0GMJy37bNAarSJDFQsJ2ZvJjvMDmfWwDVFEVe_5tOomfVNt6bOg9mexbGjMrnHBnKnZR1vQbBtQieDlQepzTZMuQrSuKn-t5XZM7V6fCW7oP-uXGX-sMOajeX65JOf6XVpk29DP6ro8WTAflCDANC193yof8-f5_EYY-3hXhJj7RBXmizDpneEQDSaSz5sFk0sV5qPcARJ9zGG73vuGFyenjPPmtDtXtpx35A-BVcOSBYVIWe9kndG3nclfefjKEuZ3m4jL9Gg1h2JBvmXSMYiZtp9MR5I6pvbvylU_PP5xJFSjVTIz7IQSjcVGO41npnwIxRXNRxFOdIUHn0tjQ-7LwvEcTXyPsHXcMD8WtgBh-wxR8aKX7WPSsT1O8d8reb2aR7K3rkV3K82K_0OgawImEpwSvp9MNKynEAJQS6ZHe_J_l77652xwPNxMRTMASk1ZsJL";
    }
    public function pay($invoice_value, $user_name, $user_phone, $user_email, $reference_id, $country_code, $callback_success, $callback_error)
    {
        $postFields = $this->postFields($invoice_value, $user_name, $user_phone, $user_email, $reference_id, $country_code, $callback_success, $callback_error);
        $data = $this->sendPayment($postFields);
        return $data;
    }
    public function callAPI($endpointURL,  $postFields = [], $requestType = 'POST')
    {
        $curl = curl_init($this->apiURL . $endpointURL);
        curl_setopt_array($curl, array(
            CURLOPT_CUSTOMREQUEST  => $requestType,
            CURLOPT_POSTFIELDS     => json_encode($postFields),
            CURLOPT_HTTPHEADER     => array("Authorization: Bearer $this->apiKey", 'Content-Type: application/json'),
            CURLOPT_RETURNTRANSFER => true,
        ));
        $response = curl_exec($curl);
        $curlErr  = curl_error($curl);
        curl_close($curl);
        $error = $this->handleError($response);
        if ($error || $curlErr) {
            return $error;
        }
        return json_decode($response);
    }
    //Commons
    private function sendPayment($postFields)
    {
        $json = $this->callAPI("/v2/SendPayment",  $postFields);
        if ($json == 'error')
            return $json;
        return $json;
    }
   
    private function handleError($response)
    {

        $json = json_decode($response);
        if (isset($json->IsSuccess) && $json->IsSuccess == true) {
            return null;
        }
        if (isset($json->ValidationErrors) || isset($json->FieldsErrors)) {
            $errorsObj = isset($json->ValidationErrors) ? $json->ValidationErrors : $json->FieldsErrors;
            $blogDatas = array_column($errorsObj, 'Error', 'Name');

            $error = implode(', ', array_map(function ($k, $v) {
                return "$k: $v";
            }, array_keys($blogDatas), array_values($blogDatas)));
        } else if (isset($json->Data->ErrorMessage)) {
            $error = $json->Data->ErrorMessage;
        }

        if (empty($error)) {
            $error = (isset($json->Message)) ? $json->Message : (!empty($response) ? $response : 'API key or API URL is not correct');
        }

        return $error;
    }
    private function postFields($invoice_value, $user_name, $user_phone, $user_email, $reference_id, $country_code, $callback_success, $callback_error)
    {
        return [
            //Fill required data
            'NotificationOption' => 'ALL', //'SMS', 'EML', or 'ALL'
            'InvoiceValue'       => $invoice_value,
            'CustomerName'       => $user_name,
            //Fill optional data
            'DisplayCurrencyIso' => 'kwd',
            'MobileCountryCode'  => $country_code,
            'CustomerMobile'     => $user_phone,
            'CustomerEmail'      => $user_email,
            'CallBackUrl'        => $callback_success,
            'ErrorUrl'           => $callback_error, //or 'https://example.com/error.php'
            'Language'           => "en",
            'CustomerReference'  => $reference_id,
        ];
    }
}
