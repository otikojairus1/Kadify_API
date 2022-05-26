<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DPOcontroller extends Controller
{
    public function access_token(Request $request){

      $rules = [
          
         'amount' => 'required',
         'firstname'    => 'required',
         'lastname'    => 'required',
         'email'    => 'required | email',
     ];
 
     $input     = $request->only('amount','firstname', 'lastname','email');
     $validator = Validator::make($input, $rules);
 
     if ($validator->fails()) {
         return response()->json(['responseCode' => 401, 'error' => $validator->messages()]);
     }


      // end of route protection

$url = "https://secure.3gdirectpay.com/API/v6/";

$curl = curl_init($url);
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

$headers = array(
   "Content-Type: application/xml",
   "Accept: application/xml",
);
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

$data = '
<?xml version="1.0" encoding="utf-8"?>
<API3G>
<CompanyToken>8D3DA73D-9D7F-4E09-96D4-3D44E7A83EA3</CompanyToken>
<Request>createToken</Request>
<Transaction>
<PaymentAmount>'.$request->amount.'</PaymentAmount>
<PaymentCurrency>KES</PaymentCurrency>
<CompanyRef>49FKEOA</CompanyRef>
<RedirectURL>http://www.google.com/</RedirectURL>

<BackURL> http://www.domain.com/backurl.php </BackURL>
<CompanyRefUnique>0</CompanyRefUnique>
<PTL>15</PTL>
<PTLtype>hours</PTLtype>
<customerFirstName>'.$request->firstname.'</customerFirstName>
<customerLastName>'.$request->lastname.'</customerLastName>
<customerZip>254</customerZip>
<customerCity>Nairobi</customerCity>
<customerCountry>KE</customerCountry>
<customerEmail>'.$request->email.'</customerEmail>
</Transaction>
<Services>
<Service>
<ServiceType>5525</ServiceType>
<ServiceDescription>Kadify card billing</ServiceDescription>
<ServiceDate>2017/11/20</ServiceDate>
</Service>
</Services>
</API3G>';

curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

//for debug only!
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

$resp = curl_exec($curl);
curl_close($curl);
//var_dump($resp);

$xmldata = simplexml_load_string($resp);
$jsondata = json_encode($xmldata);

$myJSON = json_decode($jsondata);
        return response()->json(['responseCode'=>$myJSON]);
    }


    public function verify_token(){

        $url = "https://secure.3gdirectpay.com/API/v6/";
        
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        
        $headers = array(
           "Content-Type: application/xml",
           "Accept: application/xml",
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        
        $data = '
       
        <?xml version="1.0" encoding="utf-8"?>
        <API3G>
         <CompanyToken>8D3DA73D-9D7F-4E09-96D4-3D44E7A83EA3</CompanyToken>
         <Request>verifyToken</Request>
    
        <TransactionToken>4D553D31-EEAA-4735-BCAC-1263F7B5F8DD</TransactionToken>
        </API3G>';
        
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        
        //for debug only!
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        
        $resp = curl_exec($curl);
        curl_close($curl);
       // var_dump($resp);
        
        $xmldata = simplexml_load_string($resp);
        $jsondata = json_encode($xmldata);
        
        $myJSON = json_decode($jsondata);
        
               return response()->json(['responseCode'=>$myJSON]);
            }
        
}
