<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\ContactFormMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function send(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'f_name'  => 'required|string|max:255',
            'l_name'  => 'nullable|string|max:255',
            'email'   => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors'     => $validator->errors(),
                'validation' => true
            ], 422);
        }

        $data = $validator->validated();

        try {
            Mail::to(config('mail.from.address'))
                ->send(new ContactFormMail($data));

            return response()->json([
                'message' => 'Your message was sent successfully.',
            ], 200);

        } catch (\Exception $e) {

            return response()->json([
                'error'      => 'Unable to send message.',
                'validation' => false
            ], 500);
        }
    }

}
