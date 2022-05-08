<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AirtelAfricaController extends Controller
{
    public function AccessToken(){
        $url = "https://openapiuat.airtel.africa/auth/oauth2/token";

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        
        $headers = array(
         
           "Content-Type:application/json",
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        
        $data = '
        {
            "client_id": "5767d245-a3f5-4f88-844a-0c7a8d37e0b4",
            "client_secret": "****************************",
            "grant_type": "client_credentials"
      }
        ';
        
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        
        //for debug only!
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $resp = curl_exec($curl);
        curl_close($curl);
        //var_dump($resp);
        $responce = json_decode($resp);
                return response()->json(['responseCode'=>200, "AirtelAfrica"=>$responce]);
               //eturn $responce;
    }
}
