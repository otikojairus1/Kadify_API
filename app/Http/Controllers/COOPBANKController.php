<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class COOPBANKController extends Controller
{
    public function access_token(){
        $CK = "hgcy___p9DJrlRIe0DjzGrl7kO4a";
        $SK = "xvgPTBADFM4oCQJIwQ85bsftBXsa";

        // generating access token

        $authorization = base64_encode("$CK:$SK");

$header = array("Authorization: Basic {$authorization}");

$content = "grant_type=client_credentials";

    //echo $authorization;

$curl = curl_init();

curl_setopt_array($curl, array(

CURLOPT_URL => "https://developer.co-opbank.co.ke:8243/token",

CURLOPT_HTTPHEADER => $header,

CURLOPT_SSL_VERIFYPEER => false,

CURLOPT_RETURNTRANSFER => true,

CURLOPT_POST => true,

CURLOPT_POSTFIELDS => $content

));

$response = curl_exec($curl);

//curl_close($curl);


if ($response === false) {

echo "Failed";

echo curl_error($curl);

//curl_close($curl);

echo "Failed";

exit(0);

}

$token= json_decode($response)->access_token;
return response()->json(['responseCode'=>200, "coopbankToken"=>$token]);
    }

    
}
