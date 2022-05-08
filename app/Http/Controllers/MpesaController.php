<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class MpesaController extends Controller
{

    public function stk( Request $request)
    {
       // get the Oauth Bearer access Token from safaricom
     

       $rules =
           ['amount'=> 'required|integer',
           'phone'=>'required|integer',
           'id'=>'required|integer'
           ]
       ;

       $input     = $request->only('amount','phone', 'id');
       $validator = Validator::make($input, $rules);

       if ($validator->fails()) {
           return response()->json(['success' => false, 'error' => $validator->messages()]);
       }

       $url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
      // $url = 'https://api.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';

       $curl = curl_init();
       curl_setopt($curl, CURLOPT_URL, $url);
       $credentials = base64_encode('D5VGIIfdrsmTHv7dCwGyo4hubU2YFFxN:XelQksS4JcMXfVMI');
       curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Basic '.$credentials)); //setting a custom header
       curl_setopt($curl, CURLOPT_HEADER, false);
       curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
       curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
       $curl_response = curl_exec($curl);
       $responce = json_decode($curl_response)->access_token;
       $accessToken = $responce; // access token here
       //mpesa user credentials
       $mpesaOnlineShortcode = "174379";
       $BusinessShortCode = $mpesaOnlineShortcode;
       $partyA = $request->phone;
       $partyB = $BusinessShortCode;
       $phoneNumber = $partyA;
       $mpesaOnlinePasskey = "bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919";
       date_default_timezone_set('Africa/Nairobi');
       $timestamp =  date('YmdHis');
       $amount = $request->amount;
     //  $contribution = $request->id;
       $dataToEncode = $BusinessShortCode.$mpesaOnlinePasskey.$timestamp;
       //dd($dataToEncode);
       $password = base64_encode($dataToEncode);
       //dd($password);

       //payment request to safaricom

       $url = 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';
       //$url = 'https://api.safaricom.co.ke/mpesa/stkpush/v1/processrequest';

       $curl = curl_init();
       curl_setopt($curl, CURLOPT_URL, $url);
       curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:Bearer '.$accessToken)); //setting custom header


       $curl_post_data = array(
           'BusinessShortCode' => $BusinessShortCode,
           'Password' => $password,
           'Timestamp' => $timestamp,
           'TransactionType' => 'CustomerPayBillOnline',
           'Amount' =>$amount,
           'PartyA' => $partyA,
           'PartyB' => $partyB,
           'PhoneNumber' => $partyA,
           'AccountReference'=>"KADIFY",
           'CallBackURL' => 'https://msaadaproject.herokuapp.com/api/v2/74aqaGu3sd4/callback',
           'TransactionDesc' => 'KADIFY'
       );

       $data_string = json_encode($curl_post_data);

       curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
       curl_setopt($curl, CURLOPT_POST, true);
       curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);

       $curl_response = curl_exec($curl);

        $curl_response = json_decode($curl_response);

        return response()->json(['responseCode'=>200, "mpesaWalletTopup"=>$curl_response]);
       


}
}
