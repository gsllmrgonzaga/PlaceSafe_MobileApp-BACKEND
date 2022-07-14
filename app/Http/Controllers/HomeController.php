<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;

use Notification;

use App\Notifications\SendEmailNotification;

class HomeController extends Controller
{
    public function sendnotification()
    {
        $user=User::all();

        $details=[
            'greeting'=>'Hi laravel Developer',
            'body'=>'This is the email body',
            'actiontext'=>'Subscribe this channel',
            'actionurl'=>'/',
            'lastline'=>'this is the last line',
        ];

        Notification::send($user, new SendEmailNotification($details));

        dd('done');

    }
}
