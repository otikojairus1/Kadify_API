<?php

namespace App\Http\Controllers;
use \Mailjet\Resources;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Card;
class CardController extends Controller
{
    public function create_card(Request $request){
        $rules = [
            'description' => 'required',
            'expiry' => 'required',
            'name'    => 'required',
            'balance'    => 'required',
            'transactionsno'    => 'required',
            'banktype'    => 'required',
            'contactless_payment'    => 'required',
            'merchant_lock'    => 'required',
            'friends_withdrawal'    => 'required',
            'geo_lock'    => 'required',
            'deactivate_card'    => 'required',
            'card_no'    => 'required|integer',
            'security_code'    => 'required|integer',
            'email' => 'required',
        ];
    
        $input     = $request->only('description', 'expiry','name', 'email',
        'balance','transactionsno','banktype','contactless_payment',
    'merchant_lock','friends_withdrawal', 'geo_lock','deactivate_card','card_no','security_code','email'
    
    );
        $validator = Validator::make($input, $rules);
    
        if ($validator->fails()) {
            return response()->json(['success' => false, 'error' => $validator->messages()]);
        }

        $createdCard = Card::create(['description'=>$request->description, 
    'expiry'=>$request->expiry, 'name'=>$request->name, 'balance'=>$request->balance, 'transactionsno'=>$request->transactionsno,
'banktype'=>$request->banktype, 'contactless_payment'=>$request->contactless_payment, 'merchant_lock'=>$request->merchant_lock,
'friends_withdrawal'=>$request->friends_withdrawal, 'geo_lock'=>$request->geo_lock,'deactivate_card'=>$request->deactivate_card,
'card_no' => $request->card_no,'security_code'=>$request->security_code,'email'=>$request->email,]);

// notify user of card creation 
$card_security = mt_rand(100,999);

            $mj = new \Mailjet\Client('69ad231aaa1cba22e074fd1b8e1b2121','e36b81e521e64549bcf20980e67b606a',true,['version' => 'v3.1']);
            $body = [
                'Messages' => [
                    [
                        'From' => [
                            'Email' => "otikojairus@hotmail.com",
                            'Name' => "Kadify"
                        ],
                        'To' => [
                            [
                                'Email' => $request->email,
                                'Name' => $request->name
                            ]
                        ],
                        'Subject' => "KADIFY VIRTUAL CARD CREATION",
                        'TextPart' => "Greetings from Kadify!",
                        'HTMLPart' => "<h3>Greetings from Kadify,<br> welcome to our Finance world<h3> <h1>".$card_security."</h1> <h3>is your card security code for your new Kadify virtual card with card number <h1>".$request->card_no."</h1><h3>This is a virtual card tied to ".$request->banktype." Bank</h3></h3>"
                    ]
                ]
            ];
            $response = $mj->post(Resources::$Email, ['body' => $body]);
            $response->success();

// end of notice

        return response()->json(["responceCode"=>200, "data"=>$createdCard]);

    }

    public function get_card(Request $request){
        $rules = [
            'email' => 'required',
        ];
        $input     = $request->only('email');
       $validator = Validator::make($input, $rules);
    
        if ($validator->fails()) {
            return response()->json(['success' => false, 'error' => $validator->messages()]);
        }

        $cards = Card::where('email', $request->email)->get();
        return response()->json(["responceCode"=>200, "data"=>$cards]);
    }
}
