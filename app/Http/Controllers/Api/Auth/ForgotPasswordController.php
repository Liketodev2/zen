<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\PasswordReset;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ForgotPasswordController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendResetCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(), 'validation' => true], 422);
        }

        $user = User::query()->where('email', '=', $request->email)->first();
        if ($user->inactivated) {
            return response()->json(['error' => 'Your account is deleted!', 'validation' => false], 403);
        }

        try {
            $token = rand(1000, 9999);

            PasswordReset::updateOrCreate(
                ['email' => $request->email],
                [
                    'token' => $token,
                    'created_at' => Carbon::now(),
                ]
            );

            Mail::raw("Your password reset code is: $token", function ($message) use ($request) {
                $message->to($request->email)
                    ->subject('Password Reset Code');
            });

            return response()->json(['success' => 'Reset code sent successfully.'], 200);

        }catch (\Exception $exception){
            return response()->json(['error' => $exception->getMessage(), 'validation' => false], 422);
        }

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyResetCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'token' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(), 'validation' => true], 422);
        }

        $passwordReset = PasswordReset::where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$passwordReset) {
            return response()->json(['error' => 'Invalid code!', 'validation' => false], 404);
        }

        $tokenExpiration = Carbon::parse($passwordReset->created_at)->addMinutes(5);

        if (Carbon::now()->greaterThan($tokenExpiration)) {
            return response()->json(['error' => 'Code has expired!', 'validation' => false], 410);
        }
        $token = rand(1000, 9999);
        $passwordReset->update([
            'created_at' => Carbon::parse($passwordReset->created_at)->addMinutes(5),
            'token' => $token
        ]);

        return response()->json(['success' => 'Code verified successfully.', 'data' => ['token' => $token]], 200);
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resetPassword(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'token' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(), 'validation' => true], 422);
        }

        $user = User::query()->where('email', '=', $request->email)->first();
        if ($user->inactivated) {
            return response()->json(['error' => 'Your account is deleted!', 'validation' => false], 403);
        }

        $passwordReset = PasswordReset::where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$passwordReset) {
            return response()->json(['error' => 'Invalid reset code!', 'validation' => false], 404);
        }

        $tokenExpiration = Carbon::parse($passwordReset->created_at)->addMinutes(5);
        if (Carbon::now()->greaterThan($tokenExpiration)) {
            return response()->json(['error' => 'Reset code has expired!', 'validation' => false], 410);
        }

        $user = User::query()->where('email', $request->email)->first();
        if (!$user) {
            return response()->json(['error' => 'User not found!', 'validation' => false], 404);
        }
        $user->password = Hash::make($request->password);
        $user->save();
        $passwordReset->delete();
        return response()->json(['success' => 'Password reset successfully.']);
    }
}
