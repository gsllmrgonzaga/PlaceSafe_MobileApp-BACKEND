<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;
use App\Mail\UserVerification;
use Illuminate\Support\Facades\Mail;
use App\Models\verifys;
use Illuminate\Support\Facades\DB;
use App\Mail\ForgotPasswordEmail;
use App\Models\PatientManagement;
use App\Models\LocationsManagement;
use Carbon\Carbon;
use Stevebauman\Location\Facades\Location;

class UserController extends Controller
{
    public function loginUser(Request $request)
    {   
        $validation = Validator::make($request->all(),[
            'email'=>'required',
            'password'=>'required',
        ]);

        if($validation->fails())
        {
            return response()->json($validation->errors());
        }
        else
        {
            $user=User::where('email','=', $request->input('email'))->first();
            if($user) 
            {
                $okayPassword = Hash::check($request->input('password'), $user->password);

                if($okayPassword)
                {
                    $resArr['token'] = $user->createToken('api-application')->accessToken;
                    $resArr['firstname'] = $user->firstname;
                    $resArr['lastname'] = $user->lastname;
                    return response()->json(['status'=> true, 'loggedData' => $resArr]);
                }
                else
                {
                    return response()->json(['status' => false, 'errorMessage'=>'Password does not matched.']);
                }
            } 
            else 
            {
                return response()->json(['status' => false,'errorMessage'=>'Email not found.']);
            }
        }        
    }

    public function register(Request $request)
    {
        $validation = Validator::make($request->all(),[
            'firstname'=>'required',
            'lastname'=>'required',
            'email'=>'required|email|unique:users',
            'password'=>'required|min:8|regex:/[A-Z]/|regex:/[0-9]/|regex:/[@$!%*#?&]/',
            'c_password'=>'required|same:password',
         ]);

        if($validation->fails())
        {
            return response()->json($validation->errors());
        }
        
        $allData = $request->all();
        $token = random_int(100000, 999999);

        $a = DB::table('users')->insert([
          'firstname' => $request->all()['firstname'],
          'lastname' => $request->all()['lastname'],
          'email' => $request->all()['email'],
          'password' => bcrypt($allData['password']),
          'created_at' => Carbon::now(),
          'token' =>  $token]);
      
        return response()->json(['message'=>'Successful', $a]); 
    }

    public function changepassword(Request $request) 
    {
        $validation = Validator::make($request->all(),[
            'old_password' => 'required',
            'password' => 'required|min:8|regex:/[A-Z]/|regex:/[0-9]/|regex:/[@$!%*#?&]/',
            'confirm_password'=>'required|same:password'
        ]);

        if($validation->fails()) 
        {
                return response()->json(['status' => false,'error' => $validation->errors()->first()]);
        }
        $user=$request->user();
    
        if(Hash::check($request->old_password,$user->password)) 
        {
            $user->update(['password'=>Hash::make($request->password)]);
                return response()->json(['message'=>'Submitted successfully.','status' => true,]);
        }
        else 
        {
            return response()->json(['status' => false,'error'=>'Old Password does not matched.']);
        }
    }
   
    public function forgotpasswordUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
        ]);

        if ($validator->fails()) 
        {
            return new JsonResponse(['success' => false, 'message' => "Empty fields"]);
        }

        $user=User::where('email','=',$request->input('email'))->first();
        if($user)
        {
            $user3=verifys::where('email','=',$request->input('email'))->first();
            if($user3)
            {
                $token=Str::random(8);
                $a = DB::table('users')->where([['email', $request->all()['email']]])->update(['password' =>  Hash::make($token)]);
           
                if ($a) 
                {
                    Mail::to($request->all()['email'])->send(new ForgotPasswordEmail($token));
                    return new JsonResponse(['success' => true, 'message' => "A new password has been sent"]);          
                }  
            }
            else
            {
                return response()->json(['message'=>'User not verified.']); 
            }
         }
         else
         {
            return response()->json(['message'=>'User not registered.']);  
         }
    }

    public function showUser(Request $request) 
    {
        $resArr['firstname'] = $request->user()->firstname;
        $resArr['lastname'] = $request->user()->lastname;
        $resArr['email'] = $request->user()->email;
        return response(['status'=> true, 'loggedData' => $resArr]);
    }

    public function updateprofileUser(Request $request)
    {
        $validation = Validator::make($request->all(),[
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required',
        ]);

        if($validation->fails()) 
        {
            return response()->json(['status' => false,'message'=>'Not unique email or there are empty fields']);
        }
      
        $user=$request->user();
        $user->update($request->all());

        return response()->json(['message'=>'Succesfully updated.','status' => true]);
    }

}