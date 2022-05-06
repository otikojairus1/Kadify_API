<?php

namespace App\Http\Controllers;

use \Mailjet\Resources;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use App\Models\EmailOtpVerifier;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;


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
    public function register(Request $request)
    {
        $rules = [
            'fullName' => 'required',
            'email' => 'unique:users|required',
            'password'    => 'required',
        ];
    
        $input     = $request->only('fullName', 'email','password');
        $validator = Validator::make($input, $rules);
    
        if ($validator->fails()) {
            return response()->json(['success' => false, 'error' => $validator->messages()]);
        }
    
        $user     = User::create(['fullName' => $request->fullName, 'email' => $request->email, 'password' => Hash::make($request->password)]);
        //$token = $request->name->createToken('accessToken');
        return response()->json(['responseCode' => 200, 'message' => 'user has registered successfully.']);
     
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function login(Request $request){ 
        date_default_timezone_set('Africa/Nairobi');
        $rules = [
          
            'email' => 'required',
            'password'    => 'required',
        ];
    
        $input     = $request->only('email','password');
        $validator = Validator::make($input, $rules);
    
        if ($validator->fails()) {
            return response()->json(['responseCode' => 401, 'error' => $validator->messages()]);
        }
        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){ 
            $user = Auth::user(); 
            return response()->json(['responseCode'=>200,'userDetails'=>$user, 'loginTime'=>Carbon::now()->toDateTimeString()]); 
        } 
        else{ 
            return response()->json(['responseCode'=>401,'error'=>'wrong login credentials']); 
        } 
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
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
    public function updatephone(Request $request)
    {
              //start of route protection
              $rules = [
          
                'email' => 'required',
                'phone' => 'required',
              
             
            ];
    
            $input     = $request->only('email', 'phone');
            $validator = Validator::make($input, $rules);
    
            if ($validator->fails()) {
                return response()->json(['success' => false, 'error' => $validator->messages()]);
            }

            // perform phone update
            $user = User::where('email', $request->email)->first();
            $user->phone = $request->phone;
            $user->save();
        return response()->json(["response"=>"success"]);
    }

    public function send_email_otp(Request $request)
    {
        //start of route protection
        $rules = [
          
            'email' => 'required|unique:users',
            'password' => 'required',
            'fullName' => 'required'
         
        ];

        $input     = $request->only('email', 'fullName', 'password');
        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'error' => $validator->messages()]);
        }

        User::create(['fullName' => $request->fullName, 'email' => $request->email, 'password' => Hash::make($request->password)]);
        //$token = $request->name->createToken('accessToken');
       // return response()->json(['responseCode' => 200, 'message' => 'user has registered successfully.']);

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
                        'HTMLPart' => "<h3>Greetings from Kadify,<br> welcome to our Finance world<h3> <h1>".$otpCode."</h1> <h3>is your verification code for your Kadify account</h3>"
                    ]
                ]
            ];
            $response = $mj->post(Resources::$Email, ['body' => $body]);
            $response->success();
            // var_dump($response->getData());
            // have the code backup on the server
        //    EmailOtpVerifier::create([
        //         'email'=> $request->email,
        //         'code'=>$otpCode
        //     ]);

            $userToUpdate = EmailOtpVerifier::where('email', $request->email)->first();

            if($userToUpdate){
                $userToUpdate->code = $otpCode;
                $userToUpdate->save();
            }else{
                EmailOtpVerifier::create([
                'email'=> $request->email,
                'code'=>$otpCode
            ]);
            }

          

        return response()->json(["response"=>"success", "otpsent"=>$otpCode, "data"=>$response->getData()]);
    }

    public function verify_email_otp(Request $request)
    {
        date_default_timezone_set('Africa/Nairobi');
              //start of route protection
              $rules = [
          
                'email' => 'required',
                'otp' => 'required',
             
            ];
    
            $input     = $request->only('email', 'otp');
            $validator = Validator::make($input, $rules);
    
            if ($validator->fails()) {
                return response()->json(['success' => false, 'error' => $validator->messages()]);
            }

            // end of route protection

            $checkOTP = EmailOtpVerifier::where(['email'=> $request->email, 'code'=>$request->otp])->first();
            if($checkOTP){
                // $updateUserVerification = User::where('email', $request->email)->first();
                // $updateUserVerification->email_verified_at = Carbon::now()->toDateTimeString();
                // $updateUserVerification->save();
                  
                return response()->json(["responseCode"=>200, "VerificationStatus"=> true]);
            }else{
                return response()->json(["responseCode"=>401, "VerificationStatus"=> false]);
            }
    
        
    }
}
