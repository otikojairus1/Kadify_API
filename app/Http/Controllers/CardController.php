<?php

namespace App\Http\Controllers;
use \Mailjet\Resources;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Card;
use App\Models\User;
use App\Models\cardTransaction;
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
          
            'email' => 'required',
            'merchant_allowed'    => 'required'
        ];
    
        $input     = $request->only('description', 'expiry','name','merchant_allowed',
        'balance','transactionsno','banktype','contactless_payment',
    'merchant_lock','friends_withdrawal', 'geo_lock','deactivate_card','card_no','email'
    
    );
        $validator = Validator::make($input, $rules);
    
        if ($validator->fails()) {
            return response()->json(['success' => false, 'error' => $validator->messages()]);
        }
        $card_security = mt_rand(100,999);

        $createdCard = Card::create(['description'=>$request->description, 'merchant_allowed'=>$request->merchant_allowed,
    'expiry'=>$request->expiry, 'name'=>$request->name, 'balance'=>$request->balance, 'transactionsno'=>$request->transactionsno,
'banktype'=>$request->banktype, 'contactless_payment'=>$request->contactless_payment, 'merchant_lock'=>$request->merchant_lock,
'friends_withdrawal'=>$request->friends_withdrawal, 'geo_lock'=>$request->geo_lock,'deactivate_card'=>$request->deactivate_card,
'card_no' => $request->card_no,'security_code'=>$card_security,'email'=>$request->email,]);

// notify user of card creation 


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

    public function send_card(Request $request){
        $rules = [
            'receiver_card' => 'required|integer',
            'sender_card' => 'required|integer',
            'amount' =>'required|integer',
            'email' => 'required'

        ];
        $input     = $request->only('receiver_card','sender_card','amount','email');
       $validator = Validator::make($input, $rules);
    
        if ($validator->fails()) {
            return response()->json(['success' => false, 'error' => $validator->messages()]);
        }

        $receiver_card = Card::where('card_no', $request->receiver_card)->first();
        $sender_card = Card::where('card_no', $request->sender_card)->first();

        if(!$receiver_card){
            return response()->json(['success' => false, 'error' => "receiver_card not found!"]);
        }else if(!$sender_card){
            return response()->json(['success' => false, 'error' => "sender_card not found!"]);
        }

        // check if card viable

        if($sender_card->transactionsno == 0){
            return response()->json(['success' => false, 'error' => "card charge failed. Number of transactions are not sufficient!"]);

        }

        // chack if the balance matches

       if( $sender_card->balance < $request->amount){
        return response()->json(['success' => false, 'error' => "card charge failed due to insufficient balance!"]);
       }else{
        //    check merchant type
        if($receiver_card->merchant_allowed !== $request->merchant_type){
            return response()->json(['success' => false, 'error' => "card charge failed due to wrong receiver merchant type!"]);

        }
        //    transfer funds
        $sender_card->balance = $sender_card->balance - $request->amount;
        $sender_card->transactionsno = $sender_card->transactionsno - 1;


        $sender_card->save();
        $receiver_card->balance = $receiver_card->balance + $request->amount;
        $receiver_card->save();
        $data = [
            "referenceNo"=> mt_rand(1000000,9999999),
            "amountPaid"=> $request->amount,
            "sender_card"=>$sender_card->card_no,
            "receiver_card"=>$receiver_card->card_no,
            "bank_issuerer"=>$sender_card->banktype,
            'email'=>$request->email,
        ];
        cardTransaction::create(["referenceNo"=>$data['referenceNo'],
        "amountPaid"=>$request->amount,'sender_card'=>$sender_card->card_no, 'receiver_card_name'=>$receiver_card->name,  'sender_card_name'=>$sender_card->name,'receiver_card'=>$receiver_card->card_no, 'bank_issuerer'=>$sender_card->banktype]);
        // send email confirmation


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
                            'Name' => $request->email
                        ]
                    ],
                    'Subject' => "KADIFY VIRTUAL CARD TRANSACTION RECEIPT",
                    'TextPart' => "Greetings from Kadify!",
                    'HTMLPart' => "<h3>Greetings from Kadify,<br> welcome to our Finance world<h3> We wanted to let you know of a transaction that took place on your card number <h1>".$sender_card->card_no."</h1> This is a virtual card tied to ".$sender_card->banktype." Bank</h3> The transaction is as follows: KSHS ".$request->amount.".00 was sent to a card with the number <h1>".$receiver_card->card_no." </h1>tied to an account holder with the name ".$receiver_card->name." if you are not aware of this transaction kindly delete the card from the Kadify wallet . Regards, <br>The Kadify Team</h3> "
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success();

        // end of email
        return response()->json(['success' => true, 'response' => "card charge success!" ,'data'=>$data]);


       }

        
    }

    public function get_card_transactions_outgoing(Request $request){
        $rules = ['card_no' => 'required|integer' ];
        $input     = $request->only('card_no');
       $validator = Validator::make($input, $rules);
    
        if ($validator->fails()) {
            return response()->json(['success' => false, 'error' => $validator->messages()]);
        }
        $outgoing_transactions = cardTransaction::where('sender_card', $request->card_no)->get();
        $incoming_transactions = cardTransaction::where('receiver_card', $request->card_no)->get();
   
        return response()->json(['success' => true, 'response' => "card transactions fetched successfuly!" ,'outgoing_transactions'=>$outgoing_transactions, "incoming_transactions"=>$incoming_transactions]);

    }

    public function delete_card(Request $request){
        $rules = ['card_no' => 'required|integer' ];
        $input     = $request->only('card_no');
       $validator = Validator::make($input, $rules);
    
        if ($validator->fails()) {
            return response()->json(['success' => false, 'error' => $validator->messages()]);
        }

        $card = Card::where('card_no', $request->card_no)->first();
        $card->delete();
        return response()->json(['success' => true, 'data' => "card deleted successfully"]);

    }

     public function update_balance(Request $request)
    {
        # code...
        $rules = ['phone' => 'required|integer' , 'amount'=>'required|integer'];
        $input     = $request->only('phone', 'amount');
       $validator = Validator::make($input, $rules);
    
        if ($validator->fails()) {
            return response()->json(['success' => false, 'error' => $validator->messages()]);
        }

        $user = User::where('phone', $request->phone)->first();
        $user->balance = $user->balance + $request->amount;
        $user->save();

        return response()->json(['success' => true, 'data' => "balance updated successfully"]);
        
    }


    public function update_bank_balance (Request $request){
        $rules = ['email' => 'required' , 'amount'=>'required|integer'];
        $input     = $request->only('email', 'amount');
       $validator = Validator::make($input, $rules);
    
        if ($validator->fails()) {
            return response()->json(['success' => false, 'error' => $validator->messages()]);
        }
        $user = User::where('email', $request->email)->first();
        $user->balance = $user->balance + $request->amount;
        $user->save();

        return response()->json(['success' => true, 'data' => "balance updated successfully"]);
        


    }
}
