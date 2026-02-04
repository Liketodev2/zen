<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\ContactFormMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function send(Request $request)
    {
        $data = $request->validate([
            'f_name' => 'required|string|max:255',
            'l_name' => 'nullable|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        try {
            Mail::to(env('MAIL_FROM_ADDRESS'))->send(new ContactFormMail($data));
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Unable to send message.');
        }

        return back()->with('success', 'Your message was sent successfully.');
    }
}
