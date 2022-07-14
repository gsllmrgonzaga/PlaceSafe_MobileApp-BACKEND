<?php

namespace App\Http\Controllers;

use App\Mail\VerifyEmail;
use App\Models\verifys;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    public function resendPin(Request $request)
    {
        $verify =  DB::table('verifys')->where([['email', $request->user()->email]]);  
        
        if ($verify->exists()) 
        {
            $verify->delete();
        }

        $token = random_int(100000, 999999);

        $a = DB::table('verifys')->insert([
            'email' => $request->user()->email,
            'token' =>  $token,
            'created_at' => Carbon::now()]);

        if ($a) 
        {
            Mail::to($request->user()->email)->send(new VerifyEmail($token));
            return response(['status'=> true, 'loggedData' =>"A verification mail has been sent"]);
        }
    }

    public function verifyEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => ['required'],]);

        if ($validator->fails()) 
        {
            return redirect()->back()->with(['message' => $validator->errors()]);
        }
   
        $user = DB::table('verifys')
            ->where('email', $request->user()->email)
            ->where('token', $request->token);
        
        if($user->get()->isEmpty()) 
        {
            return response(['status'=> false, 'loggedData' =>"Invalid PIN"]);
        }
        else
        {
            $verify =  DB::table('verifys')->where([
                ['email', $request->user()->email]]);
    
            if ($verify->exists()) 
            {
                $verify->delete();
            }
            $data = array("email" => $request->user()->email,"token" => $request->token, "email_verified_at"=> Carbon::now());
            DB::table('verifys')->insert($data);

            return response(['status'=> true, 'loggedData' =>"Email is verified"]);
        }
    }   
    
}