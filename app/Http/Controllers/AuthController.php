<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Client;
use Illuminate\Support\Facades\Hash;
use abdullahobaid\mobilywslaraval\Mobily;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth:api', 'phoneverified'])->except(['login' , 'register' , 'verify_account' , 'resend_verification_message']);
    }


    public function register(Request $request)
    {
        //there is a reason i did not make phone unique in validation

        $validator = Validator::make($request->all() , [
            'phone' => 'required|numeric',
            'name' => 'required|string|min:3|max:190',
            'password' => 'required|string|min:6|max:190|confirmed'
        ]);

        if($validator->fails())
        {
            return response()->json(['errors' => $validator->errors()] , 400);
        }

        $ClientNotVerified = Client::where('phone' , $request->input('phone'))
        ->where('verified_at' , null)->first();

        if($ClientNotVerified)
        {
            if(Carbon::parse($ClientNotVerified->verify_expires)->timestamp < time())
            {
                $ClientNotVerified->delete();
            } else {
                return response()->json(['error' => 'email already taken'] , 400);
            }
        }

        if(Client::where('phone' , $request->input('phone'))
        ->where('verified_at' , '!=' , null)->exists())
        {
            return response()->json(['error' , 'email already taken'] , 400);
        }

        $client = new Client;
        $client->phone = $request->input('phone');
        $client->name = $request->input('name');
        $client->password = Hash::make($request->input('password'));
        $client->verify_code = str_random(6);
        $client->verify_expires = date( 'Y-m-d H:i:s' ,strtotime('1 hour'));
        $client->save();
        $msg = Mobily::send($client->phone , 'Your Verification code is '.$client->verify_code);
        if($msg)
        {
            return response()->json(['success' => 'check your messages, a verification message has been sent to your phone number '.$client->phone] , 200);
        }else {
            return response()->json(['error' => 'error sending verification message'], 400);
        }
    }


    public function verify_account(Request $request)
    {
        $validator = Validator::make($request->all() , [
            'verify_code' => 'required|string',
            'phone' => 'required|numeric'
        ]);

        if($validator->fails())
        {
            return response()->json(['errors' => $validator->errors()] , 400);
        }

        if(Client::where('phone' , $request->input('phone'))->where('verify_code' , $request->input('verify_code'))->exists())
        {
            $client = Client::where('phone' , $request->input('phone'))->firstOrFail();
            if(Carbon::parse($client->verify_expires)->timestamp > time())
            {
                $client->verified_at = date('Y-m-d H:i:s');
                $client->save();
                return response()->json(['success' => 'account verified successfully'],200);
            } else {
                return response()->json(['error' => 'verification code expired'] , 400);
            }
        }
        return response()->json(['error' => 'verification code or phone are incorrect'],400);
    }


    public function resend_verification_message(Request $request)
    {
        $validator = Validator::make($request->all() , [
            'phone' => 'required|numeric'
        ]);
        if($validator->fails())
        {
            return response()->json(['error' => 'pls provide a phone number to send verification message to'],400);
        }
        if(!Client::where('phone' , $request->input('phone'))->exists())
        {
            return response()->json(['error' => 'your phone is invalid'],400);
        }
        $client = Client::where('phone' , $request->input('phone'))->firstOrFail();
        $client->verify_code = str_random(6);
        $client->verify_expires = date('Y-m-d H:i:s' , strtotime('1 hour'));
        $client->save();
        $msg = Mobily::send($client->phone , 'Your Verification code is '.$client->verify_code);
        if($msg)
        {
            return response()->json(['success' => 'check your messages, a verification message has been sent to your phone number '.$client->phone] , 200);
        }else{
            return response()->json(['error' => 'error sending message'] , 400);
        }
    }



    public function login()
    {
        $credentials = request(['phone', 'password']);

        if (! $token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }


    public function profile()
    {
        return response()->json(auth('api')->user());
    }


    public function logout()
    {
        auth('api')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }


    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
    }


    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60*24*7
        ]);
    }
}
