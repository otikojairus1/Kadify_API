<?php

namespace App\Http\Controllers;

use \Mailjet\Resources;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use App\Models\EmailOtpVerifier;

class UserController extends Controller
{
  //  public $BASE_URI = "http://localhost:8000/";
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function send_email_otp(Request $request)
    {
        //start of route protection
        $rules = [
          
            'email' => 'unique:users|required',
            'name' => 'required',
         
        ];

        $input     = $request->only('email', 'name');
        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'error' => $validator->messages()]);
        }


        // end of route protection
        $otpCode = mt_rand(1000,9999);

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
                        'Subject' => "KADIFY VERIFICATION OTP",
                        'TextPart' => "Greetings from Kadify!",
                        'HTMLPart' => "<h3>Greetings from Kadify,<br> welcome to our Finance world Use<h3> <h1>".$otpCode."</h1> <h3>as your verification code for your Kadify account</h3>"
                    ]
                ]
            ];
            $response = $mj->post(Resources::$Email, ['body' => $body]);
            $response->success();
            // var_dump($response->getData());
            // have the code backup on the server
           EmailOtpVerifier::create([
                'email'=> $request->email,
                'code'=>$otpCode
            ]);

        return response()->json(["response"=>"success", "otpsent"=>$otpCode, "data"=>$response->getData()]);
    }
}
