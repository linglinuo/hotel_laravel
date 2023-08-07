<?php

namespace App\Http\Controllers;

use App\Mail\DemoMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function index()
    {
        $mailData = [
            'title' => 'Mail from frp.4hotel.tw:25580',
            'body' => 'This is for testing email using smtp.'
        ];
         
        Mail::to('fiona051@gmail.com')->send(new DemoMail($mailData));
           
        dd("Email is sent successfully.");
    }
}
