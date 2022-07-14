<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\Subscribe;
use App\Models\notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Arr;
use App\Models\verifys;
use Carbon\Carbon;

class SubscriberController extends Controller
{
    public function subscribe(Request $request) 
    {
        $user1=verifys::where('email','=', $request->user()->email)
                ->where('email_verified_at','!=','null')->first();
        if($user1)
        {
            $user2=notification::where('email','=', $request->user()->email)->first();
            if($user2)
            {
                return response()->json(['message'=>'Already notified.']);
            }
            else
            {
                
                $email = $request->user()->email;
                $subscriber = notification::create(['email' => $email]); 
                return response()->json(['message'=>'Succesfully added.']);
            }   
        }
        else
        {
            return response()->json(['message'=>'User not verified.']);
        }
    }
}