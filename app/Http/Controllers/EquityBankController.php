<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class EquityBankController extends Controller
{
    //this is an endpoint to the equitybank open api banking.. it returns an access token to be
    // used to all the outgoing bank calls.
    public function generateAccessToken(){
        $url = "https://uat.finserve.africa/authentication/api/v3/authenticate/merchant";

$curl = curl_init($url);
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

$headers = array(
   "Api-Key:YTiDckHMJTRHJmstcDBZysLnJm4SHcWPJh8HifhZAuaewpb6xsIGR66oDc0MWRZK04+GoAHHvTeWE+6YsN2E3Q==",
   "Content-Type:application/json",
);
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

$data = <<<DATA
{
"merchantCode": "1835512942",
"consumerSecret": "oW5E844eTY57ZT8yhTwb94IzOjGj1k"
}
DATA;

curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

//for debug only!
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

$resp = curl_exec($curl);
curl_close($curl);
//var_dump($resp);
$responce = json_decode($resp)->accessToken;
        //return response()->json(['responseCode'=>200, "EquityOpenBankingAccessToken"=>$responce]);
       return $responce;
    }

    //this is an api to the equitybank billers open banking endpoint,, it returns all the active
    // equitybank billers.

    public function allEquityBankBillers(){
      //  $page= 1;

        //get an accessToken
        $token = EquityBankController::generateAccessToken();
            $url = "https://uat.finserve.africa/v3-apis/transaction-api/v3.0/billers?page=1&per_page=20&category=utilities";

            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

            $headers = array(
            "Authorization: Bearer ".$token,
            );
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            //for debug only!
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

            $resp = curl_exec($curl);
            curl_close($curl);
            $resp = json_decode($resp);
            return response()->json(['responseCode'=>200, "EquityOpenBankingBillers"=>$resp]);



    }


    //gets all equity bank mearchants
    public function getAllMerchants(){
            $token = EquityBankController::generateAccessToken();
            $url = "https://uat.finserve.africa/v3-apis/transaction-api/v3.0/merchants?page=1&per_page=10";

            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

            $headers = array(
            "Authorization: Bearer ".$token,
            );
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            //for debug only!
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

            $resp = curl_exec($curl);
            curl_close($curl);
            $resp = json_decode($resp);
            return response()->json(['responseCode'=>200, "EquityOpenBankingMechants"=>$resp]);

    }

    public function KYC (){
        $token = EquityBankController::generateAccessToken();

        $url = "https://uat.finserve.africa/v3-apis/v3.0/validate/identity";

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $headers = array(
        "Authorization: Bearer ".$token,
        "Content-Type: application/json",
        "signature: huKUSJ1mKy67ptMCDHgSADgPmN8h6Wm5ZYKfLoTJSHWDtA+i2Ra1e3Wc12Pp3Z/Nk+g2JcTGrvWPVw3BCae9QiFI8YpU+GPvezIOmOJvZupo09khePH2nz8TZGKuR6mRhcXd1RNc4dnE6UQbAeqpqPoXbJwOA+02RtfhSDJeLao9bRat4vGWTAlWe/T+mgzMvudeeIpToZLMvBtUVVlLuZFyQb0GeeW9YOghEqfgyzC+6Gpjtg9lnZfDDdAc3fFnGSZ3S0hgaalK94RZSNuF/7OCFKHm5Rv2Q+X91YSqL3Ka3YKkiDfS8kE2w0/8GsWp5WrZo/n3NUTkFonVvucb6w==",
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $data = <<<DATA

        {
            "identity": {
                "documentType": "ALIENID",
                "firstName": "John",
                "lastName": "Doe",
                "dateOfBirth": "1970-01-30",
                "documentNumber": "123456",
                "countryCode": "KE"
            }
        }

        DATA;

        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

        //for debug only!
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $resp = curl_exec($curl);
        curl_close($curl);
        $resp = json_decode($resp);
        return response()->json(['responseCode'=>200, "EquityOpenBankingKYC"=>$resp]);

}

//buy airtime

public function CRB(){
        $token = EquityBankController::generateAccessToken();

        $url = "https://uat.finserve.africa/v3-apis/v3.0/validate/crb";

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $headers = array(
        "Authorization: Bearer ".$token,
        "Content-Type: application/json",
        "signature: huKUSJ1mKy67ptMCDHgSADgPmN8h6Wm5ZYKfLoTJSHWDtA+i2Ra1e3Wc12Pp3Z/Nk+g2JcTGrvWPVw3BCae9QiFI8YpU+GPvezIOmOJvZupo09khePH2nz8TZGKuR6mRhcXd1RNc4dnE6UQbAeqpqPoXbJwOA+02RtfhSDJeLao9bRat4vGWTAlWe/T+mgzMvudeeIpToZLMvBtUVVlLuZFyQb0GeeW9YOghEqfgyzC+6Gpjtg9lnZfDDdAc3fFnGSZ3S0hgaalK94RZSNuF/7OCFKHm5Rv2Q+X91YSqL3Ka3YKkiDfS8kE2w0/8GsWp5WrZo/n3NUTkFonVvucb6w==",
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $data = <<<DATA
        {
            "crbIdentity": {
                "nationalId": "37101932",
                "reportType": "Bank",
                "countryCode": "KE",
                "reference": "abc05RGTY64E1",
                "customerID": "37101932",
                "firstName": "Jairus",
                "lastName": "Anjere",
                "reportReason": "checking",
                "creditBureauCode":"2344"
            }
        }
        DATA;

        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

        //for debug only!
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $resp = curl_exec($curl);
        curl_close($curl);
        $resp = json_decode($resp);
        return response()->json(['responseCode'=>200, "EquityOpenBankingCRB"=>$resp]);

}

//billPayment api
public function billPayment(Request $request){
    $rules = ['amount' => 'required | integer',
    'name' => 'required ',
];
    $input     = $request->only('amount', 'name');
    $validator = Validator::make($input, $rules);
    if ($validator->fails()) {
        return response()->json(['success' => false, 'error' => $validator->messages()]);
    }
    // This API Provides Partners the Capability To Initiate Utility Bill Payments For Goods And Services
    $token = EquityBankController::generateAccessToken();
$url = "https://uat.finserve.africa/v3-apis/transaction-api/v3.0/bills/pay";
$curl = curl_init($url);
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$headers = array(
   "Authorization: Bearer ".$token,
   "Content-Type: application/json",
   "signature: huKUSJ1mKy67ptMCDHgSADgPmN8h6Wm5ZYKfLoTJSHWDtA+i2Ra1e3Wc12Pp3Z/Nk+g2JcTGrvWPVw3BCae9QiFI8YpU+GPvezIOmOJvZupo09khePH2nz8TZGKuR6mRhcXd1RNc4dnE6UQbAeqpqPoXbJwOA+02RtfhSDJeLao9bRat4vGWTAlWe/T+mgzMvudeeIpToZLMvBtUVVlLuZFyQb0GeeW9YOghEqfgyzC+6Gpjtg9lnZfDDdAc3fFnGSZ3S0hgaalK94RZSNuF/7OCFKHm5Rv2Q+X91YSqL3Ka3YKkiDfS8kE2w0/8GsWp5WrZo/n3NUTkFonVvucb6w==",
);
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
$referenceOne = rand(1000000,9999999);
$referenceTwo = rand(1000000000000,9999999999999);
$data = '
{
     "biller": {
          "billerCode": "320320",
          "countryCode": "KE"
     },
     "bill": {
          "reference": "'.$referenceOne.'",
          "amount":"'.$request->amount.'",
          "currency": "KES"
     },
     "payer": {
          "name":  "'.$request->name.'",
          "account": "111222",
          "reference": "'.$referenceTwo.'",
          "mobileNumber": "0763000000"
     },
     "partnerId": "0170199740087",
     "remarks": "paid"
    
}';

curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

//for debug only!
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

$resp = curl_exec($curl);
    $resp = json_decode($resp);
        return response()->json(['responseCode'=>200, "EquityOpenBankingBillPayment"=>$resp]);
}


public function byAirtime(Request $request){
            $rules = ['amount' => 'required | integer',
            'Telco' => 'required',
            'countryCode' => 'required',
            'MobileNumber' => 'required'
        ];
            $input     = $request->only('amount', 'Telco', 'countryCode', 'MobileNumber');
            $validator = Validator::make($input, $rules);
            if ($validator->fails()) {
                return response()->json(['success' => false, 'error' => $validator->messages()]);
            }

        $token = EquityBankController::generateAccessToken();
        $referenceOne = rand(1000000,9999999);
        $referenceTwo = rand(1000000000000,9999999999999);
        $url = "https://uat.finserve.africa/v3-apis/transaction-api/v3.0/airtime";
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $headers = array(
        "Authorization: Bearer ".$token,
        "Content-Type: application/json",
        "signature: huKUSJ1mKy67ptMCDHgSADgPmN8h6Wm5ZYKfLoTJSHWDtA+i2Ra1e3Wc12Pp3Z/Nk+g2JcTGrvWPVw3BCae9QiFI8YpU+GPvezIOmOJvZupo09khePH2nz8TZGKuR6mRhcXd1RNc4dnE6UQbAeqpqPoXbJwOA+02RtfhSDJeLao9bRat4vGWTAlWe/T+mgzMvudeeIpToZLMvBtUVVlLuZFyQb0GeeW9YOghEqfgyzC+6Gpjtg9lnZfDDdAc3fFnGSZ3S0hgaalK94RZSNuF/7OCFKHm5Rv2Q+X91YSqL3Ka3YKkiDfS8kE2w0/8GsWp5WrZo/n3NUTkFonVvucb6w==",
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $data = '

        {
            "customer": {
                "countryCode": "'.$request->countryCode.'",
                "mobileNumber": "'.$request->MobileNumber.'"
            },
            "airtime": {
                "amount": "'.$request->amount.'",
                "reference": "'.$referenceTwo.'",
                "telco": "'.$request->Telco.'"
            }
        }

        ';

        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

        //for debug only!
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $resp = curl_exec($curl);
        curl_close($curl);
        $resp = json_decode($resp);
        return response()->json(['responseCode'=>200, "EquityOpenBankingAirtime"=>$resp]);
}

    public function send_money_within_equity(){
        $token = EquityBankController::generateAccessToken();
        $referenceTwo = rand(1000000000000,9999999999999);

        $url = "https://uat.finserve.africa/v3-apis/transaction-api/v3.0/remittance/internalBankTransfer";

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $headers = array(
        "Authorization: Bearer ".$token,
        "Content-Type: application/json",
        "signature: huKUSJ1mKy67ptMCDHgSADgPmN8h6Wm5ZYKfLoTJSHWDtA+i2Ra1e3Wc12Pp3Z/Nk+g2JcTGrvWPVw3BCae9QiFI8YpU+GPvezIOmOJvZupo09khePH2nz8TZGKuR6mRhcXd1RNc4dnE6UQbAeqpqPoXbJwOA+02RtfhSDJeLao9bRat4vGWTAlWe/T+mgzMvudeeIpToZLMvBtUVVlLuZFyQb0GeeW9YOghEqfgyzC+6Gpjtg9lnZfDDdAc3fFnGSZ3S0hgaalK94RZSNuF/7OCFKHm5Rv2Q+X91YSqL3Ka3YKkiDfS8kE2w0/8GsWp5WrZo/n3NUTkFonVvucb6w==",
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $data = '

        {
            "source": {
                "countryCode": "KE",
                "name": "A N.Other",
                "accountNumber": "0011547896523"
            },
            "destination": {
                "type": "bank",
                "countryCode": "KE",
                "name": "John Doe",
                "accountNumber": "0022547896523"
            },
            "transfer": {
                "type": "InternalFundsTransfer",
                "amount": "10000.00",
                "currencyCode": "KES",
                "reference": "'.$referenceTwo.'",
                "date": "2018-08-18",
                "description": "some remarks here"
            }
        }

        ';

        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

        //for debug only!
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $resp = curl_exec($curl);
        curl_close($curl);
        
        $resp = curl_exec($curl);
        $resp = json_decode($resp);





        return response()->json(['responseCode'=>200, "EquityOpenBankingIFT"=>$resp]);
        
    }

    public function transferToMobileWallets(){
        $token = EquityBankController::generateAccessToken();
        $referenceTwo = rand(100000000000,999999999999);
        $url = "https://uat.finserve.africa/v3-apis/transaction-api/v3.0/remittance/sendmobile";

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $headers = array(
        "Authorization: Bearer ".$token,
        "Content-Type: application/json",
        "signature: huKUSJ1mKy67ptMCDHgSADgPmN8h6Wm5ZYKfLoTJSHWDtA+i2Ra1e3Wc12Pp3Z/Nk+g2JcTGrvWPVw3BCae9QiFI8YpU+GPvezIOmOJvZupo09khePH2nz8TZGKuR6mRhcXd1RNc4dnE6UQbAeqpqPoXbJwOA+02RtfhSDJeLao9bRat4vGWTAlWe/T+mgzMvudeeIpToZLMvBtUVVlLuZFyQb0GeeW9YOghEqfgyzC+6Gpjtg9lnZfDDdAc3fFnGSZ3S0hgaalK94RZSNuF/7OCFKHm5Rv2Q+X91YSqL3Ka3YKkiDfS8kE2w0/8GsWp5WrZo/n3NUTkFonVvucb6w==",
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $data = '
        {
            "source": {
                "countryCode": "KE",
                "name": "John Doe",
                "accountNumber": "0170199740087"
            },
            "destination": {
                "type": "mobile",
                "countryCode": "KE",
                "name": "A N.Other",
                "mobileNumber": "0722753364",
                "walletName": "Mpesa"
            },
            "transfer": {
                "type": "MobileWallet",
                "amount": "1000",
                "currencyCode": "KES",
                "date": "2022-01-21",
                "description": "some remarks here",
                "reference": "564564564654"
            }
        }

        ';

        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

        //for debug only!
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $resp = curl_exec($curl);
        curl_close($curl);
        $resp = curl_exec($curl);
        $resp = json_decode($resp);

return response()->json(['responseCode'=>200, "EquityOpenBankingTransferToMobileWallet"=>$resp]);

    }

    public function swift(){
        $token = EquityBankController::generateAccessToken();
        $url = "https://uat.finserve.africa/v3-apis/transaction-api/v3.0/remittance/swift";

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $headers = array(
        "Authorization: Bearer ".$token,
        "Content-Type: application/json",
        "signature: huKUSJ1mKy67ptMCDHgSADgPmN8h6Wm5ZYKfLoTJSHWDtA+i2Ra1e3Wc12Pp3Z/Nk+g2JcTGrvWPVw3BCae9QiFI8YpU+GPvezIOmOJvZupo09khePH2nz8TZGKuR6mRhcXd1RNc4dnE6UQbAeqpqPoXbJwOA+02RtfhSDJeLao9bRat4vGWTAlWe/T+mgzMvudeeIpToZLMvBtUVVlLuZFyQb0GeeW9YOghEqfgyzC+6Gpjtg9lnZfDDdAc3fFnGSZ3S0hgaalK94RZSNuF/7OCFKHm5Rv2Q+X91YSqL3Ka3YKkiDfS8kE2w0/8GsWp5WrZo/n3NUTkFonVvucb6w==",
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $data = '

        {
            "source": {
                "countryCode": "KE",
                "name": "John Doe",
                "accountNumber": "0011547896523",
                "sourceCurrency":"KES"
            },
            "destination": {
                "type": "bank",
                "countryCode": "US",
                "name": "A N.Other",
                "bankBic": "BOTKJPJTXXX",
                "accountNumber": "12365489",
                "addressline1": "Post Box 56",
                "currency":"USD"
            },
            "transfer": {
                "type": "SWIFT",
                "amount": "10000.00",
                "currencyCode": "USD",
                "reference": "692194625798",
                "date": "2018-08-16",
                "description": "some description here",
                "chargeOption": "SELF"
            }
        }
        ';

        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

        //for debug only!
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $resp = curl_exec($curl);
        curl_close($curl);
        $resp = curl_exec($curl);
        $resp = json_decode($resp);

        return response()->json(['responseCode'=>200, "EquityOpenBankingSwiftTransfer"=>$resp]);
   
}





}
